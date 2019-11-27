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
$menuId = 89;
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
$class = 'collapse';
//printr($obj_session->data);
$filter_data=array();

$client_id = $_GET['client_id'];
$client = base64_decode($_GET['client_id']);

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
	//printr($filter_data);
	//die;
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
	//printr($filter_data);
	//die;	
}
if($display_status) {
	$checkNewCartPermission = $obj_template->checkNewCartPermission($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE']);
	$orderLimit = $obj_template->orderLimit($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE']);
	$permission = '';
//printr($checkNewCartPermission);
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
	//printr($userPrice_permission );
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
    					<span>Dispatched Order Listing </span>
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
                        <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link($rout, '&mod=dispatch&client_id='.$client_id.'', '',1); ?>">
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
                                  		<input type="text" name="filter_order" value="<?php echo isset($filter_order) ? $filter_order : '' ; ?>" 			                                        placeholder="Order NO" id="input-name" class="form-control" />
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
									//printr($userlist);
									//die;
								?>
                                
                                <div class="col-lg-7">
                                
                                	<select class="form-control" name="filter_user_name">
                                    	<option value="">Please Select</option>
                                    	<?php $val = explode("=",$filter_user_name);
										
										
										foreach($userlist as $user) { ?>
                                        	<?php if(isset($filter_user_name) && !empty($val[0]) && $val[0] == $user['user_type_id'] && $val[1]==$user['user_id'] ) { ?>
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
            
                  <footer class="panel-footer <?php echo $class; ?>">
                    <div class="row">
                       <div class="col-lg-12">
                        <input type="hidden" value="<?php echo $status;?>" id="status" name="status" />
                        <button type="submit" class="btn btn-primary btn-sm pull-right ml5" name="btn_filter"><i class="fa fa-search"></i> Search</button>
                        <a href="<?php echo $obj_general->link($rout, '&mod=dispatch&client_id='.$client_id, '',1); ?>" class="btn btn-info btn-sm pull-right" ><i class="fa fa-refresh"></i> Refresh</a>
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
                       		 
                            <option value="<?php echo $obj_general->link($rout, 'mod=dispatch&client_id='.$client_id.'&limit='.$display_limit, '',1);?>" selected="selected"><?php echo $display_limit; ?></option>				
                    <?php } else { ?>
                            <option value="<?php echo $obj_general->link($rout, 'mod=dispatch&client_id='.$client_id.'&limit='.$display_limit, '',1);?>"><?php echo $display_limit; ?></option>
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
								$total_order = $obj_template->GetOrderList($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],'AND t.status = 1 AND sos.status=3',$option,'',$filter_data,$client);
								$total_orders=count($total_order);
								$pagination_data = '';
								if($total_order!=''){
									
                            		//printr($option);
									
									$orders = $obj_template->GetOrderList($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],'AND 
									t.status = 1 AND sos.status=3',$option,'',$filter_data,$client);
									//printr($orders);
									//die;
									$start_num =((($page*$limit)-$limit)+1);
									$f = 1;
									$slNo = $f+$start_num;
									$total=0;$total_qty=0;
                            		foreach($orders as $order){
									$new_price=$obj_template->getUpdatedPrice($order['product_template_order_id'],$order['template_order_id']);
									if(isset($new_price) && $new_price!='')
									$order['price']=$new_price; 
									$menu_id = $obj_template->getMenuPermission(ORDER_PRICEEDIT_ID,$obj_session->data['ADMIN_LOGIN_SWISS'],		                                                   $obj_session->data['LOGIN_USER_TYPE']);?>
                                        <section class="panel pos-rlt clearfix" style="margin-top: 10px; border: 2px solid #8CED9C"> 
                                            <header class="panel-heading" style="border-color: #8CED9C;background: #D2FFE5;"> 
                                                <ul class="nav nav-pills pull-right"> 
                                                    <li>
                                                        <?php
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
                                                        <span class="label bg-success" style="font-size: 100%; "> Order By <?php echo $postedByData['user_name'];?></span></a>
                                                    </li> 
                                                    <li>
                                                        <a href="#" class="panel-toggle text-muted">
                                                            <i class="fa fa-caret-down fa-lg text-active"></i>
                                                            <i class="fa fa-caret-up fa-lg text"></i>
                                                        </a> 
                                                    </li>     
                                                </ul>
                                             <span class="label bg-success" style="font-size: 100%;"><?php echo $order['gen_order_id'];?></span>
 												<span class="label bg-success" style="font-size: 100%; margin-left:10px">
												<?php echo preg_replace("/\([^)]+\)/","",$order['product_name']);?></span>
                                              </header> 
                                              <?php if($menu_id OR $obj_session->data['LOGIN_USER_TYPE']==1){?>
                                             <header class="panel-heading text-right"> 
                                            <ul class="nav nav-tabs pull-left"> 
                                            <li class="active"><a href="#detail_<?php echo $f;?>" data-toggle="tab" onclick="div_hide('history_<?php echo $f;?>','detail_<?php echo $f;?>')">Order Detail</a></li> 	
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
                                            <?php }?>
                                            <div class="panel-body clearfix tab-pane fade active in" id="detail_<?php echo $f;?>">
                                                <div id="collapseOne" class="panel-collapse in"> 
                                                    <div class="panel-body text-small" style="width:35%;float: left;"><b>Option : </b><?php echo $order['zipper'].' '.$order['valve'].' '.$order['spout'].' '.$order['accessorie'].'<br><b>Dimension (Size) : </b>'.$order['width'].'X'.$order['height'].'X'.$order['gusset'].' <span style="color:red;font-weight:bold">('.$order['volume'].')</span><br><b>Color : </b>'.$order['color'].'<br><b>Order Date :</b>'.dateFormat(4,$order['date_added']); ?>
                                                    </div>
                                                    <div class="panel-body text-small"  style="width: 35%;float: left;"><?php echo '<b>Transportation : </b>'.$order['transportation_type'].'<br>';?><b>Shipment Country : </b><?php echo $order['country_name'].' / '; 
                                                    if($order['ship_type'] == 0) echo 'Self'; else echo 'Client';
                                                    echo '<br><b>Client : </b>'.$order['client_name'].'<br><b>Address :  </b><br><pre>'.$order['address'].'</pre>';
													echo '<br><b>Track Id : '.$order['track_id'].'</b><br><b>Dispatched Date : </b>'.$order['date'].'
													<br><b>Courier : </b>'.$obj_template->getCourier($order['courier_id']);
													if(isset($order['review']) && $order['review']!='') 
													echo '<br><b>Remark : </b>'.$order['review'];?>
                                                    </div>
                                                    <div class="panel-body text-small"  style="width: 10%;float: left;"><b>Quantity </b><br /><?php echo $order['quantity']; ?>
                                                    </div>
                                                    <?php if(isset($userPrice_permission['stock_order_price']) && $userPrice_permission['stock_order_price']==1)
													{?>
                                                    <div class="panel-body text-small"  style="width: 10%;float: left;"><b>Price Per Unit</b><br /><?php echo $order['currency_code'].' <span id="price_'.$f.'">'.$order['price'].'</span>'; ?> 
                                                 
                                                     <?php  
												   if($menu_id OR $obj_session->data['LOGIN_USER_TYPE']==1){?>
                                                        <span id="<?php echo 'hiddenField_'.$f;?>" style="display:none" >
                                                        <input type="text" contenteditable="true" id="<?php echo 'edit_price_'.$f; ?>" style="width:55px;border: 2px solid rgb(72, 159, 231);" value="<?php echo $order['price'];?>"/></span>
                                                        <input type="hidden" name="template_order_id<?php echo $f;?>" 
                                                           id="template_order_id<?php echo $f;?>" value="<?php echo $order['template_order_id']?>" />
                                                           <input type="hidden" name="product_template_order_id<?php echo $f;?>" 
                                                           id="product_template_order_id<?php echo $f;?>" 
                                                           value="<?php echo $order['product_template_order_id']?>"/>
                                                        <div class="btn-group"> 
                                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" onclick="edit_price(<?php echo $f;?>)">
                                                        <i class="fa fa-pencil"></i>
                                                        </a> 
                                                        </div>
                                                        <?php }?>
                                                    </div>
                                                    <div class="panel-body text-small"  style="width: 10%;float: left;"><b>Total Price </b><br /><?php echo $order['currency_code'].' '.$order['price']*$order['quantity'];  ?>
                                                    </div>                    
                                                   <?php }?>
                                                    <div class="panel-body text-small" style="width: 100%;float: left;"><b>Template Title : </b><?php echo '<a href="'.$obj_general->link('product_template', 'mod=view&template_id=
                                                    '.encode($order['product_template_id']).'', '',1).'" target="new">'.$order['title'].'</a>'; ?>
                                                     <br />
													<?php 
														$result = json_decode($order['dispach_by']);
													//echo $result->currdate;
													//die;
												$process_by = $obj_template->getUser($result->user_id,$result->user_type_id);
												//printr($process_by);die;
												$result1 = json_decode($order['process_by']);
														//printr($order['process_by']);
														//die;
													//echo $result->user_type_id;
												$process_by1 = $obj_template->getUser($result1->user_id,$result1->user_type_id);
													if(isset($result->user_id))
													echo '<b>Dispatched By : </b><span style="color:#26B756">'.$process_by['user_name'].'</span>  <b>On</b> <span style="color:#26B756"> '.dateFormat(4,$result->currdate).'</span>';
													echo '<br><b>Accepted By : </b><span style="color:#D4613B">'.$process_by1['user_name'].'</span>  <b>On</b> <span style="color:#D4613B"> '.dateFormat(4,$result1->currdate).'</span></div>';
													
													// echo $order['process_by'];?>                                   
												   	<?php if($order['note'] != '')
                                                    {
                                                    ?>
                                                   		<div class="panel-body text-small" style="width: 100%;float: left;color:red"><b>Note : </b><?php echo $order['note'];?></div>    
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
                                    //echo $pagination_data;die;
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
<script type="application/javascript">
function div_hide(hideid,showid)
{
	$('#'+hideid).hide();
	$('#'+showid).show();
}
function edit_price(id)
{
	//alert(id);
	$('#hiddenField_'+id).show();
	$('#price_'+id).hide();
	$("input[type=text][id=edit_price_"+id+"]").focusout(function(){
		var  postArray = {};
		postArray['price'] = $("input[type=text][id=edit_price_"+id+"]").val();
		postArray['template_order_id'] = $("#template_order_id"+id).val();
		postArray['product_template_order_id'] = $("#product_template_order_id"+id).val();
		postArray['status'] =3;
   		var order_price_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=update_price', '',1);?>");
			$.ajax({
				url : order_price_url,
				method : 'post',
				data : {postArray : postArray},
				success: function(response){
		//alert(response);
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
</script>
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>