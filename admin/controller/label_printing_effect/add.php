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
//Close : edit

if($display_status){
	//insert user
	if(isset($_POST['btn_save'])){
		$post = post($_POST);		
		//printr($post);die;
		$insert_id = $obj_printing->addEffect($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		//printr($post);die;
		$effect_id = $effect['printing_effect_id'];
		$obj_printing->updateEffect($effect_id,$post);
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
                <label class="col-lg-3 control-label">Effect Name</label>
                <div class="col-lg-8">
                  	<input type="text" name="name" id="name" value="<?php echo isset($effect['effect_name'])?$effect['effect_name']:'';?>" class="form-control validate[required]">
                </div>
              </div>
             
               <div class="form-group">
                <label class="col-lg-3 control-label">Price</label>
                <div class="col-lg-8">
                  	<input type="text" name="price" value="<?php echo isset($effect['price'])?$effect['price']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              <!--[kinjal] : added on 11-4-2017 for custom multi quo.--> 
               <div class="form-group">
                <label class="col-lg-3 control-label">Multiply By</label>
                <div class="col-lg-8">
                  	<input type="text" name="multi_by" value="<?php echo isset($effect['multi_by'])?$effect['multi_by']:'';?>" class="form-control validate[required]">
                </div>
              </div>
            <!-- [kinjal] : END-->
            <!--[kinjal] : added on 24-9-2019.--> 
                <div class="form-group">
                <label class="col-lg-3 control-label">Remarks</label>
                <div class="col-lg-8">
                  	<input type="text" name="remarks" value="<?php echo isset($effect['remarks'])?$effect['remarks']:'';?>" class="form-control">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Make of pouch</label>
                <div class="col-lg-3">
                  	<?php $getmake=$obj_printing->getMake();
                  	       $make_pouch =array();
                  	      if(isset($effect['make_pouch'])&& !empty($effect['make_pouch']) ){
        					    $make_pouch = explode(',',$effect['make_pouch']);
        				   }
                  	        foreach($getmake as $make){?>
                              	<div class="checkbox">
                                  <label class="checkbox-custom">
                                    <input type="checkbox" name="make_pouch[]" value="<?php echo $make['make_id'];?>" <?php echo (in_array($make['make_id'],$make_pouch))?'checked="checked"':'';?> class="validate[minCheckbox[1]]">
                                    <i class="fa fa-square-o"></i><?php echo $make['make_name'];?></label>
                                </div>
                        <?php } ?>
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

<script>
    jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#form").validationEngine();
		$("#name").blur(function(){
			var name = $(this).val();
			if(name.length > 0){
				$.ajax({
					type: "POST",
					url: '<?php echo HTTP_ADMIN;?>ajax/uniqMaterialName.php',
					dataType: 'json',
					data:'name='+name,
					success: function(json) {
						if(json > 0){
							$("#name").after('<span class="alert-danger"> Name already exists! </span>');
							return false;
						}
					}
				});
			}
		});
    });
</script> 
<!-- Close : validation script -->

<?php } else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>