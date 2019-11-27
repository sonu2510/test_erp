<?php
include("mode_setting.php");
$obj_product = new product();

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
	'href' 	=> $obj_general->link('product', '', '',1),
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
if(isset($_GET['product_id']) && !empty($_GET['product_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$product_id = base64_decode($_GET['product_id']);
		$product = $obj_product->getProduct($product_id);
		$edit = 1;
	}
	
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}else{
		
	}
}
//Close : edit

if($display_status){
	//insert user
	if(isset($_POST['btn_save'])){
		$post = post($_POST);		
		//printr($post);die;
		$insert_id = $obj_product->addProduct($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		$product_id = $product['product_id'];
		$obj_product->updateProduct($product_id,$post);
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
                <label class="col-lg-3 control-label">Select Product</label>
                <div class="col-lg-8">
                	<?php
					$products = $obj_product->getProductActive();
					?>
                	<select name="product" id="product" class="form-control validate[required]">
                    	<?php
                        foreach($products as $product){
							echo '<option value="'.$product['product_id'].'">'.$product['product_name'].'</option>';
                        } ?>
                  	</select>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Select Material</label>
                <div class="col-lg-8">
                	<?php
					$materials = $obj_product->getMaterialActive();
					?>
                	<select name="material" id="material" class="form-control validate[required]">
                    	<?php
                        foreach($materials as $material){
							echo '<option value="'.$material['product_material_id'].'">'.$material['material_name'].'</option>';
                        } ?>
                  	</select>
                </div>
              </div>
              
              <div class="form-group" >
                <label class="col-lg-3 control-label">Select Layer</label>
                <div class="col-lg-4">
                	<?php
					$layers = $obj_product->getLayerActive();
					?>
                	<select name="layer" id="layer" class="form-control validate[required]">
                    	<?php
                        foreach($layers as $layer){
							echo '<option value="'.$layer['product_layer_id'].'">'.$layer['layer'].'</option>';
                        } ?>
                  	</select>
                </div>
              </div>
              <div id="layerdiv"></div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Wastage</label>
                <div class="col-lg-8">
                	<div class="row">
                		<div class="col-sm-3">
                    		Form Quantity
                    	</div>
                    	<div class="col-lg-3">
                            To Quantity
                        </div>
                     	<div class="col-lg-3">
                            Wastage (%)
                        </div>
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
                            <span class="btn btn-info btn-xs wastageadd" ><i class="fa fa-plus"></i> Add More</span>
                        </div>
                        <input type="hidden" name="hdn_wastagecount" id="hdn_wastagecount" value="">
                   </div> 
                </div>
              </div>
              <div id="append_wastage"></div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                <div class="col-lg-4">
                  <select name="status" id="status" class="form-control">
                    <option value="1" <?php echo (isset($product['status']) && $product['status'] == 1)?'selected':'';?> > Active</option>
                    <option value="0" <?php echo (isset($product['status']) && $product['status'] == 0)?'selected':'';?>> Inactive</option>
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
		
		setLayerHtml($("#layer").val());
		$("#layer").change(function(){
			var layerid = $(this).val();
			setLayerHtml(layerid);
			//alert(html);
			/*$.post("<?php echo HTTP_ADMIN;?>ajax/getlayer.php",{layer:layerid},function(result){
				//$("span").html(result);
				alert(result);
			});*/
		});
		
    });
$(document).on('click', ".wastageadd", function () {
	more_wastage();
});

$(document).on('click', ".wastageremove", function () {
    var id = $(this).attr("id");
	$(this).parent().closest(".form-group").remove();
});

function setLayerHtml(layer){
	var html = '';
	$('#layerdiv').html('');
	for ( var i = 1; i <= layer; i++ ) {
		html += '<div class="form-group">';
		html += '<label class="col-lg-3 control-label">'+i+' Layer price / kg </label>';
		html += '	<div class="col-lg-8">';
		html += '  		<input type="text" name="price[]" id="price_'+i+'" value="" class="form-control validate[required]">';
		html += '	</div>';
		html += '</div>';
	}
	$('#layerdiv').append(html);
}

function more_wastage(){
	var total_count = parseInt( $(".more_wastage").size()) + 1;
	//alert(total_count);
	$("#hdn_imgcount").val(total_count);
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
	html	+= '				<span class="btn btn-warning btn-xs wastageremove" id="'+total_count+'" ><i class="fa fa-minus"></i> Remove</span>';
	html	+= '			</div>';
	html	+= '	   </div> ';
	html	+= '	</div>';
	html	+= '</div>';
	$("#append_wastage").append(html);
}
</script> 
<!-- Close : validation script -->
<?php } else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>