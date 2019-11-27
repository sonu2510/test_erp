<?php
include("mode_setting.php");
$obj_category = new category();

//insert user
if(isset($_POST['btn_save'])){
	$post = post($_POST);
	//printr($post);die;
	$insert_id = $obj_category->addCategory($post);
	$_SESSION['success'] = ADD;
	page_redirect($obj_general->link($rout, '', '',1));
}

//edit user
$edit = '';
if(isset($_GET['user_id']) && !empty($_GET['user_id'])){
	$user_id = base64_decode($_GET['user_id']);
	$user = $obj_user->getUser($user_id);
	
	$edit = 1;
}
if(isset($_POST['btn_update']) && $edit){
	$post = post($_POST);
	$user_id = $user['user_id'];
	$insert_id = $obj_user->updateUser($user_id,$post);
	$_SESSION['success'] = UPDATE;
	page_redirect('index.php?rout='.$rout);
}

if($display_status){
?>
<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-user"></i> Category</h4>
    </div>
    <div class="row">
      <div class="col-sm-8">
        <section class="panel">
          <header class="panel-heading bg-white"> Category Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" name="frm_category" method="post" data-validate="parsley" enctype="multipart/form-data">
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Category Name</label>
                <div class="col-lg-8">
                  <input type="text" name="name" placeholder="Name" data-required="true" value="<?php echo isset($user['first_name'])?$user['first_name']:'';?>" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                <div class="col-lg-4">
                  <select name="status" id="status" class="form-control">
                    <option value="1" <?php echo (isset($user['status']) && $user['status'] == 1)?'selected':'';?> > Active</option>
                    <option value="0" <?php echo (isset($user['status']) && $user['status'] == 0)?'selected':'';?>> Inactive</option>
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
<?php } else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>