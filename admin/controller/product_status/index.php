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
	'text' 	=> $display_name.' List ',
	'href' 	=> '',
	'icon' 	=> 'fa-list',
	'class'	=> 'active',
);

if(!$obj_general->hasPermission('view',$menuId)){
	$display_status = false;
}

if(isset($_GET['sort'])){
	$sort_name = $_GET['sort'];	
}else{
	$sort_name = 'from_quantity';
}

if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'ASC';	
}
if(isset($_GET['proforma_in_id']) && !empty($_GET['proforma_in_id'])){
	$proforma_in_id = decode($_GET['proforma_in_id']);
	//printr($proforma_in_id);
    $invoice_detail = $obj_productstatus-> getSingleInvoice($proforma_invoice_id);
	//printr($invoice_detail);
}
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

if(isset($_POST['btn_filter'])){
	
	$filter_edit = 1;
	$class ='';	
	if(isset($_POST['filter_row'])){
		$filter_row=$_POST['filter_row'];		
	}else{
		$filter_row='';
	}
	
	if(isset($_POST['filter_column'])){
		$filter_column=$_POST['filter_column'];		
	}else{
		$filter_column='';
	}
	
	if(isset($_POST['filter_status'])){
		$filter_status=$_POST['filter_status'];
	}else{
		$filter_status='';
	}
		
	$filter_data=array(
		'column_name' => $filter_column,
		'row' => $filter_row,
		'status' => $filter_status
	);
	
	//$obj_session->data['filter_data'] = $filter_data;
}

if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'DESC';	
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
    <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading bg-white"> <b><span><?php echo $display_name;?></span></b>
      			</header>
              	<!--<div class="panel-body">
                        	<label class="col-lg-3 control-label">Product Name</label>
                        	<div class="col-lg-3">
								<?php
                                //$products = $obj_productstatus->getProduct();
                                ?>
                                <select name="product" id="product" class="form-control validate" onchange="color()">
                                <option value="">Select Product</option>
                                    <?php
                                    
                                    /*foreach($products as $product){
                                        if(isset($post['product']) && $post['product'] == $product['product_id']){
                                            echo '<option value="'.$product['product_id'].'" selected="selected" >'.$product['product_name'].'</option>';
                                        }else{
                                            echo '<option value="'.$product['product_id'].'">'.$product['product_name'].'</option>';
                                        }
                                    }*/ ?>
                                </select>
                        	</div>
                     </div>
                  <div class="panel-body">
                        <label class="col-lg-3 control-label">Volume</label>
                        <div class="col-lg-3">
                            <input type="text" name="volume" value="" id="volume" class="form-control" onchange="color()"/>
                        </div>
                  </div>
                
                
              	<div class="panel-body">
              	<label class="col-lg-3 control-label">Colour</label>
                        	<div class="col-lg-3">
                             <?php //$colors = $obj_productstatus->getColor();?>
                            <select name="color" id="color" class="form-control validate" onchange="color()">
                                    <option value="">Select Color</option>
                                     <?php //foreach($colors as $colors2){ ?>
										<option value="<?php //echo $colors2['pouch_color_id']; ?>" id="option"
                                        <?php //if($clr['color'] == $colors2['pouch_color_id']) { echo 'selected="selected"'; } ?>> 
										<?php // echo $colors2['color']; ?></option>
                                        <?php //} ?>  
                                                                           
                                  </select>
                                  </div>
                           </div>-->
                           
              		<div class="panel-body">
                        <label class="col-lg-3 control-label">Product Code</label>
                        <div class="col-lg-3" id="holder">
                        	<?php $product_codes=$obj_productstatus->getActiveProductCode(); 
									//printr($product_codes);
									
									if(isset($proforma_in_id)) { 
										//$inv_product = $obj_invoice->getInvoiceProductId($invoice_no,$invoice_product_id); 
										//printr($color);
										$product_code= $obj_productstatus->getProductCode($invoice_detail['product_code_id']);
										//printr($product_code); 
									}?>
                                     <input type="hidden" id="product_code_id" name="product_code_id" value="<?php if(isset($_GET['proforma_in_id']) && ($invoice_detail['product_code_id'] != '-1' && $invoice_detail['product_code_id'] != '0')){ echo $product_code['product_code_id'];} else if(isset($invoice_detail) && $invoice_detail['product_code_id'] == '-1') { echo '-1'; } else { echo '0';} ?>">
                               <input type="text" id="keyword" class="form-control validate[required]"  autocomplete="off" value="<?php  if(isset($_GET['proforma_in_id']) && ($invoice_detail['product_code_id'] == '0')){ echo 'Cylinder';} else if( isset($invoice_detail) && $invoice_detail['product_code_id'] == '-1') { echo 'Custom'; } else {  echo isset($product_code) ? $product_code['product_code'] : ''; } ?>">
                             
                               <div id="ajax_response"></div>
                           </div>
                        <div class="col-lg-3" id="product_div"> 
                               <input type="text" name="product_name" id="product_name"  value="<?php echo isset($_GET['proforma_in_id'])?$product_code['description']:'';?>" disabled="disabled" class="form-control validate" style="width:400px"/>
             	 	 	</div>
                </div>
    		  <div class="panel-body">
                        
                       <div class="col-lg-9 col-lg-offset-3">
                            <button type="button"  name="btn_save" id="btn_save" class="btn btn-primary" onclick="getstatus();">Status</button> 
                        </div>
                  </div>
                  <div class="panel-body" id="rackstatus"></div>
                  <div class="panel-body" id="order"></div>
                  <div class="panel-body" id="stock"></div>
                              </div>
                           </div>
                        </div>
                      </div>
                 </section>
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
	color:#575757;
	cursor : default;
}
</style>
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>ckeditor3/ckeditor.js"></script>
<style type="text/css">
@media (max-width: 400px) {
  .chunk {
    width: 100% !important;
  }
}
</style>
<script type="text/javascript">
$(document).ready(function(){

    
});

function color()
	{    
		var product=$("#product").val();
		var volume = $("#volume").val();
		var color = $("#color").val();
		var product_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=product_name', '',1);?>");
		 $.ajax({
				   type: "POST",
				   url: product_url,
				   data: {product_name:product,volume:volume,color:color},
				   success: function(msg){
				 // alert(msg);
				   var msg = $.parseJSON(msg);
				  
				   var div='<ul class="list">';
				   
					if(msg.length>0)
					{
						for(var i=0;i<msg.length;i++)
						{	
							div =div+'<li><a href=\'javascript:void(0);\' discr="'+msg[i].description+'" size="'+msg[i].volume+'" mea="'+msg[i].measurement+'" color="'+msg[i].color+'" product_id="'+msg[i].product+'" product_name="'+msg[i].product_name+'"  id="'+msg[i].product_code_id+'"><span class="bold" >'+msg[i].product_code+'</span></a></li>';			
	
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
		 if(keyword == 'Cylinder' || keyword == 'cylinder' || keyword == 'CYLINDER')
		 {	
				$("#product_name").hide();
				$("#product_code_id").val('0');
				$("#color_product").val('Cylinder');
				$("#product_id").val('');
				$("#real_product_name").val('Cylinder');
				$('#size').removeAttr("readonly", "readonly");
				$('#measurement').removeAttr("readonly", "readonly");
		 }
		 else if(keyword == 'Custom' || keyword == 'custom' || keyword == 'CUSTOM')
		 {	
				$("#product_name").hide();
				$("#product_code_id").val('-1');
				$("#color_txt").show();
				$("#color_product").val('Custom');
				$("#product_id").val('');
				$("#real_product_name").val('Custom');
				$('#size').removeAttr("readonly", "readonly");
				$('#measurement').removeAttr("readonly", "readonly");
		 }
		 else if(keyword.length)
		 {	
		 	$("#size").attr("readonly","readonly");
			$("#measurement").attr("readonly",true);
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
				   var msg = $.parseJSON(msg);
				   var div='<ul class="list">';
				   //alert(msg);
					if(msg.length>0)
					{
						for(var i=0;i<msg.length;i++)
						{	
							div =div+'<li><a href=\'javascript:void(0);\' discr="'+msg[i].description+'" size="'+msg[i].volume+'" mea="'+msg[i].measurement+'" color="'+msg[i].color+'" product_id="'+msg[i].product+'" product_name="'+msg[i].product_name+'"  id="'+msg[i].product_code_id+'"><span class="bold" >'+msg[i].product_code+'</span></a></li>';			

							/*$("#color_product").val(msg[i].color);
							$("#real_product_name").val(msg[i].product_name);
							$("#product_id").val(msg[i].product);
							$("#size").val(msg[i].volume);
							$("#measurement").val(msg[i].measurement);
							$("#product_name").val(msg[i].discr);
							$("#keyword").val(msg[i].product_code);
							$("#product_code_id").val(msg[i].product_code_id);*/
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
							$("#color_product").val($(".list li[class='selected'] a").attr("color"));
							$("#real_product_name").val($(".list li[class='selected'] a").attr("product_name"));
							$("#product_id").val($(".list li[class='selected'] a").attr("product_id"));
							$("#size").val($(".list li[class='selected'] a").attr("size"));
							$("#measurement").val($(".list li[class='selected'] a").attr("mea"));
							$("#product_code_id").val($(".list li[class='selected'] a").attr("id"));
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
							$("#color_product").val($(".list li[class='selected'] a").attr("color"));
							$("#real_product_name").val($(".list li[class='selected'] a").attr("product_name"));
							$("#product_id").val($(".list li[class='selected'] a").attr("product_id"));
							$("#size").val($(".list li[class='selected'] a").attr("size"));
							$("#measurement").val($(".list li[class='selected'] a").attr("mea"));
							$("#product_code_id").val($(".list li[class='selected'] a").attr("id"));
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
				  $("#color_product").val($(this).attr("color"));
				$("#real_product_name").val($(this).attr("product_name"));
				  $("#product_id").val($(this).attr("product_id"));
				$("#size").val($(this).attr("size"));
				  $("#measurement").val($(this).attr("mea"));
				  $(this).addClass("selected");
			});
			$(this).find(".list li a:first-child").mouseout(function () {
				  $(this).removeClass("selected");
			});
			$(this).find(".list li a:first-child").click(function () {
				
				  $("#product_div").show();
                  $("#product_name").val($(this).attr("discr"));
				  $("#color_product").val($(this).attr("color"));
				 $("#real_product_name").val($(this).attr("product_name"));
				$("#product_id").val($(this).attr("product_id"));
				 $("#size").val($(this).attr("size"));
				  $("#measurement").val($(this).attr("mea"));
				  $("#product_code_id").val($(this).attr("id"));
				  $("#keyword").val($(this).text());
				  $("#ajax_response").fadeOut('slow');
				  $("#ajax_response").html("");
					
				
				});
			
		});
	function getstatus()
	{
		//alert();
		var product_code_id=$("#product_code_id").val();
	    //alert(product_code_id);
		var product_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=productstatus_id', '',1);?>");
		 $.ajax({
				   type: "POST",
				   url: product_url,
				   data: {product_code_id:product_code_id},
				   success: function(response){
					 	//alert(response);
						
							  	// console.log(response);
								 var res = $.parseJSON (response);
							
								 $("#rackstatus").html(res['rack']);
								 $("#stock").html(res['stock']);
								 $("#order").html(res['order']);
							   //  alert(res);
							     //console.log(res);
				   }
				   });
		  
		 
	   	
		 
		 
		

	}
	
	
	</script> 

							