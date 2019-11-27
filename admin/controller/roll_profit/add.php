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
if(isset($_GET['profit_id']) && !empty($_GET['profit_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$profit_id = base64_decode($_GET['profit_id']);
		$profit = $obj_profit->getRollProfit($profit_id);
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
		$insert_id = $obj_profit->addRollProfit($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		//printr($post);die;
		$profit_id = $profit['product_roll_profit_id'];
		$obj_profit->updateRollProfit($profit_id,$post);
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
                <label class="col-lg-3 control-label">From Kg </label>
                <div class="col-lg-4">
                  	<input type="text" name="from_kg" value="<?php echo isset($profit['from_kg'])?$profit['from_kg']:'';?>" class="form-control validate[required,custom[onlyNumberSp]]">
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">To Kg </label>
                <div class="col-lg-4">
                  	<input type="text" name="to_kg" value="<?php echo isset($profit['to_kg'])?$profit['to_kg']:'';?>" class="form-control validate[required,custom[onlyNumberSp]]">
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Profit / Kg  </label>
                <div class="col-lg-4">
                  	<input type="text" name="profit_kg" value="<?php echo isset($profit['profit_kg'])?$profit['profit_kg']:'';?>" class="form-control validate[required,custom[number]]">
                </div>
              </div>
              
              <?php /* <div class="form-group">
                <label class="col-lg-3 control-label">Profit / Meter  </label>
                <div class="col-lg-4">
                  	<input type="text" name="profit_meter" value="<?php echo isset($profit['profit_meter'])?$profit['profit_meter']:'';?>" class="form-control validate[required,custom[number]]">
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Profit / Piece  </label>
                <div class="col-lg-4">
                  	<input type="text" name="profit_piece" value="<?php echo isset($profit['profit_piece'])?$profit['profit_piece']:'';?>" class="form-control validate[required,custom[number]]">
                </div>
              </div> */ ?>
              
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
    });
</script> 
<!-- Close : validation script -->

<?php } else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>