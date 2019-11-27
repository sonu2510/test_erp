<?php

//jayashree
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
	'text' 	=> $display_name.' Add',
	'href' 	=> '',
	'icon' 	=> 'fa-edit',
	'class'	=> 'active',
);
//Close : bradcums

//Start : edit
$edit = '';


if(isset($_GET['pouch_id']) && !empty($_GET['pouch_id'])){
		$pouch_id = base64_decode($_GET['pouch_id']);
		//echo $pouch_id;
		$transport = $obj_boxmaster->getPouchData($pouch_id );
		//printr($transport);
		$edit = 1;
}
//Close : edit
$style = 'style="display:none;"';
//insert user
if(isset($_POST['btn_save'])){

	$post = post($_POST);
	if(isset($post['product'])){$product=$post['product'];}
	$insert_id = $obj_boxmaster->addPouch($post);
	$obj_session->data['success'] = ADD;
	page_redirect($obj_general->link($rout, 'mod=list&product_id='.encode($product), '',1));
	
	
}
if(isset($_GET['product_id'])){
		$p_id=base64_decode($_GET['product_id']);		
				
}
//edit
if(isset($_POST['btn_update']) && $edit){
	$post = post($_POST);
	$pouch_id = $transport['pouch_id'];
	$obj_boxmaster->updatePouchData($pouch_id ,$post);
	$obj_session->data['success'] = UPDATE;
	page_redirect($obj_general->link($rout, 'mod=list&product_id='.encode($post['product']), '',1));
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
         <?php $product_id = base64_decode($_GET['product_id']); 
		 $product_nm = $obj_boxmaster->getProductName($product_id); ?>
          <header class="panel-heading bg-white"> <?php echo ucwords($product_nm['product_name'])?> Detail </header>
          <div class="panel-body">
          
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
             <input type="hidden" id="pouch_idd" value="<?php echo $pouch_id;?>" />
               <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span> Select Product</label>
                        <div class="col-lg-8">
                            <?php
                            $products = $obj_boxmaster->getActiveProduct();
                            if(isset($transport['product_id'])){
							$p_id=$transport['product_id'];
							}
                          ?>
                          <input type="hidden" name="product" value="<?php echo $product_nm['product_id']; ?>">
                           <select name="product" id="product" class="form-control validate[required]" disabled="disabled">
                            <option value="">Select Product</option>
                              <option value="<?php echo $product_nm['product_id']; ?>" selected="selected" > <?php echo $product_nm['product_name']; ?></option>
                            </select>
                        </div>
              </div>
              <!-- rohit -->
              <div class="form-group option">
                        <label class="col-lg-3 control-label">Valve</label>
                        <div class="col-lg-9">
                        	<div  style="float:left;width: 200px;">
                                <label  style="font-weight: normal;">
                                <input type="hidden" name="va" value=" <?php if(isset($transport))echo $transport['valve'];?> "  />
                                  <input type="radio" name="valve" id="nv" value="No Valve" checked="checked"  class="valve"
                                  <?php if(isset($transport) && $transport['valve'] == 'No Valve') { echo 'checked="checked"'; } ?>
                                   />
                                 No Valve
                              </label>
                            
                                <label  style="font-weight: normal;">
                                  	<input type="radio" name="valve" id="wv" value="With Valve" value="valve" class="valve" 
                                    <?php if(isset($transport) && $transport['valve'] == 'With Valve') { echo 'checked="checked"'; } ?>
                                    />
                              	With Valve
                              </label>
                          </div> 
                        </div>
                      </div>
                    
                      <div id="zipper_div">
                      <?php
					  if(isset($p_id)) {
						  $zipper_available = $obj_boxmaster->checkProductzipper($p_id);
						  ?>
                        <div class="form-group option"> <label class="col-lg-3 control-label">Zipper</label>
                        	<div class="col-lg-9">
								<?php
                                $zippers = $obj_boxmaster->getActiveProductZippers();
								
                                $ziptxt = '';
                                foreach($zippers as $zipper){
									
                                ?>
                                <div   style="float:left;width: 200px;">
                        			<label  style="font-weight: normal;">
                                   <input type="hidden" name="zip" value="<?php if(isset($transport))echo $transport['zipper']; ?>"  />
									<?php
                                    if($zipper_available==0 )
                                    { 	
										if( $zipper['product_zipper_id']==2)
										{ ?>
											<input type="radio" name="zipper" value="<?php echo encode($zipper['product_zipper_id']); ?>"   onclick="showSize()"  class="zipper" checked="checked" >
										<?php echo $zipper['zipper_name'];
										}
										else
										{?>
											<input type="radio" name="zipper" value="<?php echo encode($zipper['product_zipper_id']); ?>"   disabled="disabled" class="zipper" <?php if(isset($transport) && $transport['zipper'] == encode($zipper['product_zipper_id'])) { echo 'checked="checked"'; } ?>>
										<?php echo $zipper['zipper_name'];
										}
										
                                    }
                                    else
                                    {
										if(isset($transport)) { ?>
                                        
											<input type="radio" name="zipper" class="zipper" value="<?php echo encode($zipper['product_zipper_id']); ?>" onclick="showSize()"
												<?php if(isset($transport) && $transport['zipper'] == encode($zipper['product_zipper_id'])) { echo 'checked="checked"'; } ?> >
												<?php echo $zipper['zipper_name'];
										 
										} else {

											if( $zipper['product_zipper_id']==2)
											{ ?>
												<input type="radio" name="zipper" value="<?php echo encode($zipper['product_zipper_id']); ?>"   checked="checked" onclick="showSize()"  class="zipper"
												<?php if(isset($transport) && $transport['zipper'] == encode($zipper['product_zipper_id'])) { echo 'checked="checked"'; } ?> >
											<?php echo $zipper['zipper_name'];
											}
											else
											{  ?>
												<input type="radio" name="zipper" class="zipper" value="<?php echo encode($zipper['product_zipper_id']); ?>" onclick="showSize()"
												<?php if(isset($transport) && $transport['zipper'] == encode($zipper['product_zipper_id'])) { echo 'checked="checked"'; } ?> >
											<?php echo $zipper['zipper_name'];
											}
										}
                                    } ?>
                        			</label>
                        		</div>
                        	<?php }?>
                        </div></div>
                        <?php } ?>
					</div>
                    
                       <?php
                      $spouts = $obj_boxmaster->getActiveProductSpout();
					  if($spouts){
						  ?>
                      	  <div class="form-group option">
                                <label class="col-lg-3 control-label">Spout</label>
                                 <input type="hidden" name="spo" value="<?php if(isset($transport))echo $transport['spout']; ?>"  />
                                <div class="col-lg-9">
                                   <?php
                                   $spoutsTxt = '';
                                    foreach($spouts as $spout){ ?>
                                       <div  style="float:left;width: 200px;">
                                            <label  style="font-weight: normal;">
    											                                        
												<?php
												if(isset($transport)) { ?>
                                                <input type="radio" name="spout" class="spout" id="spout" value="<?php echo encode($spout['product_spout_id']); ?>" 
														<?php if(isset($transport) && $transport['spout'] == encode($spout['product_spout_id'])) { echo 'checked="checked"'; } ?> >
												<?php }
												else {
													if($spout['product_spout_id'] == 1 )
													{ ?>
														<input type="radio" name="spout" class="spout" id="spout" value="<?php echo encode($spout['product_spout_id']); ?>" checked="checked" >
													<?php }
													else 
													{ ?>
														<input type="radio" name="spout" class="spout" id="spout" value="<?php echo encode($spout['product_spout_id']); ?>" 
														<?php if(isset($transport) && $transport['spout'] == encode($spout['product_spout_id'])) { echo 'checked="checked"'; } ?>
														>
													<?php  }
												}
												echo $spout['spout_name']; ?></label>
                                  </div>
                                    <?php } ?>
                                </div>
              </div>
                      	  	<?php
					  	} ?>
                        
                         <?php
						  $accessories = $obj_boxmaster->getActiveProductAccessorie();
						  if($accessories){
							  ?>
							  <div class="form-group option">
									<label class="col-lg-3 control-label">Accessorie</label>
                                    <input type="hidden" name="acce" value="<?php if(isset($transport))echo $transport['accessorie']; ?>"  />
									<div class="col-lg-9">
									   <?php
									   $accessorieTxt = '';
										foreach($accessories as $accessorie){ 
										?>
                                        
										   <div style="float:left;width: 200px;">
												<label  style="font-weight: normal;">
                                               
												<?php
                                                if(isset($transport)) { ?>
                                                <input type="radio" name="accessorie" value="<?php echo encode($accessorie['product_accessorie_id']); ?>"
														<?php if(isset($transport) && $transport['accessorie'] == encode($accessorie['product_accessorie_id'])) { echo 'checked="checked"'; } ?> >
												<?php }
												else {
													if($accessorie['product_accessorie_id'] == 4 )
													{ ?>
														<input type="radio" name="accessorie" value="<?php echo encode($accessorie['product_accessorie_id']); ?>" checked="checked" >
													<?php }
													else
													{ ?>
														<input type="radio" name="accessorie" value="<?php echo encode($accessorie['product_accessorie_id']); ?>"
														<?php if(isset($transport) && $transport['accessorie'] == encode($accessorie['product_accessorie_id'])) { echo 'checked="checked"'; } ?> >
													<?php }
												}
												echo $accessorie['product_accessorie_name']; ?>
												</label>
									  </div>
										<?php }	?>
									</div>
			  </div>
								<?php
							} ?>
                            
                             <div class="form-group option">
                        <label class="col-lg-3 control-label">Make Pouch</label>
                         <input type="hidden" name="mk" value="<?php if(isset($transport))echo $transport['make_pouch']; ?>"  />
                        <div class="col-lg-9">
                                <?php
                                $makes = $obj_boxmaster->getActiveMake();
                                //printr($effects);die;
                                foreach($makes as $make){?>
								<div  style="float:left;width: 200px;">
                                 <label  style="font-weight: normal;">
								<?php	if(isset($transport['make_pouch']))
									{
										if($transport['make_pouch'] == $make['make_id']) {?> 
											<input type="radio" name="make" id="make" value="<?php echo $make['make_id'];?>" checked="checked" >
										<?php }
											else{?> 
                                		  	<input type="radio" name="make" id="make" value="<?php echo $make['make_id'];?>"  >
										<?php
										}
									}
									else
									{ if($make['make_id']==1){?>
                            		    <input type="radio" name="make" id="make" value="<?php echo $make['make_id'];?>" checked="checked"  >
                                 <?php }
								 		else{?>
                                        <input type="radio" name="make" id="make" value="<?php echo $make['make_id'];?>"  >
									<?php
											}
									}
									echo $make['make_name'];?> 
                                    </div>
									<?php
									
								}
                                ?>
                            </div>
                        </div>
              <!-- /rohit -->
              
               <div class="form-group option">
                     <label class="col-lg-3 control-label">Mode of Shipment</label>
                        	<div class="col-lg-9">                
                        		<div  style="float:left;width: 200px;" id="byair">
                               	 	<label  style="font-weight: normal;">
                                  	<input type="radio" name="transport" id="tran1" value="air" checked="checked" <?php if(isset($_GET['pouch_id']) && (decode($transport['transportation']) == 'air')) { ?> checked="checked" <?php } ?> >
                              	  	By Air
                                 	</label>
                             	</div>
                             	<div style="float:left;width: 200px;" id="bysea">
                               		 <label  style="font-weight: normal;">
                                  	<input type="radio" name="transport" id="trans2" value="sea" <?php if(isset($_GET['pouch_id']) && (decode($transport['transportation']) == 'sea')) { ?> checked="checked" <?php } ?> >
                              			By Sea
                                 	</label>
                              	</div>
                             	 <div style="float:left;width: 200px;" id="byroad" >
                                		<label  style="font-weight: normal;">
                                  		<input type="radio" name="transport" id="trans3" value="road"  <?php if(isset($_GET['pouch_id']) && (decode($transport['transportation']) == 'road')) { ?> checked="checked" <?php } ?> >
                              			By Road
                                 	</label>
                              	</div> 
                       		 </div>
                      </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Pouch Volume</label>
                 <input type="hidden" name="pouch_v" value="<?php if(isset($transport))echo $transport['pouch_volume']; ?>"  />
                <div class="col-lg-4">
                	<input type="text" name="pouch_volume" value="<?php echo isset($transport) ? $transport['pouch_volume'] : '' ; ?>" placeholder="Pouch Volume" id="pouch_volume" class="form-control validate[required]" />
                </div>
                <div class="col-lg-8" style="width:300px">
                	 <?php $measurement = $obj_boxmaster->getMeasurement();
                            ?>
                            <input type="hidden" name="pouch_v_type" value="<?php if(isset($transport))echo $transport['pouch_volume_type']; ?>"  />
                            <select name="pouch_volume_type" id="pouch_volume_id" class="form-control validate[required]" >
                               <option value="">Select Measurement</option>
                                <?php
							
					            foreach($measurement as $meas){ 
								?>
                                 <?php if(isset($transport['pouch_volume_type']) && $meas['product_id'] == $transport['pouch_volume_type']) { ?>
                                        <option value="<?php echo $meas['product_id']; ?>" selected="selected"><?php echo $meas['measurement']; ?></option>
                                          <?php }
										  else
										  {?>
                                            <option value="<?php echo $meas['product_id']; ?>"><?php echo $meas['measurement']; ?></option>
                              <?php   }
							  }
								?>
                            </select>
                            
                 
                </div>

              </div>
              <div class="line line-dashed m-t-large"></div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Stock Quantity</label>
                <div class="col-lg-4">
                	<input type="text" name="pouch_quantity" value="<?php echo isset($transport) ? ($transport['quantity']) : '' ; ?>" placeholder="Quantity" id="pouch_quantity" class="form-control validate[required,custom[onlyNumberSp]]" />
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Stock Box Weight</label>
                <div class="col-lg-4">
                	<input type="text" name="box_weight" value="<?php echo isset($transport) ? $transport['box_weight'] : '' ; ?>" placeholder="Box Weight" id="box_weight" class="form-control validate[required,custom[number]]" />
                </div>
                <div class="col-lg-8" style="width:300px">
                <select name="box_weight_type" id="box_weight_com" class="form-control validate[required]" >
                               <option value="">Select Measurement</option>
                                <?php 
					            foreach($measurement as $meas){ ?>
                                      <?php 
										if((isset($transport['box_weight_type'])) && ($meas['product_id'] == $transport['box_weight_type'])) { ?>    
                                        <option value="<?php echo $meas['product_id']; ?>"
                                       selected="selected" 
                                         ><?php echo $meas['measurement']; ?></option>
										 <?php }  else
										  {?>
                                            <option value="<?php echo $meas['product_id']; ?>"><?php echo $meas['measurement']; ?></option>
                              <?php   }
							   }
								?>
                            </select>
                </div>
              </div>
              
              
               <div class="form-group">
                <label class="col-lg-3 control-label">Stock Net Weight</label>
                <div class="col-lg-4">
                	<input type="text" name="net_weight" value="<?php echo isset($transport) ? $transport['net_weight'] : '' ; ?>" placeholder="Net Weight" id="net_weight" class="form-control validate[required,custom[number]]" />
                </div>
                <div class="col-lg-8" style="width:300px">
                <select name="net_weight_type" id="net_weight_type" class="form-control validate[required]" >
                               <option value="">Select Measurement</option>
                                <?php 
					            foreach($measurement as $meas)
								{
									$stk_net_weight= isset($transport['net_weight_type'])?$transport['net_weight_type']:'1';  ?>
                                      <?php 
										if($stk_net_weight == $meas['product_id']) 
										{ ?>    
                                        		<option value="<?php echo $meas['product_id']; ?>" selected="selected" > <?php echo $meas['measurement']; ?></option>
								  <?php }  
										else
										{?>
                                            <option value="<?php echo $meas['product_id']; ?>"><?php echo $meas['measurement']; ?></option>
                              <?php     }
							    }
								?>
                            </select>
                </div>
              </div>
              
              <div class="line line-dashed m-t-large"></div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Custom Quantity</label>
                <div class="col-lg-4">
                	<input type="text" name="cust_quantity" value="<?php echo isset($transport) ? ($transport['cust_quantity']) : '' ; ?>" placeholder="Custom Quantity" id="cust_quantity" class="form-control validate[required,custom[onlyNumberSp]]" />
                </div>
              </div>
            
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Custom Box Weight</label>
                <div class="col-lg-4">
                	<input type="text" name="cust_box_weight" value="<?php echo isset($transport) ? $transport['cust_box_weight'] : '' ; ?>" placeholder="Custom Box Weight" id="cust_box_weight" class="form-control validate[required,custom[number]]" />
                </div>
                <div class="col-lg-8" style="width:300px">
                <select name="cust_box_weight_type" id="cust_box_weight_com" class="form-control validate[required]" >
                               <option value="">Select Measurement</option>
                                <?php 
					            foreach($measurement as $meas){ ?>
                                      <?php 
										if((isset($transport['cust_box_weight_type'])) && ($meas['product_id'] == $transport['cust_box_weight_type'])) { ?>    
                                        <option value="<?php echo $meas['product_id']; ?>"
                                       selected="selected" 
                                         ><?php echo $meas['measurement']; ?></option>
										 <?php }  else
										  {?>
                                            <option value="<?php echo $meas['product_id']; ?>"><?php echo $meas['measurement']; ?></option>
                              <?php   }
							   }
								?>
                            </select>
                </div>
              </div>
             
            
              
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Custom Net Weight</label>
                <div class="col-lg-4">
                	<input type="text" name="cust_net_weight" value="<?php echo isset($transport) ? $transport['cust_net_weight'] : '' ; ?>" placeholder="Net Weight" id="cust_net_weight" class="form-control validate[required,custom[number]]" />
                </div>
                <div class="col-lg-8" style="width:300px">
                <select name="cust_net_weight_type" id="cust_net_weight_type" class="form-control validate[required]" >
                               <option value="">Select Measurement</option>
                                <?php 
					            foreach($measurement as $meas)
								{
									$cust_net_weight= isset($transport['cust_net_weight_type'])?$transport['cust_net_weight_type']:'1';  ?>
                                      <?php 
										if($cust_net_weight == $meas['product_id']) 
										{ ?>    
                                        		<option value="<?php echo $meas['product_id']; ?>" selected="selected" > <?php echo $meas['measurement']; ?></option>
								  <?php }  
										else
										{?>
                                            <option value="<?php echo $meas['product_id']; ?>"><?php echo $meas['measurement']; ?></option>
                              <?php     }
							    }
								?>
                            </select>
                </div>
              </div>
             
               <div class="line line-dashed m-t-large"></div>
               <div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                <div class="col-lg-4">
                  <select name="status" id="status" class="form-control validate[required]">
                    <option value="1" <?php echo (isset($transport['status']) && $transport['status'] == 1)?'selected':'';?> > Active</option>
                    <option value="0" <?php echo (isset($transport['status']) && $transport['status'] == 0)?'selected':'';?>> Inactive</option>
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
                  <a class="btn btn-default" href="<?php echo $obj_general->link($rout, 'mod=list&product_id='.$_GET['product_id'], '',1);?>">Cancel</a>
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
<script src="<?php echo HTTP_SERVER;?>ckeditor3/ckeditor.js"></script>


<script>	
jQuery(document).ready(function(){
	// binds form submission and fields to the validation engine
	 jQuery("#form").validationEngine();
 });
 
  
 $("#product").change(function(){
	var val = $(this).val();
	var text = $("#product option[value='"+val+"']").text().toLowerCase();
	if(val==10)
	{
		$("#wv").prop("disabled",true);
		$("#nv").prop('checked', 'checked');
		jQuery("input[name='spout[]']").each(function(i) {
			if($(this).val()!='MQ==')
				jQuery(this).prop("disabled",true);
			else
				$(this).prop('checked', 'checked');
		});
		jQuery("input[name='accessorie[]']").each(function(i) {
			if($(this).val()!='NA==')
				jQuery(this).prop("disabled",true);
			else
				$(this).prop('checked', 'checked');
		});
	}
	else
	{
		$("#wv").prop("disabled",false);
		jQuery("input[name='spout[]']").each(function(i) {
			jQuery(this).prop("disabled",false);
			if($(this).val()=='MQ==')
				$(this).prop('checked', 'checked');
		});
		jQuery("input[name='accessorie[]']").each(function(i) {
			jQuery(this).prop("disabled",false);
			if($(this).val()=='NA==')
				$(this).prop('checked', 'checked');
		});
	}
	checkZipper();				
	var zipper_id=$("input[class='zipper']:checked").val();
});

/*$("#btn_save").click(function(){
	  if($("#form").validationEngine('validate')){
		  //debugger;
		  
			var add_box_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&ajax=addPouch', '',1);?>");
				var formData = $("#form").serialize();
				<?php $id = encode($product_nm['product_id']); ?>
				$.ajax({
					url : add_box_url,
					method : 'post',		
					data : {formData : formData},
					success: function(response){
						
						if(response != 0)
						{	
						 	window.location = "<?php echo $obj_general->link($rout, '&mod=list&product_id='.$id, '',1); ?>";
							set_alert_message('Record has been added successfully.','alert-success','fa fa-check');
						}
						else
						{
							alert("Your entered data is already Added");
						}
					}
				});
	}
  });
  */
 /* $("#btn_update").click(function(){
	  if($("#form").validationEngine('validate')){
		  //debugger;
		  
			var update_box_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&ajax=updatePouchData', '',1);?>");
				var formData = $("#form").serialize();
				var id =$("#pouch_idd").val();
				<?php $id = encode($p_id); ?>
				$.ajax({
					url : update_box_url,
					method : 'post',		
					data : {formData : formData,id:id},
					success: function(response){
					//	alert('<?php echo $obj_general->link($rout,'&mod=list&product_id='.encode($p_id), '',1); ?>');
						if(response != 0)
						{	
							window.location = "<?php echo $obj_general->link($rout,'&mod=list&product_id='.encode($p_id), '',1); ?>";
							alert(window.location).die();
							//set_alert_message('Record has been updated successfully.','alert-success','fa fa-check');
						}
						else
						{
							alert("Your entered data is already Added");
						}
					}
				});
	}
  });*/
  

function checkZipper(){
	var gusset_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&ajax=checkProductZipper', '',1);?>");
	var product_id = $('#product').val();
	$.ajax({
		type: "POST",
		url: gusset_url,
		dataType: 'json',
		data:{product_id:product_id}, 
		success: function(response) {
			//alert(response);
			$('#zipper_div').html(response);	
		}
	});
}
</script>