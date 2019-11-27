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
	
	$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
	$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
	
	
	if(isset($_POST['btn_save'])){
		//echo "Sdada";die;
		if(isset($_SESSION['product_array']) && count($_SESSION['product_array'])>0){	
			$order_id = $obj_order->addOrder($_POST);
			page_redirect($obj_general->link($rout, '', '',1));
		}
	}else{
		if(isset($_SESSION['product_images'])){
		  unset($_SESSION['product_images']);
		}
		
		if(isset($_SESSION['product_array'])){
		  unset($_SESSION['product_array']);
		}
		
		if(isset($_SESSION['product_die_line'])){
		  unset($_SESSION['product_die_line']);
		}
		//$obj_order->deleteOrder();
	}
	if(isset($_POST['btn_order_redirect'])){
		$obj_session->data['success'] = 'Order successfully Added!'; 
		page_redirect($obj_general->link($rout, '', '',1));
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
        <?php

		//echo preg_replace("/'/", "\\'", $msg); ?>
        <div class="col-lg-12">
        	<section class="panel">
              <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
              <form class="form-horizontal" method="post" name="form" id="order-form" enctype="multipart/form-data">
                    <div class="panel-body">
                    	<div class="col-lg-6">
                           <h4><i class="fa fa-edit"></i> General Details</h4>
                           <div class="line m-t-large" style="margin-top:-4px;"></div><br/>
                            
                            <div class="form-group" style="display:none">
                                <label class="col-lg-4 control-label">Order Type</label>
                                <div class="col-lg-8">
                                  <div class="radio  radio-group">
                                    <label class="radio-custom">
                                      <input type="radio" class="required" value="stock"  name="order_type">
                                      <i class="fa fa-circle-o"></i> Stock </label>
                                    <label class="radio-custom" style="margin-left:2px;">
                                      <input type="radio" class="required" value="custom" name="order_type" checked="checked">
                                      <i class="fa fa-circle-o"></i> Custom </label>
                                  </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-lg-4 control-label"><span class="required">*</span>Company Name</label>
                                <div class="col-lg-7">
                                     <input type="text" name="company" value="" id="company" class="form-control validate[required]">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Website</label>
                                <div class="col-lg-7">
                                     <input type="text" name="website" value="" id="website" class="form-control">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-lg-4 control-label">First Name</label>
                                <div class="col-lg-7">
                                     <input type="text" name="first_name" value="" class="form-control ">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Last Name </label>
                                <div class="col-lg-7">
                                     <input type="text" name="last_name" value="" class="form-control ">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-lg-4 control-label"><span class="required">*</span>Email Address </label>
                                <div class="col-lg-7">
                                     <input type="text" name="email" value="" class="form-control validate[required]">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-lg-4 control-label"><span class="required">*</span>Order Currency </label>
                                <div class="col-lg-7"><?PHP
								$selcurrency = $obj_order->getdefaultcurrency();
								//printr($currency);
								$currency = $obj_order->getCurrencyList();
								echo '<select name="order_currency" id="order_currency" class="form-control" disabled="disabled">';
								foreach($currency as $key=>$value)
								{
									if($selcurrency['currency_code']==$value['currency_code'])
									echo '<option value="'.$value['currency_code'].'" selected="selected">'.$value['currency_code'].'</option>';
									else
									echo '<option value="'.$value['currency_code'].'">'.$value['currency_code'].'</option>';
								}
								echo '</select>';
								?>
                                  <!--  <select name="order_currency" class="form-control">
                                    	<option value="gbp">GBP - Pound</option>
                                        <option value="usd">USD - Dollar</option>
                                        <option value="eur">EUR - Euro</option>
                                    </select>-->
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Contact No </label>
                                <div class="col-lg-7">
                                     <input type="text" name="contact_number" value="" class="form-control ">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-lg-4 control-label">VAT No </label>
                                <div class="col-lg-7">
                                     <input type="text" name="vat_number" value="" class="form-control ">
                                </div>
                            </div>
                           
                           <div class="form-group">
                                <label class="col-lg-4 control-label"><span class="required">*</span>Industry </label>
                                <div class="col-lg-7">
                                     <select name="industry" class="form-control">
                                    	<?php
										$industrys = $obj_order->getIndustrys();
										foreach($industrys as $industry){
											echo '<option value="'.$industry['enquiry_industry_id'].'">'.$industry['industry'].'</option>';
										}
										?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Order Note <br /><small class="text-muted">(for internal purpose)</small></label>
                                <div class="col-lg-7">
                                     <textarea name="order_note" class="form-control"></textarea>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Order Instruction <br /><small class="text-muted">(this will be displayed in order)</small></label>
                                <div class="col-lg-7">
                                     <textarea name="order_instruction" class="form-control"></textarea>
                                </div>
                            </div>
                            
                        </div> 
                        
                        <div class="col-lg-6">
                            <div class="col-lg-12">
                              <h4><i class="fa fa-edit"></i> Shipping Address</h4>
                              <div class="line m-t-large" style="margin-top:-4px;"></div><br/>
                              <div class="form-group">
                                <label class="col-lg-3 control-label"><span class="required">*</span> Address 1</label>
                                <div class="col-lg-8">
                                  <input type="text" name="shipping_address_1" value="<?php echo isset($zipper['zipper_name'])?$zipper['zipper_name']:'';?>" class="form-control validate[required] test">
                                </div>
                              </div>
                              
                              <div class="form-group">
                                <label class="col-lg-3 control-label">Address 2</label>
                                <div class="col-lg-8">
                                  <input type="text" name="shipping_address_2" value="<?php echo isset($zipper['zipper_name'])?$zipper['zipper_name']:'';?>" class="form-control test">
                                </div>
                              </div>
                              
                              
                           
                              <div class="form-group">
                                <label class="col-lg-3 control-label"><span class="required">*</span>Country</label>
                                <div class="col-lg-8">
								 <select name="shipping_country_id" id="shipping_country_id" class="form-control">
								 <?php
								 $defaultcountry = $obj_order->getdefaultcountry($user_id,$user_type_id);
								 $countrys = $obj_order->getCountries();
								 foreach($countrys as $country){
									 if($defaultcountry['country_id']==$country['country_id'])
									 echo '<option value="'.$country['country_id'].'" selected="selected">'.$country['country_name'].'</option>';
									else
									echo '<option value="'.$country['country_id'].'">'.$country['country_name'].'</option>';
								} ?>  
                                 </select>
                                </div>
                              </div>
                               <div class="form-group" id="tax_div" style="display:none">
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
								
                                //    echo '	<label  style="font-weight: normal;">';                                 
								//	echo '	<input type="radio" name="taxation" id="taxation" value="cst" checked="checked" > ';								
                                  //  echo '	 Out Of Gujarat </label></div><div  style="float:left;width: 200px;">';
									echo '	<label style="font-weight: normal;">';                                 
									echo '	<input type="radio" name="taxation" id="taxation" value="vat" > ';								
                                    echo '	With In Gujarat </label>';
                                    echo '</div>';
                              
                                ?>
                            </div>
                        </div>
                        	  <div class="form-group">
                                <label class="col-lg-3 control-label"><span class="required">*</span>City</label>
                                <div class="col-lg-8">
                                  <input type="text" name="shipping_city" value="<?php echo isset($zipper['zipper_name'])?$zipper['zipper_name']:'';?>" class="form-control validate[required] test">
                                </div>
                              </div>
                              <div class="form-group">
                                <label class="col-lg-3 control-label"><span class="required">*</span>Post Code</label>
                                <div class="col-lg-8">
                                  <input type="text" name="shipping_postcode" value="<?php echo isset($zipper['zipper_name'])?$zipper['zipper_name']:'';?>" class="form-control validate[required]">
                                </div>
                              </div>
                              
                            </div>
                            
                            <div class="col-lg-12">
                              
                              <h4><i class="fa fa-edit"></i> Billing Address
                                 <label class="checkbox-custom  m-l-small pull-right" style="font-size:14px;">
                                   <input type="checkbox" name="same_as_above" id="same-above" value="1">
                                 <i class="fa fa-square-o"></i> Same as Above? </label> 
                              </h4>  	
                              	
                              <div class="line m-t-large" style="margin-top:4px;"></div><br/>
                              
                              <div id="billing-details">
                                  <div class="form-group">
                                    <label class="col-lg-3 control-label"><span class="required">*</span> Address 1</label>
                                    <div class="col-lg-8">
                                      <input type="text" name="billing_address_1" value="<?php echo isset($zipper['zipper_name'])?$zipper['zipper_name']:'';?>" class="form-control validate[required]">
                                    </div>
                                  </div>
                                  
                                  <div class="form-group">
                                    <label class="col-lg-3 control-label">Address 2</label>
                                    <div class="col-lg-8">
                                      <input type="text" name="billing_address_2" value="<?php echo isset($zipper['zipper_name'])?$zipper['zipper_name']:'';?>" class="form-control">
                                    </div>
                                  </div>
                                  
                                  
                                  
                                  <div class="form-group">
                                    <label class="col-lg-3 control-label"><span class="required">*</span>Country</label>
                                    <div class="col-lg-8">
                                      <select name="billing_country_id" class="form-control">
										 <?php 
                                         $defaultcountry = $obj_order->getdefaultcountry($user_id,$user_type_id);
								 $countrys = $obj_order->getCountries();
								 foreach($countrys as $country){
									 if($defaultcountry['country_id']==$country['country_id'])
									 echo '<option value="'.$country['country_id'].'" selected="selected">'.$country['country_name'].'</option>';
									else
									echo '<option value="'.$country['country_id'].'">'.$country['country_name'].'</option>';
								}
                                         ?>  
                                     </select>
                                    </div>
                                  </div>
                                  
                                  <div class="form-group">
                                    <label class="col-lg-3 control-label"><span class="required">*</span>City</label>
                                    <div class="col-lg-8">
                                      <input type="text" name="billing_city" value="<?php echo isset($zipper['zipper_name'])?$zipper['zipper_name']:'';?>" class="form-control validate[required]">
                                    </div>
                                  </div>
                                  
                                  <div class="form-group">
                                    <label class="col-lg-3 control-label"><span class="required">*</span>Post Code</label>
                                    <div class="col-lg-8">
                                      <input type="text" name="billing_postcode" value="<?php echo isset($zipper['zipper_name'])?$zipper['zipper_name']:'';?>" class="form-control validate[required]">
                                    </div>
                                  </div>
                              </div>
                              
                            </div>
                            
                        </div>
                        
                        <div class="col-lg-12" id="add-product-div">
                           <h4><i class="fa fa-plus-circle"></i> Add Product</h4>
                           <div class="line m-t-large" style="margin-top:-4px;"></div><br/>
                          
                          	  <div class="col-lg-7">
                              <div class="form-group">
                                       <label class="col-lg-3 control-label">Quotation No.</label>
                                       <div class="col-lg-7">
                                           <input type="text" <?php if(isset($_GET['quotation_no']) && $_GET['quotation_no']!=''){?>disabled="disabled" value="<?php echo decode($_GET['quotation_no']);?>" <?php }else { echo 'value=""';}?> name="quotation_no" id="quotation_no"  class="form-control" onchange="frm_fill();" />
                                           <input type="hidden" name="product_quotation_id" id="product_quotation_id" value="" class="form-control"/>
                                       </div>
                                    </div>
                                 <div id="hide">
                                    <div class="form-group">
                                       <label class="col-lg-3 control-label"><span class="required">*</span> Product</label>
                                       <div class="col-lg-7">
                                            <?php
                                            $products = $obj_order->getActiveProduct();
                                            ?>
                                            <select name="product" id="product" class="form-control validate[required]">
                                                <?php
                                                $post['product'] = 3;
                                                foreach($products as $product){
                                                    if(isset($post['product']) && $post['product'] == $product['product_id']){
                                                        echo '<option value="'.$product['product_id'].'" selected="selected">'.$product['product_name'].'</option>';
                                                    }else{
                                                        echo '<option value="'.$product['product_id'].'">'.$product['product_name'].'</option>';
                                                    }
                                                } ?>
                                            </select>
                                       </div>
                                    </div>
                          
                                    <div class="form-group">
                                       <label class="col-lg-3 control-label"><span class="required">*</span>Printing Option</label>
                                       <div class="col-lg-7">
                                        <?php $post['printing'] = 0;?>
                                        <select name="printing" id="printing" class="form-control validate[required]">
                                            <option value="1" <?php echo (isset($post['printing']) && $post['printing'] == 1)?'selected':'';?> >With Printing</option>
                                            <option value="0" <?php echo (isset($post['printing']) && $post['printing'] == 0)?'selected':'';?>>Without Printing</option>
                                        </select>
                                       </div>
                                    </div>
                              
                                    <div class="form-group" >
                                        <label class="col-lg-3 control-label">Printing Effect</label>
                                       <div class="col-lg-7">
                                           <select name="printing_effect" id="printing_effect" class="form-control validate[required]">
                                                <?php
                                                $effects = $obj_order->getActivePrintingEffect();
                                                foreach($effects as $effect){
                                                    if(isset($post['printing_effect']) && $post['printing_effect'] == $effect['printing_effect_id']){
                                                        echo '<option value="'.$effect['printing_effect_id'].'" selected="selected">'.$effect['effect_name'].'</option>';
                                                    }else{
                                                        echo '<option value="'.$effect['printing_effect_id'].'">'.$effect['effect_name'].'</option>';
                                                    }
                                                } ?>
                                           </select>
                                       </div>
                                    </div>
                              
                                  	<div class="form-group" >
                                     <label class="col-lg-3 control-label"><span class="required">*</span> Select Layer</label>
                                     <div class="col-lg-7">
                                        <?php
                                        $layers = $obj_order->getActiveLayer();
                                        ?>
                                        <select name="layer" id="layer" class="form-control validate[required]" onchange="layeronchange()">
                                        <option value="">Please select layer</option>
                                            <?php
                                            foreach($layers as $layer){
                                                echo '<option value="'.encode($layer['product_layer_id']).'">'.$layer['layer'].'</option>';
                                            } ?>
                                        </select>
                                     </div>
                                  </div>
                              	  
                                  
                                  	<div class="form-group">
                                    <label class="col-lg-3 control-label">Make Pouch</label>
                                    <div class="col-lg-7">
                                            <?php
                                            include('model/product_quotation.php');
											$obj_quotation = new productQuotation; 
											
                                            $makes = $obj_quotation->getActiveMake();
                                            //printr($makes);die;
											?>
                                            <div >
                                            <?php $l=0; ?>
                                            <?php foreach($makes as $make){ ?>
                                            	
                                                 <label <?php echo ($l>0) ? 'style="margin-left:2px;"' : ''; ?>>
                                                      <input type="radio" name="make" id="make<?php echo $l;?>" value="<?php echo $make['make_id']; ?>" 
													  <?php echo ($l==0) ? 'checked=checked' : '';?> >
                                                      <?php echo $make['make_name']; ?> 
                                                  </label>
                                                  <?php $l++; ?> 
                                            <?php } ?>
                                            </div>
                                            
                                    </div>
                                 </div>
                                  
                                  
									<div class="form-group">
                                    <label class="col-lg-3 control-label">Material</label>
                                    <div class="col-lg-8" id="layerdiv"></div>
                                  </div>
                                  
                                  	<div class="form-group">
                                    <label class="col-lg-3 control-label"><span class="required">*</span>Size</label>
                                    <div class="col-lg-8 table-responsive">
                                     <section class="panel">
                                     <table class="table table-striped b-t text-small">
                                        <thead>
                                            <tr>
                                                <th width="20%" class="text-center">Width</th>
                                                <th width="20%" class="text-center">Height</th>
                                                <th width="20%" class="text-center">Gusset</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><input type="text" name="width" value="" class="form-control validate[required,custom[onlyNumberSp]] test" id="width">
                                               <!-- <span id="widthsugg" style="color:blue;font-size:11px;"></span>-->
                                                </td>           										<td><input type="text" name="height" value="" class="form-control validate[required,custom[onlyNumberSp]]" id="height"></td>				
                                                <td><input id="gusset_input" type="text" name="gusset" class="form-control validate[required,custom[onlyNumberSp]] gusset">
                                            <!--    <span id="gussetsugg" style="color:blue;font-size:11px;"></span>-->
                                                </td>
                                            </tr>
                                            <tr>
                                            	<td>
                                                	<div class="col-lg-3">
	                                                      <a href="#"  id="mydiv" class="btn btn-info btn-xs">View Size Table</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                        
                                     </table>
                                   
                                     </section>
                                       <span id="widthsugg" style="color:blue;font-size:11px;"></span>
                                         <span id="gussetsugg" style="color:blue;font-size:11px;"></span>
                                    </div>
                                  
                                  </div>
                                  
                                    
                                  
                              <!--    <div class="form-group quantity_in">
                                    <label class="col-lg-3 control-label">Quantity In</label>
                                    <div class="col-lg-7">
                                         <select name="quantity_type" id="quantity_type" class="form-control">
                                            <option value="meter">Meter</option>
                                            <option value="kg">Kgs</option>
                                            <option value="pieces">Pieces</option>
                                        </select>
                                    </div>
                                  </div>-->
                              
                              	 <div class="form-group" >
                                    <label class="col-lg-3 control-label"><span class="required">*</span>Quantity</label>
                                    <div class="col-lg-7" id="squantity"></div>
                                  </div>
                                  </div>
                              
                              </div>

<!-- start invoice info by invoice number -->
<div id="show" style="float:left;display:none;">
</div>
<!-- End invoice info by invoice number -->

                              <div class="col-lg-5" id="col-lg-5">
                              	<div class="form-group option">
                                    <label class="col-lg-3 control-label">Valve</label>
                                    <div class="col-lg-8">
                                       <select class="form-control" name="valve" id="valve">
                                            <option value="0">No Valve</option>
                                            <option value="1">With Valve</option>
                                       </select>
                                    </div>
                                  </div>
                              
                                  <div class="form-group option">
                                    <label class="col-lg-3 control-label">Zipper</label>
                                    <div class="col-lg-8">
                                        <?php
                                        $zippers = $obj_order->getActiveProductZippers();
                                        $ziptxt = '';
                                        $ziptxt .= '<select class="form-control" name="zipper" id="zipper">';
                                        foreach($zippers as $zipper){
                                           $ziptxt .= '<option value="'.$zipper['product_zipper_id'].'">'.$zipper['zipper_name'].'</option>';
                                        }
                                        $ziptxt .= '</select>';
                                        echo $ziptxt;
                                        ?>
                                    </div>
                                  </div>
                                  
                                  <?php
                                  $spouts = $obj_order->getActiveProductSpout();
                                  if($spouts){
                                      ?>
                                      <div class="form-group option">
                                        <label class="col-lg-3 control-label">Spout</label>
                                        <div class="col-lg-8">
                                            <?php
                                           $spoutsTxt = '';
                                           $spoutsTxt .= '<select class="form-control" name="spout" id="spout">';
                                            foreach($spouts as $spout){
                                                $spoutsTxt .= '<option value="'.$spout['product_spout_id'].'">'.$spout['spout_name'].'</option>';
                                            }
                                            $spoutsTxt .= '</select>';
                                            echo $spoutsTxt;
                                            ?>
                                        </div>
                                      </div>
                                      <?php
                                  } ?>
                                  
                                  <?php
                                  $accessorie = $obj_order->getActiveProductAccessorie();
                                  if($accessorie){
                                      ?>
                                      <div class="form-group">
                                        <label class="col-lg-3 control-label">Accessorie</label>
                                        <div class="col-lg-8" id="squantity5">
                                           <select name="accessorie" id="accessorie" class="form-control">
                                                <?php 
                                                foreach($accessorie as $accessorie) {
                                                    echo '<option value="'.$accessorie['product_accessorie_id'].'">'.$accessorie['product_accessorie_name'].'</option>';
                                                } ?>    
                                           </select>
                                        </div>
                                      </div>
                                      <?php
                                  } ?>
                                  
                               <?php /*?>   <?php
                                  $colors = $obj_order->getActiveProductColors();
                                  if($colors){
                                      ?>
                                      <div class="form-group">
                                        <label class="col-lg-3 control-label">Color</label>
                                        <div class="col-lg-8" id="squantity1">
                                           <select name="color" class="form-control">
                                                <?php 
                                                foreach($colors as $color) {
                                                    echo '<option value="'.encode($color['color']).'">'.$color['color'].'</option>';
                                                } ?>    
                                           </select>
                                        </div>
                                      </div>
                                      <?php
                                  } ?><?php */?>
                                  
                                  <?php
                                   $styles = $obj_order->getActiveProductStyle();
                                   if($styles){
                                       ?>
                                      <div class="form-group">
                                        <label class="col-lg-3 control-label">Style</label>
                                        <div class="col-lg-8" id="squantity2">
                                           <select name="style" class="form-control">
                                                <?php 
                                                foreach($styles as $style) {
                                                    echo '<option value="'.encode($style['style']).'">'.$style['style'].'</option>';
                                                } ?>    
                                           </select>
                                        </div>
                                      </div>
                                      <?php
                                   } ?>
                                  
                                <?php /*?>  <?php
                                  $volumes = $obj_order->getActiveProductVolume();
                                  if($volumes){
                                      ?>
                                      <div class="form-group">
                                        <label class="col-lg-3 control-label">Volume</label>
                                        <div class="col-lg-8" id="squantity3">
                                           <select name="volume" class="form-control">
                                                <?php 
                                                  foreach($volumes as $volume) {
                                                ?>
                                                    <option value="<?php echo encode($volume['volume']); ?>"><?php echo $volume['volume']; ?></option>
                                                <?php } ?>    
                                           </select>
                                        </div>
                                      </div>
                                      <?php
                                  } ?><?php */?>
                                  
                                 <div class="form-group" >
                                    <label class="col-lg-3 control-label">Transportation</label>
                                    <div class="col-lg-8" id="transport_div">
                                        <select name="transpotation" class="form-control validate[required]" id="trans">
                                        	<option value="">Please select</option>
                                            <option value="air" id="air">By Air</option>
                                            <option value="sea" id="sea">By Sea</option>
                                            <option value="pickup" id="pickup">Pickup</option>
                                        </select>
                                    </div>
                                  </div>
                                  
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
                                
                                
                                
                                  
                              </div>
                             <div id="hide1">
                              <div class="col-lg-7 form-group" >
                             	<label class="col-lg-3 control-label">Art Work</label>
                                <div class="col-lg-9">
                                	
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
                              <div class="col-lg-7 form-group">
                             	<label class="col-lg-3 control-label">DieLine <br/><small class="text-muted">(Only .pdf & .jpg format)</small></label>
                                <div class="col-lg-9">
                                	
                                    <div class="media-body">
                                        	<input type="file" name="die_line" id="die-line" title="<i class='fa fa-folder-open-o'></i> Browse" class="btn btn-sm btn-info m-b-small">
                                    </div>
                                    
                                    <div class="file-preview-die" style="display:none">
                                       <div class="file-preview-thumbnails-die">
                                            
                                       </div>
                                       <div class="clearfix"></div>
                                    </div>
                                    
                                    <div id="append-dieline"></div>
                                    <?php /*
                                    <div class="input-group media-body_1">			
                                    	<a style="text-align:left;" class="btn btn-default btn-block">my silent.jpg.jpg</a>			
                                        <span class="input-group-btn">				
                                        	<a onclick="removefile(1)" class="btn btn-danger remove_i"><i class="fa fa-times"></i></a>
                                        </span>		
                                    </div>
                                    */ ?>
                                </div>
                              </div>
                            </div>
                              
                              <?php /*
                              <div class="col-lg-7 form-group">
                                <label class="col-lg-3 control-label">Art Work</label>
                                <div class="col-lg-9">
                                    <div class="row" id="image-row-1">
                                        
                                        <div class="col-lg-9 media more_image" >
                                            <div class="bg-light pull-left text-center media-large thumb-large" id="display-image-1">
                                                 <img src= "<?php echo HTTP_SERVER.'images/blank-user64x64.png'; ?>" class="img-rounded" alt="">
                                            </div>
                                            <div class="media-body">
                                                <input type="file" name="art_image" id="art_image_1" title="Change" class="btn btn-sm btn-info m-b-small" />
                                                <br>
                                                <button class="btn btn-success btn-xs" onClick="uploadImage(1);" type="button"><i class="fa fa-upload"></i> Upload</button>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <a class="btn btn-success btn-xs btn-circle addmore-image" data-toggle="tooltip" data-placement="top" title="Add Image" ><i class="fa fa-plus"></i></a>
                                        </div>
                                   </div>
                                   
                                   <div id="more-img-div"></div>          
                                </div>
                             </div>
                              */ ?>
                              
                             <div class="form-group">
                                <div class="col-lg-9 col-lg-offset-3">
                                  <button type="button" id="btn-add-product" class="btn btn-primary">Add Product</button> 	
                                </div>
                             </div>
                             
                             <div id="display-product">
                             </div>
                             
<!--------------- start result ------------------>
<div id="results" style="float:left;display:none;">
	
    <div id="results2">
                        </div>
    <div class="form-group">
		<div class="col-lg-9 col-lg-offset-5">
			<button type="submit" class="btn btn-primary" id="btn_order_redirect" name="btn_order_redirect">Add Product</button>
		</div>
	</div>
</div>
                      	
    <div class="form-group">
        <div class="col-lg-9 col-lg-offset-3" id="qoute_generate" style="display:none">
            <button type="submit" name="btn_generate" id="btn_generate" class="btn btn-primary">Generate </button>	
        </div>
    </div>
</div>
<!--------------- end result -------------------->                             
                             
                             <div class="line line-dashed m-t-large"></div>
                             
                             <div class="form-group" id="footer-div" style="display:none">
                                <div class="col-lg-9 col-lg-offset-3">
                                  <button type="submit" name="btn_save" class="btn btn-primary">Save</button>
                                  <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a>  
                                </div>
                             </div>
                             
                         </div> 
        
                    </div>
                </form>
        	</section>
      	</div>
      
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

</section>
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>


<script>	

function add_images()
{	
	count += 1;
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajaximage', '',1);?>");
	$('#loading').show();
	var img_html = '';
	var file_data = $("#art-image2").prop("files")[0];          // Getting the properties of file from file field
	//var file_data = $("#art-image").val();
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
				//s$('#display-image-'+count+' img').attr('src',JSON.parse(response));
				$('#loading').remove();
			}else{
				$('#loading').remove();
			}
		}
   });	
}
function add_dieline_images() {	
die_count += 1;
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=uploadDieLine', '',1);?>");
	//$('#loading').show();
	var die_html = '';
	var file_data = $("#dieline_image2").prop("files")[0];          // Getting the properties of file from file field
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
}
function quot_form(id){	

	$("#empty-item").hide();
	var pro_quote_price_id = id;
	var quotation_no = $("#quotation_no").val();
	if($("#order-form").validationEngine('validate')){
		var hiddenDiv = document.getElementById("results");
	   	hiddenDiv.style.display = (this.value == "") ? "none":"block";
		$('#shipping_country_id').prop('disabled', true);
		
    	//$('#order_currency').prop('disabled', true);
		var postData = $("#order-form").serialize();
		var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=addQuotation', '',1);?>");			
		$.ajax({
				method: "POST",
				url: url,
				data : {pro_quote_price_id : pro_quote_price_id, quotation_no : quotation_no, postData : postData},
				success: function(response){
					$('#results2').html(response);
					//var price_tax = document.getElementByClassName("price_tax");
					//alert(price_tax);
				},
				error: function(){
						return false;
				}
			});
	}
}

//alert(document.URL);
var count=0;
var product_count = 0;
function frm_fill(){
	var quotation_no=$('#quotation_no').val();
	if(quotation_no)
	{

		
		$("#btn-add-product").hide();
		$('#results2').html("");
		$('#display-product').html("");

	$("#test").val(quotation_no);
		$('#shipping_country_id').prop('disabled', 'disabled');
		$('#tax_div').hide();
		var hiddenDiv = document.getElementById("hide");
    	hiddenDiv.style.display = (this.value == "") ? "block":"none";
		var hiddenDiv = document.getElementById("col-lg-5");
    	hiddenDiv.style.display = (this.value == "") ? "block":"none";
		var hiddenDiv = document.getElementById("hide1");
    	hiddenDiv.style.display = (this.value == "") ? "block":"none";
		var hiddenDiv = document.getElementById("show");
    	hiddenDiv.style.display = (this.value == "") ? "none":"block";
		var hiddenDiv = document.getElementById("results");
    	hiddenDiv.style.display = (this.value == "") ? "block":"none";
	var quotation_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getQuotionData', '',1);?>");
	$.ajax({
		method : 'post',
		url: quotation_url,
		data : {quotation_no : quotation_no},
		success: function(response) {
			
			$('#show').html(response);	
			var quot_country = $("#quot_country").val();
			$("#shipping_country_id").val(quot_country);	
			var quot = $('#pro_quote_id').val();
			if(quot) {
				$('#quotation_no').prop('disabled', 'disabled');
			}

		if(response==1)
		{
			alert('Please Enter Correct Qoutation No.');	
		}
		else
		{
			var data = $.parseJSON(response);
			var val = data.result;	
			var qty = data.re;
			var trans = qty.checked;
			var qty_arr = data.price;
			//alert(qty);
			var qty_div = '<select class="form-control" name="quantity" id="product_quantity_id" >';
			for ( var i = 0, l = qty_arr.length; i < l; i++ ) {	
					qty_div +='<option value="'+qty_arr[i].encode_quantity+'">'+qty_arr[i].quantity+'</option>';
				}
			qty_div += '</select>';
			$('#squantity').html(qty_div);
			var transport = '<select name="transpotation" class="form-control" >';
			if(qty.air == 1)
		{
			if(qty.checked == 'air')
			transport +='<option  value="air" selected="selected" onclick="test()">Air</option>';
			else
			transport +='<option   value="air" onclick="test()">Air</option>';
		}
		if(qty.sea == 1)
		{
			if(qty.checked == 'sea')
			 transport +='<option value="sea" selected="selected" onclick="test()">Sea</option>';
			 else
			 transport +='<option value="sea" onclick="test()">Sea</option>';
		}
		if(qty.pickup == 1)
		{
			if(qty.checked == 'pickup')
			transport +='<option value="pickup" selected="selected" onclick="test()">Pickup</option>';
			else
			transport +='<option value="pickup" onclick="test()">Pickup</option>';
		}
			transport +='</select>';
		
			$('#transport_div').html(transport);
			$('#product').val(val.product_id);
			$('#product').prop('disabled', 'disabled');
			 if(val.printing_option =='With Printing')
			 {
				printing_option = 1;
			}
			else
			{
				printing_option = 0;
			}
			$('#printing').val(printing_option);
			$('#printing').prop('disabled', 'disabled');
		
			$('#printing_effect').val(val.printing_effect_id);
			$('#printing_effect').prop('disabled', 'disabled');
			$('#layer').val(val.encode_layer);
			$('#layer').prop('disabled', 'disabled');
			setLayerHtml(val.encode_layer);
			$('#shipping_country_id').val(val.shipment_country_id);
			$('#shipping_country_id').prop('disabled', 'disabled');
			$('#height').val(parseInt(val.height));
			$('#width').val(parseInt(val.width));
			$('#gusset').val(parseInt(val.gusset));
			$('#height').prop('disabled', 'disabled');
			$('#width').prop('disabled', 'disabled');
			$('#gusset').prop('disabled', 'disabled');
			$('#product_quotation_id').val(val.product_quotation_id);
			$('#valve').val(qty.valve);
			$('#valve').prop('disabled', 'disabled');
			$('#zipper').val(qty.product_zipper_id);
			$('#zipper').prop('disabled', 'disabled');
			$('#spout').val(qty.product_spout_id);
			$('#spout').prop('disabled', 'disabled');
			$('#accessorie').val(qty.product_accessorie_id);
			$('#accessorie').prop('disabled', 'disabled');
		
				
			
			var make = $("input[type=radio][name=make]");
			for (var i = 0; i < make.length; ++i) {
				  $("input[type=radio][id=" + make[i].id + "]").attr("disabled",true);
				if(make[i].value == qty.make_pouch)
				{
					$("#"+make[i].id).prop("checked", true);
				//alert(span[i].id);
				}
				else
				{
					$(make[i].id).checked = false;  
				}
			}			
		}
	}
	});
	}
	else
	{
		$('#add-product-div').load(location.href + " #add-product-div");
	}
//	location.reload();
}

	
function test() {
	var transpotation=$('input[name=transpotation]:checked').val();
	var product_quotation_id = $('#product_quotation_id').val();
	var quotation_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getQuotionQTYData', '',1);?>");
	$.ajax({
			method : 'post',
			url: quotation_url,
			data : {transpotation : transpotation,product_quotation_id:product_quotation_id},
			success: function(response) {
			
				var qty_arr = $.parseJSON(response); 
		
			var qty_div = '<select class="form-control" name="quantity" id="product_quantity_id" >';
			for ( var i = 0, l = qty_arr.length; i < l; i++ ) {	
					qty_div +='<option value="'+qty_arr[i].encode_quantity+'">'+qty_arr[i].quantity+'</option>';
				}
				qty_div += '</select>';
				$('#squantity').html(qty_div);
			}
	});
};

function layeronchange(){
setLayerHtml($("#layer").val());
	var layerid = $(this).val();
	setLayerHtml(layerid);
}
    jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#order-form").validationEngine();
		var quotation_no=$('#quotation_no').val();
		if(quotation_no != '')
		{
			frm_fill();
		}
		$("#tax_div").hide();
		var country =  $("#shipping_country_id").val();
		if(country == 111) {
			$("#tax_div").show();
			$("#trans").val('');
			$("#air").hide();
			$("#sea").hide();
			$("#pickup").show();
		}
			$("#shipping_country_id").change(function(){
				$("#trans").val('');
			var country_id = $(this).val();
			if(country_id == 111)
			{
				$("#tax_div").show();
				$("#air").hide();
				$("#sea").hide();
				$("#pickup").show();
			
			}
			else
			{
				$("#tax_div").hide();
				$("#air").show();
				$("#sea").show();
				$("#pickup").hide();
			
			
		
			}
		});
		
/*
		setLayerHtml($("#layer").val());
		$("#layer").change(function(){
			var layerid = $(this).val();
			setLayerHtml(layerid);
		});
		*/
		$(".quantity_in").hide();
		$("#product").change(function(){
			var val = $(this).val();
			var text = $("#product option[value='"+val+"']").text().toLowerCase();
			//alert(text);
			if(text === "roll"){
				$(".gusset").hide();
				$(".option").hide();
				$(".quantity_in").show();
				$(".heightb").html("Repeat Length");
				$("#btn_generate").attr('name','btn_rgenerate');
				//setQuantityHtml('r');
			}else{
				$(".gusset").show();
				$(".option").show();
				$(".quantity_in").hide();
				$(".heightb").html("Height");
				$("#btn_generate").attr('name','btn_generate');
				//setQuantityHtml('p');
			}
			checkGusset();
		});
		
		checkGusset();
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
    });


function checkGusset(){
	
	var gusset_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=checkGusset', '',1);?>");
	$.ajax({
		type: "POST",
		url: gusset_url,
		dataType: 'json',
		data:'product_id='+$('#product').val(),
		success: function(response) {
			if(response==1){
				$('.gusset').show();	
			}else{
				$('.gusset').hide();
			}
		}
	});
}

//STart : layer
function setLayerHtml(layer){
	/*var html = '';
	$('#layerdiv').html('');
	for ( var i = 1; i <= layer; i++ ) {
		html += '<div class="form-group">';
		html += '<label class="col-lg-3 control-label">'+i+' Layer price / kg </label>';
		html += '	<div class="col-lg-4">';
		html += '  		<input type="text" name="layer_price[]" id="price_'+i+'" value="" class="form-control validate[required]">';
		html += '	</div>';
		html += '</div>';
	}
	$('#layerdiv').append(html);*/
	$("#loading").show();
	$('#layerdiv').html('');
	$.ajax({
		type: "POST",
		url: '<?php echo HTTP_ADMIN_CONTROLLER.$rout;?>/getLayerMaterial.php',
		dataType: 'json',
		data:'layer='+layer,
		success: function(json) {
			$('#layerdiv').append(json);
			var product_quotation_id=$('#product_quotation_id').val();
			
			var quotation_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getQuotionMaterial', '',1);?>");
			$.ajax({
				method : 'post',
				url: quotation_url,
				data : {product_quotation_id : product_quotation_id},
				success: function(response) {	//	alert(response);			
					var material = $.parseJSON(response);
				//	alert($('#material_1').val());
					for ( var i = 0, l = material.length; i < l; i++ ) {	
   						$('#material_'+material[i].layer).val(material[i].material_id);
						getMaterialThickness(material[i].material_id,i+1);
						if(product_quotation_id)
						{
							var c = i+1;
							$('#material_'+material[i].layer).prop('disabled', 'disabled');		
						}
					}
					
				}
			});			
			$("#loading").hide();
			var quotation_no=$('#quotation_no').val();
			if(quotation_no=='')
			setQuantityHtml('p');
		}
	});
}	
//Close : Layer

function getMaterialThickness(material_id,id){
	$("#loading").show();
	
	$.ajax({
		type: "POST",
		url: '<?php echo HTTP_ADMIN_CONTROLLER.$rout;?>/getMaterialThickness.php',
		dataType: 'json',
		data:'material_id='+material_id,
		success: function(json) {
			$('#thickness-dropdown-'+id).html(json);
			var product_quotation_id=$('#product_quotation_id').val();
			
			var quotation_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getQuotionMaterial', '',1);?>");
			$.ajax({
				method : 'post',
				url: quotation_url,
				data : {product_quotation_id : product_quotation_id},
				success: function(response) {	//	alert(response);			
					var material = $.parseJSON(response);
				//	alert($('#material_1').val());
					for ( var i = 0, l = material.length; i < l; i++ ) {	
   						$('#thickness-dropdown-'+material[i].layer).val(material[i].material_thickness);	
						if(product_quotation_id)
						{
							var c = i+1;
							$('#thickness-dropdown-'+material[i].layer).prop('disabled', 'disabled');		
						}				
					}
				}
			});			
			$("#loading").hide();
		}
	});
	var quotation_no=$('#quotation_no').val();
	if(quotation_no=='')
	setQuantityHtml('p');
}

function reloadPage(){
	location.reload();
}
$( "#order-form" ).submit(function( ) {
 $('#shipping_country_id').prop('disabled', false);
});
$("#same-above").click(function(){
	$("#loading").show();
	if($(this).prop('checked') == true){
		$("#billing-details").slideUp('slow');
	}else{
		$("#billing-details").slideDown('slow');
	}
	//$("#cinfo").slideUp().html(chtml).slideDown();
	$("#loading").fadeOut();
});

function setQuantityHtml(type){
	$("#loading").show();
	var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getMaterialQuantity', '',1);?>");
	var quantity_in;
	if(type=='r'){
		quantity_in = $('#quantity_type').val(); 	
	}else{
		quantity_in = '';	
	}
	var product_id = $('#product').val();
	$.ajax({
		type: "POST",
		url: status_url,
		dataType: 'json',
		data:$("#layerdiv table tbody tr td:nth-child(2) select"),
		success: function(json) {
			if(json){
				$("#squantity").html(json);
			}else{
				$("#squantity").html('<span class="btn btn-danger btn-xs">Please select material for quantity option.</span>');
			}
			$("#loading").hide();
		}
	});
}

$('#quantity_type').change(function(){
	$(".gusset").hide();
	$(".option").hide();
	$(".quantity_in").show();
	$(".heightb").html("Repeat Length");
	$("#btn_generate").attr('name','btn_rgenerate');
	var quotation_no=$('#quotation_no').val();
	if(quotation_no=='')
	setQuantityHtml('r');	
});


function updateQuantity(product_id){
	alert($('#edit-quantity-'+product_id).val());
}

function removeProduct(order_product_id){
$("#alertbox_"+order_product_id).modal("show");
$(".modal-title").html("Delete product".toUpperCase());
$("#setmsg").html("Are you sure you want to delete ?");
$("#popbtnok_"+order_product_id).click(function(){
//	var con = confirm("Are you sure you want to delete ?");

	var remove_product_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=removeProduct', '',1);?>");
	$.ajax({
		url : remove_product_url,
		method : 'post',
		data : {order_product_id : order_product_id},
		success: function(response){
		if(response == 0) {
			$("#results").hide();
			$("#myModal").hide();
		}	
			$('#order_product_id_'+order_product_id).html('');

			$('#display-image-1 img').attr('src','<?php echo HTTP_SERVER.'images/blank-user64x64.png'; ?>');
			product_count--;
			if(product_count<=0){
				$('#footer-div').hide();	
			}
			set_alert_message('Product successfully deleted','alert-success','fa fa-check');
		
		},
		error: function(){
			return false;	
		}
	});
$("#alertbox_"+order_product_id).hide();
$("#alertbox_"+order_product_id).modal("hide");
 });
}
/*$('#btn-add-order').click(function(){
//$('#shipping_country_id').prop('disabled', false);								   
//	$("#empty-item").show();

	if($("#order-form").validationEngine('validate')){
		var add_product_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=addProductOrder', '',1);?>");
		var postData = $("#order-form").serialize();		
		$.ajax({
			url : add_product_url,
			method : 'post',		
			data : {postData : postData},
			success: function(response){ 
			if(response != 0){
				   $('#display-product').html(response);				   
				}
			},
			error: function(){
				return false;
			}		
		});
	}
});*/
function btn_order_redirect()
{
	$('#shipping_country_id').prop('disabled', false);
	if($("#order-form").validationEngine('validate')){
		var url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=addProductOrder', '',1);?>");
		var postData = $("#order-form").serialize();
		$.ajax({
			url : url,
			method : 'post',		
			data : {postData : postData},
			success: function(response){ 
			if(response != 0){
				   $('#display-product').html(response);
				   $('#shipping_country_id').prop('disabled', true);
				}
			},
			error: function(){
				return false;
			}		
		});
	}
	
}
$('#btn-add-product').click(function(){
	//$("#add-product-div").find('input').val('');
	//$("#add-product-div").find('select').prop('selectedIndex','0');	
	//return false;
	if($("#order-form").validationEngine('validate')){
    	
		$('#product').prop('disabled', false);
		$('#quotation_no').prop('disabled', true);
		//$('#product').prop('disabled', true);
		$('#order_currency').prop('disabled', false);
		$('#printing').prop('disabled', false);
		$('#printing_effect').prop('disabled', false);
		$('#layer').prop('disabled', false);
		$('#shipping_country_id').prop('disabled', false);
		$('#height').prop('disabled', false);
		$('#width').prop('disabled',false);
		$('#gusset').prop('disabled', false);
		$('#valve').prop('disabled', false);
		$('#zipper').prop('disabled', false);
		$('#spout').prop('disabled', false);
		$('#accessorie').prop('disabled', false);
		$("input[type=radio][name=make]").prop('disabled', false);	
		var layer = $("#layer :selected").text();
		//
		for(var i=1;i<=layer;i++)
		{
			$('#material_'+i).prop('disabled', false);	
			$('#thickness-dropdown-'+i).prop('disabled', false);		
		}
		var add_product_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=addProduct', '',1);?>");
	//	var make=$("input[type='radio'][name='make']:checked").val();
	//	alert(make);

		var formData = $("#order-form").serialize();
		$('#product').prop('disabled', true);
		$.ajax({
			url : add_product_url,
			method : 'post',		
			data : {formData : formData},
			success: function(response){
				if(response != 0){
				   $('#display-product').html(response);
				   $('#shipping_country_id').prop('disabled', true);
				   
				}
			},
			error: function(){
				//$("html, body").animate({scrollTop:0},600);
				return false;
			}
		
		});
		var quotation_no=$('#quotation_no').val();
		if(quotation_no)
		{
				$('#product').prop('disabled', true);
				$('#order_currency').prop('disabled',true);
				$('#printing').prop('disabled', true);
				$('#printing_effect').prop('disabled', true);
				$('#layer').prop('disabled', true);
				$('#shipping_country_id').prop('disabled', true);
				$('#height').prop('disabled', true);
				$('#width').prop('disabled',true);
				$('#gusset').prop('disabled', true);
				$('#valve').prop('disabled', true);
				$('#zipper').prop('disabled', true);
				$('#spout').prop('disabled', true);
				$('#accessorie').prop('disabled', true);
				$("input[type=radio][name=make]").prop('disabled', true);	
				var layer = $("#layer :selected").text();
			//
			for(var i=1;i<=layer;i++)
			{
				$('#material_'+i).prop('disabled', true);	
				$('#thickness-dropdown-'+i).prop('disabled', true);		
			}
		}
	}else{
		return false;
	}
});

$('.media-body').on('change','#art-image',function(){	
	count += 1;
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajaximage', '',1);?>");
	$('#loading').show();
	var img_html = '';
	var file_data = $("#art-image").prop("files")[0];          // Getting the properties of file from file field
	//var file_data = $("#art-image").val();
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
				//s$('#display-image-'+count+' img').attr('src',JSON.parse(response));
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
	//$('#loading').show();
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
					//s$('#display-image-'+count+' img').attr('src',JSON.parse(response));
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
					//s$('#display-image-'+count+' img').attr('src',JSON.parse(response));
					$('#loading').remove();
					
					/*die_html = '<div class="input-group media-body_1" id="die-preview-'+die_count+'">';			
					   die_html +='<a style="text-align:left;" class="btn btn-default btn-block">'+response+'</a>';			
					   die_html +='<span class="input-group-btn">';				
						  die_html +='<a onclick="removefile('+die_count+')" class="btn btn-danger remove_i"><i class="fa fa-times"></i></a>';
					   die_html +='</span>';		
					die_html += '</div>';
					
					$('#append-dieline').append(die_html);
					//s$('#display-image-'+count+' img').attr('src',JSON.parse(response));
					$('#loading').remove();*/
				}
			}else{
				$('#loading').remove();
				set_alert_message('Only .pdf And .jpg Formate Allow','alert-danger','fa fa-warning');
			}
			
			/*if(response!=0){
				
				die_html = '<div class="input-group media-body_1" id="die-preview-'+die_count+'">';			
                   die_html +='<a style="text-align:left;" class="btn btn-default btn-block">'+response+'</a>';			
                   die_html +='<span class="input-group-btn">';				
                   	  die_html +='<a onclick="removefile('+die_count+')" class="btn btn-danger remove_i"><i class="fa fa-times"></i></a>';
                   die_html +='</span>';		
                die_html += '</div>';
				
				
				$('#append-dieline').append(die_html);
				//s$('#display-image-'+count+' img').attr('src',JSON.parse(response));
				$('#loading').remove();
			}else{
				$('#loading').remove();
				set_alert_message('Only .pdf Formate Allow','alert-danger','fa fa-warning');
			}*/
		}
   });
});

function removeImage(count){
	
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=removeImage', '',1);?>");
	$('#loading').show();
	
	$.ajax({
		url: url,
		data: {image_id : count},       
		type: 'post',
		success : function(){
			$('#loading').remove();
			$('#preview-'+count).remove();
			
			if($('.file-preview .file-preview-thumbnails').children().size()==0){
				$('.file-preview').css('display','none');	
			}
		}
	});
}

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

$("#width").keydown(function(e) {
			// alert(e.keyCode);
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
						//alert(response);
							if(response=="Got")
							{
								$("#widthsugg").hide();
								$("#gussetsugg").hide();
							}
							else
							{
								var val = $.parseJSON(response);
								//alert(val);
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
										//alert(response);
										//response = response.split("==");				
									if(response=="Got")
										$("#gussetsugg").hide();
									else
									{
										var val = $.parseJSON(response);
										//alert(val.gusset);
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
					//alert(response);
						//response = response.split("==");							
							if(response=="Got")
								$("#gussetsugg").hide();
							else
							{
								var val = $.parseJSON(response);
								//alert(val.gusset);
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

</script> 
<!-- Close : validation script -->

<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>