<?php
// ---------------------------------------------------------------------------------------------------------------------------Mode setting for the ADD stock Starts here
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

include('model/product_quotation.php');
$obj_quotation = new productQuotation;

//Start : edit
$edit = '';
if(isset($_GET['rack_master_id']) && !empty($_GET['rack_master_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$rack_master_id = base64_decode($_GET['rack_master_id']);
		$rack_data = $obj_rack_master->getRackData($rack_master_id);
		$edit = 1;
	}
	
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}
//Close : edit
/*
if($display_status){
	//insert 
	if(isset($_POST['btn_save'])){
		$post = post($_POST);		
		//printr($post);//	die;
		$insert_id = $obj_rack_master->addstock($post,1);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '&mod=india_add', '',1));
	}
	*/
	
	

// ---------------------------------------------------------------------------------------------------- Mode setting for the ADD stock Ends here

// ------------------------------------------------------------------------------------------------------Mode setting for the dispatch Starts here

//include("mode_setting_dispatch.php");

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

if(!$obj_general->hasPermission('view',$menuId)){
	$display_status = false;
}



if($display_status) {
    
    $user_type_id = $_SESSION['LOGIN_USER_TYPE'];
	$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];

if(isset($_POST['btn_save'])){
		$post = post($_POST);		

		$insert_id = $obj_rack_master->addstock($post,1);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '&mod=india_add', '',1));
	}

?>

<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-edit"></i>  Stock Shifting </h4>
    </div>
    <div class="row">
        	<div class="col-lg-12">
    	       <?php include("common/breadcrumb.php");?>	
            </div> 
        
          <div class="col-sm-12">
            <section class="panel">
              <div class="panel-body">
              <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
                    <section class="panel">
                                   <header class="panel-heading bg-white">Stock Shift </header>
                                   <div class="panel-body">
                                      <form class="form-horizontal" method="post" name="form_dis_product" id="form_dis_product" enctype="multipart/form-data">
                                         <div class="form-group option">
                                            <label class="col-lg-3 control-label"><span class="required">*</span>Product Code</label>
                                            <div class="col-lg-4" id="holder">
                                               <?php $product_codes=$obj_rack_master->getActiveProductCode(); 
                                                  //printr($product_codes);
                                                  ?>
                                               <input type="hidden" id="product_code_id_add" name="product_code_id_add" value="">
                                               <input type="text" id="keyword1" class="form-control validate[required]"  autocomplete="off" value=""> 
                                               <input type="hidden" name="product_code_id_st" id="product_code_id_st" value="" />
                                               <div id="ajax_response1"></div>
                                            </div>
                                            <div class="col-lg-3" id="product_div"> 
                                               <input type="text" name="product_name_st" id="product_name_st"  value="<?php echo isset($_GET['proforma_in_id'])?$product_code['description']:'';?>" disabled="disabled" class="form-control validate" style="width:400px"/>
                                            </div>
                                         </div>
                                         <div class="form-group"  id="table_details_product">
                                         </div>
                                         <div class="form-group">
                                            <label class="col-lg-3 control-label"><span class="required">*</span>Quantity</label>
                                            <div class="col-lg-4">
                                               <input type="text" name="qty_p" id="qty_p"  onchange="check_qty()" value="<?php echo isset($rack_data['column_no'])?$rack_data['column_no']:'';?>" class="form-control validate[required,custom[number]]" placeholder="Quantity">
                                               <input type="hidden" name="box_qty_new_p" id="box_qty_new_p" value="">
                                            </div>
                                         </div>
                                         <div class="form-group">
                                            <label class="col-lg-3 control-label"><span class="required">*</span>Date</label>
                                            <div class="col-lg-4">
                                               <input type="text" name="date" data-date-format="yyyy-mm-dd" value="<?php echo date("Y-m-d");?>" placeholder="Add Date" id="input_date" class="input-sm form-control datepicker validate[required]" />
                                            </div>
                                         </div>
                                         <div>
                                            <input type="hidden" name="stock_id" id="stock_id" value="">
                                            <input type="hidden" name="proforma_no_p" id="proforma_no_p" value="">
                                            <input type="hidden" name="courier_id_p" id="courier_id_p" value="">
                                            <input type="hidden" name="alldata_p" id="alldata_p" value="">
                                            <input type="hidden" name="product_p" id="product_p" value="">
                                            <input type="hidden" name="goods_id_p" id="goods_id_p" value="" />
                                            <input type="hidden" name="row_column_p" id="row_column_p" value="" />
                                            <input type="hidden" name="grouped_qty_p" id="grouped_qty_p" value="" />
                                            <input type="hidden" name="description_p" id="description_p" value="1"/>
                                            <input type="hidden" name="orderno_p" id="orderno_p" value=""/>
                                            <input type="hidden" name="my_orderno_p" id="my_orderno_p" value=""/>
                                            <input type="hidden" name="company_name_p" id="company_name_P" value=""/>												 
                                            <input type="hidden" name="valve_id_p" id="valve_id_p" value="">
                                            <input type="hidden" name="zipper_id_p" id="zipper_id_p" value="">
                                            <input type="hidden" name="spout_id_p" id="spout_id_p" value="" />
                                            <input type="hidden" name="make_id_p" id="make_id_p" value="" />
                                            <input type="hidden" name="color_id_p" id="color_id_p" value="">
                                            <input type="hidden" name="size_id_p" id="size_id_p" value="">
                                            <input type="hidden" name="accessorie_id_p" id="accessorie_id_p" value="" />
                                            <input type="hidden" name="remaining_qty_p" id="remaining_qty_p" value="" />
                                            <input type="hidden" name="product_code_id_p" id="product_code_id_p" value="" />	
                                            <input type="hidden" name="invoice_product_id_p" id="invoice_product_id_p" value="" />
                                            <input type="hidden" name="invoice_id_p" id="invoice_id_p" value="" />
                                            <input type="hidden" name="product_id_p" id="product_id_p" value="" />
                                            <input type="hidden" name="sales_qty_p" id="sales_qty_p" value="" />
                                         </div>
                                         <div class="form-group">
                                            <div class="col-lg-9 col-lg-offset-3">
                                               <button type="button" name="dispatch_product" id="dispatch_product"  onclick="savedispatch_racknotify_product()" class="btn btn-primary">Dispatch</button>
                                            </div>
                                         </div>
                                      </form>
                                   </div>
                                </section>
                           </div>
                        </section>
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
    jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#form").validationEngine();
    });
</script> 

<!-- CSS JS FOR THE ADD STOCK STARTS  HERE  -->

<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
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
#ajax_response,#a_response{
	border : 1px solid #13c4a5;
	background : #FFFFFF;
	position:relative;
	display:none;
	padding:2px 2px;
	top:auto;
	border-radius: 4px;
}
#ajax_response1{
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
<script src="https://harvesthq.github.io/chosen/chosen.jquery.js" type="text/javascript"></script>
<link rel="stylesheet" href=" https://harvesthq.github.io/chosen/chosen.css" type="text/css"/> 

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script> 
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script> 
<script>



   jQuery(document).ready(function(){
        get_pallet();
        get_pallet_box();
        // biget_pallet_boxnds form submission and fields to the validation engine
    //    $(".chosen_data").chosen();
       
		jQuery("#form").validationEngine();
		$("#input_date").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
	 	$("#dis_date").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
		
		
		

    });
	$(".chosen-select").chosen();
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
		 //alert(keyword);
		 
		 if(keyword.length)
		 {	
		 	$("#color_txt").hide();
			$("#product_name").show();
			 if(event.keyCode != 40 && event.keyCode != 38 )
			 {		
				 var product_code_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=product_code', '',1);?>");
				 $("#loading").css("visibility","visible");
				 $.ajax({
				   type: "POST",
				   url: product_code_url,
				   data: "product_code="+keyword,
				   success: function(msg){	
					//alert(msg);
				   var msg = $.parseJSON(msg);
				   var div='<ul class="list">';
				   
					if(msg.length>0)
					{
						for(var i=0;i<msg.length;i++)
						{	
							div =div+'<li><a href=\'javascript:void(0);\' discr="'+msg[i].description+'" product_id="'+msg[i].product+'" color="'+msg[i].color+'" size="'+msg[i].volume+'" mea="'+msg[i].measurement+'" id="'+msg[i].product_code_id+'"><span class="bold" >'+msg[i].product_code+'</span></a></li>';			
						
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
						{
							$("#keyword").val($(".list li[class='selected'] a").text());
							$("#product_div").show();
                  			$("#product_name").val($(".list li[class='selected'] a").attr("discr"));
							$("#product_code_id_add").val($(".list li[class='selected'] a").attr("id"));
							$("#product_id").val($(".list li[class='selected'] a").attr("product_id"));
							
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
							$("#keyword").val($(".list li[class='selected'] a").text());
							$("#product_div").show();
                  			$("#product_name").val($(".list li[class='selected'] a").attr("discr"));
							$("#product_code_id_add").val($(".list li[class='selected'] a").attr("id"));
							$("#product_id").val($(".list li[class='selected'] a").attr("product_id"));
							
						}
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
					$("#product_div").show();
                  $("#product_name").val($(this).attr("discr"));
			
				   $("#product_code_id_add").val($(this).attr("id"));
				   $("#product_id").val($(this).attr("product_id"));
				  $(this).addClass("selected");
			});
			$(this).find(".list li a:first-child").mouseout(function () {
				  $(this).removeClass("selected");
			});
			$(this).find(".list li a:first-child").click(function () {
				  $("#product_div").show();
                  $("#product_name").val($(this).attr("discr"));
				
				 	$("#product_id").val($(this).attr("product_id"));
				  $("#product_code_id_add").val($(this).attr("id"));
				  $("#keyword").val($(this).text()); 
				  $("#ajax_response").fadeOut('slow');
				  $("#ajax_response").html("");
				});
			
		});
		
		

function get_pallet()
{
	var rack_val=$("#rack_name").val();
	var arr = rack_val.split('=');
	var row = arr[0];
	var col = arr[1];
	var goods_master_id = arr[2];
	var order_status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getLabel_pallet_add_stock', '',1);?>");
	$.ajax({
			url : order_status_url,
			method : 'post',
			data : {row:row,col:col,goods_master_id:goods_master_id},
			success: function(response){
				$("#rack_number").html(response);
		},
		error: function(){
			return false;	
		}
	}); 
	
	$(".dis").show();
//	$("#rack_number").html(sel);
	$(".chosen-select").chosen();
}
 
function get_pallet_box()
{
	var rack_val=$("#rack_name_dispatch").val();
	var arr = rack_val.split('=');
	var row = arr[0];
	var col = arr[1];
	var goods_master_id = arr[2];
	var order_status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getLabel_pallet_dis_stock', '',1);?>");
	$.ajax({
			url : order_status_url,
			method : 'post',
			data : {row:row,col:col,goods_master_id:goods_master_id},
			success: function(response){
				$("#rack_num").html(response);
		},
		error: function(){
			return false;	
		}
	}); 
	$(".dis").show();
//	$("#rack_num").html(sel);
	$(".chosen-select").chosen();
}



	

$(document).click(function(){
			$("#a_response").fadeOut('slow');
			$("#a_response").html("");
		});
	   	$("#added_by").focus();
		var offset = $("#added_by").offset();
		var width = $("#holder").width();
		$("#a_response").css("width",width);
		
		$("#added_by").keyup(function(event){
		 var keyword = $("#added_by").val();
		 //alert(keyword);
		 
		 if(keyword.length)
		 {	
		 	
			 if(event.keyCode != 40 && event.keyCode != 38 )
			 {		
				 var url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=user_list', '',1);?>");
				 $("#loading").css("visibility","visible");
				 $.ajax({
				   type: "POST",
				   url: url,
				   data: "user="+keyword,
				   success: function(msg){	
					//console.log(msg);
				   var msg = $.parseJSON(msg);
				   var div='<ul class="list">';
				   
					if(msg.length>0)
					{
						for(var i=0;i<msg.length;i++)
						{	
							div =div+'<li><a href=\'javascript:void(0);\' user_id="'+msg[i].user_id+'" user_type_id="'+msg[i].user_type_id+'"><span class="bold" >'+msg[i].user_name+'</span></a></li>';			
							
						}
					}
					//alert(div);
					div=div+'</ul>';
					if(msg != 0)
					  $("#a_response").fadeIn("slow").html(div);
					else
					{
					  $("#a_response").fadeIn("slow");	
					  $("#a_response").html('<div style="text-align:left;">No Matches Found</div>');
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
							$("#added_by").val($(".list li[class='selected'] a").text());
                  			$("#added_user_id").val($(".list li[class='selected'] a").attr("user_id"));
							$("#user_type_id").val($(".list li[class='selected'] a").attr("user_type_id"));
						
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
							$("#added_by").val($(".list li[class='selected'] a").text());
							$("#added_user_id").val($(".list li[class='selected'] a").attr("user_id"));
							$("#user_type_id").val($(".list li[class='selected'] a").attr("user_type_id"));
						}
				 }
				 break;				 
				}
			 }
		 }
		 else
		 {	
			$("#a_response").fadeOut('slow');
			$("#a_response").html("");

		 }
	});
	$('#added_by').keydown( function(e) {
    if (e.keyCode == 9) {
		 $("#a_response").fadeOut('slow');
		 $("#a_response").html("");
    }
	
});
	$("#a_response").mouseover(function(){
			$(this).find(".list li a:first-child").mouseover(function () {
                  $("#added_user_id").val($(this).attr("user_id"));
				  $("#user_type_id").val($(this).attr("user_type_id"));
				  $(this).addClass("selected");
			});
			$(this).find(".list li a:first-child").mouseout(function () {
				  $(this).removeClass("selected");
			});
			$(this).find(".list li a:first-child").click(function () {
				  $("#added_user_id").val($(this).attr("user_id"));
			
				  $("#user_type_id").val($(this).attr("user_type_id"));
				  $("#added_by").val($(this).text());
				  $("#a_response").fadeOut('slow');
				  $("#a_response").html("");
				});
			
		});
	// added for the stock product add	
	$(document).click(function(){
			$("#ajax_response1").fadeOut('slow');
			$("#ajax_response1").html("");
		});
	   	$("#keyword1").focus();
		var offset = $("#keyword1").offset();
		var width = $("#holder").width();
		$("#ajax_response1").css("width",width);
		
		$("#keyword1").keyup(function(event){
		 var keyword = $("#keyword1").val();
		 //alert(keyword);
		 
		 if(keyword.length)
		 {	
		 	$("#color_txt").hide();
			$("#product_name_st").show();
			 if(event.keyCode != 40 && event.keyCode != 38 )
			 {		
				 var product_code_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=product_code', '',1);?>");

				 $("#loading").css("visibility","visible");
				 $.ajax({
				   type: "POST",
				   url: product_code_url,
				   data: "product_code="+keyword,
				   success: function(msg){	
					//alert(msg);
				   var msg = $.parseJSON(msg);
				   var div='<ul class="list">';
				   
					if(msg.length>0)
					{
						for(var i=0;i<msg.length;i++)
						{	
							div =div+'<li><a href=\'javascript:void(0);\' discr="'+msg[i].description+'" product_id="'+msg[i].product+'" color="'+msg[i].color+'" size="'+msg[i].volume+'" mea="'+msg[i].measurement+'" id="'+msg[i].product_code_id+'"><span class="bold" >'+msg[i].product_code+'</span></a></li>';			
							
						}
					}
					
					div=div+'</ul>';
					if(msg != 0)
					  $("#ajax_response1").fadeIn("slow").html(div);
					else
					{
					  $("#ajax_response1").fadeIn("slow");	
					  $("#ajax_response1").html('<div style="text-align:left;">No Matches Found</div>');
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
							$("#keyword1").val($(".list li[class='selected'] a").text());
							$("#product_div").show();
                  			$("#product_name_st").val($(".list li[class='selected'] a").attr("discr"));
							$("#product_code_id_st").val($(".list li[class='selected'] a").attr("id"));
							$("#product_id").val($(".list li[class='selected'] a").attr("product_id"));
							
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
							$("#keyword1").val($(".list li[class='selected'] a").text());
							$("#product_div").show();
                  			$("#product_name_st").val($(".list li[class='selected'] a").attr("discr"));
							$("#product_code_id_st").val($(".list li[class='selected'] a").attr("id"));
							$("#product_id").val($(".list li[class='selected'] a").attr("product_id"));
							
						}
				 }
				 break;				 
				}
			 }
		 }
		 else
		 {	
			$("#ajax_response1").fadeOut('slow');
			$("#ajax_response1").html("");

		 }
	});
	$('#keyword1').keydown( function(e) {
    if (e.keyCode == 9) {
		 $("#ajax_response1").fadeOut('slow');
		 $("#ajax_response1").html("");
    }
	
});
	$("#ajax_response1").mouseover(function(){
			$(this).find(".list li a:first-child").mouseover(function () {
					$("#product_div").show();
                  $("#product_name_st").val($(this).attr("discr"));
			
				  
				   $("#product_code_id_st").val($(this).attr("id"));
				   $("#product_id").val($(this).attr("product_id"));
				  $(this).addClass("selected");
			});
			$(this).find(".list li a:first-child").mouseout(function () {
				  $(this).removeClass("selected");
			});
			$(this).find(".list li a:first-child").click(function () {
				  $("#product_div").show();
                  $("#product_name_st").val($(this).attr("discr"));
				  //$("#color_product").val($(this).attr("color"));
				 // $("#size").val($(this).attr("size"));
				 	$("#product_id").val($(this).attr("product_id"));
				  $("#product_code_id_st").val($(this).attr("id"));
				   //
				  $("#keyword1").val($(this).text());
				  $("#ajax_response1").fadeOut('slow');
				  $("#ajax_response1").html("");
				 getStockForproductcode($("#product_code_id_st").val());
				});
			
		});
			
	

function getStockForproductcode(product_code_id)
{
	
//	 alert(product_code_id);
		var data_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getrackdataforindia', '',1);?>");
		$.ajax({
			url : data_url,
			method : 'post',
			data : {product_code_id : product_code_id},
			success: function(response){
				//alert(response);
				$("#table_details_product").html(response);
			
				
			},
			error:function(){
			}	
		});
}	

function getStockinfo()
{
	 pallet_details_dis =$('#pallet_dispatch').val();
	 //alert(product_code_id);
		var data_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=rack_qty_details', '',1);?>");
		$.ajax({
			url : data_url,
			method : 'post',
			data : {pallet_details_dis : pallet_details_dis},
			success: function(response){
				//alert(response);
				$("#table_details").html(response);
				
			},
			error:function(){
			}	
		});
}	

function savedispatch()
{
	
	
	var rack_qty=$("#rack_qty").val();
	var rack_qty_arr = rack_qty.split('&');	
	var rack_qty_length = rack_qty_arr.length-1;
	
	var product_code_id =$("#product_code_id_dis").val();

	$("#product_code_id").val(product_code_id);
	var stock_group_id =$("#stock_group_id").val();

	$("#stock_id").val(stock_group_id);
	
	var pallet_sales=$("#pallet_dispatch").val();
	$('#alldata').val(pallet_sales);

	var arr = pallet_sales.split('=');

	var row = arr[0];
	var col = arr[1];
	var goods_master_id = arr[2];
	var pallet_no = arr[3];

	
			
	for(var i=0;i<=rack_qty_length;i++)
	{
		
		var box_new = rack_qty_arr[i].split('=');	
	
	
		if(box_new[1]==product_code_id)
		{
			
			$("#box_qty_new").val(box_new[0]);
		}
		
		
	}
		
	var rack_qty =parseInt($("#box_qty_new").val());
	var dispatch_qty = parseInt($("#dispatch_qty").val());

	if(dispatch_qty>rack_qty)
	{
		alert('Your Remaining Qty is '+rack_qty+'. Please Enter Proper Qty!! ');
	}
	
	else
	{
		if($("#form_dis").validationEngine('validate'))
		{	
			var label_url = getUrl("<?php  echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=savedispatch_racknotify', '',1);?>");
			var formData = $("#form_dis").serialize();
			$.ajax({
				type: "POST",
				url: label_url,
				data:{formData : formData}, 
				success: function(response) {
					
					set_alert_message('Successfully Dispatched',"alert-success","fa-check");
			    	window.setTimeout(function(){location.reload()},1000)
				
				
				}
			});
		}
	}
	
}

function savedispatch_racknotify_product()
{

		if($("#form_dis_product").validationEngine('validate'))
		{	
			var label_url = getUrl("<?php  echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=savedispatch_racknotify_product', '',1);?>");
			var formData = $("#form_dis_product").serialize();
			$.ajax({
				type: "POST",
				url: label_url,
				data:{formData : formData}, 
				success: function(response) {
					
					set_alert_message('Successfully Dispatched',"alert-success","fa-check");
			    	window.setTimeout(function(){location.reload()},1000)
				
				
				}
			});
		}
	
	

	
	
	
}
function check_qty()
{
	var qty_p=$("#qty_p").val();
	var pallet_sales=$("#pallet_sales").val();
	var rack_qty_arr = pallet_sales.split('==');

	//alert(rack_qty_arr[3]);
	if(parseInt(rack_qty_arr[3]) < parseInt(qty_p))
	{
		alert("Please Insert Less Than Rack  Qty");
		$("#qty_p").val('');
	}
	
}

</script> 

<?php  }else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>