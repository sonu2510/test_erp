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

//edit user
$edit = '';
$invoice_id='';
if(isset($_GET['invoice_no']) && !empty($_GET['invoice_no'])){	
		$invoice_no = decode($_GET['invoice_no']);
		$invoice = $obj_invoice->getInvoiceData($invoice_no);
		$invoice_id = $invoice['transfer_invoice_id'];
		$edit = 1;
}
if(isset($_GET['proforma_id']) && !empty($_GET['proforma_id'])){	
		$proforma_id = decode($_GET['proforma_id']);
		$proforma = $obj_pro_invoice->getProforma($proforma_id);
		
		$edit = 1;
}
if(isset($_POST['btn_save'])){
    //	printr($_POST);die;
	$addedByinfo=$obj_invoice->addOrder($_POST);

	$obj_session->data['success'] = 'Invoice successfully Added!';
	page_redirect($obj_general->link($rout, '', '',1));
}
if(isset($_POST['btn_update'])){
	//printr($_POST);die;
	$addedByinfo=$obj_invoice->updateOrder($_POST,decode($_GET['invoice_no']));
	$obj_session->data['success'] = 'Invoice successfully Updated!';
	page_redirect($obj_general->link($rout, '', '',1));
}


if($display_status){	
$countries = $obj_invoice->getCountry();
$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
$addedByInfo = $obj_invoice->getUser($user_id,$user_type_id);
if($user_type_id==2 && $addedByInfo['user_type_id']==4)
{
	$ibInfo = $obj_invoice->getUser($addedByInfo['user_id'],$addedByInfo['user_type_id']);
	$addedByInfo['gst']=$ibInfo['gst'];
	$addedByInfo['company_address']=$ibInfo['company_address'];
	$addedByInfo['bank_address']=$ibInfo['bank_address'];
}
$buyer_order_no=0;
//printr(ADMIN_EMAIL);
/*SWISS PAC PVT LTD.

Padra Jambusar National highway
At Dabhasa Village, Pin 391440
Taluka.Padra,

shir@swisspack.co.in*/
?>
<section id="content">
  <section class="main padder">
    <div class="clearfix">
       <h4><i class="fa fa-edit"></i> Transfer Invoice</h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>
        <div class="col-sm-12">
        	<section class="panel">
	          <header class="panel-heading bg-white"> Transfer Invoice Detail </header>
    	      <div class="panel-body">
        	    <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
            		<div class="form-group">
        		        <label class="col-lg-3 control-label"><span class="required">*</span>Invoice Date</label>
                		<div class="col-lg-3">
		                 	<input type="text" name="invoicedate" readonly data-date-format="yyyy-mm-dd"  value="<?php if(isset($invoice)) { echo $invoice['trans_inv_date']; } else { echo date("Y-m-d") ; } ?>" placeholder="Invoice Date" id="invoicedate" class="input-sm form-control datepicker validate[required]"/>
        		         </div>
                         <?php if(isset($invoice)) {?>
                             <label class="col-lg-3 control-label"><span class="required">*</span>Transfer Invoice No</label>
                             <div class="col-lg-3">
                                <input type="text" name="trans_no" readonly value="<?php if(isset($invoice)) { echo $invoice['transfer_invoice_no']; }?>" class="form-control"/>
                             </div>
                         <?php } ?>
		             </div>
                     
                     <div class="form-group">
                  		<label class="col-lg-3 control-label"><span class="required">*</span>Proforma Invoice No</label>
                            <div class="col-lg-3">
                                <input type="text" name="proforma_no"  value="<?php if(isset($invoice)) { echo $invoice['proforma_no']; } else if(isset($proforma)) { echo $proforma['pro_in_no']; } else { echo '' ; } ?>  " placeholder="Proforma Invoice No" id="proforma_no" class="form-control validate[required]" onchange="check_pro_invoice()" />
                             </div>
                         <label class="col-lg-3 control-label">Sales Invoice No</label>
                            <div class="col-lg-3">
                                <input type="text" name="sale_no"  value="<?php if(isset($invoice)) { echo $invoice['sales_no']; } else { echo '' ; } ?>" placeholder="Sales Invoice No" id="sale_no" class="form-control" onchange="check_sales_invoice()" />
                             </div>
                    </div>
                    
                         <div class="form-group">
                            
                              <label class="col-lg-3 control-label"><span class="required">*</span>Buyers order No</label>
                                 <div class="col-lg-1">
                                     
                                        <input type="text" name="buyers_order_no" id="buyers_order_no" value="<?php if(isset($invoice['buyers_order_no'])) { echo $invoice['buyers_order_no']; }else{ echo $buyer_order_no;}?>"  class="form-control <?php echo $validate;?>">
                                </div>
                                
                                <label class="col-lg-5 control-label"><span class="required">*</span>Contact No (Phone Number)</label>
                                 <div class="col-lg-2">
                                        <input type="text" name="contact_no" id="contact_no" value="<?php if(isset($invoice)) { echo $invoice['contact_no']; } else { echo '' ; } ?>"  class="form-control <?php echo $validate;?>">
                                </div>
                            </div>
                        <div class="form-group">
                       
                        <label class="col-lg-3 control-label"><span class="required">*</span>Customer Name</label>
                        <div class="col-lg-3">
                          <input type="text" name="customer_name"  value="<?php if(isset($invoice)) { echo $invoice['customer_name']; } else if(isset($proforma)) { echo $proforma['customer_name']; } else { echo '' ; } ?>" placeholder="Customer Name" id="customer-name" class="form-control validate[required]" autocomplete="off" />
                         <input type="hidden" name="address_book_id"  value="<?php echo isset($invoice) ? $invoice['address_book_id'] : '' ; ?>" id="address_book_id" class="form-control " />
                             <div id="ajax_return"></div>
                        </div>
                         <label class="col-lg-3 control-label"><span class="required">*</span>Email</label>
                        <div class="col-lg-3">
                          <input type="text" name="email" id="email" value="<?php if(isset($invoice)) { echo $invoice['email']; } else if(isset($proforma)) { echo $proforma['email']; } else { echo '' ; } //echo isset($invoice) ? $invoice['email'] : '' ; ?>" placeholder="Email"  class="form-control validate[required,custom[email]]">
                        </div>
                      </div>
                  
                        <div class="form-group">
                            <label class="col-lg-3 control-label"><span class="required">*</span>Address</label>
                                    <div class="col-lg-8">
                                    	 <textarea class="form-control validate[required]" rows="2" cols="45" name="customer_address" id="customer_address"><?php if(isset($invoice)) { echo $invoice['customer_address']; } else if(isset($proforma)) { echo $proforma['address_info']; } else { echo '' ; } ?></textarea>
                                </div>
                 		 </div>
             
              		<div class="form-group">
             			<label class="col-lg-3 control-label"><span class="required">*</span>Country of Final Destination </label>
                		<div class="col-lg-3">
                          <select name="country_final" id="country_final" class="form-control validate[required]" >
                             <option value="">Select Country</option>
                            <?php if(isset($proforma)) 
									$sel_destination_country= isset($proforma['destination'])?$proforma['destination']:$addedByInfo['country_id'];
								else	
									$sel_destination_country= isset($invoice['country_id'])?$invoice['country_id']:$addedByInfo['country_id'];
									
                                    foreach($countries as $country){
                                        if($sel_destination_country && $sel_destination_country == $country['country_id']){
                                            echo '<option value="'.$country['country_id'].'" selected="selected" >'.$country['country_name'].'</option>';
                                        }else{
                                            echo '<option value="'.$country['country_id'].'">'.$country['country_name'].'</option>';
                                        }
                                    } ?>
                           </select>
                		</div>
              		</div>
                  
              		<div class="form-group">
               			<label class="col-lg-3 control-label"><span class="required">*</span>City</label>
		                <div class="col-lg-3">
                        	<input type="text" name="city" id="city" value="<?php echo isset($invoice) ? $invoice['city'] : '' ; ?>" class="form-control validate[required]">
                		</div> 
                        <label class="col-lg-3 control-label"><span class="required">*</span>Place Order From</label>
		                <div class="col-lg-3">
        			      <select name="region" id="region" class="form-control validate[required]">
                                	<option value="">Select State</option>
                                    <option value="Sydney" <?php if(isset($invoice['region']) && $invoice['region']=='Sydney') { echo "selected=selected";}?>>Sydney</option>
                                    <option value="Melbourne" <?php if(isset($invoice['region']) && $invoice['region']=='Melbourne') { echo "selected=selected";}?>>Melbourne</option>
                          </select>
                		</div> 
                        
              		</div> 
                     <?php $trans = (isset($invoice['trans_satus']) && !empty($invoice['trans_satus'])) ? explode(',',$invoice['trans_satus']):array();
					?>
                     <div class="form-group">
                             <label class="col-lg-3 control-label"><span class="required">*</span>Want Stock From</label>
                             <div class="col-lg-3">
                        		<input type="radio" name="trans_from[]" id="trans_from" value="s_to_m" class="validate[minCheckbox[1]]"  <?php echo (in_array('s_to_m',$trans))?'checked="checked"':'checked="checked"';?>> Sydeney To Melbourne </br>
                  				<input type="radio" name="trans_from[]" id="trans_from" value="m_to_s" class="validate[minCheckbox[1]]" <?php echo (in_array('m_to_s',$trans))?'checked="checked"':'';?>> Melbourne To Sydeney</br>
                			</div> 
                     </div>
					<div class="form-group">
                    	 <label class="col-lg-3 control-label"><span class="required">*</span>Do you want to dispatch order directly to customer?</label>
                          <div class="btn-group" data-toggle="buttons">
						<?php 
							$classy='';
                            $checky='';
                            $classn='';
                            $checkn='';
                        if(isset($invoice['dis_or_warehouse']) && $invoice['dis_or_warehouse'] == 1 ){
                            $classy='active';
                            $checky='checked="checked" ';
                        }
                        else
                        {
                            $classn='active';
                            $checkn='checked="checked" ';
                        }?>
                        <label class="btn btn-sm btn-white btn-on <?php echo $classy;?>">
                          <input type="radio" name="dispatch" id="dis" value="1" <?php echo $checky;?>>
                          Yes </label>
                        <label class="btn btn-sm btn-white btn-off <?php echo $classn;?>">
                          <input type="radio" name="dispatch" id="dis" value="0" <?php echo $checkn;?>>
                          No </label>
                      
                      </div>
                    </div>
                    <div class="form-group" id="customer_detail" <?php if(isset($invoice['dis_or_warehouse']) && $invoice['dis_or_warehouse'] == 0 ){ echo "style=display:none;";  }?>>
                    	<label class="col-lg-3 control-label"><span class="required">*</span>Customer Detail</label>
                        <div class="col-lg-8">
                              <textarea class="form-control validate[required]" rows="4" cols="45" name="customer_detail"><?php if(isset($invoice)) { echo $invoice['customer_detail']; } else if(isset($proforma)) { echo $proforma['del_address_info']; } else { echo '' ; } ?></textarea>
                         </div>
                    </div>
                     
                     <div class="form-group" id="msg" <?php if(isset($invoice['dis_or_warehouse']) && $invoice['dis_or_warehouse'] == 1 ){ echo "style=display:none;";  }?>>
                    	<label class="col-lg-3 control-label">Message</label>
                        <div class="col-lg-8">
                              <textarea class="form-control validate[required]" rows="1" cols="45" name="msg" readonly="readonly">Please Transfer This Stock To Our Warehouse !!</textarea>
                         </div>
                    </div>
                    
                    <div class="form-group" id="rack_col" <?php if(isset($invoice['dis_or_warehouse']) && $invoice['dis_or_warehouse'] == 1 ){ echo "style=display:none;";  }?>>
                             <label class="col-lg-3 control-label"><span class="required">*</span>Pallet Name</label>
                             <div class="col-lg-3">
                             	<?php $pallets = $obj_invoice->getpallet();?>
                                	  <select name="pallet_nm" id="pallet_nm" class="form-control validate[required]" onchange="get_pallet_trans()">
                                            <option value="">Select Pallet</option>
											<?php
                                                  foreach($pallets as $pallet)
                                                  {
												  	if(isset($invoice) && $pallet['goods_master_id'] == $invoice['pallet_nm'])
													{ ?>
														<option value="<?php echo $pallet['goods_master_id']; ?>" selected="selected" row_col="<?php echo $pallet['row'].'='.$pallet['column_name'];?>"><?php echo $pallet['name']; ?></option>
											<?php	}
													else
													{?>
                                                      <option value="<?php echo $pallet['goods_master_id']; ?>" row_col="<?php echo $pallet['row'].'='.$pallet['column_name'];?>"><?php echo $pallet['name']; ?></option>
                                            <?php  }
												 } ?>
                                		</select> 
                			</div>
                             <label class="col-lg-3 control-label"><span class="required">*</span>Rack Number</label>
                             <div class="col-lg-3">
                             	<select name="rack_no" id="rack_no" style="width: inherit;" class="form-control validate[required]">
                                	<option value="">Select Rack No.</option>
                                
                                
                                
                                </select>
                			</div> 
                     </div>
                     
              		 <div class="line line-dashed m-t-large"></div>
                          <div class="form-group">
							<label class="col-lg-3 control-label"><span class="required">*</span>Product Detail</label> 
							<div class="col-lg-7" >
									<section class="panel">
									  <div class="table-responsive">
										<table class="tool-row table  b-t text-small" id="myTable">
                                        	<tr>
                                            	<td>Product Code</td>
                                                <td>Product Description</td>
                                                <td>Qty</td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                			<?php 
                			        if(isset($proforma))
										$total_qty_price_data = $obj_pro_invoice->getInvoice($proforma['proforma_id']);
									else
										$total_qty_price_data = $obj_invoice->getInvoiceProduct($invoice_id);
                			
								  $productcodes = $obj_invoice->getproductcodes();//printr($productcodes);
								 // $pro_code=utf8_encode($productcodes);
								 $pro_code= htmlspecialchars(json_encode($productcodes), ENT_QUOTES, 'UTF-8');
								  	if(isset($total_qty_price_data) && !empty($total_qty_price_data))
									{
									    $total_qty_price_data =$total_qty_price_data;
									}
								  	else
									{
										$total_qty_price_data[]= array(
												'invoice_product_id' => '',
												'product_code_id' => '',
												'description' => '',
												'qty' => '',
												'date_modify' =>'',
												'date_added' => '',
												'is_delete' =>'',
												'user_id' =>'',
												'user_type_id' =>''
											);
									}	
							  if(isset($total_qty_price_data) && !empty($total_qty_price_data)){
							   		$count = 0;
							   		foreach ($total_qty_price_data as $qtyRate)
							   		{
							   		    
    							   		if(isset($proforma))
    									{
    							   		   $product_desc=$obj_invoice-> getProductCd($qtyRate['product_code_id']);
    									   $qtyRate['description'] = $product_desc['description'];
    									   $qtyRate['qty'] = $qtyRate['quantity'] ;
    									}
							   		   $product_id=$obj_invoice-> getSpoutDetail($qtyRate['product_code_id']);
										$id = $qtyRate['invoice_product_id'];
										$fan='';
									?>	 
                                    <tr>
                                    		<input type="hidden" id="min_arr" value='<?php echo $pro_code;?>' />
                                         	<input type="hidden" name="qty_master[<?php echo $count; ?>][min_qty_id]" id="min_qty_id_<?php echo $count; ?>" value="<?php echo $qtyRate['invoice_product_id']; ?>" class="form-control validate[required,custom[number]]" >
                              			<td> 
                                        			<div onclick="open_tab(<?php echo $count; ?>)">
                                                       <select name="qty_master[<?php echo $count; ?>][product_keyword]" onchange="getSpoutDetail(<?php echo $count; ?>)"  id="product_keyword<?php echo $count; ?>" class="form-control validate[required] chosen_data">
                                                        <option value="">Select Product</option>
                                                        <?php  foreach($productcodes as $code)
                                                           {
                                                               if($code['product_code_id']==$qtyRate['product_code_id'])
                                                                  echo '<option value="'.$code['product_code_id'].'" selected="selected">'.$code['product_code'].'</option>';
                                                               else
                                                                    echo '<option value="'.$code['product_code_id'].'" disc="'.$code['description'].'">'.$code['product_code'].'</option>';
                                                           }
                                                           ?>
                                                    </select>
                                                    </div>
                                                </td>  
                                                <td>   
                                                       <input type="text" name="qty_master[<?php echo $count;?>][product_name]" id="product_name_<?php echo $count; ?>" value="<?php echo $qtyRate['description'];?>"  readonly="readonly" class="form-control validate" style="width:400px"/>
                                               </td>
                                              
                           				 <td>
                                        		<input type="text" name="qty_master[<?php echo $count; ?>][min_qty]" id="min_<?php echo $count; ?>" value="<?php echo $qtyRate['qty']; ?>" class="form-control validate[required,custom[number]]" placeholder="Qty" />
                                                <input type="hidden" id="count" value='<?php echo $count;?>' />
                                    	</td>
                                    	 <td style="width:200px">
                                         <div  id="filling_div_<?php echo $count;?>" <?php if(isset($product_id) && ($product_id==31 || $product_id=='16' || $product_id=='50')){ ?> style="display:block" <?php }else{ echo 'style="display:none"';} ?>>
                                                     <input type="radio" name="qty_master[<?php echo $count;?>][filling]" id="from_top_<?php echo $count; ?>" value="Filling from Spout" <?php if(isset($qtyRate['filling']) && ($qtyRate['filling'] == 'Filling from Spout')) { echo 'checked=checked'; } ?> checked />Filling from Spout<br>
                                                     <input type="radio" name="qty_master[<?php echo $count;?>][filling]" id="from_top_<?php echo $count; ?>" value="Filling from Top" <?php if(isset($qtyRate['filling']) && ($qtyRate['filling'] == 'Filling from Top')) { echo 'checked=checked'; } ?>  />Filling from Top 
                                              </div>
                                               </td>
                                    		
                                       
                                    		
                                    <?php if($count==0){ ?>
                                        <td>
                                                <a class="btn btn-success btn-xs btn-circle addmore" data-toggle="tooltip" data-placement="top" title="Add Profit" ><i class="fa fa-plus"></i></a>
                                       </td>
                                    <?php } else { ?>
                              			<td>
                                          <a class="btn btn-danger btn-xs btn-circle remove" data-toggle="tooltip" data-placement="top" title="Remove" onClick="remove_row(<?php echo $qtyRate['invoice_product_id'];?>)"><i class="fa fa-minus"></i></a>
                                	</td>
                                    <?php } ?>
                              	</tr>
                               		<?php $count++; } } ?>
                                </table>
									  </div>
                                  	</section> 
								</div>
			  			  </div> 
             	<input type="hidden" name="edit" id="edit" value="<?php echo $edit; ?>"  />  
            <div class="line line-dashed m-t-large"></div>           
               <div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                <div class="col-lg-4">
                  <select name="status" id="status" class="form-control">                  
                    <option value="1" <?php echo (isset($invoice['status']) && $invoice['status'] == 1)?'selected':'';?> > Active</option>
                    <option value="0" <?php echo (isset($invoice['status']) && $invoice['status'] == 0)?'selected':'';?>> Inactive</option>
                  </select>
                </div>
               </div>
               
               	<div class="form-group">
                      <div class="col-lg-9 col-lg-offset-3">
                      
                       <?php if(isset($invoice_no)) {?>
                         <button type="submit" name="btn_update" id="btn_update" class="btn btn-primary">Update Order</button> 
                     <?php } 
					 	else
						{?>
                        <button type="submit" name="btn_save" id="btn_save" class="btn btn-primary" >Generate Order</button>
                        <?php } ?>	
                        <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a>
					 </div>
                </div>
	            </form>
    	      </div>
        	</section>
	    </div>
    </div>
  </section>
</section>
<div id="test"></div>
<style type="text/css">
.btn-on.active {
    background: none repeat scroll 0 0 #3fcf7f;
}
.btn-off.active{
	background: none repeat scroll 0 0 #3fcf7f;
	border: 1px solid #767676;
	color: #fff;
}
@media (max-width: 400px) {
  .chunk {
    width: 100% !important;
  }
}

#ajax_response,#ajax_return{
	border : 1px solid #13c4a5;
	background : #FFFFFF;
	position:relative;
	display:none;
	padding:2px 2px;
	top:auto;
	border-radius: 4px;
}
#holder{
	width : 350px;
}
.list {
	padding:0px 0px;
	margin:0px;
	list-style : none;
}
.list li a{
	text-align : left;
	padding:2px;
	cursor:pointer;
	display:block;
	text-decoration : none;
	color:#000000;
}
.selected{
	background : #13c4a5;
}
.bold{
	font-weight:bold;
	color: #227442;
}
.about{
	text-align:right;
	font-size:10px;
	margin : 10px 4px;
}
.about a{
	color:#BCBCBC;
	text-decoration : none;
}
.about a:hover{
	color:#575757;
	cursor : default;
}
</style>
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>ckeditor3/ckeditor.js"></script>


<script src="https://harvesthq.github.io/chosen/chosen.jquery.js" type="text/javascript"></script>
<link rel="stylesheet" href=" https://harvesthq.github.io/chosen/chosen.css" type="text/css"/> 

    

<script>
$(document).ready(function(){
            $(".chosen_data").chosen();
			 //$(".chosen_data").chosen({"disable_search": true});
           });
$(".chosen-select").chosen();
jQuery(document).ready(function(){
	jQuery("#form").validationEngine();
	
	var pallet_nm = $("#pallet_nm").val();
	if(pallet_nm!='')
		get_pallet_trans();
	
	var dis_val = $('[name="dispatch"]:checked').val();
	if(dis_val == '0')
		$("#customer_detail").hide();
		
	 $("#invoicedate").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
});
$(document).on('click', ".addmore", function () {
	more_price();
});
$(document).on('click', ".removetPrice", function () {
    var id = $(this).attr("id");
	$(this).parent().closest(".form-group").remove();
});
function more_price(){
	var count = $('#myTable tr').length;
	
	
	//console.log($('#min_arr').val());
	var arr = jQuery.parseJSON($('#min_arr').val());
	
	var html = '';
	//alert(count);
	//onchange="getdesc('+count+')
	html +='<tr><td><div onclick="open_tab('+count+')">';
	  html += '<select name="qty_master['+count+'][product_keyword]" onchange="getSpoutDetail('+count+')" id="product_keyword'+count+'" class="form-control validate[required] chosen-select "><option value="">Select Product</option>';
	  for(var i=0;i<arr.length;i++)
	  {
		 html += '<option value="'+arr[i].product_code_id+'">'+arr[i].product_code+'</option>';
	  }
	  html +=  '</select></td><td>';							
	html += '<input type="text" name="qty_master['+count+'][product_name]" id="product_name_'+count+'" value=""  readonly="readonly" class="form-control validate" style="width:400px"/></td>';
	
	html +='<td>';
	  html += '<input type="text" name="qty_master['+count+'][min_qty]]" value="" class="form-control validate[required]" placeholder="Qty">';
	html += '</td>';
		html += '  <td style="width:200px">';
                html += '<div  id="filling_div_'+count+'" style="display:none">';
                 html += '<input type="radio" name="qty_master['+count+'][filling]" id="from_top_'+count+'" value="Filling from Spout" checked  class="valve" />Filling from Spout<br>';
                 html += '<input type="radio" name="qty_master['+count+'][filling]" id="from_top_'+count+'" value="Filling from Top"   class="valve"  />Filling from Top';
        html += '</div></td>';
               

	html +='<td><input type="hidden" name="qty_master['+count+'][min_qty_id]" value="" class="form-control validate[required,custom[number]]" >';
		html +='<a class="btn btn-danger btn-xs btn-circle remove" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-minus"></i></a></div>';
	html +='</td></tr>';
	
	$("#myTable").append(html);
	
	//$(".chosen-select").chosen({"disable_search": true});
	$(".chosen-select").chosen();
	
	$(' .remove').click(function(){
		$(this).parent().parent().remove();
	});
}

$(' .remove').click(function(){
		$(this).parent().parent().remove();
});
function getdesc(count)
{
	var product_code_id = $("#product_keyword"+count).val();
		var getdesc_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=product_desc', '',1);?>");
			$.ajax({
				url : getdesc_url,
				method : 'post',
				data : {product_code_id : product_code_id},
				success: function(response){
					if(response!='')
					{
						$("#product_name_"+count).removeAttr('readonly','readonly');
						$("#product_name_"+count).val(response);
						$("#product_name_"+count).attr('readonly','readonly');
					}
				},
				error: function(){
					return false;	
				}
		});
	
}
$('[name="dispatch"][type="radio"]').on('change', function() {
	var val = $(this).val();
	if(val=='1') 
	{
		$("#customer_detail").show();
		$("#msg").hide();
		$("#rack_col").hide();
	}
	else
	{
		$("#customer_detail").hide();
		$("#msg").show();
		$("#rack_col").show();
	}
});
function remove_row(invoice_product_id)
{
	var remove_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=delQty', '',1);?>");
			$.ajax({
				url : remove_url,
				method : 'post',
				data : {invoice_product_id : invoice_product_id},
				success: function(response){
				},
				error: function(){
					return false;	
				}
		});
}
function get_pallet_trans()
{
	var row_col = $('#pallet_nm option:selected').attr('row_col');
	var arr = row_col.split('=');
	var row = arr[0];
	var col = arr[1];
	var sel = '';
	var rack_no = '<?php echo isset($invoice) ? $invoice['rack_no'] : '';?>';
				var d = 1;
				for(var i=1;i<=row;i++)
				{
					for(var r=1;r<=col;r++) 
					{
						var num = i+'='+r;
						if(rack_no == num)
		  				{ 
							 sel+= '<option value="'+i+'='+r+'" selected="selected">'+d+'</option>';
						} else { 
							 sel+= '<option value="'+i+'='+r+'">'+d+'</option>';
					    } 
						d++;
					}
				}
	$("#rack_no").html(sel);
}
function check_pro_invoice()
{
	var proforma_no=$("#proforma_no").val();
	var invoice_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=check_proforma', '',1);?>");
			$.ajax({
				url : invoice_url,
				method : 'post',
				data : {proforma_no : proforma_no},
				success: function(response){
						 if(response == ''){
								 alert('Please Enter the Proper Proforma Invoice No Or Generate the proforma Invoice'); 
								 $("#proforma_no").val("");	
							}
												
					},
				error: function(){
					return false;	
				}
			});
}
function check_sales_invoice()
{
	var sale_no=$("#sale_no").val();
	var invoice_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=check_sales', '',1);?>");
			$.ajax({
				url : invoice_url,
				method : 'post',
				data : {sale_no : sale_no},
				success: function(response){
						 if(response == ''){
								 alert('Please Enter the Proper Sales Invoice No Or Generate the Sales Invoice'); 
								 $("#sale_no").val("");	
							}
												
					},
				error: function(){
					return false;	
				}
			});
}

function open_tab(n)
{
	$(document).on('mouseover keyup keydown keypress', '#product_keyword'+n+' + .chosen-container .chosen-results li', function() {
    var productCode_text = $(this).text();
	console.log(productCode_text);
		if(productCode_text!='Select Product')
		{
			var url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=get_disc', '',1);?>");
					$.ajax({
						url : url,
						method : 'post',
						data : {productCode_text : productCode_text},
						success: function(response){
								$("#product_name_"+n).val(response);				
							},
						error: function(){
							return false;	
						}
					});
		
		}
	});
	

} 
function getSpoutDetail(count){
    
    	var product_code_id = $('#product_keyword'+count+' option:selected').val();
    	
    //	alert(product_code_id);
    	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getSpoutDetail', '',1);?>");
					$.ajax({
						url : url,
						method : 'post',
						data : {product_code_id : product_code_id},
						success: function(response){
					//	   alert(response);
    						    if(response=='16' || response=='31' || response=='50' ){
    						         $('#filling_div_'+count).css("display","block");
    						    }else{
    						         $('#filling_div_'+count).css("display","none");
    						    }
							},
						error: function(){
							return false;	
						}
					});
}

$("#customer-name").focus();
	var offset = $("#customer-name").offset();
	var width = $("#holder").width();

	$("#ajax_return").css("width",width);
	
	$("#customer-name").keyup(function(event){		
		 var keyword = encodeURIComponent($("#customer-name").val());
		 //console.log(keyword.length);
		 if(keyword.length>='3')
		 {
			 if(event.keyCode != 40 && event.keyCode != 38 )
			 {
				 var product_code_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=customer_detail', '',1);?>");
				 $("#loading").css("visibility","visible");
				 $.ajax({
				   type: "POST",
				   url: product_code_url,
				   data: "customer_name="+keyword,
				   success: function(msg){
					  	
				 var msg = $.parseJSON(msg);
				  //alert(msg);
				 	
				   var div='<ul class="list">';
				   
					if(msg.length>0)
					{
						for(var i=0;i<msg.length;i++) 
						{	
							
								div =div+'<li><a href=\'javascript:void(0);\' id="'+msg[i].address_book_id+'" c_id="'+msg[i].company_address_id+'"contact_id="'+msg[i].contact_id+'" f_id="'+msg[i].factory_address_id+'"consignee="'+msg[i].c_address+'" deladd="'+msg[i].f_address+'"vat_no="'+msg[i].vat_no+'"contact_no="'+msg[i].phone_no+'" email="'+msg[i].email_1+'"><span class="bold" >'+msg[i].company_name+'</span></a></li>';
						}
					}
					
					div=div+'</ul>';
					//console.log(div);
					if(msg != 0)
					  $("#ajax_return").fadeIn("slow").html(div);
					else
					{
					  $("#ajax_return").fadeIn("slow");	
					  $("#ajax_return").html('<div style="text-align:left;">No Matches Found</div>');
					   $("#email").val('');
				  		$("#address_book_id").val('');
						$("#customer_address").val('');
						$("#contact_no").val('');
					}
					$("#loading").css("visibility","hidden");
				   }
				 });
			 }
			 else
			 {				
				switch (event.keyCode)
				{
				 case 40:
				 {
					  found = 0;
					  $(".list li").each(function(){
						 if($(this).attr("class") == "selected")
							found = 1;
					  });
					  if(found == 1)
					  {
						var sel = $(".list li[class='selected']");
						sel.next().addClass("selected");
						sel.removeClass("selected");										
					  }
					  else
						$(".list li:first").addClass("selected");
						if($(".list li[class='selected'] a").text()!='')
						{
							$("#customer-name").val($(".list li[class='selected'] a").text());
							$("#email").val($(".list li[class='selected'] a").attr("email"));
							$("#address_book_id").val($(".list li[class='selected'] a").attr("id"));
							$("#customer_address").val($(".list li[class='selected'] a").attr("deladd"));
							$("#contact_no").val($(".list li[class='selected'] a").attr("contact_no"));
						}
				}
				 break;
				 case 38:
				 {
					  found = 0;
					  $(".list li").each(function(){
						 if($(this).attr("class") == "selected")
							found = 1;
					  });
					  if(found == 1)
					  {
						var sel = $(".list li[class='selected']");
						sel.prev().addClass("selected");
						sel.removeClass("selected");
					  }
					  else
						$(".list li:last").addClass("selected");
						if($(".list li[class='selected'] a").text()!='')
						{
							$("#customer-name").val($(".list li[class='selected'] a").text());
                  			$("#email").val($(".list li[class='selected'] a").attr("email"));
							$("#address_book_id").val($(".list li[class='selected'] a").attr("id"));
							$("#customer_address").val($(".list li[class='selected'] a").attr("deladd"));
							$("#contact_no").val($(".list li[class='selected'] a").attr("contact_no"));
							
						}
				 }
				 break;				 
				}
			 }
		 }
		 else
		 {
			$("#ajax_return").fadeOut('slow');
			$("#ajax_return").html("");
		 }
	});
	
	$('#customer-name').keydown( function(e) {
		if (e.keyCode == 9) {
			 $("#ajax_return").fadeOut('slow');
			 $("#ajax_return").html("");
		}
	});

	$("#ajax_return").mouseover(function(){
				$(this).find(".list li a:first-child").mouseover(function () {
					  $("#email").val($(this).attr("email"));
					  $("#contact_no").val($(this).attr("contact_no"));
					  $("#address_book_id").val($(this).attr("id"));
					  $("#customer_address").val($(this).attr("deladd"));
				
					  $(this).addClass("selected");
				});
				$(this).find(".list li a:first-child").mouseout(function () {
					  $(this).removeClass("selected");
					  $("#contact_id").val('');
					  $("#email").val('');
					  $("#contact_no").val('');
					  $("#customer_address").val('');
				
				});
				$(this).find(".list li a:first-child").click(function () {					
					  if($(this).attr("email")!='null')
					  	$("#email").val($(this).attr("email"));
					  else
					  	$("#email").val('');  
					  if($(this).attr("contact_no")!='null')
					  	$("#contact_no").val($(this).attr("contact_no"));
					  else
					  	$("#contact_no").val('');  
					 
					if($(this).attr("deladd")!='null')
					  	$("#customer_address").val($(this).attr("deladd"));
					  else
					 	 $("#customer_address").val('');
					 	 
    							 
					  $("#address_book_id").val($(this).attr("id"));
					   $("#customer-name").val($(this).text());
					   $("#ajax_return").fadeOut('slow');
						$("#ajax_return").html("");
					
				});
			});	
    
    

	
</script> 
<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>