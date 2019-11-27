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
if(isset($_GET['wastage_id']) && !empty($_GET['wastage_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$wastage_id = base64_decode($_GET['wastage_id']);
		$wastage = $obj_wastage->getWastage($wastage_id);
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
		$insert_id = $obj_wastage->addWastage($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		//printr($post);die;
		$wastage_id = $wastage['product_wastage_id'];
		$obj_wastage->updateWastage($wastage_id,$post);
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
                <label class="col-lg-3 control-label">From Quantity </label>
                <div class="col-lg-4">
                  	<input type="text" name="from_quantity" value="<?php echo isset($wastage['from_quantity'])?$wastage['from_quantity']:'';?>" class="form-control validate[required,custom[onlyNumberSp]]">
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">To Quantity </label>
                <div class="col-lg-4">
                  	<input type="text" name="to_quantity" value="<?php echo isset($wastage['to_quantity'])?$wastage['to_quantity']:'';?>" class="form-control validate[required,custom[onlyNumberSp]]">
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Wastage (%) </label>
                <div class="col-lg-4">
                  	<input type="text" name="wastage" value="<?php echo isset($wastage['wastage'])?$wastage['wastage']:'';?>" class="form-control validate[required,custom[number]]">
                </div>
              </div>
              <?php /*
			  <div class="form-group">
                <label class="col-lg-3 control-label">Wastage</label>
                <div class="col-lg-8">
                	<div class="row">
                		<div class="col-sm-3">From Quantity</div>
                    	<div class="col-lg-3">To Quantity</div>
                     	<div class="col-lg-3">Wastage (%)</div>
                   </div> 
                </div>
              </div>
			  <div class="form-group" >
                <label class="col-lg-3 control-label"></label>
                <div class="col-lg-8">
                	<div class="row">
                		<div class="col-sm-3">
                    		<input type="text" name="form_wastage[]" id="form_wastage_1" value="" class="form-control validate[required,custom[onlyNumberSp]]">
                    	</div>
                    	<div class="col-lg-3">
                            <input type="text" name="to_wastage[]" id="to_wastage_1" value="" class="form-control validate[required,custom[onlyNumberSp]]">
                        </div>
                     	<div class="col-lg-3">
                            <input type="text" name="wastage[]" id="wastage_1" value="" class="form-control validate[required,custom[onlyNumberSp]]">
                        </div>
                        <div class="col-lg-3">
                            <a class="btn btn-success btn-circle btn-xs wastageadd" ><i class="fa fa-plus"></i></a>
                        </div>
                        <input type="hidden" name="hdn_wastagecount" id="hdn_wastagecount" value="">
                   </div> 
                </div>
              </div>
              <div id="append_wastage"></div> */ ?>
              
              <?php /* <div class="form-group" >
                <label class="col-lg-3 control-label"></label>
                <div class="col-lg-8">
                	<div class="row">
                		<div class="col-sm-3">
                    		<input type="text" name="form_wastage[]" id="form_wastage_1" value="" class="form-control validate[required,custom[onlyNumberSp]]">
                    	</div>
                         <div class="col-lg-5">
							<div data-toggle="buttons" class="btn-group">
                            	<label class="btn btn-sm btn-white active">
                                	<input type="radio" id="option1" name="options"> 
                                    <i class="fa fa-chevron-left"></i>
                                </label>
                                <label class="btn btn-sm btn-white">
                                	<input type="radio" id="option2" name="options">
                                    <i class="fa fa-chevron-left"></i>=
                                </label>
                                <label class="btn btn-sm btn-white">
                                	<input type="radio" id="option3" name="options">
                                     <i class="fa fa-chevron-right"></i>
                                </label>
                                <label class="btn btn-sm btn-white">
                                	<input type="radio" id="option4" name="options">
                                    <i class="fa fa-chevron-right"></i>=
                                </label>
                                <label class="btn btn-sm btn-white">
                                	<input type="radio" id="option4" name="options">
                                    =
                                </label>
                             </div>
                         </div>
                         
                     	<div class="col-lg-3">
                            <input type="text" name="wastage[]" id="wastage_1" value="" class="form-control validate[required,custom[onlyNumberSp]]">
                        </div>
                        <div class="col-lg-1">
                            <!--<span class="btn btn-info btn-xs wastageadd" ><i class="fa fa-plus"></i></span>-->
                            <a class="btn btn-success btn-circle btn-xs wastageadd" ><i class="fa fa-plus"></i></a>
                        </div>
                        <input type="hidden" name="hdn_wastagecount" id="hdn_wastagecount" value="">
                   </div> 
                </div>
              </div> */?>
              
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