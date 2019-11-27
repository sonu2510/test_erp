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
		//printr($post);die;
		//$valid = $obj_quotation->checkQuantity($post['quantity'],$post['material']);
		
		//if(empty($valid)){
			if(isset($post['quantity']) && !empty($post['quantity'])){
				$last_id = $obj_quotation->addQuotation($post);
				if($last_id == "Error"){
					$obj_session->data['warning'] = 'Error : Please fillup form carefully!';
				}else{
					$obj_session->data['success'] = 'Your quotation generated!';
					//echo $obj_general->link($rout, '&mod=view&quotation_id='.encode($last_id), '',1);die;
					page_redirect($obj_general->link($rout, '&mod=view&quotation_id='.encode($last_id), '',1));
					//die;
				}
			}else{
				$obj_session->data['warning'] = 'Error : Please fillup form carefully!';
			}
		/*}else{
			$quantity_error = $valid[0]['name'].' has minimum quantity of '.$valid[0]['quantity'];	
		}*/
	}
	
	if(isset($_POST['btn_rgenerate'])){
		$post = post($_POST);		
		//printr($post);die;
		$last_id = $obj_quotation->addRollQuotation($post);
		//printr($last_id);die;
		if($last_id == "Error"){
			$obj_session->data['warning'] = 'Error : Please fillup form carefully!';
		}else{
			$obj_session->data['success'] = 'Your quotation generated!';
			page_redirect($obj_general->link($rout, '&mod=view&quotation_id='.encode($last_id), '',1));
		//die;
		}		
	}
	
	//Add quotation
	if(isset($_POST['btn_save'])){
		//printr($obj_session->data['repost']);die;
		
		$postData = unserialize($obj_session->data['repost']);
		unset($obj_session->data['repost']);
		//printr($postData);die;
		$insert_id = $obj_quotation->addQuotation($postData);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
		//die;
	}
	
	$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
	$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
	$userCurrency = $obj_quotation->getUserCurrencyInfo($user_type_id,$user_id);
	//printr($userCurrency);
	$allow_currency_status = $obj_quotation->allowCurrencyStatus($user_type_id,$user_id);
	$addedByInfo = $obj_quotation->getUser($user_id,$user_type_id);
	//printr($userCurrency);die;
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
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span> Customer Name</label>
                        <div class="col-lg-8">
                             <input type="text" name="customer" placeholder="Customer Name" value="<?php echo isset($post['customer']) ? $post['customer'] : '';?>" class="form-control validate[required]">
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
						 // printr($currencys);
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
							echo $obj_quotation->getCountryCombo($selCountry);//214?>
                        </div>
                      </div>
                    <?php //printr($_SESSION); 
					
					
						if($_SESSION['ADMIN_LOGIN_USER_TYPE']==4)
						{
							$adminId = $obj_quotation->getEmpAdminId($_SESSION['ADMIN_LOGIN_SWISS']);
							$adminCountryId = $obj_quotation->getUser($adminId,$_SESSION['ADMIN_LOGIN_USER_TYPE']);
							//printr($adminCountryId);
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
                        
                          
                    <!--   <div class="form-group" id="cst_div">
                        <label class="col-lg-3 control-label"></label>
                        <div class="col-lg-8">
                        <?php /*	echo '<div style="float:left;width: 200px;">';
								echo '	<label  style="font-weight: normal;">';                                 
								echo '	<input type="radio" name="taxation_form" id="taxation_form" value="cst_with_form_c" checked="checked" > ';								
								echo '	 CST With Form C </label></div><div  style="float:left;width: 200px;">';
								echo '	<label style="font-weight: normal;">';                                 
								echo '	<input type="radio" name="taxation_form" id="taxation_form" value="cst_without_form_c" > ';								
								echo '	CST With Out Form C </label>';
								echo '</div>';*/?>
                          </div>
                        </div>-->
                        
                        
                      <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span> Select Product</label>
                        <div class="col-lg-8">
                            <?php
                            $products = $obj_quotation->getActiveProduct();
                            ?>
                            <select name="product" id="product" class="form-control validate[required]">
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
                      
                       <div class="form-group">
                        <label class="col-lg-3 control-label">Make Pouch</label>
                        <div class="col-lg-8">
                                <?php
                                $sel_make = array();
                                if(isset($material_make) && !empty($material_make) && $material_make){
                                    $sel_make = explode(',',$material_make);
                                }
                                //printr($sel_layer);die;
                                $makes = $obj_quotation->getActiveMake();
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
                             <input type="text" name="height" value="<?php echo isset($post['height'])?$post['height']:'';?>" class="form-control validate[required,custom[onlyNumberSp]]">
                        </div>
                      </div>
                      
                      <div class="form-group gusset">
                        <label class="col-lg-3 control-label"><span class="required">*</span>Gusset </label>
                        <div class="col-lg-7">
                             <?php /* <input type="text" name="gusset" value="<?php echo isset($post['gusset'])?$post['gusset']:'';?>" class="form-control validate[required,custom[onlyNumberSp]]"> */?>
                             <div class="input-group">
                             	<input type="text" name="gusset" id="gusset_input" value="<?php echo isset($post['gusset'])?$post['gusset']:'';?>" class="form-control validate[required,custom[onlyNumberSp]]">
                                
                                	<span class="input-group-btn">
                                    	<button type="button" class="btn btn-danger"> <i class="fa fa-warning"></i> Please enter one side or single gusset only.</button>
                                    </span>             
                                   
                             </div> <span id="gussetsugg" style="color:blue;font-size:11px;"></span>   
                        </div>
                        <?php /* <div class="col-lg-4">
                        	<button class="btn btn-danger btn-sm"> <i class="fa fa-warning"></i> Please enter one side or single gusset only.</button>
                        </div> */?>
                      </div>
                      
                      <div class="form-group" >
                        <label class="col-lg-3 control-label"><span class="required">*</span> Select Printing Option</label>
                        <div class="col-lg-3">
                        	<select name="printing" id="printing" class="form-control validate[required]">
                                <option value="1" <?php echo (isset($post['printing']) && $post['printing'] == 1)?'selected':'';?> >With Printing</option>
                                <?php /* <option value="0" <?php echo (isset($post['printing']) && $post['printing'] == 0)?'selected':'';?>>Without Printing</option> */?>
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
                                <?php
                                foreach($layers as $layer){
                                    /*if(isset($post['layer']) && $post['layer'] == $layer['product_layer_id']){
                                        echo '<option value="'.encode($layer['product_layer_id']).'" selected="selected">'.$layer['layer'].'</option>';
                                    }else{*/
                                        echo '<option value="'.encode($layer['product_layer_id']).'">'.$layer['layer'].'</option>';
                                    //}
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
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Transportation</label>
                        <div class="col-lg-9">
                         	<div class="checkbox ch1" style="float: left; width: 30%;">
                                <label>
                                  <input type="checkbox" name="transpotation[]" value="<?php echo encode('air');?>" class="validate[minCheckbox[1]]">
                                  By Air
                                 </label>
                             </div>
                             <div class="checkbox ch2" style="float: left; width: 30%;">
                                <label>
                                  <input type="checkbox" name="transpotation[]" value="<?php echo encode('sea');?>" class="validate[minCheckbox[1]]">
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
                                      <input type="checkbox" name="transpotation[]" value="<?php echo encode('pickup');?>" class="validate[minCheckbox[1]]">
                                      Factory Pickup
                                     </label>
                                 </div>
								 <?php } ?>
                         </div>
                      </div>                    
                      
                   <div class="form-group">
                        <div class="col-lg-9 col-lg-offset-3">
                        <button type="submit" name="btn_generate" id="btn_generate" class="btn btn-primary">Generate </button>	
                        <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a>
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
                /*var url ="<?php echo $obj_general->link($hrefrout,'mod=add&product_id=4', '',1);?>";
                var windowName = "popUp";//$(this).attr("name");
                var windowSize = windowSizeArray[ $(this).attr("rel") ];
                window.open(url, windowName, windowSize);
                event.preventDefault();*/								 
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
		
	/*	$(document).ready(function() {
	   $('#Popup').click(function() {
	     var NWin = window.open($(this).prop('href'), '', 'height=800,width=800');
	     if (window.focus)
	     {
	       NWin.focus();
	     }
09	     return false;
10	    });
11	});​*/
 
    jQuery(document).ready(function(){	
			checkZipper();
        // binds form submission and fields to the validation engine
        jQuery("#form").validationEngine();		
			$("#country_id").change(function(){
			var country_id = $(this).val();
			if(country_id == 111)
			{
				$("#tax_div").show();				
			}
			else
			{
			$("#tax_div").hide();			
			}
		});		
	/*
		$('#form input[name="taxation"]').on('change', function() {
		   var tax = $('input[name="taxation"]:checked', '#form').val(); 
		   if(tax == 'cst')
		   {
				$("#cst_div").show();	
			}
			else
			{
			$("#cst_div").hide();	
			}
		});*/		
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
			var text = $("#product option[value='"+val+"']").text().toLowerCase();
			//alert(text);
			if(text === "roll"){
				$(".gusset").hide();
				$('#gusset_input').val("");
				//$(".zipper").hide();
				$(".option").hide();
				$(".quantity_in").show();
				$(".heightb").html("Repeat Length");
				$("#btn_generate").attr('name','btn_rgenerate');
				//setQuantityHtml('r');
			}else{
				$(".gusset").show();
				//$(".zipper").show();
				$(".option").show();
				$(".quantity_in").hide();
				$(".heightb").html("Height");
				$("#btn_generate").attr('name','btn_generate');
				//setQuantityHtml('p');
			}			
			checkGusset();
			checkZipper();			
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

//STart : layer
function setLayerHtml(layer,make){	
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

//alert(layer);
//alert(make);
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
			//	alert($("#gusset_input").val());
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
	$("#loading").show();
	var status_url = '<?php echo HTTP_ADMIN_CONTROLLER.$rout;?>/getMaterialEffect.php';
	new_material_id = $('#material_1').val();
	$.ajax({			
		url : status_url,
		type :'post',
		data :{material_id:new_material_id},
		success: function(json){
			//alert(json);
			if(json != '0'){
				$('#effectdiv').html(json);
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
			$("#loading").hide();
		}
	});
}

function reloadPage(){
	location.reload();
}

/*function setQuantityHtml(type){
	$("#loading").show();
	var status_url = getUrl("<?php // echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getQuantity', '',1);?>");
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
		data:{type:type,quantity_in:quantity_in,product_id:product_id},
		success: function(json) {
			$("#squantity").empty();
			$("#squantity").append(json['display']);
			
			if(json['gusset_available']==0){
				$('.gusset').hide();	
			}

			//$("input:checkbox").checkbox();
			$("#loading").hide();
		}
	});
}*/
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
	//alert(discount);
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
	//alert(discount);
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
</script> 
<!-- Close : validation script -->
<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>