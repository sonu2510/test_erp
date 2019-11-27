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
if(isset($_GET['store_id']) && !empty($_GET['store_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$store_id = base64_decode($_GET['store_id']);
		$store = $obj_store->getStore($store_id);
		//printr($store);die;
		$edit = 1;
	}
	
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}

//Close : edit

if($display_status){

//insert
if(isset($_POST['btn_save'])){
	$post = post($_POST);
	
	$serialize_data = serialize($post);
	//echo $serialize_data;die;
	
	$insert_id = $obj_store->addStore($serialize_data);
	$_SESSION['success'] = ADD;
	page_redirect($obj_general->link($rout, '', '',1));
}

//edit
if(isset($_POST['btn_update']) && $edit){
	$post = post($_POST);
	$branch_id = $branch['international_branch_id'];
	$chckUserName = $obj_general->uniqUserName($post['user_name'],$branch_id,'4');
	if($chckUserName){
		$obj_branch->updateBranch($branch_id,$post);
		if(isset($_FILES['logo']) && !empty($_FILES['logo']) && $_FILES['logo']['error'] == 0){
			$obj_branch->uploadLogoImage($branch_id,$_FILES['logo']);
		}
		$_SESSION['success'] = UPDATE;
		page_redirect($obj_general->link($rout, 'filter_edit='.$_GET['filter_edit'], '',1));
	}else{
		$_SESSION['warning'] = 'User name exist!';
		page_redirect($obj_general->link($rout, 'filter_edit='.$_GET['filter_edit'], '',1));
	}
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
          <header class="panel-heading bg-white"> Add Setting </header>
          <div class="panel-body">
            <form class="form-horizontal" name="form" id="form" method="post" enctype="multipart/form-data">             
                <section class="panel">
                  <header class="panel-heading text-right">
                    <ul class="nav nav-tabs pull-left">
                      <li class="active"><a data-toggle="tab" href="#general">General</a></li>
                      <li class=""><a data-toggle="tab" href="#store-detail">Store</a></li>
                      <li class=""><a data-toggle="tab" href="#local">Local</a></li>
                      <li class=""><a data-toggle="tab" href="#option">Option</a></li>
                      <li class=""><a data-toggle="tab" href="#image">Image</a></li>
                    </ul>
                  </header>
                  <div class="panel-body">
                    <div class="tab-content">
                      <div id="general" class="tab-pane fade active in">                      	
                      	  <div class="form-group">
                            <label class="col-lg-3 control-label"><span class="required">*</span>Store Name</label>
                            <div class="col-lg-8">
                              <input type="text" name="store_name" value="<?php echo isset($store['name'])?$store['name']:'';?>" class="form-control validate[required]">
                            </div>
                          </div>
                          
                          <div class="form-group">
                            <label class="col-lg-3 control-label"><span class="required">*</span>Store Url</label>
                            <div class="col-lg-8">
                              <input type="text" name="store_url" value="<?php echo isset($store['url'])?$store['url']:'';?>" class="form-control validate[required]">
                            </div>
                          </div>
                          
                          <div class="form-group">
                            <label class="col-lg-3 control-label"><span class="required">*</span>Store Owner</label>
                            <div class="col-lg-8">
                              <input type="text" name="store_owner" value="<?php echo isset($store['owner'])?$store['owner']:'';?>" class="form-control validate[required]">
                            </div>
                          </div>
                          
                          <div class="form-group">
                            <label class="col-lg-3 control-label"><span class="required">*</span>Address</label>
                            <div class="col-lg-8">
                              <textarea rows="5" cols="4" name="store_address" class="form-control validate[required]"><?php echo isset($store['address'])?$store['address']:'';?></textarea>
                            </div>
                          </div>
                          
                          <div class="form-group">
                            <label class="col-lg-3 control-label"><span class="required">*</span>Email</label>
                            <div class="col-lg-8">
                              <input type="text" name="store_email" value="<?php echo isset($store['email'])?$store['email']:'';?>" class="form-control validate[required]">
                            </div>
                          </div>
                          
                          <div class="form-group">
                            <label class="col-lg-3 control-label"><span class="required">*</span>Telephone</label>
                            <div class="col-lg-8">
                              <input type="text" name="store_telephone" value="<?php echo isset($store['telephone'])?$store['telephone']:'';?>" class="form-control validate[required]">
                            </div>
                          </div>
                          
                          <div class="form-group">
                            <label class="col-lg-3 control-label"><span class="required">*</span>Fax</label>
                            <div class="col-lg-8">
                              <input type="text" name="store_fax" value="<?php echo isset($store['fax'])?$store['fax']:'';?>" class="form-control validate[required]">
                            </div>
                          </div>
                      </div>
                      
                      <div id="store-detail" class="tab-pane fade">
                      	  <div class="form-group">
                            <label class="col-lg-4 control-label"><span class="required">*</span>Meta Title</label>
                            <div class="col-lg-8">
                              <input type="text" name="store_meta_title" value="<?php echo isset($store['meta_title'])?$store['meta_title']:'';?>" class="form-control validate[required]">
                            </div>
                          </div>
                          
                          <div class="form-group">
                            <label class="col-lg-4 control-label">Meta Tag Description</label>
                            <div class="col-lg-8">
                             	<textarea rows="5" cols="30" name="store_meta_description"><?php echo isset($store['meta_description'])?$store['meta_description']:'';?></textarea>
                            </div>
                          </div>
                      	  
                          <div class="form-group">
                            <label class="col-lg-4 control-label">Meta Tag Keywords</label>
                            <div class="col-lg-8">
                              <textarea rows="5" cols="30" name="store_meta_keywords"><?php echo isset($store['store_meta_keywords'])?$store['store_meta_keywords']:'';?></textarea>
                            </div>
                          </div>                      
                      </div>
                      
                      
                      <div id="local" class="tab-pane fade">                      		
                      	  <div class="form-group">
                            <label class="col-lg-4 control-label">Country</label>
                            <div class="col-lg-8">
								<?php 
                                $sel_country = (isset($user['country_id']))?$user['country_id']:'';
                                $countrys = $obj_general->getCountryCombo($sel_country);
                                echo $countrys;
                                ?>
                                <div class="line line-dashed m-t-large"></div>
                            </div>                            
                          </div>
                      	  
                          <div class="form-group">
                            <label class="col-lg-4 control-label">State</label>
                            <div class="col-lg-8">
                              <input type="text" name="store_state" class="form-control" value="<?php echo isset($store['state'])?$store['state']:'';?>">
                            </div>
                          </div>	
                      </div>
                      
                      
                      <div id="option" class="tab-pane fade">
                      	 
                         <div class="form-group">
                         	<label class="col-lg-4 control-label"><span class="required">*</span>Default Items Per Page</label>
                            <div class="col-lg-8">
                              <input type="text" class="form-control validate[required]" name="default_item_per_page" class="form-control" value="<?php echo isset($store['item_per_page'])?$store['item_per_page']:'';?>">
                            </div>
                         </div>
                         
                         <div class="form-group">
                         	<label class="col-lg-4 control-label">Allow Reviews</label>
                            <div class="col-lg-8">
                                <div class="radio">
                                  <label class="radio-custom">
                                    <input type="radio" value="1" checked="checked" name="allow_reviews">
                                    <i class="fa fa-circle-o checked"></i> Yes </label>
                                    
                                  <label class="radio-custom">
                                    <input type="radio" name="allow_reviews" value="0">
                                    <i class="fa fa-circle-o"></i> No </label>  
                                </div>
                            </div>
                         </div>
                         
                         
                         <div class="form-group">
                         	<label class="col-lg-4 control-label">Display Price With Tax</label>
                            <div class="col-lg-8">
                                <div class="radio">
                                  <label class="radio-custom">
                                    <input type="radio" value="1" checked="checked" name="display_price_with_tax">
                                    <i class="fa fa-circle-o checked"></i> Yes </label>
                                    
                                  <label class="radio-custom">
                                    <input type="radio" name="display_price_with_tax" value="0">
                                    <i class="fa fa-circle-o"></i> No </label>  
                                </div>
                            </div>
                         </div>
                       	
                        
                         <div class="form-group">
                            <label class="col-lg-4 control-label"> Invoice Prefix</label>
                            <div class="col-lg-8">
                              <input type="text" name="invoic_prefix" class="form-control" value="<?php echo isset($store['invoic_prefix'])?$store['invoic_prefix']:'';?>" />
                            </div>
                         </div>	
                        
                      </div>
                     
                    </div>
                  </div>
                </section>      
                
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

<!-- select2 <script src="<?php echo HTTP_SERVER;?>js/select2/select2.min.js"></script>-->
<script>
    jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#form").validationEngine();
		//jQuery("#country_id").select2()
		$("#user_name").blur(function(){
			var name = $(this).val();
			if(name.length > 0){
				$(".uniqusername").remove();
				$.ajax({
					type: "POST",
					url: '<?php echo HTTP_ADMIN;?>ajax/uniqUserName.php',
					dataType: 'json',
					data:'name='+name,
					success: function(json) {
						if(json > 0){
							$("#user_name").val('');
							$("#user_name").after('<span class="required uniqusername">Username already exists!</span>');
							return false;
						}
					}
				});
			}
		});
    });
	
    $("#form").validationEngine({
        validateNonVisibleFields: true,
        //updatePromptsPosition:true
    });
    
</script> 
<!-- Close : validation script -->

<?php
} else { 
	include_once(DIR_ADMIN.'access_denied.php');
}
?>