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
//printr($_SESSION);die;
$allow_currency_status = $obj_quotation->allowCurrencyStatus($user_type_id,$user_id);

//Start : edit
$edit = '';
if(isset($_GET['quotation_id']) && !empty($_GET['quotation_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$quotation_id = base64_decode($_GET['quotation_id']);
		$getData = " product_quotation_id, added_by_user_id, added_by_user_type_id, customer_name, shipment_country_id, quotation_number, quotation_type, quantity_type, product_id, product_name, printing_option, printing_effect, height, width, gusset, layer, currency, currency_price, cylinder_price,tool_price, customer_gress_percentage,pq.status,quotation_status,discount";
		$data = $obj_quotation->getQuotation($quotation_id,$getData,$obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);
		$tax_type = $obj_quotation->CheckQuotationTax($quotation_id);
		//printr($data);die;
	//$tool_price = $obj_quotation->getToolPrice($data['width'],$data['gusset'],$data['product_id']);
	//	printr($tool_price);
	}
}
//Close : edit
if($display_status){
	
	//Add quotation
	if(isset($_POST['btn_save'])){
		//printr($obj_session->data['repost']);die;
		$quotation_id = $data['product_quotation_id'];
		$obj_quotation->upadteQuotation($quotation_id);
		$obj_session->data['success'] = 'Your quotation saved successfully!';
		page_redirect($obj_general->link($rout, '&mod=view&quotation_id='.encode($quotation_id), '',1));
	}
	
	//send emial code
	if(isset($_POST['btn_sendemail'])){
		if(isset($_POST['smail']) && !empty($_POST['smail'])){
			$gemail = trim($_POST['smail']);
			if (!filter_var($gemail, FILTER_VALIDATE_EMAIL)) {
			  	$obj_session->data['warning'] = 'Please enter email address!';
				page_redirect($obj_general->link($rout, '&mod=view&quotation_id='.encode($quotation_id), '',1));
			}else{
				$quotation_id = $data['product_quotation_id'];
				$setCurrencyId = '';
				
				if(isset($_POST['sscurrency']) && !empty($_POST['sscurrency']) ){
					//isset($_POST['sscurrencyrate']) && (float)$_POST['sscurrencyrate'] > 0 
					/*if(isset($_POST['sscurrencyrate']) && (float)$_POST['sscurrencyrate'] > 0 ){
						$currencyRate = $_POST['sscurrencyrate'];
					}else{
						$currencyRate = 'NO';
					}*/
					$getSelCurrecnyData = $obj_quotation->getSelCurrencyInfo(decode($_POST['sscurrency']));
					$setCurrencyId = 0;
					if($getSelCurrecnyData){
						$setCurrencyId = $obj_quotation->setQuotationCurrency($quotation_id,$getSelCurrecnyData['currency_code'],$getSelCurrecnyData['price'],1);
					}
				}
			//	echo decode($_POST['sscurrency']);
			$obj_quotation->sendQuotationEmail($quotation_id,$gemail,$setCurrencyId);
				$obj_session->data['success'] = 'Success : Email send !';
				//page_redirect($obj_general->link($rout, '&mod=view&quotation_id='.encode($quotation_id), '',1));
			}
		}else{
			$obj_session->data['warning'] = 'Please enter emial address!';
			page_redirect($obj_general->link($rout, '&mod=view&quotation_id='.encode($quotation_id), '',1));
		}
	}
	
	if(isset($_GET['filter_edit'])){
		$filteredit = $_GET['filter_edit'];
	}else{
		$filteredit = 0;
	}
	if($_SESSION['ADMIN_LOGIN_USER_TYPE']==4)
	{
		$adminId = $obj_quotation->getEmpAdminId($_SESSION['ADMIN_LOGIN_SWISS']);
		$adminCountryId = $obj_quotation->getUser($adminId,$_SESSION['ADMIN_LOGIN_USER_TYPE']);
		//printr($adminCountryId);
	}
	else
	{
		$adminCountryId=$obj_quotation->getUser($user_id,$user_type_id);
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
        
      <div class="col-sm-8" style="width:85%">
        
            <section class="panel">  
            	
                <header class="panel-heading bg-white">
                 <span>Quotation Detail</span>
                 <span class="text-muted m-l-small pull-right">
                 	
                    <?php
					if($data['status'] == 1){ ?>
                   	<a class="label bg-info pdfcls" href="javascript:void(0);"><i class="fa fa-print"></i> PDF</a>
                    	<a class="label bg-primary sendmailcls" href="javascript:void(0);"><i class="fa fa-envelope"></i> Send Mail</a>
                    <?php } //data-toggle="modal" data-target="#smail" //target="_blank" href="<?php echo HTTP_SERVER.'pdf/pdf.php?mod='.encode('productQuotation').'&token='.rawurlencode(encode($data['product_quotation_id'])).'&ext='.md5('php'); ?>    
                 </span>
              </header>
              
              <div class="panel-body">
              	<label class="label bg-white m-l-mini">&nbsp;</label>
                	<span class="text-muted m-l-small pull-right">
                    	Your base currency : <b><?php echo (isset($data['currency']) && $data['currency'] != '')?$data['currency']:'INR'?></b>
                    </span>
                 <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
                      
                      
                      <?php //printr($allow_currency_status);die;
					  if($allow_currency_status && $data['status'] == 1){
						  
						  if($data['currency'] && $data['currency'] != ''){
							  $notGetCurrency = $data['currency'];
						  }else{
							  $notGetCurrency = 'INR';
						  }
						  $currencys = $obj_quotation->getNewCurrencys();
						  if($currencys){
							  ?>
                              <div class="form-group">
								<label class="col-lg-3 control-label">Select Currency</label>
								<div class="col-lg-9 row">
                                	<div class="col-lg-4">
                                        <select name="sel_currency" id="sel_currency" onchange="getCurrencyValue();" class="form-control">
                                            <option value="">Select Currency</option>
                                            <?php
                                            foreach($currencys as $currency){
                                                echo '<option value="'.encode($currency['currency_id']).'">'.$currency['currency_code'].'</option>';
                                            } ?>
                                        </select>
                                    </div>
                                    
                                    <div class="col-lg-4">
                                        <input type="text" name="sel_currency_rate" readonly="readonly" id="sel_currency_rate" placeholder="Currency Rate" class="form-control validate[condRequired[sel_currency],custom[number]]">
                                    </div>
                                        
								</div>
							  </div>
							  <?php
						  }
					  }
					  ?>
                      
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Quotation Number</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo $data['quotation_number'];?>
                            </label>
                        </div>
                      </div>
                      
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Customer Name</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo ucwords($data['customer_name']);?>
                            </label>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Shipment Country</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo $data['country_name'];?>
                            </label>
                            <?php if(isset($tax_type['tax_type']) && $tax_type['tax_type'] !='')
							{
								if($tax_type['tax_type']=='cst')
								{
									echo ' <label class="control-label normal-font"> &nbsp;&nbsp;&nbsp;&nbsp;(Out Of Gujarat)  </label>';
								}
								else
								{
									echo '<label class="control-label normal-font"> &nbsp;&nbsp;&nbsp;&nbsp;(With In Gujarat)</label> ';
								}
							}
							?>
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
                       <input type="hidden" id="max_discount" name="max_discount" value="<?php echo isset($adminCountryId['discount'])?$adminCountryId['discount']:''; ?>" class="form-control"/>
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
					   
					  $quantityData = $obj_quotation->getQuotationQuantity($data['product_quotation_id']);
					// printr($quantityData);
                      if(isset($quantityData) && !empty($quantityData)){
						  if(isset($quantityData['sea']) && !empty($quantityData['sea'])){
							  ?>
							  <div class="form-group">
								<label class="col-lg-3 control-label">Price (By Sea)</label> 
                               
								<div class="col-lg-9">
									<section class="panel">
									  <div class="table-responsive">
										<table class="table table-striped b-t text-small">
										  <thead>
											  <tr>
                                                <th>Quntity</th>
                                                <?php if($data['quotation_type'] != 1){ ?>
                                                	<th>Option</th>
                                                <?php } ?>
                                                <?php if($data['quotation_status'] == 0){
													 if(isset($adminCountryId['discount']) and $adminCountryId['discount']>0.000)
													{ 				
													 if($data['currency']=='INR')
												echo '<th>Discount</th>';
													} }?>
                                                <th>Price / pouch</th>
                                                <th>Total</th>
                                                 
                                              </tr>
										  </thead>
                                          <tbody>
											<?php $i=1;
                                                foreach($quantityData['sea'] as $skey=>$sdata){
                                                    ?>
                                                    <tr>
                                                        <th rowspan="<?php echo (count($sdata) + 1);?>"><?php echo $skey?></th>
                                                        <?php
                                                        foreach($sdata as $soption){
                                                            ?>
                                                            <tr>
                                                            	<?php if($data['quotation_type'] != 1){ ?>
                                                                	<td><?php echo ucwords($soption['text']);?></td>
                                                                <?php } ?>
                                                                 <?php if($data['quotation_status'] == 0){
																	 if(isset($adminCountryId['discount']) and $adminCountryId['discount']>0.000)
																{  if($data['currency']=='INR')
																echo '<td><input type="text" id="discount_'.$i.'" name="discount_'.$i.'" value="'.$soption['discount'].'" class="form-control" style="width: 100px;"  onfocusout="changeDiscount('.$i.')">
																<input type="hidden" id="quantity_'.$i.'" name="quantity_'.$i.'" value="'.$soption['product_quotation_quantity_id'].'" class="form-control" style="width: 100px;" ></td>'; } }?>
                                                                <td>
																	<?php   if($soption['discount'] && $soption['discount'] >0.000) {?>
                                                                <b>Total : </b><?php $pretot= $obj_quotation->numberFormate((($soption['totalPrice'] / $skey) / $data['currency_price']),"3"); echo $pretot;?><br />
                                                                <b>Discount (<?php echo $soption['discount'];?> %) : </b>
																<?php $predis = $pretot*$soption['discount']/100; 
																echo $obj_quotation->numberFormate($predis,"3");?><br />
                                                                <b>Final Total : </b>
																<?php echo $data['currency'].' '.$obj_quotation->numberFormate(($pretot-$predis),"3"); ?>
																	<?php }else echo $data['currency'].' '.
																	$obj_quotation->numberFormate((($soption['totalPrice'] / $skey) / $data['currency_price']),"3");?>
                                                                </td>
                                                                <td>
                                                                <?php  if($soption['discount'] && $soption['discount'] >0.000) {?>
                                                                <b>Total : </b><?php $tot= $obj_quotation->numberFormate(($soption['totalPrice'] / $data['currency_price'] ),"3"); echo $tot;?><br />
                                                                <b>Discount (<?php echo $soption['discount'];?> %) : </b>
																<?php $dis = $tot*$soption['discount']/100; 
																echo $obj_quotation->numberFormate($dis,"3");?><br />
                                                                <b>Final Total : </b>
																<?php echo $data['currency'].' '.($tot-$dis); ?>
																	<?php } else echo $data['currency'].' '.$obj_quotation->numberFormate(($soption['totalPrice'] / $data['currency_price'] ),"3");?>
                                                                 </td>
                                                                
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
							  <?php
						  }
						  
						  if(isset($quantityData['air']) && !empty($quantityData['air'])){
							  ?>
							  <div class="form-group">
								<label class="col-lg-3 control-label">Price (By Air)</label>
								<div class="col-lg-9">
									<section class="panel">
									  <div class="table-responsive">
                                      	    
                                            <table class="table table-striped b-t text-small">
                                              <thead>
                                                  <tr>
                                                    <th>Quntity</th>
                                                    <?php if($data['quotation_type'] != 1){ ?>
                                                    	<th>Option</td>
                                                    <?php } ?>
                                                    <?php if($data['quotation_status'] == 0){
														if(isset($adminCountryId['discount']) and $adminCountryId['discount']>0.000)
					{ if($data['currency']=='INR')
												echo '<th>Discount</th>';} }?>
                                                    <th>Price / pouch</td>
                                                    <th>Total</td>
                                                  </tr>
                                              </thead>
                                              <tbody>
                                              	<?php $i=1; //printr($quantityData['air']);
													foreach($quantityData['air'] as $akey=>$adata){
														?>
                                                        <tr>
                                                            <th rowspan="<?php echo (count($adata) + 1);?>"><?php echo $akey?></th>
                                                            <?php
															foreach($adata as $aoption){
																?>
                                                                <tr>
                                                                	<?php if($data['quotation_type'] != 1){ ?>
                                                                    	<td><?php echo ucwords($aoption['text']);?></td>
                                                                    <?php } ?>
                                                                   
																   <?php if($data['quotation_status'] == 0){
																	    if(isset($adminCountryId['discount']) and $adminCountryId['discount']>0.000)
																{ if($data['currency']=='INR')
																echo '<td><input type="text" id="discount_'.$i.'" name="discount_'.$i.'" value="'.$aoption['discount'].'" class="form-control" style="width: 100px;"  onfocusout="changeDiscount('.$i.')">
																<input type="hidden" id="quantity_'.$i.'" name="quantity_'.$i.'" value="'.$aoption['product_quotation_quantity_id'].'" class="form-control" style="width: 100px;" >
																</td>'; } }?>
                                                                   <td>  <?php  if($aoption['discount']  && $aoption['discount'] >0.000) {?>
																	<b>Total : </b><?php $pretot= $obj_quotation->numberFormate((($aoption['totalPrice'] / $akey ) / $data['currency_price']),"3"); echo $pretot;?><br />
                                                                    <b>Discount (<?php echo $aoption['discount'];?> %) : </b>
																	<?php $predis = $pretot*$aoption['discount']/100; 
																	echo $obj_quotation->numberFormate($predis,"3");?><br />
                                                                    <b>Final Total : </b>
																	<?php echo $data['currency'].' '.$obj_quotation->numberFormate(($pretot-$predis),"3"); ?>
																<?php }else echo $data['currency'].' '.$obj_quotation->numberFormate((($aoption['totalPrice'] / $akey ) / $data['currency_price']),"3");?>
                                                                    </td>
                                                                    <td>
                                                                    <?php if($aoption['discount']  && $aoption['discount'] >0.000) {?>
																	<b>Total : </b><?php $tot= $obj_quotation->numberFormate(($aoption['totalPrice'] / $data['currency_price']),"3"); echo $tot;?><br />
                                                                    <b>Discount (<?php echo $aoption['discount'];?> %) : </b>
																	<?php $dis = $tot*$aoption['discount']/100; echo $obj_quotation->numberFormate($dis,"3");?><br />
                                                                    <b>Final Total : </b>
																	<?php echo $data['currency'].' '.$obj_quotation->numberFormate(($tot-$dis),"3"); ?>
																<?php }else echo $data['currency'].' '.$obj_quotation->numberFormate(($aoption['totalPrice'] / $data['currency_price']),"3");?></td>
                                                                </tr>
																<?php
															}
															?>
                                                        </tr>
                                                        <?php
														$i++;
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
						  if(isset($quantityData['pickup']) && !empty($quantityData['pickup'])){
							  ?>
							  <div class="form-group">
								<label class="col-lg-3 control-label">Price (By Pickup)</label>
								<div class="col-lg-9">
									<section class="panel">
									  <div class="table-responsive">
										<table class="table table-striped b-t text-small">
										  <thead>
											  <tr>
                                                <th>Quntity</th>
                                                <?php if($data['quotation_type'] != 1){ ?>
                                                	<th>Option</th>
                                                <?php } ?>
                                                <?php if($data['quotation_status'] == 0){
													if(isset($adminCountryId['discount']) and $adminCountryId['discount']>0.000)
					{  if($data['currency']=='INR')
												echo '<th>Discount</th>'; } }?>
                                                <th>Price / pouch</th>
                                                <th>Total</th>
                                                <th> Price / pouch  With Tax </th>
                                                 <th>Total Price With Tax </th>
                                              </tr>
										  </thead>
                                          <tbody>
											<?php $i=1;
                                                foreach($quantityData['pickup'] as $pkey=>$pdata){
                                                    ?>
                                                    <tr>
                                                        <th rowspan="<?php echo (count($pdata) + 1);?>"><?php echo $pkey?></th>
                                                        <?php
                                                        foreach($pdata as $poption){
                                                            ?>
                                                            <tr>
                                                            	<?php if($data['quotation_type'] != 1){ ?>
                                                                	<td><?php echo ucwords($poption['text']);?></td>
                                                                <?php } ?>
                                                                 <?php if($data['quotation_status'] == 0){
																	 if(isset($adminCountryId['discount']) and $adminCountryId['discount']>0.000)
					{ if($data['currency']=='INR')
																echo '<td><input type="text" id="discount_'.$i.'" name="discount_'.$i.'" value="'.$poption['discount'].'" class="form-control" style="width: 100px;" onfocusout="changeDiscount('.$i.')">
																<input type="hidden" id="quantity_'.$i.'" name="quantity_'.$i.'" value="'.$poption['product_quotation_quantity_id'].'" class="form-control" style="width: 100px;" ></td>'; } }?>
                                                                <td>
                                                                <?php if($poption['discount']  && $poption['discount'] >0.000) {?>
                                                                <b>Total : </b><?php $pertot = $obj_quotation->numberFormate((($poption['totalPrice'] / $pkey) / $data['currency_price']),"3"); echo $pertot;?><br />
                                                                <b>Discount (<?php echo $poption['discount'];?> %) :</b>
                                                                     <?php $predis = ($pertot*$poption['discount'])/100;
																	 echo $obj_quotation->numberFormate($predis,"3");?>
                                                                     <br />
                                                                     <b>Final Total : </b>
																	<?php echo $data['currency'].' '.$obj_quotation->numberFormate(($pertot-$predis),"3");?>
                                                                <?php } else echo $data['currency'].' '.$obj_quotation->numberFormate((($poption['totalPrice'] / $pkey) / $data['currency_price']),"3");?>
                                                                </td>
                                                                <td>
																	 <?php  if($poption['discount']  && $poption['discount'] >0.000) {?>
                                                              <b>  Total : </b><?php $tot= $obj_quotation->numberFormate(($poption['totalPrice'] / $data['currency_price'] ),"3"); echo $tot;?><br />
                                                               <b>Discount (<?php echo $poption['discount'];?> %) :</b>
                                                                     <?php $dis = ($tot*$poption['discount'])/100;
																	 echo $obj_quotation->numberFormate($dis,"3");?>
                                                                     <br />
                                                                     <b>Final Total : </b>
																	<?php echo $data['currency'].' '.($tot-$dis);?>
                                                                <?php } else echo $data['currency'].' '.$obj_quotation->numberFormate(($poption['totalPrice'] / $data['currency_price'] ),"3");?>
                                                                 </td>
                                                                 
                                                                  <td>
																	<?php echo $data['currency'].' '.$obj_quotation->numberFormate((($poption['totalPriceWithTax'] / $pkey) / $data['currency_price'] ),"3");?>
                                                                 </td>
                                                                 <td>
																	<?php echo $data['currency'].' '.$obj_quotation->numberFormate(($poption['totalPriceWithTax'] / $data['currency_price'] ),"3");?>
                                                                 </td>
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
							  <?php
						  }
                      }
                      ?>
                     
                       <?php if($data['cylinder_price'] > 0){?>
                          <div class="form-group">
                            <label class="col-lg-3 control-label">Cylinder Price</label>
                            <div class="col-lg-4">
                                <label class="control-label normal-font">
                                    <?php echo $data['currency'].' '.$data['cylinder_price'];?>
                                </label>
                            </div>
                          </div>
                      <?php } ?>
                      
                      
                       <?php if($data['tool_price'] > 0){?>
                          <div class="form-group">
                            <label class="col-lg-3 control-label">Extra Tool Price</label>
                            <div class="col-lg-4">
                                <label class="control-label normal-font">
                                    <?php echo $data['currency'].' '.$data['tool_price'];?>
                                </label>
                            </div>
                          </div>
                      <?php } ?>
                      
                      <?php
						if($data['status'] == 1){
							?>
                            <div class="form-group">
                                <div class="col-lg-12"><h4><i class="fa fa-tags"></i> Email/PDF History</h4>
                                    <div class="line m-t-large" style="margin-top:-4px;"></div><br/>
                                	<?php
                                    	$history_data = $obj_quotation->getEmailHistories($data['product_quotation_id']);	  
										//printr($history_data);die;
                                		 ?>
                                         <section class="panel">
                                          <div class="table-responsive">
                                            <table class="table table-responsive table-striped b-t text-small">
                                              <thead>
                                                 <tr>
                                                     <th>To Email / PDF</th>
                                                     <th>Currency</th>
                                                     <?php
													 if(isset($data['currency']) && $data['currency'] != 'INR'){
                                                     	echo '<th>Currency Rate</th>';
													 }
													 ?>
                                                     <th>Date</th>
                                                  </tr>
                                               </thead>
                                               
                                               <tbody>
                                                <?php if($history_data) {
                                                      foreach($history_data as $hdata){
														  ?>
                                                          <tr>
                                                             <td><?php
                                                                if($hdata['source'] == 1){
                                                                    echo $hdata['to_email']; 
                                                                }else{
                                                                    echo "PDF";
                                                                }
                                                             ?></td>
                                                             <td><?php echo $hdata['currency_code']; ?></td>
                                                             <?php
                                                             if(isset($data['currency']) && $data['currency'] != 'INR'){
                                                                   echo '<td>'.$obj_quotation->numberFormate($hdata['currency_rate'],"3").'</td>';
                                                             }
                                                             ?>
                                                             <td><?php echo date('F d, Y',strtotime($hdata['date_added'])); ?></td>
                                                           </tr>
                                                      		<?php
                                                        } 
                                                } else { ?>
                                                    	<tr><td colspan="5">No Email History Available</td></td>    
                                                <?php } ?>       
                                               </tbody>
                                            </table> 
                                            </div>
                                         </section>
                                     </div>
                              </div>
                    		<?php
                        } ?>
                      
                      <div class="form-group">
                        <div class="col-lg-9 col-lg-offset-3">
                        	<?php if($data['quotation_status'] == 0){ ?>
                                <button type="submit" name="btn_save" id="btn_save" class="btn btn-primary">Save</button>	
                                <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '&mod=add', '',1);?>">Try New</a>			
                            <?php } ?>
                            
                            <a class="btn btn-default" href="<?php echo $obj_general->link($rout, 'filter_edit='.$filteredit							, '',1);?>">Cancel</a>
                            
                            <?php if($obj_session->data['ADMIN_LOGIN_SWISS']==1 && $obj_session->data['LOGIN_USER_TYPE']==1) { ?>
                            	<a class="btn btn-primary" href="<?php echo $obj_general->link($rout, 'mod=details&quotation_id='.encode($quotation_id), '',1);?>">Details</a>
                            <?php } ?>
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
		
		$(".sendmailcls").click(function(){
			var selcurrency = $("#sel_currency").val();
			/*if($("#sel_currency_rate").length){
				var rate = $("#sel_currency_rate").val();
			}else{
				var rate = 0;
			}
			
			var intRegex = /^\d+$/;
			var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;
			&& rate && rate.length == 0 || !intRegex.test(rate) || !floatRegex.test(rate)
			*/
			
			if(selcurrency.length == 0 ){
				$(".note-error").remove();
				$("#sel_currency").after('<span class="note-error required">Please select Currency</span>');
			}else{
				$(".note-error").remove();
				$("#smail").modal('show');
				$("#sscurrency").val(selcurrency);
				//$("#sscurrencyrate").val(rate);
			}
			return false;
		});
		
		$(".pdfcls").click(function(){
			
			var selcurrency = $("#sel_currency").val();
			/*if($("#sel_currency_rate").length){
				var rate = $("#sel_currency_rate").val();
			}else{
				var rate = 0;
			}*/
			
			/*var intRegex = /^\d+$/;
			var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;
			&& rate && (rate.length == 0 || !intRegex.test(rate) || !floatRegex.test(rate) )
			*/
			//alert(selcurrency);return false;
			if(selcurrency.length == 0 ){
				$(".note-error").remove();
				$("#sel_currency").after('<span class="note-error required">Please select Currency</span>');
			}else{
				$(".note-error").remove();
				var url = '<?php echo HTTP_SERVER.'pdf/pdf.php?mod='.encode('productQuotation').'&token='.rawurlencode(encode($data['product_quotation_id'])).'&ext='.md5('php');?>&ssc='+selcurrency;//+'&sscr='+btoa(rate)
				$("#sel_currency").val('');
				$("#sel_currency_rate").val('');
				window.open(url, '_blank');
			}
			return false;
		});
	});
	
	function getCurrencyValue(){
		
		var cur_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getCurrencyValue', '',1);?>");
		
		currency_id = $('#sel_currency').val();
		if(currency_id!=''){
			$('#loading').show();
			$.ajax({
				url : cur_url,
				type :'post',
				data :{currency_id:currency_id},
				success: function(response){
					$('#sel_currency_rate').val(response);
					$('#loading').hide();								
				},
				error:function(){
					set_alert_message('Error!',"alert-warning","fa-warning");          
				}			
			});
		}else{
			$('#sel_currency_rate').val('');
		}
		
	}
	function changeDiscount(id)
	{
		//alert(id);
		var max_discount =$('#max_discount').val();
		var discount = $("#discount_"+id).val();
		var quantity_id = $("#quantity_"+id).val();
	//alert(max_discount);
	if(discount>parseInt(max_discount))
	{
		set_alert_message('Discount Cannot be Greater then Max Discount',"alert-danger","fa-check");
				 window.setTimeout(function(){location.reload()},1500)
		//alert('Discount Cannot be Greater then Max Discount.');
		//$("#discount_error").html('<span class="btn btn-danger btn-xs">Discount Cannot be Greater then Max Discount.</span>');
	}
	else
	{	//alert(quantity_id);
   		var discount_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=update_discount', '',1);?>");
			$.ajax({
				url : discount_url,
				method : 'post',
				data : {discount : discount,quantity_id:quantity_id},
				success: function(response){
		//alert(response);
				set_alert_message('Successfully Updated',"alert-success","fa-check");
				 window.setTimeout(function(){location.reload()},1000)
				},
				error: function(){
					return false;	
				}
				});
	}
	}
</script>	
<!-- Close : validation script -->

<?php } else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>