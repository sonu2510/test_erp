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
if(isset($_GET['request_id']) && !empty($_GET['request_id'])){
	//if(!$obj_general->hasPermission('edit',$menuId)){
		//$display_status = false;
	//}else{
	$splitdata=array();
		$request_id = base64_decode($_GET['request_id']);
		$request= $obj_sample->getRequest($request_id);
	    $splitdata=explode("=",$request['requester']);
		$edit = 1;
    	
			//	printr($splitdata);
	//}
	
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}
//Close : edit

if($display_status){
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		//printr($post);die;
		$request_id = $request['request_id'];
		$obj_sample->updateRequest($request_id,$post);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
	$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
	$addedByInfo = $obj_sample->getUser($user_id,$user_type_id);
	  $currency = $obj_sample->getCurrency(); 
    $pi = 'S';
	$new_no = $obj_sample->generateSampleNumber();
	$no = $pi.$new_no;
	 

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
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
             <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Sample NO </label>
                <div class="col-lg-8">
                  <input type="text" name="sample_no" id="sample_no" value="<?php echo isset($request['sample_no'])?$request['sample_no']:$no;?>" class="form-control validate[required]" readonly>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Company Name</label>
                <div class="col-lg-8">
                  <input type="text" name="company_nm" id="company_nm" value="<?php echo isset($request['company_nm'])?$request['company_nm']:'';?>" class="form-control validate[required]">
                    <input type="hidden" name="address_book_id"  value="<?php echo isset($proforma_inv) ? $proforma_inv['address_book_id'] : '' ; ?>" id="address_book_id" class="form-control " />
                    <input type="hidden" name="company_address_id"  value="" id="company_address_id" class="form-control " />
					<input type="hidden" name="add_email"  value="<?php echo $addedByInfo['email'];?>" id="add_email" class="form-control " />
					 <div id="ajax_return"></div>
                </div>
              </div>
              
            
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Contact Person Name</label>
                <div class="col-lg-8">
                  <input type="text" name="contact_nm" id="contact_nm" value="<?php echo isset($request['contact_nm'])?$request['contact_nm']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Phone / Mo. No </label>
                <div class="col-lg-8">
                  <input type="text" name="phone_no" id="phone_no" value="<?php echo isset($request['phone_no'])?$request['phone_no']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Phone / Mo. No (1)</label>
                <div class="col-lg-8">
                  <input type="text" name="phone_no1" id="phone_no1" value="<?php echo isset($request['phone_no1'])?$request['phone_no1']:'';?>" class="form-control">
                </div>
              </div>
			  <div class="form-group">
                <label class="col-lg-3 control-label">Phone / Mo. No (2)</label>
                <div class="col-lg-8">
                  <input type="text" name="phone_no2" id="phone_no2" value="<?php echo isset($request['phone_no2'])?$request['phone_no2']:'';?>" class="form-control">
                </div>
              </div>
               <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Email Id (1)</label>
                <div class="col-lg-4">
                  	<input type="email" name="email_1" id="email" value="<?php echo isset($request['email_1'])?$request['email_1']:'';?>" class="form-control validate[required,custom[email]]">
                </div>
              </div>
              
			  <div class="form-group">
                <label class="col-lg-3 control-label">Email Id (2)</label>
                <div class="col-lg-4">
                  	<input type="email" name="email_2" value="<?php echo isset($request['email_2'])?$request['email_2']:'';?>" class="form-control validate[custom[email]]">
                </div>
              </div>
			  
			  <div class="form-group">
                <label class="col-lg-3 control-label">Email Id (3)</label>
                <div class="col-lg-4">
                  	<input type="email" name="email_3" value="<?php echo isset($request['email_3'])?$request['email_3']:'';?>" class="form-control validate[custom[email]]">
                </div>
              </div>
			   <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Address </label>
                <div class="col-lg-8">
                  	<textarea name="address" id="address" class="form-control validate[required]"><?php echo isset($request['address'])?$request['address']:'';?></textarea>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> City </label>
                <div class="col-lg-3">
                  	<input type="text" name="city" id="city" value="<?php echo isset($request['city'])?$request['city']:'';?>" class="form-control validate[required]">
                </div>
              </div>
			  			  
			  <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Country </label>
                <div class="col-lg-3" >               
				<?php //echo $addedByInfo['country_id'];
					if(isset($addedByInfo['country_id']) && $addedByInfo['country_id']){
								$selCountry = $addedByInfo['country_id'];
					}
					$sel_country = (isset($request['country']))?$request['country']:$addedByInfo['country_id']; 	
					//printr($sel_country);
					$countrys = $obj_general->getCountryCombo($sel_country);
					//printr($countrys);die;
					echo $countrys;
					//die;                   
					?>
					</div>
					     <div class="form-group">
                    <label class="col-lg-2 control-label"><span class="required">*</span> Currency</label>
                        <div class="col-lg-3">
                        	
                             
                            <select name="currency_id" id="currency_id" class="form-control validate[required]" >
                               
                                <?php 
								foreach($currency as $curr){
								if( $curr['currency_id'] == '1' )
									{ ?>
                                    	<option value="<?php echo $curr['currency_id']; ?>" <?php if(isset($request_id) && $curr['currency_id'] == $request['currency_id']) { ?> selected="selected" <?php } ?>><?php echo $curr['currency_code']; ?></option>
                                <?php }
							
								else{
									 
									 ?>
                                        <option value="<?php echo $curr['currency_id']; ?>"
                                        <?php 
										if(isset($request_id) && ($curr['currency_id'] == $request['currency_id'])) { ?> selected="selected" <?php }?>
                                         ><?php echo $curr['currency_code']; ?></option>
                              <?php }
							   } ?>
                            </select>
						</div>
              </div>
			  
			  			  
			  <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Pin Code </label>
                <div class="col-lg-3">
				  <input type="text" name="pin_code" id="pin_code" value="<?php echo isset($request['pin_code'])?$request['pin_code']:'';?>" class="form-control validate[required]">
                </div>
              </div>
             
         <div class="form-group" id="tax_div">
                   <label class="col-lg-3 control-label">Tax</label>
                   <div class="col-lg-8">
                      <div id="normal_div" style="float:left;width: 200px;">
                         <label  style="font-weight: normal;">
                         <input type="radio" name="taxation" id="taxation_frm" value="SEZ Unit No Tax" <?php if(isset($request) && 
                            ($request['taxation']== 'SEZ Unit No Tax')) { ?> checked="checked" <?php } ?>  checked="checked"> SEZ Unit No Tax </label> 
                      </div>
                      <div id="normal_div" style="float:left;width: 200px;"> 
                         <label  style="font-weight: normal;">
                         <input type="radio" name="taxation" id="taxation_nrm" value="With in Gujarat"  <?php if(isset($request) && ($request['taxation'] == 'With in Gujarat')) { ?> checked="checked" <?php } ?>> With In Gujarat</label>
                      </div>
                      <div id="form_div" style="float:left;width: 200px;"> 
                         <label style="font-weight: normal;">
                         <input type="radio" name="taxation" id="taxation_frm" value="Out Of Gujarat" <?php 
                            if(isset($request) && ($request['taxation'] == 'Out Of Gujarat')) { ?> checked="checked" <?php } ?> >Out Of Gujarat </label>
                      </div>
                   </div>
                </div>
                <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Identification Marks</label>
                <div class="col-lg-8">
                  <input type="text" name="identification_marks" id="identification_marks" value="<?php echo isset($request['identification_marks'])?$request['identification_marks']:'';?>" class="form-control validate[required]">
                </div>
              </div> 
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> No of Package</label>
                <div class="col-lg-8">
                  <input type="text" name="no_of_package" id="no_of_package" value="<?php echo isset($request['no_of_package'])?$request['no_of_package']:'';?>" class="form-control validate[required]">
                </div>
              </div> 
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Pouch Mode</label>
                <div class="col-lg-8">
                  <input type="text" name="pouch_mode" id="pouch_mode" value="<?php echo isset($request['pouch_mode'])?$request['pouch_mode']:'FREE SAMPLE';?>" class="form-control validate[required]">
                </div>
              </div> 
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Amount</label>
                <div class="col-lg-8">
                  <input type="text" name="amount" id="amount" value="<?php echo isset($request['amount'])?$request['amount']:'100';?>" class="form-control validate[required]">
                </div>
              </div> 
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Remark </label>
                <div class="col-lg-8">
                  	<textarea name="remark" id="remark" class="form-control validate[required]"><?php echo isset($request['remark'])?$request['remark']:'';?></textarea>
                </div>
              </div>
             
               <div class="form-group">
                       <label class="col-lg-3 control-label"><b style="color:#ff0000"> Do you want to Generate Sample Invoice ?</b></label>
                        <div class="col-lg-4">
                                 <select name="invoice_status" id="invoice_status" class="form-control validate[required]" >
                                  
                                    <option value="0" <?php if(isset($request) &&  $request['invoice_status']=='0') { ?> selected="selected" <?php } ?>>Yes</option>
                                    <option value="1" <?php if(isset($request) &&  $request['invoice_status']=='1') { ?> selected="selected" <?php } ?> >No</option>
                                </select>
                         </div>
                 </div>
              
               <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Sample Requester </label>
                <?php $userlist = $obj_sample->getUserListIndia(); ?>
				<div class="col-lg-5">
					<select class="form-control" name="requester" id="requester">
						<option value="">Please Select</option>
						<?php foreach ($userlist as $user) { ?>
							<?php if ($splitdata[0] == '2' && $splitdata[1] == $user['employee_id']) { ?>

								<option value="<?php echo "2=" . $user['employee_id']; ?>" selected="selected"><?php echo $user['name']; ?></option>
							<?php } else { ?>
								<option value="<?php echo "2=" . $user['employee_id']; ?>"><?php echo $user['name']; ?></option>
							<?php } ?>
						<?php } ?>                                       
					</select>
				</div>
              </div>
			  
              <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                <?php if($edit){?>
                  	<button type="submit" name="btn_update" id="btn_update" class="btn btn-primary">Update </button>
                <?php } else { ?>
                	<button type="button" name="btn_save" id="btn_save" class="btn btn-primary">Proceed </button>	
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
	/*color:#575757;*/
	color:#575757;
	cursor : default;
}
</style>
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

<script>
    jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#form").validationEngine();
		
    });
	$('#btn_save').click(function(){
	if($("#form").validationEngine('validate')){
		//$("#loading").show();
		var ajax_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=saveData', '',1);?>");
		var formData = $("#form").serialize();
		var admin_email = '<?php echo ADMIN_EMAIL;?>';
		var from_email = $("#add_email").val();
		var requester = $("#requester").val();
		$.ajax({
			url : ajax_url,
			method : 'post',		
			data : {formData : formData,admin_email:admin_email,from_email:from_email,requester:requester},
			success: function(res){
			//	console.log(res);
				var url = '<?php echo HTTP_SERVER; ?>admin/index.php?route=sample_request';
                window.location.href=url;
			},
			error: function(){
	
				return false;
			}
		});
	}
	else {
		return false;
	}
});
	$("#company_nm").focus();
	var offset = $("#company_nm").offset();
	var width = $("#holder").width();
	$("#ajax_return").css("width",width);
	
	$("#company_nm").keyup(function(event){		
		 var keyword = $("#company_nm").val();
		// alert(keyword);
		 if(keyword.length)
		 {
			 if(event.keyCode != 40 && event.keyCode != 38 )
			 {
				 var product_code_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=customer_detail', '',1);?>");
				 $("#loading").css("visibility","visible");
				 $.ajax({
				   type: "POST",
				   url: product_code_url,
				   data: "company_nm="+keyword,
				   success: function(msg){
					  	
				 var msg = $.parseJSON(msg);
				  //console.log(msg);
				 	
				   var div='<ul class="list">';
				   
					if(msg.length>0)
					{
						for(var i=0;i<msg.length;i++) 
						{	
							
								div =div+'<li><a href=\'javascript:void(0);\' id="'+msg[i].address_book_id+'" c_id="'+msg[i].company_address_id+'" f_id="'+msg[i].factory_address_id+'" consignee="'+msg[i].c_address+'" deladd="'+msg[i].f_address+'" email="'+msg[i].email_1+'" city="'+msg[i].city+'" state="'+msg[i].state+'" country="'+msg[i].country+'" pincode="'+msg[i].pincode+'" phone_no="'+msg[i].phone_no+'" contact_nm="'+msg[i].contact_name+'"><span class="bold" >'+msg[i].company_name+'</span></a></li>';
						}
					}
					
					div=div+'</ul>';
					//console.log(div);
					if(msg != 0)
					  $("#ajax_return").fadeIn("slow").html(div);
					else
					{
					  $("#ajax_return").fadeIn("slow");	
					  $("#ajax_return").html('<div style="text-align:left;">No Matches Found</div>');
					   $("#email").val('');
					    $("#city").val('');
						 $("#state").val('');
						  $("#pin_code").val('');
						  $("#country_id").val('');
				  		$("#address").val('');
				  		$("#address_book_id").val('');					
						$("#company_address_id").val('');
						$("#contact_nm").val('');
						$("#phone_no").val('');
						//$("#vat_no").val('');
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
							$("#company_nm").val($(".list li[class='selected'] a").text());
							$("#email").val($(".list li[class='selected'] a").attr("email"));
							$("#address").val($(".list li[class='selected'] a").attr("consignee"));							
							$("#address_book_id").val($(".list li[class='selected'] a").attr("id"));
							$("#company_address_id").val($(".list li[class='selected'] a").attr("c_id"));							
							 $("#city").val($(".list li[class='selected'] a").attr("city"));
							 $("#state").val($(".list li[class='selected'] a").attr("state"));
							  $("#pin_code").val($(".list li[class='selected'] a").attr("pin_code"));
							  $("#country_id").val($(".list li[class='selected'] a").attr("country"));
							  $("#contact_nm").val($(".list li[class='selected'] a").attr("contact_nm"));
							  $("#phone_no").val($(".list li[class='selected'] a").attr("pincode"));
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
							$("#company_nm").val($(".list li[class='selected'] a").text());
                  			$("#email").val($(".list li[class='selected'] a").attr("email"));
							$("#address").val($(".list li[class='selected'] a").attr("consignee"));							
							$("#address_book_id").val($(".list li[class='selected'] a").attr("id"));
							$("#company_address_id").val($(".list li[class='selected'] a").attr("c_id"));							
							$("#city").val($(".list li[class='selected'] a").attr("city"));
							 $("#state").val($(".list li[class='selected'] a").attr("state"));
							  $("#pin_code").val($(".list li[class='selected'] a").attr("pin_code"));
							  $("#country_id").val($(".list li[class='selected'] a").attr("country"));
							   $("#contact_nm").val($(".list li[class='selected'] a").attr("contact_nm"));
							   $("#phone_no").val($(".list li[class='selected'] a").attr("pincode"));
						}
				 }
				 break;				 
				}
			 }
		 }
		 else
		 {
			$("#ajax_return").fadeOut('slow');
			$("#ajax_return").html("");
		 }
	});
	
	$('#customer-name').keydown( function(e) {
		if (e.keyCode == 9) {
			 $("#ajax_return").fadeOut('slow');
			 $("#ajax_return").html("");
		}
	});

	$("#ajax_return").mouseover(function(){
				$(this).find(".list li a:first-child").mouseover(function () {
					  $("#email").val($(this).attr("email"));
					  $("#address").val($(this).attr("consignee"));					
					  $("#address_book_id").val($(this).attr("id"));
					  $("#company_address_id").val($(this).attr("c_id"));					
					  $("#city").val($(this).attr("city"));
					 $("#state").val($(this).attr("state"));
					  $("#pin_code").val($(this).attr("pincode"));
					  $("#country_id").val($(this).attr("country"));
					   $("#contact_nm").val($(this).attr("contact_nm"));
					   $("#phone_no").val($(this).attr("phone_no"));
					  $(this).addClass("selected");
				});
				$(this).find(".list li a:first-child").mouseout(function () {
					  $(this).removeClass("selected");
					  $("#email").val('');
					  $("#address").val('');
					  $("#delivery_info").val('');
					  $("#address_book_id").val('');
					  $("#company_address_id").val('');
					$("#city").val('');
					 $("#state").val('');
					  $("#pin_code").val('');
					  $("#country_id").val('');			
					$("#contact_nm").val('');
					$("#phone_no").val('');					
					  
				});
				$(this).find(".list li a:first-child").click(function () {					
					  if($(this).attr("email")!='null')
					  	$("#email").val($(this).attr("email"));
					  else
					  	$("#email").val('');
						
					  if($(this).attr("consignee")!='null')
					  	$("#address").val($(this).attr("consignee"));
					  else
					 	 $("#address").val('');
					
					  $("#address_book_id").val($(this).attr("id"));
					  if($(this).attr("c_id")!='null')
					 	 $("#company_address_id").val($(this).attr("c_id"));
					  else
					  	 $("#company_address_id").val('');	
						
						
						
							 $("#company_nm").val($(this).attr("contact_nm"));
						 $("#phone_no").val($(this).attr("phone_no"));
						
						 $("#city").val($(this).attr("city"));
					 $("#state").val($(this).attr("state"));
					  $("#pin_code").val($(this).attr("pincode"));
					  $("#country_id").val($(this).attr("country"));
					  $("#contact_nm").val($(this).attr("contact_nm"));
					
					    get_remark($(this).attr("id"));
					   $("#company_nm").val($(this).text());
					   $("#ajax_return").fadeOut('slow');
						$("#ajax_return").html("");
						
						
					
				});
				
			});
		
    //});
    
function get_remark(address_book_id){
    
    	var ajax_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=get_remark', '',1);?>");
			$.ajax({
			url : ajax_url,
			method : 'post',		
			data : {address_book_id : address_book_id},
			success: function(res){
			    if(res!='0')
			        $('#remark').val(res);
			},
			error: function(){
				return false;
			}
		});
    
}
</script> 
<!-- Close : validation script -->

<?php } else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>