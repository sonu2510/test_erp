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
//[sonu] (18-4-2017) for get address_book_id wise data
$add_book_id='0';
$add_url='';
if(isset($_GET['address_book_id']))
{
		$add_book_id = decode($_GET['address_book_id']);
		$add_url = '&address_book_id='.$_GET['address_book_id'];
}
//printr($add_book_id );
$bradcums[] = array(
	'text' 	=> $display_name.' List',
	'href' 	=> $obj_general->link($rout, $add_url, '',1),
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
//printr($allow_currency_status);
//Start : edit
$edit = '';
if(isset($_GET['quotation_id']) && !empty($_GET['quotation_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$quotation_id = base64_decode($_GET['quotation_id']);
		$getData = " product_quotation_id,pq.added_by_user_id,pq.added_by_user_type_id,pq.date_added,customer_name, shipment_country_id,pq.multi_product_quotation_id, quotation_type, quantity_type, product_id, product_name, printing_option, printing_effect, height, width, gusset, layer,volume, currency, currency_price,cylinder_price,tool_price, customer_gress_percentage,pq.status,pq.quotation_status,discount";
		$data = $obj_quotation->getQuotation($quotation_id,$getData,$obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);
		$addedByInfo = $obj_quotation->getUser($data[0]['added_by_user_id'],$data[0]['added_by_user_type_id']);
	//printr($data);
		//die;
		$tax_type = $obj_quotation->CheckQuotationTax($data[0]['product_quotation_id']);
	$new_product_quotation_id=$data[0]['product_quotation_id'];
		//printr($data);
		//echo $data[0]['status'];
	}
}
$user_id_emp = $_SESSION['ADMIN_LOGIN_SWISS'];
//Close : edit
//printr($display_status);//die;
if($display_status){
	if(!empty($data))
	{
	//Add quotation
	if(isset($_POST['btn_save'])){
		$quotation_id = $data[0]['multi_product_quotation_id'];
		$obj_quotation->upadteQuotation($quotation_id,$addedByInfo['country_id']);
		$obj_session->data['success'] = 'Your quotation saved successfully!';
		//printr($_POST);
		//die;
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
				$quotation_id =  $data[0]['multi_product_quotation_id'];
				$setCurrencyId = '';
				
				if(isset($_POST['sscurrency']) && !empty($_POST['sscurrency']) ){
					$getSelCurrecnyData = $obj_quotation->getSelCurrencyInfo($_POST['sscurrency']);
					$setCurrencyId = 0;
					if($getSelCurrecnyData){
						$setCurrencyId = $obj_quotation->setQuotationCurrency($quotation_id,$getSelCurrecnyData['currency_code'],1,1,$_POST['sel_currency_sec'],$_POST['currency_rate']);
					}
				}
				
			$obj_quotation->sendQuotationEmail($quotation_id,$gemail,$setCurrencyId,$_POST['sel_currency_sec'],$_POST['currency_rate']);
			if($addedByInfo['country_id']=='155')
			    $obj_quotation->sendQuotationEmailInSpanish($quotation_id,$gemail,$setCurrencyId,$_POST['sel_currency_sec'],$_POST['currency_rate']);
			    
				$obj_session->data['success'] = 'Success : Email send !';
				
			}
			//page_redirect($obj_general->link($rout, '&mod=view&quotation_id='.encode($quotation_id), '',1));
		}else{
			$obj_session->data['warning'] = 'Please enter email address!';
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
	}
	else
	{
		$adminCountryId=$obj_quotation->getUser($user_id,$user_type_id);
	}
	//printr($adminCountryId['multi_quotation_price']);
	//printr($data);
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
      <div class="col-sm-8" style="width:100%">
            <section class="panel">  
                <header class="panel-heading bg-white">
                 <span>Quotation Detail</span>
                 <span class="text-muted m-l-small pull-right">
                    <?php
					
					if($data[0]['status'] == 1){ ?>
                   	<a class="label bg-info pdfcls" href="javascript:void(0);"><i class="fa fa-print"></i> PDF</a>
                    	<a class="label bg-primary sendmailcls" href="javascript:void(0);"><i class="fa fa-envelope"></i> Send Mail</a>
                    <?php }  ?>    
                 </span>
              </header>
              <div class="panel-body">
              	<label class="label bg-white m-l-mini">&nbsp;</label>
                	<span class="text-muted m-l-small pull-right">
                    	Your base currency : <b><?php echo (isset($data[0]['currency']) && $data[0]['currency'] != '')?$data[0]['currency']:'INR'?></b>
                    </span>
                 <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
                      <?php
					  if($allow_currency_status && $data[0]['status'] == 1){
						  if($data[0]['currency'] && $data[0]['currency'] != ''){
							  $notGetCurrency = $data[0]['currency'];
						  }else{
							  $notGetCurrency = 'INR';
						  }
						  $currencys = $obj_quotation->getNewCurrencys();
						  
						  if(isset($data[0]['currency']) && $data[0]['currency'] != '')
						  {
						  	$data[0]['currency']=$data[0]['currency'];
						  }
						  else
						  {
						  	$data[0]['currency']='INR';
						  }
						 // $data[0]['currency']:'INR'
						 
						  //printr($data);
						  if($currencys){
						   ?>
                              <div class="form-group">
								<label class="col-lg-3 control-label" id="currency_label">Select Currency</label>
								<div class="col-lg-9 row">
                                	<div class="col-lg-4">
                                    <input type="hidden" name="curr_name" id="curr_name" value="<?php echo (isset($data[0]['currency']) && $data[0]['currency'] != '')?$data[0]['currency']:'INR'?>" />
                                    <?php //onchange function on currency select box is commented due to currency rate change problem 
                                    //onchange="getCurrencyValue();" //commented on 15-12-2015 by jayashree?>
                                       <?php /*?> <select name="sel_currency" id="sel_currency" class="form-control">
                                            <option value="">Select Currency</option>
                                            <?php
											if($currencys)
											{
												foreach($currencys as $currency){
													echo '<option value="'.encode($currency['currency_id']).'">'.$currency['currency_code'].'</option>';
												} 
											}
											else
											{
												echo '<option value="'.$data[0]['currency'].'" selected="selected">'.$data[0]['currency'].'</option>';
											?>
                                           	 <input type="hidden" name="else_curr_rate" id="else_curr_rate" value="1"/>
                                            <?php }?>
                                        </select><?php */?>
                                        
                                        <?php 
											if($currencys)
											{
										?>
                                                <input type="text" name="sel_currency" id="sel_currency" value="<?php echo $data[0]['currency'];?>" 
                                                class="form-control" readonly="readonly"/>
                                                <input type="text" name="sel_currency_secondary" id="sel_currency_secondary" value="" 
                                                class="form-control" style="display:none"/>
                                                <?php /*?><input type="text" name="sel_currency" id="sel_currency" value="<?php //echo encode();?>" 
                                                class="form-control" ><?php */?>
                                        
                                         <?php 
										 	} 
											else
											{
										?>
                                                <input type="hidden" name="else_curr_rate" id="else_curr_rate" value="1"/>
                                        
                                        <?php 
											}
											
											if($_SESSION['LOGIN_USER_TYPE']=='2')
											{
												$id = $obj_quotation->getEmpAdminId($_SESSION['ADMIN_LOGIN_SWISS']);
												
												$user_id_emp = $id;
											//	printr($user_id_emp);
											}//printr($_SESSION['LOGIN_USER_TYPE']);
										?>
										 
                                        <input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['ADMIN_LOGIN_SWISS'];?>"/>
                                        <input type="hidden" name="user_type_id" id="user_type_id" value="<?php echo $_SESSION['LOGIN_USER_TYPE'];?>"/>
                                    </div>
                                    <div class="col-lg-4">
                                      <?php 
									  if($currencys)
										{
										?>
                                        <input type="text" name="sel_currency_rate"  value="1" readonly="readonly" id="sel_currency_rate" placeholder="Currency Rate" class="form-control validate[condRequired[sel_currency],custom[number]]">
                                    <?php 
									}
									else
									{
									?>
                                     <input type="text" name="sel_currency_rate"  id="sel_currency_rate"  readonly="readonly" placeholder="Currency Rate" class="form-control validate[condRequired[sel_currency],custom[number]]" value="1">
                                    <?php } ?>
                                    </div>
                                    <input type="checkbox" name="mail" id="mail" value="check" class="mail_check"/> Please select Checkbox for Changing currency...!
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
                            <?php echo $data[0]['multi_quotation_number'];

							echo '&nbsp;&nbsp; [ <small class="text-muted">'.dateFormat(4,$data[0]['date_added']).'</small> ]';?> 
                            </label>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Customer Name</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                           <?php //printr($data);
                            $add_url_to=ucwords($data[0]['customer_name']);
								if($data[0]['address_book_id']!='0' && $obj_general->hasPermission('view',178))
									$add_url_to='<a href="'.$obj_general->link('address_book', '&mod=view&address_book_id=' . encode($data[0]['address_book_id']), '', 1).'">'.ucwords($data[0]['customer_name']).'</a>'?>
						   <?php echo $add_url_to;?>
                            <input type="hidden" name="customer_email" id="customer_email" value="<?php echo $addedByInfo['email'];?>" />
                            </label>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Shipment Country</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo $data[0]['country_name'];?>
                            </label>
                            <?php if(isset($tax_type['tax_type']) && $tax_type['tax_type'] !='')
							{
								if($tax_type['tax_type']=='Out Of Gujarat')
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
                  
                       <input type="hidden" id="max_discount" name="max_discount" value="<?php echo isset($adminCountryId['discount'])?$adminCountryId['discount']:''; ?>" class="form-control"/>
                      <?php if($data[0]['quotation_type'] == 1){ ?>
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
					   		//printr($dat);
					 	 $result = $obj_quotation->getQuotationQuantity($dat['product_quotation_id']);
						 if($result!='')
						 $quantityData[] =$result;
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
						$type = 'Pouch';
						if($data[0]['quantity_type']!='')
						    $type= $data[0]['quantity_type'];
						foreach($new_data as $k=>$qty_data)
						{
					?>
                      <div class="form-group">
								<label class="col-lg-3 control-label">Price (By <?php echo $k;?>)</label> 
								<div class="col-lg-10" >
									<section class="panel">
									  <div class="table-responsive" >
										<table class="table table-striped b-t text-small" >
										  <thead>
											  <tr>
                                                <th>Quntity</th>

                                                <?php if($dat['quotation_type'] != 1){ ?>
                                                	<th>Option(Printing Effect )</th>
                                                <?php } ?>
                                                <th>Dimension (Make Pouch)</th>
                                                <th>Layer:Material:Thickness</th>
                                                <?php if($dat['quotation_status'] == 0){
													 if(isset($adminCountryId['discount']) and $adminCountryId['discount']>0.000)
													{ 				
													 if($dat['currency']=='INR')
												echo '<th>Discount</th>';
													} }?>
                                                <th>Client Price / <?php echo $type; ?></th>
                                                <?php 
												if(isset($adminCountryId['multi_quotation_price']) && $adminCountryId['multi_quotation_price']=='1' || ($_SESSION['ADMIN_LOGIN_SWISS']=='1' && $_SESSION['LOGIN_USER_TYPE']=='1' ))
												{
													
												?>		                                                
                                                <th>Your Price / <?php echo $type; ?></th>
                                                <?php } ?>
                                                <th>Total</th>
                                                <?php if($k=='pickup') {
													if($data[0]['shipment_country_id']==111){  ?>
                                                 <th> Price / pouch  With Tax </th>
                                                 <th>Total Price With Tax </th>
                                                 <?php } }?>
                                                 <th>Cylinder Price</th>
                                                 <?php if(isset($adminCountryId['multi_quotation_price']) && $adminCountryId['multi_quotation_price']=='1' || ($_SESSION['ADMIN_LOGIN_SWISS']=='1' && $_SESSION['LOGIN_USER_TYPE']=='1' ))
												{
													
												?>		                                                
                                                <th>Your Cylinder Price</th>
                                                <?php } ?>
                                                 <th>Tool Price</th>
                                                  <?php if($k=='pickup') {
													if($data[0]['shipment_country_id']==111){  ?>
                                                  <th>Cylinder Price  With Tax </th>
                                                 <th>Tool Price With Tax </th> 
                                                  <?php } } ?>
                                                 <?php if($data[0]['status'] != 1){?>
                                                 <th>Action</th>
                                                 <?php }?>
                                              </tr>
										  </thead>
                                          <tbody>
                                          	<?php $i=1;
                                                foreach($qty_data as $skey=>$sdata){
                                                    ?>
                                                    <tr>
                                                        <?php 
                                                        foreach($sdata as $soption){  
													//	printr($soption); 														   ?>
                                                            <tr id="quotation-row-<?php echo $soption['product_quotation_price_id']; ?>">
                                                            <th><?php echo $skey;?></th>
                                                             <td>
															   <?php echo ucwords($soption['text']).' ('.$soption['printing_effect'].')<br>';
															   if($soption['spout_txt'] != 'No Spout')
															   {
															 		echo  $soption['spout_pouch_type'].' Spout';
																}?></td>
                                                                <td><?php echo (int)$soption['width'].'X'.(int)$soption['height'].'X'.
																$soption['gusset']; if($data[0]['product_name']!=10){if($soption['volume']>0) echo ' ('.$soption['volume'].')';}
																else echo ' (Custom)'.' ('.$soption['make'].')'; ?></td>
                                                                 <td>
                                                             <?php // printr($soption['make_id']);  
															 		/*if($soption['make_id']=='6')
																	{
																		$j=1;
																		for($gi=0;$gi<count($soption['materialData']);$gi++)
																		  {		//if($gi=='2')
																		  			
																				  if($soption['materialData'][$gi]['material_id']!='12' && $soption['make_id']=='6')
																				  {
																						if($j=='2')
																						{
																							$quo_price= $obj_quotation->getMaterialThickmessPrice('23','80');
																							$soption['materialData'][$gi]['material_name'] = 'oxo-Biodegradable PE';
																							$soption['materialData'][$gi]['material_thickness'] = '80';
																							$soption['materialData'][$gi]['material_price'] = $quo_price;
																							
																						}
																					
																							 echo '<b>'.($j).' Layer : </b>'.$soption['materialData'][$gi]['material_name'].' : '.(int)$soption['materialData'][$gi]['material_thickness'].'<br>';
																						$j++;
																					}
																					//$gi ='1';
																					
																					//echo $k;
																		 }
																	}
																	else
																	{*/
																		for($gi=0;$gi<count($soption['materialData']);$gi++)
																		  {
																				 // if($soption['materialData'][$gi]['material_id']!='12' && $soption['make_id']=='7')
																					 echo '<b>'.($gi+1).' Layer : </b>'.$soption['materialData'][$gi]['material_name'].' : '.(int)$soption['materialData'][$gi]['material_thickness'].'<br>';
																		 }
																	
																	//}
										//printr($soption);
										
										?>
                                                                 </td>
																 <?php if($dat['quotation_status'] == 0){
																	 if(isset($adminCountryId['discount']) and $adminCountryId['discount']>0.000)
																{ if($dat['currency']=='INR')
																echo '<td><input type="text" id="discount_'.$i.'" name="discount_'.$i.'" value="'.$soption['discount'].'" class="form-control" style="width: 100px;"  onfocusout="changeDiscount('.$i.')">
																<input type="hidden" id="quantity_'.$i.'" name="quantity_'.$i.'" value="'.$soption['product_quotation_quantity_id'].'" class="form-control" style="width: 100px;" ></td>'; } }?>
                                                                <td>
																	<?php   if($soption['discount'] && $soption['discount'] >0.000) {?>
                                                                <b>Total : </b><?php 
																if($soption['size_id']!='0')
																{
																$pretot= $obj_quotation->numberFormate((($soption['totalPrice'] / $skey) / $dat['currency_price']),"3"); 	
																//echo "hi";
																}
																else
																{
																$normal_val= $obj_quotation->numberFormate((($soption['totalPrice'] / $skey) / $dat['currency_price']),"3"); 	
																//$extra_val= $obj_quotation->numberFormate(((($soption['totalPrice']*15/100) / $skey) / $dat['currency_price']),"3"); 									//your client price
																$pretot=$normal_val;
																//+$extra_val;
																//echo "hello";
																}
																echo '<label class="btn btn-info btn-xs"> '.$pretot.'</label>';?><br />
                                                                <b>Discount (<?php echo $soption['discount'];?> %) : </b>
																<?php $predis = $pretot*$soption['discount']/100; 
																echo $obj_quotation->numberFormate($predis,"3");?><br />
                                                                <b>Final Total : </b>
																<?php echo $dat['currency'].' '.$obj_quotation->numberFormate(($pretot-$predis),"3"); ?>
																	<?php }else
																	{
																	 	if($soption['size_id']!='0')
																		{
																			//your client price
																			//printr($soption['totalPrice']);
																			echo '<label class="btn btn-info btn-xs">'.$dat['currency'].' '.
																			$obj_quotation->numberFormate((($soption['totalPrice'] / $skey) / $dat['currency_price']),"3").'</label>';
																															}
                                                                        else
                                                                        {
                                                                        	
																			$normal_p=$obj_quotation->numberFormate((($soption['totalPrice'] / $skey) / $dat['currency_price']),"3");	
																			//$extra_p=$obj_quotation->numberFormate(((($soption['totalPrice'] / $skey) / $dat['currency_price'])));
																			//*15/100),"3");
																			$f_p=$normal_p;
																			//+$extra_p;
																			
																			echo '<label class="btn btn-info btn-xs">'.$dat['currency'].' '.$f_p.'</label>';
                                                                        }
																	
																	}?>
                                                                </td>
																<?php 
																	if(isset($adminCountryId['multi_quotation_price']) && $adminCountryId['multi_quotation_price']=='1' || ($_SESSION['ADMIN_LOGIN_SWISS']=='1' && $_SESSION['LOGIN_USER_TYPE']=='1' ) )
																	{
                                                            
                                                       			 ?>	 
                                                                <td>
                                                                		<?php
																		//printr($soption['totalPrice']/$dat['currency_price']);
																		//echo "customerprice";
																		//printr($soption['customerGressPrice']);
																		//echo "gress price";
																		//printr($soption['gress_price']);
																		//echo "$newprice=(((".$soption['totalPrice']."-".$soption['customerGressPrice']."-".$soption['gress_price'].")/".(float)$dat['currency_price'].") /1)";
																		 $newprice=((($soption['totalPrice']-$soption['customerGressPrice']-$soption['gress_price'])/ (float)$dat['currency_price']) / 1);
																		
																		echo $dat['currency'].' '.$obj_quotation->numberFormate($newprice/$skey,"3");
																	if($soption['gress_per']!=0)
																		echo '<br><b>Pouch Gress % : </b>'.$soption['gress_per'];
																		?>
                                                                </td>
                                                               <?php } ?>
                                                                <td>
                                                                <?php  if($soption['discount'] && $soption['discount'] >0.000) {?>
                                                                <b>Total : </b><?php 
																if($soption['size_id']!='0')
																{
																	$tot= $obj_quotation->numberFormate(($soption['totalPrice'] / $dat['currency_price'] ),"3");
																}
																else
																{
																	$nor= $obj_quotation->numberFormate(($soption['totalPrice'] / $dat['currency_price'] ),"3");
																	//$extra= $obj_quotation->numberFormate((($soption['totalPrice']*15/100) / $dat['currency_price'] ),"3");
																	$tot=$nor;
																	//+$extra;
																}
																echo $tot;?><br />
                                                                <b>Discount (<?php echo $soption['discount'];?> %) : </b>
																<?php $dis = $tot*$soption['discount']/100; 
																echo $obj_quotation->numberFormate($dis,"3");?><br />
                                                                <b>Final Total : </b>
																<?php echo $dat['currency'].' '.($tot-$dis); ?>
																	<?php } else 
																	{
																	if($soption['size_id']=='0')
																	{
																		///$extra_profit=$obj_quotation->numberFormate((($soption['totalPrice']*15/100) / $dat['currency_price'] ),"3");
																		$normal=$obj_quotation->numberFormate(($soption['totalPrice'] /$dat['currency_price'] ),"3");
																		$final_ex_profit=$normal;
																		//$extra_profit+$normal;
																		
																		echo $dat['currency'].' '.$final_ex_profit;			
																	
																	}
																	else
																	{
																		echo $dat['currency'].' '.$obj_quotation->numberFormate(($soption['totalPrice'] /
																		$dat['currency_price'] ),"3");
																	
																	}
																	}?>
                                                                 </td>
                                                                 <?php if($k=='pickup') {
																	  if($data[0]['shipment_country_id']==111) { ?>
                                                  				<td>
																	<?php echo $dat['currency'].' '.$obj_quotation->numberFormate((($soption['totalPriceWithTax'] / $skey) / $dat['currency_price'] ),"3");?>
                                                                 </td>
                                                                 <td>
																	<?php echo $dat['currency'].' '.$obj_quotation->numberFormate(($soption['totalPriceWithTax'] / $dat['currency_price'] ),"3");?>
                                                                 </td>
                                                                 
                                                 <?php }  }?>  <td><?php if($soption['cylinder_price']>0) {echo (int)$soption['cylinder_price'];}else '';?></td>
                                                 <?php if(isset($adminCountryId['multi_quotation_price']) && $adminCountryId['multi_quotation_price']=='1' || ($_SESSION['ADMIN_LOGIN_SWISS']=='1' && $_SESSION['LOGIN_USER_TYPE']=='1' ))
														{
															
														?>		                                                
														<th><?php 
														        if((int)$soption['cylinder_price']==(int)$soption['cyli_gress_price'])
														            echo (int)$soption['cyli_gress_price'];
														        else
														        {
														            echo (int)($soption['cylinder_price'] - (int)$soption['cyli_gress_price']);
														            echo '<br><b>Cylinder Gress % : </b>'.$soption['gress_cyli_per'];
														        }
														    /*echo (int)($soption['cylinder_price'] - (int)$soption['cyli_gress_price']);
														//if($soption['gress_cyli_per']!=0)
														    echo '<br><b>Cylinder Gress % : </b>'.$soption['gress_cyli_per'];*/
														?></th>
														<?php } ?>
                                                                 <td><?php if($soption['tool_price']>0) {echo (int)$soption['tool_price'];}else '';?></td>
                                                                 <?php if($k=='pickup') {
																	  if($data[0]['shipment_country_id']==111) { ?>
                                                  				<td>
																	<?php if($soption['cylinder_price']>0) {echo (int)$soption['cylinder_price_withtax'];}else '';?>
                                                                 </td>
                                                                 <td>
																	<?php if($soption['tool_price']>0) {echo (int)$soption['tool_price_withtax'];}else '';?>
                                                                 </td>
																 <?php }  }?>
                                                                 
                                                               <?php if($data[0]['status'] != 1){?>  <td class="delete-quot">
                                                                <a class="btn btn-danger btn-sm" id="<?php echo $soption['product_quotation_price_id']; ?>" href="javascript:void(0);"><i class="fa fa-trash-o"></i></a></td><?php }?>
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
			<?php	} }
						if($data[0]['status'] == 1){
							?>
                            <div class="form-group">
                                <div class="col-lg-12"><h4><i class="fa fa-tags"></i> Email/PDF History</h4>
                                    <div class="line m-t-large" style="margin-top:-4px;"></div><br/>
                                	<?php
									//$history_data =array();
                                    	$history_data = $obj_quotation->getEmailHistories($data[0]['multi_product_quotation_id']);	  
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
													 if(isset($data[0]['currency']) && $data[0]['currency'] != 'INR'){
                                                     	echo '<th>Currency Rate</th>';
													 }
													 ?>
                                                     <th>Date</th>
                                                      <th></th>
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
                                                               <?php
															 	if($hdata['sec_curr']!='')
																{
																	$curr_code=$hdata['sec_curr'];
																	$curr_rate=$hdata['sec_curr_rate'];
																}
																else
																{
																	$curr_code=$hdata['currency_code'];
																	$curr_rate=$hdata['currency_rate'];
																}
															 ?>
                                                             <td><?php echo $curr_code; ?></td>
                                                             <?php
                                                             if(isset($data[0]['currency']) && $data[0]['currency'] != 'INR'){
                                                                   echo '<td>'.$obj_quotation->numberFormate($curr_rate,"3").'</td>';
                                                             }
                                                             ?>
                                                             <td><?php echo date('F d, Y',strtotime($hdata['date_added'])); ?></td>
                                                             <td><a class="label bg-info" onclick="gen_pdf('<?php echo $curr_code; ?>',<?php echo $obj_quotation->numberFormate($curr_rate,"3");?>)"><i class="fa fa-print"></i> PDF</a></td>
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
                        	<?php if($data[0]['quotation_status'] == 0){ ?>
                                <button type="submit" name="btn_save" id="btn_save" class="btn btn-primary">Save</button>	
                                <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '&mod=add'.$add_url, '',1);?>">Try New</a>			
                            <?php } ?>                            
                            <a class="btn btn-default" href="<?php echo $obj_general->link($rout, 'filter_edit='.$filteredit.$add_url, '',1);?>">Cancel</a>           
                            <?php if($obj_session->data['ADMIN_LOGIN_SWISS']==1 && $obj_session->data['LOGIN_USER_TYPE']==1) { ?>
                            	<a class="btn btn-primary" href="<?php echo $obj_general->link($rout, 'mod=details&quotation_id='.encode($quotation_id).$add_url, '',1);?>">Details</a>
                            <?php }
					?>
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
                <input type="hidden" name="currency_rate" id="currency_rate" value="" />
                <input type="hidden" name="sel_currency_sec" id="sel_currency_sec" value="" />
                <h4 class="modal-title" id="myModalLabel">Send Email</h4>
              </div>
              <div class="modal-body">
                   <div class="form-group">
                        <label class="col-lg-3 control-label">Email</label>
                        <div class="col-lg-8">
                             <input type="text" name="smail" id="cust_email" placeholder="Email" value="" class="form-control validate[required,custom[email]]">
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
<style>
.col-lg-3 {
width: 15%;
}
</style>
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

<script>

    jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#sform").validationEngine();
		
		var user_id=$("#user_id").val();
		var user_type_id=$("#user_type_id").val();
		
		/*if(user_id!='1' && user_type_id!='1')
		{
			$("#sel_currency").hide();
			$("#currency_label").hide();
			$("#sel_currency_rate").hide();
		}*/
		
		$(".sendmailcls").click(function(){			
			var selcurrency = $("#sel_currency").val();
			//alert(selcurrency);
			var else_curr_rate=$("#else_curr_rate").val();
			var sel_currency_rate=$("#sel_currency_rate").val();
			var sel_currency_secondary=$("#sel_currency_secondary").val();	
			var customer_email=$("#customer_email").val();
			//alert(selcurrency);			
			if(selcurrency.length == 0 ){
				$(".note-error").remove();
				$("#sel_currency").after('<span class="note-error required">Please select Currency</span>');
			}else{
				$(".note-error").remove();
				$("#smail").modal('show');
				$("#sscurrency").val(selcurrency);
				$("#currency_rate").val(sel_currency_rate);
				$("#sel_currency_sec").val(sel_currency_secondary);
				$("#else_cuur_rate").val(else_curr_rate);
				$("#cust_email").val(customer_email);
				//$("#cust_email").attr('readonly','readonly');
			}
			return false;
		});
		$('.delete-quot a').click(function(){
			
		var con = confirm("Are you sure you want to delete ?");
		if(con){
			
			var product_quotation_price_id=$(this).attr('id');
			var del_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=deleteProductQuotation', '',1);?>");
			$('#loading').show();
			$.ajax({
				url : del_url,
				type :'post',
				data :{product_quotation_price_id:product_quotation_price_id},
				success: function(response){
					if(response==1){ 
						$('#quotation-row-'+product_quotation_price_id).remove();
						set_alert_message('Successfully Deleted',"alert-success","fa-check");
					}
					$('#loading').hide();		
				location.reload();		
				},
				error:function(){
					set_alert_message('Error!',"alert-warning","fa-warning");          
				}			
			});
		}
	});
		$(".pdfcls").click(function(){			
			var selcurrency = $("#sel_currency").val();
			//console.log(selcurrency);
			if(selcurrency.length == 0 ){
				$(".note-error").remove();
				$("#sel_currency").after('<span class="note-error required">Please select Currency</span>');
			}else{
				$(".note-error").remove();
				var url = '<?php echo HTTP_SERVER.'pdf/pdf.php?mod='.encode('productQuotation').'&token='.rawurlencode(encode($new_product_quotation_id)).'&ext='.md5('php');?>&ssc='+selcurrency;//+'&sscr='+btoa(rate)
				$("#sel_currency").val('');
				$("#sel_currency_rate").val('');
				window.open(url, '_blank');
			}
			return false;
		});
	});
	
	function gen_pdf(curr_code,curr_rate)
	{
		var selcurrency = "<?php echo (isset($data[0]['currency']) && $data[0]['currency'] != '')?$data[0]['currency']:'INR'?>";
		var url = '<?php echo HTTP_SERVER.'pdf/pdf.php?mod='.encode('productQuotation').'&token='.rawurlencode(encode($new_product_quotation_id)).'&ext='.md5('php');?>&ssc='+selcurrency+'&curr_code='+curr_code+'&curr_rate='+curr_rate;
		window.open(url, '_blank');
	}

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
					var else_curr_rate=$("#else_curr_rate").val();
					if(else_curr_rate=='1')
					{
						$('#sel_currency_rate').val(else_curr_rate);
					}
					else
					{
						$('#sel_currency_rate').val(response);
					}
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
		var max_discount =$('#max_discount').val();
		var discount = $("#discount_"+id).val();
		var quantity_id = $("#quantity_"+id).val();
	if(discount>parseInt(max_discount))
	{
		set_alert_message('Discount Cannot be Greater then Max Discount',"alert-danger","fa-check");
				 window.setTimeout(function(){location.reload()},1500)
	}
	else
	{	
   		var discount_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=update_discount', '',1);?>");
			$.ajax({
				url : discount_url,
				method : 'post',
				data : {discount : discount,quantity_id:quantity_id},
				success: function(response){
				set_alert_message('Successfully Updated',"alert-success","fa-check");
				 window.setTimeout(function(){location.reload()},1000)
				},
				error: function(){
					return false;	
				}
				});
	}
	}
	
$(function(){
   $('.mail_check').change(function(){
	 if($(this).is(':checked')) {
	
	 var user_id=<?php echo $user_id_emp;?>;
	

	//  var user_id=$("#user_id").val();
	  
	  var user_type_id=$("#user_type_id").val();

	  	      var url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getcurrency', '',1);?>");
	  $.ajax({
				url : url,
				method : 'post',
				data : {user_id:user_id,user_type_id:user_type_id},
				success: function(response){
						var val = $.parseJSON(response);
						$("#sel_currency").removeAttr('readonly','readonly');
						$("#sel_currency").hide();
						$("#sel_currency_secondary").show();
						$("#sel_currency_secondary").val(val.result);
							
						$("#sel_currency_rate").attr('readonly','readonly');
						$("#sel_currency_rate").val(val.price);
						
							//$("#sel_currency_rate").removeAttr('readonly','readonly');
							//$("#sel_currency_rate").val('');
						
						$("#sel_currency").attr('readonly','readonly');
						$("#sel_currency_secondary").attr('readonly','readonly');
				
				},
				error: function(){
					return false;	
				}
				});
	  
              
      } else {
	  
		 var sel_curr=$("#curr_name").val();
		 $("#sel_currency_secondary").hide();
		 $("#sel_currency").show();
		 $("#sel_currency").removeAttr('readonly','readonly');
		 $("#sel_currency").val(sel_curr);
		 $("#sel_currency_rate").removeAttr('readonly','readonly');
		 $("#sel_currency_rate").val('1');
		 $("#sel_currency").attr('readonly','readonly');
		 $("#sel_currency_rate").attr('readonly','readonly');
		 $("#sel_currency_secondary").val('');
              
      }
   });
});
</script>	
<!-- Close : validation script -->
<?php }else
{?><section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-edit"></i> <?php echo $display_name;?></h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div> 
         <div class="col-sm-8" style="width:100%">
            <section class="panel">  
                <header class="panel-heading bg-white">
                 <span>Quotation Detail</span>
                 <span class="text-muted m-l-small pull-right">
                     
                 </span>
              </header>
              <div class="panel-body">No Record Found !
              </div>
              </section>
              </div>
		</div>
		</section>
		</section><?php }
} else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>