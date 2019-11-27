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
$allow_currency_status = $obj_custom_order->allowCurrencyStatus($user_type_id,$user_id);

//Start : edit
$edit = '';
if(isset($_GET['custom_order_id']) && !empty($_GET['custom_order_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$custom_order_id = base64_decode($_GET['custom_order_id']);
		$getData = ' custom_order_id, mco.added_by_user_id, mco.added_by_user_type_id, customer_name, shipment_country_id, custom_order_type, quantity_type, product_id, product_name, printing_option, printing_effect, height, width, gusset, layer, currency, currency_price, cylinder_price, customer_gress_percentage,mco.status,mco.custom_order_status,valve_price';
		$data = $obj_custom_order->getCustomOrder($custom_order_id,$getData,$obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);
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
                 <span>Custom Order Detail</span>
                </header>
              	<div class="panel-body">
              		<label class="label bg-white m-l-mini">&nbsp;</label>
                	<span class="text-muted m-l-small pull-right">
                    	Your base currency : <b><?php echo (isset($data['currency']) && $data['currency'] != '')?$data['currency']:'INR'?></b>
                    </span>
                <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
                       <div class="col-lg-7" style=" width:50%">
                      <div class="form-group">
                        <label class="col-lg-3 control-label"  style="width:25%">Custom Order Number</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo $data[0]['multi_custom_order_number'];?>
                            </label>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label"  style="width:25%">Customer Name</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo ucwords($data[0]['customer_name']);?>
                            </label>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label"  style="width:25%">Shipment Country</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo $data[0]['country_name'];?>
                            </label>
                        </div>
                      </div>
                      
                      <?php if($data[0]['customer_gress_percentage'] > 0){ ?>
                      <div class="form-group">
                        <label class="col-lg-3 control-label"  style="width:25%">Customer Gress (%)</label>
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
                        <label class="col-lg-3 control-label"  style="width:25%">Printing Option</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                                <?php echo $data[0]['printing_option'];?>
                            </label>
                        </div>
                      </div>
                   
                      <?php
                      if($data[0]['custom_order_type'] == 1){ ?>
                          <div class="form-group">
                            <label class="col-lg-3 control-label"  style="width:25%">Quantity In </label>
                            <div class="col-lg-4">
                                <label class="control-label normal-font">
                                    <?php echo ucwords($data[0]['quantity_type']);?>
                                </label>
                            </div>
                          </div>
                      <?php
					  } ?>
                      </div>
                       <div class="col-lg-5" style=" width:50%">
                                           
                      <div class="form-group">
                        <label class="col-lg-3 control-label" style="width:25%">Company Name</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo ucwords($data[0]['company_name']);?>
                            </label>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label" style="width:25%">Email</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo ucwords($data[0]['email']);?><br/>                           
                            <small class="text-muted"><?php echo "Contact Number: " .$data[0]['contact_number']; ?></small>
                            </label>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label" style="width:25%">Shipping Address</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo ucwords($data[0]['address'])." ,".$data[0]['city']." ,".$data[0]['state'];?>
                            </label>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label" style="width:25%">Order Note</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            	<?php echo $data[0]['order_note']; ?>
                            </label>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label" style="width:25%">Order Instruction</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            	<?php echo $data[0]['order_instruction']; ?>
                            </label>
                        </div>
                      </div>
                      </div>
					  <?php foreach($data as $dat)
					   {
					 	 $result = $obj_custom_order->getCustomOrderQuantity($dat['custom_order_id']);
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
								<label class="col-lg-3 control-label" style="width: 12%;">Price (By <?php echo $k;?>)</label> 
								<div class="col-lg-9">
									<section class="panel">
									  <div class="table-responsive">
										<table class="table table-striped b-t text-small">
										  <thead>
											  <tr>
                                          <th>Product Name</th>
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
                                                            ?>
                                                            <tr>
                                                              <th><?php echo $soption['product_name'];?></th>
                                                            <th ><?php echo $skey;?> </th>
                                                            <td >
															   <?php echo ucwords($soption['text']).' ('.$soption['printing_effect'].')';?></td>
                                                                <td><?php echo (int)$soption['width'].'X'.(int)$soption['height'].'X'.(int)$soption['gusset']; if($data[0]['product_id']!=10){if($soption['volume']!='') echo ' ('.$soption['volume'].')'; else echo ' (Custom)';}?><?php foreach($sdata[0]['quantity_option'] as $squantityKey=>$squantity) { 
													echo '<br/><small class="text-muted">'.$squantityKey.' : '.$squantity.'</small>';
												}echo '<br><small class="text-muted">Make Pouch : '.$soption['make'].'</small>';
												?></td>
                                                 <td>
                                                             <?php    for($gi=0;$gi<count($soption['materialData']);$gi++){
											  echo '<b>'.($gi+1).' Layer : </b>'.$soption['materialData'][$gi]['material_name'].' : '.(int)$soption['materialData'][$gi]['material_thickness'].'<br>';
										}?>
                                                                 </td>
                                                                <?php if($k=='sea') { ?>
                                                                <td>
																	<?php echo $soption['transport_price']; ?>
                                                                </td>
                                                                <?php } else {?>
                                                                <td>
                                                                <?php echo $soption['courier_charge']; ?>
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
																echo '<b>Zipper  : </b>'.$soption['zipper_option']['zipper_price'].'<br>
																<b>Valve Price :</b>'.$soption['zipper_option']['valve_price'];?>
                                                                
                                                                 </td>
                                                                 <td><?php echo  $soption['packing_price'];?></td>
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
                            <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '&mod=view&custom_order_id='.encode($custom_order_id),'',1);?>">Cancel</a>
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