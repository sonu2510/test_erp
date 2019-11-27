<?php
include("mode_setting.php");
//[kinjal]
//permission 
include('model/permission.php');
$obj_permission = new permission;

//edit user
$edit = '';
$user_id = '';
if(isset($_GET['branch_id']) && !empty($_GET['branch_id'])){
	$branch_id = base64_decode($_GET['branch_id']);
	$user = $obj_permission->getUserData(4,$branch_id,'international_branch','international_branch_id');
	//printr($user);
	if(!$user){
		$obj_session->data['warning'] = 'Error : User data not found !';
		page_redirect($obj_general->link($rout, '', '',1));
	}
	//printr($user);die;
}
//Start : insert
if(isset($_POST['btn_save'])){
	$post = post($_POST);
	//printr($post);die;
	$insert_id = $obj_permission->addPermission(4,$user['international_branch_id'],$post);
	$obj_session->data['success'] = ADD;
	page_redirect($obj_general->link($rout, '', '',1));
}

if($display_status){
	$menu_data = $obj_permission->getMenuData();

?>
  
<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-user"></i> <?php echo $display_name;?> Permission</h4>
    </div>
    <div class="row">
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
                <?php foreach($menu_data as $menu){ ?>
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
                  <?php  $n++;}?>
              </table>
              </div>
              <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                	<button type="submit" name="btn_save" id="btn_save" class="btn btn-primary">Save </button>	
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
<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>