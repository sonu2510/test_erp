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
if(!$obj_general->hasPermission('add',$menuId)){
	$display_status = false;
}
//Close : edit

$quantity_error = '';
if($display_status){
	
	$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
	$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
	
	
	if(isset($_POST['btn_save'])){
		if(isset($_SESSION['product_array']) && count($_SESSION['product_array'])>0){	
			$order_id = $obj_order->addOrder($_POST);
		}
	}else{
		if(isset($_SESSION['product_images'])){
		  unset($_SESSION['product_images']);
		}
		
		if(isset($_SESSION['product_array'])){
		  unset($_SESSION['product_array']);
		}
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
        
        <div class="col-lg-12">
        	<section class="panel">
              <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
              <form class="form-horizontal" method="post" name="form" id="order-form" enctype="multipart/form-data">
                    <div class="panel-body">
                    	<div class="col-lg-6">
                           <h4><i class="fa fa-edit"></i> General Details</h4>
                           <div class="line m-t-large" style="margin-top:-4px;"></div><br/>
                           
                            <div class="form-group">
                                <label class="col-lg-4 control-label"><span class="required">*</span>Company Name</label>
                                <div class="col-lg-7">
                                     <input type="text" name="company" value="" id="company" class="form-control validate[required]">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-lg-4 control-label"><span class="required">*</span>First Name</label>
                                <div class="col-lg-7">
                                     <input type="text" name="first_name" value="" class="form-control validate[required]">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-lg-4 control-label"><span class="required">*</span>Last Name </label>
                                <div class="col-lg-7">
                                     <input type="text" name="last_name" value="" class="form-control validate[required]">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-lg-4 control-label"><span class="required">*</span>Email Address </label>
                                <div class="col-lg-7">
                                     <input type="text" name="email" value="" class="form-control validate[required]">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-lg-4 control-label"><span class="required">*</span>Order Currency </label>
                                <div class="col-lg-7">
                                    <select name="order_currency" class="form-control">
                                    	<option value="gbp">GBP - Pound</option>
                                        <option value="usd">USD - Dollar</option>
                                        <option value="eur">EUR - Euro</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-lg-4 control-label"><span class="required">*</span>Contact No </label>
                                <div class="col-lg-7">
                                     <input type="text" name="last_name" value="" class="form-control validate[required]">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-lg-4 control-label"><span class="required">*</span>VAT No </label>
                                <div class="col-lg-7">
                                     <input type="text" name="last_name" value="" class="form-control validate[required]">
                                </div>
                            </div>
                           
                        </div> 
                        
                        <div class="col-lg-6">
                            <div class="col-lg-12">
                              <h4><i class="fa fa-edit"></i> Shipping Address</h4>
                              <div class="line m-t-large" style="margin-top:-4px;"></div><br/>
                              <div class="form-group">
                                <label class="col-lg-3 control-label"><span class="required">*</span> Address 1</label>
                                <div class="col-lg-8">
                                  <input type="text" name="shipping_address_1" value="<?php echo isset($zipper['zipper_name'])?$zipper['zipper_name']:'';?>" class="form-control validate[required] test">
                                </div>
                              </div>
                              
                              <div class="form-group">
                                <label class="col-lg-3 control-label">Address 2</label>
                                <div class="col-lg-8">
                                  <input type="text" name="shipping_address_2" value="<?php echo isset($zipper['zipper_name'])?$zipper['zipper_name']:'';?>" class="form-control test">
                                </div>
                              </div>
                              
                              <div class="form-group">
                                <label class="col-lg-3 control-label"><span class="required">*</span>City</label>
                                <div class="col-lg-8">
                                  <input type="text" name="shipping_city" value="<?php echo isset($zipper['zipper_name'])?$zipper['zipper_name']:'';?>" class="form-control validate[required] test">
                                </div>
                              </div>
                              
                              <div class="form-group">
                                <label class="col-lg-3 control-label"><span class="required">*</span>Country</label>
                                <div class="col-lg-8">
								 <?php 
                                   $sel_country = (isset($branch['country_id']))?$branch['country_id']:'';
                                   $countrys = $obj_general->getCountryCombo($sel_country);
                                   echo $countrys;
                                 ?>
                                </div>
                              </div>
                              
                              <div class="form-group">
                                <label class="col-lg-3 control-label">Post Code</label>
                                <div class="col-lg-8">
                                  <input type="text" name="shipping_postcode" value="<?php echo isset($zipper['zipper_name'])?$zipper['zipper_name']:'';?>" class="form-control">
                                </div>
                              </div>
                              
                            </div>
                            
                            <div class="col-lg-12">
                              
                              <h4><i class="fa fa-edit"></i> Billing Address
                                 <label class="checkbox-custom  m-l-small pull-right" style="font-size:14px;">
                                   <input type="checkbox" name="same_as_above" id="same-above" value="1">
                                 <i class="fa fa-square-o"></i> Same as Above? </label> 
                              </h4>  	
                              	
                              <div class="line m-t-large" style="margin-top:4px;"></div><br/>
                              
                              <div id="billing-details">
                                  <div class="form-group">
                                    <label class="col-lg-3 control-label"><span class="required">*</span> Address 1</label>
                                    <div class="col-lg-8">
                                      <input type="text" name="billing_address_1" value="<?php echo isset($zipper['zipper_name'])?$zipper['zipper_name']:'';?>" class="form-control validate[required]">
                                    </div>
                                  </div>
                                  
                                  <div class="form-group">
                                    <label class="col-lg-3 control-label">Address 2</label>
                                    <div class="col-lg-8">
                                      <input type="text" name="billing_address_2" value="<?php echo isset($zipper['zipper_name'])?$zipper['zipper_name']:'';?>" class="form-control">
                                    </div>
                                  </div>
                                  
                                  <div class="form-group">
                                    <label class="col-lg-3 control-label"><span class="required">*</span>City</label>
                                    <div class="col-lg-8">
                                      <input type="text" name="billing_city" value="<?php echo isset($zipper['zipper_name'])?$zipper['zipper_name']:'';?>" class="form-control validate[required]">
                                    </div>
                                  </div>
                                  
                                  <div class="form-group">
                                    <label class="col-lg-3 control-label"><span class="required">*</span>Country</label>
                                    <div class="col-lg-8">
                                     <?php 
                                       $sel_country = (isset($branch['country_id']))?$branch['country_id']:'';
                                       $countrys = $obj_general->getCountryCombo($sel_country);
                                       echo $countrys;
                                     ?>
                                    </div>
                                  </div>
                                  
                                  <div class="form-group">
                                    <label class="col-lg-3 control-label">Post Code</label>
                                    <div class="col-lg-8">
                                      <input type="text" name="billing_postcode" value="<?php echo isset($zipper['zipper_name'])?$zipper['zipper_name']:'';?>" class="form-control">
                                    </div>
                                  </div>
                              </div>
                              
                            </div>
                            
                        </div>
                        
                        <div class="col-lg-12">
                           <h4><i class="fa fa-plus-circle"></i> Add Product</h4>
                           <div class="line m-t-large" style="margin-top:-4px;"></div><br/>
                          
                          	  <div class="col-lg-7">
                                    <div class="form-group">
                                       <label class="col-lg-3 control-label"><span class="required">*</span> Product</label>
                                       <div class="col-lg-7">
                                            <?php
                                            $products = $obj_order->getActiveProduct();
                                            ?>
                                            <select name="product" id="product" class="form-control validate[required]">
                                                <?php
                                                $post['product'] = 3;
                                                foreach($products as $product){
                                                    if(isset($post['product']) && $post['product'] == $product['product_id']){
                                                        echo '<option value="'.$product['product_id'].'" selected="selected">'.$product['product_name'].'</option>';
                                                    }else{
                                                        echo '<option value="'.$product['product_id'].'">'.$product['product_name'].'</option>';
                                                    }
                                                } ?>
                                            </select>
                                       </div>
                                    </div>
                          
                                    <div class="form-group">
                                       <label class="col-lg-3 control-label"><span class="required">*</span>Printing Option</label>
                                       <div class="col-lg-7">
                                        <?php $post['printing'] = 0;?>
                                        <select name="printing" id="printing" class="form-control validate[required]">
                                            <option value="1" <?php echo (isset($post['printing']) && $post['printing'] == 1)?'selected':'';?> >With Printing</option>
                                            <option value="0" <?php echo (isset($post['printing']) && $post['printing'] == 0)?'selected':'';?>>Without Printing</option>
                                        </select>
                                       </div>
                                    </div>
                              
                                    <div class="form-group" >
                                        <label class="col-lg-3 control-label">Printing Effect</label>
                                       <div class="col-lg-7">
                                           <select name="printing_effect" id="printing_effect" class="form-control validate[required]">
                                                <?php
                                                $effects = $obj_order->getActivePrintingEffect();
                                                foreach($effects as $effect){
                                                    if(isset($post['printing_effect']) && $post['printing_effect'] == $effect['printing_effect_id']){
                                                        echo '<option value="'.$effect['printing_effect_id'].'" selected="selected">'.$effect['effect_name'].'</option>';
                                                    }else{
                                                        echo '<option value="'.$effect['printing_effect_id'].'">'.$effect['effect_name'].'</option>';
                                                    }
                                                } ?>
                                           </select>
                                       </div>
                                    </div>
                              
                                  <div class="form-group" >
                                     <label class="col-lg-3 control-label"><span class="required">*</span> Select Layer</label>
                                     <div class="col-lg-7">
                                        <?php
                                        $layers = $obj_order->getActiveLayer();
                                        ?>
                                        <select name="layer" id="layer" class="form-control validate[required]">
                                            <?php
                                            foreach($layers as $layer){
                                                echo '<option value="'.encode($layer['product_layer_id']).'">'.$layer['layer'].'</option>';
                                            } ?>
                                        </select>
                                     </div>
                                  </div>
                              
                                  <div class="form-group">
                                    <label class="col-lg-3 control-label">Material</label>
                                    <div class="col-lg-8" id="layerdiv"></div>
                                  </div>
                                  
                                  <div class="form-group">
                                    <label class="col-lg-3 control-label"><span class="required">*</span>Size</label>
                                    <div class="col-lg-8 table-responsive">
                                     <section class="panel">
                                     <table class="table table-striped b-t text-small">
                                        <thead>
                                            <tr>
                                                <th width="20%" class="text-center">Width</th>
                                                <th width="20%" class="text-center">Height</th>
                                                <th width="20%" class="text-center">Gusset</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><input type="text" name="width" value="" class="form-control validate[required,custom[onlyNumberSp]] test"></td>           										<td><input type="text" name="height" value="" class="form-control validate[required,custom[onlyNumberSp]]"></td>				
                                                <td><input type="text" name="gusset" value="" class="form-control validate[required,custom[onlyNumberSp]] test"></td>
                                            </tr>
                                        </tbody>
                                     </table>
                                     </section>
                                    </div>
                                  </div>
                                  
                                  <div class="form-group quantity_in">
                                    <label class="col-lg-3 control-label">Quantity In</label>
                                    <div class="col-lg-7">
                                         <select name="quantity_type" id="quantity_type" class="form-control">
                                            <option value="meter">Meter</option>
                                            <option value="kg">Kgs</option>
                                            <option value="pieces">Pieces</option>
                                        </select>
                                    </div>
                                  </div>
                              
                                  <div class="form-group">
                                    <label class="col-lg-3 control-label"><span class="required">*</span>Quantity</label>
                                    <div class="col-lg-7" id="squantity"></div>
                                  </div>
                              
                              </div>
                              
                              <div class="col-lg-5">
                              	<div class="form-group option">
                                    <label class="col-lg-3 control-label">Valve</label>
                                    <div class="col-lg-8">
                                       <select class="form-control" name="valve">
                                            <option value="0">No Valve</option>
                                            <option value="1">With Valve</option>
                                       </select>
                                    </div>
                                  </div>
                              
                                  <div class="form-group option">
                                    <label class="col-lg-3 control-label">Zipper</label>
                                    <div class="col-lg-8">
                                        <?php
                                        $zippers = $obj_order->getActiveProductZippers();
                                        $ziptxt = '';
                                        $ziptxt .= '<select class="form-control" name="zipper">';
                                        foreach($zippers as $zipper){
                                           $ziptxt .= '<option value="'.$zipper['product_zipper_id'].'">'.$zipper['zipper_name'].'</option>';
                                        }
                                        $ziptxt .= '</select>';
                                        echo $ziptxt;
                                        ?>
                                    </div>
                                  </div>
                                  
                                  <?php
                                  $spouts = $obj_order->getActiveProductSpout();
                                  if($spouts){
                                      ?>
                                      <div class="form-group option">
                                        <label class="col-lg-3 control-label">Spout</label>
                                        <div class="col-lg-8">
                                            <?php
                                           $spoutsTxt = '';
                                           $spoutsTxt .= '<select class="form-control" name="spout">';
                                            foreach($spouts as $spout){
                                                $spoutsTxt .= '<option value="'.$spout['product_spout_id'].'">'.$spout['spout_name'].'</option>';
                                            }
                                            $spoutsTxt .= '</select>';
                                            echo $spoutsTxt;
                                            ?>
                                        </div>
                                      </div>
                                      <?php
                                  } ?>
                                  
                                  <?php
                                  $colors = $obj_order->getActiveProductColors();
                                  if($colors){
                                      ?>
                                      <div class="form-group">
                                        <label class="col-lg-3 control-label">Color</label>
                                        <div class="col-lg-8" id="squantity">
                                           <select name="color" class="form-control">
                                                <?php 
                                                foreach($colors as $color) {
                                                    echo '<option value="'.$color['pouch_color_id'].'">'.$color['color'].'</option>';
                                                } ?>    
                                           </select>
                                        </div>
                                      </div>
                                      <?php
                                  } ?>
                                  
                                  <?php
                                   $styles = $obj_order->getActiveProductStyle();
                                   if($styles){
                                       ?>
                                      <div class="form-group">
                                        <label class="col-lg-3 control-label">Style</label>
                                        <div class="col-lg-8" id="squantity">
                                           <select name="style" class="form-control">
                                                <?php 
                                                foreach($styles as $style) {
                                                    echo '<option value="'.$style['pouch_style_id'].'">'.$style['style'].'</option>';
                                                } ?>    
                                           </select>
                                        </div>
                                      </div>
                                      <?php
                                   } ?>
                                  
                                  <?php
                                  $volumes = $obj_order->getActiveProductVolume();
                                  if($volumes){
                                      ?>
                                      <div class="form-group">
                                        <label class="col-lg-3 control-label">Volume</label>
                                        <div class="col-lg-8" id="squantity">
                                           <select name="volume" class="form-control">
                                                <?php 
                                                  
                                                  foreach($volumes as $volume) {
                                                ?>
                                                    <option value="<?php echo $volume['pouch_volume_id']; ?>"><?php echo $volume['volume']; ?></option>
                                                <?php } ?>    
                                           </select>
                                        </div>
                                      </div>
                                      <?php
                                  } ?>
                                  
                                  <div class="form-group">
                                    <label class="col-lg-3 control-label">Transportation</label>
                                    <div class="col-lg-8">
                                        <select name="transpotation" class="form-control">
                                            <option value="air">By Air</option>
                                            <option value="sea">By Sea</option>
                                            <option value="pickup">Pickup</option>
                                        </select>
                                    </div>
                                  </div>
                                  
                              </div>
                                 
                              <div class="col-lg-7 form-group">
                             	<label class="col-lg-3 control-label">Art Work</label>
                                <div class="col-lg-9">
                                	
                                      <div class="media-body">
                                        	<input type="file" name="file" title="<i class='fa fa-folder-open-o'></i> Browse" class="btn btn-sm btn-info m-b-small">
                                      </div>
                                    
                             		<div class="file-preview">
                                   		<div class="file-preview-thumbnails">
                                   		<div id="preview-1416630764190-0" class="file-preview-frame">
                                   			<img class="file-preview-image" src="http://192.168.1.250/erp/swisspac/upload/admin/profile/user/200_images.jpg">
                                            <a class="iremove" href="javascript:void(0);">Remove</a>
                                            
                                		</div>
                                        <div id="preview-1416630764190-0-1" class="file-preview-frame">
                                           <img class="file-preview-image" src="http://192.168.1.250/erp/swisspac/upload/admin/profile/user/200_images.jpg">
                                           <a class="iremove" href="javascript:void(0);">Remove</a>
                                        </div>
                                        <div id="preview-1416630764190-0-1" class="file-preview-frame">
                                           <img class="file-preview-image" src="http://192.168.1.250/erp/swisspac/upload/admin/profile/user/200_images.jpg">
                                           <a class="iremove" href="javascript:void(0);">Remove</a>
                                        </div>
                                        <div id="preview-1416630764190-0-1" class="file-preview-frame">
                                           <img class="file-preview-image" src="http://192.168.1.250/erp/swisspac/upload/admin/profile/user/200_images.jpg">
                                           <a class="iremove" href="javascript:void(0);">Remove</a>
                                        </div>
                                        <div id="preview-1416630764190-0-1" class="file-preview-frame">
                                           <img class="file-preview-image" src="http://192.168.1.250/erp/swisspac/upload/admin/profile/user/200_images.jpg">
                                           <a class="iremove" href="javascript:void(0);">Remove</a>
                                        </div>
                                        <div id="preview-1416630764190-0-1" class="file-preview-frame">
                                           <img class="file-preview-image" src="http://192.168.1.250/erp/swisspac/upload/admin/profile/user/200_images.jpg">
                                           <a class="iremove" href="javascript:void(0);">Remove</a>
                                        </div>
                                	</div>
                                   		<div class="clearfix"></div>
                                   		<div class="file-preview-status text-center text-success"></div>
                                   		<div class="kv-fileinput-error file-error-message" style="display: none;"></div>
                                	</div>
                                </div>
                             </div>   
                                                           
                              <div class="col-lg-7 form-group">
                                <label class="col-lg-3 control-label">Art Work</label>
                                <div class="col-lg-9">
                                    <div class="row" id="image-row-1">
                                        <div class="col-lg-9 media more_image" >
                                            <div class="bg-light pull-left text-center media-large thumb-large" id="display-image-1">
                                                 <img src= "<?php echo HTTP_SERVER.'images/blank-user64x64.png'; ?>" class="img-rounded" alt="">
                                            </div>
                                            <div class="media-body">
                                                <input type="file" name="art_image" id="art_image_1" title="Change" class="btn btn-sm btn-info m-b-small" />
                                                <br>
                                                <button class="btn btn-success btn-xs" onClick="uploadImage(1);" type="button"><i class="fa fa-upload"></i> Upload</button>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <a class="btn btn-success btn-xs btn-circle addmore-image" data-toggle="tooltip" data-placement="top" title="Add Image" ><i class="fa fa-plus"></i></a>
                                        </div>
                                   </div>
                                   
                                   <div id="more-img-div"></div>          
                                </div>
                             </div>
                              
                              
                             <div class="form-group">
                                <div class="col-lg-9 col-lg-offset-3">
                                  <button type="button" id="btn-add-product" class="btn btn-primary">Add Product</button>	
                                </div>
                             </div>
                             
                             <div id="display-product"></div>
                             
                             
                             
                             <div class="form-group">
                                <div class="col-lg-9 col-lg-offset-3">
                                  <button type="submit" name="btn_save" class="btn btn-primary">Save</button>
                                  <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a>  
                                </div>
                             </div>
                             
                         </div> 
        
                    </div>
                </form>
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
        jQuery("#order-form").validationEngine();
		setLayerHtml($("#layer").val());
		$("#layer").change(function(){
			var layerid = $(this).val();
			setLayerHtml(layerid);
		});
		
		$(".quantity_in").hide();
		$("#product").change(function(){
			var val = $(this).val();
			var text = $("#product option[value='"+val+"']").text().toLowerCase();
			//alert(text);
			if(text === "roll"){
				$(".gusset").hide();
				$(".option").hide();
				$(".quantity_in").show();
				$(".heightb").html("Repeat Length");
				$("#btn_generate").attr('name','btn_rgenerate');
				setQuantityHtml('r');
			}else{
				$(".gusset").show();
				$(".option").show();
				$(".quantity_in").hide();
				$(".heightb").html("Height");
				$("#btn_generate").attr('name','btn_generate');
				setQuantityHtml('p');
			}
		});
		
		setQuantityHtml('p');
		$(".currencycls").hide();
		$("input[name=customer_check]").click(function(){
			$("#loading").show();
			if($(this).prop('checked') == true){
				var chtml = '';
				chtml += '<div class="form-group gusset">';
					chtml += '<label class="col-lg-3 control-label"><span class="required">*</span>Customer Email</label>';
					chtml += '<div class="col-lg-6">';
					chtml += '<input type="text" name="customer_email" value="" class="form-control validate[required,custom[email]]">';
					chtml += '</div>';
				chtml += '</div>';
				chtml += '<div class="form-group gusset">';
					chtml += '<label class="col-lg-3 control-label">Customer Gress ( % )</label>';
					chtml += '<div class="col-lg-3">';
						chtml += '<input type="text" name="customer_gress" value="" class="form-control validate[custom[onlyNumberSp]]">';
					chtml += '</div>';
				chtml += '</div>';
				$(".currencycls").show();
			}else{
				var chtml = '';
				$(".currencycls").slideUp();
			}
			$("#cinfo").slideUp().html(chtml).slideDown();
			$("#loading").fadeOut();
		});
    });

//STart : layer
function setLayerHtml(layer){
	/*var html = '';
	$('#layerdiv').html('');
	for ( var i = 1; i <= layer; i++ ) {
		html += '<div class="form-group">';
		html += '<label class="col-lg-3 control-label">'+i+' Layer price / kg </label>';
		html += '	<div class="col-lg-4">';
		html += '  		<input type="text" name="layer_price[]" id="price_'+i+'" value="" class="form-control validate[required]">';
		html += '	</div>';
		html += '</div>';
	}
	$('#layerdiv').append(html);*/
	$("#loading").show();
	$('#layerdiv').html('');
	$.ajax({
		type: "POST",
		url: '<?php echo HTTP_ADMIN_CONTROLLER.$rout;?>/getLayerMaterial.php',
		dataType: 'json',
		data:'layer='+layer,
		success: function(json) {
			$('#layerdiv').append(json);
			$("#loading").hide();
		}
	});
}	
//Close : Layer

function getMaterialThickness(material_id,id){
	$("#loading").show();
	$.ajax({
		type: "POST",
		url: '<?php echo HTTP_ADMIN_CONTROLLER.$rout;?>/getMaterialThickness.php',
		dataType: 'json',
		data:'material_id='+material_id,
		success: function(json) {
			$('#thickness-dropdown-'+id).html(json);
			$("#loading").hide();
		}
	});
}

function reloadPage(){
	location.reload();
}


$("#same-above").click(function(){
	$("#loading").show();
	if($(this).prop('checked') == true){
		$("#billing-details").slideUp('slow');
	}else{
		$("#billing-details").slideDown('slow');
	}
	//$("#cinfo").slideUp().html(chtml).slideDown();
	$("#loading").fadeOut();
});

function setQuantityHtml(type){
	$("#loading").show();
	var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getQuantity', '',1);?>");
	var quantity_in;
	if(type=='r'){
		quantity_in = $('#quantity_type').val(); 	
	}else{
		quantity_in = '';	
	}
	var product_id = $('#product').val();
	$.ajax({
		type: "POST",
		url: status_url,
		dataType: 'json',
		data:{type:type,quantity_in:quantity_in,product_id:product_id},
		success: function(json) {
			$("#squantity").empty();
			$("#squantity").append(json['display']);
			
			if(json['gusset_available']==0){
				$('.gusset').hide();	
			}

			//$("input:checkbox").checkbox();
			$("#loading").hide();
		}
	});
}


$('#quantity_type').change(function(){
	$(".gusset").hide();
	$(".option").hide();
	$(".quantity_in").show();
	$(".heightb").html("Repeat Length");
	$("#btn_generate").attr('name','btn_rgenerate');
	setQuantityHtml('r');	
});


function updateQuantity(product_id){
	alert($('#edit-quantity-'+product_id).val());
}

function removeProduct(product_id){
	
	var remove_product_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=removeProduct', '',1);?>");
	$.ajax({
		url : remove_product_url,
		method : 'post',
		data : {product_id : product_id},
		success: function(response){
			$('#display-product').html(response);
			$('#more-img-div').html('');
			//$('#display-image-1 img').remove();
			$('#display-image-1 img').attr('src','<?php echo HTTP_SERVER.'images/blank-user64x64.png'; ?>');
		},
		error: function(){
			return false;	
		}
	});
}

$('#btn-add-product').click(function(){
	
	
	if($("#order-form").validationEngine('validate')){
          
		var add_product_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=addProduct', '',1);?>");
		
		$.ajax({
			url : add_product_url,
			method : 'post',
			data : $('#order-form input,#order-form select'),
			success: function(response){
				
				if(response != 0){
				   $('#display-product').html(response);
				   $('#more-img-div').html('');
					//$('#display-image-1 img').remove();
				   $('#display-image-1 img').attr('src','<?php echo HTTP_SERVER.'images/blank-user64x64.png'; ?>');
				}
			},
			error: function(){
				//$("html, body").animate({scrollTop:0},600);
				return false;
			}
		});
	}else{
		return false;
	}
});

function uploadImage(count){
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajaximage', '',1);?>");
	$('#loading').show();
	
	var file_data = $("#art_image_"+count).prop("files")[0];   // Getting the properties of file from file field
	var form_data = new FormData();                  // Creating object of FormData class
	form_data.append("file", file_data)              // Appending parameter named file with properties of file_field to form_data
	form_data.append("product_id", $('#product').val())                 // Adding extra parameters to form_data
	form_data.append("image_id",count)
	$.ajax({
		url: url,
		dataType: 'script',
		cache: false,
		contentType: false,
		processData: false,
		data: form_data,                         // Setting the data attribute of ajax with file_data
		type: 'post',
		success : function(response){
			if(response!=0){
				$('#display-image-'+count+' img').attr('src',JSON.parse(response));
				$('#loading').remove();
			}else{
				$('#loading').remove();
			}
		}
   });
}

function removeImage(count){
	
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=removeImage', '',1);?>");
	$('#loading').show();
	
	$.ajax({
		url: url,
		data: {image_id : count, product_id:$('#product').val()},       
		type: 'post',
		success : function(){
			$('#loading').remove();
			$('#image-row-'+count).remove();
		}
	});
}

$('.addmore-image').click(function(){
	
	var total_count = parseInt( $(".more_image").size()) + 1;
	
	var html = '';	
	
	html += '<div class="row" style="margin-top:10px;" id="image-row-'+total_count+'">';
	
		html += '<div class="col-lg-9 media more_image">';  
		
		  html +=  '<div class="bg-light pull-left text-center media-large thumb-large" id="display-image-'+total_count+'">';
				html +='<img src= "<?php echo HTTP_SERVER.'images/blank-user64x64.png'; ?>" class="img-rounded" alt="">';
		  html += '</div>';
		  
		  html += '<div class="media-body">';
		    html += '<input type="file" name="art_image" id="art_image_'+total_count+'" title="Change" class="btn btn-sm btn-info m-b-small" />';	  
			html += '<button type="button" onClick="uploadImage('+total_count+')" class="btn btn-success btn-xs"><i class="fa fa-upload"></i> Upload</button>';
		  html += '</div>';
		  
	   html +='</div>';
		
	   html +='<div class="col-lg-3">';
		 html +='<a class="btn btn-danger btn-xs btn-circle" data-toggle="tooltip" data-placement="top" onClick="removeImage('+total_count+');" title="Remove Image" ><i class="fa fa-minus"></i></a>';
	   html +='</div>';
	
   html +='</div>';
   
   $('#more-img-div').append(html);
	
});

</script> 
<!-- Close : validation script -->

<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>