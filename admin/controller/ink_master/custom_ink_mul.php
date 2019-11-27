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
	'text' 	=> 'Custom Multiplier Detail',
	'href' 	=> '',
	'icon' 	=> 'fa-edit',
	'class'	=> 'active',
);
//Close : bradcums
$edit = '';
if(isset($_GET['ink_id']) && !empty($_GET['ink_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$ink_id = base64_decode($_GET['ink_id']);
		$ink = $obj_ink->getInk($ink_id);
		$edit = 1;
	}
	
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}
//Close : edit
/*//Start : edit
$edit = '';
if(isset($_GET['effect_id']) && !empty($_GET['effect_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$effect_id = base64_decode($_GET['effect_id']);
		$effect = $obj_printing->getEffect($effect_id);
		$edit = 1;
	}
	
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}
//Close : edit*/

if($display_status){
	//$getmake = $obj_ink->getmakeData();
	if(isset($_POST['btn_save'])){
		$post = post($_POST);		
		$insert_id = $obj_ink->AddCustMulValue($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	if(isset($_POST['btn_edit'])){
		$post = post($_POST);		
		$insert_id = $obj_ink->EditCustMulValue($post);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	if(isset($_POST['transfer'])){
		$insert_id = $obj_ink->transfer();	
	}
		
	$data_custom = $obj_ink->getCusotmMuldata();
	//printr($data_custom);
?>

<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-edit"></i> Custom Multiplier</h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div> 
        
      <div class="col-sm-8">
        <section class="panel">
          <header class="panel-heading bg-white"> Custom Multiplier Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Ink Multiply</label>
                <div class="col-lg-5">
                  	<input type="text" name="ink_mul" id="ink_mul" value="<?php echo isset($data_custom['ink_mul'])?$data_custom['ink_mul']:'';?>" class="form-control validate[required,custom[number],min[0.001]]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Adhesive Multiply</label>
                <div class="col-lg-5">
                  	<input type="text" name="adhesive_mul" id="adhesive_mul" value="<?php echo isset($data_custom['adhesive_mul'])?$data_custom['adhesive_mul']:'';?>" class="form-control validate[required,custom[number],min[0.001]]">
                </div>
              </div>
                            
             <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
               
                <!--  	<button type="submit" name="btn_update" id="btn_update" class="btn btn-primary">Update </button>-->
              	<?php if(isset($data_custom) && !empty($data_custom)){?>
                	<input type="hidden" name="custom_mul_id" id="custom_mul_id" value="<?php echo isset($data_custom['custom_mul_id'])?$data_custom['custom_mul_id']:'';?>" />
                  	<button type="submit" name="btn_edit" id="btn_edit" class="btn btn-primary">Edit </button>
                    
                    <button type="button" name="btn_reset" id="btn_reset" class="btn btn-bg-warning">Reset </button>
                    
                    
                  <!--<button type="submit" name="transfer" id="transfer" class="btn btn-primary">Transfer </button>-->
                    
                 <?php } else {?>
                    <button type="submit" name="btn_save" id="btn_save" class="btn btn-primary">Save </button>
                 <?php } ?>
                 
                 	
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

<script>
	jQuery(document).ready(function(){
		 jQuery("#form").validationEngine();
		
	});
	
	 $('#btn_reset').click(function (event){
		 
		 $("#ink_mul").val('1');
		 $("#adhesive_mul").val('1');
		 var formData = $("#form").serialize();
		  var reset_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=reset_val', '', 1); ?>");
			$.ajax({
				type: "POST",
				url: reset_url,
				data: {formData: formData},
				success: function (response) {
					//alert(response);
					set_alert_message('Record has been reseted successfully','alert-success','fa fa-check');
					window.setTimeout(function(){location.reload()},500);	
					
				}
			});

	 });	 
</script>

<?php } else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>