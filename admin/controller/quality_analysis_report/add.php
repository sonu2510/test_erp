<?php
include("mode_setting.php");
if(isset($_GET['product_id']) && !empty($_GET['product_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$product_id = base64_decode($_GET['product_id']);
		$product = $obj_quality_report->getProduct($product_id);
	
	}
}
else if(isset($_GET['qc_report_id']) && !empty($_GET['qc_report_id'])){
  if(!$obj_general->hasPermission('edit',$menuId)){
    $display_status = false;
  }else{
    $qc_report_id = base64_decode($_GET['qc_report_id']);
    $qc_report_details = $obj_quality_report->getQcReportDetail($qc_report_id);

     //  printr($qc_report_id);
    $qc_gsm_details = $obj_quality_report->getQcReportGSMDetail($qc_report_id);
    $layer_detail= count($qc_gsm_details);
    $edit=1;
    $product_id=$qc_report_details['product_id'];
  }

}
//Start : bradcums
$bradcums = array();
$bradcums[] = array(
	'text' 	=> 'Dashboard',
	'href' 	=> $obj_general->link('dashboard', '', '',1),
	'icon' 	=> 'fa-home',
	'class'	=> '',
);
$bradcums[] = array(
	'text' 	=> 'Product Detail',
	'href' 	=> $obj_general->link($rout, '', '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);
$bradcums[] = array(
	'text' 	=> ' Product Size Detail ',
	'href' 	=> $obj_general->link($rout, 'mod=size_detail&product_id='.encode($product_id), '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);
$bradcums[] = array(
	'text' 	=> $display_name.' Detail',
	'href' 	=> '',
	'icon' 	=> 'fa-edit',
	'class'	=> 'active',
);

if($display_status){
  //insert user
  if(isset($_POST['btn_save'])){
    $post = post($_POST);
    $insert_id = $obj_quality_report->addData($post);
    //page_redirect($obj_general->link($rout, '', '',1));
  }
  //edit
 if(isset($_POST['btn_update'])){
    $post = post($_POST);
    $qc_report_id = base64_decode($_GET['qc_report_id']);
    $product_id = $_POST['product_id'];
    $obj_quality_report->updateData($qc_report_id,$post);
    $obj_session->data['success'] = UPDATE;
    page_redirect($obj_general->link($rout, 'mod=view&product_id='.encode($product_id).'&size_id='.encode($qc_report_details['size_id']).'&category_id='.encode($qc_report_details['category_id']).'&color_id='.encode($qc_report_details['color_id']), '',1));
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
      <div class="col-sm-12">
        <section class="panel">
          <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
           
       		  <div class="form-group">
                        <label class="col-lg-3 control-label">PRODUCT NAME</label>
                        
                        <div class="col-lg-4">
                         
                          <select class="form-control validate[required]"  id="product_id" name="product_id" readonly>
                            <?php 
								 $products = $obj_quality_report->getProducts(); 
										//printr($products);
									 if($products){
									foreach($products as $product) {
											if(isset($product_id)&& !empty($product_id)){
										$product_details = $product_id;
									}
									?>
								<option value="<?php echo $product['product_id']; ?>" <?php echo (isset($product['product_id']) && ($product['product_id'] == $product_details) )?'selected':'';?> ><?php echo $product['product_name']; ?></option>
								<?php } } ?>
                          </select>
                          
                        
                        </div>
                       
                </div> 


                     
                      
                  <div  class="form-group" id="zipper_div" <?php  if(isset($qc_report_details)){ echo 'style="pointer-events: none;"';}?>>   
                        <?php if($edit==1)
								{  $zipper_available = $obj_quality_report->checkProductzipper($product['product_id']);
									$zippers = $obj_quality_report->getActiveProductZippersByTintie(); ?>
									<div class="form-group option"> 
										<label class="col-lg-3 control-label">Zipper</label>
											<div class="col-lg-9">
												<?php foreach($zippers as $zipper){ ?>
													<div   style="float:left;width: 200px;">
														<label  style="font-weight: normal;">
															<input type="radio" name="zipper" id="<?php echo $zipper['product_zipper_id'];?>" value="<?php echo $zipper['product_zipper_id'];?> " <?php if($zipper['product_zipper_id']==$qc_report_details['zipper']){ echo "checked=checked";}?> onclick="showSize()"  class="zipper"><?php echo $zipper['zipper_name'];?>
														</label>
													</div>
												<?php } ?>	
											</div>
										</label>
									</div>
						<?php   } ?>
                    </div>   
                       <div  class="form-group" id="valve_div" >
                        <label class="col-lg-3 control-label">VALVE</label>
                        <div class="col-lg-9">
                        	<div  style="float:left;width: 200px;">
                                <label  style="font-weight: normal;">
                                  <input type="radio" name="valve" id="nv" value="No Valve" checked="checked"  class="valve" <?php if(isset($qc_report_details) && ($qc_report_details['valve'] == 'No Valve')) { echo 'checked="checked"'; } ?>/>
                                 	No Valve
                              </label>
                            
                                <label  style="font-weight: normal;">
                                  	<input type="radio" name="valve" id="wv" value="With Valve" value="valve" class="valve" <?php if(isset($qc_report_details) && ($qc_report_details['valve'] == 'With Valve')) { echo 'checked="checked"'; } ?> />
                              	With Valve
                              </label>
                          </div> 
                        </div>
                      </div>  
       		       <div class="form-group" id="size_div" <?php  if(isset($qc_report_details)){ echo 'style="pointer-events: none;"';}?>>
                        <?php if(isset($qc_report_id)){
						   $size_detail=$obj_quality_report->getSize($product_details,$qc_report_details['zipper']);?> 
							<label class="col-lg-3 control-label">SIZE </label>                      
								 <div class="col-lg-4">                         
									<select class="form-control validate[required]"  id="size" name="size" ">
									 <option value="0">Select Size</option>
										 <?php   if(!empty($size_detail)){
													  foreach($size_detail as $size)  { ?>
																	
													   <option value="<?php echo $size['size_master_id'];?> "  <?php echo (isset($qc_report_details) && ($size['size_master_id'] == $qc_report_details['size_id']) ) ?'selected':'';?>  ><?php echo $size['volume'].'('.$size['width'].' x '.$size['height'].' x '.$size['gusset'].')';?> </option>
													   <?php    }
											}?>
									   </select>
									</div>
                
                        <?php }?>  
                  </div>
                   <div class="form-group" <?php  if(isset($qc_report_details)){ echo 'style="pointer-events: none;"';}?>>
                     <label class="col-lg-3 control-label">COLOR CATEGORY </label>                    
                       <div class="col-lg-3">                     
                            <select class="form-control validate[required]"  id="category_id" name="category_id" onChange="getActiveColor()">
                              <option value="">Select Color Category</option>
                              <?php 
      						             $Color_category = $obj_quality_report->getColor_category();
      					
            					     		foreach($Color_category as $category)	{
            							       ?>
													<option value="<?php echo $category['category_id']; ?>" <?php if(isset($qc_report_details) && ($qc_report_details['category_id'] == $category['category_id'])) { echo 'selected="selected"'; } ?> ><?php echo $category['category']; ?></option>
      					                 	<?php }?>
                             </select>
                       </div>
                   </div>
				   <div class="form-group clr_div" <?php  if(isset($qc_report_details)){ echo 'style="pointer-events: none;"';}?> >
						<?php if($edit==1){	
								$clr = $obj_quality_report->getActiveColor($qc_report_details['category_id']); ?>
									<label class="col-lg-3 control-label">COLOR</label>
									<div class="col-lg-3">
										<select class="form-control validate[required]"  id="color_id" name="color_id" ">
										  <option value="0">Select Color</option>
											<?php if(!empty($clr)){
													foreach($clr as $color)  {?>
														<option value="<?php echo $color['pouch_color_id'];?>" <?php if(isset($qc_report_details) && ($qc_report_details['color_id'] == $color['pouch_color_id'])) { echo 'selected="selected"'; } ?>  ><?php echo $color['color'] ;?></option>
											<?php 	}
												} ?>
										</select>
									</div>
						<?php } ?>
                   </div>
                    <div class="form-group" <?php  if(isset($qc_report_details)){ echo 'style="pointer-events: none;"';}?>>
                         <label class="col-lg-3 control-label"><span class="required">*</span>  SELECT LAYERS</label>
                                <div class="col-lg-3">
                                    <?php
                                    $layers = $obj_quality_report->getActiveLayer();
                                    ?>
                                    <select name="layer_id" id="layer_id" onChange="getLayerMakeMaterial()" class="form-control validate[required]">
                                       <option value="">Select Layer</option>
                                       <?php
                                        foreach($layers as $layer){
                                          if($layer['product_layer_id']==1){?>
                                                <option value="<?php echo $layer['product_layer_id']?>"  <?php if(isset($qc_report_id) && $layer_detail==$layer['product_layer_id']){?> selected="selected" <?php }?>> 
                                                  <?php echo $layer['layer']?></option>
                                           <?php  }else{?>
                                                <option value="<?php echo $layer['product_layer_id']?>" <?php if(isset($qc_report_id) && $layer_detail==$layer['product_layer_id']){?> selected="selected" <?php }?> > <?php echo  $layer['layer']?></option>
										<?php  } 
										}
                                             ?>
                                    </select>
                                </div>
                       </div>
                      


                       <div class="form-group" id="m_div">
							<?php if($edit==1){	$clear = $print = $gsm = '';
									if($qc_report_details['category_id']=='1')
									{
										$clear = 'Clear Side';
										$print = 'Print Side';
										$gsm = '';
									}
									else if($qc_report_details['category_id']=='4')
									{
										$clear = $gsm = 'Clear Side';
										$print = 'Paper Side';
									}?>
								 <div class="col-lg-3"></div><div class="col-lg-8">
									 <section class="panel">
										  <div class="table-responsive">
										  <table class="table table-striped b-t text-small">
											<thead>
											   <tr>
												<th width="15%"></th>
												<th>Material</th>
												<th >Thickness</th>
												<?php if($qc_report_details['category_id']=='1' || $qc_report_details['category_id']=='4')
														echo '<th>'.$print.' Material</th><th >'.$print.'Thickness</th>';?>
												<th >STD GSM</th>
												<?php if($qc_report_details['category_id']=='4')
														echo '<th>'.$print.' STD GSM</th>';?>
												<th >MIN GSM</th>
												<?php if($qc_report_details['category_id']=='4')
														echo '<th>'.$print.' MIN GSM</th>';?>
												<th >MAX GSM </th>
												<?php if($qc_report_details['category_id']=='4')
														echo '<th>'.$print.' MAX GSM</th>';?>
												</tr>
										   </thead>
										   <tbody>
												<?php for($i=1;$i<=$layer_detail;$i++){
														$layer_materials = $obj_quality_report->getLayerMakeMaterial($i);
															if($layer_materials){ ?>
																<tr>
																	<td><b><?php echo $i;?> Layer</b><input type="hidden" value ="<?php if(isset($qc_report_id) && !empty($qc_report_details)) { echo $qc_gsm_details[$i-1]['qc_material_gsm_id']; } ?>" name="material[<?php echo $i;?>][qc_material_gsm_id]"></td>
																	<td>
																		<select name="material[<?php echo $i;?>][material_id]" onchange="getMaterialThickness(this.value,<?php echo $i;?>,<?php echo $layer_detail;?>,0)" id="material_<?php echo $i;?>" class="form-control validate[required]">
																			<option value="">Select Material</option>
																			<?php foreach($layer_materials as $material){ ?>
																				<option value="<?php echo $material['material_id']; ?>" <?php if($material['material_id']==$qc_gsm_details[$i-1]['material_id']){  ?> selected="selected" <?php }?>><?php echo $material['material_name']; ?></option>
																			<?php } ?>
																		</select>
																	</td>
																	<td>
																		<select name="material[<?php echo $i;?>][thickness]" class="form-control validate[required]" id="thickness-dropdown-<?php echo $i;?>">
																			<option value="">Select Thickness</option>
																				<?php $material_thickness=$obj_quality_report->getMaterialThickness($qc_gsm_details[$i-1]['material_id']);	
																					if($material_thickness){ 
																						foreach($material_thickness as $thickness_details){?>
																						<option value=" <?php echo $thickness_details['thickness']; ?>" <?php if($thickness_details['thickness']==$qc_gsm_details[$i-1]['thickness_id']){?> selected="selected" <?php }?>><?php echo $thickness_details['thickness'];?></option>
																					<?php }
																					} ?>
																		</select>
																	</td>
															<?php if($qc_report_details['category_id']=='1' || $qc_report_details['category_id']=='4')
																  { ?>
																	<td>
																		<select name="clearprintr[<?php echo $i;?>][sec_material_id]" onchange="getMaterialThickness(this.value,<?php echo $i;?>,<?php echo $layer_detail;?>,1)" id="material_<?php echo $i;?>" class="form-control validate[required]">
																			<option value="">Select Material</option>
																			<?php foreach($layer_materials as $material){ ?>
																				<option value="<?php echo $material['material_id']; ?>" <?php if($material['material_id']==$qc_gsm_details[$i-1]['sec_material_id']){  ?> selected="selected" <?php }?>><?php echo $material['material_name']; ?></option>
																			<?php } ?>
																		</select>
																	</td>
																	<td>
																		<select name="clearprintr[<?php echo $i;?>][sec_thickness]" class="form-control validate[required]" id="clearprintr_thickness-dropdown-<?php echo $i;?>">
																			<option value="">Select Thickness</option>
																				<?php $material_thickness=$obj_quality_report->getMaterialThickness($qc_gsm_details[$i-1]['sec_material_id']);	
																					if($material_thickness){ 
																						foreach($material_thickness as $thickness_details){?>
																							<option value=" <?php echo $thickness_details['thickness']; ?>" <?php if($thickness_details['thickness']==$qc_gsm_details[$i-1]['sec_thickness_id']){?> selected="selected" <?php }?>><?php echo $thickness_details['thickness'];?></option>
																					<?php }
																					} ?>
																		</select>
																	</td>
															<?php  } ?>
																	<td>
																		  <input type="text" name="material[<?php echo $i;?>][std_gsm]" value="<?php if(isset($qc_report_id) && !empty($qc_report_details)){ echo $qc_gsm_details[$i-1]['std_gsm']; } ?>" id="std_gsm" class="form-control"/>               
																	</td>
															<?php if($qc_report_details['category_id']=='4') { ?>
																	<td>
																		  <input type="text" name="material[<?php echo $i;?>][sec_std_gsm]" value="<?php if(isset($qc_report_id) && !empty($qc_report_details)){ echo $qc_gsm_details[$i-1]['sec_std_gsm']; } ?>" id="sec_std_gsm" class="form-control"/>               
																	</td>
															<?php } ?>
																	<td>
																		  <input type="text" name="material[<?php echo $i;?>][min_gsm]" value="<?php if(isset($qc_report_id) && !empty($qc_report_details)){ echo $qc_gsm_details[$i-1]['min_gsm']; } ?>" id="min_gsm" class="form-control"/>               
																	</td>
															<?php if($qc_report_details['category_id']=='4') { ?>
																	<td>
																		  <input type="text" name="material[<?php echo $i;?>][sec_min_gsm]" value="<?php if(isset($qc_report_id) && !empty($qc_report_details)){ echo $qc_gsm_details[$i-1]['sec_min_gsm']; } ?>" id="sec_min_gsm" class="form-control"/>               
																	</td>
															<?php } ?>
																	<td>
																		  <input type="text" name="material[<?php echo $i;?>][max_gsm]" value="<?php if(isset($qc_report_id) && !empty($qc_report_details)){ echo $qc_gsm_details[$i-1]['max_gsm']; } ?>" id="max_gsm" class="form-control"/>               
																	</td>
															<?php if($qc_report_details['category_id']=='4') { ?>
																	<td>
																		  <input type="text" name="material[<?php echo $i;?>][sec_max_gsm]" value="<?php if(isset($qc_report_id) && !empty($qc_report_details)){ echo $qc_gsm_details[$i-1]['sec_max_gsm']; } ?>" id="sec_max_gsm" class="form-control"/>               
																	</td>
															<?php } ?>
																</tr>
												<?php 		}
														}?>
																<tr>
																	<td></td>  
																    <td></td>
																    <td></td>
																	<?php if($qc_report_details['category_id']=='1' || $qc_report_details['category_id']=='4')
																			echo '<td></td><td></td>';?>
																	<td><input type="text" name="total_std_gsm" value="<?php if(isset($qc_report_id) && !empty($qc_report_details)){ echo $qc_report_details['total_std_gsm']; } ?>" id="total_std_gsm" class="form-control"/></td>
																<?php if($qc_report_details['category_id']=='4') { ?>
																	<td><input type="text" name="sec_total_std_gsm" value="<?php if(isset($qc_report_id) && !empty($qc_report_details)){ echo $qc_report_details['sec_total_std_gsm']; } ?>" id="sec_total_std_gsm" class="form-control"/></td>																	
																<?php } ?>
																	<td><input type="text" name="total_min_gsm" value="<?php if(isset($qc_report_id) && !empty($qc_report_details)){ echo $qc_report_details['total_min_gsm']; } ?>" id="total_min_gsm" class="form-control"/></td>
																<?php if($qc_report_details['category_id']=='4') { ?>
																	<td><input type="text" name="sec_total_min_gsm" value="<?php if(isset($qc_report_id) && !empty($qc_report_details)){ echo $qc_report_details['sec_total_min_gsm']; } ?>" id="sec_total_min_gsm" class="form-control"/></td>																	
																<?php } ?>
																	<td><input type="text" name="total_max_gsm" value="<?php if(isset($qc_report_id) && !empty($qc_report_details)){ echo $qc_report_details['total_max_gsm']; } ?>" id="total_max_gsm" class="form-control"/></td>
																<?php if($qc_report_details['category_id']=='4') { ?>
																	<td><input type="text" name="sec_total_max_gsm" value="<?php if(isset($qc_report_id) && !empty($qc_report_details)){ echo $qc_report_details['sec_total_max_gsm']; } ?>" id="sec_total_max_gsm" class="form-control"/></td>																	
																<?php } ?>
																</tr>
											</tbody>
										</table>
									</div>
								</section>
							</div>
							<?php } ?>
                	   </div> 
                    <div class="form-group">
                         <label class="col-lg-3 control-label">REGISTRATION </label>
                         
                        <div class="col-lg-3">
                            <input type="text" name="registration" value="<?php echo isset($qc_report_id)?$qc_report_details['registration']:'OK' ;?>" id="registration" class="form-control"/>
                        </div>
                  </div> 
                  
                   <div class="form-group">
                         <label class="col-lg-3 control-label">SHADE </label>
                         
                        <div class="col-lg-3">
                            <input type="text" name="shade" value="<?php echo isset($qc_report_id)?$qc_report_details['shade']:'AS PER STANDARD' ;?>" id="shade" class="form-control"/>
                        </div>
                  </div> 
                   <div class="form-group">
                         <label class="col-lg-3 control-label">DELAMINATION TEST  </label>
                         
                        <div class="col-lg-3">
                            <input type="text" name="delamination_test" value="<?php echo isset($qc_report_id)?$qc_report_details['delamination_test']:'NO  DELAMINATION' ;?>" id="delamination_test" class="form-control"/>
                        </div>
                  </div>  
                  <div class="form-group">
                         <label class="col-lg-3 control-label">POUCH LENGTH  </label>
                         
                        <div class="col-lg-3">
                            <input type="text" name="pouch_length" value="<?php echo isset($qc_report_id)?$qc_report_details['pouch_length']:'' ;?>" id="pouch_length" class="form-control"/>
                        </div>
                  </div> 
                  <div class="form-group">
                         <label class="col-lg-3 control-label">POUCH WIDTH   </label>
                         
                        <div class="col-lg-3">
                            <input type="text" name="pouch_width" value="<?php echo isset($qc_report_id)?$qc_report_details['pouch_width']:'' ;?>" id="pouch_width" class="form-control"/>
                        </div>
                  </div> 
                  <div class="form-group">
                         <label class="col-lg-3 control-label">GUSSETS </label>
                         
                        <div class="col-lg-3">
                            <input type="text" name="gusset" value="<?php echo isset($qc_report_id)?$qc_report_details['gusset_pos']:'' ;?>" id="gusset" class="form-control"/>
                        </div>
                  </div>

                   <div class="form-group">
                         <label class="col-lg-3 control-label">ZIPPER WIDTH </label>
                         
                        <div class="col-lg-3">
                            <input type="text" name="zipper_width" value="<?php echo isset($qc_report_id)?$qc_report_details['zipper_width']:'' ;?>" id="zipper_width" class="form-control"/>
                        </div>
                  </div> 
                  
                   <div class="form-group">
                         <label class="col-lg-3 control-label">ZIPPER POSITION </label>
                         
                        <div class="col-lg-3">
                            <input type="text" name="zipper_position" value="<?php echo isset($qc_report_id)?$qc_report_details['zipper_position']:'' ;?>" id="zipper_position" class="form-control"/>
                        </div>
                  </div>
                  <div class="form-group">
                         <label class="col-lg-3 control-label">V NOTCH  </label>
                         
                        <div class="col-lg-3">
                            <input type="text" name="v_notch" value="<?php echo isset($qc_report_id)?$qc_report_details['v_notch']:'' ;?>" id="v_notch" class="form-control"/>
                        </div>
                  </div> 
                  <div class="form-group">
                         <label class="col-lg-3 control-label">SEALING AREA   </label>
                         
                        <div class="col-lg-3">
                            <input type="text" name="sealing_area" value="<?php echo isset($qc_report_id)?$qc_report_details['sealing_area']:'' ;?>" id="sealing_area" class="form-control"/>
                        </div>
                  </div>
                  <div class="form-group">
                         <label class="col-lg-3 control-label">POUCH WEIGHT   </label>
                         
                        <div class="col-lg-3">
                            <input type="text" name="pouch_weight" value="<?php echo isset($qc_report_id)?$qc_report_details['pouch_weight']:'' ;?>" id="pouch_weight" class="form-control"/>
                        </div>
                  </div>
                  <div class="form-group">
                         <label class="col-lg-3 control-label">OTR AT 23 D,0 % R.H </label>
                         
                        <div class="col-lg-3">
                            <input type="text" name="otr" value="<?php echo isset($qc_report_id)?$qc_report_details['otr']:'' ;?>" id="otr" class="form-control"/>
                        </div>
                  </div>
                  <div class="form-group">
                         <label class="col-lg-3 control-label">WVTR AT 38 D 90 % R.H</label>
                         
                        <div class="col-lg-3">
                            <input type="text" name="wvtr" value="<?php echo isset($qc_report_id)?$qc_report_details['wvtr']:'' ;?>" id="wvtr" class="form-control"/>
                        </div>
                  </div>
                  <div class="form-group">
                         <label class="col-lg-3 control-label">SEALING STRENGTH   </label>
                         
                        <div class="col-lg-3">
                            <input type="text" name="sealing_strength" value="<?php echo isset($qc_report_id)?$qc_report_details['sealing_strength']:'' ;?>" id="sealing_strength" class="form-control"/>
                        </div>
                  </div> 
                 
                  
                  
				   <div class="form-group">
                         <label class="col-lg-3 control-label">BOND STRENGTH 1st  AND 2nd LAYER  </label>
                         
                        <div class="col-lg-3">
                            <input type="text" name="bond_strength_1" value="<?php echo isset($qc_report_id)?$qc_report_details['bond_strength_1']:'' ;?>" id="bond_strength_1" class="form-control"/>
                        </div>
                  </div> 
				<div class="form-group">
                         <label class="col-lg-3 control-label">BOND STRENGTH 2nd  AND 3rd LAYER  </label>
                         
                        <div class="col-lg-3">
                            <input type="text" name="bond_strength_2" value="<?php echo isset($qc_report_id)?$qc_report_details['bond_strength_2']:'' ;?>" id="bond_strength_2" class="form-control"/>
                        </div>
                  </div>
				<div class="form-group">
                         <label class="col-lg-3 control-label">BOND STRENGTH 3rd  AND 4th LAYER  </label>
                         
                        <div class="col-lg-3">
                            <input type="text" name="bond_strength_3" value="<?php echo isset($qc_report_id)?$qc_report_details['bond_strength_3']:'' ;?>" id="bond_strength_3" class="form-control"/>
                        </div>
                  </div> 
				<div class="form-group">
                         <label class="col-lg-3 control-label">BOND STRENGTH 4th  AND 5th LAYER  </label>
                         
                        <div class="col-lg-3">
                            <input type="text" name="bond_strength_4" value="<?php echo isset($qc_report_id)?$qc_report_details['bond_strength_4']:'' ;?>" id="bond_strength_4" class="form-control"/>
                        </div>
                  </div>				  
                  
                    <div class="form-group">
                         <label class="col-lg-3 control-label">BURSTING SRENGTH </label>
                         
                        <div class="col-lg-3">
                            <input type="text" name="bursting_srength" value="<?php echo isset($qc_report_id)?$qc_report_details['bursting_strength']:'' ;?>" id="bursting_srength" class="form-control"/>
                        </div>
                  </div>
                  <div class="form-group">
                         <label class="col-lg-3 control-label">ODOUR TEST    </label>
                         
                        <div class="col-lg-3">
                            <input type="text" name="odour_test" value="<?php echo isset($qc_report_id)?$qc_report_details['odour_test']:'PASS' ;?>" id="odour_test" class="form-control"/>
                        </div>
                  </div> 
                 
					<div class="form-group">
                         <label class="col-lg-3 control-label">LEAKAGE TEST  </label>
                         
                        <div class="col-lg-3">
                            <input type="text" name="leakage_test" value="<?php echo isset($qc_report_id)?$qc_report_details['leakage_test']:'PASS' ;?>" id="leakage_test" class="form-control"/>
                        </div>
                  </div>
                   <div class="form-group">
                         <label class="col-lg-3 control-label">DROP TEST   </label>
                         
                        <div class="col-lg-3">
                            <input type="text" name="drop_test" value="<?php echo isset($qc_report_id)?$qc_report_details['drop_test']:'' ;?>" id="drop_test" class="form-control"/>
                        </div>
                  </div>
                   <?php if($edit){?>
						<div class="form-group">
						  <div class="col-lg-9 col-lg-offset-3">
							  <button type="submit" name="btn_update" id="btn_update" class="btn btn-primary">Update </button>
							 <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a> 
						  </div>
					  </div>
                      <?php }  else{?>
					   <div class="col-lg-9 col-lg-offset-3"> 
							
							<button type="submit" name="btn_save" id="btn_save" class="btn btn-primary">Save </button>
						   
							<a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a> </div>
						</div>
                      <?php }  ?>
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
		<?php if(!isset($_GET['qc_report_id'])){?>
		  checkZipper();
		  getLayerMakeMaterial();
			getActiveColor();
		<?php }?>
		
    });

  function getLayerMakeMaterial()
  {
    var category_id = $('#category_id').val();
    var layer_id=$('#layer_id').val();
    var remove_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getLayerMakeMaterial', '',1);?>");
      $.ajax({
        url : remove_url,
        method : 'post',
        data : {layer_id : layer_id,category_id :category_id},
      success: function(response){ 
        
          $('#m_div').html(response);
           
            
        },
        error: function(){
          return false; 
        }
    });
  }
  function showSize()
  {
    
    var zipper=$("input[class='zipper']:checked").val();
    var product_id=$('#product_id').val();
   // alert(zipper);
 //   alert(product_id);
    var remove_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getSize', '',1);?>");
      $.ajax({
        url : remove_url,
        method : 'post',
        data : {product_id : product_id,zipper:zipper},
      success: function(response){ 
        
          $('#size_div').html(response);
         
        },
        error: function(){
          return false; 
        }
    });
  }
  function getMaterialThickness(material_id,layer_id,layers,n)
	{
    
  
    
		var remove_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getMaterialThickness', '',1);?>");
			$.ajax({
				url : remove_url,
				method : 'post',
        dataType: 'json',
				data : {material_id:material_id,layer_id : layer_id},
		      success: function(json) {
            
                    if(n==0)
                        $('#thickness-dropdown-'+layer_id).html(json);
                    else
                        $('#clearprintr_thickness-dropdown-'+layer_id).html(json);
				},
				error: function(){
					return false;	
				}
		});
	}
	
	function checkZipper(){
		
		var gusset_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=checkProductZipper', '',1);?>");
		var product_id = $('#product_id').val();
		//alert(product_id);
		$.ajax({
			type: "POST",
			url: gusset_url,
			dataType: 'json',
			data:{product_id:product_id}, 
			success: function(response) {
				//console.log(response);
				$('#zipper_div').html(response);
				showSize();	
			}
		});
}
	function getActiveColor()
	{ 
		var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getActiveColor', '',1);?>");
		var category_id = $('#category_id').val();
		$.ajax({
			type: "POST",
			url: url,
			dataType: 'json',
			data:{category_id:category_id}, 
			success: function(response) {
				//console.log(response);
				$('.clr_div').html(response);
			}
		});
	}
	</script>
<!-- Close : validation script -->
<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>
