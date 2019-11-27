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
//printr($menuId);die;
//$menuId='220';
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
	$filter_ref_no= $obj_session->data['filter_data']['ref_no'];
	$filter_customer_name= $obj_session->data['filter_data']['customer_name'];
	$filter_email = $obj_session->data['filter_data']['email'];
	$filter_product_code = $obj_session->data['filter_data']['product_code'];
	$class = '';	
	$filter_data=array(
		'invoice_no' => $filter_invoice_no,
		'country_id' => $country_id, 
		'status' => $filter_status,		
		'ref_no' => $filter_ref_no,
	    'customer_name' => $filter_customer_name,
		'email' => $filter_email,
		'product_code' => $filter_product_code,
	);
}
if(isset($_GET['sort'])){
	$sort_name = $_GET['sort'];
}else{
	$sort_name='i.invoice_id'; 
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
	if(isset($_POST['filter_ref_no'])){
		$filter_ref_no=$_POST['filter_ref_no'];		
	}else{
		$filter_ref_no='';
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
	if(isset($_POST['filter_product_code'])){
		$filter_product_code=$_POST['filter_product_code'];
	}else{
		$filter_product_code='';
	}
		
	$filter_data=array(
		'invoice_no' => $filter_invoice_no,
		'country_id' => $country_id,
		'status' => $filter_status,
		'customer_name' => $filter_customer_name,
		'ref_no'=>$filter_ref_no,
		'email' => $filter_email,
		'product_code' => $filter_product_code,

	);
	//printr($filter_data);
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
	elseif(isset($_POST['btn_track_save']))
	{
		//	printr($_POST);die; 
			$obj_invoice->SaveTrackingDetails($_POST);
			page_redirect($obj_general->link($rout, 'mod=index&inv_status='.$_GET['inv_status'], '',1));
			
	}
	elseif(isset($_POST['btn_send_customer']))
	{
		//	printr($_FILES);die; 
			$obj_invoice->send_mail_customer($_POST,$_FILES);
			page_redirect($obj_general->link($rout, 'mod=index&inv_status='.$_GET['inv_status'], '',1));
			
	}	
	elseif(isset($_POST['btn_remark']))
	{
			//printr($_POST);die;
			$obj_invoice->addremark($_POST);
			
	}	
	$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
	$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
	$addedByInfo = $obj_invoice->getUser($user_id,$user_type_id);
	//printr($addedByInfo);
	
	if($user_id=='1' && $user_type_id=='1')
	$addedByInfo['user_id']='';
	    
	
	
	//printr($obj_invoice->saveTotalInvoiceAmount('159'));
	//phpinfo();
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
	
	$invoice_notify=$obj_invoice->getInvoice_for_dipatch_notification($_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS'],$note=1);
		if(!empty($invoice_notify))
		{
			$on_sa = "onclick='show_invoice();'";
			$invoice_notify_status = count($invoice_notify);
		}else{
			
			$on_sa=''; $invoice_notify_status='';
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
		  			
		  			<?php 
		  			if($_SESSION['LOGIN_USER_TYPE']==2 && ($_SESSION['ADMIN_LOGIN_SWISS']==19 || $_SESSION['ADMIN_LOGIN_SWISS']=='144'))
		  			{
    		  			echo '<span style="margin-left:20%">';
    						echo '<a '.$on_sa.' class="a-btn">
    							<span class="a-btn-text">Sales Notification</span> 
    							<span class="a-btn-slide-text">'.$invoice_notify_status.'</span>
    							<span class="a-btn-icon-right"><span></span></span>
    						</a></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
    						echo '</span>';
		  			}
						 ?>
		  			
          			<span class="text-muted m-l-small pull-right">
            	    <?php if($obj_general->hasPermission('add',$menuId) && $_SESSION['LOGIN_USER_TYPE']==1){ ?>
   							<!--<a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add&inv_status='.$_GET['inv_status'].'&add_new=1', '',1);?>"><i class="fa fa-plus"></i> New Invoice </a> &nbsp;-->
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
		                                    <label class="col-lg-5 control-label">Buyer's Order/Ref No  </label>
        			                        <div class="col-lg-7">   
                    				          <input type="text" name="filter_ref_no" value="<?php echo isset($filter_ref_no) ? $filter_ref_no : '' ; ?>" placeholder="Order / Ref No" id="filter_ref_no" class="form-control" />
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
                                      <label class="col-lg-5 control-label">Product Code</label>
                                        <?php $productcodes = $obj_invoice->getActiveProductCode();?>
                                        <div class="col-lg-4">
                                            <select name="filter_product_code" class="form-control" id="chosen_data">
                                                <option value="">Select Product</option> 
                                                <?php  foreach($productcodes as $code)
                                                   {
                                                       if(isset($filter_product_code) && $code['product_code_id']==$filter_product_code)
                                                          echo '<option value="'.$code['product_code_id'].'" selected="selected">'.$code['product_code'].'</option>';
                                                       else
                                                          echo '<option value="'.$code['product_code_id'].'">'.$code['product_code'].'</option>';
                                                   }
                                                   ?>
                                            </select>
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
                        	           		<option value="<?php echo $obj_general->link($rout, 'mod=index&limit='.$display_limit.'&inv_status='.$_GET['inv_status'], '',1);?>" selected="selected"><?php echo $display_limit; ?></option>				
									<?php } else { ?>
                            				<option value="<?php echo $obj_general->link($rout, 'mod=index&limit='.$display_limit.'&inv_status='.$_GET['inv_status'], '',1);?>"><?php echo $display_limit; ?></option>
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
                              <th>Mode of Shipment</th>
                              <th></th>
                              <?php if($_GET['inv_status']=='2'){
                              echo '<th></th><th></th>';}?>
                              <?php //printr($_GET['inv_status']); ?>
                             <?php   if($_GET['inv_status']=='0'){?> <th>Track Id</th>
                             <?php   if($_GET['inv_status']=='0'){?> <th>Action</th><?php }?>
							 <th>Remark</th><?php }?>
                      		  <th>Status</th>
                      		  <th>Posted By</th>
                              
		                      <?php if($obj_general->hasPermission('edit',$menuId) && $_GET['inv_status']=='1'){ ?><th>Action</th><?php }?>
		                      <th></th>
        		            </tr>
                		  </thead>
	                 	  <tbody>
                  			<?php $total = $obj_invoice->getTotalInvoice($obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS'],$filter_data,$_GET['inv_status'],$addedByInfo['user_id']);
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
				//printr( $invoices);	 		
                                  if($total)
							{
                      			foreach($invoices as $invoice){ 
								//printr($_GET['inv_status']);
							//	printr($invoice);
								$class_css='';
								if($invoice['done_status']=='1' && $_GET['inv_status']!='2' && $_GET['inv_status']!='3')
								  $class_css='class=invalid';
								 //printr($invoice['done_status']);
								  if($invoice['done_status']=='2')
								  {
									 // printr($invoice);
									  //echo   $invoice['purchase_customer_name'];
								  }
								  //echo $class_css;
								
								?>
                       				<tr <?php echo ($invoice['status']==0) ? 'style="background-color:#FADADF" ' : '' ;  ?> >                        
                          				<td><input type="checkbox" name="post[]" value="<?php echo $invoice['invoice_id'];?>"></td>
                          				<td> <a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_no='.encode($invoice['invoice_id']).'&status=1&inv_status='.$_GET['inv_status'],'',1); ?>" > 
                                 <?php  
                                        
                                        $dt = strtotime($invoice['invoice_date']);
					 
                    					 if(date("m",$dt)>=4)
                    					   $dt =(date("y",$dt)).'-'.(date("y",$dt)+1);
                    					 else
                    					   $dt =(date("y",$dt)-1).'-'.(date("y",$dt));       
                                   
                                    $inv_no=$invoice['invoice_no'].'/'.$dt.'/'. date("d-m-Y",strtotime($invoice['invoice_date']));
                                 
                                 if($_GET['inv_status']=='0' || $_GET['inv_status']=='1' || $_GET['inv_status']=='2'){ ?>
                                        
                                            
                      					<?php echo $invoice['invoice_no'].'&nbsp;/&nbsp;'.$dt.'&nbsp;/&nbsp;'; echo date("d-m-Y",strtotime($invoice['invoice_date']));?></a></td>
                               	<?php } else if ($_GET['inv_status']=='3') { ?>
                                 		<?php echo $invoice['invoice_no'].'&nbsp;/&nbsp;'.$dt.'&nbsp;/&nbsp;'; echo date("d-m-Y",strtotime($invoice['convert_to_purchase_date']));?></a></td>
                                <?php } ?>
                                            
                                      <td><a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_no='.encode($invoice['invoice_id']).'&status=1&inv_status='.$_GET['inv_status'],'',1); ?>" > <?php  if(($invoice['done_status']=='1' || $invoice['done_status']=='2'|| $invoice['done_status']=='3') && $_GET['inv_status']=='0') echo $invoice['customer_name'];
									else if(($invoice['done_status']=='2'&& $_GET['inv_status']=='2') ||($invoice['done_status']=='1')||($invoice['done_status']=='3' && $_GET['inv_status']=='3')) echo "Swiss Pac Pvt Ltd.";  if($invoice['customer_dispatch']=='1'){ echo '<br><b>dispatch order directly to customer</b>';} ?></a></td>
                                      <td><a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_no='.encode($invoice['invoice_id']).'&status=1&inv_status='.$_GET['inv_status'],'',1); ?>" > <?php echo $invoice['email']; ?></a></td>
                                      <?php if(is_numeric($invoice['final_destination']))
                                          $invoice_sea = $obj_invoice->getCountryName($invoice['final_destination']);
										  	else
												$invoice_sea['country_name']=$invoice['final_destination'];
                                          ?>
                                      <td><a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_no='.encode($invoice['invoice_id']).'&status=1&inv_status='.$_GET['inv_status'],'',1); ?>" ><?php echo $invoice_sea['country_name']; ?></a></td>
                                      <!--<td><a href="<?php //echo $obj_general->link($rout, 'mod=view&invoice_no='.encode($invoice['invoice_id']).'&status=1&inv_status='.$_GET['inv_status'],'',1); ?>" ><?php //echo $invoice['country_name']; ?></a></td>-->
                                      <td><a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_no='.encode($invoice['invoice_id']).'&status=1&inv_status='.$_GET['inv_status'],'',1); ?>" ><?php echo ucwords($invoice['order_type']);?></a></td>
                                      <td><a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_no='.encode($invoice['invoice_id']).'&status=1&inv_status='.$_GET['inv_status'],'',1); ?>" > By <?php echo ucwords(decode($invoice['transportation']));?></a></td>
                                      <td>
                                      <?php 
									  
									
								 
	  //sonu change if condition 14/12/2016 
									   
									  if(($_GET['inv_status']=='0' || $_GET['inv_status']=='2' ) && $_GET['inv_status']!='3')
									  { 
									      if($invoice['done_status']=='1' && $_GET['inv_status']!='2' ){?>
                                      	
                                        <?php /*?><img src="<?php echo HTTP_SERVER.'admin/controller/invoice/pdf_img.png';?>" alt="Image" class="img-circle" style="max-width:5%;"><?php */?>
                                       <?php /*?> <a class="label bg-info" href="javascript:void(0);" onclick="pdfexport('<?php echo encode($invoice['invoice_id']);?>');"><i class="fa fa-download"></i> PDF Export</a><?php */?>
                                       
                                       
                                       <?php 
                                      // if(($invoice['customer_dispatch']=='1' )|| ((decode($invoice['transportation']))=='air') && date("d-m-Y",strtotime($invoice['invoice_date']))>='18-06-2019' ){
                                       if($invoice['courier_status']=='0' && (((decode($invoice['transportation']))=='air') || ((decode($invoice['transportation']))=='road'))){?>
                                       <a class="label bg-info"  onclick="track('<?php echo $invoice['invoice_no'];?>','<?php echo $invoice['invoice_id'];?>','<?php echo $invoice['email'];?>');" id="track_a">Add Courier Info </a><br><br>
                                      <!--  <a class="label bg-info"  onclick="upload('<?php //echo $invoice['invoice_id'];?>','<?php //echo $invoice['invoice_no'];?>','<?php //cho $invoice['buyers_orderno'];?>','<?php //echo $invoice['order_user_id'];?>','<?php //echo $invoice['date_added'];?>','<?php //echo encode($invoice['invoice_id']);?>');" id="upload_a">Upload</a>-->
                                       <?php }?>
                                       
                                       
                                       
                                        	<?php /*?><input type="file" name="file" title="Upload CSV" class="btn btn-info btn-xs" onchange="upload_pdf(<?php echo $invoice['invoice_id'];?>)" id="pdf_upload_<?php echo $invoice['invoice_id'];?>"><?php */?>
                                        
                                        	<?php 
                            		  			if($_SESSION['LOGIN_USER_TYPE']==1 &&  $_SESSION['ADMIN_LOGIN_SWISS']==1 || ($_SESSION['ADMIN_LOGIN_SWISS']==19))
                            		  			{?>
                            		  			<br> <br><a class="label bg-inverse m-l-mini"  style="background-color:#3fcf7f" onclick="reviceInvoiceModel('<?php echo $invoice['invoice_id'];?>',0)" id="reviceInvoice">Revice Invoice</a>
                            		  				<br> <br><a class="label bg-inverse m-l-mini"  style="background-color:#0e0e0e" onclick="reviceInvoiceModel('<?php echo $invoice['invoice_id'];?>',1)" id="reviceInvoice">Return</a>
                            		  			
                            		  			<?php }?>
                                      <?php }
									  else if($invoice['done_status']=='2'){ ?>
									  	<?php 
                            		  			if($_SESSION['LOGIN_USER_TYPE']==1 &&  $_SESSION['ADMIN_LOGIN_SWISS']==1 )
                            		  			{?>
									  				<br> <br><a class="label bg-inverse m-l-mini"  style="background-color:#e83e65" onclick="final_pdf('<?php echo $invoice['invoice_no'];?>','<?php echo $invoice['invoice_id'];?>')" id="reviceInvoice">merge</a><br><br> <br>
                                              <?php }?>
                                                <a href="<?php echo HTTP_UPLOAD."admin/return_doc/php-pdf-merge-master/example/".$invoice['invoice_id'].':'.$invoice['invoice_no'].'/invoice-'.$invoice['invoice_no'].'.pdf';?>" target="_blank" ><i class="fa fa-download">Export PDF</i></a>
                                              <?php// if($invoice['courier_status']=='0'){?>
                                                <br><br> <a class="label bg-info"  onclick="track('<?php echo $invoice['invoice_no'];?>','<?php echo $invoice['invoice_id'];?>','<?php echo $invoice['email'];?>');" id="track_a">Add Courier Info </a>
								                <?php //}else{
								                    $send_date= date('l, F d Y', strtotime($invoice['sent_date']));
								                ?>
								               <!--  <br><br><a class="label bg-inverse m-l-mini"  style="background-color:#0e0e0e" onclick="send_email_customer('<?php //echo $invoice['invoice_no'];?>',<?php //echo $invoice['order_user_id'];?>,'<?php //echo $invoice['customer_name'];?>',<?php //echo $invoice['country_destination'];?>,<?php //echo $invoice['invoice_id'];?>,'<?php //echo $inv_no ;?>','<?php //echo $invoice['email'];?>','<?php //echo $invoice['buyers_orderno'];?>','<?php //echo $invoice['uk_ref_no'];?>','<?php //echo $invoice['tracking_no'];?>','<?php //echo $invoice['courier_name'];?>','<?php //echo addslashes($invoice['trackinfo']);?>','<?php //echo $send_date;?>','<?php //echo 	$invoice_sea['country_name'];?>');" id="track_a">Send Customer Email</a>-->
								                <?php// }?>
								         
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
										 <a href="#" onclick="all_done('<?php echo encode($invoice['invoice_id']);?>','<?php echo $invoice['invoice_no'];?>','<?php echo $invoice['customer_dispatch'];?>','<?php echo (decode($invoice['transportation']));?>')" name="btn_edit" class="btn btn-inverse btn-xs">Done</a> 
										  
									 <?php }
									  } ?>
                                      
                                      </td>
                                      <!--sonu add 10/12/2016-->
                                  
                                    <td> <?php
                                    	if(($obj_general->hasPermission('edit','287')) && $_GET['inv_status']=='0'){?>
                                    	
													<br><br><a class="btn btn-outline-info btn-sm"  onclick="generate_sales_inv(<?php echo $invoice['invoice_id'];?>)"> Government Sales Invoice</a>
										<?php } ?>  
                                       </td>  
                                       <td> 
                                         <?php if($_GET['inv_status']=='0' && $invoice['done_status']=='1') { ?>
                                        
                                            
                                                <a href="<?php echo $obj_general->link($rout, 'mod=add&invoice_no='.encode($invoice['invoice_id']).'&inv_status='.$_GET['inv_status'],'',1); ?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                                           
                                         
                                         <?php }?>
                                        </td>
                                       <td>
                                        <?php if($_GET['inv_status']=='0' && $invoice['add_remark_status']=='0' && $invoice['remarks']==''){?>
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
												$addedByInfo1 = '';
												$addedByInfo1 .= '<div class="row">';
												$addedByInfo1 .= '<div class="col-lg-3"><img src="'.$addedByImage.'"></div>';
												$addedByInfo1 .= '<div class="col-lg-9">';
												if($addedByData['city']){ $addedByInfo1 .= $addedByData['city'].', '; }
												if($addedByData['state']){ $addedByInfo1 .= $addedByData['state'].' '; }
												if(isset($addedByData['postcode'])){ $addedByInfo1 .= $addedByData['postcode']; }
												$addedByInfo1 .= '<br>Telephone : '.$addedByData['telephone'].'</div>';
												$addedByInfo1 .= '</div>';
												$addedByName = $addedByData['first_name'].' '.$addedByData['last_name'];
												str_replace("'","\'",$addedByName);?>
                                                <a class="btn btn-info btn-xs" data-trigger="hover" data-toggle="popover" data-html="true" data-placement="top" data-content='<?php echo $addedByInfo1;?>' title="" data-original-title="<b><?php echo $addedByName;?></b>"><?php echo $addedByData['user_name'];?></a>  
                                      </td>
                                      <?php //if($invoice['done_status']!='1'){?>
                                          <?php if($obj_general->hasPermission('edit',$menuId) && $invoice['done_status']=='0'){ ?> 	
                                          	<td>
                                                <a href="<?php echo $obj_general->link($rout, 'mod=add&invoice_no='.encode($invoice['invoice_id']).'&inv_status='.$_GET['inv_status'],'',1); ?>"  name="btn_edit" class="btn btn-info btn-xs">Add Details</a>
                                              </td>
                                                <?php } ?>
                                          
                                      <?php //}
									 // printr($invoice); ?>
                       				  <td><a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_no='.encode($invoice['invoice_id']).'=&status=1&inv_status='.$_GET['inv_status'],'',1); ?>"  name="btn_edit" class="btn btn-info btn-xs">View Invoice</a></td>
                                        <?php
										 if( $_GET['inv_status']=='2' && $_GET['inv_status']!='0' && $_GET['inv_status']!='3')
											 { 
										
												if(decode($invoice['transportation'])=='sea' && $invoice['import_status']=='0')
												 {
													
												 ?>
											   <td><a onclick="gen_credit_note(<?php echo $invoice['invoice_id'];?>,'<?php echo $invoice['invoice_no'];?>',<?php echo $invoice['invoice_total_amount'];?>,<?php echo $invoice['tran_charges'];?>,'<?php echo $invoice['final_destination'];?>','<?php echo $invoice['email'];?>')"  name="btn_edit" class="btn btn-info btn-xs">Import Charges</a></td>
										<?php } 
												 if($invoice['import_status']=='1')
												 { ?><?php if($addedByInfo['user_id']=='19' || $addedByInfo['user_id']=='24' || $addedByInfo['user_id']=='33' || $addedByInfo['user_id']=='44' || $addedByInfo['user_id']=='10' ) { ?>
												             <td><a onclick="convert_invoice('<?php echo  encode($invoice['invoice_id']);?>','<?php echo $invoice['invoice_no'];?>')"  name="btn_edit_one" class="btn btn-info btn-xs">Convert To Purchase</a></td>
											        <?php } else { ?>
											         <td><a onclick="convert_invoice(<?php echo $invoice['invoice_id'];?>,'<?php echo $invoice['invoice_no'];?>')"  name="btn_edit_one" class="btn btn-info btn-xs">Convert To Purchase</a></td>
											        
											  <?php } }} ?>
                               
                               	 </tr>
                        		<?php }
								}
								//pagination
								$pagination = new Pagination();
								$pagination->total = $total;
								$pagination->page = $page;
								$pagination->limit = $limit;
								$pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
								$pagination->url = $obj_general->link($rout, '&page={page}&limit='.$limit.'&filter_edit=1&inv_status='.$_GET['inv_status'], '',1);
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
                     <div class="form-group">
						<label class="col-lg-3 control-label"><span class="required">*</span> Sent Date</label>
						<div class="col-lg-8">
							<input type="text" name="sent_date" id="sent_date" value="<?php echo date("Y-m-d");?>"  data-format="YYYY-MM-DD"  data-template="D MMM YYYY" placeholder="Date"  class="combodate form-control"/>
						</div>
					  </div>
					  <div class="form-group">
						<label class="col-lg-3 control-label"><span class="required">*</span> Courier Info. (Name)</label>
						<div class="col-lg-5">
						 <?php $couriers = $obj_invoice->getCouriers();?>
                                 <select name="courier_name" id="courier_name" class="form-control " >
                                        <option value="0">Select Courier</option>
                                      <?php foreach($couriers as $courier){
                                   if($invoice['courier_id']==$courier['courier_id']){ 
                                        echo '<option value="'.$courier['courier_name'].'" selected="selected" >'.$courier['courier_name'].'</option>';
                                    }else{
                                        echo '<option value="'.$courier['courier_name'].'">'.$courier['courier_name'].'</option>';
                                    }
                                } ?>
                                </select> 
						</div>
					  </div> 
					          
					  <div class="form-group">
						<label class="col-lg-3 control-label"><span class="required">*</span> AWS No. (Tracking No.)</label>
						<div class="col-lg-5">
							<input type="text" name="tracking_no" id="tracking_no" class="form-control validate" value=""  require/>
						</div>
					  </div>
					 <div class="form-group">
                      <label class="col-lg-3 control-label">Track Information</label>
                        <div class="col-lg-8">
                          <textarea id="trackinfo" name="trackinfo" value="" class="form-control" ></textarea>
                           <input type="hidden" name="admin" id="admin" value="<?php echo ADMIN_EMAIL;?>" />
                           <input type ="hidden" name="invoiceno" id="invoiceno" value="" />
                            <input type ="hidden" name="invoiceid" id="invoiceid" value="" />
                           
                        </div>
                     </div>
                    	
                  </div>
                  </div>
                </div>
                
              

              
              <div class="modal-footer">
                   		   <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" >Close</button>
                   		   <button type="submit" name ="btn_track_save" id="btn_track_save" class="btn btn-primary">Save</button>
                  
              </div>
              </div>
   		</form>   
    </div>
  </div>
</div>


 <div class="modal fade" id="revice_div" tabindex="-1" role="dialog" aria-labelledby="myModalLabe2" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="revice" id="revice" style="margin-bottom:0px;">
              <div class="modal-header">
               
               
                <input type="hidden" value="" name="revice_invoice_id" id="revice_invoice_id"/>
                <input type="hidden" value="" name="revice_invoice_status" id="revice_invoice_status"/>
              </div>
                 <div class="modal-body">
                 <div class="form-group">
                   <div class="panel-body">
                        <h4 class="modal-title u_title" id="myModalLabel"><b>Are you sure you want to Revice  selected Invoice ?<b></b></h4>
                       </div></div></div>
           
              <div class="modal-footer">
                   		   <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" >Close</button>
                   		    <button type="button"  name ="btn_revice" onclick="reviceInvoice()" class="btn btn-primary btn-sm" data-dismiss="modal" >Yes</button>
                   	
                  
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
<!--MODAL FOR IMPORT CHARGES-->
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
                        <input type="text" name="customduty" value="" id="customduty" class="form-control" />
                    </div>
              </div>
               <div class="panel-body" style="display:none;">
                    <label class="col-lg-3 control-label">VOTI</label>
                    <div class="col-lg-3">
                        <input type="text" name="voti" value="" id="voti" class="form-control" />
                    </div>
              </div>
              <div class="panel-body">
                    <label class="col-lg-3 control-label">GST On Import</label>
                    <div class="col-lg-3">
                        <input type="text" name="gst" value="" id="gst" class="form-control"/>
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
                <?php if($addedByInfo['user_id']=='19' || $addedByInfo['user_id']=='24' || $addedByInfo['user_id']=='33' || $addedByInfo['user_id']=='44' || $addedByInfo['user_id']=='10') { ?>
                    <button type="button"  name="btn_convert" onclick="convert()" class="btn btn-warning">Yes</button>
                <?php } else {?>
                    <button type="submit"  name="btn_convert" class="btn btn-warning">Yes</button>
                <?php } ?>
              </div>
   		</form>   
    </div>
  </div>
</div>
<!--END CONVERT TO PURCHASE MODAL-->

<!-- start india dispatch  -->
<div class="modal fade" id="gen_modal_dis" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:55%;">
    <div class="modal-content">
    
    	<form class="form-horizontal" method="post" name="credit_note" id="credit_note" style="margin-bottom:0px;">
              <div class="modal-header">
                   	<h4 class="dispatch" id="myModalLabel">Invoice List</h4>
              </div>
              
               <div class="modal-body">
                    <div class="form-group invoice_table_data">
                    	
                    </div>
              </div>
              
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
              </div>
   		</form>   
    </div>
  </div>
</div>


<div class="modal fade" id="product_list_sales" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:80%;">
    <div class="modal-content">
    
    	<form class="form-horizontal" method="post" name="credit_note" id="credit_note" style="margin-bottom:0px;">
              <div class="modal-header">
                   	<h4 class="dispatch" id="myModalLabel"><span id="span_inv_no_sales"></span></h4>
              </div>
              
               <div class="modal-body">
                    <div class="form-group invoice_data">
                    	
                    </div>
              </div>
              
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
              </div>
   		</form>   
    </div>
  </div>
</div>

<!-- End  india dispatch  -->
<!-- [sonu] 19/06/2018-->

 <div class="modal fade" id="form_con_gov" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
 	 <div class="modal-content">
    	<form class="form-horizontal" method="post" name="form" id="conform_form" style="margin-bottom:0px;">
        	<div class="modal-header title">
                <h4 class="modal-title" id="myModalLabel"><span id="pro"></span></h4>
              </div>
            <div class="modal-body">
            	<input name="invoice_id" id="invoice_id" value=""  type="hidden"/>
                <h4 class="streamlined_title"> Sure !!! <br /><br />
                						Do you want to generate Sales Invoice ?</h4>
            </div> 
             <div class="modal-footer">
                <button type="button" name="btn_submit" class="btn btn-primary" onclick="generate_sales()">Yes</button>
                 <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
              </div>
        </form>
     </div>
    </div>
</div>
<div class="modal fade" id="email_div" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:70%;" >
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="cform" id="cform" enctype="multipart/form-data"  style="margin-bottom:0px;">
              <div class="modal-header">
				<h4 class="modal-title u_title" id="myModalLabel"><span id="customer"></span></h4>
              </div>
              <div class="modal-body">
                 <div class="form-group">
                   <div class="panel-body">
                    <div class="form-group">    
						<label class="col-lg-1 control-label" id="email">To Email</label>
                       <div class="col-lg-10">
                            <!--<input type="toemail" name="toemail" value="" id="toemail" class="form-control" required />-->
                            <textarea id="toemail" name="toemail" value="" class="form-control"  required style="height: 50px;"></textarea>
                      </div>
                     </div>
                        <div class="form-group">
                            <label class="col-lg-1 control-label" id="email">CC</label>
                             <div class="col-lg-10"> 
                            <textarea id="ccemail" name="ccemail" value="" class="form-control"  required style="height: 50px;"></textarea>
                             <!--  <input type="toemail" name="ccemail" value="" id="ccemail" class="form-control"/>--><br><small style="color:red;">If you want to have multiple email CC, please add email ids with a comma (,) sign.</small>
                  
                            </div> 
                     </div>
                       <div class="form-group">
                        <label class="col-lg-1 control-label" id="email">BCC</label>
                        <div class="col-lg-10">
                            <input type="toemail" name="bccemail" value="" id="bccemail" class="form-control"/><small style="color:red;">If you want to have multiple email BCC, please add email ids with a comma (,) sign.</small>
             
                        </div> 
                      </div>
                      <div class="form-group">
                        <label class="col-lg-1 control-label" id="email">Subject</label>
                        <div class="col-lg-10">
                            <input type="text" name="subject" value="" id="subject" class="form-control" required />
                        </div> 
                       </div>
                     <div class="form-group">
                  		 <label class="col-lg-1 control-label">Upload<br/><small class="text-muted">(Only .Excel format)</small></label>
            		          <input type="file" name="file_excel" title="Upload Excel" class="btn btn-sm btn-info m-b-small " id="upload_sheet_file">
            		           <button type="button" name="btn_upload_file" id="btn_upload_file" class="btn btn-default btn-sm" >Upload</button>
            		           <small style="color:green;"> Note:To Attached Excel in Email  Please Upload .</small>
                      </div>
                     <div class="form-group"> 
                      <label class="col-lg-1 control-label">Body</label>
                        <div class="col-lg-10">
                          <textarea id="message" name="message" value="" class="form-control"  required style="height: 237px;"></textarea>
                         <input type="hidden" name="admin" id="admin" value="<?php echo ADMIN_EMAIL;?>" />
                          <input type="hidden" name="emailform" id="emailform" value="" />
                            <input type ="hidden" name="invoice_id_send" id="invoice_id_send" value="" />
                            <input type ="hidden" name="invoice_no_send" id="invoice_no_send" value="" />
                            <input type ="hidden" name="courier_name_mail" id="courier_name_mail" value="" />
                            <input type ="hidden" name="tracking_no_mail" id="tracking_no_mail" value="" />
                            <input type ="hidden" name="track_info_mail" id="track_info_mail" value="" />
                           </div> 
                        </div>
                     </div>
                       </div>	
                </div>
              <div class="modal-footer">
				   <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" >Close</button>
				   <button type="submit" name ="btn_send_customer" id="btn_send_customer"  class="btn btn-primary">Send</button>
              </div>
   		</form>   
    </div>
  </div>
</div>
<!-- [sonu] -->


<style>
    .chosen-container.chosen-container-single {
        width: 300px !important; /* or any value that fits your needs */
    }
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

.a-btn{
	background: #80a9da;
    background: linear-gradient(top, #80a9da 0%,#6f97c5 100%);
    padding-left: 20px;
    padding-right: 80px;
    height: 38px;
    display: inline-block;
    position: relative;
    border: 1px solid #5d81ab;
    box-shadow: 
		0px 1px 1px rgba(255,255,255,0.8) inset, 
		1px 1px 3px rgba(0,0,0,0.2), 
		0px 0px 0px 4px rgba(188,188,188,0.5);
    border-radius: 20px;
    float: center;
    clear: both;
    margin: -2px 0px;
    overflow: hidden;
    transition: all 0.3s linear;
}
.a-btn-text{
    padding-top: 5px;
    display: block;
    font-size: 18px;
    white-space: nowrap;
    text-shadow: 0px 1px 1px rgba(255,255,255,0.3);
    color: #446388;
    transition: all 0.2s linear;
}
.a-btn-slide-text{
    position:absolute;
    height: 100%;
    top: 0px;
    right: 52px;
    width: 0px;
    background: #63707e;
    text-shadow: 0px -1px 1px #363f49;
    color: #fff;
    font-size: 18px;
    white-space: nowrap;
    text-transform: uppercase;
    text-align: left;
    text-indent: 10px;
    overflow: hidden;
    line-height: 38px;
    box-shadow: 
		-1px 0px 1px rgba(255,255,255,0.4), 
		1px 1px 2px rgba(0,0,0,0.2) inset;
    transition: width 0.3s linear;
}
.a-btn-icon-right{
    position: absolute;
    right: 0px;
    top: 0px;
    height: 100%;
    width: 52px;
    border-left: 1px solid #5d81ab;
    box-shadow: 1px 0px 1px rgba(255,255,255,0.4) inset;
}
.a-btn-icon-right span{
    width: 38px;
    height: 38px;
    opacity: 0.7;
    position: absolute;
    left: 50%;
    top: 50%;
    margin: -20px 0px 0px -20px;
    background: transparent url(http://tympanus.net/Tutorials/AnimatedButtons/images/arrow_right.png) no-repeat 50% 55%;
    transition: all 0.3s linear;
}
.a-btn:hover{
    padding-right: 180px;
    box-shadow: 0px 1px 1px rgba(255,255,255,0.8) inset, 1px 1px 3px rgba(0,0,0,0.2);
}
.a-btn:hover .a-btn-text{
    text-shadow: 0px 1px 1px #5d81ab;
    color: #fff;
}
.a-btn:hover .a-btn-slide-text{
    width: 100px;
}
.a-btn:hover .a-btn-icon-right span{
    opacity: 1;
}
.a-btn:active {
    position: relative;
    top: 1px;
    background: #5d81ab;
    box-shadow: 1px 1px 2px rgba(0,0,0,0.4) inset;
    border-color: #80a9da;
}
</style>
<script src="https://harvesthq.github.io/chosen/chosen.jquery.js" type="text/javascript"></script>
<link rel="stylesheet" href="https://harvesthq.github.io/chosen/chosen.css" type="text/css"/> 
<script src="<?php echo HTTP_SERVER;?>ckeditor3/ckeditor.js"></script>
<script type="application/javascript">
$(document).ready(function() {
	//final_pdf('117');
	
	$("#chosen_data").chosen();
	
});

function pdfexport(invoice_id)
{			
			
		$(".note-error").remove();
		var url = '<?php echo HTTP_SERVER.'pdf/exportdocpdf.php?mod='.encode('exportdoc').'&token=';?>'+encodeURIComponent(invoice_id)+'&status='+encodeURIComponent(1)+'<?php echo '&ext='.md5('php');?>';
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
		$(".modal-title").html("WARNING");
		$("#setmsg").html('Please select atlease one record');
		$("#popbtnok").hide();
		$("#myModal").modal("show");
	}
	
}
function all_done(invoice_no,inv_gen_no,customer_dispatch,transportation)
{

	
	var get_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=alldone', '',1);?>");
		$.ajax({
				url : get_url,
				type :'post',
				data :{invoice_no:invoice_no},
				success: function(){
					set_alert_message('Successfully Updated',"alert-success","fa-check");	
				//	pdfexport(invoice_no);
					//window.setTimeout(function(){location.reload()},1000)
			    //	if(customer_dispatch==1){ 
			    	if(transportation=='air'){
				        pdfexport(invoice_no);
				    	location.reload();
				    }else{
				    	location.reload();
				    }
					
					
				},
				error:function(){
					set_alert_message('Error During Updation',"alert-warning","fa-warning");          
				}						
			});	
	
	
} 
function upload(invoice_id,invoice_no,buyers_orderno,order_uder_id,date_added,inv_id)
{
 //   pdfexport(inv_id);
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
	
	$(".u_title").html("Add Tracking Details For "+invoice_no+" " );
//	$("#emailid").val(email);
	$("#invoiceno").val(invoice_no);
	$("#invoiceid").val(invoice_id);	
	$("#track_div").modal('show');      
}
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
				var inv_id = $('#model_invoice_id').val();
				final_pdf(inv_no,inv_id);
			}
		
		});
		
		
});
$('#btn_upload_file').click(function(){
	var url=getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=uploadsheet', '',1);?>");
	var file_data = $("#upload_sheet_file").prop("files")[0];          // Getting the properties of file from file field
	var form_data = new FormData();                            // Creating object of FormData class
	form_data.append("file", file_data)
	form_data.append("invoice_id", $('#invoice_id_send').val())
	form_data.append("invoice_no", $('#invoice_no_send').val())
		$.ajax({
			url:url,
			dataType: 'json',
			cache: false,
			contentType: false,
			processData: false,
			data: form_data,                         // Setting the data attribute of ajax with file_data
			type: 'post', 
			success:function(response){
			//	console.log(response);
			}
		
		});
		
		
});
function final_pdf(inv_no,inv_id)
{
	$(".note-error").remove();
		
        $(".note-error").remove();
			var url = "<?php echo HTTP_SERVER;?>upload/admin/return_doc/php-pdf-merge-master/example/example.php?invoice_no="+inv_no+"&invoice_id="+inv_id;
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


//india dispatch 

function show_invoice()
{
		var data_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=invoiceList', '',1);?>");
		$.ajax({
			url : data_url,
			method : 'post',
			data : {},
			success: function(response){
				
				
				$(".invoice_table_data").html(response);
				
			},
			error:function(){
			}	
		});
		$("#gen_modal_dis").modal("show");
}
function reviceInvoiceModel(invoice_id,status)
{       
        $('#revice_invoice_id').val(invoice_id);
        $('#revice_invoice_status').val(status);
    	$("#revice_div").modal("show");
}
 function reviceInvoice()
{
    
   // alert(invoice_id);
   invoice_id=$("#revice_invoice_id").val();
   revice_invoice_status=$("#revice_invoice_status").val();
		var data_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=reviceInvoice', '',1);?>");
		$.ajax({
			url : data_url,
			method : 'post',
			data : {invoice_id:invoice_id,revice_invoice_status:revice_invoice_status},
			success: function(response){ 
				
			$("#revice_div").modal("hide");
			set_alert_message('Successfully Reviced Invoice',"alert-success","fa-check");
			
			},
			error:function(){
			}	
		});
	
}

function showinvoiceProduct(invoice_id,invoice_no){
	
		$("#span_inv_no_sales").html(invoice_no);
		var data_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=showSalesProduct', '',1);?>");
		$.ajax({
			url : data_url,
			method : 'post',
			data : {invoice_id : invoice_id, invoice_no: invoice_no},
			success: function(response){
				
				//console.log(response);
				$(".invoice_data").html(response);
				
			},
			error:function(){
			}	
		});
		$("#product_list_sales").modal("show");
}
function getGenSalesId(invoice_id)
{
	if($("#sales_"+invoice_id).prop('checked') == true)
	{
		$("#rack_sales_"+invoice_id).show();
	}
	else
	{
		$("#rack_sales_"+invoice_id).hide();
		$("#pallet_sales_"+invoice_id).hide();
		$("#btn_done_sales_"+invoice_id).hide();
	}
}
function get_pallet_sales(invoice_product_id,product_code_id)
{
	

	

	var rack_no=$("#rack_no_"+invoice_product_id).val();
	console.log(rack_no);
	var rack_array = rack_no.split(',');	
	// alert(rack_array);
	
	var rack_qty=$("#rack_qty_"+invoice_product_id).val();
	var rack_qty_arr = rack_qty.split('&');	
	//alert(rack_qty_arr);
	
	var length =rack_array.length;
	var rack_val=$("#rack_sales_"+invoice_product_id).val();
	var arr = rack_val.split('=');
	var row = arr[0];
	var col = arr[1];
	var goods_master_id = arr[2];
	var sel = '';
	 sel+= '<select name="pallet_sales_'+invoice_product_id+'" id="pallet_sales_'+invoice_product_id+'" style="width: inherit;" class="form-control"><option>Select Rack</option>';
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
				
	  sel+= '</select>';
	//console.log(sel);
	//console.log($("#rack_number_sales_"+invoice_product_id));
	$("#rack_number_sales_"+invoice_product_id).html(sel);
	 //$("#in_qty_"+invoice_product_id).show();
    $("#btn_done_sales_"+invoice_product_id).show();
	 $("#in_qty_"+invoice_product_id).show();
	
}

function savedispatch(invoice_product_id,product_code_id,product,qty)
{
	var rack_qty=$("#rack_qty_"+invoice_product_id).val();
	var rack_qty_arr = rack_qty.split('&');	
	var rack_qty_length = rack_qty_arr.length-1;
	var pallet_sales=$("#pallet_sales_"+invoice_product_id).val();
	
	var alldata = $("#pallet_sales_"+invoice_product_id).val();
	var arr = pallet_sales.split('=');
	//alert(arr);
	var row = arr[0];
	var col = arr[1];
	var goods_master_id = arr[2];
	var pallet_no = arr[3];
	var rack_no=$("#rack_no_"+invoice_product_id).val();
	var invoice_id=$("#invoice_id_"+invoice_product_id).val();
	var invoice_no=$("#invoice_no_"+invoice_product_id).val();
	//alert(invoice_id);
	for(var i=0;i<=rack_qty_length;i++)
	{
		var box_new = rack_qty_arr[i].split('=');	
	
		if(box_new[1]==pallet_no)
		{
			$("#box_qty_new_"+invoice_product_id).val(box_new[0]);
		}
		
	}
	//rack_sales_150
	var rack_name_dis = $("#rack_sales_"+invoice_product_id).val();
	var rack_qty =parseInt($("#box_qty_new_"+invoice_product_id).val());
	var dispatch_qty = parseInt($("#in_qty_"+invoice_product_id).val());
	var box_qty_new = $("#box_qty_new_"+invoice_product_id).val();
	//console.log(dispatch_qty);
	if(dispatch_qty>rack_qty)
	{
		alert('Your Rack Qty is '+rack_qty+'. Please Enter Proper Qty!! ');
	}
	else if(dispatch_qty>qty)
	{
		alert('Your Invoice Qty is '+qty+'. Please Enter Proper Qty!! ');
	}
	else
	{
		if(dispatch_qty!='')
		{	
			var label_url = getUrl("<?php  echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=savedispatch_racknotify', '',1);?>");
			
			$.ajax({
				type: "POST",
				url: label_url,
				data:{product_code_id : product_code_id, product:product ,rack_name_dis:rack_name_dis,rack_qty:rack_qty, pallet_sales:pallet_sales, box_qty_new:box_qty_new,rack_no:rack_no,dispatch_qty:dispatch_qty,invoice_id:invoice_id ,alldata:alldata,invoice_product_id:invoice_product_id,qty:qty}, 
				success: function(response) {
					
					set_alert_message('Successfully Dispatched',"alert-success","fa-check");
					showinvoiceProduct(invoice_id,invoice_no);
					
					
					
				}
			});
		}
	} 
	
}
function generate_sales_inv(invoice_id)
	{
 		$(".note-error").remove();
		$("#invoice_id").val(invoice_id);
		$("#form_con_gov").modal("show");
	}

	function generate_sales()
	{
		$("#form_con_gov").modal("hide");
		var invoice_id = $("#invoice_id").val();
		var gen_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=gen_sales', '',1);?>");
		$.ajax({			
			url : gen_url,
			type :'post',
			data :{invoice_id:invoice_id},
			success: function(response){
			//	alert(response);
					window.location.href='<?php echo HTTP_SERVER; ?>/admin/index.php?route=government_sales_invoice&mod=add&invoice_id='+response;
				
			},
			
						
		});
	}
	function convert()
    {
    	var id=$("#con_invoice_id").val();
    	var data_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=convert', '',1);?>");
    		$.ajax({
    			url : data_url,
    			method : 'post',
    			data : {id:id},
    			success: function(response){ 
    				window.location.href='<?php echo HTTP_SERVER; ?>/admin/index.php?route=invoice_test&mod=insert_stock&invoice_no='+id+'&inv_status=2';
    			},
    		});
    	
    }
    function send_email_customer(invoice_no,order_user_id,customer_name,country_id,invoice_id,inv_no,email,buyers_orderno,uk_ref_no,tracking_no,courier_name,track_info,send_date,country_name)
    {  
                    if(order_user_id=='7' || order_user_id=='10' || order_user_id=='24'|| order_user_id=='33'|| order_user_id=='44'|| order_user_id=='41' ){
                        var order_no=buyers_orderno;
                    }else{
                        if(uk_ref_no!='')
                            var order_no=uk_ref_no;
                        else
                            var order_no=buyers_orderno;
                    } 
                	var data_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getCCEmails', '',1);?>");
            		$.ajax({
            			url : data_url,
            			method : 'post',
            			data : {order_user_id:order_user_id},
            			success: function(response){ 
            			  //  alert(response);
            			      var msg = $.parseJSON(response);
            				$("#toemail").val(msg.to_email); 
            				$("#ccemail").val(msg.cc_email);
            			},
            		});
            	//	alert(tracking_no);
            	//	alert(track_info);
            	//	alert(courier_name);
                	$("#customer").html('Sending Tracking Mail To :- '+customer_name);
					$("#subject").val(' Invoice No: - '+inv_no+' - '+customer_name+'  ('+country_name+') Order No :'+order_no+' All the tracking Details');
					$("#message").val('<p>Hi, </p> <p>Please find attached all the details .</p><p> Invoice No: -<b> '+inv_no+'</b> - '+customer_name+' <b>'+country_name+'</b> Order No :'+order_no+'  Send via '+courier_name+'  courier on '+send_date+' </p><p>'+track_info+'</p> <br><p><b>Its Tracking No : '+tracking_no+'.</b>');
					$("#toemail").val(email);
					$("#invoice_id_send").val(invoice_id);
					$("#invoice_no_send").val(invoice_no);
					$("#tracking_no_mail").val(tracking_no);
					$("#track_info_mail").val(track_info);
					$("#courier_name_mail").val(courier_name);
					$("#emailform").val(<?php //echo $addedByInfo['email'];?>);<?php //echo $addedByInfo['email'];?>
					CKEDITOR.replace('message', {
						toolbar: [ 
							{ name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'] },
							{ name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv','-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl']},
							{ name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
							{ name: 'colors', items: ['TextColor', 'BGColor'] }]});
					$("#email_div").modal("show");
        
    }
</script>           
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>
