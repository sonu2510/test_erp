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
	$sort= 'history_id ';
}

if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'DESC';	
}

$group_id = isset($_GET['group_id'])?$_GET['group_id']:'';
$group_id = base64_decode($group_id);

$class = 'collapse';
//printr($obj_session->data);
$filter_data=array();

if(!isset($_GET['filter_edit']) || $_GET['filter_edit']==0){
	if(isset($obj_session->data['filter_data'])){
		unset($obj_session->data['filter_data']);	
	}
}

if(isset($obj_session->data['filter_data'])){
	$filter_stock_order_id = $obj_session->data['filter_data']['stock_order_id'];
	$filter_date = $obj_session->data['filter_data']['date'];
	$filter_user_name = $obj_session->data['filter_data']['postedby'];
	$class = '';
	
	$filter_data=array(
		'stock_order_id' => $filter_stock_order_id,
		'date' => $filter_date, 
		'postedby' => $filter_user_name,
	);
	//printr($filter_data);
	//die;
}
if(isset($_POST['btn_filter'])){
	
	$filter_edit = 1;
	$class = '';	
	if(isset($_POST['filter_stock_order_id'])){
		$filter_stock_order_id=$_POST['filter_stock_order_id'];		
	}else{
		$filter_stock_order_id='';
	}
	
	if(isset($_POST['filter_date'])){
		$filter_date=$_POST['filter_date'];		
	}else{
		$filter_date='';
	}
	if(isset($_POST['filter_user_name']))
	{
		$filter_user_name = $_POST['filter_user_name'];
	}else{
		$filter_user_name='';
	}
	
	$filter_data=array(
		'stock_order_id' => $filter_stock_order_id,
		'date' => $filter_date, 
		'postedby' => $filter_user_name,	
	);
	
	$obj_session->data['filter_data'] = $filter_data;	
	//printr($obj_session->data['filter_data']);
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
	if(isset($_POST['action']) && $_POST['action'] == "sendemail" && isset($_POST['post']) && !empty($_POST['post'])){
		if(!$obj_general->hasPermission('add',$menuId)){
			$display_status = false;
		} else {
				$obj_template->sendOrderEmail($_POST['post'],1,ADMIN_EMAIL,decode($_GET['group_id']));
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
    					<span>Email History Listing </span>
    					<span class="text-muted m-l-small pull-right">
                            <a class="label bg-primary sendmailcls" style="margin-left:4px;" onclick="formsubmitsetaction('form_list','sendemail','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-envelope"></i> Resend To Production</a>  
    					</span>
    				</header>
                    
                   
          <form name="form_list" id="form_list" method="post">
                               <br />
          <div class="panel" style="border-color: rgb(250, 173, 132);">
         	  <div class="panel-heading" style="background: rgb(253, 199, 170);border-color:rgb(250, 173, 132);"> 
                   <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne" style="color: #BD2216; font-weight:bold"><i class="fa fa-envelope"></i> Resend  Email History  </a>
               </div> 
            	<div id="collapseOne" class="panel-collapse collapse" style="height: 3px;"> 
              		<div class="media-body panel-body scrollbar scroll-y m-b" style="padding: 0px;"> 
						<?php $emaildetails = $obj_template->getemailhistory($group_id);
                            if($emaildetails)
                            {
                                echo '<table class="table table-striped m-b-none text-small"><thead> <tr> <th>Date</th> <th>User</th> </tr> </thead> <tbody> ';
                                foreach($emaildetails as $details)
                                {							
                                   echo  '<tr><td> ';
                                   echo dateFormat(4,$details['date']);
                                  echo '</td><td>';
                                  echo $details['user_name'];
                                  echo '</td></tr> ';
                                }
                                echo ' </tbody> </table> ';
                            }
                            ?> 
            		</div>
          		</div> 
          </div> 
         
                    	<input type="hidden" id="action" name="action" value="" />
					    <div class="table-responsive">
							<?php
							//$orders_details = $obj_template->GetEmailList($group_id);
							//printr($orders_details);
							//die;
							
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
								$total_order = $obj_template->GetEmailList($group_id);
								$total_orders=count($total_order);
								$pagination_data = '';
								if($total_order!=''){
										
                            		//printr($option);
									$orders = $obj_template->GetEmailList($group_id);
									//printr($orders);
									//die;
									$start_num =((($page*$limit)-$limit)+1);
									$f = 1;
									$slNo = $f+$start_num;
									$total=0;$total_qty=0;
									$details_order='';
                            		foreach($orders as $order){ 
									//printr($order);
									
									$new_price=$obj_template->getUpdatedPrice($order['product_template_order_id'],$order['template_order_id']);
									if(isset($new_price) && $new_price!='')
									$order['price']=$new_price;
									$edit_price_menu_id = $obj_template->getMenuPermission(ORDER_PRICEEDIT_ID,$obj_session->data['ADMIN_LOGIN_SWISS'],		                                                   $obj_session->data['LOGIN_USER_TYPE']); ?>
                                        <section class="panel pos-rlt clearfix" style="margin-top: 10px; border: 2px solid #FFDC5C"> 
                                            <header class="panel-heading" style="border-color: #FFDC5C;background: #FFF0BA;"> 
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
                                                        <span class="label bg-warning" style="font-size: 100%; ">Order By <?php echo $postedByData['user_name'];?></span></a>
                                                    </li> 
                                                    <li>
                                                        <a href="#" class="panel-toggle text-muted">
                                                            <i class="fa fa-caret-down fa-lg text-active"></i>
                                                            <i class="fa fa-caret-up fa-lg text"></i>
                                                        </a> 
                                                    </li>     
                                                </ul>
                                             <span class="label bg-warning" style="font-size: 100%;"><?php if( $details_order!=$order['gen_order_id'])
											 {
											 $details_order=$order['gen_order_id'];
											 ?>
											   <input type="checkbox" name="post[]" value="<?php echo $order['template_order_id'].'=='.$order['product_template_order_id'].'=='.$order['client_id'];?>" style="display:none" checked="checked"/>
										<?php	 }
											 echo $order['gen_order_id'];?>
                                           
                                             </span>    
 												<span class="label bg-warning" style="font-size: 100%; background:#FFC800; margin-left:10px">
												<?php echo preg_replace("/\([^)]+\)/","",$order['product_name']);?></span></header> 
                                            <?php if($edit_price_menu_id OR $obj_session->data['LOGIN_USER_TYPE']==1){?>
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
                                                            <th >New Price</th> 
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
                                                    <div class="panel-body text-small" style="width:35%;float: left;"><b>Option : </b><?php echo $order['zipper'].' '.$order['valve'].' '.$order['spout'].' '.$order['accessorie'].'<br><b>Dimension (Size) : </b>'.$order['width'].'X'.$order['height'].'X'.$order['gusset'].'<span style="color:red;font-weight:bold"> ('.$order['volume'].')</span><br><b>Color : </b>'.$order['color'].'<br><b>Order Date :</b>'.dateFormat(4,$order['date_added']); ?>
                                                    </div>
                                                    <div class="panel-body text-small"  style="width: 30%;float: left;"><?php echo '<b>Transportation : </b>'.$order['transportation_type'].'<br>';?><b>Shipment Country : </b><?php echo $order['country_name'].' / '; 
                                                    if($order['ship_type'] == 0) echo 'Self'; else echo 'Client';
                                                    echo '<br><b>Client :</b> '.$order['client_name'].'<br><b>Address :  </b><br><pre>'.$order['address'].'</pre>';?>
                                                    </div>
                                                    <div class="panel-body text-small"  style="width: 10%;float: left;"><b>Quantity </b><br /><?php echo $order['quantity']; ?>
                                                    </div>
                                                       <?php  
												   if($edit_price_menu_id OR $obj_session->data['LOGIN_USER_TYPE']==1){?>
                                                    <div class="panel-body text-small"  style="width: 15%;float: left;"><b>Price Per Unit</b><br /><?php echo $order['currency_code'].' <span id="price_'.$f.'">'.$order['price'].'</span>'; ?>
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
                                                    </div>
                                                    <div class="panel-body text-small"  style="width: 10%;float: left;"><b>Total Price </b><br /><?php echo $order['currency_code'].' '.$order['price']*$order['quantity'];  ?>
                                                    </div>        
    <?php }?>
                                                    <div class="panel-body text-small" style="width: 100%;float: left;"><b>Template Title : </b><?php echo '<a href="'.$obj_general->link('product_template', 'mod=view&template_id=
                                                    '.encode($order['product_template_id']).'', '',1).'" target="new">'.$order['title'].'</a>'; ?>
                                                    <br />
													<?php 
														$result = json_decode($order['process_by']);
														//printr($order['process_by']);
														//die;
													//echo $result->user_type_id;
												$process_by = $obj_template->getUser($result->user_id,$result->user_type_id);
												//printr($process_by);die;
													if(isset($result->action) && $result->action==1)
													echo '<b>Accepted By : </b><span style="color:#D4613B">'.$process_by['user_name'].'</span>  <b>On</b> <span style="color:#D4613B"> '.dateFormat(4,$result->currdate).'</span></div>';
													
													// echo $order['process_by'];?>
                                                            
                                                                                      
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
                                                   //if($admin_type_id!=4){
													    $menu_id = $obj_template->getMenuPermission(ORDER_INPROCESS_ID,$obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE']);
														if($menu_id OR $obj_session->data['LOGIN_USER_TYPE']==1)
														{?>
                                                           
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

<!-- Modal -->
<div class="modal fade" id="smail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="sform" id="sform" style="margin-bottom:0px;">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <input type="hidden" name="template_order_id" id="template_order_id" value="" />
                   <input type="hidden" name="admin" id="admin" value="<?php echo ADMIN_EMAIL;?>" />
                <input type="hidden" name="product_template_order_id" id="product_template_order_id" value=""/>
                <h4 class="modal-title" id="myModalLabel">Despatched Order</h4>
              </div>
            <?php /*?>  <div class="modal-body">
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
                	<input type="text" class="combodate form-control" data-format="DD-MM-YYYY" data-required="true" data-template="D MMM YYYY" 
                    name="datetime" id="datetime" value="<?php echo date("d-m-Y");   ?> ">
                </div> 
           </div>
          
              <div class="modal-body">
                   <div class="form-group">
                        <label class="col-lg-3 control-label">Courier</label>
                        <div class="col-lg-8">
                        <?php echo $obj_template->getCourierCombo();?>
                        </div>
                     </div> 
              </div>
              
               <div class="modal-body">
                   <div class="form-group">
                        <label class="col-lg-3 control-label">Remark</label>
                        <div class="col-lg-8">
                        <input type="text" name="review" id="review" value="" placeholder="Remark"  class="form-control validate[required]"/>
                        </div>
                     </div> 
              </div>
              
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="button" onclick="updatestockorderstatus()" name="btn_decline" class="btn btn-warning">Dispatch</button>
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
		postArray['status'] =1;
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

	function dispatch(id,template_order_id,product_template_order_id)
	{
		$(".note-error").remove();
		$("#smail").modal('show');
		$("#template_order_id").val(template_order_id);
		$("#product_template_order_id").val(product_template_order_id);
	}	
	
	function updatestockorderstatus()
	{
//		alert('sdf');		
		//var track_id = $("#track_id").val();		
		var datetime = $("#datetime").val();		
		//var courier_id = $("#courier_id").val();		
		var review = $("#review").val();		
		//alert(courier_id);
		if(datetime!='')
		{
			$("#datetime").val('');
			$("#review").val('');
			var adminEmail = $("#admin").val();
			$("#smail").modal('hide');
			var postArray = {};
			postArray['template_order_id'] = $("#template_order_id").val();
			postArray['product_template_order_id'] = $("#product_template_order_id").val();
			postArray['datetime'] =datetime;
			postArray['review'] =review;
			postArray['status'] =3;
			var d = new Date();
			var curr_date = d.getDate();
    		var curr_month = d.getMonth();
   			 curr_month++;   // need to add 1 – as it’s zero based !
    		var curr_year = d.getFullYear();
    		var formattedDate = curr_date + "-" + curr_month + "-" + curr_year;
			postArray['currdate'] = formattedDate;
			var order_status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=updatestockorderstatus', '',1);?>");
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
	}
</script>           
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>