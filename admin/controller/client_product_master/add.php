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
$edit='';
if(isset($_GET['client_product_id']) && !empty($_GET['client_product_id']))
{
	$productId=decode($_GET['client_product_id']);
	$product = $obj_product->getProduct($productId);
	//printr($product);
	$edit = true;
}

if($display_status){

	$product_type_id = base64_decode($_GET['product_type_id']);
		
	if($product_type_id == '1')
		$type='Plastic';
	elseif($product_type_id == '2')
		$type='Paper';
	else
		$type='Paper With Oval window';

	//insert user
	if(isset($_POST['btn_save'])){
		$post = post($_POST);
		$insert_id = $obj_product->addproduct($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, 'mod=combination&product_type_id='.encode($product_type_id), '',1));
	}
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		$productId=decode($_GET['client_product_id']);
		$obj_product->updateproduct($productId,$post);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, 'mod=combination&product_type_id='.encode($product_type_id), '',1));
		
	}
$bradcums[] = array(
	'text' 	=> $type.' List',
	'href' 	=> $obj_general->link($rout, '', '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> $display_name.' Detail',
	'href' 	=> $obj_general->link($rout, 'mod=combination&product_type_id='.encode($product_type_id), '',1),
	'icon' 	=> 'fa-edit',
	'class'	=> '',
);
//Close : bradcums

//Start : edit

//Close : edit
$bradcums[] = array(
	'text' 	=> 'Enquiry Detail List',
	'href' 	=> '',
	'icon' 	=> 'fa-list',
	'class'	=> 'active',
);


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
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data" >
              
              <div class="form-group">
              	 <label class="col-lg-3 control-label "><span class="required">*</span>Product Description</label>
                <div class="col-lg-8">
                  <input type="text" name="product_desc" id="product_desc" value="<?php echo isset($product['product_desc'])?$product['product_desc']:'';?>" class="form-control validate[required]" placeholder="Product Description">
                   <input type="hidden" name="product_type_id" value="<?php echo $product_type_id;?>">
                </div>
              </div>
              
               <div class="form-group">
                        <label class="col-lg-3 control-label "><span class="required">*</span> Select Product</label>
                        <div class="col-lg-8">
                            <?php $products = $obj_product->getActiveProduct();?>
                            <select name="product" id="product" class="form-control validate[required]">
                            <option value="">Select Product</option>
                                <?php
                                foreach($products as $pro){ ?>
                                    <option value="<?php echo $pro['product_id']; ?>"
									<?php if(isset($product['product_id']) && ($pro['product_id'] == $product['product_id'])) {
											echo 'selected="selected"';
										}?> > <?php echo $pro['product_name']; ?></option>
                                    <?php } ?> 
                                
                            </select>
                        </div>
              </div>
              
               <div  class="form-group" id="zipper_div">
                        <label class="col-lg-3 control-label">Zipper</label>
                            <div class="col-lg-9">
                            <?php
                            $zippers = $obj_product->getActiveProductZippers();
							foreach($zippers as $zipper){
                            ?>
                            <div style="float:left;width: 200px;">
                            <label  style="font-weight: normal;">
                     			<input type="radio" name="zipper" value="<?php echo $zipper['product_zipper_id']; ?>" class="zipper"
                    				 <?php  if(isset($product['zipper_id']) && ($zipper['product_zipper_id'] == $product['zipper_id'])) {
										 echo 'checked="checked"'; }elseif($zipper['product_zipper_id'] == '2'){ 
					  					echo 'checked="checked"';}?>  onclick="showSize(0)" />
							 <?php echo $zipper['zipper_name']; ?>
                            </label>
                         </div>
                       <?php } ?>
                    </div>
               </div>
              
               <?php /*?><div class="form-group" id="valve_div" >
                        <label class="col-lg-3 control-label">Valve</label>
                        <div class="col-lg-9">
                        	<div  style="float:left;width: 200px;">
                                <label  style="font-weight: normal;">
                                  <input type="radio" name="valve" id="nv" value="No Valve" checked="checked"  class="valve"
                                  <?php if(isset($product['valve']) && ($product['valve'] == 'No Valve')) {
									  echo 'checked="checked"'; } ?> >
                                 	No Valve
                                </label>
                            
                                <label style="font-weight: normal;">
                                <input type="radio" name="valve" id="wv" value="With Valve" value="valve" class="valve"
                                    <?php if(isset($product['valve']) && ($product['valve'] == 'With Valve')) {
									  echo 'checked="checked"'; }?> />
                              	With Valve
                                </label>
                          </div> 
                        </div>
                 </div><?php */?>
              
               <?php $spouts = $obj_product->getActiveProductSpout();?>
                  <div  class="form-group" id="spout_div">
                     <label class="col-lg-3 control-label">Spout</label>
                      <div class="col-lg-9">
                      <?php $spoutsTxt = '';
					  foreach($spouts as $spout){ ?>
                      <div  style="float:left;width: 200px;">
                      <label  style="font-weight: normal;">
                      <input type="radio" name="spout" class="spout" id="spout" value="<?php echo ($spout['product_spout_id']); ?>"<?php if(isset($product['spout_id']) && ($product['spout_id'] == ($spout['product_spout_id']))) {  echo 'checked="checked"'; }elseif($spout['product_spout_id'] == '1'){ 
					  echo 'checked="checked"';}?> />
                      <?php echo $spout['spout_name'];?>
                      </label>
                      </div>
                      <?php }?>
					</div>
					</div>
              
              <?php $accessories = $obj_product->getActiveProductAccessorie();?>
                  	<div  class="form-group" id="acce_div">
                         <label class="col-lg-3 control-label">Accessorie</label>
                              <div class="col-lg-9">
								  <?php $accessorieTxt = '';
                                  foreach($accessories as $accessorie){ ?>
                                      <div  style="float:left;width: 200px;">
                                          <label  style="font-weight: normal;">
                                          <input type="radio" name="accessorie" class="accessorie" id="accessorie" 
                                          value="<?php echo $accessorie['product_accessorie_id']; ?>"
                                          <?php if(isset($product['accessories_id']) && ($product['accessories_id'] == ($accessorie['product_accessorie_id']))) { 
                                          echo 'checked="checked"'; }elseif ($accessorie['product_accessorie_id'] == '4' ){ 
                                          echo 'checked="checked"';}?> />
                                          <?php echo $accessorie['product_accessorie_name'];?>
                                           </label>
                                 	  </div>
                           <?php } ?>
                            </div>
						</div>
                        
                        <div class="line line-dashed m-t-large"></div>
       						
                            <div class="form-group" id="size_qty"> </div>
                        
                        <div class="line line-dashed m-t-large"></div>
              
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
                  	<button type="submit" name="btn_update" id="btn_update" class="btn btn-primary" onClick="return changeCaptcha(this.form);">Update </button>
                    <input type="hidden" id="edit_value" value="<?php echo $edit;?>" />
                    <input type="hidden" id="client_product_id" value="<?php echo $productId;?>" />
                <?php } else { ?>
                	<button type="submit" name="btn_save" id="btn_save" class="btn btn-primary"  onClick="return changeCaptcha(this.form);">Save </button>	
                <?php } ?>  
                  <a class="btn btn-default" href="<?php echo $obj_general->link($rout, 'mod=combination&product_type_id='.encode($product_type_id), '',1);?>">Cancel</a>
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
        // binds form submission and fields to the validation engine
        jQuery("#form").validationEngine();
		var edit_value = $("#edit_value").val();
		var client_product_id = $("#client_product_id").val();
			if(edit_value == '1')
				showSize(client_product_id);
    });
	
	$("#product").change(function(){
		showSize(0);
	});
	
	function showSize(id)
	{
		var product_id = $("#product").val();
		var zipper_id=$("input[class='zipper']:checked").val();
		
			var size_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&ajaxfun=getProductSize', '',1);?>");
			$.ajax({
				type: "POST",
				url: size_url,					
				data:{product_id:product_id,zipper_id:zipper_id,id:id},
				success: function(json) {
					if(json){
						$("#size_qty").html(json);
						
					}else{
						$("#size_qty").html('<span class="btn btn-danger btn-xs">Please select Product for Size option.</span>');
					}
				}
			});		
	}
</script> 
<!-- Close : validation script -->

<?php } else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>	              