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
	//	printr($product);
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
	//printr($post);//die;
		$insert_id = $obj_product->addProduct($post);
		//die;
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		//printr($post);die;
		$product_id = $product['product_id'];
		$obj_product->updateProduct($product_id,$post);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, 'filter_edit='.$_GET['filter_edit'], '',1));

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
          <header class="panel-heading bg-white"><meta http-equiv="Content-Type" content="text/html; charset=windows-1252"> <?php echo $display_name;?> Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post"  name="form" id="form"  enctype="multipart/form-data">
              
              
            <!--  <div class="form-group">
                <label class="col-lg-3 control-label">Product Name</label>
                <div class="col-lg-8">
                  <input type="text" name="name" placeholder="Product Name" value="<?php //echo isset($product['product_name'])?$product['product_name']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              -->
               <div class="form-group">
                <label class="col-lg-3 control-label">Product Name</label>
                <div class="col-lg-8">
                 
                  <textarea name="name" id="name" class="form-control validate[required]"><?php echo isset($product['email_product'])?$product['email_product']:'';?></textarea>
                  <input type="hidden" name="product_name" id="product_name" placeholder="Product Name" value="<?php echo isset($product['product_name'])?$product['product_name']:'';?>" >
                </div>
              </div>
              
			  
               <div class="form-group">
                <label class="col-lg-3 control-label">Gusset Available?</label>
                <div class="col-lg-9">
                	<div class="radio">
                      	<label class="radio-custom">
                        <input type="radio" name="gusset_available" value="1" class="required"
						<?php echo (isset($product['gusset_available']) && $product['gusset_available'] == '1')?'checked="checked"':'';?>>
                        <i class="fa fa-circle-o"></i> Yes </label>
                    </div>
                    <div class="radio">
                      	<label class="radio-custom">
                        <input type="radio" name="gusset_available" value="0" <?php echo (isset($product['gusset_available']) && $product['gusset_available'] == '0')?'checked="checked"':'';?>>
                        <i class="fa fa-circle-o"></i> No </label>
                    </div>
                </div>
              </div>
              
               <div class="form-group">
                <label class="col-lg-3 control-label">Zipper Available?</label>
                <div class="col-lg-9">
                	<div class="radio">
                      	<label class="radio-custom">
                        <input type="radio" name="zipper_available" value="1" class="required"
						<?php echo (isset($product['zipper_available']) && $product['zipper_available'] == '1')?'checked="checked"':'';?>>
                        <i class="fa fa-circle-o"></i> Yes </label>
                    </div>
                    <div class="radio">
                      	<label class="radio-custom">
                        <input type="radio" name="zipper_available" value="0" <?php echo (isset($product['zipper_available']) && $product['zipper_available'] == '0')?'checked="checked"':'';?>>
                        <i class="fa fa-circle-o"></i> No </label>
                    </div>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Weight Available?</label>
                <div class="col-lg-9">
                	<div class="radio">
                      	<label class="radio-custom">
                        <input type="radio" name="weight_available" value="1" class="required"
						<?php echo (isset($product['weight_available']) && $product['weight_available'] == '1')?'checked="checked"':'';?>>
                        <i class="fa fa-circle-o"></i> Yes </label>
                    </div>
                    <div class="radio">
                      	<label class="radio-custom">
                        <input type="radio" name="weight_available" value="0" <?php echo (isset($product['weight_available']) && $product['weight_available'] == '0')?'checked="checked"':'';?>>
                        <i class="fa fa-circle-o"></i> No </label>
                    </div>
                </div>
              </div>
              
               <div class="form-group">
                <label class="col-lg-3 control-label">Tin Tie Available?</label>
                <div class="col-lg-9">
                	<div class="radio">
                      	<label class="radio-custom">
                        <input type="radio" name="tintie_available" value="1" class="required"
						<?php echo (isset($product['tintie_available']) && $product['tintie_available'] == '1')?'checked="checked"':'';?>>
                        <i class="fa fa-circle-o"></i> Yes </label>
                    </div>
                    <div class="radio">
                      	<label class="radio-custom">
                        <input type="radio" name="tintie_available" value="0" <?php echo (isset($product['tintie_available']) && $product['tintie_available'] == '0')?'checked="checked"':'';?>>
                        <i class="fa fa-circle-o"></i> No </label>
                    </div>
                </div>
              </div>
              
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Select Gusset Type</label>
                <div class="col-lg-9">
                	<?php $sel_gusset = (isset($product['gusset']) && !empty($product['gusset']))?explode(',',$product['gusset']):array();
					///printr($sel_gusset);?>
                    <div class="checkbox">
                      <label class="checkbox-custom">
                        <input type="checkbox" name="gusset[]" value="bottom_gusset" <?php echo (in_array('bottom_gusset',$sel_gusset))?'checked="checked"':'';?>>
                        <i class="fa fa-square-o"></i> Bottom Gusset </label>
                    </div>
                    <div class="checkbox">
                      <label class="checkbox-custom">
                        <input type="checkbox" name="gusset[]" value="side_gusset" <?php echo (in_array('side_gusset',$sel_gusset))?'checked="checked"':'';?> >
                        <i class="fa fa-square-o"></i> Side Gusset </label>
                    </div>
                    <div class="checkbox">
                      <label class="checkbox-custom">
                        <input type="checkbox" name="gusset[]" value="no_gusset_height" <?php echo (in_array('no_gusset_height',$sel_gusset))?'checked="checked"':'';?>>
                        <i class="fa fa-square-o"></i> No Gusset - Calculate Height</label>
                    </div>
                    <div class="checkbox">
                      <label class="checkbox-custom">
                        <input type="checkbox" name="gusset[]" value="no_gusset_width" <?php echo (in_array('no_gusset_width',$sel_gusset))?'checked="checked"':'';?>>
                        <i class="fa fa-square-o"></i> No Gusset - Calculate Width</label>
                    </div>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Calculate Zipper Prices</label>
                <div class="col-lg-9">
                	<div class="radio">
                      	<label class="radio-custom">
                        <input type="radio" class="" name="zipperoption" id="r1" value="w" <?php echo (isset($product['calculate_zipper_with']) && $product['calculate_zipper_with'] == 'w')?'checked="checked"':'';?>>
                        <i class="fa fa-circle-o"></i> With width </label>
                    </div>
                    <div class="radio">
                      	<label class="radio-custom">
                        <input type="radio" class="" name="zipperoption" id="r2" value="h" <?php echo (isset($product['calculate_zipper_with']) && $product['calculate_zipper_with'] == 'h')?'checked="checked"':'';?>>
                        <i class="fa fa-circle-o"></i> With height </label>
                    </div>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Gusset Printing</label>
                <div class="col-lg-9">
                	<div class="radio">
                      	<label class="radio-custom">
                        <input type="radio" class="" name="printing_option" id="p1" value="1" <?php echo (isset($product['printing_option']) && $product['printing_option'] == '1')?'checked="checked"':'';?>>
                        <i class="fa fa-circle-o"></i> Yes </label>
                    </div>
                    <div class="radio">
                      	<label class="radio-custom">
                        <input type="radio" class="" name="printing_option" id="p2" value="0" <?php echo (isset($product['printing_option']) && $product['printing_option'] == '0')?'checked="checked"':'';?>>
                        <i class="fa fa-circle-o"></i> No </label>
                    </div>
                </div>
              </div>
              
              <div class="form-group" id="printing_div">
                <label class="col-lg-3 control-label">Gusset Printing Option</label>
                <div class="col-lg-9">
                <?php $sel_printing = (isset($product['printing_option_type']) && !empty($product['printing_option_type']))?explode(',',$product['printing_option_type']):array();?>
                <div style="width: 100%;  height: 34px;">	
                	<div class="radio" style="  width: 40%;  float: left;">
                      	<label class="checkbox-custom">
                        <input type="checkbox" class="" name="printing_option_type[]" id="pType1" value="bottom" <?php echo (in_array('bottom',$sel_printing))?'checked="checked"':'';?>>
                        <i class="fa fa-square-o"></i> Bottom Printing </label>
                    </div>
                    <div style="  width: 58%;  float: right;">
                     <select name="bottom_min_qty" id="bottom_min_qty" class="form-control" style="  <?php echo (!in_array('bottom',$sel_printing))?' display:none':'';?>">
                        <option value="">Select Minimum Quantity</option>
                        <?php $min_qty = $obj_product->getQuantity();
						foreach($min_qty as $qty){
							if(isset($product['bottom_min_qty']) && $product['bottom_min_qty']==$qty['product_quantity_id'])
								echo '<option value="'.$qty['product_quantity_id'].'" selected="selected">'.$qty['quantity'].'</option>';
							else
								echo '<option value="'.$qty['product_quantity_id'].'">'.$qty['quantity'].'</option>';
							}?>
                        </select>
                    </div>
                </div>
                 <div style="width: 100%;  height: 34px;">	
                	<div class="radio" style="  width: 42%;  float: left;">
                      	<label class="checkbox-custom">
                        <input type="checkbox" class="" name="printing_option_type[]" id="pType2" value="side" <?php echo (in_array('side',$sel_printing))?'checked="checked"':'';?>>
                        <i class="fa fa-square-o"></i> Side Gusset Printing </label>
                    </div>
                    <div style="  width: 58%;  float: right; ">
                     <select name="side_min_qty" id="side_min_qty" class="form-control" style=" <?php echo (!in_array('side',$sel_printing))?' display:none':'';?> ">
                        <option value="">Select Minimum Quantity</option>
                        <?php $min_qty = $obj_product->getQuantity();
						foreach($min_qty as $qty){
							if(isset($product['side_min_qty']) && $product['side_min_qty']==$qty['product_quantity_id'])
								echo '<option value="'.$qty['product_quantity_id'].'" selected="selected">'.$qty['quantity'].'</option>';
							else
								echo '<option value="'.$qty['product_quantity_id'].'">'.$qty['quantity'].'</option>';
							}?>
                        </select>
                    </div>
                </div>
                <div style="width: 100%;  height: 34px;">	
                	<div class="radio" style="  width: 42%;  float: left;">
                      	<label class="checkbox-custom">
                        <input type="checkbox" class="" name="printing_option_type[]" id="pType3" value="both" <?php echo (in_array('both',$sel_printing))?'checked="checked"':'';?>>
                        <i class="fa fa-square-o"></i> Bottom / Side Gusset Printing </label>
                    </div>
                    <div style="  width: 58%;  float: right; "> 
                    <select name="both_min_qty" id="both_min_qty" class="form-control" style=" <?php echo (!in_array('both',$sel_printing))?' display:none':'';?> ">
                        <option value="">Select Minimum Quantity</option>
                        <?php $min_qty = $obj_product->getQuantity();
						foreach($min_qty as $qty){
							if(isset($product['both_min_qty']) && $product['both_min_qty']==$qty['product_quantity_id'])
								echo '<option value="'.$qty['product_quantity_id'].'" selected="selected">'.$qty['quantity'].'</option>';
							else
								echo '<option value="'.$qty['product_quantity_id'].'">'.$qty['quantity'].'</option>';
							}?>
                        </select>
                    </div>
                </div>
                
                 <div style="width: 100%;  height: 34px;">	
                	<div class="radio" style="  width: 42%;  float: left;">
                      	<label class="checkbox-custom">
                        <input type="checkbox" class="" name="printing_option_type[]" id="pType4" value="no" <?php echo (in_array('no',$sel_printing))?'checked="checked"':'';?>>
                        <i class="fa fa-square-o"></i> No Gusset Printing </label>
                    </div>
                    <div style="  width: 58%;  float: right; "> 
                    <select name="no_min_qty" id="no_min_qty" class="form-control" style=" <?php echo (!in_array('no',$sel_printing))?' display:none':'';?> ">
                        <option value="">Select Minimum Quantity</option>
                        <?php $min_qty = $obj_product->getQuantity();
						foreach($min_qty as $qty){
							if(isset($product['no_min_qty']) && $product['no_min_qty']==$qty['product_quantity_id'])
								echo '<option value="'.$qty['product_quantity_id'].'" selected="selected">'.$qty['quantity'].'</option>';
							else
								echo '<option value="'.$qty['product_quantity_id'].'">'.$qty['quantity'].'</option>';
							}?>
                        </select>
                    </div>
                </div>
                
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Spout Pouch Available?</label>
                <div class="col-lg-9">
                	<div class="radio">
                      	<label class="radio-custom">
                        <input type="radio" class="" name="spout_pouch_option" id="s1" value="1" <?php echo (isset($product['spout_pouch_available']) && $product['spout_pouch_available'] == '1')?'checked="checked"':'';?>>
                        <i class="fa fa-circle-o"></i> Yes </label>
                    </div>
                    <div class="radio">
                      	<label class="radio-custom">
                        <input type="radio" class="" name="spout_pouch_option" id="s2" value="0" <?php echo (isset($product['spout_pouch_available']) && $product['spout_pouch_available'] == '0')?'checked="checked"':'';?>>
                        <i class="fa fa-circle-o"></i> No </label>
                    </div>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Abbrevation</label>
                <div class="col-lg-8" style="width:200px">
                      <input type="text" name="abbrevation" id="abbrevation" placeholder="abbrevation" value="<?php echo isset($product['abbrevation'])?$product['abbrevation']:'';?>" class="form-control">
                      </div>
                    <div class="col-lg-8" style="width:100px">
                </div>
              </div>
              
               <div class="form-group">
                <label class="col-lg-3 control-label">Per Kg Price</label>
                <div class="col-lg-8" style="width:200px">
                      <input type="text" name="per_kg_price" id="per_kg_price" placeholder="per kg price" value="<?php echo isset($product['per_kg_price'])?$product['per_kg_price']:'';?>" class="form-control">
                      </div>
                    <div class="col-lg-8" style="width:100px">
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Strip Thickness</label>
                <div class="col-lg-8" style="width:200px">
                      <input type="text" name="strip_thickness" id="strip_thickness" placeholder="Strip Thickness" value="<?php echo isset($product['strip_thickness'])?$product['strip_thickness']:'';?>" class="form-control">
                      </div>
                    <div class="col-lg-8" style="width:100px">
                </div>
              </div>
              
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Select Color Category</label>
                <div class="col-lg-8">
                	<div class="form-control scrollbar scroll-y" style="height:200px" id="groupbox">
                        <?php
						$clrs = $obj_product->getAllColorCategory();
						$selected = array();
						// printr($clrs);						  
						 if($edit)
						 {	
							$selected = explode(',',$product['category']); 
						 }
							
                        foreach($clrs as $clr){
							echo '<div class="checkbox">';
								echo '<label class="checkbox-custom">';
								if(in_array($clr['color_catagory_id'],$selected)){
										echo '<input type="checkbox" checked="checked" name="category[]" id="'.$clr['color_catagory_id'].'" value="'.$clr['color_catagory_id'].'"> ';
								}else{
									echo '<input type="checkbox" name="category[]" id="'.$clr['color_catagory_id'].'" value="'.$clr['color_catagory_id'].'"> ';
								}
								echo '<i class="fa fa-square-o"></i> '.$clr['color_name'].' </label>';
							echo '</div>';
						}
						?>
                    </div>
                    <a class="btn btn-default btn-xs selectall mt5" onclick="javascript:checkall('form', true)">Select All</a>
                    <a class="btn btn-default btn-xs unselectall mt5" onclick="javascript:uncheckall('form', true)">Unselect All</a>    
                </div>
              </div>
              
               <div class="form-group">
                <label class="col-lg-3 control-label">Select Make Pouch</label>
                <div class="col-lg-9">
                	<?php $make_pouch = (isset($product['make_pouch_available']) && !empty($product['make_pouch_available']))?explode(',',$product['make_pouch_available']):array();
					//printr($make_pouch);?>
					<?php 
						$getmake=$obj_product->getMake(); 
						//printr($getmake);
						foreach($getmake as $make){
						//printr($make);
					?>
                	
                    <div class="checkbox">
                      <label class="checkbox-custom">
                        <input type="checkbox" name="pouch[]" value="<?php echo $make['make_id'];?>" <?php echo (in_array($make['make_id'],$make_pouch))?'checked="checked"':'';?> <?php // echo isset($product['strip_thickness'])?$product['strip_thickness']:''; //echo (in_array('bottom_gusset',$make_pouch))?'checked="checked"':'';?>>
                        <i class="fa fa-square-o"></i><?php echo $make['make_name'];?></label>
                    </div>
                    
                   <?php 
				   }
				   ?>
                  <!--  <div class="checkbox">
                      <label class="checkbox-custom">
                        <input type="checkbox" name="gusset[]" value="side_gusset" <?php //echo (in_array('side_gusset',$sel_gusset))?'checked="checked"':'';?> >
                        <i class="fa fa-square-o"></i> Side Gusset </label>
                    </div>
                    <div class="checkbox">
                      <label class="checkbox-custom">
                        <input type="checkbox" name="gusset[]" value="no_gusset_height" <?php //echo (in_array('no_gusset_height',$sel_gusset))?'checked="checked"':'';?>>
                        <i class="fa fa-square-o"></i> No Gusset - Calculate Height</label>
                    </div>
                    <div class="checkbox">
                      <label class="checkbox-custom">
                        <input type="checkbox" name="gusset[]" value="no_gusset_width" <?php //echo (in_array('no_gusset_width',$sel_gusset))?'checked="checked"':'';?>>
                        <i class="fa fa-square-o"></i> No Gusset - Calculate Width</label>
                    </div>-->
                </div>
              </div>
              
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
<script src="<?php echo HTTP_SERVER;?>ckeditor3/ckeditor.js"></script>
<!-- Close : validation script -->
<script>

 jQuery(document).ready(function(){
	 
	   // binds form submission and fields to the validation engine
        jQuery("#form").validationEngine();		
	 
	 if(jQuery("input[name='printing_option']:checked").val()==0)
		 $('#printing_div').hide();
	 else
			$('#printing_div').show();
		jQuery("input[name='printing_option']").change(function (){		
			if($(this).val()==0)
			{
				$('#printing_div').hide();
				$('#printing_div').find('input[type=checkbox]:checked').removeAttr('checked');				
			}
			else
				$('#printing_div').show();
		});
		jQuery("input[name='printing_option_type[]']").change(function (){					
			var id=$(this).val(); 
			if (this.checked)
			 { 	
				$('#'+id+'_min_qty').show;
				$('#'+id+'_min_qty').addClass('validate[required]');	
				$('#'+id+'_min_qty').css("display","block");			
			 }
			 else
			 {
			 	$('#'+id+'_min_qty').css("display","none");
			 }
		});
		
		var editor = CKEDITOR.replace( 'name', {
				height: '65px',
				removePlugins: 'elementspath',
				resize_enabled: false,
				addClass:' validate[required]',
		 toolbar: [ { name: 'colors', items: [ 'TextColor' ] },]}
		);
		for (var i in CKEDITOR.instances) {
        CKEDITOR.instances[i].on('change', function() {
			//alert(CKEDITOR.instances[i].val());
			var value = CKEDITOR.instances['name'].getData();
			$('#product_name').val(value);
			});
    }
	
	    
    });
$('#abb').change(function () {
			if (this.value.match(/[^a-zA-Z ]/g)) {
			//this.value = this.value.replace(/[^a-zA-Z]/g, ”);
			alert("Please Enter Only Characters");
			$('#abb').val("");
			}
});

</script> 
<?php } else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>