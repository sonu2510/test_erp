<?php
//[kinjal] -->
include("mode_setting.php");

$bradcums = array();
$bradcums[] = array(
	'text' 	=> 'Dashboard',
	'href' 	=> $obj_general->link('dashboard', '', '',1),
	'icon' 	=> 'fa-home',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> 'Proforma Invoice List',
	'href' 	=> '',
	'icon' 	=> 'fa-list',
	'class'	=> 'active',
);

//$limit = LISTING_LIMIT;
$limit = 20;
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
	$filter_contact_no = $obj_session->data['filter_data']['contact_no'];
	$filter_invoice_amount = $obj_session->data['filter_data']['invoice_amount'];
	$filter_postedby = $obj_session->data['filter_data']['postedby'];
	$filter_product_code = $obj_session->data['filter_data']['product_code'];
	$filter_buyers_no = $obj_session->data['filter_data']['buyers_no'];
	
	$filter_data=array(
		'customer_name' => $filter_customer_name,
		'invoice_number' => $filter_invoice_number,
		'contact_no' => $filter_contact_no,
		'invoice_amount' => $filter_invoice_amount,
		'email' => $filter_email,
		'postedby' => $filter_postedby,
		'buyers_no' => $filter_buyers_no,	
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
	if(isset($_POST['filter_invoice_amount']))
	{
		$filter_invoice_amount = $_POST['filter_invoice_amount'];
	}else{
		$filter_invoice_amount='';
	}if(isset($_POST['filter_contact_no']))
	{
		$filter_contact_no = $_POST['filter_contact_no'];
	}else{
		$filter_contact_no='';
	}
	if(isset($_POST['filter_product_code']))
	{
		$filter_product_code = $_POST['filter_product_code'];
	}else{
		$filter_product_code='';
	}
	if(isset($_POST['filter_buyers_no']))
	{
		$filter_buyers_no = $_POST['filter_buyers_no'];
	}else{
		$filter_buyers_no='';
	}
	$filter_data=array(
		'customer_name' => $filter_customer_name,
		'invoice_number' => $filter_invoice_number,
		'invoice_amount' => $filter_invoice_amount,
		'contact_no' => $filter_contact_no,
		'email' => $filter_email,
		'postedby' =>$filter_user_name,
		'product_code' =>$filter_product_code,
		'buyers_no'=>$filter_buyers_no,
	);
	
	$obj_session->data['filter_data'] = $filter_data;
} 
if(isset($_GET['page'])){
	if(isset($_SESSION['filter_data']) && !empty($_SESSION['filter_data'])) {
	$filter_data = ($_SESSION['filter_data']);
	}
}
//[sonu] (18-4-2017) for get address_book_id wise data
$add_book_id='0';
$add_url='';
if(isset($_GET['address_book_id']))
{
		$add_book_id = decode($_GET['address_book_id']);
		$add_url = '&address_book_id='.$_GET['address_book_id'];
}
if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'DESC';	
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
//sonu add payment 18-7-2017
if(isset($_POST['btn_payment']))
{
	$post = post($_POST);	
	$update_id = $obj_pro_invoice->InsertPayment_detail($post,ADMIN_EMAIL);	
	$obj_session->data['success'] = ADD;
    page_redirect($obj_general->link($rout, 'mod=index&is_delete=0'.$add_url, '', 1));
}
$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
$addedByInfo_login = $obj_pro_invoice->getUser($user_id,$user_type_id);


$permission = '0';
if($obj_general->hasPermission('edit','287'))
{
    $permission = '1';
}
?>
<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-list"></i> Proforma Invoice</h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>   
        
      <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading bg-white"> 
       		<span>Proforma Invoice Listing</span>
          
          </header>
          
          <div class="panel-body">
              <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link($rout, 'mod=view_proforma&is_delete='.$_GET['is_delete'].$add_url, '',1); ?>">
                <section class="panel pos-rlt clearfix">
                  <header class="panel-heading">
                    <ul class="nav nav-pills pull-right">
                      <li> <a href="#" class="panel-toggle text-muted active"><i class="fa fa-caret-down fa-lg text-active"></i><i class="fa fa-caret-up fa-lg text"></i></a> </li>
                    </ul>
                    <i class="fa fa-search"></i> Search
                  </header>
              
              <?php  $splitdata=array();
			  		if(isset($filter_user_name))
					{
			   			$splitdata=explode("=",$filter_user_name);
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
                                <div id="ajax_return"></div>
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
									$userlist = $obj_pro_invoice->getUserList($permission);
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
                          <div class="col-lg-4">
                              <!--<div class="form-group">-->
                                <label class="col-lg-5 control-label">Product Code</label>
                                <?php $productcodes = $obj_pro_invoice->getActiveProductCode();?>
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
                          <!--</div>-->
                          </div>
                          <div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-5 control-label">Buyers order No</label>
                                <div class="col-lg-7">   
                                 <input type="text" name="filter_buyers_no" value="<?php echo isset($filter_buyers_no) ? $filter_buyers_no : '' ; ?>" placeholder="Buyers order No" id="filter_buyers_no" class="form-control" />
                                </div>
                              </div>                             
                          </div>
                       </div>
                             <div class="row">
                      	<div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-5 control-label"> Invoice Amount</label>
                                <div class="col-lg-7">   
                                 <input type="text" name="filter_invoice_amount" value="<?php echo isset($filter_invoice_amount) ? $filter_invoice_amount : '' ; ?>" placeholder="Invoice Amount" id="filter_invoice_amount" class="form-control" />
                                </div>
                              </div>                             
                          </div>
                          <div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-5 control-label">Contact Number</label>
                                <div class="col-lg-7">   
                                 <input type="text" name="filter_contact_no" value="<?php echo isset($filter_contact_no) ? $filter_contact_no : '' ; ?>" placeholder="Contact Number" id="filter_contact_no" class="form-control" />
                                </div>
                              </div>                             
                          </div>
                          </div> 
                 </div>
            
                  <footer class="panel-footer <?php echo $class; ?>">
                    <div class="row">
                       <div class="col-lg-12">
                       <input type="hidden" value="<?php echo $status;?>" id="status" name="status" />
                        <button type="submit" class="btn btn-primary btn-sm pull-right ml5" name="btn_filter"><i class="fa fa-search"></i> Search</button>
                        <a href="<?php echo $obj_general->link($rout, '&status='.$status.'&is_delete='.$_GET['is_delete'].$add_url, '',1); ?>" class="btn btn-info btn-sm pull-right" ><i class="fa fa-refresh"></i> Refresh</a>
                       </div> 
                    </div>
                  </footer>                                  
              </section>
         	</form>
            
            <?php if($obj_session->data['LOGIN_USER_TYPE']==1 && $obj_session->data['ADMIN_LOGIN_SWISS']==1) { ?>
            <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link($rout, 'mod=view_proforma&is_delete='.$_GET['is_delete'].$add_url, '',1); ?>">
               <div class=" pull-left">
               	 	<div class="panel-body text-muted l-h-2x">
                   
                     <button  type="submit" class="btn btn-primary btn-sm pull-right ml5" name="notsave" style="background-color:#EAA7A7"><i ></i> Not Save</button> 
                     <button  type="submit" class="btn btn-primary btn-sm pull-right ml5" name="inactive" style="background-color:#CBC6AB"><i></i> Inactive</button>
                     <button  type="submit" class="btn btn-primary btn-sm pull-right ml5" name="active" style="background-color:#81C267"><i></i> Active</button>
                    </div>
               </div>
           </form>
           <?php }?>
           
           
            <div class="row">
             <div class="col-lg-3 pull-right">	
                 <select class="form-control" id="limit-dropdown" onchange="location=this.value;">
                    <option value="<?php echo $obj_general->link($rout, 'mod=view_proforma&is_delete='.$_GET['is_delete'].$add_url, '',1); ?>" selected="selected">--Select--</option>

                    	<?php 
							$limit_array = getLimit(); 
							foreach($limit_array as $display_limit) {
								if($limit == $display_limit) {	 
						?>
                        		<option value="<?php echo $obj_general->link($rout, 'limit='.$display_limit.'&filter_edit=1&status='.$status.'&is_delete='.$_GET['is_delete'].$add_url, '',1);?>" selected="selected"><?php echo $display_limit; ?></option>				
						<?php } else { ?>
                            	<option value="<?php echo $obj_general->link($rout, 'limit='.$display_limit.'&filter_edit=1&status='.$status.'&is_delete='.$_GET['is_delete'].$add_url, '',1);?>"><?php echo $display_limit; ?></option>
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
                     <th class="th-sortable <?php echo ($sort_order == 'ASC') ? 'active' : ''; ?> ">Proforma Invoice Number
											<br><small class="text-muted">Proforma Date</small></th>
                      <th>Final Destination</th>
                      <th>Customer Name <br>
						<small class="text-muted">Email</small></th>
                
                      <th>Posted By</th>
                      <?php if($status != 2) { ?>                      
                      <th>Status</th>
                      
                      <?php } 
						if(isset($_GET['is_delete']) && $_GET['is_delete']!='1')
						 { ?>
                      <th>Action</th>
                      <?php } ?>
                      <th></th>
                      <th></th>
                    
                    </tr>
                  </thead>
                  <tbody>
                  <?php	
                  
               //  printr($filter_data);//die;
				  if(!empty($filter_data)){  	
				//echo $proforma_status;
				  $total_pouch = $obj_invoice->getTotalProformaInvoice($filter_data, $product_status, $proforma_status,$obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],$_GET['is_delete'],$add_book_id,$permission);
				  $pagination_data = '';
				  
				  if($total_pouch) {
    					  if (isset($_GET['page'])) {
                                $page = (int)$_GET['page'];
                            } else {
                                $page = 1;
                            }
    						$option = array(
                              'sort'  => 'proforma_id',
                              'order' => $sort_order,
                              'start' => ($page - 1) * $limit,
                              'limit' => $limit,
    						  'product_status' => $product_status,
    						  'proforma_status' => $proforma_status
    					);

			        	$proformas = $obj_invoice->getProformaInvoices($option, $filter_data, $product_status, $proforma_status,$obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],$_GET['is_delete'],$add_book_id,$permission);
				
				  if(isset($total_pouch)) {
                  foreach($proformas as $proforma) {
                     $proforma_user = $obj_pro_invoice->getUser($proforma['added_by_user_id'],$proforma['added_by_user_type_id']);
				 
						$currency = $obj_pro_invoice->getCurrencyId($proforma['currency_id']);				  
					  $userInfo = $obj_pro_invoice->getUser($proforma['added_by_user_id'], $proforma['added_by_user_type_id']);
				
					  if($proforma['added_by_user_id']=='34' && $proforma['added_by_user_type_id']=='2')
					  {
						  if(($_SESSION['ADMIN_LOGIN_SWISS']=='6' && $_SESSION['LOGIN_USER_TYPE']=='4') || ($_SESSION['ADMIN_LOGIN_SWISS']=='34' && $_SESSION['LOGIN_USER_TYPE']=='2') || ($_SESSION['ADMIN_LOGIN_SWISS']=='145' && $_SESSION['LOGIN_USER_TYPE']=='2') || ($_SESSION['ADMIN_LOGIN_SWISS']=='52' && $_SESSION['LOGIN_USER_TYPE']=='2') || ( $_SESSION['ADMIN_LOGIN_SWISS']=='1' && $_SESSION['LOGIN_USER_TYPE']=='1'))
						  {
						  	$view_link = $obj_general->link($rout, 'mod=view&proforma_id='.encode($proforma['proforma_id']).'&is_delete='.$_GET['is_delete'].$add_url,'',1);
						  	$tax_invoice_link = $obj_general->link($rout, 'mod=view&proforma_id='.encode($proforma['proforma_id']).'&is_delete='.$_GET['is_delete'].'&goods_status=1'.$add_url,'',1);
							$edit_link = $obj_general->link($rout, 'mod=add&proforma_id='.encode($proforma['proforma_id']).'&is_delete='.$_GET['is_delete'].$add_url,'',1);
							$email_add =  $proforma['email'];
							
						  }
						  else
						  {
						  	$view_link =$tax_invoice_link= $edit_link = $email_add = '';
							
						  }
					  }
					  else
					  {
						  $view_link = $obj_general->link($rout, 'mod=view&proforma_id='.encode($proforma['proforma_id']).'&is_delete='.$_GET['is_delete'].$add_url,'',1);
						 $tax_invoice_link = $obj_general->link($rout, 'mod=view&proforma_id='.encode($proforma['proforma_id']).'&is_delete='.$_GET['is_delete'].'&goods_status=1'.$add_url,'',1);
						  $edit_link = $obj_general->link($rout, 'mod=add&proforma_id='.encode($proforma['proforma_id']).'&is_delete='.$_GET['is_delete'].$add_url,'',1);
						  $email_add =  $proforma['email'];
					  }
						
					 
                    
                    
                    
                     
                      echo'<tr>';
                     
                       echo'<td><input type="checkbox" name="post[]" value="'. $proforma['proforma_id'].'"></td>';
                       echo' <td><a href="'. $view_link.'">'.$proforma['pro_in_no'].'<br>
						    <small class="text-muted">'.dateFormat(4, $proforma['invoice_date']).'</small><br>
						    <small class="text-muted">Total Amount : '.$currency['currency_code'] . ' ' . $proforma['invoice_total'].'></small> </a></td>';
                     
                     ?>
                     
                     
                      <td><a href="<?php echo $view_link; ?>"><?php echo $proforma['country_name']; ?><?php if($proforma['customer_dispatch']=='1'){ echo '<br><b>dispatch order directly to customer</b>';} echo '<br><b>Buyers order no : </b>'.$proforma['buyers_order_no'];?></a></td>
                     <td><a href="<?php echo $view_link; ?>"><?php echo $proforma['customer_name']; ?></a>
                                <br><small class="text-muted"><?php echo $email_add; ?></small>
                                <br><small class="text-muted">Contact No: <?php if($proforma['contact_no']!='0' && $proforma['contact_no']!='') echo $proforma['contact_no']; ?></small>
                                </td>
                        <td>
                          <?php	$proforma_user = $obj_pro_invoice->getUser($proforma['added_by_user_id'],$proforma['added_by_user_type_id']);									
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
                                <label class="btn btn-xs btn-success <?php echo ($proforma['status']==1) ? 'active' : '';?> "> <input type="radio" name="status" value="1" id="<?php echo $proforma['proforma_id']; ?>"> <i class="fa fa-check text-active"></i>Active</label>                                   
                                <label class="btn btn-xs btn-danger <?php echo ($proforma['status']==0) ? 'active' : '';?> "> <input type="radio"  name="status" value="0" id="<?php echo $proforma['proforma_id']; ?>"> <i class="fa fa-check text-active"></i>Inactive</label> 
                            </div>
                           </td>
                           
                          <?php }?>
                          <td>   <a class="btn btn-primary" onclick="generate_sales_inv_india(<?php echo $proforma['proforma_id']; ?>)" > Government Sales Invoice</a></td>
               
                <?php                      echo'</tr>';
                      
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
                        $pagination->url = $obj_general->link($rout,'&page={page}&limit='.$limit.'&status='.$status.'&filter_edit=1&is_delete='.$_GET['is_delete'].$add_url, '',1);  
						$pagination_data = $pagination->render();
                       } else{ 
                      echo "<tr><td colspan='5'>No record found !</td></tr>";
                     }
                  
				  }
                  ?>
                  </tbody>
                </table>
                   <div class="form-group">
                        <div class="col-lg-9 col-lg-offset-3">
                        
                           <a class="btn btn-default" href="<?php echo $obj_general->link($rout, 'mod=index', '',1);?>">Cancel</a>
                        </div>
                     </div>
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

  </div>
</div>
 <div class="modal fade" id="form_con_india" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
 	 <div class="modal-content">
    	<form class="form-horizontal" method="post" name="form" id="conform_form" style="margin-bottom:0px;">
        	<div class="modal-header title">
                <h4 class="modal-title" id="myModalLabel"><span id="pro"></span></h4>
              </div>
            <div class="modal-body">
            	<input name="pro_detail_id" id="pro_detail_id" value=""  type="hidden"/>
                <h4 class="streamlined_title"> Sure !!! <br /><br />
                						Do you want to generate Sales Invoice ?</h4>
            </div> 
             <div class="modal-footer">
                <button type="button" name="btn_submit1" class="btn btn-primary" onclick="generate_sales_india()">Yes</button>
                 <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
              </div>
        </form>
     </div>
    </div>
</div>

<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
	<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script src="https://harvesthq.github.io/chosen/chosen.jquery.js" type="text/javascript"></script>
<link rel="stylesheet" href="https://harvesthq.github.io/chosen/chosen.css" type="text/css"/> 
<style>
    .chosen-container.chosen-container-single {
        width: 300px !important; /* or any value that fits your needs */
    }
    #ajax_return{
    	border : 1px solid #13c4a5;
    	background : #FFFFFF;
    	position:relative;
    	display:none;
    	padding:2px 2px;
    	top:auto;
    	border-radius: 4px;
    }
    .list {
    	padding:0px 0px;
    	margin:0px;
    	list-style : none;
    }
    .list li a{
    	text-align : left;
    	padding:2px;
    	cursor:pointer;
    	display:block;
    	text-decoration : none;
    	color:#000000;
    }
    .selected{
    	background : #13c4a5;
    }
    .bold{
    	font-weight:bold;
    	color: #227442;
    }
    .about{
    	text-align:right;
    	font-size:10px;
    	margin : 10px 4px;
    }
    .about a{
    	color:#BCBCBC;
    	text-decoration : none;
    }
    .about a:hover{
    	color:#575757;
    	cursor : default;
    }
</style>
<!-- END-->
<script type="application/javascript">
    jQuery(document).ready(function(){
      $("#chosen_data").chosen();
    });

	function generate_sales_inv(proforma_id)
	{
 		$(".note-error").remove();
		$("#pro_detail_id").val(proforma_id);
		$("#form_con").modal("show");
	}




function generate_sales_inv_india(proforma_id)
	{
 		$(".note-error").remove();
		$("#pro_detail_id").val(proforma_id);
		$("#form_con_india").modal("show");
	}
	
	function generate_sales_india()
	{
		$("#form_con_india").modal("hide"); 
		var proforma_id = $("#pro_detail_id").val();
		var gen_url = getUrl("<?php echo $obj_general->ajaxLink('proforma_invoice_product_code_wise', '&mod=ajax&fun=gen_sales_india', '',1);?>");
		$.ajax({			
			url : gen_url,
			type :'post',
			data :{proforma_id:proforma_id},
			success: function(response){ 
			
			window.location.href='<?php echo HTTP_SERVER; ?>/admin/index.php?route=government_sales_invoice&mod=add&invoice_id='+response;
				
			},
			
						
		});
	}

$("#filter_customer_name").focus();
	var offset = $("#filter_customer_name").offset();
	var width = $("#holder").width();

	$("#ajax_return").css("width",width);
	
	$("#filter_customer_name").keyup(function(event){		
		 var keyword = encodeURIComponent($("#filter_customer_name").val());
		 //console.log(keyword.length);
		 if(keyword.length>='3')
		 {
			 if(event.keyCode != 40 && event.keyCode != 38 )
			 {
				 var product_code_url = getUrl("<?php echo $obj_general->ajaxLink('proforma_invoice_product_code_wise', 'mod=ajax&fun=customer_detail', '',1);?>");
				 $("#loading").css("visibility","visible");
				 $.ajax({
				   type: "POST",
				   url: product_code_url,
				   data: "customer_name="+keyword,
				   success: function(msg){
					  	
				 var msg = $.parseJSON(msg);
				  //alert(msg);
				 	
				   var div='<ul class="list">';
				   
					if(msg.length>0)
					{
						for(var i=0;i<msg.length;i++) 
						{	
							
								div =div+'<li><a href=\'javascript:void(0);\' id="'+msg[i].address_book_id+'"><span class="bold" >'+msg[i].company_name+'</span></a></li>';
						}
					}
					
					div=div+'</ul>';
					//console.log(div);
					if(msg != 0)
					  $("#ajax_return").fadeIn("slow").html(div);
					else
					{
					  $("#ajax_return").fadeIn("slow");	
					  $("#ajax_return").html('<div style="text-align:left;">No Matches Found</div>');
					}
					$("#loading").css("visibility","hidden");
				   }
				 });
			 }
			 else
			 {				
				switch (event.keyCode)
				{
				 case 40:
				 {
					  found = 0;
					  $(".list li").each(function(){
						 if($(this).attr("class") == "selected")
							found = 1;
					  });
					  if(found == 1)
					  {
						var sel = $(".list li[class='selected']");
						sel.next().addClass("selected");
						sel.removeClass("selected");										
					  }
					  else
						$(".list li:first").addClass("selected");
						if($(".list li[class='selected'] a").text()!='')
						{
							$("#filter_customer_name").val($(".list li[class='selected'] a").text());
						}
				}
				 break;
				 case 38:
				 {
					  found = 0;
					  $(".list li").each(function(){
						 if($(this).attr("class") == "selected")
							found = 1;
					  });
					  if(found == 1)
					  {
						var sel = $(".list li[class='selected']");
						sel.prev().addClass("selected");
						sel.removeClass("selected");
					  }
					  else
						$(".list li:last").addClass("selected");
						if($(".list li[class='selected'] a").text()!='')
						{
							$("#filter_customer_name").val($(".list li[class='selected'] a").text());
                  		}
				 }
				 break;				 
				}
			 }
		 }
		 else
		 {
			$("#ajax_return").fadeOut('slow');
			$("#ajax_return").html("");
		 }
	});
	
    $('#filter_customer_name').keydown( function(e) {
    	if (e.keyCode == 9) {
    		 $("#ajax_return").fadeOut('slow');
    		 $("#ajax_return").html("");
    	}
    });

	$("#ajax_return").mouseover(function(){
		$(this).find(".list li a:first-child").mouseover(function () {
		    $(this).addClass("selected");
		});
		$(this).find(".list li a:first-child").mouseout(function () {
			$(this).removeClass("selected");
		});
		$(this).find(".list li a:first-child").click(function () {					
			   $("#filter_customer_name").val($(this).text());
			   $("#ajax_return").fadeOut('slow');
				$("#ajax_return").html("");
		});
				
	});
		
//		<!--- sonu End payment 18-7-2017-->
</script>           
