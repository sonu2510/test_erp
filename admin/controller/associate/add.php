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
if(isset($_GET['associate_id']) && !empty($_GET['associate_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$associate_id = decode($_GET['associate_id']);
		$associate = $obj_associate->getAssociate($associate_id);
		//printr($associate);die;
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
		$insert_id = $obj_associate->addAssociate($post);
		if(isset($_FILES['logo']) && !empty($_FILES['logo']) && $_FILES['logo']['error'] == 0){
			$obj_associate->uploadLogoImage($insert_id,$_FILES['logo']);
		}
		$_SESSION['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}else{
		$_SESSION['warning'] = 'User name exist!';
		page_redirect($obj_general->link($rout, '', '',1));
	}
}

//edit
if(isset($_POST['btn_update']) && $edit){
	$post = post($_POST);
	$associate_id = $associate['associate_id'];
	$chckUserName = $obj_general->uniqUserName($post['user_name'],$associate_id,'5');
	if($chckUserName){
		$obj_associate->updateAssociate($associate_id,$post);
		if(isset($_FILES['logo']) && !empty($_FILES['logo']) && $_FILES['logo']['error'] == 0){
			$obj_associate->uploadLogoImage($associate_id,$_FILES['logo']);
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
          <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" name="form" id="form" method="post" enctype="multipart/form-data">
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Logo</label>
                <div class="col-lg-9 media">
                  <div class="bg-light pull-left text-center media-large thumb-large">
                  		<?php
						$upload_path = DIR_UPLOAD.'admin/logo/';
						$http_upload = HTTP_UPLOAD.'admin/logo/';
						if(isset($associate['logo']) && $associate['logo'] != '' && file_exists($upload_path.'200_'.$associate['logo'])){
							$logo = $http_upload.'200_'.$associate['logo'];
						}else{
							$logo = HTTP_SERVER.'images/blank-user64x64.png';
						}
                        ?>
                        <img src="<?php echo $logo;?>" alt="<?php echo $associate['afirst_name'];?>">
                  </div>
                  <div class="media-body">
                    <input type="file" name="logo" title="Change" class="btn btn-sm btn-info m-b-small">
                    <br>
                    <button type="button" class="btn btn-sm btn-default">Delete</button>
                  </div>
                </div>
              </div>
              
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Company Name</label>
                <div class="col-lg-8">
                  <input type="text" name="company_name" value="<?php echo isset($associate['company_name'])?$associate['company_name']:'';?>" class="form-control">
                </div>
              </div>
              
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>First Name</label>
                <div class="col-lg-8">
                  <input type="text" name="first_name" value="<?php echo isset($associate['afirst_name'])?$associate['afirst_name']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Last Name</label>
                <div class="col-lg-8">
                  <input type="text" name="last_name" value="<?php echo isset($associate['alast_name'])?$associate['alast_name']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Email</label>
                <div class="col-lg-8">
                  <input type="text" name="email" value="<?php echo isset($associate['email'])?$associate['email']:'';?>" class="form-control validate[required,custom[email]]">
                   <div class="line line-dashed m-t-large"></div>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Username</label>
                <div class="col-lg-8">
                  <input type="text" name="user_name" id="user_name" value="<?php echo isset($associate['user_name'])?$associate['user_name']:'';?>" class="form-control validate[required]">
                  
                </div>
              </div>
              
               <?php if(isset($_SESSION['LOGIN_USER_TYPE']) && $_SESSION['LOGIN_USER_TYPE'] == 1 && $edit == 1) {?>
              <div class="form-group">
                <label class="col-lg-3 control-label">Old Password
               </label>
                <div class="col-lg-8">
                  <input type="text" name="oldpassword" value="<?php echo $associate['password_text'];?>" class="form-control" disabled="disabled">
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
                <label class="col-lg-3 control-label"><span class="required">*</span>Telephone</label>
                <div class="col-lg-8">
                  <input type="text" name="telephone" value="<?php echo isset($associate['telephone'])?$associate['telephone']:'';?>" class="form-control validate[required,custom[onlyNumberSp],minSize[10],maxSize[10]]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Address</label>
                <div class="col-lg-8">
                  <input type="text" name="address" value="<?php echo isset($associate['address'])?$associate['address']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Postcode</label>
                <div class="col-lg-8">
                  <input type="text" name="postcode" value="<?php echo isset($associate['postcode'])?$associate['postcode']:'';?>" class="form-control validate[required,custom[onlyNumberSp],maxSize[6]]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>City</label>
                <div class="col-lg-8">
                  <input type="text" name="city" value="<?php echo isset($associate['city'])?$associate['city']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>State</label>
                <div class="col-lg-8">
                  <input type="text" name="state" value="<?php echo isset($associate['state'])?$associate['state']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Country</label>
                <div class="col-lg-8">
                	<?php 
					$sel_country = (isset($associate['country_id']))?$associate['country_id']:'';
					$countrys = $obj_general->getCountryCombo($sel_country);
					echo $countrys;
					?>
                    <div class="line line-dashed m-t-large"></div>For quotation use
                </div>
              </div>
              
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Gres(Percentage %)</label>
                <div class="col-lg-8">
                	<input type="text" name="gres" value="<?php echo isset($associate['gres'])?$associate['gres']:'';?>" class="form-control" /></div>
              </div>
              
                <div class="form-group">
                <label class="col-lg-3 control-label">Gres(Percentage % For Air)</label>
                <div class="col-lg-8">
                  <input type="text" name="gres_air" value="<?php echo isset($associate['gres_air'])?$associate['gres_air']:'';?>" class="form-control" />
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Gres(Percentage % For Sea)</label>
                <div class="col-lg-8">
                  <input type="text" name="gres_sea" value="<?php echo isset($associate['gres_sea'])?$associate['gres_sea']:'';?>" class="form-control" />
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Valve Price(Indian Rupee <i class="fa fa-inr"></i>)</label>
                <div class="col-lg-8">
                	<input type="text" name="valve_price" value="<?php echo isset($associate['valve_price'])?$associate['valve_price']:'';?>" class="form-control" /></div>
              </div>
              
                <div class="form-group">
                <label class="col-lg-3 control-label">Stock Valve Price(Indian Rupee <i class="fa fa-inr"></i>)</label>
                <div class="col-lg-8">
                	<input type="text" name="stock_valve_price" value="<?php echo isset($associate['stock_valve_price'])?$associate['stock_valve_price']:'';?>" class="form-control" /></div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Allow Currency Selection
                </label>
                <div class="col-lg-8">
                	<div class="btn-group" data-toggle="buttons">
                    <?php
					if(isset($associate['allow_currency']) && $associate['allow_currency'] == 1){
						?>
                        <label class="btn btn-sm btn-white btn-on active">
                      		<input type="radio" name="allow_currency" id="allow_currency1" value="1" checked="checked" > Yes
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
                    <option value="1" <?php echo (isset($associate['status']) && $associate['status'] == 1)?'selected':'';?> > Active</option>
                    <option value="0" <?php echo (isset($associate['status']) && $associate['status'] == 0)?'selected':'';?>> Inactive</option>
                  </select>
                </div>
              </div>
              
               
              <div class="form-group">
                <label class="col-lg-3 control-label">Email Signature</label>
                <div class="col-lg-8">
                  <textarea name="email_signature" class="form-control"><?php echo isset($associate['email_signature'])?$associate['email_signature']:'';?></textarea>
                </div>
              </div>
               <!-- modified by jayashree-->
              
                <div class="form-group">
                <label class="col-lg-3 control-label">Default Currency</label>
                <div class="col-lg-4">
                <select name="default_curr" id="default_curr" class="form-control" onchange='currencyvalue()'>
                 
                 <?php $currency = $obj_associate->getdefaultcurrency();
						//printr($currency);
						//die;
				foreach($currency as $curr)
				{
				?>      
                
               <option value="<?php echo $curr['country_id'];?>" <?php echo (isset($branch['default_curr']) && $branch['default_curr'] == $curr['country_id'])?'selected':'';?> > <?php echo $curr['currency_code']; ?></option>

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
                  <input type="text" name="p_rate" value="<?php echo isset($associate['product_rate'])?$associate['product_rate']:'';?>" class="form-control" />
                </div>
              </div>
                <div class="form-group">
                <label class="col-lg-3 control-label">Cylinder-Currency Rate</label>
                <div class="col-lg-8">
                  <input type="text" name="c_rate" value="<?php echo isset($associate['cylinder_rate'])?$associate['cylinder_rate']:'';?>" class="form-control" />
                </div>
              </div>
              
             <!-- //end of modification-->
              
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
			//alert(check);
			if(!check){
				e.preventDefault();
			}
			//checkUser(name).done(function(value){
				//alert(value);
			//});
		});
		
		// UserName Already Exsist Ajax
		$("#user_name").change(function(e){
			var name = $(this).val();
			checkUser(name);
		});
    });
	
	function checkUser(name){
		var orgname = '<?php echo isset($associate['user_name'])?$associate['user_name']:'';?>';
		//alert(orgname);
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
						//alert(json);
						$("#user_name").val('');
						$("#user_name").after('<span class="required uniqusername">Username already exists!</span>');
						$("#loading").hide();
						return false;
						
					}else{
						//alert(json);
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
	

//jayashree

function currencyvalue(value)
{
	//alert(this.value);
	//alert($("#default_curr option:selected").text();
	var index = document.getElementById('default_curr').selectedIndex;
	var opt = document.getElementById('default_curr').options;
	var currvalue = opt[index].text;
	//alert(currvalue);
	document.getElementById('currval').value = currvalue;
}
</script> 
<!-- Close : validation script -->

<?php
} else { 
	include_once(DIR_ADMIN.'access_denied.php');
}
?>