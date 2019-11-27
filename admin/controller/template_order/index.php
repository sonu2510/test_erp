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
$class = 'collapse';
$filter_data=array();

if(!isset($_GET['filter_edit']) || $_GET['filter_edit']==0){
	if(isset($obj_session->data['filter_data'])){
		unset($obj_session->data['filter_data']);	
	}
}

if(!$obj_general->hasPermission('view',$menuId)){
	$display_status = false;
}
$limit = LISTING_LIMIT;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}

/*if(isset($_GET['sort'])){
	$sort = $_GET['sort'];	
}else{
	$sort= 't.template_order_id ';
}

if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'DESC';	
}*/
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
$client_id = isset($_GET['client_id'])?$_GET['client_id']:'';
$client = base64_decode($client_id);
$stock_order_id_encoded=isset($_GET['stock_order_id'])?$_GET['stock_order_id']:'';
$stock_order_id = base64_decode($stock_order_id_encoded);
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
    					<span>New Order Listing </span>
    					<span class="text-muted m-l-small pull-right">
						<?php  if(isset($obj_session->data['ADMIN_LOGIN_USER_TYPE']) && $obj_session->data['ADMIN_LOGIN_USER_TYPE'] != '')
								{
									$admin_type_id=$obj_session->data['ADMIN_LOGIN_USER_TYPE'];
								} 
								else
								{
									$admin_type_id=$obj_session->data['LOGIN_USER_TYPE'];
								} 
                                
								if($obj_general->hasPermission('add',$menuId)){
			                    if($admin_type_id==4){
									if($obj_session->data['LOGIN_USER_TYPE'] != 1)
									{
										if($permission ==0 && $checkNewCartPermission[0]['status']==1 ) {}
										else
										{?>
										<a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add&s_no='.encode($permission).'',
										 '',1);?>"><i class="fa fa-plus"></i> New Stock Order </a>
									<?php } 
									}
								}
                            	} ?>      
    					</span>
    				</header>
                    <div class="panel-body">
                        <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link
						($rout, 'mod=index&client_id='.$client_id.'', '',1); ?>">
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
                                    	<?php $val = explode("=",$filter_user_name);
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
                        <a href="<?php echo $obj_general->link($rout, 'mod=index&client_id='.$client_id, '',1); ?>" 
                        class="btn btn-info btn-sm pull-right" ><i class="fa fa-refresh"></i> Refresh</a>
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
                       		 
                            <option value="<?php echo $obj_general->link($rout, 'mod=index&client_id='.$client_id.'&limit='.$display_limit, '',1);?>" 
                            selected="selected"><?php echo $display_limit; ?></option>				
                    <?php } else { ?>
                            <option value="<?php echo $obj_general->link($rout, 'mod=index&client_id='.$client_id.'&limit='.$display_limit, '',1);?>">
							<?php echo $display_limit; ?></option>
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
									/*$option = array(
									'sort'  => $sort,
									'order' => $sort_order,
									'start' => ($page - 1) * $limit,
									'limit' => $limit,							
									);	*/
							$total_order = $obj_template->GetOrderList($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],'AND t.status = 1 AND sos.status=0 AND t.stock_order_id='.decode($_GET['stock_order_id']).'','','',$filter_data,$client,'','','','','',$stock_order_id); 
								$pagination_data = '';	
								$total_orders=count($total_order);
							
								if($total_order!=''){
									
                            		$orders = $obj_template->GetOrderList($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],'AND t.status = 1 AND sos.status=0 AND t.stock_order_id='.decode($_GET['stock_order_id']).'','','',$filter_data,$client,'','','','','',$stock_order_id);
								//	printr($order['shipment_country']);
									$start_num =((($page*$limit)-$limit)+1);
									$f = 1;
									$slNo = $f+$start_num;
									$total=0;$total_qty=0;
								
                            		foreach($orders as $order){  
									$new_price=$obj_template->getUpdatedPrice($order['product_template_order_id'],$order['template_order_id']);
									if(isset($new_price) && $new_price!='')
									$order['price']=$new_price;
									$edit_price_menu_id = $obj_template->getMenuPermission(ORDER_PRICEEDIT_ID,$obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE']);?>
                                    
                                   
                                        <section class="panel pos-rlt clearfix"  style="margin-top: 10px; border: 2px solid rgb(140, 193, 237)"> 
                                            <header class="panel-heading" style="border-color: #8CC1ED;background: #8CC1ED;"> 
                                                <ul class="nav nav-pills pull-right"> 
                                                    <li>
                                                        <?php
                                                        $postedByData = $obj_template->getUser($order['user_id'],$order['user_type_id']);
														
                                                        $addedByImage = $obj_general->getUserProfileImage($obj_session->data['LOGIN_USER_TYPE'],
														$obj_session->data['ADMIN_LOGIN_SWISS'],'100_');
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
                                                        <a  data-trigger="hover" data-toggle="popover" data-html="true" data-placement="top" 
                                                        data-content='<?php echo $postedByInfo;?>' title="" data-original-title="<b>
														<?php echo $postedByName;?></b>">
                                                        <span class="label bg-info" style="font-size: 100%; ">Order By 
														<?php echo $postedByData['user_name'];?></span></a>
                                                    </li>
                                                    <li>
                                                        <a href="#" class="panel-toggle text-muted">
                                                            <i class="fa fa-caret-down fa-lg text-active"></i>
                                                            <i class="fa fa-caret-up fa-lg text"></i>
                                                        </a> 
                                                    </li>     
                                                </ul>
                                                <span class="label bg-info" style="font-size: 100%;"><?php echo $order['gen_order_id'];?></span>
 												<span class="label bg-info" style="font-size: 100%;background: #8CC1ED;">
												<?php echo  preg_replace("/\([^)]+\)/","",$order['product_name']);?></span>
                                        <?php if($order['shipment_country']=='252'){?>
												         <span class="label bg-success" style="font-size: 100%;margin-left:30px; background:#8CC1ED;"><?php echo 'Reference No :'.$order['reference_no'];?></span><?php  } ?>
                                            </header> 
                                            <?php if($edit_price_menu_id OR $obj_session->data['ADMIN_LOGIN_SWISS']==1){?>
                                            <header class="panel-heading text-right"> 
                                            <ul class="nav nav-tabs pull-left"> 
                                            <li class="active" id="detail"><a href="#detail_<?php echo $f;?>" data-toggle="tab" onclick="div_hide('history_<?php echo $f;?>','detail_<?php echo $f;?>')">Order Detail</a></li> 	
                                            <li class=""><a href="#history_<?php echo $f;?>" data-toggle="tab" onclick="div_hide('detail_<?php echo $f;?>','history_<?php echo $f;?>')">Price Update History</a></li> 
                                            </ul>
                                            </header>
                                            
                                            <div class="tab-pane fade" id="history_<?php echo $f;?>" style="display:none">
                                            <?php $price_history=$obj_template->getUpdatedPriceHistory($order['product_template_order_id'],$order['template_order_id']);if(isset($price_history) && !empty($price_history)) {?>
                                            <div> 
                                            	<table class="table table-striped m-b-none text-small"> 
                                                	<thead> 
                                                        <tr> 
                                                        	
                                                            <th>User Name</th>
                                                            <th >Actual Price</th> 
                                                            <th >Price</th> 
                                                            <th>Date</th> 
                                                            <th>Stage</th> 
                                                        </tr> 
                                                    </thead> 
                                                    <tbody> 
                                                    <?php foreach($price_history as $k=>$value) {?>
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
                                            <?php }
									?>
                                            <div class=" panel-body clearfix tab-pane fade active in " id="detail_<?php echo $f;?>"> 
                                                <div id="collapseOne" class="panel-collapse in"> 
                                                    <div class="panel-body text-small" style="width:25%;float: left;"><b>Option : </b><?php echo 
														$order['zipper'].' '.$order['valve'].' '.$order['spout'].' '.$order['accessorie'].' '.$order['accessorie_txt_corner'].'<br><b>
														Dimension (Size) : </b>'.$order['width'].'X'.$order['height'].'X'.$order['gusset'].' <span style="color:red;font-weight:bold">('.
														$order['volume'].')</span><br><b>Color : </b>'.$order['color'].'<br><b>Order Date :</b>'.
														dateFormat(4,$order['date_added']); ?>
                                                    </div>
                                                    <div class="panel-body text-small"  style="width: 20%;float: left;">
														<?php echo '<b>Transportation : </b>'.$order['transportation_type'].'<br>';?>
                                                    	<b>Shipment Country : </b><?php echo $order['country_name'].' / '; 
                                                    	if($order['ship_type'] == 0) echo 'Self'; else echo 'Client';
                                                    	echo '<br><b>Client : </b>';
                                    							$add_url_to=ucwords($order['client_name']);
                                    								if($order['address_book_id']!='0' && $obj_general->hasPermission('view',178))
                                    									$add_url_to='<a href="'.$obj_general->link('address_book', '&mod=view&address_book_id=' . encode($order['address_book_id']), '', 1).'">'.ucwords($order['client_name']).'</a>';
                                    						   
                                    						   echo $add_url_to;
                                                    		echo '<br><b>Address :  </b><br>
															<pre>'.$order['address'].'</pre>';?>
                                                    </div>
                                                    <div class="panel-body text-small"  style="width: 10%;float: left;"><b>Quantity </b><br />
														<?php echo $order['quantity']; ?>
                                                    </div>
                                                    <?php if((isset($userPrice_permission['stock_order_price']) && $userPrice_permission['stock_order_price']==1) || ($_SESSION['ADMIN_LOGIN_SWISS']==1 && $_SESSION['LOGIN_USER_TYPE']==1))
													{
													?>
                                                    <div class="panel-body text-small"  style="width: 15%;float: left;"><b>Price Per Unit </b><br />
														<?php echo $order['currency_code'].' <span id="price_'.$f.'">'.$order['price'].'</span>'; ?> 
                                                     <?php  
												   if($edit_price_menu_id OR $obj_session->data['LOGIN_USER_TYPE']==1){?>
                                                        <span id="<?php echo 'hiddenField_'.$f;?>" style="display:none" >
                                                        <input type="text" contenteditable="true" id="<?php echo 'edit_price_'.$f; ?>" style="width:55px;border: 2px solid rgb(72, 159, 231);" value="<?php echo $order['price'];?>"/></span>
                                                        <div class="btn-group"> 
                                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" onclick="edit_price(<?php echo $f;?>)">
                                                        <i class="fa fa-pencil"></i>
                                                        </a> 
                                                        </div>
                                                        <?php }?>
                                                    </div>
                                                   
                                                    <div class="panel-body text-small"  style="width: 10%;float: left;"><b>Total Price </b><br />
														<?php echo $order['currency_code'].' '.$order['price']*$order['quantity'];  ?>
                                                    </div> 
                                                    
                                                    
                                                    <?php 
													}
													?> 
                                                    <div class="panel-body text-small"  style="width: 10%;float: left;"><b>Your Client Price <?php //echo isset($userPrice_permission['currency_code']) ? $userPrice_permission['currency_code'] : ''; ?></b><br />
                                                        <input type="text" class="form-control  validate[required]"  name="price_uk_<?php echo $f;?>"  onblur="edit_price(<?php echo $f;?>)" id="price_uk_<?php echo $f;?>" value="<?php echo isset($order['price_uk'])? $order['price_uk']:'';?>"  />
                                                    </div>
     												<div class="panel-body text-small"  style="width: 10%;float: left;"><b>Your Client Total Price <?php //echo isset($userPrice_permission['currency_code']) ? $userPrice_permission['currency_code'] : '';?></b><br />
                                                     <input type="hidden" name="qty_<?php echo $f;?>" id="qty_<?php echo $f;?>" value="<?php echo $order['quantity'];?>" />
                                                     <span id="total_price_uk_<?php echo $f;?>"><?php echo isset($order['price_uk'])? ($order['price_uk']*$order['quantity']):'';?></span>
                                                    </div>     
                                                    <div class="panel-body text-small" style="width: 100%;float: left;"><b>Template Title : </b>
														<?php echo '<a href="'.$obj_general->link('product_template', 'mod=view&template_id=
                                                    '.encode($order['product_template_id']).'', '',1).'" target="new">'.$order['title'].'</a>';
													if(!empty($order['expected_ddate']) && ($order['expected_ddate'] != '0000-00-00'))
														{
															$ex_delivery_date = dateFormat(4,$order['expected_ddate']);
														}
														else
														{
															$ex_delivery_date = 'NA';
														}
													
													
													 ?>
                                                     
                                                    <div class="panel-body text-small"  style="width: 30%;float: right;"><b>Expected Delivery Date : </b>(<?php echo $ex_delivery_date;?>)<br />  <input type="hidden" name="ddate_<?php echo $f;?>" id="ddate_<?php echo $f;?>" value="<?php echo $order['expected_ddate'];?>" /></div>                                    </div>  
												   	<?php if($order['note'] != '')
                                                    {
                                                    ?>
                                                   		<div class="panel-body text-small" style="width: 100%;float: left;color:red"><b>Note : </b>
															<?php echo $order['note'];?></div>                             <?php }?>   
												   <?php 
												   $menu_id = $obj_template->getMenuPermission(ORDER_ACCEPT_ID,$obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE']);
												   if($menu_id OR $obj_session->data['LOGIN_USER_TYPE']==1){ //echo $f;?>
                                                        <div class="panel-body text-small" style="float: right;">
                                                           
                                                           <input type="hidden" name="product_template_order_id<?php echo $f;?>" id="product_template_order_id<?php echo $f;?>" value="<?php echo $order['product_template_order_id']?>"/>
                                                            <input type="hidden" name="client_id<?php echo $f;?>" id="client_id<?php echo $f;?>" value="<?php echo $order['client_id']?>"/>
                                                         
                                                           <input type="button" name="approve<?php echo $f;?>" id="approve<?php echo $f;?>" 
                                                            value="Accept"  class="btn btn-success" onclick="expacted(<?php echo $f;?>,<?php echo $order['template_order_id']?>,<?php echo $order['product_template_order_id']?>,<?php echo $order['client_id']?>)">
                                                            
															<?php /*?><input type="button" name="approve<?php echo $f;?>" id="approve<?php echo $f;?>" 
                                                            value="Accept"  class="btn btn-success" onclick="updatestockorderstatus(<?php echo $f;?>,1)"><?php */?>
                                                            
                                                            <input type="button" name="decline<?php echo $f;?>" id="decline<?php echo $f;?>" 
                                                            value="Decline" onclick="review(<?php echo $f;?>,<?php echo $order['template_order_id']?>,<?php echo $order['product_template_order_id']?>,<?php echo $order['client_id']?>,<?php echo $order['quantity'];?>)" class="btn btn-danger">     	 
                                                        </div>    
   													<?php }?>   
                                                    <input type="hidden" name="template_order_id<?php echo $f;?>" 
                                                           id="template_order_id<?php echo $f;?>" value="<?php echo $order['template_order_id']?>" />                                    
    											</div>
    										</div> 
    
   								 		</section>                        
									<?php 
                                    $f++;
                                    }
									
									?>
                                  
                                    <?php
                                    //pagination
                                    $pagination = new Pagination();
                                    $pagination->total = $total_orders;
                                    $pagination->page = $page;
                                    $pagination->limit = $limit;
                                    $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                                    $pagination->url = $obj_general->link($rout,'&page={page}&limit='.$limit, '',1);
                                    $pagination_data = $pagination->render();
                                } else{ 
								echo "No record found !";
								} ?>
    
                        </div> <input type="hidden" name="admin" id="admin" value="<?php echo ADMIN_EMAIL;?>" />
                         
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
                <input type="hidden" name="rem_qty" id="rem_qty" value=""/>
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


<script type="application/javascript">
jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#sform").validationEngine();
		
		/*$("ul.nav li#detail").click(function(){
		
			
			//alert("hiii");
		});*/
});


function div_hide(hideid,showid)
{
	
	$('#'+hideid).css({ display: "none" });
	
	$('#'+hideid).hide();
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
		postArray['status'] =0;
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
	function review(id,template_order_id,product_template_order_id,client_id,tot_qty)
	{	
		$(".note-error").remove();
		$("#smail").modal('show');
		$("#template_order_id").val(template_order_id);
		$("#product_template_order_id").val(product_template_order_id);
		$("#client_id").val(client_id);
		$("#rem_qty").val(tot_qty);
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
				var rem_qty = $("#rem_qty").val();
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
			
			postArray['template_order_id'] = $("#template_order_id"+newid).val();
			postArray['product_template_order_id'] = $("#product_template_order_id"+newid).val();
			postArray['client_id'] = $("#client_id"+newid).val();
			postArray['review'] =review;
			postArray['status'] =status;
			postArray['due_date']=due_date;
			postArray['reason']=reason;
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
					
						set_alert_message('Successfully Updated',"alert-success","fa-check");
						$('#loading').hide();
						window.setTimeout(function(){location.reload()},1000)
					},
					error: function(){
						return false;	
					}
				});
		
	}

function edit_price(id)
{
	
	var price = $('#price_uk_'+id).val();
	var qty = $('#qty_'+id).val();
	var tot = price*qty;
	$('#total_price_uk_'+id).html(tot);
	var template_order_id = $("#template_order_id"+id).val();//alert(template_order_id);
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


</script>           
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>