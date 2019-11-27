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
      <h4><i class="fa fa-user"></i> User</h4>
    </div>
    <div class="row">
    	
        <div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>
        
      <div class="col-sm-8">
        <section class="panel">
          <header class="panel-heading bg-white"> User Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
              
              <?php /*?><div class="form-group">
                <label class="col-lg-3 control-label">Photo</label>
                <div class="col-lg-9 media">
                  <div class="bg-light pull-left text-center media-large thumb-large"><i class="fa fa-user inline fa fa-light fa fa-3x m-t-large m-b-large"></i></div>
                  <div class="media-body">
                    <input type="file" name="file" title="Change" class="btn btn-sm btn-info m-b-small">
                    <br>
                    <button class="btn btn-sm btn-default">Delete</button>
                  </div>
                </div>
              </div><?php */?>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">FirstName</label>
                <div class="col-lg-8">
                  <input type="text" name="firstname" placeholder="First Name" value="<?php echo isset($user['first_name'])?$user['first_name']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">LastName</label>
                <div class="col-lg-8">
                  <input type="text" name="lastname" placeholder="Last Name" value="<?php echo isset($user['last_name'])?$user['last_name']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Email</label>
                <div class="col-lg-8">
                  <input type="text" name="email" placeholder="test@example.com" value="<?php echo isset($user['email'])?$user['email']:'';?>" class="form-control validate[required,custom[email]]">
                  <div class="line line-dashed m-t-large"></div>
                </div>
              </div>
              
             <div class="form-group">
                <label class="col-lg-3 control-label">Username</label>
                <div class="col-lg-8">
                  <input type="text" name="username" id="username" placeholder="Username" value="<?php echo isset($user['user_name'])?$user['user_name']:'';?>"  class="form-control validate[required]">
                </div>
              </div>
               <?php if(isset($_SESSION['LOGIN_USER_TYPE']) && $_SESSION['LOGIN_USER_TYPE'] == 1 && $edit == 1) {?>
              <div class="form-group">
                <label class="col-lg-3 control-label">Old Password
               </label>
                <div class="col-lg-8">
                  <input type="text" name="oldpassword" value="<?php echo $user['password_text'];?>" class="form-control" disabled="disabled">
                </div>
              </div>
              <?php }?>
              <div class="form-group">
                <label class="col-lg-3 control-label">Password</label>
                <div class="col-lg-8">
                  <input type="password" name="password" value="" class="form-control <?php echo ($edit)?'':'validate[required]';?>" >
                  <div class="line line-dashed m-t-large"></div>
                </div>
              </div> 
              	
              
               <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Telephone</label>
                <div class="col-lg-8">
                  <input type="text" name="telephone" value="<?php echo isset($user['telephone'])?$user['telephone']:'';?>" class="form-control validate[required,custom[onlyNumberSp],minSize[10],maxSize[10]]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Address</label>
                <div class="col-lg-8">
                  <input type="text" name="address" value="<?php echo isset($user['address'])?$user['address']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Postcode</label>
                <div class="col-lg-8">
                  <input type="text" name="postcode" value="<?php echo isset($user['postcode'])?$user['postcode']:'';?>" class="form-control validate[required,custom[onlyNumberSp],maxSize[6]]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>City</label>
                <div class="col-lg-8">
                  <input type="text" name="city" value="<?php echo isset($user['city'])?$user['city']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>State</label>
                <div class="col-lg-8">
                  <input type="text" name="state" value="<?php echo isset($user['state'])?$user['state']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Country</label>
                <div class="col-lg-8">
                	<?php 
					$sel_country = (isset($user['country_id']))?$user['country_id']:'';
					$countrys = $obj_general->getCountryCombo($sel_country);
					echo $countrys;
					?>
                </div>
              </div>
                
         
              <div class="form-group">
                <label class="col-lg-3 control-label">Department</label>
                <div class="col-lg-4">
                  <select name="department" id="department" class="form-control validate[required]">
                  	<?php
					$departments = $obj_user->getDepartments();
					$sel_departmetn = isset($user['department'])?$user['department']:'';
					foreach ($departments as $department){
						if($sel_departmetn && $sel_departmetn == $department['department_id']){
							echo '<option value="'.$department['department_id'].'" selected="selected">'.$department['department_name'].'</option>';
						}else{
							echo '<option value="'.$department['department_id'].'">'.$department['department_name'].'</option>';
						}
					}
					?>
                  </select>
                  <div class="line line-dashed m-t-large"></div>For quotation use
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Gres(Percentage %)</label>
                <div class="col-lg-8">
                	<input type="text" name="gres" value="<?php echo isset($user['gres'])?$user['gres']:'';?>" class="form-control" />
                    
                </div>
              </div>
              
                <div class="form-group">
                <label class="col-lg-3 control-label">Gres(Percentage % For Air)</label>
                <div class="col-lg-8">
                  <input type="text" name="gres_air" value="<?php echo isset($user['gres_air'])?$user['gres_air']:'';?>" class="form-control" />
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Gres(Percentage % For Sea)</label>
                <div class="col-lg-8">
                  <input type="text" name="gres_sea" value="<?php echo isset($user['gres_sea'])?$user['gres_sea']:'';?>" class="form-control" />
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Stock price addition For Factory</label>
                <div class="col-lg-8">
                  <input type="text" name="stock_factory" value="<?php echo isset($user['stock_factory'])?$user['stock_factory']:'';?>" class="form-control" />
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Stock price addition For Air</label>
                <div class="col-lg-8">
                  <input type="text" name="stock_air" value="<?php echo isset($user['stock_air'])?$user['stock_air']:'';?>" class="form-control" />
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Stock price addition For Sea</label>
                <div class="col-lg-8">
                  <input type="text" name="stock_sea" value="<?php echo isset($user['stock_sea'])?$user['stock_sea']:'';?>" class="form-control" />
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Valve Price(Indian Rupee <i class="fa fa-inr"></i>)</label>
                <div class="col-lg-8">
                	<input type="text" name="valve_price" value="<?php echo isset($user['valve_price'])?$user['valve_price']:'';?>" class="form-control" /></div>
              </div>
              
               <div class="form-group">
                <label class="col-lg-3 control-label">Stock Valve Price(Indian Rupee <i class="fa fa-inr"></i>)</label>
                <div class="col-lg-8">
                	<input type="text" name="stock_valve_price" value="<?php echo isset($user['stock_valve_price'])?$user['stock_valve_price']:'';?>" class="form-control" /></div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Allow Currency Selection</label>
                <div class="col-lg-8">
                	<div class="btn-group" data-toggle="buttons">
                     <?php 
					 if(isset($user['allow_currency']) && $user['allow_currency'] == 1){
						?>
                        <label class="btn btn-sm btn-white btn-on active">
                      		<input type="radio" name="allow_currency" id="allow_currency1" value="1" checked="checked"> Yes
                      </label>
                      <label class="btn btn-sm btn-white btn-off">
                      		<input type="radio" name="allow_currency" id="allow_currency2" value="0"> No
                      </label>
                        <?php	 
					 }else{
					 	?>
                        <label class="btn btn-sm btn-white btn-on">
                      		<input type="radio" name="allow_currency" id="allow_currency1" value="1"> Yes
                      </label>
                      <label class="btn btn-sm btn-white btn-off active">
                      		<input type="radio" name="allow_currency" id="allow_currency2" value="0" checked="checked"> No
                      </label>
                     	<?php
					 }
					 ?>
                     
                    </div>
                    <div class="line line-dashed m-t-large"></div>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                <div class="col-lg-4">
                  <select name="status" id="status" class="form-control">
                    <option value="1" <?php echo (isset($user['status']) && $user['status'] == 1)?'selected':'';?> > Active</option>
                    <option value="0" <?php echo (isset($user['status']) && $user['status'] == 0)?'selected':'';?>> Inactive</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Email Signature</label>
                <div class="col-lg-8">
                 <textarea name="email_signature" class="form-control"><?php echo isset($user['email_signature'])?$user['email_signature']:'';?></textarea>
                </div>
              </div>
               <!-- modified by jayashree-->
              
                <div class="form-group">
                <label class="col-lg-3 control-label">Default Currency</label>
                <div class="col-lg-4">
                <select name="default_curr" id="default_curr" class="form-control" onchange='currencyvalue()'>
                 
                 <?php $currency = $obj_user->getdefaultcurrency();
							
				foreach($currency as $curr)
				{
				?>      
                
               <option value="<?php echo $curr['country_id'];?>" <?php echo (isset($user['default_curr']) && $user['default_curr'] == $curr['country_id'])?'selected':'';?> > <?php echo $curr['currency_code']; ?></option>

               <?php
				}
				?>
                  </select>
                </div>
              </div>
              <input type="text" name="currval" id="currval" value="" hidden>
              
                <div class="form-group">
                <label class="col-lg-3 control-label">Product-Currency Rate</label>
                <div class="col-lg-8">
                <input type="text" name="p_rate" value="<?php echo isset($user['product_rate'])?$user['product_rate']:'';?>" class="form-control" />
                </div>
              </div>
                <div class="form-group">
                <label class="col-lg-3 control-label">Cylinder-Currency Rate</label>
                <div class="col-lg-8">
                  <input type="text" name="c_rate" value="<?php echo isset($user['cylinder_rate'])?$user['cylinder_rate']:'';?>"  class="form-control" />
                </div>
              </div>
              
             <!-- end of modification-->
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
	
	function checkUser(name){
		var orgname = '<?php echo isset($user['user_name'])?$user['user_name']:'';?>';
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
						$("#username").val('');
						$("#username").after('<span class="required uniqusername">Username already exists!</span>');
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
</script> 
<!-- Close : validation script -->
<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>