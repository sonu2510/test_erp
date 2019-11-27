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
	
	//insert user
	if(isset($_POST['btn_generate'])){
		$post = post($_POST);		
			if(isset($post['quantity']) && !empty($post['quantity'])){
				/*$last_id = $obj_quotation->addQuotation($post);
				if($last_id == "Error"){
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
        
      <div class="col-sm-10">
                <section class="panel">    
                  <header class="panel-heading bg-white">
				  	<span><?php echo $display_name;?> Detail</span>
                    <span class="text-muted m-l-small pull-right">
                    	Your base currency : <b><?php if($userCurrency){ echo $userCurrency['currency_code'];} else {echo 'INR';}?></b>
                    </span>
                  </header>
                  <div class="panel-body">
                    <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
                     
                       <div class="form-group option">
                                    <label class="col-lg-3 control-label">Ink Selection</label>
                                    <div class="col-lg-9">                
                                        <div  style="float:left;width: 200px;">
                                            <label  style="font-weight: normal;">
                                                <input type="radio" name="ink_sel[]" id="with_ink_sel" value="0" >
                                                With Ink
                                            </label>
                                        </div>
                                        <div style="float:left;width: 200px;">
                                            <label  style="font-weight: normal;">
                                                <input type="radio" name="ink_sel[]" id="without_ink_sel" value="1" checked="checked" >
                                                With Out Ink
                                            </label>
                                        </div> 
                                    </div>
                                </div>
                       
                       
                       <div class="form-group mul_by" style="display:none;">
                                    <label class="col-lg-3 control-label">Ink Multiply By</label>
                                    <div class="col-lg-3">                
                                        <input type="text" name="stk_ink_mul_by" value="<?php echo isset($post['customer']) ? $post['customer'] : ''; ?>" class="form-control validate[required,custom[number],min[0.001]]" id="stk_ink_mul_by">
                                    </div>
                                    <label class="col-lg-3 control-label">Adhesive Multiply By</label>
                                    <div class="col-lg-3">                
                                        <input type="text" name="stk_adh_mul_by" value="<?php echo isset($post['customer']) ? $post['customer'] : ''; ?>" class="form-control validate[required,custom[number],min[0.001]]" id="stk_adh_mul_by">
                                    </div>
                                    
                                </div>
                       
                       <div class="form-group option">
                        <label class="col-lg-3 control-label">Profit</label>
                        <div class="col-lg-9">                
                        	<div  style="float:left;width: 200px;">
                                <label  style="font-weight: normal;">
                                  <input type="radio" name="profit[]" id="profit_rich" value="0" checked="checked" >
                                Profit Rich
                                 </label>
                             </div>
                             <div style="float:left;width: 200px;">
                                <label  style="font-weight: normal;">
                                  	<input type="radio" name="profit[]" id="profit_poor" value="1" >
                              	Profit Poor
                                 </label>
                              </div>
                              <div style="float:left;width: 200px;">
                                <label  style="font-weight: normal;">
                                  	<input type="radio" name="profit[]" id="profit_more_poor" value="2" >
                              	Profit More Poor (This option only for stock pouches)
                                 </label>
                              </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span> Template Name</label>
                        <div class="col-lg-8">
                             <input type="text" name="customer" placeholder="Template Name" value="<?php echo isset($post['customer']) ? $post['customer'] : '';?>" class="form-control validate[required]" id="customer_name">
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
                      <?php 
				 if($allow_currency_status){
						  
						  if($userCurrency){
							  $notGetCurrency = $userCurrency['currency_code'];
						  }else{
							  $notGetCurrency = 'INR';
						  }
						  $currencys = $obj_quotation->getCurrencys();
						
						  if($currencys){
							?>
						<div class="form-group currencycls">
								<label class="col-lg-3 control-label">Select Currency</label>
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
                        <label class="col-lg-3 control-label"><span class="required">*</span> Shipment Country</label>
                        <div class="col-lg-8">
                        	<?php
							$selCountry = ''; 
							if(isset($addedByInfo['country_id']) && $addedByInfo['country_id']){
								$selCountry = $addedByInfo['country_id'];
							}
							?>
                            <select name="country_id" id="country_id" class="form-control validate[required]" style="width:70%">
                            <option value="">Select Country</option>
                            <option value="111" selected="selected">India</option>
                            </select>
                        </div>
                      </div>
                    <?php
						if($_SESSION['ADMIN_LOGIN_USER_TYPE']==4)
						{
							$adminId = $obj_quotation->getEmpAdminId($_SESSION['ADMIN_LOGIN_SWISS']);
							$adminCountryId = $obj_quotation->getUser($adminId,$_SESSION['ADMIN_LOGIN_USER_TYPE']);
						
						}
						else
							$adminCountryId=$addedByInfo;
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
                       <div class="form-group" id="tax_div" >
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
                      <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span> Select Product</label>
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
                      
                       <div class="form-group option">
                        <label class="col-lg-3 control-label">Valve</label>
                        <div class="col-lg-9">                
                        	<div  style="float:left;width: 200px;">
                                <label  style="font-weight: normal;">
                                  <input type="radio" name="valve[]" id="nv" value="0" checked="checked" class="valve">
                                 No Valve
                                 </label>
                             </div>
                             <div style="float:left;width: 200px;">
                                <label  style="font-weight: normal;">
                                  	<input type="radio" name="valve[]" id="wv" value="1" class="valve">
                              	With Valve
                                 </label>
                              </div> 
                        </div>
                      </div>
                      
                       <div class="form-group option">
                        <label class="col-lg-3 control-label">Tin Tie Option</label>
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
                      
                      <div id="zipper_div">
                       
                      </div>
                      
                     <?php /*?> <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span> Select Size(WXHXG)</label>
                        <div class="col-lg-9" id="size_div" style="float: left; width: 30%;">
                        <span class="btn btn-danger btn-xs">Please select Product for Size option.</span>
                         </div>
                      </div><?php */?>
                       <div class="form-group">
                        <label class="col-lg-3 control-label">Make Pouch</label>
                        <div class="col-lg-8">
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
                                        echo '	<input type="radio" class="make" name="make" id="'.$make['make_id'].'" value="'.$make['make_id'].'"  onclick="showSize()" checked="checked"> ';
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
                        
                       <?php /*?>  <div class="form-group" id="spout_pouch_id" style="display:none;">
                        <label class="col-lg-3 control-label">Spout Pouch Type</label>
                        <div class="col-lg-9">                
                        	<div  style="float:left;width: 200px;">
                                <label  style="font-weight: normal;">
                                  <input type="radio" name="spout_pouch[]" id="spout_pouch1" value="center" class="spout_pouch" onclick="showSize()" checked="checked">
                                Center Spout
                                 </label>
                             </div>
                             <div style="float:left;width: 200px;">
                                <label  style="font-weight: normal;">
                                  	<input type="radio" name="spout_pouch[]" id="spout_pouch2" value="corner" onclick="showSize()" class="spout_pouch">
                              	Corner Spout
                                 </label>
                              </div> 
                        </div>
                      </div><?php */?>
                      
                         <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span> Select Size(WXHXG)</label>
                        <div class="col-lg-9" id="size_div" style="float: left; width: 30%;">
                        <span class="btn btn-danger btn-xs">Please select Product for Size option.</span>
                         </div>
                      </div>
                        <div class="form-group" style="display:none">
                        <label class="col-lg-3 control-label"><span class="required">*</span>Increment / Decrement</label>
                        <div class="col-lg-9">
                             <input type="radio" name="incdec" value="1" id="increment" checked="checked">Increment
                             <input type="radio" name="incdec" value="2" id="decrement">Decrement
                             <input type="text" name="incdecval" value="" />
                        </div>
                      </div>
                       <div id="customSize" style="display:none">
                      <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span>Width</label>
                        <div class="col-lg-3">                         
                             <input type="text" name="width" id="width"  value="<?php echo isset($post['width'])?$post['width']:'';?>" class="form-control validate[required,custom[onlyNumberSp]]" > 
                             <span id="widthsugg" style="color:blue;font-size:11px;"></span>                             
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
                        	<select name="printing" id="printing" class="form-control validate[required]">
                                <option value="1" <?php echo (isset($post['printing']) && $post['printing'] == 1)?'selected':'';?> >With Printing</option>
                                <option value="0" <?php echo (isset($post['printing']) && $post['printing'] == 0)?'selected':'';?> >Without Printing</option>
                            </select>
                        </div>
                      </div>
                      <div class="form-group" >
                        <label class="col-lg-3 control-label"><span class="required">*</span> Select Layer</label>
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
                         
                         <div class="col-lg-9 storezo_qty_group" style="display:none">
                         
                         <div class="checkbox" style="float: left; width: 30%;"> 
                             <label>
                           		 <input type="checkbox" name="quantity[]" class="storezo" id="quantity_9" value="<?php echo encode(100) ;?>" onclick="check(9)"/>100
                            
                             </label>
                          </div>
                          <div class="checkbox" style="float: left; width: 30%;"> 
                             <label>
                            	<input type="checkbox" name="quantity[]" class="storezo" id="quantity_10" value="<?php echo encode(200) ;?>"  onclick="check(10)"/>200
                             </label>
                       		</div>
                             <div class="checkbox" style="float: left; width: 30%;"> 
                             <label>
                           		 <input type="checkbox" name="quantity[]" class="storezo" id="quantity_11" value="<?php echo encode(500) ;?>" onclick="check(11)"/>500
                             </label>
                             </div>
                             <div class="checkbox" style="float: left; width: 30%;"> 
                             <label>
                            	<input type="checkbox" name="quantity[]" class="storezo" id="quantity_12" value="<?php echo encode(1000) ;?>"  onclick="check(12)"/>1000
                             </label>
                             </div>
                         
                         </div>
                         
                         <div class="col-lg-9 spout_qty_group" style="display:none">
                         
                          <div class="checkbox" style="float: left; width: 30%;"> 
                             <label>
                           		 <input type="checkbox" name="quantity[]" class="spout" id="quantity_13" value="<?php echo encode(10000) ;?>" onclick="check(13)"/>10000
                            
                             </label>
                          </div>
                          <div class="checkbox" style="float: left; width: 30%;"> 
                             <label>
                            	<input type="checkbox" name="quantity[]" class="spout" id="quantity_14" value="<?php echo encode(15000) ;?>"  onclick="check(14)"/>15000
                             </label>
                       		</div>
                             <div class="checkbox" style="float: left; width: 30%;"> 
                             <label>
                           		 <input type="checkbox" name="quantity[]" class="spout" id="quantity_15" value="<?php echo encode(20000) ;?>" onclick="check(15)"/>20000
                             </label>
                             </div>
                             <div class="checkbox" style="float: left; width: 30%;"> 
                             <label>
                            	<input type="checkbox" name="quantity[]" class="spout" id="quantity_16" value="<?php echo encode(30000) ;?>"  onclick="check(16)"/>30000
                             </label>
                             </div>
                              <div class="checkbox" style="float: left; width: 30%;"> 
                             <label>
                            	<input type="checkbox" name="quantity[]" class="spout" id="quantity_17" value="<?php echo encode(50000) ;?>"  onclick="check(16)"/>50000
                             </label>
                             </div>
                              <div class="checkbox" style="float: left; width: 30%;"> 
                             <label>
                            	<input type="checkbox" name="quantity[]" class="spout" id="quantity_18" value="<?php echo encode(100000) ;?>"  onclick="check(16)"/>100000
                             </label>
                             </div>
                              
                         </div>
                         <div class="col-lg-9 cup_group" style="display:none">
                        
                        <?php
						 $quantities = $obj_quotation->getQuantity1();
							$i=1;
						 foreach($quantities as $quantity)
						 {
						?>
                        	<div class="checkbox" style="float: left; width: 30%;"> 
                             <label>
                            <input type="checkbox" name="quantity[]" class="cup"  id="quantity_<?php echo $i;?>" value="<?php echo encode($quantity['quantity']) ;?>"  onclick="check(<?php echo $i;?>)"/>
                            
							<?php echo $quantity['quantity'] ;?>
                             </label>
                             </div>
                           
                          <?php
							$i++;	
						 }
						 ?>
                  	
                        </div>
                      
                      
                         <div class="col-lg-9 qty_group">
                        
                        <?php
						 $quantities = $obj_quotation->getQuantities();
							$i=1;
						 foreach($quantities as $quantity)
						 {
						?>
                        	<div class="checkbox" style="float: left; width: 30%;"> 
                             <label>
                            <input type="checkbox" name="quantity[]" class="other"  id="quantity_<?php echo $i;?>" value="<?php echo encode($quantity['quantity']) ;?>"  onclick="check(<?php echo $i;?>)"/>
                            
							<?php echo $quantity['quantity'] ;?>
                             </label>
                             </div>
                           
                          <?php
							$i++;	
						 }
						 ?>
                  	
                        </div>
                      </div>
                      
                      <?php
                      $spouts = $obj_quotation->getActiveProductSpout();
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
                      
                       <?php
                      $colors = $obj_quotation->getActiveColor();
					  if($colors){
						  ?>
                      	  <div class="form-group option">
                                <label class="col-lg-3 control-label">Color</label>
                                <div class="col-lg-9">
                                   <?php
                                   $spoutsTxt = '';
								   $i=0;
                                    foreach($colors as $color){
                                       $spoutsTxt .= '<div style="float:left;width: 150px;">';
                                            $spoutsTxt .= '<label  style="font-weight: normal;">';
											if($color['pouch_color_id'] == 1 )
											{
                                                $spoutsTxt .= '<input type="checkbox" id="color'.$i.'" name="color[]" value="'.$color['pouch_color_id'].'" checked="checked" class="colortemp" >';
											}
											else 
											{
												$spoutsTxt .= '<input type="checkbox" id="color'.$i.'" name="color[]" value="'.$color['pouch_color_id'].'" class="colortemp" >';
											}
                                            $spoutsTxt .= ''.$color['color'].'</label>';
                                        $spoutsTxt .= '</div>';
										$i++;
                                    }
                                    echo $spoutsTxt;
                                    ?>
                                </div>
                            </div>
                      	  	<?php
					  	} ?>
                        
                        <div class="form-group">
                         <div class="col-lg-9 col-lg-offset-3">
                       <a  id="btn-all-check" class="label bg-success selectall mt5"  onclick="javascript:checkall('form', true)">Select All</a>
            <a id="btn-all-uncheck" class="label bg-warning unselectall mt5"  onclick="javascript:uncheckall('form', true)">Unselect All</a>  
                        </div>
                        </div>
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
                      
                   <div class="form-group">
                        <div class="col-lg-9 col-lg-offset-3">
                      	  <button type="button" name="btn_add" id="btn_add" class="btn btn-primary" style="display:inline">Add Item</button>	
                        <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a>
                        </div>
                      </div>
                      <div id="result">                      	
                 		</div>
                        <div class="form-group">
                        <div class="col-lg-9 col-lg-offset-3" id="qoute_generate" style="display:none">
                         <button type="submit" name="btn_generate" id="btn_generate" class="btn btn-primary">Generate </button>	
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
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script>
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
			checkZipper();
		$("#layer option[value='MQ==']").attr("selected","selected");
        // binds form submission and fields to the validation engine
        jQuery("#form").validationEngine();
				
		$("#country_id").change(function(){
			var country_id = $(this).val();
			if(country_id == 111)
			{
				$("#tax_div").hide();				
			}
			else
			{
			$("#tax_div").hide();			
			}
		});
		
		$("#btn_add").click(function(){
			if($("#form").validationEngine('validate')){
			$("#product").attr('disabled',false);
			$("#country_id").attr('disabled',false);
			$("#customer_name").attr('disabled',false);
			$("#taxation").attr('disabled',false);
			var formData = $("#form").serialize();
			var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=addQuotation', '',1);?>");			
			$.ajax({
					method: "POST",					
					url: url,
					data : {formData : formData},
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
		$("#product").change(function(){				
			var val = $(this).val();
			$("input[name=make]").removeAttr('disabled','disabled');
			$("input[name=make][id='5']").prop('checked',false);
			$("input[name=make][id='5']").attr('disabled','disabled');
			$("input[name=make][id='1']").prop('checked','checked');
			var text = $("#product option[value='"+val+"']").text().toLowerCase();
			$(".qty_group").show();
			$(".storezo_qty_group").hide();
			$(".spout_qty_group").hide();
			$(".cup_group").hide();
			checkTintie();
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
			if(val==13 || val==16 || val==31 || val==30 || val==61)
			{
				$("input[name=make]").attr('disabled','disabled');
				$("input[name=make][id='5']").removeAttr('disabled','disabled');
				$("input[name=make][id='5']").prop('checked', 'checked');
				$("input[type=radio][name='zipper[]']").attr('disabled','disabled');
			}
			if(val==18)
			{	
				$('input[name="valve[]"][class="valve"][type="radio"][id="wv"]').attr("disabled","disabled");
				$(".qty_group").hide();
				$('#form input[name="quantity[]"][class="storezo"][type="checkbox"]').attr("checked","checked");
				$('#form input[name="quantity[]"][class="other"][type="checkbox"]').removeAttr("checked","checked");
				$('#form input[name="quantity[]"][class="spout"][type="checkbox"]').removeAttr("checked","checked");
				$('#form input[name="quantity[]"][class="cup"][type="checkbox"]').removeAttr("checked","checked");
				$(".storezo_qty_group").show();
			}
			else if(val==61)
			{
				$('input[name="valve[]"][class="valve"][type="radio"][id="wv"]').attr("disabled","disabled");
				$(".qty_group").hide();
				$('#form input[name="quantity[]"][class="spout"][type="checkbox"]').attr("checked","checked");
				$('#form input[name="quantity[]"][class="other"][type="checkbox"]').removeAttr("checked","checked");
				$('#form input[name="quantity[]"][class="storezo"][type="checkbox"]').removeAttr("checked","checked");
				$('#form input[name="quantity[]"][class="cup"][type="checkbox"]').removeAttr("checked","checked");
				$(".spout_qty_group").show();
			}
			else if(val=='47' || val=='48')
			{
			    $(".qty_group").hide();
			    $('#form input[name="quantity[]"][class="spout"][type="checkbox"]').removeAttr("checked","checked");
				$('#form input[name="quantity[]"][class="other"][type="checkbox"]').removeAttr("checked","checked");
				$('#form input[name="quantity[]"][class="storezo"][type="checkbox"]').removeAttr("checked","checked");
				$('#form input[name="quantity[]"][class="cup"][type="checkbox"]').attr("checked","checked");
			    $(".cup_group").show();
			}
			else
			{
			    $('#form input[name="quantity[]"][class="spout"][type="checkbox"]').removeAttr("checked","checked");
			    $('#form input[name="quantity[]"][class="storezo"][type="checkbox"]').removeAttr("checked","checked");
			    $('#form input[name="quantity[]"][class="other"][type="checkbox"]').attr("checked","checked");
			    $('#form input[name="quantity[]"][class="cup"][type="checkbox"]').removeAttr("checked","checked");
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
				//setQuantityHtml('p');
			}
		
			checkGusset();
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

/*function showSize()
{
	var zipper_id=$("input[class='zipper']:checked").val();
	var product_id = $('#product').val();
	var size_url = getUrl("<?php //echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getProductSize', '',1);?>");
	$.ajax({
		type: "POST",
		url: size_url,					
		data:{product_id:product_id,zipper_id:zipper_id},
		success: function(json) {//alert(json);
			if(json){
				$("#size_div").html(json);//alert(product_id);
				//if(product_id==16)
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
}*/
	 /*jQuery(document).ready(function(){
	 
	 
		$("#quantity option[value='MTAwMA==']").removeattr("selected","selected");
		$("#quantity option[value='MjAwMA==']").removeattr("selected","selected");
		$("#quantity option[value='NTAwMA==']").removeattr("selected","selected");
		$("#quantity option[value='MTAwMDA==']").removeattr("selected","selected");
	   // $("input[id=quantity]").removeAttr('checked','checked');

		});*/
		//$('#quantity').attr('checked', true); 
		//$('#quantity').attr('checked', false);
		
		/*$('input["type=checkbox"][name="quantity[]"]').change(function(){
		
			alert("fgg");
		
		});*/
		/*$("input[type:checkbox][name:quantity[]]").change(function(){
			alert("fgg");
			$("#quantity option[value='MTAwMA==']").removeattr("selected","selected");
			$("#quantity option[value='MjAwMA==']").removeattr("selected","selected");
			$("#quantity option[value='NTAwMA==']").removeattr("selected","selected");
			$("#quantity option[value='MTAwMDA==']").removeattr("selected","selected");
		});*/
		
		/*}
		})*/
		/*var qty_id = $('#quantity').val();
	var size_url = getUrl("<?php //echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getQty', '',1);?>");
	$.ajax({
		type: "POST",
		url: size_url,					
		data:{qty_id:},
		success: function(json) {
			if(json){
				$("#size_div").html(json);
				if(product_id==10)
				{
					$("#size option[value='0']").prop('disabled',true);
				}
				if((zip_id==5 || zip_id==6 || zip_id==7) && (product_id==1 || product_id==7))
				{
					$("#size option[value='0']").hide();
				}
			}else{
				$("#size_div").html('<span class="btn btn-danger btn-xs">Please select Product for Size option.</span>');
			}
			
		}
	});			*/


function showSize()
{
	var zipper_id=$("input[class='zipper']:checked").val();
	var zip_id=$("input[class='zipper']:checked").attr('id');
	var make_id=$("input[class='make']:checked").val();
	var product_id = $('#product').val();
	//alert(zip_id);
	//alert(make_id);
	if(make_id == 5)
	{
		$("input[type=radio][value='1'][id='wv']").attr('disabled',true);
		$("input[type=radio][value='1'][id='with_tt']").attr('disabled',true);
		$("input[type=radio][class='spout']").removeAttr('disabled','disabled');
		$("input[class=spout][value='MQ==']").attr('disabled',true);
		$("input[class=spout][value='Mg==']").prop('checked', 'checked');
	}
	else
	{	
		$("input[type=radio][value='1'][id='with_tt']").removeAttr('disabled',true);
		if(product_id != 18)
			$("input[type=radio][value='1'][id='wv']").attr('disabled',false);
			
		$("input[type=radio][class='spout']").attr('disabled',true);
		$("input[class=spout][value='MQ==']").removeAttr('disabled','disabled');
		$("input[class=spout][value='MQ==']").prop('checked', 'checked');
	}
	
	var size_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getProductSize', '',1);?>");
	$.ajax({
		type: "POST",
		url: size_url,					
		data:{product_id:product_id,zipper_id:zipper_id,make_id:make_id},
		success: function(json) {
			//alert(json);
			if(json){
				$("#size_div").html(json);
				if(product_id==10)
				{
					$("#size option[value='0']").prop('disabled',true);
				}
				if(((zip_id==5 || zip_id==6 || zip_id==7) && (product_id==1 || product_id==7)) || product_id==18 )
				{
					$("#size option[value='0']").hide();
				}
			}else{
				$("#size_div").html('<span class="btn btn-danger btn-xs">Please select Product for Size option.</span>');
			}
			$("#loading").hide();
		}
	});			
}
//STart : layer
function setLayerHtml(layer,make){
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
				var materialquantity_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getMaterialQuantity', '',1);?>");
				$.ajax({
					type: "POST",
					url: materialquantity_url,
					dataType: 'json',
					data:$("#layerdiv table tbody tr td:nth-child(2) select"),
					success: function(json) {
						if(json){
							//$("#squantity").html(json);
							
						}else{
							//$("#squantity").html('<span class="btn btn-danger btn-xs">Please select material for quantity option.</span>');
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

/*function checkZipper(){
	var gusset_url = getUrl("<?php //echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=checkProductZipper', '',1);?>");
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
}*/
function removePrintingEffect(){
	$('#effectdiv').empty();
}

function getMaterialThickness(material_id,id,layer){
	//ruchi
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
				if(product_id==10)
				{
					$("#thickness-dropdown-1 option").hide();
					$("#thickness-dropdown-1 option[value='60.000']").show();
					$("#thickness-dropdown-1 option[value='60.000']").attr('selected', 'selected');
				
				}
				else
				{
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
function checkall(formname, checktoggle)
{
     var checkboxes = new Array();
      checkboxes = $('input[name="color[]"]');
      for (var i = 0; i < checkboxes.length; i++) {
          if (checkboxes[i].type === 'checkbox') {
               checkboxes[i].checked = checktoggle;
          }
      }
}
function uncheckall(formname, checktoggle)
{
     var checkboxes = new Array();
      checkboxes = $('input[name="color[]"]');
      for (var i = 0; i < checkboxes.length; i++) {
          if (checkboxes[i].type === 'checkbox') {
               checkboxes[i].checked = '';
          }
      }
}

// mansi
$(document).ready(function() {
	$("input[type=radio][class='spout']").attr('disabled',true);
	$("input[type=radio][value='MQ==']").removeAttr('disabled','disabled');
	$("input[class=spout][value='MQ==']").prop('checked', 'checked');
	
	$('input[type=radio][class=tin_tie]').change(function() {
		
		var value = $(this).val();
		checkZipper(value);
		
	});
	
	//$('input[type=radio][name=make]').change(function() {
	/*var make_id=$("input[class='make']:checked").val();
	//alert(make_id);
		if(make_id == 5)
		{
			$("input[type=radio][value='1'][id='wv']").attr('disabled',true);
			$("input[type=radio][name='zipper[]']").attr('disabled',true);
			$("input[type=radio][name='accessorie[]']").attr('disabled',true);
			$("input[type=radio][name='zipper[]'][value='Mg==']").removeAttr('disabled',false);
			$("input[type=radio][name='accessorie[]'][value='NA==']").removeAttr('disabled',false);
			$("input[type=radio][value='1'][id='with_tt']").attr('disabled',true);
			$("input[type=radio][class='spout']").removeAttr('disabled','disabled');
			$("input[class=spout][value='MQ==']").attr('disabled',true);
			$("input[class=spout][value='Mg==']").prop('checked', 'checked');
			//$("#spout_pouch_id").show();
		}
		else
		{	$("input[type=radio][name='accessorie[]']").removeAttr('disabled',true);
			$("input[type=radio][name='zipper[]']").removeAttr('disabled',true);
			$("input[type=radio][value='1'][id='with_tt']").removeAttr('disabled',true);
			$("input[type=radio][value='1'][id='wv']").attr('disabled',false);
			$("input[type=radio][class='spout']").attr('disabled',true);
			$("input[class=spout][value='MQ==']").removeAttr('disabled','disabled');
			$("input[class=spout][value='MQ==']").prop('checked', 'checked');
			//$("#spout_pouch_id").hide();
		}*/
		
	//});
	
});

function checkTintie(){
	var gusset_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=checkProductTintie', '',1);?>");
	var product_id = $('#product').val();
	$.ajax({
		type: "POST",
		url: gusset_url,
		data:{product_id:product_id}, 
		success: function(response) {
			var val = $.parseJSON(response);
			if(val.response==0){
				$("#without_tt").prop('checked', 'checked');
				$("#with_tt").attr('disabled',true);
				
			}else{
				$("#without_tt").prop('checked', 'checked');
			}
			if(val.result==0){
				
			}else{
				$("input[type=radio][name='zipper[]']").attr('disabled',true);
			}
			checkZipper(res=0);
			}
		});
	}	
function checkZipper(tintie){
		var gusset_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=checkProductZipper', '',1);?>");
		var product_id = $('#product').val();
		$.ajax({
			type: "POST",
			url: gusset_url,
			dataType: 'json',
			data:{product_id:product_id, tintie:tintie}, 
			success: function(response) {
				$('#zipper_div').html(response);	
			}
		});
}
jQuery(document).ready(function(){
		 	
		  //$(".mul_by").hide();
		  $("#without_tt").prop('checked','checked');
			var tin =$("#without_tt").prop('checked','checked').val();
			checkZipper(tin);
			$("without_tt").attr('disabled',false);
			$("with_tt").attr('disabled',false);
			$('#form input[name="quantity[]"][class="other"][type="checkbox"]').attr("checked","checked");
			$('#form input[name="quantity[]"][class="storezo"][type="checkbox"]').removeAttr("checked","checked");
		 
});
		
/*$('#form input[name="quantity[]"][type="checkbox"]').on('change', function() {
			var val = $('#form input[name="quantity[]"]').val();
			alert(val);
			var checkStatus = $(this).is(':checked');  
			 if(checkStatus){
			 	$("#quantity option[value='"+val+"']").attr("checked","checked");
			 } 
			 else
			 {
			 	$("#quantity option[value='"+val+"']").removeAttr("checked","checked");
			 }
			 
	});*/
function check(n)
{
	var val = $("#quantity_"+n).val();
	var id = $("#quantity_"+n);
	//alert(id);
	var checkStatus = $(this).is(':checked');  
	 if(checkStatus){
		$("#quantity_"+n+" option[value='"+val+"']").attr("checked","checked");
	 } 
	 else
	 {
		$("#quantity_"+n+" option[value='"+val+"']").removeAttr("checked","checked");
	 }
}
$('#form input[name="ink_sel[]"]').on('change', function () {
												
	var value = $(this).val();
	//alert(value);
	if(value=='0')
		$(".mul_by").show();
	else
		$(".mul_by").hide();
		
 });
</script> 
<!-- Close : validation script -->
<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>