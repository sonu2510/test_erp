<?php
$_GET['inv_status']=0;
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
  'href'  => $obj_general->link($rout, 'mod=index&inv_status='.isset($_GET['inv_status']) ?$_GET['inv_status']:'' , '',1),
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
if(isset($_GET['invoice_id']) && !empty($_GET['invoice_id']) && !isset($_GET['invoice_product_id']) && empty($_GET['invoice_product_id'])){  
    $invoice_no = decode($_GET['invoice_id']);
    //echo $invoice_no;
    $invoice = $obj_invoice->getSalesInvoiceData($invoice_no);
 //   printr($invoice);
    $invoice_id = $invoice['sales_invoice_id'];
    //printr($invoice['taxation']);
    $edit = 1;
}
else if(isset($_GET['invoice_id']) && !empty($_GET['invoice_id']) && isset($_GET['invoice_product_id']) && !empty($_GET['invoice_product_id'])){
  $invoice_product_id = decode($_GET['invoice_product_id']);
  

   $invoice_no = decode($_GET['invoice_id']);
  $invoice = $obj_invoice->getSalesInvoiceData($invoice_no);
  $invoice_product = $obj_invoice->getInvoiceProductId($invoice_product_id);
    $edit = 1;
// printr($invoice_product);die;
  
}
else if(isset($_GET['invoice_status']) && !empty($_GET['invoice_status'])){  
    
  $user_type_id = $_SESSION['LOGIN_USER_TYPE'];
$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
    $invoice['invoice_status'] = $_GET['invoice_status'];
      $last_inv_no = $obj_invoice->getLastIdSalesInvoice($user_type_id,$user_id);
	   $invoice_no=$last_inv_no+1;
	 //  printr($invoice_no);
	  $invoice['challan_no']=$invoice['invoice_no']=$invoice['exp_inv_no']=$invoice_no;
    
}
//printr($invoice['invoice_status']);
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


if(isset($_POST['btn_update'])){
  //  printr($_POST);die;
     $insert_id = $obj_invoice->updatesalesinvoice($_POST,$invoice_id);
     	page_redirect($obj_general->link($rout, '', '',1));
   
     
}else if(isset($_POST['btn_update_product'])){
    //printr($_POST);die;
     $insert_id = $obj_invoice->updatesalesinvoiceRoll($_POST,$invoice_no);
     	page_redirect($obj_general->link($rout, 'mod=add&invoice_id='.encode($insert_id), '',1));
   
     
}else if(isset($_POST['btn_add'])){
 //   printr($_POST);die;
     $insert_id = $obj_invoice->addRollsalesinvoice($_POST);
    page_redirect($obj_general->link($rout, 'mod=add&invoice_id='.encode($insert_id), '',1));
   
   
     
}


if($display_status){  
    $city= $obj_invoice->getCityName();
    $countries = $obj_invoice->getCountry();
    $measurement = $obj_invoice->getMeasurement();
    $colors = $obj_invoice->getColor();
     //$sales_invoice_id = $obj_invoice->getLastId();
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
                     <div id="invoice_div">
                        <div class="form-group">
                           <label class="col-lg-3 control-label"><span class="required">*</span>INVOICE NO</label>
                           <div class="col-lg-3"> 
                              <input type="hidden" name="generate_status" id="generate_status" value="1"  >                        
                              <input type="hidden" name="edit" id="edit" value="<?php echo $edit ; ?>"  >                        
                              <input type="hidden" name="transport" id="transport" value="<?php echo $invoice['transport']; ?>"  >  
                              <input type="hidden" name="invoice_status" id="invoice_status" value="<?php echo $invoice['invoice_status']; ?>"  >                        
                              <input type="text" name="invoiceno" id="invoiceno" value="<?php echo isset($invoice) ? $invoice['invoice_no'] : '' ; ?>"  class="form-control" <?php /*?> <?php if(isset($_GET['invoice_no'])) echo 'disabled="disabled"'; ?><?php */?>>
              
                              <input type="hidden" name="admin_email" id="admin_email" value="<?php echo ADMIN_EMAIL ; ?>" />
                           </div>
                           <label class="col-lg-3 control-label">INVOICE DATE</label>
                           <div class="col-lg-3">
                              <input type="text" name="invoicedate" readonly  data-date-format="yyyy-mm-dd" value="<?php if(isset($invoice['invoice_date'])) { echo $invoice['invoice_date']; }else{ echo date("Y-m-d"); } ?>" placeholder="Invoice Date" id="invoice_date" class="input-sm form-control datepicker" />
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-lg-3 control-label"><span class="required">*</span>CHALLAN NO</label> 
                           <div class="col-lg-3">
                              <input type="text" name="challan_no"  id="challan_no" value="<?php echo isset($invoice) ? $invoice['challan_no'] : '' ; ?>" placeholder="CHALLAN NO" class="form-control">
                           </div>
                           <label class="col-lg-3 control-label">  CHALLAN DATE </label>
                           <div class="col-lg-3">
                              <input type="text" name="challan_date" readonly  data-date-format="yyyy-mm-dd" value="<?php if(isset($invoice['challan_date'])) { echo $invoice['challan_date']; }else{ echo date("Y-m-d"); } ?>" placeholder="Invoice Date" id="challan_date" class="input-sm form-control datepicker" />
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-lg-3 control-label"><span class="required">*</span>EXP INV NO</label>
                           <div class="col-lg-3">
                              <input type="text" name="exp_inv_no" id="exp_inv_no" value="<?php echo isset($invoice) ? $invoice['exp_inv_no'] : '' ; ?>"  class="form-control ">
                           </div>
                           <label class="col-lg-3 control-label">EXP DATE</label>
                           <div class="col-lg-3">
                              <input type="text" name="exp_date" readonly  data-date-format="yyyy-mm-dd" value="<?php if(isset($invoice['exp_date'])) { echo $invoice['exp_date']; }else{ echo date("Y-m-d"); } ?>" placeholder="Invoice Date" id="exp_date" class="input-sm form-control datepicker" />
                           </div>
                        </div> 
                        <?php //printr($invoice); ?>
                        <div class="form-group">
                           <label class="col-lg-3 control-label"><span class="required">*</span>BUYERS ORDER NO</label>
                           <div class="col-lg-3">
                              <input type="text" name="buyers_orderno" id="buyers_orderno" value="<?php echo isset($invoice) ? $invoice['buyers_orderno'] : '' ; ?>"  class="form-control ">
                           </div>
                           <label class="col-lg-3 control-label">BUYERS ORDER  DATE</label>
                           <div class="col-lg-3">
                              <input type="text" name="buyers_order_date"   data-date-format="yyyy-mm-dd" value="<?php if(isset($invoice['buyers_order_date'])) { echo $invoice['buyers_order_date']; }else{ echo date("Y-m-d"); } ?>" placeholder="BUYERS ORDER  DATE" id="buyers_order_date" class="input-sm form-control datepicker" />
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-lg-3 control-label">CUSTOMER NAME</label>
                           <div class="col-lg-3">
                              <input type="text" name="customer_name"  value="<?php echo isset($invoice) ? $invoice['customer_name'] : '' ; ?>" placeholder="Customer Name" id="customer-name" class="form-control " />
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-lg-3 control-label"> <span class="required">*</span>  <?php if($addedByInfo['country_id']=='111') echo 'CONSIGNEE'; else echo ' CUSTOMER ADDRESS';?></label>
                           <div class="col-lg-8">
                              <textarea class="form-control " rows="2" cols="45" name="consignee" id="consignee"><?php echo isset($invoice) ? $invoice['consignee'] : '' ;?></textarea>
                           </div>
                        </div>
                         <div class="form-group" id="same_as_above">
                         <label class="checkbox-custom  m-l-small pull-right" style="font-size:14px;">
                             		<input type="checkbox" name="same_as_above" id="same-above" value="1" <?php if(isset($invoice) && ($invoice['same_as_above'] == '1')) { ?> checked="checked" <?php } ?>>
                                 <i class="fa fa-square-o"></i> Same as Above? </label> 
                         </div>
                        <?php if($addedByInfo['country_id']=='111') {?>
                        <div class="form-group">
                           <label class="col-lg-3 control-label"> BUYER (IF OTHER THAN CONSIGNEE)</label>
                           <div class="col-lg-8">
                              <textarea class="form-control" id="other_buyer" name="other_buyer"><?php echo isset($invoice) ? $invoice['other_buyer'] : '' ; ?></textarea>
                           </div>
                        </div>
                        <?php }?>
                        <div class="form-group">
                           <label class="col-lg-3 control-label">FINAL DESTINATION</label>
                           <div class="col-lg-3">
                              <?php if(isset($addedByInfo['country_id']) && $addedByInfo['country_id']){
                                 $selCountry = $addedByInfo['country_id'];
                                 }
                                 $sel_country = isset($invoice['country_id'])?$invoice['country_id']:$addedByInfo['country_id'];   
                                 $countrys = $obj_general->getCountryCombo($sel_country);
                                 echo $countrys;
                                 ?>
                           </div>
                           <label class="col-lg-3 control-label"><span class="required">*</span>HS CODE</label>
                           <div class="col-lg-3">
                              <input type="text" name="hscode" id="hscode" value="<?php echo isset($invoice) ? $invoice['hscode'] : '' ; ?>" class="form-control "/>
                           </div>
                        </div>
                     <div class="form-group state_list">
              		<label class="col-lg-3 control-label"><span class="required">*</span>State</label>
                	<div class="col-lg-2">
                	    <?php $state_india = $obj_invoice->getIndiaState(); ?>
                	    <select name="state_id" id="state_id" class="form-control" >
                                <option value="">Select State</option>
                                <?php foreach($state_india as $state)
                                { ?>
                                    <option value="<?php echo $state['state_id']; ?>" <?php if(isset($invoice) && $invoice['state_id'] == $state['state_id'] ) { ?> selected="selected" <?php } ?> ><?php echo $state['state']; ?></option>
                                <?php } ?>
                            </select>
                  	</div>
              </div>
                        <div class="form-group option">
                           <label class="col-lg-3 control-label">GST NO</label>
                           <div class="col-lg-4">                
                              <input type="text" name="gst_no" id="gst_no" value="<?php echo isset($invoice) ? $invoice['gst_no'] : '' ; ?>"  class="form-control " />
                           </div>
                        </div>
                        <div class="form-group" id="tax_div">
                           <label class="col-lg-3 control-label">Tax</label>
                           <div class="col-lg-8">
                              <div id="normal_div" style="float:left;width: 200px;">
                                 <label  style="font-weight: normal;">
                                 <input type="radio" name="taxation" id="taxation_frm" value="SEZ Unit No Tax" <?php if(isset($invoice) && 
                                    ($invoice['taxation']== 'SEZ Unit No Tax')) { ?> checked="checked" <?php } ?>  checked="checked"> SEZ Unit No Tax </label> 
                              </div>
                              <div id="normal_div" style="float:left;width: 200px;"> 
                                 <label  style="font-weight: normal;">
                                 <input type="radio" name="taxation" id="taxation_nrm" value="With in Gujarat"  <?php if(isset($invoice) && ($invoice['taxation'] == 'With in Gujarat')) { ?> checked="checked" <?php } ?>> With In Gujarat</label>
                              </div>
                              <div id="form_div" style="float:left;width: 200px;">
                                 <label style="font-weight: normal;">
                                 <input type="radio" name="taxation" id="taxation_frm" value="Out Of Gujarat" <?php 
                                    if(isset($invoice) && ($invoice['taxation'] == 'Out Of Gujarat')) { ?> checked="checked" <?php } ?> >Out Of Gujarat </label>
                              </div>
                           </div>
                        </div>
                        <div class="form-group">
                           <label class="col-lg-3 control-label"><span class="required">*</span>  CURRENCY</label>
                           <div class="col-lg-3">
                              <?php $currency = $obj_invoice->getCurrency();?>
                              <select name="currency" id="currency" class="form-control validate[required]" >
                                 <option value="">Select Currency</option>
                                 <?php foreach($currency as $curr){ ?>
                                 <option value="<?php echo $curr['currency_id']; ?>" <?php if(isset($_GET['invoice_id']) && $curr['currency_id'] == $invoice['currency']) { ?> selected="selected" <?php } ?>><?php echo $curr['currency_code']; ?></option>
                                 <?php   } ?>
                              </select>
                           </div>
                        </div>
                      
                        <div class="form-group">
                           <label class="col-lg-3 control-label">CURRENCY RATE</label>
                           <div class="col-lg-3">                 
                              <input type="text" name="currency_rate" value="<?php echo isset($invoice) ? $invoice['currency_rate'] : '' ; ?>"  class="form-control " id="currency_rate" />
                           </div>
                        </div>
                        <?php if(($invoice['transport'] == 'air' || $invoice['transport'] == 'road') && ($invoice['invoice_status'] == '0')){
                           $transport_line ='LUT WITHOUT PAYMENT OF IGST';
                              if($invoice['invoice_date']>='2018-04-01'){
                                  $value='LETTER OF UNDERTAKING APPLICATION REFERENCE NUMBER-AD240319004221P';
                              }else{
                                    $value='LETTER OF UNDERTAKING APPLICATION REFERENCE NUMBER-AA2403180293296';    
                            }
                           }else if($invoice['transport'] == 'sea'){
                           $transport_line ='EXPORT AGAINST PAYMENT OF IGST';
                               if($invoice['invoice_date']>'2018-10-21'){
					                 $value='THIS SHIPMENT IS TAKEN UNDER THE EPCG LICENCE LICENCE NO.3430003005 DATED 23.01.2017';
					             }
                               else  if($invoice['invoice_date']>'2018-07-06'){
						                $value='THIS SHIPMENT IS TAKEN UNDER THE EPCG LICENCE LICENCE NO.3430002776 DATED 01.12.2015';
						            }
                           }else{
                                $transport_line ='LUT WITHOUT PAYMENT OF IGST';
                                $value='';
                           }?>
                        <div class="form-group">
                           <label class="col-lg-3 control-label"><?php echo $transport_line;?></label>
                           <div class="col-lg-8">
                              <textarea class="form-control" id="tran_desc" name="tran_desc"><?php echo $value;?></textarea>
                           </div>
                        </div> 
                       
                        <div class="form-group option">
                           <label class="col-lg-3 control-label"> PRINTED POUCH TYPE</label>
                           <div class="col-lg-9">                
                              <input type="text" name="printedpouches" id="printedpouches" value="<?php echo isset($invoice) ? $invoice['printedpouches'] : 'PRINTED OR UNPRINTED FLEXIBLE PACKAGING MATERIAL OF POUCHES' ; ?>"  class="form-control " />
                           </div>
                        </div>
                       
                        <div class="form-group option">
                           <label class="col-lg-3 control-label">FREIGHT CHARGE</label>
                           <div class="col-lg-3">                
                              <input type="text" name="tran_charges" value="<?php echo isset($invoice) ? $invoice['tran_charges'] : '' ; ?>"  class="form-control " id="tran_charges" />
                           </div>
                           <label class="col-lg-3 control-label"> CYLINDER MAKING CHARGES</label>
                           <div class="col-lg-3">                
                              <input type="text" name="cylinder_charges" value="<?php echo isset($invoice) ? $invoice['cylinder_charges'] : '' ; ?>"  class="form-control " id="cylinder_charges" />
                           </div>
                        </div>
                     </div>
                     <div class="form-group option">
                        <label class="col-lg-3 control-label"> DESPATCH</label>
                        <div class="col-lg-4">                
                           <input type="text" name="despatch" id="despatch" value="<?php echo isset($invoice) ? $invoice['despath'] : '' ; ?>"  class="form-control " />
                        </div>
                     </div>
                     <div class="form-group option">
                        <label class="col-lg-3 control-label"> LR NO./DT</label>
                        <div class="col-lg-4">                
                           <input type="text" name="lr_no" id="lr_no" value="<?php echo isset($invoice) ? $invoice['lr_no'] : '' ; ?>"  class="form-control " />
                        </div>
                     </div>
                     <div class="form-group option">
                        <label class="col-lg-3 control-label">VEHICLE NO</label>
                        <div class="col-lg-4">                
                           <input type="text" name="vehicle_no" id="vehicle_no" value="<?php echo isset($invoice) ? $invoice['vehicle_no'] : '' ; ?>"  class="form-control " />
                        </div>
                     </div>
                     <div class="form-group option">
                        <label class="col-lg-3 control-label">CONTAINER NO</label>
                        <div class="col-lg-4">                
                           <input type="text" name="container_no" id="container_no" value="<?php echo isset($invoice) ? $invoice['container_no'] : '' ; ?>"  class="form-control " />
                        </div>
                     </div>
                     <div class="form-group option">
                        <label class="col-lg-3 control-label">SEAL NO</label>
                        <div class="col-lg-4">                
                           <input type="text" name="seal_no" id="seal_no" value="<?php echo isset($invoice) ? $invoice['seal_no'] : '' ; ?>"  class="form-control " />
                        </div>
                     </div>
                     <div class="form-group option">
                        <label class="col-lg-3 control-label">RFID NO</label>
                        <div class="col-lg-4">                
                           <input type="text" name="rfid_no" id="rfid_no" value="<?php echo isset($invoice) ? $invoice['rfid_no'] : '' ; ?>"  class="form-control " />
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-lg-3 control-label">PALLET DETAILS</label>
                        <div class="col-lg-8">
                           <textarea class="form-control" id="pallet_detail" name="pallet_detail"><?php echo $invoice['pallet_detail'];?></textarea>
                        </div> 
                     </div>
                      <div class="form-group">
                           <label class="col-lg-3 control-label">Remarks</label>
                           <div class="col-lg-8">
                              <textarea class="form-control" id="remark" name="remark"><?php echo $invoice['remark']; ?></textarea>
                           </div>
                        </div>
            <?php //if($user_id=='1' && $user_type_id=='1'){?>
              <div class="form-group">
                       <label class="col-lg-3 control-label"><b style="color:#ff0000"> Do you want to Add IGST In Invoice ?</b></label>
                        <div class="col-lg-4">
                                 <select name="igst_status" id="igst_status" class="form-control validate[required]" >
                                    <option value="">Select IGST</option>
                                    <option value="1" <?php if(isset($invoice) &&  $invoice['igst_status']=='1') { ?> selected="selected" <?php } ?>>Yes</option>
                                    <option value="0">No</option>
                                </select>
                         </div>
                </div>
                     <?php //}?>   
                        
                     <div class="line line-dashed m-t-large"></div>
                     
                     <?php $disable = '';
                         /*   if(isset($_GET['invoice_id'])){
                                $disable='disabled';}*/
                                ?>
                     <div class="form-group">
                           <label class="col-lg-3 control-label">Product Option</label>
                           <div class="col-lg-5">
                              <div id="" style="float:left;width: 200px;">
                                 <label style="font-weight: normal;">
                                 <input type="radio" name="pro_option" id="pro_option" <?php echo $disable;?> value="0" <?php if(isset($invoice) && $invoice['product_option'] == '0') { ?> checked="checked" <?php } else { ?>checked="checked" <?php } ?>>Scrap & Roll</label> 
                              </div>
                              <!--<div id="" style="float:left;width: 200px;">
                                 <label style="font-weight: normal;">
                                 <input type="radio" name="pro_option" id="pro_option" <?php echo $disable;?> value="1" <?php //if(isset($invoice) && $invoice['product_option'] == '1') { ?> checked="checked" <?php //} ?> >Other Products</label> 
                              </div>-->
                           </div>
                        </div>
                     
                     <?php if(($_GET['invoice_status']=='2')|| isset($_GET['invoice_product_id']) ){
                         
                   
                         $product_details=array();
                         $measurement=array();
                        $product_details=$obj_invoice->getActiveProduct();
                        $measurement = $obj_invoice->getMeasurement();
                        ?>
                     <div class="form-group">
                        <label class="col-lg-3 control-label">Add Roll Details</label> 
                        <div class="col-lg-7" style="width: 75%;">
                           <section class="panel">
                              <div class="table-responsive">
                                 <table class="tool-row table-striped  b-t text-small" id="myTable">
                                    <thead>
                                       <tr>
                                          <th>Product</th>
                                          <th>Description</th>
                                          <th>Qty</th>
                                          <th>Rate </th>
                                          <th>Size</th>
                                          <th>Measurement</th>
                                          <th>Net Weight<br />
                                             (In Kg.)
                                          </th>
                                          <th></th>
                                       </tr>
                                    </thead>
                                    <tbody  id="myTbody">
                                       <?php  $count=1;
                                         // printr($invoice_product);?>           
                                       <tr class="multiplerows-<?php echo $count; ?> " id="multiplerows-<?php echo $count; ?> ">
                                          <input type="hidden" name="addroll[<?php echo $count;?>][sales_invoice_product_id]" value="<?php echo isset($invoice_product) ? $invoice_product['sales_invoice_product_id'] : '' ; ?>" class="form-control "  id="addroll[<?php echo $count;?>][sales_invoice_product_id]">  
                                          <td>
                                             <select name="addroll[<?php echo $count;?>][product]" id="addroll[<?php echo $count;?>][product]" class="hide_select form-control validate[required]" <?php if(isset($invoice) && $invoice['product_option'] == '1') { ?> style="display:block;" <?php } else { ?>style="display:none;" <?php } ?> >
                                                <option value="">Select Product </option>
                                                <?php foreach($product_details as $product){ ?>
                                                    <option value="<?php echo $product['product_id']; ?>" id="option" <?php if(isset($invoice_product) && $product['product_id'] == $invoice_product['product_id']) { ?> selected="selected" <?php } ?>><?php echo $product['product_name']; ?></option>
                                                <?php } ?>                                                      
                                             </select>
                                             
                                        	  
                                                <div class="hide_text" onclick="open_tab(<?php echo $count; ?>)"  <?php if(isset($invoice) && $invoice['product_option'] == '0') { ?> style="display:block;" <?php } else { ?>style="display:none;" <?php } ?>>
                                                    <?php //$product_codes=$obj_invoice->getActiveProductCode(); 
                									    //$pro_code= htmlspecialchars(json_encode($product_codes), ENT_QUOTES, 'UTF-8');
                							        ?>
                                                   <input type="hidden" id="min_arr" value='<?php //echo $pro_code;?>' />
                                                   <select name="addroll[<?php echo $count;?>][keyword]" id="addroll<?php echo $count;?>" class="form-control validate[required] chosen_data">
                                                        <option value="">Select Product</option>
                                                        <?php /* foreach($product_codes as $code) 
                                                           {
                                                               if($code['product_code_id']==$invoice_product['product_code_id'])
                                                                  echo '<option value="'.$code['product_code_id'].'" selected="selected">'.$code['product_code'].'</option>';
                                                               else
                                                                  echo '<option value="'.$code['product_code_id'].'">'.$code['product_code'].'</option>';
                                                           }*/
                                                           ?>
                                                    </select>
                                                    <input type="hidden" name="addroll[<?php echo $count;?>][product_id]" id="addroll_product_<?php echo $count;?>" value="<?php echo isset($invoice_product['product_id'])?$invoice_product['product_id']:''?>" />
                                                </div>
                                          </td>
                                          <td><input type="text" name="addroll[<?php echo $count;?>][description]" value="<?php echo isset($invoice_product) ? $invoice_product['description'] : '' ; ?>" class="form-control " placeholder="Description" id="addroll_description_<?php echo $count;?>">
                                          <td><input type="text" name="addroll[<?php echo $count;?>][qty]" value="<?php echo isset($invoice_product) ? $invoice_product['qty'] : '' ; ?>" class="form-control " placeholder="Qty" id="addroll[<?php echo $count;?>][rate]"></td>
                                          <td><input type="text" name="addroll[<?php echo $count;?>][rate]" value="<?php echo isset($invoice_product) ? $invoice_product['rate'] : '' ; ?>" class="form-control " placeholder="Rate" id="addroll[<?php echo $count;?>][rate]"></td>
                                          <td><input type="text" name="addroll[<?php echo $count;?>][size]"  value="<?php echo isset($invoice_product) ? $invoice_product['size'] : '' ; ?>" class="form-control " placeholder="Size" id="addroll_size_<?php echo $count;?>"></td>
                                          <td>
                                             <select name="addroll[<?php echo $count;?>][measurement]" id="addroll_measurement_<?php echo $count;?>" class="form-control " >
                                                <option value="">Select Measurement</option>
                                                <?php foreach($measurement as $meas){ ?>
                                                <option value="<?php echo $meas['product_id']; ?>" <?php if(isset($invoice_product) && $meas['product_id'] == $invoice_product['measurement']) { ?> selected="selected" <?php } ?>><?php echo $meas['measurement']; ?></option>
                                                <?php   }  ?>
                                             </select>
                                          </td>
                                          <td><input type="text" name="addroll[<?php echo $count;?>][net_weight]" value="<?php echo isset($invoice_product) ? $invoice_product['size'] : '' ; ?>" class="form-control " placeholder="Net Weight" id="addroll[<?php echo $count;?>][net_weight]"></td>
                                          <?php if($count==1 && empty($invoice_product) ){ ?>
                                          <td>
                                             <a  onclick="add_row(<?php echo $count; ?>)"class="btn btn-success btn-xs btn-circle addmore" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add Roll" id="addmore_<?php echo $count; ?>"><i class="fa fa-plus"></i></a>
                                          </td>
                                          <?php
                                             } else{
                                             
                                             ?>
                                          <td>
                                             <button type="submit" name="btn_update_product" id="btn_update_product" class="btn btn-primary">Update </button>
                                          </td>
                                          <?php }?>      
                                       </tr>
                                    </tbody>
                                 </table>
                              </div>
                           </section>
                        </div>
                     </div>
                     <?php }?>
                     <div id="result"></div>
                     <div id="invoice_results">
                        <?php 
                           //   printr($invoice);
                             if(isset($_GET['invoice_id']) && !empty($_GET['invoice_id'])) {
                                  $invoice_product_second = $obj_invoice->getSalesInvoiceProduct($invoice_no);
                                  
                            
                          if($invoice['invoice_status']=='2'  ){
                                  // printr($invoice_no);   
                            if(!empty($invoice_product_second)){       
                                   ?>
                        <table class="table table-bordered">
                           <thead>
                              <tr>
                                 <th>Product Details</th>
                                 <th>Size</th>
                                 <th>Quantity</th>
                                 <th>Rate</th>
                                 <th>Action</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php foreach($invoice_product_second as $product){
                                $measure = $obj_invoice->getMeasurementName($product['measurement']);
                                 if(!empty($measure)){
                                   $size= $product['size'].' '.$measure['measurement'];
                                  }else{
                                      $size="";
                                  }
                              ?>
                              
                              
                              <tr>
                                 <td><?php  echo $product['product_name'].'<br>'.$product['color_text']; ?></td>
                                 <td><?php  echo $size; ?></td>
                                 <td><?php  echo $product['qty']; ?> KGS </td>
                                 <td><?php  echo $product['rate']; ?></td>
                                 <td class="del-product">
                                    <a class="btn btn-danger btn-sm" href="javascript:void(0);" onClick="removeInvoice(<?php echo $product['sales_invoice_product_id'].','.$product['sales_invoice_id']; ?>)"><i class="fa fa-trash-o"></i></a>
                                    <a href="<?php echo $obj_general->link($rout, 'mod=add&invoice_id='.encode($product['sales_invoice_id']).'&invoice_product_id='.encode($product['sales_invoice_product_id']),'',1); ?>" id="btn_edit"  name="btn_edit" class="btn btn-info btn-xs btn_edit">Edit</a>
                                 </td>
                              </tr>
                              <div class="modal fade" id="alertbox_<?php echo $product['sales_invoice_product_id']; ?>">
                                 <div class="modal-dialog">
                                    <div class="modal-content">
                                       <div class="modal-header">
                                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                          <h4 class="modal-title">Title</h4>
                                       </div>
                                       <div class="modal-body">
                                          <p id="setmsg">Are you sure you want to delete ?</p>
                                       </div> 
                                       <div class="modal-footer">
                                          <button type="button" id="popbtncan" class="btn btn-default" data-dismiss="modal">Close</button>
                                          <button type="button" name="popbtnok" id="popbtnok_<?php echo $product['sales_invoice_product_id']; ?>" class="btn btn-primary">Ok</button>
                                       </div>
                                    </div>
                                    <!-- /.modal-content -->
                                 </div>
                                 <!-- /.modal-dialog -->
                              </div>
                              <?php }?>
                           </tbody>
                        </table>
                        <?php } }else{ ?>
                       <div class="table-responsive">
                        <table class="table table-bordered">
                           <thead>
                              <tr>
                                 <th>Product Name</th>
                                 <th>Size</th>
                                 <th>Color  </th>
                                 <th>Quantity</th>
                                 <th>Rate</th>
                                 <th>Option</th>
                                 <th>Action</th>
                              </tr>
                           </thead>
                           <tbody>
                              <?php 
                                 if($invoice_product_second!='')
                                 {
                                   $i =0;
                                   
                                 foreach($invoice_product_second as $invoice_d ) {
                                   
                                  if($invoice['invoice_status']=='1'){
                                       $product_code_data=$obj_invoice->GetproductcodeDetail($invoice_d['product_code_id']);
                                       // printr($product_code_data);
                                  }
                                  if($invoice_d['color']!='-1'){
                                        $color_data=$obj_invoice->GetColorDetail($invoice_d['color']);
                                       // printr($color_data);
                                  }
                                 
                                      if($invoice_d['digital_print_color']!=''){
                                 $stock_print='<b>('.$invoice_d['stock_print'].'</b>)';
                                 $digital_color=$obj_invoice->GetdigitalColorName($invoice_d['digital_print_color']);
                                 // printr($digital_color);
                                 $d_color='<b>Digital Printing With </b>'.$digital_color;
                                 }else{
                                 $stock_print='';
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
                              <tr id="invoice_product_id_<?php echo $invoice_d['sales_invoice_product_id']; ?>">
                                 <td><b><?php $product = $obj_invoice->getActiveProductName($invoice_d['product_id']);
                                    echo $product['product_name'].'<br>  '.$invoice_d['filling_details'].'<br>'. $d_color; ?></b></td>
                                 <?php $colors = $obj_invoice->getColorDetails($invoice_d['invoice_id'],$invoice_d['invoice_product_id']); 
                                   // printr($colors);  ?>
                                 <td><?php 
                                    if($invoice_d['color_text']!='' && $invoice_d['digital_print_color']!='')
                                      $color_text='<b>Digital Print:</b>'.$invoice_d['color_text'];
                                    else
                                        $color_text="";
                                    
                                    if($invoice_d['color']=='-1')
                                    {
                                    echo $invoice_d['dimension'].'<br>';                 
                                    
                                    }else{ 
                                    if($invoice['invoice_status']=='1'){
                                            $measure = $obj_invoice->getMeasurementName($product_code_data['measurement']);
                                         
                                             echo $product_code_data['volume'].'&nbsp;'.$measure['measurement'].'<br>';
                                    }else{
                                        
                                      $measure = $obj_invoice->getMeasurementName($invoice_d['measurement']);
                                           
                                        //   printr($invoice);
                                           if($invoice['country_id']!='253'){
                                                echo $invoice_d['size'].'&nbsp;'.$measure['measurement'].'<br>';
                                               }else{
                                                   $s_us = $obj_invoice->getsizeForUS($invoice_d['size'].' '.$measure['measurement']);
                                                   if($s_us!=''){
                                       echo $s_us.'<br>';
                                    }else{
                                        
                                         echo $invoice_d['size'].'&nbsp;'.$measure['measurement'].'<br>';
                                    }
                                               }
                                    }
                                    
                                    }
                                    ?>
                                 <td>
                                    <?php
                                       if(!empty($invoice_d)){
                                        
                                              
                                         
                                            if($invoice_d['color']=='-1'){
                                              echo 'Custom : '.$invoice_d['color_text'].'<br>';
                                               } else
                                                {
                                                    
                                                    echo $color_data.' <br>'.$color_text.'<br>';
                                                }                    
                                          }
                                         else{
                                               echo $color_data.' <br>'.$color_text.'<br>';
                                          }
                                       ?>
                                 </td>
                                 <?php if($invoice['invoice_status']=='1'){?>
                                 <td><input type="text" id="qty_<?php echo  $invoice_d['sales_invoice_product_id'];?>" name="qty_<?php echo  $invoice_d['sales_invoice_product_id'];?>" onchange="change_invoice_qty('<?php echo  $invoice_d['sales_invoice_product_id'];?>',0)" value="<?php echo isset($invoice_d['qty'])?$invoice_d['qty']:'' ;?>" class="form-control" ></td>
                                 </td>
                                 <?php }else{?>
                                 <td><?php echo $invoice_d['qty'];?></td>
                                 <?php }?>
                                  <?php if($invoice['invoice_status']=='1'){?>
                                 <td><input type="text" id="rate_<?php echo  $invoice_d['sales_invoice_product_id'];?>" name="rate_<?php echo  $invoice_d['sales_invoice_product_id'];?>" onchange="change_invoice_qty('<?php echo  $invoice_d['sales_invoice_product_id'];?>',1)" value="<?php echo isset($invoice_d['rate'])?$invoice_d['rate']:'' ;?>" class="form-control" ></td>
                                 </td>
                                 <?php }else{?>
                                 <td><?php  echo $invoice_d['rate']; ?></td>
                                    <?php }?>
                                 <td><?php echo ucwords($getProductSpout['spout_name']).' '.$invoice_d['valve'].'<br>'.$getProductZipper['zipper_name'].' '.ucwords($getProductAccessorie['product_accessorie_name']); ?></td>
                                 <td class="del-product"><a class="btn btn-danger btn-sm" href="javascript:void(0);" onClick="removeInvoice(<?php echo $invoice_d['sales_invoice_product_id'].','.$invoice['sales_invoice_id']; ?>)"><i class="fa fa-trash-o"></i></a>
                                 </td>
                              </tr>
                              <div class="modal fade" id="alertbox_<?php echo $invoice_d['sales_invoice_product_id']; ?>">
                                 <div class="modal-dialog">
                                    <div class="modal-content">
                                       <div class="modal-header">
                                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                          <h4 class="modal-title">Title</h4>
                                       </div>
                                       <div class="modal-body">
                                          <p id="setmsg">Are you sure you want to delete ?</p>
                                       </div>
                                       <div class="modal-footer">
                                          <button type="button" id="popbtncan" class="btn btn-default" data-dismiss="modal">Close</button>
                                          <button type="button" name="popbtnok" id="popbtnok_<?php echo $invoice_d['sales_invoice_product_id']; ?>" class="btn btn-primary">Ok</button>
                                       </div>
                                    </div>
                                    <!-- /.modal-content -->
                                 </div>
                                 <!-- /.modal-dialog -->
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
                        </div>
                        <?php  }} ?>
                     </div>
                     <?php if($edit){?>
                     <div class="form-group">
                        <div class="col-lg-9 col-lg-offset-3">
                           <button type="submit" name="btn_update" id="btn_update" class="btn btn-primary">Update </button>
                           <a class="btn btn-default" href="<?php echo $obj_general->link($rout, 'mod=index', '',1);?>">Cancel</a>
                        </div>
                     </div>
                     <?php } else {?> 
                     <div class="form-group">
                        <div class="col-lg-9 col-lg-offset-3">
                           <button type="submit" name="btn_add" id="btn_add" class="btn btn-primary">Add </button>
                           <a class="btn btn-default" href="<?php echo $obj_general->link($rout, 'mod=index', '',1);?>">Cancel</a>
                        </div>
                     </div>
                     <?php }  ?>
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
               <h4 class="dispatch" id="myModalLabel">Change Price</h4>
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

<script src="https://harvesthq.github.io/chosen/chosen.jquery.js" type="text/javascript"></script>
<link rel="stylesheet" href=" https://harvesthq.github.io/chosen/chosen.css" type="text/css"/> 

<script> 
$(document).ready(function(){
    $(".chosen_data").chosen();
    $("input:radio[name=pro_option]").change();
   });
$(".chosen-select").chosen();
jQuery(document).ready(function(){
    jQuery("#form").validationEngine();

});
function removeInvoice(sales_invoice_product_id,sales_invoice_id)
{ 
  //  alert(sales_invoice_product_id);
        $("#alertbox_"+sales_invoice_product_id).modal("show");
        $(".modal-title").html("Delete Record".toUpperCase());
        $("#setmsg").html("Are you sure you want to delete ?");
        
        $("#popbtnok_"+sales_invoice_product_id).click(function(){
          var remove_invoice_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=removeInvoice', '',1);?>");
          $.ajax({
            url : remove_invoice_url,
            method : 'post',
            data : {sales_invoice_product_id : sales_invoice_product_id,sales_invoice_id : sales_invoice_id},
            success: function(response){
            //alert(response);
              if(response == 0) {
              $("#alertbox_"+sales_invoice_product_id).hide();
            }
              $("#alertbox_"+sales_invoice_product_id).hide();
              $("#alertbox_"+sales_invoice_product_id).modal("hide");
              $('#invoice_product_id_'+sales_invoice_product_id).html('');
              set_alert_message('Invoice Record successfully deleted','alert-success','fa fa-check');
              },
            error: function(){
              return false; 
            }
          });
            $("#alertbox_"+sales_invoice_product_id).hide();
            $("#alertbox_"+sales_invoice_product_id).modal("hide");
         });
 
    }
  $(document).ready(function() {
   $("#invoice_date,#challan_date,#exp_date,#buyers_order_date").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});

   
    });     
 
 function change_invoice_qty(inv_product_id,n)
    {
        //var name1 = "'"+name+"'";
    //  alert($('#qty').val());
        if(n==0)
             var value=$('#qty_'+inv_product_id).val();
        else
             var value=$('#rate_'+inv_product_id).val();
     //  alert(value);
    		
    	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=change_invoice_qty', '',1);?>");
    	$.ajax({
    			method: "POST",					
    			url: url,
    			data : {inv_product_id:inv_product_id,value:value,n:n},
    			success: function(response)
    			{
    				// console.log(response);
    			 set_alert_message('Invoice Record successfully updated ','alert-success','fa fa-check');
    				location.reload();
    			
    			},
    			error: function(){
    					return false;	
    			}
    		});
    	
    }

function add_row(count){
        $("input[type=radio][name='pro_option'][id='pro_option']").prop('disabled', 'disabled');
        //var arr = jQuery.parseJSON($('#min_arr').val());
        var value=$("input:radio[name=pro_option]:checked").val(); 
		//console.log(value);
		var t_count = $("#myTable tr").length;
        	count=t_count;
		
				 var html = '';		  
					 html +='<tr class="multiplerows-'+count+'" id="multiplerows-'+count+'"> ';
				    html +='<td> ';
					 if(value==0)
					 {
    					 html +=' <select name="addroll['+count+'][product]" id="addroll['+count+'][product]" class="form-control validate[required]" >';
    					 html +='  <option value="">Select Product </option>';
    					  <?php foreach($product_details as $product){ ?>
                          html +=' <option value="<?php echo $product['product_id']; ?>" id="option"><?php echo $product['product_name']; ?></option>';
                                            <?php } ?>                      
                            
    					 html +='</select>';
					 }
					 else
					 {  
					     html +='<div class="hide_text" onclick="open_tab('+count+')">';
                    	  html += '<select name="addroll['+count+'][keyword]" id="addroll'+count+'" class="form-control validate[required] chosen-select "><option value="">Select Product</option>';
                    	  /*for(var i=0;i<arr.length;i++)
                    	  {
                    		 html += '<option value="'+arr[i].product_code_id+'">'+arr[i].product_code+'</option>';
                    	  }*/
                    	  html +=  '</select><input type="hidden" name="addroll['+count+'][product_id]" id="addroll_product_'+count+'" value="" />';
					 }
					 html +='</td>';
					 html +='<td>';
							html +='<input type="text" name="addroll['+count+'][description]" value="" class="form-control " placeholder="Description" id="addroll_description_'+count+'">';
					 html +='</td>';
					 html +='<td>';
								html +='<input type="text" name="addroll['+count+'][qty]" value="" class="form-control " placeholder="Qty" id="addroll['+count+'][qty]">';
					 html +='</td>';
					 html +='<td>';
							  
							 html +='<input type="text" name="addroll['+count+'][rate]" value="" class="form-control " placeholder="Rate" id="addroll['+count+'][rate]">';

					 html +='</td>';
					 html +='<td>';
							 html +='<input type="text" name="addroll['+count+'][size]"  value="" class="form-control " placeholder="Size" id="addroll_size_'+count+'"></td>';
					 html +='</td>';
				   
					 html +='<td>';
							 html +='<select name="addroll['+count+'][measurement]" id="addroll_measurement_'+count+'" class="form-control " >';
					        html +='  <option value="">Select Measurement </option>';
					         <?php foreach($measurement as $meas){ ?>
                                	 html +='<option value="<?php echo $meas['product_id']; ?>"><?php echo $meas['measurement']; ?></option>';
                        <?php   }  ?>
                         html +='</select>';
					 html +='</td>';
					 html +='<td>';
							 html +='<input type="text" name="addroll['+count+'][net_weight]"  value="" class="form-control " placeholder="Net Weight" id="addroll['+count+'][net_weight]"></td>';
					 html +='</td>';
					 html +='<td>';
				
					html+='<a class="btn btn-danger btn-xs btn-circle remove" data-toggle="tooltip" data-placement="top" title="" data-original-title="remove"><i class="fa fa-minus"></i></a></td>';
					html +='</td>';
					
					html +='</tr>';
				
				$('#myTable tr:last').after(html);
				 $(".chosen-select").chosen();
				 $("#addroll"+count+"_chosen").css("width","191px");
				$(' .remove').click(function(){
					$("#addmore").show();
					$(this).parent().parent().remove();
				
				});
			
								
	}

$("#same-above").click(function(){
	
	$("#loading").show();
	if($(this).prop('checked') == true){
		var bill = $("#consignee").val();
		$("#consignee").val(bill);
		$("#other_buyer").slideUp('slow');
		$("#same-above").val(1);
	}else{
		var del = $("#consignee").val();
		$("#consignee").val(del);
		$("#other_buyer").slideDown('slow');
		$("#same-above").val(0);
	}
	//alert($("#same-above").val());
	$("#loading").fadeOut();
});
$("input:radio[name=pro_option]").change(function(){
   var value=$("input:radio[name=pro_option]:checked").val(); 
   $("#addroll1_chosen").css("width","191px");
   if(value==1)
   {
       $(".hide_select").css("display", "none");
       $(".hide_text").css("display", "block");
   }
   else
   {
       $(".hide_select").css("display", "block");
       $(".hide_text").css("display", "none");
   }
});
function open_tab(n)
{
	$(document).on('mouseover keyup keydown keypress', '#addroll'+n+' + .chosen-container .chosen-results li', function() {
    var productCode_text = $(this).text();
	    if(productCode_text!='Select Product')
		{
			var url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=get_disc', '',1);?>");
					$.ajax({
						url : url,
						method : 'post',
						data : {productCode_text : productCode_text},
						success: function(response){
						    var value = jQuery.parseJSON(response);
								$("#addroll_description_"+n).val(value.description);
								$("#addroll_size_"+n).val(value.volume);
								$("#addroll_measurement_"+n).val(value.measurement);
								$("#addroll_product_"+n).val(value.product);
							},
						error: function(){
							return false;	
						}
					});
		
		}
	});
	

} 

</script> 
<!-- Close : validation script -->
<?php } else { 
    include(DIR_ADMIN.'access_denied.php');
  }
?>