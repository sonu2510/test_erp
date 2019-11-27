<?php

//jayashree
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
$curr='';
$edit = '';
if(isset($_GET['currency_id']) && !empty($_GET['currency_id'])){
	$currency_id = $_GET['currency_id'];
	//echo $_GET['currency_id'];
	$currency = $obj_currency->getCurrency($currency_id);
	//printr($currency);
	//die;
	$curr=$currency['country_code'];
	$edit = 1;
	
}
//Close : edit

if($display_status){
	//insert user
	if(isset($_POST['btn_save'])){
		$post = post($_POST);		
		//printr($post);die;
		$insert_id = $obj_currency->addCurrency($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		//printr($post);die;
		$currency_id = $currency['currency_id'];
		$obj_currency->updateCurrency($currency_id,$post);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
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
                <label class="col-lg-3 control-label"><span class="required">*</span> Currency Name </label>
                <div class="col-lg-4">
                  	<input type="text" name="name" id="currency_name" value="<?php echo isset($currency['currency_name'])?$currency['currency_name']:'';?>" class="form-control validate[required]"><span id="availability_status"></span>
                </div>
              </div>
              <?php
			  		$user_id = isset($obj_session->data['ADMIN_LOGIN_SWISS'])?$obj_session->data['ADMIN_LOGIN_SWISS']:'';
					$user_type_id = isset($obj_session->data['LOGIN_USER_TYPE'])?$obj_session->data['LOGIN_USER_TYPE']:'';
					
					//printr($getcurrencycode);
			  	?>
              	<input type="text" name="user_type_id" id="user_type_id" value="<?php echo $user_type_id?>" hidden />
              	<input type="text" name="user_id" id="user_id" value="<?php echo $user_id?>" hidden/>
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Currency Code</label>
                <div class="col-lg-4">
                  <select name="currcode" id="currcode" class="form-control">
                  <option value="">Select Currency</option>
                <?php 
				
					
					//echo $user;
					//die;
					
					$getcurrencycode = $obj_currency->getcurrencycode($user_id,$user_type_id);
				
					//printr($getcurrencycode);
					//die;
					foreach($getcurrencycode as $code)
					{
						
						//echo $code['country_id'];
				?>
       		<option value="<?php echo $code['country_id'];?>" <?php echo (isset($code['country_id']) && $code['country_id'] == $curr)?'selected':'';?>> <?php echo $code['currency_code'];?></option>
              <?php
					}
				?>
                </select>
                </div>
                </div>
              <div class="form-group">
               <label class="col-lg-3 control-label"><span class="required">*</span> Currency Price /
              <?php
					$currname=$obj_currency->getcurrencyname($user_id,$user_type_id);
					//printr($currname);
					//die;
					
					foreach($currname as $name)
					{
						
				  		echo $name['currency_code'];
					}
				?>
                </label>
                <div class="col-lg-4">
                
                  	<input type="text" name="price" value="<?php echo isset($currency['price'])?$currency['price']:'';?>" class="form-control validate[required,custom[number]]">
               
                </div>
              </div>
             
             <div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                <div class="col-lg-4">
                  <select name="status" id="status" class="form-control">
                    <option value="1" <?php echo (isset($currency['status']) && $currency['status'] == 1)?'selected':'';?> > Active</option>
                    <option value="0" <?php echo (isset($currency['status']) && $currency['status'] == 0)?'selected':'';?>> Inactive</option>
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
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>


<script type="text/javascript">

jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#form").validationEngine();
    });

//currency code

<?php if(empty($edit)) { ?>


$('#btn_save').click(function(){
	checkCurrencyCode();
});

$("#country_code").change(function(){ 
	checkCurrencyCode();
});


function checkCurrencyCode(){
	
	var currency_code = $("#country_code").val();
	
	if(currency_code!=''){
		var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=checkcurrencycode', '',1);?>");
		
		$("#code_status").html('Checking availability...');
		
		$.ajax({  //Make the Ajax Request
			type: "POST",  
			url: status_url, //file name
			data: "currency_code="+ currency_code,  //data
			success: function(server_response){  
				
				if(server_response==1){		
					$("#code_status").html('<font color="Green"> currency code Available </font>  ');
				}
				if(server_response ==0){
					$("#code_status").html(' <font color="red"> currency code already taken </font>');
					$('#currency_code').val('');
					return false;
				}  	
		   }
		});
	}else{
		$("#code_status").text('');
	}

}
<?php } ?>

</script>

<?php } else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>