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

//add sonu 21-4-2017
$address_id = '0';
$add_url='';
if(isset($_GET['address_book_id']))
{
    $address_id = decode($_GET['address_book_id']);
    $add_url = '&address_book_id='.$_GET['address_book_id'];
}

 //end
$bradcums[] = array(
	'text' 	=> $display_name.' List',
	'href' 	=> $obj_general->link($rout, 'mod=index&is_delete='.$_GET['is_delete'].$add_url, '',1),
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
$proforma_productcode_wise_id = '0';
if(isset($_GET['invoice_no']) && !empty($_GET['invoice_no'])){	
		$invoice_no = decode($_GET['invoice_no']);
		$invoice = $obj_invoice->getInvoiceData($invoice_no);
		$invoice_id = $invoice['invoice_id'];
		$edit = 1;
}
if(isset($_GET['invoice_product_id']) && !empty($_GET['invoice_product_id'])){
	$invoice_product_id = decode($_GET['invoice_product_id']);
	$invoice_product = $obj_invoice->getInvoiceProductId($invoice_product_id);
}
// mansi 8-1-2016
if(isset($_GET['proforma_id']))
{ 
	$pro_detail = $obj_invoice->getProformaDetail(decode($_GET['proforma_id']));
	$pro_id = $_GET['proforma_id'];
}
 if($display_status){	
    $city= $obj_invoice->getCityName();
    $countries = $obj_invoice->getCountry();
    $user_type_id = $_SESSION['LOGIN_USER_TYPE'];
    $user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
    $addedByInfo = $obj_invoice->getUser($user_id,$user_type_id);
    if($user_type_id==2 || $user_type_id==4)
    {
    	$ibInfo = $obj_invoice->getUser($user_id,$user_type_id);
    	$addedByInfo['gst']=$ibInfo['gst'];
    	$addedByInfo['company_address']=$ibInfo['company_address'];
    	$addedByInfo['bank_address']=$ibInfo['bank_address'];
    }
    if(isset($_GET['invoice_no']) && !empty($_GET['invoice_no'])){
    	$addedByInfo = $obj_invoice->getUser($invoice['user_id'],$invoice['user_type_id']);
    }
    if(isset($_POST['btn_send_customer']))
    {
      $_POST['url'] = HTTP_SERVER.'pdf/salesinvoicepdf.php?mod='.encode('salesinvoice').'&token='.rawurlencode($_GET['invoice_no']).'&ext='.md5('php').'&num=0';
      $obj_invoice->send_mail_customer($_POST,1);
      //page_redirect($obj_general->link($rout, 'mod=index&is_delete=0', '',1));
    }
    ?>
    <section id="content">
      <section class="main padder">
        <div class="clearfix">
           <h4><i class="fa fa-edit"></i> Sales Invoice</h4>
        </div>
        <div class="row">
        	<div class="col-lg-12">
    	       <?php include("common/breadcrumb.php");?>	
            </div>
            <div class="col-sm-12">
            	<section class="panel">
    	          <header class="panel-heading bg-white"> Sales Invoice Detail </header>
        	      <div class="panel-body">
            	    <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
                    	<div class="form-group">
                         <?php if($addedByInfo['country_id'] != '42'){?>
                      		<label class="col-lg-3 control-label">Purchase Invoice No</label>
                    		<div class="col-lg-3">
    		                 	<input type="text" name="purchase_no"  value="<?php if(isset($invoice)) { echo $invoice['purchase_invoiceno']; } else { echo '' ; } ?>" placeholder="Purchase Invoice No" id="purchase_no" class="form-control"  />
            		         </div>
                              <?php }?>
                             <label class="col-lg-3 control-label">Proforma Invoice No</label>
                    		<div class="col-lg-3">
    		                 	<input type="text" name="proforma_no"  value="<?php if(isset($invoice)) { echo $invoice['proforma_no']; } else { echo '' ; } ?>" placeholder="Proforma Invoice No" <?php if($edit==1){echo 'readonly=readonly';}?> id="proforma_no" class="form-control" onchange="check_proforma()" />
            		         </div>
                             
                        </div>
                		<div class="form-group">
                      	
            		        <label class="col-lg-3 control-label"><span class="required">*</span>Sales Invoice Date</label>
                    		<div class="col-lg-3">
    		                 	<input type="text" name="invoicedate" readonly="readonly" value="<?php if(isset($pro_id)){ echo $pro_detail['invoice_date']; } elseif(isset($invoice)) { echo $invoice['invoice_date']; } else { echo '' ; } //echo isset($invoice) ? $invoice['invoice_date'] : '' ; ?>" placeholder="Invoice Date" id="invoicedate" class="input-sm form-control validate[required]"/>
            		         </div>
                             
                            <label class="col-lg-3 control-label" id="invoice_no_label" <?php if($edit!='1') { ?> style="display:none" <?php } ?> >Sales Invoice No</label>
            		        <div class="col-lg-3">
                              <input type="hidden" name="in_no" id="in_no" value="<?php echo isset($invoice_no) ? $invoice_no : '' ; ?>"  >
                              <input type="hidden" name="gst" id="gst" value="<?php echo isset($addedByInfo['gst']) ? $addedByInfo['gst'] : '' ; ?>"  >
                              <input type="hidden" name="gst_val" id="gst_val" value="<?php echo isset($addedByInfo['gst']) ? $addedByInfo['gst'] : '' ; ?>"  >
                              <input type="hidden" name="company_address" id="company_address" value="<?php echo isset($addedByInfo['company_address']) ? $addedByInfo['company_address'] : '' ; ?>"  >
                              <input type="hidden" name="bank_address" id="bank_address" value="<?php echo isset($addedByInfo['bank_address']) ? $addedByInfo['bank_address'] : '' ; ?>"  >
               			      <input type="text" name="invoiceno" id="invoiceno" value="<?php if(isset($invoice_id) && $edit=='1') { echo $invoice['invoice_no']; } else { }//echo ($countryCode.$pur_id); } ?>"  class="form-control validate" readonly="readonly" <?php if($edit!='1') { ?> style="display:none" <?php } ?> >
    		                </div>
    		             </div>
                        
                 		<div class="form-group">
                        	
                            <?php if($addedByInfo['country_id'] == '155')
    						{
    							$order = 'Order No (Client Order No)';	
    							$o_no=isset($invoice) ? $invoice['exporter_orderno'] : '' ;
    							if(isset($pro_id))
    							{
    							 $o_no = $pro_detail['buyers_order_no'];
    							}
    						}
    						else
    						{
    							$o_no=isset($invoice) ? $invoice['exporter_orderno'] : '' ;
    							$order = "Exporter's order/Ref No";
    						}?>
                        	<input type="hidden" name="country_maxico"  id="country_maxico" value="<?php echo $addedByInfo['country_id'];?>">
                            <?php  if($addedByInfo['country_id'] != '42'){?>
    		                <label class="col-lg-3 control-label"><span class="required">*</span><?php echo $order;?></label>
            		        <div class="col-lg-3">
                    			<input type="text" name="ref_no" id="ref_no" value="<?php echo $o_no; ?>" placeholder="Exporter's orderno" class="form-control validate[required]">
    		                </div>
    					    <?php } if($addedByInfo['country_id'] == '155') { ?> 
                            	
                                <label class="col-lg-3 control-label"><span class="required">*</span>Order Date</label>
                                <div class="col-lg-3">
                                    <input type="text" name="orderdate" readonly data-date-format="yyyy-mm-dd" value="<?php echo isset($invoice) ? $invoice['order_date_maxico'] : '' ; ?>" placeholder="Order Date" id="orderdate" class="input-sm form-control datepicker validate[required]" />
                                </div>
    						<?php } else {?>
                                <label class="col-lg-3 control-label">Buyer's Order/Ref No</label>
                                <div class="col-lg-3">
                                     <input type="text" name="buyersno" id="buyersno" value="<?php if(isset($pro_id)){ echo $pro_detail['buyers_order_no']; } elseif(isset($invoice)) { echo $invoice['buyers_orderno']; } else { echo '' ; }//echo isset($invoice) ? $invoice['buyers_orderno'] : '' ; ?>"  class="form-control ">
                                </div>
                            <?php } ?>
            		     </div>
                    
                            <div class="form-group">
                            
                            <?php
    						if($addedByInfo['country_id'] == '155')
    						{
    							$name = 'Client Name';	
    						}
    						else
    						{
    							$name = "Customer Name";
    						}?>
                            
                            <label class="col-lg-3 control-label"><span class="required">*</span><?php echo $name;?></label>
                            <div class="col-lg-3">
                              <input type="text" name="customer_name"  value="<?php  if(isset($pro_id)){ echo $pro_detail['customer_name']; } elseif(isset($invoice)) { echo $invoice['customer_name']; } else { echo '' ; } ?>" placeholder="Customer Name" id="customer-name" class="form-control validate[required]" autocomplete="off" />
    <!--sonu 21-4-2017--> 
                              <input type="hidden" name="address_book_id"  value="<?php echo isset($invoice) ? $invoice['address_book_id'] : '' ; ?>" id="address_book_id" class="form-control " />
                              <input type="hidden" name="company_address_id"  value="" id="company_address_id" class="form-control " />
                             
    <!--sonu END 21-4-2017--> 
                                <div id="ajax_response"></div>
                            </div>
                             <label class="col-lg-3 control-label">Email</label>
                            <div class="col-lg-3">
                              <input type="text" name="email" id="email" value="<?php  if(isset($pro_id)){ echo $pro_detail['email']; } elseif(isset($invoice)) { echo $invoice['email']; } else { echo '' ; } //echo isset($invoice) ? $invoice['email'] : '' ; ?>" placeholder="Email"  class="form-control validate[,custom[email]]">
                            </div>
                          </div>
                           
                       <div class="form-group">
                        <label class="col-lg-3 control-label"> <span class="required">*</span>  <?php if($addedByInfo['country_id']=='111'){ echo 'Consignee'; }elseif($addedByInfo['country_id']!='155') { echo 'Customer Address'; } else { echo "Billing Address"; }?></label>
                        <div class="col-lg-8">
                        <textarea class="form-control validate[required]" rows="2" cols="45" name="consignee" id="consignee"><?php if(isset($pro_id)){ echo $pro_detail['address_info']; } elseif(isset($invoice)) { echo $invoice['consignee']; } else { echo '' ; } //echo isset($invoice) ? $invoice['consignee'] : '' ;?></textarea>
                        </div>
                      </div>
                      
                      <?php if($addedByInfo['country_id'] == '155'){?>
                     
                     		 <div class="form-group option"> 
                                         
                                  <label class="checkbox-custom  m-l-small pull-right" style="font-size:14px;">
                                 		<input type="checkbox" name="same_as_above" id="same-above" value="1">
                                     <i class="fa fa-square-o"></i> Same as Above? </label>
                                     
                                  </div>
                                     
                             <div class="line m-t-large" style="margin-top:4px;"> </div> <br>
                                     
                             <div id="billing-details">
                                     
                                         <div class="form-group option">
                                    
                                            <label class="col-lg-3 control-label">Delivery Address</label>
                                                <div class="col-lg-8"  id="billing_div">
                                                     <textarea name="delivery_info" id="delivery_info" class="form-control validate"><?php echo isset($invoice) ? $invoice['delivery_info_maxico'] : '' ;?></textarea>
                                                     <input type="hidden" id="del_info" value=""> 
                                                </div>
                                         
                                        </div>
                                     
                                     </div>
                     
                      <?php } ?>
    				  		
                             <div class="form-group">
                                <label class="col-lg-3 control-label"><span class="required">*</span>Country of Final Destination </label>
                                <div class="col-lg-3">
                                  <select name="country_final" id="country_final" class="form-control validate[required]" onchange="getgst()">
                                    <option value="">Select Country</option>
                                    <?php //mansi 9-1-2016
    									if(isset($pro_id))
    									{ 
    										$sel_destination_country= $pro_detail['destination'];
    									}
    									elseif(isset($invoice))
    									{ 
    										$sel_destination_country= $invoice['country_destination'];
    								    }
    									else
    									{
    										$sel_destination_country= $addedByInfo['country_id'];
    									}	
                                            foreach($countries as $country){
                                                if($sel_destination_country && $sel_destination_country == $country['country_id']){
                                                    echo '<option value="'.$country['country_id'].'" selected="selected" >'.$country['country_name'].'</option>';
                                                }else{
                                                    echo '<option value="'.$country['country_id'].'">'.$country['country_name'].'</option>';
                                                }
                                            } ?>
                                  </select>
                                </div>
                                
                              <label class="col-lg-3 control-label">Final Destination</label>
                                <div class="col-lg-3">
                                        <?php 
    										
    									if(isset($addedByInfo['country_id']) && $addedByInfo['country_id']){
                                                $selCountry = $addedByInfo['country_id'];
                                            }
                                             if (isset($pro_id)){ $sel_country = $pro_detail['destination']; } elseif(isset($invoice)) { $sel_country = $invoice['final_destination']; } else { $sel_country = $addedByInfo['country_id'] ; }	
                                            $countrys = $obj_general->getCountryCombo($sel_country);
                                            echo $countrys;
                                        ?>
                                </div>
                                
                            </div>
              <?php  if((isset($addedByInfo['country_id']) && $addedByInfo['country_id']=='42')|| (isset($addedByInfo['country_id']) && $addedByInfo['country_id']=='14') ||(isset($addedByInfo['country_id']) && $addedByInfo['country_id']=='251') || (isset($addedByInfo['country_id']) && $addedByInfo['country_id']=='209')) { ?>
               	<div class="form-group">
                        		 <?php if(isset($addedByInfo['country_id']) && $addedByInfo['country_id']=='14')
                    	           echo '<label class="col-lg-3 control-label"><span class="required">*</span>Do you want to dispatch whole order from different Warehouse ?</label>';
                    	       else
                    	          echo '<label class="col-lg-3 control-label"><span class="required">*</span>Do you want to dispatch order directly to customer?</label>';  ?>
                              <div class="btn-group" data-toggle="buttons"> 
    						<?php 
    							$classy='';
                                $checky='';
                                $classn='';
                                $checkn='';
                            if(isset($invoice['customer_dispatch']) && $invoice['customer_dispatch'] == 1 ){
                                $classy='active'; 
                                $checky='checked="checked" ';
                            }
                            else
                            {
                                $classn='active';
                                $checkn='checked="checked" ';
                            }?>
                            <label class="btn btn-sm btn-white btn-on <?php echo $classy;?>">
                              <input type="radio" name="customer_dispatch" id="dis" value="1" <?php echo $checky;?>>
                              Yes </label>
                            <label class="btn btn-sm btn-white btn-off <?php echo $classn;?>">
                              <input type="radio" name="customer_dispatch" id="dis" value="0" <?php echo $checkn;?>>
                              No </label>
                          
                          </div>
                        </div>
                  <?php      }?>
    
    						
                 <?php if($addedByInfo['country_id'] == '42')
    				   { $tax_canada = $obj_invoice->getTaxationCanada();	?>
                           <div class="form-group">
                            	<label class="col-lg-3 control-label"><span class="required">*</span>State</label>
                            	<div class="col-lg-3">
                                <select name="state" id="state" class="form-control validate[required]" >
                                   <option value="">Select State</option>
                                   <?php foreach($tax_canada as $tcanada)
    							   		{
    										if(isset($invoice['state']))
    										{ ?>
                                        		<option value="<?php echo $tcanada['taxation_canada_id']; ?>" <?php if($invoice['state'] == $tcanada['taxation_canada_id']) { ?> selected="selected" <?php  } ?>><?php echo $tcanada['state']; ?></option>
                                                
                                  <?php 	} 
    							  			else
    										{
    											echo '<option value="'.$tcanada['taxation_canada_id'],'" >'.$tcanada['state'].'</option>';
    										}
    							 		} ?>
                                   
                                  </select>
                                </div>
                           </div>
                           <?php
                           if(isset($invoice['can_gst'])){
                               $gst_checkbox=1;
                           }else{
                                $gst_checkbox=0;
                           }
                           if(isset($invoice['hst'])){
                               $hst_checkbox=3;
                           }else{
                                $hst_checkbox=0; 
                           }
                           if( isset($invoice['pst'])){
                               $pst_checkbox=2;
                           }else{
                                $pst_checkbox=0;
                           }
                     //      printr($hst_checkbox.'=='.$invoice['hst']);
                    //     printr($invoice);
                           ?>
                           <div class="form-group" id="gst_div" style="display:none;">
                            	<label class="col-lg-3 control-label"><span class="required">*</span>GST(%)</label>
                                    <div class="col-lg-3">
                                     <input type="text" class="form-control" readonly="readonly" name="can_gst" id="can_gst" value="<?php echo isset($proforma_inv['can_gst']) ? $proforma_inv['can_gst'] :'' ;?>">
                                    </div>
    									 <div class="col-lg-3">
                                     		<input type="checkbox" name="gst_checkbox" value="1" <?php if( $gst_checkbox == '1') { ?> checked="checked" <?php  } ?> >&nbsp; <label> <b style="color:#ff0000">Please Select To Add Tax</b></label>
                                     	 </div>
                                   
                            </div>
                            <div class="form-group" id="pst_div" style="display:none;">
                            	<label class="col-lg-3 control-label"><span class="required">*</span>PST(%)</label>
                                    <div class="col-lg-3">
                                         <input type="text" readonly="readonly" name="pst" id="pst" value="<?php echo isset($proforma_inv['pst']) ? $proforma_inv['pst']:'';?>" class="form-control"><br>
                                    </div>
    									 <div class="col-lg-3">
                                     		<input type="checkbox"  name="pst_checkbox" value="2" <?php if($pst_checkbox == '2') { ?> checked="checked" <?php  } ?> >&nbsp; <label> <b style="color:#ff0000">Please Select To Add Tax</b></label>
                                     	 </div>
                                   
                            </div>
                            <div class="form-group" id="hst_div" style="display:none;">
                            	<label class="col-lg-3 control-label"><span class="required">*</span>HST(%)</label>
                                    <div class="col-lg-3">
                                    	<input type="text"  readonly="readonly" name="hst" id="hst" value="<?php echo isset($proforma_inv['hst']) ? $proforma_inv['hst']:'';?>" class="form-control">
                                    </div>
    									 <div class="col-lg-3">
                                     		<input type="checkbox"  name="hst_checkbox" value="3"  <?php if($hst_checkbox == '3') { ?> checked="checked" <?php  } ?> >&nbsp; <label> <b style="color:#ff0000">Please Select To Add Tax</b></label>
                                         </div>  
    					    </div>
                   <?php } ?>
    						<?php if($addedByInfo['country_id'] != '155'){ ?> 
                     
                            <div class="form-group">
                                <label class="col-lg-3 control-label">City</label>
                                <div class="col-lg-3">
                                    <input type="text" name="portofload" id="portofload" class="form-control validate" value="<?php echo isset($invoice)?$invoice['port_load']:'';?>"/>
                                </div> 
                                <?php if($addedByInfo['country_id'] != '42'){ ?> 
                                <label class="col-lg-3 control-label">Region</label>
                                <div class="col-lg-3">
                                   <input type="text" name="region" id="region" value="<?php echo isset($invoice) ? $invoice['region'] : '' ; ?>" class="form-control validate">
                                </div> 
                                 <?php } ?>
                            </div> 
                            
                          	    <?php
    								if((isset($addedByInfo['country_id']) && $addedByInfo['country_id']=='42')){
    							  ?>
    							  <div class="form-group">
    									<label class="col-lg-3 control-label"><span class="required">*</span>QST No.</label>
    									 <div class="col-lg-3">
    									  <input type="text" name="qst_no" id="qst_no" value="<?php if(isset($proforma_inv['qst_no'])) { echo $proforma_inv['qst_no']; } ?>" class="form-control validate[required]">
    									 </div>
    							  </div>
    							  <?php } ?>  
                        
                            <div class="form-group">
                           	 <?php if($addedByInfo['country_id'] != '42'){ ?> 
                                <label class="col-lg-3 control-label"> Vessel Name and No</label>
                                    <div class="col-lg-3">
                                        <input type="text" name="vessel_name" id="vessel_name" value="<?php echo isset($invoice) ? $invoice['vessel_name'] : '' ; ?>" class="form-control validate">
                                    </div>
                                <?php } ?>
                               <label class="col-lg-3 control-label">Payment Terms</label>
                               <div class="col-lg-3">
                                 <input type="text" name="payment_terms" id="payment_terms" value="<?php if(isset($pro_id)){ echo $pro_detail['payment_terms']; } elseif(isset($invoice)) { echo $invoice['payment_terms']; } else { echo '' ; }//echo isset($invoice) ? $invoice['payment_terms'] : '' ; ?>" placeholder="Payment terms" class="form-control" />
                               </div>
                      		</div>
                          
                            
                    
                            <div class="form-group">
                        <label class="col-lg-3 control-label">Postal Code</label>
                        <div class="col-lg-3">
                         <input type="text" class="form-control" id="postal_code" name="postal_code" value="<?php echo isset($invoice) ? $invoice['postal_code'] : '' ; ?>" />
                        </div>
                        <label class="col-lg-3 control-label">Account Code</label>
                        <div class="col-lg-3">                
                             <input type="text" name="account_code" value="<?php echo isset($invoice) ? $invoice['account_code'] : '200' ; ?>"  class="form-control " id="account_code" readonly="readonly"/>
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
                        <?php if($addedByInfo['country_id'] != '42'){ ?> 
                       <label class="col-lg-3 control-label">Type</label>
                        <div class="col-lg-3">                
                             <input type="text" name="type" value="<?php echo isset($invoice) ? $invoice['type'] : '' ; ?>"  class="form-control " id="type" />
                         </div>
                         <?php } ?>
                         <label class="col-lg-3 control-label">Tax Type</label>
                            <div class="col-lg-3">
                            <?php //echo $addedByInfo['country_id'];?>
                            <?php if($addedByInfo['country_id'] == '214'){ ?> 
                            		<input type="text" class="form-control" name="tax_type" id="tax_type" value="<?php echo 'Tax on Sales';?>" />	
                            
                              <?php } else { ?>
                                 <input type="text" class="form-control" name="tax_type" id="tax_type"  
                                 value="<?php echo isset($invoice) ? $invoice['tax_type'] : 'GST on Income';?>" readonly="readonly" />
                              <?php } ?>
                            </div>
                       </div>
                        <?php }?>
                        <?php //mansi 1-2-2016 (add discount) ?>
                         <div class="form-group">
                        <label class="col-lg-3 control-label">Discount</label>
                        <div class="col-lg-3">
                         <input type="text" class="form-control" id="discount" name="discount" value="<?php echo isset($invoice) ? $invoice['discount'] : '' ; ?>" />
                        </div>
                        <label class="col-lg-3 control-label">Reorder Date</label>
                        <div class="col-lg-3">
                        	<input type="text" class="input-sm form-control datepicker validate" readonly data-date-format="yyyy-mm-dd" id="reorder_date" name="reorder_date" value="<?php echo isset($invoice) ? $invoice['reorder_date'] : '' ; ?>" placeholder="Reorder Date" />
                        </div>
                      </div> 
                        
                   
                   <div class="line line-dashed m-t-large"></div>
                    	                   
                      <?php //printr($invoice);
    						$product_codes=$obj_invoice->getActiveProductCode();
    						$pro_cd_id = '';
    						if(isset($invoice_product_id)) { 
    							$color = $obj_invoice->getColorDetails($invoice_no,$invoice_product_id);
    							//$remaining_qty['remaining_qty'] = $remaining_qty['rem_qty_display']='';
    							if($user_id=='1' && $user_type_id=='1')
    							{
    								$remaining_qty['remaining_qty']='5000';
    								$remaining_qty['rem_qty_display']='1000';
    							}
    							else
    							{
    								if($color[0]['product_code_id'] != 0 && $color[0]['product_code_id'] != -2 && $color[0]['product_code_id'] != -1)
    									$remaining_qty= $obj_invoice->getStockQty($color[0]['product_code_id'],$invoice['proforma_no']);
    							}	
    							
    							$product_code= $obj_invoice->getProductCode($color[0]['product_code_id']);
    							
    							$pro_cd_id=$invoice_product['product_code_id'];
    							//printr($pro_cd_id);
    						}
    						?>
                         <div class="form-group fright" <?php if(isset($_GET['invoice_product_id']) && $pro_cd_id!='0') { echo   'style="display:none;"' ;}?>>  
                         
                                <label class="col-lg-3 control-label">Account Code</label>
                                    <div class="col-lg-2">
                                         <input type="text" class="form-control" name="sin_account_code" id="sin_account_code" value="<?php echo isset($_GET['invoice_product_id'])?$invoice_product['sin_account_code']:'260';?>" readonly="readonly"/>
                                    </div>
                                  
                                 <label class="col-lg-1 control-label">Freight Charges</label>
                                    <div class="col-lg-2">
                                         <input type="text" class="form-control" name="sin_fright_charge" id="sin_fright_charge" value="<?php echo isset($_GET['invoice_product_id'])?$invoice_product['rate']:'';?>"  />
                                    </div>
                                   <?php if($addedByInfo['country_id'] == '42')
    							   { ?> 
                                     <label class="col-lg-1 control-label"> Case Breaking Fees</label>
                                    <div class="col-lg-2">
                                         <input type="text" class="form-control" name="sin_case_breaking_fees" id="sin_case_breaking_fees" value="<?php echo isset($_GET['invoice_product_id'])?$invoice_product['case_breaking_fees']:'';?>"  />
                                    </div>
                                
                         </div>
                         
                         <div class="form-group fright" <?php if(isset($_GET['invoice_product_id']) && $pro_cd_id!='0') { echo   'style="display:none;"' ;}?>>  
                         	<label class="col-lg-3 control-label">Label Charges</label>
                            	<div class="col-lg-2">
                                         <input type="text" class="form-control" name="sin_label_charges" id="sin_label_charges" value="<?php echo isset($_GET['invoice_product_id'])?$invoice_product['label_charges']:'';?>" />
                                </div>
                                <label class="col-lg-1 control-label">Prepress Charges</label>
                                 <div class="col-lg-2">
                                         <input type="text" class="form-control" name="sin_prepress_charges" id="sin_prepress_charges" value="<?php echo isset($_GET['invoice_product_id'])?$invoice_product['prepress_charges']:'';?>"  />
                                  </div>
                                  <?php }  else if($addedByInfo['country_id'] == '251')
                                        {
                                        ?>
                                            <label class="col-lg-1 control-label"> Extra Charges : </label>
                                            <div class="col-lg-2">
                                                 <input type="text" class="form-control" name="charges_name" id="charges_name" value="<?php echo isset($_GET['invoice_product_id'])?$invoice_product['extra_charge_name']:'';?>" placeholder="Add Charges Name"  />
                                            </div>
                                            <div class="col-lg-2">
                                                 <input type="text" class="form-control" name="extra_charge" id="extra_charge" value="<?php echo isset($_GET['invoice_product_id'])?$invoice_product['extra_charge']:'';?>"  />
                                            </div>
                                        <?php } ?>
                         </div>
    
                         
                     <?php //}
    				 // printr($invoice_product);?>
                        <div class="pro_div_hide">
                         <div class="form-group" <?php if(isset($_GET['invoice_product_id']) && ($invoice_product['product_code_id'] == '0')) echo  'style="display:none;"' ;?>>
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
                     		
                          <div class="form-group" <?php if(isset($_GET['invoice_product_id']) && ($invoice_product['product_code_id'] == '0')) echo  'style="display:none;"' ;?>>
                             	<label class="col-lg-3 control-label">Volume</label>
                                <div class="col-lg-3">
                                    <input type="text" name="volume" value="" id="volume" class="form-control" onchange="color_chng()"/>
                                </div>
                     	 </div>
                    
                  		  <div class="form-group" <?php if(isset($_GET['invoice_product_id']) && ($invoice_product['product_code_id'] == '0')) echo  'style="display:none;"' ;?>>
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
                         
                         </div>
                          <div class="form-group option productCd" <?php if(isset($_GET['invoice_product_id']) && ($invoice_product['product_code_id'] == '0')) echo  'style="display:none;"' ;?>>
                   			<label class="col-lg-3 control-label"><span class="required">*</span>Product Code</label>
                    			<div class="col-lg-2" id="holder"> 
                                  
                                   <input type="hidden" id="product_code_id" name="product_code_id" value="<?php if(isset($_GET['invoice_product_id']) && ($invoice_product['product_code_id'] != '-1') && ($invoice_product['product_code_id'] != '-2')){ echo $product_code['product_code_id'];} else if(isset($invoice_product) && $invoice_product['product_code_id'] == '-1') { echo '-1'; } else if(isset($invoice_product) && $invoice_product['product_code_id'] == '-2') { echo '-2'; }else { echo '';} ?>">
                                   <input type="text" id="product_keyword" tabindex="0" class="form-control validate[required]"  autocomplete="off" value="<?php  if(isset($_GET['invoice_product_id']) && ($invoice_product['product_code_id'] != '-1') && ($invoice_product['product_code_id'] != '-2')){ echo $product_code['product_code'];} else if( isset($invoice_product) && $invoice_product['product_code_id'] == '-1') { echo 'Custom'; } else if( isset($invoice_product) && $invoice_product['product_code_id'] == '-2') { echo 'Cylinder'; } else { } ?>">
                         			 <div id="ajax_return"></div>
                                
                 	 	 		</div>
                               
                               <div class="col-lg-2" id="product_div"> 
                                   <input type="text" name="product_name" id="product_name" value="<?php echo isset($_GET['invoice_product_id'])?$invoice_product['product_description']:'';?>"  readonly="readonly" class="form-control validate" style="width:400px"/> 
                 	 	 		</div>
                             
                    		 </div>
                             
                             <?php if($addedByInfo['country_id'] == '155')
    							   { ?>
    						 <div class="form-group ">
    							<label class="col-lg-3 control-label">Pedimento</label>
    							<div class="col-lg-9">
    								<div  style="float:left;width: 500px;">
    													
    								<input type="text" name="pedimento_mexico" id="pedimento_mexico" class="form-control" placeholder="Pedimento" value="<?php echo isset($invoice_product) ? $invoice_product['pedimento_mexico'] :''; ?>">
    
    
    								</div> 
    							</div>
    						 </div>
    							   <?php } ?>
                              <div class="form-group">
                                <label class="col-lg-3 control-label rem"  <?php if(isset($_GET['invoice_product_id']) && ($invoice_product['product_code_id'] == '0')) echo  'style="display:none;"' ;?> >Stock Available Qty</label>
                              		 <div class="col-lg-1" id="qty_div" >
                                         <input type="text" name="rem_qty" id="rem_qty" value="<?php if(isset($_GET['invoice_product_id']) && ($invoice_product['product_code_id'] == '0')) { echo '1' ;} elseif(isset($_GET['invoice_product_id']) && ($invoice_product['product_code_id'] != '0') && ($invoice_product['product_code_id'] != '-1') && ($invoice_product['product_code_id'] != '-2')) { echo $remaining_qty['remaining_qty']; }else {echo '';}?>" readonly="readonly"  class="form-control validate"   <?php if(isset($_GET['invoice_product_id']) && ($invoice_product['product_code_id'] == '0')) echo  'style="display:none;"' ;?> />
                                          
                                          <input type="hidden" id="user_id" value="<?php echo $user_id;?>" />
                                          <input type="hidden" id="user_type_id"  value="<?php echo $user_type_id;?>" />
                                 	</div>
                           <!--sonu comment label 02/01/2017-->
                             <?php /*?>    <label class="col-lg-3 control-label rem"  <?php if(isset($_GET['invoice_product_id']) && ($invoice_product['product_code_id'] == '0')) echo  'style="display:none;"' ;?> >Invoice Inventory Balanced Qty</label><?php */?>
                                 <div class="col-lg-1" id="qty_div" >
                                 	
                                     <input type="hidden" name="inv_bal_qty" id="inv_bal_qty" value="<?php if(isset($_GET['invoice_product_id']) && ($invoice_product['product_code_id'] == '0')) { echo '1' ;} elseif(isset($_GET['invoice_product_id']) && ($invoice_product['product_code_id'] != '0') && ($invoice_product['product_code_id'] != '-1') && ($invoice_product['product_code_id'] != '-2')) { echo $remaining_qty['rem_qty_display']; }else {echo '';}?>" readonly="readonly"  class="form-control validate"   <?php if(isset($_GET['invoice_product_id']) && ($invoice_product['product_code_id'] == '0')) echo  'style="display:none;"' ;?> />
                                	
                                 </div>
                                 <label class="col-lg-2 control-label pro"  <?php if(isset($_GET['invoice_product_id']) && ($invoice_product['product_code_id'] == '0')) echo  'style="display:none;"' ;?> >Proforma  Available Qty</label>
                              		 <div class="col-lg-1" id="qty_div" >
                                         <input type="text" name="proforma_qty" id="proforma_qty" value="<?php if(isset($_GET['invoice_product_id']) && ($invoice_product['product_code_id'] == '0')) { echo '1' ;} elseif(isset($_GET['invoice_product_id']) && ($invoice_product['product_code_id'] != '0') && ($invoice_product['product_code_id'] != '-1') && ($invoice_product['product_code_id'] != '-2')) { echo $invoice_product['qty']; }else {echo '';}?>" readonly="readonly"  class="form-control validate"   <?php if(isset($_GET['invoice_product_id']) && ($invoice_product['product_code_id'] == '0')) echo  'style="display:none;"' ;?> />
                          
                                  
                                          
                                        
                                 	</div>
                                 <?php if($addedByInfo['country_id']=='14') { ?>
                                  <label class="col-lg-3 control-label rem"  <?php if(isset($_GET['invoice_product_id']) && ($invoice_product['product_code_id'] == '0')) echo  'style="display:none;"' ;?> >Transfered Qty</label>
                                 <div class="col-lg-1" id="qty_div" >
                                 	
                                     <input type="text" name="transfer_qty" id="transfer_qty" value="<?php if(isset($_GET['invoice_product_id']) && ($invoice_product['product_code_id'] == '0')) { echo '1' ;} elseif(isset($_GET['invoice_product_id']) && ($invoice_product['product_code_id'] != '0') && ($invoice_product['product_code_id'] != '-1') && ($invoice_product['product_code_id'] != '-2')) { echo $remaining_qty['tran_qty']; }?>" readonly="readonly"  class="form-control validate"   <?php if(isset($_GET['invoice_product_id']) && ($invoice_product['product_code_id'] == '0')) echo  'style="display:none;"' ;?> />
                                	
                                 </div>
                                 <?php } ?>
                               </div>   
                             
                              <div class="form-group">
                                    <label class="col-lg-3 control-label sin_qty_lable" <?php if(isset($_GET['invoice_product_id']) && $pro_cd_id!='0') { echo   'style="display:none;"' ;}?>>Qty</label>
                                    <div class="col-lg-1" id="sin_qty_div" <?php if(isset($_GET['invoice_product_id']) && $pro_cd_id!='0') { echo   'style="display:none;"' ;}?>> 
                                       <input type="text" name="sin_qty" id="sin_qty" value="<?php echo isset($_GET['invoice_product_id'])?$invoice_product['qty']:'1';?>"  readonly="readonly" class="form-control validate"/>
                                    </div>
    							<label class="col-lg-3 control-label"></label> 
    							<div class="col-lg-3  pro_table"  <?php if(isset($_GET['invoice_product_id']) && ($invoice_product['product_code_id'] == '0')) echo  'style="display:none;"' ;?>>
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
    												'rate' => '',
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
    											
                                                <td><input type="text" name="color[<?php echo $colorkey; ?>][qty]" value="<?php echo $clr['qty']; ?>" class="form-control validate[required,custom[onlyNumberSp]]" placeholder="Qty" id="qty" onChange="getinvoice_amt()" ></td>
                                                <td><input type="text" name="color[<?php echo $colorkey; ?>][rate]" value="<?php echo $clr['rate']; ?>" class="form-control validate[required,custom[number],min[0.001]]" placeholder="Rate" id="rate"  ></td>
                                               </td>
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
         
                <?php if($addedByInfo['country_id'] != '42')
    			{?>
    			     <?php    if(($addedByInfo['country_id'] == '209' || $addedByInfo['country_id'] == '251')  )
                            	{
                            	    $label="VAT(%)";$readonly="";
                            	}else{
                            	    $label="Tax (%)";$readonly='readonly="readonly"';
                            	}?>					
    							 
                 <div class="form-group ">
                    <label class="col-lg-3 control-label"><?php echo $label;?></label>
                    <div class="col-lg-2">
                      <?php $gst=0;
    				   //mansi 6-2-2016 (change for tax in singapore)
    				  ?>
                          <?php  
    							if($user_type_id!='1' && $user_id!='1')
    						 		$gst_tax_new = isset($invoice) ? $invoice['tax_maxico'] : round($addedByInfo['gst']) ;
    							else
    								$gst_tax_new = isset($invoice) ? $invoice['tax_maxico'] : $gst ;
    								
    						?>
                      <input type="text" name="tax_maxico" class="form-control " id="tax_maxico" value="<?php echo $gst_tax_new;  ?> " <?php echo $readonly;?>>
                  
                    </div>
                 </div>
                <?php }?>
                 <div class="form-group " >
                    <label class="col-lg-3 control-label">Invoice Amount</label>
                    <div class="col-lg-3">
                      <?php 
    						 	$payment_terms_new = isset($invoice) ? $invoice['payment_terms'] : ''  ;?>
                      <input type="text" name="payment_terms" class="form-control terms" id="payment_terms_maxico" value="<?php echo  $payment_terms_new ;?>" readonly="readonly"/>
                        
                       <input type="hidden" class="form-control terms" id="old_inv_amt" value=""/>
                    </div>
                    <?php $payment = (isset($invoice['payment_maxico']) && !empty($invoice['payment_maxico'])) ? explode(',',$invoice['payment_maxico']):array();
    					?>
                     <label class="col-lg-3 control-label payment_label"  style="display:none;">Mode Of Payment</label>
                    <div class="col-lg-3 payment_div"  style="display:none;"> 
                      
                         <?php  if((isset($addedByInfo['country_id']) && $addedByInfo['country_id']=='251') || (isset($addedByInfo['country_id']) && $addedByInfo['country_id']=='209')) { $payment_label='Cheque';}else{
                        $payment_label='Check'; }?>
                      <input type="radio" name="payment[]" id="payment" value="cash" class="validate[minCheckbox[1]]" <?php echo (in_array('cash',$payment))?'checked="checked"':'checked="checked"';?>> Cash </br>
                      <?php if($addedByInfo['country_id'] != '14') {?>
                        <input type="radio" name="payment[]" id="payment" value="check" class="validate[minCheckbox[1]]" <?php echo (in_array('check',$payment))?'checked="checked"':'';?>> <?php echo $payment_label;?> </br>
                      <?php } else {?>
                        <input type="radio" name="payment[]" id="payment" value="paypal" class="validate[minCheckbox[1]]" <?php echo (in_array('paypal',$payment))?'checked="checked"':'';?>> PayPal </br>
                      <?php }?>
                      <input type="radio" name="payment[]" id="payment" value="credit_card" class="validate[minCheckbox[1]]"  <?php echo (in_array('credit_card',$payment))?'checked="checked"':'';?>> Credit Card </br>
                      <input type="radio" name="payment[]" id="payment" value="transfer" class="validate[minCheckbox[1]]"  <?php echo (in_array('transfer',$payment))?'checked="checked"':'';?>> Transfer </br>
                     <?php if($addedByInfo['country_id'] == '214') {?>
                        <input type="radio" name="payment[]" id="payment" value="NETS" class="validate[minCheckbox[1]]" <?php echo (in_array('NETS',$payment))?'checked="checked"':'';?>> NETS </br>
                      <?php } ?> 
                      <?php if($addedByInfo['country_id'] == '42') {?>
                        <input type="radio" name="payment[]" id="payment" value="E-Transfer" class="validate[minCheckbox[1]]" <?php echo (in_array('E-Transfer',$payment))?'checked="checked"':'checked="checked"';?>> E-Transfer </br>
                        <input type="radio" name="payment[]" id="payment" value="Paypal" class="validate[minCheckbox[1]]" <?php echo (in_array('Paypal',$payment))?'checked="checked"':'';?>> Paypal </br>
                      <?php } ?>
                    </div>
                 </div>
    			 <div class="mexico_div" style="display:none;">
                 <div class="form-group">
                    <label class="col-lg-3 control-label">Payment Type</label>
                    <div class="col-lg-2" >
                      
                       <select name="payment_type" id="payment_type" class="form-control" onchange="getpayment_amt()">   
                            <option value="">Select Payment Type</option>               
                            <option value="full" <?php echo (isset($invoice['pay_type_maxico']) && $invoice['pay_type_maxico'] == 'full')?'selected':'';?> > Full</option>
                            <option value="Advance" <?php echo (isset($invoice['pay_type_maxico']) && $invoice['pay_type_maxico'] == 'Advance')?'selected':'';?>> Advance</option>
                            <option value="Part" <?php echo (isset($invoice['pay_type_maxico']) && $invoice['pay_type_maxico'] == 'Part')?'selected':'';?>> Part</option>
                            <option value="waiting" <?php echo (isset($invoice['pay_type_maxico']) && $invoice['pay_type_maxico'] == 'waiting')?'selected':'';?>> A Waiting For Payment</option>
                      </select>
                      
                    </div>
                    <label class="col-lg-3 control-label" style="margin-left:120px;">Date of Payment Receipt</label>
                             <div class="col-lg-3">
                                 <input type="text" name="date_of_payment_receipt" readonly data-date-format="yyyy-mm-dd"  value="<?php if(isset($pro_id)){ echo $pro_detail['date_of_payment_receipt']; } 
    							 	elseif(isset($invoice)) 
    								{ 
    									if($invoice['date_of_payment_receipt']=='0000-00-00')
    										echo date("Y-m-d") ;
    									else
    										echo $invoice['date_of_payment_receipt']; 
    								} 
    								
    								else { echo date("Y-m-d") ; } //echo isset($invoice) ? $invoice['invoice_date'] : '' ; ?>" placeholder="Invoice Date" id="date_of_payment_receipt" class="input-sm form-control datepicker validate[required]"/>
                      </div>
                 </div>
                 
                 <div class="form-group">
                  <?php
    			  if($addedByInfo['country_id'] == '155')
    				{?>
                   		 <label class="col-lg-3 control-label">Amount</label>
                            <div class="col-lg-3">
                             <input type="text" name="amt_maxico" class="form-control validate[custom[number]]" id="amt_maxico" value="<?php echo isset($invoice) ? $invoice['amt_maxico'] : '' ; ?>">
                            </div>
                            
                             <label class="col-lg-3 control-label">Currency</label>
                            <div class="col-lg-3">
                                <?php $currency = $obj_invoice->getCurrency();
    							?>
                                   <select name="currency" id="currency_maxico" class="form-control validate" >
                               
    								   <option value="">Select Currency</option>
    									<?php 
    									foreach($currency as $curr)
    									{
    										
    										if($addedByInfo['country_id'] == '155')
    										{
    											if( $curr['currency_id'] == '2' ||  $curr['currency_id'] == '10')
    											{?>
    											<option value="<?php echo $curr['currency_id']; ?>" <?php if(isset($_GET['invoice_no']) && $curr['currency_id'] == $invoice['curr_id']) { ?> selected="selected" <?php } ?>><?php echo $curr['currency_code']; ?></option>
    								  <?php 
    								  } } 
    								  	if($addedByInfo['country_id'] == '42')
    									{
    										if( $curr['currency_id'] == '2' ||  $curr['currency_id'] == '15')
    										{?>
    											<option value="<?php echo $curr['currency_id']; ?>" <?php if(isset($_GET['invoice_no']) && $curr['currency_id'] == $invoice['curr_id']) { ?> selected="selected" <?php } ?>><?php echo $curr['currency_code']; ?></option>
    								  <?php 
    								  } } 
    								  else if($addedByInfo['country_id'] == '14')
    									{
    										if( $curr['currency_id'] == '6')
    									{ ?>
                                        	<option value="<?php echo $curr['currency_id']; ?>" <?php if(isset($proforma_id) && $curr['currency_id'] == $proforma_inv['currency_id']) { ?> selected="selected" <?php } ?>><?php echo $curr['currency_code']; ?></option>
    									 <?php } }
    									 else if($addedByInfo['country_id'] == '214')
    									{
    										if( $curr['currency_id'] == '8')
    									{ ?>
                                        	<option value="<?php echo $curr['currency_id']; ?>" <?php if(isset($proforma_id) && $curr['currency_id'] == $proforma_inv['currency_id']) { ?> selected="selected" <?php } ?>><?php echo $curr['currency_code']; ?></option>
    									 	
    									 <?php } } } ?>
    								  
    							</select>
                            </div>
                   <?php } else { ?>
                   
                       <label class="col-lg-3 control-label">Amount Paid</label>
                            <div class="col-lg-3">                
                                 <input type="text" name="amount_paid" value="<?php echo isset($invoice) ? $invoice['amount_paid'] : ''; ?>"  class="form-control validate[custom[number]]" id="amt_maxico"/>
                             </div>
                             
                            <label class="col-lg-3 control-label"><span class="required">*</span> Currency</label>
                            <div class="col-lg-3">
                                <?php $currency = $obj_invoice->getCurrency();
    							//printr($currency);?>
                                 <select name="currency" id="currency" class="form-control validate" >
                                   <option value="">Select Currency</option>
                                    <?php foreach($currency as $curr){ 
                                    if($addedByInfo['country_id'] == '42')
    								{
    									if( $curr['currency_id'] == '2' ||  $curr['currency_id'] == '15')
    									{?>
    										<option value="<?php echo $curr['currency_id']; ?>" <?php if(isset($_GET['invoice_no']) && $curr['currency_id'] == $invoice['curr_id']) { ?> selected="selected" <?php } ?>><?php echo $curr['currency_code']; ?></option>
    								  <?php 
    								  } } 
    								  else if($addedByInfo['country_id'] == '155')
    								 {
    									if( $curr['currency_id'] == '2' ||  $curr['currency_id'] == '10')
    									{ ?>
                                        	<option value="<?php echo $curr['currency_id']; ?>" <?php if(isset($proforma_id) && $curr['currency_id'] == $proforma_inv['currency_id']) { ?> selected="selected" <?php } ?>><?php echo $curr['currency_code']; ?></option>
                                        <?php } }   
    								  	else if($addedByInfo['country_id'] == '14')
    									{
    										if( $curr['currency_id'] == '6')
    									{ ?>
                                        	<option value="<?php echo $curr['currency_id']; ?>" <?php if(isset($proforma_id) && $curr['currency_id'] == $proforma_inv['currency_id']) { ?> selected="selected" <?php } ?>><?php echo $curr['currency_code']; ?></option>
    									 <?php } }
    									 else if($addedByInfo['country_id'] == '214')
    									{
    										if( $curr['currency_id'] == '8')
    									{ ?>
                                        	<option value="<?php echo $curr['currency_id']; ?>" <?php if(isset($proforma_id) && $curr['currency_id'] == $proforma_inv['currency_id']) { ?> selected="selected" <?php } ?>><?php echo $curr['currency_code']; ?></option>
    									 	
    									 <?php } }
    								  
    								  
    								    else{ ?>
                                            <option value="<?php echo $curr['currency_id']; ?>" <?php if(isset($_GET['invoice_no']) && $curr['currency_id'] == $invoice['curr_id']) { ?> selected="selected" <?php } ?>><?php echo $curr['currency_code']; ?></option>
                                  <?php   }} ?>
                                </select>
                      
                            </div>
                         
                         <?php } ?>   
                         
                    </div>
               		
                    <div class="form-group">
                    <label class="col-lg-3 control-label">Details</label>
                    <div class="col-lg-6">
                      
                      	<textarea name="detail_maxico" id="detail_maxico"  class="form-control validate"><?php echo isset($invoice) ? $invoice['detail_maxico'] : ''  ; ?></textarea>
                       
                    </div>
                 </div>
               </div>
    			 
    			<?php //}?>
                   <div class="form-group">
                    <label class="col-lg-3 control-label">Status</label>
                    <div class="col-lg-4">
                      <select name="status" id="status" class="form-control">                  
                        <option value="1" <?php echo (isset($invoice['status']) && $invoice['status'] == 1)?'selected':'';?> > Active</option>
                        <option value="0" <?php echo (isset($invoice['status']) && $invoice['status'] == 0)?'selected':'';?>> Inactive</option>
                      </select>
                      
                      
                    </div>
                   </div>
                  <input type="hidden" id="invoice_id_max" name="invoice_id_max" value="<?php echo isset($invoice_no) ? $invoice_no : '';?>"/>
                           <div class="form-group">
                                  <div class="col-lg-9 col-lg-offset-3">
                                  <button type="button" name="btn_save" id="btn_save" class="btn btn-primary" <?php if(isset($_GET['invoice_product_id'])){ ?> style="display:none" <?php } ?> onclick="displaygenerate();">Add Product</button>
                                      <button type="button" name="btn_fright" id="btn_fright" class="btn btn-primary" onclick="">Fright Charges</button>
                                     
                                   <?php 
                                    if(isset($invoice_no) && isset($invoice_product_id)) {?>
                                    <input type="hidden" name="pro_id" value="<?php echo $invoice_product_id;?>" id="pro_id" />
                                    <input type="hidden" value="<?php echo $invoice_product_id;?>" id="pro_id_amt" />
                                  <input type="hidden" id="invoice_id" name="invoice_id" value="<?php echo $invoice_no;?>"/>	
                                 
                                      <a href=" <?php echo $obj_general->link($rout, 'mod=add&invoice_no='.encode($invoice_no).'&is_delete='.$_GET['is_delete'],'',1); ?>" id="invoice_update"  name="invoice_update" class="btn btn-primary">Update Product</a>
                                     
                                 <?php } ?>
                                 
                                 
                                 </div>
                            </div>
                    
                    <?php  //} ?>
                    
                    
                      <div id="invoice_results">
               <?php 
    				$invoice_no_real = isset($invoice_no) ? $invoice_no : '';
    		   if(isset($invoice_no_real) && !empty($invoice_no_real)) {
    					$invoice_product_second = $obj_invoice->getInvoiceProduct($invoice_id);
    			   
    				 ?>
                        <table class="table table-bordered"> 
                            <thead>
                                <tr>
                                    <th>Product Name </th>
                                    <th>Quantity</th>
                                    <th>Rate</th> 
                                 <?php echo '<th>Action</th>  ';?>                               
                                </tr>
                            </thead>
                            <tbody>
                            <input type="hidden" id="invoice_id_encode" value="<?php echo encode($invoice_no_real);?>"/>	
        						<?php 
    							if($invoice_product_second!='')
    							{
    								foreach($invoice_product_second as $invoice_d ) {
    							//printr($invoice_d);
    									$invoice_product_id = $invoice_d['invoice_product_id'];
    						if(isset($invoice_product_id) && !empty($invoice_product_id))
    						{ $case=$label=$prepress='';
    						
    						 ?>
                             
                             	<input type="hidden" id="prd_code_id" name="prd_code_id[]" value="<?php echo $invoice_d['product_code_id']; ?>"/>
    						  <input type="hidden" name="invoice_id" value="<?php echo $invoice_no_real; ?>"  />
                		      <tr id="invoice_product_id_<?php echo $invoice_d['invoice_product_id']; ?>">
    							  <td><b><?php 
    							  		if($invoice_d['product_code_id'] == '-2')
    									{
    										echo 'Cylinder <br>';
    									
    									}elseif($invoice_d['product_code_id'] == '-1')
    									{
    										echo 'Custom <br>'. $invoice_d['product_description'];
    									}
    									elseif($invoice_d['product_code_id'] == '1194')
    									{
    										echo 'Sample <br>';	
    									}
    									elseif($invoice_d['product_code_id'] == '0')
    									{
    										if($invoice_d['rate']!='0.0000')
    											echo'<b>Freight Charges : </b>'.$invoice_d['rate'];
    											
    										if($invoice_d['case_breaking_fees']!='0.0000')
    										{
    											echo'<br><b>Case Breaking Charges : </b>'.$invoice_d['case_breaking_fees'];
    											$case = '<br>'.$invoice_d['case_breaking_fees'];
    										}
    										if($invoice_d['label_charges']!='0.0000')
    										{
    											echo'<br><b>Label Charges : </b>'.$invoice_d['label_charges'];
    											$label = '<br>'.$invoice_d['label_charges'];
    										}
    										if($invoice_d['prepress_charges']!='0.0000')
    										{
    											echo'<br><b>Prepress Charges : </b>'.$invoice_d['prepress_charges'];
    											$prepress = '<br>'.$invoice_d['prepress_charges'];
    										}
    										if($invoice_d['extra_charge_name']!='0' && $invoice_d['extra_charge']!='0' )
                        					{
                        						echo '<br>'.$invoice_d['extra_charge_name'].':'.$invoice_d['extra_charge'];
                        						$prepress = '<br>'.$detail['extra_charge'];
                        					}
    										
    											
    									}
    									else
    									{
    							  			  $product_code= $obj_invoice->getProductCode($invoice_d['product_code_id']);
    										  echo $product_code['product_name'].'<br>'.$product_code['product_code'];
    								    }
    									?></b><input type="hidden" id="invoice_pcode_<?php echo $invoice_d['invoice_product_id']; ?>" value="<?php echo $invoice_d['product_code_id']; ?>" /></td>
                      					
                      			   <td>
    				  				<?php echo $invoice_d['qty'];?>
                                    <input type="hidden" id="invoice_qty_<?php echo $invoice_d['invoice_product_id']; ?>" value="<?php echo $invoice_d['qty']; ?>"  />
    							  </td>
                				  <td>
    								  <?php
    								  	if($invoice_d['rate']!='0.0000')
    								  		 echo $invoice_d['rate'];
    									echo $case;
    									echo $label;
    									echo $prepress;?>
                                       <input type="hidden" id="invoice_rate_<?php echo $invoice_d['invoice_product_id']; ?>" value="<?php echo $invoice_d['rate']; ?>"  />
    							  </td>
                           
                     			<td class="del-product">
                     			<?php if(!isset($_GET['invoice_no']))
    							{?>   
                     			    <a class="btn btn-danger btn-sm" href="javascript:void(0);" onClick="removeInvoice(<?php echo $invoice_d['invoice_product_id'].','.$invoice_d['invoice_id']; ?>)"><i class="fa fa-trash-o"></i></a>
    			                 <?php } ?>
    			                 <a href="<?php echo $obj_general->link($rout, 'mod=add&invoice_no='.encode($invoice_no_real).'&invoice_product_id='.encode($invoice_d['invoice_product_id']).'&is_delete='.$_GET['is_delete'],'',1); ?>" id="btn_edit"  name="btn_edit" class="btn btn-info btn-xs btn_edit">Edit</a>
                			      </td>
                               <?php // } ?>
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
                    <?php if($addedByInfo['country_id'] == '14') {?>
                     <div class="col-lg-9">
                           <!--<div id="mySelect" class="select btn-group m-b" data-resize="auto">
                                <button type="button" data-toggle="dropdown" class="btn btn-white btn-sm dropdown-toggle"> <span class="dropdown-label">Please Select </span> <span class="caret"></span> </button> 
                                <ul class="dropdown-menu" onclick="">
                                   <li data-value=""><a>Please Select</a></li>
                                   <li class="li_tag" data-value="close"><a href="" >Generate Sales Invoice and Close</a></li>
                                   <li class="li_tag" data-value="download"><a href="">Generate Sales Invoice and Download</a></li>
                                   <li class="li_tag" data-value="dispatch" ><a href="">Generate Sales Invoice and Dispatch Stock</a></li>
                                   <li class="li_tag" data-value="email"><a href="">Generate Sales Invoice and Send Email</a></li>
                                  
                                </ul>
                           </div>-->
                           <div class="col-lg-9 col-lg-offset-1" id="btn_combo">
        						<button type="button" id="generate_inv" onclick="gen_invoice('close')" class="btn btn-primary" >Update Sales Invoice and Close</button>
        						<button type="button" id="generate_inv" onclick="gen_invoice('download')" class="btn btn-primary">Update Sales Invoice and Download</button> 
        						<button type="button" id="generate_inv" onclick="gen_invoice('dispatch')" class="btn btn-primary">Update Sales Invoice and Dispatch Stock</button> 
        						<button type="button" id="generate_inv" onclick="gen_invoice('email')" class="btn btn-primary">Update Sales Invoice and Send Email</button>
        						<button type="button" id="generate_inv" onclick="gen_invoice('courier')" class="btn btn-primary">Update Sales Invoice and Add Courier Details</button> 
        					</div>
                                           
                        </div> 
                    <?php }else { ?>
                        <div class="col-lg-9 col-lg-offset-3">
                            <button type="button" name="btn_update" id="btn_update" class="btn btn-primary">Update </button>
                            <a class="btn btn-default" href="<?php echo $obj_general->link($rout, 'mod=index&is_delete='.$_GET['is_delete'].$add_url, '',1);?>">Cancel</a>
                        </div>
                    <?php } ?>
                </div>
        <?php }  ?>
                <div class="col-lg-9 col-lg-offset-3">
                    <button type="button" id="generate_invoice"  class="btn btn-primary" name="generate_invoice" onClick="add_invoices()" style="display:none" >Generate Invoice</button>
                  
                   <button type="button" name="inv_payment" id="inv_payment" class="btn btn-primary" style="display:none"> Payment </button> 
    					
                </div>
    	            </form>
        	      </div>
            	</section>
    	    </div>
        </div>
      </section>
    </section>
    <div id="test"></div>
    <!-- Modal For Remove -->
    <div id="smail" class="modal fade in" aria-hidden="false">
        <div class="modal-dialog">
            <div class="modal-content">
            	<form class="form-horizontal" method="post" name="model_form" id="model_form" style="margin-bottom:0px;">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                       
                        <h4 class="modal-title">DELETE</h4>
                    </div>
                    <div class="modal-body">
                         <input type="hidden" name="invoice_product_id_model" id="invoice_product_id_model" value=""  />
                         <input type="hidden" name="invoice_id_model" id="invoice_id_model" value=""  />
                        <p id="setmsg">Are you sure you want to delete selected record ?</p>
                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
                        <button class="btn btn-primary" id="popbtnok" name="popbtnok" type="button" onclick="deleteinvoice()">Ok</button>
                    </div>
                  </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="email_div" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" style="width:70%;" >
        <div class="modal-content">
          <form class="form-horizontal" method="post" name="cform" id="cform" style="margin-bottom:0px;">
                  <div class="modal-header">
            <h4 class="modal-title u_title" id="myModalLabel"><span id="customer"></span></h4>
                  </div>
                  <div class="modal-body">
                    <div class="form-group">
                       <div class="panel-body">
                        <div class="form-group">    
                	    <label class="col-lg-1 control-label" id="email">To Email</label>
                        <div class="col-lg-10"> 
                            <input type="toemail" name="toemail" value="" id="toemail" class="form-control" required />
                        </div>
                    </div>
                     <div class="form-group">
                       <label class="col-lg-1 control-label" id="email">CC</label>
                        <div class="col-lg-10"> 
                            <input type="toemail" name="ccemail" value="" id="ccemail" class="form-control"/><br><small style="color:red;">If you want to have multiple email CC, please add email ids with a comma (,) sign.</small>
                        </div>
                    </div>
                    <div class="form-group">
    					<label class="col-lg-1 control-label" id="email">BCC</label>
                        <div class="col-lg-10"> 
                            <input type="toemail" name="bccemail" value="" id="bccemail" class="form-control"/><small style="color:red;">If you want to have multiple email BCC, please add email ids with a comma (,) sign.</small>
                        </div>
                    </div>
                        <!--<div class="form-group">    
                            <label class="col-lg-1 control-label" id="email">To Email</label>
                            <div class="col-lg-3"> 
                                <input type="toemail" name="toemail" value="" id="toemail" class="form-control" required />
                            </div>
                            <label class="col-lg-1 control-label" id="email">CC</label>
                            <div class="col-lg-3"> 
                                <input type="toemail" name="ccemail" value="" id="ccemail" class="form-control"/>
                            </div>
                            <label class="col-lg-1 control-label" id="email">BCC</label>
                            <div class="col-lg-3"> 
                                <input type="toemail" name="bccemail" value="" id="bccemail" class="form-control"/>
                            </div>
                         </div>-->
                          <div class="form-group">
                            <label class="col-lg-1 control-label" id="email">Subject</label>
                            <div class="col-lg-10">
                                <input type="text" name="subject" value="" id="subject" class="form-control" required />
                            </div>
                        </div>
                         <div class="form-group">
                          <label class="col-lg-1 control-label">Body</label>
                            <div class="col-lg-10">
                                <textarea id="message" name="message" value="" class="form-control"  required style="height: 237px;"></textarea>
                             
                               <input type="hidden" name="admin" id="admin" value="<?php echo ADMIN_EMAIL;?>" />
                              
                               <input type="hidden" name="emailform" id="emailform" value="" />
                                <input type ="hidden" name="sales_invoice_send" id="sales_invoice_send" value="" />
                               </div>
                            </div>
                         </div>
                       </div> 
                    </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" >Close</button>
                     <button type="submit" name ="btn_send_customer" id="btn_send_customer"  onclick="send_mail_customer()"class="btn btn-primary">Send</button>
                  </div>
          </form>   
        </div>
      </div>
    </div>
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
    
    #ajax_response, #ajax_return{
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
    
    </style>
    <!-- Start : validation script -->
    <link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
    <script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
    <script src="<?php echo HTTP_SERVER;?>ckeditor3/ckeditor.js"></script>
    <script>
    /*function add_pro_detail()
    {
    	addProformaSales();
    }*/
    $("#invoice_update").click(function() {
    	//addProformaSales();
    	
    	if($("#form").validationEngine('validate')){
    	
    	var edit=$("#edit").val();
    	
    	
    	var update_invoice_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=updateInvoiceProduct', '',1);?>");
    			var myform = $('#form');
    	var disabled = myform.find(':disabled').removeAttr('disabled');
    	var formData = myform.serialize();
    	//alert(formData);
    		$.ajax({
    				url : update_invoice_url,
    				type : 'post',		
    				data : {formData : formData},
    				success: function(response){
    				var value = $.parseJSON(response);
    				//cosole.log(response);
    				alert(response);
    					if(value.response != 0){
    						$(".productCd").show();
    						$(".pro_div_hide").show();
    						$(".pro_table").show();
    						$("#rem_qty").show();
    						$(".rem").show();
    						$(".qty_div").show();
    						$(".fright").hide();
    						$('.sin_qty_lable').hide();
    						$('#sin_qty_div').hide();
    						$("#invoice_results").html("");
    						$("#invoice_results").html(value.response);
    						$("#payment_terms_maxico").val(value.payment_amt);
    						$('#invoice_update').hide(); 
    						$('#btn_save').show();
    						$("#product").val('');
    						$("#netweight").val('');	
    						$("#size").val('');
    						$("#color_0").val('');
    						$("#item_no").val('');
    						$("#measurement").val('');
                            $("#pedimento_mexico").val('');
    						$("#buyers_o_no").val('');
    						$("#net_weight_0").val('');
    						$("#rem_qty").val('');
    						$("#qty").val('');
    						$("#rate").val('');
    						$("#product_keyword").val('');
    						$("#sin_fright_charge").val('');
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
    
    
    
    jQuery(document).ready(function(){
        
        //$("#invoicedate").datepicker('disable');
        
    	var edit=$("#edit").val();	
    		if(edit != '')
    			$( "#state" ).change();
    
    	if($("#fright_product").val() == '0')
    		$("#btn_fright").hide();
    	
    
    	var inv_id=$("#pro_id_amt").val();
    	var Cd_id=$("#product_code_id").val();
    	//alert(Cd_id);
    	if(typeof inv_id != "undefined" && Cd_id=='')
    	{
    		$('.fright').show();
    		$('.sin_qty_lable').show();
    		$('#sin_qty_div').show();
    	}
    	else
    	{
    		$('.fright').hide();
    		$('.sin_qty_lable').hide();
    		$('#sin_qty_div').hide();
    	}
    	
    	$(document).bind("keydown", function(event){
    		var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
    		if( keyCode == 13 ) {
    		
    		}
    		
    	});
    	
    	jQuery("#form").validationEngine();
    	
        var edit_val = $("#edit").val();
        if(edit_val == '1')
        {
        	//$(".payment_div").show();
        	//$(".mexico_div").show();
        	$(".tax_div").show();
        	//$(".payment_label").show();
        	
        }
    	
    $(document).click(function(){
    	 $("#ajax_response").fadeOut('slow');
    	 $("#ajax_response").html("");
    });
    	
    	$("#customer-name").focus();
    	var offset = $("#customer-name").offset();
    	var width = $("#holder").width();
    	$("#ajax_response").css("width",width);
    $("#customer-name").keyup(function(event){		
    		 var keyword = $("#customer-name").val();
    		 if(keyword.length)
    		 {
    			 if(event.keyCode != 40 && event.keyCode != 38 )
    			 {
    				 var product_code_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=customer_detail'.$add_url, '',1);?>");
    				 $("#loading").css("visibility","visible");
    				 $.ajax({
    				   type: "POST",
    				   url: product_code_url,
    				   data: "customer_name="+keyword,
    				   success: function(msg){	
    				 var msg = $.parseJSON(msg);
    				   var div='<ul class="list">';
     //kavita 13-4-2017
    					if(msg.length>0)
    					{
    						for(var i=0;i<msg.length;i++)
    						{	//alert(keyword);
    						div =div+'<li><a href=\'javascript:void(0);\' id="'+msg[i].address_book_id+'" consignee="'+msg[i].c_address+'" company_address_id="'+msg[i].company_address_id+'"  email="'+msg[i].email_1+'"><span class="bold" >'+msg[i].company_name+'</span></a></li>';
    						}
    					}
    					div=div+'</ul>';
    					if(msg != 0)
    					  $("#ajax_response").fadeIn("slow").html(div);
    					else
    					{
    					  $("#ajax_response").fadeIn("slow");	
    					  $("#ajax_response").html('<div style="text-align:left;">No Matches Found</div>');
    					   $("#email").val('');
    				  		$("#consignee").val('');
    				  		$("#address_book_id").val('');
    						$("#company_address_id").val('');
    						$("#factory_address_id").val('');
    						
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
    							$("#customer-name").val($(".list li[class='selected'] a").text());
    							$("#email").val($(".list li[class='selected'] a").attr("email"));
    							$("#consignee").val($(".list li[class='selected'] a").attr("consignee"));				
    							$("#address_book_id").val($(".list li[class='selected'] a").attr("id"));
    							$("#company_address_id").val($(".list li[class='selected'] a").attr("company_address_id"));
    						
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
    							$("#customer-name").val($(".list li[class='selected'] a").text());
                      			$("#email").val($(".list li[class='selected'] a").attr("email"));
    							$("#consignee").val($(".list li[class='selected'] a").attr("consignee"));
    							$("#address_book_id").val($(".list li[class='selected'] a").attr("id"));
    							$("#company_address_id").val($(".list li[class='selected'] a").attr("company_address_id"));
    							
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
    	
    	$('#customer-name').keydown( function(e) {
    		if (e.keyCode == 9) {
    			 $("#ajax_response").fadeOut('slow');
    			 $("#ajax_response").html("");
    		}
    	});
    
    $("#ajax_response").mouseover(function(){
    			$(this).find(".list li a:first-child").mouseover(function () {
                      $("#email").val($(this).attr("email"));
    				  $("#consignee").val($(this).attr("consignee"));
    				  $("#address_book_id").val($(this).attr("id"));
    				  $("#company_address_id").val($(this).attr("company_address_id"));
    				
    				  $(this).addClass("selected");
    			});
    			$(this).find(".list li a:first-child").mouseout(function () {
    				  $(this).removeClass("selected");
    				  $("#email").val('');
    				  $("#consignee").val('');				 
    				  $("#address_book_id").val('');
    				  $("#company_address_id").val('');
    				
    			});
    				$(this).find(".list li a:first-child").click(function () {
                      if($(this).attr("email")!='null')
    					  	$("#email").val($(this).attr("email"));
    					  else
    					  	$("#email").val('');
    						
    					  if($(this).attr("consignee")!='null')
    					  	$("#consignee").val($(this).attr("consignee"));
    					  else
    					 	 $("#consignee").val('');							 
    				
    					  $("#customer_name_id").val($(this).attr("id"));
    					  if($(this).attr("company_address_id")!='null')
    					
    					 	 $("#company_address_id").val($(this).attr("company_address_id"));
    					  else
    					  	 $("#company_address_id").val('');	
    					
    						
    				  $("#customer-name").val($(this).text());
    				  $("#ajax_response").fadeOut('slow');
    				  $("#ajax_response").html("");
    				
    			});
    			
    		});
    		   var product_name=$("#product_name").val();
    		  	var product_code_id=$("#product_code_id").val();
    		    if(product_code_id == '-1')
    		   {
    		   		$("#product_name").removeAttr("readonly", "readonly");
    				$("#product_div").show();
    		   }
    		   else if(product_name=='')
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
    });
    
    
    $("#invoiceno").change(function() {
          var checknum=$(this).val();
    	  if(checknum==0)
    	  {
    		  alert("Please Enter Valid Number");
    		  $("#invoiceno").val(""); 
    	  }
          if(checknum!=''){
    		var check_invoice_no = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=checkInvoiceNo'.$add_url, '',1);?>");
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
    	$("#smail").modal("show");
    	$("#invoice_product_id_model").val(invoice_product_id);
    	$("#invoice_id_model").val(invoice_id);
    }
    $(document).ready(function() {
    	 //$("#invoicedate").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide'); getgst();});
    	 $("#orderdate,#invoicedate").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
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
    	var country_final=$("#country_final").val();
    	var amt_max=$("#amt_maxico").val();
    		if(amt_max == '')
    		{
    			var amt_maxico = 0;
    		}
    		else
    		{
    			var amt_maxico = $("#amt_maxico").val();
    		}
    		var tax = $("#tax_maxico").val();
    		var total_amt_mexico_paid = $("#payment_terms_maxico").val();
    		var total_amt=total_amt_mexico_paid;
    		//alert(amt_maxico+'==='+total_amt);
    		if(parseFloat(amt_maxico) <= parseFloat(total_amt))
    		{
    	
    			$.ajax({
    				url : update_invoice_url,
    				method : 'post',		
    				data : {formData : formData},
    				success: function(response){
    			//	alert(response);
    					if(response != 0){
    						var url =  getUrl("<?php echo $obj_general->link($rout, '&mod=index&is_delete='.$_GET['is_delete'].$add_url, '',1); ?>");
    					//	if($_SESSION['LOGIN_USER_TYPE']!='4' && $_SESSION['ADMIN_LOGIN_SWISS']!='44')
    						    var redirect = setTimeout(function(){window.location = url; 	}, 800);				
    						//window.location = "<?php //echo $obj_general->link($rout, '', '',1); ?>";
    					}
    					 set_alert_message('Invoice Record successfully updated ','alert-success','fa fa-check');
    				},
    				error: function(){		
    					return false;
    				}
    			});
    		
    		}
    		else
    		{
    					alert("Your Advance Amount is More");
    					$("#amt_maxico").val("");
    					
    		}
    		
    });
    function displaygenerate()
    {
    	var edit=$("#edit").val();
    	if(edit=='')
    	{
    		$("#generate_invoice").show();
    		$("#inv_payment").show();
    	}
    	else
    	{
    		$("#generate_invoice").hide();
    		$("#inv_payment").hide();
    	}
    }
    
    /*$("input[name*='amount_paid']").change(function(){
    	var edit=$("#edit").val();
    	if(edit=='')
    		$("#generate_invoice").show();
    	else
    		$("#generate_invoice").hide();
    });
    *///var value_input = $("input[name*='xxxx']").val();
    
    $("#btn_save").click(function(){
    	var p_id = [];
    	$('input[name="prd_code_id[]"]').each(function(){
     		
    		p_id = $(this).val();
    		//alert(p_id);
    		var product_code_id=$("#product_code_id").val();
    		//alert(product_code_id);
    		if(product_code_id == p_id)
    		{
    			alert("You already added this product");
    			$("#product_keyword").val('');
    			$("#product_div").hide();
    			$("#qty").val('');
    			$("#rem_qty").val('');
    			$("#rate").val('');
    			return false;
    		}
    		
    		
    	});
    	
        if($("#form").validationEngine('validate')){ 
        		//alert("")
        			var add_product_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=addInvoice', '',1);?>");
        				var formData = $("#form").serialize();
        				var qty=$("#qty").val();
        				var rate=$("#rate").val();
        				
        				$.ajax({
        					url : add_product_url,
        					method : 'post',		
        					data : {formData : formData},
        					success: function(response){
        						if(response != 0){
        							  $(".pro_div_hide").show();
        							 $(".productCd").show();
        							 $(".pro_table").show();
        							 $("#rem_qty").show();
        							$(".rem").show();
        							$(".fright").hide();
        							$('.sin_qty_lable').hide();
        							$('#sin_qty_div').hide();
        							$("#invoiceno").prop('disabled', true);
        							$("#region").prop('disabled', true);
        							$("#postal_code").prop('disabled',true);
        							$("#account_code").prop('disabled',true);
        							$("#sent").prop('disabled',true);
        							$("#invoice_status").prop('disabled',true);
        							$("#type").prop('disabled',true);
        							$("#tax_type").prop('disabled',true);
        							//$("#invoicedate").prop('disabled', true);
        							$("#ref_no").prop('disabled', true);
        							$("#buyersno").prop('disabled', true);
        							$("#country_final").prop('disabled', true);
        							$("#consignee").prop('disabled', true);
        							$("#portofload").prop('disabled', true);
        							
        							$("#vessel_name").prop('disabled', true);
        							$("#country_id").prop('disabled', true);
        							$("#payment_terms").prop('disabled', true);
        							$("#customer-name").prop('disabled', true);
        						//[kinjal] : comment on [1-12-2015 tue]
        							//$("#payment_type").prop('disabled', true);
        							//$("#amt_maxico").prop('disabled', true);
        							//$("#currency").prop('disabled', true);
        							//$("#detail_maxico").prop('disabled', true);
        						// end comment
        							$("#delivery_info").prop('disabled', true);
        							$("#orderdate").prop('disabled', true);
        							$("#email").prop('disabled', true);
        							//$("#sin_account_code").val('');
        							$("#sin_fright_charge").val('');
        							$("#qty").val('');
        							$("#rem_qty").val('');
        							$("#rate").val('');
        							$("#product_keyword").val('');
        							$("#product_div").hide();
        							$('#myTable tr').not(':eq(0)').not(':eq(0)').remove();
        							$("#invoice_results").html(response);
        							//alert($("#fright_product").val());
        							if($("#fright_product").val() == '0')
        								$("#btn_fright").hide();
        							$("#pedimento_mexico").val('');	
        							var cal_inv_amt= $("#new_inv_amt").val();
        							$("#payment_terms_maxico").val(cal_inv_amt);
        							
        							var invoice_no=$("#invoice_no").val();
        							//alert(invoice_no);
        							$("#invoiceno").val(invoice_no);
        							$("#invoiceno").show();
        							$("#invoice_no_label").show();
        						}
        					},
        					error: function(){
        						return false;
        					}
        				});
        		
        		}
    		else {			
    			return false;
    		}
    		
    });
    
    function add_invoices() {
    		var qty=$("#qty").val();
    		var rate=$("#rate").val();
    		
    		var country_final=$("#country_final").val();
    		var payment_type=$("#payment_type").val();
    		//alert(payment_type);
    		//die;
    			var amt_max=$("#amt_maxico").val();
    			if(amt_max == '')
    			{
    				var amt_maxico = 0;
    			}
    			else
    			{
    				var amt_maxico = $("#amt_maxico").val();
    			}
    			//var amt_maxico=$("#amt_maxico").val();
    			var tax = $("#tax_maxico").val();
    			var total_amt_mexico_paid = $("#payment_terms_maxico").val();
    			var total_amt=total_amt_mexico_paid;
    		if(amt_maxico!='0' || (payment_type=='waiting' && amt_maxico=='0'))
    		{
    			if(parseFloat(amt_maxico) <= parseFloat(total_amt))
    			{ //alert("fgggg");
    				
    				
    				$("#product_code_id").prop('disabled', true);
    				$("#size").prop('disabled', true);
    				$("#color_0").prop('disabled', true);
    				$("#qty").prop('disabled', true);
    				$("#rate").prop('disabled', true);
    				$("#netweight").prop('disabled', true);
    				$("#measurement").prop('disabled', true);
    				$("#buyers_o_no").prop('disabled', true);
    				$("#net_weight_0").prop('disabled', true);
    				$("qty").removeClass("validate[required,custom[number],min[1]]");
    				$("qty").removeClass("validate[required,custom[number],min[0.001]]");
    				$("#item_no").prop('disabled', true);
    				var formData = $("#form").serialize();
    				
    				var update_invoice_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=generateInvoice', '',1);?>");
    				$.ajax({
    						url : update_invoice_url,
    						method : 'post',		
    						data : {formData : formData,country_final:country_final},
    						success: function(response){
    							//alert(response);
    							set_alert_message('Invoice successfully Added!',"alert-success","fa-check");
    							
    							window.location.href='<?php echo HTTP_SERVER; ?>/admin/index.php?route=sales_invoice&mod=view&invoice_no='+response+'&status=1+&is_delete=0';
    							
    						
    						}
    						
    					});
    				/*$( "#form" ).submit(function( event ) {
    					return true;
    				});*/
    			}
    			else
    			{
    				$("qty").removeClass("validate[required,custom[number],min[1]]");
    				$("qty").removeClass("validate[required,custom[number],min[0.001]]");
    				alert("Your Advance Amount is More");
    				$("#amt_maxico").val("");
    				/*$( "#form" ).submit(function( event ) {
    					return false;
    				});*/
    				
    			}
    		}
    		else
    		{
    			alert("Please Fill Your Payment Detail!!");
    		
    		}
    		
    		
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
    $(document).ready(function() {
    	 $("#ajax_return").fadeOut('slow');
    	 $("#ajax_return").html("");
    	 $("#product_keyword").focus();
    	var offset = $("#product_keyword").offset();
    	var width = $("#holder").width();
    	$("#ajax_return").css("width",width);
    	
    	$("#product_keyword").keyup(function(event){		
    		 var keyword = $("#product_keyword").val();
    		// alert(keyword);
    		 if(keyword == 'Cylinder' || keyword == 'cylinder' || keyword == 'CYLINDER')
    		 {	
    				
    				$("#product_code_id").val('-2');
    				//$("#product_id").val('');
    				//$("#real_product_name").val('Cylinder');
    				
    		 }
    		 else if(keyword == 'Custom' || keyword == 'custom' || keyword == 'CUSTOM')
    		 {	//alert(keyword);
    				//
    				$("#product_name").removeAttr("readonly", "readonly");
    				$("#product_div").show();
    				$("#product_code_id").val('-1');
    				//$("#color_txt").show();
    				//$("#color_product").val('Custom');
    				//$('#size').removeAttr("readonly", "readonly");
    			//	$('#measurement').removeAttr("readonly", "readonly");
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
    				   	//alert(msg);
    					   var msg = $.parseJSON(msg);
    					   var div='<ul class="list">';
    					   
    							if(msg.length>0)
    							{
    								for(var i=0;i<msg.length;i++)
    								{	// mansi (change for stock available quantity)
    									div =div+'<li><a href=\'javascript:void(0);\' discr="'+msg[i].description+'" id="'+msg[i].product_code_id+'" ><span class="bold" >'+msg[i].product_code+'</span></a></li>';
    									// remaining_qty="'+msg[i].remaining_qty+"' 	
    								}
    							}
    							
    							div=div+'</ul>';
    							if(msg != 0)
    							  $("#ajax_return").fadeIn("slow").html(div);
    							else
    							{
    							  $("#ajax_return").fadeIn("slow");	
    							  $("#ajax_return").html('<div style="text-align:left;">No Matches Found</div>');
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
    								$("#product_keyword").val($(".list li[class='selected'] a").text());
    								$("#product_div").show();
    								$("#product_name").val($(".list li[class='selected'] a").attr("discr"));
    								$("#product_code_id").val($(".list li[class='selected'] a").attr("id"));
    								
    								var product_code_id = $(".list li[class='selected'] a").attr("id");
    								getStockQty(product_code_id);
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
    								$("#product_keyword").val($(".list li[class='selected'] a").text());
    								$("#product_div").show();
    								$("#product_name").val($(".list li[class='selected'] a").attr("discr"));
    								$("#product_code_id").val($(".list li[class='selected'] a").attr("id"));
    							
    								var product_code_id = $(".list li[class='selected'] a").attr("id");
    								getStockQty(product_code_id);
    							}
    					 }
    					 break;				 
    					}
    				 }
    			 }
    			 else
    			 {
    				$("#ajax_return").fadeOut('slow');
    				$("#ajax_return").html("");
    			 }
    	});
    	$('#product_keyword').keydown( function(e) {
        if (e.keyCode == 9) {
    		 $("#ajax_return").fadeOut('slow');
    		 $("#ajax_return").html("");
        }
    	
    });
    	$("#ajax_return").mouseover(function(){
    			$(this).find(".list li a:first-child").mouseover(function () {
    					$("#product_div").show();
                      $("#product_name").val($(this).attr("discr"));
    				  $(this).addClass("selected");
    			});
    			$(this).find(".list li a:first-child").mouseout(function () {
    				  $(this).removeClass("selected");
    			});
    			$(this).find(".list li a:first-child").click(function () {
    				  $("#product_div").show();
                      $("#product_name").val($(this).attr("discr"));
    				  $("#product_code_id").val($(this).attr("id"));
    				
    					var product_code_id = $(this).attr("id");
    					getStockQty(product_code_id);
    				
    				  $("#product_keyword").val($(this).text());
    				  $("#ajax_return").fadeOut('slow');
    				  $("#ajax_return").html("");
    				});
    			
    		});
    	
    
    
    });
    
    function getStockQty(product_code_id)
    {
    	
    	var user_id = $("#user_id").val();
    	var u_type_id = $("#user_type_id").val();
    	var pro_no = $("#proforma_no").val();
    	if(user_id=='1' && u_type_id=='1')
    	{
    		$("#rem_qty").val(5000);
    		$("#inv_bal_qty").val(1000);
    	
    	}
    	else
    	{
    		var get_qty_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getStockQty', '',1);?>");
    		$.ajax({
    			url : get_qty_url,
    			method : 'post',
    			data : {product_code_id : product_code_id,pro_no:pro_no},
    			success: function(response){
    					//console.log(response);
    					var val =  $.parseJSON(response);
    					//alert(val);
    					$("#rem_qty").val(val.remaining_qty);
    					$("#inv_bal_qty").val(val.rem_qty_display);
    					if(val.tran_qty!='empty')
    						$("#transfer_qty").val(val.tran_qty);
    					var qty = $("#qty").val();
    				},
    			error: function(){
    				return false;	
    			}
    		});
    	}
    	
    }
    $("#same-above").click(function(){
    	$("#loading").show();
    	if($(this).prop('checked') == true){
    		var bill = $("#consignee").val();
    		$("#delivery_info").val(bill);
    		$("#billing-details").slideUp('slow');
    	}else{
    		var del = $("#del_info").val();
    		$("#delivery_info").val(del);
    		$("#billing-details").slideDown('slow');
    	}
    	$("#loading").fadeOut();
    });
    
    //mansi (payment button)
    $("#inv_payment").click(function(){
    
    	$(".payment_div").show();
    	$(".mexico_div").show();
    	$(".tax_div").show();
    	
    	$(".payment_label").show();
    	$("#btn_save").hide();
    });
    
    //kinjal [10-12-2015] make fun. for singapore fright charges
    $(document).ready(function(){
    	 $('#btn_fright').on('click', function(event) { 
    	 		
    			if($('.fright').css('display') == 'none' && $(".productCd").css('display') == 'block' && $("#rem_qty").css('display') == 'block' && $(".rem").css('display') == 'block')
    			{	
    				$('#product_name').val('Freight');
    				$('.fright').show();
    				 $(".productCd").hide();
    				 $(".pro_div_hide").hide();
    				 $("#rem_qty").hide();
    				  $("#inv_bal_qty").hide();
    				$(".rem").hide();
    				$(".pro_table").hide();
    				$('.sin_qty_lable').show();
    				$('#sin_qty_div').show();
    				$("#product_code_id").val("");
    			}
    			else
    			{	
    				$('#product_name').val('');
    				$('.fright').hide();
    				 $(".productCd").show();
    				  $(".pro_div_hide").show();
    				 $("#rem_qty").show();
    				$(".rem").show();
    				$(".pro_table").show();
    				$("#inv_bal_qty").show();
    				$('.sin_qty_lable').hide();
    				$('#sin_qty_div').hide();
    			}
    		});
    });
    function getinvoice_amt()
    {
    	var sin_account_code = $("#sin_account_code").val();
    	var sin_fright_charge = $("#sin_fright_charge").val();
    	//var sin_case_breaking_fees=$("#sin_case_breaking_fees");
    	var product_code_id = $("#product_code_id").val();
    	var tran_qty = $("#transfer_qty").val();
    	var product_keyword = $("#product_keyword").val();
    	//alert(product_keyword);
    	
    	if(sin_account_code != '' && sin_account_code != '0' && typeof sin_account_code != "undefined" && sin_fright_charge!='')
    	{
    		var stock_qty = '1';
    		var inv_bal_qty = '1';
    	}
    	else
    	{
    		var stock_qty = $("#rem_qty").val();
    		var inv_bal_qty = $("#inv_bal_qty").val();
    	}
    		
    	var qty = $("#qty").val();
    	var country_id = $("#country_maxico").val();
        var proforma_qty =$("#proforma_qty").val();
    	if(country_id == '14')
    	{
    		stock_qty1=stock_qty+tran_qty;
    	}
    	else
    	{
    		stock_qty1=stock_qty;
    	}
    	//if(parseInt(qty) <= parseInt(stock_qty) && parseInt(inv_bal_qty)!='0' && parseInt(inv_bal_qty)>0)
    	if((parseInt(qty) <= parseInt(stock_qty1)) || (product_code_id=='-1')  || (product_code_id=='1194') || (product_keyword.startsWith("CUST")) || (product_keyword.startsWith("LBL"))  || (product_keyword=='CPBB') || (product_code_id=='-2'))
    	{
    	}
    	else
    	{
    		alert("Sorry you can't insert qty more then Define Stock Qty & Please Check the Invetory Stock Availability!!");
    		$("#qty").val("");
    		return false;
    	}
    	var pro = ('#proforma_no').val();
    	if(pro!='')
    	{
        	if(parseInt(qty) <= parseInt(proforma_qty) && parseInt(proforma_qty)!='0' && parseInt(proforma_qty)>0)
        	{
        		//alert("hello");
        		
        	}else
        	{
        		alert("Sorry you can't insert qty more then Define Proforma Qty  Please Check the Proforma Qty !!");
        		$("#qty").val("");
        		return false;
        	}
    	}
    	
    }
    
    function getpayment_amt()
    {
    	var edit_amt = $("#payment_terms_maxico").val();
    	var payment_type=$("#payment_type").val();
    	
    	if(payment_type=='full')
    	{
    		$("#amt_maxico").val(edit_amt);
    	}
    	else
    	{
    		var amt_max = $("#amt_maxico").val();
    		if(amt_max !='')
    			$("#amt_maxico").val(amt_max);
    		else
    			$("#amt_maxico").val('');
    		
    	}
    	//$("input[name*='amount_paid']").change();
    }
    
    function gettaxinvoice()
    {
    	var inv_id_sc = $("#invoice_id_max").val();
    	if(inv_id_sc == '')
    	{
    		
    		var inv_id = $("#invoice_id_max_sec").val();
    	}
    	else
    	{
    		var inv_id = inv_id_sc;
    	}
    	
    	
    	var tax = $("#tax_maxico").val();
    	var inv_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=gettaxinvoice', '',1);?>");
    	 $("#loading").css("visibility","visible");
    	 $.ajax({
    	   type: "POST",
    	   url: inv_url,
    	   data:{inv_id:inv_id,tax:tax} ,
    	   success: function(response){
    	   		$("#payment_terms_maxico").val(Math.round(response));
    	   		
    	  	 }
    	   
    	   });
    	
    }
    function deleteinvoice()
    {
    	var form_data = $("#model_form").serialize();
    	var invoice_product_id = $("#invoice_product_id_model").val();
    	var invoice_id_model = $("#invoice_id_encode").val();
    	//alert(invoice_id_model);
    	var inv_rate = $("#invoice_rate_"+invoice_product_id).val();
    	var inv_qty= $("#invoice_qty_"+invoice_product_id).val();
    	var p_code = $("#invoice_pcode_"+invoice_product_id).val();
    	var tax_maxico= $("#tax_maxico").val();
    	var payment_terms_maxico = $("#payment_terms_maxico").val();
    	
    	
    	/*if(p_code != '0')
    	{*/
    		var tax_maxico= $("#tax_maxico").val();
    		var payment_terms_maxico = $("#payment_terms_maxico").val();
    		var amt = inv_qty * inv_rate;
    		var invoice_amt = (amt + ((amt * tax_maxico )/100));
    		//alert(amt+'+(('+amt+'*'+tax_maxico+')/100)');
    		var pay_new_amt = parseInt(payment_terms_maxico)-parseInt(invoice_amt);
    		$("#payment_terms_maxico").val(Math.round(pay_new_amt));
    	//}
    	
    		var remove_invoice_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=removeInvoice', '',1);?>");
    			$.ajax({
    				url : remove_invoice_url,
    				method : 'post',
    				data : {form_data : form_data,tax_maxico:tax_maxico,payment_terms_maxico:payment_terms_maxico,inv_rate:inv_rate,inv_qty:inv_qty,p_code:p_code},
    				success: function(response){
    					//alert(response);
    						$("#smail").modal("hide");
    						$('#invoice_product_id_'+invoice_product_id).html('');
    						set_alert_message('Invoice Record successfully deleted','alert-success','fa fa-check');
    						window.location.href='<?php echo HTTP_SERVER; ?>/admin/index.php?route=sales_invoice&mod=add&invoice_no='+invoice_id_model+'&is_delete=0';
    					},
    				error: function(){
    					return false;	
    				}
    			});
    						
    }
    function getgst()
    {
    	var final_country=$("#country_final").val();
    	//alert(final_country);
    	var invoice_date=$("#invoicedate").val();
    	var utcdate='2016-02-01';	
    	var country_id=$("#country_id").val();
    	var gst_val=$("#gst_val").val();
    	//alert(country_id);
    	if(final_country=='214' && country_id=='214' )
    	{
    		if(invoice_date!='' && invoice_date < utcdate)
    		{
    			$("#tax_maxico").removeAttr('readonly','readonly');
    			$("#tax_maxico").val('0');
    			$("#gst").val('0');
    			$("#tax_maxico").attr('readonly','readonly');
    		}
    		else
    		{
    			$("#tax_maxico").val(gst_val);
    			$("#gst").val(gst_val);
    		}	
    		
    	}
    	else if(final_country=='155' && country_id=='155' )
    	{
    		$("#tax_maxico").val(gst_val);
    		$("#gst").val(gst_val);
    	}
    	else if(final_country=='14' && country_id=='14' )
    	{
    		$("#tax_maxico").val(gst_val);
    		$("#gst").val(gst_val);
    		
    	}	
    	else
    	{
    		$("#tax_maxico").removeAttr('readonly','readonly');
    		$("#tax_maxico").val('0');
    		$("#gst").val('0');
    		$("#tax_maxico").attr('readonly','readonly');
    		
    	
    	}
    		
    
    }
    $("#country_id").change(function()
    {
    	getgst();
    });
    //[kinjal] (30-5-2016) for getting product data
    /*function addProformaSales()
    {
    	var country_final=$("#country_final").val();
    	var formData = $("#form").serialize();
    		//alert(formData);	
    			var insert_url = getUrl("<?php //echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getProformaProductData', '',1);?>");
    			$.ajax({
    					url : insert_url,
    					method : 'post',		
    					data : {formData : formData,country_final:country_final},
    					success: function(response){
    						
    						//alert(response);
    						//set_alert_message('Invoice successfully Added!',"alert-success","fa-check");
    						
    						//window.location.href='<?php //echo HTTP_SERVER; ?>/admin/index.php?route=sales_invoice&mod=view&invoice_no='+response+'&status=1&is_delete=0';
    							//location.replace(<?php //echo HTTP_SERVER; ?>/admin/index.php?route=sales_invoice&mod=add&invoice_no='+response+'&is_delete=0);
    					
    					}
    					
    				});
    }*/
    //[kinjal] : (8-6-2016) for get tax value		
    $( "#state" ).change(function() {
    
    	var state_id = $(this).val();
    	//alert(state_id);
    	var state_detail = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=state_detail', '',1);?>");
    
    		$.ajax({
    
    				url : state_detail,
    
    				method : 'post',
    
    				data : {state_id : state_id},
    
    				success: function(response){
    					//alert(response);
    					var msg = $.parseJSON(response);
    				
    					if((msg.gst!= '0.000' || msg.rst!= '0.000') && msg.hst=='0.000')
    					{
    
    						$("#can_gst").val(msg.gst);
    
    						$("#pst").val(msg.rst);
    
    						$("#gst_div").show();
    
    						$("#pst_div").show();
    
    						$("#hst_div").hide();
    
    						$("#hst").val('');
    					}
    					else if(msg.hst!= '0.000' && (msg.gst== '0.000' && msg.rst== '0.000'))
    					{	
    
    						$("#hst").val(msg.hst);
    
    						$("#hst_div").show();
    
    						$("#gst_div").hide();
    
    
    						$("#pst_div").hide();
    
    						$("#can_gst").val('');
    
    						$("#pst").val('');
    
    					}
    
    					else if(msg.gst== '0.000' && msg.rst== '0.000')
    
    					{	
    
    						$("#can_gst").val(msg.gst);
    
    						$("#pst").val(msg.rst);
    
    						$("#gst_div").show();
    
    						$("#pst_div").show();
    
    						$("#hst_div").hide();
    
    						$("#hst").val('');
    
    					}
    
    
    				},
    
    				error: function()
    				{
    					return false;	
    				}
    
    		});
    
    	
    
    });
    function check_invoice()
    {
    	var purchase_no=$("#purchase_no").val();
    	var invoice_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=check_purchaseno', '',1);?>");
    			$.ajax({
    				url : invoice_url,
    				method : 'post',
    				data : {purchase_no : purchase_no},
    				success: function(response){
    						 if(response == ''){
    								 alert('Please Enter the Proper Purchase Invoice No Or Generate the purchase Invoice'); 
    								 $("#purchase_no").val("");	
    							}
    												
    					},
    				error: function(){
    					return false;	
    				}
    			});
    	
    }
    function check_proforma()
    {
    	var proforma_no=$("#proforma_no").val();
    	var invoice_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=check_proforma', '',1);?>");
    			$.ajax({
    				url : invoice_url,
    				method : 'post',
    				data : {proforma_no : proforma_no},
    				success: function(response){
    						 if(response == ''){
    								 alert('Please Enter the Proper Proforma Invoice No Or Generate the proforma Invoice'); 
    								 $("#proforma_no").val("");	
    							}
    												
    					},
    				error: function(){
    					return false;	
    				}
    			});
    	
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
    				  $("#ajax_return").fadeIn("slow").html(div);
    				else
    				{
    				  $("#ajax_return").fadeIn("slow");	
    				  $("#ajax_return").html('<div style="text-align:left;">No Matches Found</div>');
    				}
    				$("#loading").css("visibility","hidden");
    			   }
    		});
    	
    }
    function gen_invoice(data_val) {
      /*var data_val = $(this).attr('data-value');*/ // gets innerHTML of clicked li
      var sales_invoice_id =<?php echo $invoice_id; ?>;
      var rout = '<?php echo $rout;?>';
        //$("#btn_update").click();
        var editor = CKEDITOR.instances.message;
        if (editor) {
            editor.destroy(true); 
        } 
        
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
    	var country_final=$("#country_final").val();
    	var amt_max=$("#amt_maxico").val();
    		if(amt_max == '')
    		{
    			var amt_maxico = 0;
    		}
    		else
    		{
    			var amt_maxico = $("#amt_maxico").val();
    		}
    		var tax = $("#tax_maxico").val();
    		var total_amt_mexico_paid = $("#payment_terms_maxico").val();
    		var total_amt=total_amt_mexico_paid;
    		//alert(amt_maxico+'==='+total_amt);
    		if(parseFloat(amt_maxico) <= parseFloat(total_amt))
    		{
    	        $.ajax({
    				url : update_invoice_url,
    				method : 'post',		
    				data : {formData : formData},
    				success: function(response){
            		        if(response != 0){
            					if(data_val=='download'){
                                    window.open('<?php echo HTTP_SERVER.'pdf/salesinvoicepdf.php?mod='.encode('salesinvoice').'&token='.$_GET['invoice_no'].'&status=1&ext='.md5('php').'&n=0';?>', '_blank'); 
                                }     
                                else if(data_val=='dispatch'){
                                    //window.location.href='<?php echo HTTP_SERVER; ?>admin/index.php?route=proforma_invoice_product_code_wise&mod=dis_stock&invoice_no='+sales_invoice_id+'&is_delete=0';
                                    window.location.href='<?php echo HTTP_SERVER; ?>admin/index.php?route=rack_master';
                                }
                                else if(data_val=='courier'){
                                    //window.location.href='<?php echo HTTP_SERVER; ?>admin/index.php?route=proforma_invoice_product_code_wise&mod=dis_stock&invoice_no='+sales_invoice_id+'&is_delete=0';
                                    setTimeout(function(){window.location = getUrl('<?php echo $obj_general->link($rout, '&mod=add_courier&is_delete=0&invoice_no='.$_GET['invoice_no'], '',1); ?>');}, 800);
								}
                                else if(data_val=='email')
                                {
                                  $("#customer").html('Sending Mail To :- '+$("#customer-name").val());
                                  $("#subject").val((' Invoice - '+$("#invoiceno").val()+' - Customer Company- '+$("#customer-name").val()));
                                  $("#message").html('<p> Hello '+$("#customer-name").val()+',</p><p> Please find attached sales invoice for your reference.</p><p> Do you let us know if any more  questions. </p><p>Thanks  for your business .</p>');   
                        
                                  $("#toemail").val($("#email").val());
                                  $("#sales_invoice_send").val(sales_invoice_id);
                                  $("#emailform").val('<?php echo $addedByInfo['email'];?>');      
                        
                        
                                CKEDITOR.replace('message', {
                                    toolbar: [ 
                                      { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'] },
                                      { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv','-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl']},
                                      { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
                                      { name: 'colors', items: ['TextColor', 'BGColor'] }]});
                                  $("#email_div").modal("show");
                                }
                                
                                if(data_val=='download' || data_val=='close')
                                    var redirect=setTimeout(function(){window.location = getUrl('<?php echo $obj_general->link($rout, '&mod=index&is_delete=0', '',1); ?>');}, 800);
                            						//var url =  getUrl("<?php echo $obj_general->link($rout, '&mod=index&is_delete='.$_GET['is_delete'].$add_url, '',1); ?>");
                                						//var redirect = setTimeout(function(){window.location = url; 	}, 800);				
                            }
    					    set_alert_message('Invoice Record successfully updated ','alert-success','fa fa-check');
    				},
    				error: function(){		
    					return false;
    				}
    			});
    		
    		}
    		else
    		{
    			alert("Your Advance Amount is More");
    			$("#amt_maxico").val("");
    					
    		}
    		
    }
    //allow_currency
    </script> 
    <?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>