<?php
include("mode_setting.php");
//Start : bradcums
$bradcums = array();
$bradcums[] = array(
  'text'  => 'Dashboard',
  'href'  => $obj_general->link('dashboard', '', '',1),
  'icon'  => 'fa-home',
  'class' => '',
);

$bradcums[] = array(
  'text'  => $display_name.' List',
  'href'  => $obj_general->link($rout, 'mod=index&inv_status='.$_GET['inv_status'], '',1),
  'icon'  => 'fa-list',
  'class' => '',
);

$bradcums[] = array(
  'text'  => $display_name.' Detail',
  'href'  => '',
  'icon'  => 'fa-edit',
  'class' => 'active',
);
//Close : bradcums

//edit user
$edit = '';
if(isset($_GET['invoice_no']) && !empty($_GET['invoice_no'])){  
    $invoice_no = decode($_GET['invoice_no']);
    //echo $invoice_no;
    $invoice = $obj_invoice->getInvoiceData($invoice_no);
   // printr($invoice);
    $invoice_id = $invoice['invoice_id'];
    $edit = 1;
}

//if(isset($_GET['invoice_no'])&&!empty($_GET['invoice_no']))
//$inv_status = $_GET['inv_status'];
//$invoice = $_GET['invoice_no'];
//printr($invoice );

//printr($inv_status );//$stock_o_no = $custom_o_no = '';
if(isset($_GET['user_id']) && !empty($_GET['user_id']))
{
  $decode_user_id = decode($_GET['user_id']);
  $ex_id=explode("=",$decode_user_id);
  $orders_user_id = $ex_id[1];
  $orders_user_type_id = $ex_id[0];
  
  $user_data_of_order = $obj_invoice->userOrderData($orders_user_id,$orders_user_type_id);
  $stock_o_no = $user_data_of_order['stock_order_no'];
  $custom_o_no = $user_data_of_order['custom_order_no'];
} 
if(isset($_GET['invoice_product_id']) && !empty($_GET['invoice_product_id'])){
  $invoice_product_id = decode($_GET['invoice_product_id']);
  $invoice_product = $obj_invoice->getInvoiceProductId($invoice_product_id);
  $invoice_color_details = $obj_invoice->getInvoiceProductColorId($invoice_product_id);
  $product_code_details = $obj_invoice->product_code_details($invoice_product['product_code_id']);
}
if(isset($_POST['generate_invoice'])){
  
  //printr($_POST);die;
  //$obj_invoice->addInInvoice($_POST['invoice_id']);
  //$obj_invoice->addOutInvoice($_POST['invoice_id']);
  //die;
  //[kinjal] : [29-8-2016] for direct generate by stock and custom order num.
    $insert_id = $obj_invoice->addInvoiceData($_POST);
  
  //uncomment this code after completing all insertion process [kinjal]
  /*$url = HTTP_SERVER.'pdf/invoicepdf.php?mod='.encode('invoice').'&token='.encode($_POST['invoice_id']).'&status='.rawurlencode($_POST['status']).'&ext='.md5('php');
  $obj_invoice->sendInvoiceEmail($_POST['invoice_id'],$_POST['status'],'', $url);

  $invoice_data = $obj_invoice->getInvoiceData($_POST['invoice_id']);
  $addedByinfo=$obj_invoice->getUser($invoice_data['user_id'],$invoice_data['user_type_id']);
  $obj_session->data['success'] = 'Invoice successfully Added!';
  page_redirect($obj_general->link($rout, '&mod=view&invoice_no='.encode($_POST['invoice_id']).'&status=1', '',1));*/
  
}
if($display_status){  
$city= $obj_invoice->getCityName();
$countries = $obj_invoice->getCountry();
$measurement = $obj_invoice->getMeasurement();
$colors = $obj_invoice->getColor();
$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
//printr($user_type_id.'====='.$user_id);
//$userCurrency = $obj_invoice->getUserCurrencyInfo($user_type_id,$user_id);
$addedByInfo = $obj_invoice->getUser($user_id,$user_type_id);
//printr($addedByInfo);
if($invoice['order_user_id']!='')
{
  $ibInfo = $obj_invoice->getUser($invoice['order_user_id'],'4');
  $addedByInfo['gst']=$ibInfo['gst'];
  $addedByInfo['company_address']=$ibInfo['company_address'];
  $addedByInfo['bank_address']=$ibInfo['bank_address'];
}
if(isset($_POST['change_price']))
{
  $post = post($_POST);   
  //($post);//die;
  $change = $obj_invoice->change_price($post);
  
  $obj_session->data['success'] = UPDATE;
  page_redirect($obj_general->link($rout, '&mod=add&invoice_no='.$_GET['invoice_no'].'&inv_status=1', '',1));
}
//printr($invoice);
?>

<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-user"></i> User</h4>
    </div>
    <div class="row">
      <div class="col-lg-12">
         <?php include("common/breadcrumb.php");?>  
        </div>
        <div class="col-sm-12">
          <section class="panel">
            <header class="panel-heading bg-white"> Invoice Detail </header>
            <div class="panel-body">
              <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
                <div id="invoice_div" class="table-responsive">
                    <div class="form-group">
                     <label class="col-lg-3 control-label"><span class="required">*</span>Invoice No</label>
                    <div class="col-lg-3">
              <input type="hidden" name="generate_status" id="generate_status" value="<?php echo isset($invoice) ? $invoice['generate_status'] : '' ; ?>"  >                        
              <input type="hidden" name="edit" id="edit" value="<?php echo $edit ; ?>"  >                        
                          <input type="hidden" name="in_no" id="in_no" value="<?php echo isset($invoice_no) ? $invoice_no : '' ; ?>"  >
                          <input type="hidden" name="gst" id="gst" value="<?php echo isset($issetaddedByInfo['gst']) ? $addedByInfo['gst'] : '' ; ?>"  >
                          <input type="hidden" name="company_address" id="company_address" value="<?php echo isset($addedByInfo['company_address']) ? $addedByInfo['company_address'] : '' ; ?>"  >
                          <input type="hidden" name="bank_address" id="bank_address" value="<?php echo isset($addedByInfo['bank_address']) ? $addedByInfo['bank_address'] : '' ; ?>"  >
                       <input type="text" name="invoiceno" id="invoiceno" value="<?php echo isset($invoice) ? $invoice['invoice_no'] : '' ; ?>"  class="form-control" <?php /*?> <?php if(isset($_GET['invoice_no'])) echo 'disabled="disabled"'; ?><?php */?>>
                            <input type="hidden" name="generate_status" id="generate_status" value="<?php echo isset($invoice) ? $invoice['generate_status'] : '' ; ?>" />
                            <input type="hidden" name="admin_email" id="admin_email" value="<?php echo ADMIN_EMAIL ; ?>" />
                            <input type="hidden" name="inv_status" id="inv_status" value="<?php echo isset($_GET['inv_status']) ? $_GET['inv_status'] : '' ; ?>"  >
                    </div>
                    <label class="col-lg-3 control-label">Invoice Date</label>
                    <div class="col-lg-3">
                      <input type="text" name="invoicedate" readonly data-date-format="yyyy-mm-dd" value="<?php echo isset($invoice) ? $invoice['invoice_date'] : date("Y-m-d") ; ?>" placeholder="Invoice Date" id="invoicedate" class="input-sm form-control datepicker" />
                     </div>
                 </div>
                    
                <div class="form-group">
                    
                    <label class="col-lg-3 control-label"><span class="required">*</span>Exporter's order/Ref No</label>
                    <div class="col-lg-3">
                      <input type="text" name="ref_no" id="ref_no" value="<?php echo isset($invoice) ? $invoice['exporter_orderno'] : '' ; ?>" placeholder="Exporter's orderno" class="form-control">
                    </div>
                    
                    <label class="col-lg-3 control-label"><span class="required">*</span>Buyer's Order/Ref No</label>
                  <div class="col-lg-3">
                       <input type="text" name="buyersno" id="buyersno" value="<?php echo isset($invoice) ? $invoice['buyers_orderno'] : '' ; ?>"  class="form-control validate[required]">
                    </div>
                 </div>
                      <?php if($addedByInfo['country_id']=='111'){?>
                <div class="form-group">
                    <label class="col-lg-3 control-label">Other Reference(s)</label>
                    <div class="col-lg-3">
                     <input type="text" name="other_ref" id="other_ref" value="<?php echo isset($invoice) ? $invoice['other_ref'] : '' ; ?>"  class="form-control validate">
                    </div>
                           <label class="col-lg-3 control-label"><span class="required">*</span>Place of Receipt By Pre-Carrier</label>
                     <div class="col-lg-3">
                         <select name="pre_carrier" id="pre_carrier" class="form-control validate[required]" >
                          <option value="">Select City Name</option>
                           <?php foreach($city as $cty) { 
                  $carrier_id= isset($invoice['pre_carrier'])?$invoice['pre_carrier']:'3';
                ?>
                                <?php if($carrier_id==$cty['invoice_city_id'] ){ ?>
                                    <option value="<?php echo $cty['invoice_city_id']; ?>" selected="selected"><?php echo $cty['city_name']; ?></option>
                                 <?php } else { ?>
                                    <option value="<?php echo $cty['invoice_city_id']; ?>" ><?php echo $cty['city_name']; ?></option>
                                 <?php } ?>
                            <?php } ?>
                         </select>
                    </div>
                </div> 
                         <div class="form-group">
                            <label class="col-lg-3 control-label">UK Reference NO</label>
                                <div class="col-lg-3">
                                   <input type="text" name="uk_ref_no" id="uk_ref_no" value="<?php echo isset($invoice) ? $invoice['uk_ref_no'] : '' ; ?>"  class="form-control ">
                                </div>
                            
                            <!-- kavita add 16-8-2017-->
                  <label class="col-lg-3 control-label">Invoice Title</label>
                    <div class="col-lg-3">
                      <input type="text" name="invoice_title" id="invoice_title" value="<?php echo isset($invoice) ? $invoice['invoice_title'] : '' ; ?>"  class="form-control ">
                    </div>
                   <!-- end-->
                            </div>         
                  <?php }?>    
                    <div class="form-group">
                    <label class="col-lg-3 control-label"><span class="required">*</span>Customer Name</label>
                    <div class="col-lg-3">
                        <input type="text" name="customer_name"  value="<?php echo isset($invoice) ? $invoice['customer_name'] : '' ; ?>" placeholder="Customer Name" id="customer-name" class="form-control validate[required]" />
                    </div>
                     <label class="col-lg-3 control-label">Email</label>
                    <div class="col-lg-3">
                      <input type="text" name="email" id="email" value="<?php echo isset($invoice) ? $invoice['email'] : '' ; ?>" placeholder="Email"  class="form-control validate[required,custom[email]]">
                    </div>
                  </div>
                    <div class="form-group">
                <label class="col-lg-3 control-label"> <span class="required">*</span>  <?php if($addedByInfo['country_id']=='111') echo 'Consignee'; else echo 'Cutomer Address';?></label>
                <div class="col-lg-8">
                <textarea class="form-control validate[required]" rows="2" cols="45" name="consignee" id="consignee"><?php echo isset($invoice) ? $invoice['consignee'] : '' ;?></textarea>
                </div>
              </div>
              <?php if($addedByInfo['country_id']=='111') {?>
                    <div class="form-group">
                <label class="col-lg-3 control-label">Buyer(If other than consignee)</label>
                <div class="col-lg-8">
                 <textarea class="form-control" id="other_buyer" name="other_buyer"><?php echo isset($invoice) ? $invoice['buyer'] : '' ; ?></textarea>
                </div>
              </div>
              <?php }?>
                  <div class="form-group">
                  <label class="col-lg-3 control-label">Country of Final Destination </label>
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
                    <label class="col-lg-3 control-label"><span class="required">*</span>Port of Loading</label>
                    <div class="col-lg-3">
                      <select name="portofload" id="portofload" class="form-control validate[required]" >
                        <option value="">Select City Name</option>
                       <?php  foreach($city as $cty) { ?>
                <?php if($invoice['port_load']==$cty['invoice_city_id']){ ?>
                                    <option value="<?php echo $cty['invoice_city_id']; ?>" selected="selected"><?php echo $cty['city_name']; ?></option>
                                 <?php } else { ?>
                                    <option value="<?php echo $cty['invoice_city_id']; ?>"><?php echo $cty['city_name']; ?></option>
                                 <?php }
                  } ?>
                      </select>
                    </div> 
                         <?php if($addedByInfo['country_id']=='111') {?>
                    <label class="col-lg-3 control-label"><span class="required">*</span>Port of Discharge </label>
                    <div class="col-lg-3"> 
                      <?php if(isset($_GET['invoice_no']) && (decode($invoice['transportation']) == 'sea')) { ?>
                       
                       <input type="text" name="port_of_dis" id="port_of_dis" value="<?php echo isset($invoice['port_discharge'])?$invoice['port_discharge']:'' ; ?>" placeholder="Port of Discharge" class="form-control validate[required]" />
              
              <?php } else
               {?>
              
              
              <select name="port_of_dis" id="port_of_dis" class="form-control validate[required]" >
                        <option value="">Select Country</option>
                          <?php $sel_port_country= isset($invoice['port_discharge'])?$invoice['port_discharge']:'';
                      foreach($countries as $country){
                                    if($sel_port_country && $sel_port_country == $country['country_id']){
                                        echo '<option value="'.$country['country_id'].'" selected="selected" >'.$country['country_name'].'</option>';
                                    }else{
                                        echo '<option value="'.$country['country_id'].'">'.$country['country_name'].'</option>';
                                    }
                                } ?>
                           </select>
                            <?php } ?>
                    </div>
                           <?php }?>
                  </div> 
                 
                    <div class="form-group">
                   <label class="col-lg-3 control-label">Final Destination</label>
                    <div class="col-lg-3">
                             <?php if(isset($_GET['invoice_no']) && (decode($invoice['transportation']) == 'sea')) { ?>
                                <input type="text" name="country_id" id="country_id" value="<?php echo isset($invoice['final_destination'])?$invoice['final_destination']:'' ; ?>" placeholder="Final Destination" class="form-control validate[required]" />
                           
                           <?php } else
               {?>
              
                            <?php if(isset($addedByInfo['country_id']) && $addedByInfo['country_id']){
                  $selCountry = $addedByInfo['country_id'];
                }
                $sel_country = isset($invoice['final_destination'])?$invoice['final_destination']:$addedByInfo['country_id'];   
                $countrys = $obj_general->getCountryCombo($sel_country);
                echo $countrys;
              ?>
              <?php }?>
                    </div>
                   <label class="col-lg-3 control-label"><span class="required">*</span>Payment Terms</label>
                   <div class="col-lg-3">
                     <input type="text" name="payment_terms" id="payment_terms" value="<?php echo isset($invoice) ? $invoice['payment_terms'] : '' ; ?>" placeholder="Payment terms" class="form-control validate[required]" />
                   </div>
              </div>
              
                 <div class="form-group">
                       <label class="col-lg-3 control-label"><b style="color:#ff0000"> Do you want to Add IGST In Invoice ?</b></label>
                        <div class="col-lg-4">
                                 <select name="igst_status" id="igst_status" class="form-control validate[required]" >
                                    <option value="">Select IGST</option>
                                    <option value="1" <?php if(isset($invoice) &&  $invoice['igst_status']=='1') { ?> selected="selected" <?php } ?>>Yes</option>
                                    <option value="0" >No</option>
                                </select>
                         </div>
                 </div>
                 <div class="form-group">
                       <label class="col-lg-3 control-label"><b style="color:#ff0000">Freight Charges  In Invoice ?</b></label>
                        <div class="col-lg-4">
                                 <select name="air_f_status" id="air_f_status" class="form-control validate[required]" >
                                    <option value="0">Pouches</option>
                                    <option value="1">SCOOPS</option>
                                    <option value="2" >ROLL</option>
                                    <option value="3" >MAILER BAGS</option>
                                    <option value="4" >SEALER MACHINE</option>
                                    <option value="5" >STOREZO</option>
                                    <option value="6" >PAPER BOX</option>
                                    <option value="7" >PLASTIC DISPOSABLE LID / CONTAINER</option>
                                    <option value="8" >PLASTIC GLASSES</option>
                                    <option value="10" >H/B CHAIR</option>
                                    <option value="11" >Silica Gel</option>
                                    <option value="12" >Oxigen absorbers</option>
                                    <option value="2" >PLASTIC CAP</option>
                                </select>
                         </div>
                 </div>    
                 <div class="form-group">  
                       <label class="col-lg-3 control-label"><b style="color:#002bff">Courier Name</b></label>
                        <div class="col-lg-4">
                         <?php $couriers = $obj_invoice->getCouriers();?>
                                 <select name="courier_id" id="courier_id" class="form-control " >
                                        <option value="0">Select Courier</option>
                                      <?php foreach($couriers as $courier){
                                   if($invoice['courier_id']==$courier['courier_id']){ 
                                        echo '<option value="'.$courier['courier_id'].'" selected="selected" >'.$courier['courier_name'].'</option>';
                                    }else{
                                        echo '<option value="'.$courier['courier_id'].'">'.$courier['courier_name'].'</option>';
                                    }
                                } ?>
                                </select>
                         </div>
                 </div> 
              
              
              
              
              
           
              	<div class="form-group">
                    	 <label class="col-lg-3 control-label"><span class="required">*</span>Do you want to dispatch order directly to customer?</label>
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
                
                     
                    <!-- start taxation -->
                    
                    	<div class="form-group">
                    	 <label class="col-lg-3 control-label"><span class="required">*</span>Do you want to Add Wooden Pallet in Invoice ?</label>
                          <div class="btn-group" data-toggle="buttons">
						<?php 
							$classy1='';
                            $checky1=''; 
                            $classn1='';
                            $checkn1='';
                        if(isset($invoice['show_pallet']) && $invoice['show_pallet'] == 1 ){
                            $classy1='active'; 
                            $checky1='checked="checked" ';
                        }
                        else
                        {
                            $classn1='active';
                            $checkn1='checked="checked" ';
                        }?>
                        <label class="btn btn-sm btn-white btn-on <?php echo $classy1;?>">
                          <input type="radio" name="show_pallet" id="show_pallet" value="1" <?php echo $checky1;?>>
                          Yes </label>
                        <label class="btn btn-sm btn-white btn-off <?php echo $classn1;?>">
                          <input type="radio" name="show_pallet" id="show_pallet" value="0" <?php echo $checkn1;?>>
                          No </label>
                      
                      </div>
                    </div>
                    <div class="form-group" id="tax_type" style="display:none;"> 
                        <label class="col-lg-3 control-label">Tax</label>
                        <div class="col-lg-8">
                            <div>
                                <label  style="font-weight: normal;">
                                <input type="radio" name="tax_mode" id="tax_mode" value="normal" checked="checked" <?php if(isset($invoice) && $invoice['tax_mode'] == 'normal') echo 'checked="checked"'; ?> > Normal </label>
                            </div>
                            <div>
                                <label style="font-weight: normal;">
                                <input type="radio" name="tax_mode" id="tax_mode" value="form" <?php if(isset($invoice) && $invoice['tax_mode'] == 'form') echo 'checked="checked"'; ?> > Form </label>
                            </div>
                        </div>
                     </div>
           <?php if(isset($invoice) && $invoice['tax_form'] !='') 
                     $f = explode(",", $invoice['tax_form']);?>
                    <div class="form-group" id="form_div" style="display:none;">
                        <label class="col-lg-3 control-label">Select Form</label>
                        <div class="col-lg-8">
                             <div>
                                <label  style="font-weight: normal;">
                                <input type="checkbox" name="form[]" id="form1" value="H Form" <?php if(isset($f) && in_array('H Form',$f)){ echo 'checked="checked"';} ?>> H Form</label>
                            </div>
                            <div>
                                <label  style="font-weight: normal;">
                                <input type="checkbox" name="form[]" id="form2" value="CT1" <?php if(isset($f) && in_array('CT1',$f)){ echo 'checked="checked"'; }?>> CT1</label>
                            </div>
                            <div>
                                <label  style="font-weight: normal;">
                                <input type="checkbox" name="form[]" id="form3" value="CT3" <?php if(isset($f) && in_array('CT3',$f)){ echo 'checked="checked"';  }?>> CT3</label>
                            </div>
                        </div>
                    </div>
                     <!--end rohits tax div-->
                <div class="form-group" id="tax_div" style="display:none;">
              <label class="col-lg-3 control-label">Zone</label>
              <div class="col-lg-8">
          <div>
            <label  style="font-weight: normal;">
                        <input type="radio" name="taxation" id="taxation" value="cst_with_form_c" checked="checked"
                        <?php if(isset($invoice) && $invoice['taxation'] == 'cst_with_form_c') echo 'checked="checked"'; ?>
                         > Out Of Gujarat (CST With Form C) </label>
                    </div>
          <div>
                      <label style="font-weight: normal;">
                        <input type="radio" name="taxation" id="taxation" value="cst_without_form_c"
                        <?php if(isset($invoice) && $invoice['taxation'] == 'cst_without_form_c') echo 'checked="checked"'; ?>
                         > Out Of Gujarat (CST With Out Form C) </label>
                    </div>
                    <div>
                      <label style="font-weight: normal;">
                          <input type="radio" name="taxation" id="taxation" value="vat"
                            <?php if(isset($invoice) && $invoice['taxation'] == 'vat') echo 'checked="checked"'; ?>
                             > With In Gujarat </label>
                    </div>
        </div>
            </div> 
                <!-- End Taxation -->
                 <?php if($addedByInfo['country_id']=='111') {?>
                <div class="form-group">
              <label class="col-lg-3 control-label"> Delivery</label>
                <div class="col-lg-3">
                   <input type="text" name="delivery" id="delivery" value="<?php echo isset($invoice) ? $invoice['delivery'] : '' ; ?>" placeholder="Delivery" class="form-control validtae[required]" /> 
                 </div>
               <label class="col-lg-3 control-label"><span class="required">*</span>HS CODE</label>
                 <div class="col-lg-3">
                   <input type="text" name="hscode" id="hscode" value="<?php echo isset($invoice) ? $invoice['HS_CODE'] : '' ; ?>" class="form-control validate[required,custom[onlyNumberSp]]"/>
                 </div>
               </div>
               <?php }?>
                  <div class="form-group">
                    <label class="col-lg-3 control-label"><span class="required">*</span> Currency</label>
                    <div class="col-lg-3">
                        <?php $currency = $obj_invoice->getCurrency();?>
                        <select name="currency" id="currency" class="form-control validate[required]" >
                           <option value="">Select Currency</option>
                            <?php foreach($currency as $curr){ ?>
                                    <option value="<?php echo $curr['currency_id']; ?>" <?php if(isset($_GET['invoice_no']) && $curr['currency_id'] == $invoice['curr_id']) { ?> selected="selected" <?php } ?>><?php echo $curr['currency_code']; ?></option>
                          <?php   } ?>
                        </select>
                    </div>
               </div>
               
                <?php if($addedByInfo['country_id']=='111') {?>
                  <div class="form-group option">
                        <label class="col-lg-3 control-label">Mode of Shipment</label>
                        <input type="hidden" value="<?php if(isset($_GET['invoice_no']) && (decode($invoice['transportation']) != '')) { echo decode($invoice['transportation']);}?>" id="ship_type" />
                        <div class="col-lg-9">                
                          <div  style="float:left;width: 200px;" id="byair">
                                <label  style="font-weight: normal;">
                                  <input type="radio" name="transport" id="tran1" value="air" <?php if(isset($_GET['invoice_no']) && (decode($invoice['transportation']) == 'air')) { ?> checked="checked" <?php } ?> >
                                By Air
                                 </label>
                             </div>
                             <div style="float:left;width: 200px;" id="bysea">
                                <label  style="font-weight: normal;">
                                    <input type="radio" name="transport" id="trans2" value="sea" <?php if(isset($_GET['invoice_no']) && (decode($invoice['transportation']) == 'sea')) { ?> checked="checked" <?php } ?> >
                                By Sea
                                 </label>
                              </div>
                              <div style="float:left;width: 200px;" id="byroad" >
                                <label  style="font-weight: normal;">
                                    <input type="radio" name="transport" id="trans3" value="road"  <?php if(isset($_GET['invoice_no']) && (decode($invoice['transportation']) == 'road')) { ?> checked="checked" <?php } ?> >
                                By Road
                                 </label>
                              </div> 
                        </div>
              </div>
              
             
              <div class="form-group option" id="container_div" style="display:none">
               <label class="col-lg-3 control-label">Container No.</label>
                <div class="col-lg-3">                
                 <input type="text" name="container_no" value="<?php echo isset($invoice) ? $invoice['container_no'] : '' ; ?>"  class="form-control " id="container_no" />
                 </div>
                 <label class="col-lg-3 control-label">Seal No.</label>
                <div class="col-lg-3">                
                 <input type="text" name="seal_no" value="<?php echo isset($invoice) ? $invoice['seal_no'] : '' ; ?>"  class="form-control " id="seal_no" />
                 </div>
                 </div>
             <div class="form-group option" id="rfid_div" style="display:none">
               <label class="col-lg-3 control-label">RFID E Seal No.</label>
                <div class="col-lg-3">                
                 <input type="text" name="rfid_no" value="<?php echo isset($invoice) ? $invoice['rfid_no'] : '' ; ?>"  class="form-control " id="rfid_no" />
                 </div>
               
                 </div>
                
                 
                  <?php 
              if(decode($invoice['transportation']) == 'sea')
                 $pouch_type=' PRINTED OR UNPRINTED FLEXIBLE PACKAGING MATERIAL IN THE FORM OF POUCHES';
              else
                 $pouch_type='PRINTED OR UNPRINTED FLEXIBLE PACKAGING MATERIAL OF POUCHES';
              ?>
                <div class="form-group option">
               <label class="col-lg-3 control-label"><span class="required">*</span>Printed Pouches Type</label>
                <div class="col-lg-9">                
                 <input type="text" name="printedpouches" id="printedpouches" value="<?php echo isset($invoice) ? $invoice['pouch_type'] : $pouch_type ; ?>"  class="form-control validate[required]" />
                 </div>
               </div>
                  <div class="form-group">
                <label class="col-lg-3 control-label">Pouches Type Description</label>
                <div class="col-lg-8">
                 <textarea class="form-control" name="pouch_desc" id="pouch_desc"><?php echo isset($invoice) ? $invoice['pouch_desc'] : '' ; ?></textarea>
                </div>
              </div>
                  <div class="form-group">
                <label class="col-lg-3 control-label">Transportation Description</label>
                <div class="col-lg-8">
                 <textarea class="form-control" id="tran_desc" name="tran_desc"><?php echo isset($invoice) ? $invoice['tran_desc'] : '' ; ?></textarea>
                </div>
              </div>
              
            <div class="form-group">
                <label class="col-lg-3 control-label">Remarks</label>
                <div class="col-lg-8">
                <textarea class="form-control " rows="3" cols="45" name="remarks" id="remarks"><?php echo isset($invoice) ? $invoice['remarks'] : '' ;?></textarea>
                </div>
              </div>
             <div class="form-group option">
               <label class="col-lg-3 control-label"><span class="required">*</span>Transportation Charges</label>
                <div class="col-lg-3">                
                 <input type="text" name="tran_charges" value="<?php echo isset($invoice) ? $invoice['tran_charges'] : '' ; ?>"  class="form-control validate[required,custom[number],min[0.001]]" id="tran_charges" />
            </div>
            <label class="col-lg-3 control-label"><span class="required">*</span>Extra Transportation Charges</label>
                <div class="col-lg-3">                
                 <input type="text" name="extra_tran_charges" value="<?php echo isset($invoice) ? $invoice['extra_tran_charges'] : '' ; ?>"  class="form-control " id="extra_tran_charges" />
            </div>
            </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Cylinder Making Charges</label>
                <div class="col-lg-3">                
                 <input type="text" name="cylinder_charges" value="<?php echo isset($invoice) ? $invoice['cylinder_charges'] : '' ; ?>"  class="form-control validate[custom[number]]" id="cylinder_charges" />
                 </div>
                 <label class="col-lg-3 control-label">Tool Cost</label>
                <div class="col-lg-3">                
                 <input type="text" name="tool_cost" value="<?php echo isset($invoice) ? $invoice['tool_cost'] : '' ; ?>"  class="form-control " id="tool_cost" />
                 </div>
               </div>
              
               <?php }?>
               
                <?php if($addedByInfo['country_id']!='111') {?>
                <div class="form-group">
                <label class="col-lg-3 control-label">Postal Code</label>
                <div class="col-lg-3">
                 <input type="text" class="form-control" id="postal_code" name="postal_code" value="<?php echo isset($invoice) ? $invoice['postal_code'] : '' ; ?>" />
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
               
              <!-- jaya end 25-10-2016 -->
               
         <?php }?>
        </div>
                
                <?php if((isset($invoice) && $invoice['final_destination']=='42') || isset($invoice) && $invoice['final_destination']=='14'|| isset($invoice) && $invoice['final_destination']=='189' ) {?>
                  <div class="form-group">
                    <label class="col-lg-3 control-label">Account Code</label>
                        <div class="col-lg-3">                
                             <input type="text" name="account_code" value="<?php echo isset($invoice) ? $invoice['account_code'] : '' ; ?>"  class="form-control " id="account_code" />
                         </div>
                    </div>
                    
                <?php }
        //printr($invoice);
        if($invoice['done_status'] == '0' && $invoice['order_type']=='sample' )
        {
         ?>
                  <!--change by sejal 18-02-2017 -->
          <div class="form-group option">
                       <label class="col-lg-3 control-label">Packing Option</label>
                        
                        <div class="col-lg-9">                
                          <div  style="float:left;width: 200px;" id="cover">
                                <label  style="font-weight: normal;">
                                 <input type="radio" name="sampleoption" id="sampleoption" value="Cover" <?php if(isset($_GET['invoice_no']) ){ ?> checked="checked" <?php } ?> >
                                Fedex Box
                                 </label>
                             </div>
                             <div style="float:left;width: 200px;" id="box">
                                <label  style="font-weight: normal;">
                                 <input type="radio" name="sampleoption" id="sampleoption" value="Box" <?php if(isset($_GET['invoice_no'])) { ?> checked="checked" <?php } ?> >
                                Fedex Envelope
                                 </label>
                              </div>
                              <div style="float:left;width: 200px;" id="pack">
                                <label  style="font-weight: normal;">
                                 <input type="radio" name="sampleoption" id="sampleoption" value="Box" <?php if(isset($_GET['invoice_no'])) { ?> checked="checked" <?php } ?> >
                                Fedex Pack
                                 </label>
                              </div>
                              </div>  
                      <?php }?>               
                  <div class="line line-dashed m-t-large"></div>
                    
                    <!--jaya commented it 25-10-2016 -->
                    
            <?php /*?>     <?php if($addedByInfo['country_id'] == '111') {?>
                
                  <div class="form-group option">
                    <label class="col-lg-3 control-label">Stock Order Number</label>
                      <div class="col-lg-3">                
                             <input type="text" name="stock_order_number" id="stock_order_number"  <?php if(isset($_GET['quotation_no']) && $_GET['quotation_no']!=''){?>disabled="disabled"  value="<?php echo decode($_GET['quotation_no']);?>" <?php }
                    elseif (isset($stock_o_no) && $stock_o_no!='') { echo 'value="'.$stock_o_no.'" disabled="disabled"';}else { echo 'value=""';}?>  onchange="frm_fill_stock();" class="form-control validate" />
                         </div>
                    </div>
                    
                    <div class="form-group option">
                        <label class="col-lg-3 control-label">Custom Order Number</label>
                        <div class="col-lg-3">                
                             <input type="text" name="custom_order_number" id="custom_order_number"  <?php if(isset($_GET['quotation_no']) && $_GET['quotation_no']!=''){?>disabled="disabled"  value="<?php echo decode($_GET['quotation_no']);?>" <?php }
               elseif (isset($custom_o_no) && $custom_o_no!='') { echo 'value="'.$custom_o_no.'" disabled="disabled"';} else { echo 'value=""';}?> onchange="frm_fill();" class="form-control validate"/>
                             
                         </div>
                    </div>
                
        <?php } ?><?php */?>
              
     <div id="combination_div"    class="table-responsive">
    <?php /*?>          <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span> Select Product</label>
                        <div class="col-lg-8">
                            <?php $products = $obj_invoice->getActiveProduct(); ?>
                            <select name="product" id="product" class="form-control validate[required]">
                            <option value="">Select Product</option>
                                <?php  foreach($products as $product){
                                    if(isset($post['product']) && $post['product'] == $product['product_id']){ ?>
                                        <option value="<?php echo $product['product_id']; ?>" selected="selected" ><?php echo $product['product_name']; ?></option>
                                    <?php }else{ ?>
                                        <option value="<?php echo $product['product_id']; ?>" <?php if(isset($invoice_product_id) && ($product['product_id'] == $invoice_product['product_id'])) { echo 'selected="selected"';}?> > <?php echo $product['product_name']; ?></option>
                                    <?php }
                                } ?>
                                 <?php if(isset($invoice_product['product_id']))
                  $id=$invoice_product['product_id'];
                elseif(isset($product['product_id']))
                  $id=$product['product_id'];
                ?>
                            <option value="11" <?php if(isset($id) && ($id  == '11')) echo  'selected="selected"';?>>Plastic Scoop</option>
                            </select>
                        </div>
                </div>
                    <div class="form-group option">
                    <label class="col-lg-3 control-label">Valve</label>
                    <div class="col-lg-9">
                        <div  style="float:left;width: 200px;">
                            <label  style="font-weight: normal;">
                              <input type="radio" name="valve" id="nv" value="No Valve" checked="checked"  class="valve"
                              <?php if(isset($invoice_product_id) && ($invoice_product['valve'] == 'No Valve')) { echo 'checked="checked"';} ?> >No Valve </label>                            
                           <label style="font-weight: normal;">
                                <input type="radio" name="valve" id="wv" value="With Valve" class="valve"  <?php if(isset($invoice_product_id) && ($invoice_product['valve'] == 'With Valve')) {
                                  echo 'checked="checked"'; }?> >With Valve </label>
                        </div> 
                    </div>
                </div>
          <input type="hidden" name="fetched_zipper" id="fetched_zipper" value="<?php echo isset($invoice_product['zipper']) ? $invoice_product['zipper']:'';?>"  />                    
                    <div id="zipper_div" class="form-group">
                      <div class="form-group option"> <label class="col-lg-3 control-label">Zipper</label>
                            <div class="col-lg-9"><?php $zippers = $obj_invoice->getActiveProductZippers();
                foreach($zippers as $zipper){?>
                                <div style="float:left;width: 200px;">
                                  <label  style="font-weight: normal;">
                                 <input type="radio" name="zipper" value="<?php echo encode($zipper['product_zipper_id']); ?>"   class="zipper"
                                 <?php if(isset($invoice_product['zipper']) && $invoice_product['zipper'] == encode($zipper['product_zipper_id'])) echo 'checked="checked"'; ?> >
                       <?php echo $zipper['zipper_name']; ?>
                                  </label>
                                </div>
                              <?php } ?>
                          </div>
                      </div>                       
                    </div>
                    
                    
                    <?php $spouts = $obj_invoice->getActiveProductSpout();?>
                  <div id="spout_div" class="form-group" <?php if(isset($invoice_product['product_id']) && $invoice_product['product_id']==0){ ?> style="display:none" <?php } ?>>
                         <label class="col-lg-3 control-label">Spout</label>
                              <div class="col-lg-9">
                              <?php $spoutsTxt = '';
                  foreach($spouts as $spout){ ?>
                  <div  style="float:left;width: 200px;">
                  <label  style="font-weight: normal;">
                                      <input type="radio" name="spout" class="spout" id="spout" value="<?php echo encode($spout['product_spout_id']); ?>"
                                      <?php if(isset($invoice_product['spout']) && ($invoice_product['spout'] == encode($spout['product_spout_id']))) { 
                                      echo 'checked="checked"'; }elseif(!isset($invoice_product['spout']) && (encode($spout['product_spout_id'])=='MQ==')){ 
                                      echo 'checked="checked"';}?> />
                              <?php echo $spout['spout_name'];?>
                              </label>
                              </div>
                          <?php }?>
                        </div>
          </div>
                    
                    
                      <?php $accessories = $obj_invoice->getActiveProductAccessorie();?>
                    <div id="acce_div"  class="form-group" <?php if(isset($invoice_product['product_id']) && $invoice_product['product_id']==0){ ?> style="display:none" <?php } ?>>
                         <label class="col-lg-3 control-label">Accessorie</label>
                              <div class="col-lg-9">
                              <?php $accessorieTxt = '';
                  foreach($accessories as $accessorie){ ?>
                    <div  style="float:left;width: 200px;">
                                      <label  style="font-weight: normal;">
                                          <input type="radio" name="accessorie" class="accessorie" id="accessorie" 
                                          value="<?php echo encode($accessorie['product_accessorie_id']); ?>"
                                          <?php if(isset($invoice_product['accessorie']) && ($invoice_product['accessorie'] == encode($accessorie['product_accessorie_id']))) { 
                                          echo 'checked="checked"'; }elseif(!isset($invoice_product['accessorie']) && (encode($accessorie['product_accessorie_id'])=='NA==')){ 
                                          echo 'checked="checked"';}?> />
                  <?php echo $accessorie['product_accessorie_name'];?>
                               </label>
                               </div>
                           <?php }?>
                            </div>
            </div>
                    
                      <div class="form-group option">
                          <label class="col-lg-3 control-label">Make Pouch</label>
                          <div class="col-lg-9">
                                <?php $makes = $obj_invoice->getActiveMake();
                //printr($makes);
                                foreach($makes as $make){?>
                <div style="float:left;width:200px;">
                                    <?php if(isset($invoice_product['make_pouch'])) { ?>
                        <input type="radio" name="make" id="make" value="<?php echo $make['make_id'];?>" <?php if($invoice_product['make_pouch'] == $make['make_id']) { ?> checked="checked"  <?php } ?> />
                                     <?php } else{?>
                                         <input type="radio" name="make" id="make" value="<?php echo $make['make_id'];?>" <?php   if($make['make_id']=='1'){ ?> checked="checked" <?php } ?> >
                                     <?php  }  echo $make['make_name'];?> 
                                 </div>
                              <?php  } ?>
                              </div>
                            </div>
                          
                          <div class="form-group option">
                              <label class="col-lg-3 control-label">Buyer's Order No.</label>
                                <div class="col-lg-2">
                                  <input type="text" name="buyers_o_no" id="buyers_o_no" value="<?php echo isset($invoice_product) ? $invoice_product['buyers_o_no'] : '' ; ?>"  class="form-control validate[required]" />
                                </div>
                                <label class="col-lg-3 control-label" id="item_no_text">Item No.</label>
                                <div class="col-lg-2">
                                  <input type="text" name="item_no" id="item_no" value="<?php echo isset($invoice_product) ? $invoice_product['item_no'] : '' ; ?>"  class="form-control " />
                                </div>
                     </div>
                          
                          <div class="form-group">
              <label class="col-lg-3 control-label"></label> 
              <div class="col-lg-7" style="width: 75%;">
                  <section class="panel">
                    <div class="table-responsive">
                    <table class="tool-row table-striped  b-t text-small" id="myTable">
                      <thead>
                        <tr>
                                                <th>Color</th>
                                                <th></th>
                                                <th>Qty</th>
                                                <th>Rate </th>
                                                <th>Size</th>
                                                <th>Measurement</th>
                                                <th>Net Weight<br />
                                                 (In Kg.)</th>
                                                <th>Dimension</th>
                                                <th></th>
                                              </tr>
                      </thead>
                                          <tbody  id="myTbody">
                                    <input type="hidden" id="color_arr" value='<?php echo json_encode($colors);?>' />
                                     <input type="hidden" id="mea_arr" value='<?php echo json_encode($measurement);?>' />
                                  <?php if(isset($invoice_product_id)) { 
                          $color = $obj_invoice->getColorDetails($invoice_no,$invoice_product_id); 
                          //printr($color); printr($colors);
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
                        'is_delete' => '',
                        'color_text'=>'');
                    // echo"ki";    
                    }//printr($color);
                        if($color)
                        {
                          $i = 0;
                          foreach($color as $colorkey => $clr) { //printr($clr);?>
                                   <tr id="tr_<?php echo $i; ?>">  
                      <td>
                        <select name="color[<?php echo $colorkey;?>][color]" id="color_<?php echo $i;?>" class="form-control validate[required]"   onchange="color(<?php echo $i; ?>)">                               
                                         <option value="">Select Color </option>
                                                <?php foreach($colors as $colors2){ ?>
                          <option value="<?php echo $colors2['pouch_color_id']; ?>" id="option"
                                              <?php if($clr['color'] == $colors2['color']) { echo 'selected="selected"'; } ?>
                                              ><?php echo $colors2['color']; ?></option>
                                            <?php } ?>
                                              <option value="-1" id="option" <?php if($clr['color'] != '' && $clr['color']=='Custom')
                        {?> selected="selected" <?php } ?>>Custom</option>
                          </select>
                      </td>
                                             <td>
                              <?php if(($clr['color']) == '-1'  && (empty($clr['color_text']) || !empty($clr['color_text']))){ ?>
                              <input type="text" name="color[<?php echo $colorkey; ?>][color_text]" value="<?php echo $clr['color_text']; ?>" 
                              id="color_txt_<?php echo $i; ?>" class="form-control"/>
                              <?php }else{ ?>
                <input type="text" name="color[<?php echo $colorkey; ?>][color_text]" value="<?php echo $clr['color_text']; ?>" 
                                id="color_txt_<?php echo $i; ?>" class="form-control" <?php if(empty($clr['color_text'])) {?> style="display:none" <?php }?>/>  
                <?php }?> 
                                </td>
                                            <td><input type="text" name="color[<?php echo $colorkey; ?>][qty]" value="<?php echo $clr['qty']; ?>" class="form-control validate[required,custom[number],min[1]]" placeholder="Qty" id="qty"></td>
                                            <td><input type="text" name="color[<?php echo $colorkey; ?>][rate]" value="<?php echo $clr['rate']; ?>" class="form-control validate[required,custom[number]]" placeholder="Rate" id="rate"></td>
                                            <td><input type="text" name="color[<?php echo $colorkey; ?>][size]" value="<?php echo $clr['size']; ?>" class="form-control validate[required,custom[number]]" placeholder="Size" id="size"></td>
                                            <td><select name="color[<?php echo $colorkey; ?>][measurement]" id="measurement" class="form-control validate[required]" >
                                           <option value="">Select Measurement</option>
                                            <?php foreach($measurement as $meas){ ?>
                                                    <option value="<?php echo $meas['product_id']; ?>"
                                                    <?php if(isset($_GET['invoice_no'])) {
                                                    if($clr['measurement'] == $meas['measurement']) { ?> selected="selected" <?php }} ?>
                                                     ><?php echo $meas['measurement']; ?></option>
                                          <?php   }  ?>
                                        </select>
                                        </td>
                                         <td><input type="text" name="color[<?php echo $colorkey; ?>][net_weight]" value="<?php echo $clr['net_weight']; ?>" class="form-control validate[required,custom[number]] nwt_wt" placeholder="Net Weight" id="net_weight_<?php echo $i;?>"  onchange="net_wt(0)"></td>
                                      <td><input type="text" name="color[<?php echo $colorkey; ?>][dimension]" value="<?php echo $clr['dimension']; ?>" class="form-control validate[min[1]]" placeholder="dimension" id="dimension">
                                            <input type="hidden" name="color[<?php echo $colorkey; ?>][invoice_color_id]" value="<?php echo $clr['invoice_color_id']; ?>" class="form-control validate[min[1]]" placeholder="invoice_color_id" id="invoice_color_id_<?php echo $i;?>"></td>
                                      <td><?php if($i == 0) { ?><a class="btn btn-success btn-xs btn-circle addmore" data-toggle="tooltip" data-placement="top" title="Add Profit" id="addmore" ><i class="fa fa-plus"></i></a><?php }
                       else { ?>
                                                 <a class="btn btn-danger btn-xs btn-circle remove" data-toggle="tooltip" data-placement="top" title="Remove" onclick="remove_tr(<?php echo $i; ?>)" ><i class="fa fa-minus"></i></a>
                       <?php } ?>                                
                                            </td>
                                           </tr>
                    <?php $i++; } }?>
                                        </tbody>
                    </table>
                    </div>
                                    </section> 
                </div>
                </div> 
                <div class="form-group option">
                       <label class="col-lg-3 control-label">Total Net weight</label>
                        <div class="col-lg-3"> 
                        <?php if(isset($invoice_product_id)) {?>              
                             <input type="text" name="netweight" id="netweight" placeholder="Total Net weight" value="<?php echo isset($invoice_product) ? $invoice_product['net_weight'] : '' ; ?>"  class="form-control validate[required,custom[number]]" readonly="readonly" />
                            <?php } else {?>
                             <input type="text" name="netweight" id="netweight" placeholder="" value="" class="form-control validate[required,custom[number]]" readonly="readonly" />
                          <?php }?>
                 </div>
                <input type="hidden" name="edit" id="edit" value="<?php echo $edit; ?>"  />  
                 <div class="col-lg-3">                
                     <select name="measurement" id="measurement" class="form-control validate[required] measure" disabled="disabled">
                          <option value="">Select Measurement</option>
                              <?php foreach($measurement as $meas){ 
                if(isset($invoice_product_id)){?>
                                    <option value="<?php echo $meas['product_id']; ?>"
                                        <?php 
                    if(isset($_GET['invoice_no'])) {
                    if($meas['product_id'] == $invoice_product['measurement_two']) { ?> selected="selected" <?php }} ?>
                                         ><?php echo $meas['measurement']; ?></option>
                 <?php  }else{?>
                                  <option value="<?php echo $meas['product_id']; ?>" ><?php echo $meas['measurement']; ?></option>
                <?php }}?>
                       </select>
                       <input type="text" id="mea" name="measurement" style="display:none" value="1" />
                 </div>
               </div>
               
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
                      <a class="btn btn-primary btn-sm" onclick="change_price(<?php echo $invoice_no; ?>, '<?php echo isset($invoice) ? $invoice['buyers_orderno'] : '' ; ?>')">Change Price</a>
                      <button type="button" name="btn_save" id="btn_save" class="btn btn-primary" <?php if(isset($_GET['invoice_product_id'])){ ?> 
                         style="display:none" <?php } ?> onclick="displaygenerate();">Add Product</button>
                         
                       <?php if(isset($invoice_no) && isset($invoice_product_id)) {?>
                        <input type="hidden" name="pro_id" value="<?php echo $invoice_product_id;?>" id="pro_id" />
                      <input type="hidden" name="invoice_id" value="<?php echo $invoice_no;?>"/>
                         <button type="button" name="invoice_update" id="invoice_update" class="btn btn-primary">Update Product</button> 
                     <?php } ?> 
           </div>
                </div>
                
               <?php */?>


               <!-- added by sonu 06-03-2018 -->


                 <div class="form-group option">
                        <label class="col-lg-3 control-label"><span class="required">*</span>Product Code</label>
                        <div class="col-lg-4" id="holder">
                          <?php $product_codes=$obj_invoice->getActiveProductCode(); ?>
               

             
                              <input type="hidden" id="product_code_id" name="product_code_id" value="<?php echo isset($invoice_product) ? $product_code_details['product_code_id'] : '' ; ?>">
                               <input type="text" id="keyword" class="form-control"  autocomplete="off" value="<?php echo isset($product_code_details) ? $product_code_details['product_code'] : '' ; ?>">
                               <input type="hidden" name="real_product_name" id="real_product_name" value="<?php echo isset($product_code_details) ? $product_code_details['product_code'] : '' ; ?>" />
                                <input type="hidden" name="product_id" id="product_id" value="<?php echo isset($invoice_product) ? $invoice_product['product_id'] : '' ; ?>" />
                                <input type="hidden" name="valve" id="valve" value="<?php echo isset($invoice_product) ? $invoice_product['valve'] : '' ; ?>" />
                                <input type="hidden" name="spout" id="spout" value="<?php echo isset($invoice_product) ? $invoice_product['spout'] : '' ; ?>" />
                                <input type="hidden" name="accessorie" id="accessorie" value="<?php echo isset($invoice_product) ? $invoice_product['accessorie'] : '' ; ?>" />
                                <input type="hidden" name="make" id="make" value="<?php echo isset($invoice_product) ? $invoice_product['make_pouch'] : '' ; ?>" />
                                <input type="hidden" name="zipper" id="zipper" value="<?php echo isset($invoice_product) ? $invoice_product['zipper']: '' ; ?>" />
                                <input type="hidden" name="u_product_code" id="u_product_code" value="<?php echo isset($invoice_product) ? 'update': 'add' ; ?>" />
                               <div id="ajax_response"></div>
                         
                        </div>
                        <div class="col-lg-4" id="product_div"> 
                               <input type="text" name="product_name" id="product_name"  value="<?php echo isset($invoice_product) ? $product_code_details['description'] : '' ; ?>" disabled="disabled" class="form-control validate" style="width:500px"/>
                  </div>
                </div>

              <div class="form-group">
              <label class="col-lg-3 control-label"></label> 
              <div class="col-lg-7" style="width: 75%;">
                  <section class="panel">
                    <div class="table-responsive">
                    <table class="tool-row table-striped  b-t text-small" id="myTable">
                      <thead>
                        <tr>
                              <th>Color</th>                          
                              <th>Qty</th>
                              <th>Rate </th>
                              <th>Size</th>
                              <th>Measurement</th>
                              <th>Net Weight<br />
                               (In Kg.)</th>
                              <th>Dimension</th>
                                             
                </tr>
                      </thead>
                     <tbody  id="myTbody">
                                    <input type="hidden" id="color_arr" value='<?php echo json_encode($colors);?>' />
                                     <input type="hidden" id="mea_arr" value='<?php echo json_encode($measurement);?>' />
                                
                     <tr id="tr">  
                      <td>
                        <select name="color_product" id="color_product" class="form-control validate[required]" >                               
                         <option value="">Select Color </option>
                                              <?php foreach($colors as $colors2){ ?>
                          <option value="<?php echo $colors2['pouch_color_id']; ?>" id="option"
                                              <?php if(isset($invoice_color_details['color']) && $invoice_color_details['color'] == $colors2['pouch_color_id']) { echo 'selected="selected"'; } ?>
                                              ><?php echo $colors2['color']; ?></option>
                                            <?php } ?>
                        <option value="-1" id="option" <?php if(isset($invoice_color_details['color']) && $invoice_color_details['color'] != '' && $invoice_color_details['color']=='Custom')
                          {?> selected="selected" <?php } ?>>Custom</option>
                          </select>
                      </td>
                      
                      <td><input type="text" name="qty" value="<?php echo isset($invoice_color_details) ? $invoice_color_details['qty'] : '' ; ?>" class="form-control" placeholder="Qty" id="qty"></td>
                      <td><input type="text" name="rate" value="<?php echo isset($invoice_color_details) ? $invoice_color_details['rate'] : '' ; ?>" class="form-control " placeholder="Rate" id="rate"></td>
                      
                      <?php if(isset($invoice_color_details['size'])){
                          $size_arr=explode(' ',$invoice_color_details['size']);
                      }?>
                      <td><input type="text" name="size" id="size" value="<?php echo isset($invoice_color_details) ? $size_arr[0] : '' ; ?>" class="form-control " placeholder="Size" id="size"></td>
                      <td><select name="measurement" id="measurement" class="form-control validate[required]" >
                     <option value="">Select Measurement</option>
                      <?php foreach($measurement as $meas){ ?>
                              <option value="<?php echo $meas['product_id']; ?>"
                              <?php if(isset($_GET['invoice_no'])) {
                              if(isset($product_code_details['measurement']) && $product_code_details['measurement'] == $meas['product_id']) { ?> selected="selected" <?php }} ?>
                               ><?php echo $meas['measurement']; ?></option>
                    <?php   }  ?>
                  </select>
                  </td>
                                         <td><input type="text" name="net_weight" value="<?php echo isset($invoice_color_details) ?$invoice_color_details['net_weight'] : '' ; ?>" class="form-control" placeholder="Net Weight" id="net_weight"></td>
                                      <td><input type="text" name="dimension" value="<?php echo isset($invoice_color_details) ? $invoice_color_details['dimension'] : '' ; ?>" class="form-control " placeholder="dimension" id="dimension">
                                            <input type="hidden" name="invoice_color_id" value="<?php echo isset($invoice_color_details) ? $invoice_color_details['invoice_color_id'] : '' ; ?>" class="form-control " placeholder="invoice_color_id" id="invoice_color_id"></td>
                                      
                                           </tr>
                 
                                        </tbody>
                    </table>
                    </div>
                                    </section> 
                </div>
                </div>  


                <div class="form-group option">
                    <label class="col-lg-3 control-label">Buyer's Order No.</label>
                      <div class="col-lg-2">
                        <input type="text" name="buyers_o_no" id="buyers_o_no" value="<?php echo isset($invoice_product) ? $invoice_product['buyers_o_no'] : '' ; ?>"  class="form-control " />
                      </div>
                      <label class="col-lg-3 control-label">Reference No.</label>
                      <div class="col-lg-2">
                        <input type="text" name="pro_ref_no" id="pro_ref_no" value="<?php echo isset($invoice_product) ? $invoice_product['ref_no'] : '' ; ?>"  class="form-control " />
                      </div>
            </div>   
              <div class="form-group option">
                      <label class="col-lg-3 control-label"> Job Name</label>
                        <div class="col-lg-2">
                          <input type="text" name="job_name" id="job_name" value="<?php echo isset($invoice_color_details) ? $invoice_color_details['color_text'] : '' ; ?>"  class="form-control " />
                        </div>
                         <label class="col-lg-3 control-label" id="item_no_text">Item No.</label>
                      <div class="col-lg-2">
                        <input type="text" name="item_no" id="item_no" value="<?php echo isset($invoice_product) ? $invoice_product['item_no'] : '' ; ?>"  class="form-control " />
                      </div>
                       
             </div>
                       
              
               
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
                      <a class="btn btn-primary btn-sm" onclick="change_price(<?php echo $invoice_no; ?>, '<?php echo isset($invoice) ? $invoice['buyers_orderno'] : '' ; ?>')">Change Price</a>
                      <button type="button" name="btn_save" id="btn_save" class="btn btn-primary" <?php if(isset($_GET['invoice_product_id'])){ ?> 
                         style="display:none" <?php } ?> onclick="displaygenerate();">Add Product</button>
                         
                       <?php if(isset($invoice_no) && isset($invoice_product_id)) {?>
                        <input type="hidden" name="pro_id" value="<?php echo $invoice_product_id;?>" id="pro_id" />
                      <input type="hidden" name="invoice_id" value="<?php echo $invoice_no;?>"/>
                         <button type="button" name="invoice_update" id="invoice_update" class="btn btn-primary">Update Product</button> 
                     <?php } ?> 
           </div>
                </div>
                








                </div>
                
                <div id="result"></div>
                
                  <div id="invoice_results"  class="table-responsive">
           <?php if(isset($invoice_no) && !empty($invoice_no)) {
          $invoice_product_second = $obj_invoice->getInvoiceProduct($invoice_id);
    //  printr($invoice_product_second);
        //die; ?>
                    <table class="table table-bordered"> 
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Size</th>
                                <th>Color : Quantity</th>
                                <th>Rate</th>                                
                                <th>Option</th>
                                <th>Transport</th>
                                <?php if($invoice['generate_status']==0)
                  {?>  
                                <th>Action</th>  
                                <?php } ?>              
                            </tr>
                        </thead>
                        <tbody>
                <?php 
              if(!empty($invoice_product_second))
              {
                $i =0;
              foreach($invoice_product_second as $invoice_d ) {
             //  printr($invoice_d);
           
                   if($invoice_d['digital_print_color']!=''){
				
					     $digital_color=$obj_invoice->GetdigitalColorName($invoice_d['digital_print_color']);
					    // printr($digital_color);
					     $d_color='<b>Digital Printing With </b>'.$digital_color;
					 }else{
				
					      $d_color='';
					 }
				
                  
                  
                  
                  
            //  printr( $d_color);
              //die;
            $invoice_product_id = $invoice_d['invoice_product_id'];
            if(isset($invoice_product_id) && !empty($invoice_product_id))
            {
               $getProductSpout = $obj_invoice->getSpout(decode($invoice_d['spout']));
                     $getProductZipper = $obj_invoice->getZipper(decode($invoice_d['zipper']));
                       $getProductAccessorie = $obj_invoice->getAccessorie(decode($invoice_d['accessorie'])); ?>
              <input type="hidden" name="invoice_id" value="<?php echo $invoice_no; ?>"  />
                      <tr id="invoice_product_id_<?php echo $invoice_d['invoice_product_id']; ?>">
                <td><b><?php $product = $obj_invoice->getActiveProductName($invoice_d['product_id']);
                      echo $product['product_name'].'<br>  '.$invoice_d['filling_details'].'<br>'. $d_color; ?></b></td>
                            <?php $colors = $obj_invoice->getColorDetails($invoice_d['invoice_id'],$invoice_d['invoice_product_id']); 
              //printr($colors);  ?>
                          <td><?php foreach($colors as $size) 
                              { 
                                    if($invoice_d['product_id'] != '47'||$invoice_d['product_id'] != '48')
    										$size['size']= filter_var($size['size'], FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
                                   	 if($size['color_text']!='' && $invoice_d['digital_print_color']!='')
                                  	    $color_text='<b>Digital Print:</b>'.$size['color_text'];
                                  	  else
                                  	      $color_text="";
                              
                                if($size['color']=='Custom')
                                {
                                echo $size['dimension'].'<br>';                 
                             
                                }else{ 
                                    
                                    if($invoice['final_destination']!='253'){
                                         echo $size['size'].'&nbsp;'.$size['measurement'].'<br>';
                                        }else{
                                            $s_us = $obj_invoice->getsizeForUS($size['size'].' '.$size['measurement']);
                                            if($s_us!=''){
            						              echo $s_us.'<br>';
            						         }else{
            						                echo $size['size'].'&nbsp;'.$size['measurement'].'<br>';
            						         }
                                        }
                                    
                                }
                              } ?>
                  <td>
                    <?php 
                 foreach($colors as $colorss) {
                    if($colorss['color']=='Custom')
                      echo 'Custom : '.$colorss['color_text'].' '.$colorss['qty'].'<br>';
                    else
                    {
                        
                        echo $colorss['color'].' : '.$colorss['qty'].'<br>'.$color_text.'<br>';
                    }                    
                  }$measure = $obj_invoice->getMeasurementName($invoice_d['measurement_two']); 
                  echo '<b>Net Weight :</b>'.$invoice_d['net_weight'].'&nbsp;'.$measure['measurement']; ?>
                </td>
                      <td>
                  <?php //printr($colors);
                      foreach($colors as $rate_val)
                      {
             
                        
                  if($_GET['inv_status']=='3')
                      {
                            echo $rate_val['rate_with_proportion'].'<br>';
                        
                            
                  
                        }else if($invoice['done_status']=='0'){
                            
                        ?>  <div class="col-lg-3">
                             <input type="text" class="form-control"  id="invoice_rate<?php echo $rate_val['invoice_color_id'];?>" name="invoice_rate<?php echo $rate_val['invoice_color_id'];?>" onchange="updateRate(<?php echo  $rate_val['invoice_color_id'];?>,<?php echo $rate_val['invoice_color_id'] ;?>)" value="<?php echo isset($rate_val['rate'])?$rate_val['rate']:'' ;?>" >  
                             </div>
                            <?php  if($colorss['color']=='Custom'){?>
                                 <div class="col-lg-8">
                                         <div class="form-group">
                                             <label class="col-lg-2 control-label">Cylinder Rate</label>
                                                 <div class="col-lg-4">
                                                     <input type="text" class="form-control"  id="cylinder_rate<?php echo $rate_val['invoice_color_id'];?>" name="cylinder_rate<?php echo $rate_val['invoice_color_id'];?>" onchange="updateCylinderRate(<?php echo  $rate_val['invoice_color_id'];?>,<?php echo $rate_val['invoice_color_id'] ;?>)" value="<?php echo isset($rate_val['cylinder_rate'])?$rate_val['cylinder_rate']:'' ;?>" >  
                                               </div> 
                                        
                                                <label class="col-lg-2 control-label">No of Cylinder</label>
                                                 <div class="col-lg-4">
                                                     <input type="text" class="form-control"  id="no_of_cylinder<?php echo $rate_val['invoice_color_id'];?>" name="no_of_cylinder<?php echo $rate_val['invoice_color_id'];?>" onchange="updateCylinderNo(<?php echo  $rate_val['invoice_color_id'];?>,<?php echo $rate_val['invoice_color_id'] ;?>)" value="<?php echo isset($rate_val['no_of_cylinder'])?$rate_val['no_of_cylinder']:'' ;?>" >  
                                                </div>
                                         </div>
                                 </div>
                        <?php
                               }  
                      }else{
                          echo $rate_val['rate'].'<br>';
                             if($colorss['color']=='Custom'){
                                 
                                  echo '<b>Cylinder Rate:</b> '.$rate_val['cylinder_rate'].'<br>';
                                   echo '<b>No Of Cylinder: </b>'.$rate_val['no_of_cylinder'].'<br>';
                             }
                      }
                       //[kinjal] make on 6-12-2016 for other country's purchase invoice
                       
                      
                      } ?>
                </td>
                      <td><?php echo ucwords($getProductSpout['spout_name']).' '.$invoice_d['valve'].'<br>'.$getProductZipper['zipper_name'].' '.ucwords($getProductAccessorie['product_accessorie_name']); ?></td>
                <td><?php echo 'By '.ucwords(decode($invoice['transportation'])); ?></td>
                              <?php if($invoice['generate_status']==0)
                  {?>
                <td class="del-product"><a class="btn btn-danger btn-sm" href="javascript:void(0);" onClick="removeInvoice(<?php echo $invoice_d['invoice_product_id'].','.$invoice_d['invoice_id']; ?>)"><i class="fa fa-trash-o"></i></a>
                       <a href="<?php echo $obj_general->link($rout, 'mod=add&invoice_no='.encode($invoice_no).'&invoice_product_id='.encode($invoice_d['invoice_product_id']).'&inv_status='.$_GET['inv_status'],'',1); ?>" id="btn_edit"  name="btn_edit" class="btn btn-info btn-xs btn_edit">Edit</a>
                        </td>
                              <?php } ?>
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
      <?php } else { ?>
               <tr>
                <td colspan="7">No Records Found!!!</td>  
              </tr>
        <?php }
      $i ++;} }?>
              </tbody>
                </table>
          <?php  } ?>
        </div> 
      <?php if($edit){?>
      <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                    <button type="button" name="btn_update" id="btn_update" class="btn btn-primary">Update </button>
                    <a class="btn btn-default" href="<?php echo $obj_general->link($rout, 'mod=index&inv_status='.$_GET['inv_status'], '',1);?>">Cancel</a>
                </div>
            </div>
    <?php }  ?>
            <div class="col-lg-9 col-lg-offset-3">
                <button type="submit" id="generate_invoice"  class="btn btn-primary" name="generate_invoice" onClick="add_invoices()" style="display:none" >Generate Invoice</button>
            </div>
              </form>
            </div>
          </section>
      </div>
    </div>
  </section>
</section>
<div class="modal fade" id="form_con1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:46%;">
    <div class="modal-content">
     <form class="form-horizontal" method="post" name="form" id="change_price" style="margin-bottom:0px;">
              <div class="modal-header">
                      <h4 class="dispatch" id="myModalLabel">Cahnge Price</h4>
            <input type="hidden" name="form_invoice_id" class="form-control terms" id="form_invoice_id" value=""/> 
              </div>
               <div class="modal-body">
        <div class="form-group " >
          <label class="col-lg-3 control-label">Buyer Order No</label>
          <div class="col-lg-3">          
            <input type="text" name="form_buyers_no" class="form-control terms" id="form_buyers_no" value=""/>                    
          </div>  
        </div>
         <div class="form-group " >
          <label class="col-lg-3 control-label">Invoice Total Qty </label>
          <div class="col-lg-3">          
            <input type="text" name="invoice_total_qty" class="form-control terms" id="invoice_total_qty" value=""/>                    
          </div>  
            
          
          
        </div>             
                    
             </div>
              <div class="modal-footer">
                
               <button type="submit" name="change_price" class="btn btn-primary">Change</button>
         <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
              </div>
      </form>   
    </div>
  </div>
</div>
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

#ajax_response,#ajax_return{
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
       jQuery("#form").validationEngine();
       
     // [kinjal] on(24-9-2016)
       var stock_order_no = '<?php echo isset($stock_o_no)?$stock_o_no:'' ; ?>';
       var custom_order_no = '<?php echo isset($custom_o_no)?$custom_o_no:'' ; ?>';
      
        if(stock_order_no!='' || custom_order_no!='')
        frm_fill();
      
      
      /*if(custom_order_no!='')
        frm_fill();
        
      if(stock_order_no!='')
        frm_fill_stock();*/
        
    //end [kinjal]  
    
    //jaya on 25-10-2016
    
    var generate_status = $("#generate_status").val();
    
    var add_new = '<?php echo isset($_GET['add_new'])? $_GET['add_new'] : '';?>';
    //alert($_GET['add_new']);
    
    if(add_new=='1')
    {
       $("#combination_div").show();
       $("#invoice_div").show();
    }
    else
    {
        if(generate_status=='0')
        {
          $("#invoice_div").hide();
          $("#combination_div").show();
        }
        else
        {
            $("#invoice_div").show();
          $("#combination_div").hide();
        }
    
    }
    
      // $("#byroad").hide();
     var fetched_zipper = $("#fetched_zipper").val();
       if(fetched_zipper == '') {
        checkZipper();
       }
     var ship_type = $("#ship_type").val();
     if(ship_type=='')
       $("input:radio[name=transport]:not(:disabled):first").attr('checked', true);
     else if(ship_type=='sea')  
       $('#container_div').css("display","block");$('#rfid_div').css("display","block");
     
     //console.log(ship_type);    
        $("input:radio[name=transport]").change(function(){
                transport=$("input:radio[name=transport]:checked").val(); 
                console.log(transport);
                if(transport=='sea')
                {
                
                  	$('#container_div').css("display","block");
                  	$('#rfid_div').css("display","block");
                }
                else
                {
                  $('#container_div').css("display","none");
                  $('#rfid_div').css("display","none");
                }     
        });
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
    $("#tax_div").show();
    $("#tax_type").show();
    $("#byroad").show();
    $("#byair").hide();
    $("#bysea").hide();
    $("#tran1").prop('checked', false);
    $("#trans3").prop('checked', true); 
  } else {
    $("#form_div").hide();
    $("#tax_div").hide();
    $("#tax_type").hide();
   // $("#byroad").hide();
    $("#byair").show();
    $("#bysea").show();
    $("#trans3").prop('checked', false);
    $("#tran1").prop('checked', true);
  }
}
);
$('input[type=radio][name=tax_mode]').change(function() {
  if(this.value == 'normal'){
    $("#tax_div").show();
    $("#form_div").hide();
  } else {
    $("#tax_div").hide();
    $("#form_div").show();
  }
});
$('input[name="form[]"]').change(function() {
  if($('#form1').prop("checked") == true) {   
    $("#tax_div").hide();
  } else if($('#form2').prop("checked") == true || $('#form3').prop("checked") == true)  {
    $("#tax_div").show();
  } else {
    $("#tax_div").hide();
  } 
}); 
// comment by sonu 20-4-2017
//$("#invoiceno").change(function() {
//      var checknum=$(this).val();
//    if(checknum==0)
//    {
//      alert("Please Enter Valid Number");
//      $("#invoiceno").val(""); 
//    }
//      if(checknum!=''){
//    var check_invoice_no = getUrl("<?php //echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=checkInvoiceNo', '',1);?>");
//         $.ajax({
//                url : check_invoice_no,
//        method : 'post',
//                data: {checknum:checknum},
//                success: function(response){
//                    if(response == $('#invoiceno').val()){
//                        alert('Your entered Invoice Number is already Inserted'); 
//             $("#invoiceno").val("");                         
//                    } 
//                }
//            });
//      }
//   });       
function removeInvoice(invoice_product_id,invoice_id)
{ 
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
    //alert(response);
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
  $("#product").change(function(){
      var val = $(this).val();
      var text = $("#product option[value='"+val+"']").text().toLowerCase();
      
      $("#wv").prop("disabled",false);
      jQuery("input[name='spout[]']").each(function(i) {
        jQuery(this).prop("disabled",false);
        if($(this).val()=='MQ==')
          $(this).prop('checked', 'checked');
        });
        jQuery("input[name='accessorie[]']").each(function(i) {
          jQuery(this).prop("disabled",false);
          if($(this).val()=='NA==')
            $(this).prop('checked', 'checked');
        });
      
      checkZipper();
      var zipper_id=$("input[class='zipper']:checked").val();
    });
function checkZipper(){
  var gusset_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=checkProductZipper', '',1);?>");
  var product_id = $('#product').val();
  $.ajax({
    type: "POST",
    url: gusset_url,
    dataType: 'json',
    data:{product_id:product_id}, 
    success: function(response) {
      $('#zipper_div').html(response);  
    }
  });
}

$("#invoice_update").click(function() {
  
  if($("#form").validationEngine('validate')){
  var update_invoice_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=updateInvoiceProduct', '',1);?>");
      var myform = $('#form');
  var disabled = myform.find(':disabled').removeAttr('disabled');
  var formData = myform.serialize();
  //alert(formData);
      $.ajax({
        url : update_invoice_url,
        method : 'post',    
        data : {formData : formData},
        success: function(response){
        //alert(response);
          if(response != 0){
           $("#invoice_results").html("");
            $("#invoice_results").html(response);
            $('#invoice_update').hide(); //TO hide
            $('#btn_save').show();
            $("#product_id").val('');
            $("#keyword").val('');
            $("#pro_ref_no").val(''); 
            $("#size").val('');
            $("#color").val('');
            $("#item_no").val('');
            $("#measurement").val('');
            $("#buyers_o_no").val('');
            $("#net_weight").val('');
            $("#qty").val('');
            $("#rate").val('');
            $('#myTable tr').not(':eq(0)').not(':eq(0)').remove(); 
            $("#measurement").val("");
            $("input:radio[name=zipper]:not(:disabled):first").attr('checked', true);
            $("input:radio[name=valve]:not(:disabled):first").attr('checked', true);
            $("input:radio[name=spout]:not(:disabled):first").attr('checked', true);
            $("input:radio[name=accessorie]:not(:disabled):first").attr('checked', true);

           //   window.location = "<?php //echo HTTP_SERVER.'admin/index.php?route=invoice_test&mod=add&&invoice_no='. $_GET['invoice_no'].'&inv_status=1' ?>";
          }
        },
        error: function(){
          return false;
        }
      });
  }
});

$("#btn_update").click(function() {
  /*if($("#invoiceno").val() == '' || $("#invoiceno").val() == 0)
  {
    alert("Please Insert Valide Invoice No.");
    $("#invoiceno").val("");
    return false;
  }*/
  var update_invoice_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=updateInvoiceRecord', '',1);?>");
  var myform = $('#form');
  //alert(myform)
  var disabled = myform.find(':disabled').removeAttr('disabled');
  var formData = myform.serialize();
      $.ajax({
        url : update_invoice_url,
        method : 'post',    
        data : {formData : formData},
        success: function(response){
          //console.log(response);
          if(response != 0){
             window.location = "<?php echo HTTP_SERVER.'admin/index.php?route=invoice_test&mod=index&inv_status=1' ?>";
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
          console.log(response);
            if(response != 0){
               $("#invoiceno").prop('disabled', true);
              $("#invoicedate").prop('disabled', true);
              $("#ref_no").prop('disabled', true);
              $("#buyersno").prop('disabled', true);
              $("#other_ref").prop('disabled', true);
              $("#country_final").prop('disabled', true);
              $("#pre_carrier").prop('disabled', true);
              $("#consignee").prop('disabled', true);
              $("#other_buyer").prop('disabled', true);
              $("#other_buyer").prop('disabled', true);
              $("#portofload").prop('disabled', true);
              $("#currency").prop('disabled', true);
              $("#vessel_name").prop('disabled', true);
              $("#port_of_dis").prop('disabled', true);
              $("#country_id").prop('disabled', true);
              $("#tran1").prop('disabled', true);
              $("#trans2").prop('disabled', true);
              $("#payment_terms").prop('disabled', true);
              $("#hscode").prop('disabled', true);
              $("#customer-name").prop('disabled', true);
              $("#email").prop('disabled', true);
              $("#delivery").prop('disabled', true);
              $("#printedpouches").prop('disabled', true);
              $("#pouch_desc").prop('disabled', true);
              $("#tran_desc").prop('disabled', true);
              $("#tran_charges").prop('disabled', true);
              $("#extra_tran_charges").prop('disabled', true);
              $("#remarks").prop('disabled', true);
              $("#netweight").val('');              
              $("#product").val('');
              $("#size").val('');
              $("#buyers_o_no").val('');
              $("#keyword").val('');
              $("#product_name").val('');
              $("#net_weight").val('');
              $("#color_product").val('');
              $("#item_no").val('');
              $("#measurement").val('');
              $("#qty").val('');
              $("#rate").val('');
              $("#dimension").val('');
              $('#myTable tr').not(':eq(0)').not(':eq(0)').remove();
              $("#net_measurement").val("");
              $("input:radio[name=zipper]:not(:disabled):first").attr('checked', true);
              $("input:radio[name=valve]:not(:disabled):first").attr('checked', true);
              $("input:radio[name=spout]:not(:disabled):first").attr('checked', true);
              $("input:radio[name=accessorie]:not(:disabled):first").attr('checked', true);
              $('input[name="taxation"]').attr('disabled', 'disabled');
              $('input[name="transport"]').attr('disabled', 'disabled');
              $("#form_div").prop('disabled', true);
              $("#tax_mode").prop('disabled', true);
              $("#form1").prop('disabled', true);
              $("#form2").prop('disabled', true);
              $("#form3").prop('disabled', true);
              $("#invoice_results").html(response)
            }
          },
          error: function(){
            return false;
          }
        });
    }
    else {      return false;
    }
});
function add_invoices() {
     $("#keyword").prop('disabled', true);
    $("#size").prop('disabled', true);
    $("#color_product").prop('disabled', true);
    $("#qty").prop('disabled', true);
    $("#rate").prop('disabled', true);
    $("#netweight").prop('disabled', true);
    $("#measurement").prop('disabled', true);
    $("#buyers_o_no").prop('disabled', true);
    $("#net_weight").prop('disabled', true);
    $("#item_no").prop('disabled', true);
}
/*function remove_tr(tr_id)
{ 
  var val=parseInt($("#net_weight_"+tr_id).val());
  $("#tr_"+tr_id).hide();
  $("#color_"+tr_id).find('option:selected').removeAttr("selected");
  var total = $('#netweight').val();
  var final = total-val;
  $('#netweight').val(final);
}
// check box form checked
function color(n)
{
  var val = $("#color_"+n).val();
  if(val==-1)
  {
    $("#size").removeClass("form-control validate[required,custom[number]]").addClass("form-control validate[custom[number]]");
    $("#measurement").removeClass("form-control validate[required]").addClass("form-control validate");
  }
  $("#color_txt_"+n).val('');
  $("#color_txt_"+n).hide();
  if(val == -1)
  {   
    $("#color_txt_"+n).show();
  }
}
function net_wt(n)
{ 
  var le= $('.nwt_wt').length;
  
  var new_val=0;
  for(var i=1;i <=le;i++){
    var c = i-1;
    var val1=$("#net_weight_"+c).val();
      new_val +=  parseFloat(val1);
    }
    var one= 1;
    $(".measure option[value=" + one +"]").attr("selected","selected");
  $('#netweight').val(new_val);
}
$('.addmore').click(function(){
    var html = '';
    var count = $('#myTable tr').length;
    var color = (count-1);
    var size_id = (color-1);
    var arrm = jQuery.parseJSON($('#mea_arr').val());
    var countAddmore = $('select#color_0 option#option').length;
    var arr = jQuery.parseJSON($('#color_arr').val());
      html +='<tr><td>';
      html +='<select name="color['+color+'][color]" id="color_'+color+'" class="form-control validate[required]"  onchange=color('+color+')><option value="">Select color</option>';
          for(var i=0;i<arr.length;i++)
          {
           html += '<option value="'+arr[i].pouch_color_id+'">'+arr[i].color+'</option>';
          }
      html +='<option value="-1" id="option">Custom</option>';
      html +='</select>';             
      html +='</td>';
        html +='<td>';
      html += '<input class="form-control" type="text" value="" name="color['+color+'][color_text]" id="color_txt_'+color+'" style="display:none">';
      html += '</td>';        
      html +='<td>';
      html +='<input type="text" name="color['+color+'][qty]" value="" class="form-control validate[required,custom[number],min[1]]" placeholder="Qty">';
      html +='</td>';     
      html +='<td>';
      html += '<input type="text" name="color['+color+'][rate]" value="" class="form-control validate[required,custom[number],min[0.001]]" placeholder="Rate">';
      html +='</td>'; 
      html +='<td>';
      html += '<input type="text" name="color['+color+'][size]" value="" class="form-control validate[required,custom[number]]" placeholder="Size">';
      html +='</td>';
    var arry = jQuery.parseJSON($('#mea_arr').val());       
      html +='<td>';
      html +='<select name="color['+color+'][measurement]" class="form-control validate[required]"><option value="">Select Measurement</option>';
          for(var i=0;i<arry.length;i++)
          {
             html += '<option value="'+arry[i].product_id+'">'+arry[i].measurement+'</option>';
          }
      html +='</select>';             
      html +='</td>';
      html +='<td><input type="text" name="color['+color+'][net_weight]" class="form-control validate[required,custom[number]] nwt_wt" placeholder="Net Weight" id="net_weight_'+color+'"  onchange="net_wt(0)"></td>';
      html +='<td ><input type="text" name="color['+color+'][dimension]" class="form-control validate[min[1]]" placeholder="dimension" id="dimension_'+color+'"><input type="hidden" name="color['+color+'][invoice_color_id]" class="form-control validate[min[1]]" placeholder="invoice_color_id" id="invoice_color_id_'+color+'"></td>';
      html +='<td>';
      html +='<a class="btn btn-danger btn-xs btn-circle remove" data-toggle="tooltip" data-placement="top" title="Remove" onclick="remove_tr('+color+')" ><i class="fa fa-minus"></i></a>';
      html +='</td></tr>';
      //alert(html);
    $('.tool-row').append(html);
    var color_id = (color-1);
    var option = $("#color_"+color_id+" option:selected").text();
    var id = $('#color_'+color_id+'').val();
    if(count >= countAddmore) {
      $("#addmore").hide();
    }
    var new_id = +id+1;
    $('.remove').click(function(){
      $("#addmore").show();
      $(this).parent().parent().remove();
    });   
});

*/
function frm_fill(){
  
  var custom_order_number=$('#custom_order_number').val();
  var stock_order_number=$('#stock_order_number').val();
  //alert(custom_order_number);
  if(custom_order_number!='' || stock_order_number!='')
  {
    $('#combination_div').hide();
    $('#save_btn_div').hide();
  
    var pop_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getCustomData', '',1);?>");
    $.ajax({
      method: "POST",         
      url: pop_url,
      data : {custom_order_number : custom_order_number, stock_order_number:stock_order_number},
      success: function(response){  
        //alert(response);
        console.log(response);
        if(response=='')
        {
          alert("Please Create New Custom Order");
          $("#result").html('');
        }
        else
          var val = $.parseJSON(response);
        
        //alert(val);

        if(val.response==1)
        {
          set_alert_message('Quotation No not Found',"alert-warning","fa-warning");   
          $("#qoute_generate").hide();  
          $('#combination_div').show();
          $('#save_btn_div').show();
        }
        else
        {         
          $("#result").html(val.response);  
          $("#generate_invoice").show();  
        }
        
        if(response=='')
          $("#qoute_generate").hide();
      },
      error: function(){
          return false; 
      }
    });
  }
  else
    $('#combination_div').show();
}

/*function frm_fill(){
  
  var custom_order_number=$('#custom_order_number').val();
  //alert(custom_order_number);
  if(custom_order_number!=''
  )
  {
    $('#combination_div').hide();
    $('#save_btn_div').hide();
  
    var pop_url = getUrl("<?php //echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getCustomData', '',1);?>");
    $.ajax({
      method: "POST",         
      url: pop_url,
      data : {custom_order_number : custom_order_number},
      success: function(response){  
        alert(response);
        if(response=='')
        {
          alert("Please Create New Custom Order");
          $("#result").html('');
        }
        else
          var val = $.parseJSON(response);

        if(val.response==1)
        {
          set_alert_message('Quotation No not Found',"alert-warning","fa-warning");   
          $("#qoute_generate").hide();  
          $('#combination_div').show();
          $('#save_btn_div').show();
        }
        else
        {         
          $("#result").html(val.response);  
          //$("#qoute_generate").show();  
        }
        
        if(response=='')
          $("#qoute_generate").hide();
      },
      error: function(){
          return false; 
      }
    });
  }
  else
    $('#combination_div').show();
}*/
function updateRate(invoice_color_id){
  
  var invoice_rate=$('#invoice_rate'+invoice_color_id).val();
  //  alert(invoice_rate);
    var qty_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=updateRateForCustomOrder', '',1);?>");
    $.ajax({
      url : qty_url,
      type :'post',
      data :{invoice_rate:invoice_rate,invoice_color_id:invoice_color_id},
      success: function(response){
        //alert(response);
      
        set_alert_message('Successfully Updated',"alert-success","fa-check");
          window.setTimeout(function(){location.reload()},500);         
      },
      error:function(){
        set_alert_message('Error During Updation',"alert-warning","fa-warning");          
      }           
    });
  
}function updateCylinderRate(invoice_color_id){
  
  var cylinder_rate=$('#cylinder_rate'+invoice_color_id).val();
  //  alert(invoice_rate);
    var qty_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=updateCylinderRate', '',1);?>");
    $.ajax({
      url : qty_url,
      type :'post',
      data :{cylinder_rate:cylinder_rate,invoice_color_id:invoice_color_id},
      success: function(response){
        //alert(response);
      
        set_alert_message('Successfully Updated',"alert-success","fa-check");
          window.setTimeout(function(){location.reload()},500);         
      },
      error:function(){
        set_alert_message('Error During Updation',"alert-warning","fa-warning");          
      }           
    });
  
}function updateCylinderNo(invoice_color_id){
  
  var no_of_cylinder=$('#no_of_cylinder'+invoice_color_id).val();
  //  alert(invoice_rate);
    var qty_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=updateCylinderNo', '',1);?>");
    $.ajax({
      url : qty_url,
      type :'post',
      data :{no_of_cylinder:no_of_cylinder,invoice_color_id:invoice_color_id},
      success: function(response){
        //alert(response);
      
        set_alert_message('Successfully Updated',"alert-success","fa-check");
          window.setTimeout(function(){location.reload()},500);         
      },
      error:function(){
        set_alert_message('Error During Updation',"alert-warning","fa-warning");          
      }           
    });
  
}
function frm_fill_stock(){
  
  var stock_order_number=$('#stock_order_number').val();
  //alert(custom_order_number);
  if(stock_order_number!='')
  {
    $('#combination_div').hide();
    $('#save_btn_div').hide();
  
    var pop_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getStockData', '',1);?>");
    $.ajax({
      method: "POST",         
      url: pop_url,
      data : {stock_order_number : stock_order_number},
      success: function(response){  
      //  alert(response);
        $("#result").html(response);
        if(response=='')
        {
          alert("Please Create New Stock Order");
          $("#result").html('');
        }
        else
          var val = $.parseJSON(response);


        if(val.response==1)
        {
          set_alert_message('Stock Order No not Found',"alert-warning","fa-warning");   
          $("#qoute_generate").hide();  
          $('#combination_div').show();
          $('#generate_invoice').show();
        }
        else
        {         
          $("#result").html(val.response);  
          //$("#qoute_generate").show();  
        }
        
        if(response=='')
          $("#qoute_generate").hide();
      },
      error: function(){
          return false; 
      }
    });
  }
  else
    $('#combination_div').show();
}
function change_price(invoice_id,buyer_no)
{
    $("#form_con1").modal('show');
    $("#form_invoice_id").val(invoice_id);
    $("#form_buyers_no").val(buyer_no);
}



$("#keyword").focus();
    var offset = $("#keyword").offset();
    var width = $("#holder").width();
    $("#ajax_response").css("width",width);
    
    $("#keyword").keyup(function(event){
     var keyword = $("#keyword").val();
     if(keyword == 'Cylinder' || keyword == 'cylinder' || keyword == 'CYLINDER')
     {  
        $("#product_name").hide();
        $("#product_code_id").val('0');
      
        $("#product_id").val('');
        $("#real_product_name").val('Cylinder');
        $('#size').removeAttr("readonly", "readonly");
        $('#measurement').removeAttr("readonly", "readonly");
        $('#color_product').removeAttr("readonly", "readonly");
     }
     else if(keyword == 'Custom' || keyword == 'custom' || keyword == 'CUSTOM')
     {  
      //alert('hi');
        $("#product_name").hide();
        $("#product_code_id").val('-1');
        $("#color_txt").show();
     
        $("#product_id").val('');
        $("#real_product_name").val('Custom');
        $('#size').removeAttr("readonly", "readonly");
        $('#measurement').removeAttr("readonly", "readonly");
        $('#color_product').removeAttr("readonly", "readonly");
     }
     else if(keyword.length)
     {  
      
      $("#size").attr("readonly","readonly");
      $("#measurement").attr("readonly",true);
      $("#color_product").attr("readonly",true);
      $("#color_txt").hide();
      $("#product_name").show();
      //alert('hii');
       if(event.keyCode != 40 && event.keyCode != 38 )
       { 
          
         var product_code_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=product_code', '',1);?>");
         // alert(product_code_url);
         $("#loading").css("visibility","visible");
         $.ajax({
           type: "POST",
           url: product_code_url,
           data: "product_code="+keyword,
           success: function(msg){
        
       
           var msg = jQuery.parseJSON((msg));
      //     console.log(msg);
           var div='<ul class="list">';
           
          if(msg.length>0)
          {
            for(var i=0;i<msg.length;i++)
            { 
            //   console.log(msg[i]);
              div =div+'<li><a href=\'javascript:void(0);\' discr="'+msg[i].description+'" size="'+msg[i].volume+'" mea="'+msg[i].measurement+'" color_name="'+msg[i].pouch_color_id+'" valve="'+msg[i].valve+'" accessorie="'+msg[i].accessorie+'" make_pouch="'+msg[i].make_pouch+'" spout="'+msg[i].spout+'"spout="'+msg[i].spout+'" zipper="'+msg[i].zipper+'" product_id="'+msg[i].product+'" product_name="'+msg[i].product_name+'"  id="'+msg[i].product_code_id+'"><span class="bold" >'+msg[i].product_code+'</span></a></li>';     

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
              $("#color_product").val($(".list li[class='selected'] a").attr("color_name"));
              $("#real_product_name").val($(".list li[class='selected'] a").attr("product_name"));
              $("#product_id").val($(".list li[class='selected'] a").attr("product_id"));
              $("#size").val($(".list li[class='selected'] a").attr("size"));
              $("#zipper").val($(".list li[class='selected'] a").attr("zipper"));
              $("#spout").val($(".list li[class='selected'] a").attr("spout"));
              $("#accessorie").val($(".list li[class='selected'] a").attr("accessorie"));
              $("#make").val($(".list li[class='selected'] a").attr("make_pouch"));
              $("#valve").val($(".list li[class='selected'] a").attr("valve"));
              $("#measurement").val($(".list li[class='selected'] a").attr("mea"));
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
              $("#color_product").val($(".list li[class='selected'] a").attr("color_name"));
              $("#real_product_name").val($(".list li[class='selected'] a").attr("product_name"));
              $("#product_id").val($(".list li[class='selected'] a").attr("product_id"));
              $("#size").val($(".list li[class='selected'] a").attr("size"));
              $("#zipper").val($(".list li[class='selected'] a").attr("zipper"));
              $("#spout").val($(".list li[class='selected'] a").attr("spout"));
              $("#accessorie").val($(".list li[class='selected'] a").attr("accessorie"));
              $("#make").val($(".list li[class='selected'] a").attr("make_pouch"));
              $("#valve").val($(".list li[class='selected'] a").attr("valve"));
              $("#measurement").val($(".list li[class='selected'] a").attr("mea"));
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
          $("#color_product").val($(this).attr("color_name"));
        $("#real_product_name").val($(this).attr("product_name"));
          $("#product_id").val($(this).attr("product_id"));
        $("#size").val($(this).attr("size"));
          $("#measurement").val($(this).attr("mea"));
          $(this).addClass("selected");
           $("#product_code_id").val($(this).attr("id"));
          //getStockQty($(this).attr("id"));
      });
      $(this).find(".list li a:first-child").mouseout(function () {
          $(this).removeClass("selected");
      });
      $(this).find(".list li a:first-child").click(function () {
        //alert($(this).attr("color"));
          $("#product_div").show();
                  $("#product_name").val($(this).attr("discr"));
          $("#color_product").val($(this).attr("color_name"));
          $("#u_product_code").val('add');
          

          if($(this).attr("color_name")=='Custom')
          {
            $("#color_txt").show();
          <?php if($addedByInfo['country_id']=='155'){ ?>
            $("#express_rate").show();
            $("#exp_rate, #exp_rate1").show();
          <?php } ?>
          getGussetPrinting($("#product_id").val());
          
          }
          else
          {
            $("#color_txt").hide();
          <?php if($addedByInfo['country_id']=='155'){ ?>
            $("#express_rate").hide();
            $("#exp_rate, #exp_rate1").hide();
          <?php } ?>
          $("#gusset_printing").html('');
          } 
          $("#real_product_name").val($(this).attr("product_name"));
          $("#product_id").val($(this).attr("product_id"));

          $("#zipper").val($(this).attr("zipper"));
          $("#spout").val($(this).attr("spout"));
          $("#accessorie").val($(this).attr("accessorie"));
          $("#make").val($(this).attr("make_pouch"));
          $("#valve").val($(this).attr("valve"));
         
          $("#measurement").val($(this).attr("mea"));
          $("#product_code_id").val($(this).attr("id"));
          $("#keyword").val($(this).text());
          $("#ajax_response").fadeOut('slow');
          $("#ajax_response").html("");
      
        
        
      
        //sonu 30-6-2017
        var country_id = $("#country_id").val();
        //[kinjal] made cond for when user select custom product that it shown error so...[1-8-2017]
        if(country_id == '111' && $(this).attr("color")!='Custom'){
          getrate($(this).attr("id"));
        }
        else
        {
          $("#rate").val('');
        }
        });
        //add sonu for all country rate 25-8-2017
        $("#qty").val('');
        //end sonu
      //sonu end
      //console.log($(this).attr("product_id"));
      
    });

</script> 
<!-- Close : validation script -->
<?php } else { 
    include(DIR_ADMIN.'access_denied.php');
  }
?>