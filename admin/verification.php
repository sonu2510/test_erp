<?php
// Start: Building System
include_once("../ps-config.php");
// End: Building System
include('model/user.php');
$obj_user = new user;
?>

<?php include('common/header.php');?>
<section id="content">
  <div class="main padder">
    <div class="row">
      <div class="col-lg-4 col-lg-offset-4 m-t-large">
        <section class="panel">
          <header class="panel-heading text-center"> Verification </header>
          
          <div id="frm_verify" class="panel-body">
            <div class="block">
              <label class="control-label">Enter Verification Code</label>
              <input type="text" name="verification_textbox" id="verification-textbox" placeholder="Code" class="form-control validate[required]">
            </div>
            
            <div class="col-lg-12" style="margin-top:10px;">
            	<button type="button" style="width:100%;" name="btn_submit" id="verify-btn" class="btn btn-success">Submit</button>
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>
</section>


<!-- app --> <script src="<?php echo HTTP_SERVER;?>js/app.v2.js"></script>
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

<script>
    jQuery(document).ready(function(){
		// binds form submission and fields to the validation engine
        jQuery("#frm_verify").validationEngine();
    });
	
	$('#verify-btn').click(function(){
		$('#loading').show();
		//$('.alert-warning,.alert-success').remove();
		var verification_code = $('#verification-textbox').val();
		$.ajax({
			url : 'ajax/verification.php',
			type: 'post',
			data: {verification_code:verification_code},
			success: function(data){
				
				$('#loading').remove();
				if(data==1){
					//set_alert_message('',"alert-success","fa-check");
					window.location='reset.php';	
				}else{
					set_alert_message('Verification Code Doesn\'t Match',"alert-warning","fa-warning");	
				}
			}
			
		});
		
	});
	
</script> 
<!-- Close : validation script -->

<?php include('common/footer.php');?>