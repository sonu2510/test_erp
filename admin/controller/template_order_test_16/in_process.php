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
//offline
//$menuId = 88;
//online
$menuId = 76;
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
	$sort= 't.template_order_id ';
}

if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'DESC';	
}

$client_id = isset($_GET['client_id'])?$_GET['client_id']:'';
$client = base64_decode($client_id);
$stock_order_id_encoded=isset($_GET['stock_order_id'])?$_GET['stock_order_id']:'';
$stock_order_id = base64_decode($stock_order_id_encoded);

//printr($_GET['multi_custom_order_id']);
$multi_custom_order_id = '';
if(isset($_GET['multi_custom_order_id']) && $_GET['multi_custom_order_id'])
{
	$multi_custom_order_id = base64_decode($_GET['multi_custom_order_id']);
}
$class = 'collapse';
$filter_data=array();

if(!isset($_GET['filter_edit']) || $_GET['filter_edit']==0){
	if(isset($obj_session->data['filter_data'])){
		unset($obj_session->data['filter_data']);	
	}
}

if(isset($obj_session->data['filter_data'])){
	$filter_order = $obj_session->data['filter_data']['order_no'];
	$filter_date = $obj_session->data['filter_data']['date'];
	$filter_product_name = $obj_session->data['filter_data']['product_name'];
	$filter_user_name = $obj_session->data['filter_data']['postedby'];
	$class = '';
	
	$filter_data=array(
		'order_no' => $filter_order,
		'date' => $filter_date, 
		'product_name' => $filter_product_name,
		'postedby' => $filter_user_name,
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
	if(isset($_POST['filter_product_name'])){
		$filter_product_name=$_POST['filter_product_name'];
	}else{
		$filter_product_name='';
	}
	
	if(isset($_POST['filter_user_name']))
	{
		$filter_user_name = $_POST['filter_user_name'];
	}else{
		$filter_user_name='';
	}
	
	$filter_data=array(
		'order_no' => $filter_order,
		'date' => $filter_date, 
		'product_name' => $filter_product_name,
		'postedby' => $filter_user_name,	
	);
	
	$obj_session->data['filter_data'] = $filter_data;	
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
	$userPrice_permission = $obj_template->getUser($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE']);
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
    					<span>Stock Order In-Process Listing </span>
    					<span class="text-muted m-l-small pull-right">
						<?php if($obj_general->hasPermission('add',$menuId)){
									if($obj_session->data['LOGIN_USER_TYPE'] != 1)
									{
										if($permission ==0 && $checkNewCartPermission[0]['status']==1 ) {}
										else
										{?>
										<?php /*?><a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add&s_no='.encode($permission).'', '',1);?>"><i class="fa fa-plus"></i> Add </a><?php */?>
									<?php } 
									}
                            	} ?>      
    					</span>
    				</header>
                     <div class="panel-body">
                        <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link($rout, '&mod=in_process&client_id='.$client_id.'', '',1); ?>">
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
                        <div class="col-lg-4">
                              	<div class="form-group">
                                	<label class="col-lg-5 control-label">Product</label>
                                   <?php							
										$products = $obj_template->getActiveProduct();
									?>
                                     <div class="col-lg-7">
                                       	<select class="form-control" name="filter_product_name">
                               		     	<option value="">Please Select</option>
                                    		<?php foreach($products as $product) { ?>
                                        	<?php if(isset($filter_product_name) && !empty($filter_product_name) && $filter_product_name == 
											$product['product_name']) { ?>
                                           		 <option value="<?php echo $product['product_name']; ?>" selected="selected">
											<?php echo $product['product_name']; ?></option>
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
									$userlist = $obj_template->getUserList();
									?>
                                
                                <div class="col-lg-7">
                                	<select class="form-control" name="filter_user_name">
                                    	<option value="">Please Select</option>
                                    	<?php 
									$val = explode("=",$filter_user_name);
										foreach($userlist as $user) { ?>
                                        	<?php if(isset($filter_user_name) && !empty($val[0]) && $val[0] == $user['user_type_id'] && $val[1]==$user['user_id'] ) { ?>                                       
                                    			<option value="<?php echo $user['user_type_id']."=".$user['user_id']; ?>" selected="selected">
												<?php echo $user['user_name']; ?></option>
                                            <?php } else { ?>
                                            	<option value="<?php echo $user['user_type_id']."=".$user['user_id']; ?>"><?php echo $user['user_name']; ?></option>
                                            <?php } ?>
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
                        <a href="<?php echo $obj_general->link($rout, '&mod=in_process&client_id='.$client_id, '',1); ?>" class="btn btn-info btn-sm pull-right" ><i class="fa fa-refresh"></i> Refresh</a>
                       </div> 
                    </div>
                  </footer>                                  
              </section>
           </form>    
            <div class="col-lg-3 pull-right">	
                <select class="form-control" id="limit-dropdown" onchange="location=this.value;">
                <option value="<?php echo $obj_general->link($rout, '', '',1);?>" selected="selected">--Select--</option>	
					<?php 
                        $limit_array = getLimit(); 
                        foreach($limit_array as $display_limit) {
                            if($limit == $display_limit) {	 
                    ?>
                       		 
                            <option value="<?php echo $obj_general->link($rout, 'mod=in_process&client_id='.$client_id.'&limit='.$display_limit, '',1);?>" selected="selected"><?php echo $display_limit; ?></option>				
                    <?php } else { ?>
                            <option value="<?php echo $obj_general->link($rout, 'mod=in_process&client_id='.$client_id.'&limit='.$display_limit, '',1);?>"><?php echo $display_limit; ?></option>
                    <?php } ?>
                    <?php } ?>
                 </select>
           </div>
             <label class="col-lg-1 pull-right" style="margin-top:5px;">Show</label>             
          </div>
                    <form name="form_list" id="form_list" method="post">
                    	<input type="hidden" id="action" name="action" value="" />
					    <div class="table-responsive">
							                       
    
									<?php
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
									);
								
								if($multi_custom_order_id!='')
									$total_order=$obj_template->getCustomAcceptedRecords(1,'','',$multi_custom_order_id);
								else
									$total_order = $obj_template->GetOrderList($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],'AND t.status = 1 AND sos.status=1',$option,'',$filter_data,$client,'','','','','',$stock_order_id);
								$pagination_data = '';
								$total_orders=count($total_order);
								if($total_order!=''){
									//printr($total_order);
									
									if($multi_custom_order_id!='')
										$orders=$obj_template->getCustomAcceptedRecords(1,'','',$multi_custom_order_id);
									else
										$orders = $obj_template->GetOrderList($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],'AND t.status = 1 AND sos.status=1',$option,'',$filter_data,$client,'','','','','',$stock_order_id);
									
									$start_num =((($page*$limit)-$limit)+1);
									$f = 1;
									$slNo = $f+$start_num;
									$total=0;$total_qty=0;
							if($orders!='')
                            	foreach($orders as $order){
									//printr($order); 
									
									
									$dis_qty=$obj_template->getDispatchQty($order['template_order_id'],$order['product_template_order_id']);
									if($multi_custom_order_id=='')
										$new_price=$obj_template->getUpdatedPrice($order['product_template_order_id'],$order['template_order_id']);
									
									if(isset($new_price) && $new_price!='')
										$order['price']=$new_price;
									
									$edit_price_menu_id = $obj_template->getMenuPermission(ORDER_PRICEEDIT_ID,$obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE']); ?>
                                        <section class="panel pos-rlt clearfix" style="margin-top: 10px; border: 2px solid #FFDC5C"> 
                                            <header class="panel-heading" style="border-color: #FFDC5C;background: #FFF0BA;"> 
                                                <ul class="nav nav-pills pull-right"> 
                                                    <li>
                                                        <?php
                                                        $postedByData = $obj_template->getUser($order['user_id'],$order['user_type_id']);
                                                        $addedByImage = $obj_general->getUserProfileImage($obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS'],'100_');
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
                                                        <span class="label bg-warning" style="font-size: 100%; ">Order By <?php echo $postedByData['user_name'];?></span></a>
                                                    </li> 
                                                    <li>
                                                        <a href="#" class="panel-toggle text-muted">
                                                            <i class="fa fa-caret-down fa-lg text-active"></i>
                                                            <i class="fa fa-caret-up fa-lg text"></i>
                                                        </a> 
                                                    </li>     
                                                </ul>
                                             <span class="label bg-warning" style="font-size: 100%;"><?php echo $order['gen_order_id'];?></span>    
 												<span class="label bg-warning" style="font-size: 100%; background:#FFC800; margin-left:10px">
												<?php echo preg_replace("/\([^)]+\)/","",$order['product_name']);?></span>
                                                 
                                            </header> 
                                           <?php $menu_id_dispatched = $obj_template->getMenuPermission(ORDER_DISPATCHED_ID,$obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE']);?>
                                             <header class="panel-heading text-right"> 
                                            <ul class="nav nav-tabs pull-left">
                                             	<?php if($edit_price_menu_id OR $obj_session->data['LOGIN_USER_TYPE']==1){?> 
                                                    <li class="active"><a href="#detail_<?php echo $f;?>" data-toggle="tab" onclick="div_hide('history_<?php echo $f;?>','delay_history_<?php echo $f;?>','track_history_<?php echo $f;?>','detail_<?php echo $f;?>')">Order Detail</a></li>
                                                    <?php 
												}
													if($multi_custom_order_id=='')
													 { 
													 	if($edit_price_menu_id OR $obj_session->data['LOGIN_USER_TYPE']==1){ ?>
                                                    <li class=""><a href="#history_<?php echo $f;?>" data-toggle="tab" onclick="div_hide('detail_<?php echo $f;?>','delay_history_<?php echo $f;?>','track_history_<?php echo $f;?>','history_<?php echo $f;?>')">Price Update History</a></li> 
                                                 <?php } 
                                                if($menu_id_dispatched OR $obj_session->data['LOGIN_USER_TYPE']==1)
												{ ?>
                                                <li class=""><a href="#delay_history_<?php echo $f;?>" data-toggle="tab" onclick="div_hide('track_history_<?php echo $f;?>','detail_<?php echo $f;?>','history_<?php echo $f;?>','delay_history_<?php echo $f;?>')">Delay Date Update History</a></li> 
                                                
                                                    <li class=""><a href="#track_history_<?php echo $f;?>" data-toggle="tab" onclick="div_hide('history_<?php echo $f;?>','delay_history_<?php echo $f;?>','detail_<?php echo $f;?>','track_history_<?php echo $f;?>')">Track History</a></li>  
                                                <?php 
												}
												} ?>
                                            
                                            </ul>
                                            </header> 
                                            
										  <?php 
										  
										  if($multi_custom_order_id=='')
										  {
											  if($edit_price_menu_id OR $obj_session->data['LOGIN_USER_TYPE']==1){?> 
												<div class="tab-pane fade" id="history_<?php echo $f;?>" style="display:none">
												<?php $price_history=$obj_template->getUpdatedPriceHistory($order['product_template_order_id'],$order['template_order_id']);
												if(isset($price_history) && !empty($price_history)) {?>
												<div> 
													<table class="table table-striped m-b-none text-small"> 
														<thead> 
															<tr> 
																<th>User Name</th>
																<th >Actual Price</th> 
																<th >New Price</th> 
																<th>Date</th> 
																<th>Stage</th> 
															</tr> 
														</thead> 
														<tbody> 
														<?php foreach($price_history as $k=>$value) {
																?>
															<tr> 
																<td><?php  $historyUserName = $obj_template->getUser($value['user_id'],$value['user_type_id']);
																			echo $historyUserName['name'];?> </td> 
																<td ><?php echo $value['old_price'];?></td> 
																<td ><?php echo $value['new_price'];?></td> 
																<td><?php echo dateFormat(4,$value['date_added']);?></td> 
																<td><?php if($value['status'] == 0) echo 'New Order Page';
																		  elseif($value['status'] == 1) echo 'In Process Page';
																		  elseif($value['status'] == 3) echo 'Dispatch Page';?></td> 
														   </tr>  
														   <?php }?>
														</tbody> 
													  </table> 
												 </div>
												 <?php } else echo 'No History';?>
												 </div>
                                            <?php
											  }
											}?>
                                            
                                             
										  <?php  
										  if($multi_custom_order_id=='')
										  {
											  if($menu_id_dispatched OR $obj_session->data['LOGIN_USER_TYPE']==1)
													 { ?>
														 <div class="tab-pane fade" id="delay_history_<?php echo $f;?>" style="display:none">
														 <?php $date_history=$obj_template->getUpdatedDelayDateHistory($order['product_template_order_id'],$order['template_order_id']);
																	if(isset($date_history) && !empty($date_history)) 
																	{ ?>
																		<div> 
																			<table class="table table-striped m-b-none text-small"> 
																				<thead> 
																					<tr> 
																						<th>User Name</th>
																						<th >Actual Date</th> 
																						<th>Actual Review</th>
																						<th >New Date</th> 
																						<th>New Review</th> 
																						<th>Stage</th> 
																					</tr> 
																				</thead> 
																				<tbody> 
																				<?php foreach($date_history as $k=>$value) {
																						if(!empty($value['old_ddate']) && $value['old_ddate'] != '0000-00-00')
																						{
																							$old_date = dateFormat(4,$value['old_ddate']);
																						}
																						else
																						{
																							$old_date = 'NA';
																						}?>
																						<tr> 
																							<td><?php  $historyUserName = $obj_template->getUser($value['edited_by_user_id'],$value['edited_by_user_type_id']);
																							echo $historyUserName['name'];?> </td> 
																							<td ><?php echo $old_date;?></td> 
																							<td ><?php echo $value['old_review'];?></td> 
																							<td><?php echo dateFormat(4,$value['new_final_ddate']);?></td> 
																							<td ><?php echo $value['new_final_review'];?></td>
																							<td><?php echo 'In Process Page';?></td> 
																					   </tr>  
																				   <?php }?>
																				</tbody> 
																			  </table> 
																		 </div>																 
															 <?php } else echo 'No History';
															 
													 }?>
                                                     </div>
                                                     
                                                     <div class="tab-pane fade" id="track_history_<?php echo $f;?>" style="display:none">
                                                     <?php $track_history=$obj_template->getUpdatedTrackHistory($order['product_template_order_id'],$order['template_order_id']);
													 		//echo $track_history;
																if(isset($track_history) && !empty($track_history)) 
																{?>
                                                                	<div> 
                                                                        <table class="table table-striped m-b-none text-small"> 
                                                                            <thead> 
                                                                                <tr> 
                                                                                    <th>User Name</th>
                                                                                    <th>Dispatched Date</th>
                                                                                    <th>Review</th> 
                                                                                    <th>Dispatched Qty</th>
                                                                                    <th>Stage</th> 
                                                                                </tr> 
                                                                            </thead> 
                                                                            <tbody> 
                                                                            <?php foreach($track_history as $k=>$value) {
																			
																					$result = json_decode($value['dispach_by']);
																					$dis_by = $obj_template->getUser($result->user_id,$result->user_type_id);
																					?>
                                                                                <tr> 
                                                                                    <td><?php echo $dis_by['name'];?> </td> 
                                                                                    <td ><?php echo dateFormat(4,$value['dis_date']);?></td> 
                                                                                    <td><?php echo $value['review'];?></td> 
                                                                                    <td ><?php echo $value['dis_qty'];?></td>
                                                                                    <td><?php echo 'Dispatch Page';?></td> 
                                                                               </tr>  
                                                                               <?php }?>
                                                                            </tbody> 
                                                                          </table> 
                                                                     </div>																 
                                                         <?php } else echo 'No History';?>
                                                     </div>
											<?php }?>
                                            
                                            <div class="panel-body clearfix tab-pane fade active in detail" id="detail_<?php echo $f;?>" style="display:block;">
                                               <div id="collapseOne" class="panel-collapse in"> 
                                                    <div class="panel-body text-small" style="width:35%;float: left;"><b>Option : </b><?php echo $order['zipper'].' '.$order['valve'].' '.$order['spout'].' '.$order['filling_details'].' '.$order['accessorie'].'<br><b>Dimension (Size) : </b>'.$order['width'].'X'.$order['height'].'X'.$order['gusset'].'<span style="color:red;font-weight:bold"> ('.$order['volume'].')</span><br><b>Color : </b>'.$order['color'].'<br><b>Order Date :</b>'.dateFormat(4,$order['date_added']); ?>
                                                    </div>
                                                    <div class="panel-body text-small"  style="width: 30%;float: left;"><?php echo '<b>Transportation : </b>'.$order['transportation_type'].'<br>';?><b>Shipment Country : </b><?php echo $order['country_name'].' / '; 
                                                    if($order['ship_type'] == 0) echo 'Self'; else echo 'Client';
                                                    echo '<br><b>Client :</b> '.$order['client_name'].'<br><b>Address :  </b><br><pre>'.$order['address'].'</pre>';?>
                                                    </div>
                                                    
                                                    
                                                    <div class="panel-body text-small"  style="width: 10%;float: left;">
                                                    <b>Order Quantity</b><br /><?php echo $order['quantity']; ?><br /><br />
                                                    <b>Remaining Qty </b><br /><?php echo $order['quantity']-$dis_qty['total_dis_qty']; ?>
                                                    <input type="hidden" name="remaining_qty" id="remaining_qty" value= " <?php echo $order['quantity']-$dis_qty['total_dis_qty']; ?> "  />
                                                    </div>
                                                       <?php  
												   if($edit_price_menu_id OR $obj_session->data['LOGIN_USER_TYPE']==1){?>
                                                    <div class="panel-body text-small"  style="width: 15%;float: left;"><b>Price Per Unit </b><br /><?php echo $order['currency_code'].' <span id="price_'.$f.'">'.$order['price'].'</span>'; ?>
                                                    <span id="<?php echo 'hiddenField_'.$f;?>" style="display:none" >
                                                        <input type="text" contenteditable="true" id="<?php echo 'edit_price_'.$f; ?>" style="width:55px;border: 2px solid rgb(72, 159, 231);" value="<?php echo $order['price'];?>"/></span>
                                                        
                                                           <input type="hidden" name="product_template_order_id<?php echo $f;?>" id="product_template_order_id<?php echo $f;?>" 
                                                           value="<?php echo $order['product_template_order_id']?>"/>
                                                        <div class="btn-group"> 
                                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" onclick="edit_price(<?php echo $f;?>)">
                                                        <i class="fa fa-pencil"></i>
                                                        </a> 
                                                        </div>
                                                    </div>
                                                      
                                                    <div class="panel-body text-small"  style="width: 10%;float: left;"><b>Total Price </b><br /><?php echo $order['currency_code'].' '.$order['price']*$order['quantity'];  ?>
                                                    </div>        
    <?php }?>
    <input type="hidden" name="template_order_id<?php echo $f;?>" 
                                                           id="template_order_id<?php echo $f;?>" value="<?php echo $order['template_order_id']?>" />
    												<div class="panel-body text-small"  style="width: 10%;float: left;"><b>Your Client Price <?php //echo isset($userPrice_permission['currency_code']) ? $userPrice_permission['currency_code'] : ''; ?></b><br />
                                                        <input type="text" class="form-control  validate[required]"  name="price_uk_<?php echo $f;?>"  onblur="edit_price(<?php echo $f;?>)" id="price_uk_<?php echo $f;?>" value="<?php echo isset($order['price_uk'])? $order['price_uk']:'';?>"  />
                                                    </div>
                                                     <div class="panel-body text-small"  style="width: 10%;float: left;"><b>Your Client Total Price <?php //echo isset($userPrice_permission['currency_code']) ? $userPrice_permission['currency_code'] : ''; ?></b><br />
                                                     <input type="hidden" name="qty_<?php echo $f;?>" id="qty_<?php echo $f;?>" value="<?php echo $order['quantity'];?>" />
                                                     <span id="total_price_uk_<?php echo $f;?>"><?php echo isset($order['price_uk'])? ($order['price_uk']*$order['quantity']):'';?></span>
                                                    </div>     
                                                    <div class="panel-body text-small" style="width: 100%;float: left;"><b>Template Title : </b>
													
													<?php 
													if($multi_custom_order_id!='')
														echo $order['title'];
													else
														echo '<a href="'.$obj_general->link('product_template', 'mod=view&template_id='.encode($order['product_template_id']).'', '',1).'" target="new">'.$order['title'].'</a>'; 
                                                    if(!empty($order['expected_ddate']) && ($order['expected_ddate'] != '0000-00-00'))
														{
															$ex_delivery_date = dateFormat(4,$order['expected_ddate']);
														}
														else
														{
															$ex_delivery_date = 'NA';
														}

														?>
                                                    	<div class="panel-body text-small"  style="width: 30%;float: right;"><b>Expected Delivery Date : </b>(<?php echo $ex_delivery_date;?>)<br />  <input type="hidden" name="ddate_<?php echo $f;?>" id="ddate_<?php echo $f;?>" value="<?php echo $order['expected_ddate'];?>" /></div> 
                                                        
                                                       
                                                    <br />
													<?php 
												
														$result = json_decode($order['process_by']);
														//printr($result);
														$final_ddate = $obj_template->getFinalddate($order['product_template_order_id'],$order['template_order_id']);
													//printr($final_ddate);
														if(!empty($final_ddate['new_final_ddate']) && ($final_ddate['new_final_ddate'] != '0000-00-00'))
														{
															$ex_ddate = dateFormat(4,$final_ddate['new_final_ddate']);
														}
														else
														{
															$ex_ddate = 'NA';
														}
														
													$final_review=isset($final_ddate['new_final_review'])?$final_ddate['new_final_review']:'';
												$process_by = $obj_template->getUser($result->user_id,$result->user_type_id);
												//printr($process_by);
												if($multi_custom_order_id!='')
												{
													echo '<b>Accepted By : </b><span style="color:#D4613B">'.$process_by['user_name'].'</span>  <b>On</b> <span style="color:#D4613B"> '.dateFormat(4,$result->currdate).'</span>
													
													<br><br><b>Final Expected Delivery Date : </b><span>'.dateFormat(4,$order['expected_ddate']).'</span>
													<br><b>Review  : </b><span>'.$order['review'].'</span></div>';
												}
												else
												{
													if(isset($result->action) && $result->action==1)
													echo '<b>Accepted By : </b><span style="color:#D4613B">'.$process_by['user_name'].'</span>  <b>On</b> <span style="color:#D4613B"> '.dateFormat(4,$result->currdate).'</span>
													
													<br><br><b>Final Expected Delivery Date : </b><span>'.$ex_ddate.'</span>
													<br><b>Review  : </b><span>'.$final_review.'</span></div>';
												}
													
												?>
                                                      <input type="hidden" name="final_ddate_<?php echo $f;?>" id="final_ddate_<?php echo $f;?>" value="<?php echo $final_ddate['new_final_ddate'];?>" /> 
                                                      <input type="hidden" name="final_review_<?php echo $f;?>" id="final_review_<?php echo $f;?>" value="<?php echo $final_ddate['new_final_review'];?>" />      
                                                                                      
												   	<?php if($order['note'] != '')
                                                    {
                                                    ?>
                                                   		<div class="panel-body text-small" style="width: 100%;float: left;color:red"><b>Note : </b><?php echo $order['note'];?></div>    
                                               <?php }?>   
												   <?php if(isset($obj_session->data['ADMIN_LOGIN_USER_TYPE']) && $obj_session->data['ADMIN_LOGIN_USER_TYPE'] != '')
														{
															$admin_type_id=$obj_session->data['ADMIN_LOGIN_USER_TYPE'];
														} 
														else
														{
															$admin_type_id=$obj_session->data['LOGIN_USER_TYPE'];
														}
                                                  
													    $menu_id = $obj_template->getMenuPermission(ORDER_DISPATCHED_ID,$obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE']);
														if($menu_id OR $obj_session->data['LOGIN_USER_TYPE']==1)
														{
															 	$str=substr( $order['gen_order_id'],0,4);
																//echo $str;
																 if($str!='CUST')
                                                   			 	{
                                                    	?>												
	                                                      	 
                                                          	 <div class="panel-body text-small" style="float: right;">
                                                            <input type="button" name="dispatch<?php echo $f;?>" id="dispatch<?php echo $f;?>" value="Dispatch"  class="btn btn-warning" onclick="dispatch(<?php echo $f;?>,<?php echo $order['template_order_id']?>,<?php echo $order['product_template_order_id']?>,<?php echo $order['client_id']?>,<?php echo $order['quantity']?>,<?php echo $order['quantity']-$dis_qty['total_dis_qty']; ?> )" >
                                                            <input type="button" name="delay<?php echo $f;?>" id="delay<?php echo $f;?>" value="Delay"  class="btn btn-success" onclick="delay(<?php echo $f;?>,<?php echo $order['template_order_id']?>,<?php echo $order['product_template_order_id']?>,<?php echo $order['client_id']?>)" >
                                                	 <?php 
														$menu_id = $obj_template->getMenuPermission(ORDER_DECLINE_ID,$obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE']);
														if($menu_id OR $obj_session->data['LOGIN_USER_TYPE']==1)
														{
														?>
														<input type="button" name="decline<?php echo $f;?>" id="decline<?php echo $f;?>" value="Decline" onclick="review(<?php echo $f;?>,<?php echo $order['template_order_id']?>,<?php echo $order['product_template_order_id']?>,<?php echo $order['client_id']?>,<?php echo $order['quantity']-$dis_qty['total_dis_qty']; ?>)" class="btn btn-danger"> 
                               							 </div>    
                                                      	<?php  } } ?> 
													<?php }?> 
                                                                                          
    											</div>
    										</div> 
    
   								 		</section>
                                        
                                        <?php 
                                    $f++;
                                    }
                                    //pagination
                                    $pagination = new Pagination();
                                    $pagination->total = $total_orders;
                                    $pagination->page = $page;
                                    $pagination->limit = $limit;
                                    $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                                    $pagination->url = $obj_general->link($rout,'&page={page}&limit='.$limit, '',1);//HTTP_ADMIN.'index.php?rout='.$rout.'&page={page}';
                                    $pagination_data = $pagination->render();
                                  
								} else{ 
								echo "No record found !"; 
								} ?>
    
                        </div>
                    </form>
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

<!-- Modal -->
<div class="modal fade" id="smail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="sform" id="sform" style="margin-bottom:0px;">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <input type="hidden" name="template_order_iddispatch" id="template_order_iddispatch" value="" />
                   <input type="hidden" name="admindispatch" id="admindispatch" value="<?php echo ADMIN_EMAIL;?>" />
                   <input type="hidden" name="client_iddispatch" id="client_iddispatch" value=""/>
                   <input type="hidden" name="total_qtydispatch" id="total_qtydispatch" value=""/>
                   <input type="hidden" name="rem_qtydispatch" id="rem_qtydispatch" value=""/>
                <input type="hidden" name="product_template_order_iddispatch" id="product_template_order_iddispatch" value=""/>
                
                <h4 class="modal-title" id="myModalLabel">Dispatched Order</h4>
              </div>
             <?php /*?> <div class="modal-body">
                   <div class="form-group">
                        <label class="col-lg-3 control-label">Track ID</label>
                        <div class="col-lg-8">
                        <input type="text" name="track_id" id="track_id" value="" placeholder="Track ID"  class="form-control validate[required]"/>
                        </div>
                     </div> 
              </div><?php */?>
              
              <div class="form-group"> 
           		<label class="col-lg-3 control-label">Quantity</label> 
                <div class="col-lg-4"> 
						<input type="quantity" name="dis_qty" id="dis_qty" value="" placeholder="Qty"  class="form-control validate[required]"/>
             </div> 
           </div><br />
              
           <div class="form-group"> 
           		<label class="col-lg-3 control-label">Date</label> 
                <div class="col-lg-9"> 
                	<input type="text" class="combodate form-control" data-format="DD-MM-YYYY" data-required="true" data-template="D MMM YYYY" name="datetime" id="datetime" value="<?php echo date("d-m-Y");   ?> ">
                </div> 
           </div>
          
            <?php /*?>  <div class="modal-body">
                   <div class="form-group">
                        <label class="col-lg-3 control-label">Courier</label>
                        <div class="col-lg-8">
                        <?php echo $obj_template->getCourierCombo();?>
                        </div>
                     </div> 
              </div><?php */?>
              
               <div class="modal-body">
                   <div class="form-group">
                        <label class="col-lg-3 control-label">Remark</label>
                        <div class="col-lg-8">
                        <input type="text" name="reviewdispatch" id="reviewdispatch" value="" placeholder="Remark"  class="form-control validate[required]"/>
                        </div>
                     </div> 
              </div>
              
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="button" onclick="updatestockorderstatus('dispatch',3,'')" name="btn_dispatch" class="btn btn-warning">Dispatch</button>
                <?php $name="quantity"==""; ?>
              </div>
   		</form>   
    </div>
  </div>
</div>

<div class="modal fade" id="reviewsmail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="sform" id="sform" style="margin-bottom:0px;">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <input type="hidden" name="template_order_idreview" id="template_order_idreview" value="" />
                 <input type="hidden" name="adminreview" id="adminreview" value="<?php echo ADMIN_EMAIL;?>" />
                <input type="hidden" name="product_template_order_idreview" id="product_template_order_idreview" value=""/>
                <input type="hidden" name="client_idreview" id="client_idreview" value=""/>
                 <input type="hidden" name="rem_qtyreview" id="rem_qtyreview" value=""/>
                <h4 class="modal-title" id="myModalLabel">Review For Decline Order</h4>
              </div>
              <div class="modal-body">
                   <div class="form-group">
                        <label class="col-lg-3 control-label">Review</label>
                        <div class="col-lg-8">
                             <textarea name="reviewreview" id="reviewreview" placeholder="Review" value="" class="form-control validate[required]"></textarea>
                        </div>
                     </div> 
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="button" onclick="updatestockorderstatus('review',2,'')" name="btn_decline" class="btn btn-danger">Decline</button>
              </div>
   		</form>   
    </div>
  </div>
</div>

<!-- Model For Delay-->
<div class="modal fade" id="date" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="sform" id="sform" style="margin-bottom:0px;">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <input type="hidden" name="template_order_iddelay" id="template_order_iddelay" value="" />
                 <input type="hidden" name="admindelay" id="admindelay" value="<?php echo ADMIN_EMAIL;?>" />
                <input type="hidden" name="product_template_order_iddelay" id="product_template_order_iddelay" value=""/>
                <input type="hidden" name="client_iddelay" id="client_iddelay" value=""/>
               
                <input type="hidden" name="final_datedelay" id="final_datedelay" value="<?php echo $final_ddate['new_final_ddate'];?>"/>
                <input type="hidden" name="final_reviewdelay" id="final_reviewdelay" value="<?php echo $final_ddate['new_final_review'];?>"/>
                <input type="hidden" name="acc_user_iddelay" id="acc_user_iddelay" value="<?php echo $final_ddate['user_id'];?>"/>
                <input type="hidden" name="acc_user_type_iddelay" id="acc_user_type_iddelay" value="<?php echo $final_ddate['user_type_id'];?>"/>
                
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
                <button type="button" onclick="updatestockorderstatus('delay','',1)" name="btn_accept" class="btn btn-success">Update</button>
              </div>
   		</form>   
    </div>
  </div>
</div>
 
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
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
 <!-- combodate --> <script src="<?php echo HTTP_SERVER;?>js/combodate/moment.min.js"></script> 
 <script src="<?php echo HTTP_SERVER;?>js/combodate/combodate.js"></script>
<script type="application/javascript">
jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#sform").validationEngine();
});
function div_hide(hide1,hide2,hide3,showid)
{
	$('#'+hide2).css({ display: "none" });
	$('#'+hide3).css({ display: "none" });
	$('#'+hide1).css({ display: "none" });
	$('#'+hide2).hide();
	$('#'+hide3).hide();
	$('#'+hide1).hide();
	$('#'+showid).show();
}
function edit_price(id)
{
	
	$('#hiddenField_'+id).show();
	$('#price_'+id).hide();
	$("input[type=text][id=edit_price_"+id+"]").focusout(function(){
		var  postArray = {};
		postArray['price'] = $("input[type=text][id=edit_price_"+id+"]").val();
		postArray['template_order_id'] = $("#template_order_id"+id).val();
		postArray['product_template_order_id'] = $("#product_template_order_id"+id).val();
		postArray['status'] =1;
   		var order_price_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=update_price', '',1);?>");
			$.ajax({
				url : order_price_url,
				method : 'post',
				data : {postArray : postArray},
				success: function(response){
	
				set_alert_message('Successfully Updated',"alert-success","fa-check");
				 window.setTimeout(function(){location.reload()},1000)
				},
				error: function(){
					return false;	
				}
				});
		$('#hiddenField_'+id).hide();
		$('#price_'+id).show();
		$('#price_'+id).html(postArray['price']);
    });
	
}

	function delay(id,template_order_id,product_template_order_id,client_id)
	{
		
		var final_date = $("#final_ddate_"+id).val();
		var review = $("#final_review_"+id).val();
		var arr = final_date.split('-');
		
		var month = parseInt(arr[1]) - 1;
	
		$(".note-error").remove();
		$("#due_date").val(final_date);
		$('.day option[value="' + parseInt(arr[2]) + '"]').prop('selected', true);
		$('.month option[value="' + month + '"]').prop('selected', true);
		$('.year option[value="' + arr[0] + '"]').prop('selected', true);
		
		$("#reason").val(review);
		$("#date").modal('show');
		$("#template_order_iddelay").val(template_order_id);
		$("#product_template_order_iddelay").val(product_template_order_id);
		$("#client_iddelay").val(client_id);
		
	}

	function dispatch(id,template_order_id,product_template_order_id,client_id,qty,rem_qty)
	{
		
		$(".note-error").remove();
		$("#smail").modal('show');
		$("#template_order_iddispatch").val(template_order_id);
		$("#product_template_order_iddispatch").val(product_template_order_id);
		$("#client_iddispatch").val(client_id);
		$("#total_qtydispatch").val(qty);
		$("#rem_qtydispatch").val(rem_qty);
	}	
	function review(id,template_order_id,product_template_order_id,client_id,rem_qty)
	{	
		
		$(".note-error").remove();
		$("#reviewsmail").modal('show');
		$("#template_order_idreview").val(template_order_id);
		$("#product_template_order_idreview").val(product_template_order_id);
		$("#client_idreview").val(client_id);
		$("#rem_qtyreview").val(rem_qty);
	}
	function updatestockorderstatus(id,status,n)
	{
		
		if(status == 3)
		{
			var newid = id;
		}
		else if(status == 2)
		{
			var newid = id;		
		}	
		
		if(status == 2)
		{
			if($("#review"+newid).val()=='')
			{
				$(".note-error").remove();
				alert('Please Give Review');
				return false;
			}
		}
		if(n == '1')
		{
			var newid = id;	
			$("#date").modal('hide');
			var due_date = $("#due_date").val();
			var reason = $("#reason").val();
			var old_date = $("#final_date"+newid).val();
			var old_review = $("#final_review"+newid).val();
			var u_id = $("#acc_user_id"+newid).val();
			var u_type_id= $("#acc_user_type_id"+newid).val();
		}
		
		var dis_qty = $("#dis_qty").val();
		var rem_qty = $("#rem_qty"+newid).val();
	
		if(parseInt(rem_qty) < parseInt(dis_qty))
		{
			$(".note-error").remove();
			alert('Please Give Qty Less than '+ rem_qty);
			$("#dis_qty").val('');
			return false;
		}
		
	
		
		var datetime = $("#datetime").val();		
		var review = $("#review"+newid).val();		
	
		if((datetime!='') || review!='')
		{
			$("#dis_qty"+newid).val('');
			$("#datetime"+newid).val('');
			$("#review"+newid).val('');
			var adminEmail = $("#admin"+newid).val();
			$("#smail").modal('hide');
			$("#reviewsmail").modal('hide');
			var postArray = {};
			postArray['template_order_id'] = $("#template_order_id"+newid).val();
			postArray['product_template_order_id'] = $("#product_template_order_id"+newid).val();
			postArray['client_id'] = $("#client_id"+newid).val();
			postArray['dis_qty'] =dis_qty;
			postArray['datetime'] =datetime;
			postArray['review'] =review;
			postArray['due_date'] =due_date;
			postArray['reason']=reason;
			postArray['old_review']=old_review;
			postArray['old_date']=old_date;
			postArray['u_id']=u_id;
			postArray['u_type_id']=u_type_id;
			postArray['status'] =status;
			postArray['total_qty'] = $("#total_qty"+newid).val();
			postArray['rem_qty'] =rem_qty;
			
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
				set_alert_message('Successfully Updated',"alert-success","fa-check");
				$('#loading').hide();
				  window.setTimeout(function(){location.reload()},1000)
				},
				error: function(){
					return false;	
				}
				});
		}
		else
		{
			alert('Please Fill Form');
			
		}
	}
	
function edit_price(id)
{
	var price = $('#price_uk_'+id).val();
	var qty = $('#qty_'+id).val();
	var tot = price*qty;
	$('#total_price_uk_'+id).html(tot);
	var template_order_id = $("#template_order_id"+id).val();
	var price_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=updatePriceUk', '',1);?>");
	$.ajax({
			url : price_url,
			method : 'post',
			data : {price : price,template_order_id:template_order_id},
			success: function(response){
	
			set_alert_message('Successfully Updated',"alert-success","fa-check");
			  window.setTimeout(function(){location.reload()},1000)
			},
			error: function(){
				return false;	
			}
	});
}
	/*function updatestockorderstatus()
	{
//		alert('sdf');		
		var track_id = $("#track_id").val();		
		var datetime = $("#datetime").val();		
		var courier_id = $("#courier_id").val();		
		var review = $("#review").val();		
		//alert(courier_id);
		if(track_id != '' && datetime!='' && courier_id!='')
		{
			$("#track_id").val('');
			$("#datetime").val('');
			$("#courier_id").val('');
			$("#review").val('');
			var adminEmail = $("#admin").val();
			$("#smail").modal('hide');
			var postArray = {};
			postArray['template_order_id'] = $("#template_order_id").val();
			postArray['product_template_order_id'] = $("#product_template_order_id").val();
			postArray['track_id'] =track_id;
			postArray['datetime'] =datetime;
			postArray['courier_id'] =courier_id;
			postArray['review'] =review;
			postArray['status'] =3;
			var d = new Date();
			var curr_date = d.getDate();
    		var curr_month = d.getMonth();
   			 curr_month++;   // need to add 1 – as it’s zero based !
    		var curr_year = d.getFullYear();
    		var formattedDate = curr_date + "-" + curr_month + "-" + curr_year;
			postArray['currdate'] = formattedDate;
			var order_status_url = getUrl("<?php //echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=updatestockorderstatus', '',1);?>");
			$.ajax({
				url : order_status_url,
				method : 'post',
				data : {postArray : postArray,adminEmail:adminEmail},
				success: function(response){
				//alert(response);	
				set_alert_message('Successfully Updated',"alert-success","fa-check");
				  window.setTimeout(function(){location.reload()},1000)
				},
				error: function(){
					return false;	
				}
				});
		}
		else
		{
			alert('Please Fill Form');
			//return false();
		}
	}*/
</script>           
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>