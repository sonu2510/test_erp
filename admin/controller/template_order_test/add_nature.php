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
	'href' 	=> '',
	'icon' 	=> 'fa-list',
	'class'	=> 'active',
);

$limit = LISTING_LIMIT;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}
if(isset($_GET['status']))
{
		if($_GET['status']==0)
	{
		//local
		 //$menuId=83;
		 //online New Order = 75
		$menuId =203;
	}
	else if($_GET['status']==3)
	{
		//local menuid
		 //$menuId=89;
		//online Dispatched=77
		$menuId=205;
	}
	else if($_GET['status']==2)
	{
		// local
		// $menuId=90;
		//online Decline=78
		$menuId=206;
	}
	else if($_GET['status']==1)
	{
		// local 
		//$menuId=88;
		//online In Process=76
		$menuId=204;
	}
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
	//$userCurrency = $obj_pro_invoice->getUserCurrencyInfo($user_type_id,$user_id);
	$addedByInfo = $obj_template->getUser($user_id,$user_type_id);
	
}

$add_url='';
if(isset($_GET['address_book_id']))
{
    $address_id = decode($_GET['address_book_id']);
    $add_url = '&address_book_id='.$_GET['address_book_id'];
}	
if($display_status) {
	$checkNewCartPermission = $obj_template->checkNewCartPermission($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE']);
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
	}
	$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
	$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
//$menu_id = array('79','80');
	 //$permission = $obj_template->getUserPermission($menu_id);
	
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
         <div class="panel-body">
         <?php   $templatedetails =$obj_template->gettemplatetitle($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],'','','','');
		 			//printr( $templatedetails);
			  ?>
                	<form class="form-horizontal" method="post" name="form" id="order-form" enctype="multipart/form-data">
                            <!--    <div class="form-group">
                                <label class="col-lg-3 control-label">Pouch Type</label>
		                        <div class="col-lg-9">                
		                        	<div  class="checkbox ch1" style="float:left;width: 200px;">
		                                <label  style="font-weight: normal;">
		                                  <input type="radio" name="stock_print" checked="checked" onchange="getColorforDigitalPrint()" id="stock_print1" value="stock"  />
		                            	Stock
		                              </label>
		                          </div>
		                           <div class="checkbox ch2" style="float:left;width: 200px;">
		                                <label  style="font-weight: normal;">
		                                  	<input type="radio" name="stock_print" id="digital_print2" onchange="getColorforDigitalPrint()"  value="Digital Print" >
		                              	Digital Print
		                               </label>
		                          </div>
                                </div>  
                            </div>-->
                               <div class="form-group">
                                <label class="col-lg-3 control-label"><span class="required">*</span> Client Name</label>
                                <div id="holder" class="col-lg-4"> 
                                        <input type="text" id="keyword" tabindex="0" class="form-control "  autocomplete="off">
                                        <div id="ajax_response"></div>
                                </div>
                                <label class="col-lg-3 control-label"></label></label>
                                <div id="holder" class="col-lg-4"> 
                                <?php if($obj_general->hasPermission('add',$menuId))
                                      { 
                        				if($obj_session->data['LOGIN_USER_TYPE'] != 1)
                        				{
                        					if($permission ==0 && $checkNewCartPermission[0]['status']==1 ) 
                        					{
                        					    
                        					}
                        					else
                        					{
                        					    ?><?php if(isset($_GET['status']) && $_GET['status']==0) 
                        					    {?>
                                                    <a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add&s_no='.encode($permission).'&status=0'.$add_url, '',1);?>">
                                                    <i class="fa fa-plus"></i> New Stock Order </a>
                                                <?php 
                                                }
                                                elseif(!isset($_GET['status'])) 
                                                {?>
                                                    <a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add&s_no='.encode($permission).''.$add_url, '',1);?>">
                                                    <i class="fa fa-plus"></i> New Stock Order </a>
                                          <?php }?>
                                      <?php }
                        				}
                        			}?>
                                
                                
                                 </div>
                                </div>
                                <?php // mansi 22-1-2016 (add buyers order no in template order)?> 
                                <div class="form-group">
                                <label class="col-lg-3 control-label"><span class="required">*</span> Buyers Order No</label>
                                <div id="holder" class="col-lg-4"> 
                                        <input type="text" id="buyers_order_no" name="buyers_order_no" class="form-control " >
                                </div>
                                </div>
                                <input type="hidden" name="con_id" id="con_id" value="<?php echo $addedByInfo['country_id'];?>">
                                 <?php // sejal 14-04-2017 told by vikas sir
							//	printr($addedByInfo);
								   if($addedByInfo['country_id']=='252'||$addedByInfo['country_id']=='189' ||$addedByInfo['country_id']=='42' ||$addedByInfo['country_id']=='47' ||$addedByInfo['country_id']=='238'||$addedByInfo['country_id']=='112' ||$addedByInfo['country_id']=='251'|| $addedByInfo['country_id']=='90'|| $addedByInfo['country_id']=='172'||$addedByInfo['country_id']=='170'||$addedByInfo['country_id']=='230'||$addedByInfo['country_id']=='253'||$addedByInfo['country_id']=='209'){
								  ?>
                                <div class="form-group">
                                <label class="col-lg-3 control-label"><span class="required">*</span> Reference No</label>
                                <div id="holder" class="col-lg-4"> 
                                       
                                       <?php 
									/*	if($addedByInfo['country_id']=='252')
										{
											$getlatestrefno = $obj_template->getlatestrefno($addedByInfo['country_id']);
											$getlatestrefno = $getlatestrefno+1;
											echo '<input type="text" id="ref_no" name="ref_no" class="form-control " value="'.$getlatestrefno.'" >';//disabled=disabled
										}
										else
										{*/?>
                                         <input type="text" id="ref_no" name="ref_no" class="form-control " >
                                        <?php //} ?>
                                </div>
                                </div>
                                <?php }  ?>
                                
                                
                            <div  class="form-group" id=""  style="">
                             <label class="col-lg-3 control-label">Product</label>
                                <div class="col-lg-3">
                                     <select name="product_nm" id="product_nm" class="form-control">
                                        <option value="">Select Product</option>
                                        <?php $pro = $obj_template->getActiveProductId();
                                           foreach($pro as $p)
                                           {
                                               echo '<option value="'.$p['product_id'].'">'.$p['product_name'].'</option>';
                                           }?>
							        </select>
                              </div>
                          </div>
                      
                        <div id="volume">
                            
                        </div>
                        
                        <div id="color_con">
                            
                        </div>
                        <div id="img_div">
                            
                            
                        </div>
                            <div class="form-group option" id="product_code_div" style="display:none">
                                <label class="col-lg-3 control-label"><span class="required">*</span>Product Code</label>
                                <div class="col-lg-4" id="holder">
                                   <input type="text" id="product_code"  class="form-control validate[required]" autocomplete="off" value="" name="product_code"  />
                                   <?php $product_codes=$obj_template->getActiveProductCode($filter_data); 
                                            //printr($filter_data);
                                           
                                      foreach($product_codes as $product){ 
                                       ?>
                                        <input type="hidden" name="product_code_id" id="product_code_id" value="<?php echo $product['product_code_id'] ?>" />
                                     <?php } ?>
                                       <div id="ajax_product"></div>
                                 	
                                </div>
                                <div class="col-lg-3" id="product_div"> 
                                       <input type="text" name="product_name" id="product_name"  value="<?php //echo isset($product['product_code_id'])?$product['description']:'';?>" disabled="disabled" class="form-control validate" style="width:400px"/>
                                       <input type="hidden" name="color_id" id="color_id" value="" />
                                </div>
                        </div>
                  
                    
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
  			
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Shippment Country</label>
                        <div class="col-lg-4">
                                 <select name="shippment_details" id="shippment_details" class="form-control"  >
                                <option value="">Select Country</option>
							  </select>
                        </div>
                      </div>
                <div class="form-group" id="digital_print_div2" style="display: none">
                    <label class="col-lg-3 control-label">Front Side Color</label>
                          <div class="col-lg-1">
                               <div class="input-group">
                                  <span class="input-group-btn">
                                      <button type="button" class="btn btn-default btn-number" disabled="disabled" data-type="minus" data-field="front_color">
                                          <span class="glyphicon glyphicon-minus"></span>
                                      </button>
                                  </span>
                                  <input type="text" name="front_color" id="front_color" class="form-control input-number" value="1" min="1" max="6">
                                  <span class="input-group-btn">
                                      <button type="button" class="btn btn-default btn-number" data-type="plus" data-field="front_color">
                                          <span class="glyphicon glyphicon-plus"></span>
                                      </button>
                                  </span>
                              </div><!-- /input-group -->
                              </div>
                      </div>
                       <section id="content">
        
                      
                  <div class="form-group" id="digital_print_div1" style="display: none">
                          <label class="col-lg-3 control-label">Back Side Color</label>
                          <div class="col-lg-1">
                               <div class="input-group">
                                  <span class="input-group-btn">
                                      <button type="button" class="btn btn-default btn-number" disabled="disabled" data-type="minus1" data-field="back_color">
                                          <span class="glyphicon glyphicon-minus"></span>
                                      </button>
                                  </span>
                                  <input type="text" name="back_color" id="back_color" class="form-control input-number" value="1" min="1" max="6">
                                  <span class="input-group-btn">
                                      <button type="button" class="btn btn-default btn-number" data-type="plus1" data-field="back_color">
                                          <span class="glyphicon glyphicon-plus"></span>
                                      </button>
                                  </span>
                               </div><!-- /input-group -->
                              </div>
                      </div>
                          <div class="form-group" id="digital_print_div" style="display: none">
                        <label class="col-lg-3 control-label">Color</label>
                      	  <div class="col-lg-4">
                      	  	<?php  $colors = $obj_template->getActiveColorForDigitalPrint();?>
                                 <select name="digital_print_color" id="digital_print_color" class="form-control"  >
                                	<option value="">Select Color</option>
                                	<?php foreach($colors as $color){?>
                                		<option value="<?php echo $color['pouch_color_id'].'=='.$color['color_value'];?>"><?php echo $color['color'];?></option>
                                	<?php }?>

							  </select>
                        </div>
                      </div>
                       <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span>Transpotation</label>
                        <div class="col-lg-4">
                             <select name="transport_details" id="transport_details" class="form-control validate[required]"  >
                			<option value="">Select Transpotation</option>
							 </select>
                        </div>
                      </div>
                    
					  
                      <div class="form-group">
                            <label class="col-lg-3 control-label">Order Type</label>
                            <div class="col-lg-4">
                                 <input type="radio" name="order_type" id="order_type" value="sample" />Free Sample Order</label> 
                     				<label  style="font-weight: normal;">
                                  <input type="radio" name="order_type" id="order_type" value="commercial"  checked="checked"/>Commercial Order</label>
                            </div>
                      </div>
                      
                       <div class="form-group">
                        <label class="col-lg-3 control-label">Shipment Type</label>
                       		 <div class="col-lg-4">
             <input type="radio" name="shipment" id="shipment" value="0"  checked="checked" onclick="selfshipment()"/>Self</label> 
                     <label  style="font-weight: normal;">
                     <input type="radio" name="shipment" id="shipment" value="1" onclick="shipmentcountry()"/>Client</label>
		<br><div style="display:none;" id="address"><span class="required">*</span>
                     <label id="addrr"> Address :</label><textarea name="addressval" id="addressval"  
					 class="form-control validate[required]" ></textarea>
                     </div>
                     </div>
              <?php $deafultcountry = $obj_template->getDefaultcountry($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE']);
			  //printr($deafultcountry);
			  ?>
               <input type="hidden" name="userid" id="userid" value="<?php echo $obj_session->data['ADMIN_LOGIN_SWISS'];?>" />
               <input type="hidden" name="usertypeid" id="usertypeid" value="<?php echo $obj_session->data['LOGIN_USER_TYPE'];?>" />
                <input type="hidden" name="permission" id="permission" value="<?php echo decode($_GET['s_no']);?>" />
                <input type="hidden" name="product_id" id="product_id" value="" />
                <input type="hidden" name="country_id" id="country_id" value="" />
                <input type="hidden" name="transport" id="transport" value="" /> 
                <input type="hidden" name="defaultcountry" id="defaultcountry" value="<?php echo $deafultcountry['country_id'];?>" />
                <div class="col-lg-12" id="add-product-div">
          <h4><i class="fa fa-plus-circle"></i> Add Product</h4>
          <div id="order_template">
          </div>
          <div class="form-group" id="footer-div" >
          <div class="col-lg-9 col-lg-offset-3">
          <?php if(isset($_GET['status'])) {?>
              <a class="btn btn-default" href="<?php echo $obj_general->link($rout, 'mod=cartlist_view&status=0', '',1);?>">Cancel</a>  
              <?php }else{ ?>
              <a class="btn btn-default" href="<?php echo $obj_general->link($rout, 'mod=cartlist_view', '',1);?>">Cancel</a>  
              <?php  }?>
          </div>
          </div>
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
<style type="text/css">.jqstooltip { position: absolute;left: 0px;top: 0px;visibility: hidden;background: rgb(0, 0, 0) transparent;background-color: rgba(0,0,0,0.6);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";color: white;font: 10px arial, san serif;text-align: left;white-space: nowrap;padding: 5px;border: 1px solid white;z-index: 10000;}.jqsfield { color: white;font: 10px arial, san serif;text-align: left;}</style>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<!-- select2 --> <script src="<?php echo HTTP_SERVER;?>js/select2/select2.min.js"></script>
<script type="application/javascript">
$(document).ready(function(){
		
	$(document).click(function(){
		$("#ajax_response").fadeOut('slow');
		 $("#ajax_response").html("");
	});
	$("#keyword").focus();
	var offset = $("#keyword").offset();
	var width = $("#holder").width();
	$("#ajax_response").css("width",width);
	
	$("#keyword").keyup(function(event){		
		 var keyword = $("#keyword").val();
		 if(keyword.length)
		 {
			 if(event.keyCode != 40 && event.keyCode != 38 )
			 {
				 var client_name_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax_test&fun=client_name', '',1);?>");
				 $("#loading").css("visibility","visible");
				 $.ajax({
				   type: "POST",
				   url: client_name_url,
				   data: "client_name="+keyword,
				   success: function(msg){					
				 var msg = $.parseJSON(msg);
				 //console.log(msg);
				   var div='<ul class="list">';
					if(msg.length>0)
					{	
						for(var i=0;i<msg.length;i++)
						{	
							div =div+'<li><a href=\'javascript:void(0);\'><span class="bold">'+msg[i].client_name+'</span></a></li>';				
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
						$("#keyword").val($(".list li[class='selected'] a").text());
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
			  $("#ajax_response").fadeOut('slow');
			 $("#ajax_response").html("");
		});
	});
});
function fill(name){
	$('#client_name').val(name);
	$('#display').hide();	
}





function autoComplete(){
	var client_name = $('#client_name').val();
	
	if(client_name!='')
	{
	var client_name_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax_test&fun=client_name', '',1);?>");
				$.ajax({
					method:'post',
					url : client_name_url,
					data: {client_name:client_name},
					 success: function( data ) {
						
				/*	var data = $.parseJSON(data);
					var div='<div class="dropdown pull-left m-r"> <ul class="dropdown-menu pos-stc inline" role="menu" aria-labelledby="dropdownMenu"> ';
					if(data.length>0)
					{
						for(var i=0;i<data.length;i++)
						{	
							div =div+'<li><a onclick="fill(\''+data[i].client_name+'\')" onkeyup="fill(\''+data[i].client_name+'\')">'+data[i].client_name+'</a></li>';				
						}
					}
					
					div=div+'</ul> </div>';
						$('#display').html(data).show();*/
						
					}
				});
	}
}


	
	function hi_con(product_id)
	{
	
		
		template_id = $('#product_code').val();
		//alert(template_id);
		var product_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=displaycountry', '',1);?>");
		
		var userid = $('#userid').val();
		var defaultcountry = $('#defaultcountry').val();
	
	
		$.ajax({
			url : product_url,
			method : 'post',
      		data:{product_id:product_id,userid:userid},	
			success: function(response){
				
				//alert(response);
					var val = $.parseJSON(response);
				//alert(val);
				var tselect = $('#transport_details');
				
				tselect.children().remove();
				tselect.append($("<option>").val(0).text('Select Transpotation'));
				
				var dselect = $('#shippment_details');dselect.children().remove();
				dselect.append($("<option>").val(0).text('Select Country'));
				
				for(var i=0;i<val.length;i++)
				{
				
				//alert(val[i].country_id);
			if(defaultcountry == val[i].country_id)
			{
				dselect.append($("<option>").val(val[i].country_id).text(val[i].country_name).attr('selected', true));	
        				
        		country_id = $('#shippment_details').val();
        		
        		$("#country_id").val(val[i].country_id);
        		$("#transport").val('');
        		
        		var product_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=displaytranspotation', '',1);?>");
        	
        		var userid = $('#userid').val();
        	
        		$.ajax({
        			url : product_url,
        			method : 'post',
              		 data:{country_id:country_id,product_id:product_id,userid:userid},	
        			success: function(response){
        				
        				var dselect = $('#transport_details');
        				response = response.split("==");
        				dselect.children().remove();
        				dselect.append($("<option>").val(response[i]).text('Select Transpotation'));
        				for(var i=0;i<response.length-1;i++)
        				{
        					var res_val=response[i];
        					if(country_id=='42')
        					{
        						if(response[i]=='By Air')
        						   response[i]='Rush Order';
        						 else
        						  response[i]='Normal Order';
        					}
        					else
        						response[i]=response[i];
        					dselect.append($("<option>").val(res_val).text(response[i]));
        					
        				}
        				$('#order_template').html('');
        				
        			},
        			error: function(){
        				set_alert_message('Error!',"alert-warning","fa-warning"); 
        			}
		});			
			}
			else
				dselect.append($("<option>").val(val[i].country_id).text(val[i].country_name));
				
				}
			
				$('#order_template').html('');
				
				$("#product_id").val(product_id);
			
				$("#transport").val('');
							
			},
			error: function(){
				set_alert_message('Error!',"alert-warning","fa-warning"); 
			}
		});
	}
	$("#shippment_details").change(function()
	{
		country_id = $('#shippment_details').val();
		//console.log()
		var defaultcountry = $('#defaultcountry').val();
		if(defaultcountry!=country_id)
		{
			var self = '0'; var client='1';
			$("input[type=radio][value=" + self + "]").prop("disabled",true);
			$("input[type=radio][value=" + client + "]").prop('checked', 'checked');
			$("#address").show();
			$("#addressval").val('');
		}
		else
		{
			var self = '0';var client='1';
			$("input[type=radio][value=" + self + "]").prop("disabled",false);			
			$("input[type=radio][value=" + client + "]").prop('checked', false);
			$("input[type=radio][value=" + self + "]").prop('checked', true);
			$("#address").hide();
			$("#addressval").val('');
		}
	//console.log($("#product_id").val());
		$("#country_id").val(country_id);
	//	$("#transport").val('');
		$("#transport_details").val('');
	//	hi_con($("#product_id").val());
	/*	var product_id = $('#product_details').val();
		console.log(product_id);
		var product_url = getUrl("<?php //echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=displaytranspotation', '',1);?>");
		
		var userid = $('#userid').val();
	
		$.ajax({
			url : product_url,
			method : 'post',
      		 data:{country_id:country_id,product_id:product_id,userid:userid},	
			success: function(response){
				 
				var dselect = $('#transport_details');
				response = response.split("==");
				dselect.children().remove();
				dselect.append($("<option>").val(response[i]).text('Select Transpotation'));
				for(var i=0;i<response.length-1;i++)
				
				dselect.append($("<option>").val(response[i]).text(response[i]));
				$('#order_template').html('');
				
			},
			error: function(){
				set_alert_message('Error!',"alert-warning","fa-warning"); 
			}
		});*/
		
		
	});
	
    $('#digital_print_color').change(function(){
        $("#transport_details").change();
    });
	$("#transport_details").change(function()
	{
		var transport = $("#transport_details").val();
		
		var product_code_id = $("#product_code_id").val();
		var country_id = $("#country_id").val();
		var color_id = $("#clr_details").val();
		var stock_print = $('input[name=stock_print]:radio:checked').val();
    	var digital_print_color = $('#digital_print_color').val();
		
		//alert(product_code_id);
		//alert(transport);
				//alert(country_id);
		var add_product_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=checktemplatelist', '',1);?>");
		
	
		$.ajax({
			url : add_product_url,
			method : 'post',
      		data:{transport:transport,product_code_id:product_code_id,country_id:country_id,color_id:color_id,stock_print:stock_print,digital_print_color:digital_print_color},	
			success: function(response){
			//	console.log(response);
				$('#order_template').html(response);
			},
			error: function(){
				set_alert_message('Error!',"alert-warning","fa-warning"); 
			}
		});
	
		
	});
	
	function shipmentcountry(shipment)
	{$("#addressval").val('');
		$("#address").show();
		
		
	}
	function selfshipment(shipment)
	{	  $("#addressval").val('');
			$("#address").hide();
		
	}
	function removeTemplate(template_size_id)
	{
		var remove_template_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=removeTemplate', '',1);?>");
		$.ajax({
			url : remove_template_url,
			method : 'post',
			data : {template_size_id : template_size_id},
			success: function(){
				$('#'+template_size_id).hide();	
			},
			error: function(){
				return false;	
			}
			});
	}

	function flytocart(template_size_id)
	{
		
		var $counter = $('#panel-order');
		var posX     = $counter.offset().left;
		var posY     = $counter.offset().top;
		var c        = 0 ;
		
		  $('#'+template_size_id).css({ position:'absolute',
   top:$('#'+template_size_id).offset().top,
   left:$('#'+template_size_id).offset().left}).animate({	
			  top:$('#panel-order').offset().top,
   left:$('#panel-order').offset().left
			},1000,function(){
				 $('#'+template_size_id).prependTo('#panel-order').css('position','static');
		  });		  
	
	}
	//mansi 22-1-2016 (add buyers order no)
	//mansi 22-1-2016 (add buyers order no)
	function addtocart(template_id,template_size_id,id,product_id,country,transport)
	{ 
		var color_id = $('#color_combo'+id).val();
		var quantity = $('#quantity'+id).val();
		var note = $('#note'+id).val();		
		var ship_type = $('input[name=shipment]:radio:checked').val();
		var d_date = $('#input_ddate'+id).val();
		var shipmentcountry = country;
		var transport = $('#transport_details').val();			
		var userid = $('#userid').val();
		var address = $('#addressval').val();
		var permission = $('#permission').val();
		var client_name = $('#keyword').val();
		var client_price=$('#client_price'+id).val();
		var stock_price_comp=$('#stock_price_comp'+id).val();
		var cust_order_no=$('#cust_order_no'+id).val();
		var buyers_order_no=$('#buyers_order_no').val();
		var order_type= $('input[name=order_type]:radio:checked').val();
		var product_code_id=$('#product_code_id').val();
		var con_id= $("#con_id").val();
		var filling="";	
		var stock_print = $('input[name=stock_print]:radio:checked').val();
    	var digital_print_color = $('#digital_print_color').val();
        var front_color = $('#front_color').val();
    	var back_color = $('#back_color').val();
        //alert(digital_print_color);
            if(stock_print=="Digital Print"){
                var file_data = $("#die-line").prop("files")[0];    
            }else{
               var file_data='';
                
            }
    	//var dieline = $('#die-line').val(); 
        
             // Getting the properties of file from file field
    	var form_data = new FormData();                            // Creating object of FormData class
    	form_data.append("file", file_data)
    	form_data.append("color_id", color_id) 
    	form_data.append("quantity", quantity) 
    	form_data.append("ship_type", ship_type) 
    	form_data.append("note", note) 
    	form_data.append("d_date", d_date) 
    	form_data.append("shipmentcountry", shipmentcountry) 
    	form_data.append("transport", transport) 
    	form_data.append("userid", userid) 
    	form_data.append("transport", transport) 
    	form_data.append("address", address) 
    	form_data.append("permission", permission) 
    	form_data.append("client_name", client_name) 
    	form_data.append("client_price", client_price) 
    	form_data.append("stock_price_comp", stock_price_comp) 
    	form_data.append("cust_order_no", cust_order_no) 
    	form_data.append("buyers_order_no", buyers_order_no) 
    	form_data.append("order_type", order_type) 
    	form_data.append("product_code_id", product_code_id) 
    	form_data.append("con_id", con_id) 
    	form_data.append("stock_print", stock_print)
    	form_data.append("digital_print_color", digital_print_color)
    	form_data.append("product_id", product_id)
    	form_data.append("template_size_id", template_size_id)
    	form_data.append("template_id", template_id)
    	form_data.append("back_color", back_color)
    	form_data.append("front_color", front_color)
    	//
		if(product_id=='31' || product_id=='16' || product_id=='50')
		     var filling= $('input[name=filling]:radio:checked').val();
		     
		form_data.append("filling", filling)
		
		var reference_no="";
	
		 if(con_id=='252'||con_id=='112'||con_id=='42'||con_id=='238' ||con_id=='189' || con_id=='47' ||con_id=='251'||con_id=='90'||con_id=='170'||con_id=='230'||con_id=='253'||con_id=='209'||con_id=='172')
		    reference_no=$("#ref_no").val();
		
        form_data.append("reference_no", reference_no)
		
		if(order_type=='sample')
			$('input[name=order_type][value=commercial]').attr('disabled','disabled');
		else
			$('input[name=order_type][value=sample]').attr('disabled','disabled');
		
		//console.log(form_data);
			
		if(stock_price_comp==1)
		{
		
			var cond="stock_price_comp=='1' && client_price!=''";
		}
		else
		{
			var cond="stock_price_comp=='0'";
		}
	if( $('#keyword').val()!='')
	{
		if(quantity>0 && cond)
		{
			var addtocart_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=addtocart', '',1);?>");
			$.ajax({
					//url : addtocart_url,
            		//method : 'post',
            		//data: {data:form_data},                         // Setting the data attribute of ajax with file_data
					//data : {template_id : template_id,template_size_id : template_size_id,color_id : color_id,quantity : quantity,note:note,client_name:client_name,shipmentcountry : shipmentcountry,transport:transport,product_id : product_id,ship_type : ship_type,userid : userid,address : address,permission : permission,d_date : d_date,client_price:client_price,cust_order_no:cust_order_no,buyers_order_no:buyers_order_no,reference_no:reference_no,order_type : order_type, product_code_id:product_code_id,filling:filling,stock_print:stock_print,digital_print_color:digital_print_color},
					url:addtocart_url,
            		dataType: 'script',
            		cache: false,
            		contentType: false,
            		processData: false,
            		data: form_data,                         // Setting the data attribute of ajax with file_data
            		type: 'post',
					success: function(response)
					{
					console.log(response);
						set_alert_message('Successfully Added To Your Cart',"alert-success","fa-check");
						$('#quantity'+id).val('');
						$('#buyers_order_no').val('');
						//$('#ref_no').val('');
						
			
						var refreshcart_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=GetOrderList', '',1);?>");
						$.ajax({
								url : refreshcart_url,
								method : 'post',
								data : {},
								success: function(response)
								{
									$('#cartDiv').html(response);
								},
								error: function()
								{ 
									return false;	
								}
							});
					},
					error: function()
					{
						return false;	
					}
				});
		}
		else if(stock_price_comp==1 && client_price=='')
		{
		
			alert('Please Enter Client Price');
		}
		else if(quantity=='')
		{
			alert('Please Enter Quantity');		
		}
	}
	else
	{
		alert('Please Enter Customer Name');		
	}
	}

function inputddate(n)
{ 

	$('#input_ddate'+n).datepicker('show');
	var nowTemp = new Date();
		var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
		var checkin = $('#input_ddate'+n).datepicker({
				onRender: function(date) {
					
					return date.valueOf() < now.valueOf() ? 'disabled' : '';
				}
			}).on('changeDate', function(ev) {
				checkin.hide();
				
    	}).data('datepicker');
		
}

$(document).click(function(){
		$("#ajax_product").fadeOut('slow');
		$("#ajax_product").html("");
});
	   	$("#product_code").focus();
		var offset = $("#product_code").offset();
		var width = $("#holder").width();
		$("#ajax_product").css("width",width);
		
		$("#product_code").keyup(function(event){
		 var product_code = $("#product_code").val();
			//alert(product_code);
		
		 if(product_code.length)
		 {	
		 	
			 if(event.keyCode != 40 && event.keyCode != 38 )
			 {		
				 var product_code_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=product_code', '',1);?>");
				 $("#loading").css("visibility","visible");
				 $.ajax({
				   type: "POST",
				   url: product_code_url,
				   data: "product_code="+product_code,
				   success: function(msg){
					  // alert(msg);
				   var msg = $.parseJSON(msg);
				  //	alert(msg);
				   var div='<ul class="list">';
				   
					if(msg.length>0)
					{
						for(var i=0;i<msg.length;i++)
						{	
							div =div+'<li><a href=\'javascript:void(0);\' discr="'+msg[i].description+'" size="'+msg[i].volume+'" mea="'+msg[i].measurement+'" color="'+msg[i].color+'" product_id="'+msg[i].product+'" product_name="'+msg[i].product_name+'" product_id="'+msg[i].product+'"   id="'+msg[i].product_code_id+'"><span class="bold" >'+msg[i].product_code+'</span></a></li>';			
						}
					}
					
					div=div+'</ul>';
					if(msg != 0)
					  $("#ajax_product").fadeIn("slow").html(div);
					else
					{
					  $("#ajax_product").fadeIn("slow");	
					  $("#ajax_product").html('<div style="text-align:left;">No Matches Found</div>');
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
							$("#product_code").val($(".list li[class='selected'] a").text());
							$("#product_div").show();
                  			$("#product_name").val($(".list li[class='selected'] a").attr("discr"));
							$("#product_code").val($(".list li[class='selected'] a").attr("product_code"));
							$("#real_product_name").val($(".list li[class='selected'] a").attr("product_name"));
							$("#pro_id").val($(".list li[class='selected'] a").attr("product_id"));
							$("#size").val($(".list li[class='selected'] a").attr("size"));
							$("#measurement").val($(".list li[class='selected'] a").attr("mea"));
							$("#product_code_id").val($(".list li[class='selected'] a").attr("id"));
							$("#color_id").val($(".list li[class='selected'] a").attr("color"));
						
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
							$("#product_code").val($(".list li[class='selected'] a").text());
							$("#product_div").show();
                  			$("#product_name").val($(".list li[class='selected'] a").attr("discr"));
							$("#product_code").val($(".list li[class='selected'] a").attr("product_code"));
							$("#real_product_name").val($(".list li[class='selected'] a").attr("product_name"));
							$("#pro_id").val($(".list li[class='selected'] a").attr("product_id"));
							$("#size").val($(".list li[class='selected'] a").attr("size"));
							$("#measurement").val($(".list li[class='selected'] a").attr("mea"));
							$("#product_code_id").val($(".list li[class='selected'] a").attr("id"));
							$("#color_id").val($(".list li[class='selected'] a").attr("color"));
						}
				 }
				 break;				 

				}
			 }
		 }
		 else
		 {	
			$("#ajax_product").fadeOut('slow');
			$("#ajax_product").html("");
		 }
		 
	});
	$('#product_code').keydown( function(e) {
    if (e.keyCode == 9) {
		 $("#ajax_product").fadeOut('slow');
		 $("#ajax_product").html("");
    }
	
});
$("#ajax_product").mouseover(function(){
			
				$(this).find(".list li a:first-child").mouseover(function () {
				$("#product_div").show();
                $("#product_name").val($(this).attr("discr"));
				$("#product_code").val($(this).attr("product_code"));
				$("#real_product_name").val($(this).attr("product_name"));
				$("#pro_id").val($(this).attr("product_id"));
				$("#size").val($(this).attr("size"));
				$("#measurement").val($(this).attr("mea"));
				$("#color_id").val($(this).attr("color"));
				$(this).addClass("selected");
			});
			$(this).find(".list li a:first-child").mouseout(function () {
				  $(this).removeClass("selected");
			});
			$(this).find(".list li a:first-child").click(function () {
				
				// alert($(this).attr("id"));
				 var product_code_id=$(this).attr("id");
				 	var country_id = $('#country_id').val();
						//sonu 12/12/2016
						if(country_id=='111'){
							 getrate(product_code_id);
						}
				
				 $("#product_div").show();
                 $("#product_name").val($(this).attr("discr"));
				 $("#product_code").val($(this).attr("product_code"));
				 $("#real_product_name").val($(this).attr("product_name"));
				 $("#pro_id").val($(this).attr("product_id"));
				 $("#size").val($(this).attr("size"));
				 $("#measurement").val($(this).attr("mea"));
				 $("#product_code_id").val($(this).attr("id"));
				 $("#product_code").val($(this).text());
				 $("#ajax_product").fadeOut('slow');
				 $("#color_id").val($(this).attr("color"));
				 $("#ajax_product").html("");
					
				//showSize();
				hi_con($(this).attr("product_id"));
				
				if($(this).attr("product_id")=='31' || $(this).attr("product_id")=='16' || $(this).attr("product_id")=='50')
    				$("#filling_div").show();
    			else
    				$("#filling_div").hide();
				
				
				});
			
		});
function fill(name){
	$('#product_name').val(name);
	$('#display').hide();	
}
	 //add by sonu  21-03-2018  for digital_print 
function getColorforDigitalPrint(){

	var val=$('input[name=stock_print]:radio:checked').val();
	//alert(val);
	if(val == "Digital Print"){
		$('#digital_print_div').css("display","block");
		$('#digital_print_div1').css("display","block");
		$('#digital_print_div2').css("display","block");
		//$("#stock_print1").attr('disabled',true);
	}
	else
	{
	   
	    $('#digital_print_div').css("display","none");
	    $('#digital_print_div1').css("display","none");
	    $('#digital_print_div2').css("display","none");
	    //$("#digital_print2").attr('disabled',true);
	}
}
$('.btn-number').click(function(e){
    e.preventDefault();
    
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
             if(type == 'minus1') {
         
            if(currentVal > input.attr('min')) {
                input.val(currentVal - 1).change();
            } 
            if(parseInt(input.val()) == input.attr('min')) {
                $(this).attr('disabled', true);
            }

        } else if(type == 'plus1') {
            
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
$('#product_nm').change(function(){
  var value = $(this).val();
  var url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getVolume', '',1);?>");
		$.ajax({
			url : url,
			method : 'post',
      		data:{value:value},	
			success: function(response){
				$('#volume').html(response);
			},
			
		});
});
$(document).on('change','#volumedetails',function(){
  var value = $(this).val();
  var product_nm = $('#product_nm').val();
  //alert(value);
  var url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getPCode', '',1);?>");
		$.ajax({
			url : url,
			method : 'post',
      		data:{product_nm:product_nm,value:value},	
			success: function(response){
				
				$('#color_con').html(response);
			},
			
		});
});
$(document).on('change','#clr_details',function(){
  var value = $(this).val();
  var product_nm = $('#product_nm').val();
  var volumedetails = $('#volumedetails').val();
  //alert(value);
  var url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getimage', '',1);?>");
		$.ajax({
			url : url,
			method : 'post',
      		data:{product_nm:product_nm,value:value,volumedetails:volumedetails},	
			success: function(response){
				
				$('#img_div').html(response);
			},
			
		});
});
function addproductcode(product_code_id,product_code,description,img_no,total){
	$('#product_code_id').val(product_code_id);
	$('#product_code').val(product_code);
	$('#product_name').val(description);
	var id = $('#product_nm').val();
	
	hi_con(id);

    	$('#product_code').css("display","none");
	$('#product_name').css("display","none");
	
	for(var i=1; i<=total; i++){
	    if(i!==img_no){
	    
	      $('#pro_img_'+i).css("display","none");
	    }
	}
	
}
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

</script> 
<script class="jsbin" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>  
<style>
	.inactive{
		//background-color:#999;	
	}
</style>

        
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>