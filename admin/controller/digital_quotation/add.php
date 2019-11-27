<?php
include("mode_setting.php");

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
	'icon' 	=> 'fa-list',
	'class'	=> 'active',
);

$limit = LISTING_LIMIT;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}

$class = 'collapse';

$filter_data= array();
if(isset($_POST['btn_filter'])){
	$class = '';
	$filter_edit = 1;
	$class ='';	
	if(isset($_POST['filter_measurement'])){
		$filter_measurement=$_POST['filter_measurement'];		
	}else{
		$filter_measurement='';
	}	
	
	if(isset($_POST['filter_status'])){
		$filter_status=$_POST['filter_status'];
	}else{
		$filter_status='';
	}
		
	$filter_data=array(
		'measurement' => $filter_measurement,
		'status' => $filter_status
	);
}

if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'DESC';
	//$sort_by ='sort_by=STOCK';	
}
if($display_status){
	$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
	$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
	//$userCurrency = $obj_digital->getUserCurrencyInfo($user_type_id,$user_id);
	$addedByInfo = $obj_digital->getUser($user_id,$user_type_id);
	
}

$add_url='';
if($display_status) {
	/*$checkNewCartPermission = $obj_template->checkNewCartPermission($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE']);
	$orderLimit = $obj_template->orderLimit($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE']);
	$permission = '';
	for($i=1;$i<$orderLimit;$i++)
	{
		if($checkNewCartPermission[0]['order_s_no'] == $i)
		{
			$permission =$i+1;
		}		
	}
	if($checkNewCartPermission[0]['order_s_no'] == '')
	{
		$permission =1;
	}
	
	
	if(isset($_POST['action']) && ($_POST['action'] == "active" || $_POST['action'] == "inactive") && isset($_POST['post']) && !empty($_POST['post']))
	{
		$status = 0;
		if($_POST['action'] == "active"){
			$status = 1;
		}
		$obj_templateorder->updateStatus($status,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	
	}else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
	 	$obj_templateorder->updateStatus(2,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}*/
	 if(isset($_POST['btn_gen']))
	 {
	    $obj_digital->saveQuotation($_POST['digital_quotation_id']);
	    if(!empty($addedByInfo['lang_id']) && $addedByInfo['lang_id']=='86')
		{
		    $obj_digital->sendQuotationEmailInOtherLang($_POST['digital_quotation_id']);
		}
	    $obj_digital->Digital_quotation_mail($_POST['digital_quotation_id']);
	    page_redirect($obj_general->link($rout, '', '',1));
	 }
	 
	$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
	$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];

?>
<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-list"></i> <?php echo $display_name;?></h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>   
      <div class="col-lg-12" >
        <section class="panel">
         <div class="panel-body table-responsive">
     
         <?php   /*$templatedetails =$obj_template->gettemplatetitle($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],'','','','');*/
		 			//printr( $templatedetails);
			  ?>
                	<form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
                            <div class="form-group">
                                <label class="col-lg-3 control-label">Printing Type</label>
		                        <div class="col-lg-9">                
		                        	<div  class="checkbox ch1" style="float:left;width: 200px;">
		                                <label  style="font-weight: normal;">
		                                  <input type="radio" name="stock_print" checked="checked" id="digital_print2" value="Digital Print"  />Digital Printing
		                                </label>
		                          </div>
		                           <div class="checkbox ch2" style="float:left;width: 200px;">
		                                <label  style="font-weight: normal;">
		                                  	<input type="radio" name="stock_print" id="foil_print1"  value="Foil Print" disabled>Foil Stamping
		                               </label>
		                          </div>
		                          <div class="checkbox ch2" style="float:left;width: 250px;">
		                                <label  style="font-weight: normal;">
		                                  	<input type="radio" name="stock_print" id="foil_digi_print1" value="Foil & Digital Print" disabled>Foil Stamping & Digital Printing
		                               </label>
		                          </div>
                                </div>  
                            </div>
                           <div class="form-group">
                                <label class="col-lg-3 control-label"><span class="required">*</span> Client Name</label>
                                <div id="holder" class="col-lg-4"> 
                                        <input type="text" id="keyword" name="customer" tabindex="0" class="form-control validate[required]"  placeholder="Customer Name" autocomplete="off">
                                        <input type="hidden" id="address_book_id" name="address_book_id" value=""/>
                                        <div id="ajax_response"></div>
                                </div>
                        </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><span class="required">*</span>Client Email</label>
                                <div class="col-lg-4">
                                     <input type="text" name="email" placeholder="Customer Email" value="<?php echo isset($post['email']) ? $post['email'] : '';?>" class="form-control validate[required,custom[email]]" id="email">                                      
                                </div>
                             </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><span class="required">*</span>Shipment Country</label>
                                <div class="col-lg-4">
                                	<?php
        							$selCountry = '';
        							if(isset($addedByInfo['country_id']) && $addedByInfo['country_id']){
        								$selCountry = $addedByInfo['country_id'];
        							}
        							echo $obj_digital->getCountryCombo($selCountry);//214?>
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
                                <input type="hidden" name="con_id" id="con_id" value="<?php echo $addedByInfo['country_id'];?>">
                                 <?php if($addedByInfo['country_id']=='252'||$addedByInfo['country_id']=='225'||$addedByInfo['country_id']=='189' ||$addedByInfo['country_id']=='42' ||$addedByInfo['country_id']=='47' ||$addedByInfo['country_id']=='238'||$addedByInfo['country_id']=='112' ||$addedByInfo['country_id']=='251'|| $addedByInfo['country_id']=='90'|| $addedByInfo['country_id']=='172'||$addedByInfo['country_id']=='170'||$addedByInfo['country_id']=='230'||$addedByInfo['country_id']=='253'||$addedByInfo['country_id']=='209'){
								  ?>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label">Inquiry Reference No.</label>
                                            <div class="col-lg-4"> 
                                                   <input type="text" id="ref_no" name="ref_no" placeholder="Reference No" class="form-control " >
                                            </div>
                                        </div>
                                <?php }  ?>
                                
                            <div class="form-group" id="make_div">
                                <label class="col-lg-3 control-label">Make Pouch</label>
                                <div class="col-lg-8" class="make_class">
                                    <?php
                                    $makes = $obj_digital->getActiveMake();
                                  
                                    foreach($makes as $make){
                                         echo '<div  style="float:left;width: 200px;">';
                                        echo '	<label  style="font-weight: normal;">';
                                        if($make['make_id']==1){
                                            echo '<input type="radio" class="make" name="make" id="'.$make['make_id'].'" value="'.$make['make_id'].'" onclick="getProduct()" checked="checked" > ';
                                        }
    									else{
    									    echo '<input type="radio" class="make" name="make" id="'.$make['make_id'].'" onclick="getProduct()" value="'.$make['make_id'].'" > ';
    									}
                                        echo '	 '.$make['make_name'].' </label>';
                                        echo '</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                            
                            <div class="form-group" id="product_dropdown"></div>
                              <div class="form-group">
                                    <label class="col-lg-3 control-label"><span class="required">*</span>Select Size (WXHXG)</label>
                                    <div class="col-lg-9" id="size_div" style="float: left; width: 30%;">
                                        <span class="btn btn-danger btn-xs">Please select Product for Size option.</span>
                                     </div>
                              </div>
                              <div id="color_div" class="form-group"></div>
                              <div class="form-group option" id="valve_id">
                                <label class="col-lg-3 control-label"> Valve</label>
                                <div class="col-lg-9">                
                                	<div  style="float:left;width: 200px;">
                                        <label  style="font-weight: normal;">
                                         <input type="radio" name="valve" id="nv" value="No Valve" checked="checked" >
                                            No Valve
                                         </label>
                                     </div>
                                     <div style="float:left;width: 200px;">
                                        <label  style="font-weight: normal;">
                                         <input type="radio" name="valve" id="wv" value="With Valve" >
                                      	   With Valve
                                         </label>
                                      </div> 
                                </div>
                              </div>
                              <div id="zipper_div" class="form-group"><input type="hidden" id="zipper" name="zipper" value=""/><input type="hidden" id="weight" name="weight" value=""/></div>
                               <div class="form-group" id="spout_div" style=-"display:none;">
                                      <?php
                                          $spouts = $obj_digital->getActiveProductSpout();
                    					  if($spouts){
                    						  ?>
                    						    <label class="col-lg-3 control-label">Spout</label>
                                                    <div class="col-lg-9">
                                                       <?php
                                                       $spoutsTxt = '';
                                                        foreach($spouts as $spout){
                                                           $spoutsTxt .= '<div  style="float:left;width: 200px;">';
                                                                $spoutsTxt .= '<label  style="font-weight: normal;">';
                    											if($spout['product_spout_id'] == 1 )
                    											{
                                                                    $spoutsTxt .= '<input type="radio" class="spout" name="spout" value="'.$spout['product_spout_id'].'" checked="checked">';
                    											}
                    											else 
                    											{
                    												$spoutsTxt .= '<input type="radio" class="spout" name="spout" value="'.$spout['product_spout_id'].'" >';
                    											}
                                                                $spoutsTxt .= ''.$spout['spout_name'].'</label>';
                                                            $spoutsTxt .= '</div>';
                                                        }
                                                        echo $spoutsTxt;
                                                        ?>
                                                    </div>
                                                
                                          	  	<?php
                    					  	} ?>
            					  	</div>
            					  	<?php
						  $accessories = $obj_digital->getActiveProductAccessorie();
						  if($accessories){
							  ?>
							  <div class="form-group option" id="acce_div">
									<label class="col-lg-3 control-label">Accessorie</label>
									<div class="col-lg-9">
									   <?php
									   $accessorieTxt = '';
										foreach($accessories as $accessorie){
										   $accessorieTxt .= '<div style="float:left;width: 200px;">';
												$accessorieTxt .= '<label  style="font-weight: normal;">';
												if($accessorie['product_accessorie_id'] == 4 )
												{
												    echo '<input type="radio" name="accessorie[]" value="'.$accessorie['product_accessorie_id'].'" checked="checked">'.' '.$accessorie['product_accessorie_name'].'<br>';
												}
												else
												{
												    /*if($accessorie['product_accessorie_id'] == 3 )
													{
														echo '<br><br><input type="checkbox" name="accessorie[]" value="'.$accessorie['product_accessorie_id'].'">'.' '.$accessorie['product_accessorie_name'].'<br>';
													}
													else if($accessorie['product_accessorie_id'] == 8)
													{
														echo '<input type="checkbox" name="accessorie[]" value="'.$accessorie['product_accessorie_id'].'">'.' '.$accessorie['product_accessorie_name'].'<br><div class="acc_div"></div>';
													}
													else if($accessorie['product_accessorie_id'] == 9)
													{
														echo '<input type="checkbox" name="accessorie[]" value="'.$accessorie['product_accessorie_id'].'">'.' '.$accessorie['product_accessorie_name'].'<br><div class="acc_div"></div>';
													}
													else
													{*/
														if($accessorie['product_accessorie_id'] != 3 && $accessorie['product_accessorie_id'] != 8 && $accessorie['product_accessorie_id'] != 9)
														echo '<input type="radio" name="accessorie[]" value="'.$accessorie['product_accessorie_id'].'">'.' '.$accessorie['product_accessorie_name'].'<br>';
													//}			
												}
												$accessorieTxt .= '</label>';
											$accessorieTxt .= '</div>';
										}
										?>
									</div>
								</div>
								<?php
							} ?>
                               
                             
                            <!--<div class="form-group option" style="display:none;">
                                <label class="col-lg-3 control-label"><span class="required">*</span>Product Code</label>
                                <div class="col-lg-4" id="holder">
                                   <input type="text" id="product_code"  class="form-control validate[required]" autocomplete="off" value="" name="product_code"  />
                                   <?php //$product_codes=$obj_digital->getActiveProductCode($filter_data); 
                                            
                                      //foreach($product_codes as $product){ 
                                       ?>
                                        <input type="hidden" name="product_code_id" id="product_code_id" value="<?php //echo $product['product_code_id'] ?>" />
                                     <?php //} ?>
                                       <div id="ajax_product"></div>
                                 	
                                </div>
                                <div class="col-lg-3" id="product_div"> 
                                       <input type="text" name="product_name" id="product_name"  value="" disabled="disabled" class="form-control validate" style="width:400px"/>
                                       <input type="hidden" name="color_id" id="color_id" value="" />
                                </div>
                            </div>-->
                  
                    
                       <div  class="form-group" id="filling_div"  style="display:none">
                         <label class="col-lg-3 control-label">Filling Selection</label>
                            <div class="col-lg-9">
                                <div  style="float:left;width: 200px;">
                                    <label  style="font-weight: normal;">
                                      <input type="radio" name="filling" id="from_top" value="Filling from Top" checked="checked"  class="valve"/>
                                        Filling from Top
                                  </label>
                                
                                    <label  style="font-weight: normal;">
                                        <input type="radio" name="filling" id="from_spout" value="Filling from Spout" class="valve"/>
                                  		  Filling from Spout
                                  </label>
                              </div> 
                          </div>
                      </div>
  			 
                    <div class="form-group" id="digital_print_div2">
                        <label class="col-lg-3 control-label">Front Side Color</label>
                          <div class="col-lg-1">
                               <div class="input-group">
                                  <span class="input-group-btn">
                                      <button type="button" class="btn btn-default btn-number f_minus" disabled="disabled" data-type="minus" data-field="front_color">
                                          <span class="glyphicon glyphicon-minus"></span>
                                      </button>
                                  </span>
                                  <input type="text" name="front_color" id="front_color" onchange="getcolordetail(1)" class="form-control input-number" value="1" min="1" max="4" readonly>
                                  <span class="input-group-btn">
                                      <button type="button" class="btn btn-default btn-number f_plus" data-type="plus" data-field="front_color">
                                          <span class="glyphicon glyphicon-plus"></span>
                                      </button>
                                  </span>
                              </div><!-- /input-group -->
                              </div>
                      </div>
                  <div class="form-group" id="digital_print_div1">
                          <label class="col-lg-3 control-label">Back Side Color</label>
                          <div class="col-lg-1">
                               <div class="input-group">
                                  <span class="input-group-btn">
                                      <button type="button" class="btn btn-default btn-number b_minus" disabled="disabled" data-type="minus1" data-field="back_color">
                                          <span class="glyphicon glyphicon-minus"></span>
                                      </button>
                                  </span>
                                  <input type="text" name="back_color" id="back_color" onchange="getcolordetail(2)" class="form-control input-number" value="0" min="0" max="4" readonly>
                                  <span class="input-group-btn">
                                      <button type="button" class="btn btn-default btn-number b_plus" data-type="plus1" data-field="back_color">
                                          <span class="glyphicon glyphicon-plus"></span>
                                      </button>
                                  </span>
                               </div><!-- /input-group -->
                              </div>
                      </div>
                      <div class="form-group" id="digital_print_div">
                        <label class="col-lg-3 control-label"><span class="required">*</span>Total Color Including Front & Back Side</label>
                      	  <div class="col-lg-4">
                      	  	<?php  $colors = $obj_digital->getActiveColorForDigitalPrint();?>
                                 <select name="digital_print_color" id="digital_print_color" class="form-control  validate[required]"  >
                                	<!--<option value="">Select Color</option>-->
                                	<?php foreach($colors as $color){?>
                                		<option value="<?php echo $color['pouch_color_id'].'=='.$color['color_value'];?>" color="<?php echo $color['color_value'];?>"><?php echo $color['color'];?></option>
                                	<?php }?>

							  </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span>Printing Effect Option</label>
                      	  <div class="col-lg-4">
                      	  	  <select name="effect" id="effect" class="form-control validate[required]">
                                	<!--<option value="">Select Printing Effect</option>-->
                                	<option value="Matt Finish Ink">Matt Finish Ink</option>
                                	<option value="Gloss Finish Ink">Gloss Finish Ink</option>
                                	<!--<option value="Matt & Glossy Finish">Matt & Gloss Finish</option>-->
                              </select>
                        </div>
                      </div>
                       <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span>Quantity</label>
                        <div class="col-lg-9">
                             <?php  $DigitalQty = $obj_digital->getDigitalQty($addedByInfo['user_id']);
                                    $dQty= explode(",",$DigitalQty['digital_quantity']);
                                    foreach($dQty as $qty){
                                        echo '<div class="checkbox" style="float: left; width: 30%;" id="div_check">';
                    						echo '<label>';
                    							echo '<input type="checkbox" name="quantity[]" value="'.$qty.'" class="validate[minCheckbox[1]]">'.$qty;
                    						echo  '</label>';
                    					echo '</div>';
                    				}?>           
                        </div>
                      </div>
                        <div class="form-group" style="display:none;">
                            <label class="col-lg-3 control-label">Transportation</label>
                            <div class="col-lg-4">
                             	<div class="checkbox r1" style="float: left; width: 30%;">
                                    <label>
                                      <input type="radio" name="transpotation" value="<?php echo 'By Air';?>" class="validate[minCheckbox[1]]" >
                                      By Air
                                     </label>
                                 </div>
                                 <div class="checkbox r2" style="float: left; width: 30%;">
                                    <label>
                                      <input type="radio" name="transpotation" value="<?php echo 'By Sea';?>" class="validate[minCheckbox[1]]"  >
                                      By Sea
                                     </label>
                                 </div>
                                 <?php 
    							 if($addedByInfo['country_id'] == '111'){
    								 ?>
                                 	 <div class="checkbox r3" style="float: left;  width: 30%;">
                                        <label>
                                          <input type="radio" name="transpotation" value="<?php echo 'Pickup';?>" class="validate[minCheckbox[1]]">
                                          Factory Pickup
                                         </label>
                                     </div>
                                 	 <?php
    							 } ?>
                             </div>
                          </div>
					  
                       <div class="form-group">
                        <?php $deafultcountry = $obj_digital->getDefaultcountry($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE']);?>
                                           <input type="hidden" name="userid" id="userid" value="<?php echo $obj_session->data['ADMIN_LOGIN_SWISS'];?>" />
                                           <input type="hidden" name="usertypeid" id="usertypeid" value="<?php echo $obj_session->data['LOGIN_USER_TYPE'];?>" />
                            <div class="col-lg-12" id="add-product-div">
                             <div id="order_template"></div>
                              <div class="form-group" id="footer-div" >
                                  <div class="col-lg-9 col-lg-offset-3">
                                        <button type="button" name="btn_add" id="btn_add" class="btn btn-primary" style="display:inline">Add Item</button>
                                       <!-- <a class="btn btn-default" href="<?php //echo $obj_general->link($rout, 'mod=cartlist_view', '',1);?>">Cancel</a>  -->
                                  </div>
                              </div>
                          </div>  
                    </div>
                    <div class="form-group" id="result">
                        
                    </div>
                    <div class="form-group" id="gen_div">
                        <div class="col-lg-9 col-lg-offset-3">
                            <button type="submit" name="btn_gen" id="btn_gen" class="btn btn-primary" style="display:none;">Generate Quotation</button>
                            <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a>  
                      </div>
                    </div>
                    
          </div>
          </div>  
          </div>  
         </form>
       </section>
      </div>
    </div>
  </section>
</section>
<style>
#ajax_volume{
	border : 1px solid #13c4a5;
	background : #FFFFFF;
	position:relative;
	display:none;
	padding:2px 2px;
	top:auto;
	border-radius: 4px;
}


#ajax_response{
	border : 1px solid #13c4a5;
	background : #FFFFFF;
	position:relative;
	display:none;
	padding:2px 2px;
	top:auto;
	border-radius: 4px;
}
#ajax_product{
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
}</style>
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<!-- select2 --> <script src="<?php echo HTTP_SERVER;?>js/select2/select2.min.js"></script>
<script type="application/javascript">
    jQuery(document).ready(function(){
    	//checkZipper(0);
    	$("#spout_div").hide();
    	$("#country_id").change();
    	getProduct();
    	jQuery("#form").validationEngine();
    	<?php if($addedByInfo['country_id'] == '111'){ ?>
    	        $("input[type=radio][name='transpotation'][value='By Air']").attr('checked', false);
    	        $("input[type=radio][name='transpotation'][value='Pickup']").attr('checked', true);
    	<?php }
    	     else {	?>
    	        $("input[type=radio][name='transpotation'][value='Pickup']").attr('checked', false);
    	        $("input[type=radio][name='transpotation'][value='By Air']").attr('checked', true);
    	<?php }?>
    	$(document).click(function(){
    		$("#ajax_response").fadeOut('slow');
    		 $("#ajax_response").html("");
    	});
        var offset = $("#keyword").offset();
    	var width = $("#holder").width();
    	$("#ajax_response").css("width",width);
        var currentRequest = null;	
    	$("#keyword").keyup(function(event){		
    		 var keyword = $("#keyword").val();
    		 if(keyword.length>='3')
    		 {
    			 if(event.keyCode != 40 && event.keyCode != 38 )
    			 {
    				 var client_name_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=client_name', '',1);?>");
    				 $("#loading").css("visibility","visible");
    				 currentRequest = $.ajax({
    				   type: "POST",
    				   async:'true',
    				   url: client_name_url,
    				   data: "client_name="+keyword,
    				   beforeSend : function()    {           
                            if(currentRequest != null) {
                                currentRequest.abort();
                            }
                        },
    				   success: function(msg){					
    				 var msg = $.parseJSON(msg);
    				 //console.log(msg);
    				   var div='<ul class="list">';
    					if(msg.length>0)
    					{	
    						for(var i=0;i<msg.length;i++)
    						{	
    							div =div+'<li><a href=\'javascript:void(0);\' id="'+msg[i].address_book_id+'"  email='+msg[i].email_1+'><span class="bold">'+msg[i].company_name+'</span></a></li>';				
    						}
    					}
    					div=div+'</ul>';
    					if(msg != 0)
    					  $("#ajax_response").fadeIn("slow").html(div);
    					else
    					{
    					  $("#ajax_response").fadeIn("slow");	
    					  $("#ajax_response").html('<div style="text-align:left;">No Matches Found</div>');
    					   $("#address_book_id").val('');
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
    						    $("#keyword").val($(".list li[class='selected'] a").text());
    						    $("#address_book_id").val($(".list li[class='selected'] a").attr("id"));
    						    $("#email").val($(".list li[class='selected'] a").attr("email"));
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
    					        $("#keyword").val($(".list li[class='selected'] a").text());
    					        $("#address_book_id").val($(".list li[class='selected'] a").attr("id"));   
    					        $("#email").val($(".list li[class='selected'] a").attr("email"));
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
    			  $(this).addClass("selected");
    		});
    		$(this).find(".list li a:first-child").mouseout(function () {
    			  $(this).removeClass("selected");
    		});
    		$(this).find(".list li a:first-child").click(function () {
    			  $("#keyword").val($(this).text());
    			  $("#email").val($(this).attr("email"));
    			   $("#address_book_id").val($(this).attr("id"));
    			  $("#ajax_response").fadeOut('slow');
    			 $("#ajax_response").html("");
    		});
    	});
    });
    

    $('.btn-number').click(function(e){
        e.preventDefault();
        
       var fieldName = $(this).attr('data-field');
       var type      = $(this).attr('data-type');
       var input = $("input[name='"+fieldName+"']");
       var currentVal = parseInt(input.val());
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
            if(type == 'minus1') {
                if(currentVal > input.attr('min')) {
                    input.val(currentVal - 1).change();
                } 
                if(parseInt(input.val()) == input.attr('min')) {
                    $(this).attr('disabled', true);
                }
    
            } else if(type == 'plus1') {
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
        if(valueCurrent >= minValue) {
            $(".btn-number[data-type='minus1'][data-field='"+name+"']").removeAttr('disabled')
        } 
        if(valueCurrent <= maxValue) {
            $(".btn-number[data-type='plus1'][data-field='"+name+"']").removeAttr('disabled')
        }
    });
    

    function getColor(){ 
		var zipper_id=$('#size option:selected').attr('zipper');
		$("#zipper").val(zipper_id);
		
		var weight=$('#size option:selected').attr('weight');
		$("#weight").val(weight);
		
	    var make_id=$("input[class='make']:checked").val();
	    var volume = $('#size option:selected').attr('volume');
	    var product_id = $('#product').val();
	    var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getColor', '',1);?>");
	    $.ajax({
			type: "POST",
			url: url,
			data:{product_id:product_id,make_id:make_id,volume:volume,zipper_id:zipper_id}, 
			success: function(response) {
			    	//console.log(response);
			    	$('#color_div').html(response);
			    
			}
		});
    }
    $("#country_id").change(function(){			
		var stext = $('#country_id').find('option:selected').text().toLowerCase();
		var country_id = $(this).val();
		if(country_id == 111){
			$(".r1").hide();
			$(".r2").hide();
			$(".r3").show();
			$("#tax_div").show();	
		}	
		else{
			$(".r1").show();
			$(".r2").show();
			$(".r3").hide();
			$("#tax_div").hide();
		}
    });
    $("#btn_add").click(function(){
    	if($("#form").validationEngine('validate')){
    		    var formData = $("#form").serialize();
    		    
    		$("#product").attr('disabled',false);
    		//var pro = $("#product option").eq($("#product").prop("selectedIndex")).val();
    		//$("#country_id").attr('enabled',true);
    		//$("#customer_name").attr('enabled',true);
    		//$("#taxation").attr('enabled',true);
    		var volume = $('#size option:selected').attr('volume');
    		var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=addQuotation', '',1);?>");			
    		$.ajax({
    				method: "POST",					
    				url: url,
    				data : {formData : formData,volume:volume},
    				success: function(response){	
    					//console.log($("#product").val());
    					//$("#product").attr('disabled',true);
    	            	$("#country_id").attr('disabled',true);
    		            $("#customer_name").attr('disabled',true);
    		            $("#email").attr('disabled',true);
    		            $("#result").html(response);
    		            $("#btn_gen").show();
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
	function getcolordetail(n){ 
	   $('select#digital_print_color').each(function() {
                $('select#digital_print_color option').removeAttr("selected","selected").change();
        });
       var front=$('#front_color').val();
       var back=$('#back_color').val();
       if(n==1){
          var max_range=  4-front;  
          $("#back_color").prop('min',0);
          $("#back_color").prop('max',max_range);
          //fixed by kinjal on (13-9-2019)
          if(max_range!=0)
            $(".b_plus").attr('disabled',false); 
         //END kinjal
        } 
        if(n==2){
          var max_range=  4-back; 
          $("#front_color").prop('min',1);
          $("#front_color").prop('max',max_range);
          //fixed by kinjal on (13-9-2019)
          if(max_range!=0)
            $(".f_plus").attr('disabled',false);
          if(back=='4')
          {
              $("#front_color").prop('min',0);
              $("#front_color").prop('max',max_range);
              $("#front_color").val(0);
          }
          //END kinjal  
        }
        var total_color = parseInt(front)+parseInt(back);console.log(total_color);
        $("#digital_print_color option[color='"+total_color+"']").prop("selected","selected").change();  
    }
    function getProduct()
    {
        var make = $("input[class='make']:checked").val();
        var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getProduct', '',1);?>");			
		$.ajax({
				method: "POST",					
				url: url,
				data : {make : make,},
				success: function(response){	
					$("#product_dropdown").html(response);
		            
				},
				error: function(){
						return false;	
				}
			});
    }
    function product_info(){
    	var product = $('#product').val();
    	var make_id=$("input[class='make']:checked").val();
    	if(make_id==5)
    	    $("#spout_div").show();
    	else
    	    $("#spout_div").hide();
    	var size_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getProductSize', '',1);?>");
    	$.ajax({
    		type: "POST",
    		url: size_url,					
    		data:{product_id:product,make_id:make_id},
    		success: function(json) {
    		    if(json){
    				$("#size_div").html(json);
    			}else{
    				$("#size_div").html('<span class="btn btn-danger btn-xs">Please select Product for Size option.</span>');
    			}
    			$("#loading").hide();
    		}
    	});
    }
</script> 

<style>
	.inactive{
		//background-color:#999;	
	}
</style>

        
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>