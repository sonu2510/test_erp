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

$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
$allow_currency_status = $obj_quotation->allowCurrencyStatus($user_type_id,$user_id);

//Start : edit
$edit = '';
if(isset($_GET['quotation_id']) && !empty($_GET['quotation_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$quotation_id = base64_decode($_GET['quotation_id']);
		$getData = ' product_quotation_id, pq.added_by_user_id, pq.added_by_user_type_id, customer_name, shipment_country_id, quotation_type, quantity_type, product_id, product_name, printing_option, printing_effect, height, width, gusset, layer, currency, currency_price, cylinder_price, customer_gress_percentage,pq.status,pq.quotation_status,valve_price';
		$data = $obj_quotation->getQuotation($quotation_id,$getData,$obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);
		//printr($data);die;
	}
}
//Close : edit
if($display_status){	
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
      <div class="col-sm-10" style="width:100%">
            <section class="panel">  
                <header class="panel-heading bg-white">
                 <span>Quotation Detail</span>
                </header>
              	<div class="panel-body">
              		<label class="label bg-white m-l-mini">&nbsp;</label>
                	
                <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Quotation Number</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo $data[0]['multi_quotation_number'];?>
                            </label>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Customer Name</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo ucwords($data[0]['customer_name']);?>
                            </label>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Shipment Country</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo $data[0]['country_name'];?>
                            </label>
                        </div>
                      </div>
                      
                      <?php if($data[0]['customer_gress_percentage'] > 0){ ?>
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Customer Gress (%)</label>
                        <div class="col-lg-8">
                            <label class="control-label normal-font">
                            <?php echo $data[0]['customer_gress_percentage'];?>
                            <small class="text-muted">- Below price display without customer gress ( %)</small>
                            </label>
                            <br />
                            <small class="text-muted">- Any email send to client with adding customer gress price.</small>
                        </div>
                      </div>
                      <?php } ?>	
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Product Name</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo $data[0]['product_name'];?>
                            </label>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Printing Option</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                                <?php echo $data[0]['printing_option'];?>
                            </label>
                        </div>
                      </div>
                      <?php /*?><?php if($data[0]['printing_option'] == 'With Printing'){?>
                          <div class="form-group">
                            <label class="col-lg-3 control-label">Printing Effect</label>
                            <div class="col-lg-4">
                                <label class="control-label normal-font">
                                    <?php echo $data[0]['printing_effect'];?>
                                </label>
                            </div>
                          </div>
                      <?php } ?><?php */?>
                      
                    <?php /*?>  <?php
					  $materialData = $obj_quotation->getQuotationMaterial($data[0]['product_quotation_id']);
					  //printr($materialData);die;
                      if(isset($materialData) && !empty($materialData)){
                          ?>
                          <div class="form-group">
                            <label class="col-lg-3 control-label">Material</label>
                            <div class="col-lg-9">
                                <section class="panel">
                                  <div class="table-responsive">
                                    <table class="table table-striped b-t text-small">
                                      <thead>
                                        <tr>
                                          <th></th>
                                          <th>Material</th>
                                          <th>Thickness</th>
                                          <th>GSM</th>
                                          <th>Price/KG</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                      <?php
                          				for($gi=0;$gi<count($materialData);$gi++){
											?>
											<tr>
											  <td><?php echo ($gi+1)." Layer";?></td>
											  <td><?php echo $materialData[$gi]['material_name'];?></td>
											  <td><?php echo (int)$materialData[$gi]['material_thickness'];?></td>
                                              <td><?php echo $materialData[$gi]['material_gsm'];?></td>
                                              <td><?php echo $materialData[$gi]['material_price'];?></td>
											</tr>
                                            <?php
										}
									   ?>
                                      </tbody>
                                    </table>
                                  </div>
                                </section> 
                            </div>
                          </div>
                          <?php
                      }<?php */?>
                      <?php
                      if($data[0]['quotation_type'] == 1){ ?>
                          <div class="form-group">
                            <label class="col-lg-3 control-label">Quantity In </label>
                            <div class="col-lg-4">
                                <label class="control-label normal-font">
                                    <?php echo ucwords($data[0]['quantity_type']);?>
                                </label>
                            </div>
                          </div>
                      <?php
					  } 
					  foreach($data as $dat)
					   {
					 	 $result = $obj_quotation->getQuotationQuantity($dat['product_quotation_id']);
						 //printr($result);
						 if($result!='')
						 $quantityData[]=$result;
					   }
					    if(!empty($quantityData))
					   {
						foreach($quantityData as $k=>$qty_data)
						{
							foreach($qty_data as $tag=>$qty)
							{
								foreach($qty as $q=>$arr)
								{
									$new_data[$tag][$q][]=$arr[0];
								}
							}	
						}
					//printr($new_data);
						foreach($new_data as $k=>$qty_data)
						{
					?>
                      <div class="form-group">
								<label class="col-lg-3 control-label">Price (By <?php echo $k;?>)</label> 
								<div class="col-lg-9">
									<section class="panel">
									  <div class="table-responsive">
										<table class="table table-striped b-t text-small">
										  <thead>
											  <tr>
                                          <th>Quantity</th>
                                          <th>Options(Printing Effect)</th>
                                          <th>Dimension</th>
                                          <th>Layer:Material:Thickness</th>
                                         <?php if($k=='sea') { ?>
                                          <th>Transport Price</th>
                                          <?php } else { ?>
                                             <th>Courier Charge</th>
                                             <?php } ?>
                                          <th>Price</th>
                                        <th>Paking Price</th>
                                        </tr>
										  </thead>
                                          <tbody>
                                          	<?php $i=1;
                                                foreach($qty_data as $skey=>$sdata){
                                                    ?>
                                                    <tr>
                                                        
                                                   <?php 
                                                        foreach($sdata as $soption){
													//	printr($soption);
														$spout_weight = $obj_quotation->getSpoutWeight($soption['spout_txt']);
														if($data[0]['product_id']=='61')
														    $spout_weight = $obj_quotation->spoutDetail($soption['volume']);
														
														$tintie_weight = $obj_quotation->getTintieWeight($soption['zipper_txt']);
                                                    		//printr($soption);     ?>
                                                            <tr>
                                                            <th ><?php echo $skey;?> </th>
                                                            <td >
															   <?php echo ucwords($soption['text']).' ('.$soption['printing_effect'].')<br>';
															   
															   
															   $normal_p=$obj_quotation->numberFormate((($soption['totalPrice'] / $skey) / $dat['currency_price']),"3");	
																			//$extra_p=$obj_quotation->numberFormate(((($soption['totalPrice'] / $skey) / $dat['currency_price'])));
																			//*15/100),"3");
																			$f_p=$normal_p;
																			//+$extra_p;
																			
																			echo '<b style="color:red"><br>Price / Pouch : <br>'.$dat['currency'].' '.$f_p.'</b><br><br><b style="color:#CC33FF">Tool Price :'.$soption['tool_price'].'<br><br>Cylinder Price : '.$soption['cylinder_price'].'</b>';
															   
															  //  echo '<b style="color:red"><br>Price / Pouch : <br>'.$dat['currency'].' '.$obj_quotation->numberFormate(($pretot-$predis),"3").'</b>';
															  
															  if($soption['ink_sel']==0)
															  {
															      echo '<br><br>With ink';
															      echo '<br><small class="text-muted">Ink Mul By : '.$soption['ink_mul_by'].'<br><small class="text-muted">Adhesive Mul By : '.$soption['adh_mul_by'] ;
															  }
															  else
															  {
															      echo '<br><br>Without ink';
															  }
															  
															  
															  ?></td>
                                                                <td><?php echo (int)$soption['width'].'X'.(int)$soption['height'].'X'.(int)$soption['gusset']; if($data[0]['product_id']!=10){if($soption['volume']!='') echo ' ('.$soption['volume'].')'; else echo ' (Custom)';}
                                                                            if($data[0]['product_id']=='66')
                                                                                echo '<br><small class="text-muted">Calculation done by using this size : </small>'.((int)$soption['width']+10).'X'.((int)$soption['height']+10).'X'.(int)$soption['gusset'];
                                                                                
                                                                        ?><?php foreach($soption['quantity_option'] as $squantityKey=>$squantity) { 
																//printr($squantity);
																
													echo '<br/><small class="text-muted">'.$squantityKey.' : '.$squantity.'</small>';
												}echo '<br><small class="text-muted">Make Pouch : '.$soption['make'].'</small>';
												if($soption['spout_txt'] != 'No Spout')
                                                        echo '<br><small class="text-muted">Total Spout Weight: '.number_format($spout_weight['weight']*$skey,3,".","").' KG</small>';
													if($soption['zipper_txt'][0] == 'T')
                                                        echo '<br><small class="text-muted">Total TinTie Weight: '.number_format($tintie_weight['weight']*$skey,3,".","").' KG</small>';
																						
														echo '<br><span style="font-size:13px"><small class="text-muted">Ink Price : '.$soption['ink_price'].'</span>';
														echo '<br><span style="font-size:13px"><small class="text-muted">Ink Solvent Price : '.$soption['ink_solvent_price'].'</span>';
														
														echo '<br><span style="font-size:13px"><small class="text-muted">';
														if($soption['cpp_adhesive']=='1')
															echo 'CPP Adhesive Price : '.$soption['adhesive_price'];
														else
															echo 'Adhesive Price : '.$soption['adhesive_price'];
														echo '</span>';
														if($soption['adhesive_solvent_price']!='0.000')
														echo '<br><span style="font-size:13px"><small class="text-muted">Adhesive Solvent Price : '.$soption['adhesive_solvent_price'].'</span>';
												?></td>
                                                 <td>
                                                             <?php    for($gi=0;$gi<count($soption['materialData']);$gi++){
											  echo '<b>'.($gi+1).' Layer : </b>'.$soption['materialData'][$gi]['material_name'].' : '.(int)$soption['materialData'][$gi]['material_thickness'].'<br><b  style="color:#51A351">Price : '.$soption['materialData'][$gi]['material_price'].'</b><br><br>';
										}?>
                                                                 </td>
                                                                <?php if($k=='sea') { ?>
                                                                <td>
																	<?php echo $soption['transport_price']; ?>
                                                                </td>
                                                                <?php } else {?>
                                                                <td>
                                                                <?php echo '<b>'.$soption['courier_charge'].'</b><br><br> <b style="color:#0066CC"> Courier Charge With Tax : ';
												   
												   if(isset($soption['quantity_option']['Total Weight Without Zipper']) && $soption['quantity_option']['Total Weight Without Zipper']!='0.000 KG')
												   {
												      
													 echo $obj_quotation->numberFormate(($soption['courier_charge']/$soption['quantity_option']['Total Weight Without Zipper']),"3").'</b><br>';
													}
													else if(isset($soption['quantity_option']['Total Weight Without Zipper With Tin Tie']) && $soption['quantity_option']['Total Weight Without Zipper With Tin Tie']!='0.000 KG')
													{
														
														echo $obj_quotation->numberFormate(($soption['courier_charge']/$soption['quantity_option']['Total Weight Without Zipper With Tin Tie']),"3").'</b><br>';
													}
													else if(isset($soption['quantity_option']['Total Weight Without Zipper With Spout']) && $soption['quantity_option']['Total Weight Without Zipper With Spout']!='0.000 KG')
													{
														
														echo $obj_quotation->numberFormate(($soption['courier_charge']/$soption['quantity_option']['Total Weight Without Zipper With Spout']),"3").'</b><br>';
													}
													else
													{
														echo $obj_quotation->numberFormate(($soption['courier_charge']/$soption['quantity_option']['Total Weight With Zipper']),"3").'</b><br>';
													}
													
										echo '<br> <b style="color:#0066CC">Basic Courier Price : '.$soption['actual_courier_price'].' </b><br>' ;
										
										 ?>
                                                                </td>
                                                                <?php }?>
                                                                <td>
                                                               <?php 
															if(isset($soption['zipper_option']['spout_price'])){
																echo '<b>Spout  : </b>'.$soption['zipper_option']['spout_price'].'<br>';
															}
															if(isset($soption['zipper_option']['accessorie_price'])){
																echo '<b>Acc  : </b>'.$soption['zipper_option']['accessorie_price'].'<br>';
															}
															if(strchr($soption['text'],"Tin"))
															{
																echo '<b>Tin Tie  : </b>'.$soption['zipper_option']['zipper_price'].'<br>';
															}
															else
															{
																echo '<b>Zipper  : </b>'.$soption['zipper_option']['zipper_price'].'<br>';
															}
																echo '<b>Valve Price :</b>'.$soption['zipper_option']['valve_price'];?>
                                                                
                                                                 </td>
                                                                 <td><?php //printr($soption); 
																	 echo  '<b style="color:#FF6600">Pouch Packing Price : '.$soption['packing_price'].'</b><br>';
																	  if($soption['spout_txt'] != 'No Spout')
																		 echo '<br><b style="color:#FF6600">Spout Packing Price : '.$soption['spout_additional_packing_price'].'</b>';
																	 
																	 $packing_total=$soption['packing_price']+$soption['spout_additional_packing_price'];
																	 echo '<br><br><b style="color:#FF6600">Total : '.$obj_quotation->numberFormate($packing_total,"3").'</b>';
																	 ?></td>
                                                            </tr>
                                                            <?php
                                                        }
                                                        ?>
                                                    </tr>
                                                    <?php $i++;
                                                }
                                             ?>
                                          </tbody>
										</table>
									  </div>
									</section> 
								</div>
							  </div>
			<?php	}}?>
                      <div class="form-group">
                        <div class="col-lg-9 col-lg-offset-3">
                            <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '&mod=view&quotation_id='.encode($quotation_id),'',1);?>">Cancel</a>
                        </div>
                      </div>
                    </form>
                </div>
          </section>    
      </div>
    </div>
  </section>
</section>
<!-- Modal -->
<div class="modal fade" id="smail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="sform" id="sform" style="margin-bottom:0px;">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <input type="hidden" name="sscurrency" id="sscurrency" value="" />
                <input type="hidden" name="sscurrencyrate" id="sscurrencyrate" value="" />
                <h4 class="modal-title" id="myModalLabel">Send Email</h4>
              </div>
              <div class="modal-body">
                   <div class="form-group">
                        <label class="col-lg-3 control-label">Email</label>
                        <div class="col-lg-8">
                             <input type="text" name="smail" placeholder="Email" value="" class="form-control validate[required,custom[email]]">
                        </div>
                     </div> 
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="submit" name="btn_sendemail" class="btn btn-primary btn-sm">Send</button>
              </div>
   		</form>   
    </div>
  </div>
</div>
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script>
    jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#sform").validationEngine();
	});
</script>	
<!-- Close : validation script -->
<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>