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
	'href' 	=> $obj_general->link('department', '', '',1),
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

if(!$obj_general->hasPermission('add',$menuId)){
	$display_status = false;
}

if($display_status){

//insert
if(isset($_POST['btn_save'])){
	$post = post($_POST);
	
	if(isset($post['database_backup']) || isset($post['file_backup'])){
		$insert_id = $obj_backup->addBackup($post,$obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE']);
		$_SESSION['success'] = ADD;
	}
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
              	<div class="col-lg-3"></div>
                <div class="col-lg-8">  
                    <div class="checkbox">
                      <label class="checkbox-custom">
                        <input type="checkbox" id="2" name="database_backup">
                        <i class="fa fa-square-o"></i>Database Backup?</label>
                    </div>                                 
                </div>
              </div>
              
              <div class="form-group">
                <div class="col-lg-3"></div>
                <div class="col-lg-8">
                	<div class="checkbox">
                      <label class="checkbox-custom">
                        <input type="checkbox" id="2" name="file_backup">
                        <i class="fa fa-square-o"></i>Files Backup?</label>
                    </div>                                       
                </div>
              </div>
              
              <?php /* <div class="form-group">
                <label class="col-lg-3 control-label">View Permission</label>
                <div class="col-lg-8">
                  <div class="m-b">
                  	<select name="view[]" id="viewid" class="form-control" multiple data-rel="chosen">
                    	<?php echo $menu_options = $obj_department->menu_combo();?>
                    </select>
                  </div>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Add Permission</label>
                <div class="col-lg-8">
                
	                <select name="add[]" id="addid" class="form-control" multiple data-rel="chosen">
                    	<?php echo $menu_options = $obj_department->menu_combo();?>
                    </select>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Edit Permission</label>
                <div class="col-lg-8">
                  	<select name="edit[]" id="editid" class="form-control" multiple data-rel="chosen">
                    	<?php echo $menu_options = $obj_department->menu_combo();?>
                    </select>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Delete Permission</label>
                <div class="col-lg-8">
                	<select name="delete[]" id="deleteid" class="form-control" multiple data-rel="chosen">
                    	<?php echo $menu_options = $obj_department->menu_combo();?>
                    </select>
                </div>
              </div> */ ?>              
              <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                	<button type="submit" name="btn_save" id="btn_save" class="btn btn-primary">Take Backup </button>	
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