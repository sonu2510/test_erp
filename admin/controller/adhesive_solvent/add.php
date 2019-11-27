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
if(isset($_GET['solvent_id']) && !empty($_GET['solvent_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$solvent_id = base64_decode($_GET['solvent_id']);
		$solvent = $obj_adhesive->getAdhesiveSolvent($solvent_id);
		$edit = 1;
	}
	
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}
//Close : edit

if($display_status){
	$getmake = $obj_adhesive->getmakeData();
	//insert
	if(isset($_POST['btn_save'])){
		$post = post($_POST);		
		$insert_id = $obj_adhesive->addAdhesiveSolvent($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		//printr($post);die;
		$solvent_id = $solvent['adhesive_solvent_id'];
		$obj_adhesive->updateAdhesiveSolvent($solvent_id,$post);
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
                <label class="col-lg-3 control-label">Adhesive Solvent Price</label>
                <div class="col-lg-8">
                  	<input type="text" name="price" id="price" value="<?php echo isset($solvent['price'])?$solvent['price']:'';?>" class="form-control validate[required,custom[number]]">
                </div>
              </div>
              <!--ruchi-->
                <div class="form-group">
                <label class="col-lg-3 control-label">Product Make </label>
                <div class="col-lg-8" >
                        <?php 
                         $selected_make = array();		
                         if(isset($solvent['make_id']) && !empty($solvent['make_id'])){
                             $selected_make = $solvent['make_id'];
                         }
                        if(isset($getmake) && !empty($getmake)){ 
                            echo '<select name="make" id="make" class="form-control validate[required]" style="width:70%">';
                                    echo '<option value="">Select Product Make</option>';
                                    foreach($getmake as $makes){
                                            if($makes['make_id'] == $selected_make ){
                                                echo  '<option value="'.$makes['make_id'].'" selected="selected">'.$makes['make_name'].'</option>';
                                            }else{
                                                echo '<option value="'.$makes['make_id'].'" >'.$makes['make_name'].'</option>';
                                            }
                                    }
                            echo '</select>';								
                        }
                        ?>
                    </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Adhesive Solvent Unit</label>
                <div class="col-lg-8">
                  	<input type="text" name="unit" id="unit" value="<?php echo isset($solvent['adhesive_solvent_unit'])?$solvent['adhesive_solvent_unit']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Minimum Product Quantity</label>
                <div class="col-lg-8">
                  	<input type="text" name="min_qty" id="unit" value="<?php echo isset($solvent['adhesive_solvent_min_qty'])?$solvent['adhesive_solvent_min_qty']:'';?>" class="form-control validate[required,custom[number]]">
                </div>
              </div>
              <!--ruchi-->
             <div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                <div class="col-lg-4">
                  <select name="status" id="status" class="form-control">
                    <option value="1" <?php echo (isset($solvent['status']) && $solvent['status'] == 1)?'selected':'';?> > Active</option>
                    <option value="0" <?php echo (isset($solvent['status']) && $solvent['status'] == 0)?'selected':'';?>> Inactive</option>
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

<script>
    jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#form").validationEngine();
    });
</script> 
<!-- Close : validation script -->

<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>