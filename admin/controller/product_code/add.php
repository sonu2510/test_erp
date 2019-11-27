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
$quotation_no='';
$product_quotation_price_id =0;
if(isset($_GET['product_code_id']) && !empty($_GET['product_code_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$product_code_id = base64_decode($_GET['product_code_id']);
		$product_code = $obj_product_code->getProductCodeData($product_code_id);
		//printr($product_code);
		$edit = 1;
	}
	
}else if(isset($_GET['product_quotation_price_id']) && !empty($_GET['product_quotation_price_id'])){
  if(!$obj_general->hasPermission('edit',$menuId)){
    $display_status = false;
  }else{
    $product_quotation_price_id = base64_decode($_GET['product_quotation_price_id']);
    $quotation_no = base64_decode($_GET['quotation_no']);
   
    $product_code = $obj_product_code->getQuotationProductDetail($product_quotation_price_id);
 //    printr($product_code);die;
    $edit = 0;
  }
  
}
else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}
//Close : edit

if($display_status){
	//insert user
	if(isset($_POST['btn_save'])){
		$post = post($_POST);	
//	printr(	$_FILES['die_line']['name']);
	     $insert_id = $obj_product_code->addProductCode($post,$_FILES['die_line']['name']);
	
		$obj_session->data['success'] = ADD;
    	 if(isset($_GET['product_quotation_price_id']) && !empty($_GET['product_quotation_price_id'])){ 
          page_redirect($obj_general->link('custom_order', 'mod=add&quotation_no='.$_GET['quotation_no'], '',1));
        }else{    
          page_redirect($obj_general->link($rout, '', '',1));
        }
		
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		$product_code_id = $product_code['product_code_id'];
		
	//	if($_FILES['die_line']['name']==""){
		    //	$_FILES['die_line']['name']=$product_code['product_code_image'];
	//	}
		$obj_product_code->updateProductCode($product_code_id,$post,$_FILES['die_line']['name']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	$default_country=$obj_product_code->getUser($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']); 
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
        <?php // if(isset($_GET['proforma_id'])){ echo $pro_detail['invoice_date']; } elseif(isset($invoice)) { echo $invoice['invoice_date']; } else { echo '' ; } ; ?>
      <div class="col-sm-8">
        <section class="panel">
          <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
            
              <?php /*?><div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Product Code</label>
                <div class="col-lg-8">
                
                <?php  
				$value= isset($product_code['product_code'])?$product_code['product_code']:'';
				
				if($_SESSION['ADMIN_LOGIN_SWISS'] !='1' AND $_SESSION['LOGIN_USER_TYPE'] !='1') {
				//echo $product_code['product_code']; 
				$cust = explode("CUST",$value);
				//printr($cust);
				$value= isset($cust[1])?$cust[1]:'';
			 ?>
                <input value="CUST" name="CUST" readonly="readonly" size="6" style="margin-right:none; border-right:none;text-align:right;" onfocus="document.getElementById('product_code_field').focus ()">
				<?php  }// ?>
                  	<input type="text" name="product_code" id="product_code" value="<?php echo $value;?>" class=" validate[required]"><span id="exists" style="color:red;display:none;"></span> 
                </div>
              </div><?php */?> 
               
              <div class="form-group">
                
                 <label class="col-lg-3 control-label"><span class="required">*</span>Product Code</label>
                  <?php  
					$value= isset($product_code['product_code'])?$product_code['product_code']:'';
				
        			if($_SESSION['ADMIN_LOGIN_SWISS'] !='1' AND $_SESSION['LOGIN_USER_TYPE'] !='1') {
        					$cust = explode("CUST",$value);
        					$value= isset($cust[1])?$cust[1]:'';
        			 	?>
                        	<div class="col-lg-2">
                       		<input value="CUST" name="CUST" readonly="readonly" size="6" class="form-control" style="margin-right:none; border-right:none;text-align:right;" onfocus="document.getElementById('product_code_field').focus ()">
                            </div>
                    <?php } ?>
                   <div class="col-lg-3">
                	<input type="text" name="product_code" id="product_code" value="<?php echo $value;?>" class="form-control validate[required]"><span id="exists" style="color:red;display:none;"></span> 
                    </div>
                    
                    <?php 
					if($_SESSION['ADMIN_LOGIN_SWISS'] =='1' AND $_SESSION['LOGIN_USER_TYPE'] =='1') {?>
                      <div class="form-group">
                <label class="col-lg-1 control-label">DieLine <br/><small class="text-muted">(Only .pdf & .jpg format)</small></label>
                <div class="col-lg-3">                    
                    <div class="media-body">
                            <input type="file" name="die_line" id="die-line" title="<i class='fa fa-folder-open-o'></i> Browse" class="btn btn-sm btn-info m-b-small">
                    </div> 
                      <?php if($edit=='1'&& !empty($product_code['product_code_image'])){?>   <div ><?php
						   	$ext = pathinfo($product_code['product_code_image'], PATHINFO_EXTENSION);
							$html ='';
						//	printr($product_code['product_code_image']);
                           if($ext!='pdf')
											{
                                            	$html .='<p class=""><a href="'.HTTP_UPLOAD.'admin/product_code_image/'.$product_code['product_code_image'].'" target="_blank">
												<img class="" alt="Image" width="100" height="100" src="'.HTTP_UPLOAD.'admin/product_code_image/'.$product_code['dieline_name'].'"></a></p><a href="'.HTTP_UPLOAD.'admin/product_code_image/'.$product_code['product_code_image'].'" target="_blank">'.$product_code['product_code_image'].'</a>';
												
											}
											else
											{
												$html .='<p class=""><a href="'.HTTP_UPLOAD.'admin/product_code_image/'.$product_code['product_code_image'].'" target="_blank"><img class="" alt="Image" width="100" height="100" src="'.HTTP_UPLOAD.'admin/product_code_image/pdf.jpg"></a></p>
															<a href="'.HTTP_UPLOAD.'admin/product_code_image/'.$product_code['product_code_image'].'" target="_blank">'.$product_code['product_code_image'].'</a>';	
											}echo $html;?></div>  <?php }?>  
                    <div class="file-preview-die" style="display:none">
                       <div class="file-preview-thumbnails-die">                            
                       </div>
                       <div class="clearfix"></div>
                    </div>                    
                    <div id="append-dieline"></div>                
                </div>
              </div>
                <?php }?>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Description</label>
                <div class="col-lg-8">
                  	<input type="text" name="description" value="<?php echo isset($product_code['description'])?$product_code['description']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              
                	<div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span> Select Product</label>
                        <div class="col-lg-8">
                            <?php $products = $obj_product_code->getActiveProduct(); ?>
                            <select name="product" id="product" class="form-control validate[required]">
                            <option value="">Select Product</option>
                                <?php  foreach($products as $product){
                                    if(isset($product_code['product']) && $product_code['product'] == $product['product_id']){ ?>
                                        <option value="<?php echo $product['product_id']; ?>" selected="selected" ><?php echo $product['product_name']; ?></option>
                                    <?php }else{ ?>
                                        <option value="<?php echo $product['product_id']; ?>" <?php if(isset($product_code['product']) && ($product_code['product'] == $product['product_id'])) { echo 'selected="selected"';}?> > <?php echo $product['product_name']; ?></option>
                                    <?php }
                                } ?>
                                 <?php if(isset($product_code['product']))
									$id=$product_code['product'];
								elseif(isset($product['product_id']))
									$id=$product['product_id'];
								?>
                            <option value="11" <?php if(isset($id) && ($id  == '11')) echo  'selected="selected"';?>>Plastic Scoop</option>
                            </select>
                        </div>
                </div>
             
             	<div class="form-group option">
                    <label class="col-lg-3 control-label">Valve</label>
                    <div class="col-lg-9">
                        <div  style="float:left;width: 200px;">
                            <label  style="font-weight: normal;">
                              <input type="radio" name="valve" id="nv" value="No Valve" checked="checked"  class="valve"
                              <?php if(isset($product_code_id) && ($product_code['valve'] == 'No Valve')) { echo 'checked="checked"';} ?> >No Valve </label>                            
                           <label style="font-weight: normal;">
                                <input type="radio" name="valve" id="wv" value="With Valve" class="valve"  <?php if(isset($product_code_id) && ($product_code['valve'] == 'With Valve')) {
                                  echo 'checked="checked"'; }?> >With Valve </label>
                        </div> 
                    </div>
                </div>
                
                <div id="zipper_div">
                      <div class="form-group option"> <label class="col-lg-3 control-label">Zipper</label>
                            <div class="col-lg-9"><?php $zippers = $obj_product_code->getActiveProductZippers();
								foreach($zippers as $zipper){?>
                            		<div style="float:left;width: 200px;">
			                            <?php	if(isset($product_code['zipper'])) { ?>
                                         <input type="radio" name="zipper" id="zipper" value="<?php echo $zipper['product_zipper_id'];?>" <?php if($product_code['zipper'] == $zipper['product_zipper_id']) { ?> checked="checked"  <?php } ?> />
                                          <?php } else{?>
                                         <input type="radio" name="zipper" id="zipper" value="<?php echo $zipper['product_zipper_id'];?>" <?php   if($zipper['product_zipper_id']==1){ ?> checked="checked" <?php } ?> ><?php  }  echo $zipper['zipper_name'];?> 
		                            </div>
        	                    <?php } ?>
            	            </div>
                	    </div>                       
                    </div>
                    
                     <?php $spouts = $obj_product_code->getActiveProductSpout();?>
                  <div id="spout_div">
                    <div class="form-group option">   <label class="col-lg-3 control-label">Spout</label>
                          <div class="col-lg-9">
							  <?php 
								  foreach($spouts as $spout){ ?>
                                  <div style="float:left;width: 200px;">
			                            <?php	if(isset($product_code['spout'])) { ?>
                                         <input type="radio" name="spout" id="spout" value="<?php echo $spout['product_spout_id'];?>" <?php if($product_code['spout'] == $spout['product_spout_id']) { ?> checked="checked"  <?php } ?> />
                                          <?php } else{?>
                                         <input type="radio" name="spout" id="spout" value="<?php echo $spout['product_spout_id'];?>" <?php   if($spout['product_spout_id']==1){ ?> checked="checked" <?php } ?> ><?php  }  echo $spout['spout_name'];?> 
		                            </div>
                              <?php }?>
                        </div>
                       </div>
					</div>
                    
                    <?php $accessories = $obj_product_code->getActiveProductAccessorie();?>
                  	<div id="acce_div">
                          <div class="form-group option"> <label class="col-lg-3 control-label">Accessorie</label>
                              <div class="col-lg-9">
                              <?php $accessorieTxt = $check1 = $check2='';
								  foreach($accessories as $accessorie){ ?>
    								  <div  style="float:left;width: 200px;">
    								      <?php 
    								            
    								            if($accessorie['product_accessorie_id'] == 4 )
												{  ?>
											    	<input type="radio" name="accessorie[]" value="<?php echo $accessorie['product_accessorie_id'];?>" checked="checked" <?php if($product_code['accessorie'] == $accessorie['product_accessorie_id']) { ?> checked="checked"  <?php } ?> ><?php echo $accessorie['product_accessorie_name'] ;?><br>
										<?php    }else
												 {
												    if($accessorie['product_accessorie_id'] == 3 )
													{?>
														<input type="checkbox" name="accessorie[]" value="<?php echo $accessorie['product_accessorie_id'];?>" <?php if($product_code['accessorie_second'] == $accessorie['product_accessorie_id']) { ?> checked="checked"  <?php } ?> ><?php echo $accessorie['product_accessorie_name'] ;?><br
											<?php		}
													else if($accessorie['product_accessorie_id'] == 8)
													{ ?>
														<input type="checkbox" name="accessorie[]" value="<?php echo $accessorie['product_accessorie_id'];?>" <?php if($product_code['accessorie_second'] == $accessorie['product_accessorie_id']) { ?> checked="checked"  <?php } ?> ><?php echo $accessorie['product_accessorie_name'] ;?><br>
											<?php		}
													else if($accessorie['product_accessorie_id'] == 9)
													{ ?>
													<input type="checkbox" name="accessorie[]" value="<?php echo $accessorie['product_accessorie_id'];?>" <?php if($product_code['accessorie_second'] == $accessorie['product_accessorie_id']) { ?> checked="checked"  <?php } ?> ><?php echo $accessorie['product_accessorie_name'] ;?><br>
											<?php		}
													else
													{?>
														
														<input type="radio" name="accessorie[]" value="<?php echo $accessorie['product_accessorie_id'];?>"<?php if($product_code['accessorie'] == $accessorie['product_accessorie_id']) { ?> checked="checked"  <?php } ?>  ><?php echo $accessorie['product_accessorie_name'] ;?><br>
											<?php		}			
												} ?>
                                      </div>
								   <?php }?>
                            </div> 
                            </div>
						</div>
                        
                        <div class="form-group option">
                        	<label class="col-lg-3 control-label">Make Pouch</label>
                        	<div class="col-lg-9">
                                <?php $makes = $obj_product_code->getActiveMake();
                                foreach($makes as $make){?>
								<div style="float:left;width:200px;">
                                    <?php	if(isset($product_code['make_pouch'])) { ?>
											  <input type="radio" name="make" id="make" value="<?php echo $make['make_id'];?>" <?php if($product_code['make_pouch'] == $make['make_id']) { ?> checked="checked"  <?php } ?> />
                                     <?php } else{?>
                                         <input type="radio" name="make" id="make" value="<?php echo $make['make_id'];?>" <?php   if($make['make_id']==1){ ?> checked="checked" <?php } ?> >
                                     <?php  }  echo $make['make_name'];?> 
                                 </div>
                             	<?php  } ?>
                            	</div>
                          	</div>
                            
                            <?php //[kinjal] added if cond on 19-4-2017 for other user cond 
							if($_SESSION['ADMIN_LOGIN_SWISS'] =='1' AND $_SESSION['LOGIN_USER_TYPE'] =='1') { ?>
                           		 <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span> Select Color</label>
                        <div class="col-lg-6">
                            <?php $colors = $obj_product_code->getColor(); ?>
                            <select name="color" id="color" class="form-control validate[required]">
                            <option value="">Select Color</option>
                                <?php  foreach($colors as $color){
                                    if(isset($post['product']) && $post['product'] == $color['pouch_color_id']){ ?>
                                        <option value="<?php echo $color['pouch_color_id']; ?>" selected="selected" ><?php echo $color['color']; ?></option>
                                    <?php }else{ ?>
                                        <option value="<?php echo $color['pouch_color_id']; ?>" <?php if(isset($product_code_id) && ($product_code['color'] == $color['pouch_color_id'])) { echo 'selected="selected"';}?> > <?php echo $color['color']; ?></option>
                                    <?php }
                                } ?>
                                 <option value="-1"  <?php if(isset($product_code_id) && ($product_code['color'] == '-1')) { echo 'selected="selected"';}?>  >Custom</option>
                            </select>
                        </div>
                </div>
              				<?php } 
								else
								{?>
                                	<input type="hidden" name="color" id="color" value="-1" />
                                	<input type="hidden" name="product_quotation_price_id" id="product_quotation_price_id" value="<?php echo $product_quotation_price_id; ?>" />
                                    <input type="hidden" name="quotation_no" id="quotation_no" value="<?php echo $quotation_no; ?>" />
                                <?php }
								// end [kinjal]?>
								
								
								 <?php //[kinjal] added if cond on 4-7-2017 for other user cond 
							if($_SESSION['ADMIN_LOGIN_SWISS'] !='1' AND $_SESSION['LOGIN_USER_TYPE'] !='1') { ?>
                            
                            	<div class="form-group">
                                        	<label class="col-lg-3 control-label"><span class="required">*</span>Width</label>
                                                <div class="col-lg-3">
                                                <input type="text" name="width" id="width"  value="<?php echo isset($product_code['width'])?floatval($product_code['width']):''; ?>" class="form-control validate[required,custom[number],min[0.001]]" >
                                        
                            			</div>
                            	</div>
                                
                                <div class="form-group">
                                        	<label class="col-lg-3 control-label"><span class="required">*</span>Height</label>
                                                <div class="col-lg-3">
                                                <input type="text" name="height" id="height"  value="<?php echo isset($product_code['height'])?floatval($product_code['height']):''; ?>" class="form-control validate[required,custom[number],min[0.001]] " >
                                        
                            			</div>
                            	</div>
                                <div class="form-group">
                                        	<label class="col-lg-3 control-label">Gusset</label>
                                                <div class="col-lg-3">
                                                <input type="text" name="gusset" id="gusset"  value="<?php echo isset($product_code['gusset'])?floatval($product_code['gusset']):''; ?>" class="form-control " >
                                        
                            			</div>
                            	</div>
                                <?php } ?>
              
              <div class="form-group">
                  <label class="col-lg-3 control-label"><span class="required">*</span> Volume</label>
                    <div class="col-lg-3">
                       <input type="text" name="volume" id="volume" value="<?php echo isset($product_code['volume']) ? $product_code['volume'] : '' ; ?>" class="form-control validtae[required]" /> 
                     </div>
                  <label class="col-lg-3 control-label">Measurement</label>
                     <div class="col-lg-3">
                            <?php $measurement = $obj_product_code->getMeasurement(); ?>
                            <select name="measurement" id="measurement" class="form-control validate[required]">
                            <option value="">Select Measurement</option>
                                <?php  foreach($measurement as $mea){
                                    if(isset($post['product']) && $post['product'] == $mea['product_id']){ ?>
                                        <option value="<?php echo $mea['product_id']; ?>" selected="selected" ><?php echo $mea['measuremant']; ?></option>
                                    <?php }else{ ?>
                                        <option value="<?php echo $mea['product_id']; ?>" <?php if(isset($product_code_id) && ($mea['product_id'] == $product_code['measurement'])) { echo 'selected="selected"';}?> > <?php echo $mea['measurement']; ?></option>
                                    <?php }
                                } ?>
                            </select>
                        </div>
               </div>
              
             <div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                <div class="col-lg-4">
                  <select name="status" id="status" class="form-control">
                    <option value="1" <?php echo (isset($country['status']) && $country['status'] == 1)?'selected':'';?> > Active</option>
                    <option value="0" <?php echo (isset($country['status']) && $country['status'] == 0)?'selected':'';?>> Inactive</option>
                  </select>
                </div>
              </div>
              
              
              
               
            
               <?php 
			  	//printr($default_country['country_id']);
				if($default_country['country_id']=='111')
				{
			  ?>
              <div class="form-group">
             	<label class="col-lg-3 control-label"><span class="required">*</span> Box no</label>
                    <div class="col-lg-3">
                   
                       <input type="text" name="box_no" id="box_no" value="<?php echo isset($product_code['box_no']) ? $product_code['box_no'] : '' ; ?>" class="form-control validtae[required]" /> 
                     </div>
                     </div>
             <?php
			 }
			 ?>
			
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
		$("#product_code").change(function(e){
			var code = $(this).val();
			checkProductCode(code);
		});
    });
	function checkProductCode(code){
		var orgcode = '<?php echo isset($product_code['product_code'])?$product_code['product_code']:'';?>';
		if(<?php echo $_SESSION['ADMIN_LOGIN_SWISS'];?> !='1' && <?php echo $_SESSION['LOGIN_USER_TYPE'];?> !='1')
		    var code = 'CUST'+code;
		 
		 console.log(code);   
		if(code.length > 0 && orgcode != code){
			$(".uniqusername").remove();
			var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=ProductCodeExsist', '',1);?>");
			$("#loading").show();
			$.ajax({
				url : status_url,
				type :'post',
				data :{code:code},
				success: function(json) {
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
	

  var die_count = 0;

$('.media-body').on('change','#die-line',function(){
  die_count += 1;
  var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=uploadDieLine', '',1);?>");
  var die_html = '';
  var file_data = $("#die-line").prop("files")[0];          // Getting the properties of file from file field
  var form_data = new FormData();                            // Creating object of FormData class
  form_data.append("file", file_data)                   // Appending parameter named file with properties of file_field to form_data
  form_data.append("product_id", $('#product').val())        // Adding extra parameters to form_data
  form_data.append("die_id",die_count)
  $.ajax({
    url: url,
    dataType: 'json',
    cache: false,
    contentType: false,
    processData: false,
    data: form_data,                         // Setting the data attribute of ajax with file_data
    type: 'post',
    success : function(response){
      console.log(response);
      if(typeof response.ext != 'undefined'){
        if(response.ext == 'img'){
          die_html = '<div id="die-preview-'+die_count+'" class="file-preview-frame">';
            die_html +='<img class="file-preview-image" src="'+response.name+'">';
            die_html += '<a class="iremove" href="javascript:void(0);" onClick="removefile('+die_count+')">Remove</a>';      
          die_html += '</div>';
          $('.file-preview-die').show();
          $('.file-preview-thumbnails-die').append(die_html);
          $('#loading').remove();
        }else{
          die_html = '<div id="die-preview-'+die_count+'" class="file-preview-frame">';
            die_html +='<img class="file-preview-image" src="<?php echo HTTP_SERVER .'images/pdf_image.jpg'; ?>">';
            die_html += '<a class="iremove" href="javascript:void(0);" onClick="removefile('+die_count+')">Remove</a>';      
            die_html +='<div style="margin-top:8px;width:135px;">';
              if((response.name).length>15){
                die_html +='<a href="javascript:void(0)" style="text-align:left;">'+(response.name).substring(0,15)+'..'+'</a>';
            }else{
              die_html +='<a href="javascript:void(0)" style="text-align:left;">'+(response.name)+'</a>';
            }
            die_html +='</div>';
          die_html += '</div>';
          $('.file-preview-die').show();
          $('.file-preview-thumbnails-die').append(die_html);
          $('#loading').remove();
        }
      }else{
        $('#loading').remove();
        set_alert_message('Only .pdf And .jpg Formate Allow','alert-danger','fa fa-warning');
      }
    }
   });
});



</script> 
<!-- Close : validation script -->

<?php } else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>