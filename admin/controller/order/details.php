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
		$getData = ' product_quotation_id, added_by_user_id, added_by_user_type_id, customer_name, shipment_country_id, quotation_number, quotation_type, quantity_type, product_id, product_name, printing_option, printing_effect, height, width, gusset, layer, currency, currency_price, cylinder_price, customer_gress_percentage, status,quotation_status';
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
        
      <div class="col-sm-10">
        
            <section class="panel">  
            	
                <header class="panel-heading bg-white">
                 <span>Quotation Detail</span>
                </header>
              
              	<div class="panel-body">
              		<label class="label bg-white m-l-mini">&nbsp;</label>
                	<span class="text-muted m-l-small pull-right">
                    	Your base currency : <b><?php echo (isset($data['currency']) && $data['currency'] != '')?$data['currency']:'INR'?></b>
                    </span>
                 
                
                <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
                     
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Customer Name</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo ucwords($data['customer_name']);?>
                            </label>
                        </div>
                      </div>
                      
                      <?php if($data['customer_gress_percentage'] > 0){ ?>
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Customer Gress (%)</label>
                        <div class="col-lg-8">
                            <label class="control-label normal-font">
                            <?php echo $data['customer_gress_percentage'];?>
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
                            <?php echo $data['product_name'];?>
                            </label>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Size</label>
                        <div class="col-lg-9">
                            <section class="panel">
                              <div class="table-responsive">
                                <table class="table table-striped b-t text-small">
                                  <thead>
                                    <tr>
                                      <th>Width</th>
                                      <th><?php if($data['quotation_type'] == 1){
												echo 'Repeat Length';
											}else{
												echo 'Height';
											}
										?></th>
                                      <?php if($data['quotation_type'] == 0){ ?> 
                                      	<th>Gusset</th>
                                      <?php } ?>
                                    </tr>
                                  </thead>
                                  <tbody>
                                       <tr>
                                          	<td><?php echo (int)$data['width'];?> mm</td>
                                          	<td><?php echo (int)$data['height'];?> mm</td>
                                            <?php if($data['quotation_type'] == 0){ ?> 
                                          		<td><?php echo (int)$data['gusset'];?> mm</td>
                                            <?php } ?>
                                       </tr>
                                  </tbody>
                                </table>
                              </div>
                            </section> 
                        </div>
                      </div>
                                            
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Printing Option</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                                <?php echo $data['printing_option'];?>
                            </label>
                        </div>
                      </div>
                      <?php if($data['printing_option'] == 'With Printing'){?>
                          <div class="form-group">
                            <label class="col-lg-3 control-label">Printing Effect</label>
                            <div class="col-lg-4">
                                <label class="control-label normal-font">
                                    <?php echo $data['printing_effect'];?>
                                </label>
                            </div>
                          </div>
                      <?php } ?>
                      
                      <?php
					  $materialData = $obj_quotation->getQuotationMaterial($data['product_quotation_id']);
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
                      }
                      ?>
                      
                      <?php if($data['quotation_type'] == 1){ ?>
                          <div class="form-group">
                            <label class="col-lg-3 control-label">Quantity In </label>
                            <div class="col-lg-4">
                                <label class="control-label normal-font">
                                    <?php echo ucwords($data['quantity_type']);?>
                                </label>
                            </div>
                          </div>
                      <?php
					  } 
					  ?>
                      
                      <?php
                      $quotation_packing_transport = $obj_quotation->getQuotationPackingAndTransportDetails($data['product_quotation_id']);
					  ?>
                      <div class="form-group">
                            <label class="col-lg-3 control-label">Packing Price/Pouch </label>
                            <div class="col-lg-4">
                                <label class="control-label normal-font">
                                    <?php echo "INR ".($quotation_packing_transport['packing_price']); ?>
                                </label>
                            </div>
                      </div>
                      
                      <div class="form-group">
                            <label class="col-lg-3 control-label">Transport/Pouch </label>
                            <div class="col-lg-4">
                                <label class="control-label normal-font">
                                    <?php echo "INR ".($quotation_packing_transport['transport_width_base_price']+$quotation_packing_transport['transport_height_base_price']); ?>
                                </label>
                            </div>
                      </div>
                      
                      <?php
                      $quotation_other_details = $obj_quotation->getQuotationOtherDetails($data['product_quotation_id']);
					   //printr($quotation_other_details);die;
					  if(isset($quotation_other_details['sea']) && !empty($quotation_other_details['sea'])) {
					  ?>
                      
                      <div class="form-group">
                            <label class="col-lg-3 control-label">By Sea</label>
                            <div class="col-lg-9">
                                <section class="panel">
                                  <div class="table-responsive">
                                    <table class="table table-striped b-t text-small">
                                      <thead>
                                        <tr>
                                          <th>Quantity</th>
                                          <th>Options</th>
                                          <th>Transport Price</th>
                                          <th>Zipper Price</th>
                                          <th>Valve Price</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                      <?php
										foreach($quotation_other_details['sea'] as $skey=>$sdata){
											//printr($sdata);die;
											?>
											<tr>
											  <td rowspan="<?php echo (count($sdata['zipper_option']) + 1);?>">
												<?php echo $skey;
												foreach($sdata['quantity_option'] as $squantityKey=>$squantity) { 
													echo '<br/><small class="text-muted">'.$squantityKey.' : '.$squantity.'</small>';
												}
												?>
											  </td>
											  
											   <?php
												  foreach($sdata['zipper_option'] as $optionKey=>$soption){
													  echo '<tr>';
														echo '<td>'.$optionKey.'</td>'; 
														echo '<td>'.$sdata['transport_price'].'</td>';
														echo '<td>'.$soption['zipper_price'].'</td>';
														echo '<td>'.$soption['valve_price'].'</td>';
													  echo '</tr>';	
														
												  }
											   ?>
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
                          <?php } 
						  if(isset($quotation_other_details['air']) && !empty($quotation_other_details['air'])) { ?>
                          
						  	 <div class="form-group">
                                <label class="col-lg-3 control-label">By Air</label>
                                <div class="col-lg-9">
                                    <section class="panel">
                                      <div class="table-responsive">
                                        <table class="table table-striped b-t text-small">
                                          <thead>
                                            <tr>
                                              <th>Quantity</th>
                                              <th>Options</th>
                                              <th>Courier Charge</th>
                                              <th>Zipper Price</th>
                                              <th>Valve Price</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                          <?php
                                            foreach($quotation_other_details['air'] as $akey=>$adata){
                                                //printr($sdata);die;
                                                ?>
                                                <tr>
                                                  <td rowspan="<?php echo (count($adata['zipper_option']) + 1);?>">
                                                    <?php echo $akey;
                                                    foreach($adata['quantity_option'] as $aquantityKey=>$aquantity) { 
                                                        echo '<br/><small class="text-muted">'.$aquantityKey.' : '.$aquantity.'</small>';
                                                    }
                                                    ?>
                                                  </td>
                                                  
                                                   <?php
                                                      foreach($adata['zipper_option'] as $aoptionKey=>$aoption){
                                                          echo '<tr>';
                                                            echo '<td>'.$aoptionKey.'</td>'; 
                                                            echo '<td>'.$adata['courier_charge'].'</td>';
                                                            echo '<td>'.$aoption['zipper_price'].'</td>';
                                                            echo '<td>'.$aoption['valve_price'].'</td>';
                                                          echo '</tr>';	
                                                      }
                                                   ?>
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
                          <?php } 
						   
						   if(isset($quotation_other_details['pickup']) && !empty($quotation_other_details['pickup'])) { ?>
                          
						  	 <div class="form-group">
                                <label class="col-lg-3 control-label">By Pickup</label>
                                <div class="col-lg-9">
                                    <section class="panel">
                                      <div class="table-responsive">
                                        <table class="table table-striped b-t text-small">
                                          <thead>
                                            <tr>
                                              <th>Quantity</th>
                                              <th>Options</th>
                                              <th>Courier Charge</th>
                                              <th>Zipper Price</th>
                                              <th>Valve Price</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                          <?php
                                            foreach($quotation_other_details['pickup'] as $pkey=>$pdata){
                                                //printr($sdata);die;
                                                ?>
                                                <tr>
                                                  <td rowspan="<?php echo (count($pdata['zipper_option']) + 1);?>">
                                                    <?php echo $pkey;
                                                    foreach($pdata['quantity_option'] as $pquantityKey=>$pquantity) { 
                                                        echo '<br/><small class="text-muted">'.$pquantityKey.' : '.$pquantity.'</small>';
                                                    }
                                                    ?>
                                                  </td>
                                                  
                                                   <?php
                                                      foreach($pdata['zipper_option'] as $poptionKey=>$poption){
                                                          echo '<tr>';
                                                            echo '<td>'.$poptionKey.'</td>'; 
                                                            echo '<td>'.$pdata['courier_charge'].'</td>';
                                                            echo '<td>'.$poption['zipper_price'].'</td>';
                                                            echo '<td>'.$poption['valve_price'].'</td>';
                                                          echo '</tr>';	
                                                      }
                                                   ?>
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
						  
						 <?php } ?>
						  
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