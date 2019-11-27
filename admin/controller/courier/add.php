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
if(isset($_GET['courier_id']) && !empty($_GET['courier_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$courier_id = decode($_GET['courier_id']);
		$courier = $obj_courier->getCourier($courier_id);
		//printr($client);die;
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
	$insert_id = $obj_courier->addCourier($post);
	$_SESSION['success'] = ADD;
	page_redirect($obj_general->link($rout, '', '',1));
	
}

//edit
if(isset($_POST['btn_update']) && $edit){
	$post = post($_POST);
	$courier_id = $courier['courier_id'];
	$obj_courier->updateCourier($courier_id,$post);
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
                <label class="col-lg-3 control-label"><span class="required">*</span>Courier Name</label>
                <div class="col-lg-8">
                  <input type="text" name="courier_name" value="<?php echo isset($courier['courier_name'])?$courier['courier_name']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Contact Person Name</label>
                <div class="col-lg-8">
                  <input type="text" name="contact_person" value="<?php echo isset($courier['contact_person'])?$courier['contact_person']:'';?>" class="form-control">
                </div>
              </div>
              
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Contact Email</label>
                <div class="col-lg-8">
                  <input type="text" name="email" value="<?php echo isset($courier['email'])?$courier['email']:'';?>" class="form-control validate[custom[email]]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Contact Telephone</label>
                <div class="col-lg-8">
                  <input type="text" name="telephone" value="<?php echo isset($courier['telephone'])?$courier['telephone']:'';?>" class="form-control validate[minSize[8],maxSize[15]]">
                  
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Fuel Surcharge (%)</label>
                <div class="col-lg-8">
                  	<input type="text" name="fuel_surcharge" value="<?php echo isset($courier['fuel_surcharge'])?$courier['fuel_surcharge']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Service Tax (%)</label>
                <div class="col-lg-8">
                  <input type="text" name="service_tax" value="<?php echo isset($courier['service_tax'])?$courier['service_tax']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Handling Charge (Rs.)</label>
                <div class="col-lg-8">
                  <input type="text" name="handling_charge" value="<?php echo isset($courier['handling_charge'])?$courier['handling_charge']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <?php /* <div class="form-group">
                <label class="col-lg-3 control-label">Zone</label>
                <?php /* <div class="col-lg-7">
                  <input type="text" name="zone[]" value="" class="form-control">
                </div>  ?>
                <div class="col-lg-2">
                    <a class="btn btn-success btn-xs addmore"><i class="fa fa-plus"></i> Add Zone</a>
                </div>
              </div>
              <?php
			  if($edit){
				  $courier_zones = $obj_courier->getCourierZone($courier['courier_id']);
				  //printr($courier_zones);die;
				  if($courier_zones && !empty($courier_zones)){
					  $count = 1;
					  foreach($courier_zones as $courier_zone){
						  echo '<div class="form-group">';
						  	echo '<label class="col-lg-3 control-label"></label>';
							echo '<div class="col-lg-7">';
								echo '<input type="text" name="zone[]" value="'.$courier_zone['zone'].'" class="form-control" id="zone_'.$count.'">';
							echo '</div>';
							echo '<div class="col-lg-2">';
								echo '<a class="btn btn-warning btn-xs btn-circle removetzone" id="'.$count.'"><i class="fa fa-minus"></i></a>';
							echo '</div>';
						  echo '</div>';
						  $count++;
					  }
				  }
			  } ?>    
              <div id="append_zone"></div> */ ?>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                <div class="col-lg-4">
                  <select name="status" id="status" class="form-control">
                    <option value="1" <?php echo (isset($courier['status']) && $courier['status'] == 1)?'selected':'';?> > Active</option>
                    <option value="0" <?php echo (isset($courier['status']) && $courier['status'] == 0)?'selected':'';?>> Inactive</option>
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

<!-- select2 <script src="<?php echo HTTP_SERVER;?>js/select2/select2.min.js"></script>-->
<script>
    jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#form").validationEngine();
    });
//Start : zone	
/*$(document).on('click', ".addmore", function () {
	more_zone();
});

$(document).on('click', ".removetzone", function () {
    var id = $(this).attr("id");
	$(this).parent().closest(".form-group").remove();
});
function more_zone(){
	
	var total_count = parseInt( $(".more_zone").size()) + 1;
	
	var html 	= '';
	html	+= '<div class="form-group more_zone" id="zone_main_'+total_count+'" >';
	html	+= '	<label class="col-lg-3 control-label"></label>';
	html	+= '	<div class="col-lg-7">';
	html	+= '		<input type="text" name="zone[]" value="" class="form-control" id="zone_'+total_count+'">';
	html	+= '	</div>';
	html	+= '	<div class="col-lg-2">';
	html	+= '		<a class="btn btn-warning btn-xs btn-circle removetzone" id="'+total_count+'"><i class="fa fa-minus"></i></a>';
	html	+= '	</div>';
	html	+= '</div>';
	$("#append_zone").append(html);
}*/
//Close : zone	
</script> 
<!-- Close : validation script -->

<?php
} else { 
	include_once(DIR_ADMIN.'access_denied.php');
}
?>