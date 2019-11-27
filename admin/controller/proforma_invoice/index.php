<?php

//jayashree
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

$limit = LISTING_LIMIT;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}

if(!$obj_general->hasPermission('view',$menuId)){
	$display_status = false;
}

$class = 'collapse';
$filter_data= array();
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

if(isset($_POST['inactive'])){
		$filter_status = 0;
		$filter_proforma_status = 0;
	}
	elseif(isset($_POST['notsave'])){
		$filter_status = '';
		$filter_proforma_status = 1;
	}
	else{
		$filter_status = 1;
		$filter_proforma_status = 0;		
	}
if(isset($obj_session->data['filter_data'])){
	$filter_customer_name = $obj_session->data['filter_data']['customer_name'];
	$filter_invoice_number = $obj_session->data['filter_data']['invoice_number'];
	$filter_email = $obj_session->data['filter_data']['email'];
	$filter_postedby = $obj_session->data['filter_data']['postedby'];
	//$filter_product_code = $obj_session->data['filter_data']['product_code'];
	$filter_data=array(
		'customer_name' => $filter_customer_name,
		'invoice_number' => $filter_invoice_number,
		'email' => $filter_email,
		'postedby' => $filter_postedby,
		//'product_code' => $filter_product_code,	
	);
}
if(isset($_POST['btn_filter'])){
	
	$class = '';
	$filter_edit = 1;
	$class ='';	
	if(isset($_POST['filter_customer_name'])){
		$filter_customer_name=$_POST['filter_customer_name'];		
	}else{
		$filter_customer_name='';
	}
	if(isset($_POST['filter_invoice_number'])){
		$filter_invoice_number=$_POST['filter_invoice_number'];		
	}else{
		$filter_invoice_number='';
	}
	if(isset($_POST['filter_email'])){
		$filter_email=$_POST['filter_email'];		
	}else{
		$filter_email='';
	}
	if(isset($_POST['filter_user_name']))
	{
		$filter_user_name = $_POST['filter_user_name'];
	}else{
		$filter_user_name='';
	}
	/*if(isset($_POST['filter_product_code']))
	{
		$filter_product_code = $_POST['filter_product_code'];
	}else{
		$filter_product_code='';
	}*/
	$filter_data=array(
		'customer_name' => $filter_customer_name,
		'invoice_number' => $filter_invoice_number,
		'email' => $filter_email,
		'postedby' =>$filter_user_name,
		//'product_code' =>$filter_product_code
	);
	
	$obj_session->data['filter_data'] = $filter_data;

} 

if(isset($_GET['page'])){
	if(isset($_SESSION['filter_data']) && !empty($_SESSION['filter_data'])) {
	$filter_data = ($_SESSION['filter_data']);
	}
}

if(isset($_GET['sort'])){
	$sort = $_GET['sort'];	
}else{
	$sort= 'proforma_id';
}

if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'DESC';	
}


//[kinjal] (13-4-2017) for get address_book_id wise data
$add_book_id='0';
$add_url='';
if(isset($_GET['address_book_id']))
{
		$add_book_id = decode($_GET['address_book_id']);
		$add_url = '&address_book_id='.$_GET['address_book_id'];
}

//active inactive delete
 if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
	
		$obj_pro_invoice->updateProformaStatus(2,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, 'mod=index&is_delete=0'.$add_url, '',1));
	
}
$status = '';
if($obj_session->data['LOGIN_USER_TYPE']==1 && $obj_session->data['ADMIN_LOGIN_SWISS']==1) {
	if(isset($_POST['inactive']) || (isset($_GET['status']) && $_GET['status'] == 1) || (isset($_POST['status']) && $_POST['status'] == 1) ){		
		$proforma_status = 0;
		$product_status = 0;
		$status = '1';
	}
	elseif(isset($_POST['notsave']) || (isset($_GET['status'])  && $_GET['status']== 2) || (isset($_POST['status']) && $_POST['status'] == 2)){
		$proforma_status = 1;
		$product_status = '';
		$status = '2';
	}
	else{
		$proforma_status = 0;
		$product_status = 1;
		$status = '0';
	}
}
else
{
	$proforma_status = 0;
	$product_status = 1;	
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
          	<span class="text-muted m-l-small pull-right">
          	   <?php 
			   if(isset($_GET['is_delete']) && $_GET['is_delete']=='1')
			   { ?>
			   		<a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=index&is_delete=0'.$add_url, '',1);?>"><i class="fa fa-mail-reply"></i> Back</a>
			   
			  <?php
			   }
			   else
			   {
			   	if($obj_general->hasPermission('add',$menuId)){ ?>
          		 <a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add&is_delete='.$_GET['is_delete'], '',1);?>"><i class="fa fa-plus"></i> New Proforma</a>
                    <?php } if($obj_general->hasPermission('delete',$menuId)){ ?>
                      <a class="label bg-danger" onclick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>
                      <a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=index&is_delete=1'.$add_url, '',1);?>"><i class="fa fa-eye"></i> View Deleted Proforma</a>
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
                      <li> <a href="#" class="panel-toggle text-muted active"><i class="fa fa-caret-down fa-lg text-active"></i><i class="fa fa-caret-up fa-lg text"></i></a> </li>
                    </ul>
                    <i class="fa fa-search"></i> Search
                  </header>
              
              <?php 
			  		if(isset($filter_user_name))
					{
			   			$splitdata=explode("=",$filter_user_name);
			  		 	//printr($splitdata[0]);
					 }
			  ?>
			 
               <div class="panel-body clearfix <?php echo $class; ?>">        
                      <div class="row">
                      	<div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-5 control-label">Proforma Invoice Number</label>
                                <div class="col-lg-7">   
                                 <input type="text" name="filter_invoice_number" value="<?php echo isset($filter_invoice_number) ? $filter_invoice_number : '' ; ?>" placeholder="Proforma Invoice Number" id="filter_invoice_number" class="form-control" />
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
									$splitdata=array();
                			  		if(isset($filter_user_name))
                					{
                			   			$splitdata=explode("=",$filter_user_name);
                			  		 	//printr($splitdata[0]);
                					 }
									$userlist = $obj_pro_invoice->getUserList();
								?>
                                <div class="col-lg-7">
                                	<select class="form-control" name="filter_user_name">
                                    	<option value="">Please Select</option>
                                    	<?php foreach($userlist as $user) { ?>
                                        	<?php  if(!empty($splitdata) && $splitdata[0] == $user['user_type_id'] && $splitdata[1] ==$user['user_id']) { ?>
                                            
                                    			<option value="<?php echo $user['user_type_id']."=".$user['user_id']; ?>" selected="selected"><?php echo $user['user_name']; ?></option>
                                            <?php } else { ?>
                                            	<option value="<?php echo $user['user_type_id']."=".$user['user_id']; ?>"><?php echo $user['user_name']; ?></option>
                                            <?php } ?>
                                        <?php } ?>                                       
                                    </select>
                                </div>
                              </div>                              
                          </div>
                          <!--<div class="col-lg-4">
                              
                                <label class="col-lg-5 control-label">Product Code</label>
                                <?php //$productcodes = $obj_pro_invoice->getActiveProductCode();?>
                                <div class="col-lg-4">
                                    <select name="filter_product_code" class="form-control" id="chosen_data">
                                        <option value="">Select Product</option> 
                                        <?php  /*foreach($productcodes as $code)
                                           {
                                               if($code['product_code_id']==$filter_product_code)
                                                  echo '<option value="'.$code['product_code_id'].'" selected="selected">'.$code['product_code'].'</option>';
                                               else
                                                    echo '<option value="'.$code['product_code_id'].'">'.$code['product_code'].'</option>';
                                           }*/
                                           ?>
                                    </select>
                                </div>                        
                          
                          </div>-->
                       </div>                     
                 </div>
            
                  <footer class="panel-footer clearfix <?php echo $class; ?>">
                    <div class="row">
                       <div class="col-lg-12">
                       <input type="hidden" value="<?php echo $status;?>" id="status" name="status" />
                        <button type="submit" class="btn btn-primary btn-sm pull-right ml5" name="btn_filter"><i class="fa fa-search"></i> Search</button>
                        <a href="<?php echo $obj_general->link($rout, '&status='.$status.'&is_delete='.$_GET['is_delete'], '',1); ?>" class="btn btn-info btn-sm pull-right" ><i class="fa fa-refresh"></i> Refresh</a>
                       </div> 
                    </div>
                  </footer>                                  
              </section>
         	</form>
            
            <?php if($obj_session->data['LOGIN_USER_TYPE']==1 && $obj_session->data['ADMIN_LOGIN_SWISS']==1) { ?>
            <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link($rout, 'mod=index&is_delete='.$_GET['is_delete'].$add_url, '',1); ?>">
           <div class=" pull-left">
           	 	<div class="panel-body text-muted l-h-2x">
               
                 <button  type="submit" class="btn btn-primary btn-sm pull-right ml5" name="notsave" style="background-color:#EAA7A7"><i ></i> Not Save</button> 
                 <button  type="submit" class="btn btn-primary btn-sm pull-right ml5" name="inactive" style="background-color:#CBC6AB"><i></i> Inactive</button>
                 <button  type="submit" class="btn btn-primary btn-sm pull-right ml5" name="active" style="background-color:#81C267"><i></i> Active</button>
                </div>
           </div> 
           <?php }?>
           </form>
           
            <div class="row">
             <div class="col-lg-3 pull-right">	
                 <select class="form-control" id="limit-dropdown" onchange="location=this.value;">
                    <option value="<?php echo $obj_general->link($rout, 'mod=index&is_delete='.$_GET['is_delete'].$add_url, '',1);?>" selected="selected">--Select--</option>

                    	<?php 
							$limit_array = getLimit(); 
							foreach($limit_array as $display_limit) {
								if($limit == $display_limit) {	 
						?>
                        		<option value="<?php echo $obj_general->link($rout, 'limit='.$display_limit.'&status='.$status.'&is_delete='.$_GET['is_delete'].$add_url, '',1);?>" selected="selected"><?php echo $display_limit; ?></option>				
						<?php } else { ?>
                            	<option value="<?php echo $obj_general->link($rout, 'limit='.$display_limit.'&status='.$status.'&is_delete='.$_GET['is_delete'].$add_url, '',1);?>"><?php echo $display_limit; ?></option>
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
                <table class="table b-t text-small table-hover">
                  <thead>
                    <tr>
                      <th width="20"><input type="checkbox"></th>
                      <?php // mansi 19-1-2016 (for shorting) ?>
                   
                     <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">Proforma Invoice Number
                     	<span class="th-sort">
                            	<a href="<?php  echo $obj_general->link($rout, 'sort=date_added'.'&order=ASC'.'&is_delete='.$_GET['is_delete'].$add_url, '',1);?>">
                                	<i class="fa fa-sort-down text"></i>
                                    
                                <a href="<?php echo $obj_general->link($rout, 'sort=date_added'.'&order=DESC'.'&is_delete='.$_GET['is_delete'].$add_url, '',1);?>">
                                <i class="fa fa-sort-up text-active"></i>
                            <i class="fa fa-sort"></i></span>
                     </th>
                     
                     <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">Buyers Order No 
                     	<span class="th-sort">
                            	<a href="<?php echo $obj_general->link($rout, 'sort=buyers_order_no'.'&order=ASC'.'&is_delete='.$_GET['is_delete'].$add_url, '',1);?>">
                                	<i class="fa fa-sort-down text"></i>
                                    
                                <a href="<?php echo $obj_general->link($rout, 'sort=buyers_order_no'.'&order=DESC'.'&is_delete='.$_GET['is_delete'].$add_url, '',1);?>">
                                <i class="fa fa-sort-up text-active"></i>
                            <i class="fa fa-sort"></i></span>
                     </th>
                     <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">Invoice Date
                      	<span class="th-sort">
                            	<a href="<?php  echo $obj_general->link($rout, 'sort=invoice_date'.'&order=ASC'.'&is_delete='.$_GET['is_delete'].$add_url, '',1);?>">
                                	<i class="fa fa-sort-down text"></i>
                                    
                                <a href="<?php  echo $obj_general->link($rout, 'sort=invoice_date'.'&order=DESC'.'&is_delete='.$_GET['is_delete'].$add_url, '',1);?>">
                                <i class="fa fa-sort-up text-active"></i>
                            <i class="fa fa-sort"></i></span>
                     </th>
                      <th>Final Destination</th>
                      <th>Customer Name</th>
                      <th>Email</th>
                       <th>Invoice Total Amount</th>
                      <th>Posted By</th>
                      <?php if($status != 2) { ?>                      
                      <th>Status</th>
                      <?php }
						if(isset($_GET['is_delete']) && $_GET['is_delete']!='1')
						 { ?>
                      <th>Action</th>
                      <?php } ?>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php	
				  $total_pouch = $obj_pro_invoice->getTotalInvoice($filter_data, $product_status, $proforma_status,$obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],$_GET['is_delete'],$add_book_id);
				  $pagination_data = '';
				  
				  if($total_pouch) {
					  if (isset($_GET['page'])) {
                            $page = (int)$_GET['page'];
                        } else {
                            $page = 1;
                        }
						$option = array(
                          'sort'  => $sort,
                          'order' => $sort_order,
                          'start' => ($page - 1) * $limit,
                          'limit' => $limit,
						  'product_status' => $product_status,
						  'proforma_status' => $proforma_status
					);

				$proformas = $obj_pro_invoice->getInvoices($option, $filter_data, $product_status, $proforma_status,$obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],$_GET['is_delete'],$add_book_id);
				//printr($proformas);
				//printr($_SESSION);
				  if(isset($total_pouch)) {
                  foreach($proformas as $proforma) {					  
					  $userInfo = $obj_pro_invoice->getUser($proforma['added_by_user_id'], $proforma['added_by_user_type_id']);
					  
					  //[kinjal] : on [24-8-2016] make this con. only for gopidiii. 
					  if($proforma['added_by_user_id']=='34' && $proforma['added_by_user_type_id']=='2')
					  {
						  if(($_SESSION['ADMIN_LOGIN_SWISS']=='6' && $_SESSION['LOGIN_USER_TYPE']=='4') || ($_SESSION['ADMIN_LOGIN_SWISS']=='34' && $_SESSION['LOGIN_USER_TYPE']=='2') || ( $_SESSION['ADMIN_LOGIN_SWISS']=='1' && $_SESSION['LOGIN_USER_TYPE']=='1'))
						  {
						  	$view_link = $obj_general->link($rout, 'mod=view&proforma_id='.encode($proforma['proforma_id']).'&is_delete='.$_GET['is_delete'].$add_url,'',1);
							$edit_link = $obj_general->link($rout, 'mod=add&proforma_id='.encode($proforma['proforma_id']).'&is_delete='.$_GET['is_delete'].$add_url,'',1);
							$email_add =  $proforma['email'];
							
						  }
						  else
						  {
						  	$view_link = $edit_link = $email_add = '';
							
						  }
					  }
					  else
					  {
						  $view_link = $obj_general->link($rout, 'mod=view&proforma_id='.encode($proforma['proforma_id']).'&is_delete='.$_GET['is_delete'].$add_url,'',1);
						  $edit_link = $obj_general->link($rout, 'mod=add&proforma_id='.encode($proforma['proforma_id']).'&is_delete='.$_GET['is_delete'].$add_url,'',1);
						  $email_add =  $proforma['email'];
					  }
						
					
					
					if($proforma['proforma_status']==1){ //for not saved?>
                        <tr id="proforma-row-<?php echo $proforma['proforma_id']; ?>" style="background-color:#f2dede"> 
                        <?php } else { ?> 
                        <tr id="proforma-row-<?php echo $proforma['proforma_id']; ?>" <?php echo ($proforma['status']==0) ? 'style="background-color:#fcf8e3" ' : '' ; ?>> <?php } ?>                       
                          <td><input type="checkbox" name="post[]" value="<?php echo $proforma['proforma_id'];?>"></td>
                          <td><a href="<?php echo $view_link; ?>"  name="btn_edit" ><?php echo $proforma['pro_in_no']; ?></a></td>
                          <td><a href="<?php echo $view_link; ?>"  name="btn_edit" ><?php echo $proforma['buyers_order_no'];?></a> </td>
                          <td><a href="<?php echo $view_link; ?>"  name="btn_edit" ><?php echo dateFormat(4,$proforma['invoice_date']);?></a></td>
						  <td><a href="<?php echo $view_link; ?>"  name="btn_edit" ><?php echo $proforma['country_name']; ?></a></td>
                          <td><a href="<?php echo $view_link; ?>"  name="btn_edit"><?php echo $proforma['customer_name']; ?></a></td>
                          <td><a href="<?php echo $view_link; ?>"  name="btn_edit" ><?php echo $email_add; ?></a></td>
                           <td><a href="<?php echo $view_link; ?>"  name="btn_edit" ><?php echo round($proforma['invoice_total']); ?></a></td>
                          <td>
                          <?php	
						  								
								$addedByImage = $obj_general->getUserProfileImage($proforma['added_by_user_type_id'],$proforma['added_by_user_id'],'100_');
								$addedByInfo = '';
								$addedByInfo .= '<div class="row">';
									$addedByInfo .= '<div class="col-lg-3"><img src="'.$addedByImage.'"></div>';
									$addedByInfo .= '<div class="col-lg-9">';
									if($userInfo['city']){ $addedByInfo .= $userInfo['city'].', '; }
									if($userInfo['state']){ $addedByInfo .= $userInfo['state'].' '; }
									if(isset($userInfo['postcode'])){ $addedByInfo .= $userInfo['postcode']; }
									$addedByInfo .= '<br>Telephone : '.$userInfo['telephone'].'</div>';
								$addedByInfo .= '</div>';
								$addedByName = $userInfo['first_name'].' '.$userInfo['last_name'];
								str_replace("'","\'",$addedByName);
							?>
								<a class="btn btn-info btn-xs" data-trigger="hover" data-toggle="popover" data-html="true" data-placement="top" data-content='<?php echo $addedByInfo;?>' title="" data-original-title="<b><?php echo $addedByName;?></b>"><?php echo $userInfo['user_name'];?></a>
                          
                          </td>
                          <?php if($status != 2) { ?>
                          <td>
                          <div data-toggle="buttons" class="btn-group">
                                <label class="btn btn-xs btn-success <?php echo ($proforma['status']==1) ? 'active' : '';?> "> <input type="radio" 
                                 name="status" value="1" id="<?php echo $proforma['proforma_id']; ?>"> <i class="fa fa-check text-active"></i>Active</label>                                   
                                <label class="btn btn-xs btn-danger <?php echo ($proforma['status']==0) ? 'active' : '';?> "> <input type="radio" 
                                    name="status" value="0" id="<?php echo $proforma['proforma_id']; ?>"> <i class="fa fa-check text-active"></i>Inactive</label> 
                            </div>
                           </td>
                          <?php }?>
                          <?php 
						   if(isset($_GET['is_delete']) && $_GET['is_delete']!='1')
						   { ?>
                              <td>
                              <?php 
							  if($obj_general->hasPermission('edit',$menuId)){ ?> 		
												<a href="<?php echo $edit_link; ?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
										<?php
										}
									?>
                               </td>
                          <?php }  /*?> <td><a href="<?php echo $obj_general->link($rout, 'mod=view&proforma_id='.encode($proforma['proforma_id']),'',1); ?>"  name="btn_edit" class="btn btn-info btn-xs">View Invoice</a></td><?php */?>
                           	<?php if($_GET['is_delete']!=1) { ?>
                           <td>
                              <a class="btn btn-primary btn-sm" target="_blank" href="<?php echo $obj_general->link('sales_invoice', '&mod=add&proforma_id='.encode($proforma['proforma_id']).'&is_delete='.$_GET['is_delete'], '',1);?>">Generate Invoice</a>
                           </td>
                           <?php } ?>
                        </tr>
                      <?php // background:#FFC800;
					 }
				  } else {
						  echo '<tr><td colspan="4">No Record Found</td></tr>';
                  } 
				  //pagination
                        $pagination = new Pagination();
						$pagination->total = $total_pouch;
                        $pagination->page = $page;
                        $pagination->limit = $limit;
                        $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                        $pagination->url = $obj_general->link($rout,'&page={page}&limit='.$limit.'&status='.$status.'&filter_edit=1'.'&is_delete='.$_GET['is_delete'].$add_url, '',1);
                        $pagination_data = $pagination->render();
                       } else{ 
                      echo "<tr><td colspan='5'>No record found !</td></tr>";
                  }
                  ?>
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
<script src="https://harvesthq.github.io/chosen/chosen.jquery.js" type="text/javascript"></script>
<link rel="stylesheet" href="https://harvesthq.github.io/chosen/chosen.css" type="text/css"/> 
<style>
    .chosen-container.chosen-container-single {
    width: 300px !important; /* or any value that fits your needs */
}
</style>
<script>

jQuery(document).ready(function(){
  $("#chosen_data").chosen();
});
	$('input[name=status]').change(function() {
		var proforma_id=$(this).attr('id');
		var status_value = this.value;
		var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=updateProStatus', '',1);?>");
       	$.ajax({			
			url : status_url,
			type :'post',
			data :{proforma_id:proforma_id,status_value:status_value},
			success: function(){
				set_alert_message('Successfully Updated',"alert-success","fa-check");
				var status = $("#status").val();
				var current_url = $(location).attr('href');
				var url = current_url+'&status='+status;
				window.location.href = url;
			},
			error:function(){
				set_alert_message('Error During Updation',"alert-warning","fa-warning");  
			}
						
		});
    });
</script>           
