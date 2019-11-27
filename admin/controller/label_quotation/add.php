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
	$userCurrency = $obj_label_quotation->getUserCurrencyInfo($user_type_id,$user_id);
	$addedByInfo = $obj_label_quotation->getUser($user_id,$user_type_id);
//	printr($addedByInfo);
}

$add_url='';
if($display_status) {
	
     if(isset($_POST['btn_gen']))
     {
        $obj_label_quotation->UpdateQuotation($_POST['lable_quotation_id']);
        page_redirect($obj_general->link($rout, 'mod=view&quotation_id='.encode($_POST['lable_quotation_id']), '',1));
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
                        <?php if($user_type_id==1)
                        {?>    
                            <div class="form-group option">
                                <label class="col-lg-3 control-label">Profit</label>
                                    <div class="col-lg-9">                
                                    	<div  style="float:left;width: 200px;">
                                            <label  style="font-weight: normal;">
                                              <input type="radio" name="profit" id="profit_rich" value="profit" checked="checked" >
                                            Profit Rich
                                             </label>
                                         </div>
                                         <div style="float:left;width: 200px;">
                                            <label  style="font-weight: normal;">
                                              	<input type="radio" name="profit" id="profit_poor" value="profit_poor" >
                                          	Profit Poor
                                             </label>
                                          </div>
                                          <div style="float:left;width: 200px;">
                                            <label  style="font-weight: normal;">
                                              	<input type="radio" name="profit" id="profit_more_poor" value="profit_more_poor" >
                                          	Profit More Poor
                                             </label>
                                          </div>
                                </div>
                              </div>
                         <?php } 
                               else
                               {
                                   echo '<input type="hidden" id="profit" name="profit" value="'.$addedByInfo['profit_type'].'"/>';
                               }?>
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
                                     <input type="text" name="email" placeholder="Customer Email" value="" class="form-control validate[required,custom[email]]" id="email">                                      
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
        							echo $obj_label_quotation->getCountryCombo($selCountry);//214?>
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
                                
                          
                            
                            <div class="form-group" id="product_dropdown">
                              <label class="col-lg-3 control-label"><span class="required">*</span>Select Product</label>
                               <div class="col-lg-4">
                                    <select name="product" id="product" class="form-control validate[required]" onchange="product_info()">
                                        <option value="">Select Product</option>
                                          <?php    
                                          	$products = $obj_label_quotation->getProduct();
                                          foreach($products as $product){
                                                        echo '<option value="'.$product['product_id'].'">'.$product['product_name'].'</option>';
                                              } 
                                            ?>
                                      </select>
                                </div>
                            </div>
                            <div style="display:none" id="sup_shape_div" class="form-group">
                               <label class="col-lg-3 control-label"><span class="required">*</span>Select Window</label>
                               <div class="col-lg-4">
                                   	<div  style="float:left;width: 100px;">
                                        <label  style="font-weight: normal;">
                                          <input type="radio" name="sup_window" id="normal" value="Normal" checked>
                                          Normal
                                         </label>
                                     </div>	
                                     <div  style="float:left;width: 100px;">
                                        <label  style="font-weight: normal;">
                                          <input type="radio" name="sup_window" id="rectangle" value="Rectangle">
                                          Rectangle Window
                                         </label>
                                     </div>
                                     <div  style="float:left;width: 100px;">
                                        <label  style="font-weight: normal;">
                                          <input type="radio" name="sup_window" id="oval" value="Oval">
                                          Oval Window
                                         </label>
                                     </div>
                                </div>
                              </div>  
                              <div class="form-group">
                                    <label class="col-lg-3 control-label"><span class="required">*</span>Select Size (WXHXG)</label>
                                    <div class="col-lg-9" id="size_div" style="float: left; width: 30%;">
                                        <span class="btn btn-danger btn-xs">Please select Product for Size option.</span>
                                     </div>
                              </div>
                                 <div style="display:none" id="customSize">
                                  <div class="form-group">
                                    <label class="col-lg-3 control-label widthtb"><span class="required">*</span>Width</label>
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
                                    <label class="col-lg-3 control-label"><span class="required">*</span> Gusset </label>
                                    <div class="col-lg-5">
                                         <div class="input-group">
                                         	<input type="text" name="gusset" id="gusset_input" value="<?php echo isset($post['gusset'])?$post['gusset']:'';?>" class="form-control validate[required]">
                                            	<span class="input-group-btn">
                                                	<button type="button" class="btn btn-danger"> <i class="fa fa-warning"></i> Please enter one side or single gusset only.</button>
                                                </span>  
                                         </div> <span id="gussetsugg" style="color:blue;font-size:11px;"></span>   
                                    </div>
                                  </div>
                              </div>    
                             
                           <div id="zipper_div" class="form-group"><input type="hidden" id="zipper" name="zipper" value=""/><input type="hidden" id="weight" name="weight" value=""/></div>
                            <div class="form-group" id="shape_dropdown">
                              <label class="col-lg-3 control-label"><span class="required">*</span>Select Shape</label>
                               <div class="col-lg-4">
                                    <select name="shape" id="shape" class="form-control validate[required]" onchange="getstickersize()">
                                        <option value="">Select Shape</option>
                                       
                                          <?php     
                                          	$label_shapes = $obj_label_quotation->getLabelShape();
                                             foreach($label_shapes as $shape){
                                                  
                                                        echo '<option value="'.$shape['shape_master_id'].'">'.$shape['shape_name'].'</option>';
                                                   
                                            } 
                                            ?>
                                         <option value="0">Custom Shape</option>
                                      </select>
                                </div> 
                            </div>  
                               <div class="form-group">
                                    <label class="col-lg-3 control-label"><span class="required">*</span>Select Sticker  Size (WXH)</label>
                                    <div class="col-lg-9" id="StickerSize_dropdown" style="float: left; width: 30%;">
                                         <span class="btn btn-danger btn-xs">Please select Shape for Sticker Size option.</span>
                                     </div> 
                          </div>
                             <div style="display:none" id="customStickerSize">
                                  <div class="form-group">
                                    <label class="col-lg-3 control-label widthtb"><span class="required">*</span> Sticker Width</label>
                                    <div class="col-lg-3">                         
                                         <input type="text" name="sticker_width" id="sticker_width"  onchange="getMinWidth()" value="<?php echo isset($post['width'])?$post['width']:'';?>" class="form-control validate[required,custom[onlyNumberSp]]" > 
                                         <span id="sticker_widthsugg" style="color:blue;font-size:11px;"></span>                             
                                    </div>
                                  </div>
                                  
                                  <div class="form-group">
                                    <label class="col-lg-3 control-label heightb"><span class="required">*</span>Sticker Height</label>
                                    <div class="col-lg-3">
                                         <input type="text" name="sticker_height" id="sticker_height" onchange="getMinHeight()" value="<?php echo isset($post['height'])?$post['height']:'';?>" class="form-control validate[required,custom[onlyNumberSp]]">
                                             <span id="sticker_heightsugg" style="color:blue;font-size:11px;"></span>       
                                    </div>
                                  </div>
                              </div>
                              <div class="form-group" id="make_div">
                                <label class="col-lg-3 control-label">Sticker Material</label>
                                <div class="col-lg-8" class="make_class">
                                    <?php
                                    $makes = $obj_label_quotation->getMake();
                                  
                                    foreach($makes as $make){
                                         echo '<div  style="float:left;width: 200px;">';
                                        echo '	<label  style="font-weight: normal;">';
                                        if($make['make_id']!=1){
                                            echo '<input type="radio" class="make" name="make" id="'.$make['make_id'].'" value="'.$make['make_id'].'" onclick="getmaterial()"  checked="checked" > ';
                                        }
    									else{
    									    echo '<input type="radio" class="make" name="make" id="'.$make['make_id'].'" onclick="getmaterial()" value="'.$make['make_id'].'" > ';
    									}
                                        echo '	 '.$make['make_name'].' </label>';
                                        echo '</div>';
                                    }
                                    ?>
                                </div>
                            </div>
                          <!--<div class="form-group"  id="calculate_sheet_div"></div>-->   
                          <div class="form-group" id="material_dropdown" style="display:none;">
                              <label class="col-lg-3 control-label"><span class="required">*</span>Select Sheet material</label>
                               <div class="col-lg-4" id="material_div">
                                </div>
                            </div> 
                            <div class="form-group" id="effect_dropdown">
                              <label class="col-lg-3 control-label"><span class="required">*</span>Printing Effect Option</label>
                               <div class="col-lg-4" id="effect_div">
                                </div>
                            </div>
                           <div class="form-group">
                            <label class="col-lg-3 control-label"><span class="required">*</span>Quantity</label>
                            <div class="col-lg-9" id="qty_div">
                                         
                            </div>
                          </div>
                               <div class="form-group">
                        <label class="col-lg-3 control-label">Transportation</label>
                        <div class="col-lg-9">
                         	<div class="checkbox ch1" style="float: left; width: 30%;">
                                <label>
                                  <input type="checkbox" name="transpotation[]" value="air" class="validate[minCheckbox[1]]" >
                                  By Air
                                 </label>
                             </div>
                             <div class="checkbox ch2" style="float: left; width: 30%;">
                                <label>
                                  <input type="checkbox" name="transpotation[]" value="sea" class="validate[minCheckbox[1]]"  >
                                  By Sea
                                 </label>
                             </div>
                             <?php 
							 if($userCurrency['currency_code'] == 'INR'){
								 ?>
                             	 <div class="checkbox ch3" style="float: left;  width: 30%;">
                                    <label>
                                      <input type="checkbox" name="transpotation[]" value="pickup" class="validate[minCheckbox[1]]">
                                      Factory Pickup
                                     </label>
                                 </div>
                             	 <?php
							 } else
							 { ?>
								 <div class="checkbox ch3" style="float: left; display:none;  width: 30%;">
                                    <label>
                                      <input type="checkbox" name="transpotation[]" value="pickup"  class="validate[minCheckbox[1]]">
                                      Factory Pickup
                                     </label>
                                 </div>
								 <?php } ?>
                         </div>
                      </div> 
					   
                           <div class="form-group">
                            <?php $deafultcountry = $obj_label_quotation->getDefaultcountry($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE']);?>
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
    
        getmaterial();
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
    							div =div+'<li><a href=\'javascript:void(0);\' id="'+msg[i].address_book_id+'" email='+msg[i].email_1+'><span class="bold">'+msg[i].company_name+'</span></a></li>';				
    						}
    					}
    					div=div+'</ul>';
    					//console.log(div);
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
        	$("#country_id").attr('disabled',false);
    	   $("#customer_name").attr('disabled',false);
    	   $("#email").attr('disabled',false);
    	var formData = $("#form").serialize();
    		$("#product").attr('disabled',false);
    		var volume = $('#size option:selected').attr('volume');
    		var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=addQuotation', '',1);?>");			
    		$.ajax({
    				method: "POST",					
    				url: url,
    				data : {formData : formData,volume:volume},
    				success: function(response){	
    					//console.log(response);
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

/* function getProduct()
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
    }*/
    function product_info(){
    	var product = $('#product').val();
    	if(product==3 || product==8){
    	   $('#sup_shape_div').show(); 
    	}else{
    	     $('#sup_shape_div').hide(); 
    	}
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
    function getstickersize(){ 
    	var product_id = $('#product').val();
    	var size_master_id = $('#size').val();console.log(size_master_id);
    	var shape_id = $('#shape').val();
        var sup_window = $("input[name='sup_window']:checked").val();	
   
            if(size_master_id!=0){
            	var size_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getStickerSize', '',1);?>");
            	$.ajax({
            		type: "POST",
            		url: size_url,					
            		data:{product_id:product_id,size_master_id:size_master_id,shape_id:shape_id,sup_window:sup_window},
            		success: function(json) {
            		    if(json){
            				$("#StickerSize_dropdown").html(json);
            			}else{
            				$("#StickerSize_dropdown").html('<span class="btn btn-danger btn-xs">Please select Shape for Sticker Size option.</span>');
            			}
            			$("#loading").hide();
            		}
            	});
            	$("#customStickerSize").hide();	
            }else{
                $("#customStickerSize").show();	
            }
    }
    function getmaterial(){   
      
         var make_pouch = $("input[class='make']:checked").val();
            	var size_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getmaterial', '',1);?>");
            	$.ajax({
            		type: "POST",
            		url: size_url,					
            		data:{make_pouch:make_pouch},
            		success: function(json) {
            		console.log(json);
            		    if(json){  
            				$("#material_div").html(json);
            			}
            		}
            	});
        	getcalculate_sheet();
           
    }
     
    function customSize()
	{
	    $('#shape').val('');
	    $('#sticker_size').val('');
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
		
	function customStickerSize()
	{
		if($('#sticker_size').val()==0)
		{
			$("#customStickerSize").show();	
		}
		else
		{
			$("#customStickerSize").hide();
			$("#sticker_width").val('');
			$("#sticker_height").val('');
		}
		getcalculate_sheet();
	}
	function getMinWidth()
	{
	   	var width=$("#sticker_width").val();
	   	var min_width = $('#sticker_size option:selected').attr('min_width');
      
		if(width<parseInt(min_width)){  
		   var width=$("#sticker_width").val('');
		   $("#sticker_widthsugg").html("Minimum Width is "+min_width+" " );
		}
		getcalculate_sheet(); 
	}
	function getMinHeight()
	{ 
	  	var height=$("#sticker_height").val();
	   	var min_height = $('#sticker_size option:selected').attr('min_height');

		if(height<parseInt(min_height)){  
		   var height=$("#sticker_height").val('');
		      $("#sticker_heightsugg").text("Minimum Height is "+min_height+" " );
		    //  alert(html);
		}
		getcalculate_sheet();
	}
	function getQtyandeffects()
	{ 
	    var sheet_id = $('#material').val();
    	var size_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getQtyandeffects', '',1);?>");
    	$.ajax({
    		type: "POST",
    		url: size_url,					
    		data:{sheet_id:sheet_id},
    		success: function(json) {
    				 var res = $.parseJSON (json);
					 $("#qty_div").html(res['qty']);
					 $("#effect_div").html(res['effect']);
    		
    		} 
    	});
          
	}
	function getcalculate_sheet() 
	{ //commented by kinjal on 27-09-2019 (nandanbhai don't want show when generate quote)
	      
	   // alert('gaehhrh');
	 	var sticker_width=0;  
	 	var sticker_height=0;  
    	var sheet_id =$('#material').val();
         var make_pouch = $("input[name='make']:checked").val();
    		if($('#sticker_size').val()==0){
                sticker_width =	$("#sticker_width").val();
		        sticker_height = $("#sticker_height").val();
    		}else{
    		    sticker_width = $('#sticker_size option:selected').attr('max_width');
    		    sticker_height = $('#sticker_size option:selected').attr('max_height');
    		}
        
     if(sticker_width!=0){
    	
    	/*$('select#material').each(function() {
                $('select#material option').removeAttr("selected","selected").change();
        });*/
    	var size_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=calculate_sheet', '',1);?>");
    	$.ajax({
    		type: "POST",
    		url: size_url,					
    		data:{sheet_id:sheet_id,sticker_width:sticker_width,sticker_height:sticker_height,make_pouch:make_pouch},
    		success: function(json) {
					 //$("#calculate_sheet_div").html(json);
					 //console.log(json);
					 $("#material option[value='"+json+"']").prop("selected","selected").change();
					 getQtyandeffects();
    		}
    	});  
     }
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