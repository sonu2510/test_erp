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
}

if($display_status){
	$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
	$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
	//$userCurrency = $obj_pro_invoice->getUserCurrencyInfo($user_type_id,$user_id);
	$addedByInfo = $obj_template->getUser($user_id,$user_type_id);
	
}


if($display_status) {
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
			  ?>
                	<form class="form-horizontal" method="post" name="form" id="order-form" enctype="multipart/form-data">
                   
                       <div class="form-group">
                       	<label class="col-lg-3 control-label"><span class="required">*</span> Client Name</label>
                        <div id="holder" class="col-lg-4"> 
                      			<input type="text" id="keyword" tabindex="0" class="form-control "  autocomplete="off">
                     			 <div id="ajax_response"></div>
                     	</div>
 				        </div>
                        <?php // mansi 22-1-2016 (add buyers order no in template order)?>
                        <div class="form-group">
                       	<label class="col-lg-3 control-label"><span class="required">*</span> Buyers Order No</label>
                        <div id="holder" class="col-lg-4"> 
                      			<input type="text" id="buyers_order_no" name="buyers_order_no" class="form-control " >
                     			 <div id="ajax_response"></div>
                     	</div>
 				        </div>
                        
                        <input type="hidden" name="con_id" id="con_id" value="<?php echo $addedByInfo['country_id'];?>">
                        <?php // sejal 14-04-2017 told by vikas sir
								
								  if($addedByInfo['country_id']=='252'){
								  ?>
                                <div class="form-group">
                                <label class="col-lg-3 control-label"><span class="required">*</span> Reference No</label>
                                <div id="holder" class="col-lg-4"> 
                                        <input type="text" id="ref_no" name="ref_no" class="form-control " >
                                </div>
                                </div>
                                <?php } 
                                ?>
                                
                                                             
                        
                        
  				 <div class="form-group">
                        <label class="col-lg-3 control-label">Product</label>
                        <div class="col-lg-4">
                              <select name="product_details" id="product_details" class="form-control" onchange="product_detail();">
                              
				  <?php
				  $templatedetails =$obj_template->gettemplatetitle($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE']);
			 
			     foreach($templatedetails as $temdet){  
			     if($temdet['product_id']=='3')
			     {?>
			        <option value="<?php echo $temdet['product_id']; ?>" selected="selected">
						<?php echo preg_replace("/\([^)]+\)/","",$temdet['product_name']); ?>
						 </option>
			     <?php }
			     else
			     {
			     ?>
                
                        
                        <option value="<?php echo $temdet['product_id']; ?>">
						<?php echo preg_replace("/\([^)]+\)/","",$temdet['product_name']); ?>
						 </option>
    
                <?php } 
                }?></select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Shippment-Country</label>
                        <div class="col-lg-4">
                             <select name="shippment_details" id="shippment_details" class="form-control"  >
             				   <option value="">Select Country</option>
				  </select>
                        </div>
                      </div>
                       <div class="form-group">
                        <label class="col-lg-3 control-label">Transpotation</label>
                        <div class="col-lg-4">
                             <select name="transport_details" id="transport_details" class="form-control" >
                             <option value="">Select Transpotation</option>
                             <option value="By Air"  selected="selected" >By Air</option>
                             <option value="By Sea">By Sea</option>
				  </select>
                        </div>
                      </div>
                       <div class="form-group">
                        <label class="col-lg-3 control-label">Zipper</label>
                         <?php 
									$zipperlist = $obj_template->getActiveProductZippers();
							?>
                        <div class="col-lg-4">
               	              <select name="zipper_details" id="zipper_details" class="form-control"  >
               	                  
               				  <option value="">Select Zipper</option>
               			   	<?php foreach($zipperlist as $zipper) { ?>
                                        	<?php if( $zipper['zipper_name'] =='With Zipper') { ?>
                                            
                                    			<option value="<?php echo $zipper['zipper_name']; ?>" selected="selected"><?php echo $zipper['zipper_name']; ?></option>
                                            <?php } else { ?>
                                            	<option value="<?php echo $zipper['zipper_name']; ?>"><?php echo $zipper['zipper_name']; ?></option>
                                            <?php } ?>
                                        <?php } ?>              
                            
				  </select>
                        </div>
                      </div>
                       <div class="form-group">
                        <label class="col-lg-3 control-label">Valve</label>
                        <div class="col-lg-4">
                              <select name="valve_details" id="valve_details" class="form-control"  >
               				  <option value="">Select Valve</option>
                              <option value="No Valve" selected="selected">no Valve</option>
                                <option value="With Valve">With Valve</option>
				  </select>
                        </div>
                      </div>
                      
                     <!-- change by sejal 13-02-2017-->
                    <div class="form-group" >
                        <label class="col-lg-3 control-label"><span class="required">*</span>Volume</label>
                        <div class="col-lg-4" id ="holder">
                     <?php /*?>   <select name="volume" id="volume" class="form-control validate[required] chosen-select">
                        <?php $all_volume = $obj_template->getVolume(); ?>
                        <option value="">Select Volume</option>
                        <?php foreach($all_volume as $volume){ ?>
                        <option value="<?php echo $volume['volume']?>"><?php echo $volume['volume']?></option>
                        <?php } ?>
                  </select><?php */?>
                  
                  <input type="text" id="volume_details" tabindex="0" class="form-control "  autocomplete="off">
                     			 <div id="ajax_volume"></div>
                        </div>
                      </div>    
                      
                      
					 <?php /*?> <div class="form-group">
                       	<label class="col-lg-3 control-label"><span class="required">*</span> Volume</label>
                        <div id="holder" class="col-lg-4"> 
                      			<input type="text" id="volume" name="volume" class="form-control validate[required]" >
                     	</div>
 				        </div>
					  <?php */?>
					  <div class="form-group">
              	<label class="col-lg-3 control-label"><span class="required">*</span>Colour</label>
                        	<div class="col-lg-3">
                             <?php $colors = $obj_template->getActiveColor();?>
                            <select name="color" id="color" class="form-control validate[required] chosen_data" onchange="checktemplatelist();">
                                    <option value="">Select Color</option>
                                     <?php foreach($colors as $colors2){ ?>
										<option value="<?php echo $colors2['pouch_color_id']; ?>" id="option"
                                        <?php //if($clr['color'] == $colors2['pouch_color_id']) { echo 'selected="selected"'; } ?>> 
										<?php echo $colors2['color']; ?></option>
                                        <?php } ?>  
                                                                           
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
              <?php $deafultcountry = $obj_template->getDefaultcountry($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE']);?>
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
<script src="https://harvesthq.github.io/chosen/chosen.jquery.js" type="text/javascript"></script>
<link rel="stylesheet" href=" https://harvesthq.github.io/chosen/chosen.css" type="text/css"/> 
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<!-- select2 --> <script src="<?php echo HTTP_SERVER;?>js/select2/select2.min.js"></script>
<script>
jQuery(document).ready(function(){
   
   product_detail();
      //  $(".chosen_data").chosen();
   //  $(".chosen-select").chosen();
  
});

</script>
<script type="application/javascript">

$(document).ready(function(){
    
     // $(".chosen_data").chosen();
     //$(".chosen-select").chosen();
    //$(function()){
   //alert("kinjal");
     // alert("hi");
	
		//$("#keyword").keyup();
    //});
      //$(".chosen_data").chosen();
    
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
		// alert(keyword);
		 if(keyword.length)
		 {
			 if(event.keyCode != 40 && event.keyCode != 38 )
			 {
				 var client_name_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=client_name', '',1);?>");
				 $("#loading").css("visibility","visible");
				 $.ajax({
				   type: "POST",
				   url: client_name_url,
				   data: "client_name="+keyword,
				   success: function(msg){					
				 var msg = $.parseJSON(msg);
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
	var client_name_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=client_name', '',1);?>");
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


function product_detail()
	{
		//alert("hi");
		var product_id = $('#product_details').val();	
		//alert(product_id);
		var volume_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=displayvolume', '',1);?>");
			$.ajax({
			type: 'post',
			url : volume_url,
			data:{product_id:product_id},	
			success: function(response){
				//alert(response);
				$("#volume_detail").html(response);
			}
		 });
		
		var template_id = $('#product_details').val();
		var product_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=displaycountry', '',1);?>");
		
		var userid = $('#userid').val();
		var defaultcountry = $('#defaultcountry').val();
	
	
		$.ajax({
			url : product_url,
			method : 'post',
      		 data:{template_id:template_id,userid:userid},	
			success: function(response){
				
				
					var val = $.parseJSON(response);
				//alert(val);
			//	var tselect = $('#transport_details');
				//
			//	tselect.children().remove();
			//	tselect.append($("<option>").val(0).text('Select Transpotation'));
				
				var dselect = $('#shippment_details');dselect.children().remove();
				dselect.append($("<option>").val(0).text('Select Country'));
				
				for(var i=0;i<val.length;i++)
				{
				
				
			if(defaultcountry == val[i].country_id)
			{
				dselect.append($("<option>").val(val[i].country_id).text(val[i].country_name).attr('selected', true));	
				
		country_id = $('#shippment_details').val();
		
		$("#country_id").val(val[i].country_id);
		//$("#transport").val('');
					//product_id = $('#product_details').val();
			//		var product_url = getUrl("<?php //echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=displaytranspotation', '',1);?>");
			//	
			//		var userid = $('#userid').val();
			//	
			//		$.ajax({
			//			url : product_url,
			//			method : 'post',
			//      		 data:{country_id:country_id,product_id:product_id,userid:userid},	
			//			success: function(response){
			//				
			//				var dselect = $('#transport_details');
			//				response = response.split("==");
			//				dselect.children().remove();
			//				dselect.append($("<option>").val(response[i]).text('Select Transpotation'));
			//				
			//				
			//				for(var i=0;i<response.length-1;i++)
			//				{
			//					var res_val=response[i];
			//					if(country_id=='42')
			//					{
			//						if(response[i]=='By Air')
			//						   response[i]='Rush Order';
			//						 else
			//						  response[i]='Normal Order';
			//					}
			//					else
			//						response[i]=response[i];
			//						
			//					if(response[i]=='By Air')
			//					    dselect.append($("<option selected='selected'>").val(res_val).text(response[i]));
			//					else
			//					     dselect.append($("<option>").val(res_val).text(response[i]));
			//					
			//					
			//				}
			//				$('#order_template').html('');
			//				
			//			},
			//			error: function(){
			//				set_alert_message('Error!',"alert-warning","fa-warning"); 
			//			}
			//		});	
			//$("#transport_details").change();
			    
			}
			else
				dselect.append($("<option>").val(val[i].country_id).text(val[i].country_name));
				
				}
			
				$('#order_template').html('');
				
				$("#product_id").val(template_id);
			
				//$("#transport").val('');
							
			},
			error: function(){
				set_alert_message('Error!',"alert-warning","fa-warning"); 
			}
		});
		
		//	$("#transport_details").val('');
	    //	$('#zipper_details').val('');
	//		$('#valve_details').val('');
		//	$('#color').val('');
	//		$("#volume_details").val('');
	}
	$("#shippment_details").change(function()
	{
		$("#transport_details").val('');
		$('#zipper_details').val('');
			$('#valve_details').val('');
			$('#color').val('');
			$("#volume_details").val('');
		//country_id = $('#shippment_details').val();
//		var defaultcountry = $('#defaultcountry').val();
//		if(defaultcountry!=country_id)
//		{
//			var self = '0'; var client='1';
//			$("input[type=radio][value=" + self + "]").prop("disabled",true);
//			$("input[type=radio][value=" + client + "]").prop('checked', 'checked');
//			$("#address").show();
//			$("#addressval").val('');
//		}
//		else
//		{
//			var self = '0';var client='1';
//			$("input[type=radio][value=" + self + "]").prop("disabled",false);			
//			$("input[type=radio][value=" + client + "]").prop('checked', false);
//			$("input[type=radio][value=" + self + "]").prop('checked', true);
//			$("#address").hide();
//			$("#addressval").val('');
//		}
//	
//		$("#country_id").val(country_id);
//		$("#transport").val('');
//		product_id = $('#product_details').val();
//		var product_url = getUrl("<?php //echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=displaytranspotation', '',1);?>");
//		
//		var userid = $('#userid').val();
//	
//		$.ajax({
//			url : product_url,
//			method : 'post',
//      		 data:{country_id:country_id,product_id:product_id,userid:userid},	
//			success: function(response){
//				 
//				var dselect = $('#transport_details');
//				response = response.split("==");
//				dselect.children().remove();
//				dselect.append($("<option>").val(response[i]).text('Select Transpotation'));
//				for(var i=0;i<response.length-1;i++)
//				
//				dselect.append($("<option>").val(response[i]).text(response[i]));
//				$('#order_template').html('');
//				
//			},
//			error: function(){
//				set_alert_message('Error!',"alert-warning","fa-warning"); 
//			}
//		});
//	//	transport_detail();
		
	});
	
	$("#transport_details").change(function()
	//function trans_detail()
	{
		
		
			$('#zipper_details').val('');
			$('#valve_details').val('');
			$('#color').val('');
			$("#volume_details").val('');
	//	var transport = $("#transport_details").val();
//		
//		$("#transport").val(transport);
//		
//		var product_id = $("#product_id").val();
//		var country_id = $("#country_id").val();
//		
//		var product_url = getUrl("<?php //echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=displayzippervalve', '',1);?>");
//	
//		var userid = $('#userid').val();
//	
//		$.ajax({
//			url : product_url,
//			method : 'post',
//      		 data:{transport:transport,product_id:product_id,country_id:country_id},	
//			success: function(response){
//				 
//				var val = $.parseJSON(response);
//				var vselect = $('#valve_details');
//				vselect.children().remove();
//				
//				var zselect = $('#zipper_details');
//				zselect.children().remove();
//		
//		
//				zselect.append($("<option>").val(0).text('Select Zipper'));
//				vselect.append($("<option>").val(0).text('Select Valve'));
//		
//				
//				for(var i=0;i<val.valve.length;i++)
//				{
//					if(val.valve[i] =='No Valve')
//					   
//						vselect.append($("<option selected='selected'>").val(val.valve[i]).text(val.valve[i]));
//					else					   
//						vselect.append($("<option>").val(val.valve[i]).text(val.valve[i]));
//				}
//				for(var i=0;i<val.zipper.length;i++)
//				{
//					if(val.zipper[i] =='With Zipper')
//						zselect.append($("<option selected='selected'>").val(val.zipper[i]).text(val.zipper[i]));
//					else
//						zselect.append($("<option >").val(val.zipper[i]).text(val.zipper[i]));
//				}
//			
//				$('#order_template').html('');
//				
//			},
//			error: function(){
//				set_alert_message('Error!',"alert-warning","fa-warning"); 
//			}
//		});
		
		
	});
//}
	$("#zipper_details").change(function()
	{
		$('#valve_details').val('');
			$('#color').val('');
			$("#volume_details").val('');
		//
//		var transport = $("#transport_details").val();
//		$("#transport").val(transport);
//		
//		var product_id = $("#product_id").val();
//		var country_id = $("#country_id").val();
//		var zipper = $("#zipper_details").val();
//		var valve = $("#valve_details").val();
//		
//		var add_product_url = getUrl("<?php //echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=checktemplatelist', '',1);?>");
//		
//	
//		$.ajax({
//			url : add_product_url,
//			method : 'post',
//      		 data:{transport:transport,product_id:product_id,country_id:country_id,zipper:zipper,valve:valve},	
//			success: function(response){
//				
//				$('#order_template').html(response);
//				$('#color').val('');
//			},
//			error: function(){
//				set_alert_message('Error!',"alert-warning","fa-warning"); 
//			}
//		});
//	
		
	});
	
	$("#valve_details").change(function()
	{
	    	$('#color').val('');
			$("#volume_details").val('');
		
	});
	$("#volume_details").change(function()
	{
	    	$('#color').val('');
			
		
	});
	
	function checktemplatelist()
	{
		//alert("hii");
		var transport = $("#transport_details").val();
		$("#transport").val(transport);
				
		var product_id = $("#product_id").val();
		var country_id = $("#country_id").val();
		var zipper = $("#zipper_details").val();
		var valve = $("#valve_details").val();
		var color = $("#color").val();
		var volume = $("#volume_details").val();
		
		var add_product_url = getUrl("<?php  echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=checktemplatelist', '',1);?>");
		
	
		$.ajax({
			url : add_product_url,
			method : 'post',
      		 data:{transport:transport,product_id:product_id,country_id:country_id,zipper:zipper,valve:valve,color:color,volume:volume},	
			success: function(response){
				
				$('#order_template').html(response);
			},
			error: function(){
				set_alert_message('Error!',"alert-warning","fa-warning"); 
			}
		});
	
		
	}

	function shipmentcountry(shipment)
	{$("#addressval").val('');
		$("#address").show();
		
		
	}
	function selfshipment(shipment)
	{	$("#addressval").val('');
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
	    var con_id= $("#con_id").val();
	    var reference_no='';
	if(con_id=='252')	
		reference_no=$('#ref_no').val();
		//alert(order_type);
		
		if(order_type=='sample')
			$('input[name=order_type][value=commercial]').attr('disabled','disabled');
		else
			$('input[name=order_type][value=sample]').attr('disabled','disabled');
			
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
					url : addtocart_url,
					method : 'post',
					data : {template_id : template_id,template_size_id : template_size_id,color_id : color_id,quantity : quantity,note:note,client_name:client_name,
					shipmentcountry : shipmentcountry,transport:transport,product_id : product_id,ship_type : ship_type,userid : userid,address : address,permission : permission,d_date : d_date,client_price:client_price,cust_order_no:cust_order_no,buyers_order_no:buyers_order_no,reference_no:reference_no,order_type : order_type},
					success: function(response)
					{
					//alert(response);
						set_alert_message('Successfully Added To Your Cart',"alert-success","fa-check");
						$('#quantity'+id).val('');
						$('#buyers_order_no').val('');
						
			
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
	 
//
//$("#volume").focus(function(event){
//		 var keyword = $("#keyword").val();
//		product_id = $('#product_details').val();	
//		//alert(product_id);
//		
//		//change by sejal 13-02-17
//		var volume_url = getUrl("<?php //echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=displayvolume', '',1);?>");
//			$.ajax({
//			type: "POST",
//			url : volume_url,
//			data: "product_id="+volume,			
//      		//data:{product_id:product_id},	
//			success: function(response){
//		//	alert(response);
//			 var val =  $.parseJSON(response);
//				//alert (val);
//			var div='<ul class="list">';
//			    if(response.length>0)
//				{
//					for(var i=0;i<response.length;i++)
//					{	
//						div =div+'<li><a href=\'javascript:void(0);\' vol="'+response[i].volume+'" </a></li>';			
//	
//					}
//				}
//				div=div+'</ul>';
//								
//				
//			}
//			});
//});
//				
				

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

 //PRIDICTION FOR VOLUME 
$("#volume_details").focus();
	var offset = $("#volume_details").offset();
	var width = $("#holder").width();
	$("#ajax_volume").css("width",width)
	$("#volume_details").keyup(function(event){		
		 var keyword = $("#volume_details").val();
		 //alert(keyword);
		 
		 if(keyword.length)
		 {	
			 if(event.keyCode != 40 && event.keyCode != 38 )
			 {	
				 var volume_pridict = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=volume_pridict', '',1);?>");
				 $("#loading").css("visibility","visible");
				 $.ajax({
				   type: "POST",
				   url: volume_pridict,
				   data: {keyword:keyword},
				   success: function(result){	
				 var msg = $.parseJSON(result);
				 
				   var div='<ul class="list">';
				   
					if(msg.length>0)
					{ 
						for(var i=0;i<msg.length;i++)
						{	
							div =div+'<li><a href=\'javascript:void(0);\' volume_id="'+msg[i].volume_id+'" volume="'+msg[i].volume+'"><span class="bold">'+msg[i].volume+'</span></a></li>';
						}
					}
					
					div=div+'</ul>';
					
					if(msg != 0)
					  $("#ajax_volume").fadeIn("slow").html(div);
					else
					{
					  $("#ajax_volume").fadeIn("slow");	
					  $("#ajax_volume").html('<div style="text-align:left;">No Matches Found</div>');
					   //$("#volume_id").val('');
				  		
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
							$("#volume_details").val($(".list li[class='selected'] a").text());
							//$("#email").val($(".list li[class='selected'] a").attr("email"));
							//$("#consignee").val($(".list li[class='selected'] a").attr("consignee"));
							//$("#delivery_info").val($(".list li[class='selected'] a").attr("deladd"));
							//$("#del_info").val($(".list li[class='selected'] a").attr("deladd"));
							//$("#volume_id").val($(".list li[class='selected'] a").attr("volume_id"));
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
							$("#volume_details").val($(".list li[class='selected'] a").text());
							//$("#email").val($(".list li[class='selected'] a").attr("email"));
							//$("#consignee").val($(".list li[class='selected'] a").attr("consignee"));
							//$("#delivery_info").val($(".list li[class='selected'] a").attr("deladd"));
							//$("#del_info").val($(".list li[class='selected'] a").attr("deladd"));
							//$("#volume_id").val($(".list li[class='selected'] a").attr("volume_id"));
						}
				 }
				 break;				 
				}
			 }
		 }
		 else
		 {
			$("#ajax_volume").fadeOut('slow');
			$("#ajax_volume").html("");
		 }
	});
	$('#volume_details').keydown( function(e) {
		if (e.keyCode == 9) {
			 $("#ajax_volume").fadeOut('slow');
			 $("#ajax_volume").html("");
		}
	});

$("#ajax_volume").mouseover(function(){
			$(this).find(".list li a:first-child").mouseover(function () {
                  //$("#email").val($(this).attr("email"));
				  //$("#consignee").val($(this).attr("consignee"));
				  //$("#delivery_info").val($(this).attr("deladd"));
				  //$("#del_info").val($(this).attr("deladd"));
				  //$("#volume_id").val($(this).attr("volume_id"));
				  $(this).addClass("selected");
			});
			$(this).find(".list li a:first-child").mouseout(function () {
				  $(this).removeClass("selected");
				  //$("#email").val('');
				  //$("#consignee").val('');
				  //$("#delivery_info").val('');
				  //$("#volume_id").val('');
			});
			$(this).find(".list li a:first-child").click(function () {
                  //$("#email").val($(this).attr("email"));
				  //$("#consignee").val($(this).attr("consignee"));
				  //$("#delivery_info").val($(this).attr("deladd"));
				  //$("#del_info").val($(this).attr("deladd"));
				  //$("#volume_id").val($(this).attr("volume_id"));
				  $("#volume_details").val($(this).text());
				  $("#ajax_volume").fadeOut('slow');
				 	$("#ajax_volume").html("");
				
			});
			
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