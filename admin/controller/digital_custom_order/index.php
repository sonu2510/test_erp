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

if(isset($_GET['sort'])){
	$sort = $_GET['sort'];	
}else{
	$sort= 'date_added';
}

if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'DESC';	
}

$class = 'collapse';
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
	$filter_custom_order = $obj_session->data['filter_data']['custom_order_no'];
    $filter_quo_no = $obj_session->data['filter_data']['filter_quo_no'];
	$filter_date = $obj_session->data['filter_data']['date'];
	$filter_customer_name = $obj_session->data['filter_data']['customer_name'];
	$filter_product_name = $obj_session->data['filter_data']['product_name'];
	$filter_country = $obj_session->data['filter_data']['country'];
	$filter_postedby = $obj_session->data['filter_data']['postedby'];
	$class = '';
	
	$filter_data=array(
		'custom_order_no' => $filter_custom_order,
		'quo_no' => $filter_quo_no,
		'date' => $filter_date, 
		'customer_name' => $filter_customer_name,
		'product_name' => $filter_product_name,
		'country' => $filter_country,
		'postedby' => $filter_postedby,
	);
}
//printr($_POST);
$status = '';
if($obj_session->data['LOGIN_USER_TYPE']==1 && $obj_session->data['ADMIN_LOGIN_SWISS']==1) {
	if(isset($_POST['inactive']) || (isset($_GET['status']) && $_GET['status'] == 1) || (isset($_POST['status']) && $_POST['status'] == 1) ){
		$con= "AND mcoi.custom_order_status = 1  AND mcoi.status='0'  AND mcoi.quotation_status=1 ";
		$cond="AND mco.custom_order_status = 1  AND mco.status='0' AND mcoi.quotation_status=1 ";
		$status = '1';
	}
	elseif(isset($_POST['notsave']) || (isset($_GET['status'])  && $_GET['status']== 2) || (isset($_POST['status']) && $_POST['status'] == 2)){
		$con= "AND mcoi.custom_order_status = 0  AND  mcoi.quotation_status=1 ";
		$cond="AND mco.custom_order_status = 0 AND mcoi.quotation_status=1  ";
		$status = '2';
	}
	else{
		$con= "AND mcoi.custom_order_status = 1   AND quotation_status=1  AND mcoi.status='1'";
		$cond="AND mco.custom_order_status = 1  AND mco.status='1' AND mcoi.quotation_status=1 ";
		$status = '0';
	}
}
else
{
	$con= "AND mcoi.custom_order_status = 1  AND mcoi.quotation_status=1 ";
	$cond="AND mco.custom_order_status = 1 AND mcoi.quotation_status=1 ";	
}
//echo $status;
//echo $con.'<br>'.$cond;

//[sonu] (21-4-2017) for get address_book_id wise data
$add_book_id='0';
$add_url='';
if(isset($_GET['address_book_id']))
{
		$add_book_id = decode($_GET['address_book_id']);
		$add_url = '&address_book_id='.$_GET['address_book_id'];
}
//end

if(isset($_POST['btn_filter'])){
	$filter_edit = 1;
	$class = '';	
	if(isset($_POST['filter_custom_order'])){
		$filter_custom_order=$_POST['filter_custom_order'];		
	}else{
		$filter_quotation='';
	}
	if(isset($_POST['filter_quo_no'])){
		$filter_quo_no=$_POST['filter_quo_no'];		
	}else{
		$filter_quo_no='';
	}
	if(isset($_POST['filter_date'])){
		$filter_date=$_POST['filter_date'];		
	}else{
		$filter_date='';
	}
	if(isset($_POST['filter_customer_name'])){
		$filter_customer_name=$_POST['filter_customer_name'];
	}else{
		$filter_customer_name='';
	}	
	if(isset($_POST['filter_product_name'])){
		$filter_product_name=$_POST['filter_product_name'];
	}else{
		$filter_product_name='';
	}
	if(isset($_POST['country_id'])){
		$filter_country=$_POST['country_id'];
	}else{
		$filter_country='';
	}
	if(isset($_POST['filter_user_name']))
	{
		$filter_user_name = $_POST['filter_user_name'];
	}else{
		$filter_user_name='';
	}
	$filter_data=array(
		'custom_order_no' => $filter_custom_order,
		'quo_no' => $filter_quo_no,
		'date' => $filter_date, 
		'customer_name' => $filter_customer_name,
		'product_name' => $filter_product_name,
		'country' => $filter_country,
		'postedby' => $filter_user_name,	
	);
	$obj_session->data['filter_data'] = $filter_data;		
}


//sonu add 17-4-2017 for uk
	$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
	$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
	$addedByInfo = $obj_digital_custom_order->getUser($user_id,$user_type_id);
//	printr($addedByInfo['country_id'] );
	//end
	
if($display_status) {
	//active inactive delete
	if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
		//printr($_POST['post']);die;
		if(!$obj_general->hasPermission('delete',$menuId)){
			$display_status = false;
		} else { 
			//printr($_POST['post']);die; 
			foreach($_POST['post'] as $cust_order_id){
				$obj_digital_custom_order->deleteCustomOrder($cust_order_id);
			}
			$obj_session->data['success'] = UPDATE;
			page_redirect($obj_general->link($rout, ''.$add_url, '',1));
		}
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
		  	<span><?php echo $display_name;?> Listing </span>
          	<span class="text-muted m-l-small pull-right">
            	<?php if($obj_general->hasPermission('add',$menuId)){ ?>
   					<a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add'.$add_url, '',1);?>"><i class="fa fa-plus"></i> New Custom Order </a>
                <?php } ?>
                <?php if($obj_general->hasPermission('delete',$menuId)){ ?>   
                      <a class="label bg-danger" style="margin-left:4px;" onclick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>
                <?php } ?>      
            </span>
          </header>
          <div class="panel-body">
            
            <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link($rout, ''.$add_url, '',1); ?>">
                <section class="panel pos-rlt clearfix">
                  <header class="panel-heading">
                    <ul class="nav nav-pills pull-right">
                      <li> <a href="#" class="panel-toggle text-muted active"><i class="fa fa-caret-down fa-lg text-active"></i><i class="fa fa-caret-up fa-lg text"></i></a> </li>
                    </ul>
                    <i class="fa fa-search"></i> Search
                  </header>
                <div class="panel-body clearfix <?php echo $class; ?>">        
                      <div class="row">
                        <div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-5 control-label">Custom Order No</label>
                                <div class="col-lg-7">
                                  <input type="text" name="filter_custom_order" value="<?php echo isset($filter_custom_order) ? $filter_custom_order : '' ; ?>" placeholder="Name" id="input-name" class="form-control" />
                                </div>
                              </div>
                              
                              
                                  <div class="form-group">
                                <label class="col-lg-5 control-label">Quotation No</label>
                                <div class="col-lg-7">
                                  <input type="text" name="filter_quo_no" value="<?php echo isset($filter_quo_no) ? $filter_quo_no : '' ; ?>" placeholder="Quotation No" id="input-no" class="form-control">
                                </div>
                              </div>
                              
                              
                               <div class="form-group">
                                <label class="col-lg-5 control-label">Date</label>
                                <div class="col-lg-7">                                                            
                                
                                 <input type="text" name="filter_date" readonly="readonly" data-date-format="dd-mm-yyyy" value="<?php echo isset($filter_date) ? $filter_date : '' ; ?>" placeholder="Date" id="input-name" class="input-sm form-control datepicker" />
                                </div>
                              </div>
                              
                          </div>
                          <div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-4 control-label">Customer</label>
                                <div class="col-lg-8">
                                  <input type="text" name="filter_customer_name" value="<?php echo isset($filter_customer_name) ? $filter_customer_name : '' ; ?>" placeholder="Customer Name" id="input-price" class="form-control">
                                </div>
                              </div> 
                              
                               <div class="form-group">
                                <label class="col-lg-4 control-label">Country</label>
                                <div class="col-lg-8">
                                	<?php
										$sel_country = (isset($branch['country_id']))?$branch['country_id']:''; 
										$countrys = $obj_general->getCountryCombo($sel_country);
										echo $countrys;                   
									?>	             	
                                </div>
                              </div>
                          </div>
                          
                          <div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-5 control-label">Product</label>
                                <?php							
									$products = $obj_digital_custom_order->getActiveProduct();
								?>
                                <div class="col-lg-7">
                                	<select class="form-control" name="filter_product_name">
                                    	<option value="">Please Select</option>
                                    	<?php foreach($products as $product) { ?>
                                        	<?php if(isset($filter_product_name) && !empty($filter_product_name) && $filter_product_name == $product['product_name']) { ?>
                                    			<option value="<?php echo $product['product_name']; ?>" selected="selected"><?php echo $product['product_name']; ?></option>
                                            <?php } else { ?>
                                            	<option value="<?php echo $product['product_name']; ?>"><?php echo $product['product_name']; ?></option>
                                            <?php } ?>
                                        <?php } ?>                                       
                                    </select>
                                </div>
                              </div>                              
                          </div>
                          
                           <div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-5 control-label">Posted By User</label>
                                <?php							
									$userlist = $obj_digital_custom_order->getUserList();
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
                        <input type="hidden" value="<?php echo $status;?>" id="status" name="status" />
                        <button type="submit" class="btn btn-primary btn-sm pull-right ml5" name="btn_filter"><i class="fa fa-search"></i> Search</button>
                        <a href="<?php echo $obj_general->link($rout, ''.$add_url, '',1); ?>" class="btn btn-info btn-sm pull-right" ><i class="fa fa-refresh"></i> Refresh</a>
                       </div> 
                    </div>
                  </footer>                                  
              </section>
           </form>    
            <?php if($obj_session->data['LOGIN_USER_TYPE']==1 && $obj_session->data['ADMIN_LOGIN_SWISS']==1) { ?>
            <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link($rout, ''.$add_url, '',1); ?>">
           <div class=" pull-left">
           	 	<div class="panel-body text-muted l-h-2x">
               
                 <button  type="submit" class="btn btn-primary btn-sm pull-right ml5" name="notsave" style="background-color:#EAA7A7"><i ></i> Not Save</button> 
                 <button  type="submit" class="btn btn-primary btn-sm pull-right ml5" name="inactive" style="background-color:#CBC6AB"><i></i> Inactive</button>
                 <button  type="submit" class="btn btn-primary btn-sm pull-right ml5" name="active" style="background-color:#81C267"><i></i> Active</button>
                </div>
           </div> 
           <?php }?>
           </form>
           <div class="col-lg-3 pull-right">	
                <select class="form-control" id="limit-dropdown" onchange="location=this.value;">
                <option value="<?php echo $obj_general->link($rout, '&status='.$status, '',1);?>" selected="selected">--Select--</option>	
					<?php 
                        $limit_array = getLimit(); 
                        foreach($limit_array as $display_limit) {
                            if($limit == $display_limit) {	 
                    ?>
                       		 
                            <option value="<?php echo $obj_general->link($rout, 'limit='.$display_limit.'&status='.$status.$add_url, '',1);?>" selected="selected"><?php echo $display_limit; ?></option>				
                    <?php } else { ?>
                            <option value="<?php echo $obj_general->link($rout, 'limit='.$display_limit.'&status='.$status.$add_url, '',1);?>"><?php echo $display_limit; ?></option>
                    <?php } ?>
                    <?php } ?>
                 </select>
           </div>
             <label class="col-lg-1 pull-right" style="margin-top:5px;">Show</label>             
          </div>
          
          <form name="form_list" id="form_list" method="post">
           <input type="hidden" id="action" name="action" value="" />
          	<div class="table-responsive">
                <table id="quotation-row" class="table b-t text-small table-hover">
                  <thead>
                    <tr>
                      <th width="20"><input type="checkbox" ></th>
                      <th>Sr. No.</th>                     
                      <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">
                      		Order No.
                            <span class="th-sort">
                            	<a href="<?php echo $obj_general->link($rout, 'sort=date_added'.'&order=ASC'.$add_url, '',1);?>">
                                	<i class="fa fa-sort-down text"></i>
                                    
                                <a href="<?php echo $obj_general->link($rout, 'sort=date_added'.'&order=DESC'.$add_url, '',1);?>">
                                <i class="fa fa-sort-up text-active"></i>
                            <i class="fa fa-sort"></i></span>
                      </th>
                    <!--sonu add 17-4-2017-->
					  <?php if($addedByInfo['country_id'] == 252 ){?>
                        <th>Reference No.</th>
                      <?php }?>
                    <!--sonu end 17-4-2017-->
                      <th>Customer Name</th>
                    <th>Company Name</th>
                       <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">
                      		Product
                            <span class="th-sort">
                            	<a href="<?php echo $obj_general->link($rout, 'sort=product_name'.'&order=ASC'.$add_url, '',1);?>">
                                <i class="fa fa-sort-down text"></i>
                                <a href="<?php echo $obj_general->link($rout, 'sort=product_name'.'&order=DESC'.$add_url, '',1);?>">
                                <i class="fa fa-sort-up text-active"></i>
                            <i class="fa fa-sort"></i></span>
                      </th>
                       <?php if(isset($status) && $status != 2){?>
                      <th>Status</th>
                      <?php }?>
                      <?php if($obj_session->data['LOGIN_USER_TYPE']==1 && $obj_session->data['ADMIN_LOGIN_SWISS']==1) { ?>
                      	<th >Action</th>
                      <?php } ?>       
                      <th>Posted By</th>
                      <th>Order Status</th>
                     
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                  $total_custom_order = $obj_digital_custom_order->getTotalCustomOrder($obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS'],$filter_data,$cond,$add_book_id);
				 //echo $total_custom_order;
				//die;
                  $pagination_data = '';
                  if($total_custom_order!=0){
                        if (isset($_GET['page'])) {
                            $page = (int)$_GET['page'];
                        } else {
                            $page = 1;
                        }
                      //oprion use for limit or and sorting function	
                      $option = array(
                            'sort'  => $sort,
                            'order' => $sort_order,
                            'start' => ($page - 1) * $limit,
                            'limit' => $limit,
							'con' =>$con,
							'cond' =>$cond,
                      );	
                      $customOrder = $obj_digital_custom_order->getCustomOrders($obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS'],$option,$filter_data,$add_book_id);		
					// printr($customOrder);
					 //die;
					  $start_num =((($page*$limit)-$limit)+1);
					  $f = 0;
					  $slNo = $f+$start_num;
					  foreach($customOrder as $cust_order){ 
					  //printr($cust_order);
					  $multi_quation_id = $obj_digital_custom_order->getmulti_quation_id($cust_order['multi_product_quotation_id']);
					  	$postedByData = $obj_digital_custom_order->getUser($cust_order['added_by_user_id'],$cust_order['added_by_user_type_id']);
					  	
						if($postedByData){  ?>                           
                            <?php if($cust_order['custom_order_status']==0){  ?>
                            	<tr id="quotation-row-<?php echo $cust_order['multi_custom_order_id']; ?>" style="background-color:#f2dede">
                            <?php } else { ?>
                            	<tr id="quotation-row-<?php echo $cust_order['multi_custom_order_id']; ?>" <?php echo ($cust_order['status']==0) ? 'style="background-color:#fcf8e3" ' : '' ; ?> >
                            <?php } ?>
                              <td><input type="checkbox" name="post[]" value="<?php echo $cust_order['multi_custom_order_id'];?>"></td>	
							  <td width="1%"><?php echo $slNo++;?></td>
							  <td>
								<a href="<?php echo $obj_general->link($rout, '&mod=view&custom_order_id='.encode($cust_order['multi_custom_order_id']).'&filter_edit='.$filter_edit.$add_url, '',1);?>"><?php echo $cust_order['multi_custom_order_number'];?>
                                <?php if($cust_order['use_device']){ 
										echo '<small class="text-muted">[From '.ucwords($cust_order['use_device']).']</small>';
								 } ?>	
								<br /><small class="text-muted"><?php echo dateFormat(4,$cust_order['date_added']);?></small>
                                                                        <br /><small class="text-muted"><b>Quo.no - [<?php echo $multi_quation_id['digital_quotation_no'];?>]</b></small>
                                </a>
							  </td>
							  
							        
           			     <!--   sonu add 17-4-2017-->
                           <?php if($addedByInfo['country_id'] == 252 ){?>
                              <td>
                               <a href="<?php echo $obj_general->link($rout, '&mod=view&custom_order_id='.encode($cust_order['multi_custom_order_id']).'&filter_edit='.$filter_edit.$add_url, '',1);?>">             	 		  <?php echo $cust_order['reference_no'];?>
                              </a> 
                              </td>
                              <?php }?> 
                               <!-- end-->
							  
							 <td>
                 			 <a href="<?php echo $obj_general->link($rout, '&mod=view&custom_order_id='.encode($cust_order['multi_custom_order_id']).'&filter_edit='.$filter_edit.$add_url, '',1);?>">             	 
							  	  <?php echo $cust_order['customer_name'];?><br/>
                              	  <small class="text-muted"><?php echo $cust_order['country_name']; ?></small>
                              	  <br /><small class="text-muted"><b>Reference No :- </b><?php echo $cust_order['reference_no'];?></small>
                              </a>  	
                              </td>
                              <td> <a href="<?php echo $obj_general->link($rout, '&mod=view&custom_order_id='.encode($cust_order['multi_custom_order_id']).'&filter_edit='.$filter_edit.$add_url, '',1);?>">             	 
							  	  <?php echo $cust_order['company_name'];?><br/>
                              	  <small class="text-muted"><?php echo $cust_order['email']; ?></small>
                              </a>  	</td>
							  <td>
                              	<a href="<?php echo $obj_general->link($rout, '&mod=view&custom_order_id='.encode($cust_order['multi_custom_order_id']).'&filter_edit='.$filter_edit.$add_url, '',1);?>">
								<?php echo $cust_order['product_name'];?><br />
								<small class="text-muted"><span style="color:blue"> <?php echo ' '.'['.$cust_order['zipper_txt'].' '.
								$cust_order['valve_txt'].' '.$cust_order['spout_txt'].' '.$cust_order['accessorie_txt'].']';?></span></small><br />
                                
                                </a>
							  </td>
                              <?php if(isset($status) && $status != 2){?>
                              <td>
                              	<div data-toggle="buttons" class="btn-group">
                                	<label class="btn btn-xs btn-success <?php echo ($cust_order['status']==1) ? 'active' : '';?> "> <input type="radio" 
                                    name="status" value="1" id="<?php echo $cust_order['multi_custom_order_id']; ?>"> <i class="fa fa-check text-active"></i>Active</label>
                                     
                                	<label class="btn btn-xs btn-danger <?php echo ($cust_order['status']==0) ? 'active' : '';?> "> <input type="radio" 
                                    name="status" value="0" id="<?php echo $cust_order['multi_custom_order_id']; ?>"> <i class="fa fa-check text-active"></i>Inactive</label>
                                	</div>
                              </td>
                              <?php }?>
                              <?php if($obj_session->data['LOGIN_USER_TYPE']==1 && $obj_session->data['ADMIN_LOGIN_SWISS']==1) { ?>
                              
                                  <td class="delete-quot">
                                    <a class="btn btn-danger btn-sm" id="<?php echo $cust_order['multi_custom_order_id']; ?>" href="javascript:void(0);"><i class="fa fa-trash-o"></i></a>
                                    </td>
                                   
                              <?php } ?>
                              	
							  <td> 
                              
								<?php
									$addedByImage = $obj_general->getUserProfileImage($cust_order['added_by_user_type_id'],$cust_order['added_by_user_id'],'100_');
									$postedByInfo = '';
									$postedByInfo .= '<div class="row">';
										$postedByInfo .= '<div class="col-lg-3"><img src="'.$addedByImage.'"></div>';
										$postedByInfo .= '<div class="col-lg-9">';
										if($postedByData['city']){ $postedByInfo .= $postedByData['city'].', '; }
										if($postedByData['state']){ $postedByInfo .= $postedByData['state'].' '; }
										if(isset($postedByData['postcode'])){ $postedByInfo .= $postedByData['postcode']; }
										$postedByInfo .= '<br>Telephone : '.$postedByData['telephone'].'</div>';
									$postedByInfo .= '</div>';
									$postedByName = $postedByData['first_name'].' '.$postedByData['last_name'];
									str_replace("'","\'",$postedByName);
								?>
								<a class="btn btn-info btn-xs" data-trigger="hover" data-toggle="popover" data-html="true" data-placement="top" data-content='<?php echo $postedByInfo;?>' title="" data-original-title="<b><?php echo $postedByName;?></b>"><?php echo $postedByData['user_name'];?></a>
							  <?php //echo $postedByData['user_name'];//$postedByData['first_name'].' '.$postedByData['last_name'];?></td>
                              <td>
								  <?php if($cust_order['accept_decline_status']=='1') 
                                            echo '<a class=" btn-success btn-sm">Accepted Order</a>';
                                        elseif($cust_order['accept_decline_status']=='2')
                                            echo '<a class=" btn-danger btn-sm" >Declined Order</a>';
										elseif($cust_order['accept_decline_status']=='3')
                                            echo '<a class=" bg-primary btn-sm"  onclick=dispatch_order_detail('.$cust_order['multi_custom_order_id'].') >Dispatched Order</a>';
                                 ?>
                              </td>
                           
							</tr>
							<?php
							//$f++;
						}
                      }
                        //pagination
                        $pagination = new Pagination();
                        $pagination->total = $total_custom_order;
                        $pagination->page = $page;
                        $pagination->limit = $limit;
                        $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                        $pagination->url = $obj_general->link($rout,'&page={page}&limit='.$limit.'&status='.$status.'&filter_edit=1'.$add_url, '',1);//HTTP_ADMIN.'index.php?rout='.$rout.'&page={page}';
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
              <div class="col-sm-2 hidden-xs"> </div>
              	<?php echo $pagination_data;?>
            </div>
          </footer>
        </section>
      </div>
    </div>
  </section>
</section>
<div class="modal fade" id="track_div" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="tform" id="tform" style="margin-bottom:0px;">
              <div class="modal-header">
               
                <h4 class="modal-title u_title" id="myModalLabel"> Order Tracking Details</h4>
              </div>
              <div class="modal-body">
                 <div class="form-group">
                   <div class="panel-body" id="inv_data">
                   
		
                    	
                  </div>
                  </div>
                </div>
                
              

              
              <div class="modal-footer">
                   		   <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" >Close</button>
                   		 
                  
              </div>
              </div>
   		</form>   
    </div>
  </div>
</div>
<script type="application/javascript">

	$('.delete-quot a').click(function(){
		var con = confirm("Are you sure you want to delete ?");
		if(con){
			var custom_order_id=$(this).attr('id');
			var del_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=deleteCustomOrder', '',1);?>");
			$('#loading').show();
			$.ajax({
				url : del_url,
				type :'post',
				data :{custom_order_id:custom_order_id},
				success: function(response){
					//alert(response);
					if(response==1){
						$('#quotation-row-'+custom_order_id).remove();
						set_alert_message('Successfully Deleted',"alert-success","fa-check");	
					}
					$('#loading').hide();								
				},
				error:function(){
					set_alert_message('Error!',"alert-warning","fa-warning");          
				}			
			});
		}
	});
	$('input[type=radio][name=status]').change(function() {
		var custom_order_id=$(this).attr('id');
		var status_value = this.value;
		var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=updateCustomOrderStatus', '',1);?>");
        $.ajax({
			url : status_url,
			type :'post',
			data :{custom_order_id:custom_order_id,status_value:status_value},
			success: function(response){
		//		alert(response);
				if(response==1){
					set_alert_message('Successfully Updated',"alert-success","fa-check");	
					$('#quotation-row-'+custom_order_id).remove();
				}else{
					set_alert_message('You Don\'t Have Access To Enable Quotation',"alert-warning","fa-warning");						
				}									
			},
			error:function(){
				set_alert_message('sda',"alert-warning","fa-warning");          
			}			
		});
    });
    function clone_data(multi_custom_order_id)
    {
        var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=clone_data', '',1);?>");
        $.ajax({
			url : url,
			type :'post',
			data :{multi_custom_order_id:multi_custom_order_id},
			success: function(response){
				//console.log(response);
				//if(response==1){
					set_alert_message('Successfully Cloned',"alert-success","fa-check");
					location.reload();
				//}									
			}			
		});
    }
      function dispatch_order_detail(order_id)
    {
        var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=dispatch_order_detail', '',1);?>");
     
        $.ajax({
			url : url,
			type :'post',
			data :{order_id:order_id},
			success: function(response){
				
				$('#inv_data').html(response);
				
				$("#track_div").modal('show'); 
			}			
		});
    }
</script>           
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>