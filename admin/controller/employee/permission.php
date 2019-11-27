<?php
//[kinjal]
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
	'text' 	=> 'Employee List',
	'href' 	=> $obj_general->link('employee', $queryString, '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> 'Employee Permission',
	'href' 	=> '',
	'icon' 	=> 'fa-edit',
	'class'	=> 'active',
);
//Close : bradcums

include("mode_setting.php");
//permission 
include('model/permission.php');
$obj_permission = new permission;

//edit user

$edit = '';
if(isset($_GET['employee_id']) && !empty($_GET['employee_id'])){
	$employee_id = base64_decode($_GET['employee_id']);
	$user = $obj_permission->getUserData(2,$employee_id,'employee','employee_id');
	if(!$user){
		$obj_session->data['warning'] = 'Error : User data not found !';
		page_redirect($obj_general->link($rout, $queryString, '',1));
	}
}
if($display_status){
	
	$employeesUserTypeId = $user['user_type_id'];
	$employeeUserId = $user['user_id'];
	
	if($employeesUserTypeId == 1 && $employeeUserId == 1){
		$selColum = 'admin_menu_id,name';
		$getdata = $obj_permission->getMenuData($selColum);
		$menu_data['perMenu'] = $getdata;
	}else{
		$menu_data['perMenu'] =  $obj_permission->getUserAssignPermissionMenu($employeesUserTypeId,$employeeUserId);
	}
	//Start : insert
	if(isset($_POST['btn_save'])){
		$post = post($_POST);
		$setdata = array();
		if(isset($post['add']) && !empty($post['add']) && isset($menu_data['perMenu']) && !empty($menu_data['perMenu'])){
			$addMenuIds = array_column($menu_data['perMenu'], 'admin_menu_id');
			$setdata['add'] = array_intersect($post['add'],$addMenuIds);
		}else{
			$setdata['add'] = array();
		}
		if(isset($post['edit']) && !empty($post['edit']) && isset($menu_data['perMenu']) && !empty($menu_data['perMenu'])){
			$editMenuIds = array_column($menu_data['perMenu'], 'admin_menu_id');
			$setdata['edit'] = array_intersect($post['edit'],$editMenuIds);
		}else{
			$setdata['edit'] = array();
		}
		if(isset($post['view']) && !empty($post['view']) && isset($menu_data['perMenu']) && !empty($menu_data['perMenu'])){
			$viewMenuIds = array_column($menu_data['perMenu'], 'admin_menu_id');
			$setdata['view'] = array_intersect($post['view'],$viewMenuIds);
		}else{
			$setdata['view'] = array();
		}
		if(isset($post['delete']) && !empty($post['delete']) && isset($menu_data['perMenu']) && !empty($menu_data['perMenu'])){
			$deleteMenuIds = array_column($menu_data['perMenu'], 'admin_menu_id');
			$setdata['delete'] = array_intersect($post['delete'],$deleteMenuIds);
		}else{
			$setdata['delete'] = array();
		}
		$insert_id = $obj_permission->addPermission(2,$user['employee_id'],$setdata);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, $queryString, '',1));
	}
?>
    
<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-user"></i> <?php echo $display_name;?> Permission</h4>
    </div>
    <div class="row">
    	
        <div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div> 
        
      <div class="col-sm-12">
      	
        <section class="panel">
          <header class="panel-heading bg-white"> <?php echo $display_name;?> Permission Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="frm_permission" data-validate="parsley" enctype="multipart/form-data">
              
              <div class="form-group">
                <label class="col-lg-3 control-label"></label>
                <div class="col-lg-8">
                	<label>
                      	<?php echo $user['first_name'].' '.$user['last_name'];?>
                    </label>
                </div>
              </div>
              
              <table border="0"  width="100%" class="table  b-t text-small">
              	<tr>
                	<td style=" width:25%"> <b>Model</b> </td>
                	<td style="width:15%"> <b>View Permission</b> </td>
                   	<td style="width:15%"><b>Add Permission</b></td>
                   	<td style="width:15%"><b>Edit Permission</b></td>
                    <td style="width:15%"><b>Delete Permission</b></td>
                   	<td style="width:15%"><b>Select All / Unselect All</b></td>
                </tr>
              </table>
              <div style=" overflow: auto; height:500px">
                <table border="0"  width="100%" class="table  b-t text-small">
                <?php
					$selected_add = array();								  
					 if(isset($user['add_permission']) && !empty($user['add_permission'])){
						 $selected_add = unserialize($user['add_permission']);
					 } 
					 $selected_view = array();								  
					 if(isset($user['view_permission']) && !empty($user['view_permission'])){
						 $selected_view = unserialize($user['view_permission']);
					 }
					 $selected_edit = array();								  
					 if(isset($user['edit_permission']) && !empty($user['edit_permission'])){
						 $selected_edit = unserialize($user['edit_permission']);
					 }
					 $selected_delete = array();								  
					 if(isset($user['delete_permission']) && !empty($user['delete_permission'])){
						 $selected_delete = unserialize($user['delete_permission']);
					 }
					 $n=1;
				?>
                <?php if(isset($menu_data['perMenu']) && !empty($menu_data['perMenu'])){
							foreach($menu_data['perMenu'] as $menu){ ?>
                                <tr>
                                    <td style=" width:30%"><b><?php echo $menu['name']; ?></b></td>
                                    
                                    <td style="width:15%"><input type="checkbox" name="view[]" id="<?php echo $menu['admin_menu_id']; ?>" value="<?php echo $menu['admin_menu_id']; ?>" class="check_<?php echo $n; ?>" <?php if(in_array($menu['admin_menu_id'],$selected_view)){ echo 'checked = "checked"'; } ?>> 
                                     </td>
                                     
                                     <td style="width:15%"><input type="checkbox" name="add[]" id="<?php echo $menu['admin_menu_id']; ?>" value="<?php echo $menu['admin_menu_id']; ?>" class="check_<?php echo $n; ?>" <?php if(in_array($menu['admin_menu_id'],$selected_add)){ echo 'checked = "checked"'; } ?>> 
                                     </td>
                                     
                                     <td style="width:15%"><input type="checkbox" name="edit[]" id="<?php echo $menu['admin_menu_id']; ?>" value="<?php echo $menu['admin_menu_id']; ?>" class="check_<?php echo $n; ?>" <?php if(in_array($menu['admin_menu_id'],$selected_edit)){ echo 'checked = "checked"'; } ?>> 
                                     </td>
                                     
                                     <td style="width:15%"><input type="checkbox" name="delete[]" id="<?php echo $menu['admin_menu_id']; ?>" value="<?php echo $menu['admin_menu_id']; ?>" class="check_<?php echo $n; ?>" <?php if(in_array($menu['admin_menu_id'],$selected_delete)){ echo 'checked = "checked"'; } ?>> 
                                     </td>
                                     
                                     <td style="width:15%"><center><input type="checkbox" onclick="selecctall(<?php echo $n;?>)" id="selectall_<?php echo $n; ?>" name="check" <?php if (in_array($menu['admin_menu_id'],$selected_delete) && in_array($menu['admin_menu_id'],$selected_edit) && in_array($menu['admin_menu_id'],$selected_add) && in_array($menu['admin_menu_id'],$selected_view)) { echo 'checked = "checked"'; } ?>></center>
                                     </td>
                      
                                    </tr>
						  <?php  $n++;} }?>
              </table>
              </div>
              <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                	<button type="submit" name="btn_save" id="btn_save" class="btn btn-primary">Save </button>	
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
<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>