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
if(isset($_GET['zipper_id']) && !empty($_GET['zipper_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$zipper_id = base64_decode($_GET['zipper_id']);
		$zipper = $obj_zipper->getZipper($zipper_id);
		$edit = 1;
		
	}
	
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}
//Close : edit

if($display_status){
	//insert user
	if(isset($_POST['btn_save'])){
		$post = post($_POST);		
		//printr($post);die;
		$insert_id = $obj_zipper->addZipper($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		//printr($post);die;
		$zipper_id = $zipper['product_zipper_id'];
		$obj_zipper->updateZipper($zipper_id,$post);
		$obj_session->data['success'] = UPDATE;
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
                <label class="col-lg-3 control-label"><span class="required">*</span> Name</label>
                <div class="col-lg-8">
                  <input type="text" name="name" value="<?php echo isset($zipper['zipper_name'])?$zipper['zipper_name']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Abbreviation</label>
                <div class="col-lg-8">
                  	<input type="text" name="abbr" id="abbr" value="<?php echo isset($zipper['zipper_abbr'])?$zipper['zipper_abbr']:'';?>" class="form-control validate[required]">
                </div>
              </div>

                <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Unit </label>
                <div class="col-lg-4">
                  	<input type="text" name="unit" value="<?php echo isset($zipper['zipper_unit'])?$zipper['zipper_unit']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Minimum Product Quantity</label>
                <div class="col-lg-4">
                  	<input type="text" name="min_qty" value="<?php echo isset($zipper['zipper_min_qty'])?$zipper['zipper_min_qty']:'';?>" class="form-control validate[required,custom[number]]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Price /cm (Rs) <br><small>Fromula : (W X Zip-price X 10) / 1000</small></label>
                <div class="col-lg-4">
                  	<input type="text" name="price" value="<?php echo isset($zipper['price'])?$zipper['price']:'';?>" class="form-control validate[required,custom[number]]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> wastage </label>
                <div class="col-lg-4">
                  	<input type="text" name="wastage" value="<?php echo isset($zipper['wastage'])?$zipper['wastage']:'';?>" class="form-control validate[required,custom[number]]">
                </div>
              </div>
              
                <div class="form-group">
                <label class="col-lg-3 control-label"> Weight(Kg) </label>
                <div class="col-lg-4">
                  	<input type="text" name="Weight" value="<?php echo isset($zipper['Weight'])?$zipper['Weight']:'';?>" class="form-control ">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Serial No </label>
                <div class="col-lg-4">
                  	<input type="text" name="serial_no" value="<?php echo isset($zipper['serial_no'])?$zipper['serial_no']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"> Slider Price </label>
                <div class="col-lg-4">
                  	<input type="text" name="slider_price" value="<?php echo isset($zipper['slider_price'])?$zipper['slider_price']:'';?>" class="form-control validate[custom[number]]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Remark </label>
                <div class="col-lg-6">
                   <textarea name="remark" id="remark" class="form-control validate[required]"><?php echo isset($zipper['remark'])?$zipper['remark']:'';?></textarea>
                </div> 
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                <div class="col-lg-4">
                  <select class="form-control" name="status">
                    <option value="1"> Active</option>
                    <option value="0" <?php echo (isset($zipper['status']) && $zipper['status']==0) ? 'selected="selected"' : '';?>> Inactive</option>
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

<script>
    jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#form").validationEngine();
    });
	
	$('#abb').change(function () {
			if (this.value.match(/[^a-zA-Z ]/g)) {
			//this.value = this.value.replace(/[^a-zA-Z]/g, ”);
			alert("Please Enter Only Characters");
			$('#abb').val("");
			}
	});
	
//Start : wastage	
/*$(document).on('click', ".wastageadd", function () {
	more_wastage();
});

$(document).on('click', ".wastageremove", function () {
    var id = $(this).attr("id");
	$(this).parent().closest(".form-group").remove();
});
function more_wastage(){
	var total_count = parseInt( $(".more_wastage").size()) + 1;
	//alert(total_count);
	$("#hdn_wastagecount").val(total_count);
	var html 	= '';
	html	+= '<div class="form-group more_wastage" id="wastage_main_'+total_count+'" >';
	html	+= '	<label class="col-lg-3 control-label"></label>';
	html	+= '	<div class="col-lg-8">';
	html	+= '		<div class="row">';
	html	+= '			<div class="col-sm-3">';
	html	+= '				<input type="text" name="form_wastage[]" id="form_wastage_'+total_count+'" value="" class="form-control validate[required,custom[onlyNumberSp]]">';
	html	+= '			</div>';
	html	+= '			<div class="col-lg-3">';
	html	+= '				<input type="text" name="to_wastage[]" id="to_wastage_'+total_count+'" value="" class="form-control validate[required,custom[onlyNumberSp]]">';
	html	+= '			</div>';
	html	+= '			<div class="col-lg-3">';
	html	+= '				<input type="text" name="wastage[]" id="wastage_'+total_count+'" value="" class="form-control validate[required,custom[onlyNumberSp]]">';
	html	+= '			</div>';
	html	+= '			<div class="col-lg-3">';
	html	+= '				<a class="btn btn-danger btn-circle btn-xs wastageremove" id="'+total_count+'" ><i class="fa fa-minus"></i></a>';
	//html	+= '				<span class="btn btn-warning btn-xs wastageremove" id="'+total_count+'" ><i class="fa fa-minus"></i> Remove</span>';
	html	+= '			</div>';
	html	+= '	   </div> ';
	html	+= '	</div>';
	html	+= '</div>';
	$("#append_wastage").append(html);
}*/
//Close : wastage
</script> 
<!-- Close : validation script -->

<?php } else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>