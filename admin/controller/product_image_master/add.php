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
if(isset($_GET['product_image_id']) && !empty($_GET['product_image_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$product_image_id = base64_decode($_GET['product_image_id']);
		//$product = $obj_product_img->getProduct($product_id);
		$prod_img=$obj_product_img->getProductimg($product_image_id);
		//printr($prod_img);
		//die;
		$edit = 1;
	}
	
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}
//Close : edit

if($display_status){

	if(isset($_POST['btn_save'])){
	$post = post($_POST);
	$insert_id = $obj_product_img->addProductImage($post);
	$_SESSION['success'] = ADD;
	page_redirect($obj_general->link($rout, '', '',1));
	
	}
//edit
	if(isset($_POST['btn_update']) ){
		$post = $_POST;
		$product_image_id = base64_decode($_GET['product_image_id']);
		$obj_product_img->updateProduct($product_image_id,$post);
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
          <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post"  name="form" id="form"  enctype="multipart/form-data">
              
              <div class="form-group">
                        <label class="col-lg-3 control-label">Select Product</label>
                        <div class="col-lg-8">
                            <?php $products = $obj_product_img->getAllProducts(); ?>
                            <select name="product" id="product" class="form-control validate[required]">
                           <?php foreach($products as $product){ 
						   			if($product['product_id']==$prod_img['product_id'])
									{
						   		?>
                           			<option value="<?php echo $product['product_id']; ?>" selected="selected"><?php echo $product['product_name']; ?></option>
                           <?php }else{ ?>
                               <option value="<?php echo $product['product_id']; ?>"><?php echo $product['product_name']; ?></option>
                            <?php
								}	
							 } ?>
                            </select>
                     </div>
              </div>
              
                 <?php /*?> <div class="form-group option">
                    <label class="col-lg-3 control-label">Valve</label>
                    <div class="col-lg-9">
                        <div  style="float:left;width: 200px;">
                            <label  style="font-weight: normal;">
                              <input type="radio" name="valve" id="nv" value="No Valve" checked="checked"  class="valve"
                              <?php if(isset($prod_img['valve']) && ($prod_img['valve'] == 'No Valve')) { echo 'checked="checked"';} ?> >No Valve </label>                            
                           <label style="font-weight: normal;">
                                <input type="radio" name="valve" id="wv" value="With Valve" class="valve"  <?php if(isset($prod_img['valve']) && ($prod_img['valve'] == 'With Valve')) {
                                  echo 'checked="checked"'; }?> >With Valve </label>
                        </div> 
                    </div>
                </div>
				<div id="zipper_div">
                      <div class="form-group option"> <label class="col-lg-3 control-label">Zipper</label>
                            <div class="col-lg-9"><?php $zippers = $obj_product_img->getActiveProductZippers();
								foreach($zippers as $zipper){?>
                            		<div style="float:left;width: 200px;">
			                            <label  style="font-weight: normal;">
            						         <input type="radio" name="zipper" value="<?php echo encode($zipper['product_zipper_id']); ?>"   class="zipper"
						                     <?php if(isset($prod_img['zipper']) && $prod_img['zipper'] == encode($zipper['product_zipper_id'])) 
											 {echo 'checked="checked"';}
											 elseif(!isset($prod_img['zipper']) && (encode($zipper['product_zipper_id'])=='Mg=='))
											 { echo 'checked="checked"';} ?> >
											 <?php echo $zipper['zipper_name']; ?>
                        			    </label>
		                            </div>
        	                    <?php } ?>
            	            </div>
                	    </div>                       
                    </div>
                    
                    
                    <?php $spouts = $obj_product_img->getActiveProductSpout();?>
                  <div id="spout_div" <?php if(isset($invoice_product['product_id']) && $invoice_product['product_id']==0){ ?> style="display:none" <?php } ?>>
                     <label class="col-lg-3 control-label">Spout</label>
                      <div class="col-lg-9">
                      <?php $spoutsTxt = '';
					  foreach($spouts as $spout){ ?>
                      <div  style="float:left;width: 200px;">
                      <label  style="font-weight: normal;">
                      <input type="radio" name="spout" class="spout" id="spout" value="<?php echo encode($spout['product_spout_id']); ?>"
                      <?php if(isset($prod_img['spout']) && ($prod_img['spout'] == encode($spout['product_spout_id']))) { 
					  echo 'checked="checked"'; }elseif(!isset($prod_img['spout']) && (encode($spout['product_spout_id'])=='MQ==')){ 
					  echo 'checked="checked"';}?> />
                      <?php echo $spout['spout_name'];?>
                      </label>
                      </div>
                      <?php }?>
					</div>
					</div>
                    
                    
                      <?php $accessories = $obj_product_img->getActiveProductAccessorie();?>
                  	<div id="acce_div" <?php if(isset($invoice_product['product_id']) && $invoice_product['product_id']==0){ ?> style="display:none" <?php } ?>>
                     <label class="col-lg-3 control-label">Accessorie</label>
                      <div class="col-lg-9">
                      <?php $accessorieTxt = '';
					  foreach($accessories as $accessorie){ ?>
                      <div  style="float:left;width: 200px;">
                      <label  style="font-weight: normal;">
                      <input type="radio" name="accessorie" class="accessorie" id="accessorie" 
                      value="<?php echo encode($accessorie['product_accessorie_id']); ?>"
                      <?php if(isset($prod_img['accessorie']) && ($prod_img['accessorie'] == encode($accessorie['product_accessorie_id']))) { 
					  echo 'checked="checked"'; }elseif(!isset($prod_img['accessorie']) && (encode($accessorie['product_accessorie_id'])=='NA==')){ 
					  echo 'checked="checked"';}?> />
                      <?php echo $accessorie['product_accessorie_name'];?>
                       </label>
                       </div>
                       <?php }?>
						</div>
						</div>
                    
                   		<div class="form-group option">
                        	<label class="col-lg-3 control-label">Make Pouch</label>
                        	<div class="col-lg-9">
                                <?php $makes = $obj_product_img->getActiveMake();
                                foreach($makes as $make){?>
								<div style="float:left;width:200px;">
                                    <?php	if(isset($prod_img['make'])) { ?>
											  <input type="radio" name="make" id="make" value="<?php echo $make['make_id'];?>" <?php if($prod_img['make'] == $make['make_id']) { ?> checked="checked"  <?php } ?> />
                                     <?php } else{?>
                                         <input type="radio" name="make" id="make" value="<?php echo $make['make_id'];?>" <?php   if($make['make_id']==1){ ?> checked="checked" <?php } ?> >
                                     <?php  }  echo $make['make_name'];?> 
                                 </div>
                             	<?php  } ?>
                            	</div>
                          	</div>
              <?php */?>
               <div class="form-group">
					<label class="col-lg-3 control-label">Product Image</label>
                       <div class="col-lg-9">
                        <div class="media-body">
                            <input type="file" name="art_image" id="art-image"  class="custom-file-input">
                         </div>
                         <div class="file-preview" style="margin-top: 10px; <?php if(!isset($prod_img['product_image_url'])){ ?>display:none <?php } ?>">
                            <div class="file-preview-thumbnails">
								<?php  if(isset($prod_img['product_image_url'])){ ?>
                                   <div id="preview" class="file-preview-frame">
                                    <img class="file-preview-image" id="img1" src=" <?php echo $prod_img['product_image_url'];?>">
                                   </div>
                                  <?php }?>
                                            
                          	</div>
                             <input type="hidden" name="image_url" id="image_url" value="<?php if(isset($prod_img['product_image_url'])){ echo $prod_img['product_image_url']; } ?>"/>
                                   		<div class="clearfix"></div>
                                   		<div class="file-preview-status text-center text-success"></div>
                                   		<div class="kv-fileinput-error file-error-message" style="display: none;"></div>
                          </div>
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


<!-- Close : validation script -->

<style>

.custom-file-input::-webkit-file-upload-button {
  visibility: hidden;
}
.custom-file-input::before {
  content: 'Brows';
  display: inline-block;
  background: -webkit-linear-gradient(top, #5191d1, #5191d1);
  border: 1px solid #fff;
  border-radius: 3px;
  padding: 5px 8px;
  outline: none;
  white-space: nowrap;
  -webkit-user-select: none;
  cursor: pointer;
 // text-shadow: 1px 1px #fff;
  font-weight: 700;
  font-size: 10pt;
    color: white;
}
.custom-file-input:hover::before {
  border-color: #fff;
}
.custom-file-input:active::before {
  	background: -webkit-linear-gradient(top, #5191d1, #5191d1);
}
input[type="file"]{
   // opacity:1;
   height:30px;
    width:80px;
}



</style>
<script>


    jQuery(document).ready(function(){
var editor = CKEDITOR.replace( 'name', {
		height: '65px',
		removePlugins: 'elementspath',
		resize_enabled: false,
 toolbar: [ { name: 'colors', items: [ 'TextColor' ] },]}
);
		
        jQuery("#form").validationEngine();		
    });

$('#art-image').on('change',function(){	
	//debugger;
	//count += 1;
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajaximage&fun=ajaximage', '',1);?>");
	//$('#loading').show();
	var img_html = '';
	var file_data = $("#art-image").prop("files")[0];          // Getting the properties of file from file field
	//var file_data = $("#art-image").val();
	var form_data = new FormData();                            // Creating object of FormData class
	form_data.append("file", file_data)           			// Appending parameter named file with properties of file_field to form_data
	
	$.ajax({
		url: url,
		dataType: 'script',
		cache: false,
		contentType: false,
		processData: false,
		data: form_data,                         // Setting the data attribute of ajax with file_data
		type: 'post',
		success : function(response){
			//$('#loading').remove();
		//alert(response);
			if(response!=0){
				$('#image_url').val(JSON.parse(response));
				if($('#img1').length){
					$('#img1').each(function () {
									$(this).attr('src',JSON.parse(response));	
										
										});
					
					
					}
				else{
				img_html += '<div id="preview" class="file-preview-frame">';
                  img_html +='<img class="file-preview-image" src="'+JSON.parse(response)+'">';
                img_html += '</div>';
				
				
				
				$('.file-preview').show();
				$('.file-preview-thumbnails').append(img_html);
				//s$('#display-image-'+count+' img').attr('src',JSON.parse(response));
				}
			}else{
				$('#loading').remove();
			}
		}
   });
});




</script> 

<?php } else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>