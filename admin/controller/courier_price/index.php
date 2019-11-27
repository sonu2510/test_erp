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
	'text' 	=> $display_name.' & Detail',
	'href' 	=> '',
	'icon' 	=> 'fa-list',
	'class'	=> 'active',
);
//Close : bradcums

//edit user
$edit = '';


if(isset($_GET['user_id']) && !empty($_GET['user_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$user_id = base64_decode($_GET['user_id']);

		$user = $obj_user->getUser(1,$user_id);
		
		
		//printr($user);die;
		$edit = 1;
	}
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}

if($display_status){
	
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		//printr($post);die;
		$user_id = $user['user_id'];
		$chckUserName = $obj_general->uniqUserName($post['username'],$user_id,'1');
		if($chckUserName){
		$insert_id = $obj_user->updateUser($user_id,$post);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, 'filter_edit='.$_GET['filter_edit'], '',1));
		}else{
		$_SESSION['warning'] = 'User name exist!';
		page_redirect($obj_general->link($rout, 'filter_edit='.$_GET['filter_edit'], '',1));	
		}
	}
	
	//insert user
	if(isset($_POST['btn_save'])){
		$post = post($_POST);
		$chckUserName = $obj_general->uniqUserName($post['user_name'],'','');
		if($chckUserName){
		//printr($post);die;
		$insert_id = $obj_user->addUser($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}else{
		$_SESSION['warning'] = 'User name exist!';
		page_redirect($obj_general->link($rout, '', '',1));
	}
}

?>

<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-list"></i> Courier Price List</h4>
    </div>
    <div class="row">
    	
        <div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>
        
      <div class="col-sm-8">
        <section class="panel">
          <header class="panel-heading bg-white"> Courier Price List </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
              
              <div class="form-group">
                <label class="col-lg-2 control-label"><span class="required">*</span>Country</label>
                <div class="col-lg-8">
                	<?php 
					$sel_country = (isset($user['country_id']))?$user['country_id']:'';
					$countrys = $obj_general->getCountryCombo($sel_country);
					echo $countrys;
					?>
                </div>
              </div>
                
         	<div class="form-group">
                <label class="col-lg-2 control-label"><span class="required">*</span>Weight (KG)</label>
                <div class="col-lg-3">
                	<input type="text" name="weight" value="<?php echo isset($user['gres'])?$user['gres']:'';?>" class="form-control validate[required]" />
                    
                </div>
              </div>
              
              
             <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                    <button type="button" name="btn_save" id="btn_save" onclick="get_price()" class="btn btn-primary">Submit </button>	
                 <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a>
                </div>
              </div>
              
              
              <div class="form-group">
				
              	<div class="col-lg-9 tab_data">
                	<div class="panel-body">
                    	<div class="table-responsive">
                        
                        </div>                    
                    </div>                	
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
<script>
    jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#form").validationEngine();
		
		$("#").submit(function (e) {
			
			var name = $("#username").val();
			var check = checkUser(name);
			e.preventDefault();
		});
		
		// UserName Already Exsist Ajax
		$("#username").change(function(e){
			var name = $(this).val();
			checkUser(name);
			
		});
    });
	
	function get_price()
	{
		var formData = $("#form").serialize();
		if($("#form").validationEngine('validate')){ 
			var get_price_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=get_price', '',1);?>");	
			$.ajax({
						url : get_price_url,
						method : 'post',
						data : {formData : formData},
						success: function(response){
							
							$(".tab_data").html(response);
						//set_alert_message('Successfully Added',"alert-success","fa-check");
						//window.setTimeout(function(){location.reload()},100)
						},
						error: function(){
							return false;	
						}
					});
			}		
		
	}
</script> 
<!-- Close : validation script -->
<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>