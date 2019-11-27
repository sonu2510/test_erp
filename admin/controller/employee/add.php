<?php
include("mode_setting.php");

if(isset($_GET['user_type']) && $_GET['user_type'] && isset($_GET['user_id']) && $_GET['user_id']){
  $user_type_id = decode($_GET['user_type']);
  $user_id = decode($_GET['user_id']);
  $queryString = '&user_type='.$_GET['user_type'].'&user_id='.$_GET['user_id'];
}else{
  $user_type_id = $obj_session->data['LOGIN_USER_TYPE'];
  $user_id = $obj_session->data['ADMIN_LOGIN_SWISS'];
  $queryString = '';
}

//Start : bradcums
$bradcums = array();
$bradcums[] = array(
	'text' 	=> 'Dashboard',
	'href' 	=> $obj_general->link('dashboard', '', '',1),
	'icon' 	=> 'fa-home',
	'class'	=> '',
);

if($user_type_id == 4){
	$bradcums[] = array(
		'text' 	=> 'International Branch List',
		'href' 	=> $obj_general->link('international_branch', '', '',1),
		'icon' 	=> 'fa-list',
		'class'	=> '',
	);
}

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
$employee_id='';
if(isset($_GET['employee_id']) && !empty($_GET['employee_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$employee_id = base64_decode($_GET['employee_id']);
		$employee = $obj_employee->getEmployee($employee_id);
		//printr($employee);
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
	//printr($post);die;
	$chckUserName = $obj_general->uniqUserName($post['user_name'],'','');
	if($chckUserName){
		$insert_id = $obj_employee->addEmployee($post,$user_type_id,$user_id);
		$_SESSION['success'] = ADD;
		//page_redirect($obj_general->link($rout, $queryString, '',1));
	}else{
		$_SESSION['warning'] = 'User name exist!';
		//page_redirect($obj_general->link($rout, $queryString, '',1));
	}
}

//edit
if(isset($_POST['btn_update']) && $edit){
	$post = post($_POST);
	$employee_id = $employee['employee_id'];
	$chckUserName = $obj_general->uniqUserName($post['user_name'],$employee_id,'2');
	if($chckUserName){
		$obj_employee->updateEmployee($employee_id,$post);
		$_SESSION['success'] = UPDATE;
		page_redirect($obj_general->link($rout, $queryString.'&filter_edit='.$_GET['filter_edit'], '',1));
	}else{
		$_SESSION['warning'] = 'User name exist!';
		page_redirect($obj_general->link($rout, $queryString.'&filter_edit='.$_GET['filter_edit'], '',1));
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
          <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" name="form" id="form" method="post" enctype="multipart/form-data">
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>First Name</label>
                <div class="col-lg-8">
                  <input type="text" name="first_name" value="<?php echo isset($employee['efirst_name'])?$employee['efirst_name']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Last Name</label>
                <div class="col-lg-8">
                  <input type="text" name="last_name" value="<?php echo isset($employee['elast_name'])?$employee['elast_name']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Email</label>
                <div class="col-lg-8">
                  <input type="text" name="email" value="<?php echo isset($employee['email'])?$employee['email']:'';?>" class="form-control validate[required,custom[email]]">
                   <div class="line line-dashed m-t-large"></div>
                </div>
              </div>
<div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>User Type</label>
                    <div class="col-lg-4">
                        <select name="user_type" class="form-control" id="user_type">
                            <?php
                           $user_type = $obj_employee->getUserType();
                           
                              foreach ($user_type as $user) {
                              // printr($user);
                                ?>
                                <option value="<?php echo $user['user_type_id']; ?>" <?php echo (isset($employee['user_type']) && $employee['user_type'] == $user['user_type_id']) ? 'selected' : ''; ?> ><?php echo $user['user_type_name']; ?></option>
                            <?php } ?>
                           </select>
                           </div>
                   </div>     
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Username</label>
                <div class="col-lg-8">
                  <input type="text" name="user_name" id="user_name" value="<?php echo isset($employee['user_name'])?$employee['user_name']:'';?>" class="form-control validate[required]">
                  
                </div>
              </div>
              
                <?php if(isset($_SESSION['LOGIN_USER_TYPE']) && $_SESSION['LOGIN_USER_TYPE'] == 1 && $edit == 1) {?>
              <div class="form-group">
                <label class="col-lg-3 control-label">Old Password
               </label>
                <div class="col-lg-8">
                  <input type="text" name="oldpassword" value="<?php echo $employee['password_text'];?>" class="form-control" disabled="disabled">
                </div>
              </div>
              <?php }?>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Password</label>
                <div class="col-lg-8">
                  <input type="password" name="password" value="" class="form-control <?php echo ($edit == 0)?'validate[required]':'';?>">
                   <div class="line line-dashed m-t-large"></div>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Associated Account</label>
				<div class="col-lg-8">
					<script src="<?php echo HTTP_SERVER;?>js/select2.min.js"></script>
					<script src="<?php echo HTTP_SERVER;?>js/chosen.jquery.min.js"></script>
					<!--<link href="<?php //echo HTTP_SERVER;?>css/chosen.min.css" rel="stylesheet"/>-->
					<link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet"/>
					
					
					<?php
					 $users=array();
					$userlist = $obj_employee->getUserList($employee_id);
						if (isset($employee) && $employee['associate_acnt'])
						{
							$users = explode(',',$employee['associate_acnt']);
							//printr($users);
							
							echo '<input type="hidden" name="edit_user_data" id="edit_user_data" value="'.json_encode($users).'">';	
						}?>
					<select data-placeholder="Begin typing a name to filter..." multiple class="chosen-select form-control select2-container select2-container-multi" name="associate_acnt[]">
						<option value=""></option>
						<?php foreach ($userlist as $user) { ?>
							<?php if (isset($employee) && in_array($user['user_type_id'].'='. $user['user_id'],$users)) { ?>

								<option value="<?php echo $user['user_type_id'] . "=" . $user['user_id']; ?>" selected="selected"><?php echo $user['user_name']; ?></option>
							<?php } else { ?>
								<option value="<?php echo $user['user_type_id'] . "=" . $user['user_id']; ?>"><?php echo $user['user_name']; ?></option>
							<?php } ?>
						<?php } ?>                                       
					  </select>
					
				</div>
              </div>
              
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Telephone</label>
                <div class="col-lg-8">
                  <input type="text" name="telephone" value="<?php echo isset($employee['telephone'])?$employee['telephone']:'';?>" class="form-control validate[required,custom[onlyNumberSp],minSize[10],maxSize[10]]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Address</label>
                <div class="col-lg-8">
                  <input type="text" name="address" value="<?php echo isset($employee['address'])?$employee['address']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Postcode</label>
                <div class="col-lg-8">
                  <input type="text" name="postcode" value="<?php echo isset($employee['postcode'])?$employee['postcode']:'';?>" class="form-control validate[required,custom[onlyNumberSp],maxSize[6]]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>City</label>
                <div class="col-lg-8">
                  <input type="text" name="city" value="<?php echo isset($employee['city'])?$employee['city']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>State</label>
                <div class="col-lg-8">
                  <input type="text" name="state" value="<?php echo isset($employee['state'])?$employee['state']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Country</label>
                <div class="col-lg-8">
                	<?php 
					$sel_country = (isset($employee['country_id']))?$employee['country_id']:'';
					$countrys = $obj_general->getCountryCombo($sel_country);
					echo $countrys;
					?>
                </div>
              </div>
               <div class="form-group">
                <label class="col-lg-3 control-label">Language Selection</label>
				<div class="col-lg-8">
					<?php $langlist = $obj_employee->getLanguage(); //printr($branch);
					      $lang = array();//$lang
						if (isset($employee) && !empty($employee) && $employee['lang_id'])
						{
							$lang = explode(',',$employee['lang_id']);
							//printr($lang);
							
							echo '<input type="hidden" name="edit_user_data" id="edit_user_data" value="'.json_encode($lang).'">';	
						}?>
					<select data-placeholder="Begin typing a name to filter..." multiple class="chosen-select form-control select2-container select2-container-multi" name="lang[]">
						<option value=""></option>
						<?php foreach ($langlist as $langs) { ?>
							<?php if (isset($employee) && in_array($langs['lang_id'],$lang)) { ?>

								<option value="<?php echo $langs['lang_id']; ?>" selected="selected"><?php echo $langs['language']; ?></option>
							<?php } else { ?>
								<option value="<?php echo $langs['lang_id']; ?>"><?php echo $langs['language']; ?></option>
							<?php } ?>
						<?php } ?>                                       
					  </select>
					
				</div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                <div class="col-lg-4">
                  <select name="status" id="status" class="form-control">
                    <option value="1" <?php echo (isset($employee['status']) && $employee['status'] == 1)?'selected':'';?> > Active</option>
                    <option value="0" <?php echo (isset($employee['status']) && $employee['status'] == 0)?'selected':'';?>> Inactive</option>
                  </select>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Email Signature</label>
                <div class="col-lg-8">
                  <textarea name="email_signature"  class="form-control"><?php echo isset($employee['email_signature'])?$employee['email_signature']:'';?></textarea>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Commission In (%)</label>
                <div class="col-lg-4">
                  <input type="text" name="commission" value="<?php echo isset($employee['commission'])?$employee['commission']:'';?>" class="form-control" />
                </div>
              </div>
              
               <div class="form-group">
               <label class="col-lg-3 control-label">Stock Order Price Display</label>
          		<div class="col-lg-9">
                	<div class="radio">
                      	<label class="radio-custom">
                        <input type="radio" name="stock_order" value="1" class="required"
						<?php echo (isset($employee['stock_order_price']) && $employee['stock_order_price'] == '1')?'checked="checked"':'';?>>
                        <i class="fa fa-circle-o"></i> Yes </label>
                    </div>
                    <div class="radio">
                      	<label class="radio-custom">
                        <input type="radio" name="stock_order" value="0" <?php echo (isset($employee['stock_order_price']) && $employee['stock_order_price'] == '0')?'checked="checked"':'';?>>
                        <i class="fa fa-circle-o"></i> No </label>
                    </div>
                </div>
                </div>
                
                 <div class="form-group">
               <label class="col-lg-3 control-label">Multi Quotation Price Display</label>
          		<div class="col-lg-9">
                	<div class="radio">
                      	<label class="radio-custom">
                        <input type="radio" name="multi_quotation_price" value="1" class="required"
						<?php echo (isset($employee['multi_quotation_price']) && $employee['multi_quotation_price'] == '1')?'checked="checked"':'';?>>
                        <i class="fa fa-circle-o"></i> Yes </label>
                    </div>
                    <div class="radio">
                      	<label class="radio-custom">
                        <input type="radio" name="multi_quotation_price" value="0" <?php echo (isset($employee['multi_quotation_price']) && $employee['multi_quotation_price'] == '0')?'checked="checked"':'';?>>
                        <i class="fa fa-circle-o"></i> No </label>
                    </div>
                </div>
                </div>
                
               <div class="form-group">
               <label class="col-lg-3 control-label">Input Stock Order Price Display Compulsory</label>
          		<div class="col-lg-9">
                	<div class="radio">
                      	<label class="radio-custom">
                        <input type="radio" name="stock_comp" value="1" class="required"
						<?php echo (isset($employee['stock_price_compulsory']) && $employee['stock_price_compulsory'] == '1')?'checked="checked"':'';?>>
                        <i class="fa fa-circle-o"></i> Yes </label>
                    </div>
                    <div class="radio">
                      	<label class="radio-custom">
                        <input type="radio" name="stock_comp" value="0" <?php echo (isset($employee['stock_price_compulsory']) && $employee['stock_price_compulsory'] == '0')?'checked="checked"':'';?>>
                        <i class="fa fa-circle-o"></i> No </label>
                    </div>
                </div>
                </div>
              
              <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                <?php if($edit){?>
                  	<button type="submit" name="btn_update" id="btn_update" class="btn btn-primary">Update </button>
                <?php } else { ?>
                	<button type="submit" name="btn_save" id="btn_save" class="btn btn-primary">Save </button>	
                <?php } ?>  
                  <a class="btn btn-default" href="<?php echo $obj_general->link($rout, $queryString, '',1);?>">Cancel</a>
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
		CKEDITOR.replace('email_signature', {
			toolbar: [ 
	{ name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
	{ name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source' ] },
	
	'/',
	{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic','Strike' ] },
	{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote'] },
	
	{ name: 'styles', items: [ 'Styles', 'Format'] },
	]});
    });
</script>
<script>
     jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#form").validationEngine();
		
		$("#").submit(function (e) {
			//e.preventDefault();
			var name = $("#user_name").val();
			var check = checkUser(name);
			if(!check){
				e.preventDefault();
			}
		});
		
		// UserName Already Exsist Ajax
		$("#user_name").change(function(e){
			var name = $(this).val();
			checkUser(name);
		});
    });
	
	function checkUser(name){
		var orgname = '<?php echo isset($employee['user_name'])?$employee['user_name']:'';?>';
		if(name.length > 0 && orgname != name){
			$(".uniqusername").remove();
			var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=UserNameAlreadyExsist', '',1);?>");
			$("#loading").show();
			$.ajax({
				url : status_url,
				type :'post',
				data :{name:name},
				success: function(json) {
					if(json > 0){
						$("#user_name").val('');
						$("#user_name").after('<span class="required uniqusername">Username already exists!</span>');
						$("#loading").hide();
						return false;
					}else{
						$("#loading").hide();
						$(".uniqusername").remove();
						return true;
					}
				}
			});
		}else{
			$("#loading").hide();
			$(".uniqusername").remove();
			return true;
		}
	}
	$(".chosen-select").chosen({
		no_results_text: "Oops, nothing found!"
	});
</script> 
<!-- Close : validation script -->


<?php
} else { 
	include_once(DIR_ADMIN.'access_denied.php');
}
?>