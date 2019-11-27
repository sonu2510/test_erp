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
$edit = '';

$limit = LISTING_LIMIT;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}

if($display_status){

	if(isset($_POST['btn_save'])){
		
		$post = post($_POST);	
		//printr($_FILES);
		$insert_id = $obj_dms->addTax($post,$_FILES['art_image']);
		$obj_session->data['success'] = ADD;
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
                <label class="col-lg-3 control-label"><span class="required">*</span>Title</label>
                <div class="col-lg-4">
                 <input type="text" name="title" id="title"  class="form-control validate[required]" value=" ">
                </div>
              </div> 
              
              <?php // for upload image and document ?>
              
              <div class="form-group">
					<label class="col-lg-3 control-label">Upload Document</label>
                       <div class="col-lg-9">
                        <div class="media-body">
                            <input type="file" name="art_image" id="art-image"  class="custom-file-input">
                         </div>
                         <br/>
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

<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>



<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

<script>

// for uploading document and images

 jQuery(document).ready(function(){
	 jQuery("#form").validationEngine();		
    });

jQuery(document).ready(function(){
	   jQuery("#form").validationEngine();
	   
	   $('#date').datepicker({format:'yyyy-mm-dd',}).on('changeDate',function(e){$(this).datepicker('hide');});
	   
	   
	    var nowTemp = new Date();
	    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
	    var checkin = $('#date').datepicker({
   			onRender: function(date) {
    		return date.valueOf() < now.valueOf() ? '' : '';
    		}
    	}).on('changeDate', function(ev) {
			var minu=ev.date.valueOf();
			var newDate = new Date(minu);
			var secDate = new Date(minu);
				newDate.setDate(newDate.getDate()-10);
				checkout.setValue(newDate);
				newDate.setDate(secDate.getDate()-3);
				lastcheckout.setValue(newDate);
				checkin.hide();
			
    	}).data('datepicker');
    	var checkout = $('#remainder_date').datepicker({
    		onRender: function(date) {
			}}).on('changeDate', function(ev) {
    		checkout.hide();
    	}).data('datepicker');
		
		var lastcheckout=$('#last_remainder_date').datepicker({
    		onRender: function(date) {
			}
    	}).on('changeDate', function(ev) {
    		lastcheckout.hide();
    	}).data('datepicker');
});

$(' .remove').click(function(){
	$(this).parent().parent().remove();
});
</script>
<?php 
 } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>




