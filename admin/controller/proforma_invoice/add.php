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

//[kinjal] (13-4-2017) for get address_book_id wise data
$add_book_id='0';
$add_url='';
if(isset($_GET['address_book_id']))
{
		$add_book_id = decode($_GET['address_book_id']);
		$add_url = '&address_book_id='.$_GET['address_book_id'];
}



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
$edit = '';
if(isset($_GET['proforma_id']) && !empty($_GET['proforma_id'])){
	$proforma_id = decode($_GET['proforma_id']);
	$proforma_inv = $obj_pro_invoice->getProforma($proforma_id);
	//printr($proforma_inv);
	$edit = 1;

}
if(isset($_GET['proforma_in_id']) && !empty($_GET['proforma_in_id'])){
	$proforma_in_id = decode($_GET['proforma_in_id']);
	$invoice_detail = $obj_pro_invoice->getSingleInvoice($proforma_in_id);
}
if(isset($_POST['generate_invoice'])) {
	$proforma_data = $obj_pro_invoice->getProforma($_POST['proforma_id']);	
	$addedByinfo=$obj_pro_invoice->getUser($proforma_data['added_by_user_id'],$proforma_data['added_by_user_type_id']);
	$obj_session->data['success'] = 'Invoice successfully Added!';
	page_redirect($obj_general->link($rout, '&mod=view&proforma_id='.encode($_POST['proforma_id']).'&is_delete='.$_GET['is_delete'].''.$add_url, '',1));
}

if(isset($_GET['user_id']) && !empty($_GET['user_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$user_id = base64_decode($_GET['user_id']);
		$user = $obj_user->getUser(1,$user_id);
		$edit = 1;
	}
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}
if($display_status){
	$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
	$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
	//printr($user_id);
	//printr($user_type_id );
	$userCurrency = $obj_pro_invoice->getUserCurrencyInfo($user_type_id,$user_id);
	$addedByInfo = $obj_pro_invoice->getUser($user_id,$user_type_id);
	
	//printr($addedByInfo);
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
          <header class="panel-heading bg-white"><span class="required">*</span> Proforma Invoice Detail </header>
          <div class="panel-body">
          	 
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
			  <?php $LastInvoiceId = $obj_pro_invoice->getLastId(); ?>
                  <div class="form-group">
                    	<input type="hidden" name="invoiceno" placeholder="Invoice No" value="<?php if(isset($proforma_id)) { echo $proforma_inv['proforma_id']; } 
                    else { echo ($LastInvoiceId['proforma_id']+1); } ?>" class="form-control validate[required]">
                    	<label class="col-lg-3 control-label">Invoice Date</label>
                         <div class="col-lg-3">
                             <input type="text" name="invoicedate" readonly="readonly" data-date-format="yyyy-mm-dd" value="<?php
                             if(isset($proforma_inv['invoice_date'])) { echo $proforma_inv['invoice_date']; }else{ echo date("Y-m-d"); }  ?>" placeholder="Invoice Date" id="input-name" 
                             class="input-sm form-control datepicker" />
                         </div>
                  </div>
              		
                  <div class="form-group">
                   	 <label class="col-lg-3 control-label">Proforma</label>
                        <div class="col-lg-3">
                            <input type="text" name="Proforma" readonly="readonly" data-date-format="yyyy-mm-dd" 
                            value="<?php if(isset($proforma_inv['proforma'])) { echo $proforma_inv['proforma']; }else{ echo date("Y-m-d"); }  ?>" placeholder="Proforma" id="input-proforma" 
                            class="input-sm form-control datepicker" />
                        </div>
                     	<label class="col-lg-3 control-label"><span class="required">*</span>Buyers order No</label>
                        <div class="col-lg-1">
                          <input type="text" name="buyersno" id="buyersno" value="<?php if(isset($proforma_inv['buyers_order_no'])) { 
                          echo $proforma_inv['buyers_order_no'];  } ?> "  class="form-control validate[required]"/>
                        </div> <b style="color:#ff0000"> <?php echo "if no buyers no then enter zero";?> </b>
                  </div>
              
             	 <div class="form-group">
                	<label class="col-lg-3 control-label">Buyers Date</label>
                    <div class="col-lg-3">
                   		 <input type="text" name="buyers_date" readonly="readonly" data-date-format="yyyy-mm-dd" value="<?php if(isset($proforma_inv['buyers_date'])) 
                   			 { echo $proforma_inv['buyers_date']; }else{ echo date("Y-m-d"); }  ?>" placeholder="Buyers Date" id="input-buyerdate" class="input-sm form-control datepicker" />
                    </div>
                	<label class="col-lg-3 control-label"><span class="required">*</span>Country of origin of goods</label>
                     <div class="col-lg-3">
                          <input type="text" name="country" id="input-country" value="<?php if(isset($proforma_inv['goods_country'])) {
                           echo $proforma_inv['goods_country']; } ?>" class="form-control validate[required]">
                    </div>
              </div>
              
                  <div class="form-group">
                   	 <label class="col-lg-3 control-label"><span class="required">*</span>Customer Name</label>
                        <div class="col-lg-3">
                           <input type="text" name="customer_name"  value="<?php if(isset($proforma_inv['customer_name'])) { echo $proforma_inv['customer_name']; } ?>" placeholder="Customer Name" id="customer-name" class="form-control validate[required]" />          <input type="hidden" name="address_book_id"  value="<?php echo isset($proforma_inv) ? $proforma_inv['address_book_id'] : '' ; ?>" id="address_book_id" class="form-control " />
                            <input type="hidden" name="company_address_id"  value="" id="company_address_id" class="form-control " />
                            <input type="hidden" name="factory_address_id"  value="" id="factory_address_id" class="form-control " />
                            
                         	<div id="ajax_response"></div>
                         </div>
                         <label class="col-lg-3 control-label"><span class="required">*</span>Email</label>
                        <div class="col-lg-3">
                          <input type="text" name="email" id="email" value="<?php if(isset($proforma_inv['email'])) 
                          { echo $proforma_inv['email']; } ?>" placeholder="Email"  class="form-control validate[required,custom[email]]">
                        </div>
                  </div>
                 <div class="form-group">
                    <label class="col-lg-3 control-label">Client Billing Address</label>
                        <div class="col-lg-8">
                      	  <textarea class="form-control" id="input-address" name="clientaddress"><?php if(isset($proforma_inv['address_info'])) 
                       		 { echo $proforma_inv['address_info']; } ?></textarea>
                        </div>
                  </div>
               
                   <div class="form-group">
                         <label class="checkbox-custom  m-l-small pull-right" style="font-size:14px;">
                             		<input type="checkbox" name="same_as_above" id="same-above" value="1">
                                 <i class="fa fa-square-o"></i> Same as Above? </label>
                  </div>
                  <div id="billing-details">
                   <div class="form-group">
                    <label class="col-lg-3 control-label">Client Delivery Address</label>
                        <div class="col-lg-8">
                      	  <textarea class="form-control" id="input-del-address" name="client_del_address"><?php if(isset($proforma_inv['delivery_address_info'])) 
                       		 { echo $proforma_inv['delivery_address_info']; } ?></textarea>
                             <input type="hidden" id="del_info" value=""> 
                        </div>
                  </div>
                  </div>
              		<?php
                    /*if($addedByInfo['country_id']=='214')
                    {
					 ?>

   					<div class="form-group">
   					<label class="col-lg-3 control-label"><?php if(isset($addedByInfo['country_id']) && $addedByInfo['country_id']=='155') {?> RFC No. <?php } else { ?> Gst No.<?php } ?></label>
                    <div class="col-lg-3">
                      <input type="text" name="vat_no" id="vat_no" value="<?php if(isset($proforma_inv['vat_no'])) 
                      { echo $proforma_inv['vat_no']; } ?>" class="form-control validate">
                    </div>
              </div>

          	  <?php } 
			  else
			  {*/
			  ?>
             <input type="hidden" name="con_id" id="con_id" value="<?php echo $addedByInfo['country_id'] ;?>" class="form-control validate">
             	 <div class="form-group">
   					<label class="col-lg-3 control-label"><span class="required">*</span><?php if(isset($addedByInfo['country_id']) && $addedByInfo['country_id']=='155')  {?> RFC No. <?php } elseif(isset($addedByInfo['country_id']) && $addedByInfo['country_id']=='214') { echo 'GST No.';}else { ?> GST No. (Tin No.)<?php } ?></label>
                   
                    <div class="col-lg-3">
                      <input type="text" name="vat_no" id="vat_no" value="<?php if(isset($proforma_inv['vat_no'])) { echo $proforma_inv['vat_no']; } ?>" class="form-control validate[required]">
                      <b style="color:#ff0000"> <?php echo "if you haven't No then Write Down - Not Available ";?> </b>
                    </div>
                    
                   <?php //printr($addedByInfo);
				   if($addedByInfo['country_id'] != '111')
					{ ?>
                    <label class="col-lg-3 control-label"><span class="required">*</span>Discount</label>
                        <div class="col-lg-3">
                          <input type="text" name="discount" id="discount" value="<?php if(isset($proforma_inv['discount'])) 
                          { echo $proforma_inv['discount']; } ?>" class="form-control validate[required]">
                        </div>
                    <?php } ?>
                    
              </div>
              <div class="form-group">
                    <label class="col-lg-3 control-label"><span class="required">*</span>Delivery</label>
                        <div class="col-lg-8">
                          <input type="text" name="delivery" id="input-delivery" value="<?php if(isset($proforma_inv['delivery_info'])) 
                          { echo $proforma_inv['delivery_info']; } ?>" class="form-control validate[required]">
                        </div>
              </div>
              
              <div class="form-group">
                    <label class="col-lg-3 control-label"><span class="required">*</span> Currency</label>
                        <div class="col-lg-3">
                        	<?php
							  $currency = $obj_pro_invoice->getCurrency();
                            ?>
                             
                            <select name="currency" id="currency" class="form-control validate[required]" >
                               <option value="">Select Currency</option>
                                <?php
								foreach($currency as $curr){
								if($addedByInfo['country_id'] == '155')
								{
									if( $curr['currency_id'] == '2' ||  $curr['currency_id'] == '10')
									{ ?>
                                    	<option value="<?php echo $curr['currency_id']; ?>" <?php if(isset($proforma_id) && $curr['currency_id'] == $proforma_inv['currency_id']) { ?> selected="selected" <?php } ?>><?php echo $curr['currency_code']; ?></option>
                                     <?php }
									 }
									 
									 else if($addedByInfo['country_id'] == '42')
									{
										if( $curr['currency_id'] == '2' ||  $curr['currency_id'] == '15')
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
									 
									 else{
									 
									 ?>
                                        <option value="<?php echo $curr['currency_id']; ?>"
                                        <?php 
										if(isset($proforma_id) && ($curr['currency_id'] == $proforma_inv['currency_id'])) { ?> selected="selected" <?php }?>
                                         ><?php echo $curr['currency_code']; ?></option>
                              <?php }
							   } ?>
                            </select>
						</div>
                 		<label class="col-lg-3 control-label"><span class="required">*</span>Payment Terms</label>
                		<div class="col-lg-3">
                            <input type="text" name="payment_terms" id="input-payment" value="<?php if(isset($proforma_inv['payment_terms'])) 
                            { echo $proforma_inv['payment_terms']; } ?>" class="form-control validate[required]">
                		</div>
              		</div>
                
              <div class="form-group">
              		<label class="col-lg-3 control-label"><span class="required">*</span>Final Destination</label>
                        <div class="col-lg-3">
							<?php
                            if(isset($addedByInfo['country_id']) && $addedByInfo['country_id']){
                                        $selCountry = $addedByInfo['country_id'];
                            }
                       		 	$sel_country = (isset($proforma_inv['destination']))?$proforma_inv['destination']:$addedByInfo['country_id']; 	
                        		$countrys = $obj_general->getCountryCombo($sel_country);
                        		echo $countrys;                   
                        	?>	 
                        </div>
					<label class="col-lg-3 control-label">Port Loading</label>
                        <div class="col-lg-3">
                            <input type="text" name="port_loading" id="input-port" value="<?php if(isset($proforma_id) && !empty($proforma_id)) 
                            { echo $proforma_inv['port_loading']; } ?>" class="form-control validate" >
                        </div>
             	 </div>
               
               <?php // [mansi-> 9-6-2016(taxation of gst,pst,hst in canada)] ?>
               
               <?php if($addedByInfo['country_id'] == '42')
				   { $tax_canada = $obj_pro_invoice->getTaxationCanada();
				   	//printr($tax_canada);?>
                       <div class="form-group">
                        	<label class="col-lg-3 control-label"><span class="required">*</span>State</label>
                        	<div class="col-lg-3">
                            <select name="state" id="state" class="form-control validate[required]" >
                               <option value="">Select State</option>
                               <?php foreach($tax_canada as $tcanada)
							   		{
										if(isset($proforma_inv['state']))
										{ ?>
                                    		<option value="<?php echo $tcanada['taxation_canada_id']; ?>" <?php if($proforma_inv['state'] == $tcanada['taxation_canada_id']) { ?> selected="selected" <?php  } ?>><?php echo $tcanada['state']; ?></option>
                                            
                              <?php 	} 
							  			else
										{
											echo '<option value="'.$tcanada['taxation_canada_id'],'" >'.$tcanada['state'].'</option>';
										}
							 		} ?>
                               
                              </select>
                            </div>
                       </div>
                       <div class="form-group" id="gst_div" style="display:none;">
                        	<label class="col-lg-3 control-label"><span class="required">*</span>GST(%)</label>
                                <div class="col-lg-3">
                                 <input type="text" class="form-control" readonly="readonly" name="gst" id="gst" value="<?php echo isset($proforma_inv['gst']) ? $proforma_inv['gst'] :'' ;?>">
                                </div>
                        </div>
                        <div class="form-group" id="pst_div" style="display:none;">
                        	<label class="col-lg-3 control-label"><span class="required">*</span>PST(%)</label>
                                <div class="col-lg-3">
                                     <input type="text" readonly="readonly" name="pst" id="pst" value="<?php echo isset($proforma_inv['pst']) ? $proforma_inv['pst']:'';?>" class="form-control"><br>
                                </div>
                        </div>
                        <div class="form-group" id="hst_div" style="display:none;">
                        	<label class="col-lg-3 control-label"><span class="required">*</span>HST(%)</label>
                                <div class="col-lg-3">
                                	<input type="text"  readonly="readonly" name="hst" id="hst" value="<?php echo isset($proforma_inv['hst']) ? $proforma_inv['hst']:'';?>" class="form-control">
                                </div>
                        </div>
               <?php } ?>
               
              <div class="form-group" id="tax_div">
                    <label class="col-lg-3 control-label">Tax</label>
                     <div class="col-lg-8">
                        <div id="normal_div" style="float:left;width: 200px;">
							<label  style="font-weight: normal;">
                        	<input type="radio" name="taxation" id="taxation_nrm" value="normal" checked="checked" 
							<?php if(isset($proforma_inv['tax_mode']) && ($proforma_inv['tax_mode'] == 'normal')) { ?> checked="checked" <?php } ?> >Normal </label>
                   	   </div>
					 <div id="form_div" style="float:left;width: 200px;">
                    		<label style="font-weight: normal;">
                       	 	<input type="radio" name="taxation" id="taxation_frm" value="form"  <?php 
							if(isset($proforma_inv['tax_mode']) && ($proforma_inv['tax_mode'] == 'form')) { ?> checked="checked" <?php } ?>>Form </label>
                     </div>
                   </div>
                   
                   
             </div>
              
              <?php if(isset($proforma_inv) && $proforma_inv['tax_mode'] == 'form') 
			 			$f_nm = explode(",", $proforma_inv['tax_form_name']);?>  
              <div class="form-group" id="fm_div" style="display:none">
                      <label class="col-lg-3 control-label">Select Form</label>
                      	 <div class="col-lg-8">
                        	<div>
                                <label  style="font-weight: normal;">
                                        <input type="checkbox" name="form[]" id="h_form"  value="H Form"  
                                      <?php if(isset($f_nm) && in_array('H Form',$f_nm)){ echo 'checked="checked"'; } ?>> H Form 
                                 </label>
                              </div>
                                
							<div id="ct1_div">
								<label style="font-weight: normal;">                                
                             	   <input type="checkbox" name="form[]" id="ct1" value="CT1"
                                	<?php if(isset($f_nm) && in_array('CT1',$f_nm)){ echo 'checked="checked"'; }?>>CT1
                                 </label>
                             </div> 
							<div id="ct2_div">
                                <label style="font-weight: normal;">                             
                               		 <input type="checkbox" name="form[]" id="ct3" value="CT3" 
									 <?php if(isset($f_nm) && in_array('CT3',$f_nm)){ echo 'checked="checked"'; }?> />CT3 
                                 </label>
                            </div>
                          </div>
                      </div>
                          
              <div class="form-group" id="nrm_div" style="display:none">
                      <label class="col-lg-3 control-label">Select</label>
                       <div class="col-lg-8">
                        	<div>
								<label  style="font-weight: normal;">                                 
									<input type="radio" name="nrm_tax" id="taxation1" value="cst_with_form_c" <?php if(isset($proforma_inv['taxation']) && ($proforma_inv['taxation'] == 'cst_with_form_c')) { ?> checked="checked" <?php } ?>> 
                                Out Of Gujarat (CST With Form C) 
                                </label>
                            </div>
                                
							<div>
								<label style="font-weight: normal;">                                
									<input type="radio" name="nrm_tax" id="taxation2" value="cst_without_form_c"  checked="checked" <?php if(isset($proforma_inv['taxation']) && 
								($proforma_inv['taxation'] == 'cst_without_form_c')) { ?> checked="checked" <?php } ?> >							
								Out Of Gujarat (CST With Out Form C) 
                                </label>
                                
                            </div>
                                
							<div>
									<label style="font-weight: normal;">                             
									<input type="radio" name="nrm_tax" id="taxation3" value="vat" <?php if(isset($proforma_inv['taxation']) && 
									($proforma_inv['taxation']== 'vat')) { ?> checked="checked" <?php } ?>> With In Gujarat </label>
                             </div>
                             <div>
									<label style="font-weight: normal;">                             
									<input type="radio" name="nrm_tax" id="taxation4" value="sez_no_tax" <?php if(isset($proforma_inv['taxation']) && 
									($proforma_inv['taxation']== 'sez_no_tax')) { ?> checked="checked" <?php } ?>> SEZ  Unit - No Tax </label>
                             </div>
                 	   </div>
                 </div>
                        
              <div class="form-group option">
                        <label class="col-lg-3 control-label">Mode of Shipment</label>
                        <div class="col-lg-9">                
                        	<div  class="checkbox ch1" style="float:left;width: 200px;">
                                <label  style="font-weight: normal;">
                                  <input type="radio" name="transport" id="tran1" value="air" 
								  <?php if(isset($proforma_id) && (decode($proforma_inv['transportation']) == 'air')) { ?> checked="checked" <?php } ?>/>
                                By Air
                              </label>
                          </div>
                           <div class="checkbox ch2" style="float:left;width: 200px;">
                                <label  style="font-weight: normal;">
                                  	<input type="radio" name="transport" id="trans2" value="sea" 
									<?php if(isset($proforma_id) && (decode($proforma_inv['transportation']) == 'sea')) { ?> checked="checked" <?php } ?> >
                              	By Sea
                               </label>
                          </div>
                           <?php //if($userCurrency['currency_code'] == 'INR'){ ?>
                             	 <div class="checkbox ch3" style="float: left;  width: 30%;">
                                    <label><input type="radio" name="transport" id="trans3" value="road" <?php if(isset($proforma_id) && (decode($proforma_inv['transportation']) == 'road')) { ?> checked="checked" <?php } ?> >By Pickup</label>
                                 </div>
                                 
                          <?php //} ?>
                        </div>
              </div>
                               
              <div class="form-group">
                   <label class="col-lg-3 control-label"><span class="required">*</span>Signature Date</label>
                		<div class="col-lg-3">
                  <input type="text" name="signature_date" readonly="readonly" data-date-format="yyyy-mm-dd" value="<?php 
				  if(isset($proforma_inv['sign_date'])) { echo $proforma_inv['sign_date']; } ?>" placeholder="Buyers Date" id="signature_date" 
                  class="input-sm form-control datepicker" />
                  <input type="hidden" name="edit" id="edit" value="<?php echo $edit; ?>"  />
                  
                </div>
                <?php if($addedByInfo['country_id']=='214'){ //echo $proforma_inv['gst_tax'];?>
                <div id="">
                 	<label class="col-lg-3 control-label">Gst Tax (%)</label>
                        <div class="col-lg-3">
                          <input type="text" name="gst_tax" id="gst_tax" value="<?php echo isset($proforma_inv['gst_tax']) ? $proforma_inv['gst_tax'] :  round($addedByInfo['gst']) ; ?>" class="form-control" readonly="readonly">
                           <input type="hidden" name="gst" id="gst" value="<?php echo isset($addedByInfo['gst']) ? round($addedByInfo['gst']) : '' ; ?>"  >
                         </div>
                	</div>
                  <?php } if($addedByInfo['country_id']=='111'){ ?>
                  <div id="freight">
                 	<label class="col-lg-3 control-label">Packing Charges</label>
                        <div class="col-lg-3">
                          <input type="text" name="packing" id="packing_charges" value="<?php if(isset($proforma_inv['packing_charges'])) { 
                          echo $proforma_inv['packing_charges']; } ?>" class="form-control" >
                         </div>
                	</div>
                    <?php } ?>
              </div>
                                          
              <div class="form-group">
             	<label class="col-lg-3 control-label"><span class="required">*</span> Select Bank</label>
                    <div class="col-lg-3" id="bank_details">
                     <?php if(isset($proforma_id)) { 
						$bank = $obj_pro_invoice->getbankDetails($proforma_inv['currency_id']);						
						if(isset($bank) && !empty($bank)) { ?>
						<select name="bank_id" id="bank_id" class="form-control validate[required]" style="width:70%" >
							<option value="">Select Bank</option>							
								<?php foreach($bank as $details){ ?>
									<option value="<?php echo $details['bank_detail_id']; ?>"
                                    	<?php if($details['bank_detail_id'] == $proforma_inv['bank_id']) { ?> selected="selected" <?php } ?>
                                     	><?php echo $details['benefry_bank_name']; ?>
                                    </option>
								<?php } ?>
						</select>
						<?php } 
						} else { ?>
                        	<span class="btn btn-danger btn-xs form-control validate[required]">Please Select Currency for Bank Option.</span>
                            <?php }//	printr($proforma_inv['freight_charges']); ?>
					</div>
                  <div id="freight">
                 	<label class="col-lg-3 control-label">Freight Charges</label>
                        <div class="col-lg-3">
                          <input type="text" name="freight" id="freight_char" value="<?php if(isset($proforma_inv['freight_charges'])) { 
                          echo $proforma_inv['freight_charges']; } ?>" class="form-control" >
                         </div>
                	</div>
               </div>
              <?php //printr($invoice_detail);?>
            	<div class="line line-dashed m-t-large"></div>
                  
              <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span> Select Product</label>
                        <div class="col-lg-8">
                            <?php $products = $obj_pro_invoice->getActiveProduct();?>
                            <select name="product" id="product" class="form-control validate[required]">
                            <option value="">Select Product</option>
                                <?php
                                foreach($products as $product){ ?>
                                    <option value="<?php echo $product['product_id']; ?>"
									<?php if(isset($proforma_in_id) && ($product['product_id'] == $invoice_detail['product_id'])) {
											echo 'selected="selected"';
										}?> > <?php echo $product['product_name']; ?></option>
                                    <?php } ?> 
                                
                            <option value="0" <?php if(isset($invoice_detail['product_name']) && $invoice_detail['product_name'] == 'Cylinder') 
							echo 'selected="selected"'; ?>>Cylinder</option>
                            </select>
                        </div>
              </div>
                   
              <div  class="form-group" id="valve_div" <?php if(isset($invoice_detail['product_id']) && ($invoice_detail['product_id']==0 || $invoice_detail['product_id']=='28')){ ?> style="display:none" <?php } ?>>
                        <label class="col-lg-3 control-label">Valve</label>
                        <div class="col-lg-9">
                        	<div  style="float:left;width: 200px;">
                                <label  style="font-weight: normal;">
                                  <input type="radio" name="valve" id="nv" value="No Valve" checked="checked"  class="valve"
                                  <?php if(isset($invoice_detail['valve']) && ($invoice_detail['valve'] == 'No Valve')) {
									  echo 'checked="checked"'; } ?>    onchange="blankselectedvalue()"/>
                                 	No Valve
                              </label>
                            
                                <label  style="font-weight: normal;">
                                  	<input type="radio" name="valve" id="wv" value="With Valve" value="valve" class="valve"
                                    <?php if(isset($invoice_detail['valve']) && ($invoice_detail['valve'] == 'With Valve')) {
									  echo 'checked="checked"'; }?>   onchange="blankselectedvalue()"/>
                              	With Valve
                              </label>
                          </div> 
                        </div>
                      </div>
                      
	
                  <div  class="form-group" id="zipper_div" <?php if(isset($invoice_detail['product_id']) && ($invoice_detail['product_id']==0 || $invoice_detail['product_id']=='28')){ ?> style="display:none" 
	
    				  <?php } ?>>
                      	<input type="hidden" name="fetched_zipper" id="fetched_zipper" value="<?php echo isset($invoice_detail['zipper']) ? $invoice_detail['zipper']:'';?>"  />
                      
                        <label class="col-lg-3 control-label">Zipper</label>
                            <div class="col-lg-9">
                            <?php
                            $zippers = $obj_pro_invoice->getActiveProductZippers();
							//printr($zippers);
							foreach($zippers as $zipper){
                            ?>
                            <div   style="float:left;width: 200px;">
                            <label  style="font-weight: normal;">
                     <input type="radio" name="zipper" value="<?php echo encode($zipper['product_zipper_id']); ?>" onclick="showSize()" id="zip"  class="zipper"
                     <?php  if(isset($invoice_detail['zipper']) && ($invoice_detail['zipper'] == encode($zipper['product_zipper_id']))) {
								 echo 'checked="checked"'; }?>  />
					 <?php echo $zipper['zipper_name']; ?>
                            </label>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    
                    <?php $spouts = $obj_pro_invoice->getActiveProductSpout();?>
                  <div  class="form-group" id="spout_div" <?php if(isset($invoice_detail['product_id']) && ($invoice_detail['product_id']==0 || $invoice_detail['product_id']=='28')){ ?> style="display:none" <?php } ?>>
                     <label class="col-lg-3 control-label">Spout</label>
                      <div class="col-lg-9">
                      <?php $spoutsTxt = '';
					  foreach($spouts as $spout){ ?>
                      <div  style="float:left;width: 200px;">
                      <label  style="font-weight: normal;">
                      <input type="radio" name="spout" class="spout" id="spout" value="<?php echo encode($spout['product_spout_id']); ?>"
                      <?php if(isset($invoice_detail['spout']) && ($invoice_detail['spout'] == encode($spout['product_spout_id']))) { 
					  echo 'checked="checked"'; }elseif(!isset($invoice_detail['spout']) && (encode($spout['product_spout_id'])=='MQ==')){ 
					  echo 'checked="checked"';}?>   onchange="blankselectedvalue()"/>
                      <?php echo $spout['spout_name'];?>
                      </label>
                      </div>
                      <?php }?>
					</div>
					</div>
                      	  	   
                    <?php $accessories = $obj_pro_invoice->getActiveProductAccessorie();?>
                  	<div  class="form-group" id="acce_div" <?php if(isset($invoice_detail['product_id']) && ($invoice_detail['product_id']==0 || $invoice_detail['product_id']=='28')){ ?> style="display:none" <?php } ?>>
                         <label class="col-lg-3 control-label">Accessorie</label>
                              <div class="col-lg-9">
								  <?php $accessorieTxt = '';
                                  foreach($accessories as $accessorie){ ?>
                                      <div  style="float:left;width: 200px;">
                                          <label  style="font-weight: normal;">
                                          <input type="radio" name="accessorie" class="accessorie" id="accessorie" 
                                          value="<?php echo encode($accessorie['product_accessorie_id']); ?>"
                                          <?php if(isset($invoice_detail['accessorie']) && ($invoice_detail['accessorie'] == encode($accessorie['product_accessorie_id']))) { 
                                          echo 'checked="checked"'; }elseif(!isset($invoice_detail['accessorie']) && (encode($accessorie['product_accessorie_id'])=='NA==')){ 
                                          echo 'checked="checked"';}?>   onchange="blankselectedvalue()"/>
                                          <?php echo $accessorie['product_accessorie_name'];?>
                                           </label>
                                 	  </div>
                           <?php }?>
                            </div>
						</div>
                        
                        <div  class="form-group" id="filling_div" <?php if(isset($invoice_detail['product_id']) && ($invoice_detail['product_id']==31 || $invoice_detail['product_id']=='16')){ ?> style="display:block" 
						<?php }else{ echo 'style="display:none"';} ?>>
                         <label class="col-lg-3 control-label">Filling Selection</label>
                            <div class="col-lg-9">
                                <div  style="float:left;width: 200px;">
                                    <label  style="font-weight: normal;">
                                      <input type="radio" name="filling" id="from_top" value="Filling from Top" checked="checked"  class="valve"
                                      <?php if(isset($invoice_detail['filling']) && ($invoice_detail['filling'] == 'Filling from Top')) {
                                          echo 'checked="checked"'; } ?>/>
                                        Filling from Top
                                  </label>
                                
                                    <label  style="font-weight: normal;">
                                        <input type="radio" name="filling" id="from_spout" value="Filling from Spout" class="valve"
                                        <?php if(isset($invoice_detail['filling']) && ($invoice_detail['filling'] == 'Filling from Spout')) {
                                          echo 'checked="checked"'; }?> />
                                    Filling from Spout
                                  </label>
                              </div> 
                            </div>
						</div>
                        
                    <div class="form-group">
                      	<label class="col-lg-3 control-label"><span class="required">*</span> Select Size(WXHXG)</label>
                        <div class="col-lg-9" id="size_div" style="float: left; width: 30%;">
                        <?php if(isset($proforma_in_id)) {
							$inv = $obj_pro_invoice->getSingleInvoice($proforma_in_id);
							//printr($inv); die;
							$data = $obj_pro_invoice->getProductSize($inv['product_id'], $inv['zipper']);
							//printr($data); die;
						 ?>
                        <select id="size" name="size" class="form-control validate[required]" onchange="customSize()"><option value="">Select Size</option>
                        <?php foreach( $data as $item) { ?>
                        <option value="<?php echo encode($item['size_master_id']); ?>"
                        <?php if($inv['size'] == encode($item['size_master_id']) ) { echo 'selected="selected"'; } ?> >
                        <?php 
						if($item['volume']!=0)
						echo $item['volume']; ?>
						<?php echo $item['width'].'X'.$item['height'].'X'.$item['gusset']; ?></option>
                        <?php } ?>
                        <option value="0"  <?php if($inv['size'] == '0' ) { echo 'selected="selected"'; } ?>  >Custom</option>
                        </select>
                        <?php } else { ?>
                        <span class="btn btn-danger btn-xs">Please select Product for Size option.</span>
                        <?php } ?>
                        </div>
                      </div>

                    <div <?php echo isset($proforma_in_id)?'':'style="display:none"';?> id="customSize"  class="form-group">
                        <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span>Width</label>
                        <div class="col-lg-3">
                        <input type="text" name="width" id="width"  value="<?php echo isset($invoice_detail['width'])?floatval($invoice_detail['width']):''; ?>" class="form-control validate[required,custom[onlyNumberSp]]" >
						<span id="widthsugg" style="color:blue;font-size:11px;"></span>                             
                        </div>
                         <div class="col-lg-3">
                          <a href="#"  id="mydiv" class="btn btn-info btn-xs">View Size Table</a>                               
                        </div>
                      </div>
                     
                      <div class="form-group">
                        <label class="col-lg-3 control-label heightb"><span class="required">*</span>Height</label>
                        <div class="col-lg-3">
                           <input type="text" name="height" id="height" value="<?php echo 
							 isset($invoice_detail['height'])?floatval($invoice_detail['height']):'';?>" 
                             class="form-control validate[required,custom[onlyNumberSp]]">
                             
                         </div>
                      </div>
                     
                   <div class="form-group gusset">
                        <label class="col-lg-3 control-label"><span class="required">*</span>Gusset </label>
                        <div class="col-lg-7">
                             <div class="input-group">
                             	<input type="text" name="gusset" id="gusset_input" 
                                value="<?php echo isset($invoice_detail['gusset'])?floatval($invoice_detail['gusset']):'';?>" 
                                class="form-control validate[required]">
                                <span class="input-group-btn">
                                   <button type="button" class="btn btn-danger"> <i class="fa fa-warning"></i>
                                    Please enter one side or single gusset only.</button>
                                </span>  
                             </div> <span id="gussetsugg" style="color:blue;font-size:11px;"></span>   
                        </div>
                      </div>
                      </div>
                      
            		<div class="form-group">
								<label class="col-lg-3 control-label"></label> 
								<div class="col-lg-7">
									<section class="panel">
									  <div class="table-responsive">
										<table class="tool-row table-striped  b-t text-small" id="myTable">
										  <thead>
											  <tr>
                                                <th><span class="required">*</span>Color</th>
                                                <th></th>
                                                <th><span class="required">*</span>Qty</th>
                                                <th><span class="required">*</span>Rate </th>
                                                <th>Custom Product Description</th>
                                              	<?php if(isset($invoice_detail['product_id']) && $invoice_detail['product_id']!=0){ ?>
                                                <th></th>
                                                <?php } ?>
                                              </tr>
										  </thead>
                                          <tbody id="myTbody">
                                             
                             <?php $colors = $obj_pro_invoice->getActiveColor();
                             //printr($colors);
                             ?>
                             
                               <input type="hidden" id="color_arr" value='<?php echo json_encode($colors);?>' />       
                            <?php if(isset($proforma_in_id)) {
							$color = $obj_pro_invoice->getColorDetails($proforma_id,$proforma_in_id); 
							//printr($color);
							}else{
								$color[]=array('id' =>'',
            					'proforma_id' => '',
            					'proforma_invoice_id' =>'',
            					'color' =>'',
            					'color_text' =>'', 
            					'rate' =>'',
            					'quantity' =>'',
            					'description' =>'', 
           						 'color_name' =>''
								 );
							}
							if($color)
							{
							$i = 0;										
							foreach($color as $colorkey => $clr) {
							//printr($color) ;?>
                            <input type="hidden" name="cyl" id="cyl" value="<?php echo isset($clr['color'])?$clr['color']:'';?>"/>
                            <tr id="tr_<?php echo $i; ?>">  
							<td> 
                            	<select name="color[<?php echo $colorkey;?>][color]" id="color_<?php echo $i; ?>" class="form-control validate[required]" 
                                onchange="color(<?php echo $i; ?>)">
                                    <option value="">Select Color</option>
                                    <option id="cy_option" value="0" <?php if(($clr['color']!='') && ($clr['color']==0)){ echo 'selected="selected"'; }?> 
                                    style="display:none;" >Cylinder</option>
                                    <?php foreach($colors as $colors2){ ?>
										<option value="<?php echo $colors2['pouch_color_id']; ?>" id="option"
                                        <?php if($clr['color'] == $colors2['pouch_color_id']) { echo 'selected="selected"'; } ?>> 
										<?php echo $colors2['color']; ?></option>
                                        <?php } ?>  
                                        <option value="-1" id="clr_option" <?php if($clr['color'] != '' && $clr['color'] == '-1')
												{ echo 'selected="selected"'; } ?>> Custom</option>                                      
                                  </select>
                             </td>
                             <td>
                         	  <?php if(($clr['color']) == '-1'  && (empty($clr['color_text']) || !empty($clr['color_text']))){ ?>
                              <input type="text" name="color[<?php echo $colorkey; ?>][color_text]" value="<?php echo $clr['color_text']; ?>" 
                              id="color_txt_<?php echo $i; ?>" class="form-control"/>
                              <?php }else{ ?>
								<input type="text" name="color[<?php echo $colorkey; ?>][color_text]" value="<?php echo $clr['color_text']; ?>" 
                                id="color_txt_<?php echo $i; ?>" class="form-control" <?php if(empty($clr['color_text'])) {?> style="display:none" <?php }?>/>  
								<?php }?>  </td>
                               <td><input type="text" name="color[<?php echo $colorkey; ?>][qty]" value="<?php echo $clr['quantity']; ?>" id="qty" 
                               class="form-control validate[required,custom[number],min[1]]" placeholder="Qty"></td>
								<td><input type="text" name="color[<?php echo $colorkey; ?>][rate]" value="<?php echo $clr['rate']; ?>" id="rate" class="form-control validate[required,custom[number],min[0.001]]" placeholder="Rate" rate_price="rate_<?php echo $i; ?>"></td>
                                <td><input type="text" name="color[<?php echo $colorkey; ?>][description]" value="<?php echo $clr['description']; ?>" 
                                id="description" class="form-control" placeholder="Description">
                             <input type="hidden" name="color[<?php echo $colorkey; ?>][id]" value="<?php echo $clr['id']; ?>" class="form-control validate[min[1]]" placeholder="id" id="id_<?php echo $i;?>">
                                </td>
                               
                                <?php //if(isset($invoice_detail['product_id']) && $invoice_detail['product_id']!=''){ ?>
                                <td ><?php if($i == 0) { ?><a class="btn btn-success btn-xs btn-circle addmore" data-toggle="tooltip" data-placement="top" 
                                title="Add Color" id="addmore" ><i class="fa fa-plus"></i></a>
								<?php } else {?>
                                 <a class="btn btn-danger btn-xs btn-circle remove" data-toggle="tooltip" data-placement="top" title="Remove" 
                                 onclick="remove_tr(<?php echo $i; ?>)" ><i class="fa fa-minus"></i></a>
                                 <?php } ?>                                
                                </td>
                                </tr>
							<?php $i++; } } ?>
                                </tbody>
								</table>
								</div>
                               </section> 
								</div>
			  </div>
               
               		<div class="form-group">
                     <div class="col-lg-9 col-lg-offset-3">
                      <button type="button"  name="btn_save" id="btn_save" class="btn btn-primary" <?php if(isset($_GET['proforma_in_id'])){ ?> style="display:none" <?php }?> onclick="displaygenerate();">Add Product</button> 
                      	<?php if(isset($proforma_id) && isset($proforma_in_id)) {?>
							<input type="hidden" name="pro_id" value="<?php echo $proforma_in_id;?>" id="pro_id" />
                 		     <button type="button" name="proforma_update" id="proforma_update" class="btn btn-primary">Update Product</button> 
                     <?php } ?>						
           			 </div>
           	 	</div>
			
            		<div id="invoice_results">
   		   <?php 
			if(isset($proforma_inv['proforma_id'])){
			$proforma = $obj_pro_invoice->getProformaInvoice($proforma_inv['proforma_id']); ?>
        	<table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Size</th>
                    <th>Color : Quantity</th>
                    <th>Rate</th>
                    <th>Option</th>
                    <th>Transport</th>	 
                    <th>Action</th>                
                </tr>
            </thead>
            <tbody>
        <?php
   		$pro = $obj_pro_invoice->getProforma($proforma_inv['proforma_id']);
	   	$getInvoices = $obj_pro_invoice->getInvoice($proforma_inv['proforma_id']);
		//printr($getInvoices);
		//die;
	   	if(isset($getInvoices) && !empty($getInvoices)) {
            foreach($getInvoices as $invoice ) {
				
                //get spout details
                $getProductSpout = $obj_pro_invoice->getSpout(decode($invoice['spout']));
                //get zipper details
                $getProductZipper = $obj_pro_invoice->getZipper(decode($invoice['zipper']));
                //get accessorie details
                $getProductAccessorie = $obj_pro_invoice->getAccessorie(decode($invoice['accessorie'])); ?>
                <input type="hidden" name="proforma_id" value="<?php echo $proforma_id; ?>"  />
                <tr id="proforma_invoice_id_<?php echo $invoice['proforma_invoice_id']; ?>">

				  <td><b><?php echo $invoice['product_name']; ?></b></td>
                  <?php if(isset($invoice['gusset']) && $invoice['gusset'] != 0) { ?>
				  <td><?php echo floatval($invoice['width']).'X'.floatval($invoice['height']).'X'.floatval($invoice['gusset']);
				  if(!empty($invoice['volume'])) { echo ' ('.$invoice['volume'].')'; }
				   ?></td>
                  <?php } else { ?>
                  <td><?php echo floatval($invoice['width']).'X'.floatval($invoice['height']);
				  if(!empty($invoice['volume'])) { echo ' ('.$invoice['volume'].')'; }
				   ?></td>
                  <?php } ?>
				  <?php $quantity = $obj_pro_invoice->getColorDetails($proforma_inv['proforma_id'],$invoice['proforma_invoice_id']); //printr($quantity);//die;?>
				  <td>
				  <?php
                  	foreach($quantity as $quantity_val) {
						  $clr_text='';
						if($quantity_val['color']=='-1')
						{
							$clr_text = "(".$quantity_val['color_text'].")";
						}
					  	$clr_nm =  $quantity_val['color_name'];
					  
						echo $clr_nm.''.$clr_text.' : '.$quantity_val['quantity'].'<br>';				 
				  } ?>
				  </td>
                  
				  <td>
				  <?php foreach($quantity as $rate_val) {
					
					  echo $rate_val['rate'].'<br>';
				  } ?>
				  </td>
				  <td><?php echo ucwords($getProductSpout['spout_name']).' '.$invoice['valve'].'<br>'.$getProductZipper['zipper_name'].' '.
				  ucwords($getProductAccessorie['product_accessorie_name']); ?></td>
				  <td><?php echo 'By '.ucwords(decode($pro['transportation'])); ?></td>
				  <td class="del-product"><a class="btn btn-danger btn-sm" href="javascript:void(0);" 
                  onClick="removeInvoice(<?php echo $invoice['proforma_invoice_id'].','.$invoice['proforma_id'];; ?>)"><i class="fa fa-trash-o"></i></a>
                 <a href="<?php echo$obj_general->link($rout, 'mod=add&proforma_id='.encode($proforma_id).'&proforma_in_id='.
				 encode($invoice['proforma_invoice_id']).'&is_delete='.$_GET['is_delete'].''.$add_url,'',1); ?>" id="btn_edit"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                  </td>
				  </tr>
                  <!-- CONFIRMATION ALERT BOX -->
                    <div class="modal fade" id="alertbox_<?php echo $invoice['proforma_invoice_id']; ?>">
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
                                    <button type="button" name="popbtnok" id="popbtnok_<?php echo $invoice['proforma_invoice_id']; ?>" 
                                    class="btn btn-primary">Ok</button>
                                 
                                </div>
                            </div><!-- /.modal-content -->
                        </div><!-- /.modal-dialog -->
                    </div>
                    <!-- END OF CONFIRMATION BOX-->
<?php } } else { ?>
    <tr>
    	<td colspan="7">No Records Found!!!</td>  
    </tr>
    <?php } }?>
		  </tbody></table>
	</div>
   
 		<?php if($edit){?>
				<div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                    <button type="button" name="btn_update" id="btn_update" class="btn btn-primary">Update </button>
                    <a class="btn btn-default" href="<?php echo $obj_general->link($rout, 'mod=index&is_delete='.$_GET['is_delete'].$add_url, '',1);?>">Cancel</a>
                </div>
            </div>
         <?php }  ?>
                <div class="col-lg-9 col-lg-offset-3">
          <?php //if(!($edit)) {?>
         	<button type="submit" id="generate_invoice"  class="btn btn-primary" name="generate_invoice" onClick="add_invoices()" style="display:none" >Generate Proforma Invoice
             </button>
			<?php //} ?>
        </div>
		</form>
        
          </div>
        </section>
        
       </div>
    
    </div>
  
  </section>
  
  <div class="modal fade" id="smail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" style="width:350px">
        <div class="modal-content">
        <form class="form-horizontal" method="post" name="sform" id="sform" style="margin-bottom:0px;">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">Size Table</h4>
                    </div>
                    <div  id="toolpriceview">
                    </div>
            </form>                               
        </div>
      </div>
    </div>

</section>
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
<style type="text/css">
@media (max-width: 400px) {
  .chunk {
    width: 100% !important;
  }
}
</style>
<script>

    jQuery(document).ready(function(){
		/*var con_id=$("#con_id").val();
		if(con_id == 111)
			$("#product option[value^=39]").remove();*/
			
			
			
	$("#customer-name").focus();
	var offset = $("#customer-name").offset();
	var width = $("#holder").width();
	$("#ajax_response").css("width",width);
	
	$("#customer-name").keyup(function(event){		
		 var keyword = $("#customer-name").val();
		// alert(keyword);
		 if(keyword.length)
		 {	//[kinjal] : changed 13-4-2017 
			 if(event.keyCode != 40 && event.keyCode != 38 )
			 {
				 var product_code_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=customer_detail', '',1);?>");
				 $("#loading").css("visibility","visible");
				 $.ajax({
				   type: "POST",
				   url: product_code_url,
				   data: "customer_name="+keyword,
				   success: function(msg){	
				 var msg = $.parseJSON(msg);
				 //alert(msg);
				 	//console.log(msg);
				   var div='<ul class="list">';
				   
					if(msg.length>0)
					{ 	//alert(msg[1].address);
						for(var i=0;i<msg.length;i++)
						{	
						div =div+'<li><a href=\'javascript:void(0);\' id="'+msg[i].address_book_id+'" company_add_id="'+msg[i].company_address_id+'" factory_add_id="'+msg[i].factory_address_id+'" consignee="'+msg[i].c_address+'" deladd="'+msg[i].f_address+'" email="'+msg[i].email_1+'" tin_no="'+msg[i].vat_no+'"><span class="bold" >'+msg[i].company_name+'</span></a></li>';
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
							$("#input-address").val($(".list li[class='selected'] a").attr("consignee"));
							$("#input-del-address").val($(".list li[class='selected'] a").attr("deladd"));
							//$("#delivery_info").val($(".list li[class='selected'] a").attr("deladd"));
							//$("#del_info").val($(".list li[class='selected'] a").attr("deladd"));
							$("#vat_no").val($(".list li[class='selected'] a").attr("tin_no"));
							$("#address_book_id").val($(".list li[class='selected'] a").attr("id"));
							$("#company_address_id").val($(".list li[class='selected'] a").attr("company_add_id"));
							$("#factory_address_id").val($(".list li[class='selected'] a").attr("factory_add_id"));
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
							$("#input-address").val($(".list li[class='selected'] a").attr("consignee"));
							$("#input-del-address").val($(".list li[class='selected'] a").attr("deladd"));
							//$("#delivery_info").val($(".list li[class='selected'] a").attr("deladd"));
							//$("#del_info").val($(".list li[class='selected'] a").attr("deladd"));
							$("#vat_no").val($(".list li[class='selected'] a").attr("tin_no"));
							$("#address_book_id").val($(".list li[class='selected'] a").attr("id"));
							$("#company_address_id").val($(".list li[class='selected'] a").attr("company_add_id"));
							$("#factory_address_id").val($(".list li[class='selected'] a").attr("factory_add_id"));

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
					  $("#input-address").val($(this).attr("consignee"));
					  $("#input-del-address").val($(this).attr("deladd"));
					////  $("#delivery_info").val($(this).attr("deladd"));
					//  $("#del_info").val($(this).attr("deladd"));
					  $("#vat_no").val($(this).attr("tin_no")); 
					  $("#address_book_id").val($(this).attr("id"));
					  $("#company_address_id").val($(this).attr("company_add_id"));
					  $("#factory_address_id").val($(this).attr("factory_add_id"));
					  
					  $(this).addClass("selected");
				});
				$(this).find(".list li a:first-child").mouseout(function () {
					  $(this).removeClass("selected");
					  //$("#email").val('');
					  $("#input-address").val('');
					  $("#input-del-address").val('');
					  //$("#delivery_info").val('');
					  $("#vat_no").val(''); 
					  $("#address_book_id").val('');
					  $("#company_address_id").val('');
					  $("#factory_address_id").val('');
				});
				$(this).find(".list li a:first-child").click(function () {
					  
					  if($(this).attr("email")!='null')
					  	$("#email").val($(this).attr("email"));
					  else
					  	$("#email").val('');
						
					  if($(this).attr("consignee")!='null')
					  	$("#input-address").val($(this).attr("consignee"));
					  else
					 	 $("#input-address").val('');
						 
					  if($(this).attr("deladd")!='null')
					 	 $("#input-del-address").val($(this).attr("deladd"));
					  else
					  	 $("#input-del-address").val('');	
						//alert($(this).attr("company_add_id"));
						 
					  $("#vat_no").val($(this).attr("tin_no"));
					  $("#address_book_id").val($(this).attr("id"));
					  if($(this).attr("company_add_id")!='null')
					 	 $("#company_address_id").val($(this).attr("company_add_id"));
					  else
					  	 $("#company_address_id").val('');	
					
					  if($(this).attr("factory_add_id")!='null')
					 	 $("#factory_address_id").val($(this).attr("factory_add_id"));
					  else
					  	  $("#factory_address_id").val('');
						  
					  $("#customer-name").val($(this).text());
					  $("#ajax_response").fadeOut('slow');
					  $("#ajax_response").html("");
					
				});
				
			});
				
        jQuery("#form").validationEngine();
		$("#cy_option").css("display","none");
		var product_id=$("#product").val();
		if(product_id==0)
			$(".addmore").hide();
		
    });
	 var fetched_zipper = $("#fetched_zipper").val();
	   if(fetched_zipper == '') {
			checkZipper();
	   }
	   
		jQuery("#form").validationEngine();
		$("#country_id").change(function(){
			
			var country_id = $(this).val();
			var con_id=$("#con_id").val();
			//alert(country_id);
			if(country_id == 111)
			{	$('input:radio[name=transport][value=road]').attr('checked','checked');
				$("#tax_div").show();
				$("#nrm_div").show();
				$("#freight").show();				
			}
			else if(country_id == 169)
			{
				//$('input:radio[name=transport][value=air]').attr('checked','checked');
				$("#tax_div").hide();
				$("#nrm_div").hide();
				//$("#freight").hide();
				$("#fm_div").hide();
			}
			else
			{	//$("#freight_char").val('');	
				$('input:radio[name=transport][value=air]').attr('checked','checked');
				$("#tax_div").hide();
				$("#nrm_div").hide();
				//$("#freight").hide();
				$("#fm_div").hide();
				//$("#freight_char").val('');
			}
			if(country_id==169)
			{
				$("#freight").show();		
			}
			
			getgst();
			
		});
		
	
	
		function getgst()
		{
			var con_id=$("#con_id").val();
			var country_id = $("#country_id").val();
			if(con_id == '214')
			{
				var invoice_date=$("#input-name").val();
				var gst = $("#gst").val();
				//var dNow = new Date();
				//var utcdate= dNow.getFullYear() + '-' + (dNow.getMonth()+ 1) + '-' + dNow.getDate();
				var utcdate='2016-02-01';
				
				if(country_id == '214')
				{
					if(invoice_date!='' && invoice_date < utcdate)
					{
						
						$("#gst_tax").val('0');
					}
					else
					{
						$("#gst_tax").val(gst);
					}
				}
				else
					$("#gst_tax").val('0');
					//$("#gst_tax").val('');
					//$("#gst_tax").removeAttr('readonly', 'readonly');
				/*}
				else
				{
					//alert("ert");
					$("#gst_tax").val('0');
					//$("#gst_tax").attr('readonly', true);
				}*/
			}
		}
		
			
		$('input[type=radio][name=taxation]').change(function() {
			
		if(this.value == 'normal'){
				$("#fm_div").hide();
				$("#nrm_div").show();
		} else {
				$("#nrm_div").hide();
				$("#fm_div").show();
		}
		});
		
		    $('input[type="checkbox"]').click(function(){
				if($('#h_form').prop("checked") == true) {		
					$("#nrm_div").hide();
				}else if($("#ct1").prop("checked") == true || $("#ct3").prop("checked") == true)
				{
            			$("#nrm_div").show();
				}
				else
				{	
					$("#nrm_div").hide();
				}
        	});
	
	if($("input:radio[name=taxation]:checked").val() == 'normal') {
		
		$("#nrm_div").show();
	} else {
		
		$("#fm_div").show();
		
		if($('input[name="form[]"]:checked').val() == 'CT3' || $('input[name="form[]"]:checked').val() == 'CT1')
		{	
			$("#nrm_div").show();
			
		}
		else
		{	
			$("#nrm_div").hide();
		}
	}
	
		
		
		$("#country_id").change(function(){			
			var stext = $('#country_id').find('option:selected').text().toLowerCase();
			if( stext === "india"){
				$(".ch1").hide();
				$(".ch2").hide();
				$(".ch3").show();	
			}
			else if ( stext === "nepal"){
				$(".ch1").show();
				$(".ch2").show();
				$(".ch3").show();
			}	
			else{
				$(".ch1").show();
				$(".ch2").show();
				$(".ch3").hide();
			}
	    }).change();	


				
		
function add_invoices() {
//	alert("hi");
	$("#product").prop('disabled', true);
	$("#size").prop('disabled', true);
	$("#color_0").prop('disabled', true);
	$("#qty").prop('disabled', true);
	$("#rate").prop('disabled', true);
}
function color(n)
{
	var val = $("#color_"+n).val();
	$("#color_txt_"+n).val('');
	$("#color_txt_"+n).hide();
	if(val == -1)
	{ 	
		$("#color_txt_"+n).show();
	}
	var country_id = $('#country_id').val();
						if(country_id=='111'){
							getrate(val,n);
						}
}
	function displaygenerate()
	{
		var edit=$("#edit").val();
		//alert(edit);
		if(edit=='')
			
			$("#generate_invoice").show();
			
		else
			$("#generate_invoice").hide();
				
	}
		//sonu add if condition  [Oxo-Degradable Bags - Brand: Bak2Earth - Stand up pouch] product  20-1-2017  
		//[online product id = 22] Silica Gel / Moisture Absorbers[online product id 38]
		
	//var product_id=$("#product").val();
	//alert(product_id);
	/*if(product_id==28 )
					{
						$("#valve_div").hide();
						$("#zipper_div").hide();
						$("#spout_div").hide();
						$("#acce_div").hide();
				}
				else{
						$("#valve_div").show();
						$("#zipper_div").show();
						$("#spout_div").show();
						$("#acce_div").show();
						//$("#btn_save").show();
					}*/
	$("#product").change(function(){
			var val = $(this).val();
			//alert(val);
			$("#cy_option").hide();
			$("#color_0").prop('disabled',false);
			$("#color_0").show();
			$(".addmore").show();
			
	
				/*if(val==28 )
				{
					$("#valve_div").hide();
					$("#zipper_div").hide();
					$("#spout_div").hide();
					$("#acce_div").hide();
					$("#btn_save").show();
					
					
				}
				else
				{
					$("#valve_div").show();
					$("#zipper_div").show();
					$("#spout_div").show();
					$("#acce_div").show();
					$("#btn_save").show();
				}*/
	
		// sonu end 
			
			var text = $("#product option[value='"+val+"']").text().toLowerCase();
			$("#width").val('');
			$("#height").val('');
			$("#gusset_input").val('');
			$("#customSize").hide();
			
			if(val==0)
			{
				//alert(0);
				$("#valve_div").hide();
				$("#zipper_div").hide();
				$("#spout_div").hide();
				$("#acce_div").hide();
				
				$("#color_txt_0").hide(); 
				$(".addmore").hide();
				
				$("#cy_option").removeAttr('style');
				$("#color_0 option[value=0]").attr('selected','selected');
				$("#color_0").prop('disabled',true); //--
				$('#myTable tr').not(':eq(0)').not(':eq(0)').remove(); //--
			}
			else if(val=='28')
			{//alert(28);
				$("#valve_div").hide();
				$("#zipper_div").hide();
				$("#spout_div").hide();
				$("#acce_div").hide();
			}
			else
			{
					$("#valve_div").show();
					$("#zipper_div").show();
					$("#spout_div").show();
					$("#acce_div").show();
					
				$("#wv").prop("disabled",false);
				jQuery("input[name='spout']").each(function(i) {
					jQuery(this).prop("disabled",false);
					if($(this).val()=='MQ==')
						$(this).prop('checked', 'checked');
				});
				jQuery("input[name='accessorie']").each(function(i) {
					jQuery(this).prop("disabled",false);
					if($(this).val()=='NA==')
						$(this).prop('checked', 'checked');
				});
			}
			
			if(val=='31' || val=='16')
				$("#filling_div").show();
			else
				$("#filling_div").hide();
			
			checkGusset();
			checkZipper();
			showSize();
		});
	function checkZipper(){
	//alert("hii");
		var gusset_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=checkProductZipper', '',1);?>");
		var product_id = $('#product').val();
	$.ajax({
		type: "POST",
		url: gusset_url,
		dataType: 'json',
		data:{product_id:product_id}, 
		success: function(response) {
		//alert(response);
			$('#zipper_div').html(response);
			showSize();	
		}
	});
}
$("#customSize").hide();
if($('#size').val() == 0) {
	$("#customSize").show();
}
function customSize()
		{
			if($('#size').val()==0)
			{
				$("#customSize").show();	
			}
			else
			{
				$("#customSize").hide();
				$("#width").val('');
				$("#height").val('');
				$("#gusset").val('');
			}
			
			blankselectedvalue();
		}
function checkGusset(){
	var gusset_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=checkProductGusset', '',1);?>");
	var product_id = $('#product').val();
	$.ajax({
		type: "POST",
		url: gusset_url,
		dataType: 'json',
		data:{product_id:product_id}, 
		success: function(response) {
			if(response==0){
				$(".gusset").hide();	
				$('#gusset_input').val("");
			}else{
				$(".gusset").show();
			}
		}
	});
}
function showSize()
{	
	$( "#zip" ).prop( "disabled", false );
	var zipper_id=$("input[class='zipper']:checked").val();
	//alert(zipper_id);
	var product_id = $('#product').val();
	//alert(product_id);
	var size_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getProductSize', '',1);?>");
	$.ajax({
		type: "POST",
		url: size_url,					
		data:{product_id:product_id,zipper_id:zipper_id},
		success: function(json) {
			//alert(json);
			if(json){
				$("#size_div").html(json);
				if(product_id==10)
				{
					$("#size option[value='0']").prop('disabled',true);
				}
			}else{
				$("#size_div").html('<span class="btn btn-danger btn-xs">Please select Product for Size option.</span>');
			}
			$("#loading").hide();
		}
	});			
}


$(' .addmore').click(function(){

		var html = '';
		var count = $('#myTable tr').length;
		var color = (count-1);
		var size_id = (color-1);
		var countAddmore = $('select#color_0 option#option').length;
		//alert($('#color_arr').val());
		var arr = jQuery.parseJSON($('#color_arr').val());	
			html +='<tr><td>';
			html += '<select name="color['+color+'][color]"  id="color_'+color+'" class="form-control validate[required]" onchange=color('+color+')><option value="">Select color</option>';
				for(var i=0;i<arr.length;i++)
				 {
				 	html += '<option value="'+arr[i].pouch_color_id+'">'+arr[i].color+'</option>';
				 }
			html +='<option id="option" value="-1">Custom</option>';
			html +=  '</select>';							
			html += '</td>';
			html +='<td>';
			html += '<input class="form-control" type="text" value="" name="color['+color+'][color_text]" id="color_txt_'+color+'" style="display:none">';
			html += '</td>';
			html +='<td>';
			html += '<input type="text" name="color['+color+'][qty]" value="" class="form-control validate[required,custom[number],min[1]]" placeholder="Qty">';
			html += '</td>';			
			html +='<td>';
			html += '<input type="text" name="color['+color+'][rate]" value="" class="form-control validate[required,custom[number],min[0.001]]" placeholder="Rate" rate_price="rate_'+color+'" id="rate">';
			html +='</td>';
			html +='<td>';
			html +='<input type="text" name="color['+color+'][description]" value="" class="form-control" placeholder="Description"><input type="hidden" name="color['+color+'][id]" class="form-control validate[min[1]]" placeholder="id" id="id_'+color+'">';
			html +='</td>';
			html +='<td>';
			html +='<a class="btn btn-danger btn-xs btn-circle remove" data-toggle="tooltip" data-placement="top" title="Remove" onclick="remove_tr('+color+')"><i class="fa fa-minus"></i></a>';
			html +='</td></tr>';
						
		$('.tool-row').append(html);
		var color_id = (color-1);
		var option = $("#color_"+color_id+" option:selected").text();
		var id = $('#color_'+color_id+'').val();
		if(count >= countAddmore) {
			$("#addmore").hide();
		}
		var new_id = +id+1;
		$(' .remove').click(function(){
			$("#addmore").show();
			$(this).parent().parent().remove();
		});
		
	});


$('#btn_save').click(function(){
	/*$("#valve_div").show();
	$("#zipper_div").show();
	$("#spout_div").show();
	$("#acce_div").show();*/
	$("#taxation1").prop('enable', true);
	$("#taxation2").prop('enable', true);
	$("#taxation3").prop('enable', true);
	$("#taxation4").prop('enable', true);
	$("#taxation_nrm").prop('enable', true);	
	$("#taxation_frm").prop('enable', true);
	$("#h_form").prop('enable', true);
	$("#ct1").prop('enable', true);
	$("#ct3").prop('enable', true);
	
	//$("#generate_invoice").show();
								
	if($("#form").validationEngine('validate')){
			var add_product_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=addProformaInvoice', '',1);?>");
				var formData = $("#form").serialize();
				//alert(formData);
				$.ajax({
					url : add_product_url,
					method : 'post',		
					data : {formData : formData},
					success: function(response){
						//alert(response);
						if(response != 0){
	
							$("input:radio[name=zipper]:not(:disabled):first").attr('checked', true);
							$("input:radio[name=valve]:not(:disabled):first").attr('checked', true);
							$("input:radio[name=spout]:not(:disabled):first").attr('checked', true);
							$("input:radio[name=accessorie]:not(:disabled):first").attr('checked', true);
							$("#input-name").prop('disabled', true);
							$("#customer-name").prop('disabled', true);
							$("#email").prop('disabled', true);
							$("#buyersno").prop('disabled', true);
							$("#input-country").prop('disabled', true);
							$("#input-proforma").prop('disabled', true);
							$("#input-delivery").prop('disabled', true);
							$("#input-address").prop('disabled', true);
							$("#input-del-address").prop('disabled', true);
							$("#currency").prop('disabled', true);
							$("#input-payment").prop('disabled', true);
							$("#country_id").prop('disabled', true);
							$("#input-port").prop('disabled', true);
							$("#signature_date").prop('disabled', true);
							$("#bank_id").prop('disabled', true);
							$("#tran1").prop('disabled', true);
							$("#trans2").prop('disabled', true);

							$("#status").prop('disabled', true);
							$("#pro_in_no").prop('disabled', true);
							$("#taxation1").prop('disabled', true);
							$("#taxation2").prop('disabled', true);
							$("#taxation3").prop('disabled', true);
							$("#taxation4").prop('disabled', true);
							$("#taxation_nrm").prop('disabled', true);
							$("#taxation_frm").prop('disabled', true);
							$("#freight_char").prop('disabled', true);
							$("#packing_charges").prop('disabled', true);
							$("#gst_tax").prop('disabled', true);
							$("#h_form").prop('disabled', true);
							$("#ct1").prop('disabled', true);
							$("#ct3").prop('disabled', true);
							$("#product").val('');
							$("#size").val('');
							$("#customSize").hide();
							$('#description').val('');
							$("#width").val('');
							$("#height").val('');
							$("#gusset_input").val('');
							$("#color_0").val('');
							$("#color_txt_0").val('');
							$("#qty").val('');
							$("#rate").val('');
							$("#product_desc").val('');
							$('#myTable tr').not(':eq(0)').not(':eq(0)').remove();
							$("#myTable tr:nth-child(2)").show();
							$("#invoice_results").html(response);
							$("#filling_div").hide();
							
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

function removeInvoice(proforma_invoice_id,proforma_id){
$("#alertbox_"+proforma_invoice_id).modal("show");
$(".modal-title").html("Delete Record".toUpperCase());
$("#setmsg").html("Are you sure you want to delete ?");
$("#popbtnok_"+proforma_invoice_id).click(function(){
	var remove_invoice_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=removeInvoice', '',1);?>");
	$.ajax({
		url : remove_invoice_url,
		method : 'post',
		data : {proforma_invoice_id : proforma_invoice_id,proforma_id:proforma_id},
		success: function(response){
		//   alert(response);
			if(response == 0) {			
			$("#alertbox_"+proforma_invoice_id).hide();
		}
			$("#alertbox_"+proforma_invoice_id).hide();
			$("#alertbox_"+proforma_invoice_id).modal("hide");
			$('#proforma_invoice_id_'+proforma_invoice_id).html('');
			set_alert_message('Proforma Invoice Record successfully deleted','alert-success','fa fa-check');
			},
		error: function(){
			return false;	
		}
	});
$("#alertbox_"+proforma_invoice_id).hide();
$("#alertbox_"+proforma_invoice_id).modal("hide");
 });
}
  
$('select[name=currency]').change(function() {
		var currency_id=$(this).attr('id');
		var currency_value = this.value;
		var currency_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getbankDetails', '',1);?>");
        $.ajax({
			url : currency_url,
			type :'post',
			data :{currency_id:currency_value},
			success: function(responce){
				if(responce != '') {
					$("#bank_details").html();
					$("#bank_details").html(responce);
				} else {
					var BD = '<span class="btn btn-danger btn-xs form-control validate[required]">Please Change Currency for Bank Option.</span>';
					$("#bank_details").html();
					$("#bank_details").html(BD);
				}
			},
			error:function(){
				return false;
			}			
		});
    });
	 
$("#proforma_update").click(function() {
					/*$("#valve_div").show();
					$("#zipper_div").show();
					$("#spout_div").show();
					$("#acce_div").show();*/
	if($("#form").validationEngine('validate')){
	var update_invoice_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=updateInvoice', '',1);?>");
			var formData = $("#form").serialize();
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
						$('#proforma_update').hide();
						$('#product').val('');
						$('#customSize').hide();
						$("#color_0").val('');
						$("#color_txt_0").hide();
						$("#qty").val('');
						$("#rate").val('');
						$("#product_desc").val('');
						$('#myTable tr').not(':eq(0)').not(':eq(0)').remove();
						$('#size').val('');
						$('#description').val('');
						$("input:radio[name=zipper]:not(:disabled):first").attr('checked', true);
						$("input:radio[name=valve]:not(:disabled):first").attr('checked', true);
						$("input:radio[name=spout]:not(:disabled):first").attr('checked', true);
						$("input:radio[name=accessorie]:not(:disabled):first").attr('checked', true);
						$("#filling_div").hide();
					}
				},
				error: function(){
		
					return false;
				}
			
			});
	}
	
});
function remove_tr(tr_id) {
	$("#tr_"+tr_id).hide();
	$("#color_"+tr_id).find('option:selected').removeAttr("selected");
}
$("#btn_update").click(function() {
 $("#product").prop('disabled', true);
	$("#size").prop('disabled', true);
	$("#color_0").prop('disabled', true);
	$("#qty").prop('disabled', true);
	$("#rate").prop('disabled', true);
	
	var update_proforma_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=updateProforma', '',1);?>");
	var myform = $('#form');
	var disabled = myform.find(':disabled').removeAttr('disabled');
	var formData = myform.serialize();
			$.ajax({
				url : update_proforma_url,
				method : 'post',		
				data : {formData : formData},
				success: function(response){
				//alert(response);
					var is_delete=<?php echo $_GET['is_delete'];?>;
					if(response != 0){
					
						var url =  getUrl("<?php echo $obj_general->link($rout, '&mod=index&is_delete='.$_GET['is_delete'].$add_url, '',1); ?>");
							//console.log(url);
							
						var redirect = setTimeout(function(){
						/*var status_url = getUrl("<?php //echo $obj_general->ajaxLink($rout, '&mod=index', '',1);?>");
							var url = status_url+'&is_delete='+is_delete;
							window.location.href = url;*/
							
							window.location = url; 	}, 800);						
					   set_alert_message('Proforma Invoice Record successfully updated ','alert-success','fa fa-check');

					}
				},
				error: function(){		
					return false;
				}
			
			});
});
$(document).ready(function() {
	 $("#input-proforma").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
	 $("#input-name").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide'); getgst();});
	 $("#input-buyerdate").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
	 $("#signature_date").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
});
var windowSizeArray = [ "width=200,height=200",
                                "width=300,height=400,scrollbars=yes" ];
        $(document).ready(function(){
            $('#mydiv').click(function (event){
			 var product_id = $("#product").val();
				var pop_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getViewToolprice', '',1);?>");
				$.ajax({
					method: "POST",					
					url: pop_url,
					data : {product_id : product_id},
					success: function(response){						
						//alert(response);
						$("#toolpriceview").html(response);
						$("#smail").modal('show');						
					},
					error: function(){
							return false;	
					}
				});
            });
        });

//$("vat_no").modal('hide');

$("#width").keydown(function(e) {
			 if (e.keyCode == 9) {
			 var width = $(this).val();
			 var product_id = $("#product").val();
			 var gusset = $("#gusset_input").val();			
				var widthsuggestion_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getWidthSuggestion', '',1);?>");
				$.ajax({
					method: "POST",					
					url: widthsuggestion_url,
					data : {width : width,product_id :product_id,gusset:gusset},
					success: function(response){												
							if(response=="Got")
							{
								$("#widthsugg").hide();
								$("#gussetsugg").hide();
							}
							else
							{
								var val = $.parseJSON(response);
								$("#widthsugg").show();
								$("#widthsugg").html("Suggested Width : "+val+"mm else tool price will be applicable");
								if(gusset !='')
								{
								var widthsuggestion_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getGussetSuggestion', '',1);?>");
								$.ajax({
									method: "POST",
									url: widthsuggestion_url,
									data : {width : width,product_id :product_id,gusset :gusset},
									success: function(response){
									if(response=="Got")
										$("#gussetsugg").hide();
									else
									{
										var val = $.parseJSON(response);
										$("#gussetsugg").show();
										$("#gussetsugg").html(val);
									}
									},
									error: function(){
									return false;	
									}
									});
								}
							}
					},
					error: function(){
							return false;	
					}
				});
			 }
		});
$("#gusset_input").keydown(function(e) {
			if (e.keyCode == 9) {
				 var width = $("#width").val();
			 	 var gusset = $(this).val();
			 	 var product_id = $("#product").val();
				 var basecurr = $("#basecurr").val();
				 var widthsuggestion_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getGussetSuggestion', '',1);?>");
				 $.ajax({
					method: "POST",
					url: widthsuggestion_url,
					data : {width : width,product_id :product_id,gusset :gusset,basecurr:basecurr},
					success: function(response){
							if(response=="Got")
								$("#gussetsugg").hide();
							else
							{
								var val = $.parseJSON(response);
								$("#gussetsugg").show();
								$("#gussetsugg").html(val);
							}
					},
					error: function(){
							return false;	
					}
				});
			 }
	});
	
$("#same-above").click(function(){
	
	$("#loading").show();
	if($(this).prop('checked') == true){
		var bill = $("#input-address").val();
		$("#input-del-address").val(bill);
		$("#billing-details").slideUp('slow');
		$("#same-above").val(1);
	}else{
		var del = $("#del_info").val();
		$("#input-del-address").val(del);
		$("#billing-details").slideDown('slow');
		$("#same-above").val(0);
	}
	//alert($("#same-above").val());
	$("#loading").fadeOut();
});
//[Mansi] : (9-6-2016) for get tax value	
	
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

						$("#gst").val(msg.gst);

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

						$("#gst").val('');

						$("#pst").val('');

					}

					else if(msg.gst== '0.000' && msg.rst== '0.000')

					{	

						$("#gst").val(msg.gst);

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
//[sonu] onb 12/11/2016
function getrate(val,n){
	
	var size = $('#size').val();
	var valve = $("input:radio[name=valve]:checked").val();
	var zipper=$("input:radio[name=zipper]:checked").val();
	var spout=$("input:radio[name=spout]:checked").val();
	var accessorie=$("input:radio[name=accessorie]:checked").val();
	var color = val;
	//alert(valve);
		//alert("#rate[rate_price=rate_"+n+"]");	
	//alert("input:text[name=color["+n+"]['rate']]");
	var ratesuggestion_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getproductrate', '',1);?>");
	$.ajax({
		method: "POST",
		url:ratesuggestion_url ,
		data:{size:size,valve:valve,zipper:zipper,spout:spout,accessorie:accessorie,color:color},
		success: function(response)
		{  
			if(response != 0){		
				//alert(response);
				console.log(response);				
				$("#rate[rate_price=rate_"+n+"]").val(response);
			
			}
		}
		
	});
 }
 function blankselectedvalue()
 {
	 $("#color_0").val('');
	 $("#color_txt_0").val('');
	 $("#qty").val('');
	 $("#rate").val('');
	 $('#myTable tr').not(':eq(0)').not(':eq(0)').remove();
	 $("#myTable tr:nth-child(2)").show();
	 
 }
</script> 
<?php  } else { 
		include(DIR_ADMIN.'access_denied.php');
	} ?>
