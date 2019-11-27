<?php
include("mode_setting.php");
$obj_department = new department;

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
	'href' 	=> $obj_general->link('staff_group', '', '',1),
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
if(isset($_GET['staffgroup_id']) && !empty($_GET['staffgroup_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$staffgroup_id = base64_decode($_GET['staffgroup_id']);
		$staffgroup = $obj_department->getGroup($staffgroup_id);
		//printr($staffgroup);
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
	$insert_id = $obj_department->addStaffGroup($post);
	$_SESSION['success'] = ADD;
	page_redirect($obj_general->link($rout, '', '',1));
}

//edit
if(isset($_POST['btn_update']) && $edit){
	$post = post($_POST);
	$staff_group_id = $staffgroup['staff_group_id'];
	$obj_department->updateGroup($staff_group_id,$post);
	$_SESSION['success'] = UPDATE;
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
            <form class="form-horizontal" name="form" id="form" method="post" enctype="multipart/form-data">
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Group Name</label>
                <div class="col-lg-8">
                  <input type="text" name="name" value="<?php echo isset($staffgroup['staff_group_name'])?$staffgroup['staff_group_name']:'';?>" placeholder="Group Name" class="form-control validate[required]">
                </div>
              </div>            
              <div class="form-group"> <label class="col-lg-3 control-label">Address</label>
				<div class="col-lg-6">
				<textarea placeholder="Address" name="staff_group_addr" rows="5" class="form-control " data-trigger="keyup" data-rangelength="[0,200]"><?php echo isset($staffgroup['staff_group_addr'])?$staffgroup['staff_group_addr']:'';?></textarea> 
				
				</div> 
				</div>
				 <div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                <div class="col-lg-4">
                  <select name="status" id="status" class="form-control">
                    <option value="1" <?php echo (isset($staffgroup['status']) && $staffgroup['status'] == 1)?'selected':'';?> > Active</option>
                    <option value="0" <?php echo (isset($staffgroup['status']) && $staffgroup['status'] == 0)?'selected':'';?>> Inactive</option>
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

<?php
} else { 
	include_once(DIR_ADMIN.'access_denied.php');
}
?>