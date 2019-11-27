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

if(isset($_GET['status']))
{
if($_GET['status']==0)
{
	//local
	 //$menuId=83;
	 //online
	$menuId = 203;
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
}
//printr($menuId);
//die;
//printr($menuId);
if(!$obj_general->hasPermission('view',$menuId)){
	//printr($menuId);
	$display_status = false;
}
if(!isset($_GET['filter_edit']) || $_GET['filter_edit']==0){
	if(isset($obj_session->data['filter_data'])){
		unset($obj_session->data['filter_data']);	
	}
}
$limit = LISTING_LIMIT;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}

if(isset($_GET['sort'])){
	$sort = $_GET['sort'];	
}else{
	$sort=' st.stock_order_id';
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
$status='';
if(isset($_POST['btn_checkout'])){
		$status = '1';
		if(isset($_POST['post']) == '')
		{
			$obj_session->data['warning'] = CHECK;
		}
		else
		{
		
		//printr($_POST['post']); die;
			$checkoutorder = $obj_template->Checkoutrecords($status,$_POST);
			//printr($checkoutorder);
			//die;
			$obj_session->data['success'] = CHECK_OUT;
			
			//die;
			page_redirect($obj_general->link($rout, 'mod=cartlist_view&status=0', '',1));
		
		}
		
	}
	

if($display_status) {
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
	
	if(isset($_POST['action']) && $_POST['action'] == "sendemail" && isset($_POST['post']) && !empty($_POST['post'])){
		if(!$obj_general->hasPermission('add',$menuId)){
			$display_status = false;
		} else {
			$obj_template->sendOrderEmail($_POST['post'],1,ADMIN_EMAIL);
		}
	}
	if(isset($_POST['action']) && $_POST['action'] == "senddispatchemail" && isset($_POST['post']) && !empty($_POST['post'])){
		if(!$obj_general->hasPermission('add',$menuId)){
			$display_status = false;
		} else {
			$obj_template->sendOrderEmail($_POST['post'],3,ADMIN_EMAIL);
		}
	}
	if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
		if(!$obj_general->hasPermission('delete',$menuId)){
			$display_status = false;
		} else {
			$obj_template->deleteorder($_POST['post']);
			$obj_session->data['success'] = UPDATE;
		}
	}
    //[kinjal] : 0n 7-6-2017
	if(isset($_POST['action']) && $_POST['action'] == "accept" && isset($_POST['post']) && !empty($_POST['post'])){
	    //$d = $obj_general->hasPermission('accept',$menuId);
	    //printr($d);die;
		if(!$obj_general->hasPermission('edit',$menuId))
		{
		    //echo $menuId;die;
		    $display_status = false;
		} else {
		//	printr($menuId);die;
			$obj_template->Acceptorder($_POST['post']);//die;
		
			$obj_session->data['success'] = UPDATE;
		}
	}
	if(isset($_POST['action']) && $_POST['action'] == "decline" && isset($_POST['post']) && !empty($_POST['post'])){
	  
		if(!$obj_general->hasPermission('edit',$menuId))
		{
		 
		    $display_status = false;
		} else {

			$obj_template->Declineorder($_POST['post']);//die;
			$obj_session->data['success'] = UPDATE;
		}
	}
	//end [kinjal]
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
          <header class="panel-heading bg-white"><meta http-equiv="Content-Type" content="text/html; charset=windows-1252"> 
		  	<span><?php if(isset($_GET['status']))
					{
						if($_GET['status']==0)
						$head='Indian New Order Listing';	
					}
					echo $head;?></span>
          	<span class="text-muted m-l-small pull-right">
            <?php if($_SESSION['ADMIN_LOGIN_SWISS']=='1' && $_SESSION['LOGIN_USER_TYPE']=='1') { ?>
            <a class="label bg-danger" style="margin-left:4px;" onClick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>
            <?php } ?>
              <a class="label bg-success" style="margin-left:4px;" onClick="formsubmitsetaction('form_list','accept','post[]','<?php echo DELETE_WARNING;?>')"><i class="label bg-info "></i> Accept</a>   
              <a class="label bg-success" style="margin-left:4px;" onClick="formsubmitsetaction('form_list','decline','post[]','<?php echo DELETE_WARNING;?>')"><i class="label bg-info "></i> Decline</a>
            	<?php if($obj_general->hasPermission('add',$menuId)){ 
				//printr($menuId);
				//die;
				if($obj_session->data['LOGIN_USER_TYPE'] != 1)
				{
					if($permission ==0 && $checkNewCartPermission[0]['status']==1 ) {}
					else
					{?><?php if(isset($_GET['status']) && $_GET['status']==0) {?>
                        <a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add&s_no='.encode($permission).'&status=0', '',1);?>">
                        <i class="fa fa-plus"></i> New Stock Order </a>
                        <?php }elseif(!isset($_GET['status'])) {?>
                        <a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add&s_no='.encode($permission).'', '',1);?>">
                        <i class="fa fa-plus"></i> New Stock Order </a>
                        <?php }?>
              <?php }
				} ?>
                <?php if(isset($_GET['status']) && $_GET['status']==1) {?>
				<a class="label bg-primary sendmailcls" style="margin-left:4px;" onClick="formsubmitsetaction('form_list','sendemail','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-envelope"></i> Send To Production</a>  
			<?php } 
				if(isset($_GET['status']) && $_GET['status']==3)
				{
				?>
            	<a class="label bg-primary sendmailcls" style="margin-left:4px;" onClick="formsubmitsetaction('form_list','senddispatchemail','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-envelope"></i> Send To Dispatched</a>
            <?php 
             }
				}?> 
            </span>
          </header>
             <div class="panel-body">
                        <form class="form-horizontal" method="post" data-validate="parsley" 
                        action="<?php echo $obj_general->link($rout, 'mod=indian_new_order&status='.$_GET['status'], '',1); ?>">
                        <section class="panel pos-rlt clearfix">
            		    <header class="panel-heading">
                    		<ul class="nav nav-pills pull-right">
                      		<li> <a href="#" class="panel-toggle text-muted active"><i class="fa fa-caret-down fa-lg text-active"></i>
                            <i class="fa fa-caret-up fa-lg text"></i></a> </li>
                   	    	</ul>
                    		<i class="fa fa-search"></i> Search
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
                                                            <?php //if (!empty($splitdata) && $splitdata[0] == $user['user_type_id'] && $splitdata[1] == $user['user_id']) { ?>

                                                               <!-- <option value="<?php //echo $user['user_type_id'] . "=" . $user['user_id']; ?>" selected="selected"><?php //echo $user['user_name']; ?></option>-->
                                                            <?php //} else { ?>
                                                                <option value="<?php echo $user['user_type_id'] . "=" . $user['user_id']; ?>"><?php echo $user['user_name']; ?></option>
                                                            <?php //} ?>
                                                        <?php } ?>                                       
                                                    </select>
                                                
                                		</div>
                              		</div>
									</div>
									
                              </div>
             
                  <footer class="panel-footer <?php echo $class; ?>">
                    <div class="row">
                       <div class="col-lg-12">
                        <input type="hidden" value="<?php echo $status;?>" id="status" name="status" />
                        <button type="submit" class="btn btn-primary btn-sm pull-right ml5" name="btn_filter"><i class="fa fa-search"></i> Search</button>
                        <a href="<?php echo $obj_general->link($rout, 'mod=indian_new_order&status='.$_GET['status'], '',1); ?>" class="btn btn-info btn-sm pull-right" ><i class="fa fa-refresh"></i> Refresh</a>
                       </div> 
                    </div>
                  </footer>                                  
              </section>
           </form>    
            <div class="col-lg-3 pull-right">	
                <select class="form-control" id="limit-dropdown" onChange="location=this.value;">
                <option value="<?php echo $obj_general->link($rout, '', '',1);?>" selected="selected">--Select--</option>	
					<?php 
                        $limit_array = getLimit(); 
                        foreach($limit_array as $display_limit) {
                            if($limit == $display_limit) {	 
                    ?>
                            <option value="<?php echo $obj_general->link($rout, 'mod=indian_new_order&status='.$_GET['status'].'&limit='.$display_limit, '',1);?>" selected="selected"><?php echo $display_limit; ?></option>				
                    <?php } else { ?>
                            <option value="<?php echo $obj_general->link($rout, 'mod=indian_new_order&status='.$_GET['status'].'&limit='.$display_limit, '',1);?>"><?php echo $display_limit; ?></option>
                    <?php } ?>
                    <?php } ?>
                 </select>
           </div>
             <label class="col-lg-1 pull-right" style="margin-top:5px;">Show</label>             
          </div>
           <header class="panel-heading text-right"> 
                                            <ul class="nav nav-tabs pull-left" id="view_list"> 
                                            <li class=""><a href="<?php echo $obj_general->link($rout, 'mod=indian_new_order&status='.$_GET['status'].'&temp_status=1', '',1);?>">Last 5 Days</a></li> 
                                             <li class=""><a href="<?php echo $obj_general->link($rout, 'mod=indian_new_order&status='.$_GET['status'].'&temp_status=2', '',1);?>">Last 10 Days</a></li> 
                                              <li class=""><a href="<?php echo $obj_general->link($rout, 'mod=indian_new_order&status='.$_GET['status'].'&temp_status=3', '',1);?>"><?php /*?><a href="#detaillast15days" data-toggle="tab" onclick="detail_getdata('last15days')"><?php */?>Last 15 Days</a></li> 
                                               <li class=""><a href="<?php echo $obj_general->link($rout, 'mod=indian_new_order&status='.$_GET['status'].'&temp_status=4', '',1);?>">Last 20 Days</a></li> 
                                                <li class=""><a href="<?php echo $obj_general->link($rout, 'mod=indian_new_order&status='.$_GET['status'].'&temp_status=5', '',1);?>">Last 30 Days</a></li> 
                                                <li class="active"><a href="<?php echo $obj_general->link($rout, 'mod=indian_new_order&status='.$_GET['status'].'&temp_status=6', '',1);?>">Last 45+Days</a></li>
                                            </ul>
                                            </header>
          	<div class="table-responsive">
           <form name="form_list" id="form_list" method="post">
           <input type="hidden" id="action" name="action" value="" />
          	<div class="table-responsive">
                <table id="quotation-row" class="table b-t text-small table-hover">
                  <thead>
                    <tr>
                       <?php 
                        // if($_SESSION['ADMIN_LOGIN_SWISS']=='1' && $_SESSION['LOGIN_USER_TYPE']=='1' || !isset($_GET['status']))
					  // {
					 ?>
                        <th><input type="checkbox"/></th>
                        <?php // }?>
                        <?php $st = '';
							
							if(isset($_GET['status']))
							 {
							 	$st = '&status='.$_GET['status'];
							 }		 ?>
                        <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> "> Order No
                       		<span class="th-sort">
                            	<a href="<?php  echo $obj_general->link($rout, 'mod=indian_new_order'.$st.'&sort=gen_order_id'.'&order=ASC', '',1);?>">
                                	<i class="fa fa-sort-down text"></i>
                                    
                                <a href="<?php echo $obj_general->link($rout, 'mod=indian_new_order'.$st.'&sort=gen_order_id'.'&order=DESC', '',1);?>">
                                <i class="fa fa-sort-up text-active"></i>
                            <i class="fa fa-sort"></i></span>
                     </th> 
                       
                      <?php /*?><th>Order Date</th><?php */?>
                     <th>Client Name</th>
                     <th>Buyers Order No</th>
                     <?php /*?>     // sonu add order_type 19-1-2017<?php */?>
                     <th>Order Type </th>
                    <th>Shippment Country</th>
                    <th>Transportation </th>
                    <th>Option</th>
                    <th>Dimension (Size)</th>
                    <th>Color</th>
                    <th>Quantity </th>
                    <th>Posted By</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
				  
					if(isset($_GET['status']))
					{
						$cond = 'AND sos.status="'.$_GET['status'].'" AND t.status=1';
						$tot_status=1;
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
					//printr($_GET['status']);
				 $total_orders = $obj_template->GetTotalCartOrderListForIndians($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],$cond,$_GET['status'],$filter_data,$interval);
				//printr($total_orders);
				// die;
				  	  $pagination_data = '';
                      if($total_orders){
                        if (isset($_GET['page'])) {
                            $page = (int)$_GET['page'];
                        } else {
                            $page = 1;
                        }
						// mansi 22-1-2016 for sorting
                  		$option = array(
                          'sort'  => $sort,
                          'order' => $sort_order,
                          'start' => ($page - 1) * $limit,
                          'limit' => $limit,
						  
						);
						//printr($option);
						// add $option 
						//printr($_GET['status']);
                     $orders = $obj_template->GetCartOrderListForIndians($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],$cond,$_GET['status'],$filter_data,$interval,$option);
					 
					//printr($orders);
					 //printr($_SESSION);die;
					 $f = 1;$total=0;$total_qty=0; $temp_order_id='';
					 $value='';
					 if($orders)
					 {
					 foreach($orders as $order){  
					   
					   //add for digital Print
					    if($order['stock_print']=='Digital Print'){
					     $stock_print='<b>('.$order['stock_print'].'</b>)';
					     $digital_color=$obj_template->GetdigitalColorName($order['digital_print_color']);
					    // printr($digital_color);
					    $d_color='<b>Digital Printing With </b>'.$digital_color.'<br> <b> Front Side : </b> '.$order['front_color']. ' Color <br><b> Back Side :</b>'.$order['back_color'].' Color.';
					 }else{
					     $stock_print='';
					      $d_color='';
					 }  
					     
					 
					 $string1=$order['gen_order_id'];					
					 $order_type_value= substr($string1,0,3);
				
					// printr($order_type_value);
						//printr($order); 
					if($order_type_value =='STK')
					 {

					 //if($_SESSION['ADMIN_LOGIN_SWISS']=='1' && $_SESSION['LOGIN_USER_TYPE']=='1' || !isset($_GET['status']))
					   //{
					   ?>
                           <td><input type="checkbox" name="post[]" value="<?php echo $order['template_order_id'].'=='.$order['product_template_order_id'].'=='.$order['client_id'].'=='.$order['gen_order_id'];?>"/></td>
                           <?php //}?>
                       
                           <td><?php echo $order['gen_order_id'].'<br>'.dateFormat(4,$order['date_added']);?>
                           <input type="hidden" name="order_type_value" id="order_type_value" value="<?php echo $order_type_value ;?>" /></td>
                           <?php					  
				// $total = $obj_template->totalCount($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],$order['client_id'],$tot_status,$order['stock_order_id']);
				 //printr($total); 
					   ?>                       
							 <?php /*?> <td><?php echo dateFormat(4,$order['date_added']);?></td><?php */?>
							 <td>
							     <?php $add_url_to=ucwords($order['client_name']);
                                    								if($order['address_book_id']!='0')
                                    									$add_url_to='<a href="'.$obj_general->link('address_book', '&mod=view&address_book_id=' . encode($order['address_book_id']), '', 1).'">'.ucwords($order['client_name']).'</a>';?>
							      <?php echo $add_url_to;?>
							      
							 </td>
                              <td><?php echo $order['buyers_order_no'];?></td>
                                <?php /*?>  // sonu add order_type 19-1-2017<?php */
								$string1=$order['gen_order_id'];					
					 			$order_type_value= substr($string1,0,4);
								//printr($order_type_value);
								?> <input type="hidden" name="order_type_value" id="order_type_value" value="<?php echo $order_type_value ;?>" />
                               <td><?php echo $order['order_type'];?></td>
							 <td><?php if($order['ship_type'] == '0')
							 {
								 $ship_type = 'Self';
							 }
							 else
							 {
								 $ship_type = 'Client';
							 }
							 echo $order['country_name'].' / '.$ship_type;?><br/> </td>
                              <td><?php  echo $order['transportation_type'];?></td>
                              <td><?php echo '<b>'.$order['product_name'].'</b><br>'.$order['zipper'].' '.$order['valve'].' '.$order['spout'].' '.$order['accessorie'];?></td>
                              <td><?php echo '<b style="color:red">'.$order['volume'].'</b><br>'.$order['width'].'X'.$order['height'].'X'.$order['gusset']; ?></td>
                              <td><?php echo $order['color'].'<br>'.$d_color;?></td>
                              <td><?php echo $order['quantity']; ?></td>
							  <td><?php
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
                                     <td>
                                   <?php $menu_id = $obj_template->getMenuPermission(ORDER_ACCEPT_ID,$obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE']);
								if($menu_id OR $obj_session->data['LOGIN_USER_TYPE']==1){ ?>
                                    
                                     <input type="button" name="approve<?php echo $f;?>" id="approve<?php echo $f;?>" value="Accept"  class="btn btn-success" onclick="expacted(<?php echo $f;?>,<?php echo $order['template_order_id']?>,<?php echo $order['product_template_order_id']?>,<?php echo $order['client_id']?>)" />
                                     
                                     
                                    <input type="button" name="decline<?php echo $f;?>" id="decline<?php echo $f;?>" value="Decline" onclick="review(<?php echo $f;?>,<?php echo $order['template_order_id']?>,<?php echo $order['product_template_order_id']?>,<?php echo $order['client_id']?>)" class="btn btn-danger"/>
                                    <input type="hidden" name="template_order_id<?php echo $f;?>" id="template_order_id<?php echo $f;?>" value="<?php echo $order['template_order_id']?>" />
                                     <input type="hidden" name="product_template_order_id<?php echo $f;?>" id="product_template_order_id<?php echo $f;?>" value="<?php echo $order['product_template_order_id']?>"/>
                                    <input type="hidden" name="client_id<?php echo $f;?>" id="client_id<?php echo $f;?>" value="<?php echo $order['client_id']?>"/>
                                    <?php } ?></td>
                            </tr>
                         <?php 
						 $value = $value.'='.$order['product_template_order_id'];
						  $temp_order_id = $temp_order_id.'='.$order['template_order_id'];
						   }else{
							   ?>
							    <td><input type="checkbox" name="post[]" value="<?php echo $order['stock_order_id'].'=='.$order['custom_order_id'].'=='.$order['gen_order_id'];?>"/>
                                <input type="hidden" name="order_type_value" id="order_type_value" value="<?php echo $order_type_value ;?>" /></td>
                           <?php //}?>
                       
                           <td><?php echo $order['gen_order_id'].'<br>'.dateFormat(4,$order['date_added']);?></td>
                           <?php					  
				// $total = $obj_template->totalCount($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],$order['client_id'],$tot_status,$order['stock_order_id']);
				 //printr($total); 
					   ?>                       
							 <?php /*?> <td><?php echo dateFormat(4,$order['date_added']);?></td><?php */?>
							  <td><?php echo $order['client_name'];?></td>
                              <td><?php echo $order['buyers_order_no'];?></td>
                                <?php /*?>  // sonu add order_type 19-1-2017<?php */
								$string1=$order['gen_order_id'];					
					 			$order_type_value= substr($string1,0,4);
								//printr($order_type_value);
								?> <input type="hidden" name="order_type_value" id="order_type_value" value="<?php echo $order_type_value ;?>" />
                               <td><?php echo $order['order_type'];?></td>
							 <td><?php if($order['ship_type'] == '0')
							 {
								 $ship_type = 'Self';
							 }
							 else
							 {
								 $ship_type = 'Client';
							 }
							 echo $order['country_name'].' / '.$ship_type;?><br/> </td>
                              <td><?php  echo $order['transportation_type'];?></td>
                              <td><?php echo '<b>'.$order['product_name'].'</b><br>'.$order['zipper'].' '.$order['valve'].' '.$order['spout'].' '.$order['accessorie'];?></td>
                              <td><?php echo '<b style="color:red">'.$order['volume'].'</b><br>'.$order['width'].'X'.$order['height'].'X'.$order['gusset']; ?></td>
                              <td><?php echo $order['color'];?></td>
                              <td><?php echo $order['quantity']; ?></td>
							  <td><?php
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
                                     <td>
                                   <?php $menu_id = $obj_template->getMenuPermission(ORDER_ACCEPT_ID,$obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE']);
								if($menu_id OR $obj_session->data['LOGIN_USER_TYPE']==1){ 
                               // printr($order['stock_order_id'] ."===".  $order['custom_order_id']);
								?>
                                 
             						 <input type="button" name="approve<?php echo $f;?>" id="approve<?php echo $f;?>" value="Accept"  class="btn btn-success" onclick="accept_data(<?php echo $f;?>,<?php echo $order['stock_order_id']?>,<?php echo   $order['custom_order_id']?>)" />
                                     
                      
                                    <input type="button" name="decline<?php echo $f;?>" id="decline<?php echo $f;?>" value="Decline" onclick="decline_data(<?php echo $f;?>,<?php echo $order['stock_order_id']?>,<?php echo $order['custom_order_id']?>)" class="btn btn-danger"/>
                                    <input type="hidden" name="template_order_id<?php echo $f;?>" id="multi_custom_order_id<?php echo $f;?>" value="<?php echo $order['stock_order_id']?>" />
                                     <input type="hidden" name="product_template_order_id<?php echo $f;?>" id="custom_order_id<?php echo $f;?>" value="<?php echo $order['custom_order_id']?>"/>
                                    <input type="hidden" name="client_id<?php echo $f;?>" id="client_id<?php echo $f;?>" value="<?php echo $order['client_id']?>"/>
                                    <?php } ?></td>
                            </tr>
							   <?php
							   
							   }
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
                                    $pagination->url = $obj_general->link($rout,'&mod=indian_new_order&status=0&page={page}&limit='.$limit, '',1);//HTTP_ADMIN.'index.php?rout='.$rout.'&page={page}';
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
                  <footer class="panel-footer">
                        <div class="row">
                            <div class="col-sm-3 hidden-xs"> </div>
                            <?php echo $pagination_data;?>
                        </div>
                    </footer>
            </div>
         </section>
         </div>
        </div>
     </section>
</section>
<!-- Modal For Decline -->
<div class="modal fade" id="smail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="sform" id="sform" style="margin-bottom:0px;">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <input type="hidden" name="template_order_id" id="template_order_id" value="" />
                 <input type="hidden" name="admin" id="admin" value="<?php echo ADMIN_EMAIL;?>" />
                <input type="hidden" name="product_template_order_id" id="product_template_order_id" value=""/>
                <input type="hidden" name="client_id" id="client_id" value=""/>
                <h4 class="modal-title" id="myModalLabel">Review For Decline Order</h4>
              </div>
              <div class="modal-body">
                   <div class="form-group">
                        <label class="col-lg-3 control-label">Review</label>
                        <div class="col-lg-8">
                             <textarea name="review" id="review" placeholder="Review" value="" class="form-control validate[required]"></textarea>
                        </div>
                     </div> 
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="button" onclick="updatestockorderstatus1('review',2)" name="btn_decline" class="btn btn-danger">Decline</button>
              </div>
   		</form>   
    </div>
  </div>
</div>

<!-- Model For Accept-->
<div class="modal fade" id="date" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="sform" id="sform" style="margin-bottom:0px;">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <input type="hidden" name="template_order_id" id="template_order_id" value="" />
                 <input type="hidden" name="admin" id="admin" value="<?php echo ADMIN_EMAIL;?>" />
                <input type="hidden" name="product_template_order_id" id="product_template_order_id" value=""/>
                <input type="hidden" name="client_id" id="client_id" value=""/>
                <input type="hidden" name="abc" id="abc" value="<?php echo  $obj_general->link($rout,'&page={page}&limit='.$limit, '',1);?>"/>
                <h4 class="modal-title" id="myModalLabel">Expected Delivery Date For Dispatch Order</h4>
              </div>
              <div class="modal-body">
                   <div class="form-group">
                        <label class="col-lg-4 control-label">Expected Delivery Date</label>
                        <div class="col-lg-7">
                             <input type="text" name="date" id="due_date" value="<?php echo date("Y-m-d");?>"  data-format="YYYY-MM-DD"  data-template="D MMM YYYY" 
                         placeholder="Delivery Date"  class="combodate form-control"/>
                        </div>
                     </div> 
                     
                     <div class="form-group">
                        <label class="col-lg-4 control-label">Review</label>
                        <div class="col-lg-7">
                             <textarea name="reason" id="reason" placeholder="Review" value="" class="form-control validate[required]"></textarea>
                        </div>
                     </div> 
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="button" onclick="updatestockorderstatus1('accept',1)" name="btn_accept" class="btn btn-success">Save</button>
              </div>
   		</form>   
    </div>
  </div>
</div>


<!-- Model For Accept custom order 28-2-2017-->
<!--<div class="modal fade" id="Custom" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="sform" id="sform" style="margin-bottom:0px;">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <input type="hidden" name="multi_custom_order_id_app" id="multi_custom_order_id_app" value="" />
                 <input type="hidden" name="custom_order_id_app" id="custom_order_id_app" value="" />
                 <input type="hidden" name="admin" id="admin" value="<?php //echo ADMIN_EMAIL;?>" />
                <h4 class="modal-title" id="myModalLabel">Expected Delivery Date For Dispatch Order</h4>
              </div>
              <div class="modal-body">
                   <div class="form-group">
                        <label class="col-lg-4 control-label">Expected Delivery Date</label>
                        <div class="col-lg-7">
                             <input type="text" name="date" id="due_date" value="<?php //echo date("Y-m-d");?>"  data-format="YYYY-MM-DD"  data-template="D MMM YYYY" 
                         placeholder="Delivery Date"  class="combodate form-control"/>
                        </div>
                     </div> 
                     
                     <div class="form-group">
                        <label class="col-lg-4 control-label">Review</label>
                        <div class="col-lg-7">
                            <textarea name="cust_accept" id="cust_accept" placeholder="Review" value="" class="form-control validate[required]"></textarea>
                        </div>
                     </div> 
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="button" onclick="updatecustomorderstatus('accept',1)" name="btn_accept" class="btn btn-success">Save</button>
              </div>
   		</form>   
    </div>
  </div>
</div>-->

<!-- [sonu] end-->
<!-- Modal For Decline custom order -->
<!--<div class="modal fade" id="custom_decline_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="sform" id="sform" style="margin-bottom:0px;">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <input type="hidden" name="multi_custom_order_id_dec" id="multi_custom_order_id_dec" value="" />
                <input type="hidden" name="custom_order_id_dec" id="custom_order_id_dec" value="" />
                 <input type="hidden" name="admin" id="admin" value="<?php //echo ADMIN_EMAIL;?>" />
                <h4 class="modal-title" id="myModalLabel">Review For Decline Order</h4>
              </div>
              <div class="modal-body">
                   <div class="form-group">
                        <label class="col-lg-3 control-label">Review</label>
                        <div class="col-lg-8">
                           <textarea name="cust_decline" id="cust_decline" placeholder="Review" value="" class="form-control validate[required]"></textarea>
                        </div>
                     </div> 
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="button" onclick="updatecustomorderstatus('decline',2)" name="btn_decline" class="btn btn-danger">Decline</button>
              </div>
   		</form>   
    </div>
  </div>
</div>-->

<!-- [sonu] end-->



<script type="application/javascript">
function detail_getdata(data)
{
	//alert(
	var u=window.location.href;
	alert(u);
	var url=u+'&temp_status='+data;
	location.href=url;
	$("#detail").attr('id','detail'+data);
	
}

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
			var ul=u+'&temp_status=6';
		 	$("ul.nav > li a[href*='"+ul+"']").parent("ul.nav > li").addClass("active");
		}
		//alert(u);
       // $("ul.nav > li a[href*='"+u+"']").parent("ul.nav > li").addClass("active");
    });

function reloadPage(){
	location.reload();
}
function removeTemplateOrder(template_order_id){
	var remove_templateorder_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=removeTemplateOrder', '',1);?>");
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
	
function review(id,template_order_id,product_template_order_id,client_id)
{	
	$(".note-error").remove();
	$("#smail").modal('show');
	$("#template_order_id").val(template_order_id);
	$("#product_template_order_id").val(product_template_order_id);
	$("#client_id").val(client_id);
}	

function expacted(id,template_order_id,product_template_order_id,client_id)
{	
	$(".note-error").remove();
	$("#date").modal('show');
	$("#template_order_id").val(template_order_id);
	$("#product_template_order_id").val(product_template_order_id);
	$("#client_id").val(client_id);
}	

function updatestockorderstatus1(id,status)
{ 
		if(status == 2)
		{
			if($("#review").val()=='')
			{
				$(".note-error").remove();
				alert('Please Give Review');
				return false;
			}
			$("#smail").modal('hide');
			var review = $("#review").val();
			$("#review").val('');
		}
		
		if(status == 1)
		{
			if($("#due_date").val()=='')
			{
				$(".note-error").remove();
				alert('Please Select Date');
				return false;
			}
			$("#date").modal('hide');
			var due_date = $("#due_date").val();
			var reason = $("#reason").val();
		}
		
		var adminEmail = $("#admin").val();
		
		var postArray = {};
		if(status == 1)
		{
			var newid = '';
		}
		else if(status == 2)
		{
			var newid = '';		
		}
		//var order_type_value =$("#order_type_value").val();
		//alert(order_type_value);	
		postArray['template_order_id'] = $("#template_order_id"+newid).val();
		postArray['product_template_order_id'] = $("#product_template_order_id"+newid).val();
		postArray['client_id'] = $("#client_id"+newid).val();
		postArray['review'] =review;
		postArray['status'] =status;
		postArray['due_date']=due_date;
		postArray['reason']=reason;
		
		//alert(postArray['due_date']);
		//alert(postArray['review']);
		var d = new Date();
		
		var curr_date = d.getDate();
		var curr_month = d.getMonth();
		curr_month++;   // need to add 1 – as it’s zero based !
		var curr_year = d.getFullYear();
		var formattedDate = curr_date + "-" + curr_month + "-" + curr_year;
		postArray['currdate'] = formattedDate;
		$('#loading').show();	
		var order_status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=updatestockorderstatus', '',1);?>");
		$.ajax({
			url : order_status_url,
			method : 'post',
			data : {postArray : postArray,adminEmail:adminEmail},
			success: function(response){
				//console.log(response);
				//set_alert_message('Successfully Updated',"alert-success","fa-check");
				$('#loading').hide();
				 window.setTimeout(function(){location.reload()},1000)
				},
				error: function(){
					return false;	
				}
			});
	
}	
//[sonu] Started on (28-2-2017)
/*
function accept_data(f,multi_custom_order_id,custom_order_id)
{	
	//console.log(multi_custom_order_id+'==='+custom_order_id);
	$(".note-error").remove();
	//alert(multi_custom_order_id);
	//alert(custom_order_id);
	$("#multi_custom_order_id_app").val(multi_custom_order_id);
	$("#custom_order_id_app").val(custom_order_id);
	$("#Custom").modal('show');
}	
function decline_data(multi_custom_order_id,custom_order_id)
{
	//console.log(multi_custom_order_id+'==='+custom_order_id);
	$(".note-error").remove();
	$("#custom_decline_model").modal('show');
	$("#multi_custom_order_id_dec").val(multi_custom_order_id);
	$("#custom_order_id_dec").val(custom_order_id);
	$("#custom_decline_model").modal('show');
}
function updatecustomorderstatus(id,status)
{
	//console.log(id+'----'+status);
	if(status == 2)
	{
		if($("#cust_decline").val()=='')
		{
			$(".note-error").remove();
			alert('Please Give Review');
			return false;
		}
		$("#decline_model").modal('hide');
		var cust_decline = $("#cust_decline").val();
	
		$("#cust_decline").val('');
		var txt = '_dec';
	}
	
	if(status == 1)
	{
		if($("#due_date").val()=='')
		{
			$(".note-error").remove();
			alert('Please Select Date');
			return false;
		}
		$("#date").modal('hide');
		var due_date =$("#due_date").val();
	//	var review = $("#reason").val();
		var cust_accept = $('#cust_accept').val();
	//	alert(cust_accept);
		var txt = '_app';
	}
	//alert("#multi_custom_order_id"+txt);
	var postArray = {};
	postArray['multi_custom_order_id'] = $("#multi_custom_order_id"+txt).val();
	postArray['custom_order_id'] = $("#custom_order_id"+txt).val();
	postArray['cust_accept'] =cust_accept;
	postArray['review'] =cust_decline;
	postArray['due_date']=due_date;
	postArray['status'] =status;
	
	var d = new Date();
	var curr_date = d.getDate();
	var curr_month = d.getMonth();
	curr_month++;
	var curr_year = d.getFullYear();
	var formattedDate = curr_date + "-" + curr_month + "-" + curr_year;
	postArray['currdate'] = formattedDate;
	
	var adminEmail = $("#admin").val();
	
	var order_status_url = getUrl("<?php //echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=updateAccDeclinestatus', '',1);?>");
	$.ajax({
		url : order_status_url,
		method : 'post',
		data : {postArray : postArray,adminEmail:adminEmail},
		success: function(response){
			
			console.log(response);
				if(status == '1')
					set_alert_message('Successfully Accepted',"alert-success","fa-check");
				else
					set_alert_message('Successfully Declined',"alert-success","fa-check");
					
				$('#loading').hide();
				//window.setTimeout(function(){location.reload()},1000)
			},
			error: function(){
				return false;	
			}
		});
}*/

//[sonu] end
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