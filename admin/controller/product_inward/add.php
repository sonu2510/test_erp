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

if(isset($_GET['product_inward_id']) && !empty($_GET['product_inward_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$product_inward_id = base64_decode($_GET['product_inward_id']);
		$pro_inward = $obj_product_inward->getProductItem($product_inward_id);
		//printr($pro_inward['user_id']);
		$edit = 1;
	}
	
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}
//Close : edit

if($display_status){
	//insert user
	if(isset($_POST['btn_save'])){
		$post = post($_POST);		
		$insert_id = $obj_product_inward->addProducts($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		$product_inward_id = $pro_inward['product_inward_id'];
		$obj_product_inward->updateProducts($product_inward_id,$post);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}

	$latest_no=0;
	$product_inward_id = $obj_product_inward->getlatestNo();
	if(!empty($product_inward_id))
		$latest_no=$product_inward_id;
	
	$strpad = str_pad($latest_no+1,8,'0',STR_PAD_LEFT)
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
     
      <div class="col-sm-8">
        <section class="panel">
          <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form_stock" id="form_stock" enctype="multipart/form-data">
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Inward No.</label>
                <div class="col-lg-5">
                	<input type="text" readonly="readonly" name="inward_no" id="inward_no" value="<?php echo isset($pro_inward['inward_no'])?$pro_inward['inward_no']:'INWD'.$strpad;?>" class="form-control validtae[required],custom[number]" />

                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Vendor Name</label>
                <div class="col-lg-3">
                     <?php $vendors = $obj_product_inward->getVendors(); ?>
                            
                            <select name="vendor_info_id" id="vendor_info_id" class="form-control validate[required]">
                            <option value="">Select Vendor</option>
                            
                            
                             <?php foreach($vendors as $vendor) { ?>
                        <?php if(isset($pro_inward) && $pro_inward['vender_id']==$vendor['vendor_info_id'])
				  		 {?>
                                <option value="<?php echo $vendor['vendor_info_id']; ?>" selected="selected"><?php echo $vendor['vender_first_name'].' '.$vendor['vender_last_name']; ?></option>
                        <?php } else { ?>
                       			 <option value="<?php echo $vendor['vendor_info_id']; ?>"> <?php echo $vendor['vender_first_name'].' '.$vendor['vender_last_name']; ?></option>
				   <?php } }?>
                            </select>
                    
                </div>
                <label class="col-lg-3 control-label"><span class="required">*</span>In</label>
                <div class="col-lg-3">
                	<input type="text" name="inward_date" id="date" value="<?php echo isset($pro_inward['inward_date'])?$pro_inward['inward_date']:date('Y-m-d');?>" class="form-control validtae[required],custom[number]" />

                </div>

                
              </div>
              
			  
              <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span>Product Category</label>
                        <div class="col-lg-3">
                            <?php $products = $obj_product_inward->getActiveProductCategory(); 
									//printr($products);?>
                            
                            <select name="product_category_id" id="product_category_id" class="form-control validate[required]">
                            <option value="">Select Category</option>
                            
                            
                             <?php foreach($products as $product) { ?>
                        <?php if(isset($pro_inward) && $pro_inward['product_category_id']==$product['product_category_id'])
				  		 {?>
                                <option value="<?php echo $product['product_category_id']; ?>" selected="selected"><?php echo $product['product_category_name']; ?></option>
                        <?php } else { ?>
                       			 <option value="<?php echo $product['product_category_id']; ?>"> <?php echo $product['product_category_name']; ?></option>
				   <?php } }?>
                            </select>
                        </div>
						
						
				<label class="col-lg-3 control-label"><span class="required">*</span>Manufacturing Date</label>
                <div class="col-lg-3">
                	<input type="text" name="manufacutring_date" id="date1" value="<?php echo isset($pro_inward['manufacutring_date'])?$pro_inward['manufacutring_date']:date('Y-m-d');?>" class="form-control validtae[required],custom[number]" />

                </div>
                </div>
                              
                <div class="form-group">
                     <label class="col-lg-3 control-label"><span class="required">*</span>Product Name</label>
                        <div class="col-lg-5">
                        	<?php 
							if(isset($pro_inward['product_item_id']))
								$pro_item_id =$obj_product_inward->getProductItemInfo($pro_inward['product_item_id']);
								//printr($pro_item_id);  ?>
                       			<input type="text" name="product_item_name" id="product_item_name" value="<?php echo isset($pro_inward['product_item_id']) ? $pro_item_id['product_name'] : '' ; ?>" class="form-control  validate[required]" />
                                    <input type="hidden" name="product_item_id" id="product_item_id" value="<?php echo isset($pro_inward['product_item_id']) ? $pro_inward['product_item_id'] : '' ; ?>" class="form-control  validate[required]" />
							<div id="ajax_response"></div>
                        </div>
				</div>       
               <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Roll No</label>
					<div class="col-lg-4">
						<input type="text" name="roll_no" id="roll_no" value="<?php echo isset($pro_inward['roll_no']) ? $pro_inward['roll_no'] : '' ; ?>" class="form-control  validate[required]"/>

					</div>
              </div>

				<div class="form-group">
                <label class="col-lg-3 control-label">Size</label>
                <div class="col-lg-4">
                	<input type="text" name="inward_size" id="inward_size" value="<?php echo isset($pro_inward['inward_size']) ? $pro_inward['inward_size'] : '' ; ?>" class="form-control " />

                </div>
              </div>
              
             	 
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Qty</label>
                <div class="col-lg-3">
                  	<input type="text" name="qty" id="qty" value="<?php echo isset($pro_inward['qty']) ? $pro_inward['qty'] : '' ; ?>" class="form-control  validate[required]"/>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Unit</label>
                <div class="col-lg-3">
                	
						<?php $units = $obj_product_inward->getUnit(); 
                                        //printr($products);?>
                                
                                <select name="unit_id" id="unit_id" class="form-control validate[required]">
                                <option value="">Select Product</option>
                                
                                
                                 <?php foreach($units as $unit) { ?>
                            <?php if(isset($pro_inward) && $pro_inward['unit_id']==$unit['unit_id'])
                             {?>
                                    <option value="<?php echo $unit['unit_id']; ?>" selected="selected"><?php echo $unit['unit']; ?></option>
                            <?php } else { ?>
                                     <option value="<?php echo $unit['unit_id']; ?>"> <?php echo $unit['unit']; ?></option>
                       <?php } }?>
                                </select>
                </div>

                 <label class="col-lg-3 control-label">Secondary Unit</label>
                <div class="col-lg-3">
                	
						<?php //$units = $obj_product_inward->getUnit(); 
                                        //printr($products);?>
                                
                                <select name="sec_unit_id" id="sec_unit_id" class="form-control ">
                                <option value="">Select Product</option>
                                
                                
                                 <?php foreach($units as $unit) { ?>
                            <?php if(isset($pro_inward) && $pro_inward['sec_unit_id']==$unit['unit_id'])
                             {?>
                                    <option value="<?php echo $unit['unit_id']; ?>" selected="selected"><?php echo $unit['unit']; ?></option>
                            <?php } else { ?>
                                     <option value="<?php echo $unit['unit_id']; ?>"> <?php echo $unit['unit']; ?></option>
                       <?php } }?>
                                </select>
                </div>
                
              </div>
           
              <div class="form-group">
                     <label class="col-lg-3 control-label"><span class="required">*</span>Name Of Storekeeper</label>
                      <div class="col-lg-3">
                            <?php $users = $obj_product_inward->getUserDetail(); 
									//printr($users);?>
                            
                            <select name="user_id" id="user_id" class="form-control validate[required]">
                            <option value="">Select User</option>
                            
                            
                             <?php foreach($users as $user) { ?>
								
								<?php  //printr($pro_inward);	
							
								if( isset($pro_inward) && $pro_inward['user_id']==$user['employee_id'])
								 {
									
									 ?>
								
										<option value="<?php echo $user['employee_id']; ?>" selected="selected"><?php echo $user['first_name'].' '.$user['last_name']; ?></option>
								<?php } else { ?>
										 <option value="<?php echo $user['employee_id']; ?>"> <?php  echo $user['first_name'].' '.$user['last_name']; ?></option>
						   <?php } }?>
                            </select>
                     </div>
             </div>
  
             
              <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                <?php if($edit){?>
                  	<button type="submit" name="btn_update" id="btn_update" class="btn btn-primary">Update </button>
                <?php } else { ?>
                	<button type="submit" name="btn_save" id="btn_save" class="btn btn-primary">Save </button>	
                <?php } ?>  
                  <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a>
                </div>
              </div>
            </form>
          </div>
        </section>
        
      </div>
    </div>
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

#ajax_response, #ajax_res,#ajax_return{
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
</style>
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

<script>

jQuery(document).ready(function(){
	   jQuery("#form_stock").validationEngine();
	   
	    $("#date").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
		$("#date1").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});

		var checkin = $('#date').datepicker({
   			onRender: function(date) {
    		return date.valueOf() < now.valueOf() ? '' : '';
    		}
    	}).on('changeDate', function(ev) {
			if (ev.date.valueOf() <= checkout.date.valueOf()) {
				var newDate = new Date(ev.date);
          		newDate.setDate(newDate.getDate());
    			checkout.setValue(newDate);
    		}
    		checkin.hide();
    		$('#date')[0].focus();
    	}).data('datepicker');
	
});	
$("#product_item_name").focus();
	var offset = $("#product_item_id").offset();
	var width = $("#holder").width();
	$("#ajax_response").css("width",width);
	
	$("#product_item_name").keyup(function(event){		
		 var keyword = $("#product_item_name").val();
		// alert(keyword);
		 if(keyword.length)
		 {	//[kinjal] : changed 13-4-2017 
			 if(event.keyCode != 40 && event.keyCode != 38)
			 {
				 var product_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=product_item_detail', '',1);?>");
				 $("#loading").css("visibility","visible");
				 $.ajax({
				   type: "POST",
				   url: product_url,
				   data: "product_item="+keyword,
				   success: function(msg){	
				 var msg = $.parseJSON(msg);
				
				 //console.log(msg);
				   var div='<ul class="list">';
				   
					if(msg.length>0)
					{ 	//alert(msg[1].address);
						for(var i=0;i<msg.length;i++)
						{	
							div =div+'<li><a href=\'javascript:void(0);\' id="'+msg[i].product_item_id+'" product_name="'+msg[i].product_name+'" ><span class="bold" >'+msg[i].product_name+'</span></a></li>';
						}
					}
					
					div=div+'</ul>';
				
					if(msg != 0)
					  $("#ajax_response").fadeIn("slow").html(div);
					else
					{
						$("#ajax_response").fadeIn("slow");	
						$("#ajax_response").html('<div style="text-align:left;">No Matches Found</div>');
						$("#product_item_id").val('');
						
					
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
							$("#product_item_name").val($(".list li[class='selected'] a").text());
							$("#product_item_id").val($(".list li[class='selected'] a").attr("id"));
							
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
							$("#product_item_name").val($(".list li[class='selected'] a").text());
                           
						   	$("#product_item_id").val($(".list li[class='selected'] a").attr("id"));
							
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
	
	$('#product_item_name').keydown( function(e) {
		if (e.keyCode == 9) {
			 $("#ajax_response").fadeOut('slow');
			 $("#ajax_response").html("");
		}
	});

	$("#ajax_response").mouseover(function(){
				$(this).find(".list li a:first-child").mouseover(function () {
					 $("#product_item_id").val($(this).attr("id"));
					  $(this).addClass("selected");
				});
				$(this).find(".list li a:first-child").mouseout(function () {
					  $(this).removeClass("selected");
					  $("#product_item_id").val('');
				});
				$(this).find(".list li a:first-child").click(function () {
					  	  
					  $("#product_item_id").val($(this).attr("id"));
					  
					  $("#product_item_name").val($(this).text());
					 $("#ajax_response").fadeOut('slow');
					  $("#ajax_response").html("");
					  
					
				});
				
			});	
	
</script>


<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>
