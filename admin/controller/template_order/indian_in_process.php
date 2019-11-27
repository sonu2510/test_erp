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
}
//printr(date("d-m-Y"));
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
	$class = '';
	$filter_data=array(
		'order_no' => $filter_order,
		'date' => $filter_date, 
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
	$filter_data=array(
		'order_no' => $filter_order,
		'date' => $filter_date, 
	);
	$obj_session->data['filter_data'] = $filter_data;	
}
if(isset($_POST['btn_dispatch'])){
		
		//printr($_POST);//die;
		$status = '3';
		for($i=0;$i<count($_POST['key']);$i++)
		{
			if(!empty($_POST['dis_qty'][$i]))
			{
				$arr=array('user_id'=>$obj_session->data['ADMIN_LOGIN_SWISS'],
							'user_type_id'=>$obj_session->data['LOGIN_USER_TYPE'],
							'action'=>$status,
							'currdate'=>date('d-m-Y')
				);
				$value = "date='".$_POST['datetime']."',review='".$_POST['remark_sel'][$i]."',dispach_by='".json_encode($arr)."'";
				$dis_qty = ",dis_qty='".$_POST['dis_qty'][$i]."'";
				$rem_qty ='';
				if(!empty($_POST['custom_order_id'][$i]))
				{
					$cond = "custom_order_id = '" .(int)$_POST['custom_order_id'][$i]. "' AND multi_custom_order_id = '".(int)$_POST['stock_order_id'][$i]."'";
					$total_qty='';
					$n='1';
				}
				else
				{
					$cond = "template_order_id = '" .(int)$_POST['template_order_id'][$i]. "' AND product_template_order_id = '".(int)$_POST['product_template_order_id'][$i]."'";
					$n='0';
					$total_qty = $_POST['tot_qty'][$i];
				}
					
				//printr($n);
				
				$result = $obj_template->updatestockorderstatus($value,$cond,$dis_qty,$rem_qty,$status,$_POST['template_order_id'][$i],$_POST['product_template_order_id'][$i],$total_qty,$n);
				$admin_mail=ADMIN_EMAIL;
			
				
				if(!empty($_POST['custom_order_id'][$i]))
					$post[] =($_POST['custom_order_id'][$i].'=='.$_POST['stock_order_id'][$i]);
				else
					$post[] =($_POST['template_order_id'][$i].'=='.$_POST['product_template_order_id'][$i].'=='.$_POST['client_id'][$i]);
					
					
					$p = array('post'=>$post);
			}
			
		}
		//printr($_POST['user_name']);die;
		//$_POST['user_name'] = '2=60=Australia';
		//uncomment this fun [kinjal] 27/10/2016
		$obj_template->sendDispatchOrderEmail($post,$status,$admin_mail,'','','',$_POST['order_type']);
		
		$obj_template->sendWarningMailForGenInv($_POST['user_name'],$admin_mail,'0',$_POST['order_type']);
		
		page_redirect($obj_general->link($rout, '&mod=indian_in_process&status=1', '',1));	
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
						if($_GET['status']==1)
						$head='Indian In Process Listing';	
					}
					echo $head;?></span>
          	<span class="text-muted m-l-small pull-right">
            <?php $menu_id = $obj_template->getMenuPermission(ORDER_DISPATCHED_ID,$obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE']);
			//printr($menu_id);
			if($menu_id OR $obj_session->data['LOGIN_USER_TYPE']==1)
			{?>
            	<a class="label bg-warning" style="margin-left:4px;" onClick="dispatchMulOrder()">Dispatched</a>
               <?php /*?> <a class="label bg-warning" style="margin-left:4px;" onClick="dispatchMulOrder('post[]')">Dispatched</a><?php */?>
            
            <?php } 
			if($_SESSION['ADMIN_LOGIN_SWISS']=='1' && $_SESSION['LOGIN_USER_TYPE']=='1')
			{ ?>
            		<a class="label bg-danger" style="margin-left:4px;" onClick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>
        <?php } ?>
            	<?php if($obj_general->hasPermission('add',$menuId)){ 
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
                        <form class="form-horizontal" method="post" id="form" name="form"  data-validate="parsley" action="<?php echo $obj_general->link($rout, 'mod=indian_in_process&status='.$_GET['status'], '',1); ?>">
                        <section class="panel pos-rlt clearfix">
            		    <header class="panel-heading">
                    		<ul class="nav nav-pills pull-right">
                      		<li> <a href="#" class="panel-toggle text-muted active"><i class="fa fa-caret-down fa-lg text-active"></i>
                            <i class="fa fa-caret-up fa-lg text"></i></a> </li>
                   	    	</ul>
                    		<a href="#" class="panel-toggle text-muted active"><i class="fa fa-search"></i> Search </a>
                  		</header>
                        
                  	<div class="panel-body clearfix <?php echo $class; ?>">        
                     	 <div class="row">
                        	<div class="col-lg-4">
                            	  <div class="form-group">
                                		<label class="col-lg-5 control-label">Order No</label>
                                		<div class="col-lg-7">
                                  		<input type="text" name="filter_order" value="<?php echo isset($filter_order) ? $filter_order : '' ; ?>" placeholder="Order NO" id="input-name" class="form-control" />
                                		</div>
                              		</div>
                               		<div class="form-group">
                                		<label class="col-lg-5 control-label">Date</label>
                                		<div class="col-lg-7">                                                            
                                             <input type="text" name="filter_date" readonly="readonly" data-date-format="dd-mm-yyyy" 
                                             value="<?php echo isset($filter_date) ? $filter_date : '' ; ?>" 
                                             placeholder="Date" id="input-name" class="input-sm form-control datepicker" />
                                		</div>
                              		</div>
                              </div>
                         </div>
            
                  <footer class="panel-footer <?php echo $class; ?>">
                    <div class="row">
                       <div class="col-lg-12">
                        <input type="hidden" value="<?php echo $status;?>" id="status" name="status" />
                        <button type="submit" class="btn btn-primary btn-sm pull-right ml5" name="btn_filter"><i class="fa fa-search"></i> Search</button>
                        <a href="<?php echo $obj_general->link($rout, 'mod=indian_in_process&status='.$_GET['status'], '',1); ?>" class="btn btn-info btn-sm pull-right" ><i class="fa fa-refresh"></i> Refresh</a>
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
                            <option value="<?php echo $obj_general->link($rout, 'mod=indian_in_process&status='.$_GET['status'].'&limit='.$display_limit, '',1);?>" selected="selected"><?php echo $display_limit; ?></option>				
                    <?php } else { ?>
                            <option value="<?php echo $obj_general->link($rout, 'mod=indian_in_process&status='.$_GET['status'].'&limit='.$display_limit, '',1);?>"><?php echo $display_limit; ?></option>
                    <?php } ?>
                    <?php } ?>
                 </select>
           </div>
             <label class="col-lg-1 pull-right" style="margin-top:5px;">Show</label>             
          </div>
           <header class="panel-heading text-right"> 
                                            <ul class="nav nav-tabs pull-left" id="view_list"> 
                                            <li class=""><a href="<?php echo $obj_general->link($rout, 'mod=indian_in_process&status='.$_GET['status'].'&temp_status=1', '',1);?>">Last 5 Days</a></li> 
                                             <li class=""><a href="<?php echo $obj_general->link($rout, 'mod=indian_in_process&status='.$_GET['status'].'&temp_status=2', '',1);?>">Last 10 Days</a></li> 
                                              <li class=""><a href="<?php echo $obj_general->link($rout, 'mod=indian_in_process&status='.$_GET['status'].'&temp_status=3', '',1);?>"><?php /*?><a href="#detaillast15days" data-toggle="tab" onclick="detail_getdata('last15days')"><?php */?>Last 15 Days</a></li> 
                                               <li class=""><a href="<?php echo $obj_general->link($rout, 'mod=indian_in_process&status='.$_GET['status'].'&temp_status=4', '',1);?>">Last 20 Days</a></li> 
                                                <li class=""><a href="<?php echo $obj_general->link($rout, 'mod=indian_in_process&status='.$_GET['status'].'&temp_status=5', '',1);?>">Last 30 Days</a></li> 
                                                <li class="active"><a href="<?php echo $obj_general->link($rout, 'mod=indian_in_process&status='.$_GET['status'].'&temp_status=6', '',1);?>">Last 45+Days</a></li>
                                            </ul>
                                            </header>
          	<div class="table-responsive">
           <form name="form_list" id="form_list" method="post">
           <input type="hidden" id="action" name="action" value="" />
          	<div class="table-responsive">
                <table id="quotation-row" class="table b-t text-small table-hover">
                  <thead>
                    <tr>
                       <?php //$total_orders = $obj_template->getCustomAcceptedRecords();
                        // if($_SESSION['ADMIN_LOGIN_SWISS']=='1' && $_SESSION['LOGIN_USER_TYPE']=='1' || !isset($_GET['status']))
					  // {
					 ?>
                        <th><input type="checkbox"/></th>
                        <?php // }?>
                   <?php   $st = '';
							
							if(isset($_GET['status']))
							 {
							 	$st = '&status='.$_GET['status'];
							 }		 ?>

                      <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">  Order No
                       		<span class="th-sort">
                            	<a href="<?php  echo $obj_general->link($rout, 'mod=indian_in_process'.$st.'&sort=gen_order_id'.'&order=ASC', '',1);?>">
                                	<i class="fa fa-sort-down text"></i>
                                    
                                <a href="<?php echo $obj_general->link($rout, 'mod=indian_in_process'.$st.'&sort=gen_order_id'.'&order=DESC', '',1);?>">
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
                    <th>Order Qty </th>
                    <th>Remaining Qty </th>
                    <th>Posted By</th>
                  <!--  <th>Action</th>-->
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
				 $total_orders = $obj_template->GetTotalCartOrderListForIndians($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],$cond,isset($_GET['status']),$filter_data,$interval);
				
				  	  $pagination_data = '';
                      if($total_orders){
                        if (isset($_GET['page'])) {
                            $page = (int)$_GET['page'];
                        } else {
                            $page = 1;
                        }
						// mansi 22-1-2016 (for sorting)
                 		$option = array(
                          'sort'  => $sort,
                          'order' => $sort_order,
                          'start' => ($page - 1) * $limit,
                          'limit' => $limit,
						  
						);
				 	//printr($_GET['status']);
                     $orders = $obj_template->GetCartOrderListForIndians($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],$cond,$_GET['status'],$filter_data,$interval,$option);
					//printr($orders);
					 $f = 1;$total=0;$total_qty=0; $temp_order_id='';
					 $value='';
					 if($orders)
					 {
					 foreach($orders as $order){  
					 	//printr($order);
					 	$dis_qty=$obj_template->getDispatchQty($order['template_order_id'],$order['product_template_order_id']);
						//printr($dis_qty);
					
					   ?>
                           <td>
								<?php if(isset($order['custom_order_id'])) 
									  { ?>                           
                          				 <input type="checkbox" name="post[]" value="<?php echo $order['stock_order_id'].'=='.$order['custom_order_id'].'==0=='.$order['gen_order_id'];?>"/>
                                <?php }
									  else
									  { ?>
                                      	  <input type="checkbox" name="post[]" value="<?php echo $order['template_order_id'].'=='.$order['product_template_order_id'].'=='.$order['client_id'].'=='.$order['gen_order_id'];?>"/>
                              <?php   } ?></td>
                       
                       
                           <td><?php echo $order['gen_order_id'].'<br>'.dateFormat(4,$order['date_added']);?></td>
                           <?php					  
				 //$total = $obj_template->totalCount($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],$order['client_id'],$tot_status,$order['stock_order_id']);
				 
					   ?>                       
							
							  <td><?php echo $order['client_name'];?></td>
                              <td><?php echo $order['buyers_order_no'];?></td>
                               <?php /*?>  // sonu add order_type 19-1-2017<?php */?>
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
                              <td><?php echo '<b>'.$order['product_name'].'</b><br>'.$order['zipper'].' '.$order['valve'].' '.$order['spout'].' '.$order['accessorie'].' '.$order['accessorie_txt_corner'];?></td>
                              <td><?php echo '<b style="color:red">'.$order['volume'].'</b><br>'.$order['width'].'X'.$order['height'].'X'.$order['gusset']; ?></td>
                              <td><?php echo $order['color'];?></td>
                              <td><?php echo $order['quantity']; ?></td>
                               <td><?php echo $order['quantity']-$dis_qty['total_dis_qty']; ?></td>
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
                                    $pagination->url = $obj_general->link($rout,'&mod=indian_in_process&status=1&page={page}&limit='.$limit, '',1);//HTTP_ADMIN.'index.php?rout='.$rout.'&page={page}';
                                    $pagination_data = $pagination->render();
                                 
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

<!-- Model for Dispatch Multiple Record -->
<div class="modal fade" id="dispatchModel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:70%;">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="sform" id="sform" style="margin-bottom:0px;">
              <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    	<input type="hidden" name="formdata" id="formdata" value="" />
                   	<h4 class="dispatch" id="myModalLabel">Dispatched Order</h4>
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
									//printr($userlist);
								?>
                             <div class="col-lg-7">
                                	<select class="form-control" name="user_name" id="user_name">
                                    	<option value="">Please Select</option>
                                    	<?php foreach($userlist as $user) { ?>
                                        		<option value="<?php echo $user['user_type_id']."=".$user['user_id'].'='.$user['country_name']; ?>"><?php echo $user['user_name']; ?></option>
                                        <?php } ?>                                       
                                    </select>
                                </div>
                    </div>
              </div>
              
              <div class="shown_div" style="display:none;">
                  <div class="modal-body">
                    <div class="form-group table_data">
                    </div>
                  </div>
                  
                  <div class="line line-dashed m-t-large"></div>
                  
                  <?php /*?> <div class="modal-body">
                       <div class="form-group">
                            <label class="col-lg-3 control-label">Track ID</label>
                            <div class="col-lg-8">
                            <input type="text" name="track_id" id="track_id" value="" placeholder="Track ID"  class="form-control validate[required]"/>
                            </div>
                         </div> 
                  </div><?php */?>
                  
                  
               <div class="form-group"> 
                    <label class="col-lg-3 control-label">Date</label> 
                    <div class="col-lg-9"> 
                        <input type="text" class="combodate form-control" data-format="DD-MM-YYYY" data-required="true" data-template="D MMM YYYY" name="datetime" id="datetime" value="<?php echo date("d-m-Y");   ?> ">
                    </div> 
               </div>
              
                 <?php /*?> <div class="modal-body">
                       <div class="form-group">
                            <label class="col-lg-3 control-label">Courier</label>
                            <div class="col-lg-8">
                            <?php echo $obj_template->getCourierCombo();?>
                            </div>
                         </div> 
                  </div><?php */?>
                  
                   <?php /*?><div class="modal-body">
                       <div class="form-group">
                            <label class="col-lg-3 control-label">Remark</label>
                            <div class="col-lg-8">
                            <input type="text" name="review" id="review" value="" placeholder="Remark"  class="form-control validate[required]"/>
                            </div>
                         </div> 
                  </div><?php */?>
             <!--onclick="updatestockorderstatus('dispatch',3)"-->
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                    <button type="submit"  name="btn_dispatch" class="btn btn-warning">Dispatch</button>
                  </div>
              </div>
   		</form>   
    </div>
  </div>
</div>
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script type="application/javascript">
jQuery(document).ready(function(){
	jQuery("#sform").validationEngine();
});
function detail_getdata(data)
{
	var u=window.location.href;
	var url=u+'&temp_status='+data;
	location.href=url;
	$("#detail").attr('id','detail'+data);
}

$(document).ready(function () {
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

function dispatchMulOrder()
{
	$("#dispatchModel").modal('show');
}

/*function dispatchMulOrder(elemName)
{
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
		var data_url = getUrl("<?php //echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getTxtRecord', '',1);?>");
		var formData = $("#form_list input[name='post[]']").serialize();
		$.ajax({
			url : data_url,
			method : 'post',
			data : {formData : formData},
			success: function(response){
				//alert(response);
				$(".table_data").html(response);
				
			},
			error:function(){
				//set_alert_message('Error During Updation',"alert-warning","fa-warning");          
			}	
		});
		$("#dispatchModel").modal('show');
		
	}
	else
	{
		$(".modal-title").html("WARNING");
		$("#setmsg").html('Please select atlease one record');
		$("#popbtnok").hide();
		$("#myModal").modal("show");
	}
	
}*/
function dis_qty(key,n)
{
	var dis_qty = $("#dis_qty_"+key).val();
	var rem_qty = $("#rem_qty_"+key).val();
	if(n=='0')
	{
		if(parseInt(rem_qty) < parseInt(dis_qty) || parseInt(dis_qty)===0)
		{
			$(".modal-title").html("WARNING");
			$("#setmsg").html('Please Give Qty Less than '+ rem_qty+' Or Equal To'+ rem_qty);
			$("#popbtnok").hide();
			$("#myModal").modal("show");
			$("#dis_qty_"+key).val('');
		}
	}
}

$("#user_name").change(function() {
	var data_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getTxtRecord', '',1);?>");
		var user_val = $(this).val();
		var order_type = $('input[name=order_type]:radio:checked').val();
		if(user_val!='')
		{
			$.ajax({
				url : data_url,
				method : 'post',
				data : {user_val : user_val,order_type:order_type},
				success: function(response){
					console.log(response);
					var val = $.parseJSON(response);
					if(val.records==0)
					{
						$(".shown_div").hide();
						$(".table_data").html();
					}
					else
					{
						$(".shown_div").show();
						$(".table_data").html(val.response);
					}
					
				},
				error:function(){
					//set_alert_message('Error During Updation',"alert-warning","fa-warning");          
				}	
			});
		}
		else
		{
			$(".shown_div").hide();
			$(".table_data").html('');
		}
});
$('input:radio[name=order_type]').change(function () {
	$("#user_name").val('');
	$(".table_data").html('');
	$(".shown_div").hide();
});

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