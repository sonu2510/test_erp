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

//[sonu] (18-4-2017) for get address_book_id wise data
$add_book_id='0';
$add_url='';
if(isset($_GET['address_book_id']))
{
		$add_book_id = decode($_GET['address_book_id']);
		$add_url = '&address_book_id='.$_GET['address_book_id'];
}


$bradcums[] = array(
	'text' 	=> $display_name.' List',
	'href' 	=>$obj_general->link($rout, 'mod=index12&is_delete='.$_GET['is_delete'].$add_url, '',1),
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
	$edit = 1;
	//printr($proforma_inv);
}
if(isset($_GET['proforma_in_id']) && !empty($_GET['proforma_in_id'])){
	$proforma_in_id = decode($_GET['proforma_in_id']);
	$invoice_detail = $obj_pro_invoice-> getSingleInvoice($proforma_in_id);
	//printr($invoice_detail);
}
/*if(isset($_POST['generate_invoice'])) {
	$proforma_data = $obj_pro_invoice->getProforma($_POST['proforma_id']);	
	$addedByinfo=$obj_pro_invoice->getUser($proforma_data['added_by_user_id'],$proforma_data['added_by_user_type_id']);
	$obj_session->data['success'] = 'Invoice successfully Added!';
	page_redirect($obj_general->link($rout, '&mod=view&proforma_id='.encode($_POST['proforma_id']).'&is_delete='.$_GET['is_delete'], '',1));
}*/

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
	$userCurrency_detail = $obj_pro_invoice->getUserCurrencyInfo($user_type_id,$user_id);

	$addedByInfo = $obj_pro_invoice->getUser($user_id,$user_type_id);
//printr($_SERVER['REMOTE_HOST']);
	  $currency = $obj_pro_invoice->getCurrency(); 
	  $buyer_order_no = $obj_pro_invoice->getLastBuyerOrderNo(); 
	  
//	 printr($addedByInfo);
if(($addedByInfo['user_id']=='19' && $addedByInfo['user_type_id']=='4') || ($addedByInfo['country_id']=='14'))
    $buyer_order_no=$buyer_order_no+1;
else
    $buyer_order_no='';
     
     
// printr($buyer_order_no);
        if(isset($_POST['btn_send_customer']))
    	{
    		$_POST['url'] = HTTP_SERVER.'pdf/proformapdf.php?mod='.encode('proforma_invoice').'&token='.rawurlencode($_GET['proforma_id']).'&ext='.md5('php').'&num=0&goods_status=0';
    		$obj_pro_invoice->send_mail_customer($_POST,1);
    		page_redirect($obj_general->link($rout, 'mod=index&is_delete=0', '',1));
    	}               
    /*if($user_type_id==1)
    {
       printr($_SERVER['REMOTE_ADDR']);
       printr($_SERVER['SERVER_ADDR']);
    }*/
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
			  <?php $LastInvoiceId = $obj_pro_invoice->getLastId();?>
              <div class="form-group">
                <input type="hidden" name="invoiceno" placeholder="Invoice No" value="<?php if(isset($proforma_id)) { echo $proforma_inv['proforma_id']; } else { echo ($LastInvoiceId['proforma_id']+1); } ?>" class="form-control validate[required]">
                <label class="col-lg-3 control-label">Invoice Date</label>
                 <div class="col-lg-3">
                 <input type="text" name="invoicedate" readonly="readonly" data-date-format="yyyy-mm-dd" value="<?php if(isset($proforma_inv['invoice_date'])) { echo $proforma_inv['invoice_date']; }else{ echo date("Y-m-d"); } ?>" placeholder="Invoice Date" id="input-name" 
                 class="input-sm form-control datepicker" />
                 </div>
                 <?php if(isset($addedByInfo['country_id']) && $addedByInfo['country_id']=='155')  {?>
                     <label class="col-lg-3 control-label">Generate Proforma As</label>
                     <div class="col-lg-3">
                        <select name="gen_pro_as" id="gen_pro_as" class="form-control validate[required]" >
                            <option value="1" <?php if(isset($proforma_id) && $proforma_inv['gen_pro_as'] == 1) { ?> selected="selected" <?php } ?> >Clifton Packaging SA de CV</option>
                            <option value="2" <?php if(isset($proforma_id) && $proforma_inv['gen_pro_as'] == 2) { ?> selected="selected" <?php } ?> >Swiss Pac</option>
                        </select>
                     </div>
                 <?php } 
                 else { 
                        if((isset($addedByInfo['country_id']) && ($addedByInfo['country_id']=='251' || $addedByInfo['country_id']=='214'))){ ?>
                            <label class="col-lg-3 control-label">Proforma Title</label>
                            <div class="col-lg-3">
                                <input type="text" name="proforma_title" id="proforma_title" value="<?php if(isset($proforma_inv['proforma_title'])) { echo $proforma_inv['proforma_title']; }else{ echo 'PROFORMA INVOICE';}?>"  class="form-control ">
                            </div>
                        <?php } ?>    
                        <input type="hidden" name="gen_pro_as" value="0" />

                 <?php } ?>
              </div>
              <?php $validate='';if($addedByInfo['country_id'] != '111'){$validate=' validate[required]';}?>
              <div class="form-group">
                <label class="col-lg-3 control-label">Proforma</label>
                <div class="col-lg-3">
                	<input type="text" name="Proforma" readonly="readonly" data-date-format="yyyy-mm-dd" value="<?php if(isset($proforma_inv['proforma'])) { echo $proforma_inv['proforma']; } else{ echo date("Y-m-d"); }?>" placeholder="Proforma" id="input-proforma"                   class="input-sm form-control datepicker" />
             	</div>
                <label class="col-lg-3 control-label"><span class="required">*</span>Buyers order No</label>
                <div class="col-lg-1">
                      <input type="text" name="buyersno" id="buyersno" value="<?php if(isset($proforma_inv['buyers_order_no'])) { echo $proforma_inv['buyers_order_no']; }else{ echo $buyer_order_no;}?>"  class="form-control <?php echo $validate;?>">
                 </div><b style="color:#ff0000"> <?php echo "If no buyers no. then enter zero";?> </b>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Buyers Date</label>
                <div class="col-lg-3">
                <input type="text" name="buyers_date" readonly="readonly" data-date-format="yyyy-mm-dd" value="<?php if(isset($proforma_inv['buyers_date'])){ echo $proforma_inv['buyers_date']; }else{ echo date("Y-m-d"); }  ?>" placeholder="Buyers Date" id="input-buyerdate" class="input-sm form-control datepicker" />
                </div>
                <label class="col-lg-3 control-label"><span class="required">*</span>Country of origin of goods</label>
                 <div class="col-lg-3">
                  <input type="text" name="country" id="input-country" value="<?php if(isset($proforma_inv['goods_country'])) {
				   echo $proforma_inv['goods_country']; } else {	if($addedByInfo['country_id'] == '155' || $addedByInfo['country_id'] == '11') {echo $addedByInfo['country_name']="India"; } else {echo $addedByInfo['country_name'];}}?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Customer Name</label>
                <div class="col-lg-3">
              
                	<input type="text" name="customer_name"  value="<?php if(isset($proforma_inv['customer_name'])) { echo $proforma_inv['customer_name']; } ?>" placeholder="Customer Name" id="customer-name" class="form-control validate[required]" />
                    <input type="hidden" name="address_book_id"  value="<?php echo isset($proforma_inv) ? $proforma_inv['address_book_id'] : '' ; ?>" id="address_book_id" class="form-control " />
                    <input type="hidden" name="company_address_id"  value="" id="company_address_id" class="form-control " />
                  <input type="hidden" name="factory_address_id"  value="" id="factory_address_id" class="form-control " />
                  <input type="hidden" name="contact_name"  value="" id="contact_name" class="form-control " />
                    <div id="ajax_return"></div>
                 </div>
                 <label class="col-lg-3 control-label"><span class="required">*</span>Email</label>
                <div class="col-lg-3">
                  <input type="text" name="email" id="email" value="<?php if(isset($proforma_inv['email'])) { echo $proforma_inv['email']; } ?>" placeholder="Email"  class="form-control validate[required,custom[email]]">
                  <span style="color:red;" id="email_msg"></span>
                </div>
              </div>
                 <div class="form-group">
                    <label class="col-lg-3 control-label"><span class="required">*</span>Contact Number</label>
                        <div class="col-lg-3">
                          <input type="text" name="contact_no" id="contact_no" value="<?php if(isset($proforma_inv['contact_no'])) { echo $proforma_inv['contact_no']; } ?>" placeholder="Contact Number"  class="form-control validate[required]">
                        </div>
                  </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Client Address</label>
                <div class="col-lg-8">
                <textarea class="form-control" id="input-address" name="clientaddress"><?php if(isset($proforma_inv['address_info'])) 
				{ echo $proforma_inv['address_info']; } ?></textarea>
                </div>
              </div>
              
               <div class="form-group" id="same_as_above">
                         <label class="checkbox-custom  m-l-small pull-right" style="font-size:14px;">
                             		<input type="checkbox" name="same_as_above" id="same-above" value="1" >
                                 <i class="fa fa-square-o"></i> Same as Above? </label> 
                  </div>
                  <div id="billing-details">
                   <div class="form-group">
                    <label class="col-lg-3 control-label">Client Delivery Address</label>
                        <div class="col-lg-8">
                      	  <textarea class="form-control" id="input-del-address" name="client_del_address"><?php if(isset($proforma_inv['del_address_info'])) 
                       		 { echo $proforma_inv['del_address_info']; } ?></textarea>
                             <input type="hidden" id="del_info" value=""> 
                        </div>
                  </div>
                  </div>
              
              
              <input type="hidden" name="con_id" id="con_id" value="<?php echo $addedByInfo['country_id'] ;?>" class="form-control validate">
              <div class="form-group">
                  
                <?php  // printr($addedByInfo);?>
                    <label class="col-lg-3 control-label"><span class="required">*</span>
                    <?php if(isset($addedByInfo['country_id']) && $addedByInfo['country_id']=='155')  {?> RFC No. <?php }
                    elseif((isset($addedByInfo['country_id']) && $addedByInfo['country_id']=='214') || (isset($addedByInfo['country_id']) && $addedByInfo['country_id']=='42')) { echo 'GST No.';} 
                    elseif((isset($addedByInfo['country_id']) && $addedByInfo['country_id']=='251') || (isset($addedByInfo['country_id']) && $addedByInfo['country_id']=='209')|| (isset($addedByInfo['country_id']) && $addedByInfo['country_id']=='235')) { echo 'VAT No.';} 
                    else { ?> GST No. (Tin No.)<?php } ?></label>
                    <div class="col-lg-3">
                      <input type="text" name="vat_no" id="vat_no" value="<?php if(isset($proforma_inv['vat_no'])) { echo $proforma_inv['vat_no']; }else { echo 'NA';} ?>" class="form-control validate[required]">
                      <b style="color:#ff0000"> <?php echo "if you haven't GST No. then Write Down - Not Available ";?> </b>
                    </div>
               <?php 
				   if($addedByInfo['country_id'] != '14')
					{
					    $class ='validate[required]';
					    if($addedByInfo['country_id'] == '111')
					        $class =''; ?>
                    <label class="col-lg-3 control-label"><span class="required">*</span>Discount (%)</label>
                        <div class="col-lg-3">
                          <input type="text" name="discount" id="discount" value="<?php if(isset($proforma_inv['discount'])){ echo $proforma_inv['discount']; }  ?>" class="form-control <?php echo $class; ?>">
                        </div>
                  <?php }else if($addedByInfo['country_id'] == '14')  { ?>
						   <label class="col-lg-3 control-label"><span class="required">*</span>Discount (%)</label>
								<div class="col-lg-3">
								  <select name="discount" id="discount" class="form-control validate[required]" >
								  <option value="0" <?php if(isset($proforma_id) &&  $proforma_inv['discount']=='0') { ?> selected="selected" <?php } ?>>0</option>
								  <option value="5" <?php if(isset($proforma_id) &&  $proforma_inv['discount']=='5') { ?> selected="selected" <?php } ?>>5</option>
								  <option value="10" <?php if(isset($proforma_id) &&  $proforma_inv['discount']=='10') { ?> selected="selected" <?php } ?>>10</option>
								  <option value="12" <?php if(isset($proforma_id) &&  $proforma_inv['discount']=='12') { ?> selected="selected" <?php } ?>>12</option>
								  <option value="15" <?php if(isset($proforma_id) &&  $proforma_inv['discount']=='15') { ?> selected="selected" <?php } ?>>15</option>
								  <option value="20" <?php if(isset($proforma_id) &&  $proforma_inv['discount']=='20') { ?> selected="selected" <?php } ?>>20</option>
								  <option value="25" <?php if(isset($proforma_id) &&  $proforma_inv['discount']=='25') { ?> selected="selected" <?php } ?>>25</option>
								  </select>
								</div>
				  <?php }?>
              </div>
               <?php 
			 //	if((isset($addedByInfo['country_id']) && $addedByInfo['country_id']=='42')){
			  ?>
			  <!--<div class="form-group">
              		<label class="col-lg-3 control-label"><span class="required">*</span>QST No.</label>
                     <div class="col-lg-3">
                      <input type="text" name="qst_no" id="qst_no" value="<?php //if(isset($proforma_inv['qst_no'])) { echo $proforma_inv['qst_no']; } ?>" class="form-control ">
                     </div>
              </div>-->
              <?php //} ?>
              
           <?php  if(isset($addedByInfo['country_id']) && $addedByInfo['country_id']!='111') { ?>
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
                        if(isset($proforma_inv['customer_dispatch']) && $proforma_inv['customer_dispatch'] == 1 ){
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
              <?php     
                    }
                    else
                        echo '<input type="hidden" name="customer_dispatch" value="0">';
                        
               if($addedByInfo['country_id'] == '251' || $addedByInfo['country_id'] == '11'  || $addedByInfo['country_id'] == '196' )
					{ ?>
					
					<div class="form-group">
                    	 <label class="col-lg-3 control-label"><span class="required">*</span>Do You Want To Print HSN Code in Proforma ?</label>
                          <div class="btn-group" data-toggle="buttons">
    						<?php 
    							$classy2=$checky2=$classn2=$checkn2='';
                                if(isset($proforma_inv['hsn_code']) && $proforma_inv['hsn_code'] == 1 ){
                                    $classy2='active'; 
                                    $checky2='checked="checked" ';
                                }
                                else
                                {
                                    $classn2='active';
                                    $checkn2='checked="checked" ';
                                }?>
                              
                                <label class="btn btn-sm btn-white btn-off <?php echo $classy2;?>"><input type="radio" name="hsn_code" id="hsn" value="1" <?php echo $checky2;?>>Yes </label>
                                <label class="btn btn-sm btn-white btn-on <?php echo $classn2;?>"><input type="radio" name="hsn_code" id="hsn" value="0" <?php echo $checkn2;?>>No </label>
                          
                          </div>
                    </div>
				    	
					<div class="form-group">
                    	 <label class="col-lg-3 control-label"><span class="required">*</span>Do You Want To Print Bank Details in Proforma ?</label>
                          <div class="btn-group" data-toggle="buttons">
						<?php 
							$classy1='';
                            $checky1=''; 
                            $classn1='';
                            $checkn1='';
                        if(isset($proforma_inv['customer_dispatch']) && $proforma_inv['customer_dispatch'] == 1 ){
                            $classy1='active'; 
                            $checky1='checked="checked" ';
                        }
                        else
                        {
                            $classn1='active';
                            $checkn1='checked="checked" ';
                        }?>
                      
                        <label class="btn btn-sm btn-white btn-off <?php echo $classn1;?>">
                          <input type="radio" name="customer_bank_detail" id="dis" value="0" <?php echo $checkn1;?>>
                         Yes </label>
                        <label class="btn btn-sm btn-white btn-on <?php echo $classy1;?>">
                          <input type="radio" name="customer_bank_detail" id="dis" value="1" <?php echo $checky1;?>>
                          No </label>
                      
                      </div>
                    </div>
                    
					
					<?php 
					    
					}
					else
                        echo '<input type="hidden" name="customer_bank_detail" value="0">';
					?>
			    <?php 
			     if($addedByInfo['country_id'] == '251'){
			    
			     ?>
			    
			    
			    	  <div class="form-group">
             
                            <label class="col-lg-3 control-label">Delivery Charges</label>
                                <div class="col-lg-3">
                                  <input type="text" name="delivery_charges" id="delivery_charges" value="<?php if(isset($proforma_inv['delivery_charges'])) { 
                                  echo $proforma_inv['delivery_charges']; } ?>" class="form-control" >
                                 </div>
                                
                           </div> 
                           <div class="form-group">
             
                            <label class="col-lg-3 control-label">Other Charges</label>
                                <div class="col-lg-3">
                                  <input type="text" name="other_charges_comments" id="other_charges_comments" value="<?php if(isset($proforma_inv['other_charges_comments'])) { 
                                  echo $proforma_inv['other_charges_comments']; } ?>" placeholder=" Comments " class="form-control" >
                                 </div> 
                                 <div class="col-lg-3">
                                  <input type="text" name="other_charges" id="other_charges" value="<?php if(isset($proforma_inv['other_charges'])) { 
                                  echo $proforma_inv['other_charges']; } ?>" class="form-control" >
                                 </div>
                                
                           </div>
               <?php }?>
              
              
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Delivery</label>
                 <?php 
				   if($addedByInfo['country_id']!= '14')
					{ ?>
					 <div class="col-lg-8">
					  <input type="text" name="delivery" id="input-delivery" value="<?php if(isset($proforma_inv['delivery_info'])) { echo $proforma_inv['delivery_info']; } ?>" class="form-control validate[required]">
					</div>
					
					<?php }else{?>
						<div class="col-lg-6">
									  <select name="delivery" id="input-delivery" class="form-control validate[required]" >
									  <option value="TNT" <?php if(isset($proforma_id) &&  $proforma_inv['delivery_info']=='TNT') { ?> selected="selected" <?php } ?>>TNT</option>
									  <option value="AUSTRALIA POST" <?php if(isset($proforma_id) &&  $proforma_inv['delivery_info']=='AUSTRALIA POST') { ?> selected="selected" <?php } ?>>AUSTRALIA  POST</option>
									  <option value="COURIER PLEASE" <?php if(isset($proforma_id) &&  $proforma_inv['delivery_info']=='COURIER PLEASE') { ?> selected="selected" <?php } ?>>COURIER  PLEASE</option>
									  <option value="Warehouse Pick up" <?php if(isset($proforma_id) &&  $proforma_inv['delivery_info']=='Warehouse Pick up') { ?> selected="selected" <?php } ?>>Warehouse Pick up</option>
									  </select>
									</div>
					<?php }?>
              </div>
              
               <div class="form-group">
                    <label class="col-lg-3 control-label"><span class="required">*</span> Currency</label>
                        <div class="col-lg-3">
                        	
                             
                            <select name="currency" id="currency" class="form-control validate[required]" >
                               
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
									if(  $curr['currency_id'] == '15')
									{ ?>
                                    	<option value="<?php echo $curr['currency_id']; ?>" <?php if(isset($proforma_id) && $curr['currency_id'] == $proforma_inv['currency_id']) { ?> selected="selected" <?php } ?>><?php echo $curr['currency_code']; ?></option>
                                     <?php }
								}
									 
								else if($addedByInfo['country_id'] == '14')
									{
										if( $curr['currency_id'] == '6')
									{ ?>
                                    	<option value="<?php echo $curr['currency_id']; ?>" <?php if(isset($proforma_id) && $curr['currency_id'] == $proforma_inv['currency_id']) { ?> selected="selected" <?php } ?>><?php echo $curr['currency_code']; ?></option>
									 <?php } 
									    
								}
								else if($addedByInfo['country_id'] == '11' )
									{
									if($curr['currency_id'] == '2' ||  $curr['currency_id'] == '17' ||  $curr['currency_id'] == '3' )
									{ ?>
                                    	<option value="<?php echo $curr['currency_id']; ?>" <?php if(isset($proforma_id) && $curr['currency_id'] == $proforma_inv['currency_id']) { ?> selected="selected" <?php } ?>><?php echo $curr['currency_code']; ?></option>
									 <?php
									} 
									    
								}else if($addedByInfo['country_id'] == '196' )
									{
									if($curr['currency_id'] == '3' ||   $curr['currency_id'] == '17')
									{ ?>
                                    	<option value="<?php echo $curr['currency_id']; ?>" <?php if(isset($proforma_id) && $curr['currency_id'] == $proforma_inv['currency_id']) { ?> selected="selected" <?php } ?>><?php echo $curr['currency_code']; ?></option>
									 <?php
									} 
									    
								}
								else if($addedByInfo['country_id'] == '214')
								{
										if( $curr['currency_id'] == '8')
									{ ?>
                                    	<option value="<?php echo $curr['currency_id']; ?>" <?php if(isset($proforma_id) && $curr['currency_id'] == $proforma_inv['currency_id']) { ?> selected="selected" <?php } ?>><?php echo $curr['currency_code']; ?></option>
									 	
									 <?php } 
								    
								}
								else if($addedByInfo['country_id'] == '209' || $addedByInfo['country_id'] == '251'|| $addedByInfo['country_id'] == '235')
								{
										if( $curr['currency_id'] == '17' || $curr['currency_id'] == '2')
									{ ?>
                                    	<option value="<?php echo $curr['currency_id']; ?>" <?php if(isset($proforma_id) && $curr['currency_id'] == $proforma_inv['currency_id']) { ?> selected="selected" <?php } ?>><?php echo $curr['currency_code']; ?></option>
									 	
									 <?php } 
								    
								}
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
                  		<?php /*?>	<input type="text" name="payment_terms" id="input-payment" value="<?php if(isset($proforma_inv['payment_terms'])) 
						{ echo $proforma_inv['payment_terms']; } ?>" class="form-control validate[required]">
					<?php */?>
					
						<select name="payment_terms" id="payment_terms" class="form-control validate[required]"> 
                        <option value="Credit"<?php if(isset($proforma_id) && $proforma_inv['payment_terms'] == 'full') { ?> selected="selected" <?php } ?>> Credit</option>
                        <option value="Advance" <?php if(isset($proforma_id) && $proforma_inv['payment_terms'] == 'Advance') { ?> selected="selected" <?php }else{ ?>selected="selected"<?php }?>>Advance</option>
                        <option value="Part"<?php if(isset($proforma_id) && $proforma_inv['payment_terms'] == 'Part') { ?> selected="selected" <?php } ?>> Part Payment</option>
                        
                  </select>
					
                		</div>
              	</div>
                
               <div class="form-group">
              		<label class="col-lg-3 control-label"><span class="required">*</span>Final Destination</label>
                	<div class="col-lg-3">
                  	<?php //echo $addedByInfo['country_id'];
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
					{ echo $proforma_inv['port_loading']; } ?>" class="form-control" >
                 	</div>
              </div>
              
                  <div class="form-group state_list" style='display:none;'>
                  		<label class="col-lg-3 control-label"><span class="required">*</span>State</label>
                    	<div class="col-lg-2">
                    	    <?php $state_india = $obj_pro_invoice->getIndiaState(); //chosen_data?>
                    	    <select name="slist" id="slist" required="required" class="form-control validate[required] chosen_data" >
                                    <option value="">Select State</option>
                                    <?php foreach($state_india as $state)
                                    { ?>
                                        <option value="<?php echo $state['state_id']; ?>" <?php if(isset($proforma_id) && $proforma_inv['state_india'] == $state['state_id'] ) { ?> selected="selected" <?php } ?> ><?php echo $state['state']; ?></option>
                                    <?php } ?>
                                </select>
                      	</div>
                  </div>
              
              <?php if($addedByInfo['country_id'] == '42')
				   { $tax_canada = $obj_pro_invoice->getTaxationCanada();
				   	//printr($proforma_inv);?>
                       <div class="form-group canada_state_list">
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
											echo '<option value="'.$tcanada['taxation_canada_id'],'" > <b>'.$tcanada['abbreviation'] .' => </b>'.$tcanada['state'].'</option>';
										}
							 		} ?>
                               
                              </select>
                            </div>
                       </div>
                       <?php $pst_check=$gst_check=$hst_check='';
                           if(isset($proforma_inv['can_gst'])){
                               $gst_checkbox=1;
                           }else{
                                $gst_checkbox=0;$gst_check='checked';
                           }
                           if(isset($proforma_inv['hst'])){
                               $hst_checkbox=3;
                           }else{
                                $hst_checkbox=0; $hst_check='checked';
                           }
                           if( isset($proforma_inv['pst'])){
                               $pst_checkbox=2;
                           }else{
                               $pst_checkbox=0;$pst_check='checked';
                           }?>
                       <div class="form-group" id="gst_div" style="display:none;">
                           
                        	<label class="col-lg-3 control-label"><span class="required">*</span>GST(%)</label>
                                <div class="col-lg-3">
                                 <input type="text" class="form-control" readonly="readonly" name="gst" id="gst" value="<?php echo isset($proforma_inv['gst']) ? $proforma_inv['gst'] :'' ;?>">
                                </div>
                              
								
                                 	<div class="col-lg-3">
                                 		<input type="checkbox" name="gst_checkbox" <?php echo $gst_check; ?> value="1" <?php if($gst_checkbox == '1') { ?> checked="checked" <?php  } ?> />&nbsp; <label> <b style="color:#ff0000">Please Select To Add Tax</b></label>
                               	   </div>
                               
                                 
									
                        </div>
                        <div class="form-group" id="pst_div" style="display:none;">
                        	<label class="col-lg-3 control-label"><span class="required">*</span>PST(%)</label>
                                <div class="col-lg-3">
                                     <input type="text" readonly="readonly" name="pst" id="pst" value="<?php echo isset($proforma_inv['pst']) ? $proforma_inv['pst']:'';?>" class="form-control"><br>
                                </div>
 									<div class="col-lg-3">
                                       <input type="checkbox" name="pst_checkbox" value="2" <?php echo $pst_check; ?> <?php if($pst_checkbox == '2') { ?> checked="checked" <?php  } ?> />&nbsp; <label> <b style="color:#ff0000">Please Select To Add Tax</b></label>
                                   </div>
                               
                        </div>
                        <div class="form-group" id="hst_div" style="display:none;">
                        	<label class="col-lg-3 control-label"><span class="required">*</span>HST(%)</label>
                                <div class="col-lg-3">
                                	<input type="text"  readonly="readonly" name="hst" id="hst" value="<?php echo isset($proforma_inv['hst']) ? $proforma_inv['hst']:'';?>" class="form-control">
                                </div>
 									<div class="col-lg-3">
                                       <input type="checkbox" name="hst_checkbox" value="3" <?php echo $hst_check; ?> <?php if($hst_checkbox == '3') { ?> checked="checked" <?php  } ?> /> &nbsp; <label> <b style="color:#ff0000">Please Select To Add Tax</b></label>
                                   </div>
                                
                        </div>
               <?php } ?>
               
              <div class="form-group" id="tax_div">
                    <?php ?>
					<label class="col-lg-3 control-label">Tax</label>
					 <div class="col-lg-8">
                           <div id="form_div" style="float:left;width: 200px;">
                                <label style="font-weight: normal;">
                                <input type="radio" name="taxation" id="taxation_frm" value="Out Of Gujarat"  checked="checked" <?php 
                                if(isset($proforma_inv['taxation']) && ($proforma_inv['taxation'] == 'Out Of Gujarat')) { ?> checked="checked" <?php } ?>>Out Of Gujarat </label>
                         </div>
                            <div id="normal_div" style="float:left;width: 200px;">
                                <label  style="font-weight: normal;">
                                <input type="radio" name="taxation" id="taxation_nrm" value="With in Gujarat"  <?php if(isset($proforma_inv['taxation']) && ($proforma_inv['taxation'] == 'With in Gujarat')) { ?> checked="checked" <?php } ?>> With In Gujarat</label>
                           </div>
                         
                          <div id="normal_div" style="float:left;width: 200px;">
                                <label  style="font-weight: normal;">
                        <input type="radio" name="taxation" id="taxation_frm" value="sez_no_tax" <?php if(isset($proforma_inv['taxation']) && 
									($proforma_inv['taxation']== 'sez_no_tax')) { ?> checked="checked" <?php } ?>> SEZ  Unit - No Tax </label> </div>
                       </div>
					
					
               
             </div>
                     <?php    if(($addedByInfo['user_id'] == '19')  )
                                	{
                                	    $label_trans="Pickup From Warehouse";
                                	}else{
                                	    $label_trans="By Pickup";
                                	}
                            $ch1=$ch2=$ch3=$ch4='display:block;';$ch3_check='';$ch1_check='checked';
                            /*if($addedByInfo['country_id'] == '111')
                            {   //$ch1=$ch2='display:none;';$ch3_check='checked';//echo 'we';
                                if((isset($proforma_inv['destination']) && $proforma_inv['destination'] != '169' && $proforma_inv['destination'] != '26' && $proforma_inv['destination'] != '27' ))
                                {
                                    $ch1=$ch2='display:none;';$ch3_check='checked';//echo 'hi';
                                }
                            }
                            elseif((isset($proforma_inv['destination']) && $proforma_inv['destination'] != '169' && $proforma_inv['destination'] != '26' && $proforma_inv['destination'] != '27' ) || $addedByInfo['country_id'] != '251')
                            {
                                $ch3=$ch4='display:none;';$ch1_check='checked';
                            }*/
                            if($addedByInfo['country_id'] == '111')
                            {
                                if((isset($proforma_inv['destination']) && $proforma_inv['destination']== '169' && $proforma_inv['destination'] == '26' && $proforma_inv['destination'] == '27' )){
                                    $ch1=$ch2=$ch3=$ch4='display:block;';$ch1_check='checked';
                                }else{
                                    $ch1=$ch2='display:none;';$ch3_check='checked';}
                            }
                            elseif($addedByInfo['country_id'] != '251')
                            {
                                $ch3=$ch4='display:none;';$ch1_check='checked';
                            }
                          //  printr($addedByInfo);printr($ch1_check);
                            ?>
                 <div class="form-group option">
                        <label class="col-lg-3 control-label">Mode of Shipment</label>
                        <div class="col-lg-8">                
                        	<div  class="checkbox ch1" style="float:left;width: 200px;<?php echo $ch1;?>" >
                                <label  style="font-weight: normal;">
                                  <input type="radio" name="transport" id="tran1" value="air" <?php if(isset($proforma_id) && (decode($proforma_inv['transportation']) == 'air')) { ?> checked="checked" <?php } ?><?php echo $ch1_check;?> />
                                <?php if($addedByInfo['country_id']=='42'){ echo 'Rush Order';} else { echo 'By Air';}?> 
                              </label>
                          </div>
                           <div class="checkbox ch2" style="float:left;width: 200px; <?php echo $ch2;?>">
                                <label  style="font-weight: normal;">
                                  	<input type="radio" name="transport" id="trans2" value="sea" <?php if(isset($proforma_id) && (decode($proforma_inv['transportation']) == 'sea')) { ?> checked="checked" <?php } ?> >
                              	 <?php if($addedByInfo['country_id']=='42'){ echo 'Normal Order';} else { echo 'By Sea';}?> 
                               </label>
                          </div>
                         <?php if($addedByInfo['country_id'] == '11') {  ?>
                          <div class="checkbox ch5" style="float:left;width: 200px; <?php echo $ch5;?>">
                                <label  style="font-weight: normal;">
                                  	<input type="radio" name="transport" id="trans5" value="By Air & Sea" <?php if(isset($proforma_id) && (decode($proforma_inv['transportation']) == 'By Air & Sea')) { ?> checked="checked" <?php } ?> >
                              	     By Air & Sea
                               </label>
                          </div>
                           <?php } //if($userCurrency['currency_code'] == 'INR'){ ?>
                             	 <div class="checkbox ch3" style="float: left;  width: 30%; <?php echo $ch3;?>">
                                    <label><input type="radio" name="transport" id="trans3" value="road" <?php if(isset($proforma_id) && (decode($proforma_inv['transportation']) == 'road')) { ?> checked="checked" <?php } ?> <?php echo $ch3_check;?>><?php echo $label_trans;?></label>
                                 </div>
                           <?php //} ?>
                           
                           <div class="checkbox ch4" style="float: left;  width: 20%; <?php echo $ch4;?>">
                                    <label><input type="radio" name="transport" id="trans4" value="by road" <?php if(isset($proforma_id) && (decode($proforma_inv['transportation']) == 'by road')) { ?> checked="checked" <?php } ?> >By Road</label>
                            </div>
                           
                        </div>
              </div>
                        <?php /*?>  <?php if($addedByInfo['country_id']=='111') { ?>
                          <div class="form-group option">
                        <label class="col-lg-3 control-label" id="lbl_pay">Select</label>
                        <div class="col-lg-9">                
                        	<div  class="checkbox chpay1" style="float:left;width: 200px;">
                                <label  style="font-weight: normal;">
                                  <input type="radio" name="tran_pay" id="tran_pay" value="To Pay" 
								  <?php if(isset($proforma_id) && (decode($proforma_inv['trans_pay']) == 'To Pay')) { ?> checked="checked" <?php } ?> checked="checked" />
                               To Pay
                              </label>
                           </div>
                          <!--  change by sejal-->
                           <div class="checkbox chpay2" style="float:left;width: 200px;">
                          
                                <label  style="font-weight: normal;">
                                
                                  	<input type="radio" name="tran_pay" id="tran_pay" value="Paid" 
									<?php if(isset($proforma_id) && (decode($proforma_inv['trans_pay']) == 'Paid')) { ?> checked="checked" <?php } ?> >
                              	Paid
                               </label>
                          </div>
                       </div>
                        
              </div>
                      <?php } ?><?php */?>               
          <div class="form-group">
                 <?php /*?>  <label class="col-lg-3 control-label"><span class="required">*</span>Signature Date</label> <?php */?>
                		<div class="col-lg-3">
                        <?php  /*?>  <input type="text" name="signature_date" readonly="read>only" data-date-format="yyyy-mm-dd" value="<?php if(isset($proforma_inv['sign_date'])) { echo $proforma_inv['sign_date']; } ?>" placeholder="Buyers Date" id="signature_date" class="input-sm form-control datepicker" /> <?php */?>
                          <input type="hidden" name="edit" id="edit" value="<?php echo $edit; ?>"  />
                      	</div>
                 <?php if($addedByInfo['country_id']!='111' && $addedByInfo['country_id']!='42'){ ?>
                	<div class="col-lg-9">
                	     <?php    if(($addedByInfo['country_id'] == '209' || $addedByInfo['country_id'] == '251'|| $addedByInfo['country_id'] == '235')  )
                                	{
                                	    $label="VAT(%)";$readonly="";
                                	}else{
                                	    $label="GST(%)";$readonly='readonly="readonly"';
                                	}?>
                 		<label class="col-lg-8 control-label"><?php echo $label;?></label>
                        <div class="col-lg-2">
                              <input type="text" name="gst_tax"  id="gst_tax" value="<?php echo isset($proforma_inv['gst_tax']) ? $proforma_inv['gst_tax'] :  round($addedByInfo['gst']) ; ?>" class="form-control" <?php echo $readonly;?>>
                           <input type="hidden" name="gst" id="gst" value="<?php echo isset($addedByInfo['gst']) ? round($addedByInfo['gst']) : '' ; ?>"  >
                         </div>
                	</div>
                  <?php } //printr($addedByInfo);?>
              </div>
                                          
              <div class="form-group">
             	<label class="col-lg-3 control-label"><span class="required">*</span> Select Bank</label>
                    <div class="col-lg-3" id="bank_details">
                        
                     <?php 
                    /*  if($addedByInfo['user_id']=='44'){
                         echo '<input type="hidden" name="paypal_link" id="paypal_link" value="paypal.me/PouchMakersCanadaINC" >';
                         echo '<input type="hidden" name="e-transfer_link" id="e-transfer_link" value="sales@pouchmakers.com" >';
                         
                      }*/
                   
                    if(isset($proforma_id)) {
						$bank = $obj_pro_invoice->getbankDetails($proforma_inv['currency_id'],$addedByInfo['user_id']);	
				
						if(isset($bank) && !empty($bank)) { ?>
						<select name="bank_id" id="bank_id" class="form-control validate[required]" style="width:70%" >
							<option value="">Select Bank</option>	<?php	foreach($bank as $details){ ?>
									<option value="<?php echo $details['bank_detail_id']; ?>"
                                    <?php if($details['bank_detail_id'] == $proforma_inv['bank_id']) { ?> selected="selected" <?php } ?>
                                     ><?php echo $details['benefry_bank_name']; ?> <?php if($addedByInfo['country_id']=='155'){ echo ' [<b>'.$addedByInfo['bank_accnt'].'</b>]';	 }?> </option>
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
               <?php //add by sonu packing charges 19-1-2017 
							   if($addedByInfo['country_id']=='111'){ ?>
              		  <div class="form-group">
             
                                    <label class="col-lg-3 control-label">Packing Charges</label>
                                        <div class="col-lg-3">
                                          <input type="text" name="packing" id="packing_charges" value="<?php if(isset($proforma_inv['packing_charges'])) { 
                                          echo $proforma_inv['packing_charges']; } ?>" class="form-control" >
                                         </div>
                      </div> 
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Remarks</label>
                            <div class="col-lg-8">
                              <textarea type="text" name="pro_remark" id="pro_remark" value="" class="form-control" ><?php if(isset($proforma_inv['pro_remark'])) { echo $proforma_inv['pro_remark']; } ?></textarea>
                             </div>
                      </div>
                      
                      <?php } 
                      else { 
                            $disable ='';
                           if(isset($proforma_id))
                            $disable='disabled';
                      ?> 
                        <div class="line line-dashed m-t-large"></div>
                             <div class="form-group">
                                 <label class="col-lg-3 control-label">You Want To make proforma only for Frieght Charge?</label>
                                    <div class="col-lg-3">
                                      <div class="checkbox" style="float:left;width: 200px;">
    		                                <label  style="font-weight: normal;">
    		                                  	<input type="radio" name="for_freight_charge" <?php echo $disable;?>  <?php if(isset($proforma_id) && ($proforma_inv['for_freight_charge'] == 'No')) { ?> checked="checked" <?php } ?> id="for_freight_charge" checked="checked" value="No" >No
    		                               </label>
		                                </div>
		                                <div class="checkbox" style="float:left;width: 200px;">
    		                                <label  style="font-weight: normal;">
    		                                  	<input type="radio" name="for_freight_charge" <?php echo $disable;?> <?php if(isset($proforma_id) && ($proforma_inv['for_freight_charge'] == 'Yes')) { ?> checked="checked" <?php } ?> id="for_freight_charge_yes"  value="Yes" >Yes
    		                               </label>
		                                </div>
                                     </div>
                             </div>
                      
                      <?php }?>
                      
                      <?php //adding for dubai(fagunbhai) on 21-11-2018
							   if($addedByInfo['country_id']=='251'){ ?>
                          		  <div class="form-group">
                                        <label class="col-lg-3 control-label">Terms and conditions (Only For Stock)</label>
                                        <div class="col-lg-7">
                                          <input type="text" name="terms_and_cond" id="terms_and_cond" value="<?php echo !empty($proforma_inv['terms_and_cond']) ? $proforma_inv['terms_and_cond'] : 'Note: Any local duty or charges applicable in UAE will be borne by your company'; ?>" class="form-control" >
                                         </div>
                                  </div>
                      
                      <?php } ?>
                      
                      <div class="line line-dashed m-t-large"></div>
                  
                   <!--<div class="form-group">
                        	<label class="col-lg-3 control-label">Product Name</label>
                        	<div class="col-lg-3">
								<?php
                                //$products = $obj_pro_invoice->getActiveProduct();
                                ?>
                                <select name="product" id="product" class="form-control validate" onchange="color_chng()">
                                <option value="">Select Product</option>
                                    <?php
                                    
                                    /*foreach($products as $product){
                                        if(isset($post['product']) && $post['product'] == $product['product_id']){
                                            echo '<option value="'.$product['product_id'].'" selected="selected" >'.$product['product_name'].'</option>';
                                        }else{
                                            echo '<option value="'.$product['product_id'].'">'.$product['product_name'].'</option>';
                                        }
                                    } */ ?>
                                </select>
                        	</div>
                           
                            
                       </div>
                  <div class="form-group">
                         <label class="col-lg-3 control-label">Volume <br />  <b style="color:#ff0000"> <?php //echo "Size Like : 200 , 70 only in numbers";?> </b></label>
                         
                        <div class="col-lg-3">
                            <input type="text" name="volume" value="" id="volume" class="form-control" onchange="color_chng()"/>
                        </div>
                  </div>
                
              	<div class="form-group">
              	    <label class="col-lg-3 control-label">Colour</label>
                        	<div class="col-lg-3">
                             <?php //$colors = $obj_pro_invoice->getActiveColor();?>
                            <select name="color" id="color" class="form-control validate" onchange="color_chng()">
                                    <option value="">Select Color</option>
                                     <?php /*foreach($colors as $colors2){ ?>
										<option value="<?php echo $colors2['pouch_color_id']; ?>" id="option"
                                        <?php //if($clr['color'] == $colors2['pouch_color_id']) { echo 'selected="selected"'; } ?>> 
										<?php echo $colors2['color']; ?></option>
                                        <?php }*/  ?>  
                                                                           
                                  </select>
                            </div>
                </div>-->
                      <div id="for_frieght" style="display:block;">
                         <div class="form-group">
                                    <label class="col-lg-3 control-label">Pouch Type</label>
                                        <div class="col-lg-9">
                                            <?php if($addedByInfo['country_id']!='111')
                                            { ?>
                                                <div class="checkbox" style="float:left;width: 200px;">
            		                                <label  style="font-weight: normal;">
            		                                  	<input type="radio" name="stock_print" id="None_print" <?php if(isset($invoice_detail['stock_print']) && ($invoice_detail['stock_print'] == 'None')) { echo 'checked="checked"'; } ?> checked="checked" value="None" >
            		                              	None
            		                               </label>
        		                                </div>
                                        <?php } ?>
                                            
                                            <?php if($addedByInfo['country_id']=='111'){ ?>    
                		                                        
                		                        	<div  class="checkbox" style="float:left;width: 200px;">
                		                                <label  style="font-weight: normal;">
                		                                  <input type="radio" name="stock_print" checked="checked" id="stock_print"  <?php if(isset($invoice_detail['stock_print']) && ($invoice_detail['stock_print'] == 'stock')) { echo 'checked="checked"'; } ?> value="stock"  />
                		                            	Stock
                		                              </label>
                		                          </div>
                		                           
    		                                <?php } ?>
    		                                
    		                                <div class="checkbox" style="float:left;width: 200px;">
        		                                <label  style="font-weight: normal;">
        		                                  	<input type="radio" name="stock_print" id="container_print"  <?php if(isset($invoice_detail['stock_print']) && ($invoice_detail['stock_print'] == 'Containers')) { echo 'checked="checked"'; } ?> value="Containers" >
        		                              	Cups , Glasses & Containers (FOR Stock & Custom)
        		                               </label>
    		                                </div>
    		                                <?php if($addedByInfo['country_id']!='42' && $addedByInfo['country_id']!='14'){ ?>
    		                                <div class="checkbox" style="float:left;width: 200px;">
            		                                <label  style="font-weight: normal;">
            		                                  	<input type="radio" name="stock_print" id="digi_print"  value="Digital Print"  <?php if(isset($invoice_detail['stock_print']) && ($invoice_detail['stock_print'] == 'Digital Print')) { echo 'checked="checked"'; } ?> >
            		                              	Digital Print
            		                               </label>
            		                          </div>
            		                      
            		                      <div class="checkbox" style="float:left;width: 200px;">
            		                                <label  style="font-weight: normal;">
            		                                  	<input type="radio" name="stock_print" id="foil_print"  value="Foil Stamping"  <?php if(isset($invoice_detail['stock_print']) && ($invoice_detail['stock_print'] == 'Foil Stamping')) { echo 'checked="checked"'; } ?> >
            		                              	Foil Stamping
            		                               </label>
            		                          </div>
            		                      <?php } ?>
                                    </div>  
                                </div>
                            <?php //if($addedByInfo['country_id']=='111'){ ?> 
        						<div class="form-group" id="digital_print_div" style="<?php if(isset($invoice_detail['stock_print']) && ($invoice_detail['stock_print'] == 'Digital Print' || $invoice_detail['stock_print'] == 'Foil Stamping')) { echo "display: block;"; } else { echo "display: none;"; } ?>">
        							<label class="col-lg-3 control-label">Please Select How many colors(plates) are used in this order?</label>
        							  <div class="col-lg-1">
        								   <div class="input-group">
        									  <span class="input-group-btn">
        										  <button type="button" class="btn btn-default btn-number" disabled="disabled" data-type="minus" data-field="plate">
        											  <span class="glyphicon glyphicon-minus"></span>
        										  </button>
        									  </span>
        									  <input type="text" name="plate" id="plate" class="form-control input-number" value="<?php echo isset($invoice_detail) ? $invoice_detail['plate'] : '1'?>" min="1" max="6">
        									  <span class="input-group-btn">
        										  <button type="button" class="btn btn-default btn-number" data-type="plus" data-field="plate">
        											  <span class="glyphicon glyphicon-plus"></span>
        										  </button>
        									  </span>
        								  </div><!-- /input-group -->
        							 </div>
                              </div>
                        <?php //} ?>
                        
                            <div class="form-group" id="con_print_div" style="<?php if(isset($invoice_detail['stock_print']) && ($invoice_detail['stock_print'] == 'Containers')) { echo "display: block;"; } else { echo "display: none;"; } ?>">
        							<label class="col-lg-3 control-label">Please Select Which type of product you want to order ?</label>
        							  <div class="col-lg-9">
        								   <div  class="checkbox" style="float:left;width: 200px;">
        		                                <label  style="font-weight: normal;">
        		                                  <input type="radio" name="stock_con" checked="checked" id="stock_con" <?php if(isset($invoice_detail['stock_con']) && ($invoice_detail['stock_con'] == 'stock')) { echo 'checked="checked"'; } ?> value="stock"  />
        		                            	Stock
        		                              </label>
        		                          </div>
        		                           <div class="checkbox" style="float:left;width: 200px;">
        		                                <label  style="font-weight: normal;">
        		                                  	<input type="radio" name="stock_con" id="cust_con"  value="cust"  <?php if(isset($invoice_detail['stock_con']) && ($invoice_detail['stock_con'] == 'cust')) { echo 'checked="checked"'; } ?>>
        		                              	Custom
        		                               </label>
        		                          </div>
        							 </div>
                              </div>
                             <div class="form-group">
            					<label class="col-lg-3 control-label">Tool Price</label>
                            	<div class="col-lg-2">
            					    <input type="text" name="tool_price" value="<?php if(isset($_GET['proforma_in_id'])){ echo $invoice_detail['tool_price'];}  ?>" id="tool_price" class="form-control"/>
                                </div>
                                <label class="col-lg-3 control-label">Stock Available Qty</label>
                                <div class="col-lg-2" id="stock_id"> 
                     	 	        <input type="text" name="rem_qty" readonly value="<?php if(isset($invoice_detail) && ($invoice_detail['product_code_id'] == '0')) { echo '1' ;} elseif(isset($invoice_detail) && ($invoice_detail['product_code_id'] != '0') && ($invoice_detail['product_code_id'] != '-1') && ($invoice_detail['product_code_id'] != '-2')) { echo $remaining_qty['remaining_qty'].''; }else {echo '';}?>" id="rem_qty" class="form-control remaining_qty" placeholder="Qty">
                                </div>
                             </div>
                             <?php 
                             if ($addedByInfo['user_id']=='19' )
							   { ?>
                                    <div class="form-group">
                                        <label class="col-lg-3 control-label">Plus / Minus Quantity</label>
                                        <div class="col-lg-3">
                                            <div  style="float:left;">
                                                				
                    						<input type="text" name="plus_minus_quantity" id="plus_minus_quantity" class="form-control" placeholder="Plus / Minus Quantity" value="<?php echo isset($invoice_detail) ? $invoice_detail['plus_minus_quantity'] :''; ?>">
                    
                    
                                            </div> 
                                        </div>
                                    </div> 
                                <?php } if(isset($addedByInfo['country_id']) && $addedByInfo['country_id']!='111') { ?>
                                   <div class="form-group">
                                	  <?php if(isset($addedByInfo['country_id']) && $addedByInfo['country_id']=='14')
                            	           echo '<label class="col-lg-3 control-label"><span class="required">*</span>Do you want to dispatch This Product from different Warehouse ?</label>';
                            	       else
                            	          echo '<label class="col-lg-3 control-label"><span class="required">*</span>Do you want to dispatch This Product directly to customer?</label>';  ?>
                                	 
                                	 
                                      <div class="btn-group" data-toggle="buttons">
                    						<?php 
                    							$classyp='';
                                                $checkyp=''; 
                                                $classnp='';
                                                $checknp='';
                                            if(isset($invoice_detail['customer_dispatch_p']) && $invoice_detail['customer_dispatch_p'] == 1 ){
                                                $classyp='active'; 
                                                $checkyp='checked="checked" ';
                                            }
                                            else
                                            {
                                                $classnp='active';
                                                $checknp='checked="checked" ';
                                            }?>
                                            <label class="btn btn-sm btn-white btn-on <?php echo $classyp;?>">
                                              <input type="radio" name="customer_dispatch_p" id="dis" value="1" <?php echo $checkyp;?>>
                                              Yes </label>
                                            <label class="btn btn-sm btn-white btn-off <?php echo $classnp;?>">
                                              <input type="radio" name="customer_dispatch_p" id="dis" value="0" <?php echo $checknp;?>>
                                              No </label>
                                        </div>
                                     </div> 
						<?php  } ?>
              		<div class="form-group option">
                        <label class="col-lg-3 control-label"><span class="required">*</span>Product Code</label>
                        <div class="col-lg-4" id="holder">
                        	<?php $product_codes=$obj_pro_invoice->getActiveProductCode(); 
									//printr($product_codes);
									
									if(isset($proforma_in_id)) { 
										
										$user_type_id=$_SESSION['LOGIN_USER_TYPE'];
										$user_id=$_SESSION['ADMIN_LOGIN_SWISS'];
										if($user_id=='1' && $user_type_id=='1')
										{
											$remaining_qty['remaining_qty']='5000';
											$remaining_qty['rem_qty_display']='1000';
										}
										else
										{//echo'kkkk';
											if($invoice_detail['product_code_id'] != '0' && $invoice_detail['product_code_id'] != '-2' && $invoice_detail['product_code_id'] != '-1')
												$remaining_qty= $obj_pro_invoice->getStockQty($invoice_detail['product_code_id'],$user_type_id,$user_id,'');
										}
										$product_code= $obj_pro_invoice->getProductCode($invoice_detail['product_code_id']);
									//	printr($product_code);
									}?>
                                     <input type="hidden" id="product_code_id" name="product_code_id" value="<?php if(isset($_GET['proforma_in_id']) && ($invoice_detail['product_code_id'] != '-1' && $invoice_detail['product_code_id'] != '0')){ echo $product_code['product_code_id'];} else if(isset($invoice_detail) && $invoice_detail['product_code_id'] == '-1') { echo '-1'; } else { echo '0';} ?>">
                               <input type="text" id="keyword" class="form-control validate[required]"  autocomplete="off" value="<?php  if(isset($_GET['proforma_in_id']) && ($invoice_detail['product_code_id'] == '0')){ echo 'Cylinder';} else if( isset($invoice_detail) && $invoice_detail['product_code_id'] == '-1') { echo 'Custom'; } else {  echo isset($product_code) ? $product_code['product_code'] : ''; } ?>">
                               <input type="hidden" name="real_product_name" id="real_product_name" value="<?php echo isset($product_code['product_name'])?$product_code['product_name']:''?>" />
                                <input type="hidden" name="product_id" id="product_id" value="<?php echo isset($product_code['product'])?$product_code['product']:''?>" />
                                <input type="hidden" name="zipper_id" id="zipper_id" value="" />
                               <div id="ajax_response"></div>
                         
                        </div>
                        <div class="col-lg-4" id="product_div"> 
                               <input type="text" name="product_name" id="product_name"  value="<?php echo isset($_GET['proforma_in_id'])?$product_code['description']:'';?>" disabled="disabled" class="form-control validate" style="width:500px"/>
             	 	 	</div>
             	 	 	<label class="col-lg-1 control-label">Total Invoice Amount</label>
                        <div class="col-lg-1" id="amount"> 
                        <?php $amount = (($proforma_inv['invoice_total']*100)/(100+$proforma_inv['gst_tax'])) ; ?>
                            <!--<div id="total_amount" class="bold" style="border:1px solid black;text-align: center;"><?php //echo isset($_GET['proforma_id'])?number_format($amount,2):'0';?></div>-->
             	 	        <input type="text" name="total_amount" style="text-align: center;font-weight: bold;" readonly value="<?php echo isset($_GET['proforma_id'])?number_format($amount,2):'0';?>" id="total_amount" class="form-control" placeholder="Invoice Amount">
                        </div>
                </div>
                    
                    
                    <div  class="form-group" id="filling_div" <?php if(isset($product_code['product']) && ($product_code['product']==31 || $product_code['product']=='16' || $product_code['product']=='50')){ ?> style="display:block" <?php }else{ echo 'style="display:none"';} ?>>
                         <label class="col-lg-3 control-label">Filling Selection</label>
                            <div class="col-lg-9">
                                <div  style="float:left;width: 200px;">
                                    <label  style="font-weight: normal;">
                                      <input type="radio" name="filling" id="from_top" value="Filling from Top"class="valve"
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
						
						<div  class="form-group" id="seal_div" <?php if(isset($product_code['product']) && ($product_code['product']==1 )){ ?> style="display:block" <?php }else{ echo 'style="display:none"';} ?>>
                         <label class="col-lg-3 control-label">Sealing Selection</label>
                            <div class="col-lg-9">
                                <div  style="float:left;width: 200px;">
                                    <label  style="font-weight: normal;">
                                      <input type="radio" name="filling" id="from_top" value="Seal in center"
                                      <?php if(isset($invoice_detail['filling']) && ($invoice_detail['filling'] == 'Seal in center')) {
                                          echo 'checked="checked"'; } ?>/>
                                        Seal in center
                                  </label>
                                
                                    <label  style="font-weight: normal;">
                                        <input type="radio" name="filling" id="from_spout" value="Seal in side" class="valve"
                                        <?php if(isset($invoice_detail['filling']) && ($invoice_detail['filling'] == 'Seal in side')) {
                                          echo 'checked="checked"'; }?> />
                                    Seal in side
                                  </label>
                              </div> 
                            </div>
						</div>
						
						
                    <?php //add by sonu 28-8-2017
    				$edit_num='0';$gusset_printing_option = $printing_option='na';
    					if($addedByInfo['country_id']=='155' || $addedByInfo['country_id']=='209' || $addedByInfo['country_id']=='251' || $addedByInfo['country_id']=='235'){ 
    					
    					if(isset($proforma_in_id))
    					{ //printr($invoice_detail);
    						$code_pro = $obj_pro_invoice->getProductCode($invoice_detail['product_code_id']);
    						if($code_pro['color']=='Custom')
    						{
    							$edit_num='1';
    							$gusset_printing_option = $invoice_detail['gusset_printing_option'];
    							$printing_option = $invoice_detail['printing_option'];
    							echo '<input type="hidden" id="custom_pro" value="'.$code_pro['product'].'"/>';
    						}
    					}
    					echo'   <div id="gusset_printing"></div> ';
    					    
    					}
    						if($addedByInfo['country_id']=='155' ){ 
    					?> 
					  
    					<div class="form-group">
                            <label class="col-lg-3 control-label">Discount On Qty</label>
                            <div class="col-lg-2">
                                <div  style="float:left;width: 200px;">
                                    <label  style="font-weight: normal;">					
                                    
                                      
        									<input type="checkbox" name="dis_qty" onchange="qty_change()" value="1000"	<?php if(isset($proforma_inv['Mexico_dis_qty']) && ($proforma_inv['Mexico_dis_qty'] == '1000')) { echo 'checked="checked"'; }?>id="qty1" onclick="if(this.checked) {document.form.qty2.checked=false;document.form.qty3.checked=false;}" />1000
        								</label>
        								  <label  style="font-weight: normal;">
        										<input type="checkbox" name="dis_qty" onchange="qty_change()" value="2000" <?php if(isset($proforma_inv['Mexico_dis_qty']) && ($proforma_inv['Mexico_dis_qty'] == '2000')) { echo 'checked="checked"'; }?> id="qty2" onclick="if(this.checked) {document.form.qty1.checked=false;document.form.qty3.checked=false;}" />
        									2000
        									</label>
        								  <label  style="font-weight: normal;">
        								  <input type="checkbox" name="dis_qty" id="qty3"  onchange="qty_change()" value="5000"  <?php if(isset($proforma_inv['Mexico_dis_qty']) && ($proforma_inv['Mexico_dis_qty'] == '5000')) { echo 'checked="checked"'; }?>onclick="if(this.checked) {document.form.qty1.checked=false;document.form.qty2.checked=false;}" />5000
        								  </label>
        
                                </div> 
                            </div>
        					<label class="col-lg-1 control-label">Pedimento</label>
                            <div class="col-lg-3">
                                <div  style="float:left;width: 500px;">
                                    				
        						<input type="text" name="pedimento_mexico" id="pedimento_mexico" class="form-control" placeholder="Pedimento" value="<?php echo isset($invoice_detail) ? $invoice_detail['pedimento_mexico'] :''; ?>">
        
        
                                </div> 
                            </div>
                    </div>
                    <?php //sonu end  
							   } 
							   ?>
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
                                                   
    											    <th>
    												
    													<span class="required">*</span>Rate <?php if($addedByInfo['country_id']=='155'){ echo '[Normal Delivery]'; }?></th>
    													
    												<?php if($addedByInfo['country_id']=='155'){ ?>
    														<th id="exp_rate" <?php if(isset($invoice_detail) && $invoice_detail['express_rate'] != '0') {?>  style="display:block" <?php } else { ?> style="display:none" <?php } ?>>Rate [Express Delivery]</th>
    													<?php } ?>
    												<?php if($addedByInfo['country_id']=='111'){ ?>
    														<th id="net_weight">Net Weight</th>
    												<?php } ?>
                                                    <th>Size</th>
                                                    <th>Measurement</th>
                                                    <th>Description</th>
                                                  	<?php if(isset($invoice_detail['product_id']) && $invoice_detail['product_id']!=0){ ?>
                                                    	<th></th>
                                                    <?php } ?>
                                                  </tr>
    										  </thead>
                                              <tbody id="myTbody">
                                                     <?php $colors = $obj_pro_invoice->getActiveColor();?>
                                                       <input type="hidden" id="color_arr" value='<?php echo json_encode($colors);?>' />       
                                                    <?php if(isset($proforma_in_id)) {
                        								//$color = $obj_pro_invoice->getColorDetails($proforma_id,$proforma_in_id); 
                        								$color = $obj_pro_invoice->getProductCode($invoice_detail['product_code_id']);
                        								//printr($color);
                        							}else{
                        								$color=array('id' =>'',
                        												'proforma_id' => '',
                        												'proforma_invoice_id' =>'',
                        												'color' =>'',
                        												'color_text' =>'', 
                        												'rate' =>'',
                        												'quantity' =>'',
                        												'volume' => '',
                        												'description' =>'', 
                        												 'color_name' =>''
                        												);
                        							
                        							}
                        							?>
                                                    <input type="hidden" name="cyl" id="cyl" value="<?php echo isset($color['color'])?$color['color']:'';?>"/>
                                                    <tr id="tr_<?php //echo $i; ?>">  
                                                    
                        							<td> 
                                                    	<input type="text" id="color_product" name="color_product" value="<?php if(isset($invoice_detail) && $invoice_detail['product_code_id'] == '-1') { echo 'Custom'; }else if(isset($invoice_detail) && $invoice_detail['product_code_id'] == '0') { echo 'Cylinder'; } else { echo $color['color']; }?>" readonly="readonly" class="form-control" />
                                                     </td>
                                                    
                                                     <td>
                                                      		<input type="text" placeholder="Desc. / Job Name" name="color_text" value="<?php echo isset($invoice_detail['color_text'] ) ? $invoice_detail['color_text'] : ''; ?>" id="color_txt" class="form-control validate[required]" <?php if(isset($invoice_detail) && $invoice_detail['color_text'] != '') {?>  style="display:block" <?php } else { ?> style="display:none" <?php } ?> />
                                                 	  </td>
                                                        
                                                       <td><input type="text" name="qty" onchange="getProductPriceAllCountry()" value="<?php echo isset($invoice_detail) ? $invoice_detail['quantity'] : ''; ?>" id="qty" class="form-control validate[required,custom[number],min[1]]" placeholder="Qty"></td>
                                                        
                        
                        							   <td>
                        							       
                        							       <?php if($addedByInfo['country_id']=='155')
                        							                $vali = '';
                        							            else
                        							                $vali ='validate[required,custom[number],min[0.001]]';
                        							            ?>
                        							            <input type="hidden" name="original_rate" value="<?php echo isset($invoice_detail) ? $invoice_detail['rate'] : ''; ?>" id="original_rate" class="form-control" placeholder="Rate">
                        							       <input type="text" name="rate" value="<?php echo isset($invoice_detail) ? $invoice_detail['rate'] : ''; ?>" id="rate" class="form-control <?php echo $vali;?>" placeholder="Rate"></td>
                                                       <?php if($addedByInfo['country_id']=='155'){ ?>
                        									<td id="exp_rate1" <?php if(isset($invoice_detail) && $invoice_detail['express_rate'] != '0') {?>  style="display:block" <?php } else { ?> style="display:none" <?php } ?>><input <?php if(isset($invoice_detail) && $invoice_detail['express_rate'] != '0') {?>  style="display:block" <?php } else { ?> style="display:none" <?php } ?>type="text" name="express_rate" value="<?php echo isset($invoice_detail) ? $invoice_detail['express_rate'] : ''; ?>" id="express_rate"  class="form-control" placeholder="Rate"></td>
                        
                        								<?php } ?>
                        								<?php if($addedByInfo['country_id']=='111'){ ?>
                        										<td id="net_weight1" ><input type="text" name="netweight" value="<?php echo isset($invoice_detail) ? $invoice_detail['netweight'] : ''; ?>" id="netweight"  class="form-control" placeholder="Net Weight"></td>
                        								<?php } ?>
                                                       <td><input type="text" name="size" value="<?php if(isset($invoice_detail) && ($invoice_detail['product_code_id'] == '-1' || $invoice_detail['product_code_id'] == '0')) { echo $invoice_detail['size'];} else { echo $color['volume']; } ?>" class="form-control" placeholder="Size" id="size" <?php if(isset($invoice_detail) && $invoice_detail['product_code_id'] != '-1' && $invoice_detail['product_code_id'] != '0') { ?> readonly="readonly" <?php } ?> ></td>
                                                       
                                                       <td> <?php $measurement = $obj_pro_invoice->getMeasurement(); ?>
                                                            <select name="measurement" id="measurement" class="form-control" <?php if(isset($invoice_detail) && ($invoice_detail['product_code_id'] != '-1' && $invoice_detail['product_code_id'] != '0') ) { ?>readonly="readonly" <?php } ?>>
                                                               <option value="">Select Measurement</option>
                                                                <?php foreach($measurement as $meas){ ?>
                                                                        <option value="<?php echo $meas['product_id']; ?>"
                                                                        <?php if(isset($_GET['proforma_in_id'])) {
                                                                        
                                                                        if(($color['measurement'] == $meas['measurement']) || isset($invoice_detail) && ($invoice_detail['product_code_id'] == '-1' || $invoice_detail['product_code_id'] == '0'  )&& ($invoice_detail['measurement'] == $meas['product_id'])) { ?> selected="selected" <?php }} ?>
                                                                         ><?php echo $meas['measurement']; ?></option>
                                                              <?php   }  ?>
                                                       		 </select>
                                                        </td>
                                                        
                                                        
                                                        <td><input type="text" name="description" value="<?php echo isset($invoice_detail) ? $invoice_detail['description'] :''; ?>" id="description" class="form-control" placeholder="Description">
                                                     
                                                        </td>
                                                       
                                                      
                                                        </tr>
    							
                                    </tbody>
    								</table>
    								</div>
                                   </section> 
    								</div>
    			  </div>
			  </div>
               <div class="form-group">
                     <div class="col-lg-9 col-lg-offset-3">
                      <?php //if(isset($proforma_inv) && $proforma_inv['for_freight_charge']!='Yes'){ ?>   
                      <button type="button"  name="btn_save" id="btn_save" class="btn btn-primary" <?php if(isset($_GET['proforma_in_id']) || (isset($proforma_inv) && $proforma_inv['for_freight_charge']=='Yes')){ ?> 
                         style="display:none" <?php } ?> onclick="displaygenerate();">Add Product</button>
                      <?php //}?>   
                      <?php if(isset($proforma_id) && isset($proforma_in_id)) {?>
							<input type="hidden" name="pro_id" value="<?php echo $proforma_in_id;?>" id="pro_id" />
                  
                     <button type="button" name="proforma_update" id="proforma_update" class="btn btn-primary">Update Product</button> 
                     <?php } ?>						
           	 </div>
           	 </div>
			<div id="invoice_results">
			 <div class="table-responsive">
   		   <?php 
			if(isset($proforma_inv['proforma_id'])){
			?>
        	<table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Size</th>
                    <th>Color : Quantity</th>
                    <th>Rate<?php if($addedByInfo['country_id']=='155'){ echo '[Normal Delivery] <br> Rate [Express Delivery]'; }?></th>
                    <th>Option</th>
                    <th>Transport</th>	 
                    <th>Action</th>                
                </tr>
            </thead>
            <tbody>
        <?php
   		$pro = $obj_pro_invoice->getProforma($proforma_inv['proforma_id']);
	   	$getInvoices = $obj_pro_invoice->getInvoice($proforma_inv['proforma_id']);
	   	if(isset($getInvoices) && !empty($getInvoices)) {
            foreach($getInvoices as $invoice ) { 
				$product_code_data = $obj_pro_invoice->getProductCode($invoice['product_code_id']);?>
                <input type="hidden" name="proforma_id" id="proforma_id" value="<?php echo $proforma_id; ?>"  />
                <input type="hidden" name="proforma_no" id="proforma_no" value="<?php echo $pro['pro_in_no']; ?>"  />
                <input type="hidden" id="total_amt" name="total_amt" value="<?php echo $pro['invoice_total']; ?>"/>
                <tr id="proforma_invoice_id_<?php echo $invoice['proforma_invoice_id']; ?>">

				  <td><b><?php echo $product_code_data['product_code']; ?></b><br /><?php echo $invoice['product_name']; ?></td>
                 	
                     <td><?php $measure = $obj_pro_invoice->getMeasurementName($invoice['measurement']); 
								if($invoice['product_code_id']=='-1' || $invoice['product_code_id']=='0')
									 echo $invoice['size'].'&nbsp;'.$measure['measurement'].'<br>';
								else
									 echo $product_code_data['volume'].'&nbsp;'.$product_code_data['measurement'].'<br>';
									 ?>
					   </td>
                     
				
				  <td>
				  <?php 
				  	$clr_text='';
					if($invoice['product_code_id']=='-1')
					{
						$clr_nm = 'Custom';
						$clr_text = "(".$invoice['color_text'].")";
					}
					elseif($invoice['product_code_id']=='0')
					{
						$clr_nm = 'Cylinder';
					}
					else
					{
						$clr_nm = $product_code_data['color'];
					}
					  
					  
						echo $clr_nm.''.$clr_text.' : ';
						
						
						if($product_code_data['product']=='6')
    				        echo $invoice['quantity'];
    				    else
    				        echo number_format($invoice['quantity'],"0", '.', ''); 
    				    
    				    echo '<br>';
				  //} ?>
				  </td>
                  
				  <td> <?php echo $invoice['rate'].'<br>';
				        if($addedByInfo['country_id']=='155') { echo $invoice['express_rate'];}
				        if($addedByInfo['country_id']=='111') { echo 'NET W. : '.$invoice['netweight'].' KG';}?>
				  </td>
				  <td><?php echo ucwords($product_code_data['spout_name']).' '.$product_code_data['valve'].'<br>'.$product_code_data['zipper_name'].' '. ucwords($product_code_data['product_accessorie_name']); ?></td>
				 <td>
                 
				  	<?php if($addedByInfo['country_id']=='42')
					{
						if(ucwords(decode($proforma_inv['transportation']))=='Air')
						{							
								  echo 'Rush Order';
						}
						else
						{
						
								 echo 'Normal Order';
													
						}
					}
												
				  	else
					
					{							
							echo 	'By'.ucwords(decode($proforma_inv['transportation']));
													
					} 
					
					?>
                    </td>
				  <td class="del-product"><a class="btn btn-danger btn-sm" href="javascript:void(0);" onClick="removeInvoice(<?php echo $invoice['proforma_invoice_id'].','.$invoice['proforma_id'];; ?>)"><i class="fa fa-trash-o"></i></a>
                		 <a href="<?php echo $obj_general->link($rout, 'mod=add&proforma_id='.encode($proforma_id).'&proforma_in_id='.encode($invoice['proforma_invoice_id']).'&is_delete='.$_GET['is_delete'],'',1); ?>" id="btn_edit"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
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
	</div>
   
 <?php if($edit){?>
			<div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                    <button type="button" name="btn_update" id="btn_update" class="btn btn-primary">Update </button>
                    <a class="btn btn-default" href="<?php echo $obj_general->link($rout, 'mod=index&is_delete='.$_GET['is_delete'].$add_url, '',1);?>">Cancel</a>
                </div>
            </div>
<?php }  ?>
                
                <?php if($addedByInfo['country_id']=='14' || $_SESSION['LOGIN_USER_TYPE'] == 1) { ?>
                    <div class="form-group">
    					<!--<div class="col-lg-2 col-lg-offset-3">
    						<button type="button" id="generate_invoice"  class="btn btn-primary" name="generate_invoice" onClick="add_invoices()" style="display:none" >Generate Proforma Invoice</button>style="display:none"
    					</div>-->
    					<div class="col-lg-9 col-lg-offset-1" id="btn_combo" >
    						<button type="button" id="generate_inv" onclick="gen_invoice('close')" class="btn btn-primary" >Generate Proforma and Close</button>
    						<button type="button" id="generate_inv" onclick="gen_invoice('download')" class="btn btn-primary">Generate Proforma Invoice and Download</button> 
    						<button type="button" id="generate_inv" onclick="gen_invoice('payment')" class="btn btn-primary">Generate Proforma and Add Payment</button> 
    						<button type="button" id="generate_inv" onclick="gen_invoice('email')" class="btn btn-primary">Generate Proforma and Send Email</button> 
    						<button type="button" id="generate_inv" onclick="gen_invoice('transfer')" class="btn btn-primary">Generate Proforma and Transfer Invoice</button>
    					</div>	
			    	</div>
                <?php } else {  ?>
                    <div class="col-lg-9 col-lg-offset-3">
                 	        <button type="button" id="generate_invoice"  class="btn btn-primary" name="generate_invoice" onClick="add_invoices()" style="display:none" >Generate Proforma Invoice</button>
                    </div>
                <?php } ?>
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
<!-- kinjal made on 20-4-2019 -->	
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
                            <input type="toemail" name="ccemail" value="" id="ccemail" class="form-control"/><br><small style="color:red;">If you want to have multiple email CC, please add email ids with a comma (,) sign.</small>
                        </div>
						<label class="col-lg-1 control-label" id="email">BCC</label>
                        <div class="col-lg-3"> 
                            <input type="toemail" name="bccemail" value="" id="bccemail" class="form-control"/><small style="color:red;">If you want to have multiple email BCC, please add email ids with a comma (,) sign.</small>
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
                            <input type ="hidden" name="proforma_id_send" id="proforma_id_send" value="" />
                           </div>
                        </div>
                     </div>
                       </div>	
                </div>
              <div class="modal-footer">
				   <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" >Close</button>
				   <button type="submit" name ="btn_send_customer" id="btn_send_customer"  class="btn btn-primary">Send</button>
              </div>
   		</form>   
    </div>
  </div>
</div>
<!-- END [kinjal] -->
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
<script src="<?php echo HTTP_SERVER;?>ckeditor3/ckeditor.js"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/select2/select2.min.js"></script>

<script src="https://harvesthq.github.io/chosen/chosen.jquery.js" type="text/javascript"></script>
<link rel="stylesheet" href="https://harvesthq.github.io/chosen/chosen.css" type="text/css"/> 
<style>
    .chosen-container.chosen-container-single {
    width: 300px !important; /* or any value that fits your needs */
}

@media (max-width: 400px) {
  .chunk {
    width: 100% !important;
  }
}
</style>
<script>

   
    jQuery(document).ready(function(){
        jQuery("#form").validationEngine();
        $(".chosen_data").chosen();
        
        var char_frieght = '<?php echo isset($proforma_inv) ? $proforma_inv['for_freight_charge'] : '';?>';
       
        if(char_frieght=='Yes')
            $("#for_frieght").css('display','none');
    });
           
    <?php if($addedByInfo['country_id'] == '155'){ ?>  
    jQuery(function(){
       jQuery('#same-above').click();
       $('#same_as_above').css('display','none');
	   
	      
    });
    <?php } ?>
    jQuery(document).ready(function(){
        
        <?php if($addedByInfo['country_id'] == '111'){ ?>
            $(".state_list").show();
           
        <?php } ?>
        
         <?php if($addedByInfo['country_id'] == '42'){ ?>
               if($('#country_id').val()== 42){
                  $(".canada_state_list").show();
               }
             //console.log($('#country_id').val());
        <?php } ?>
		var custom = <?php echo $edit_num;?>;
	   var  gusset_printing_option= '<?php echo $gusset_printing_option;?>';
	   var printing_option ='<?php echo $printing_option;?>';
		//alert(custom+'+++++'+gusset_printing_option+'+++++'+printing_option);
		if(custom==1)
		{
			var product_id = $("#custom_pro").val();
			getGussetPrinting(product_id,'1',printing_option,gusset_printing_option);
		}
			
        
		
		jQuery("#form").validationEngine();
		$("#cy_option").css("display","none");
		var product_id=$("#product").val();
		if(product_id==0)
			$(".addmore").hide();
		
//[kinjal] : (8-6-2016) for get tax value edit time 
		var edit=$("#edit").val();	
		if(edit != '')
			$( "#state" ).change();
		else
		    $('select[name=currency]').change();
		
	
	//change customer  detail function 21-4-2017 address book 
			
//$("#customer-name").focus();
	var offset = $("#customer-name").offset();
	var width = $("#holder").width();

	$("#ajax_return").css("width",width);
	 var currentRequest = null;
	$("#customer-name").keyup(function(event){		
		 var keyword = encodeURIComponent($("#customer-name").val());
		 //console.log(keyword.length);
		 if(keyword.length>='3')
		 {
			 if(event.keyCode != 40 && event.keyCode != 38 )
			 {
				 var product_code_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=customer_detail', '',1);?>");
				 $("#loading").css("visibility","visible");
				 currentRequest = $.ajax({
				   type: "POST",
				   url: product_code_url,
				   data: "customer_name="+keyword,
				   beforeSend : function()    {           
                        if(currentRequest != null) {
                            currentRequest.abort();
                        }
                    },
				   success: function(msg){
					  	
				 var msg = $.parseJSON(msg);
				  //alert(msg);
				 	
				   var div='<ul class="list">';
				   
					if(msg.length>0)
					{
						for(var i=0;i<msg.length;i++) 
						{	     
						   // if()
						    var f_str = msg[i].f_address;
						    console.log(f_str);console.log('hhhh');
						    var c_str = msg[i].c_address;
						    var consi ='';
						    if(c_str!='' && c_str!=null)
						        consi = c_str.replace(/"|'/g,"'");
						    var fact ='';
						    if(f_str!=''  && f_str!=null)
						        fact = f_str.replace(/"|'/g,"'");
						    
                            div =div+'<li><a href=\'javascript:void(0);\' id="'+msg[i].address_book_id+'" c_id="'+msg[i].company_address_id+'"contact_id="'+msg[i].contact_id+'" f_id="'+msg[i].factory_address_id+'"consignee="'+consi+'" deladd="'+fact+'" vat_no="'+msg[i].vat_no+'" contact_no="'+msg[i].phone_no+'" contact_name="'+msg[i].contact_name+'" email="'+msg[i].email_1+'"><span class="bold" >'+msg[i].company_name+'</span></a></li>';
						}
					}
					
					div=div+'</ul>';
				//	console.log(div);
					if(msg != 0)
					  $("#ajax_return").fadeIn("slow").html(div);
					else
					{
					  $("#ajax_return").fadeIn("slow");	
					  $("#ajax_return").html('<div style="text-align:left;">No Matches Found</div>');
					   $("#email").val('');
					   $("#contact_no").val('');
					   $("#contact_id").val('');
				  		$("#consignee").val('');
				  		$("#address_book_id").val('');					
						$("#company_address_id").val('');
						$("#factory_address_id").val('');
						$("#contact_name").val('');
						$("#vat_no").val('');
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
							$("#contact_no").val($(".list li[class='selected'] a").attr("contact_no"));
							$("#contact_id").val($(".list li[class='selected'] a").attr("contact_id"));
							$("#input-address").val($(".list li[class='selected'] a").attr("consignee"));							
							$("#address_book_id").val($(".list li[class='selected'] a").attr("id"));
							$("#company_address_id").val($(".list li[class='selected'] a").attr("c_id"));							
							$("#vat_no").val($(".list li[class='selected'] a").attr("vat_no"));
							$("#factory_address_id").val($(".list li[class='selected'] a").attr("f_id"));
							$("#input-del-address").val($(".list li[class='selected'] a").attr("deladd"));
							$("#contact_name").val($(".list li[class='selected'] a").attr("contact_name"));
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
                  			$("#contact_no").val($(".list li[class='selected'] a").attr("contact_no"));
                  			$("#contact_id").val($(".list li[class='selected'] a").attr("contact_id"));
							$("#input-address").val($(".list li[class='selected'] a").attr("consignee"));							
							$("#address_book_id").val($(".list li[class='selected'] a").attr("id"));
							$("#company_address_id").val($(".list li[class='selected'] a").attr("c_id"));							
							$("#vat_no").val($(".list li[class='selected'] a").attr("vat_no"));
							$("#factory_address_id").val($(".list li[class='selected'] a").attr("f_id"));
							$("#input-del-address").val($(".list li[class='selected'] a").attr("deladd"));
							$("#contact_name").val($(".list li[class='selected'] a").attr("contact_name"));
							
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
	
	$('#customer-name').keydown( function(e) {
		if (e.keyCode == 9) {
			 $("#ajax_return").fadeOut('slow');
			 $("#ajax_return").html("");
		}
	});

	$("#ajax_return").mouseover(function(){
				$(this).find(".list li a:first-child").mouseover(function () {
					  $("#email").val($(this).attr("email"));
					  $("#contact_no").val($(this).attr("contact_no"));
					  $("#contact_id").val($(this).attr("contact_id"));
					  $("#input-address").val($(this).attr("consignee"));					
					  $("#address_book_id").val($(this).attr("id"));
					  $("#company_address_id").val($(this).attr("c_id"));
					  $("#factory_address_id").val($(this).attr("f_id"));
					  $("#input-del-address").val($(this).attr("deladd"));
					  $("#vat_no").val($(this).attr("vat_no"));
					  $("#contact_name").val($(this).attr("contact_name"));
					  $(this).addClass("selected");
				});
				$(this).find(".list li a:first-child").mouseout(function () {
					  $(this).removeClass("selected");
					  $("#contact_id").val('');
					  $("#email").val('');
					  $("#contact_no").val('');
					  $("#input-address").val('');
					  $("#delivery_info").val('');
					  $("#address_book_id").val('');
					  $("#company_address_id").val('');	
					  $("#factory_address_id").val('');
					  $("#input-del-address").val('');
					  $("#contact_name").val('');
					  $("#vat_no").val('NA');
				});
				$(this).find(".list li a:first-child").click(function () {					
					  if($(this).attr("email")!='null')
					  	$("#email").val($(this).attr("email"));
					  else
					  	$("#email").val('');  
					  	if($(this).attr("contact_no")!='null')
					  	$("#contact_no").val($(this).attr("contact_no"));
					  else
					  	$("#contact_no").val(''); 
					  if($(this).attr("contact_id")!='null')
					  	$("#contact_id").val($(this).attr("contact_id"));
					  else
					  	$("#contact_id").val('');
						
					  if($(this).attr("consignee")!='null')
					  	$("#input-address").val($(this).attr("consignee"));
					  else
					 	 $("#input-address").val('');
					
					if($(this).attr("deladd")!='null')
					  	$("#input-del-address").val($(this).attr("deladd"));
					  else
					 	 $("#input-del-address").val('');
					
					//console.log($(this).attr("deladd"));
    			    	
    					if($(this).attr("vat_no") == '')
    					{
    					    $("#vat_no").val('NA');
    					}
    					else if($(this).attr("vat_no")!='null')
    					{
    						$("#vat_no").val($(this).attr("vat_no"));
    					}
    					else
    					{
    						$("#vat_no").val('NA');
    					}	
    					//console.log($(this).attr("vat_no"));
					  $("#address_book_id").val($(this).attr("id"));
					  if($(this).attr("c_id")!='null')
					 	 $("#company_address_id").val($(this).attr("c_id"));
					  else
					  	 $("#company_address_id").val('');	
					  	 
					  	if($(this).attr("f_id")!='null')
					  	 	$("#factory_address_id").val($(this).attr("f_id"));
					  	 else
					  	    $("#factory_address_id").val('');
					  
					  	if($(this).attr("contact_name")!='null')
					  	 	$("#contact_name").val($(this).attr("contact_name"));
					  	 else
					  	    $("#contact_name").val('');
					  	    
									
					   $("#customer-name").val($(this).text());
					   $("#ajax_return").fadeOut('slow');
						$("#ajax_return").html("");
					 <?php if(isset($addedByInfo['country_id']) && $addedByInfo['country_id']!='111')  {?>
					         $("#email").change();
					  <?php }?>
				});
				
			});
		
    });
	 var fetched_zipper = $("#fetched_zipper").val();
	   if(fetched_zipper == '') {
			checkZipper();
	   }
	   
		jQuery("#form").validationEngine();
		$("#country_id").change(function(){
			
			var country_id = $(this).val();
			var con_id=$("#con_id").val();
			if(country_id == 111)
			{	//$('input:radio[name=transport][value=road]').attr('checked','checked');
				$("#tax_div").show();
				//$("#nrm_div").show();
				$("#freight").show();
				$(".state_list").show();
			}
			else if(country_id == 169 || country_id == 26 || country_id == 27)
			{
				$("#tax_div").hide();
				//$("#nrm_div").hide();
				//$("#fm_div").hide();
			}
			else if(country_id == 42)
			{
			    $(".canada_state_list").show();
			    $("#tax_div").hide();
			    
			}
			else
			{
			//	$('input:radio[name=transport][value=air]').attr('checked','checked');
				$("#tax_div").hide();
			//	$("#nrm_div").hide();
				//$("#fm_div").hide();
		
    			$('.canada_state_list option:first').prop('selected',true);
    			$(".canada_state_list").hide();
    			$("#gst_div").hide();
    			$("#pst_div").hide();
    			$("#hst_div").hide();
			}
			$(".state_list").val('');
			$(".state_list").hide();
			
			if(country_id==169)
			{
				$("#freight").show();		
			}
			getgst();
			
		});
			
		function getgst()
		{
			var con_id=$("#con_id").val();
			//alert(con_id);
			var gst = $("#gst").val();
			var country_id = $("#country_id").val();
			//alert(country_id);
			if(con_id == '214')
			{
				var invoice_date=$("#input-name").val();
				
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
			}
			else
			{ //[kinjal] : made else condon 01-12-2016
				if(con_id==country_id)
					$("#gst_tax").val(gst);
				else
					$("#gst_tax").val('0');
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
	
		
		
			
		
function add_invoices() {
	$("#product").prop('disabled', true);
	$("#size").prop('disabled', true);
	$("#keyword").prop('disabled', true);
	$("#measurement").prop('disabled', true);
	$("#qty").prop('disabled', true);
	$("#rate").prop('disabled', true);
	var proforma_id = $("#proforma_id").val();
	var freight_char=$("#freight_char").val();
	var update_invoice_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=generateInvoice', '',1);?>");
	$.ajax({
			url : update_invoice_url,
			method : 'post',		
			data : {proforma_id : proforma_id,freight_char:freight_char},
			success: function(response){
				set_alert_message('Invoice successfully Added!',"alert-success","fa-check");
				window.location.href='<?php echo HTTP_SERVER; ?>/admin/index.php?route=proforma_invoice_product_code_wise&mod=view&proforma_id='+response+'&status=1+&is_delete=0';
			}
		});
}
function color(n)
{
	var val = $("#color_"+n).val();
	var value = $('input[name=stock_print]:radio:checked').val();
	$("#color_txt_"+n).val('');
	$("#color_txt_"+n).hide();
	if(val == -1 || value=='Digital Print')
	{ 	
		$("#color_txt_"+n).show();
	}
}
	function displaygenerate()
	{
		var edit=$("#edit").val();
		if(edit=='')
		{
			$("#generate_invoice").show();$("#btn_combo").show();
		}
		else
		{
			$("#generate_invoice").hide();$("#btn_combo").hide();
		}		
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
	var zipper_id = $("#zipper_id").val();
	var product_id = $("#product_id").val();
	
	var size_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getProductSize', '',1);?>");
	$.ajax({
		type: "POST",
		url: size_url,					
		data:{product_id:product_id,zipper_id:zipper_id},
		success: function(json) {
			if(json){
				//alert(json);
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
	
$('#btn_save').click(function(){
	$("#taxation1").prop('enable', true);
	$("#taxation2").prop('enable', true);
	$("#taxation3").prop('enable', true);
	$("#taxation4").prop('enable', true);
	$("#taxation_nrm").prop('enable', true);	
	$("#taxation_frm").prop('enable', true);
	$("#h_form").prop('enable', true);
	$("#ct1").prop('enable', true);
	$("#ct3").prop('enable', true);
	$("#email").removeClass("validate[required,custom[email]]");
	var con_id = '<?php echo $addedByInfo['country_id'];?>';

	if(con_id=='111')
	{
	    var slist = $("#slist").val();
	    //console.log(slist);
	    if(slist=='')
	        $("#slist_chosen").addClass("validate[required]");
	    else
	    {
	       $("#slist_chosen").removeClass("validate[required]");
	       $(".slist_chosenformError").css("display","none");
	    }
	}
	if($("#form").validationEngine('validate')){
			var add_product_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=addProformaInvoice', '',1);?>");
				var formData = $("#form").serialize();
				
				$.ajax({
					url : add_product_url,
					method : 'post',		
					data : {formData : formData,con_id:con_id},
					success: function(response){
						//alert(response);
						console.log($("input[name=transport]:radio:checked").val());
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
							//$("#freight_char").prop('disabled', true);
							$("#h_form").prop('disabled', true);
							$("#ct1").prop('disabled', true);
							$("#ct3").prop('disabled', true);
							$("#product").val('');
							$("#size").val('');
							$("#customer_dispatch_p").val('');
							$("#customSize").hide();
							$('#description').val('');
							$("#width").val('');
							$("#height").val('');
							$("#gusset_input").val('');
							$("#color_0").val('');
							$("#color_text").val('');
							$("#qty").val('');
							$("#rem_qty").val('');
							$("#rate").val('');
							$("#keyword").val('');
							$("#product_name").val('');
							$("#color_product").val('');
							$("#product_desc").val('');
							$('#myTable tr').not(':eq(0)').not(':eq(0)').remove();
							$("#myTable tr:nth-child(2)").show();
							$("#invoice_results").html(response);
							$("#tool_price").val('');
							$("#filling_div").hide();
							$("#seal_div").hide();
							$("#gusset_printing").html('');
							$("#pedimento_mexico").val('');
							//$("#none_print").val('');
							//$("#cust_print").val('');
							//$("#stock_print").val('');
							$("#plate").val('1');
							//$("#digi_print").val('');
							//$("#stock_print").val('');
							//$("#container_print").val('');
							$("input:radio[name=stock_print][id=stock_print]").attr('checked', true);
							$("input:radio[name=stock_print][id=none_print]").attr('checked', true);
							$("input:radio[name=stock_con]").attr('checked', false);
						    //console.log($("#proforma_status").val());
						    if($("#proforma_status").val()=='1')
						    {
							    $("#generate_invoice").show();$("#btn_combo").show();
						    }    
							 $("#for_freight_charge_yes").prop('disabled', true);
							 $("#for_freight_charge").prop('disabled', true);  
						    //$("#proforma_status_new").val(($("#proforma_status").val()));
						    $("#total_amount").val($("#total_amt").val());	
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
    		    $("#total_amount").val(response);	
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
    $("#alertbox_"+proforma_invoice_id).modal("");
     });
}
  
$('select[name=currency]').change(function() {
		var currency_id=$(this).attr('id');
		var currency_value = this.value;
		var user_id = <?php echo $addedByInfo['user_id'];?>;
		var currency_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getbankDetails', '',1);?>");
        $.ajax({
			url : currency_url,
			type :'post',
			data :{currency_id:currency_value,user_id:user_id},
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
	if($("#form").validationEngine('validate')){
	var update_invoice_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=updateInvoice', '',1);?>");
			var formData = $("#form").serialize();
			var con_id = '<?php echo $addedByInfo['country_id'];?>';
			$.ajax({
				url : update_invoice_url,
				method : 'post',		
				data : {formData : formData,con_id:con_id},
				success: function(response){
					if(response != 0){
						$("#invoice_results").html("");
						$("#invoice_results").html(response);
						$('#proforma_update').hide();
						$('#product').val('');
						$('#customSize').hide();
						$("#color_0").val('');
						$("#color_text").hide();
						$("#qty").val('');
						$("#rem_qty").val('');
						$("#rate").val('');
						$("#product_desc").val('');
						$('#myTable tr').not(':eq(0)').not(':eq(0)').remove();
						$('#size').val('');
						$('#description').val('');
						$("input:radio[name=zipper]:not(:disabled):first").attr('checked', true);
						$("input:radio[name=valve]:not(:disabled):first").attr('checked', true);
						$("input:radio[name=spout]:not(:disabled):first").attr('checked', true);
						$("input:radio[name=accessorie]:not(:disabled):first").attr('checked', true);
						$("#keyword").val('');
						$("#product_name").val('');
						$("#color_product").val('');
						$("#measurement").val('');
						$("#tool_price").val('');
						$("#filling_div").hide();
						$("#seal_div").hide();
						$("#gusset_printing").html('');
						$("#pedimento_mexico").val('');
						//$("#none_print").val('');
						//$("#cust_print").val('');
						//$("#stock_print").val('');
						$("#plate").val('1');
						//$("#digi_print").val('');
						//$("#stock_print").val('');
						//$("#container_print").val('');
						$("input:radio[name=stock_print][id=stock_print]").attr('checked', true);
						$("input:radio[name=stock_print][id=none_print]").attr('checked', true);
						$("input:radio[name=stock_con]").attr('checked', false);
						//console.log($("#proforma_status").val());
						if($("#proforma_status").val()=='1'){
							$("#generate_invoice").show();$("#btn_combo").show();}
						//$("#proforma_status_new").val(($("#proforma_status").val()));	
						$("#total_amount").val($("#total_amt").val());	
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
	$("#color_product").prop('disabled', true);
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
					//console.log(response);
					var is_delete=<?php echo $_GET['is_delete'];?>;
					if(response != 0){
						var url =  getUrl("<?php echo $obj_general->link($rout, '&mod=index&is_delete='.$_GET['is_delete'], '',1); ?>");
						var redirect = setTimeout(function(){window.location = url; 	}, 800);	
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
	 $("#input-name").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');getgst();});
	 $("#input-buyerdate").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
	 $("#signature_date").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
});
var windowSizeArray = [ "width=200,height=200","width=300,height=400,scrollbars=yes" ];
        $(document).ready(function(){
            $('#mydiv').click(function (event){
			 var product_id = $("#product").val();
				var pop_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getViewToolprice', '',1);?>");
				$.ajax({
					method: "POST",					
					url: pop_url,
					data : {product_id : product_id},
					success: function(response){						
						$("#toolpriceview").html(response);
						$("#smail").modal('show');						
					},
					error: function(){
							return false;	
					}
				});
            });
        });

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
	/*function color_chng()
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
				 // alert(msg);
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
		
	}*/
	
	$(document).click(function(){
			$("#ajax_response").fadeOut('slow');
			$("#ajax_response").html("");
		});
	   	//$("#keyword").focus();
		var offset = $("#keyword").offset();
		var width = $("#holder").width();
		$("#ajax_response").css("width",width);
		 var currentRequest = null;
		$("#keyword").keyup(function(event){
		 var keyword = $("#keyword").val();
		 if(keyword == 'Cylinder' || keyword == 'cylinder' || keyword == 'CYLINDER')
		 {	
				$("#product_name").hide();
				$("#product_code_id").val('0');
				$("#color_product").val('Cylinder');
				$("#product_id").val('');
				$("#real_product_name").val('Cylinder');
				$('#size').removeAttr("readonly", "readonly");
				$('#measurement').removeAttr("readonly", "readonly");
		 }
		 else if(keyword == 'Custom' || keyword == 'custom' || keyword == 'CUSTOM')
		 {	
			//alert('hi');
				$("#product_name").hide();
				$("#product_code_id").val('-1');
				$("#color_txt").show();
				$("#color_product").val('Custom');
				$("#product_id").val('');
				$("#real_product_name").val('Custom');
				$('#size').removeAttr("readonly", "readonly");
				$('#measurement').removeAttr("readonly", "readonly");
		 }
		 else if(keyword.length >='2')
		 {	
		 	
		 	$("#size").attr("readonly","readonly");
			$("#measurement").attr("readonly",true);
			$("#color_txt").hide();
			$("#product_name").show();
			 if(event.keyCode != 40 && event.keyCode != 38 )
			 {		
				 var product_code_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=product_code', '',1);?>");
				 $("#loading").css("visibility","visible");
				  currentRequest = $.ajax({
				   type: "POST",
				   url: product_code_url,
				   data: "product_code="+keyword,
				   beforeSend : function()    {           
                        if(currentRequest != null) {
                            currentRequest.abort();
                        }
                    },
				   success: function(msg){
				   var msg = $.parseJSON(msg);
				   var div='<ul class="list">';
				   
					if(msg.length>0)
					{
						for(var i=0;i<msg.length;i++)
						{	
							div =div+'<li><a href=\'javascript:void(0);\' discr="'+msg[i].description+'" size="'+msg[i].volume+'" mea="'+msg[i].measurement+'" color="'+msg[i].color+'" product_id="'+msg[i].product+'" product_name="'+msg[i].product_name+'"  id="'+msg[i].product_code_id+'"><span class="bold" >'+msg[i].product_code+'</span></a></li>';			

							/*$("#color_product").val(msg[i].color);
							$("#real_product_name").val(msg[i].product_name);
							$("#product_id").val(msg[i].product);
							$("#size").val(msg[i].volume);
							$("#measurement").val(msg[i].measurement);
							$("#product_name").val(msg[i].discr);
							$("#keyword").val(msg[i].product_code);
							$("#product_code_id").val(msg[i].product_code_id);*/
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
							$("#color_product").val($(".list li[class='selected'] a").attr("color"));
							$("#real_product_name").val($(".list li[class='selected'] a").attr("product_name"));
							$("#product_id").val($(".list li[class='selected'] a").attr("product_id"));
							$("#size").val($(".list li[class='selected'] a").attr("size"));
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
							$("#color_product").val($(".list li[class='selected'] a").attr("color"));
							$("#real_product_name").val($(".list li[class='selected'] a").attr("product_name"));
							$("#product_id").val($(".list li[class='selected'] a").attr("product_id"));
							$("#size").val($(".list li[class='selected'] a").attr("size"));
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
				  $("#color_product").val($(this).attr("color"));
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
				  $("#color_product").val($(this).attr("color"));
				  
				  if($(this).attr("color")=='Custom')
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
				  
				  var value = $('input[name=stock_print]:radio:checked').val();
	                if(value == "Digital Print")
	                    $("#color_txt").show();
	                    
				  var con = $('input[type=radio][name=stock_con]:checked').val();
                    if(con=='cust')
                        $("#color_txt").show();
				  
				  $("#real_product_name").val($(this).attr("product_name"));
				$("#product_id").val($(this).attr("product_id"));
				 $("#size").val($(this).attr("size"));
				  $("#measurement").val($(this).attr("mea"));
				  $("#product_code_id").val($(this).attr("id"));
				  $("#keyword").val($(this).text());
				  $("#ajax_response").fadeOut('slow');
				  $("#ajax_response").html("");
				getStockQty($(this).attr("id"));	
				showSize();
				
				
				if($(this).attr("product_id")=='31' || $(this).attr("product_id")=='16' || $(this).attr("product_id")=='50'){
    				$("#filling_div").show();$('input[type="radio"][value="Filling from Top"]').attr("checked","checked");}
    			else{
    				$("#filling_div").hide();}
				
				if($(this).attr("product_id")=='1'){
				    $("#seal_div").show();$('input[type="radio"][value="Seal in center"]').attr("checked","checked");}
				else{
				    $("#seal_div").hide();}
				//sonu 30-6-2017
				var country_id = $("#country_id").val();
				//[kinjal] made cond for when user select custom product that it shown error so...[1-8-2017]
				if(country_id == '111' && $(this).attr("color")!='Custom' && $(this).attr("product_id")!='11'){
					getrate($(this).attr("id"));
					getwieght($(this).attr("id"));
				}
				/*if(country_id == '155' && $(this).attr("color")!='Custom'){
					productrateAllCountry($(this).attr("id"));
					//getwieght($(this).attr("id"));
				}*/
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
//[kinjal] on 21-6-2017		
function getStockQty(product_code_id)
{
	
	var user_id = $("#user_id").val();
	var u_type_id = $("#user_type_id").val();	
	if(user_id=='1' && u_type_id=='1')
	{
		$("#rem_qty").val(5000);
		$("#inv_bal_qty").val(1000);
	
	}
	else
	{
		var get_qty_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getStockQty'.$add_url, '',1);?>");
		$.ajax({
			url : get_qty_url,
			method : 'post',
			data : {product_code_id : product_code_id},
			success: function(response){
					var val =  $.parseJSON(response);
					
					$("#rem_qty").val(val.remaining_qty);					
					//added this cond. by kinjal on (4-11-2019)
					if(val.remaining_qty=='0')
					    $(".remaining_qty").css({ 'background-color' : 'lightcoral', 'color' : 'white' });
					else
					    $(".remaining_qty").css({ 'background-color' : '', 'color' : '' });
					
					var qty = $("#qty").val();
				},
			error: function(){
				return false;	
			}
		});
	}
	
}



//end [kinjAL]

//sonu 30-6-2017
function getrate(product_code_id){
	var color=$('#color_product').val();
//	alert("hi hi : "+product_code_id);
	var ratesuggestion_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=productrate', '',1);?>");
		$.ajax({
			method: "POST",
			url:ratesuggestion_url ,
			data:{product_code_id:product_code_id,color:color},
			success: function(response)
			{  
		//	alert(response);
				if(response != 0){		
					//alert(response);
					//console.log(response);				
					$("#rate").val(response);
					$("#original_rate").val(response);
				
				}
			}
			
		});
}


//end sonu

//[kinjal] : made on (28-8-2018)
function getwieght(product_code_id){
	
	var ratesuggestion_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=productweight', '',1);?>");
		$.ajax({
			method: "POST",
			url:ratesuggestion_url ,
			data:{product_code_id:product_code_id},
			success: function(response)
			{  //console.log(response);
				if(response != 0){		
					$("#netweight").val(response);
				}
			}
			
		});
}
//end [kinjAL]
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
					if(msg.gst_no!= '0.000')
					{
						$("#vat_no").val(msg.gst_no);
						
					}
				
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
//});



//add function for  get price [sonu] 25-8-2017

function getProductPriceAllCountry(){
	
	var product_code_id = $("#product_code_id").val();	
	var product_id = $("#product_id").val();	
	var transportation =$("input[name='transport']:checked").val();	
	var t_qty =$("input[name='dis_qty']:checked").val();	
	var qty =$("#qty").val();
	var country_id = '<?php echo $addedByInfo['country_id'];?>';
	var ib_id = '<?php echo $addedByInfo['user_id'];?>';
	var color =$("#color_product").val();
    //console.log(t_qty);
	if(color!='Custom'){
		var ratesuggestion_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=productrateAllCountry', '',1);?>");
		$.ajax({
			method: "POST",
			url:ratesuggestion_url ,
			data:{product_code_id:product_code_id,transportation:transportation,qty:qty,country_id:country_id,t_qty:t_qty,ib_id:ib_id},
			success: function(response)
			{  
		        console.log(response);
				if(response != 0){		
					if(country_id !='111')
					{
						$("#rate").val(response);
					}
					if(country_id =='111' && product_id=='11')
					{
						$("#rate").val(response);
					}
				}
				else
				{
					if(country_id =='111')
					{
						var value = $('input[name=stock_print]:radio:checked').val();
						if(value == "Digital Print")	
							getDigitalPrice(product_code_id,qty,country_id);
					}
				}
			}
			
		});
	}
	
}

function qty_change(){
    $("#qty").val('');
	$("#rate").val(''); 
}
$("#gen_pro_as").change(function(){
	var value = $(this).val();
	if(value=='2')
	{
		$("#bank_id option[value='11']").remove();
		$("#currency").val('2');
	}
	else
	{
		$("#currency").val('10');
    	$('select[name=currency]').change();
	}
});
// End sonu

function getGussetPrinting(product_id,n='0',printing_option='',gusset_printing_option='')
{
		//alert(gusset_printing_option);
		var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getGussetPrinting', '',1);?>");
			$.ajax({
				type: "POST",
				url: url,					
				data:{product_id:product_id,n:n,printing_option:printing_option,gusset_printing_option:gusset_printing_option},
				success: function(json) {
					
						$("#gusset_printing").html(json);
					
				}
			});	
}

//[kinjal] made on (3-5-2018)
$("#stock_print, #digi_print,#container_print,#None_print,#foil_print").click(function(){
	//alert('ghgh');
	var value = $('input[name=stock_print]:radio:checked').val();
	if(value == "Digital Print" || value == "Foil Stamping")
	{
		//$("#color_txt").show();
		$('#digital_print_div').css("display","block");
	}
	else
	{
		//$("#color_txt").hide();
		$('#digital_print_div').css("display","none");
	}
	if(value == "Containers")
	    $('#con_print_div').css("display","block");
	else
		$('#con_print_div').css("display","none");
});
$("#for_freight_charge,#for_freight_charge_yes").click(function(){
    var value = $('input[name=for_freight_charge]:radio:checked').val();
    //console.log(value);
    if(value == "Yes")
    {
        $("#for_frieght").css("display","none");
        $("#freight_char").addClass("form-control validate[required]");
    }
    else
        $("#for_frieght").css("display","block");
});
$('.btn-number').click(function(e){
    e.preventDefault();
    $("#qty").val('');
	var fieldName = $(this).attr('data-field');
     var type      = $(this).attr('data-type');
 // alert(e);
    var input = $("input[name='"+fieldName+"']");
    var currentVal = parseInt(input.val());
      // alert(currentVal);
    if (!isNaN(currentVal)) {
        if(type == 'minus') {
         
            if(currentVal > input.attr('min')) {
                input.val(currentVal - 1).change();
            } 
            if(parseInt(input.val()) == input.attr('min')) {
                $(this).attr('disabled', true);
            }

        } else if(type == 'plus') {
            
            // alert(parseInt(input.val()));
            //lert(type);
            if(currentVal < input.attr('max')) {
                input.val(currentVal + 1).change();
            }
            if(parseInt(input.val()) == input.attr('max')) {
                $(this).attr('disabled', true);
            }

        }
    } else {
        input.val(0);
    }
});
$('.input-number').change(function() {
    
    minValue =  parseInt($(this).attr('min'));
    maxValue =  parseInt($(this).attr('max'));
    valueCurrent = parseInt($(this).val());
    
    name = $(this).attr('name');
    if(valueCurrent >= minValue) {
        $(".btn-number[data-type='minus'][data-field='"+name+"']").removeAttr('disabled')
    } 
    if(valueCurrent <= maxValue) {
        $(".btn-number[data-type='plus'][data-field='"+name+"']").removeAttr('disabled')
    } 
});
function getDigitalPrice(product_code_id,qty,country_id)
{
	var rate = $("#original_rate").val();
	var plate = $("#plate").val();
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getDigitalPrice', '',1);?>");
		$.ajax({
			type: "POST",
			url: url,					
			data:{product_code_id:product_code_id,qty:qty,plate:plate,rate:rate,country_id:country_id},
			success: function(json) {
				
					//console.log(json);
				$("#rate").val(json);
			}
		});	
}
$('#plate').change(function() {
	$("#qty").val('');
});
function gen_invoice(data_val)
{
	var editor = CKEDITOR.instances.message;
    if (editor) {
        editor.destroy(true); 
    } 
	var proforma_id = $("#proforma_id").val();
	var freight_char = $("#freight_char").val();
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=generate_pro', '',1);?>");
	var rout = '<?php echo $rout;?>';
	$.ajax({
		type: "POST",
		url: url,					
		data:{data_val:data_val,proforma_id:proforma_id,rout:rout,freight_char:freight_char},
		success: function(json) {
				//	console.log(json);
				if(data_val=='download')
					window.open(''+json+'');				
				else if(data_val=='payment' || data_val=='transfer')
					var redirect=setTimeout(function(){window.location = getUrl(''+json+'');}, 800);
				else if(data_val=='email')
				{
					$("#customer").html('Sending Mail To :- '+$("#customer-name").val());
					$("#subject").val($("#buyersno").val()+' - '+$("#proforma_no").val()+' - '+$("#customer-name").val());
					$("#message").val('<p>Hello '+$("#customer-name").val()+',</p> <p>Please Find attached Proforma Invoice for your order.</p><p>You can either pay over the phone or as a bank transfer.</p> <p>The bank details are on the Invoice. Please send me the payment advise if you choose to direct deposit & I will dispatch the stocks.</p> <p>I look forward to hearing back from you and please let me know if anything.</p><p>Thanks.</p>');
					$("#toemail").val($("#email").val());
					$("#proforma_id_send").val(proforma_id);
					$("#emailform").val('<?php echo $addedByInfo['email'];?>');<?php //echo $addedByInfo['email'];?>
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
			
		}
	});
}
$("#country_id").change(function(){			
			var stext = $('#country_id').find('option:selected').text().toLowerCase();
			if( stext === "india"){
				$(".ch1").hide();
				$(".ch2").hide();
				$(".ch3").show();
				$(".ch4").show();
				$(".chpay1").show();
				$(".chpay2").show();
				$("#lbl_pay").show();	
			}
			else if ( stext === "nepal" || stext === "bhutan"){
				$(".ch1").show();
				$(".ch2").show();
				$(".ch3").show();
				$(".ch4").show();
				$(".chpay1").hide();
				$(".chpay2").hide();
				$("#lbl_pay").hide();
			}
			else{
				$(".ch1").show();
				$(".ch2").show();
				$(".ch3").hide();
				$(".ch4").hide();
				$(".chpay1").hide();
				$(".chpay2").hide();
				$("#lbl_pay").hide();
			}
			var con_id= <?php echo $addedByInfo['country_id'];?>;
			if(con_id==251)
			{
			    $(".ch1").show();
				$(".ch2").show();
				$(".ch3").show();
				$(".ch4").show();
				$(".chpay1").hide();
				$(".chpay2").hide();
				$("#lbl_pay").hide();
			}
			
	    }).change();
	    
	    
 $("#email").change(function(){
   
    var email = $(this).val();
    //  alert(email);
    var url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=check_customer', '',1);?>");
    $.ajax({
				url : url,
				method : 'post',
				data : {email : email},
				success: function(response){
				    if(response!='0')
				    {
				       
				        $("#email_msg").html(response);
				       // $("#email").val('');
				    }
				},
				error: function(){
					return false;	
				}
		});	
});
	    
//END [kinjal]
</script> 
<?php  } else { 
		include(DIR_ADMIN.'access_denied.php');
	} ?>
