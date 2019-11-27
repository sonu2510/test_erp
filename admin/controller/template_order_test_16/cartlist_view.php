<?php //ruchi 30/4/2015 changes for price uk
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

$address_id='0';
$add_url='';
if(isset($_GET['address_book_id']))
{
    $address_id = decode($_GET['address_book_id']);
    $add_url = '&address_book_id='.$_GET['address_book_id'];
}

if(isset($_GET['status']))
{
	if($_GET['status']==0)
	{
		//local
		 //$menuId=83;
		 //online
		$menuId = 75;
	}
	else if($_GET['status']==3)
	{
		//local menuid
		 //$menuId=89;
		//online
		$menuId=77;
	}
	else if($_GET['status']==2)
	{
		// local
		// $menuId=90;
		//online
		$menuId=78;
	}
	else if($_GET['status']==1)
	{
		// local 
		//$menuId=88;
		//online
		$menuId=76;
	}
	$sts =$_GET['status'] ;
}
else
{
    
    $sts = '0';
}
if($display_status){
	$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
	$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
	//$userCurrency = $obj_pro_invoice->getUserCurrencyInfo($user_type_id,$user_id);
	$addedByInfo = $obj_template->getUser($user_id,$user_type_id);
	//printr($addedByInfo);
}
if(!$obj_general->hasPermission('view',$menuId)){
	$display_status = false;
}
if(!isset($_GET['filter_edit']) || $_GET['filter_edit']==0){
	if(isset($obj_session->data['filter_data'])){
		unset($obj_session->data['filter_data']);	
	}
}
$limit = '20';
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}

if(isset($_GET['sort'])){
	$sort = $_GET['sort'];	
}else{
	$sort= 't.template_order_id';
}
if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'DESC';	
}
$client_id = isset($_GET['client_id'])?$_GET['client_id']:'';
$client = base64_decode($client_id);
$class = 'collapse';
$filter_data=array();
if(isset($obj_session->data['filter_data'])){
	$filter_order = $obj_session->data['filter_data']['order_no'];
	$filter_date = $obj_session->data['filter_data']['date'];
	$filter_by_shipment = $obj_session->data['filter_data']['filter_by_shipment'];
	$filter_ib_user_name = $obj_session->data['filter_data']['filter_ib_user_name'];
	$filter_country = $obj_session->data['filter_data']['filter_country'];
	$filter_user_name = $obj_session->data['filter_data']['filter_user_name'];
	
	
	$class = '';
	$filter_data=array(
		'order_no' => $filter_order,
		'date' => $filter_date, 
		'by_shipment' => $filter_by_shipment,
		'ib_user_name' => $filter_ib_user_name,
		'country' => $filter_country,
		'filter_user_name' => $filter_user_name,
	);
}


if(isset($_POST['btn_filter'])){
	$filter_edit = 1;
	$class = '';	
	if(isset($_POST['filter_order'])){
		$filter_order=$_POST['filter_order'];	
	}else{
		$filter_order='';
	}
	
	if(isset($_POST['filter_date'])){
		$filter_date=$_POST['filter_date'];		
	}else{
		$filter_date='';
	}
	if(isset($_POST['filter_by_shipment'])){
		$filter_by_shipment=$_POST['filter_by_shipment'];		
	}else{
		$filter_by_shipment='';
	}
	if(isset($_POST['filter_ib_user_name'])){
		$filter_ib_user_name=$_POST['filter_ib_user_name'];		
	}else{
		$filter_ib_user_name='';
	}
	if(isset($_POST['country_id'])){
		$filter_country=$_POST['country_id'];		
	}else{
		$filter_country='';
	}
	if(isset($_POST['filter_user_name'])){
		$filter_user_name=$_POST['filter_user_name'];		
	}else{
		$filter_user_name='';
	}
	$filter_data=array(
		'order_no' => $filter_order,
		'date' => $filter_date, 
		'by_shipment' => $filter_by_shipment,
		'ib_user_name' => $filter_ib_user_name,
		'country' => $filter_country,
		'filter_user_name' => $filter_user_name,
	);
	$obj_session->data['filter_data'] = $filter_data;	
}
if(isset($_POST['btn_checkout'])){
		$status = '1';
		if(isset($_POST['post']) == '')
		{
			$obj_session->data['warning'] = CHECK;
		}
		else
		{
		
			$checkoutorder = $obj_template->Checkoutrecords($status,$_POST);
			//printr($checkoutorder);
			//die;
			$obj_session->data['success'] = CHECK_OUT;
			
			page_redirect($obj_general->link($rout, 'mod=cartlist_view&status=0', '',1));
		
		}
		
	}

if(isset($_POST['btn_gen']))
{
	//printr($_POST);die;
	$obj_invoice->addBoxDetail($_POST);
	
	page_redirect($obj_general->link('invoice_test', '&mod=box_detail&invoice_no='.encode($_POST['added_invoice_id']).'&inv_status=1'.$add_url, '',1));
	//$obj_session->data['success'] = 'Your Sales Invoice Generated Successfully!!!';
}
/*if(isset($_POST['data_done']))
{
	$obj_invoice->addInvoiceData($_POST);
}*/

if($display_status) {
   
   //printr($_POST['action']);//die;
	if(isset($_POST['action']) && $_POST['action'] == "sendemail" && isset($_POST['post']) && !empty($_POST['post'])){
		if(!$obj_general->hasPermission('add',$menuId)){
			$display_status = false;
		} else {
			$obj_template->sendOrderEmail($_POST['post'],1,ADMIN_EMAIL);
		}
	}
	else if(isset($_POST['action']) && $_POST['action'] == "senddispatchemail" && isset($_POST['post']) && !empty($_POST['post'])){
		if(!$obj_general->hasPermission('add',$menuId)){
			$display_status = false;
		} else {
			$obj_template->sendOrderEmail($_POST['post'],3,ADMIN_EMAIL);
		}
	}
	else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
		//printr($_POST['post']);die;
		if(!$obj_general->hasPermission('delete',$menuId)){
			$display_status = false;
		} else {
			$obj_template->deleteorder($_POST['post']);
			$obj_session->data['success'] = UPDATE;
		}
	}
	//printr($_POST['action']);
		$checkNewCartPermission = $obj_template->checkNewCartPermission($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE']);
	$orderLimit = $obj_template->orderLimit($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE']);
	$permission = '';
	for($i=1;$i<$orderLimit;$i++)
	{
		if($checkNewCartPermission[0]['order_s_no'] == $i)
		{
			$permission =$i+1;
		}		
	}
	if($checkNewCartPermission[0]['order_s_no'] == '')
	{
		$permission =1;
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
		  	<span><?php if(isset($_GET['status']))
					{
						if($_GET['status']==0)
						$head='New Order Listing';	
						if($_GET['status']==1)
						$head='Stock Order In-Process Listing';
						if($_GET['status']==2)
						$head='Decline Order Listing';
						if($_GET['status']==3)
						$head = 'Dispatch Order Listing';
						
						$k = '&status='.$_GET['status'];
					}
					else
					{
						$head='Open Order Listing ';
						$k = '';
						
					} echo $head;?></span>
          	<span class="text-muted m-l-small pull-right">
            
            <?php
				//$menu_id = $obj_template->getMenuPermission(ORDER_DISPATCHED_ID,$obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE']);
				
			
			
			 if(($_SESSION['ADMIN_LOGIN_SWISS']=='19' && $_SESSION['LOGIN_USER_TYPE']=='2') || ($_SESSION['ADMIN_LOGIN_SWISS']=='1' && $_SESSION['LOGIN_USER_TYPE']=='1')) { ?>
              <a class="label bg-success" style="margin-left:4px;" onclick="show_dis_list_view()"><i class="fa fa-trash-o"></i> Generate Invoice</a>
            
            <!--<a class="label bg-danger" style="margin-left:4px;" onclick="formsubmitsetaction('form_list','delete','post[]','<?php //echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>-->
            <a class="label bg-danger" style="margin-left:4px;" onclick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>
            <?php } ?>
            	<?php if($obj_general->hasPermission('add',$menuId)){ 
				if($obj_session->data['LOGIN_USER_TYPE'] != 1)
				{
					if($permission ==0 && $checkNewCartPermission[0]['status']==1 ) {}
					else
					{?><?php if(isset($_GET['status']) && $_GET['status']==0) {?>
                        <a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add&s_no='.encode($permission).'&status=0'.$add_url, '',1);?>">
                        <i class="fa fa-plus"></i> New Stock Order </a>
                        <?php }elseif(!isset($_GET['status'])) {?>
                        <a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add&s_no='.encode($permission).''.$add_url, '',1);?>">
                        <i class="fa fa-plus"></i> New Stock Order </a>
                        <?php }?>
              <?php }
				} ?>
                <?php if(isset($_GET['status']) && $_GET['status']==1) {?>
				<!--<a class="label bg-primary sendmailcls" style="margin-left:4px;" onclick="formsubmitsetaction('form_list','sendemail','post[]','<?php //echo DELETE_WARNING;?>')"><i class="fa fa-envelope"></i> Send To Production</a> --> 
			<?php } 
				if(isset($_GET['status']) && $_GET['status']==3)
				{
				?>
            	<!--<a class="label bg-primary sendmailcls" style="margin-left:4px;" onclick="formsubmitsetaction('form_list','senddispatchemail','post[]','<?php //echo DELETE_WARNING;?>')"><i class="fa fa-envelope"></i> Send To Dispatche</a>-->
            <?php 
             }
				}?> 
            </span>
          </header>
             <div class="panel-body">
                        <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link($rout, 'mod=cartlist_view&status='.$sts.$add_url, '',1); ?>">
                        <section class="panel pos-rlt clearfix">
            		    <header class="panel-heading">
                    		<ul class="nav nav-pills pull-right">
                      		<li> <a href="#" class="panel-toggle text-muted active"><i class="fa fa-caret-down fa-lg text-active"></i>
                            <i class="fa fa-caret-up fa-lg text"></i></a> </li>
                   	    	</ul>
                    	<a href="#" class="panel-toggle text-muted active">	<i class="fa fa-search"></i> Search </a>
                  		</header>
                        
                  	<div class="panel-body clearfix <?php echo $class; ?>">        
                     	 <div class="row">
                        	<div class="col-lg-4">
                            	  <div class="form-group">
                                		<label class="col-lg-3 control-label">Order No</label>
                                		<div class="col-lg-8">
											<input type="text" name="filter_order" value="<?php echo isset($filter_order) ? $filter_order : '' ; ?>" placeholder="Order NO" id="input-name" class="form-control" />
                                		</div>										
                              		</div>
								</div>
								<div class="col-lg-4">
									<div class="form-group">
                                		<label class="col-lg-3 control-label">Country</label>
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
                                		<label class="col-lg-3 control-label">Date</label>
                                		<div class="col-lg-8">                                                            
                                             <input type="text" name="filter_date" readonly="readonly" data-date-format="dd-mm-yyyy" 
                                             value="<?php echo isset($filter_date) ? $filter_date : '' ; ?>" 
                                             placeholder="Date" id="input-name" class="input-sm form-control datepicker" />
                                		</div>
                              		</div>
									</div>
								
								</div>
								
							 <div class="row">
									<div class="col-lg-4">
                                                <div class="form-group">
                                                    <label class="col-lg-3 control-label">Branch Head</label>
                                                    <div class="col-lg-8">
                                                    
                                                        <?php							
														$ib_userlist = $obj_template->getInternationalUserList();
														//printr($ib_userlist);
													?>
                                					<select class="form-control" name="filter_ib_user_name">
                                    					<option value="">Please Select</option>
                                    					<?php foreach($ib_userlist as $ib_user) { ?>
                                        				<?php if(isset($filter_ib_user_name) && !empty($filter_ib_user_name) && $filter_ib_user_name == $ib_user['user_name']) { ?>
                                    					<option value="<?php echo $ib_user['user_id']; ?>" selected="selected"><?php echo $ib_user['user_name']; ?></option>
                                            			<?php } else { ?>
                                            			<option value="<?php echo $ib_user['user_id']; ?>"><?php echo $ib_user['user_name']; ?></option>
                                           				 <?php } ?>
                                        				<?php } ?>                                       
                                    				</select>
                                                    </div>
                                                </div>                             
                                            </div>
									<div class="col-lg-4">
									<div class="form-group">
                                		<label class="col-lg-3 control-label">Shipment By</label>
                                		<div class="col-lg-6">                                                            
                                            <select class="form-control" name="filter_by_shipment">
												<option value="">Please Select Shipment</option>
												<option value="By Air">By Air</option>
												<option value="By Sea">By Sea</option>
											</select>
                                		</div>
                              		</div>
									</div>
									<div class="col-lg-4">
									<div class="form-group">
                                		<label class="col-lg-3 control-label">Posted By</label>
                                		<div class="col-lg-6">     
											<?php							
														$userlist = $obj_template->getUserList();
														//printr($ib_userlist);
													?>
                                            
                                                    <select class="form-control" name="filter_user_name">
                                                        <option value="">Please Select</option>
                                                        <?php foreach ($userlist as $user) { ?>
                                                            <?php if (isset($splitdata) && $splitdata[0] == $user['user_type_id'] && $splitdata[1] == $user['user_id']) { ?>

                                                                <option value="<?php echo $user['user_type_id'] . "=" . $user['user_id']; ?>" selected="selected"><?php echo $user['user_name']; ?></option>
                                                            <?php } else { ?>
                                                                <option value="<?php echo $user['user_type_id'] . "=" . $user['user_id']; ?>"><?php echo $user['user_name']; ?></option>
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
                        <a href="<?php echo $obj_general->link($rout, 'mod=cartlist_view&status='.$sts.$add_url, '',1); ?>" class="btn btn-info btn-sm pull-right" ><i class="fa fa-refresh"></i> Refresh</a>
                       </div> 
                    </div>
                  </footer>                                  
              </section>
           </form>    
            <div class="col-lg-3 pull-right">	
                <select class="form-control" id="limit-dropdown" onchange="location=this.value;">
                <option value="<?php echo $obj_general->link($rout, ''.$add_url, '',1);?>" selected="selected">--Select--</option>	
					<?php 
                        $limit_array = getLimit(); 
                        foreach($limit_array as $display_limit) {
                            if($limit == $display_limit) {	 
                    ?>
                            <option value="<?php echo $obj_general->link($rout, 'mod=cartlist_view&status='.$sts.'&limit='.$display_limit.$add_url, '',1);?>" selected="selected"><?php echo $display_limit; ?></option>				
                    <?php } else { ?>
                            <option value="<?php echo $obj_general->link($rout, 'mod=cartlist_view&status='.$sts.'&limit='.$display_limit.$add_url, '',1);?>"><?php echo $display_limit; ?></option>
                    <?php } ?>
                    <?php } ?>
                 </select>
           </div>
             <label class="col-lg-1 pull-right" style="margin-top:5px;">Show</label>             
          </div>
           <header class="panel-heading text-right"> 
                                            <ul class="nav nav-tabs pull-left" id="view_list"> 
                                            <li class=""><a href="<?php echo $obj_general->link($rout, 'mod=cartlist_view'.$k.'&temp_status=1'.$add_url, '',1);?>">Last 5 Days</a></li> 
                                             <li class=""><a href="<?php echo $obj_general->link($rout, 'mod=cartlist_view'.$k.'&temp_status=2'.$add_url, '',1);?>">Last 10 Days</a></li> 
                                              <li class=""><a href="<?php echo $obj_general->link($rout, 'mod=cartlist_view'.$k.'&temp_status=3'.$add_url, '',1);?>"><?php /*?><a href="#detaillast15days" data-toggle="tab" onclick="detail_getdata('last15days')"><?php */?>Last 15 Days</a></li> 
                                               <li class=""><a href="<?php echo $obj_general->link($rout, 'mod=cartlist_view'.$k.'&temp_status=4'.$add_url, '',1);?>">Last 20 Days</a></li> 
                                                <li class=""><a href="<?php echo $obj_general->link($rout, 'mod=cartlist_view'.$k.'&temp_status=5'.$add_url, '',1);?>">Last 30 Days</a></li> 
                                                <li class="active"><a href="<?php echo $obj_general->link($rout, 'mod=cartlist_view'.$k.'&temp_status=6'.$add_url, '',1);?>">Last 45+Days</a></li>
                                            </ul>
                                            </header>
          	
           
           
          	<div class="table-responsive">
          	   
               <form name="form_list" id="form_list" method="post">
                 <input type="hidden" id="action" name="action" value="" />
                <table id="quotation-row" class="table b-t text-small table-hover">
                  <thead>
                    <tr>
                       
                        <?php ?>
                        <th><input type="checkbox"/></th>
                        <?php 
							$st = '';
							
							if(isset($_GET['status']))
							 {
							 	$st = '&status='.$_GET['status'];
							 }		 ?>
                      	
                        <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">  Order No
                       		<span class="th-sort">
                            	<a href="<?php echo $obj_general->link($rout, 'mod=cartlist_view'.$st.'&sort=gen_order_id'.$add_url.'&order=ASC', '',1);?>">
                                	<i class="fa fa-sort-down text"></i>
                                    
                                <a href="<?php echo $obj_general->link($rout, 'mod=cartlist_view'.$st.'&sort=gen_order_id'.$add_url.'&order=DESC', '',1);?>">
                                <i class="fa fa-sort-up text-active"></i>
                            <i class="fa fa-sort"></i></span>
                     </th> 
                       
                     <th>Date</th>
                     <th>Client Name</th>
                     <th>Buyers Order No </th>
                     <?php if($addedByInfo['country_id']=='252'|| $addedByInfo['country_id']=='112'|| $addedByInfo['country_id']=='251'||$addedByInfo['country_id']=='253'|| $addedByInfo['country_id']=='230'|| $addedByInfo['country_id']=='170'||$addedByInfo['country_id']=='172'||$addedByInfo['country_id']=='90'){ ?>
                     <th>Reference No</th>
                     <?php } ?>
                     <?php /*?>     // sonu add order_type 19-1-2017<?php */?>
                     <th>Order Type </th>
                     <th>Shippment Country</th>
                     <th>Transportation</th> 
                     <th align="center">&nbsp;</th>
                     <th>Posted By</th>
                    <?php if(isset($_GET['status']) && $_GET['status']==1) ?>
                    	<th></th> 
                  </tr>
                  </thead>
                  <tbody>
                  <?php
				  $dis_cond=$dis_table=$dis_select=$sen='';
					if(isset($_GET['status']))
					{
						$cond = 'AND (sos.status="'.$_GET['status'].'" '.$dis_cond.') AND t.status=1';
						if($_GET['status']==3)
						{
							$dis_cond = ' (t.template_order_id=sodh.template_order_id AND t.product_template_order_id = sodh.product_template_order_id AND sodh.status=0)';
							//'OR (t.template_order_id=sodh.template_order_id AND t.product_template_order_id = sodh.product_template_order_id)';
							$dis_table = ', stock_order_dispatch_history_test as sodh';	
							$dis_select = 'sum(sodh.dis_qty) as  dis_qty,sum(sodh.dis_qty*t.price) as dis_total_price,';
							$sen = 'Dispatched';
							$cond = 'AND ('.$dis_cond.') AND t.status=1';
							//$dis_cond = 'OR (sos.status = 1) OR (sos.status = 2)';
						}
						else if($_GET['status']==2)
						{
							$dis_cond = 'AND (t.template_order_id=sodh.template_order_id AND t.product_template_order_id = sodh.product_template_order_id AND sodh.status=2)';
							$dis_table = ', stock_order_dispatch_history_test as sodh';	
							$dis_select = 'sum(sodh.decline_qty) as  dis_qty,sum(sodh.decline_qty*t.price) as dis_total_price,';
							$sen = 'Declined';
							$cond = 'AND (sos.status="'.$_GET['status'].'" '.$dis_cond.') AND t.status=1';
						}
						
						
						if($_GET['status']==0)
							$mod='index';
						if($_GET['status']==1)
							$mod='in_process';
						if($_GET['status']==2)
							$mod='decline';
						if($_GET['status']==3)
							$mod = 'dispatch';
						$tot_status=1;
						$s = $_GET['status'];//Define for status
						$page_s = '&status='.$s;
					}
					else
					{
						$cond = 'AND t.status="0"';	
						$mod='cart_list';
						$tot_status=0;
						$s = '0';
						$page_s='';
					}
					if(isset($_GET['temp_status']) && $_GET['temp_status']!='6')
					{
						if($_GET['temp_status']=='1')
							$interval=5;
						if($_GET['temp_status']=='2')
							$interval=10;
						if($_GET['temp_status']=='3')
							$interval=15;
						if($_GET['temp_status']=='4')
							$interval=20;
						if($_GET['temp_status']=='5')
							$interval=30;
					}
					else
					{
						$interval='';
					}
				 $total_orders = $obj_template->GetTotalCartOrderList($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],$cond,isset($_GET['status']),$filter_data,$interval,$dis_table,$dis_select,$s,$address_id);
					//printr($total_orders);
				  	  $pagination_data = '';
                      if($total_orders){
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
						  
					);
                  
                     $orders = $obj_template->GetCartOrderList($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],$cond,isset($_GET['status']),$filter_data,$interval,$dis_table,$dis_select,$option,$s,$address_id);
					//printr($orders);
					 $f = 1;$total=0;$total_qty=0; $temp_order_id='';
					 $value='';
					 if($orders)
					 {
					 foreach($orders as $order){
					     
					     
					     /* if($order['stock_print']=='Digital Print'){
        					     $stock_print='<b>('.$order['stock_print'].'</b>)';
        					     $digital_color=$obj_template->GetdigitalColorName($order['digital_print_color']);
        					    // printr($digital_color);
        					     $d_color='<b>Digital Printing With </b>'.$digital_color;
        					 }else{
        					     $stock_print='';
        					      $d_color='';
        					 }*/
					 
						//printr($order);
						$cust_cond='';
						if(isset($order['custom_order_id']))
						{
							$cust_cond = '&multi_custom_order_id='.encode($order['stock_order_id']);
						}
					 	$order_qty = $order['total_qty'];
						$total_price = $order['total_price'];
						if(isset($_GET['status']))
						{
							if($_GET['status']==3 || $_GET['status']==2)
							{
								$order_qty = $order['dis_qty'];
								$total_price = $order['dis_total_price'];
							}
						}
					/* if(isset($_GET['status']))
					 {
					  if($_GET['status']==1 || $_GET['status']==3)
					   {*/
					   
					  /* if($_SESSION['ADMIN_LOGIN_SWISS']=='1' && $_SESSION['LOGIN_USER_TYPE']=='1' || !isset($_GET['status']))
					   {*/
						
						   ?>
                           <td><input type="checkbox" name="post[]" value="<?php echo $order['template_order_id'].'=='.$order['product_template_order_id'].'=='.$order['client_id'].'=='.$order['gen_order_id'];?>"/></td>
                           <?php //}
						   // } ?>
                       
                           <td><a href="<?php echo $obj_general->link($rout, 'mod='.$mod.'&client_id='.encode($order['client_id']).'&stock_order_id='.encode($order['stock_order_id']).''.$cust_cond.''.$add_url, '',1);?>"><?php echo $order['gen_order_id'];?></a></td>
                           <?php					  
				 $total = $obj_template->totalCount($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],$order['client_id'],$tot_status,$order['stock_order_id'],$s);
				//printr($total); 
					   ?>                       
							  <td><a href="<?php echo $obj_general->link($rout, 'mod='.$mod.'&client_id='.encode($order['client_id']).'&stock_order_id='.encode($order['stock_order_id']).''.$cust_cond.''.$add_url,'',1);?>"><?php echo dateFormat(4,$order['date_added']);?></a></td>
                              
							  <td>
								<?php
								if(isset($_GET['status']) && $_GET['status']==1)
								{
									$noteinfo = '';
									if(!isset($order['custom_order_id']))
									{
										$noteorders = $obj_template->GetOrderList($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],'AND t.status = 1 AND sos.status=1','','','',$order['client_id'],'','','','','',$order['stock_order_id']);
									//printr($noteorders);
										
										$noteinfo .= '<div>';
										$noteinfo .= '<div>';
										foreach($noteorders as $noteorder)
										{
											$noteinfo .=$noteorder['note'].'<br />';
										}
										$noteinfo .= '</div>';
								}
								?>
								<a  data-trigger="hover" data-toggle="popover" data-html="true" data-placement="top" data-content='<?php echo $noteinfo;?>' title="" data-original-title="<b><?php echo $order['client_name'].' Note';?></b>">
                               <span class="" style="font-size: 100%; "><?php echo $order['client_name'];?></span>
                               <?php 
								}
								else
								{
								?>
                                <a href="<?php echo $obj_general->link($rout, 'mod='.$mod.'&client_id='.encode($order['client_id']).'&stock_order_id='.encode($order['stock_order_id']).''.$cust_cond.''.$add_url,'',1);?>"><?php echo $order['client_name'];?>
                                </a>
                                <?php
								}
								?>
                               </a>
							  </td>
                              
                              <?php //mansi 20-1-2016(change for display buyers order no on index page) ?>
                              <td><a href="<?php echo $obj_general->link($rout, 'mod='.$mod.'&client_id='.encode($order['client_id']).'&stock_order_id='.encode($order['stock_order_id']).''.$cust_cond.''.$add_url,'',1);?>"><?php echo $order['buyers_order_no']; ?></td>
                               <?php //sejal 14-04-2017 add ref.no. ?>
                              <?php if($addedByInfo['country_id']=='112'||$addedByInfo['country_id']=='252'|| $addedByInfo['country_id']=='251'||$addedByInfo['country_id']=='172'||$addedByInfo['country_id']=='209'||$addedByInfo['country_id']=='253'|| $addedByInfo['country_id']=='230'|| $addedByInfo['country_id']=='170'||$addedByInfo['country_id']=='90'){ ?>
                               <td><a href="<?php echo $obj_general->link($rout, 'mod='.$mod.'&client_id='.encode($order['client_id']).'&stock_order_id='.encode($order['stock_order_id']).''.$cust_cond.''.$add_url,'',1);?>"><?php echo $order['reference_no']; ?></td>
                              <?php } ?>
                              <td><?php echo $order['order_type']; ?></td>
							 <td>
                 			 <a href="<?php echo $obj_general->link($rout, 'mod='.$mod.'&client_id='.encode($order['client_id']).'&stock_order_id='.encode($order['stock_order_id']).''.$cust_cond.''.$add_url, '',1);?>">     
                             <?php if($order['ship_type'] == '0')
							 {
								 $ship_type = 'Self';
							 }
							 else
							 {
								 $ship_type = 'Client';
							 }
							 echo $order['country_name'].' / '.$ship_type;?><br/>
                              </a>  	
                              </td>
                              <td><?php $air = isset($total['tran']['By Air']) ? $total['tran']['By Air'] : '';
							  			 $sea = isset($total['tran']['By Sea']) ? $total['tran']['By Sea'] : '';
										 $slash ='';
										 if($air!= '' && $sea!='')
										 {
										 	$slash = ' / ';
										 }
							  			echo $air.''.$slash.''.$sea;?></td>
                              <td><?php echo '<table cellpadding="0" cellspacing="0"><tr>
						  <td><b>Total : </b>'.$total['total_count']['total'].'</td><td><b>Accepted : </b>'.$total['total_count']['accepted'].'</td><td><b>Decline : </b>'.$total['total_count']['decline'].'</td><td><b>Dispatch : </b>'.$total['total_count']['dispatch'].'</td><td><b>Pending : </b>'.$total['total_count']['pending'].'</td><td><b>Total '.$sen.' Qty : </b>'.$order_qty.'</td><td><b>Total Price : </b>'.$total_price.' '.$order['currency_code'].'</td></tr></table>';
							 //echo 'Total : '.$total['total'].'Accepted :'.$total['accepted'].' Decline :'.$total['decline'].' Dispatch :'.$total['dispatch'].' Pending :'.$total['pending'];?></td>
							  <td>  <?php
                                     $postedByData = $obj_template->getUser($order['user_id'],$order['user_type_id']);
                                     $addedByImage = $obj_general->getUserProfileImage($order['user_type_id'],$order['user_id'],'100_');
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
                                     <a  data-trigger="hover" data-toggle="popover" data-html="true" data-placement="top" data-content='<?php echo $postedByInfo;?>' title="" data-original-title="<b><?php echo $postedByName;?></b>">
                                     <span class="label bg-info" style="font-size: 100%; "><?php echo $postedByData['user_name'];?></span>
                                     </a> </td>
                                      <?php if(isset($_GET['status']) && $_GET['status']==1) { ?>
                                    	 <td> <input type="button" name="approve<?php echo $f;?>" id="approve<?php echo $f;?>" value="Send Mail"  class="btn btn-success" onclick="acceptmail(<?php echo $f;?>,<?php echo $order['product_template_order_id'];?>,<?php echo $order['client_id'];?>)" /></td>
                                        <?php } ?>
                                         <input type="hidden" name="admin" id="admin" value="<?php echo ADMIN_EMAIL;?>" />
                            </tr>
                         <?php 
						 $value = $value.'='.$order['product_template_order_id'];
						  $temp_order_id = $temp_order_id.'='.$order['template_order_id'];
							$f++;
						} }
						?>
							 <input type="hidden" name="product_template_order_id" id="product_template_order_id" value="<?php echo $value;?>" />
                        <input type="hidden" name="template_order_id" id="template_order_id" value="<?php echo $temp_order_id;?>" />
              </div>
                         <?php 
						  			$pagination = new Pagination();
                                    $pagination->total = $total_orders;
                                    $pagination->page = $page;
                                    $pagination->limit = $limit;
                                    $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                                    $pagination->url = $obj_general->link($rout,'&mod=cartlist_view'.$page_s.'&page={page}&limit='.$limit.$add_url, '',1);//HTTP_ADMIN.'index.php?rout='.$rout.'&page={page}';
                                    $pagination_data = $pagination->render();
                                    //echo $pagination_data;die;
						 } else{ 
                            echo "No record found !";
                         } ?>
                   </div>
                    </tbody>
                </table>
                    <div style="margin-right: 20px;width: 100%;text-align: right;padding-right: 20px;">
                         <input type="hidden" name="count" id="count" value="<?php echo $f-1;?>" />
                        <?php if($obj_session->data['LOGIN_USER_TYPE'] != 1 && !isset($_GET['status']))
				{?>
                   <div style="text-align: center;">
                         <button type="submit" name="btn_checkout" class="btn btn-primary">Submit Order</button>
                        </div>
                        
                       
                 <?php }?>    
                         <?php if($obj_session->data['LOGIN_USER_TYPE'] != 1)
				{?>                
                   <?php } ?>
                      </div>
                 </form> 
                  
            </div>
            <footer class="panel-footer">
                        <div class="row">
                            <div class="col-sm-3 hidden-xs"> </div>
                            <?php echo $pagination_data;?>
                        </div>
                    </footer>
         </section>
         </div>
        </div>
     </section>
</section>

<!--model for dispatch list-->
<div class="modal fade" id="show_dispatch" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:85%;">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="sform" id="form_dis" style="margin-bottom:0px;">
        		<input type="hidden" name="curr_date" id="curr_date" value="<?php echo date("Y-m-d");?>"  />
              <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                   	<h4 class="dispatch" id="myModalLabel">Generate Invoice</h4>
              </div>
              
              <div class="modal-body">
              		<div class="form-group">
                             <label class="col-lg-3 control-label">Order Type</label>
                             <div class="col-lg-7">
                             <input type="radio" name="order_type" id="order_type" value="sample" />Sample Order</label> 
                     				<label  style="font-weight: normal;">
                                  <input type="radio" name="order_type" id="order_type" value="commercial"  checked="checked" />Commercial Order</label>
                             </div>
                    </div>
              
              			
              		<div class="form-group">
                            <label class="col-lg-3 control-label">User</label>
                             <?php							
									$userlist = $obj_template->getUserList();
								?>
                             <div class="col-lg-7">
                                	<select class="form-control" name="user_name" id="user_name" onchange="get_user_dis_data()">
                                    	<option value="">Please Select</option>
                                    	<?php foreach($userlist as $user) { ?>
                                        		<option value="<?php echo $user['user_type_id']."=".$user['user_id']; ?>"><?php echo $user['user_name']; ?></option>
                                        <?php } ?>                                       
                                    </select>
                                </div>
                           </div>
                           
              </div>
              <div style="display:none;" id="dispatch_list_div">
              	
                	
              	
              </div>
                 
                 
                 <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                    <button type="button"  name="data_done" id="data_done" onclick="addInvoiceData()" class="btn btn-warning">Done</button>
                    <button type="submit"  name="btn_gen" id="btn_gen" class="btn btn-warning" style="display:none;">Generate</button>
                  </div>
   		</form>   
    </div>
  </div>
</div>

<style>
	@media 
	only screen and (max-width: 760px),
	(min-device-width: 768px) and (max-device-width: 1024px)  {
		
		.div_len{width:100%;
				overflow-y: auto;
				_overflow: auto;}
		
	}
	.div_len{width:90%;}
</style>
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script type="application/javascript">
function detail_getdata(data)
{
	var u=window.location.href;
	var url=u+'&temp_status='+data;
	location.href=url;
	$("#detail").attr('id','detail'+data);
            
}

$(document).ready(function () {
	jQuery("#form_dis").validationEngine();
       $("ul.nav > li").removeClass("active");
		var u=window.location.href;
		if(window.location.href.indexOf("temp_status") > -1) {
		 	$("ul.nav > li a[href*='"+u+"']").parent("ul.nav > li").addClass("active");
		}
		else
		{
			var ul=u+'&temp_status=6';
		 	$("ul.nav > li a[href*='"+ul+"']").parent("ul.nav > li").addClass("active");
		}
    });

function reloadPage(){
	location.reload();
}
function removeTemplateOrder(template_order_id){
	var remove_templateorder_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=removeTemplateOrder'.$add_url, '',1);?>");
	$.ajax({
		url : remove_templateorder_url,
		method : 'post',
		data : {template_order_id : template_order_id},
		success: function(response){
			set_alert_message('Successfully Deleted',"alert-success","fa-check");
			reloadPage();	
		},
		error: function(){
			return false;	
		}
	});
	}
	
function acceptmail(id,product_template_order_id,client_id)
{	
	$(".note-error").remove();
	var postArray = {};
	postArray['product_template_order_id'] = product_template_order_id;
	postArray['client_id'] = client_id;
	postArray['status'] ='';
	postArray['send'] ='send';
	var adminEmail = $("#admin").val();
	var d = new Date();
	var curr_date = d.getDate();
	var curr_month = d.getMonth();
	curr_month++; 
	var curr_year = d.getFullYear();
	var formattedDate = curr_date + "-" + curr_month + "-" + curr_year;
	postArray['currdate'] = formattedDate;
	$('#loading').show();	
	var order_status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=updatestockorderstatus'.$add_url, '',1);?>");
	$.ajax({
		url : order_status_url,
		method : 'post',
		data : {postArray : postArray,adminEmail:adminEmail},
		success: function(response){
			//alert(response);
			set_alert_message('Successfully Updated',"alert-success","fa-check");
			$('#loading').hide();
			 window.setTimeout(function(){location.reload()},1000)
			},
			error: function(){
				return false;	
			}
		});
}

function show_dis_list_view()
{
	$("#show_dispatch").modal('show');
}
function get_user_dis_data()
{
	var user_info = $("#user_name").val();
//	console.log( user_info);
	var order_type = $('input[name=order_type]:radio:checked').val();
	var data_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=get_user_dis_data'.$add_url, '',1);?>");
	$.ajax({
		url : data_url,
		method : 'post',
		data : {user_info : user_info,order_type:order_type},
		success: function(response){
			console.log(response);
			var val = $.parseJSON(response);
			///console.log(val.total_row);
			//alert($("#total_td").val());
			if(val.total_row=='0')
			{
				$("#data_done").hide();
			}
			else
			{	
				   $("#data_done").show();
				   if(val.response==1)
					{
						$('#stock_th').show();	
						$('#stock_td').show();
						$('#cust_th').show();
						$('#cust_td').show();
						
						
					}
					else
					{					
						$("#dispatch_list_div").show();
						$("#dispatch_list_div").html(val.response);	
						
					}
			}
				
			},
			error: function(){
				return false;	
			}
		});
}
function addInvoiceData()
{
	 
		var formData = $("#form_dis").serialize();
		var ship_country=$('#ship_country').val();

		var data_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=addInvoiceData'.$add_url, '',1);?>");
		$.ajax({
			url : data_url,
			method : 'post',
			data : {formData : formData},
			//contentType : 'application/json',
			//dataType:"json",
			success: function(response){
				//	console.log(response);
					var data=JSON.parse(response);
					var cnt=data.length;
					
					var total_td = $("#total_td").val();
					//alert(data+'======'+cnt+'=='+total_td);
					if(cnt > 0)
					{
						   $.each(data, function (i,item) {
						        console.log(item);
								$("#div_td_"+i).show();
								$("#box_td_"+i).show();
								$("#tot_box_td_"+i).show();
								$("#box_qty_td_"+i).show();   
						
							     if(ship_country==170 || ship_country==253 ){
							         
							     $("#item_no_td_"+i).show();  
							     }
								$("#cust_div_td_"+i).show();
								$("#cust_box_td_"+i).show();
								$("#cust_tot_box_td_"+i).show();
								$("#cust_box_qty_d_"+i).show();
								$("#cust_jobcard_name_td_"+i).show();
								
								$("#added_invoice_id").val(item.added_invoice_id);
								
								//alert();
								//if(item.cust_box_weight!='')
								var div_attr=$("#boxweight_"+i).attr("div_attr");
								if(div_attr=='cust')
								{
									if(item.transportation=='sea')
									{
										$("#boxweight_"+i).val(item.cust_box_weight);
										$("#netweight_"+i).val(item.cust_net_weight);
										$("#box_qty_"+i).val(item.cust_quantity);
										var dis_qty = $("#cust_dis_qty_"+i).val();
										var total_boxes = dis_qty/item.cust_quantity;
										$("#total_box_"+i).val(parseInt(total_boxes));
										var fill_qty = item.cust_quantity * parseInt(total_boxes);
										var rem_qty = dis_qty - fill_qty;
										if(dis_qty>=rem_qty)
										{
											$("#cust_rem_qty_td_"+i).show();
											$("#rem_qty_"+i).attr('readonly','readonly');
											$("#rem_qty_"+i).val(parseInt(rem_qty));
											$("#stk_rem_qty_th").show();
										}
									}
									else
									{
										$("#boxweight_"+i).val();
										$("#netweight_"+i).val();
										$("#box_qty_"+i).val();
										$("#total_box_"+i).val();
										$("#rem_qty_"+i).val();
									}
									$("#cust_invoiceid_"+i).val(item.invoice_ids);
								}
								else
								{
									if(item.transportation=='sea')
									{
									
										$("#netweight_"+i).val(item.net_weight);
										$("#boxweight_"+i).val(item.box_weight);
										$("#box_qty_"+i).val(item.box_qty);
										var dis_qty = $("#dis_qty_id_"+i).val();
										var total_boxes = dis_qty/item.box_qty;
										$("#total_box_"+i).val(parseInt(total_boxes));
										
										var fill_qty = item.box_qty * parseInt(total_boxes);
										var rem_qty = dis_qty - fill_qty;
										if(dis_qty>rem_qty && rem_qty!='0')
										{
											$("#rem_qty_td_"+i).show();
											$("#rem_qty_"+i).attr('readonly','readonly');
											$("#rem_qty_"+i).val(parseInt(rem_qty));
											$("#cust_rem_qty_th").show();
										}
									}
									else
									{
										$("#netweight_"+i).val();
										$("#boxweight_"+i).val();
										$("#box_qty_"+i).val();
										$("#total_box_"+i).val();
										$("#rem_qty_"+i).val();
										
									}
									$("#stk_invoiceid_"+i).val(item.invoice_ids);
								}
								
						   });
					}
					
					$('#stk_net_th,#stk_box_th,#stk_tot_box_th,#stk_boxqty_th').show();	
					$('#cust_net_th,#cust_boxqty_th,#cust_tot_box_th,#cust_box_th').show();
					$("#data_done").hide();
					//$("#stk_div_hide,#cust_div_hide").remove();
					
					$('#btn_gen').show();
				},
				error: function(){
					return false;	
				}
			});
	
}
$('input:radio[name=order_type]').change(function () {
	$("#user_name").val('');
	$("#dispatch_list_div").hide();
	$("#data_done").hide();
	$("#dispatch_list_div").html('');	
});

function Remove_order_for_invoice(order_id,n){
	//alert("hii");
	var remove_templateorder_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=removeOrderForInvoice', '',1);?>");
	$.ajax({
		url : remove_templateorder_url,
		method : 'post',
		data : {order_id:order_id,n:n},
		success: function(response){
		/*	if(n==1){
				$('#stock-row-'+order_id).remove();
			}else{
				$('#custom-row-'+order_id).remove();
			}*/
			
				 window.setTimeout(function(){location.reload()},1000)
		},
			error: function(){
				return false;	
			}
		
	});
}
//added by sonu
function updateDoneStatus(){
         var tem_id = [];
            $(':checkbox:checked').each(function(i){
              tem_id[i] = $(this).val();
            });
        
		var updateDoneStatus = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=updateDoneStatus', '',1);?>");
    	$.ajax({
				url : updateDoneStatus,
				method : 'post',		
				data : {tem_id : tem_id},
				success: function(response){
				    	 window.setTimeout(function(){location.reload()},1000)
    
    		},
    			error: function(){
    				return false;	
    			}
    		
    	});
}


</script>         
<style>
pre {
	display: inline;
	 padding:0px; 
	 margin: 0 0 0px; 
	font-size: 13px;
	 line-height: 0; 
	 word-break:normal; 
	 word-wrap:normal; 
	 background-color:transparent; 
	 border: 0px solid #ccc; 
	 border-radius: 0px; 
}
</style>  
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>