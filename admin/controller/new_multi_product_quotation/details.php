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

//Start : edit
$edit = '';
if(isset($_GET['quotation_id']) && !empty($_GET['quotation_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$quotation_id = base64_decode($_GET['quotation_id']);
		$getData = ' product_quotation_id, pq.added_by_user_id, pq.added_by_user_type_id, customer_name, shipment_country_id, quotation_type, quantity_type, product_id, product_name, printing_option, printing_effect, height, width, gusset, layer, currency, currency_price, cylinder_price, customer_gress_percentage,pq.status,pq.quotation_status,valve_price';
		$data = $obj_quotation->getQuotation($quotation_id,$getData,$obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);
		$countryCourierData = $obj_quotation->getCountryCourier($data[0]['shipment_country_id']);
		$courierName = $obj_quotation->getCountryCourierName($data[0]['shipment_country_id'],$countryCourierData['courier_id']);
		if($data[0]['added_by_user_type_id']!=1 && $data[0]['added_by_user_id']!=1) 
	    	$currency_detail = $obj_quotation->getUserWiseCurrency($data[0]['added_by_user_type_id'],$data[0]['added_by_user_id']);
		//printr($data);//die;
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
                 <span class="text-muted m-l-small pull-right">
                 		<a class="label bg-inverse " onclick="test();" href="javascript:void(0);"><i class="fa fa-print" ></i> Print</a>
                 </span>
                </header>
              
              	<div class="panel-body" id="">
              		<label class="label bg-white m-l-mini">&nbsp;</label>
                	
                    <span class="text-muted m-l-small pull-right">
                    	Your base currency : <b><?php echo (isset($data['currency']) && $data['currency'] != '')?$data['currency']:'INR'?></b>
                    </span>
                     <?php if($user_id =='1' && $user_type_id =='1'){
                                if($data[0]['added_by_user_type_id']!=1 && $data[0]['added_by_user_id']!=1) {?><br />
                     <span class="text-muted m-l-small pull-right">
                    	Product Conversion Rate  : <b><?php echo 'INR : '.$data[0]['currency_price'];?></b>
                    </span>
                    <br />
                     <span class="text-muted m-l-small pull-right">
                    	Cylinder Conversion Rate  : <b><?php echo 'INR : '.$currency_detail['cylinder_rate'];?></b>
                    </span>
                    <br />
                     <span class="text-muted m-l-small pull-right">
                    	Tool Conversion Rate  : <b><?php echo 'INR : '.$currency_detail['tool_rate'];?></b>
                    </span>
                   
                   <?php } }?>
                   
                    <form class="form-horizontal" method="post" name="form"  enctype="multipart/form-data">
                          <div class="form-group" >
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
                                <?php echo $data[0]['country_name'].'&nbsp;&nbsp;&nbsp;'.'<b style="color:#FF6600"> [ '.$courierName['courier_name'].' / '.$courierName['zone'].' ]</b>';?>
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
                          } ?>
                          <div class="form-group" id="print_div">
                          <?php 
                          foreach($data as $dat)
                           {
                             $result = $obj_quotation->getQuotationQuantity($dat['product_quotation_id']);
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
                                              <th>Layer:Material:Thickness:Layer Price</th>
                                             <?php //if($k=='sea') { ?>
                                                 <th>Transport Price</th>
                                              <?php //} 
                                                if($k=='air') { ?>
                                                    <th>Courier Charge:</th>
                                                 <?php } ?>
                                              <?php if($data[0]['product_id']!='6') { ?>
                                                    <th>Price</th>
                                              <?php } ?>
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
															//	printr($dat['currency_price']);
																$type = 'Pouch';
																if($soption['quantity_type']!='')
																    $type= $soption['quantity_type'];
                                                            	$spout_weight = $obj_quotation->getSpoutWeight($soption['spout_txt']);
																$tintie_weight = $obj_quotation->getTintieWeight($soption['zipper_txt']);
																
																if((int)$soption['cylinder_price']==(int)$soption['cyli_gress_price']){
                                        						    $gress_cyli = $soption['cyli_gress_price'];}
                                        						else{
                                        						    $gress_cyli = ($soption['cylinder_price']-$soption['cyli_gress_price']).'<br> GP % : '.$soption['gress_cyli_per'];}
                                                        ?>
                                                                <tr>
                                                                <th ><?php echo $skey;?> </th>
                                                                <td >
                                                                   <?php echo ucwords($soption['text']).' ('.$soption['printing_effect'].')<br>';
																   echo ucwords($soption['gusset_printing_type']).'<br>';
                                                                   if($soption['spout_txt'] != 'No Spout')
                                                                   {
                                                                        echo  $soption['spout_pouch_type'].' Spout';
                                                                    }?>
                                                                    
                                                                    <?php 
                                                                        
                                                                            echo "<br />";
                                                                    
                                                                        $newprice=((($soption['totalPrice']-$soption['customerGressPrice']-$soption['gress_price'])/ (float)$dat['currency_price']) / 1);
                                                                        
                                                                        
                                                                        
																		echo '<b style="color:blue"><br>Gress Selling Price / '.$type.': <br>'.$dat['currency'].' '.$obj_quotation->numberFormate($newprice/$skey,"3").'<b><br> GP % : '.$soption['gress_per'];
																		
																		echo "<br />";
																	
																	 if($soption['discount'] && $soption['discount'] >0.000) {
																	
                                                                            if($soption['size_id']!='0')
        																    {
        																        $pretot= $obj_quotation->numberFormate((($soption['totalPrice'] / $skey) / $dat['currency_price']),"3"); 										
        																    }
        																    else
            																{
                																$normal_val= $obj_quotation->numberFormate((($soption['totalPrice'] / $skey) / $dat['currency_price']),"3"); 	
                																//$extra_val= $obj_quotation->numberFormate(((($soption['totalPrice']*15/100) / $skey) / $dat['currency_price']),"3"); 									
                																$pretot=$normal_val;
                																//+$extra_val;
            																}
        																        echo $pretot;
        																?><br />
                                                                                <b>Discount (<?php echo $soption['discount'];?> %) : </b>
        																        <?php $predis = $pretot*$soption['discount']/100; 
        																                echo $obj_quotation->numberFormate($predis,"3");?><br />
                                                                                <b>Final Total : </b>
        																<?php 
        																    
        																
        																    echo '<b style="color:red"><br>Selling Price / '.$type.': <br>'.$dat['currency'].' '.$obj_quotation->numberFormate(($pretot-$predis),"3").'</b><br><br><b style="color:#CC33FF">Tool Price : '.$soption['tool_price'].'<br><br>Selling Cylinder Price : '.$soption['cylinder_price'].'</b><br><br>Gress Selling Cylinder Price : '.$gress_cyli; ?>
                                                                    <?php  }else
																 
																    {
																	 	if($soption['size_id']!='0')
																		{
																			echo '<b style="color:red"><br>Selling Price / '.$type.' : <br>'.$dat['currency'].' '.
																			$obj_quotation->numberFormate((($soption['totalPrice'] / $skey) / $dat['currency_price']),"3").'</b><br><br><b style="color:#CC33FF">Tool Price :'.$soption['tool_price'].'<br><br>Selling Cylinder Price : '.$soption['cylinder_price'].'</b><br><br>Gress Selling Cylinder Price : '.$gress_cyli;												}
                                                                        else
                                                                        {
                                                                        	
																			$normal_p=$obj_quotation->numberFormate((($soption['totalPrice'] / $skey) / $dat['currency_price']),"3");	
																			//$extra_p=$obj_quotation->numberFormate(((($soption['totalPrice'] / $skey) / $dat['currency_price'])));
																			//*15/100),"3");
																			$f_p=$normal_p;
																			//+$extra_p;
																			
																			echo '<b style="color:red"><br>Selling Price / '.$type.' : <br>'.$dat['currency'].' '.$f_p.'</b><br><br><b style="color:#CC33FF">Tool Price :'.$soption['tool_price'].'<br><br>Selling Cylinder Price : '.$soption['cylinder_price'].'</b><br><br>Gress Selling Cylinder Price : '.$gress_cyli;
                                                                        }
																	
																	} 
																	
																	
																	 if($soption['cust_ink_mul_by']!='0')
																	   echo '<br><br><small class="text-muted">Cust Ink mul By : '.$soption['cust_ink_mul_by'].' </small>';
																	 if($soption['cust_adhesive_mul_by']!='0')
																	    echo '<br><small class="text-muted">Cust Adhesive mul By : '.$soption['cust_adhesive_mul_by'].' </small>';
																	
																	?>   
                                                                    
                                                                    
                                                                   
                                                                    
                                                                    </td>
                                                                    <td><?php echo (int)$soption['width'].'X'.(int)$soption['height'].'X'.(int)$soption['gusset']; if($data[0]['product_id']!=10){if($soption['volume']!='') echo ' <b>('.$soption['volume'].')</b>'; else echo ' (Custom)';}?><?php foreach($soption['quantity_option'] as $squantityKey=>$squantity)
                                                                     { 
                                                        echo '<br/><span style="font-size:13px"><small class="text-muted">'.$squantityKey.' : '.$squantity.'</small></span >';
														
                                                    }echo '<br><span style="font-size:13px"><small class="text-muted">Make Pouch : '.$soption['make'].'</small></span >';
                                                    if($soption['spout_txt'] != 'No Spout')
                                                        echo '<br><span style="font-size:13px"><small class="text-muted">Total Spout Weight : '.number_format($spout_weight['weight']*$skey,3,".","").' KG</small></span >'; 
													if($soption['zipper_txt'][0] == 'T')
                                                        echo '<br><span style="font-size:13px"><small class="text-muted">Total TinTie Weight : '.number_format($tintie_weight['weight']*$skey,3,".","").' KG</small></span >';
														
                                                    if($soption['size_id']=='0')
                                                        echo '<br><span style="font-size:13px"><small class="text-muted">Extra Profit : '.$soption['extra_profit'].'% </small></span >';
														
														echo '<br><span style="font-size:13px"><small class="text-muted">Ink Price : '.$soption['ink_price'].'</span>';
														
														if($soption['ink_multi_by']!='0')
															echo '<br><span style="font-size:13px"><small class="text-muted">Ink Price multiply by : '.$soption['ink_multi_by'].'</span>';
															
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
                                                                 <?php   
																 	/*if($soption['make_id']=='6')
																	{
																 		$j=1;
																		 for($gi=0;$gi<count($soption['materialData']);$gi++)
																		 {		
																				if($soption['materialData'][$gi]['material_id']!='16' && $soption['make_id']=='6')
																				 {
																				 	if($j=='2')
																					{
																						$quo_price= $obj_quotation->getMaterialThickmessPrice('23','80');
																						$soption['materialData'][$gi]['material_name'] = 'oxo-Biodegradable PE';
																						$soption['materialData'][$gi]['material_thickness'] = '80';
																						$soption['materialData'][$gi]['material_price'] = $quo_price;
																						
																					}
																						echo '<b>'.($j).' Layer : </b>'.$soption['materialData'][$gi]['material_name'].' : '.(int)$soption['materialData'][$gi]['material_thickness'].'<br><b  style="color:#51A351">'.'Price : '.$soption['materialData'][$gi]['material_price'].'</b><br><br>';
																						$j++;		
																				}
											//Calculated Layer Price : '.$soption['materialData'][$gi]['layer_wise_price'].'	  
																		  }
																	}
																	else
																	{*/
																		for($gi=0;$gi<count($soption['materialData']);$gi++)
																			 {
																					echo '<b>'.($gi+1).' Layer : </b>'.$soption['materialData'][$gi]['material_name'].' : '.(int)$soption['materialData'][$gi]['material_thickness'].'<br><b  style="color:#51A351">'.'Price : '.$soption['materialData'][$gi]['material_price'].'</b><br><br>';
												//Calculated Layer Price : '.$soption['materialData'][$gi]['layer_wise_price'].'	  
																			  }
																	
																	//}
																?>
                                           	                          </td>
                                                                    <?php //if($k=='sea') { ?>
                                                                    <td>
                                                                        <?php echo '<b style="color:#0066CC">Product Transport Price : '.$soption['transport_price'].'<br><br>Spout Transport Price : '.$soption['spout_transport_price'].'<b><br>';
																		$transport_total=$soption['transport_price']+$soption['spout_transport_price'];
																		
																		 echo '<b style="color:#0066CC"><br> Total : '.$obj_quotation->numberFormate($transport_total,"3").'<b>';?>
                                                                    </td>
                                                                    <?php //} 
                                                                        if($k=='air') {?>
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
													else if($data[0]['product_id']=='6') 
													{
														
														echo $obj_quotation->numberFormate(($soption['courier_charge']/$soption['quantity_option']['Total Kgs']),"3").'</b><br>';
													}
													else
													{
														echo $obj_quotation->numberFormate(($soption['courier_charge']/$soption['quantity_option']['Total Weight With Zipper']),"3").'</b><br>';
													}
													//echo $soption['courier_charge'].'/'.$soption['quantity_option']['Total Weight With Zipper'];
										echo '<br> <b style="color:#0066CC">Basic Courier Price : '.$soption['actual_courier_price'].' </b><br>' ;
										
										 ?>
                                                    
                                                    
                                                    
                                                                    </td>
                                                                    <?php }
                                                                        if($data[0]['product_id']!='6') { ?>
                                                                       
                                                                    <td>
                                                                   <?php 
																   //printr($soption['zipper_option']);
                                                                if(isset($soption['zipper_option']['spout_price'])){
                                                                    echo '<b>Spout  : </b>'.$soption['zipper_option']['spout_price'].'<br>';
                                                                }
                                                                if(isset($soption['zipper_option']['accessorie_price'])){
                                                                    echo '<b>Acc  : </b>'.$soption['zipper_option']['accessorie_price'].'<br>';
																	if($soption['zipper_option']['accessorie_price_corner'] != 0)
																	{
																		echo ' + '.$soption['zipper_option']['accessorie_price_corner'].'<br>';
																	}
                                                                }
                                                                
                                                                if(strchr($soption['text'],"Tin"))
                                                                {
                                                                    echo '<b>Tin Tie  : </b>'.$soption['zipper_option']['zipper_price'].'<br>';
                                                                }
                                                                else
                                                                {
                                                                    echo '<b>Zipper  : </b>'.$soption['zipper_option']['zipper_price'].'<br>';
                                                                }
                                                                    echo '<b>Valve Price :</b>'.$soption['zipper_option']['valve_price'];
                                                                    
                                                                    echo '<br><br><b>Laser Scoring Price:</b>'.$soption['laser_price'];
                                                                   
                                                                   if($data[0]['product_id'] == '55' || $data[0]['product_id'] == '56' || $data[0]['product_id'] == '57')
																		echo '<br><br><b>Handle Price:</b>'.$soption['handle_price'];
                                                                    ?>
                                                                    
                                                                     </td>
                                                                     <?php } ?>
                                                                     <td><?php //printr($soption); 
																	 if($data[0]['product_id']=='6')
																	 	$packing_price='Roll Packing Price : '.$soption['packing_base_price'];
																	else
																		$packing_price='Pouch Packing Price : '.$soption['packing_price'];
																		
																	 echo  '<b style="color:#FF6600">'.$packing_price.'</b><br>';
																	 
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
                          </div>
                        </form>
                        <div class="form-group">
                            <div class="col-lg-9 col-lg-offset-3">
                                <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '&mod=view&quotation_id='.encode($quotation_id).$add_url,'',1);?>">Cancel</a>
                            </div>
                          </div>
                </div>
          
          </section>    
      </div>
    </div>
  </section>
</section>

<!-- Modal -->
<!--<div class="modal fade" id="smail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
</div>-->
<!-- Start : validation script -->

<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script>
    jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#sform").validationEngine();
	});
		
function test() {

	 var html="<html>";
	html+='<head>';
	
	html+='<style>.col-lg-3 {width: 15%;}#client { border-left: 6px solid #0087c3;  float: left;   padding-left: 6px;}h1 {	background:#333;    border-bottom: 1px solid #5d6975;    border-top: 1px solid #5d6975;    color: #FFF;    font-size:  12px;    font-weight: normal;    line-height: 1.4em;    margin: 0 0 20px;    text-align: center;}article, article address, table.meta, table.inventory { margin: 0 0 3em; }table.meta, table.balance { float: right; width: 50%; }table.meta:after, table.balance:after { clear: both; content: ""; display: table; }table.meta th { width: 40%;  font-size:  12px; }table.meta td { width: 60%;   font-size:  12px; }table { font-size:  12px; table-layout: fixed; width: 100%; }table { border-collapse: separate; border-spacing: 1px; font-size:  12px;  }th, td { border-width: 1px;position: relative; text-align: left;  font-size:  12px; }th, td { border-radius: 0em; border-style: solid; font-size:  12px;}th { background: #EEE; border-color: #BBB; font-size:  12px;td { border-color: #DDD; font-size:  font-size:  12px;}.sign_td {	height:100px;}div #express{	margin:30px 40px;}</style>';
	
    html+='<h2><u>Quotation Detail</u></h2>';
    html+="<b>Quotation Number</b>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $data[0]['multi_quotation_number'];?><br><b>Customer Name</b>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ucwords($data[0]['customer_name']);?><br><b>Shipment Country</b>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $data[0]['country_name'] ; ?>&nbsp;&nbsp;&nbsp;<b style='color:#FF6600'> [ <?php echo $courierName['courier_name'];?>  / <?php echo $courierName['zone']; ?> ]</b><br>";
    <?php if($data[0]['customer_gress_percentage'] > 0){ ?>
        html+="<br><b>Customer Gress (%)/b>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $data[0]['customer_gress_percentage'];?><small class='text-muted'>- Below price display without customer gress ( %)</small><br /><small class='text-muted'>- Any email send to client with adding customer gress price.</small>";
    <?php } ?>
    
    html+="<br><b>Product Name</b>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $data[0]['product_name'];?><br><b>Printing Option</b>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $data[0]['printing_option'];?><br><br>";
    
    <?php if($data[0]['quotation_type'] == 1){ ?>
        html+="<b>Quantity In<?php echo ucwords($data[0]['quantity_type']);?></b>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ucwords($data[0]['quantity_type']);?><br><br>";
    <?php } ?>
    html+= $('#print_div').html();

    html+="</html>";	//alert(html);

    var printWin = window.open('','','');

    printWin.document.write(html);

    printWin.document.close();

    printWin.focus();

    printWin.print();

    printWin.close();

}
</script>	
<!-- Close : validation script -->
<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>