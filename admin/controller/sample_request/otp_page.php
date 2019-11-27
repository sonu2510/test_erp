<?php
include("mode_setting.php");
//printr($_POST);//die;
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
if(isset($_GET['request_id']) && !empty($_GET['request_id'])){
	/*if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{*/
		$request_id = base64_decode($_GET['request_id']);
		$request= $obj_sample->getRequest($request_id);
		//printr($accessorie);die;
		$edit = 1;
	//}
	
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}
//Close : edit

if(isset($_GET['request_id']) && !empty($_GET['request_id']))
{
	$req_id = decode($_GET['request_id']);
	$req_data = $obj_sample->getRequest($req_id);
	//printr($req_data);
}
if($display_status){
	//insert user
	if(isset($_POST['submit_otp'])){
		//printr($_POST);die;
		$post = post($_POST);		
		$req_id = decode($_GET['request_id']);
		$admin_email = ADMIN_EMAIL;
		//printr($req_data);
		$insert_id = $obj_sample->updateRequestOTP($req_id,$_POST,$admin_email);//die;
		//$obj_session->data['success'] = ADD;
       //die;
       page_redirect($obj_general->link($rout, '', '',1));
	}
	
?>

<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-edit"></i> OTP FORM</h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div> 
        
      <div class="col-sm-8">
        <section class="panel">
          <header class="panel-heading bg-white"> OTP Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
              
              <div class="form-group">
				<center><label class="col-lg-3 control-label"></label><div class="col-lg-4">Enter OTP</div></center>
			  </div>
			  <div class="form-group">
				<!--<div class="tableheader">Enter OTP</div>-->
				
				<center><label class="col-lg-3 control-label"></label><div class="col-lg-4" style="color:#31ab00;">Check your email for the OTP</div></center>
				<!--<p style="color:#31ab00;">Check your email for the OTP</p>-->
				</div>	
				<div class="form-group">
					<center>
						<label class="col-lg-3 control-label"></label>
						<div class="col-lg-4">
							<input type="text" name="otp"  id="otp" placeholder="One Time Password" class="form-control validate[required]" required>
							 <span id="check"></span>
							 <input type="hidden" name="posted_otp" id="posted_otp" value="<?php echo isset($req_data) ? $req_data['sended_otp'] : '';?>">
						</div>
					</center>					
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label"></label>
						<div class="col-lg-4">
							<center><small style="color:red;">Note : Do not refresh and close this window, if you refresh and closing this window will your request is canceled.</small></center>
							<center><button type="submit" name="submit_otp" id="submit_otp" class="btn btn-primary">Submit OTP </button>
							<a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a></center>
							
						</div>
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
	$('#otp').change(function(){
	var otp = $("#otp").val();
	var posted_otp = $("#posted_otp").val();
	
	var ajax_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=checkOTP', '',1);?>");
	$.ajax({
					url : ajax_url,
					method : 'post',		
					data : {otp : otp,posted_otp:posted_otp},
					success: function(response){
						$("#check").show();
						if(response != 0)
						{	
							var msg = "Correct OTP Entered";
							$("#check").html('<span style="color:green">'+msg+'</span>');
							var url = "<?php echo $obj_general->link($rout, '', '',1);?>";
							window.setTimeout(function(){window.location = url;},1000);
						}
						else
						{
							var msg = "Wrong OTP Entered";
							$("#check").html('<span style="color:red">'+msg+'</span>');
							$("#otp").val('');
							return false;
						}
					},
					error: function(){
			
						return false;
					}
				});
});
</script> 
<!-- Close : validation script -->

<?php } else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>