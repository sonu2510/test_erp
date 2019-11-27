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
	//printr($menuId);die;
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
	$class = '';	
	$filter_data=array(
		'invoice_no' => $filter_invoice_no,
		'country_id' => $country_id, 
		'status' => $filter_status,		
		'customer_name' => $filter_customer_name,		
		'email' => $filter_email,		
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
		
	$filter_data=array(
		'invoice_no' => $filter_invoice_no,
		'country_id' => $country_id,
		'status' => $filter_status,
		'customer_name' => $filter_customer_name,
		'email' => $filter_email,

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
			page_redirect($obj_general->link($rout, 'mod=index&inv_status='.$_GET['inv_status'], '',1));
		}
	}else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
		if(!$obj_general->hasPermission('delete',$menuId)){
			$display_status = false;
		} else {
			//printr($_POST['post']);die;
			$obj_invoice->updateInvoiceStatus(2,$_POST['post']);
			$obj_session->data['success'] = UPDATE;
			page_redirect($obj_general->link($rout, 'mod=index&inv_status='.$_GET['inv_status'], '',1));
		}
	}
	elseif(isset($_POST['btn_send_trackid']))
	{
			//printr($_POST);die;
			$obj_invoice->sendemailtrackid($_POST);
			
	}
	elseif(isset($_POST['btn_remark']))
	{
			//printr($_POST);die; sonu add 23-2-2017
			$obj_invoice->addremark($_POST);
			
	}		
	$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
	$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
	$addedByInfo = $obj_invoice->getUser($user_id,$user_type_id);
	//printr($addedByInfo);
	
	if($user_id=='1' && $user_type_id=='1')
	$addedByInfo['user_id']='';
	
	if(isset($_POST['btn_generate'])){
		
		$obj_invoice ->saveImportCharges($_POST);
		//page_redirect($obj_general->link('', '&mod=index&client_id='.encode($stock['client_id']).'&stock_order_id='.encode($stock['stock_order_id']), '',1));
		//die;
		page_redirect($obj_general->link($rout, 'mod=index&inv_status='.$_GET['inv_status'], '',1));
	}
	if(isset($_POST['btn_convert'])){
		
		$obj_invoice->convertInPurchase($_POST);
		page_redirect($obj_general->link($rout, 'mod=index&inv_status=2', '',1));
	}
	    
	
	
	//printr($obj_invoice->saveTotalInvoiceAmount('159'));
	//phpinfo();
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
   							<?php /*?><a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>"><i class="fa fa-plus"></i> New Invoice </a><?php */?> &nbsp;
                    <?php }   if($addedByInfo['country_id']!='111') {?>
                       <a class="label bg-info" href="javascript:void(0);" onclick="csvlink('post[]')"> <i class="fa fa-print"></i> CSV Export</a>
                      <?php /*?> <a class="label bg-inverse" href="<?php echo $obj_general->link($rout, 'mod=import', '',1);?>" > <i class="fa fa-print"></i> CSV Import</a><?php */?>
					<?php } if($obj_general->hasPermission('edit',$menuId)){ ?>
                        <a class="label bg-success" style="margin-left:4px;" onclick="formsubmitsetaction('form_list','active','post[]','<?php echo ACTIVE_WARNING;?>')"><i class="fa fa-check"></i> Active</a>
                        <a class="label bg-warning" onclick="formsubmitsetaction('form_list','inactive','post[]','<?php echo INACTIVE_WARNING;?>')"><i class="fa fa-times"></i> Inactive</a>
                     <?php }
					if($obj_general->hasPermission('delete',$menuId)){ ?>       
                        <a class="label bg-danger" onclick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>
                    <?php } ?>                
            		</span>
          		</header>          
          		<div class="panel-body">
            	    <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link($rout, 'mod=index&inv_status='.$_GET['inv_status'], '',1); ?>">
                		<section class="panel pos-rlt clearfix">
                  			<header class="panel-heading">
                    			<ul class="nav nav-pills pull-right">
                      				<li> <a href="#" class="panel-toggle text-muted active"><i class="fa fa-caret-down fa-lg text-active"></i><i class="fa fa-caret-up fa-lg text"></i></a> 
                                    </li>
                    			</ul>
                                <!--sonu change in search 14/12/2016-->
			                     <a href="#" class="panel-toggle text-muted active"><i class="fa fa-search"></i> Search </a>	
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
							</div>        
			                <footer class="panel-footer <?php echo $class; ?>">
			                   <div class="row">
            			          <div class="col-lg-12">
                        			<button type="submit" class="btn btn-primary btn-sm pull-right ml5" name="btn_filter"><i class="fa fa-search"></i> Search</button>
			                         <a href="<?php echo $obj_general->link($rout, 'mod=index&inv_status='.$_GET['inv_status'], '',1); ?>" class="btn btn-info btn-sm pull-right" ><i class="fa fa-refresh"></i> Refresh</a>
            			          </div> 
                   				</div>
			                </footer>                                  
            		  </section>
          			</form>    			  
		            <div class="row">
        		        <div class="col-lg-3 pull-right">	
                		    <select class="form-control" id="limit-dropdown" onchange="location=this.value;">
			                     <option value="<?php echo $obj_general->link($rout, 'mod=index&inv_status='.$_GET['inv_status'], '',1);?>" selected="selected">--Select--</option>
            		       	<?php $limit_array = getLimit(); 
									foreach($limit_array as $display_limit) {
										if($limit == $display_limit) {	 ?>
                        	           		<option value="<?php echo $obj_general->link($rout, 'mod=index&inv_status='.$_GET['inv_status'].'&limit='.$display_limit, '',1);?>" selected="selected"><?php echo $display_limit; ?></option>				
									<?php } else { ?>
                            				<option value="<?php echo $obj_general->link($rout, 'mod=index&inv_status='.$_GET['inv_status'].'&limit='.$display_limit, '',1);?>"><?php echo $display_limit; ?></option>
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
                              <th>Order Type</th>
                			 <?php   if($_GET['inv_status']=='2' || $_GET['inv_status']=='3' ){?> 
                               <th></th>
                              <th></th>
                               <th></th>
                              <?php } ?>               
                            <?php   if($_GET['inv_status']=='0'){?> <th></th> <th>Track Id</th>
							 <th>Remark</th>
							
							 <?php }?>
                      		  <th>Status</th>
                      		  <th>Posted By</th>
                              
		                      <?php if($obj_general->hasPermission('edit',$menuId) && $_GET['inv_status']=='1'){ ?><th>Action</th><?php }?>
		                      <th></th>
        		            </tr>
                		  </thead>
	                 	  <tbody>
                  			<?php 
							//printr($obj_session->data['LOGIN_USER_TYPE']);
							//printr($obj_session->data['ADMIN_LOGIN_SWISS']);
							
		$total = $obj_invoice->getTotalInvoice($obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS'],$filter_data,$_GET['inv_status'],$addedByInfo['user_id']);
							//printr($total);
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
                     		  $invoices = $obj_invoice->getInvoice($obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS'],$option,$filter_data,$_GET['inv_status'],$addedByInfo['user_id']);
					 		if($total && $invoices)
							{
							
							
                      			             foreach($invoices as $invoice){ 
								//printr($invoice);
								$class_css='';
								if($invoice['done_status']=='1' && $_GET['inv_status']!='2' && $_GET['inv_status']!='3')
								  $class_css='class=invalid';
								
								?>
                       				<tr <?php echo ($invoice['status']==0) ? 'style="background-color:#FADADF" ' : '' ; //echo $class_css; ?> >                        
                          				<td><input type="checkbox" name="post[]" value="<?php echo $invoice['invoice_id'];?>"></td>
                          				<td> <a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_no='.encode($invoice['invoice_id']).'&status=1&inv_status='.$_GET['inv_status'],'',1); ?>" > 
                                            <?php echo $invoice['invoice_no'].'&nbsp;/&nbsp;'.(date('y')).'-'.(date('y')+1).'&nbsp;/&nbsp;'; echo date("d-m-Y",strtotime($invoice['invoice_date']));?></a></td>
                                       
                                      <td><a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_no='.encode($invoice['invoice_id']).'&status=1&inv_status='.$_GET['inv_status'],'',1); ?>" > <?php  if(($invoice['done_status']=='1' || $invoice['done_status']=='2'|| $invoice['done_status']=='3') && $_GET['inv_status']=='0') echo $invoice['customer_name'];
									else if(($invoice['done_status']=='2'&& $_GET['inv_status']=='2') ||($invoice['done_status']=='1')||($invoice['done_status']=='3' && $_GET['inv_status']=='3'))echo "Swiss Pac Pvt Ltd."; ?></a></td>
                                      <td><a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_no='.encode($invoice['invoice_id']).'&status=1&inv_status='.$_GET['inv_status'],'',1); ?>" > <?php echo $invoice['email']; ?></a></td>
                                      
                                      <?php if(is_numeric($invoice['final_destination']))
                                          $invoice_sea = $obj_invoice->getCountryName($invoice['final_destination']);
										  	else
												$invoice_sea['country_name']=$invoice['final_destination'];
                                          ?>
                                          
                                      <td><a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_no='.encode($invoice['invoice_id']).'&status=1&inv_status='.$_GET['inv_status'],'',1); ?>" ><?php echo $invoice_sea['country_name']; ?></a></td>
                                      <td><a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_no='.encode($invoice['invoice_id']).'&status=1&inv_status='.$_GET['inv_status'],'',1); ?>" ><?php echo ucwords($invoice['order_type']);?></a></td>
                                      <td>
                                      <?php 
	  //sonu change if condition 14/12/2016 
									   
									  if(($_GET['inv_status']=='0' || $_GET['inv_status']=='2' ) && $_GET['inv_status']!='3')
									  { 
									      if($invoice['done_status']=='1' && $_GET['inv_status']!='2' ){?>
                                      	
                                        <?php /*?><img src="<?php echo HTTP_SERVER.'admin/controller/invoice/pdf_img.png';?>" alt="Image" class="img-circle" style="max-width:5%;"><?php */?>
                                       <?php /*?> <a class="label bg-info" href="javascript:void(0);" onclick="pdfexport('<?php echo encode($invoice['invoice_id']);?>');"><i class="fa fa-download"></i> PDF Export</a><?php */?>
                                        <a class="label bg-info"  onclick="upload('<?php echo $invoice['invoice_id'];?>','<?php echo $invoice['invoice_no'];?>','<?php echo $invoice['buyers_orderno'];?>','<?php echo $invoice['order_user_id'];?>','<?php echo $invoice['date_added'];?>');" id="upload_a">Upload</a>
                                        	<?php /*?><input type="file" name="file" title="Upload CSV" class="btn btn-info btn-xs" onchange="upload_pdf(<?php echo $invoice['invoice_id'];?>)" id="pdf_upload_<?php echo $invoice['invoice_id'];?>"><?php */?>
                                        
                                      <?php }
									  else if($invoice['done_status']=='2'){ ?>
									  			
                                                <a href="<?php echo HTTP_UPLOAD."admin/return_doc/".$invoice['invoice_no'].'/invoice-'.$invoice['invoice_no'].'.pdf';?>" target="_blank" ><i class="fa fa-download">Export PDF</i></a>
									 <?php  }
									 // else{ ?>
										<?php /*?> <a href="#" onclick="all_done('<?php echo encode($invoice['invoice_id']);?>','<?php echo $invoice['invoice_no'];?>')" name="btn_edit" class="btn btn-inverse btn-xs">Done</a> <?php */?>
										  
									 <?php //}
									  }
									  elseif($_GET['inv_status']=='1')
									  {?>
                                      
                                      
                                     <?php if($invoice['done_status']=='1'){?>
                                      	
                                        <?php /*?><img src="<?php echo HTTP_SERVER.'admin/controller/invoice/pdf_img.png';?>" alt="Image" class="img-circle" style="max-width:5%;"><?php */?>
                                        <a class="label bg-info" href="javascript:void(0);" onclick="pdfexport('<?php echo encode($invoice['invoice_id']);?>');"><i class="fa fa-download"></i> PDF Export</a>
                                      <?php }
									  else{ ?>
										 <a href="#" onclick="all_done('<?php echo encode($invoice['invoice_id']);?>','<?php echo $invoice['invoice_no'];?>')" name="btn_edit" class="btn btn-inverse btn-xs">Done</a> 
										  
									 <?php }
									  } ?>
                                      
                                      </td>
                                      <!--sonu add 10/12/2016-->
                                    <td>
                                  <?php if($_GET['inv_status']=='0' && $invoice['track_id']=='') { ?>
							           
                                         	 <a class="label bg-warning"  onclick="track('<?php echo $invoice['invoice_no'];?>','<?php echo $invoice['invoice_id'];?>','<?php echo $invoice['email'];?>');" id="track_a">Track</a>
                                       <?php }?>  
                                       </td>   
                                       <td>
                                        <?php if($_GET['inv_status']=='0' && $invoice['add_remark_status']=='0'  && $invoice['remarks']==''){?>
            							   <a class="label label-default"  onclick="remark('<?php echo $invoice['invoice_no'];?>','<?php echo $invoice['invoice_id'];?>');" id="remark">Remark</a>
                                       <?php } ?></td> 
                                      <td><div data-toggle="buttons" class="btn-group">
                                            <label class="btn btn-xs btn-success <?php echo ($invoice['status']==1) ? 'active' : '';?> "> <input type="radio" 
                                             name="status" value="1" id="<?php echo $invoice['invoice_no']; ?>"> <i class="fa fa-check text-active"></i>Active</label>                                   
                                            <label class="btn btn-xs btn-danger <?php echo ($invoice['status']==0) ? 'active' : '';?> "> <input type="radio" 
                                                name="status" value="0" id="<?php echo $invoice['invoice_no']; ?>"> <i class="fa fa-check text-active"></i>Inactive</label> 
                                        </div></td>
                          			  <td><?php 
									  			if($_GET['inv_status']=='3')
												{
													$addedByData = $obj_invoice->getUser($invoice['purchase_user_id'],$invoice['purchase_user_type_id']);								
													$addedByImage = $obj_general->getUserProfileImage($invoice['purchase_user_type_id'],$invoice['purchase_user_id'],'100_');	
												}
												else
												{
													$addedByData = $obj_invoice->getUser($invoice['user_id'],$invoice['user_type_id']);								
													$addedByImage = $obj_general->getUserProfileImage($invoice['user_type_id'],$invoice['user_id'],'100_');
												}
									  			//$addedByData = $obj_invoice->getUser($invoice['user_id'],$invoice['user_type_id']);								
												//$addedByImage = $obj_general->getUserProfileImage($invoice['user_type_id'],$invoice['user_id'],'100_');
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
                                      <?php //if($invoice['done_status']!='1'){?>
                                          <?php if($obj_general->hasPermission('edit',$menuId) && $invoice['done_status']=='0'){ ?> 	
                                          	<td>
                                                <a href="<?php echo $obj_general->link($rout, 'mod=add&invoice_no='.encode($invoice['invoice_id']).'&inv_status='.$_GET['inv_status'],'',1); ?>"  name="btn_edit" class="btn btn-info btn-xs">Add Details</a>
                                              </td>
                                                <?php } ?>
                                          
                                      <?php //} ?>
                       				  <td><a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_no='.encode($invoice['invoice_id']).'=&status=1&inv_status='.$_GET['inv_status'],'',1); ?>"  name="btn_edit" class="btn btn-info btn-xs">View Invoice</a></td>
                                      
                                       <?php
										 if( $_GET['inv_status']=='2' && $_GET['inv_status']!='0' && $_GET['inv_status']!='3')
											 { 
										
												if(decode($invoice['transportation'])=='sea' && $invoice['import_status']=='0')
												 {
													
												 ?>
											   <td><a onclick="gen_credit_note('<?php echo $invoice['invoice_id'];?>','<?php echo $invoice['invoice_no'];?>','<?php echo $invoice['invoice_total_amount'];?>','<?php echo $invoice['tran_charges'];?>','<?php echo $invoice['final_destination'];?>','<?php echo $invoice['email'];?>')"  name="btn_edit" class="btn btn-info btn-xs">Import Charges</a></td>
										<?php } 
												 if($invoice['import_status']=='1')
												 {?>
												 <td><a onclick="convert_invoice('<?php echo $invoice['invoice_id'];?>','<?php echo $invoice['invoice_no'];?>')"  name="btn_edit_one" class="btn btn-info btn-xs">Convert To Purchase</a></td>
											  <?php }} ?>
		                        </tr>
                        		<?php }
								}
								//pagination
								$pagination = new Pagination();
								$pagination->total = $total;
								$pagination->page = $page;
								$pagination->limit = $limit;
								$pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
								$pagination->url = $obj_general->link($rout, '&page={page}&limit='.$limit.'&filter_edit=1&inv_status='.$_GET['inv_status'] ,'',1);
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
<!-- Model For Uploading Documents-->
<div class="modal fade" id="detail_div" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="dform" id="dform" style="margin-bottom:0px;">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <input type="hidden" name="invoice_id" id="model_invoice_id" value="" />
                <input type="hidden" name="invoice_no" id="model_invoice_no" value="" />
                 <input type="hidden" name="model_buyers_orderno" id="model_buyers_orderno" value="" />
                 <input type="hidden" name="model_order_uder_id" id="model_order_uder_id" value="" />
                 <input type="hidden" name="model_date_added" id="model_date_added" value="" />
                 <input type="hidden" name="admin" id="admin" value="<?php echo ADMIN_EMAIL;?>" />
                <h4 class="modal-title u_title" id="myModalLabel"></h4>
              </div>
              <div class="modal-body">
                 <div class="form-group">
                  		 <label class="col-lg-3 control-label">Upload<br/><small class="text-muted">(Only .pdf format)</small></label>
        		      <input type="file" name="file" title="Upload Pdf" class="btn btn-sm btn-info m-b-small " id="upload_pdf_file">
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" name="btn_upload" id="btn_upload" class="btn btn-default btn-sm" data-dismiss="modal">Upload</button>
              </div>
   		</form>   
    </div>
  </div>
</div>

<!-- [kinjal] end-->
<!-- [sonu] 10/12/2016-->
<div class="modal fade" id="track_div" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="tform" id="tform" style="margin-bottom:0px;">
              <div class="modal-header">
               
                <h4 class="modal-title u_title" id="myModalLabel"></h4>
              </div>
              <div class="modal-body">
                 <div class="form-group">
                   <div class="panel-body">
                   <label class="col-lg-3 control-label" id="email">Email Id</label>
                        <div class="col-lg-6">
                            <input type="email" name="emailid" value="" id="emailid" class="form-control" readonly="readonly" />
                        </div>
                        </div>
                    <div class="panel-body">
                    <label class="col-lg-3 control-label">Track Id</label>
                        <div class="col-lg-6">
                            <input type="text" name="trackid" value="" id="trackid" class="form-control" />
                        </div>
                     </div>
                      <label class="col-lg-3 control-label">Track Information</label>
                        <div class="col-lg-8">
                          <textarea id="trackinfo" name="trackinfo" value="" class="form-control" ></textarea>
                           <input type="hidden" name="admin" id="admin" value="<?php echo ADMIN_EMAIL;?>" />
                           <input type ="hidden" name="invoiceno" id="invoiceno" value="" />
                            <input type ="hidden" name="invoiceid" id="invoiceid" value="" />
                           
                        </div>
                     </div>
                    	
                </div>
              
              <div class="modal-footer">
                   		   <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" >Close</button>
                   		   <button type="submit" name ="btn_send_trackid" id="btn_send_trackid" class="btn btn-primary">Send</button>
                  
              </div>
   		</form>   
    </div>
  </div>
</div>
<div class="modal fade" id="remark_div" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="tform" id="tform" style="margin-bottom:0px;">
              <div class="modal-header">
               
                <h4 class="modal-title u_title" id="myModalLabel"></h4>
              </div>
              <div class="modal-body">
                 <div class="form-group">
                          <div class="form-group option">
					<label class="col-lg-3 control-label">Remarks</label>
                        <div class="col-lg-8">
                             <textarea class="form-control" name="remarks" id="remarks"><?php echo isset($invoice) ? $invoice['remarks'] : '' ; ?></textarea>
                        </div>
              	 
                      
                           <input type ="hidden" name="invoice_no" id="invoice_no" value="" />
                            <input type ="hidden" name="invoice_id" id="invoice_id" value="" />
                           
                        </div>
                     </div>
                    	
                </div>  
              <div class="modal-footer">
                   		   <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" >Close</button>
                   		   <button type="submit" name ="btn_remark" id="btn_remark" class="btn btn-primary">Add</button>
                  
              </div>
   		</form>   
    </div>
  </div>
</div>
<!--sonu end -->
<div class="modal fade" id="gen_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:55%;">
    <div class="modal-content">
    
    	<form class="form-horizontal" method="post" name="import_charges" id="import_charges" style="margin-bottom:0px;">
              <div class="modal-header">
                   	<h4 class="dispatch" id="myModalLabel">IMPORT CHARGES FOR INVOICE NO : <span id="span_inv_no"></span></h4>
                  	 <input type="hidden" name="gen_invoice_id" id="gen_invoice_id" value=""  />
                     <input type="hidden" name="gen_country_id" id="gen_country_id" value=""  />
                     <input type="hidden" name="gen_email_address" id="gen_email_address" value=""  />
                     <input type="hidden" name="admin_email" id="admin_email" value="<?php echo ADMIN_EMAIL;?>" />
                    <input type="hidden" name="gen_inv_number" id="gen_inv_number" value=""  /> 
              </div>
              <!--sonu 6/12/2016-->
              <div class="panel-body">
                <label class="col-lg-3 control-label">Agent Name</label>
                <div class="col-lg-3">
                    <input type="text" name="agentname" value="" id="agentname" class="form-control"/>
           	 	</div>
              </div>
               <div class="panel-body">
                    <label class="col-lg-3 control-label">Agent Adress</label>
                    <div class="col-lg-3">
                        <input type="text" name="agentaddress" value="" id="agentaddress" class="form-control" />
                    </div>
              </div>
               <div class="panel-body">
                    <label class="col-lg-3 control-label">E-Mail Id</label>
                    <div class="col-lg-3">
                        <input type="text" name="mailid" value="" id="mailid" class="form-control" />
                    </div>
               </div>
               <div class="panel-body">
                    <label class="col-lg-3 control-label">ABN No</label>
                    <div class="col-lg-3">
                        <input type="text" name="abnno" value="" id="abnno" class="form-control"/>
                    </div>
              </div>
               <div class="panel-body">
                    <label class="col-lg-3 control-label">CIF Amount</label>
                    <div class="col-lg-3">
                        <input type="text" name="cifamount" value="" id="cifamount" class="form-control" readonly="readonly"/>
                    </div>
              </div>
               <div class="panel-body">
                    <label class="col-lg-3 control-label">FOB Amount</label>
                    <div class="col-lg-3">
                        <input type="text" name="fobamount" value="" id="fobamount" class="form-control" readonly="readonly"/>
                    </div>
              </div>
               <div class="panel-body">
                    <label class="col-lg-3 control-label">Custom Duty</label>
                    <div class="col-lg-3">
                        <input type="text" name="customduty" value="" id="customduty" class="form-control" readonly="readonly"/>
                    </div>
              </div>
               <div class="panel-body" style="display:none;">
                    <label class="col-lg-3 control-label">VOTI</label>
                    <div class="col-lg-3">
                        <input type="text" name="voti" value="" id="voti" class="form-control" readonly="readonly"/>
                    </div>
              </div>
              <div class="panel-body">
                    <label class="col-lg-3 control-label">GST On Import</label>
                    <div class="col-lg-3">
                        <input type="text" name="gst" value="" id="gst" class="form-control" readonly="readonly"/>
                    </div>
              </div>
             <div class="panel-body">
                    <label class="col-lg-3 control-label">Other Charges</label>
                    <div class="col-lg-3">
                        <input type="text" name="othercharges" value="" id="othercharges" class="form-control"/>
                    </div>
              </div>
              <div class="panel-body">
                    <label class="col-lg-3 control-label">Clearing Charges</label>
                    <div class="col-lg-3">
                        <input type="text" name="clearingcharges" value="" id="clearingcharges" class="form-control"/>
                    </div>
              </div>
      		<div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="submit"  name="btn_generate" class="btn btn-warning">Save</button>
              </div>
   		</form>   
    </div>
  </div>
</div>
<!--END IMPORT CHARGES MODAL-->

<!--MODAL CONVERT TO PURCHASE-->
<div class="modal fade" id="con_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:46%;">
    <div class="modal-content">
   	 <form class="form-horizontal" method="post" name="convert_inv" id="convert_inv" style="margin-bottom:0px;">
              <div class="modal-header">
                   	<h4 class="dispatch" id="myModalLabel">Convert To It <span id="con_inv_no"></span> ?</h4>
                  	 <input type="hidden" name="con_invoice_id" id="con_invoice_id" value=""  />
              </div>
               <div class="panel-body">
                <label class="col-lg-5 control-label">Do you want to Convert it in Purchase Invoice ?</label>
                
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">No</button>
                <button type="submit"  name="btn_convert" class="btn btn-warning">Yes</button>
              </div>
   		</form>   
    </div>
  </div>
</div>
<!--END CONVERT TO PURCHASE MODAL-->
<style>
@-webkit-keyframes invalid {
  from { background-color: red; }
  to { background-color: inherit; }
}
@-moz-keyframes invalid {
  from { background-color: red; }
  to { background-color: inherit; }
}
@-o-keyframes invalid {
  from { background-color: red; }
  to { background-color: inherit; }
}
@keyframes invalid {
  from { background-color: red; }
  to { background-color: inherit; }
}
.invalid {
  -webkit-animation: invalid 1s infinite; /* Safari 4+ */
  -moz-animation:    invalid 1s infinite; /* Fx 5+ */
  -o-animation:      invalid 1s infinite; /* Opera 12+ */
  animation:         invalid 1s infinite; /* IE 10+ */
}
</style>
<script type="application/javascript">
$(document).ready(function() {
	//final_pdf('117');
	
	
	
});

function pdfexport(invoice_id)
{			
			
			$(".note-error").remove();
			var url = '<?php echo HTTP_SERVER.'pdf/exportdocpdf.php?mod='.encode('exportdoc').'&token=';?>'+encodeURIComponent(invoice_id)+'&status='+encodeURIComponent(1)+'<?php echo '&ext='.md5('php');?>';
			//alert(url);
			//var url = '<?php //echo HTTP_SERVER.'pdf/exportdocpdf.php?mod='.encode('exportdoc').'&token='.rawurlencode($_GET['invoice_no']).'&status='.rawurlencode($_GET['status']).'&ext='.md5('php');?>';
			 window.open(url, '_blank');
		return false;
}

	$('input[name=status]').change(function() {
	//alert("change");
		var invoice_no=$(this).attr('id');
		var status_value = this.value;
		var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=updateInvoice', '',1);?>");
       //alert(status_url);
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
			//$('#test').html(re);
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
function all_done(invoice_no,inv_gen_no)
{

	pdfexport(invoice_no);
	var get_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=alldone', '',1);?>");
		$.ajax({
				url : get_url,
				type :'post',
				data :{invoice_no:invoice_no},
				success: function(){
					set_alert_message('Successfully Updated',"alert-success","fa-check");	
					window.setTimeout(function(){location.reload()},1000)
				},
				error:function(){
					set_alert_message('Error During Updation',"alert-warning","fa-warning");          
				}						
			});	
	
}
function upload(invoice_id,invoice_no,buyers_orderno,order_uder_id,date_added)
{
	$(".u_title").html("Upload Documents For "+invoice_no+" Invoice");
	$("#model_invoice_id").val(invoice_id);
	$("#detail_div").modal('show');
	$("#model_invoice_no").val(invoice_no);
	$("#model_buyers_orderno").val(buyers_orderno);
	$("#model_order_uder_id").val(order_uder_id);
	$("#model_date_added").val(date_added);	
}
//sonu add function 10/12/2016
function track(invoice_no,invoice_id,email)
{
	$(".u_title").html("Send Track id For "+invoice_no+" " );
	$("#emailid").val(email);
	$("#invoiceno").val(invoice_no);
	$("#invoiceid").val(invoice_id);	
	$("#track_div").modal('show');	
}
//sonu add function 23/2/2017
function remark(invoice_no,invoice_id)
{
	
	$(".u_title").html("Add Remark  "+invoice_no+" " );	
	$("#invoice_no").val(invoice_no);
	$("#invoice_id").val(invoice_id);	
	$("#remark_div").modal('show');	
}



$('#btn_upload').click(function(){
	var url=getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=uploadpdf', '',1);?>");
	var file_data = $("#upload_pdf_file").prop("files")[0];          // Getting the properties of file from file field
	var form_data = new FormData();                            // Creating object of FormData class
	form_data.append("file", file_data)
	form_data.append("invoice_id", $('#model_invoice_id').val())
	form_data.append("invoice_no", $('#model_invoice_no').val())
	form_data.append("buyers_orderno", $('#model_buyers_orderno').val())
	form_data.append("order_uder_id", $('#model_order_uder_id').val())
	form_data.append("date_added", $('#model_date_added').val())
	form_data.append("admin", $('#admin').val())
		$.ajax({
			url:url,
			dataType: 'json',
			cache: false,
			contentType: false,
			processData: false,
			data: form_data,                         // Setting the data attribute of ajax with file_data
			type: 'post',
			success:function(response){
				console.log(response);
				$("#detail_div").modal('hide');
				var inv_no = $('#model_invoice_no').val();
				final_pdf(inv_no);
			}
		
		});
		
		
});

function final_pdf(inv_no)
{
	$(".note-error").remove();
			var url = '<?php echo HTTP_SERVER.'upload/admin/return_doc/export_pdf.php?invoice_no=';?>'+inv_no;
			window.location.href = url;
		return false;
}
function gen_credit_note(invoice_id,invoice_no,invoice_total_amount,tran_charges,country_id,email_id)
{		
		$("#span_inv_no").html(invoice_no);
		$("#gen_invoice_id").val(invoice_id);
		//$("#gen_inv_total_amt").val(invoice_total_amount);
		$("#cifamount").val(invoice_total_amount);
		var fob_amount = invoice_total_amount-tran_charges;
		var custom_duty = ((fob_amount*5)/100);
		var voti = invoice_total_amount + custom_duty;
		var gst_on_import = ((voti*10)/100);
		$("#fobamount").val(fob_amount);
		$("#customduty").val(custom_duty);
		$("#voti").val(voti);
		$("#gst").val(gst_on_import);
		$("#gen_country_id").val(country_id);
		$("#gen_email_address").val(email_id);
		$("#gen_inv_number").val(invoice_no);
		
		$("#gen_modal").modal("show");
		
			
}
function convert_invoice(invoice_id,invoice_no)
{
	//console.log(invoice_no);
	$("#con_inv_no").html(invoice_no);
	$("#con_invoice_id").val(invoice_id);
	$("#con_model").modal("show");
}
</script>           
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>