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


//Start : edit
$edit = '';
/*if(isset($_GET['rack_master_id']) && !empty($_GET['rack_master_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$rack_master_id = base64_decode($_GET['rack_master_id']);
		$rack_data = $obj_domestic_stock->getRackData($rack_master_id);
		$edit = 1;
	}
	
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}*/
//Close : edit

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


$limit = LISTING_LIMIT;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}

$filter_data=array();
$filter_value='';

$class = 'collapse';

$title='Entry';
if(isset($_GET['status']) && $_GET['status']==1)
{
	$title='Dispatch';
}

if($display_status) {
    
    $user_type_id = $_SESSION['LOGIN_USER_TYPE'];
	$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
	
	$status=isset($_GET['status'])?$_GET['status']:'0';

	if(isset($_POST['btn_save'])){
		$post = post($_POST);		
	//	printr($post);	die;
		$insert_id = $obj_domestic_stock->addstock($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '&mod=add', '',1));
	}
	/*if(isset($_POST['btn_dis'])){
		$post = post($_POST);		
 
	    $check_number = $obj_domestic_stock->check_invoice($post);
    	if(empty($check_number))
    	{
    	    $obj_session->data['warning'] = WARNING;
    	    //printr($post);
    	}
		else
		{
    		$insert_id = $obj_domestic_stock->dispatchstock($post);
    		$obj_session->data['success'] = ADD;
    		page_redirect($obj_general->link($rout, '&mod=add&status=1', '',1));
		}
	}*/
//-----------------------------------------------------------------------------------------------------------------------------------------------------------------------Mode setting for the dispatch Ends here
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
          <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail 
          <?php if($user_id=='1' && $user_type_id=='1'){?>
           <span class="pull-right">
              <a class="label bg-inverse" href="<?php echo $obj_general->link($rout, 'mod=import', '',1);?>" > <i class="fa fa-print"></i> CSV Import</a>
             </span>
             <?php }?>
             </header>
			<div class="panel-body">
            <form class="form-horizontal" method="post" name="form2" id="form2" enctype="multipart/form-data">                 
				<section class="panel">   
				   <div class="panel-body">
				   <div class="tab-content">
					  <!-- addstock   start  -->
					  <div id="addstock" class="tab-pane active">
						 <section class="panel">
							<header class="panel-heading bg-white"> <?php echo $display_name;?> <?php echo $title;?></header>
							<div class="panel-body">
							   <div class="form-group option">
									 <label class="col-lg-3 control-label"><span class="required">*</span>Product Code</label>
									 <div class="col-lg-4" id="holder">
										<?php $product_codes=$obj_domestic_stock->getActiveProductCode(); 
										   //printr($product_codes);
										   ?>
										<input type="hidden" id="product_code_id_add" name="product_code_id_add" value="">
										<input type="text" id="keyword" class="form-control validate[required]"  autocomplete="off" value=""> 
										<input type="hidden" name="product_id" id="product_id" value="" />
										<div id="ajax_response"></div>
									 </div>
									 <div class="col-lg-3" id="product_div"> 
										<input type="text" name="product_name" id="product_name"  value="<?php echo isset($_GET['proforma_in_id'])?$product_code['description']:'';?>" disabled="disabled" class="form-control validate" style="width:400px"/>
									 </div>
								  </div>
								  <div id="table_details_product"></div>
								  <?php if($status==0) { ?>
									  <div class="form-group">
										 <label class="col-lg-3 control-label"><span class="required">*</span>Box Number</label>
											<div class="col-lg-4">
												<input type="text" name="box_no" id="box_no" value="<?php echo isset($rack_data['box_no'])?$rack_data['box_no']:'';?>" class="form-control validate[required,custom[number]]" placeholder="Box Number">
										 </div>
									  </div>
								  <?php } ?>
								  <div class="form-group">
									 <label class="col-lg-3 control-label"><span class="required">*</span>Quantity</label>
									 <div class="col-lg-4">
										<input type="number" name="qty" id="qty" value="<?php echo isset($rack_data['column_no'])?$rack_data['column_no']:'';?>" class="form-control validate[required,custom[number]]" placeholder="Quantity">
									 </div>
								  </div>
								  <?php if($status==1) { ?>
									  <div class="form-group">
										 <label class="col-lg-3 control-label"><span class="required">*</span>Invoice No.<br><small style="color:red;">Proforma invoice no.(For domestic)<br>STK Order no.(For Export)</small></label>
											<div class="col-lg-4">
												<input type="text" name="invoice_no" id="invoice_no" value="<?php echo isset($rack_data['invoice_no'])?$rack_data['invoice_no']:'';?>" class="form-control validate[required]" placeholder="Proforma Invoice / STK Order No.">
										 </div>
									  </div>
								  <?php } ?>
								  <div class="form-group">
									 <label class="col-lg-3 control-label"><span class="required">*</span>Date</label>
									 <div class="col-lg-4">
										<input type="text" name="date" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d', strtotime(' -1 day')) //date("Y-m-d");?>" placeholder="Add Date" id="input_date" class="input-sm form-control datepicker validate[required]" />
									 </div>
								  </div>
								  
								  
								  <div class="form-group">
									 <div class="col-lg-9 col-lg-offset-3">
										<?php if(isset($_GET['status']) && $_GET['status']==1) { ?>
													<button type="button" name="btn_dis" id="btn_dis" class="btn btn-primary">Dispatch </button>
										<?php }
											  else
											  {?>
													<button type="submit" name="btn_save" id="btn_save" class="btn btn-primary">Add Stock </button>
										<?php } ?>
									 </div>
								   </div>		
							   
							</div>   
						 </section>
					  </div>
					 <!-- addstock   End -->        
				  </div>
			   </div>
			</section>			
            </form> 
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
            //jQuery("#form1").validationEngine(); 
            jQuery("#form2").validationEngine(); 
	$("#input_date").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
 	$("#dis_date").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
//set_alert_message('Only .pdf And .jpg Formate Allow','alert-warning','fa fa-warning');
});
            
$(document).click(function(){
	$("#ajax_response").fadeOut('slow');
	$("#ajax_response").html("");
});
$("#keyword").focus();
var offset = $("#keyword").offset();
var width = $("#holder").width();
$("#ajax_response").css("width",width);
	 var currentRequest = null;	
$("#keyword").keyup(function(event){
 var keyword = $("#keyword").val();
            
    if(keyword.length)
     {	
     	$("#color_txt").hide();
    	$("#product_name").show();
    	 if(event.keyCode != 40 && event.keyCode != 38 )
    	 {		
    		 var product_code_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=product_code', '',1);?>");
    		 $("#loading").css("visibility","visible");
    		 currentRequest = $.ajax({
    		   type: "POST",
    		   url: product_code_url,
    		   data: "product_code="+keyword,
    		   beforeSend : function()    {           
                    if(currentRequest != null) {
                        currentRequest.abort();
                    }
                },
    		   success: function(msg){	
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
		  <?php if(isset($_GET['status']) && $_GET['status']==1) { ?>
				getStockForproductcode($(this).attr("id"));
		  <?php } ?>
		});
		
});
		
function getStockForproductcode(product_code_id)
{
        var data_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getrackdataforindia', '',1);?>");
		$.ajax({
			url : data_url,
			method : 'post',
			data : {product_code_id : product_code_id},
			success: function(response){
				$("#table_details_product").html(response);
			},
			error:function(){
			}	
		});
}
function getboxValue()
{
	var arr = jQuery.parseJSON($('#box_qty_array').val());
	var box_no = $("#box_no option:selected").val();
	$("#qty").attr({"max" : arr[box_no],"min" : 1});
}
$("#btn_dis").click(function(){
    if($("#form2").validationEngine('validate')){
        var formData = $("#form2").serialize();
        var url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=check_invoice', '',1);?>");
        $.ajax({
			url : url,
			method : 'post',
			data : {formData : formData},
			success: function(response){
				/*console.log(response);
				if(response=='')
				{
				    set_alert_message('Sorry!!! This item is not available in Invoice or Stock Order.','alert-danger','fa fa-warning');
				    $("#invoice_no").val('');
				}
				else*/
				    dispatchstock();
			},
			error:function(){
			}	
		});
    }
});
function dispatchstock()
{
    if($("#form2").validationEngine('validate'))
    {   var formData = $("#form2").serialize();
        var url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=dispatchstock', '',1);?>");
        $.ajax({
			url : url,
			method : 'post',
			data : {formData : formData},
			success: function(response){
				set_alert_message('Record has been added successfully.','alert-success','fa-check');
				location.reload();
			},
			error:function(){
			}	
		});
    }
}
</script> 

<?php  }else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>