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



//[sonu] (21-4-2017) for get address_book_id wise data
$add_book_id='0';
$add_url='';
if(isset($_GET['address_book_id']))
{
		$add_book_id = decode($_GET['address_book_id']);
		$add_url = '&address_book_id='.$_GET['address_book_id'];
}
//end



$bradcums[] = array(
	'text' 	=> $display_name.' List',
	'href' 	=> $obj_general->link($rout, ''.$add_url, '',1),
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
//printr($user_type_id.'====='.$user_id);
$allow_currency_status = $obj_digital_custom_order->allowCurrencyStatus($user_type_id,$user_id);
//Start : edit
$edit = '';
if(isset($_GET['custom_order_id']) && !empty($_GET['custom_order_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$custom_order_id = base64_decode($_GET['custom_order_id']);
		$custom_order_id_pdf = base64_decode($_GET['custom_order_id']);
	//	echo $custom_order_id;
	//	die;
		
	//	printr($obj_session->data['LOGIN_USER_TYPE'].'++++'.$obj_session->data['ADMIN_LOGIN_SWISS']);
	//	printr($custom_order_id);
		$getData = " mcoi.address_book_id,mcoi.date_added,mcoi.multi_product_quotation_id,mco.accept_decline_status,custom_order_id,mco.added_by_user_id,mco.added_by_user_type_id, customer_name, shipment_country_id,mco.multi_custom_order_id, custom_order_type, quantity_type, product_id, product_name, printing_option, printing_effect, height, width, gusset, layer,volume, currency, currency_price, cylinder_price,tool_price, customer_gress_percentage,mco.status,mco.custom_order_status,discount";
		$data = $obj_digital_custom_order->getCustomOrder($custom_order_id,$getData,$obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);
		$tax_type = $obj_digital_custom_order->CheckCustomOrderTax($data[0]['custom_order_id']);
		  $multi_quation_id = $obj_digital_custom_order->getmulti_quation_id($data[0]['multi_product_quotation_id']);
	//	echo $quotation_id;
	
		
	}
}
//printr($data);
//Close : edit
if($display_status){
	if(!empty($data))
	{
	//Add quotation
	if(isset($_POST['btn_save'])){
	

        //printr($_POST);die;
       $obj_digital_custom_order->uploadDieline($_POST,$_FILES['die_line'],1);   
       $obj_digital_custom_order->insertNote($_POST); 
    
    
  	$custom_order_id = $data[0]['multi_custom_order_id'];

	$obj_digital_custom_order->upadteCustomOrder($custom_order_id);//die;
	$obj_session->data['success'] = 'Your Custom Order saved successfully!';
		page_redirect($obj_general->link($rout, '&mod=view&custom_order_id='.encode($custom_order_id).$add_url, '',1));

}

	if(isset($_POST['btn_sendemail'])){
		if(isset($_POST['smail']) && !empty($_POST['smail'])){
			$gemail = trim($_POST['smail']);
			if (!filter_var($gemail, FILTER_VALIDATE_EMAIL)) {
			  	$obj_session->data['warning'] = 'Please enter email address!';
				page_redirect($obj_general->link($rout, '&mod=view&custom_order_id='.encode($custom_order_id).$add_url, '',1));
			}else{
				$custom_order_id =  $data[0]['multi_custom_order_id'];
				$setCurrencyId = '';				
				if(isset($_POST['sscurrency']) && !empty($_POST['sscurrency']) ){
					$getSelCurrecnyData = $obj_digital_custom_order->getSelCurrencyInfo(decode($_POST['sscurrency']));
					$setCurrencyId = 0;
					if($getSelCurrecnyData){
						$setCurrencyId = $obj_digital_custom_order->setCustomOrderCurrency($custom_order_id,$getSelCurrecnyData['currency_code'],$getSelCurrecnyData['price'],1);
					}
				}
			
		    	$obj_digital_custom_order->sendCustomOrderEmail($custom_order_id,$gemail,$setCurrencyId);
         // die;
				$obj_session->data['success'] = 'Success : Email send !';
				page_redirect($obj_general->link($rout, '&mod=view&custom_order_id='.encode($custom_order_id).$add_url, '',1));
			}
		}else{
			$obj_session->data['warning'] = 'Please enter emial address!';
			page_redirect($obj_general->link($rout, '&mod=view&custom_order_id='.encode($custom_order_id).$add_url, '',1));
		}
	}
	if(isset($_GET['filter_edit'])){
		$filteredit = $_GET['filter_edit'];
	}else{
		$filteredit = 0;
	}
	if($_SESSION['ADMIN_LOGIN_USER_TYPE']==4)
	{
		$adminId = $obj_digital_custom_order->getEmpAdminId($_SESSION['ADMIN_LOGIN_SWISS']);
		$adminCountryId = $obj_digital_custom_order->getUser($adminId,$_SESSION['ADMIN_LOGIN_USER_TYPE']);
	}
	else
	{
		$adminCountryId=$obj_digital_custom_order->getUser($user_id,$user_type_id);
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
      <div class="col-sm-8" style="width:100%">
            <section class="panel">  
                <header class="panel-heading bg-white">
                 <span>Custom Order Detail</span>
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
                    						  $currencys = $obj_digital_custom_order->getNewCurrencys();
                    				
                    						  if($currencys){ 
                    							  ?>
                                  <div class="form-group" style="display:none;">
                    								<label class="col-lg-3 control-label">Select Currency</label>
                    								<div class="col-lg-9 row">
                                                    	<div class="col-lg-4">
                                                            <select name="sel_currency" id="sel_currency" onchange="getCurrencyValue();" class="form-control">
                                                                <option value="">Select Currency</option>
                                                                <?php 
                                                                foreach($currencys as $currency){
                                                                    
                                                                   
                                                                  if(isset($data)&& $data[0]['currency']==$currency['currency_code'])
                                                                         echo '<option value="'.encode($currency['currency_id']).'" selected>'.$currency['currency_code'].'</option>';
                                                                    echo '<option value="'.encode($currency['currency_id']).'" selected>'.$currency['currency_code'].'</option>';
                                                                } ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <input type="text" name="sel_currency_rate" readonly id="sel_currency_rate" placeholder="Currency Rate" class="form-control validate[condRequired[sel_currency],custom[number]]">
                                                        </div>
                    								</div>
                    							  </div>
                    							  <?php
                    						  }
          					     } ?>
               <div class="form-group">
                 <div class="col-lg-7" style=" width:50%">
                          <div class="form-group">
                            <label class="col-lg-3 control-label" style="width:25%">Custom Order Number</label>
                            <div class="col-lg-4">
                                <label class="control-label normal-font">
                                <?php echo $data[0]['multi_custom_order_number'];?>
                                </label>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-lg-3 control-label" style="width:25%">Reference No</label>
                            <div class="col-lg-4">
                                <label class="control-label normal-font">
                                <?php echo ucwords($data[0]['reference_no']);?>
                                </label>
                            </div>
                          </div>
                           <div class="form-group">
                            <label class="col-lg-3 control-label" style="width:25%">Quotation No</label>
                            <div class="col-lg-4">
                                <label class="control-label normal-font">
                                <?php echo ucwords($multi_quation_id['digital_quotation_no']);?>
                                </label>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-lg-3 control-label" style="width:25%">Customer Name</label>
                            <div class="col-lg-4">
                                <label class="control-label normal-font">
                                <?php echo ucwords($data[0]['customer_name']);?>
                                </label>
                            </div>
                          </div>
                        <div class="form-group">
                          <label class="col-lg-3 control-label" style="width:25%">Shipment Country</label>
                          <div class="col-lg-4">
                              <label class="control-label normal-font">
                              <?php echo $data[0]['country_name'];?>
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
                        
                        <?php if($data[0]['customer_gress_percentage'] > 0){ ?>
                           <div class="form-group">
                              <label class="col-lg-3 control-label" style="width:25%">Customer Gress (%)</label>
                              <div class="col-lg-4">
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
                        <label class="col-lg-3 control-label" style="width:25%">Printing effact</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                                <?php echo $data[0]['printing_effect'];?>
                            </label>
                        </div>
                      </div>
                  
                           <input type="hidden" id="max_discount" name="max_discount" value="<?php echo isset($adminCountryId['discount'])?$adminCountryId['discount']:''; ?>" class="form-control"/>
                            <?php if($data[0]['custom_order_type'] == 1){ ?>
                                <div class="form-group">
                                    <label class="col-lg-3 control-label" style="width:25%">Quantity In </label>
                                    <div class="col-lg-4">
                                        <label class="control-label normal-font">
                                            <?php echo ucwords($data[0]['quantity_type']);?>
                                        </label>
                                    </div>
                                  </div>
                            <?php   }	  ?>
               </div>
               <div class="col-lg-5" style=" width:50%">
                                           
                      <div class="form-group">
                        <label class="col-lg-3 control-label" style="width:25%">Company Name</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php 
                  							$add_url_to=ucwords($data[0]['company_name']);
                  								if($data[0]['address_book_id']!='0' && $obj_general->hasPermission('view',178))
                  									$add_url_to='<a href="'.$obj_general->link('address_book', '&mod=view&address_book_id=' . encode($data[0]['address_book_id']), '', 1).'">'.ucwords($data[0]['company_name']).'</a>'?>
                  						   <?php echo $add_url_to;?>
                            
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
            </div>
					<?php
					   foreach($data as $dat)
					   {
					//	printr($data);
					  
					   	 $multi_custom_order_id=$dat['multi_custom_order_id'];
					  	// printr($multi_custom_order_id);
						 $custom_order_id=$dat['custom_order_id'];
					//	 echo $custom_order_id;
						 $country_id = $dat['shipment_country_id'];
					//	 printr($country_id);
					 	 $result = $obj_digital_custom_order->getCustomOrderQuantity($dat['custom_order_id']);
				
						 if($result!='')
						 	$quantityData[] =$result;
					   } 
					   //printr($multi_custom_order_id);
					  //printr($quantityData);
					   if(!empty($quantityData))
					   {
						foreach($quantityData as $k=>$qty_data)
						{
							//printr($quantityData);
							foreach($qty_data as $tag=>$qty)
							{	
								foreach($qty as $q=>$arr)
								{
									$new_data[$tag][$q][]=$arr[0];
									//printr($tag);
								}
							}	
						}
						
					
						
						//printr($new_data);
					//	printr($dat);
						foreach($new_data as $k=>$qty_data)
						{
							if($dat['shipment_country_id'] == 42)
							{
								if($k=='air')
									$rush = 'Rush order';
								else
								    $rush = 'Normal order';
							}
							else
								$rush = $k;
					?>
                    
                <div class="form-group">
                <label class="col-lg-3 control-label">Price (By <?php echo $rush;?>)</label> 
                <div class="col-lg-8" >
									<section class="panel">
									  <div class="table-responsive">
										<table class="table table-striped b-t text-small">
										  <thead>
											  <tr>
                                                <th>Add details</th>
                                                <th>Product Name</th>
                                                <th>Quantity</th>
                                             
                                                <th>Dimension (Make Pouch)</th>                                              
                                                <th>Price / pouch</th>
                                                <th>Total</th>
                                                <?php if($k=='pickup') {
													if($data[0]['shipment_country_id']==111){  ?>
                                                 <th> Price / pouch  With Tax </th>
                                                 <th>Total Price With Tax </th>
                                                 <?php } }?>
                                                 <th>Plate Price</th>
                                          
                                                 <?php if($data[0]['status'] != 1){?>
                                                 <th>Action</th>
                                                 <?php }?>
                                                 <th></th>
                                              </tr>
										  </thead>
                                          <tbody>
                                          	<?php $i=1;
                                                foreach($qty_data as $skey=>$sdata){
                                                 //echo $n;   ?>
                                                    <tr>
                                                        <?php $no=1;
                                                        foreach($sdata as $soption){        
												            $total_color = explode('==',$soption['total_color']);        
												            $req ='';    
												            if($no==1)
												                $req ='required';
														   ?>
                                                            <tr id="quotation-row-<?php echo $soption['custom_order_price_id']; ?>">
                                                            <th>    
                                                                    <div class="form-group">
                                                                        <label class="col-lg-4 control-label"><?php echo $no.' )';?> Product Code</label>
                                                                        <div class="col-lg-8">
                                                                            <?php $code = $obj_digital_custom_order->getproductCode($soption['product_code_id']);
                                                                                  echo '<span style="color:red;">'.$code['product_code'].'</span>';    
                                                                                ?>    
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label class="col-lg-4 control-label">Special Instruction Or Note <br /><small class="text-muted">(this will be displayed in order)</small></label>
                                                                        <div class="col-lg-8">
                                                                             <textarea name="product_special_instruction[<?php echo $soption['custom_order_id']; ?>]" id="product_special_instruction" class="form-control"><?php echo $soption['product_instruction'];?></textarea>
                                                                                 <input type="hidden" name="custom_order_id" value="<?php echo $soption['custom_order_id']; ?>">
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="form-group">
                                                                    <label class="col-lg-4 control-label">Art Work Or DieLine  <br/><small class="text-muted">(Only .pdf & .jpg format)</small></label>
                                                                    <div class="col-lg-8">                    
                                                                        <div class="media-body">
                                                                                <input type="file" multiple="multiple" name="die_line[<?php echo $soption['custom_order_price_id'];?>][]" id="die-line"  title="<i class='fa fa-folder-open-o'></i> Browse" class="btn btn-sm btn-info m-b-small" <?php echo $req;?> >   <!-- == removw  for temporary-->
                                                                        </div>                    
                                                                        <div class="file-preview-die" style="display:none">
                                                                           <div class="file-preview-thumbnails-die">                            
                                                                           </div>
                                                                           <div class="clearfix"></div>
                                                                        </div>                    
                                                                        <div id="append-dieline"></div>                
                                                                    </div>
                                                                    <br>   <label class="col-lg-4 control-label"> </label>
                                                                       <a class="btn btn-primary btn-sm" id="<?php echo $soption['custom_order_price_id']; ?>" href="<?php echo $obj_general->link($rout, 'mod=view_custom_order&order_id='.encode($soption['custom_order_price_id']).'&custom_order_id='.$_GET['custom_order_id'].'&filter_edit='.$filteredit, '',1);?>" >View Detail</a>
                                                                  </div>
                                                         </th>
                                                            <th><?php echo $soption['product_name'];?><br> <br><?php echo ucwords($soption['text']).' ('.$soption['printing_effect'].')<br><br> <b style="color:red;">Quoted Printing in : </b>'.$total_color[1].' Colors';?></th>
                                                            <th><?php echo $skey;?> </th>
                                                          
                                                                <td><?php echo (int)$soption['width'].'X'.(int)$soption['height'].'X'. $soption['gusset']; if($data[0]['product_name']!=10){if($soption['volume']>0) echo ' ('.$soption['volume'].')';}
                                        																else echo ' (Custom)'.' ('.$soption['make'].')'; ?></td>
                                                                <td>
            												                              	<?php   echo $dat['currency'].' '.$soption['pouch_price'];?>
                                                               </td> <td>
                                                                    <?php   echo $dat['currency'].' '.$soption['totalPrice'];?>
                                                               </td>
                                                                         
                                                              
                                                           <td><?php if($soption['color_plate_price']>0) {echo (int)$soption['color_plate_price'];}else '';	 ?></td>
                                                               
                                                                 <?php if($data[0]['status'] != 1){?>  <td class="delete-quot">
                                                              <a class="btn btn-danger btn-sm" id="<?php echo $soption['custom_order_price_id']; ?>" href="javascript:void(0);"><i class="fa fa-trash-o"></i></a>  
                                                                </td><?php }?>
                                                                
                                                                <td>  </td>
                                                                <td>
                                                                 <?php  //[sonu] (21-5-2019) 
                                                                 $menu_id = $obj_template->getMenuPermission(ORDER_ACCEPT_ID,$obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE']);																
                                                                      if($menu_id OR $obj_session->data['LOGIN_USER_TYPE']==1  && $data[0]['custom_order_status'] == '1')
                                    																  {
                                    																	  
                                    																			if($soption['accept_decline_status']=='0')
                                    																			{ ?>
                                    																				<a class="btn btn-success" onclick="approve(<?php echo $multi_custom_order_id; ?>,<?php echo $soption['custom_order_id']; ?>)" id="approve" href="javascript:void(0);" >Accept</a><br /><br />
                                    																	  <?php }
                                    																			if($soption['accept_decline_status']!='2' && $soption['accept_decline_status']!='3') 
                                    																			{?>
                                    																				<a class="btn btn-danger" onclick="decline(<?php echo $multi_custom_order_id; ?>,<?php echo $soption['custom_order_id']; ?>)" id="decline" href="javascript:void(0);" >Decline</a>                   
                                                                      <?php 
                                                                      			} 
                                    																		}
                                    																?>
                                                                </td>
                                                                </tr>
                                                            <?php $no++;
                                                        }
                                                        ?>
                                                    </tr>
                                                    <?php $i++;
                                                }//$n++;
                                             ?>
                                          </tbody>
              										</table>
              									  </div>
              									</section> 
              								</div>
              							  </div>
			<?php	} }
						//if($data[0]['status'] == 1){
							?>
                           <?php /*?> <div class="form-group">
                                <div class="col-lg-12"><h4><i class="fa fa-tags"></i> Email/PDF History</h4>
                                    <div class="line m-t-large" style="margin-top:-4px;"></div><br/>
                                	<?php
									//$history_data =array();
                                    	$history_data = $obj_digital_custom_order->getEmailHistories($data[0]['multi_custom_order_id']);	  
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
                                                             if(isset($data[0]['currency']) && $data[0]['currency'] != 'INR'){
                                                                   echo '<td>'.$obj_digital_custom_order->numberFormate($hdata['currency_rate'],"3").'</td>';
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
                        } ?><?php */?>
                      
                      <div class="form-group">
                        <div class="col-lg-9 col-lg-offset-3">
                        	<?php if($data[0]['custom_order_status'] == 0){ ?>
                                <button type="submit" name="btn_save" id="btn_save" class="btn btn-primary">Save</button>	
                                <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '&mod=add'.$add_url, '',1);?>">Try New</a>			
                            <?php } ?>                         
                              
                           
                            <?php  
								//end [kinjal]
								
									/*?><?php if($obj_session->data['ADMIN_LOGIN_SWISS']==1 && $obj_session->data['LOGIN_USER_TYPE']==1) { ?>
                            	<a class="btn btn-primary" href="<?php echo $obj_general->link($rout, 'mod=details&custom_order_id='.encode($custom_order_id), '',1);?>">Details</a>
                            <?php }
					?><?php */?>
                    			
                                <a class="btn btn-default" href="<?php echo $obj_general->link($rout, 'filter_edit='.$filteredit.$add_url, '',1);?>">Cancel</a>
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

<div class="modal fade" id="detail_div" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="dform" id="dform" style="margin-bottom:0px;" enctype="multipart/form-data">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                 <h4 class="modal-title" id="myModalLabel">Add Detail</h4>
              </div>
              <div class="modal-body">
                 <div class="form-group">
                    <label class="col-lg-4 control-label">Note<br /><small class="text-muted">(for internal purpose)</small></label>
                    <div class="col-lg-8">
                         <textarea name="product_note" id="product_note" class="form-control"></textarea>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-lg-4 control-label">Special Instruction <br /><small class="text-muted">(this will be displayed in order)</small></label>
                    <div class="col-lg-8">
                         <textarea name="product_special_instruction" id="product_special_instruction" class="form-control"></textarea>
                    </div>
                </div>
                
                <div class="form-group">
                <label class="col-lg-4 control-label">DieLine <br/><small class="text-muted">(Only .pdf & .jpg format)</small></label>
                <div class="col-lg-8">                    
                    <div class="media-body">
                            <input type="file" multiple="multiple" name="die_line[]" id="die-line"  title="<i class='fa fa-folder-open-o'></i> Browse" class="btn btn-sm btn-info m-b-small">
                    </div>                    
                    <div class="file-preview-die" style="display:none">
                       <div class="file-preview-thumbnails-die">                            
                       </div>
                       <div class="clearfix"></div>
                    </div>                    
                    <div id="append-dieline"></div>                
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-4 control-label">Art Work</label>
                <div class="col-lg-8">                    
                    <div class="media-body">
                            <input type="file" multiple="multiple" name="art_image[]" id="art-image" title="<i class='fa fa-folder-open-o'></i> Browse" class="btn btn-sm btn-info m-b-small">
                    </div>                    
                    <div class="file-preview" style="display:none">
                        <div class="file-preview-thumbnails">                      
                        </div>
                        <div class="clearfix"></div>
                        <div id="append-artwork"></div>  
                    </div>
                </div>
              </div>
              </div>
              <input type="hidden" name="multi_custom_order_id" id="multi_custom_order_id"  />
              <input type="hidden" name="custom_order_id" id="custom_order_id" />
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="button" name="btn_adddetail" id="btn_adddetail" class="btn btn-primary btn-sm">Add</button>
              </div>
   		</form>   
    </div>
  </div>
</div>

<!-- [kinjal] (10-9-2016)-->

<!-- Modal For Decline -->
<div class="modal fade" id="decline_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="sform" id="sform" style="margin-bottom:0px;">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <input type="hidden" name="multi_custom_order_id_dec" id="multi_custom_order_id_dec" value="" />
                <input type="hidden" name="custom_order_id_dec" id="custom_order_id_dec" value="" />
                 <input type="hidden" name="admin" id="admin" value="<?php echo ADMIN_EMAIL;?>" />
                <h4 class="modal-title" id="myModalLabel">Review For Decline Order</h4>
              </div>
              <div class="modal-body">
                   <div class="form-group">
                        <label class="col-lg-3 control-label">Review</label>
                        <div class="col-lg-8">
                             <textarea name="review" id="review" placeholder="Review" value="" class="form-control validate[required]"></textarea>
                        </div>
                     </div> 
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="button" onclick="updatecustomorderstatus('decline',2)" name="btn_decline" class="btn btn-danger">Decline</button>
              </div>
   		</form>   
    </div>
  </div>
</div>

<!-- Model For Accept-->
<div class="modal fade" id="date" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="sform" id="sform" style="margin-bottom:0px;">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <input type="hidden" name="multi_custom_order_id_app" id="multi_custom_order_id_app" value="" />
                 <input type="hidden" name="custom_order_id_app" id="custom_order_id_app" value="" />
                 <input type="hidden" name="admin" id="admin" value="<?php echo ADMIN_EMAIL;?>" />
                <h4 class="modal-title" id="myModalLabel">Expected Delivery Date For Dispatch Order</h4>
              </div>
              <div class="modal-body">
                   <div class="form-group">
                        <label class="col-lg-4 control-label">Expected Delivery Date</label>
                        <div class="col-lg-7">
                             <input type="text" name="date" id="due_date" value="<?php echo date("Y-m-d");?>"  data-format="YYYY-MM-DD"  data-template="D MMM YYYY" 
                         placeholder="Delivery Date"  class="combodate form-control"/>
                        </div>
                     </div> 
                     
                     <div class="form-group">
                        <label class="col-lg-4 control-label">Review</label>
                        <div class="col-lg-7">
                             <textarea name="reason" id="reason" placeholder="Review" value="" class="form-control validate[required]"></textarea>
                        </div>
                     </div> 
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="button" onclick="updatecustomorderstatus('accept',1)" name="btn_accept" class="btn btn-success">Save</button>
              </div>
   		</form>   
    </div>
  </div>
</div>

<!-- [kinjal] end-->



<style>
.col-lg-3 {
width: 15%;
}
</style>
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

<script type="text/javascript">
	function add_details(multi_custom_order_id,custom_order_id)
		{	
			$("#detail_div").modal('show');
			$("#multi_custom_order_id").val(multi_custom_order_id);
			$("#custom_order_id").val(custom_order_id);
			var get_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getNote', '',1);?>");
			$.ajax({
				url : get_url,
				type :'post',
				data :{custom_order_id:custom_order_id},
				success: function(response){
				//alert(response);
					var Content = JSON.parse(response);
					if(Content.instr != '' || Content.note!='')
					{
						$("#product_note").val(Content.note);
						$("#product_special_instruction").val(Content.instr);
					}
				},
				error:function(){
					set_alert_message('Error!',"alert-warning","fa-warning");          
				}			
			});
		}
		
 jQuery(document).ready(function(){
        getCurrencyValue();
 });
    jQuery(document).ready(function(){
     
        // binds form submission and fields to the validation engine
        jQuery("#sform").validationEngine();
		
		$(".sendmailcls").click(function(){			
			var selcurrency = $("#sel_currency").val();			
			if(selcurrency.length == 0 ){
				$(".note-error").remove();
				$("#sel_currency").after('<span class="note-error required">Please select Currency</span>');
			}else{
				$(".note-error").remove();
				$("#smail").modal('show');
				$("#sscurrency").val(selcurrency);
			}
			return false;
		});

		/*$(".add_details").click(function(){			
			$("#detail_div").modal('show');
		});*/
		
		$('.delete-quot a').click(function(){
			
		var con = confirm("Are you sure you want to delete ?");
		if(con){
			
			var custom_order_price_id=$(this).attr('id');
			var del_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=deleteProductCustomOrder', '',1);?>");
			$('#loading').show();
			$.ajax({
				url : del_url,
				type :'post',
				data :{custom_order_price_id:custom_order_price_id},
				success: function(response){//alert(response);
					if(response==1){ 
						$('#quotation-row-'+custom_order_price_id).remove();
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
			if(selcurrency.length == 0 ){
				$(".note-error").remove();
				$("#sel_currency").after('<span class="note-error required">Please select Currency</span>');
			}else{
				$(".note-error").remove();
				var currency=$("#sel_currency option:selected").text();
			    var currency_rate=$("#sel_currency_rate").val();
		  
				var url = '<?php echo HTTP_SERVER.'pdf/custom_orderpdf.php?mod='.encode('custom_order').'&token='.rawurlencode(encode($custom_order_id_pdf)).'&ext='.md5('php');?>&currency='+currency+'&currency_rate='+currency_rate;
					
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
	{ //alert(id);
		var max_discount =$('#max_discount').val();
		var discount = $("#discount_"+id).val();
		var quantity_id = $("#quantity_"+id).val();
	if(discount>parseInt(max_discount))
	{
		set_alert_message('Discount Cannot be Greater then Max Discount',"alert-danger","fa-check");
				 window.setTimeout(function(){location.reload()},1500)
	}
	else
	{	//alert(quantity_id);
   		var discount_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=update_discount', '',1);?>");
			$.ajax({
				url : discount_url,
				method : 'post',
				data : {discount : discount,quantity_id:quantity_id},
				success: function(response){//alert(response);
				set_alert_message('Successfully Updated',"alert-success","fa-check");
				 window.setTimeout(function(){location.reload()},1000)
				},
				error: function(){
					return false;	
				}
				});
	}
	}
	
var count=0;
$('.media-body').on('change','#art-image',function(){
	count += 1;
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajaximage', '',1);?>");//alert(url);
	$('#loading').show();
	var img_html = '';
	var file_data = $("#art-image").prop("files")[0];          // Getting the properties of file from file field
	var form_data = new FormData();                            // Creating object of FormData class
	form_data.append("file", file_data);          			// Appending parameter named file with properties of file_field to form_data
	form_data.append("product_id", $('#product').val());      // Adding extra parameters to form_data
	form_data.append("image_id",count);
	$.ajax({
		url: url,
		dataType: 'json',
		cache: false,
		contentType: false,
		processData: false,
		data: form_data,                         // Setting the data attribute of ajax with file_data
		type: 'post',
		success : function(response){	
			console.log(response);
			if(typeof response.ext != 'undefined'){
		
			if(response.ext == 'img'){
				img_html += '<div id="preview-'+count+'" class="file-preview-frame">';
                  img_html +='<img class="file-preview-image" src="'+response.name+'">';
                  img_html += '<a class="iremove" href="javascript:void(0);" onClick="removeImage('+count+')">Remove</a>';      
                img_html += '</div>';
				$('.file-preview').show();
				$('.file-preview-thumbnails').append(img_html);
				$('#loading').remove();
			}
			else{
				img_html = '<div id="preview-'+count+'" class="file-preview-frame">';
				  img_html +='<img class="file-preview-image" src="<?php echo HTTP_SERVER .'images/pdf_image.jpg'; ?>">';
				  img_html += '<a class="iremove" href="javascript:void(0);" onClick="removeImage('+count+')">Remove</a>';      
				  img_html +='<div style="margin-top:8px;width:135px;">';
					if((response.name).length>15){
						img_html +='<a href="javascript:void(0)" style="text-align:left;">'+(response.name).substring(0,15)+'..'+'</a>';
					}else{
						img_html +='<a href="javascript:void(0)" style="text-align:left;">'+(response.name)+'</a>';
					}
					  img_html +='</div>';
					img_html += '</div>';
					$('.file-preview').show();
					$('.file-preview-thumbnails').append(img_html);
					$('#loading').remove();
				}
			}else{
				$('#loading').remove();
			}
		}
   });
});

/*var die_count = 0;

$('.media-body').on('change','#die-line',function(){
	die_count += 1;
	var url = getUrl("<?php //echo $obj_general->ajaxLink($rout, '&mod=uploadDieLine', '',1);?>");
	var die_html = '';
	var file_data = $("#die-line").prop("files")[0];          // Getting the properties of file from file field
	var form_data = new FormData();                            // Creating object of FormData class
	form_data.append("file", file_data)              			// Appending parameter named file with properties of file_field to form_data
	form_data.append("product_id", $('#product').val())        // Adding extra parameters to form_data
	form_data.append("die_id",die_count)
	$.ajax({
		url: url,
		dataType: 'json',
		cache: false,
		contentType: false,
		processData: false,
		data: form_data,                         // Setting the data attribute of ajax with file_data
		type: 'post',
		success : function(response){
			console.log(response);
			if(typeof response.ext != 'undefined'){
				if(response.ext == 'img'){
					die_html = '<div id="die-preview-'+die_count+'" class="file-preview-frame">';
					  die_html +='<img class="file-preview-image" src="'+response.name+'">';
					  die_html += '<a class="iremove" href="javascript:void(0);" onClick="removefile('+die_count+')">Remove</a>';      
					die_html += '</div>';
					$('.file-preview-die').show();
					$('.file-preview-thumbnails-die').append(die_html);
					$('#loading').remove();
				}else{
					die_html = '<div id="die-preview-'+die_count+'" class="file-preview-frame">';
					  die_html +='<img class="file-preview-image" src="<?php //echo HTTP_SERVER .'images/pdf_image.jpg'; ?>">';
					  die_html += '<a class="iremove" href="javascript:void(0);" onClick="removefile('+die_count+')">Remove</a>';      
					  die_html +='<div style="margin-top:8px;width:135px;">';
					    if((response.name).length>15){
					  	 	die_html +='<a href="javascript:void(0)" style="text-align:left;">'+(response.name).substring(0,15)+'..'+'</a>';
						}else{
							die_html +='<a href="javascript:void(0)" style="text-align:left;">'+(response.name)+'</a>';
						}
					  die_html +='</div>';
					die_html += '</div>';
					$('.file-preview-die').show();
					$('.file-preview-thumbnails-die').append(die_html);
					$('#loading').remove();
				}
			}else{
				$('#loading').remove();
				set_alert_message('Only .pdf And .jpg Formate Allow','alert-danger','fa fa-warning');
			}
		}
   });
});
*/
function removefile(count){
	//alert(count);
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=removeFile', '',1);?>");
	$('#loading').show();
	$.ajax({
		url: url,
		data: {die_id : count},       
		type: 'post',
		success : function(){
		//	alert(count);
		//	alert(re);
			$('#loading').remove();
			$('#die-preview-'+count).remove();
			if($('.file-preview-die .file-preview-thumbnails-die').children().size()==0){
				$('.file-preview-die').css('display','none');	
			}
		}
	});
}
function removeImage(count){
	//alert(count);
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=removeImage', '',1);?>");
	$('#loading').show();
	$.ajax({
		url: url,
		data: {art_id : count},       
		type: 'post',
		success : function(){
		//	alert(count);
		//	alert(re);
			$('#loading').remove();
			$('#preview-'+count).remove();
			if($('.file-preview .file-preview-thumbnails').children().size()==0){
				$('.file-preview').css('display','none');	
			}
		}
	});
}

$('#btn_adddetail').click(function(){
	var url=getUrl("<?php echo $obj_general->ajaxLink($rout,'&mod=ajax&fun=Adddetail','',1);?>");
	var formData=$('#dform').serialize();
	 //alert(fd);
		$.ajax({
			url:url,
			data:{formData:formData},
			type:'post',
			success:function(response){
			console.log(response);
				$("#detail_div").modal('hide');
			}
		
		});
});

//[kinjal] Started on (10-9-29016)

function approve(multi_custom_order_id,custom_order_id)
{	
	//console.log(multi_custom_order_id+'==='+custom_order_id);
	$(".note-error").remove();
	$("#date").modal('show');
	$("#multi_custom_order_id_app").val(multi_custom_order_id);
	$("#custom_order_id_app").val(custom_order_id);
}	
function decline(multi_custom_order_id,custom_order_id)
{
	//console.log(multi_custom_order_id+'==='+custom_order_id);
	$(".note-error").remove();
	$("#decline_model").modal('show');
	$("#multi_custom_order_id_dec").val(multi_custom_order_id);
	$("#custom_order_id_dec").val(custom_order_id);
}
function updatecustomorderstatus(id,status)
{
	//console.log(id+'----'+status);
	if(status == 2)
	{
		if($("#review").val()=='')
		{
			$(".note-error").remove();
			alert('Please Give Review');
			return false;
		}
		$("#decline_model").modal('hide');
		var review = $("#review").val();
		$("#review").val('');
		var txt = '_dec';
	}
	
	if(status == 1)
	{
		if($("#due_date").val()=='')
		{
			$(".note-error").remove();
			alert('Please Select Date');
			return false;
		}
		$("#date").modal('hide');
		var due_date = $("#due_date").val();
		var review = $("#reason").val();
		var txt = '_app';
	}
	//alert("#multi_custom_order_id"+txt);
	var postArray = {};
	postArray['multi_custom_order_id'] = $("#multi_custom_order_id"+txt).val();
	postArray['custom_order_id'] = $("#custom_order_id"+txt).val();
	postArray['review'] =review;
	postArray['due_date']=due_date;
	postArray['status'] =status;
	
	var d = new Date();
	var curr_date = d.getDate();
	var curr_month = d.getMonth();
	curr_month++;
	var curr_year = d.getFullYear();
	var formattedDate = curr_date + "-" + curr_month + "-" + curr_year;
	postArray['currdate'] = formattedDate;
	
	var adminEmail = $("#admin").val();
	
	var order_status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=updateAccDeclinestatus', '',1);?>");
	$.ajax({
		url : order_status_url,
		method : 'post',
		data : {postArray : postArray,adminEmail:adminEmail},
		success: function(response){
			
			//console.log(response);
				if(status == '1')
					set_alert_message('Successfully Accepted',"alert-success","fa-check");
				else
					set_alert_message('Successfully Declined',"alert-success","fa-check");
					
				$('#loading').hide();
				window.setTimeout(function(){location.reload()},1000)
			},
			error: function(){
				return false;	
			}
		});
}
//[kinjal] end
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
                 <span>Custom Order Detail</span>
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