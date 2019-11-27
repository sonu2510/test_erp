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
		
		if($_SESSION['LOGIN_USER_TYPE']=='1' && $_SESSION['ADMIN_LOGIN_SWISS']=='1'){
		    if(isset($post['product_quotation_price_id']) && !empty($post['product_quotation_price_id']))
			{
					//	printr($post);die;
				//	printr($post['product_quotation_price_id']);
				foreach ($post['product_quotation_price_id'] as $value) {
					//printr($value);
					$arr=array();
					$arr=explode('==', $value);
				//	printr($arr); 
				//	printr($post['product_code_id_'.$arr[0]]);
					
					if(empty($post['product_code_id_'.$arr[0]])){
					
						//printr($post['product_code_id_'.$arr[0]]);

						$obj_general->link($rout, '&mod=add&quotation_no='.encode($post['quotation_no_add']), '',1);
							$obj_session->data['success'] = 'Your Custom Order generated!';
					}else{
						//	echo 'hiii';die;
						   // printr($post);die;
							$multi_cust_id= $obj_custom_order->addQuotationToCustomOrder($post);
		                    page_redirect($obj_general->link($rout, '&mod=view&custom_order_id='.encode($multi_cust_id).'&filter_edit=0', '',1));
					}
				}

		
			
			}
			if(isset($post['quantity']) && !empty($post['quantity'])){
				page_redirect($obj_general->link($rout, '&mod=view&custom_order_id='.encode($_POST['multi_cust_id']), '',1));
			}else{
				$obj_session->data['warning'] = 'Error : Please fillup form carefully!';
			}
		    
		    
		    
		    
		}else{
		       // if($_SESSION['LOGIN_USER_TYPE']=='1' && $_SESSION['ADMIN_LOGIN_SWISS']=='1')
		          //  printr($post);die;
			//if((isset($post['product_quotation_price_id_air']) && !empty($post['product_quotation_price_id_air'])) && (isset($post['product_quotation_price_id_sea']) && !empty($post['product_quotation_price_id_sea'])))
			if(isset($post['product_quotation_price_id']) && !empty($post['product_quotation_price_id']))
			{
			    	$multi_cust_id= $obj_custom_order->addQuotationToCustomOrder($post);
				 page_redirect($obj_general->link($rout, '&mod=view&custom_order_id='.encode($multi_cust_id).'&filter_edit=0', '',1));
			}
			if(isset($post['quantity']) && !empty($post['quantity'])){
				page_redirect($obj_general->link($rout, '&mod=view&custom_order_id='.encode($_POST['multi_cust_id']), '',1));
			}else{
				$obj_session->data['warning'] = 'Error : Please fillup form carefully!';
			}
			
		}
	}
	
	if(isset($_POST['btn_rgenerate'])){
		$post = post($_POST);
			
		//printr($post);die;
		$last_id = $obj_custom_order->addRollQuotation($post);
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
		$insert_id = $obj_custom_order->addCustomOrder($postData);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	//die;
	}
	
	$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
	$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
	$userCurrency = $obj_custom_order->getUserCurrencyInfo($user_type_id,$user_id);
	//printr($userCurrency);
	$allow_currency_status = $obj_custom_order->allowCurrencyStatus($user_type_id,$user_id);
	$addedByInfo = $obj_custom_order->getUser($user_id,$user_type_id);
//	printr($addedByInfo);//die;
	if(isset($_GET['quotation_no']))
	{ 
		$quotation_id = $obj_custom_order->getQuotationId(decode($_GET['quotation_no']));
		$address_book_id = $obj_custom_order->getmulti_quation_id($quotation_id);
		$quota_detail = $obj_custom_order->getQuotaDetail($quotation_id);
		//printr($quota_detail);
	}
	$quota_detail['shipment_country_id']=$addedByInfo['country_id'];
	//echo $quotation_id;
	//$data = $obj_quotation->getQuotation($quotation_id,'',$obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);
//printr($address_book_id);

        if($addedByInfo['country_id']!='253'){
           // printr($addedByInfo);
    	    $user_address = $obj_custom_order->getUserAddress($user_id,$user_type_id);
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
                                     <input type="hidden" name="address_book_id" value="<?php if(isset($_GET['quotation_no'])){ echo $address_book_id['address_book_id']; }?>" id="address_book_id" >
                                      <input type="hidden" name="company_address_id" value="" id="company_address_id" >
                                       <input type="hidden" name="factory_address_id" value="" id="factory_address_id" >
                                </div>
                            </div>
                            
                            <div class="form-group" style="display:none; ">
                                <label class="col-lg-4 control-label">Website</label>
                                <div class="col-lg-7">
                                     <input type="text" name="website" value="" id="website" class="form-control">
                                </div>
                            </div>
                            
                            <div class="form-group" style="display:none; ">
                                <label class="col-lg-4 control-label">First Name</label>
                                <div class="col-lg-7">
                                     <input type="text" name="first_name" value="" class="form-control ">
                                </div>
                            </div>
                            
                            <div class="form-group" style="display:none; ">
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
								$selcurrency = $obj_custom_order->getdefaultcurrency();
								//printr($selcurrency);
								
								$currency = $obj_custom_order->getCurrencyList();
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
                            
                          
                            
                              <?php  if($addedByInfo['country_id'] != '42')
									{?>
        	                            <div class="form-group">
        	                                <label class="col-lg-4 control-label">VAT No </label>
        	                                <div class="col-lg-7">
        	                                     <input type="text" name="vat_number" value="" class="form-control ">
        	                                </div>
        	                            </div>
	                           <?php }?>
	                           
	                           
                           <?php 
              					if((isset($addedByInfo['country_id']) && ($addedByInfo['country_id']!='14' || $addedByInfo['country_id']!='214'|| $addedByInfo['country_id']!='42' || $addedByInfo['country_id']!='155' ))){
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
										$industrys = $obj_custom_order->getIndustrys();
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
                              	$tax_canada = $obj_custom_order->getTaxationCanada();?>
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
								   $countrys = $obj_custom_order->getCountries();
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
            							$adminId = $obj_custom_order->getEmpAdminId($_SESSION['ADMIN_LOGIN_SWISS']);
            							$adminCountryId = $obj_custom_order->getUser($adminId,$_SESSION['ADMIN_LOGIN_USER_TYPE']);
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
                              	$tax_canada = $obj_custom_order->getTaxationCanada();?>
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
                                        <?php $countrys = $obj_custom_order->getCountries();
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
      <!-- End of fixed div one time entry-->                  
		      <div class="col-lg-12" id="add-product-div">
             <!-- <h4><i class="fa fa-plus-circle"></i> Add Product</h4>
              <div class="line m-t-large" style="margin-top:-4px;"></div>
              <br />-->
                        <div class="form-group">
                            <label class="col-lg-3 control-label"><span class="required">*</span>Enter Quotation No.</label>
                            <div class="col-lg-8"><input type="text"  class="form-control validate[required] "  id="quotation_no" name="quotation_no" <?php if(isset($_GET['quotation_no']) && $_GET['quotation_no']!=''){?>disabled="disabled" value="<?php echo decode($_GET['quotation_no']);?>" <?php }else { echo 'value=""';}?> onchange="frm_fill();">
                            </div>
                        </div>
                      <?php /*?>  <div id="combination_div">
                      <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span> Select Product</label>
                        <div class="col-lg-8">
                            <?php
                            $products = $obj_custom_order->getActiveProduct();
                            ?>
                            <select name="product" id="product" class="form-control validate[required]">
                            <option value="">Select Product</option>
                                <?php
								//$post['product'] = 3;
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
             		<div id="gusset_printing"></div>         
                       <div class="form-group option">
                        <label class="col-lg-3 control-label">Valve</label>
                        <div class="col-lg-9">                
                        	<div  style="float:left;width: 200px;">
                                <label  style="font-weight: normal;">
                                  <input type="radio" name="valve[]" id="nv" value="0" checked="checked" >
                                 No Valve
                                 </label>
                             </div>
                             <div style="float:left;width: 200px;">
                                <label  style="font-weight: normal;">
                                  	<input type="radio" name="valve[]" id="wv" value="1" >
                              	With Valve
                                 </label>
                              </div> 
                        </div>
                      </div>
                      
                      <div id="zipper_div">
                       
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span> Select Size(WXHXG)</label>
                        <div class="col-lg-9" id="size_div" style="float: left; width: 30%;">
                        <span class="btn btn-danger btn-xs">Please select Product for Size option.</span>
                         </div>
                      </div>
                       <div class="form-group">
                        <label class="col-lg-3 control-label">Make Pouch</label>
                        <div class="col-lg-8">
                                <?php
                                $sel_make = array();
                                if(isset($material_make) && !empty($material_make) && $material_make){
                                    $sel_make = explode(',',$material_make);
                                }
                                //printr($sel_layer);die;
                                $makes = $obj_custom_order->getActiveMake();
                                //printr($effects);die;
                                foreach($makes as $make){
                                     echo '<div  style="float:left;width: 200px;">';
                                    echo '	<label  style="font-weight: normal;">';
                                    if(isset($sel_make) && in_array($make['make_id'],$sel_make)){
                                        echo '	<input type="radio" name="make" id="'.$make['make_id'].'" value="'.$make['make_id'].'" checked="checked" > ';
                                    }elseif($make['make_id']==1){
                                        echo '	<input type="radio" name="make" id="'.$make['make_id'].'" value="'.$make['make_id'].'" checked="checked"> ';
                                    }
									else{
									echo '	<input type="radio" name="make" id="'.$make['make_id'].'" value="'.$make['make_id'].'" > ';
									}
                                    echo '	 '.$make['make_name'].' </label>';
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                       <div style="display:none" id="customSize">
                      <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span>Width</label>
                        <div class="col-lg-3">                         
                             <input type="text" name="width" id="width"  value="<?php echo isset($post['width'])?$post['width']:'';?>" class="form-control validate[required,custom[onlyNumberSp]]" > 
                             <span id="widthsugg" style="color:blue;font-size:11px;"></span>                             
                        </div>
                         <div class="col-lg-3">
                          <a href="#"  id="mydiv" class="btn btn-info btn-xs">View Size Table</a>                               
                          </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label heightb"><span class="required">*</span>Height</label>
                        <div class="col-lg-3">
                             <input type="text" name="height" id="height" value="<?php echo isset($post['height'])?$post['height']:'';?>" class="form-control validate[required,custom[onlyNumberSp]]">
                        </div>
                      </div>
                      
                      <div class="form-group gusset">
                        <label class="col-lg-3 control-label"><span class="required">*</span>Gusset </label>
                        <div class="col-lg-7">
                             <div class="input-group">
                             	<input type="text" name="gusset" id="gusset_input" value="<?php echo isset($post['gusset'])?$post['gusset']:'';?>" class="form-control validate[required]">
                                	<span class="input-group-btn">
                                    	<button type="button" class="btn btn-danger"> <i class="fa fa-warning"></i> Please enter one side or single gusset only.</button>
                                    </span>  
                             </div> <span id="gussetsugg" style="color:blue;font-size:11px;"></span>   
                        </div>
                      </div>
                      </div>
                      <div class="form-group" >
                        <label class="col-lg-3 control-label"><span class="required">*</span> Select Printing Option</label>
                        <div class="col-lg-3">
                        	<?php $post['printing'] = 0;?>
                            <select name="printing" id="printing" class="form-control validate[required]">
                                <option value="1" <?php echo (isset($post['printing']) && $post['printing'] == 1)?'selected':'';?> >With Printing</option>
                                <option value="0" <?php echo (isset($post['printing']) && $post['printing'] == 0)?'selected':'';?>>Without Printing</option>
                            </select>
                        </div>
                      </div>
                      <div class="form-group" >
                        <label class="col-lg-3 control-label"><span class="required">*</span> Select Layer</label>
                        <div class="col-lg-3">
                            <?php
                            $layers = $obj_custom_order->getActiveLayer();
                            ?>
                            <select name="layer" id="layer" onChange="removePrintingEffect()" class="form-control validate[required]">
                               <option value="">Select Layer</option>
                                <?php
                                foreach($layers as $layer){
									if($layer['product_layer_id']==1)
										echo '<option value="'.encode($layer['product_layer_id']).'" selected="selected">'.$layer['layer'].'</option>';
									else
                                        echo '<option value="'.encode($layer['product_layer_id']).'">'.$layer['layer'].'</option>';
                                } ?>
                            </select>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Material</label>
                        <div class="col-lg-9" id="layerdiv"></div>
                      </div>
                      
                     <!-- Ruchi --> 
                      <div class="form-group" id="effectdiv"></div>
                 
                      <!-- Ruchi -->
                      
                      <div class="form-group quantity_in">
                        <label class="col-lg-3 control-label">Quantity In</label>
                        <div class="col-lg-3">
                             <select name="quantity_type" id="quantity_type" class="form-control">
                                <option value="meter">Meter</option>
                                <option value="kg">Kgs</option>
                                <option value="pieces">Pieces</option>
                            </select>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span>Quantity</label>
                        <div class="col-lg-9" id="squantity">
                        	<span class="btn btn-danger btn-xs">Please select material for quantity option.</span>
                        </div>
                      </div>
                      
                      <?php
                      $spouts = $obj_custom_order->getActiveProductSpout();
					  if($spouts){
						  ?>
                      	  <div class="form-group option">
                                <label class="col-lg-3 control-label">Spout</label>
                                <div class="col-lg-9">
                                   <?php
                                   $spoutsTxt = '';
                                    foreach($spouts as $spout){
                                       $spoutsTxt .= '<div  style="float:left;width: 200px;">';
                                            $spoutsTxt .= '<label  style="font-weight: normal;">';
											if($spout['product_spout_id'] == 1 )
											{
                                                $spoutsTxt .= '<input type="radio" name="spout[]" value="'.encode($spout['product_spout_id']).'" checked="checked">';
											}
											else 
											{
												$spoutsTxt .= '<input type="radio" name="spout[]" value="'.encode($spout['product_spout_id']).'" >';
											}
                                            $spoutsTxt .= ''.$spout['spout_name'].'</label>';
                                        $spoutsTxt .= '</div>';
                                    }
                                    echo $spoutsTxt;
                                    ?>
                                </div>
                            </div>
                      	  	<?php
					  	} ?>
                        
                        <?php
						  $accessories = $obj_custom_order->getActiveProductAccessorie();
						  if($accessories){
							  ?>
							  <div class="form-group option">
									<label class="col-lg-3 control-label">Accessorie</label>
									<div class="col-lg-9">
									   <?php
									   $accessorieTxt = '';
										foreach($accessories as $accessorie){
										   $accessorieTxt .= '<div style="float:left;width: 200px;">';
												$accessorieTxt .= '<label  style="font-weight: normal;">';
												if($accessorie['product_accessorie_id'] == 4 )
												{
													$accessorieTxt .= '<input type="radio" name="accessorie[]" value="'.encode($accessorie['product_accessorie_id']).'" checked="checked">';
												}
												else
												{
													$accessorieTxt .= '<input type="radio" name="accessorie[]" value="'.encode($accessorie['product_accessorie_id']).'">';
												}
													$accessorieTxt .= ''.$accessorie['product_accessorie_name'];
												$accessorieTxt .= '</label>';
											$accessorieTxt .= '</div>';
										}
										echo $accessorieTxt;
										?>
									</div>
								</div>
								<?php
							} ?>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Transportation</label>
                        <div class="col-lg-9">
                         	<div class="checkbox ch1" style="float: left; width: 30%;">
                                <label>
                                  <input type="checkbox" name="transpotation[]" value="<?php echo encode('air');?>" class="validate[minCheckbox[1]]" >
                                  By Air
                                 </label>
                             </div>
                             <div class="checkbox ch2" style="float: left; width: 30%;">
                                <label>
                                  <input type="checkbox" name="transpotation[]" value="<?php echo encode('sea');?>" class="validate[minCheckbox[1]]"  >
                                  By Sea
                                 </label>
                             </div>
                             <?php 
							 if($userCurrency['currency_code'] == 'INR'){
								 ?>
                             	 <div class="checkbox ch3" style="float: left;  width: 30%;">
                                    <label>
                                      <input type="checkbox" name="transpotation[]" value="<?php echo encode('pickup');?>" class="validate[minCheckbox[1]]">
                                      Factory Pickup
                                     </label>
                                 </div>
                             	 <?php
							 } else
							 { ?>
								 <div class="checkbox ch3" style="float: left; display:none;  width: 30%;">
                                    <label>
                                      <input type="checkbox" name="transpotation[]" value="<?php echo encode('pickup');?>"  class="validate[minCheckbox[1]]">
                                      Factory Pickup
                                     </label>
                                 </div>
								 <?php } ?>
                         </div>
                      </div>          
                      </div><?php */?>
                      <div style="display:none">
                           <div class="form-group">
                                    <label class="col-lg-3 control-label">Note<br /><small class="text-muted">(for internal purpose)</small></label>
                                    <div class="col-lg-8">
                                         <textarea name="product_note" class="form-control"></textarea>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Special Instruction <br /><small class="text-muted">(this will be displayed in order)</small></label>
                                    <div class="col-lg-8">
                                         <textarea name="product_special_instruction" class="form-control"></textarea>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                             	<label class="col-lg-3 control-label">DieLine <br/><small class="text-muted">(Only .pdf & .jpg format)</small></label>
                                <div class="col-lg-8">
                                	
                                    <div class="media-body">
                                        	<input type="file" name="die_line" id="die-line" title="<i class='fa fa-folder-open-o'></i> Browse" class="btn btn-sm btn-info m-b-small">
                                    </div>
                                    
                                    <div class="file-preview-die" style="display:none">
                                       <div class="file-preview-thumbnails-die">
                                            
                                       </div>
                                       <div class="clearfix"></div>
                                    </div>
                                    
                                    <div id="append-dieline"></div>
                                
                                </div>
                              </div>
                                
                                  
                          
                              
                              <div class="form-group">
                             	<label class="col-lg-3 control-label">Art Work</label>
                                <div class="col-lg-8">
                                	
                                    <div class="media-body">
                                        	<input type="file" name="art_image" id="art-image" title="<i class='fa fa-folder-open-o'></i> Browse" class="btn btn-sm btn-info m-b-small">
                                    </div>
                                    
                             		<div class="file-preview" style="display:none">
                                   		<div class="file-preview-thumbnails">
                                            
                                		</div>
                                   		<div class="clearfix"></div>
                                   		<div class="file-preview-status text-center text-success"></div>
                                   		<div class="kv-fileinput-error file-error-message" style="display: none;"></div>
                                	</div>
                                </div>
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
			checkZipper();
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
		
		$("#btn_add").click(function(){
			if($("#form").validationEngine('validate')){
			$("#shipping_country_id").attr('disabled',false);
			$("#customer_name").attr('disabled',false);
			$("#taxation").attr('disabled',false);
			$("#size").prop('disabled',false);
			$('#fixedDiv').find('input, textarea, button, select').attr('disabled',false);
			var formData = $("#form").serialize();
			var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=addCustomOrder', '',1);?>");			
			$.ajax({
					method: "POST",					
					url: url,
					data : {formData : formData},
					success: function(response){												
						$("#shipping_country_id").attr('disabled',true);
						$("#customer_name").attr('disabled',true);
						$("#taxation").attr('disabled',true);
						$('#fixedDiv').find('input, textarea, button, select').attr('disabled','disabled');
						$("#result").html(response);	
						$("#qoute_generate").show();					
					},
					error: function(){
							return false;	
					}
				});
			}
			else
			{
				return false;
			}
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

	$('#form input[name="make"]').on('change', function() {
			$(this).attr('checked', 'checked');
   			var makeid=$('input[name="make"]:checked', '#form').val(); 
			setLayerHtml($("#layer").val(),makeid);
		});
		
		var makeid = '';
			if ($('input[name="make"]').is(':checked'))
			{
				makeid= $('input[name="make"]:checked', '#form').val();
			}

		setLayerHtml($("#layer").val(),makeid);	
		$("#layer").change(function(){
			
			var layerid = $(this).val();
			var makeid = '';
			if ($('input[name="make"]').is(':checked'))
			{
				makeid = $('input[name="make"]:checked', '#form').val();				
			} 
				setLayerHtml(layerid,makeid);
		});	
		
		$(".quantity_in").hide();
		$("#product").change(function(){				//alert('sdf');
			var val = $(this).val();
			var text = $("#product option[value='"+val+"']").text().toLowerCase();
			var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getGussetPrinting', '',1);?>");
	$.ajax({
		type: "POST",
		url: url,					
		data:{val:val},
		success: function(json) {//alert(json);
				$("#gusset_printing").html(json);//alert(product_id);
		}
	});		

			if(val==10)
			{
				$("#wv").prop("disabled",true);
				$("#nv").prop('checked', 'checked');
				jQuery("input[name='spout[]']").each(function(i) {
					if($(this).val()!='MQ==')
						jQuery(this).prop("disabled",true);
					else
						$(this).prop('checked', 'checked');
				});
				jQuery("input[name='accessorie[]']").each(function(i) {
					if($(this).val()!='NA==')
						jQuery(this).prop("disabled",true);
					else
						$(this).prop('checked', 'checked');
				});
				$("#layer option").hide();
				$("#layer option[value='MQ==']").show();
				$("#layer option[value='MQ==']").attr("selected","selected");
			}
			else
			{
				$("#wv").prop("disabled",false);
				$("#layer option").show();
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
			}
			if(text === "roll"){
				$(".gusset").hide();
				$('#gusset_input').val("");
				$(".option").hide();
				$(".quantity_in").show();
				$(".heightb").html("Repeat Length");
				$("#btn_generate").attr('name','btn_rgenerate');
			}else{
				$(".gusset").show();
				$(".option").show();
				$(".quantity_in").hide();
				$(".heightb").html("Height");
				$("#btn_generate").attr('name','btn_generate');
			}			
			checkGusset();
			checkZipper();
			$('#effectdiv').html('');
			var zipper_id=$("input[class='zipper']:checked").val();
			showSize();
			var makeid = '';
			if ($('input[name="make"]').is(':checked'))
			{
				makeid= $('input[name="make"]:checked', '#form').val();
			}
		setLayerHtml($("#layer").val(),makeid);
		});
		$(".currencycls").hide();
		
		$("input[name=customer_check]").click(function(){
			$("#loading").show();
			if($(this).prop('checked') == true){
				var chtml = '';
				chtml += '<div class="form-group gusset">';
					chtml += '<label class="col-lg-3 control-label"><span class="required">*</span>Customer Email</label>';
					chtml += '<div class="col-lg-6">';
					chtml += '<input type="text" name="customer_email" value="" class="form-control validate[required,custom[email]]">';
					chtml += '</div>';
				chtml += '</div>';
				chtml += '<div class="form-group gusset">';
					chtml += '<label class="col-lg-3 control-label">Customer Gress ( % )</label>';
					chtml += '<div class="col-lg-3">';
						chtml += '<input type="text" name="customer_gress" value="" class="form-control validate[custom[onlyNumberSp]]">';
					chtml += '</div>';
				chtml += '</div>';
				$(".currencycls").show();
			}else{
				var chtml = '';
				$(".currencycls").slideUp();
			}
			$("#cinfo").slideUp().html(chtml).slideDown();
			$("#loading").fadeOut();
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
	
function print_change() {
	var makeid = '';
	if ($('input[name="make"]').is(':checked'))
	{
		makeid= $('input[name="make"]:checked', '#form').val();
	}
	setLayerHtml($("#layer").val(),makeid);	
}

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
function setLayerHtml(layer,make){//alert('sdf');	
	$("#loading").show();
	$('#layerdiv').html('');
	$.ajax({
		type: "POST",
		url: '<?php echo HTTP_ADMIN_CONTROLLER.$rout;?>/getLayerMaterial.php',
		dataType: 'json',
		data:{layer:layer,make:make}, 
		success: function(json) {
			$('#layerdiv').append(json);
			$("#squantity").html('<span class="btn btn-danger btn-xs">Please select material for quantity option.</span>');
			$("#loading").hide();
			var product_id = $('#product').val();
			if(product_id==10)
			{
				$("#material_1 option").hide();
				$("#material_1 option[value='16']").show();
				
			}
			else
			{
				$("#material_1 option").show();
				$("#material_1 option[value='16']").hide();
			}
			$("#layerdiv table tbody tr td:nth-child(2)").change(function(){
				var material_id = $("#layerdiv table tbody tr td:nth-child(2) select").val();
				var selectedVal = 0;
				var selected = $("input[type='radio'][name='printing_option_type[]']:checked");
				if (selected.length > 0) {
					selectedVal = selected.val();
				}
				
				var materialquantity_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getMaterialQuantity', '',1);?>");
				materialquantity_url=materialquantity_url+'&qty='+selectedVal;
				$.ajax({
					type: "POST",
					url: materialquantity_url,
					dataType: 'json',
					data:$("#layerdiv table tbody tr td:nth-child(2) select"),
					success: function(json) {//alert(json);
						if(json){
							$("#squantity").html(json);
							
						}else{
							$("#squantity").html('<span class="btn btn-danger btn-xs">Please select material for quantity option.</span>');
						}
						$("#loading").hide();
					}
				});
			});
		}
	});	
	checkGusset();
}	
//Close : Layer
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
function removePrintingEffect(){
	$('#effectdiv').empty();
}

function getMaterialThickness(material_id,id,layer){
	//ruchi
	var zipper_id=$("input[class='zipper']:checked").val();
	$("#loading").show();
	var status_url = '<?php echo HTTP_ADMIN_CONTROLLER.$rout;?>/getMaterialEffect.php';
	new_material_id = $('#material_1').val();
	$.ajax({			
		url : status_url,
		type :'post',
		data :{material_id:new_material_id,layer:layer},
		success: function(json){
			if(json != '0'){
				$('#effectdiv').html(json);
					var product_id = $('#product').val();
				if(product_id==10)
				{
					$("#printing_effect option").hide();
					$("#printing_effect option[value='1']").show();
				}
				else
				{
					$("#printing_effect option").show();				
				}
			}else{
				$('#effectdiv').html('');
			}
			$("#loading").hide();		
		}				
	});
	//ruchi
	$("#loading").show();
	$.ajax({
		type: "POST",
		url: '<?php echo HTTP_ADMIN_CONTROLLER.$rout;?>/getMaterialThickness.php',
		dataType: 'json',
		data:'material_id='+material_id,
		success: function(json) {
			$('#thickness-dropdown-'+id).html(json);
			var product_id = $('#product').val();
			//alert(product_id);
				if(product_id==10)
				{
					$("#thickness-dropdown-1 option").hide();
					$("#thickness-dropdown-1 option[value='60.000']").show();
					$("#thickness-dropdown-1 option[value='60.000']").attr('selected', 'selected');
				}
				else
				{
					if(zipper_id=='NQ==' && material_id==16)
					{
						$("#thickness-dropdown-"+id+" option[value='25.000']").hide();
					}
					$("#thickness-dropdown-1 option").show();
					var len = $('#thickness-dropdown-'+id+' option').length;
					if(len==2)
					{
						$('#thickness-dropdown-'+id+' option:eq(1)').attr('selected', 'selected');
					}		
				}
			$("#loading").hide();
		}
	});
}

function reloadPage(){
	location.reload();
}

$('#quantity_type').change(function(){
	$(".gusset").hide();
	$(".option").hide();
	$(".quantity_in").show();
	$(".heightb").html("Repeat Length");
	$("#btn_generate").attr('name','btn_rgenerate');
	setQuantityHtml('r');
});
function checkdiscount()
{
	var discount =$('#discount').val();
	var max_discount =$('#max_discount').val();
	if(discount>parseInt(max_discount))
	{
		$("#discount_error").html('<span class="btn btn-danger btn-xs">Discount Cannot be Greater then Max Discount.</span>');
		event.preventDefault();
	}
	else
	{
		$("#discount_error").html('');
	}
}
$( "form" ).submit(function( event ) {
  var discount =$('#discount').val();
	var max_discount =$('#max_discount').val();
	if(discount>parseInt(max_discount))
	{
		$("#discount_error").html('<span class="btn btn-danger btn-xs">Discount Cannot be Greater then Max Discount.</span>');
		event.preventDefault();
	}
	else
	{
		$("#discount_error").html('');
	}
});
var count=0;
$('.media-body').on('change','#art-image',function(){
	count += 1;
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajaximage', '',1);?>");
	$('#loading').show();
	var img_html = '';
	var file_data = $("#art-image").prop("files")[0];          // Getting the properties of file from file field
	var form_data = new FormData();                            // Creating object of FormData class
	form_data.append("file", file_data)              			// Appending parameter named file with properties of file_field to form_data
	form_data.append("product_id", $('#product').val())        // Adding extra parameters to form_data
	form_data.append("image_id",count)
	$.ajax({
		url: url,
		dataType: 'script',
		cache: false,
		contentType: false,
		processData: false,
		data: form_data,                         // Setting the data attribute of ajax with file_data
		type: 'post',
		success : function(response){
			if(response!=0){
				img_html += '<div id="preview-'+count+'" class="file-preview-frame">';
                  img_html +='<img class="file-preview-image" src="'+JSON.parse(response)+'">';
                  img_html += '<a class="iremove" href="javascript:void(0);" onClick="removeImage('+count+')">Remove</a>';      
                img_html += '</div>';
				$('.file-preview').show();
				$('.file-preview-thumbnails').append(img_html);
				$('#loading').remove();
			}else{
				$('#loading').remove();
			}
		}
   });
});

var die_count = 0;

$('.media-body').on('change','#die-line',function(){
	die_count += 1;
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=uploadDieLine', '',1);?>");
	var die_html = '';
	var file_data = $("#die-line").prop("files")[0];          // Getting the properties of file from file field
	var form_data = new FormData();                            // Creating object of FormData class
	form_data.append("file", file_data)              			// Appending parameter named file with properties of file_field to form_data
	form_data.append("product_id", $('#product').val())        // Adding extra parameters to form_data
	form_data.append("die_id",die_count)
	$.ajax({
		url: url,
		dataType: 'json',
		cache: false,
		contentType: false,
		processData: false,
		data: form_data,                         // Setting the data attribute of ajax with file_data
		type: 'post',
		success : function(response){
			if(typeof response.ext != 'undefined'){
				if(response.ext == 'img'){
					die_html = '<div id="die-preview-'+count+'" class="file-preview-frame">';
					  die_html +='<img class="file-preview-image" src="'+response.name+'">';
					  die_html += '<a class="iremove" href="javascript:void(0);" onClick="removefile('+die_count+')">Remove</a>';      
					die_html += '</div>';
					$('.file-preview-die').show();
					$('.file-preview-thumbnails-die').append(die_html);
					$('#loading').remove();
				}else{
					die_html = '<div id="die-preview-'+die_count+'" class="file-preview-frame">';
					  die_html +='<img class="file-preview-image" src="<?php echo HTTP_SERVER .'images/pdf_image.jpg'; ?>">';
					  die_html += '<a class="iremove" href="javascript:void(0);" onClick="removefile('+die_count+')">Remove</a>';      
					  die_html +='<div style="margin-top:8px;width:135px;">';
					    if((response.name).length>15){
					  	 	die_html +='<a href="javascript:void(0)" style="text-align:left;">'+(response.name).substring(0,15)+'..'+'</a>';
						}else{
							die_html +='<a href="javascript:void(0)" style="text-align:left;">'+(response.name)+'</a>';
						}
					  die_html +='</div>';
					die_html += '</div>';
					$('.file-preview-die').show();
					$('.file-preview-thumbnails-die').append(die_html);
					$('#loading').remove();
				}
			}else{
				$('#loading').remove();
				set_alert_message('Only .pdf And .jpg Formate Allow','alert-danger','fa fa-warning');
			}
		}
   });
});

function removefile(count){
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=removeFile', '',1);?>");
	$('#loading').show();
	$.ajax({
		url: url,
		data: {die_id : count},       
		type: 'post',
		success : function(){
			$('#loading').remove();
			$('#die-preview-'+count).remove();
			if($('.file-preview-die .file-preview-thumbnails-die').children().size()==0){
				$('.file-preview-die').css('display','none');	
			}
		}
	});
}

$('.addmore-image').click(function(){
	var total_count = parseInt( $(".more_image").size()) + 1;
	var html = '';	
	html += '<div class="row" style="margin-top:10px;" id="image-row-'+total_count+'">';
		html += '<div class="col-lg-9 media more_image">';  
		  html +=  '<div class="bg-light pull-left text-center media-large thumb-large" id="display-image-'+total_count+'">';
				html +='<img src= "<?php echo HTTP_SERVER.'images/blank-user64x64.png'; ?>" class="img-rounded" alt="">';
		  html += '</div>';
		  
		  html += '<div class="media-body">';
		    html += '<input type="file" name="art_image" id="art_image_'+total_count+'" title="Change" class="btn btn-sm btn-info m-b-small" />';	  
			html += '<button type="button" onClick="uploadImage('+total_count+')" class="btn btn-success btn-xs"><i class="fa fa-upload"></i> Upload</button>';
		  html += '</div>';
	    html +='</div>';
		
	   html +='<div class="col-lg-3">';
		 html +='<a class="btn btn-danger btn-xs btn-circle" data-toggle="tooltip" data-placement="top" onClick="removeImage('+total_count+');" title="Remove Image" ><i class="fa fa-minus"></i></a>';
	   html +='</div>';
	
   html +='</div>';
   $('#more-img-div').append(html);
});

/*function addalert() {
	var selections = [];
        // Show selections in next tab
	$("input[type='checkbox'][name='product_quotation_price_id[]']:checked").each(function() { 
		var value = $(this).val();
		
		//addalert
		//alert(value);
		var arr = value.split('==');
		if ($.inArray(arr[1], selections) < 0){
		   selections.push(arr[1]);
		   $('#cust_qty_'+arr[0]).show();
		}
		else
		{
			alert("You can't select same dimention Value");
			$(this).attr('checked', false);
		}
	});
	$("input[type='checkbox'][name='product_quotation_price_id[]']:not(:checked)").each(function() { 
		var value = $(this).val();
		var arr = value.split('==');
		$('#cust_qty_'+arr[0]).hide();
		$('#cust_qty_'+arr[0]).val('');
		$('#new_total_'+arr[0]).text('');
		update_record(arr[0]);
	});
}*/
function addQty(product_quotation_price_id)
{
	
	var discount = $('#cust_discount_'+product_quotation_price_id).val();
	$('#new_total_'+product_quotation_price_id).text('');
	var real_qty = $('#real_qty_'+product_quotation_price_id).val();
	var qty = $('#cust_qty_'+product_quotation_price_id).val();
	if(parseInt(qty) > parseInt(real_qty))
	{
		$('#cust_qty_'+product_quotation_price_id).val('');
		$('#new_total_'+product_quotation_price_id).text('');
		$("#cust_total_"+product_quotation_price_id).val('');
		alert("Please Enter Qty Less Than Real Qty");
	}
	else
	{
		var per_pouch_price = $('#per_pouch_price_'+product_quotation_price_id).val();
		var tot_perpouch_cal = per_pouch_price * qty ;
		var currency = $('#curr_'+product_quotation_price_id).val();
		if(discount!='0.000')
		{
			var t_dis = tot_perpouch_cal.toFixed(3)*discount/100;
			var tot = parseInt(tot_perpouch_cal.toFixed(3))-parseInt(t_dis);
			var dis = '<br><br><b>Order Total : </b>'+tot_perpouch_cal.toFixed(3)+
					  '<br><b>Discount('+discount+' %) : </b>'+t_dis.toFixed(3)+
					  '<br><b>Order Final Total : </b>'+(currency+' '+tot);
			$("#new_total_"+product_quotation_price_id).append(dis);
			$("#cust_total_"+product_quotation_price_id).val(tot_perpouch_cal.toFixed(3)-t_dis);	   
		}
		else
		{
			$("#new_total_"+product_quotation_price_id).append(currency+' '+tot_perpouch_cal.toFixed(3));
			$("#cust_total_"+product_quotation_price_id).val(tot_perpouch_cal.toFixed(3));
		}
	}
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
							 //console.log(msg.f_address);
							 //console.log(msg.f_address.length);
						//	 console.log(msg.f_address.size);
							 
							 
							  if(msg.email_1 !=null)
								 $("#email").val(msg.email_1);
							  else
								$("#email").val('');
						
							 /* if(msg.c_address !=null)
								 $("#shipping_address_1").val(msg.c_address);
							  else
								  $("#shipping_address_1").val('');*/
						 
							  if(msg.factory_address_id !=null)
								  $("#factory_address_id").val(msg.factory_address_id);	
							  else
								 $("#factory_address_id").val('');		
				 
							  if(msg.company_address_id!=null)
								 $("#company_address_id").val(msg.company_address_id);
							  else
								 $("#company_address_id").val('');	
							
							  if(msg.f_address!=null){
								 $("#shipping_address_1").val(msg.f_address);
								 	// console.log(msg.f_address);
							 } else{
								 // $("#billing_address_1").val('');	
							  }
							  
						/*	 if(msg.c_city!='null')
								 $("#shipping_city").val(msg.c_city);
							  else
								 $("#shipping_city").val('');	*/
							 if(msg.f_city!=null)
								 $("#shipping_city").val(msg.f_city);
							  else{
							//	 $("#billing_city").val('');	
							} if(msg.f_pincode!=null)
								 $("#shipping_postcode").val(msg.f_pincode);
							  else{
							//	 $("#billing_postcode").val('');	
							  }
							/* if(msg.c_pincode!=null)
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