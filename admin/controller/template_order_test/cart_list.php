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

if(!$obj_general->hasPermission('view',$menuId)){
	$display_status = false;
}
if(isset($_POST['btn_checkout'])){
	//printr($_POST);die;
		$status = '1';
		$checkoutorder = $obj_template->savenote($status,$_POST);
		page_redirect($obj_general->link($rout, 'mod=cartlist_view', '',1));
	}
$client_id = $_GET['client_id'];
$client = base64_decode($_GET['client_id']);
//printr($client);
//die;
if($display_status) {
	$checkNewCartPermission = $obj_template->checkNewCartPermission($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE']);
	$orderLimit = $obj_template->orderLimit($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE']);
	$permission = '';
//printr($obj_session->data['ADMIN_LOGIN_SWISS']);
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
		  	<span><?php //echo $display_name;?>Open Order Listing </span>
          	<span class="text-muted m-l-small pull-right">
            	<?php if($obj_general->hasPermission('add',$menuId)){ 
				if($obj_session->data['LOGIN_USER_TYPE'] != 1)
				{
					if($permission ==0 && $checkNewCartPermission[0]['status']==1 ) {}
					else
					{?>
                        <a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add&s_no='.encode($permission).'', '',1);?>">
                        <i class="fa fa-plus"></i> Add </a>
              <?php }
				}
			} ?>      
            </span>
          </header>
          
          	<div class="table-responsive">
           <form name="form_list" id="form_list" method="post">
           <input type="hidden" id="action" name="action" value="" />
          
                  <?php
				      $total_orders = $obj_template->GetOrderList($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],' 
					  AND t.status != 1  AND t.end_date > NOW() ','','','',$client,1);
				  	  $pagination_data = '';
                      if($total_orders){
                        if (isset($_GET['page'])) {
                            $page = (int)$_GET['page'];
                        } else {
                            $page = 1;
                        } 
                  
                         $orders = $obj_template->GetOrderList($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],' AND t.status 
    					  != 1  AND t.end_date > NOW() ','','','',$client,1); 
    					 $f = 1;$total=0;$total_qty=0;
    					//printr($orders);
    					// die;
    					 foreach($orders as $order){
    					     
    					 if($order['stock_print']=='Digital Print'){
    					     $stock_print='<b>('.$order['stock_print'].'</b>)';
    					     $digital_color=$obj_template->GetdigitalColorName($order['digital_print_color']); 
    					    // printr($digital_color);
    					     $d_color='<b>Digital Printing With </b>'.$digital_color.'<br> <b> Front Side : </b> '.$order['front_color']. ' Color <br><b> Back Side :</b>'.$order['back_color'].' Color.';
    					 }else{
    					     $stock_print='';
    					      $d_color='';
    					 }
					 
					 //printr($order); ?>
                        <section class="panel pos-rlt clearfix" style="margin-top: 10px; border: 2px solid rgb(140, 193, 237)"> 
                    <header class="panel-heading" style="border-color: #8CC1ED;background: #8CC1ED;"> 
                        <ul class="nav nav-pills pull-right"> 
                            <li>
                                 <?php
                                     $postedByData = $obj_template->getUser($order['user_id'],$order['user_type_id']);
									// printr($postedByData);
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
                                     <span class="label bg-info" style="font-size: 100%; ">Order By <?php echo $postedByData['user_name'];?></span>
                                     </a>
                                     
                            </li> 
                            <li>
                                <a  href="javascript:void(0);" onClick="removeTemplateOrder(<?php echo $order['template_order_id'];?>)">
                                <span class="label bg-danger" ><i class="fa fa-trash-o"></i></span></a>
                            </li>
                            <li> 
                                <a href="#" class="panel-toggle text-muted">
                                    <i class="fa fa-caret-down fa-lg text-active"></i>
                                    <i class="fa fa-caret-up fa-lg text"></i>
                                </a> 
                            </li> 
                            
                        </ul>
                        <span class="label bg-info" style="font-size: 100%;background: #8CC1ED;"><?php echo $order['product_name'];?></span>
                       
                   </header> 
                   <div class="panel-body clearfix" > 
                        <div id="collapseOne" class="panel-collapse in"> 
                            <div class="panel-body text-small" style="width:25%;float: left;"><b>Option : </b><?php echo $order['zipper'].' '.$order['valve'].' '.$order['spout'].' '.$order['filling_details'].' '.$order['accessorie'].'<br><b>Dimension (Size) : </b>'.$order['width'].'X'.$order['height'].'X'.$order['gusset'].' 
							<span style="color:red;font-weight:bold">('.$order['volume'].')</span><br><b>Color : </b>'.$order['color'].'<br> '.$d_color; ?>
                            </div>
                            <div class="panel-body text-small"  style="width: 25%;float: left;"><?php echo '<b>Transportation : </b>'.$order['transportation_type'].'<br>';?><b>Shipment Country : </b><?php echo $order['country_name'].' / '; 
                                        if($order['ship_type'] == 0) echo 'Self'; else echo 'Client';
                                        echo '<br><b>Client : </b>'.$order['client_name'].'<br><b>Address :  </b><br><pre>'.$order['address'].'</pre>';?>
                            </div>
                            <div class="panel-body text-small"  style="width: 10%;float: left;"><b>Quantity </b><br />
							</b><br />
                            
							<?php if($order['shipment_country']=='252'){?>
										<input type="text" class="form-control  validate[required]"  name="qty_edit<?php echo $f;?>"  onblur="qty_edit(<?php echo $f;?>,<?php echo $order['template_order_id'];?>,<?php echo $order['shipment_country']; ?>,<?php echo $order['ship_type'];?>,'<?php echo $order['transportation_type'];?>',<?php echo $order['admin_user_id'];?>,<?php echo $order['client_id'];?>)" id="qty_edit<?php echo $f;?>" value="<?php echo isset($order['quantity'])? $order['quantity']:'';?>"  />	
							<?php }else{ 
										echo $order['quantity']; }
							$total_qty=$total_qty+$order['quantity'];?>
                            </div>
                            <div class="panel-body text-small"  style="width: 10%;float: left;"><b>Price (INDIA)</b><br /><?php echo $order['currency_code'].' '.$order['price']; $currency_code = $order['currency_code']; ?>
                            </div>
                            <div class="panel-body text-small"  style="width: 10%;float: left;"><b>Price (<?php echo isset($userPrice_permission['currency_code']) ? $userPrice_permission['currency_code'] : ''; ?>)</b><br />
                            <input type="text" class="form-control  validate[required]"  name="price_uk_<?php echo $f;?>"  onblur="edit_price(<?php echo $f;?>)" id="price_uk_<?php echo $f;?>" value="<?php echo isset($order['price_uk'])? $order['price_uk']:'';?>"  />
                            </div>
                             <div class="panel-body text-small"  style="width: 10%;float: left;"><b>Total Price (INDIA)</b><br /><?php echo $order['currency_code'].' '.$order['price']*$order['quantity']; $total =($total+($order['price']*$order['quantity'])); ?>
                            </div> 
                             <div class="panel-body text-small"  style="width: 10%;float: left;"><b>Total Price (<?php echo isset($userPrice_permission['currency_code']) ? $userPrice_permission['currency_code'] : ''; ?>)</b><br />
							 <input type="hidden" name="qty_<?php echo $f;?>" id="qty_<?php echo $f;?>" value="<?php echo $order['quantity'];?>" />
							 <span id="total_price_uk_<?php echo $f;?>"><?php echo isset($order['price_uk'])? ($order['price_uk']*$order['quantity']):'';?></span>
                            </div>                    
                       
                            <div class="panel-body text-small" style="width: 90%;float: left;"><b>Template Title : </b><?php echo '<a href="'.$obj_general->link('product_template', 'mod=view&template_id='.encode($order['product_template_id']).'', '',1).'" target="new">'.$order['title'] .' '.$stock_print.'</a>'; ?>
                            	<div class="panel-body text-small"  style="width: 30%;float: right;"><b>Expected Delivary Date : </b>(<?php echo dateFormat(4,$order['expected_ddate']);?>)<br />  <input type="hidden" name="ddate_<?php echo $f;?>" id="ddate_<?php echo $f;?>" value="<?php echo $order['expected_ddate'];?>" />
                            </div>            
                            
                            </div> 
                            
                                                                 
                       
                            <div class="panel-body text-small" style="width: 100%;float: left;"><b>Note : </b>
                            <textarea name="note<?php echo $f;?>" class="form-control"   <?php if($obj_session->data['LOGIN_USER_TYPE'] == 1)
				{ echo 'readonly="readonly"';}?> ><?php echo $order['note'];?></textarea>
                                     <input type="hidden" name="template_order_id<?php echo $f;?>" id="template_order_id<?php echo $f;?>" value="<?php echo $order['template_order_id'];?>" />
                                     <?php $order_id=$order['order_id'];?>
                            </div>                                      
                        </div>
                  </div> 
              </section>                        
                        <?php 
							$f++;
						}
						?>
                        <input type="hidden" name="product_template_order_id" id="product_template_order_id" value="<?php echo $order_id;?>" />
                        <div style="margin-right: 20px;width: 100%;text-align: right;padding-right: 20px;">
                            <span class="badge bg-warning" style="font-size:14px;">Total Quantity : <?php echo $total_qty;?></span>
                             <?php if($obj_session->data['LOGIN_USER_TYPE'] != 1)
    				                {?>
                                        <span class="badge bg-danger" style="font-size:14px;">Total Price : <?php echo $currency_code.' '.$total;?></span>
                              <?php }?>
                        </div>
                         <?php } else{ 
                            echo "No record found !";
                         } ?>
                        <input type="hidden" name="count" id="count" value="<?php echo $f-1;?>" />
                        <?php if($obj_session->data['LOGIN_USER_TYPE'] != 1)
				                {?>
                                   <div style="text-align: center;">                     
                                         <button type="submit" name="btn_checkout" class="btn btn-primary">Save</button>
                                             <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '&mod=cartlist_view', '',1);?>">Cancel</a> 
                                        </div>
                          <?php }?>  
                   </div>
                 </form> 
            </div>
         </section>
         </div>
        </div>
     </section>
</section>
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

<script type="application/javascript">
jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#form_list").validationEngine();
		
});
function edit_price(id)
{
	var price = $('#price_uk_'+id).val();
	var qty = $('#qty_'+id).val();
	var tot = price*qty;
	$('#total_price_uk_'+id).html(tot);
//	alert(tot);
}
//[kinjal] made on 22-7-2017
function qty_edit(id,template_order_id,shipment_country,ship_type,transportation_type,admin_user_id,client_id)
{
	var qty = $('#qty_edit'+id).val();
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=updatetempQty', '',1);?>");
	$.ajax({
		url : url,
		method : 'post',
		data : {template_order_id : template_order_id,qty:qty,shipment_country:shipment_country,ship_type:ship_type,transportation_type:transportation_type,admin_user_id:admin_user_id,client_id:client_id},
		success: function(response){
			//alert(response);
			set_alert_message('Successfully Updated Quantity',"alert-success","fa-check");
			reloadPage();	
		},
		error: function(){
			return false;	
		}
	});
	//alert(qty);
}


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
			//alert(response);
			set_alert_message('Successfully Deleted',"alert-success","fa-check");
			reloadPage();	
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