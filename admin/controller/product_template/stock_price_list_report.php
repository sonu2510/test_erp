<?php
include("mode_setting.php");

$bradcums = array();
$bradcums[] = array(
	'text' 	=> 'Dashboard',
	'href' 	=> $obj_general->link('dashboard', '', '',1),
	'icon' 	=> 'fa-home',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> $display_name.' List',
	'href' 	=> '',
	'icon' 	=> 'fa-list',
	'class'	=> 'active',
);

$limit = LISTING_LIMIT;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}

if(isset($_GET['order'])){
	$sort_order = $_GET['order']; 
}else{
	$sort_order = 'ASC';
}

if($display_status) {
$addedByInfo=array();
?>

<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-list"></i> <?php echo $display_name;?></h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>   
        
      <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading bg-white"> 
		  	<span><?php echo $display_name;?> Listing</span>
          	<span class="text-muted m-l-small pull-right">
            </span>
          </header>

          <div class="panel-body"></div>
			 <form class="form-horizontal" method="post" name="frm_add" id="frm_add" enctype="multipart/form-data" action=" <?php echo $obj_general->link($rout, 'mod=view_report', '',1) ?>">
              
            <?php if($_SESSION['LOGIN_USER_TYPE']==1 && $_SESSION['ADMIN_LOGIN_SWISS']==1) { ?>
                      <div class="form-group">
                        <label class="col-lg-3 control-label">User</label>
                        <div class="col-lg-3">
                        <?php $userlist = $obj_template->getEmpList();?>
        					<select class="form-control" name="user_name" >
                                <option value="">Select User</option>
                                <?php foreach($userlist as $user) { ?>
                                        <option value="<?php echo $user['user_id']; ?>"><?php echo $user['user_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                      </div>
            <?php } 
                  else
                  {
                      echo '<input type="hidden" name="user_name" value="'.$_SESSION['ADMIN_LOGIN_SWISS'].'"> ';
                      $addedByInfo = $obj_template->getUser($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
                  } 
                  if(isset($addedByInfo['country_id']) && $addedByInfo['country_id']){
					$selCountry = $addedByInfo['country_id'];
				  }
                  ?>
                  
                 <div class="form-group">
                    <label class="col-lg-3 control-label">Country</label>
                    <div class="col-lg-3">
                    <?php $country = $obj_template->getCountry();?>
    					<select class="form-control" name="country" >
                            <option value="">Select Country</option>
                            <?php foreach($country as $con) { 
                                    if(isset($addedByInfo['country_id']) && $addedByInfo['country_id'] == $con['country_id']) { ?>
                                        <option value="<?php echo $con['country_id']; ?>" selected><?php echo $con['country_name']; ?></option>
                            <?php   } else{ ?>
                                        <option value="<?php echo $con['country_id']; ?>"><?php echo $con['country_name']; ?></option>
                            <?php }
                            } ?>
                        </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-lg-3 control-label">Report Of</label>
                    <div class="col-lg-3">
    					<select class="form-control" name="report" >
                            <option value="0">By Sea - By Air</option>
                            <option value="By Sea">By Sea</option>
                            <option value="By Air">By Air</option>
                            <option value="By Pickup">By Pickup</option>
                        </select>
                    </div>
                  </div>
              
              <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                	<button type="submit" name="btn_pro" id="btn_pro" class="btn btn-primary">Proceed</button>	
                </div>
              </div>
              
              
            </form>
          
          
          <footer class="panel-footer">
            <div class="row">
              <div class="col-sm-3 hidden-xs"> </div>
             
            </div>
          </footer>
        </section>
      </div>
    </div>
  </section>
</section>
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

<script>
jQuery(document).ready(function(){
	jQuery("#frm_add").validationEngine();
});


</script>

<?php 
 } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>       