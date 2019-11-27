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

//edit user
$edit = '';
if(isset($_GET['invoice_no']) && !empty($_GET['invoice_no'])){	
		$invoice_no = decode($_GET['invoice_no']);
		$invoice = $obj_invoice->getInvoiceData($invoice_no);
		$invoice_id = $invoice['invoice_id'];
		$edit = 1;
}
if(isset($_GET['invoice_product_id']) && !empty($_GET['invoice_product_id'])){
	$invoice_product_id = decode($_GET['invoice_product_id']);
	$invoice_product = $obj_invoice->getInvoiceProductId($invoice_product_id);
	//echo $invoice_product_id;
}
/*if(isset($_POST['generate_invoice'])){
	$url = HTTP_SERVER.'pdf/invoicepdf.php?mod='.encode('invoice').'&token='.encode($_POST['invoice_id']).'&status='.rawurlencode($_POST['status']).'&ext='.md5('php');
	//$obj_invoice->sendInvoiceEmail($_POST['invoice_id'],$_POST['status'],'', $url);

	$invoice_data = $obj_invoice->getInvoiceData($_POST['invoice_id']);
	$addedByinfo=$obj_invoice->getUser($invoice_data['user_id'],$invoice_data['user_type_id']);
	$obj_session->data['success'] = 'Invoice successfully Added!';
	page_redirect($obj_general->link($rout, '&mod=view&invoice_no='.encode($_POST['invoice_id']).'&status=1', '',1));
	
}*/
if($display_status){	
$city= $obj_invoice->getCityName();
$countries = $obj_invoice->getCountry();
$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
$addedByInfo = $obj_invoice->getUser($user_id,$user_type_id);
if($user_type_id==2 && $addedByInfo['user_type_id']==4)
{
	$ibInfo = $obj_invoice->getUser($addedByInfo['user_id'],$addedByInfo['user_type_id']);
	$addedByInfo['gst']=$ibInfo['gst'];
	$addedByInfo['company_address']=$ibInfo['company_address'];
	$addedByInfo['bank_address']=$ibInfo['bank_address'];
}
/*SWISS PAC PVT LTD.

Padra Jambusar National highway
At Dabhasa Village, Pin 391440
Taluka.Padra,

shir@swisspack.co.in*/
?>
<section id="content">
  <section class="main padder">
    <div class="clearfix">
       <h4><i class="fa fa-edit"></i> Purchase Invoice</h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>
        <div class="col-sm-12">
        	<section class="panel">
	          <header class="panel-heading bg-white"> Purchase Invoice Detail </header>
    	      <div class="panel-body">
        	    <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
            		<div class="form-group">
                    
                    
                    
                    
                  	 <label class="col-lg-3 control-label"><span class="required">*</span>Purchase Invoice No</label>
        		        <div class="col-lg-3">
                        <?php 
							$userCountry = $obj_invoice->getUserCountry($user_type_id,$user_id);
							if($userCountry){
								$countryCode = (isset($userCountry['country_code']) && $userCountry['country_code'] )?$userCountry['country_code']:'IN';					
							}else{
								$countryCode='IN';
							}
							$LastInvoiceId = $obj_invoice->getLastIdPur();
							$id=$LastInvoiceId['invoice_id']+1;
							$pur_id=str_pad($id,8,'0',STR_PAD_LEFT);?>
                          <input type="hidden" name="in_no" id="in_no" value="<?php echo isset($invoice_no) ? $invoice_no : '' ; ?>"  >
                          <input type="hidden" name="gst" id="gst" value="<?php echo isset($addedByInfo['gst']) ? $addedByInfo['gst'] : '' ; ?>"  >
                          <input type="hidden" name="company_address" id="company_address" value="<?php echo isset($addedByInfo['company_address']) ? $addedByInfo['company_address'] : '' ; ?>"  >
                          <input type="hidden" name="bank_address" id="bank_address" value="<?php echo isset($addedByInfo['bank_address']) ? $addedByInfo['bank_address'] : '' ; ?>"  >
           			      <input type="text" name="invoiceno" id="invoiceno" value="<?php if(isset($invoice_id)) { echo $invoice['invoice_no']; } else { echo ($countryCode.$pur_id);} ?>"  class="form-control validate[required]" readonly="readonly">
		                </div>
        		        <label class="col-lg-3 control-label">Purchase Invoice Date</label>
                		<div class="col-lg-3">
		                 	<input type="text" name="invoicedate" readonly data-date-format="yyyy-mm-dd" value="<?php echo isset($invoice) ? $invoice['invoice_date'] : '' ; ?>" placeholder="Invoice Date" id="invoicedate" class="input-sm form-control datepicker" />
        		         </div>
		             </div>
                    
             		<div class="form-group">
                    
		                <label class="col-lg-3 control-label">Exporter's order/Ref No</label>
        		        <div class="col-lg-3">
                			<input type="text" name="ref_no" id="ref_no" value="<?php echo isset($invoice) ? $invoice['exporter_orderno'] : '' ; ?>" placeholder="Exporter's orderno" class="form-control">
		                </div>
                    
        		        <label class="col-lg-3 control-label"><span class="required">*</span>Buyer's Order/Ref No</label>
			            <div class="col-lg-3">
            			     <input type="text" name="buyersno" id="buyersno" value="<?php echo isset($invoice) ? $invoice['buyers_orderno'] : '' ; ?>"  class="form-control validate[required]">
		                </div>
        		     </div>
                    
                    <div class="form-group">
                    <label class="col-lg-3 control-label"><span class="required">*</span>Customer Name</label>
                    <div class="col-lg-3">
                        <input type="text" name="customer_name"  value="<?php echo isset($invoice) ? $invoice['customer_name'] : 'SWISS PAC PVT LTD.' ; ?>" placeholder="Customer Name" id="customer-name" class="form-control validate[required]" readonly="readonly" />
                    </div>
                     <label class="col-lg-3 control-label">Email</label>
                    <div class="col-lg-3">
                      <input type="text" name="email" id="email" value="<?php echo isset($invoice) ? $invoice['email'] : 'shir@swisspack.co.in' ; ?>" placeholder="Email"  class="form-control validate[custom[email]]" readonly="readonly">
                    </div>
                  </div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span>Address</label>
                        <div class="col-lg-8">
                        <textarea class="form-control validate[required]" rows="2" cols="45" name="consignee" id="consignee" readonly="readonly"><?php echo isset($invoice) ? $invoice['consignee'] : 'Padra Jambusar National highway
At Dabhasa Village, Pin 391440
Taluka.Padra,' ;?></textarea>
               		</div>
              </div>
             
              		<div class="form-group">
             			<label class="col-lg-3 control-label"><span class="required">*</span>Country of Final Destination </label>
                		<div class="col-lg-3">
                          <select name="country_final" id="country_final" class="form-control validate[required]" >
                             <option value="">Select Country</option>
                            <?php $sel_destination_country= isset($invoice['country_destination'])?$invoice['country_destination']:$addedByInfo['country_id'];
                                    foreach($countries as $country){
                                        if($sel_destination_country && $sel_destination_country == $country['country_id']){
                                            echo '<option value="'.$country['country_id'].'" selected="selected" >'.$country['country_name'].'</option>';
                                        }else{
                                            echo '<option value="'.$country['country_id'].'">'.$country['country_name'].'</option>';
                                        }
                                    } ?>
                           </select>
                		</div>
              			<label class="col-lg-3 control-label"> Vessel Name and No</label>
                        <div class="col-lg-3">
                            <input type="text" name="vessel_name" id="vessel_name" value="<?php echo isset($invoice) ? $invoice['vessel_name'] : '' ; ?>" class="form-control validate">
                        </div>
              		</div>
                  
              		<div class="form-group">
               			<label class="col-lg-3 control-label"><span class="required">*</span>City</label>
		                <div class="col-lg-3">
                        	<input type="text" name="portofload" id="portofload" value="<?php echo isset($invoice) ? $invoice['port_load'] : '' ; ?>" class="form-control validate">
        			       <?php /*?> <select name="portofload" id="portofload" class="form-control validate[required]" >
			                  <option value="">Select City Name</option>
            		       <?php  foreach($city as $cty) { ?>
								<?php if($invoice['port_load']==$cty['invoice_city_id']){ ?>
                                    <option value="<?php echo $cty['invoice_city_id']; ?>" selected="selected"><?php echo $cty['city_name']; ?></option>
                                 <?php } else { ?>
                                    <option value="<?php echo $cty['invoice_city_id']; ?>"><?php echo $cty['city_name']; ?></option>
                                 <?php }
								  } ?>
                 			</select><?php */?>
                		</div> 
                        <label class="col-lg-3 control-label">Region</label>
		                <div class="col-lg-3">
        			       <input type="text" name="region" id="region" value="<?php echo isset($invoice) ? $invoice['region'] : '' ; ?>" class="form-control validate">
                		</div> 
                        
              		</div> 
                 
                    <div class="form-group">
                   <label class="col-lg-3 control-label">Final Destination</label>
                    <div class="col-lg-3">
                            <?php if(isset($addedByInfo['country_id']) && $addedByInfo['country_id']){
									$selCountry = $addedByInfo['country_id'];
								}
								$sel_country = isset($invoice['final_destination'])?$invoice['final_destination']:$addedByInfo['country_id']; 	
								$countrys = $obj_general->getCountryCombo($sel_country);
								echo $countrys;
							?>
                    </div>
                   <label class="col-lg-3 control-label"><span class="required">*</span>Payment Terms</label>
                   <div class="col-lg-3">
                     <input type="text" name="payment_terms" id="payment_terms" value="<?php echo isset($invoice) ? $invoice['payment_terms'] : '' ; ?>" placeholder="Payment terms" class="form-control validtae[required]" />
                   </div>
              </div>
                    <div class="form-group">
                    <label class="col-lg-3 control-label"><span class="required">*</span> Currency</label>
                    <div class="col-lg-3">
                        <?php $currency = $obj_invoice->getCurrency();?>
                        <select name="currency" id="currency" class="form-control validate" >
                           <option value="">Select Currency</option>
                            <?php foreach($currency as $curr){ ?>
                                    <option value="<?php echo $curr['currency_id']; ?>" <?php if(isset($_GET['invoice_no']) && $curr['currency_id'] == $invoice['curr_id']) { ?> selected="selected" <?php } ?>><?php echo $curr['currency_code']; ?></option>
                          <?php   } ?>
                        </select>
                    </div>
               </div>
            
               <div class="form-group">
                <label class="col-lg-3 control-label">Postal Code</label>
                <div class="col-lg-3">
                 <input type="text" class="form-control" id="postal_code" name="postal_code" value="<?php echo isset($invoice) ? $invoice['postal_code'] : '' ; ?>" />
                </div>
                <label class="col-lg-3 control-label">Account Code</label>
                <div class="col-lg-3">                
             	 	 <input type="text" name="account_code" value="<?php echo isset($invoice) ? $invoice['account_code'] : '501' ; ?>"  class="form-control " id="account_code" readonly="readonly"/>
                 </div>
              </div>
              <div class="form-group option">
               
               <label class="col-lg-3 control-label">Sent Message</label>
                <div class="col-lg-3">                
             	 	 <input type="text" name="sent" value="<?php echo isset($invoice) ? $invoice['sent'] : '' ; ?>"  class="form-control " id="sent" />
                 </div>
                 <label class="col-lg-3 control-label">Invoice Status</label>
                    <div class="col-lg-3">
	                     <input type="text" class="form-control" name="invoice_status" id="invoice_status" value="<?php echo isset($invoice) ? $invoice['invoice_status'] : '' ; ?>" />
                    </div>
               </div>
               
               <div class="form-group option">
                   <label class="col-lg-3 control-label">Type</label>
                   <div class="col-lg-3">                
                         <input type="text" name="type" value="<?php echo isset($invoice) ? $invoice['type'] : '' ; ?>"  class="form-control " id="type" />
                   </div>
                   <label class="col-lg-3 control-label">Tax Type</label>
                   <div class="col-lg-3">
                             <input type="text" class="form-control" name="tax_type" id="tax_type" value="<?php echo isset($invoice) ? $invoice['tax_type'] : 'BAS Excluded' ; ?>" readonly="readonly" />
                   </div>
               </div>
               
               <div class="form-group option">
                   <label class="col-lg-3 control-label">Freight Charges</label>
                   <div class="col-lg-3">                
                         <input type="text" name="freight_charge" value="<?php echo isset($invoice) ? $invoice['freight_charge'] : '' ; ?>"  class="form-control " id="freight_charge" />
                   </div>
                   <label class="col-lg-3 control-label">Transportation</label>
                  <div class="col-lg-3">
                        <div class="checkbox ch1" style="float: left; width: 30%; display: block;">
                            <label>
                              <input type="radio" name="transportation[]" value="Air" class="validate[minCheckbox[1]]" checked="checked" <?php if(isset($invoice) && ($invoice['transportation'] == 'Air')) { echo 'checked="checked"';} ?>>
                              By Air
                             </label>
                         </div>
                         <div class="checkbox ch2" style="float: left; width: 30%; display: block;">
                            <label>
                              <input type="radio" name="transportation[]" value="Sea" class="validate[minCheckbox[1]]"  <?php if(isset($invoice) && ($invoice['transportation'] == 'Sea')) { echo 'checked="checked"';} ?>>
                              By Sea
                             </label>
                         </div>
                         <div class="checkbox ch3" style="float: left; width: 30%;display: block;">
                                <label>
                                  <input type="radio" name="transportation[]" value="Pickup" class="validate[minCheckbox[1]]" <?php if(isset($invoice) && ($invoice['transportation'] == 'Pickup')) { echo 'checked="checked"';} ?>>
                                  Factory Pickup
                                 </label>
                         </div>
                    </div>
               </div>
				
               <div class="line line-dashed m-t-large"></div>
                	 
                     <div class="form-group">
                        	<label class="col-lg-3 control-label">Product Name</label>
                        	<div class="col-lg-3">
								<?php
                                $products = $obj_invoice->getActiveProduct();
                                ?>
                                <select name="product" id="product" class="form-control validate" onchange="color_chng()">
                                <option value="">Select Product</option>
                                    <?php
                                    
                                    foreach($products as $product){
                                        if(isset($post['product']) && $post['product'] == $product['product_id']){
                                            echo '<option value="'.$product['product_id'].'" selected="selected" >'.$product['product_name'].'</option>';
                                        }else{
                                            echo '<option value="'.$product['product_id'].'">'.$product['product_name'].'</option>';
                                        }
                                    } ?>
                                </select>
                        	</div>
                           
                            
                       </div>
                 		
                      <div class="form-group">
                         	<label class="col-lg-3 control-label">Volume</label>
                            <div class="col-lg-3">
                                <input type="text" name="volume" value="" id="volume" class="form-control" onchange="color_chng()"/>
                            </div>
                 	 </div>
                
              		  <div class="form-group">
                            <label class="col-lg-3 control-label">Colour</label>
                                <div class="col-lg-3">
                                 <?php $colors = $obj_invoice->getActiveColor();?>
                                <select name="color" id="color" class="form-control validate" onchange="color_chng()">
                                        <option value="">Select Color</option>
                                         <?php foreach($colors as $colors2){ ?>
                                            <option value="<?php echo $colors2['pouch_color_id']; ?>" id="option"
                                            <?php //if($clr['color'] == $colors2['pouch_color_id']) { echo 'selected="selected"'; } ?>> 
                                            <?php echo $colors2['color']; ?></option>
                                            <?php } ?>  
                                                                               
                                      </select>
                                </div>
                      </div>                  
                         
                         
                     <div class="form-group option">
               
               			<label class="col-lg-3 control-label"><span class="required">*</span>Product Code</label>
                			<div class="col-lg-3" id="holder"> 
                               <?php  
									$product_codes=$obj_invoice->getActiveProductCode();
									///printr($product_codes);
									if(isset($invoice_product_id)) { 
									$color = $obj_invoice->getColorDetails($invoice_no,$invoice_product_id); 
									//printr($color);
									$product_code= $obj_invoice->getProductCode($color[0]['product_code_id']);
									//printr($product_code); 
									}
								?>
                                <input type="hidden" id="product_code_id" name="product_code_id" value="<?php if(isset($_GET['invoice_product_id']) && ($invoice_product['product_code_id'] != '-1')){ echo $product_code['product_code_id'];} else if(isset($invoice_product) && $invoice_product['product_code_id'] == '-1') { echo '-1'; } else { } ?>">
                               <input type="text" id="keyword" class="form-control "  autocomplete="off" value="<?php  if(isset($_GET['invoice_product_id']) && ($invoice_product['product_code_id'] != '-1')){ echo $product_code['product_code'];} else if( isset($invoice_product) && $invoice_product['product_code_id'] == '-1') { echo 'Cylinder'; } else { } ?>">
                     			 <div id="ajax_response"></div>
             	 	 		</div>
                           <div class="col-lg-3" id="product_div"> 
                               <input type="text" name="product_name" id="product_name"  value="<?php echo isset($_GET['invoice_product_id'])?$product_code['description']:'';?>" disabled="disabled" class="form-control validate" style="width:400px"/>
             	 	 		</div>
                            
                		 </div>
                          <div class="form-group">
							<label class="col-lg-3 control-label"></label> 
							<div class="col-lg-7" style="width: 22%;">
									<section class="panel">
									  <div class="table-responsive">
										<table class="tool-row table-striped  b-t text-small" id="myTable">
										  <thead>
											  <tr>
                                                <th><span class="required">*</span>Qty</th>
                                                <th><span class="required">*</span>Rate </th>
                                              </tr>
										  </thead>
                                          <tbody  id="myTbody">
				                            <?php if(isset($invoice_product_id)) { 
													$color = $obj_invoice->getColorDetails($invoice_no,$invoice_product_id); 
												  }else {$color[]=array('invoice_color_id'=>'',
													'invoice_product_id' => '',
													'invoice_id' => '',
													'color' => '',
													'qty' => '',
													'without_freight_charge_rate' => '',
													'size' => '',
													'net_weight' =>'',
													'dimension' => '',
													'measurement' => '',
													'date_added' => '',
													'date_modify' => '',
													'is_delete' => '');
												 }
												if($color)
												{
													$i = 0;
													foreach($color as $colorkey => $clr) { ?>
 				                           <tr id="tr_<?php echo $i; ?>">  
											
                                            <td><input type="text" name="color[<?php echo $colorkey; ?>][qty]" value="<?php echo $clr['qty']; ?>" class="form-control validate[required,custom[number],min[1]]" placeholder="Qty" id="qty"></td>
                                            <td><input type="text" name="color[<?php echo $colorkey; ?>][rate]" value="<?php echo $clr['without_freight_charge_rate']; ?>" class="form-control validate[required,custom[number],min[0.001]]" placeholder="Rate" id="rate"></td>
                                           	<input type="hidden" name="color[<?php echo $colorkey; ?>][invoice_color_id]" value="<?php echo $clr['invoice_color_id']; ?>" class="form-control validate[min[1]]" placeholder="invoice_color_id" id="invoice_color_id_<?php echo $i;?>"></td>
			                                </tr>
										<?php $i++; } }?>
                                        </tbody>
										</table>
									  </div>
                                  	</section> 
								</div>
			  			  </div> 
             	<input type="hidden" name="edit" id="edit" value="<?php echo $edit; ?>"  />  
            <div class="line line-dashed m-t-large"></div>           
               <div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                <div class="col-lg-4">
                  <select name="status" id="status" class="form-control">                  
                    <option value="1" <?php echo (isset($invoice['status']) && $invoice['status'] == 1)?'selected':'';?> > Active</option>
                    <option value="0" <?php echo (isset($invoice['status']) && $invoice['status'] == 0)?'selected':'';?>> Inactive</option>
                  </select>
                </div>
               </div>
               
               <div class="form-group">
                      <div class="col-lg-9 col-lg-offset-3">
                      <button type="button" name="btn_save" id="btn_save" class="btn btn-primary" <?php if(isset($_GET['invoice_product_id'])){ ?> 
                         style="display:none" <?php } ?> onClick="displaygenerate();">Add Product</button>
                       <?php if(isset($invoice_no) && isset($invoice_product_id)) {?>
                        <input type="hidden" name="pro_id" value="<?php echo $invoice_product_id;?>" id="pro_id" />
                      <input type="hidden" name="invoice_id" value="<?php echo $invoice_no;?>"/>
                         <button type="button" name="invoice_update" id="invoice_update" class="btn btn-primary">Update Product</button> 
                     <?php } ?>	
					 </div>
                </div>
                  <div id="invoice_results">
           <?php if(isset($invoice_no) && !empty($invoice_no)) {
		   //	echo $invoice_no;
			    $invoice_product_second = $obj_invoice->getInvoiceProduct($invoice_id);
				//printr($invoice_product_second);
				 ?>
                    <table class="table table-bordered"> 
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Rate</th> 
                                <th>Rate (With Freight charge)</th>                                
                              	<th>Action</th>                
                            </tr>
                        </thead>
                        <tbody>
    						<?php 
							if($invoice_product_second!='')
							{
							foreach($invoice_product_second as $invoice_d ) {
							//printr($invoice_d);
						$invoice_product_id = $invoice_d['invoice_product_id'];
						//echo $invoice_product_id;
						if(isset($invoice_product_id) && !empty($invoice_product_id))
						{
						 ?>
						  <input type="hidden" name="invoice_id" id="invoice_id" value="<?php echo $invoice_no; ?>"  />
            		      <tr id="invoice_product_id_<?php echo $invoice_d['invoice_product_id']; ?>">
							  <td><b><?php if($invoice_d['product_code_id']!='-1')
											{
												 $product_code= $obj_invoice->getProductCode($invoice_d['product_code_id']);
											}
											else
											{
												$product_code['product_name'] = '';
												$product_code['product_code']='CYLINDER';
											}
							 // $product_code= $obj_invoice->getProductCode($invoice_d['product_code_id']);
										  echo $product_code['product_name'].'<br>'.$product_code['product_code'];?></b></td>
                  					<?php $colors = $obj_invoice->getColorDetails($invoice_d['invoice_id'],$invoice_d['invoice_product_id']); 
								?>
                  			   <td>
				  				<?php echo $invoice_d['qty'];?>
							  </td>
            				  <td>
								  <?php echo $invoice_d['without_freight_charge_rate'];?>
							  </td>
                              <td>
								  <?php echo $invoice_d['rate'];?>
							  </td>
                 			<td class="del-product"><a class="btn btn-danger btn-sm" href="javascript:void(0);" onClick="removeInvoice(<?php echo $invoice_d['invoice_product_id'].','.$invoice_d['invoice_id']; ?>)"><i class="fa fa-trash-o"></i></a>
			                 <a href="<?php echo $obj_general->link($rout, 'mod=add&invoice_no='.encode($invoice_no).'&invoice_product_id='.encode($invoice_d['invoice_product_id']),'',1); ?>" id="btn_edit"  name="btn_edit" class="btn btn-info btn-xs btn_edit">Edit</a>
            			      </td>
						  </tr>
                        <div class="modal fade" id="alertbox_<?php echo $invoice_d['invoice_product_id']; ?>">
	                        <div class="modal-dialog">
    	                        <div class="modal-content">
        	                        <div class="modal-header">
            	                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                	                    <h4 class="modal-title">Title</h4>
                    	            </div>
                        	        <div class="modal-body">
                            	        <p id="setmsg">Message</p>
                                	</div>
                                	<div class="modal-footer">
                                    	<button type="button" id="popbtncan" class="btn btn-default" data-dismiss="modal">Close</button>
                                    	<button type="button" name="popbtnok" id="popbtnok_<?php echo $invoice_d['invoice_product_id']; ?>" class="btn btn-primary">Ok</button>
                                	</div>
                            	</div><!-- /.modal-content -->
                        	</div><!-- /.modal-dialog -->
	                    </div>
                    <!-- END OF CONFIRMATION BOX-->
			<?php	} else { ?>
					     <tr>
					    	<td colspan="7">No Records Found!!!</td>  
					    </tr>
		    <?php }} }?>
		  				</tbody>
          			</table>
          <?php  } ?>
    		</div> 
			<?php if($edit){?>
			<div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                    <button type="button" name="btn_update" id="btn_update" class="btn btn-primary">Update </button>
                    <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a>
                </div>
            </div>
    <?php }  ?>
            <div class="col-lg-9 col-lg-offset-3">
                <button type="button" id="generate_invoice"  class="btn btn-primary" name="generate_invoice" onClick="add_invoices()" style="display:none" >Generate Invoice</button>
            </div>
	            </form>
    	      </div>
        	</section>
	    </div>
    </div>
  </section>
</section>
<div id="test"></div>
<style type="text/css">
.btn-on.active {
    background: none repeat scroll 0 0 #3fcf7f;
}
.btn-off.active{
	background: none repeat scroll 0 0 #3fcf7f;
	border: 1px solid #767676;
	color: #fff;
}
@media (max-width: 400px) {
  .chunk {
    width: 100% !important;
  }
}
#ajax_response{
	border : 1px solid #13c4a5;
	background : #FFFFFF;
	position:relative;
	display:none;
	padding:2px 2px;
	top:auto;
	border-radius: 4px;
}
#holder{
	width : 350px;
}
.list {
	padding:0px 0px;
	margin:0px;
	list-style : none;
}
.list li a{
	text-align : left;
	padding:2px;
	cursor:pointer;
	display:block;
	text-decoration : none;
	color:#000000;
}
.selected{
	background : #13c4a5;
}
.bold{
	font-weight:bold;
	color: #227442;
}
.about{
	text-align:right;
	font-size:10px;
	margin : 10px 4px;
}
.about a{
	color:#BCBCBC;
	text-decoration : none;
}
.about a:hover{
	color:#575757;
	cursor : default;
}
</style>
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>ckeditor3/ckeditor.js"></script>
<script>
jQuery(document).ready(function(){
	$(document).click(function(){
		$("#ajax_response").fadeOut('slow');
		$("#ajax_response").html("");
	});
	$("#keyword").focus();
	var offset = $("#keyword").offset();
	var width = $("#holder").width();
	$("#ajax_response").css("width",width);
	
	$("#keyword").keyup(function(event){		
		 var keyword = $("#keyword").val();
		  var key = keyword.toUpperCase();
		 if(key == 'CYLINDER')
		 {	
				$("#product_code_id").val('-1');
		 }
		 else if(keyword.length)
		 {
			 if(event.keyCode != 40 && event.keyCode != 38 )
			 {
				 var product_code_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=product_code', '',1);?>");
				 $("#loading").css("visibility","visible");
				 $.ajax({
				   type: "POST",
				   url: product_code_url,
				   data: "product_code="+keyword,
				   success: function(msg){	
				   var msg = $.parseJSON(msg);
				   var div='<ul class="list">';
				   
					if(msg.length>0)
					{
						for(var i=0;i<msg.length;i++)
						{	
							div =div+'<li><a href=\'javascript:void(0);\' discr="'+msg[i].description+'" id="'+msg[i].product_code_id+'"><span class="bold" >'+msg[i].product_code+'</span></a></li>';
						}
					}
					
					div=div+'</ul>';
					if(msg != 0)
					  $("#ajax_response").fadeIn("slow").html(div);
					else
					{
					  $("#ajax_response").fadeIn("slow");	
					  $("#ajax_response").html('<div style="text-align:left;">No Matches Found</div>');
					}
					$("#loading").css("visibility","hidden");
				   }
				 });
			 }
			 else
			 {				
				switch (event.keyCode)
				{
				 case 40:
				 {
					  found = 0;
					  $(".list li").each(function(){
						 if($(this).attr("class") == "selected")
							found = 1;
					  });
					  if(found == 1)
					  {
						var sel = $(".list li[class='selected']");
						sel.next().addClass("selected");
						sel.removeClass("selected");										
					  }
					  else
						$(".list li:first").addClass("selected");
						if($(".list li[class='selected'] a").text()!='')
						{
							$("#keyword").val($(".list li[class='selected'] a").text());
							$("#product_div").show();
                  			$("#product_name").val($(".list li[class='selected'] a").attr("discr"));
							$("#product_code_id").val($(".list li[class='selected'] a").attr("id"));
						}
				}
				 break;
				 case 38:
				 {
					  found = 0;
					  $(".list li").each(function(){
						 if($(this).attr("class") == "selected")
							found = 1;
					  });
					  if(found == 1)
					  {
						var sel = $(".list li[class='selected']");
						sel.prev().addClass("selected");
						sel.removeClass("selected");
					  }
					  else
						$(".list li:last").addClass("selected");
						if($(".list li[class='selected'] a").text()!='')
						{
							$("#keyword").val($(".list li[class='selected'] a").text());
							$("#product_div").show();
                  			$("#product_name").val($(".list li[class='selected'] a").attr("discr"));
							$("#product_code_id").val($(".list li[class='selected'] a").attr("id"));
						}
				 }
				 break;				 
				}
			 }
		 }
		 else
		 {
			$("#ajax_response").fadeOut('slow');
			$("#ajax_response").html("");
		 }
	});
	$('#keyword').keydown( function(e) {
    if (e.keyCode == 9) {
		 $("#ajax_response").fadeOut('slow');
		 $("#ajax_response").html("");
    }
	
});
	$("#ajax_response").mouseover(function(){
			$(this).find(".list li a:first-child").mouseover(function () {
					$("#product_div").show();
                  $("#product_name").val($(this).attr("discr"));
				  $("#product_code_id").val($(this).attr("id"));
				  $(this).addClass("selected");
			});
			$(this).find(".list li a:first-child").mouseout(function () {
				  $(this).removeClass("selected");
			});
			$(this).find(".list li a:first-child").click(function () {
				  $("#product_div").show();
                  $("#product_name").val($(this).attr("discr"));
				  $("#product_code_id").val($(this).attr("id"));
				  $("#keyword").val($(this).text());
				  $("#ajax_response").fadeOut('slow');
				  $("#ajax_response").html("");
				});
			
		});

		jQuery("#form").validationEngine();
		   
		   
		   
		   var product_name=$("#product_name").val();
		   //alert(product_name);
		   if(product_name=='')
		   {
		   		$("#product_div").hide();
		   }
		   else
		   {
		   		$("#product_div").show();
		   }
		  	var destination = $("#country_id").val();
		if(destination==170)
			$("#item_no_text").html('Special Code');
		else
			$("#item_no_text").html('Item No.');
		if(destination == 111) {
			$("#byroad").show();
			$("#byair").hide();
			$("#bysea").hide();
			$("#byair").prop('disabled', true);
			$("#byair").prop('disabled', true);
			$("#trans3").attr('checked', true);
			$("#tax_type").show();
		} else {
			$("#tax_div").hide();
			$("#form_div").hide();
		}
	   });
	   
	 
	
if($("input:radio[name=tax_mode]:checked").val() == 'normal') {
	$("#tax_div").show();
} else {
	$("#form_div").show();
}
$("#country_id").change(function(){
	var final_dest = $("#country_id").val();
	if(final_dest==170)
		$("#item_no_text").html('Special Code');
	else
		$("#item_no_text").html('Item No.');
	if(final_dest == 111) {
$("input:radio[name=tax_mode]:first").prop('checked', true);
		$("#tran1").prop('checked', false);
		$("#trans3").prop('checked', true);	
	} else {
		$("#form_div").hide();
		$("#trans3").prop('checked', false);
		$("#tran1").prop('checked', true);
	}
}
);
$("#invoiceno").change(function() {
      var checknum=$(this).val();
	  if(checknum==0)
	  {
		  alert("Please Enter Valid Number");
		  $("#invoiceno").val(""); 
	  }
      if(checknum!=''){
		var check_invoice_no = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=checkInvoiceNo', '',1);?>");
         $.ajax({
                url : check_invoice_no,
				method : 'post',
                data: {checknum:checknum},
                success: function(response){
                    if(response == $('#invoiceno').val()){
                        alert('Your entered Invoice Number is already Inserted'); 
						 $("#invoiceno").val("");                         
                    } 
                }
            });
      }
   });
 
function removeInvoice(invoice_product_id,invoice_id)
{ 
	//alert("fff");
	$("#alertbox_"+invoice_product_id).modal("show");
	$(".modal-title").html("Delete Record".toUpperCase());
	$("#setmsg").html("Are you sure you want to delete ?");
	
	$("#popbtnok_"+invoice_product_id).click(function(){
		var remove_invoice_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=removeInvoice', '',1);?>");
		$.ajax({
			url : remove_invoice_url,
			method : 'post',
			data : {invoice_product_id : invoice_product_id,invoice_id : invoice_id},
			success: function(response){
				if(response == 0) {
				$("#alertbox_"+invoice_product_id).hide();
			}
				$("#alertbox_"+invoice_product_id).hide();
				$("#alertbox_"+invoice_product_id).modal("hide");
				$('#invoice_product_id_'+invoice_product_id).html('');
				set_alert_message('Invoice Record successfully deleted','alert-success','fa fa-check');
				},
			error: function(){
				return false;	
			}
		});
			$("#alertbox_"+invoice_product_id).hide();
			$("#alertbox_"+invoice_product_id).modal("hide");
	 });
 
}
	$(document).ready(function() {
	 $("#invoicedate").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
		});			
	

$("#invoice_update").click(function() {
	
	if($("#form").validationEngine('validate')){
	var update_invoice_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=updateInvoiceProduct', '',1);?>");
			var myform = $('#form');
	var disabled = myform.find(':disabled').removeAttr('disabled');
	var formData = myform.serialize();
		$.ajax({
				url : update_invoice_url,
				method : 'post',		
				data : {formData : formData},
				success: function(response){
					//alert(response);
					if(response != 0){
						$("#invoice_results").html("");
						$("#invoice_results").html(response);
						$('#invoice_update').hide(); 
						$('#btn_save').show();
						$("#product").val('');
						$("#netweight").val('');	
						$("#size").val('');
						$("#color_0").val('');
						$("#item_no").val('');
						$("#measurement").val('');
						$("#buyers_o_no").val('');
						$("#net_weight_0").val('');
						$("#qty").val('');
						$("#rate").val('');
						$("#keyword").val('');
						$("#product_div").hide();
						$('#myTable tr').not(':eq(0)').not(':eq(0)').remove();
						$("#measurement").val("");
						$("input:radio[name=zipper]:not(:disabled):first").attr('checked', true);
						$("input:radio[name=valve]:not(:disabled):first").attr('checked', true);
						$("input:radio[name=spout]:not(:disabled):first").attr('checked', true);
						$("input:radio[name=accessorie]:not(:disabled):first").attr('checked', true);
					}
				},
				error: function(){
					return false;
				}
			});
	}
});
$("#btn_update").click(function() {
	if($("#invoiceno").val() == '' || $("#invoiceno").val() == 0)
	{
		alert("Please Insert Valide Invoice No.");
		$("#invoiceno").val("");
		return false;
	}
	var update_invoice_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=updateInvoiceRecord', '',1);?>");
						var myform = $('#form');
	var disabled = myform.find(':disabled').removeAttr('disabled');
	var formData = myform.serialize();
			$.ajax({
				url : update_invoice_url,
				method : 'post',		
				data : {formData : formData},
				success: function(response){
					//alert(response);
					if(response != 0){
						 window.location = "<?php echo $obj_general->link($rout, '', '',1); ?>";
					}
					 set_alert_message('Invoice Record successfully updated ','alert-success','fa fa-check');
				},
				error: function(){		
					return false;
				}
			});
});
function displaygenerate()
{
	var edit=$("#edit").val();
	if(edit=='')
		$("#generate_invoice").show();
	else
		$("#generate_invoice").hide();
}
$("#btn_save").click(function(){
	if($("#form").validationEngine('validate')){ 
			var add_product_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=addInvoice', '',1);?>");
				var formData = $("#form").serialize();
				//alert(formData);
				$.ajax({
					url : add_product_url,
					method : 'post',		
					data : {formData : formData},
					success: function(response){
						if(response != 0){
							$("#invoiceno").prop('disabled', true);
							$("#region").prop('disabled', true);
							$("#postal_code").prop('disabled',true);
							$("#account_code").prop('disabled',true);
							$("#sent").prop('disabled',true);
							$("#invoice_status").prop('disabled',true);
							$("#type").prop('disabled',true);
							$("#tax_type").prop('disabled',true);
							$("#invoicedate").prop('disabled', true);
							$("#ref_no").prop('disabled', true);
							$("#buyersno").prop('disabled', true);
							$("#country_final").prop('disabled', true);
							$("#consignee").prop('disabled', true);
							$("#portofload").prop('disabled', true);
							$("#currency").prop('disabled', true);
							$("#vessel_name").prop('disabled', true);
							$("#country_id").prop('disabled', true);
							$("#payment_terms").prop('disabled', true);
							$("#customer-name").prop('disabled', true);
							$("#email").prop('disabled', true);
							$("#freight_charge").prop('disabled', true);
							$("#qty").val('');
							$("#rate").val('');
							$("#keyword").val('');
							$("#product_div").hide();
							$('#myTable tr').not(':eq(0)').not(':eq(0)').remove();
							$("#invoice_results").html(response)
						}
					},
					error: function(){
						return false;
					}
				});
		}
		else {			return false;
		}
});
function add_invoices() {
		$("#product_code_id").prop('disabled', true);
		$("#size").prop('disabled', true);
		$("#color_0").prop('disabled', true);
		$("#qty").prop('disabled', true);
		$("#rate").prop('disabled', true);
		$("#netweight").prop('disabled', true);
		$("#measurement").prop('disabled', true);
		$("#buyers_o_no").prop('disabled', true);
		$("#net_weight_0").prop('disabled', true);
		$("#item_no").prop('disabled', true);
		var invoice_id = $("#invoice_id").val();
		var update_invoice_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=generateInvoice', '',1);?>");
		$.ajax({
				url : update_invoice_url,
				method : 'post',		
				data : {invoice_id : invoice_id},
				success: function(response){
					
					set_alert_message('Invoice successfully Added!',"alert-success","fa-check");
					
					window.location.href='<?php echo HTTP_SERVER; ?>/admin/index.php?route=purchase_invoice&mod=view&invoice_no='+response+'&status=1';
					
				}
				
			});
}
function remove_tr(tr_id)
{	
	var val=parseInt($("#net_weight_"+tr_id).val());
	$("#tr_"+tr_id).hide();
	$("#color_"+tr_id).find('option:selected').removeAttr("selected");
	var total = $('#netweight').val();
	var final = total-val;
	$('#netweight').val(final);
}
function color_chng()
{
	var product=$("#product").val();
	var volume = $("#volume").val();
	var color = $("#color").val();
	var product_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=product_name', '',1);?>");
	 $.ajax({
			   type: "POST",
			   url: product_url,
			   data: {product_name:product,volume:volume,color:color},
			   success: function(msg){
			  //alert(msg);
			   var msg = $.parseJSON(msg);
			  
			   var div='<ul class="list">';
			   
				if(msg.length>0)
				{
					for(var i=0;i<msg.length;i++)
					{	
						div =div+'<li><a href=\'javascript:void(0);\' discr="'+msg[i].description+'" size="'+msg[i].volume+'" mea="'+msg[i].measurement+'" color="'+msg[i].color+'" product_id="'+msg[i].product+'" product_name="'+msg[i].product_name+'"  id="'+msg[i].product_code_id+'"><span class="bold" >'+msg[i].product_code+'</span></a></li>';			

					}
				}
				
				div=div+'</ul>';
				if(msg != 0)
				  $("#ajax_response").fadeIn("slow").html(div);
				else
				{
				  $("#ajax_response").fadeIn("slow");	
				  $("#ajax_response").html('<div style="text-align:left;">No Matches Found</div>');
				}
				$("#loading").css("visibility","hidden");
			   }
		});
	
}
</script> 
<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>