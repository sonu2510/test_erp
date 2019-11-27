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
$edit=false;
if(isset($_GET['spout_id']) && !empty($_GET['spout_id']))
{
	$spoutid=$_GET['spout_id'];
	$spout = $obj_spout->getSpout($spoutid);
	$edit = true;
	
}


//Close : edit

if($display_status){
	//insert user
	if(isset($_POST['btn_save'])){
		$post = post($_POST);		
		//printr($post);die;
		$insert_id = $obj_spout->addspout($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		//printr($post);die;
		$spout_id = $spout['product_spout_id'];
		$obj_spout->updatespout($spout_id,$post);
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
                  <input type="text" name="name" value="<?php echo isset($spout['spout_name'])?$spout['spout_name']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
               <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Abbreviation</label>
                <div class="col-lg-8">
                  	<input type="text" name="abbrevation" id="abbrevation" value="<?php echo isset($spout['spout_abbr'])?$spout['spout_abbr']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
              	 <label class="col-lg-3 control-label"><span class="required">*</span>Unit</label>
                <div class="col-lg-4">
                  <input type="text" name="unit" value="<?php echo isset($spout['spout_unit'])?$spout['spout_unit']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
              	 <label class="col-lg-3 control-label">Minimum Product Quantity</label>
                <div class="col-lg-4">
                  <input type="text" name="min_qty" value="<?php echo isset($spout['spout_min_qty'])?$spout['spout_min_qty']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Price of one Spout </label>
                <div class="col-lg-4">
                  	<input type="text" name="price" value="<?php echo isset($spout['price'])?$spout['price']:'';?>" class="form-control validate[required,custom[number]]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Wastage in Percentage</label>
                <div class="col-lg-4">
                  	<input type="text" name="wastage" value="<?php echo isset($spout['wastage'])?$spout['wastage']:'';?>" class="form-control validate[required,custom[number]]">
                </div>
              </div>
              
                <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Transportation By Air </label>
                <div class="col-lg-4">
                  	<input type="text" name="by_air" value="<?php echo isset($spout['by_air'])?$spout['by_air']:'';?>" class="form-control validate[required,custom[number]]">
                   
                </div>
                 <span class="required"> Will be multiplied with total courier charges </span> 
              </div>
              
                 <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Transportation By Sea </label>
                <div class="col-lg-4">
                  	<input type="text" name="by_sea" value="<?php echo isset($spout['by_sea'])?$spout['by_sea']:'';?>" class="form-control validate[required,custom[number]]">
                </div>
                <span class="required"> Will be added to trasportastion for sea</span>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Weight in Kgs </label>
                <div class="col-lg-4">
                  	<input type="text" name="weight" value="<?php echo isset($spout['weight'])?$spout['weight']:'';?>" class="form-control validate[required,custom[number]]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Additional Packaging Price </label>
                <div class="col-lg-4">
                  	<input type="text" name="additional_packaging_price" value="<?php echo isset($spout['additional_packaging_price'])?$spout['additional_packaging_price']:'';?>" class="form-control validate[required,custom[number]]">
                </div>
                <span class="required"> Will be added to as packing per pouch</span>
              </div>
              
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Additional Profit / Pouch </label>
                <div class="col-lg-4">
                  	<input type="text" name="additional_profit_pouch" value="<?php echo isset($spout['additional_profit_pouch'])?$spout['additional_profit_pouch']:'';?>" class="form-control validate[required,custom[number]]">
                </div>
                <span class="required"> Will be added to as packing per pouch</span>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Serial No </label>
                <div class="col-lg-4">
                  	<input type="text" name="serial_no" value="<?php echo isset($spout['serial_no'])?$spout['serial_no']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Weight </label>
                <div class="col-lg-4">
                  	<input type="text" name="weight_temp" value="<?php echo isset($spout['weight_temp'])?$spout['weight_temp']:'';?>" class="form-control validate">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                <div class="col-lg-4">
                  <select class="form-control" name="status">
						<?php if($spout['status']==1){ ?>
                            <option value="1" selected="selected">Active</option>
                            <option value="0" >Inactive</option>
                            <?php
                        }else { ?>
                            <option value="1">Active</option>
                            <option value="0" selected="selected">Inactive</option>
                        <?php } ?>
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