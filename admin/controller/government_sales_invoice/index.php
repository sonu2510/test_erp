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
	$filter_customer_name= $obj_session->data['filter_data']['customer_name'];
	$filter_shipment= $obj_session->data['filter_data']['filter_shipment'];
	$filter_product= $obj_session->data['filter_data']['filter_product'];

	$class = '';	
	$filter_data=array(
		'invoice_no' => $filter_invoice_no,
		'country_id' => $country_id, 
	    'customer_name' => $filter_customer_name,
	    'filter_shipment' => $filter_shipment,
	    'filter_product' => $filter_product,
		'email' => $filter_email,	);
}
if(isset($_GET['sort'])){
	$sort_name = $_GET['sort'];
}else{
	$sort_name='sales_invoice_id'; 
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
	if(isset($_POST['filter_shipment'])){
		$filter_shipment=$_POST['filter_shipment'];		
	}else{
		$filter_shipment='';
	}	
	if(isset($_POST['filter_product'])){
		$filter_product=$_POST['filter_product'];		
	}else{
		$filter_product='';
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
	if(isset($_POST['filter_year'])){
		$filter_year=$_POST['filter_year'];		
	}else{
		$filter_year='';
	}

		
	$filter_data=array(
		'invoice_no' => $filter_invoice_no,
		'country_id' => $country_id,
		'customer_name' => $filter_customer_name,
		'filter_shipment' => $filter_shipment,
		'filter_product' => $filter_product,
		'filter_year' => $filter_year,
	

	);
	//printr($filter_data);
	$obj_session->data['filter_data'] = $filter_data;	
}
if(isset($filter_year)){
		$filter_year=$filter_year;		
	}else{
		 $filter_year = date('Y'); 
	}

if($display_status) {
	//active inactive delete
	if(isset($_POST['action']) && ($_POST['action'] == "active" || $_POST['action'] == "inactive" || $_POST['action'] == "return") && isset($_POST['post']) && !empty($_POST['post']))
	{
		if(!$obj_general->hasPermission('edit',$menuId)){
			$display_status = false;
		} else {
			$status = 0;
			if($_POST['action'] == "active"){
				$status = 1;
			}if($_POST['action'] == "return"){
				$status = 2;
			}
		//	printr($_POST['post']);die;
			$obj_invoice->updateInvoiceStatus($status,$_POST['post']);
			$obj_session->data['success'] = UPDATE;			
			page_redirect($obj_general->link($rout, 'mod=index', '',1));
		}
	}else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
		if(!$obj_general->hasPermission('delete',$menuId)){
			$display_status = false;
		} else {
			//printr($_POST['post']);die;
			$obj_invoice->updateInvoiceStatus(3,$_POST['post']);
			$obj_session->data['success'] = UPDATE;
			page_redirect($obj_general->link($rout, 'mod=index', '',1));
		}
	}
 
 
        	if(isset($_POST['export']) || (isset($_GET['export']) && $_GET['export'] == 1) || (isset($_POST['export']) && $_POST['export'] == 1) ){		
        		$invoice_status = 0;
        		$inv = '&export=1';
        	}
        	elseif(isset($_POST['local']) || (isset($_GET['local'])  && $_GET['local']== 2) || (isset($_POST['local']) && $_POST['local'] == 2)){
        		$invoice_status = 1;
        		$inv = '&local=2';
        	
        	}
        	elseif(isset($_POST['oxygen']) || (isset($_GET['oxygen'])  && $_GET['local']== 2) || (isset($_POST['oxygen']) && $_POST['oxygen'] == 2)){
        		$invoice_status = 3;
        		$inv = '&oxygen=2';
        	
        	}else{
        	    $invoice_status = '2';
        	    $inv ='';
            }
  
  
	$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
	$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
	$addedByInfo = $obj_invoice->getUser($user_id,$user_type_id);
	
	
	if($user_id=='1' && $user_type_id=='1')
	$addedByInfo['user_id']='';
	    
	
	
	//printr($obj_invoice->saveTotalInvoiceAmount('159'));
	//phpinfo();

 if($obj_general->hasPermission('edit',$menuId)==''){ 
      $pdf_status=1;
 }else{
    
     $pdf_status=0;
 }


//$exec = exec("hostname"); //the "hostname" is a valid command in both windows and linux
//$hostname = trim($exec); //remove any spaces before and after
//$ip = gethostbyname($hostname);
//echo $ip."<br>rtkj5wk65kik67o3467gheuighuigh54ikjhnq4" ;die;


//printr($_SERVER['REMOTE_ADDR']);die;

 
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
            	    <?php if($obj_general->hasPermission('add',$menuId) ){ ?>
   						<a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add&invoice_status=2', '',1);?>"><i class="fa fa-plus"></i> New Invoice </a> &nbsp;
                    <?php }if($addedByInfo['country_id']!='111') {?>
                       <a class="label bg-info" href="javascript:void(0);" onclick="csvlink('post[]')"> <i class="fa fa-print"></i> CSV Export</a>
                      <?php /*?> <a class="label bg-inverse" href="<?php echo $obj_general->link($rout, 'mod=import', '',1);?>" > <i class="fa fa-print"></i> CSV Import</a><?php */?>
					<?php } if($obj_general->hasPermission('edit',$menuId)){ ?>
                        <a class="label bg-success" style="margin-left:4px;" onclick="formsubmitsetaction('form_list','active','post[]','<?php echo ACTIVE_WARNING;?>')"><i class="fa fa-check"></i> Active</a>
                        <a class="label bg-warning" onclick="formsubmitsetaction('form_list','inactive','post[]','<?php echo INACTIVE_WARNING;?>')"><i class="fa fa-times"></i> Cancel</a> 
                        <a class="label bg-inverse" onclick="formsubmitsetaction('form_list','return','post[]','<?php echo INACTIVE_WARNING;?>')"><i class="fa fa-times"></i> Return</a>
                     <?php }
					if($obj_general->hasPermission('delete',$menuId)){ ?>       
                        <a class="label bg-danger" onclick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>
                    <?php } ?>                
            		</span>
          		</header>          
          		<div class="panel-body">
            	    <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link($rout, 'mod=index&inv_status=0', '',1); ?>">
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
    			                                <label class="col-lg-5 control-label">Product</label>
                			                    <div class="col-lg-6">
            			                        	  <?php $products = $obj_invoice->getALLProduct();?>
    		            							  <select name="filter_product" id="filter_product" class="form-control " >
                   										<option value="">Select Product</option>
                     						 			<?php foreach($products as $product){?>
    															
    									 							<option value="<?php echo $product['product_id']; ?>" <?php if(isset($filter_product)&& $filter_product==$product['product_id']) echo 'selected="selected"';?>><?php echo $product['product_name']; ?></option>
    								 						<?php } ?>	
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
    		                                    <label class="col-lg-5 control-label">Mode of Shipment</label>
            			                        <div class="col-lg-7">   
                        				          <input type="text" name="filter_shipment" value="<?php echo isset($filter_shipment) ? $filter_shipment : '' ; ?>" placeholder="Mode of Shipment" id="filter_shipment" class="form-control" />
                                    		</div>
                                  		</div>                             
                              		</div>
                              		<div class="col-lg-4">
                              		   <div class="form-group">
                                            <label class="col-lg-5 control-label"><span class="required">*</span>Year</label>
                                            <div class="col-lg-7">
                                                <select name="filter_year" class="form-control validate[required]">
                                                  <option>Select Year</option>
                                                  <?php 
                                                
                                                  for($m=2018;$m<=2025;$m++)
                                                        { ?>
                                                            <option value='<?php echo $m; ?> '<?php if(isset($filter_year)&& $filter_year==$m) echo 'selected="selected"';?>><?php echo $m;?></option>
                                                  <?php }?>
                                                </select>	
                                            </div>
                                          </div>
                                        </div>
                          		</div>
                          	       
			                <footer class="panel-footer <?php echo $class; ?>">
			                   <div class="row">
            			          <div class="col-lg-12">
                        			<button type="submit" class="btn btn-primary btn-sm pull-right ml5" name="btn_filter"><i class="fa fa-search"></i> Search</button>
			                         <a href="<?php echo $obj_general->link($rout, 'mod=index&inv_status=0', '',1); ?>" class="btn btn-info btn-sm pull-right" ><i class="fa fa-refresh"></i> Refresh</a>
            			          </div> 
                   				</div>
			                </footer>                                  
            		  </section>
          			</form> 
          			  <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link($rout, 'mod=index', '',1); ?>">
                       <div class=" pull-left">
                       	 	<div class="panel-body text-muted l-h-2x">
                           
                              <?php if($obj_general->hasPermission('edit',$menuId)){ ?>  
                              <button  type="submit" class="btn btn-primary btn-sm pull-right ml5" name="oxygen" style="background-color:#1b1b1b"><i></i> Oxygen Absorbers</button>
                               
                             <button  type="submit" class="btn btn-primary btn-sm pull-right ml5" name="export" style="background-color:#CBC6AB"><i></i> Export</button>
                         
                             <button  type="submit" class="btn btn-primary btn-sm pull-right ml5" name="local" style="background-color:#81C267"><i></i> Domestic</button>
                           
                            
                            
                             
                          
                                    <a href="<?php echo $obj_general->link($rout, 'mod=search', '',1); ?>" class="btn  btn-sm" target='_blank' > Searching For Customers</a>
                                    <a href="<?php echo $obj_general->link($rout, 'mod=view_proforma', '',1); ?>" class="btn  btn-sm" target='_self'  style="background-color:#EAA7A7" >View Proforma invoice</a>
                              <?php }?>
                            </div>
                       </div> 
                       
                       </form>
		            <div class="row">
        		        <div class="col-lg-3 pull-right">	
                		    <select class="form-control" id="limit-dropdown" onchange="location=this.value;">
			                     <option value="<?php echo $obj_general->link($rout, 'mod=index&inv_status=0', '',1);?>" selected="selected">--Select--</option>
            		       	<?php $limit_array = getLimit(); 
									foreach($limit_array as $display_limit) {
										if($limit == $display_limit) {	 ?>
                        	           		<option value="<?php echo $obj_general->link($rout, 'mod=index&limit='.$display_limit.'&inv_status=0', '',1);?>" selected="selected"><?php echo $display_limit; ?></option>				
									<?php } else { ?>
                            				<option value="<?php echo $obj_general->link($rout, 'mod=index&limit='.$display_limit.'&inv_status=0', '',1);?>"><?php echo $display_limit; ?></option>
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
                		      <th>Final Destination</th>
                		      <th>Mode of Shipment</th>
                		      <?php if($obj_general->hasPermission('edit',$menuId)){ ?>
                		          <th>Action</th>
                          		  <th>Status</th>
                          		  
		                      <?php }?>
		                        <th>Posted By</th>
		                        <?php 	if($invoice_status!='0')
		                        
		                                echo '<th>Owner Of The Invoice</th>';
		                       ?>
		                        
    		                  </tr>
                		  </thead> 
	                 	  <tbody>
                  			<?php $total = $obj_invoice->getTotalInvoice($obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS'],$filter_data,0,$addedByInfo['user_id'],$invoice_status);
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
								   'limit' => $limit,
								   
							  );	
                     		  $invoices = $obj_invoice->getInvoice($obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS'],$option,$filter_data,0,$addedByInfo['user_id'],$invoice_status);
			//	printr( $invoices);	 		
                                  if($total)
							{
                      			foreach($invoices as $invoice){ 
                      			  //  printr( $invoice);	 
							
							
							
							            	
						
							            if($invoice['status']==0)
							                $style='style="background-color:#FA8072"';
							            else if($invoice['status']==2)
								             $style='style="background-color:#FADD8E"';
								        else
								             $style='';
								 
								?> 
                       				<tr  <?php echo $style;?> >                        
                          				<td><input type="checkbox" name="post[]" value="<?php echo $invoice['sales_invoice_id'];?>"></td>
                          				<td> <a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_no='.encode($invoice['sales_invoice_id']).'&pdf_status='.$pdf_status,'',1); ?>" > 
                                 <?php  
                                        
                                     $dt = strtotime($invoice['invoice_date']);
					                    
                    					 if(date("m",$dt)>=4)
                    					   $dt =(date("y",$dt)).'-'.(date("y",$dt)+1);
                    					 else
                    					   $dt =(date("y",$dt)-1).'-'.(date("y",$dt));       
                                 
                                 
                                        $time_s=new DateTime($invoice['date_added']);
                                        $t=$time_s->format('H:i:s');
                                        if($t!='00:00:00'){
                                          $time= '/&nbsp;'.$t;
                                        }else{
                                           $time='';
                                        }
                                       	if($user_id=='1' && $user_type_id=='1'){
                                          
                                           //printr($time_s->format('H:i:s')); 
                                          // printr($time);
                                        }
                                 		 echo $invoice['invoice_no'].'&nbsp;/&nbsp;'.$dt.'&nbsp;/&nbsp;'.$invoice['invoice_date'].''.$time;?>
                                 		<br><small>Invoice Amount : <?php echo round($invoice['invoice_total_amount'])?></small></a></td>
                               
                                            
                                      <td>
                                          <a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_no='.encode($invoice['sales_invoice_id']).'&pdf_status='.$pdf_status,'',1); ?>" >
                                      
                                         <?php   echo $invoice['customer_name'];?></a>
                                      </td>
                                    
                                      <?php if(is_numeric($invoice['country_id']))
                                          $invoice_sea = $obj_invoice->getCountryName($invoice['country_id']);
										  	else
												$invoice_sea['country_name']=$invoice['country_id'];
                                          ?>
                                          </td>
                                      <td><a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_no='.encode($invoice['sales_invoice_id']).'&pdf_status='.$pdf_status,'',1); ?>" ><?php echo $invoice_sea['country_name']; ?></a></td>
                                      <td><a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_no='.encode($invoice['sales_invoice_id']).'&pdf_status='.$pdf_status,'',1); ?>" ><?php echo $invoice['transport']; ?></a></td>
                                      
                                     
                                      
                                       <?php if($obj_general->hasPermission('edit',$menuId)){ ?> 	
                                      <td><div data-toggle="buttons" class="btn-group">
                                            <label class="btn btn-xs btn-success <?php echo ($invoice['status']==1) ? 'active' : '';?> ">
                                                 <input type="radio" name="status" value="1" id="<?php echo $invoice['sales_invoice_id']; ?>"> <i class="fa fa-check text-active"></i>Active</label>                                   
                                            <label class="btn btn-xs btn-danger <?php echo ($invoice['status']==0) ? 'active' : '';?> ">
                                                 <input type="radio"  name="status" value="0" id="<?php echo $invoice['sales_invoice_id']; ?>"> <i class="fa fa-check text-active"></i>Cancel</label> 
                                             <label class="btn btn-xs  btn-inverse <?php echo ($invoice['status']==2) ? 'active' : '';?> ">
                                                 <input type="radio"  name="status" value="2" id="<?php echo $invoice['sales_invoice_id']; ?>"> <i class="fa fa-check text-active"></i>Return</label> 
                                        </div></td>
                                       
                                          	<td>
                                                <a href="<?php echo $obj_general->link($rout, 'mod=add&invoice_id='.encode($invoice['sales_invoice_id']),'',1); ?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                                              </td>
                                                <?php } ?>
                          			  <td><?php 
									  		
													$addedByData = $obj_invoice->getUser($invoice['user_id'],$invoice['user_type_id']);								
													$addedByImage = $obj_general->getUserProfileImage($invoice['user_type_id'],$invoice['user_id'],'100_');
											
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
                                     <?php 
									  			if($invoice_status=='0')
												{ ?><td></td><?php 
												}
												else
												{
													$addedByData = $obj_invoice->getUser($invoice['added_user_id'],$invoice['added_user_type_id']);								
													$addedByImage = $obj_general->getUserProfileImage($invoice['added_user_id'],$invoice['added_user_type_id'],'100_');
											
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
                                              
                                              <td>  <a class="btn btn-default btn-xs" data-trigger="hover" data-toggle="popover" data-html="true" data-placement="top" data-content='<?php echo $addedByInfo;?>' title="" data-original-title="<b><?php echo $addedByName;?></b>"><?php echo $addedByData['user_name'];?></a>  
                                      
                                                  <?php if($addedByData['user_id']=='39' && $invoice['dis_status']!=0)
                                                        {
                                                            ?> <a name="dispatch" id="dispatch" onclick="dispatch(<?php echo $invoice['sales_invoice_id'];?>)" class="btn btn-xs bg-primary">Dispatch</a>
                                                  <?php } ?>
                                            </td>
                                            <?php 
                                      }?>
                                    
                       			
                               
                               	 </tr>
                        		<?php }
								}
								//pagination
								$pagination = new Pagination();
								$pagination->total = $total;
								$pagination->page = $page;
								$pagination->limit = $limit;
								$pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
								$pagination->url = $obj_general->link($rout, '&page={page}&limit='.$limit.'&filter_edit=1'.$inv, '',1);
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


<!-- [sonu] 19/06/2018-->

 <div class="modal fade" id="form_con" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
<!-- [sonu] -->


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
<script type="application/javascript">
$(document).ready(function() {
	//final_pdf('117');
	
	
	
});

	$('input[name=status]').change(function() {
	//alert("change");
		var invoice_no=$(this).attr('id');
		var status_value = this.value;
		var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=updateInvoice', '',1);?>");
       //alert(status_value);
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
function generate_sales_inv(invoice_id)
	{
 		$(".note-error").remove();
		$("#invoice_id").val(invoice_id);
		$("#form_con").modal("show");
	}

	function generate_sales()
	{
		$("#form_con").modal("hide");
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
	function dispatch(invoice_id)
	{
	   var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=dispatch', '',1);?>");
		$.ajax({			
			url : url,
			type :'post',
			data :{invoice_id:invoice_id},
			success: function(response){
		
				//	window.location.href='<?php echo HTTP_SERVER; ?>/admin/index.php?route=government_sales_invoice&mod=add&invoice_id='+response;
				location.reload();
				
			},
			
						
		});
	}
</script>           
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>
