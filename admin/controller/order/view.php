<?php
include("mode_setting.php");

//Start : bradcums
$bradcums = array();
$bradcums[] = array(
	'text' 	=> 'Dashboard',
	'href' 	=> $obj_general->link('dashboard', '', '',1),
	'icon' 	=> 'fa-home',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> $display_name.' List',
	'href' 	=> $obj_general->link($rout, '', '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> $display_name.' Detail',
	'href' 	=> '',
	'icon' 	=> 'fa-edit',
	'class'	=> 'active',
);
//Close : bradcums

$limit = LISTING_LIMIT;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}

$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
$allow_currency_status = $obj_order->allowCurrencyStatus($user_type_id,$user_id);

//Start : edit
$edit = '';
if(isset($_GET['order_id']) && !empty($_GET['order_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$order_id = base64_decode($_GET['order_id']);
		//echo $order_id;
		//die;
		
		$data = $obj_order->getOrder($order_id,$obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);
		
		$currency_data = $obj_order->getOrderCurrency($order_id);

	}
}
//Close : edit
if($display_status){
?>

<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-edit"></i> <?php echo $display_name;?></h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div> 
      <div class="col-sm-12">
        
            <section class="panel">  
            	
                <header class="panel-heading bg-white">
                 <span>Order Detail</span> 
                </header>
              
              <div class="panel-body">
              	<label class="label bg-white m-l-mini">&nbsp;</label>
                	
                 <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Order Number</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo ucwords($data['order_number']);?>
                            </label>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Customer Name</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo ucwords($data['customer_name']);?>
                            </label>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Order Type</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo ucwords($data['order_type']);?>
                            </label>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Company Name</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo ucwords($data['company_name']);?>
                            </label>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Email</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo ucwords($data['email']);?><br/>
                           
                            <small class="text-muted"><?php echo "Contact Number: " .$data['contact_number']; ?></small>
                            </label>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Shipping Address</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo ucwords($data['address'])." ,".$data['city']." ,".$data['state'];?>
                            </label>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Order Note</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            	<?php echo $data['order_note']; ?>
                            </label>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Order Instruction</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            	<?php echo $data['order_instruction']; ?>
                            </label>
                        </div>
                      </div>
                      	
                      <?php 
					  	$order_products = $obj_order->getOrderProducts($order_id);
					 ?>                       
					<div class="col-lg-12"><h4><i class="fa fa-tags"></i> Product Details</h4>
                           <div class="line m-t-large" style="margin-top:-4px;"></div><br/>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"> Product Name</label>
                        <div class="col-lg-9">
                            <label class="control-label normal-font">
                             <?php echo $order_products[0]['product_name']; ?>
                            </label>
                        </div>
                    </div>

					<div class="form-group">
						<div class="table-responsive">
							<table class="table table-striped b-t text-small">
								<thead>
									<tr>
                                    	<th>Art Work</th>
                                        <th>Die Line</th>
                                    	<th>Transportation</th>
                                    	<th>Quntity</th>
                                        <th>Option(Printing Effect )</th>
                                        <th>Dimension (Make Pouch)</th>
                                        <th>Layer:Material:Thickness</th>
                                        <th>Price / pouch</th>
										<th>Total</th>
                                        <?php if($order_products[0]['total_price_with_tax'] != 0) { ?>
                                        <th>Price / pouch With Tax</th>
                                        <th>Total Price With Tax</th>
                                        <?php } ?>
                                        <th>Cylinder Price</th>
                                        <?php //if($order_products[0]['tool_price'] != 0) { ?>
										<th>Tool Price</th>
                                        <?php //} ?>
                                    </tr>
                                </thead>
                                <tbody>

                                <?php $i=0; ?>
                                <?php foreach($order_products as $order_product){

									$i++;							
								$order_product_materials = $obj_order->getOrderProductMaterials($order_product['order_product_id']);
								//$order_product_materials = $obj_order->getMultiQuotationMaterial($order_product['order_product_id']);
								$TotalPrice = $order_product['total_price']+$order_product['gress_price'];

								?>                                
                                	<tr>
                                    	
                            <!-- .crousel slide -->
						 <td> <?php 
						// printr($order_product_images);
						 $order_product_images = $obj_order->getOrderProductImages($order_product['order_product_id']);
						 $html = '';			
							 if(isset($order_product_images) && !empty($order_product_images)) {
						  $html .='<div class="carousel slide auto" id="c-slide-'.$order_product['order_product_id'].'">
							<ol class="carousel-indicators out">';
							 for($j=0;$j<(count($order_product_images));$j++){ 
							 	$html .='<li data-target="#c-slide-'.$order_product['order_product_id'].'" data-slide-to="'.$j.'" class=""></li>';
							 }
						$html .='</ol>';
						$html .='<div class="carousel-inner">';
							 $i=0;
							  
							foreach($order_product_images as $image){ 
								if($i==0){
									$html .=' <div class="item active">';
								}else{
									$html .=' <div class="item">';
								}
									$html .='<p class="text-center"><img class="" alt="Image" width="100" height="100" src="'.HTTP_UPLOAD.'admin/artwork/100_'.$image['image_name'].'"></p>';
								$html .='</div>';
								$i++;
							}
						$html .='</div>
							<a class="left carousel-control" style="width:15px;" href="#c-slide-'.$order_product['order_product_id'].'" data-slide="prev"> <i class="fa fa-chevron-left"></i> </a>
							<a class="right carousel-control" style="width:15px;" href="#c-slide-'.$order_product['order_product_id'].'" data-slide="next"> <i class="fa fa-chevron-right"></i> </a> </div>';
						echo $html;
							 } else {
								 echo '<p>
<img class="" width="100" height="100" src="'.HTTP_UPLOAD.'admin/artwork/blank.jpg" alt="Image">
</p>';
							 }
						 
						?>
                        </td>
                                        <td><?php
                            	$order_product_die_lines = $obj_order->getOrderProductDieLines($order_product['order_product_id']);
								$html = '';
						 
						if(isset($order_product_die_lines) && !empty($order_product_die_lines)) {
						  $html .='<div class="carousel slide auto" id="c-slide-'.$order_product_die_lines[0]['order_product_die_line_id'].'">
							<ol class="carousel-indicators out">';
							 for($j=0;$j<(count($order_product_die_lines));$j++){ 
							 	$html .='<li data-target="#c-slide-'.$order_product_die_lines[0]['order_product_die_line_id'].'" data-slide-to="'.$j.'" class=""></li>';
							 }
						$html .='</ol>';
						$html .='<div class="carousel-inner">';
							 $i=0;
							  //printr($order_product_die_lines);
							foreach($order_product_die_lines as $keys=>$image){ 
								if($i==0){
									$html .=' <div class="item active">';
								}else{
									$html .=' <div class="item">';
								}
									$html .='<p class="text-center"><img class="" alt="Image" width="100" height="100" src="'.HTTP_UPLOAD.'admin/artwork/100_'.$image['name'].'"></p>';
								$html .='</div>';
								$i++;
							}
						$html .='</div>
							<a class="left carousel-control" style="width:15px;" href="#c-slide-'.$order_product_die_lines[0]['order_product_die_line_id'].'" data-slide="prev"> <i class="fa fa-chevron-left"></i> </a>
							<a class="right carousel-control" style="width:15px;" href="#c-slide-'.$order_product_die_lines[0]['order_product_die_line_id'].'" data-slide="next"> <i class="fa fa-chevron-right"></i> </a> </div>';
						echo $html;
						} else {
								 echo '<p>
<img class="" width="100" height="100" src="'.HTTP_UPLOAD.'admin/artwork/blank.jpg" alt="Image">
</p>';
							 }
						 
						?>
                           </td>
                                    	<th><?php echo (ucwords(decode($order_product['transport_type']))); ?></th>
                                        <th><?php echo ucwords($order_product['quantity']); ?></th>
                                        <td><?php echo ucwords($order_product['zipper_txt']).'<br>'.ucwords($order_product['valve_txt']).'<br>'.ucwords($order_product['spout_txt']).'<br>'.ucwords($order_product['accessorie_txt']).' ('.ucwords($order_product['printing_effect']).')'; ?></td>
                                        <td><?php echo $order_product['width'].'X'.$order_product['height'].'X'.$order_product['gusset']; ?></td>
                                        <td>
										<?php foreach($order_product_materials as $order_product_material){
											?>
											<?php echo '<b>'.$order_product_material['layer'].' Layer :</b>'.' '.$order_product_material['material_name'].': '.floatval($order_product_material['material_thickness']).'<br>'; ?>

										<?php } ?>
                                        </td>
                                        <td><?php echo $currency_data['currency'].' '.sprintf ("%.3f",($TotalPrice/$order_product['quantity'])); ?></td>
                                        <td><?php echo $currency_data['currency'].' '.sprintf ("%.3f",$TotalPrice); ?></td>
                                        <?php if(isset($order_product['total_price_with_tax']) && ($order_product['total_price_with_tax'] != 0)) {
												echo '<td>'.$currency_data['currency'].' '.sprintf ("%.3f",($order_product['total_price_with_tax']/$order_product['quantity'])).'</td>';
										} ?>
                                        <?php if(isset($order_product['total_price_with_tax']) && ($order_product['total_price_with_tax'] != 0)) {
												echo '<td>'. $currency_data['currency'].' '.sprintf ("%.3f",$order_product['total_price_with_tax']).'</td>'; } ?>
                                        <td><?php echo floatval($order_product['cylinder_price']); ?></td>
                                        <td><?php if($order_product['tool_price'] > 0) { echo $order_product['tool_price']; } ?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-lg-12">
                    	<div id="results_box"></div>
                         <div id="pagination_controls"></div>

                            <?php
                               
                              //printr($history_data);die;
							  $history_count = $obj_order->gettotalcountOrderHistories($order_id);
							 $last = ceil($history_count/$limit);
                             ?>                         
                        </div>
                    </div>
                    
                  
                   
                    <div class="col-lg-12 history-form"><h4><i class="fa fa-plus-circle"></i> Order History</h4>
                        <div class="line m-t-large" style="margin-top:-4px;"></div><br/>
                        
                       
                        <div class="form-group">                                
                           <label class="col-lg-3 control-label">Email Notification</label>
                           <div class="col-lg-1">
                              
                            <?php
                                $user_type_id = $_SESSION['LOGIN_USER_TYPE'];
                                $user_id = $_SESSION['ADMIN_LOGIN_SWISS']; 
                            ?>
                                                      
                             <input type="checkbox" class="form-control validate[required]" id="email_notif" name="email_notif" value="0">
                             <input type="hidden" name="emailval" id="emailval" value="<?php echo $data['email'];?>" />
                                
                           </div>
                        </div>
                       
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Order Status</label>
                           <div class="col-lg-4">
                              <?php
							  	$order_statuses = $obj_order->getOrderStatuses();
								//printr($order_statuses);
								//die;
								
							  ?>
                                <select name="order_statusid" id="order_statusid" class="form-control validate[required]" onchange='changevalue()' >
                                   <option value="">Select Order Status</option>
								   <?php if($order_statuses) { ?>
                                	 <?php foreach($order_statuses as $order_status){ ?>
                                    	<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['status_name']; ?></option>
                                     <?php } ?>
                                   <?php } ?> 
                                </select>
                                <input type="hidden" id="order_status_id" class="form-control validate[required]" name="order_status_id" value="" />
                                <span class="order-error required"></span>
                           </div>
                        </div>
                               					
                       
                        <div class="form-group">
                        	<label class="col-lg-3 control-label">Notes</label>
                            
                            <div class="col-lg-9">
                           
                                <textarea  class="form-control validate[required]" id="enq-note" rows="3" cols="8" name="new_note"></textarea>                      
                                <input type="hidden" id="order_id" name="order_id" value="<?php echo $order_id;?>"/>
                                <span class="note-error required"></span>
                            </div>
                        </div>
                                                                      
                        <div class="form-group">
                            <div class="col-lg-9 pull-right">
                                <a class="btn btn-primary" id="add-history"><i class="fa fa-plus"></i> Add History</a>
                            </div>
                        </div>
                    </div>
                    		
                      
                    <div class="form-group">
                       <div class="col-lg-9 col-lg-offset-3">             
                          <a class="btn btn-default" href="<?php echo $obj_general->link($rout,'', '',1);?>">Cancel</a>
                       </div>
                    </div>
                  </form>
                </div>
              </section>    
           </div>
      </div>
  </section>
</section>

<!-- Modal -->

<script src="<?php echo HTTP_SERVER;?>js/lightbox/js/lightbox.min.js"></script>
<link href="<?php echo HTTP_SERVER;?>js/lightbox/css/lightbox.css" rel="stylesheet" />


<script>
var rpp = <?php echo $limit; ?>; // results per page
var last = <?php echo $last; ?>; // last page number

$(document).ready( function () {
 request_page(1); 
});
function request_page(pn){//alert(pn);
	var results_box = document.getElementById("results_box");
	var pagination_controls = document.getElementById("pagination_controls");
	results_box.innerHTML = "loading results ...";
	var hr = new XMLHttpRequest();
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=test&order_id='.$order_id,'',1); ?>");						

    hr.open("POST",url, true);
    hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
			
			results_box.innerHTML = hr.responseText;
			//alert(hr.responseText);
	    }
    }
    hr.send("rpp="+rpp+"&last="+last+"&pn="+pn);
	// Change the pagination controls
	var paginationCtrls = "";
	
	pagination_controls.innerHTML = paginationCtrls;
}
</script>

<script>

$('#add-history').click(function(){
		var error = 0;			
		var value = document.getElementById('order_status_id').value;
		
		//var numrecords = 10;
		//var pagenum = 1;
		if($('input[name="email_notif"]').is(':checked'))
		{
		  document.getElementById('email_notif').value=1;
		//alert(value);
		}
		var emailval = document.getElementById('email_notif').value;
		//alert(emailval);
		$('.note-error').html('');
		$('.order-error').html('');
	
		if(value == '')
		{
			$('.order-error').html('Please Select Order Status');
			if($('#enq-note').val()==''){		
				$('.note-error').html('Please Enter Note');	
				error++;
			}
		}
		else
		{
			if(value == 3)
			{
				if($('#enq-note').val()==''){		
					$('.note-error').html('Please Enter Note');	
					error++;
				}
			}
			
			var history_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=addHistory', '',1);?>");
			$.ajax({
			 	url : history_url,
			 	type : 'post',				
			 	data : $('.history-form input,.history-form textarea, .history-form select'),
			 	success : function(response){	
				//alert(response['response']);
					var val = $.parseJSON(response);
					//alert(val.response);
					//alert(val.result);
					$('#loading').remove();
					$('#no-history').remove();
					$('#history-body').append(val.response);
					$(this).removeAttr('disabled');
					$(this).html('<i class="fa fa-plus"></i> Add History');
					set_alert_message(val.result,"alert-success","fa-check");
				}
		  	});
		}		
});

function changevalue()
{
	var value = $('#order_statusid').val()
	document.getElementById('order_status_id').value = value;
}

</script>



<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>


<!-- Close : validation script -->

<?php } else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>