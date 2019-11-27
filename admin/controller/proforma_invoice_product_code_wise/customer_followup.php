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
	'text' 	=> 'Customer followups List',
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
/*if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{*/
	$sort_order = 'DESC';	
//}
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

$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
$addedByInfo_login = $obj_pro_invoice->getUser($user_id,$user_type_id);
//printr($addedByInfo_login);

$permission = '0';
if($obj_general->hasPermission('edit','287'))
{
    $permission = '1';
}
?>
<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-list"></i> Customer followups</h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>   
        
      <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading bg-white"> 
       		<span>Customer followups Listing</span>
          	<span class="text-muted m-l-small pull-right">
                       
            </span>
          </header>
          
          <div class="panel-body">
              <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link($rout, 'mod=customer_followup&is_delete='.$_GET['is_delete'].$add_url, '',1); ?>">
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
                        <a href="<?php echo $obj_general->link($rout, 'mod=customer_followup&status='.$status.'&is_delete='.$_GET['is_delete'].$add_url, '',1); ?>" class="btn btn-info btn-sm pull-right" ><i class="fa fa-refresh"></i> Refresh</a>
                       </div> 
                    </div>
                  </footer>                                  
              </section>
         	</form>
            
            <?php if($obj_session->data['LOGIN_USER_TYPE']==1 && $obj_session->data['ADMIN_LOGIN_SWISS']==1) { ?>
            <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link($rout, 'mod=customer_followup&is_delete='.$_GET['is_delete'].$add_url, '',1); ?>">
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
                    <option value="<?php echo $obj_general->link($rout, 'mod=customer_followup&is_delete='.$_GET['is_delete'].$add_url, '',1); ?>" selected="selected">--Select--</option>

                    	<?php 
							$limit_array = getLimit(); 
							foreach($limit_array as $display_limit) {
								if($limit == $display_limit) {	 
						?>
                        		<option value="<?php echo $obj_general->link($rout, 'mod=customer_followup&limit='.$display_limit.'&status='.$status.'&is_delete='.$_GET['is_delete'].''.$add_url, '',1);?>" selected="selected"><?php echo $display_limit; ?></option>				
						<?php } else { ?>
                            	<option value="<?php echo $obj_general->link($rout, 'mod=customer_followup&limit='.$display_limit.'&status='.$status.'&is_delete='.$_GET['is_delete'], '',1);?>"><?php echo $display_limit; ?></option>
                        <?php } ?>
                        <?php } ?>
                 </select>
             </div>
                <label class="col-lg-1 pull-right" style="margin-top:5px;">Show</label>	
           </div>   
          </div>
              <header class="panel-heading text-right"> 
                    <ul class="nav nav-tabs pull-left" id="view_list"> 
	                    <li class=""><a href="<?php echo $obj_general->link($rout, 'mod=customer_followup&temp_status=1&is_delete='.$_GET['is_delete'], '',1);?>">Last 15 Days</a></li> 
	                    <li class=""><a href="<?php echo $obj_general->link($rout, 'mod=customer_followup&temp_status=2&is_delete='.$_GET['is_delete'], '',1);?>">Last 25 Days</a></li>                                              
	                    <li class=""><a href="<?php echo $obj_general->link($rout, 'mod=customer_followup&temp_status=3&is_delete='.$_GET['is_delete'], '',1);?>">Last 30 Days</a></li> 
	                    <li class=""><a href="<?php echo $obj_general->link($rout, 'mod=customer_followup&temp_status=4&is_delete='.$_GET['is_delete'], '',1);?>">Last 60 Days</a></li>
	                    <li class=""><a href="<?php echo $obj_general->link($rout, 'mod=customer_followup&temp_status=5&is_delete='.$_GET['is_delete'], '',1);?>">Last 90+ Days</a></li>                  
                    </ul>
             </header>
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
				      <th>Mail Send Customer</th>    
                      <th>Posted By</th>
                      <th>Status</th>
                    
                    
                    </tr>
                  </thead>
                  <tbody>
                  <?php	
				    	
				//echo $proforma_status;
                  	if(isset($_GET['temp_status']) && $_GET['temp_status']!='5')
					{
						if($_GET['temp_status']=='1')
							$interval=15;
						if($_GET['temp_status']=='2')
							$interval=25;
						if($_GET['temp_status']=='3')
							$interval=30;
						if($_GET['temp_status']=='4')
							$interval=60;
						/*if($_GET['temp_status']=='5')
							$interval=90;*/
					} 
					else
					{
						$interval='';
					}
				$total = $obj_pro_invoice->getInvoicesCustomerFollowups('', $filter_data, $product_status, $proforma_status,$obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],0,$add_book_id,$permission,1,$interval,$addedByInfo_login['user_id']);
				  //$total_pouch = $obj_pro_invoice->getTotalInvoiceCustomerFollowups($filter_data, $product_status, $proforma_status,$obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],0,$add_book_id,$permission,1,$interval);
				 $total_pouch = count($total);
				  $pagination_data = '';
				  
				  if($total_pouch) {
					  if (isset($_GET['page'])) {
                            $page = (int)$_GET['page'];
                        } else {
                            $page = 1;
                        }
						$option = array(
                          'sort'  => 'p.invoice_date',
                          'order' => $sort_order,
                          'start' => ($page - 1) * $limit,
                          'limit' => $limit,
						  'product_status' => $product_status,
						  'proforma_status' => $proforma_status
					);

				$proformas = $obj_pro_invoice->getInvoicesCustomerFollowups($option, $filter_data, $product_status, $proforma_status,$obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],0,$add_book_id,$permission,1,$interval,$addedByInfo_login['user_id']);
			
				  if(isset($total_pouch)) {
                  foreach($proformas as $proforma) {
                      $view_link=$obj_general->link($rout, 'mod=view&proforma_id='.encode($proforma['proforma_id']).'&is_delete=0'.$add_url,'',1);
                      $proforma_user = $obj_pro_invoice->getUser($proforma['added_by_user_id'],$proforma['added_by_user_type_id']);
				
						$currency = $obj_pro_invoice->getCurrencyId($proforma['currency_id']);				  
					  $userInfo = $obj_pro_invoice->getUser($proforma['added_by_user_id'], $proforma['added_by_user_type_id']);
					 
					if($proforma['proforma_status']==1){ ?>
                        <tr id="proforma-row-<?php echo $proforma['proforma_id']; ?>" style="background-color:#f2dede"> 
                        <?php } else { ?> 
                        <tr id="proforma-row-<?php echo $proforma['proforma_id']; ?>" <?php echo ($proforma['status']==0) ? 'style="background-color:#fcf8e3" ' : '' ; ?>> <?php } ?>                       
                          <td><input type="checkbox" name="post[]" value="<?php echo $proforma['proforma_id'];?>"></td>
                        <td><a href="<?php echo $view_link; ?>"><?php echo $proforma['pro_in_no']; ?><br>
						    <small class="text-muted"><?php echo dateFormat(4, $proforma['invoice_date']); ?></small><br>
						    <small class="text-muted">Total Amount : <?php echo $currency['currency_code'] . ' ' . $proforma['invoice_total']; ?></small> </a></td>
						  <td><a href="<?php echo $view_link; ?>"><?php echo $proforma['country_name']; ?><?php if($proforma['customer_dispatch']=='1'){ echo '<br><b>dispatch order directly to customer</b>';} echo '<br><b>Buyers order no : </b>'.$proforma['buyers_order_no'];?></a></td>
                          <td><a href="<?php echo $view_link; ?>"><?php echo $proforma['customer_name']; ?></a>
                                <br><small class="text-muted"><?php echo $proforma['email']; ?></small>
                                <br><small class="text-muted">Contact No: <?php if($proforma['contact_no']!='0' && $proforma['contact_no']!='') echo $proforma['contact_no']; ?></small>
                                </td>
                           <td>
                                     <button type="button" name ="send_customer" id="send_customer"  onclick="send_customer_modal(<?php echo $proforma['proforma_id']; ?>,'<?php echo $proforma['pro_in_no']; ?>','<?php echo $proforma['customer_name']; ?>','<?php echo $proforma['email']; ?>','<?php echo $addedByInfo_login['company_name']; ?>','<?php echo $addedByInfo_login['country_id']; ?>')" class="btn btn-primary">Send Mail</button>
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
                       
                          <td>
                          <div data-toggle="buttons" class="btn-group">
                                <label class="btn btn-xs btn-success <?php echo ($proforma['status']==1) ? 'active' : '';?> "> <input type="radio" name="status" value="1" id="<?php echo $proforma['proforma_id']; ?>"> <i class="fa fa-check text-active"></i>Active</label>                                   
                                <label class="btn btn-xs btn-danger <?php echo ($proforma['status']==0) ? 'active' : '';?> "> <input type="radio"  name="status" value="0" id="<?php echo $proforma['proforma_id']; ?>"> <i class="fa fa-check text-active"></i>Inactive</label> 
                            </div>
                           </td>
                         
                    </tr>
                      <?php
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
                        $pagination->url = $obj_general->link($rout,'mod=customer_followup&temp_status='.$_GET['temp_status'].'&page={page}&limit='.$limit.'&status='.$status.'&filter_edit=1&is_delete='.$_GET['is_delete'], '',1);  
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
<div class="modal fade" id="customer_send_div" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:70%;">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="cform" id="cform" style="margin-bottom:0px;">
              <div class="modal-header">
               
                <h4 class="modal-title u_title" id="myModalLabel"><span id="customer_name"></span></h4>
              </div>
              <div class="modal-body">
                 <div class="form-group">
                   <div class="panel-body">
                    <div class="form-group">    
                	    <label class="col-lg-1 control-label" id="email">To Email</label>
                        <div class="col-lg-10"> 
                            <input type="toemail" name="toemail" value="" id="toemail" class="form-control" required />
                        </div>
                    </div>
                    <div class="form-group">
                       <label class="col-lg-1 control-label" id="email">CC</label>
                        <div class="col-lg-10"> 
                            <input type="toemail" name="ccemail" value="" id="ccemail" class="form-control"/><br><small style="color:red;">If you want to have multiple email CC, please add email ids with a comma (,) sign.</small>
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
                      <label class="col-lg-1 control-label">Body </label>
                        <div class="col-lg-10">
                          <textarea id="customerinfo" name="customerinfo" value="" class="form-control"  style="height: 237px;" required></textarea>
                         
                           <input type="hidden" name="admin" id="admin" value="<?php echo ADMIN_EMAIL;?>" />
                          
                           <input type="hidden" name="emailform" id="emailform" value="" />
                            <input type ="hidden" name="proforma_id_send" id="proforma_id_send" value="" />
                           </div>
                        </div>
                     </div>
                       </div>	
                </div>
              

              
              <div class="modal-footer">
                   		   <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" >Close</button>
                   		   <button type="button" name ="btn_send_customer" id="btn_send_customer"  onclick="send_mail_customer()"class="btn btn-primary">Send</button>
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
<script src="<?php echo HTTP_SERVER;?>ckeditor3/ckeditor.js"></script>

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
    $(document).ready(function () {
       $("ul.nav > li").removeClass("active");
		var u=window.location.href;
		//var ul=u+'&temp_status=6';
		if(window.location.href.indexOf("temp_status") > -1) {
		  //alert("hi it contains");
		 	$("ul.nav > li a[href*='"+u+"']").parent("ul.nav > li").addClass("active");
		}
		else
		{
		 //alert("hi it doesnt contains");
			var ul=u+'&temp_status=3';
		 	$("ul.nav > li a[href*='"+ul+"']").parent("ul.nav > li").addClass("active");
		}
		//alert(u);
       // $("ul.nav > li a[href*='"+u+"']").parent("ul.nav > li").addClass("active");
    });

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



function send_customer_modal(proforma_id,proforma_no,customer_name,customer_email,company_name,country_id)
{
    var editor = CKEDITOR.instances.customerinfo;
    if (editor) {
        editor.destroy(true); 
    } 
    var email = '<?php echo $addedByInfo_login['email'];?>';
    $("#bccemail").val(email);
    $("#proforma_id_send").val(proforma_id);
	$("#customer_name").val(proforma_no+' => <b>'+customer_name+'</b>'); 
	$("#subject").val('Followup Email - '+proforma_no+' - '+customer_name+' - '+company_name);  
	$("#toemail").val(customer_email);
		if(country_id==111)
		    $("#customerinfo").val('<p>Hello '+customer_name+',</p> <p>Please Find attached Proforma Invoice for your order.</p> <p>I look forward to hearing back from you and please let me know if anything.</p><p>Thanks.</p>');
		else
		    $("#customerinfo").val('<p>Hello '+customer_name+',</p> <p>Just touching base to check whether the payment has been made for this order?</p> <p>Please find attached Proforma Invoice for your reference. Let us know once paid and we would process your order straight away.</p><p>I look forward to hearing back from you and let me know if you have any questions.</p><p>Thanks.</p>');
		    
    	CKEDITOR.replace('customerinfo', {
		toolbar: [ 
			{ name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'] },
			{ name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv','-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl']},
			{ name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
			{ name: 'colors', items: ['TextColor', 'BGColor'] }]});
	$("#customer_send_div").modal("show");
}
function send_mail_customer(){
	    	
      jQuery("#cform").validationEngine();	
      if($("#cform").validationEngine('validate')){
          
        	var formData = $("#cform").serialize();
        	
            var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=send_mail_customer', '', 1); ?>");
            $.ajax({
                url : url,
                type :'post',
                data :{formData:formData},
                success: function(response){
                   // console.log(response);
                    set_alert_message('Successfully send ',"alert-success","fa-check");
    		    	location.reload()
                },
 

            });
      }
      
		
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
				 var product_code_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=customer_detail', '',1);?>");
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
		

</script>           
