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
		$getData = " product_quotation_id,pq.added_by_user_id,pq.added_by_user_type_id, customer_name, shipment_country_id,pq.multi_product_quotation_id, quotation_type, quantity_type, product_id, product_name, printing_option, printing_effect, height, width, gusset, layer,volume, currency, currency_price, cylinder_price,tool_price, customer_gress_percentage,pq.status,pq.quotation_status,discount";
		$data = $obj_quotation->getQuotation($quotation_id,$getData,$obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);
		$tax_type = $obj_quotation->CheckQuotationTax($quotation_id);
		//printr($data);
		
	}
}
//printr($data);die;
//Close : edit
if($display_status){
	if(!empty($data))
	{
	//Add quotation
	if(isset($_POST['btn_save'])){
		$quotation_id = $data[0]['multi_product_quotation_id'];
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
				$quotation_id =  $data[0]['multi_product_quotation_id'];
				$setCurrencyId = '';
				
				if(isset($_POST['sscurrency']) && !empty($_POST['sscurrency']) ){
					$getSelCurrecnyData = $obj_quotation->getSelCurrencyInfo(decode($_POST['sscurrency']));
					$setCurrencyId = 0;
					if($getSelCurrecnyData){
						$setCurrencyId = $obj_quotation->setQuotationCurrency($quotation_id,$getSelCurrecnyData['currency_code'],$getSelCurrecnyData['price'],1);
					}
				}
			
			$obj_quotation->sendQuotationEmail($quotation_id,$gemail,$setCurrencyId);
				$obj_session->data['success'] = 'Success : Email send !';
				page_redirect($obj_general->link($rout, '&mod=view&quotation_id='.encode($quotation_id), '',1));
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
      <div class="col-sm-8" style="width:100%">
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
                        <div class="col-lg-4">
                            <span class="text-muted m-l-small pull-right">  <a class="label bg-danger" href="javascript:void(0);" onclick="excel(<?php echo base64_decode($_GET['quotation_id']);?>)"><i class="fa fa-print"></i> Excel </a></span>
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
					 	 $result = $obj_quotation->getQuotationQuantity($dat['product_quotation_id']);
						 //printr($dat['product_quotation_id']);
						 if($result!='')
						 $quantityData[] =$result;
					   } 
					 //  printr($quantityData);
					   //printr($data);
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
						//printr($new_data);die;
						foreach($new_data as $k=>$qty_data)
						{// printr($qty_data);die;
						
						

					?>
                      <div class="form-group">
								<label class="col-lg-3 control-label">Price (By <?php echo $k;?>)</label> 
								<div class="col-lg-9">
									<section class="panel">
									  <div class="table-responsive">
										<table class="table table-striped b-t text-small">
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
                                                <th>Price / pouch</th>
                                                <?php /*?><th>Total</th>
                                                <?php if($k=='pickup') {
													if($data[0]['shipment_country_id']==111){  ?>
                                                 <th> Price / pouch  With Tax </th>
                                                 <th>Total Price With Tax </th>
                                                 <?php } }?>
                                                 <th>Cylender Price</th>
                                                 <th>Tool Price</th><?php */?>
                                                 <?php if($data[0]['status'] != 1){?>
                                                 <th>Action</th>
                                                 <?php }?>
                                              </tr>
										  </thead>
                                          <tbody>
                                          	<?php $i=1;$k=1;
                                                foreach($qty_data as $skey=>$sdata){
                                                    ?>
                                                    <tr>
                                                        <?php 
                                                        foreach($sdata as $soption){ 
														//printr($soption);  
														   ?>
                                                            <tr id="quotation-row-<?php echo $soption['product_quotation_price_id']; ?>">
                                                            <th><?php echo $skey;?></th>
                                                            <?php $i=$k++;?>
                                                             <td>
															   <?php echo ucwords($soption['text']).' ('.$soption['printing_effect'].')';?></td>
                                                                <td><?php echo (int)$soption['width'].'X'.(int)$soption['height'].'X'.
																$soption['gusset']; if($data[0]['product_name']!=10){if($soption['volume']>0) echo ' ('.$soption['volume'].')';}
																else echo ' (Custom)'.' ('.$soption['make'].')'; ?></td>
                                                                 <td>
                                                             <?php    for($gi=0;$gi<count($soption['materialData']);$gi++){
											  echo '<b>'.($gi+1).' Layer : </b>'.$soption['materialData'][$gi]['material_name'].' : '.(int)$soption['materialData'][$gi]['material_thickness'].'<br>';
										}?>
                                                                 </td>
																 <?php if($dat['quotation_status'] == 0){
																	
																	 if(isset($adminCountryId['discount']) and $adminCountryId['discount']>0.000)
																{ if($dat['currency']=='INR')
																echo '<td><input type="text" id="discount_'.$i.'" name="discount_'.$i.'" value="'.$soption['discount'].'" class="form-control" style="width: 100px;"  onfocusout="changeDiscount('.$i.')">
																<input type="hidden" id="quantity_'.$i.'" name="quantity_'.$i.'" value="'.$soption['product_quotation_quantity_id'].'" class="form-control" style="width: 100px;" ></td>'; } }?>
                                                                <td>
																	<?php   if($soption['discount'] && $soption['discount'] >0.000) {?>
                                                                <b>Total : </b><?php $pretot= $obj_quotation->numberFormate((($soption['totalPrice'] / $skey) / $dat['currency_price']),"3"); echo $pretot;?><br />
                                                                <b>Discount (<?php echo $soption['discount'];?> %) : </b>
																<?php $predis = $pretot*$soption['discount']/100; 
																echo $obj_quotation->numberFormate($predis,"3");?><br />
                                                                <b>Final Total : </b>
																<?php echo $dat['currency'].' '.$obj_quotation->numberFormate(($pretot-$predis),"3"); ?>
																	<?php }else echo $dat['currency'].' <span id="price_'.$i.'">'.
																	$obj_quotation->numberFormate((($soption['totalPrice'] / $skey) / $dat['currency_price']),"3").'</span>';?>
                                                                     <span id="<?php echo 'hiddenField_'.$i;?>" style="display:none" >
                                                        <input type="text" contenteditable="true" id="<?php echo 'edit_price_'.$i; ?>" style="width:55px;border: 2px solid rgb(72, 159, 231);" value="<?php echo $obj_quotation->numberFormate((($soption['totalPrice'] / $skey) / $dat['currency_price']),"3");?>"/>
                                                         <input type="hidden" name="product_quotation_price_id<?php echo $i;?>" id="product_quotation_price_id<?php echo $i;?>" value="<?php echo $soption['product_quotation_price_id']?>" />
                                                           <input type="hidden" name="qty<?php echo $i;?>" id="qty<?php echo $i;?>" value="<?php echo $skey;?>" />
                                                           <input type="hidden" name="currency_price<?php echo $i;?>" id="currency_price<?php echo $i;?>" value="<?php echo $dat['currency_price']?>" />
                                                           </span>
                                                         <?php //if($data[0]['status'] != 1){?> 
                                                        <div class="btn-group"> 
                                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" onclick="edit_price(<?php echo $i;?>)">
                                                        	<i class="fa fa-pencil"></i>
                                                        </a> 
                                                        </div><?php // }?>
                                                                </td>
                                                               <?php /*?> <td>
                                                                <?php  if($soption['discount'] && $soption['discount'] >0.000) {?>
                                                                <b>Total : </b><?php $tot= $obj_quotation->numberFormate(($soption['totalPrice'] / $dat['currency_price'] ),"3"); echo $tot;?><br />
                                                                <b>Discount (<?php echo $soption['discount'];?> %) : </b>
																<?php $dis = $tot*$soption['discount']/100; 
																echo $obj_quotation->numberFormate($dis,"3");?><br />
                                                                <b>Final Total : </b>
																<?php echo $dat['currency'].' '.($tot-$dis); ?>
																	<?php } else echo $dat['currency'].' '.$obj_quotation->numberFormate(($soption['totalPrice'] / $dat['currency_price'] ),"3");?>
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
                                                                 <td><?php if($soption['tool_price']>0) {echo (int)$soption['tool_price'];}else '';?></td><?php */?>
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
						?>
                      
                      <div class="form-group">
                        <div class="col-lg-9 col-lg-offset-3">
                        	<?php if($data[0]['quotation_status'] == 0){ ?>
                                <button type="submit" name="btn_save" id="btn_save" class="btn btn-primary">Save</button>	
                                <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '&mod=add', '',1);?>">Try New</a>			
                            <?php } ?>                            
                            <a class="btn btn-default" href="<?php echo $obj_general->link($rout, 'filter_edit='.$filteredit							, '',1);?>">Cancel</a>           
                            <?php if($obj_session->data['ADMIN_LOGIN_SWISS']==1 && $obj_session->data['LOGIN_USER_TYPE']==1) { ?>
                            	<a class="btn btn-primary" href="<?php echo $obj_general->link($rout, 'mod=details&quotation_id='.encode($quotation_id), '',1);?>">Details</a>
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
				success: function(response){//alert(response);
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
			if(selcurrency.length == 0 ){
				$(".note-error").remove();
				$("#sel_currency").after('<span class="note-error required">Please select Currency</span>');
			}else{
				$(".note-error").remove();
				var url = '<?php echo HTTP_SERVER.'pdf/pdf.php?mod='.encode('productQuotation').'&token='.rawurlencode(encode($quotation_id)).'&ext='.md5('php');?>&ssc='+selcurrency;//+'&sscr='+btoa(rate)
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
	function edit_price(id)
    {
    	//alert(id);
    	$('#hiddenField_'+id).show();
    	$('#price_'+id).hide();
    	$("input[type=text][id=edit_price_"+id+"]").focusout(function(){
    		var  postArray = {};
    		postArray['price'] = $("input[type=text][id=edit_price_"+id+"]").val();
    		postArray['product_quotation_price_id'] = $("#product_quotation_price_id"+id).val();
    		postArray['qty'] = $("#qty"+id).val();
    		postArray['currency_price'] = $("#currency_price"+id).val();
    		postArray['status'] =0;
       		var order_price_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=update_price', '',1);?>");
    			$.ajax({
    				url : order_price_url,
    				method : 'post',
    				data : {postArray : postArray},
    				success: function(response){
    		//alert(response);
    				set_alert_message('Successfully Updated',"alert-success","fa-check");
    				 window.setTimeout(function(){location.reload()},1000)
    				},
    				error: function(){
    					return false;	
    				}
    				});
    		$('#hiddenField_'+id).hide();
    		$('#price_'+id).show();
    		$('#price_'+id).html(postArray['price']);
        });
    	
    }
    function excel(quotation_id)
    {
        var add_product_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=data', '',1);?>");
    	 $.ajax({
            url: add_product_url, // the url of the php file that will generate the excel file
           	data : {quotation_id:quotation_id},
    		method : 'post',
            success: function(response){
    		//console.log(response);
    			excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(response);
    			 $('<a></a>').attr({
    							'id':'downloadFile',
    							'download': 'stock-price-report.xls',
    							'href': excelData,
    							'target': '_blank'
    					}).appendTo('body');
    					$('#downloadFile').ready(function() {
    						$('#downloadFile').get(0).click();
    					});
            }
    		
        });
    }
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