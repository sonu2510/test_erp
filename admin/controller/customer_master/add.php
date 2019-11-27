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

if(isset($_GET['cust_id']) && !empty($_GET['cust_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$cust_id = base64_decode($_GET['cust_id']);
		$customer = $obj_customer_master->getCust($cust_id);
		//printr($customer);
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
		$insert_id = $obj_customer_master->addCustomer($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		$cust_id = $customer['cust_id'];
		$obj_customer_master->updateCustomer($cust_id,$post);
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
            <form class="form-horizontal" method="post" name="form_sample" id="form_sample" enctype="multipart/form-data">
              
              
               <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Email</label>
                <div class="col-lg-5">
                  	<input type="text" name="email" id="email"  placeholder="test@gmail.com" value="<?php echo isset($customer['email'])?$customer['email']:'';?>" class="form-control validate[required,custom[email]]"><span id="exists" style="color:red;display:none;"></span> 
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>First Name</label>
                <div class="col-lg-5">
                  	<input type="text" name="first_name" placeholder="First Name" id="first_name" value="<?php echo isset($customer['first_name'])?$customer['first_name']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Last Name</label>
                <div class="col-lg-5">
                  	<input type="text" name="last_name" id="last_name" placeholder="Last Name" value="<?php echo isset($customer['last_name'])?$customer['last_name']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Address</label>
                <div class="col-lg-5">
                  	<textarea type="text" name="address" placeholder="Address" value="" class="form-control validate[required]"><?php echo isset($customer['address'])?$customer['address']:'';?></textarea>
                     <div class="line line-dashed m-t-large"></div>
                </div>
              </div>
              
               <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Company Name</label>
                <div class="col-lg-5">
                  	<input type="text" name="company_name" id="company_name" placeholder="Company Name" value="<?php echo isset($customer['company_name'])?$customer['company_name']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
               <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Company Address</label>
                <div class="col-lg-5">
                  	<textarea type="text" name="company_address" placeholder="Company Address" value="" class="form-control validate[required]"><?php echo isset($customer['company_address'])?$customer['company_address']:'';?></textarea>
                     
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Brand Name</label>
                <div class="col-lg-5">
                  	<input type="text" name="brand_name" placeholder="Brand Name" value="<?php echo isset($customer['brand_name'])?$customer['brand_name']:'';?>" class="form-control validate[required]">
                    <div class="line line-dashed m-t-large"></div>
                </div>
              </div>
              
               <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Industry Product</label>
                <div class="col-lg-5">
                  	<input type="text" name="industry_p" placeholder="Industry Product" value="<?php echo isset($customer['industry_p'])?$customer['industry_p']:'';?>" class="form-control validate[required]">
                    <div class="line line-dashed m-t-large"></div>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Telephone</label>
                <div class="col-lg-5">
                  	<input type="text" name="telephone" placeholder="Telephone" value="<?php echo isset($customer['telephone'])?$customer['telephone']:'';?>" class="form-control validate[required,custom[onlyNumberSp],minSize[10],maxSize[10]]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Fax No</label>
                <div class="col-lg-5">
                  	<input type="text" name="fax" placeholder="Fax No" value="<?php echo isset($customer['fax'])?$customer['fax']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <?php $country= $obj_customer_master->getCountryList();
			 //printr($country); ?>
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Country</label>
                <div class="col-lg-4">
                  	 <select class="form-control validate[required]" name="country_name">
                  <option >Select Country Name</option>
                   <?php foreach($country as $value)
				   {	?>
                   <?php if($customer['country_name']==$value['country_name'])
				   {?>
                   
	                   	 	<option value="<?php echo $value['country_name']; ?>" selected="selected"><?php echo $value['country_name']; ?>
                            <?php } else { ?>
					 <option value="<?php echo $value['country_name']; ?>"> <?php echo $value['country_name']; ?></option>
				   <?php }?>
                   <?php }?>
                  </select>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>State</label>
                <div class="col-lg-5">
                  	<input type="text" name="state" placeholder="State" value="<?php echo isset($customer['state'])?$customer['state']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>City</label>
                <div class="col-lg-5">
                  	<input type="text" name="city" placeholder="City" value="<?php echo isset($customer['city'])?$customer['city']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Postcode</label>
                <div class="col-lg-5">
                  	<input type="text" name="postcode" placeholder="Postcode" value="<?php echo isset($customer['postcode'])?$customer['postcode']:'';?>" class="form-control validate[required]">
                </div>
              </div>
             
             
              <div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                <div class="col-lg-5">
                  <select name="status" id="status" class="form-control">
                    <option value="1" <?php echo (isset($customer['status']) && $customer['status'] == 1)?'selected':'';?> > Active</option>
                    <option value="0" <?php echo (isset($customer['status']) && $customer['status'] == 0)?'selected':'';?>> Inactive</option>
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
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

<script>
jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#form_sample").validationEngine();
		$("#email").change(function(e){
			var email = $(this).val();
			checkemailaddress(email);
		});
});
	$('#company_name,#first_name,#last_name').keyup(function () {
			if (this.value.match(/[^a-zA-Z ]/g)) {
			//this.value = this.value.replace(/[^a-zA-Z]/g, ”);
			alert("Please Enter Only Characters");
			}
	});
	
 	function checkemailaddress(email){
		var cust_email = '<?php echo isset($customer['email'])?$customer['email']:'';?>';
		if(email.length > 0 && cust_email != email){
			//$(".uniqusername").remove();
			var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=checkemailaddress', '',1);?>");
			$("#loading").show();
			$.ajax({
				url : status_url,
				type :'post',
				data :{email:email},
				success: function(json) {
					if(json > 0){
						$("#email").val('');
						$("#exists").show();
						$("#exists").html('Email is already exists!');
						$("#loading").hide();
						return false;
					}else{
						$("#loading").hide();
						$("#exists").hide();
						return true;
					}
				}
			});
		}else{
			$("#loading").hide();
			return true;
		}
	}	
	

</script>


<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>
