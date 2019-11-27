<?php
include("mode_setting.php");
//permission 
include('model/permission.php');
$obj_permission = new permission;

//edit user
$edit = '';
$user_id = '';
if(isset($_GET['associate_id']) && !empty($_GET['associate_id'])){
	$associate_id = decode($_GET['associate_id']);
	$user = $obj_permission->getUserData(5,$associate_id,'associate','associate_id');
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
	$insert_id = $obj_permission->addPermission(5,$user['associate_id'],$post);
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
      <div class="col-sm-8">
      	
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
              
              <div class="form-group">
                <label class="col-lg-3 control-label">View Permission</label>
                <div class="col-lg-8">
                	<div class="form-control scrollbar scroll-y" style="height:200px">
                        <?php
						 $selected_view = array();								  
						 if(isset($user['view_permission']) && !empty($user['view_permission'])){
							 $selected_view = unserialize($user['view_permission']);
						 }
						 //echo $sel_add;die;
                        foreach($menu_data as $menu){
							echo '<div class="checkbox">';
							echo '	<label class="checkbox-custom">';
							if(in_array($menu['admin_menu_id'],$selected_view)){
								echo '	<input type="checkbox" checked="checked" name="view[]" id="'.$menu['admin_menu_id'].'" value="'.$menu['admin_menu_id'].'"> ';
							}else{
								echo '	<input type="checkbox" name="view[]" id="'.$menu['admin_menu_id'].'" value="'.$menu['admin_menu_id'].'"> ';
							}
							echo '	<i class="fa fa-square-o"></i> '.$menu['name'].' </label>';
							echo '</div>';
						}
						?>
                    </div>
                    <a class="btn btn-default btn-xs selectall mt5" href="javascript:void(0);">Select All</a>
                    <a class="btn btn-default btn-xs unselectall mt5" href="javascript:void(0);">Unselect All</a> 
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Add Permission</label>
                <div class="col-lg-8">
                	<div class="form-control scrollbar scroll-y" style="height:200px">
                        <?php
						 $selected_add = array();								  
						 if(isset($user['add_permission']) && !empty($user['add_permission'])){
							 $selected_add = unserialize($user['add_permission']);
						 }						  
                        foreach($menu_data as $menu){
							echo '<div class="checkbox">';
							echo '	<label class="checkbox-custom">';
							if(in_array($menu['admin_menu_id'],$selected_add)){
								echo '	<input type="checkbox" checked="checked" name="add[]" id="'.$menu['admin_menu_id'].'" value="'.$menu['admin_menu_id'].'"> ';
							}else{
								echo '	<input type="checkbox" name="add[]" id="'.$menu['admin_menu_id'].'" value="'.$menu['admin_menu_id'].'"> ';
							}
							echo '	<i class="fa fa-square-o"></i> '.$menu['name'].' </label>';
							echo '</div>';
							//echo $menu['name'].'<input type="checkbox" >'; 
						}
						?>
                    </div>
                    <a class="btn btn-default btn-xs selectall mt5" href="javascript:void(0);">Select All</a>
                    <a class="btn btn-default btn-xs unselectall mt5" href="javascript:void(0);">Unselect All</a> 
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Edit Permission</label>
                <div class="col-lg-8">
                	<div class="form-control scrollbar scroll-y" style="height:200px">
                        <?php
						$selected_edit = array();								  
						 if(isset($user['edit_permission']) && !empty($user['edit_permission'])){
							 $selected_edit = unserialize($user['edit_permission']);
						 }						  
                        foreach($menu_data as $menu){
							echo '<div class="checkbox">';
							echo '	<label class="checkbox-custom">';
							if(in_array($menu['admin_menu_id'],$selected_edit)){
								echo '	<input type="checkbox" checked="checked" name="edit[]" id="'.$menu['admin_menu_id'].'" value="'.$menu['admin_menu_id'].'"> ';
							}else{
								echo '	<input type="checkbox" name="edit[]" id="'.$menu['admin_menu_id'].'" value="'.$menu['admin_menu_id'].'"> ';
							}
							echo '	<i class="fa fa-square-o"></i> '.$menu['name'].' </label>';
							echo '</div>';
							//echo $menu['name'].'<input type="checkbox" >'; 
						}
						?>
                    </div>
                    <a class="btn btn-default btn-xs selectall mt5" href="javascript:void(0);">Select All</a>
                    <a class="btn btn-default btn-xs unselectall mt5" href="javascript:void(0);">Unselect All</a>    
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Delete Permission</label>
                <div class="col-lg-8">
                	<div class="form-control scrollbar scroll-y" style="height:200px">
                        <?php
						 $selected_edit = array();								  
						 if(isset($user['delete_permission']) && !empty($user['delete_permission'])){
							 $selected_edit = unserialize($user['delete_permission']);
						 }
                        foreach($menu_data as $menu){
							echo '<div class="checkbox">';
							echo '	<label class="checkbox-custom">';
							if(in_array($menu['admin_menu_id'],$selected_edit)){
								echo '	<input type="checkbox" checked="checked" name="delete[]" id="'.$menu['admin_menu_id'].'" value="'.$menu['admin_menu_id'].'"> ';
							}else{
								echo '	<input type="checkbox" name="delete[]" id="'.$menu['admin_menu_id'].'" value="'.$menu['admin_menu_id'].'"> ';
							}
							echo '	<i class="fa fa-square-o"></i> '.$menu['name'].' </label>';
							echo '</div>';
						}
						?>
                    </div>
                    <a class="btn btn-default btn-xs selectall mt5" href="javascript:void(0);">Select All</a>
                    <a class="btn btn-default btn-xs unselectall mt5" href="javascript:void(0);">Unselect All</a>   
                </div>
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
<script type="text/javascript">
/*$(".selectall").click(function(){
	alert("sdsadasd");
	$(this).parent().children().find('fa fa-square-o').addClass('fa fa-square-o checked');
});*/
</script>
<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>