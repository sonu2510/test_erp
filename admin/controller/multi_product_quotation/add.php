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
//sonu add 18-4-2017
$address_id='0';
$add_url='';
if(isset($_GET['address_book_id']))
{
    $address_id = decode($_GET['address_book_id']);
    $add_url = '&address_book_id='.$_GET['address_book_id'];
}
//end

$bradcums[] = array(
	'text' 	=> $display_name.' List',
	'href' 	=> $obj_general->link($rout, $add_url, '',1),
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
	
	//insert user
	if(isset($_POST['btn_generate'])){
		$post = post($_POST);		
	
			if(isset($post['quantity']) && !empty($post['quantity'])){
				///$last_id = $obj_quotation->addQuotation($post);
				/*if($last_id == "Error"){
					$obj_session->data['warning'] = 'Error : Please fillup form carefully!';
				}else{
					$obj_session->data['success'] = 'Your quotation generated!';*/
					page_redirect($obj_general->link($rout, '&mod=view&quotation_id='.encode($_POST['multi_quote_id']), '',1));
				//}
			}else{
				$obj_session->data['warning'] = 'Error : Please fillup form carefully!';
			}
	}
	
	if(isset($_POST['btn_rgenerate'])){
		$post = post($_POST);		
		$last_id = $obj_quotation->addRollQuotation($post);
		if($last_id == "Error"){
			$obj_session->data['warning'] = 'Error : Please fillup form carefully!';
		}else{
			$obj_session->data['success'] = 'Your quotation generated!';
			page_redirect($obj_general->link($rout, '&mod=view&quotation_id='.encode($last_id), '',1));
		}		
	}
	
	//Add quotation
	if(isset($_POST['btn_save'])){
		$postData = unserialize($obj_session->data['repost']);
		unset($obj_session->data['repost']);
		$insert_id = $obj_quotation->addQuotation($postData);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
	$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
	$userCurrency = $obj_quotation->getUserCurrencyInfo($user_type_id,$user_id);
	$allow_currency_status = $obj_quotation->allowCurrencyStatus($user_type_id,$user_id);
	$addedByInfo = $obj_quotation->getUser($user_id,$user_type_id);
	//printr($allow_currency_status);
	
	$thickness_permission = $obj_quotation->getMenuPermission('252',$user_id,$user_type_id);
	$thick_show='0';
	if($thickness_permission || ($user_type_id=='1' && $user_id=='1') )
		$thick_show='1';
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
                    <span class="text-muted m-l-small pull-right">
                    	Your base currency : <b><?php if($userCurrency){ echo $userCurrency['currency_code'];} else {echo 'INR';}?></b>
                    </span>
                  </header>
                  <div class="panel-body">
                    <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
                    
                  <?php if($user_type_id=='1' && $user_id=='1')
						{  $currencys = $obj_quotation->getNewCurrencys();
						    //print_r($_SESSION);
						        //currencycls
						?>
                           <!-- <label class="col-lg-3 control-label">Material</label>-->
                           <!--[kinjal] : change view for swisspac to set tool rate and cylinder rate.// 23-12-2015-->
                    	<div class="form-group">
                        	<label class="col-lg-3 control-label"></label>
                            <div class="col-lg-9">
                            	
                                <section class="panel">
                                 <div class="table-responsive">
                                    <table class="table table-striped b-t text-small">
                                      <thead>
                                        <tr>
                                        	 <th width="25%">Select Currency</th>
                                        	 <th>Currency Rate</th>
                                          	 <th>Tool Rate</th>
                                             <th>Cylinder Rate</th>
                                        </tr>
                                      </thead>
                                     <tbody>
                                     	<tr>
                                        	<td>
                                            	<select name="selcurrency" id="selcurrency" class="form-control" onchange="getCurrencyValue();">
                                                    <option value="">Select Currency</option>
                                                    <?php
                                                    if($currencys)
                                                    {
                                                          foreach($currencys as $currency)
														  {
                                                                    
                                                                   echo '<option value="'.$currency['currency_code'].'=='.$currency['currency_id'].'">'.$currency['currency_code'].'</option>';
                                                           } 
                                                    }
                                                    else
                                                    {
                                                       
                                                    ?>
                                                     <input type="hidden" name="else_curr_rate" id="else_curr_rate" value="1" />
                                                    <?php }?>
                                               </select>
                                            </td>
                                            <td>
                                            	 <?php 
												  if($currencys)
													{
													?>
														<input type="text" name="sel_currency_rate"  value=" "  id="sel_currency_rate" placeholder="Currency Rate" class="form-control validate[condRequired[sel_currency],custom[number]]">
													<?php 
                                                    }
                                                    else
                                                    {
                                                    ?>
														 <input type="text" name="sel_currency_rate"  id="sel_currency_rate"  placeholder="Currency Rate" class="form-control validate[condRequired[sel_currency],custom[number]]" value="1">
												<?php } ?>
                                            </td>
                                            <td>
                                            	<input type="text" name="swiss_tool_rate"  id="swiss_tool_rate"  placeholder="Tool Rate" class="form-control validate[required,custom[number]]" value="">
                                            </td>
                                            <td>
                                            	<input type="text" name="swiss_cylinder_rate"  id="swiss_cylinder_rate"  placeholder="Cylinder Rate" class="form-control validate[required,custom[number]]" value="">
                                            </td>
                                        </tr>
                                     </tbody>
                                  </table>
                              </div>
                            </section>
                           </div>
						 </div>
                         <!--end [kinjal]-->
			<?php	} $i=0;?>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span>(<?php $i=$i+1; echo $i; ?>) Customer Name</label>
                        <div class="col-lg-8">
                             <input type="text" name="customer" placeholder="Customer Name" value="<?php echo isset($post['customer']) ? $post['customer'] : '';?>" class="form-control validate[required]" id="customer_name">
                              <input type="hidden" name="address_book_id"  value="<?php echo isset($post['address_book_id']) ? $post['address_book_id'] : '';?>" id="address_book_id" class="form-control " />
                              <input type="hidden" name="company_address_id"  value="<?php echo isset($post['address_book_id']) ? $post['address_book_id'] : '';?>" id="company_address_id" class="form-control " />
                              <div id="ajax_return"></div>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span>(<?php $i=$i+1; echo $i; ?>)Email</label>
                        <div class="col-lg-4">
                             <input type="text" name="email" placeholder="Customer Email" value="<?php echo isset($post['email']) ? $post['email'] : '';?>" class="form-control validate[required]" id="email">                                      
                        </div>
                      </div>
                      
                      <div style="display:none">
                     <div class="form-group">
                        <label class="col-lg-3 control-label">Customer Email ?</label>
                        <div class="col-lg-8">
                        	<div class="checkbox">
                              <label>
                                <input type="checkbox" name="customer_check" value="1">
                                Yes i want to send email to customer !
                              </label>
                            </div>
                        </div>
                      </div>
                      <?php //echo "getCurresdsdfncys"; 
				 if($allow_currency_status){
						// echo "getCurrencys"; 
						  if($userCurrency){
							  $notGetCurrency = $userCurrency['currency_code'];
						  }else{
							  $notGetCurrency = 'INR';
						  }
						  $currencys = $obj_quotation->getCurrencys();
						 // printr($currencys);
						  if($currencys){
							?>
						        <div class="form-group currencycls">
								<label class="col-lg-3 control-label">(<?php $i=$i+1; echo $i; ?>)Select Currency</label>
								<div class="col-lg-9 row">
                                	<div class="col-lg-4">
                                        <select name="sel_currency" id="sel_currency" class="form-control">
                                            <option value="">Select Currency</option>
                                            <?php
                                      foreach($currencys as $currency){
												if($currency['currency_code'] != $notGetCurrency){
                                                	echo '<option value="'.encode($currency['currency_id']).'">'.$currency['currency_code'].'</option>';
												}
                                            } ?>
                                       </select>
                                    </div>
                                    <?php
									if($userCurrency['currency_code'] != 'INR'){
										?>
										<div class="col-lg-3">
											<input type="text" name="sel_currency_rate" placeholder="Currency Rate" class="form-control validate[condRequired[sel_currency],custom[number]]">
										</div>
										<?php
						  		} ?>
							</div>
							  </div>
						<?php
						 }
					  }
					  ?>
                      </div>
                      <div id="cinfo"></div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span>(<?php $i=$i+1; echo $i; ?>) Shipment Country</label>
                        <div class="col-lg-8">
                        	<?php
							$selCountry = ''; 
							if(isset($addedByInfo['country_id']) && $addedByInfo['country_id']){
								$selCountry = $addedByInfo['country_id'];
							}
							echo $obj_quotation->getCountryCombo($selCountry);//214?>
                        </div>
                      </div>
                    <?php
						if(isset($_SESSION['ADMIN_LOGIN_USER_TYPE']) && $_SESSION['ADMIN_LOGIN_USER_TYPE']==4)
						{
							$adminId = $obj_quotation->getEmpAdminId($_SESSION['ADMIN_LOGIN_SWISS']);
							$adminCountryId = $obj_quotation->getUser($adminId,$_SESSION['ADMIN_LOGIN_USER_TYPE']);
						}
						else
							$adminCountryId=$addedByInfo;
						if(isset($adminCountryId['country_id']) && $adminCountryId['country_id'] && $adminCountryId['country_id']==111){ 
                     if(isset($adminCountryId['discount']) and $adminCountryId['discount']>0.000)
					{ ?><div class="form-group">
                        <label class="col-lg-3 control-label">(<?php $i=$i+1; echo $i; ?>)Discount %</label>
                        <div class="col-lg-8">
                        	<input type="text" id="discount" name="discount" value="" class="form-control" onblur="checkdiscount()"/>
                            <input type="hidden" id="max_discount" name="max_discount" value="<?php echo isset($adminCountryId['discount'])?$adminCountryId['discount']:''; ?>" class="form-control"/>
                        <div id="discount_error"></div>
                        </div>
                      </div>
                      <?php }
					  }
					?>
                   <!-- <div class="form-group" id="normalform_div">
                        <label class="col-lg-3 control-label">(<?php $i=$i+1; echo $i; ?>) Taxation</label>
                        <div class="col-lg-8">
                                <?php /*
                                $sel_tax = array();
                                if(isset($tax) && !empty($tax) && $tax){
                                    $sel_tax = $tex;
                                }
                                    echo '<div><div>';
									echo '<label  style="font-weight: normal;">';                                 
									echo '<input type="radio" name="normalform" id="normalform" value="Normal" checked="checked" > ';								
									echo 'Normal </label></div><div >';
									echo '<label style="font-weight: normal;">';                                 
									echo '<input type="radio" name="normalform" id="normalform" value="form" > ';								
									echo 'Form </label></div></div>'; */
								?>
                            </div>
                        </div> 
                       <div class="form-group" id="tax_div">
                        <label class="col-lg-3 control-label">(<?php //$i=$i+1; echo $i; ?>) Zone</label>
                        <div class="col-lg-8">
                                <?php /*
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
                              */
                                ?>
                            </div>
                        </div>
                         <div class="form-group" id="formtypes">
                        <label class="col-lg-3 control-label">(<?php // $i=$i+1; echo $i; ?>) Form Types</label>
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
                      </div>  --> 
					  
					  <div class="form-group" id="tax_div">
                        <label class="col-lg-3 control-label">(<?php $i=$i+1; echo $i; ?>) Zone</label>
                        <div class="col-lg-8">
                                <?php 
                                $sel_tax = array();
                                if(isset($tax) && !empty($tax) && $tax){
                                    $sel_tax = $tex;
                                }
                                    
											echo '<div >';
											echo '	<label style="font-weight: normal;">';                                 
											echo '	<input type="radio" name="normalform" id="taxation" value="Out Of Gujarat" checked="checked" > ';								
										echo '	 Out Of Gujarat</label></div>
										<div  >';
									echo '	<label style="font-weight: normal;">';                                 
									echo '	<input type="radio" name="normalform" id="taxation" value="With In Gujarat" > ';								
                                    echo '	With In Gujarat </label>';
                                    echo '</div>';
                              
                                ?>
                            </div>
                        </div>  
                      <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span>(<?php $i=$i+1; echo $i; ?>) Select Product</label>
                        <div class="col-lg-8">
                            <?php
                            $products = $obj_quotation->getActiveProduct();
                            ?>
                            <select name="product" id="product" class="form-control validate[required]">
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
                      
                      <div id="Slider" class="form-group" style="display:none;">
                            <label class="col-lg-3 control-label">(<?php $i=$i+2; echo $i; ?>)Slider Required ?</label>
                            <div class="col-lg-9">                
                            	<div  style="float:left;width: 200px;">
                                    <label  style="font-weight: normal;">
                                      <input type="radio" name="slider" id="without_s" value="0" checked="checked" >
                                     Without Slider
                                     </label>
                                 </div>
                                 <div style="float:left;width: 200px;">
                                    <label  style="font-weight: normal;">
                                      	<input type="radio" name="slider" id="with_s" value="1" >
                                  	With  Slider
                                     </label>
                                  </div> 
                            </div>
                          </div>
                          
                          <div id="Slider_option" class="form-group" style="display:none;">
                            <label class="col-lg-3 control-label">(<?php $i=$i+2; echo $i; ?>)Slider Option</label>
                            <div class="col-lg-9">                
                            	
                            	<?php $zip = $obj_quotation->getActiveZippers();
                            	
                            	foreach($zip as $zips)
                            	{
                            	?>
                                    <div  style="float:left;width: 200px;">
                                        <label  style="font-weight: normal;">
                                          <input type="radio" name="slider_option" id="<?php echo $zips['product_zipper_id'];?>" value="<?php echo encode($zips['product_zipper_id']);?>" <?php if($zips['product_zipper_id']=='14') {  ?>checked="checked" <?php } ?> >
                                         <?php echo $zips['zipper_name'];?>
                                         </label>
                                     </div>
                                 
                                <?php } ?>
                            </div>
                          </div>
                          
                          <div id="silder_view" class="form-group" style="display:none;">
                            <label class="col-lg-3 control-label">(<?php $i=$i+2; echo $i; ?>)Slider Position</label>
                            <div class="col-lg-9">                
                            	
                            	<div  style="float:left;width: 200px;">
                                    <label  style="font-weight: normal;">
                                      <input type="radio" name="s_view" id="" value="Slider Zipper On Top Of Pouch">
                                     Slider Zipper On Top Of Pouch
                                     </label>
                                 </div>
                                 <div  style="float:left;width: 200px;">
                                    <label  style="font-weight: normal;">
                                      <input type="radio" name="s_view" id="" value="Slider Zipper Inside Of Pouch"  checked="checked" >
                                     Slider Zipper Inside Of Pouch
                                     </label>
                                 </div>
                                 
                                
                            </div>
                          </div>
                          
                          <div id="tamper" class="form-group" style="display:none;">
                            <label class="col-lg-3 control-label">(<?php $i=$i+2; echo $i; ?>)Temper Evident Slider Zipper Required?</label>
                            <div class="col-lg-9">                
                            	
                            	<div  style="float:left;width: 200px;">
                                    <label  style="font-weight: normal;">
                                      <input type="radio" name="tamperevident" id="tamperevident" value="Without Tamper Evident" checked="checked" >
                                     No
                                     </label>
                                 </div>
                                 <div  style="float:left;width: 200px;">
                                    <label  style="font-weight: normal;">
                                      <input type="radio" name="tamperevident" id="tamperevident" value="With Tamper Evident" >
                                     Yes
                                     </label>
                                 </div>
                                 
                                
                            </div>
                          </div>
                          
             		<div id="gusset_printing"></div>  

                       <div class="form-group option" id="valve_id" style="display:block;">
                        <label class="col-lg-3 control-label">(<?php $i=$i+2; echo $i; ?>) Valve</label>
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
                      
                      <!--<div id="gusset_printing"></div>         -->
                         
                          
                          
                       <div class="form-group option" id="tin_tie_id">
                        <label class="col-lg-3 control-label">(<?php $i=$i+1; echo $i; ?>) Tin Tie Option</label>
                        <div class="col-lg-9">                
                        	<div  style="float:left;width: 200px;">
                                <label  style="font-weight: normal;">
                                  <input type="radio" name="tin_tie[]" id="with_tt" value="1" class="tin_tie">
                                With Tin Tie
                                 </label>
                             </div>
                             <div style="float:left;width: 200px;">
                                <label  style="font-weight: normal;">
                                  	<input type="radio" name="tin_tie[]" id="without_tt" value="0" class="tin_tie">
                              	Without Tin Tie
                                 </label>
                              </div> 
                        </div>
                      </div>
                      
                      <div id="zipper_div" class="form-group"> 
                       
                      </div>
                      
                      <div id='laser_div' class="form-group" style="display:none;">
					  
					  
					
						<label class="col-lg-3 control-label">(<?php $i=$i+1; echo $i; ?>) Laser Scoring Required ?</label>
						
						<div class="col-lg-9">                
						<!--<label class="btn btn-black btn-xs "> </i><input type="checkbox" name="laser" id="top" checked="checked"></i> Laser Scorin On Top </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<label class="btn btn-black btn-xs "> </i><input type="checkbox" name="laser" id="side"></i> Laser Scorin On Side </label><br><br>
                        	-->
							<?php $lasers = $obj_quotation->getLaserScoring();
									foreach($lasers as $laser){ ?>
										 <div  style="float:left;width: 200px;">
											<label  style="font-weight: normal;">
											  
											  <input type="radio" name="laser_name" id="laser_name" value="<?php  echo $laser['type_id']; ?>"  <?php if($laser['type_id']=='1') {echo 'checked="checked"' ;} ?>class="">
											<?php echo $laser['laser_name'];?>
											 </label>
										 </div>

									<?php } ?>
                             
                        </div>
					  </div>
                         <div class="form-group" id="make_div">
                        <label class="col-lg-3 control-label">(<?php $i=$i+1; echo $i; ?>) Make Pouch</label>
                        <div class="col-lg-8" class="make_class">
                                <?php
                                $sel_make = array();
                                if(isset($material_make) && !empty($material_make) && $material_make){
                                    $sel_make = explode(',',$material_make);
                                }
                               
                                $makes = $obj_quotation->getActiveMake();
                              
                                foreach($makes as $make){
                                     echo '<div  style="float:left;width: 200px;">';
                                    echo '	<label  style="font-weight: normal;">';
                                    if(isset($sel_make) && in_array($make['make_id'],$sel_make)){
                                        echo '	<input type="radio" class="make" name="make" id="'.$make['make_id'].'" value="'.$make['make_id'].'"  onclick="showSize()" checked="checked"> ';
                                    }elseif($make['make_id']==1){
                                        echo '	<input type="radio" class="make" name="make" id="'.$make['make_id'].'" value="'.$make['make_id'].'" onclick="showSize()" checked="checked" > ';
                                    }
									else{
									echo '	<input type="radio" class="make" name="make" id="'.$make['make_id'].'" onclick="showSize()" value="'.$make['make_id'].'" > ';
									}
                                    echo '	 '.$make['make_name'].' </label>';
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                      
                       <div class="form-group" id="plastic_color" style="display:none;">
                        <label class="col-lg-3 control-label"><span class="required">*</span>(<?php $i=$i+1; echo $i; ?>) Color Of Plastic</label>
                        <div class="col-lg-9" style="float: left; width: 30%;">
                         <?php
                            $colors = $obj_quotation->getColors();
                            ?>
                         <select name="color" id="color" class="form-control validate[required]">
                            <option value="">Select Color</option>
                            
                             <?php
								
                                foreach($colors as $color){
                                    if(isset($post['color']) && $post['color'] == $color['plastic_color_id']){
                                        echo '<option value="'.$color['plastic_color_id'].'" selected="selected" >'.$color['color'].'</option>';
                                    }else{
                                        echo '<option value="'.$color['plastic_color_id'].'">'.$color['color'].'</option>';
                                    }
                                } ?>
                            
                            </select>
                         </div>
                      </div>
                      
                       <div class="form-group" id="size_mailer" style="display:none;">
                        <label class="col-lg-3 control-label">(<?php $i=$i+1; echo $i; ?>) Size Type</label>
                        <div class="col-lg-9">                
                        	<div  style="float:left;width: 200px;">
                                <label  style="font-weight: normal;">
                                  <input type="radio" name="size_mailer[]" id="size_mailer1" value="inch" class="size_mailer" onclick="showSize()" checked="checked">
                               <b>Size in inch</b>
                                 </label>
                             </div>
                             <div style="float:left;width: 200px;">
                                <label  style="font-weight: normal;">
                                  	<input type="radio" name="size_mailer[]" id="size_mailer2" value="mm" onclick="showSize()" class="size_mailer" >
                              	<b> Size in mm</b>
                                 </label>
                              </div> 
                        </div>
                      </div>
 
                       <div class="form-group" id="spout_pouch_id" style="display:none;">
                        <label class="col-lg-3 control-label">(<?php $i=$i+1; echo $i; ?>) Spout Pouch Type</label>
                        <div class="col-lg-9">                
                        	<div  style="float:left;width: 200px;">
                                <label  style="font-weight: normal;">
                                  <input type="radio" name="spout_pouch[]" id="spout_pouch1" value="Center" class="spout_pouch" onclick="showSize()" checked="checked">
                                Center Spout
                                 </label>
                             </div>
                             <div style="float:left;width: 200px;">
                                <label  style="font-weight: normal;">
                                  	<input type="radio" name="spout_pouch[]" id="spout_pouch2" value="Corner" onclick="showSize()" class="spout_pouch">
                              	Corner Spout
                                 </label>
                              </div> 
                        </div>
                      </div>
                      
                     <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span>(<?php $i=$i+1; echo $i; ?>)  Select Size(WXHXG)</label>
                        <div class="col-lg-9" id="size_div" style="float: left; width: 30%;">
                        <span class="btn btn-danger btn-xs">Please select Product for Size option.</span>
                         </div>
                      </div>
                       <div id="Handle_div" class="form-group" style="display:none;">
							<label class="col-lg-3 control-label">(<?php $i=$i+2; echo $i; ?>) Handle</label>
							<div class="col-lg-9">                
								<?php $handles = $obj_quotation->gethandle();
										foreach($handles as $handle){ ?>
											 <div  style="float:left;width: 200px;">
												<label  style="font-weight: normal;">
												  <input type="radio" name="handle_name" id="handle_name" value="<?php  echo $handle['handle_id']; ?>"  <?php if($handle['handle_id']=='1') {echo 'checked="checked"' ;} ?>class="">
												<?php echo $handle['handle_name'];?>
												 </label>
											 </div>

										<?php } ?>
									<span class="btn btn-danger btn-xs">Please select Handle Base On Your Size Selection.</span>	
							</div>
						</div>
                       <div style="display:none" id="customSize">
                      <div class="form-group">
                        <label class="col-lg-3 control-label widthtb"><span class="required">*</span>(<?php $i=$i+1; echo $i; ?>) Width</label>
                        <div class="col-lg-3">                         
                             <input type="text" name="width" id="width"  value="<?php echo isset($post['width'])?$post['width']:'';?>" class="form-control validate[required,custom[onlyNumberSp]]" > 
                             <span id="widthsugg" style="color:blue;font-size:11px;"></span>                             
                        </div>
                         <div class="col-lg-3">
                          <a href="#"  id="mydiv" class="btn btn-info btn-xs">View Size Table</a>                               
                          </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label heightb"><span class="required">*</span>(<?php $i=$i+1; echo $i; ?>) Height</label>
                        <div class="col-lg-3">
                             <input type="text" name="height" id="height" value="<?php echo isset($post['height'])?$post['height']:'';?>" class="form-control validate[required,custom[onlyNumberSp]]">
                        </div>
                      </div>
                      
                      <div class="form-group gusset">
                        <label class="col-lg-3 control-label"><span class="required">*</span>(<?php $i=$i+1; echo $i; ?>) Gusset </label>
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
                        <label class="col-lg-3 control-label"><span class="required">*</span>(<?php $i=$i+1; echo $i; ?>)  Select Printing Option</label>
                        <div class="col-lg-3">
                        	<select name="printing" id="printing" class="form-control validate[required]">
                                <option value="1" <?php echo (isset($post['printing']) && $post['printing'] == 1)?'selected':'';?> >With Printing</option>
                                <option value="0" <?php echo (isset($post['printing']) && $post['printing'] == 0)?'selected':'';?> >No Printing</option>
                              </select>
                        </div>
                        <span id="sugg" style="color:red;font-size:15px;"></span>
                      </div>
                      <div class="form-group" >
                        <label class="col-lg-3 control-label"><span class="required">*</span>(<?php $i=$i+1; echo $i; ?>)  Select Layer</label>
                        <div class="col-lg-3">
                            <?php
                            $layers = $obj_quotation->getActiveLayer();
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
                        <label class="col-lg-3 control-label">(<?php $i=$i+1; echo $i; ?>) Material</label>
                        <div class="col-lg-9" id="layerdiv"></div>
                      </div>
                      
                     <!-- Ruchi --> 
                      <div class="form-group" id="effectdiv"></div>
                 
                      <!-- kinjal -->
                      <div class="form-group" id="jery_msg" style="display:none;"><label class="col-lg-3 control-label"></label><div class="col-lg-3"><span class="btn btn-danger btn-xs">Our minimum quantity of stock size for jerky packaging is 15000 bags.
For custom size jerky bags our minimum quantity will be 50000 bags.</span></div></div>
                      <div class="form-group quantity_in">
                        <label class="col-lg-3 control-label">(<?php $i=$i+1; echo $i; ?>) Quantity In</label>
                        <div class="col-lg-3">
                             <select name="quantity_type" id="quantity_type" class="form-control">
                                <option value="">Select Quantity Type</option>
                                <option value="meter">Meter</option>
                                <option value="kg">Kgs</option>
                               <!-- <option value="pieces">Pieces</option>-->
                            </select>
                        </div>
                      </div>
                      
                      <div class="form-group" id="qty_div" >
                        <label class="col-lg-3 control-label"><span class="required">*</span>(<?php $i=$i+2; echo $i; ?>) Quantity</label>
                        <div class="col-lg-9" id="squantity">
                        	<span class="btn btn-danger btn-xs">Please select material for quantity option.</span>
                        	
                        </div>
                        <span id="div_for_mtr"></span>
                      </div>
                      
                      
                      <div class="form-group" style="display:none;" id="btn_mtr">
                        <div class="col-lg-9 col-lg-offset-3">
                      	  <button type="button" name="mtr_btn" id="mtr_btn" class="btn btn-primary" style="display:inline">Let's Calculate Kgs ? </button>	
                        </div>
                      </div>
                      <div class="form-group" id="note_id_mtr">
                         <div class="col-lg-9 col-lg-offset-3">
                            <span style="color:red;" id="note_mtr"></span> 
                         </div>
                      </div>
                      <!-- end kinjal -->
                      <div id="mtr_div">
                      
                        <?php
                      $spouts = $obj_quotation->getActiveProductSpout();
					  if($spouts){
						  ?>
                      	  <div class="form-group option" id="spout_div">
                                <label class="col-lg-3 control-label">(<?php $i=$i+1; echo $i; ?>) Spout</label>
                                <div class="col-lg-9">
                                   <?php
                                   $spoutsTxt = '';
                                    foreach($spouts as $spout){
                                       $spoutsTxt .= '<div  style="float:left;width: 200px;">';
                                            $spoutsTxt .= '<label  style="font-weight: normal;">';
											if($spout['product_spout_id'] == 1 )
											{
                                                $spoutsTxt .= '<input type="radio" class="spout" name="spout[]" value="'.encode($spout['product_spout_id']).'" checked="checked">';
											}
											else 
											{
												$spoutsTxt .= '<input type="radio" class="spout" name="spout[]" value="'.encode($spout['product_spout_id']).'" >';
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
						  $accessories = $obj_quotation->getActiveProductAccessorie();
						  //printr($accessories);
						  if($accessories){
							  ?>
							  <div class="form-group option" id="acce_div">
									<label class="col-lg-3 control-label">(<?php $i=$i+1; echo $i; ?>) Accessorie</label>
									<div class="col-lg-9">
									   <?php
									   $accessorieTxt = '';
										foreach($accessories as $accessorie){
										   $accessorieTxt .= '<div style="float:left;width: 200px;">';
												$accessorieTxt .= '<label  style="font-weight: normal;">';
												if($accessorie['product_accessorie_id'] == 4 )
												{
													//$accessorieTxt .= '<input type="radio" name="accessorie[]" value="'.encode($accessorie['product_accessorie_id']).'" checked="checked">';
													
													echo '<input type="radio" name="accessorie[]" value="'.encode($accessorie['product_accessorie_id']).'" checked="checked">'.' '.$accessorie['product_accessorie_name'].'<br>';
												}
												else
												{
												    if($accessorie['product_accessorie_id'] == 3 )
													{
														//$accessorieTxt .= '<input type="checkbox" name="accessorie[]" value="'.encode($accessorie['product_accessorie_id']).'">';
														echo '<br><br><input type="checkbox" name="accessorie[]" value="'.encode($accessorie['product_accessorie_id']).'">'.' '.$accessorie['product_accessorie_name'].'<br>';
													}
													else if($accessorie['product_accessorie_id'] == 8)
													{
														echo '<input type="checkbox" name="accessorie[]" value="'.encode($accessorie['product_accessorie_id']).'">'.' '.$accessorie['product_accessorie_name'].'<br><div class="acc_div"></div>';
													}
													else if($accessorie['product_accessorie_id'] == 9)
													{
														echo '<input type="checkbox" name="accessorie[]" value="'.encode($accessorie['product_accessorie_id']).'">'.' '.$accessorie['product_accessorie_name'].'<br><div class="acc_div"></div>';
													}
													else
													{
														
														//$accessorieTxt .= '<input type="radio" name="accessorie[]" value="'.encode($accessorie['product_accessorie_id']).'">';
														echo '<input type="radio" name="accessorie[]" value="'.encode($accessorie['product_accessorie_id']).'">'.' '.$accessorie['product_accessorie_name'].'<br>';
													}			
												}
													//$accessorieTxt .= ''.$accessorie['product_accessorie_name'].'<br>';
												$accessorieTxt .= '</label>';
											$accessorieTxt .= '</div>';
										}
										//echo $accessorieTxt.'<br>';
										?>
									</div>
								</div>
								<?php
							} ?>
                     
                      <div class="form-group">
                        <label class="col-lg-3 control-label">(<?php $i=$i+1; echo $i; ?>) Transportation</label>
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
                      
                   <div class="form-group">
                        <div class="col-lg-9 col-lg-offset-3">
                      	  <button type="button" name="btn_add" id="btn_add" class="btn btn-primary" style="display:inline">Add Item</button>	
                        <a class="btn btn-default" href="<?php echo $obj_general->link($rout, ''. $add_url, '',1);?>">Cancel</a>
                        </div>
                      </div>
                      <div id="result">                      	
                 		</div>
                        <div class="form-group">
                        <div class="col-lg-9 col-lg-offset-3" id="qoute_generate" style="display:none">
                         <button type="submit" name="btn_generate" id="btn_generate" class="btn btn-primary">Generate </button>	
                          </div>
                      </div>
                    </div>
                    </form>
                  </div>                
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
      </div>
    </div>

  </section>
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
	/*color:#575757;*/
	color:#575757;
	cursor : default;
}
</style>
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script>

	
 var windowSizeArray = [ "width=200,height=200",
                                "width=300,height=400,scrollbars=yes" ];
        $(document).ready(function(){
            
           
            $("input[type=radio][name='make'][value='9']").attr('disabled',true);
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
			var size_id = $('#size').val();
			var product_id = $('#product').val();
			if(product_id==6)
			    $('#mydiv').hide();
			else
			    $('#mydiv').show();
			if(product_id=='19' && size_id=='0')
			{
				//alert('0 size');
				$('#div_check_15000').hide();
				
			}
			else if(product_id=='19' && size_id!='0')
			{
				
				var re='';
				 re += '<div class="checkbox" style="float: left; width: 30%;" id="div_check_15000">';
						re += '<label id="15000">';
							re += '<input type="checkbox" name="quantity[]" value="MTUwMDA=" class="validate[minCheckbox[1]]">15000';
						re += '</label>';
					re += '</div>';
				//alert(re);
				$('#div_check_15000').html(re);
				$('#div_check_15000').show();
			}
		}
		
		
function getCurrencyValue(){
		
		var cur_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getCurrencyValue', '',1);?>");
		var currency_val = $('#selcurrency').val();
		var currency = currency_val.split('==');
		var currency_id= currency[1];
		if(currency_id!=''){
			$('#loading').show();
			$.ajax({
				url : cur_url,
				type :'post',
				data :{currency_id:currency_id},
				success: function(response){
					var else_curr_rate=$("#else_curr_rate").val();
					if(else_curr_rate=='1')
					{
						$('#sel_currency_rate').val(else_curr_rate);
					}
					else
					{
						$('#sel_currency_rate').val(response);
					}
					$('#loading').hide();								
				},
				error:function(){
					set_alert_message('Error!',"alert-warning","fa-warning");          
				}			
			});
		}else{
			$('#sel_currency_rate').val('');
		}
	}
		
$(document).ready(function() {
	$("input[type=radio][class='spout']").attr('disabled',true);
	$("input[type=radio][value='MQ==']").removeAttr('disabled','disabled');
	$("input[class=spout][value='MQ==']").prop('checked', 'checked');
	$('input[type=radio][class=tin_tie]').change(function() {
		
		var value = $(this).val();
		checkZipper(value);
		
	});
	$('input[type=radio][name=make]').change(function() {
		
		var value = $(this).val();
		if($("input[name=make][id='5']").is(':checked'))
		{
			$("input[type=radio][value='1'][id='wv']").attr('disabled',true);
			$("input[type=radio][name='zipper[]']").attr('disabled',true);
			//$("input[type=radio][name='accessorie[]']").attr('disabled',true);
			$("input[type=radio][name='zipper[]'][value='Mg==']").removeAttr('disabled',false);
			//$("input[type=radio][name='accessorie[]'][value='NA==']").removeAttr('disabled',false);
			$("input[type=radio][value='1'][id='with_tt']").attr('disabled',true);
			$("input[class=spout]").removeAttr('disabled',false);
			$("input[class=spout][value='MQ==']").attr('disabled',true);
			$("input[class=spout][value='Mg==']").prop('checked', 'checked');
			$("#spout_pouch_id").show();
		}
		else
		{	//$("input[type=radio][name='accessorie[]']").removeAttr('disabled',true);
			$("input[type=radio][name='zipper[]']").removeAttr('disabled',true);
			$("input[type=radio][value='1'][id='with_tt']").removeAttr('disabled',true);
			$("input[type=radio][value='1'][id='wv']").attr('disabled',false);
			$("input[class=spout]").attr('disabled',true);
			$("input[class=spout][value='MQ==']").removeAttr('disabled',false);
			$("input[class=spout][value='MQ==']").prop('checked', 'checked');
			$("#spout_pouch_id").hide();
		}
	    if($("input[name=make][id='9']").is(':checked'))
	    {
	        $("#layer option[value='MQ=='],#layer option[value='NA=='],#layer option[value='NQ==']").hide();
	        $("#layer option[value='Mg==']").attr("selected","selected");
	    }
	    else
	        $("#layer option[value='MQ=='],#layer option[value='NA=='],#layer option[value='NQ==']").show();                                                
		
	});

});
    jQuery(document).ready(function(){
		//$("#spout_pouch_id").hide();
			
		$("#layer option[value='MQ==']").attr("selected","selected");
        // binds form submission and fields to the validation engine
        jQuery("#form").validationEngine();
				
		$("#country_id").change(function(){
			var country_id = $(this).val();
			if(country_id == 111)
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
			$("#product").attr('disabled',false);
			$("#country_id").attr('disabled',false);
			$("#customer_name").attr('disabled',false);
			$("#taxation").attr('disabled',false);
			var formData = $("#form").serialize();
			var printing_type_val = $('input[type=radio][name="printing_option_type[]"]:checked').attr('data-val');
			//alert(printing_type_val);
			var printing_val=printing_type_val;
			if (typeof printing_type_val === "undefined") {
				printing_val='no gusset';
				
			}
			//alert(printing_val);
			//alert(formData);
			var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=addQuotation', '',1);?>");			
			$.ajax({
					method: "POST",					
					url: url,
					data : {formData : formData,printing_val:printing_val},
					success: function(response){	
						//alert(response);											
						$("#product").attr('disabled',true);
						$("#country_id").attr('disabled',true);
						$("#customer_name").attr('disabled',true);
						$("#taxation").attr('disabled',true);
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
	

				
	//})
	/*$('#form input[name="make"]').on('change',function () {
	
		var makepouch=$('input[name="make"]:checked', '#form').val();
		//alert(makepouch);
		
	});*/
	
	$('#form input[name="normalform"]').on('change', function() {
		var normalform=$('input[name="normalform"]:checked', '#form').val();
		if( normalform === "form"){
				$("#tax_div").hide();
				$("#formtypes").show();
				//var formtype=$('input[name="formtype"]:checked','#form').val();
				
				
				//var valff = $(".formtypeclass").val();
				
				
			}	
			else{
				$("#tax_div").show();
				$("#formtypes").hide();	
			}
			
		});
		
		function formtype(){
			
			
		}
	$(".formtypeclass").click(function() {
   //var id=$(this).id();
  
	//var testval=[];
				/*var valff = $(".formtypeclass:checked").each(function(){
					testval.push($(this).val());*/
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
				//});
				
 		
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
					
						//response = response.split("==");							
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
			
			if(makeid==2)
				$("#size option[value='0']").hide();
			else
				$("#size option[value='0']").show(); 
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
			//1alert(layerid);
			var makeid = '';
			if ($('input[name="make"]').is(':checked'))
			{
				makeid = $('input[name="make"]:checked', '#form').val();				
			} 
				setLayerHtml(layerid,makeid);
		});	
		
			$(".quantity_in").hide();
			
$("#product").change(function(){
			
			var val = $(this).val();
			//console.log(val);
			$("input[type=radio][name='make']").removeAttr('disabled',false);
			if(val!='22' && val!='20' && val!='3' && val!='7' && val!='1' && val!='5' && val!='4')
				$("input[type=radio][name='make'][id='6']").attr('disabled','disabled');	
			
			if(val=='3')
				$("input[class=zipper][id=1]").prop('checked', 'checked');
			
			var text = $("#product option[value='"+val+"']").text().toLowerCase();
			var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getGussetPrinting', '',1);?>");
			$.ajax({
				type: "POST",
				url: url,					
				data:{val:val},
				success: function(json) {
					//if(json){
						$("#gusset_printing").html(json);
					//}
				}
			});		
		if(val==6)
            $('#printing').find('option:contains(No Printing)').show(); //$("#printing option[value='0']").show();
          else
            $('#printing').find('option:contains(No Printing)').hide();
		
		//$("without_tt").attr('disabled',false);
			$("input[class=make][id=1]").prop('checked', 'checked');
		checkTintie();
		$("#spout_pouch_id").hide();
			$("#size_mailer").hide();
			$("#plastic_color").hide();
			//alert(val);
			//if(val==16)
			$("#tin_tie_id , #valve_id , #spout_div , #acce_div").addClass("option");	
			$("#tin_tie_id").show();
			$("#valve_id").show();
			$("#zipper_div").show();
			$("#make_div").show();
			$("#spout_div").show();
			$("#acce_div").show();
		//	$('#silder_view').hide();
			if(val=='3' || val=='7' || val=='4')
			{
			    $("#Slider").show();//alert('if');
			}
			else
			{
			    $("#Slider").hide();//alert('else');
			}    
			if(val=='19')
			{
			  $("#jery_msg").show();
			}
			else
			{
			   $("#jery_msg").hide();
			}
			if(val==10)
			{	$("#size_mailer").show();
				$("#plastic_color").show();
				
				$("#tin_tie_id , #valve_id , #spout_div , #acce_div").removeClass("option");	
				$("#tin_tie_id").hide();
				$("#valve_id").hide();
				$("#zipper_div").hide();
				$("#make_div").hide();
				$("#spout_div").hide();
				$("#acce_div").hide();
				
				$("input[type=radio][name='make']").attr('disabled','disabled');
				$("input[type=radio][name='make'][value='1']").removeAttr('disabled',false);
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
			else if(val==22)
			{
			    $("input[type=radio][name='make'][value='6']").prop('checked', 'checked');
			    $("input[type=radio][name='make']").attr('disabled','disabled');
			    $("input[type=radio][name='make'][value='6']").removeAttr('disabled',false);
			}
			/*else if(val==1 || val==7)
			{
				$("#without_tt").prop("disabled",false);
				$("#with_tt").prop("disabled",false);
				
			}*/
			else
			{
				//$("with_tt").attr('disabled',true);	
				//$("#without_tt").prop("disabled",false);		
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
			
			if(val=='5' || val=='20' || val=='7' || val=='22' || val=='3' || val=='4')
			{
				$("#laser_div").show();
			}
			else
				$("#laser_div").hide();
			
			if(val=='55' || val=='56' || val=='57')
				$("#Handle_div").show();
			else
				$("#Handle_div").hide();
			
			if(val=='3' || val=='7' || val=='4' || val=='5' || val=='1' || val=='9')
				$("input[type=radio][name='make'][value='9']").removeAttr('disabled',false);
			else
			    $("input[type=radio][name='make'][value='9']").attr('disabled',true);
			 
			if(val=='3' || val=='4') 
			    $("input[name='accessorie[]'][value='Mw==']").prop('checked', 'checked');
			else
			    $("input[name='accessorie[]'][value='Mw==']").prop('checked', false);
			
			//console.log(val);    
			if(val=='6'){
				$(".gusset").hide();
				$('#gusset_input').val("");
				$("#zipper_div").hide();
				$(".option").hide();
				$(".quantity_in").show();
				$(".heightb").html("Repeat Length");
				$(".widthtb").html("Roll Width");
				$("input[type=radio][name='make'][value='6']").removeAttr('disabled',false);
				//$("#btn_generate").attr('name','btn_rgenerate');
				//setQuantityHtml('r');
			}else{
				$(".gusset").show();
				//$(".zipper").show();
				$(".option").show();
				$(".quantity_in").hide();
				$(".heightb").html("(18) Height");
				$(".widthtb").html("Width");
				//$("#btn_generate").attr('name','btn_generate');
				//setQuantityHtml('p');
				/*if(val!='3' && val!='4' )
				    $("input[type=radio][name='make'][value='6']").attr('disabled',true);
				else*/
				    $("input[type=radio][name='make'][value='6']").removeAttr('disabled',false);
			}			
		// quad seal bag with make of pouch oxo	 
			/*if(val=='9'){
			    $("input[type=radio][name='make'][value='6']").removeAttr('disabled',false);  
			}*/
			checkGusset();
			//checkZipper();
			
			$('#effectdiv').html('');
			var zipper_id=$("input[class='zipper']:checked").val();
			//makePouch();
			showSize();
			var makeid = '';
			if ($('input[name="make"]').is(':checked'))
			{
				makeid= $('input[name="make"]:checked', '#form').val();
			}
			
		setLayerHtml($("#layer").val(),makeid);
		});
		//setQuantityHtml('p');
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
		
		$("#country_id").change(function(){			
			var stext = $('#country_id').find('option:selected').text().toLowerCase();
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
	
function checkTintie(){
	var gusset_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=checkProductTintie', '',1);?>");
	var product_id = $('#product').val();
	$.ajax({
		type: "POST",
		url: gusset_url,
		data:{product_id:product_id}, 
		success: function(response) {
			//alert(response);
			var val = $.parseJSON(response);
			if(val.response==0){
				$("#without_tt").prop('checked', 'checked');
				$("#with_tt").attr('disabled',true);
				
			}else{
				$("#without_tt").prop('checked', 'checked');
				$("#with_tt").attr('disabled',false);
				//$("#without_tt").attr('disabled',true);
				
			}
			if(val.result==0 || val.result===null)
			{
				
				$("input[class=make][id=5]").attr('disabled',true);
				//$("#spout_pouch_id").hide();
			}
			else
			{
				$("input[class=make][id=5]").attr('disabled',false);
				//$("input[class=make][id=5]").prop('checked', 'checked');
				//$("#spout_pouch_id").show();
				//$("#spout_pouch_id").hide();
				//$("input[class=spout][value='MQ==']").attr('disabled',true);
				//$("input[class=spout][value='Mg==']").prop('checked','checked');
				//var zip_id=$("input[class='zipper']:checked").attr('id');
			}
			checkZipper(res=0);
		}
	});
}

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
	//alert("show size");
	var zipper_id=$("input[class='zipper']:checked").val();
	var zip_id=$("input[class='zipper']:checked").attr('id');
	var product_id = $('#product').val();
	var size_type=$("input[class='size_mailer']:checked").val();
	//alert(size_type);
	$("#material_1").val('');
	var make_id=$("input[class='make']:checked").val();
	if(make_id==5 || make_id==4)
		{
			$("input[class=zipper][id=2]").prop('checked', 'checked');
			$("input[type=radio][value='0'][id='nv']").prop('checked', 'checked');
		}
	 else if(make_id==3)
	{
		//alert("got");
		$("input[name='accessorie[]'][value='Mw==']").prop('checked', 'checked');
	}
	else if(make_id==6)
	{
		//alert("got");make id 7 offline and 6 for online
		$("#material_2").attr("readonly","readonly");
		$("#layer option[value='Mg==']").attr("selected","selected");
		$("#material_1 option[value='7']").attr("selected","selected");
		$("#thickness-dropdown-1 option[value='52.000']").attr("selected","selected");
		$("#printing_effect option[value='2']").attr("selected","selected");
	}	
	else
	{
		$("input[name='accessorie[]'][value='Mw==']").removeAttr('checked', 'checked');
	}
	//[kinjal] on 14-6-2017	//online :MTY= & offline : MTU=
    if(zipper_id=='MTY=')
	{//alert('hi');
		$("input[name='accessorie[]'][value='OA==']").prop('checked', 'checked');
		$("input[name='accessorie[]'][value='OA==']").prop('disabled', 'true');
		$(".acc_div").html('<input type="hidden" name="accessorie[]" value="OA==">');
	}
	else
	{
		$("input[name='accessorie[]'][value='OA==']").removeAttr('checked', 'checked');
		$("input[name='accessorie[]'][value='OA==']").removeAttr('disabled', 'disabled');
		$(".acc_div").html("");
	}	
	var spout_pouch=$("input[class='spout_pouch']:checked").val();
	//var make_id=$("input[name='make']:checked").val();
	
	
	if(product_id==3 || product_id=='4')
	    $("input[name='accessorie[]'][value='Mw==']").prop('checked', 'checked');
	else
	    $("input[name='accessorie[]'][value='Mw==']").prop('checked', false);
	    
	var size_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getProductSize', '',1);?>");
	$.ajax({
		type: "POST",
		url: size_url,					
		data:{product_id:product_id,zipper_id:zipper_id,make_id:make_id,spout_pouch:spout_pouch,size_type:size_type},
		success: function(json) {
			if(json){
				$("#size_div").html(json);
				//if(product_id==16)
				if(product_id==10)
				{
					$("#size option[value='0']").prop('disabled',true);
				}
				/*if((zip_id==5 || zip_id==6 || zip_id==7) && (product_id==1 || product_id==7))
				{
					$("#size option[value='0']").hide();
				}*/
			}else{
				$("#size_div").html('<span class="btn btn-danger btn-xs">Please select Product for Size option.</span>');
			}
			$("#loading").hide();
		}
	});			
}

//STart : layer
function setLayerHtml(layer,make){
	//console.log(layer+'=='+make);
	$("#loading").show();
	$('#layerdiv').html('');
	$.ajax({
		type: "POST",
		url: '<?php echo HTTP_ADMIN_CONTROLLER.$rout;?>/getLayerMaterial.php',
		dataType: 'json',
		data:{layer:layer,make:make}, 
		success: function(json) {
			$('#layerdiv').append(json);
			//offline make id is 7 and online 6
			if(make=='6')
			{
				$("#material_1 option[value='7']").attr("selected","selected");
				getMaterialThickness(7,1,2);
				//offline material 2 id is 21 and online 23
				$("#material_2 option[value='23']").attr("selected","selected");
				$("#material_2").attr('disabled','disabled');
				getMaterialThickness(23,2,2);
				$("#layerdiv table tbody tr td:nth-child(1)").change();
				//$("#thickness-dropdown-1 option[value='52.000']").attr("selected","selected");
			}
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
			//added by kinjal on 8-8-2018 order by shishirsir
			if(make==1)
			    $("#material_1 option[value='7']").hide();
			else
			    $("#material_1 option[value='7']").show();
			if(product_id!=6)
			{
    			if(make=='6')
    			{
    				var lay_val = $('#material_2 option:selected').val() ;
    				if(lay_val == 23 )
    				getquantity();
    			
    			}
    			$("#layerdiv table tbody tr td:nth-child(2)").change(function(){
    				//alert($("#layerdiv table tbody tr td:nth-child(2) select"));
    					getquantity();
    				
    			});
			}
			else
			{
			    $("#layerdiv table tbody tr td:nth-child(2)").change(function(){
    				//alert($("#layerdiv table tbody tr td:nth-child(2) select"));
    					getRollquantity();
    				
    			});
			}
			if(make=='9')
			{//kinjal made this condition on (18-4-2019) order by prashant sir
			    if(layer=='Mw==')
			        $("#material_2 option[value='16']").hide();
			    else if(layer=='Mg==')
			        $("#material_2 option[value='31'],#material_2 option[value='32']").hide();
			}
		}
	});	
	checkGusset();
}	


function getquantity()
{
	
	var product_id = $('#product').val();
	var size_id = $('#size').val();
	var material_id = $("#layerdiv table tbody tr td:nth-child(2) select").val();
	//console.log(material_id);
				var selectedVal = 0;
				var selected = $("input[type='radio'][name='printing_option_type[]']:checked");
				if (selected.length > 0) {
					selectedVal = selected.val();
				}
				
				var makeid= $('input[name="make"]:checked', '#form').val();
				if(makeid==6)
				    selectedVal='10000';
				 //console.log(selectedVal);   
				var zipper_id=$("input[class='zipper']:checked").val();
				var zip_id=$("input[class='zipper']:checked").attr('id');
				
				var materialquantity_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getMaterialQuantity', '',1);?>");
				materialquantity_url=materialquantity_url+'&qty='+selectedVal;//console.log(materialquantity_url+'&qty='+selectedVal);  
				$.ajax({
					type: "POST",
					url: materialquantity_url,
					dataType: 'json',
					data:$("#layerdiv table tbody tr td:nth-child(2) select"),
					success: function(json) {
						//console.log(materialquantity_url);  
						if(json){
							$("#squantity").html(json);
							if(zip_id=='3')
							{
								//$("input[type=checkbox][value='MTAwMDA='][name='quantity[]']").hide();
								//$("input[type=checkbox][value='MTUwMDA='][name='quantity[]']").hide();
								//kinjal commented on 9-2-2018 order by shishirsir (steve want quo for 10000 qty)
								//$('label[id="10000"]').hide();
								//$('label[id="15000"]').hide();
							}
							else if(zip_id=='4')
							{
								//$("input[type=checkbox][value='MTAwMDA='][name='quantity[]']").show();
								//$("input[type=checkbox][value='MTUwMDA='][name='quantity[]']").show();
								$('label[id="10000"]').hide();
								$('label[id="15000"]').hide();
								$('label[id="20000"]').hide();
								$('label[id="30000"]').hide();
									
							}
							else
							{
									$('label[id="10000"]').show();
									$('label[id="15000"]').show();
									$('label[id="20000"]').show();
									$('label[id="30000"]').show();
							
							}
							if(product_id=='10')
							{
								$('label[id="10000"]').hide();
							}
							if(product_id=='19' && size_id=='0')
							{
								//alert('0 layer');
								$('#div_check_15000').hide();
								
							}
							else if(product_id=='19' && size_id!='0')
							{
								$('#div_check_15000').show();//alert('1 layer');
							} 
						}else{
							$("#squantity").html('<span class="btn btn-danger btn-xs">Please select material for quantity option.</span>');
						}
						$("#loading").hide();
					}
				});


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

/*function checkZipper(){
	//alert(tintie);

		var gusset_url = getUrl("<?php //echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=checkProductZipper', '',1);?>");
		var product_id = $('#product').val();
		$.ajax({
			type: "POST",
			url: gusset_url,
			dataType: 'json',
			data:{product_id:product_id}, 
			success: function(response) {
				$('#zipper_div').html(response);	
				if(product_id==1 || product_id==7)
				{
					$("input[id=5]").attr('disabled',false);
					$("input[id=6]").attr('disabled',false);
					$("input[id=7]").attr('disabled',false);
				}
				else{
					$("input[id=5]").parent().hide();
					$("input[id=6]").parent().hide();
					$("input[id=7]").parent().hide();
				}
			}
		});
}*/
function checkZipper(tintie){
		//alert(tintie);
		var gusset_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=checkProductZipper', '',1);?>");
		var product_id = $('#product').val();
		$.ajax({
			type: "POST",
			url: gusset_url,
			dataType: 'json',
			data:{product_id:product_id, tintie:tintie}, 
			success: function(response) {
				//console.log(response);
				$('#zipper_div').html(response);
				showSize();
				
				if(product_id=='3' || product_id=='7' || product_id=='4')
				{
				    $("input[type=radio][name='zipper[]'][id='16']").prop('disabled', true);
    	            $("input[type=radio][name='zipper[]'][id='14']").prop('disabled', true);
				}
				if(product_id=='3' || product_id=='4')
				    $("input[type=radio][name='zipper[]'][id='17']").prop('disabled', false);
				else
				    $("input[type=radio][name='zipper[]'][id='17']").prop('disabled', true);
			}
		});
}


jQuery(document).ready(function(){
		 	
		  $("#without_tt").prop('checked','checked');
			var tin =$("#without_tt").prop('checked','checked').val();
			checkZipper(tin);
			$("without_tt").attr('disabled',false);
			$("with_tt").attr('disabled',false);
		 
});

function removePrintingEffect(){
	$('#effectdiv').empty();
}

function getMaterialThickness(material_id,id,layer){
	//ruchi
	var make_id=$("input[class='make']:checked").val();
	//alert(make_id);
	$("#quantity_type").val('');
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
				if(make_id == '6')
					$("#printing_effect option[value='2']").attr("selected","selected");
				
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
				
				var first_material = $("#material_1").val();
			//alert(zipper_id+'=='+material_id+'=='+first_material)
			if(first_material==6)
			{
					var materialid = $("#material_3").val();
			        var materialid = $("#material_3").val();
					if(materialid==16)
					{
						var thick_show = <?php echo $thick_show;?> ;
						if(thick_show=='1')
						{
							$("#thickness-dropdown-3 option[value='25.000']").show();
							$("#thickness-dropdown-3 option[value='60.000']").show();
						}
						else
						{	
							$("#thickness-dropdown-3 option[value='25.000']").hide();
							$("#thickness-dropdown-3 option[value='60.000']").hide();
						}
					}
					else
					{
						$("#thickness-dropdown-3 option[value='25.000']").show();
						$("#thickness-dropdown-3 option[value='60.000']").show();
					}
			}
			else
			{
				$("#thickness-dropdown-3 option[value='25.000']").show();
				$("#thickness-dropdown-3 option[value='60.000']").show();
			
			}
						
			$("#loading").hide();
		}
	});
}

function reloadPage(){
	location.reload();
}



function checkdiscount()
{
	var discount =$('#discount').val();
	var max_discount =$('#max_discount').val();
	if(discount>parseInt(max_discount))
	{
		$("#discount_error").html('<span class="btn btn-danger btn-xs">Discount Cannot be Greater then Max Discount.</span>');
		event.preventDefault();
		//alert('Discount Cannot be Greater then Max Discount.');
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
		//alert('Discount Cannot be Greater then Max Discount.');
		$("#discount_error").html('<span class="btn btn-danger btn-xs">Discount Cannot be Greater then Max Discount.</span>');
		event.preventDefault();
	}
	else
	{
		$("#discount_error").html('');
	}
});


$("#customer_name").focus();
	var offset = $("#customer_name").offset();
	var width = $("#holder").width();
	$("#ajax_return").css("width",width);
	
	$("#customer_name").keyup(function(event){		
		 var keyword = $("#customer_name").val();
		// alert(keyword);
		 if(keyword.length>='3')
		 {
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
				 // alert(msg);
				 	
				   var div='<ul class="list">';
				   
					if(msg.length>0)
					{
						for(var i=0;i<msg.length;i++) 
						{	
							//div =div+'<li><a href=\'javascript:void(0);\' id="'+msg[i].id+'" consignee="'+msg[i].address+'" deladd="'+msg[i].delivery_address+'"vat_no="'+msg[i].tin_no+'" email="'+msg[i].email+'"><span class="bold" >'+msg[i].name+'</span></a></li>';
								div =div+'<li><a href=\'javascript:void(0);\' id="'+msg[i].address_book_id+'"  email="'+msg[i].email_1+'" country_id="'+msg[i].country+'" company_address_id="'+msg[i].company_address_id+'" ><span class="bold" >'+msg[i].company_name+'</span></a></li>';
						}
					}
					
					div=div+'</ul>';
					//console.log(div);
					if(msg != 0)
					  $("#ajax_return").fadeIn("slow").html(div);
					else
					{
					  $("#ajax_return").fadeIn("slow");	
					  $("#ajax_return").html('<div style="text-align:left;">No Matches Found</div>');
					  $("#address_book_id").val('');
					  $("#email").val('');
					  //$("#country_id").val('');
					 $("#company_address_id").val('');
						
						
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
							$("#customer_name").val($(".list li[class='selected'] a").text());
							$("#address_book_id").val($(".list li[class='selected'] a").attr("id"));
							$("#company_address_id").val($(".list li[class='selected'] a").attr("company_address_id"));
							$("#email").val($(".list li[class='selected'] a").attr('email'));
						//	$("#country_id").val($(".list li[class='selected'] a").attr('country_id'));
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
							$("#customer_name").val($(".list li[class='selected'] a").text());                  			
							$("#address_book_id").val($(".list li[class='selected'] a").attr("id"));
							$("#company_address_id").val($(".list li[class='selected'] a").attr("company_address_id"));
							$("#email").val($(".list li[class='selected'] a").attr('email'));
							//$("#country_id").val($(".list li[class='selected'] a").attr('country_id'));
							
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
	
	$('#customer_name').keydown( function(e) {
		if (e.keyCode == 9) {
			 $("#ajax_return").fadeOut('slow');
			 $("#ajax_return").html("");
		}
	});

	$("#ajax_return").mouseover(function(){
				$(this).find(".list li a:first-child").mouseover(function () {
					  $("#address_book_id").val($(this).attr("id"));
					  $("#email").val($(this).attr("email"));
					  //$("#country_id").val($(this).attr("country_id"));
					  $("#company_address_id").val($(this).attr("company_address_id"));
					  $(this).addClass("selected");
				});
				$(this).find(".list li a:first-child").mouseout(function () {
					  $(this).removeClass("selected");
					  $("#address_book_id").val('');
					 //$("#country_id").val('');
					   $("#company_address_id").val('');
					 $("#email").val('');
				});
				$(this).find(".list li a:first-child").click(function () {
					  
					  $("#address_book_id").val($(this).attr("id"));
					  $("#email").val($(this).attr("email"));
					 // $("#country_id").val($(this).attr("country_id"));
					  $("#company_address_id").val($(this).attr("company_address_id"));
					  $("#customer_name").val($(this).text());
					  $("#ajax_return").fadeOut('slow');
					  $("#ajax_return").html("");
					
				});
				
			});
/*function makePouch(){
	
	var product_id = $('#product').val();
	//alert(product_id);
	
	var make_url = getUrl("<?php //echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getMakePouch', '',1);?>");
	$.ajax({
		type: "POST",
		url: make_url,					
		data:{product_id:product_id},
		success: function(json) {
				//alert(json);
				$(".make_class").html(json);
		}
		
	})	
}*/ 
	$("#printing").change(function(){
        var val = $(this).val();
        if(val==0)
            $('#sugg').html('Single color print like red, black, white colors are counted as with printing option');
        else
            $('#sugg').html('');
	});
	//kinjal made on 23-5-2018
	$('input[type=radio][name=slider]').change(function() {
         //$('#zipper_div').hide();
        var val = $(this).val();
       console.log(val);
        $('#laser_div').hide();
        if(val==0)
        {
            //$("#acce_div").show();
            $("#zipper_div").show();
            $("#tin_tie_id").show();
            $("#spout_div").show();
            $('#Slider_option').hide();
            $('#silder_view').hide();
            $("input[type=radio][name='make'][value='3']").attr('disabled', false);
            $("input[type=radio][name='make'][value='4']").attr('disabled', false);
            $("input[type=radio][name='make'][value='5']").attr('disabled', false);
            $("input[type=radio][name='make'][value='6']").attr('disabled', false);
            $("input[type=radio][name='make'][value='7']").attr('disabled', false);
            $("input[type=radio][name='zipper[]'][id='2']").prop('checked', false);
            $("input[type=radio][name='zipper[]'][id='1']").prop('checked', true);
            
            $("input[type=radio][name='slider_option'][id='14']").prop('checked', true);
            
            $("input[name='accessorie[]'][value='OQ==']").removeAttr('checked', 'checked');
    		$("input[name='accessorie[]'][value='OQ==']").removeAttr('disabled', 'disabled');
            $("input[name='accessorie[]'][value='OA==']").removeAttr('checked', 'checked');
    		$("input[name='accessorie[]'][value='OA==']").removeAttr('disabled', 'disabled');
    		$(".acc_div").html("");
    		$("input[type=radio][name='zipper[]'][id='16']").prop('disabled', true);
    		$("input[type=radio][name='zipper[]'][id='14']").prop('disabled', true);
    		$("input[type=radio][name='laser_name'][value='1']").prop('checked', true);
    		$("input[type=radio][name='laser_name'][value='2']").prop('checked', false);
        }
        else
        {
             //$("#acce_div").hide();
            $("#zipper_div").hide();
            $("#tin_tie_id").hide();
            $("#spout_div").hide();
            $('#silder_view').show();
            $('#Slider_option').show();
            $("input[type=radio][name='make'][value='3']").attr('disabled', true);
            $("input[type=radio][name='make'][value='4']").attr('disabled', true);
            $("input[type=radio][name='make'][value='5']").attr('disabled', true);
            $("input[type=radio][name='make'][value='6']").attr('disabled', true);
            $("input[type=radio][name='make'][value='7']").attr('disabled', true);
            $("input[type=radio][name='zipper[]'][id='1']").prop('checked', false);
            $("input[type=radio][name='zipper[]'][id='2']").prop('checked', true);
            
            $("input[type=radio][name='slider_option'][id='14']").prop('checked', true);
            
            $("input[name='accessorie[]'][value='OA==']").prop('checked', 'checked');
		    $("input[name='accessorie[]'][value='OA==']").prop('disabled', 'true');
		    $(".acc_div").html('<input type="hidden" name="accessorie[]" value="OA==">');
            
        }
        $('input[type=radio][name=s_view]').change();
	});
	
	$('input[type=radio][name=slider_option]').change(function() {
	    var val = $(this).val();
	    if(val=='MTQ=')
	    {
	        $(".acc_div").html("");
	        $("input[name='accessorie[]'][value='OQ==']").prop('checked', false);
	        $("input[name='accessorie[]'][value='OA==']").prop('checked', 'checked');
    		$("input[name='accessorie[]'][value='OA==']").prop('disabled', 'true');
    		$(".acc_div").html('<input type="hidden" name="accessorie[]" value="OA==">');
	    }
	    else
	    {
	        $(".acc_div").html("");
	        $("input[name='accessorie[]'][value='OA==']").prop('checked', false);
	        $("input[name='accessorie[]'][value='OQ==']").prop('checked', 'checked');
    		$("input[name='accessorie[]'][value='OQ==']").prop('disabled', 'true');
    		$(".acc_div").html('<input type="hidden" name="accessorie[]" value="OQ==">');
	    }
	});
	$('input[type=radio][name=s_view]').change(function() {
        
        var val = $(this).val();
        //alert(val);
        if(val=='Slider Zipper Inside Of Pouch')
        {
           $("input[type=radio][name='laser_name'][value='1']").prop('checked', false);
           $("input[type=radio][name='laser_name'][value='2']").prop('checked', true);
           $('#tamper').hide();
           $("input[type=radio][name='tamperevident'][value='Without Tamper Evident']").prop('checked', true);
        }
        else
        {
           $("input[type=radio][name='laser_name'][value='2']").prop('checked', false);
           $("input[type=radio][name='laser_name'][value='1']").prop('checked', true);
//           $("input[type=radio][name='tamperevident'][value='Without Tamper Evident']").prop('checked', true);
           $('#tamper').show();
        }    
        
	});
    $('#quantity_type').change(function(){
    //	alert('hii');
    	$(".gusset").hide();
    	$(".option").hide();
    	var qty_type = $(this).val();
    	$(".heightb").html("Repeat Length");
    	$(".widthtb").html("Roll Width");
    	//$("#btn_generate").attr('name','btn_rgenerate');
    	//setQuantityHtml('r');
    	//getquantity();
    	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getRollQty', '',1);?>");
    		var qty_type = $('#quantity_type').val();
    		$.ajax({
    			type: "POST",
    			url: url,
    			dataType: 'json',
    			data:{qty_type:qty_type}, 
    			success: function(response) {
    				if(qty_type=='meter')
    			    {   
    				    $("#btn_mtr").show();
    				    $("#mtr_tbox").show();
    				    $("#mtr_div").hide();
    			    }
    				else
    				{
    				    $("#mtr_div").show();
    				    $("#btn_mtr").hide();
    				    $("#mtr_tbox").hide();
    				    $("#note_mtr").html('');
    				}
    				 $("#squantity").html(response);
    			}
    		});
    	
});
$('#btn_mtr').click(function(){
   $("#mtr_div").hide();
   if($("#form").validationEngine('validate'))
   {
        var printing_type_val = $('input[type=radio][name="printing_option_type[]"]:checked').attr('data-val');
		//alert(printing_type_val);
		var printing_val=printing_type_val;
		if (typeof printing_type_val === "undefined") {
			printing_val='no gusset';
			
		}
		//var material_id = $("#layerdiv table tbody tr td:nth-child(2) select").val();
		$("#product").attr('disabled',false);
		$("#country_id").attr('disabled',false);
		$("#customer_name").attr('disabled',false);
		$("#taxation").attr('disabled',false);
        var formData = $("#form").serialize();
        getRollquantity();
        var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getKgs', '',1);?>");
		$.ajax({
			type: "POST",
			url: url,
			dataType: 'json',
			data:{formData:formData,printing_val:printing_val}, 
			success: function(response) {
				$("#product").attr('disabled',true);
				$("#country_id").attr('disabled',true);
				$("#customer_name").attr('disabled',true);
				$("#taxation").attr('disabled',true);
				
				var qty = $("#qty_for_mtr").val();
				var mtr = $("#qty_in_mtr").val();
				//console.log(qty+'======='+mtr);
				if(response.floor_kg>=qty)
				{ 
				    $("#note_mtr").html('Total Kgs: '+response.total_kgs+' <br> Total Mtr : '+response.total_mtr+'<br> Total Pieces : '+response.total_piece+'<br> Total Pieces Per Kg : '+response.total_piece_per_kg+' <br> Now You Can Generate The Quatation.');
				    $("#mtr_div").show();
				}
				else
				{
				    $("#note_mtr").html('Total Kgs: '+response.total_kgs+' <br> Total Mtr : '+response.total_mtr+'<br> Total Pieces : '+response.total_piece+'<br> Total Pieces Per Kg : '+response.total_piece_per_kg+' <br>For this material specification our minimum quantity is '+qty+' Kg. Can you Please enter '+mtr+' meter or more to get quotation ?');//'+response+' Kgs'
				    $("#mtr_div").hide();
				}
				   
			}
		});
   }
});
	function getRollquantity()
    {   var formData = $("#form").serialize();
            var material_sel = $("#layerdiv table tbody tr td:nth-child(2) select").serialize();
            //console.log(material_sel);
            var materialquantity_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getRollquantity', '',1);?>");
			$.ajax({
				type: "POST",
				url: materialquantity_url,
				dataType: 'json',
				data:{material_sel:material_sel,formData:formData},
				success: function(json) {
				   // console.log(json.totalMtr);
					//var value = $.parseJSON(json);
					//console.log(value.totalMtr);
					if(json)
					   var html ='<input type="hidden" id="qty_for_mtr" value="'+json.qty+'" class="form-control" ><input type="hidden" id="qty_in_mtr" value="'+json.totalMtr+'" class="form-control" >';
					else
					   var html ='<input type="hidden" id="qty_for_mtr" value="200" class="form-control" ><input type="hidden" id="qty_in_mtr" value="0" class="form-control" >';
					   
					$("#div_for_mtr").html(html);
				}
			});
	}
    //end kinjal
</script> 
<!-- Close : validation script -->
<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>