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
//Start : edit
$edit = '';
if(!$obj_general->hasPermission('add',$menuId)){
	$display_status = false;
}
//Close : edit

$quantity_error = '';
if($display_status){
	//printr($_POST);//die;
	//insert user
	if(isset($_POST['btn_generate'])){
		$post = post($_POST);
	
			if(isset($post['digital_product_quotation_price_id']) && !empty($post['digital_product_quotation_price_id']))
			{
			    	$multi_cust_id= $obj_digital_custom_order->addQuotationToCustomOrder($post);
				 page_redirect($obj_general->link($rout, '&mod=view&custom_order_id='.encode($multi_cust_id).'&filter_edit=0', '',1));
			}
	
	}
	
	if(isset($_POST['btn_rgenerate'])){
		$post = post($_POST);
			
		//printr($post);die;
		$last_id = $obj_digital_custom_order->addRollQuotation($post);
		//printr($last_id);die;
		if($last_id == "Error"){
			$obj_session->data['warning'] = 'Error : Please fillup form carefully!';
		}else{
			$obj_session->data['success'] = 'Your Custom Order generated!';
			page_redirect($obj_general->link($rout, '&mod=view&custom_order_id='.encode($last_id), '',1));
		//die;
		}		
	}
	
	//Add quotation
	if(isset($_POST['btn_save'])){
		//printr($obj_session->data['repost']);die;
		$postData = unserialize($obj_session->data['repost']);
		unset($obj_session->data['repost']);
		//printr($postData);die;
		$insert_id = $obj_digital_custom_order->addCustomOrder($postData);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	//die;
	}
	
	$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
	$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
	$userCurrency = $obj_digital_custom_order->getUserCurrencyInfo($user_type_id,$user_id);
	//printr($userCurrency);
	$allow_currency_status = $obj_digital_custom_order->allowCurrencyStatus($user_type_id,$user_id);
	$addedByInfo = $obj_digital_custom_order->getUser($user_id,$user_type_id);
//	printr($addedByInfo);//die;
	if(isset($_GET['quotation_no']))
	{ 
		$quotation_id = $obj_digital_custom_order->getQuotationId(decode($_GET['quotation_no']));
		$address_book_id = $obj_digital_custom_order->getmulti_quation_id($quotation_id);
		$quota_detail = $obj_digital_custom_order->getQuotaDetail($quotation_id);
		//printr($quota_detail);
	}
	//echo $quotation_id;
	//$data = $obj_quotation->getQuotation($quotation_id,'',$obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);
//printr($data);
$quota_detail['shipment_country_id']=$addedByInfo['country_id'];
        if($addedByInfo['country_id']!='253'){
           // printr($addedByInfo);
    	    $user_address = $obj_digital_custom_order->getUserAddress($user_id,$user_type_id);
        }


   
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
				  	<span><?php echo $display_name;?> Detail</span>
                    
                  </header>
                  <div class="panel-body">
                    <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
                    
                    <!-- Fixed Value Div One Time Entry-->
                    <div id="fixedDiv">
                      <div class="col-lg-6">
                           <h4><i class="fa fa-edit"></i> General Details</h4>
                           <div class="line m-t-large" style="margin-top:-4px;"></div><br>
                            
                            <div class="form-group">
                                <label class="col-lg-4 control-label"><span class="required">*</span><span class="company_lab"><?php if(isset($_GET['quotation_no'])){ echo 'Customer';}else {echo 'Company';}?> Name</span></label>
                                <div class="col-lg-7">
                                      <input type="text" name="company" value="<?php if(isset($_GET['quotation_no'])){ echo $quota_detail['customer_name']; }?>" id="company" class="form-control validate[required]">
                                     <input type="hidden" name="product_id" value="<?php if(isset($_GET['quotation_no'])){ echo $quota_detail['product_id']; }?>" id="product" >
                                     <input type="hidden" name="address_book_id" value="<?php if(isset($_GET['quotation_no'])){ echo $address_book_id; }?>" id="address_book_id" >
                                      <input type="hidden" name="company_address_id" value="" id="company_address_id" >
                                       <input type="hidden" name="factory_address_id" value="" id="factory_address_id" >
                                </div>
                            </div>
                            
                            <div class="form-group" style="display: none;">
                                <label class="col-lg-4 control-label">Website</label>
                                <div class="col-lg-7">
                                     <input type="text" name="website" value="" id="website" class="form-control">
                                </div>
                            </div>
                            
                            <div class="form-group" style="display: none;">
                                <label class="col-lg-4 control-label">First Name</label>
                                <div class="col-lg-7">
                                     <input type="text" name="first_name" value="" class="form-control ">
                                </div>
                            </div>
                            
                            <div class="form-group" style="display: none;">
                                <label class="col-lg-4 control-label">Last Name </label>
                                <div class="col-lg-7">
                                     <input type="text" name="last_name" value="" class="form-control ">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-lg-4 control-label"><span class="required">*</span>Email Address </label>
                                <div class="col-lg-7">
                                     <input type="text" name="email" value="" id="email" class="form-control validate[required]">
                                </div>
                            </div>
                         
								
                            <div class="form-group">
                                <label class="col-lg-4 control-label"><span class="required">*</span>Order Currency </label>
                                <div class="col-lg-7">
                                <?PHP
								$selcurrency = $obj_digital_custom_order->getdefaultcurrency();
								//printr($selcurrency);
								
								$currency = $obj_digital_custom_order->getCurrencyList();
								//printr($_GET['quotation_no']);
								echo '<select name="order_currency" id="order_currency" class="form-control">';
								foreach($currency as $key=>$value)
								{
									
									if($addedByInfo['country_id'] == '155')
									{
										if($value['currency_code'] == 'MXN' || $value['currency_code'] == 'USD')
										{
											if(isset($_GET['quotation_no']) && $value['currency_code'] == $quota_detail['currency'])
											{	
												echo '<option value="'.$value['currency_code'].'" selected="selected">'.$value['currency_code'].'</option>';
											}	
											elseif($selcurrency['currency_code']==$value['currency_code'])
											{	
												echo '<option value="'.$value['currency_code'].'" selected="selected">'.$value['currency_code'].'</option>';
											}
											else
											{	
												echo '<option value="'.$value['currency_code'].'">'.$value['currency_code'].'</option>';
											}
										}
									}
									else if($addedByInfo['country_id'] == '42')
									{
										if($value['currency_code'] == 'CAD' || $value['currency_code'] == 'USD')
										{
											if(isset($_GET['quotation_no']) && $value['currency_code'] == $quota_detail['currency'])
											{	
												echo '<option value="'.$value['currency_code'].'" selected="selected">'.$value['currency_code'].'</option>';
											}	
											elseif($selcurrency['currency_code']==$value['currency_code'])
											{	
												echo '<option value="'.$value['currency_code'].'" selected="selected">'.$value['currency_code'].'</option>';
											}
											else
											{	
												echo '<option value="'.$value['currency_code'].'">'.$value['currency_code'].'</option>';
											}
										}
									}
									else
									{
										if(isset($_GET['quotation_no']) && $value['currency_code'] == $quota_detail['currency'])
										{	
											echo '<option value="'.$value['currency_code'].'" selected="selected">'.$value['currency_code'].'</option>';
										}	
										elseif($selcurrency['currency_code']==$value['currency_code'])
										{	
											echo '<option value="'.$value['currency_code'].'" selected="selected">'.$value['currency_code'].'</option>';
										}
										else
										{	
											echo '<option value="'.$value['currency_code'].'">'.$value['currency_code'].'</option>';
										}
										
									}
								}
								
								echo '</select>';
								?>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Contact No </label>
                                <div class="col-lg-7">
                                     <input type="text" name="contact_number" value="" class="form-control ">
                                </div> 
                            </div>
                            
                            <div class="form-group" style="display: none;">
                                <label class="col-lg-4 control-label">VAT No </label>
                                <div class="col-lg-7">
                                     <input type="text" name="vat_number" value="" class="form-control ">
                                </div>
                            </div>
                            <?php 
              					if((isset($addedByInfo['country_id']) && ($addedByInfo['country_id']!='14' || $addedByInfo['country_id']!='214'|| $addedByInfo['country_id']!='155' ))){
									 ?>
								 <div class="form-group">
									<label class="col-lg-4 control-label"><span class="required">*</span>Reference No</label>
                                        <div class="col-lg-7">
                                             <input type="text" name="ref_no" id="ref_no" value="" class="form-control validate[required]">
                                        </div>
							 	 </div>
					 		 <?php } ?>
                           <div class="form-group">
                                <label class="col-lg-4 control-label"><span class="required">*</span>Industry </label>
                                <div class="col-lg-7">
                                     <select name="industry" class="form-control">
                                    	<?php
										$industrys = $obj_digital_custom_order->getIndustrys();
										foreach($industrys as $industry){
											echo '<option value="'.$industry['enquiry_industry_id'].'">'.$industry['industry'].'</option>';
										}
										?>
                                    </select>
                                </div>
                        </div>
                            
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Order Note <br><small class="text-muted">(for internal purpose)</small></label>
                                <div class="col-lg-7">
                                     <textarea name="order_note" class="form-control"></textarea>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Order Instruction <br><small class="text-muted">(this will be displayed in order)</small></label>
                                <div class="col-lg-7">
                                     <textarea name="order_instruction" class="form-control"></textarea>
                                </div>
                            </div>
                            
                      </div>
                        
                        <div class="col-lg-6">
                            <div class="col-lg-12">
                              <h4><i class="fa fa-edit"></i> Shipping Address ( Delivery Address )</h4>
                              <div class="line m-t-large" style="margin-top:-4px;"></div><br>
                              <div class="form-group">
                                <label class="col-lg-3 control-label"><span class="required">*</span> Address </label>
                                <div class="col-lg-8">
                                    <?php //printr($user_address);?>
                                  <!--<input type="text" name="shipping_address_1" id="shipping_address_1" value="<?php //if(isset($user_address['company_address'])) { echo $user_address['company_address']; } ?>" class="form-control validate[required] test">-->
                                <textarea name="shipping_address_1" id="shipping_address_1" class="form-control validate[required] test"><?php if(isset($user_address['company_address'])) { echo $user_address['company_address']; } ?></textarea>
                                </div>
                              </div>
                              
                              <div class="form-group" style="display: none;">
                                <label class="col-lg-3 control-label">Address 2</label>
                                <div class="col-lg-8">
                                  <input type="text" name="shipping_address_2" value="" class="form-control test">
                                </div>
                              </div>
                              
                              <div class="form-group" style="display: none;">
                                <label class="col-lg-3 control-label"><span class="required">*</span>City</label>
                                <div class="col-lg-8">
                                  <input type="text" name="shipping_city" value="" id="shipping_city" class="form-control validate[required] test">
                                </div>
                              </div>
                             
                                    
                           
                              <?php  if($addedByInfo['country_id']=='42'){
                              	$tax_canada = $obj_digital_custom_order->getTaxationCanada();?>
                              	      <div class="form-group">
			                        	<label class="col-lg-3 control-label"><span class="required">*</span>State</label>
			                        	<div class="col-lg-3">
			                            <select name="shipping_state" id="shipping_state" class="form-control validate[required]" >
			                               <option value="">Select State</option>
			                               <?php foreach($tax_canada as $tcanada)
										   		{												
														echo '<option value="'.$tcanada['state'].'" > <b>'.$tcanada['abbreviation'] .' => </b>'.$tcanada['state'].'</option>';
													
										 		} ?>
			                               
			                              </select>
			                            </div>
			                       </div>

                              <?php }?>
                              <div class="form-group">
                                <label class="col-lg-3 control-label"><span class="required">*</span>Country</label>
                                <div class="col-lg-8">								 
								 <select name="shipping_country_id" id="shipping_country_id" class="form-control">
								 <?php 
								   $countrys = $obj_digital_custom_order->getCountries();
								   foreach($countrys as $country){
									   if(isset($_GET['quotation_no']) && $country['country_id'] == $quota_detail['shipment_country_id']){
									 ?>	
											<option value="<?php echo $country['country_id']; ?>"  selected="selected" ><?php echo $country['country_name']; ?></option>
									  <?php }else{?>
											<option value="<?php echo $country['country_id']; ?>" ><?php echo $country['country_name']; ?></option>
                                 
                                 <?php }	} ?>  
                                 </select>										
                                </div>
                              </div>
                                <?php //printr($_SESSION); 
            						if(isset($_SESSION['ADMIN_LOGIN_USER_TYPE']) && $_SESSION['ADMIN_LOGIN_USER_TYPE']==4)
            						{
            							$adminId = $obj_digital_custom_order->getEmpAdminId($_SESSION['ADMIN_LOGIN_SWISS']);
            							$adminCountryId = $obj_digital_custom_order->getUser($adminId,$_SESSION['ADMIN_LOGIN_USER_TYPE']);
            							//printr($adminCountryId);
            						}
            						else{
            							$adminCountryId=$addedByInfo;
            						}
            			        	if(isset($adminCountryId['country_id']) && $adminCountryId['country_id'] && $adminCountryId['country_id']==111){ 
                                         if(isset($adminCountryId['discount']) and $adminCountryId['discount']>0.000)
                            					{ ?><div class="form-group">
                                                    <label class="col-lg-3 control-label">Discount %</label>
                                                    <div class="col-lg-8">
                                                    	<input type="text" id="discount" name="discount" value="" class="form-control" onblur="checkdiscount()"/>
                                                        <input type="hidden" id="max_discount" name="max_discount" value="<?php echo isset($adminCountryId['discount'])?$adminCountryId['discount']:''; ?>" class="form-control"/>
                                                    <div id="discount_error"></div>
                                                    </div>
                                                  </div>
                                                  <?php }
                    					  }
        					?>
                     <div class="form-group" id="normalform_div">
                        <label class="col-lg-3 control-label">Taxation</label>
                        <div class="col-lg-8">
                                <?php
                                $sel_tax = array();
                                if(isset($tax) && !empty($tax) && $tax){
                                    $sel_tax = $tex;
                                }
                                    echo '<div><div  >';
									echo '	<label  style="font-weight: normal;">';                                 
								echo '	<input type="radio" name="normalform" id="normalform" value="Normal" checked="checked" > ';								
								echo '	 Normal </label></div>
								<div >';
								echo '	<label style="font-weight: normal;">';                                 
								echo '	<input type="radio" name="normalform" id="normalform" value="form" > ';								
								echo '	Form </label></div></div>';
								?>
                            </div>
                        </div> 
                       <div class="form-group" id="tax_div">
                        <label class="col-lg-3 control-label">Zone</label>
                        <div class="col-lg-8">
                                <?php
                                $sel_tax = array();
                                if(isset($tax) && !empty($tax) && $tax){
                                    $sel_tax = $tex;
                                }
                                    echo '<div  >';
									echo '	<label  style="font-weight: normal;">';                                 
								echo '	<input type="radio" name="taxation" id="taxation" value="cst_with_form_c" checked="checked" > ';								
								echo '	 Out Of Gujarat (CST With Form C) </label></div>
								<div >';
								echo '	<label style="font-weight: normal;">';                                 
								echo '	<input type="radio" name="taxation" id="taxation" value="cst_without_form_c" > ';								
								echo '	 Out Of Gujarat (CST With Out Form C) </label></div>
								<div  >';
									echo '	<label style="font-weight: normal;">';                                 
									echo '	<input type="radio" name="taxation" id="taxation" value="vat" > ';								
                                    echo '	With In Gujarat </label>';
                                    echo '</div>';
                              
                                ?>
                            </div>
                        </div>
                         <div class="form-group" id="formtypes">
                        <label class="col-lg-3 control-label">Form Types</label>
                        <div class="col-lg-9">
                         	<div class="checkbox chf1" style="float: left; width: 30%;">
                                <label>
                                  <input type="checkbox" name="formtype[]" value="H Form" id="hform" class="formtypeclass" checked="checked">
                                 H Form
                                 </label>
                             </div>
                             <div class="checkbox chf2" style="float: left; width: 30%;">
                                <label>
                                  <input type="checkbox" name="formtype[]" value="CT1" id="ct1" class="formtypeclass"  >
                                  CT1
                                 </label>
                             </div>
                            <div class="checkbox chf3" style="float: left;  width: 30%;">
                                    <label>
                                      <input type="checkbox" name="formtype[]" value="CT3"  id="ct2" class="formtypeclass">
                                      CT3
                                     </label>
                            </div>
                           </div>
                      </div> 
                              <div class="form-group">
                                <label class="col-lg-3 control-label"><span class="required">*</span>Post Code</label>
                                <div class="col-lg-8">
                                  <input type="text" name="shipping_postcode" id="shipping_postcode" value="" class="form-control validate[required]">
                                </div>
                              </div>
                               <div class="form-group">
                                <label class="col-lg-3 control-label">Contact No </label>
                                <div class="col-lg-7">
                                     <input type="text" name="contact_number" value="" class="form-control ">
                                </div> 
                            </div>
                              
                            </div>
                            
                            <div class="col-lg-12">
                              
                              <h4><i class="fa fa-edit"></i> Billing Address ( Buyer Address )
                                 <label class="checkbox-custom  m-l-small pull-right" style="font-size:14px;">
                                   <input type="checkbox" name="same_as_above" id="same-above" value="1">
                                 <i class="fa fa-square-o"></i> Same as Above? </label> 
                              </h4>  	
                              	
                              <div class="line m-t-large" style="margin-top:4px;"></div><br>
                              
                              <div id="billing-details">
                                  <div class="form-group">
                                    <label class="col-lg-3 control-label"><span class="required">*</span> Address 1</label>
                                    <div class="col-lg-8">
                                      <!--<input type="text" name="billing_address_1" id="billing_address_1" value="" class="form-control validate[required]">-->
                                      <textarea name="billing_address_1" id="billing_address_1" class="form-control validate[required]"><?php if(isset($user_address['company_address'])) { echo $user_address['company_address']; } ?></textarea>
                                    </div>
                                  </div>
                                  
                                  <div class="form-group" style="display:none">
                                    <label class="col-lg-3 control-label">Address 2</label>
                                    <div class="col-lg-8">
                                      <input type="text" name="billing_address_2" value="" class="form-control">
                                    </div>
                                  </div>
                                  
                                  <div class="form-group">
                                    <label class="col-lg-3 control-label"><span class="required">*</span>City</label>
                                    <div class="col-lg-8">
                                      <input type="text" name="billing_city" value="" id="billing_city" class="form-control validate[required]">
                                    </div>
                                  </div> 
                                    <?php  if($addedByInfo['country_id']=='42'){
                              	$tax_canada = $obj_digital_custom_order->getTaxationCanada();?>
                              	      <div class="form-group">
			                        	<label class="col-lg-3 control-label"><span class="required">*</span>State</label>
			                        	<div class="col-lg-3">
			                            <select name="billing_state" id="billing_state" class="form-control validate[required]" >
			                               <option value="">Select State</option>
			                               <?php foreach($tax_canada as $tcanada)
										   		{
												
													echo '<option value="'.$tcanada['state'].'" > <b>'.$tcanada['abbreviation'] .' => </b>'.$tcanada['state'].'</option>';
													
										 		} ?>
			                               
			                              </select>
			                            </div>
			                       </div>

                              <?php }?>
                                 
                                    <div class="form-group">
                                    <label class="col-lg-3 control-label"><span class="required">*</span>Country</label>
                                    <div class="col-lg-8">
                                      <select name="billing_country_id" id="billing_country_id" class="form-control">
                                        <?php $countrys = $obj_digital_custom_order->getCountries();
											 foreach($countrys as $country){ 
												if(isset($_GET['quotation_no']) && $country['country_id'] == $quota_detail['shipment_country_id']){?>	
                                                	<option value="<?php echo $country['country_id']; ?>"  selected="selected" ><?php echo $country['country_name']; ?></option>
												 <?php }else{?>
													<option value="<?php echo $country['country_id']; ?>" ><?php echo $country['country_name']; ?></option>   
												<?php } }?>	
                                      </select>
                                    </div>
                                  </div>
                                   <div class="form-group">
                                    <label class="col-lg-3 control-label"><span class="required">*</span>Post Code</label>
                                    <div class="col-lg-8">
                                      <input type="text" name="billing_postcode" value="" class="form-control validate[required]" id="billing_postcode">
                                    </div>
                                  </div>

                              </div>
                              
                            </div>
                            
                           
                            
                      </div>
                  </div>
             
		      <div class="col-lg-12" id="add-product-div">
       
                        <div class="form-group">
                            <label class="col-lg-3 control-label"><span class="required">*</span>Enter Quotation No.</label>
                            <div class="col-lg-8"><input type="text"  class="form-control validate[required] "  id="quotation_no" name="quotation_no" <?php if(isset($_GET['quotation_no']) && $_GET['quotation_no']!=''){?>disabled="disabled" value="<?php echo decode($_GET['quotation_no']);?>" <?php }else { echo 'value=""';}?> onchange="frm_fill();">
                            </div>
                        </div>
                  
                <div id="save_btn_div">
                   <div class="form-group">
                        <div class="col-lg-9 col-lg-offset-3">
                      	  <?php /*?><button type="button" name="btn_add" id="btn_add" class="btn btn-primary" style="display:inline">Add Item</button>	<?php */?>
                        <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a>
                        </div>
                      </div>
                       </div>
                      <div id="result">                      	
                 		</div>
                        <div class="form-group">
                        <div class="col-lg-9 col-lg-offset-3" id="qoute_generate" style="display:none">
                         <button type="submit" name="btn_generate" id="btn_generate" onclick="generate()" class="btn btn-primary">Generate </button>	
                          </div>
                      </div> 
                       </div>  
                       <input type="hidden" id="check_id" value="" />
                    </form>
                             
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
                     </div>    
                </section>        
      </div>
    </div>
  </section>
</section>

<style>
    .chosen-container.chosen-container-single {
        width: 300px !important; /* or any value that fits your needs */
    }
</style>
<script src="https://harvesthq.github.io/chosen/chosen.jquery.js" type="text/javascript"></script>
<link rel="stylesheet" href="https://harvesthq.github.io/chosen/chosen.css" type="text/css"/> 

<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script>


function frm_fill(){
	var quotation_no=$('#quotation_no').val();
	if(quotation_no!='')
	{
		$('#combination_div').hide();
		$('#save_btn_div').hide();
	//	$("#shipping_country_id").attr('disabled',true);
	//	$("#taxation").attr('disabled',true);
		var pop_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getQuotationData', '',1);?>");
		$.ajax({
			method: "POST",					
			url: pop_url,
			data : {quotation_no : quotation_no},
			success: function(response){	
			 console.log(response);
				if(response=='')
				{
					alert("Please Create New Quotation");
					$("#result").html('');
				}
				else
					var val = $.parseJSON(response);
				//val.result;
				//alert(val.result);
				var result=val.result;
			
			//$("#shipping_country_id").option();
			$("#shipping_country_id option[value='"+result+"']").attr("selected","selected");
			$("#billing_country_id option[value='"+result+"']").attr("selected","selected");
			$("#company").val(val.customer);
			$("#email").val(val.email);
			//sonu add 21-4-2017
			$("#address_book_id").val(val.address_book_id);
			var address_book_id=val.address_book_id;			
			
			getcustomer_all_data(address_book_id);
			
			
			
			var lab=$(".company_lab").text('Customer Name');
			var currency_code=val.currency_code;
			//alert(currency_code);
			//$("#order_currency option[value='"+currency_code+"']").attr("selected","selected");
			//alert(lab);
				//if(country_id
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
					$("#qoute_generate").show();
					$(".choosen_data").chosen();
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

	

$("#same-above").click(function(){
	$("#loading").show();
	if($(this).prop('checked') == true){
		$("#billing-details").slideUp('slow');
	}else{
		$("#billing-details").slideDown('slow');
	}
	$("#loading").fadeOut();
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

    jQuery(document).ready(function(){	
	var quotation_no=$('#quotation_no').val();
		if(quotation_no != '')
		{
			frm_fill();
		}
			
		$("#layer option[value='MQ==']").attr("selected","selected");
        // binds form submission and fields to the validation engine
        jQuery("#form").validationEngine();
				
		$("#shipping_country_id").change(function(){
			var shipping_country_id = $(this).val();
			if(shipping_country_id == 111)
			{
				$("#tax_div").show();	
				$("#normalform_div").show();
				$("#formtypes").hide();	
			}
			else
			{
			$("#tax_div").hide();
			$("#normalform_div").hide();	
			$("#formtypes").hide();		
			}
		});

				

	$('#form input[name="normalform"]').on('change', function() {
		var normalform=$('input[name="normalform"]:checked', '#form').val();
		if( normalform === "form"){
				$("#tax_div").hide();
				$("#formtypes").show();
			}	
			else{
				$("#tax_div").show();
				$("#formtypes").hide();	
			}
			
		});
	$(".formtypeclass").click(function() {
		if($('#hform').is(':checked'))
		{
			$("#tax_div").hide();
		}
		else
		{
			if(($('#ct1').is(':checked')) || ($('#ct2').is(':checked'))) 
			{
				$("#tax_div").show();
			}
		}
	});


	
		
		

	
	
		
	
		
		$("#shipping_country_id").change(function(){			
			var stext = $('#shipping_country_id').find('option:selected').text().toLowerCase();
			if( stext === "india"){
				$(".ch1").hide();
				$(".ch2").hide();
				$(".ch3").show();	
			}	
			else{
				$(".ch1").show();
				$(".ch2").show();
				$(".ch3").hide();
			}
	    }).change();
    });
	


function showSize()
{
	var zipper_id=$("input[class='zipper']:checked").val();
	var product_id = $('#product').val();
	var size_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getProductSize', '',1);?>");
	$.ajax({
		type: "POST",
		url: size_url,					
		data:{product_id:product_id,zipper_id:zipper_id},
		success: function(json) {//alert(json);
			if(json){
				$("#size_div").html(json);//alert(product_id);
				$("#size option[value='0']").prop('selected',true);
				$("#size").prop('disabled',true);
				customSize();
			/*	if(product_id==10)
				{
					$("#size option[value='0']").prop('disabled',true);
				}*/
			}else{
				$("#size_div").html('<span class="btn btn-danger btn-xs">Please select Product for Size option.</span>');
			}
			$("#loading").hide();
		}
	});			
}
//STart : layer


function removePrintingEffect(){
	$('#effectdiv').empty();
}



function reloadPage(){
	location.reload();
}




function getcustomer_all_data(address_book_id)
{
		//alert(address_book_id);
		 var customer = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=customer_all_detail', '',1);?>");
					 $("#loading").css("visibility","visible");
					 $.ajax({
					   type: "POST",
					   url: customer,
					   data:{address_book_id:address_book_id},
					   success: function(msg){
							
							var msg = $.parseJSON(msg);
							 //console.log(msg);
							 
							 
							  /*if(msg.email_1 !='null')
								 $("#email").val(msg.email_1);
							  else
								$("#email").val('');*/
						
							 /* if(msg.c_address !='null')
								 $("#shipping_address_1").val(msg.c_address);
							  else
								  $("#shipping_address_1").val('');*/
						 
							  if(msg.factory_address_id !='null')
								  $("#factory_address_id").val(msg.factory_address_id);	
							/*  else
								 $("#factory_address_id").val('');		*/
				 
							  if(msg.company_address_id!='null')
								 $("#company_address_id").val(msg.company_address_id);
						/*	  else
								 $("#company_address_id").val('');	*/
							
							  if(msg.f_address!='null')
								 $("#billing_address_1").val(msg.f_address);
							/*  else
								  $("#billing_address_1").val('');	*/
								  
						/*	 if(msg.c_city!='null')
								 $("#shipping_city").val(msg.c_city);
							  else
								 $("#shipping_city").val('');	*/
							 if(msg.f_city!='null')
								 $("#billing_city").val(msg.f_city);
							 /* else
								 $("#billing_city").val('');*/	
							 if(msg.f_pincode!='null')
								 $("#billing_postcode").val(msg.f_pincode);
							/*  else
								 $("#billing_postcode").val('');	*/
							/* if(msg.c_pincode!='null')
								 $("#shipping_postcode").val(msg.c_pincode);
							  else
								 $("#shipping_postcode").val('');	*/														 
							
							 $("#vat_number").val(msg.vat_no);						
							
							
						
					   }
					 });
	
	}
	function generate()
	{
	    $('.choosen_data').each(function() {
			var id = $(this).attr('id');
			var select = $(this).attr('select');
			var value = $("#"+id+"").val();
			if($("#"+id+"").val()!='')
			{
				$("#"+id+"_chosen").attr('class','chosen-container chosen-container-single');
				$(".formError").remove();
			}
			else
			{
				if($("#"+select).prop("checked") == true)
				{
				    $("#"+id+"_chosen").attr('class','chosen-container chosen-container-single validate[required]');
				}
			}
		});
	}
</script> 
<!-- Close : validation script -->
<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>