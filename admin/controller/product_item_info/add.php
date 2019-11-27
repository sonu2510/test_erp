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

if(isset($_GET['product_item_id']) && !empty($_GET['product_item_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$product_item_id = base64_decode($_GET['product_item_id']);
		$product_code = $obj_product_item->getProductCodeData($product_item_id);
		$material_layer = $obj_product_item->getMaterialLayer($product_item_id);
		//printr($product_code);
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
		$insert_id = $obj_product_item->addProductCode($post);
		$obj_session->data['success'] = ADD;
	page_redirect($obj_general->link($rout, '', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		$product_item_id = $product_code['product_item_id'];
		//printr($product_item_id);
		$obj_product_item->updateProductCode($product_item_id,$post);
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
                <label class="col-lg-3 control-label"><span class="required">*</span>Product Code</label>
                <div class="col-lg-8">
                  	<input type="text"  id="product_code" name="product_code" value="<?php echo isset($product_code['product_code'])?$product_code['product_code']:'';?>" class="form-control validate[required]">
                     <span id="exists" style="color:red;display:none;"></span>
                </div>
              </div>
              
              <?php /*?><div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Description</label>
                <div class="col-lg-8">
                  	<input type="text" name="description" value="<?php echo isset($product_code['description'])?$product_code['description']:'';?>" class="form-control validate[required]">
                </div>
              </div><?php */?>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Product Name</label>
                <div class="col-lg-8">
                  	<input type="text" id="product_name" name="product_name" value="<?php echo isset($product_code['product_name'])?$product_code['product_name']:'';?>" class="form-control validate[required]">
                      <span id="exists_product_name" style="color:red;display:none;"></span>
                      
                </div>
              </div> 
                <div class="form-group">
                    <label class="col-lg-3 control-label"><span class="required">*</span> Product Category</label>
                    <div class="col-lg-8">
                        <?php $productcategory = $obj_product_item->getProductCategory(); 
                                //printr($productcategory);die;?>
                        
                        <select name="product_category" id="product_category" class="form-control validate[required]">
                        <option value="">Select Product Category</option>
                        
                        <?php //printr($product_code);//die; ?>
                         <?php foreach($productcategory as $productcat) { ?>
                    <?php if(isset($product_code['product_category_id'])==$productcat['product_category_id'])
                     {?>
                            <option value="<?php echo $productcat['product_category_id']; ?>"  <?php echo(isset($product_code) && $product_code['product_category_id'] == $productcat['product_category_id'])?'selected':'';?>><?php echo $productcat['product_category_name']; ?></option>
                    <?php } else { ?>
                            <option value="<?php echo $productcat['product_category_id']; ?>" > <?php echo $productcat['product_category_name']; ?></option>
               <?php  }}?>
                        </select>
                    </div>
            </div>
				

              <div class="form-group">
                  <label class="col-lg-3 control-label"><span class="required">*</span> Current Stock</label>
                    <div class="col-lg-3">
						<input type="text" id="current_stock" name="current_stock" value="<?php echo isset($product_code['current_stock'])?$product_code['current_stock']:'';?>" class="form-control validate[required]">

                    </div>
				</div>
				
				 <div id="thk" class="form-group" style="display:none">
                  <label class="col-lg-3 control-label"><span class="required">*</span> Thickness </label>
                    <div class="col-lg-3">
						<input type="text" id="thickness" name="thickness" value="<?php echo isset($product_code['product_thickness'])?$product_code['product_thickness']:'';?>" class="form-control validate[required]">

                    </div>
                     <label class="col-lg-3 control-label"><span class="required">*</span> GSM (Density)</label>
                    <div class="col-lg-3">
						<input type="text" id="gsm" name="gsm" value="<?php echo isset($product_code['product_gsm'])?$product_code['product_gsm']:'';?>" class="form-control validate[required]">

                    </div>
				</div>
              
              <div class="form-group">
                  <label class="col-lg-3 control-label"><span class="required">*</span> Unit</label>
                    <div class="col-lg-3">
                            <?php $measurement = $obj_product_item->getMeasurement();
									//printr($product_code);
							 ?>
                            <select name="unit" id="unit" class="form-control validate[required]">
                            <option value="">Select Unit</option>
                                <?php  foreach($measurement as $mea){
                                    if(isset($post['product']) && $post['product'] == $mea['unit_id']){ ?>
                                        <option value="<?php echo $mea['unit_id']; ?>" selected="selected" ><?php echo $mea['unit']; ?></option>
                                    <?php }else{ ?>
                                        <option value="<?php echo $mea['unit_id']; ?>" <?php if(isset($product_item_id) && ($mea['unit_id'] == $product_code['unit'])) { echo 'selected="selected"';}?> > <?php echo $mea['unit']; ?></option>
                                    <?php }
                                } ?>
                            </select>
                        </div>
                        
                  <label class="col-lg-3 control-label"> Secondary Unit</label>
                     <div class="col-lg-3">
                            <?php $measurement = $obj_product_item->getMeasurement();
									//printr($product_code);
							 ?>
                            <select name="sec_unit" id="sec_unit" class="form-control validate[required]">
                            <option value="">Select Unit</option>
                                <?php  foreach($measurement as $mea){
                                    if(isset($post['product']) && $post['product'] == $mea['unit_id']){ ?>
                                        <option value="<?php echo $mea['unit_id']; ?>" selected="selected" ><?php echo $mea['unit']; ?></option>
                                    <?php }else{ ?>
                                        <option value="<?php echo $mea['unit_id']; ?>" <?php if(isset($product_item_id) && ($mea['unit_id'] == $product_code['unit'])) { echo 'selected="selected"';}?> > <?php echo $mea['unit']; ?></option>
                                    <?php }
                                } ?>
                            </select>
                        </div>
               </div>
               
              <div class="form-group">
                <label class="col-lg-3 control-label">Material</label>
                <div class="col-lg-9">
                	<div class="radio">
                      	<label class="radio-custom">
                        	<input type="radio" name="material"  value="1" <?php  if(isset($product_code['material']) && $product_code['material'] == '1') { ?> checked="checked" <?php }?> class="required"  checked="checked" />
						
                        <i class="fa fa-circle-o"></i> Raw Material</label>
                    </div>
                    <div class="radio">
                      	<label class="radio-custom">                   
                        
                        <input type="radio" name="material" value="2" <?php  if(isset($product_code['material']) && $product_code['material'] == '2') { ?> checked="checked" <?php } ?>  >
                        <i class="fa fa-circle-o"></i> Finished </label>
                    </div>
                </div>
              </div>
              
			    <div class="form-group">
                <label class="col-lg-3 control-label">Select Layer</label>
                <div class="col-lg-8">
                	<div class="form-control scrollbar scroll-y" style="height:150px">
                        <?php
						$sel_layer = array();
						if(isset($material_layer) && !empty($material_layer) && $material_layer){
							$sel_layer = explode(',',$material_layer);
						}
						//printr($sel_layer);die;
						$layers = $obj_product_item->getActiveLayer();
						
                        foreach($layers as $layer){
                            echo '<div class="checkbox">';
                            echo '	<label class="checkbox-custom">';
							if(isset($sel_layer) && in_array($layer['layer'],$sel_layer)){
                            	echo '	<input type="checkbox" name="layer[]" id="'.$layer['layer'].'" value="'.$layer['layer'].'" checked="checked" onclick="checklayer('.$layer['layer'].')"> ';
							}else{
								echo '	<input type="checkbox" name="layer[]" id="'.$layer['layer'].'" value="'.$layer['layer'].'" onclick="checklayer('.$layer['layer'].')" > ';
							}
                            echo '	<i class="fa fa-square-o"></i> '.$layer['layer'].' </label>';
                            echo '</div>';
                        }
						?>
                    </div>
			  
			  </div>
			  
              <div class="form-group">
                <label class="col-lg-3 control-label">Manufacturing Process</label>
                <div class="col-lg-4">
                  <?php $process = $obj_product_item->getProductionProcess(); //printr($process);
						 $p=array();
						 if(isset($product_code['production_process_id'])&& !empty($product_code['production_process_id']) ){
					    		$p = unserialize($product_code['production_process_id']);
				  			 }
							
						 
						foreach($process as $pro)
						{
						?>
							<div class="checkbox chf1" style="float: left; width: 40%;">
                                <label>
                                  <input type="checkbox" name="process_name[]" value="<?php echo $pro['production_process_id'];?>" id="process_name" class="formtypeclass" <?php if(isset($p)&& in_array($pro['production_process_id'],$p)){ echo 'checked = "checked"'; } ?>>
                                 <?php echo $pro['production_process_name'];?>
                                 </label>
                             </div>
						<?php }?>
                  	
                </div>
                <div class="form-group">
                                    <div class="col-lg-9 col-lg-offset-3">
                                        <a  id="btn-all-check" class="label bg-success selectall mt5"  onclick="javascript:checkall('form', true)">Select All</a>
                                        <a id="btn-all-uncheck" class="label bg-warning unselectall mt5"  onclick="javascript:uncheckall('form', true)">Unselect All</a>  
                                    </div>
                                </div>
              </div>
              
             <div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                <div class="col-lg-4">
                  <select name="status" id="status" class="form-control">
                    <option value="1" <?php echo (isset($product_code['status']) && $product_code['status'] == 1)?'selected':'';?> > Active</option>
                    <option value="0" <?php echo (isset($product_code['status']) && $product_code['status'] == 0)?'selected':'';?>> Inactive</option>
                  </select>
                </div>
              </div>
				<input type="hidden" name="edit_text" id="edit_text" value="<?php echo isset($edit)?$edit:'0';?>" />
                <input type="hidden" name="product_item_id" id="product_item_id" value="<?php echo isset($product_item_id)?$product_item_id:'';?>" />
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
jQuery(document).ready(function(){
	 
	   jQuery("#form").validationEngine();
	  $("#product_category").change();
});
var edit = $("#edit_text").val();
var product_item_id = $("#product_item_id").val();
$("#product_name").change(function() {
    var check_product_name=$(this).val();	
			checkproduct_name(check_product_name,edit,product_item_id);
		});
	
function checkproduct_name(product_name,edit,product_item_id)
	{ //alert(edit+'=='+product_item_id); 
	 var orgno = '<?php echo isset($product_code['product_name'])?$product_code['product_name']:'';?>';
		if(product_name.length > 0 && orgno != product_name){
			$(".uniqusername").remove();
			var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=checkproduct_name', '',1);?>");
			$("#loading").show();
			$.ajax({
				url : status_url,
				type :'post',
				data :{product_name:product_name,edit:edit,product_item_id:product_item_id},
				success: function(json) {
					
					if(json > 0){
						$("#product_name").val('');
						$("#exists_product_name").show();
						$("#exists_product_name").html('Product Name already exists!');
						$("#loading").hide();
						return false;
					}else{
						$("#loading").hide();
						$("#exists_product_name").hide();
						return true;
					}
				}
			});
		}else{
			$("#loading").hide();
			return true;
		}
	}


$("#product_category").change(function() { 
	
	var optionvalue=$('select[name=product_category]').val();
//alert(optionvalue);
	if(optionvalue==3){
		
	$('#thk').css('display','inline');
	}else{
	$('#thk').css('display','none');
}
	
});
$("#product_code").change(function() {
    var check_product_code=$(this).val();	
			checkProduct_code(check_product_code,edit,product_item_id);
		});
	
function checkProduct_code(product_code,edit,product_item_id)
	{   var orgno = '<?php echo isset($product_code['product_code'])?$product_code['product_code']:'';?>';
		if(product_code.length > 0 && orgno != product_code){
			$(".uniqusername").remove();
			var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=checkProductCode', '',1);?>");
			$("#loading").show();
			$.ajax({
				url : status_url,
				type :'post',
				data :{product_code:product_code,edit:edit,product_item_id:product_item_id},
				success: function(json) {
					console.log(json);
					if(json > 0){
						$("#product_code").val('');
						$("#exists").show();
						$("#exists").html('Product Code already exists!');
						$("#loading").hide();
						return false;
					}else{
						$("#loading").hide();
						$("#exists").hide();
						return true;
					}
				}
			});
		}else{
			$("#loading").hide();
			return true;
		}
	}
	
	function checkall(formname, checktoggle)
	{
		var checkboxes = new Array();
		checkboxes = $('input[name="process_name[]"]');
		for (var i = 0; i < checkboxes.length; i++) {
			if (checkboxes[i].type === 'checkbox') {
				checkboxes[i].checked = checktoggle;
			}
		}
	}
	
	
	function uncheckall(formname, checktoggle)
	{
		var checkboxes = new Array();
		checkboxes = $('input[name="process_name[]"]');
		for (var i = 0; i < checkboxes.length; i++) {
			if (checkboxes[i].type === 'checkbox') {
				checkboxes[i].checked = '';
			}
		}
	}	   	  	   	   
</script>

<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>
