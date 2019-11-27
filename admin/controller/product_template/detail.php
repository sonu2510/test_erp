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

$limit = LISTING_LIMIT;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}

$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];

//Start : edit
$edit = '';
if(isset($_GET['template_id']) && !empty($_GET['template_id'])){
	if(!$obj_general->hasPermission('view',$menuId)){
		$display_status = false;
	}else{
		$template_id = base64_decode($_GET['template_id']);
		//echo $order_id;
		//die;
		
		$data = $obj_template->getTempalte($template_id);
		$currency_data = $obj_template->getIBDetail($data[0]['user']);
		
		if($data[0]['userCurrencyPrice']!='0.00'){
		    $data[0]['userCurrencyPrice']=$data[0]['userCurrencyPrice'];
		    
		}else{
		    $data[0]['userCurrencyPrice']=$currency_data['product_rate'];
		}
		
		/*printr($data);
		die;*/
	//	$currency_data = $obj_order->getOrderCurrency($order_id);
	//	printr($currency_data);
		//die;
	}
}
if(isset($_POST['btn_save'])){
		//echo "Sdada";die;
		$order_id = $obj_template->addTemplate($template_id);
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
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
        
        <div class="col-lg-10">
        	<section class="panel">
              <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
              <form class="form-horizontal" method="post" name="form" id="order-form" enctype="multipart/form-data">
                    <div class="panel-body">
                    	<div class="col-lg-6" style="width:100%">
                           <h4><i class="fa fa-edit"></i> General Details</h4>
                           <div class="line m-t-large" style="margin-top:-4px;"></div><br/>
                         <span class="text-muted m-l-small pull-right">
                    	    Product Conversion Rate  : <b>	<?php echo $data[0]['userCurrencyPrice'];?></b>
                    </span> 
                     <div class="form-group">
                        <label class="col-lg-3 control-label">Title</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            	<?php echo $data[0]['title'];?>
                            </label>
                        </div>
                      </div>
                        <div class="form-group">
								<label class="col-lg-3 control-label">Product</label>
                                    <div class="col-lg-4">
                                         <label class="control-label normal-font">
                                                <?php echo $data[0]['product_name'];?>
                                        </label>
								</div>
							  </div>  
                              
                          <div class ="form-group">
                           <label class="col-lg-3 control-label">Shipment Country</label>
                                    <div class="col-lg-9 row">
                                    <div class="col-lg-10">
                                       <label class="control-label normal-font">        
											<?php $countries=json_decode($data[0]['country']);
                                              $str='';
                                              foreach($countries as $country)
                                              {
                                                  $str .= "country_id = ".$country." OR ";
                                              }
                                              $countryval = substr($str,0,-3);
                                             $country_name = $obj_template->getmultiplecountry($countryval);
                                               echo $country_name;?>
                           				</label>
                                    </div>
                                    </div>
                                    </div>
                      
                        <div class="form-group">
								<label class="col-lg-3 control-label">User</label>
								<div class="col-lg-9 row">
                                	<div class="col-lg-4">
                                        <label class="control-label normal-font">
                           					 <?php echo $data[0]['first_name'].' '.$data[0]['last_name'];?>
                            			</label>
                                    </div>
                                   <label class="col-lg-3 control-label">Currency</label>
                                    
                                    <div class="col-lg-4">
                                        <label class="control-label normal-font">
                             <?php echo $data[0]['currency_code'];?>
                            </label> 
                                    </div>
								</div>
						 </div>       
                         <div class="form-group">
								<label class="col-lg-3 control-label">Transpotation Type</label>
								<div class="col-lg-9 row">
                                	<div class="col-lg-4">
                                        <label class="control-label normal-font">
                            <?php //[kinjal] modify on (28/3/2016)
								if($data[0]['transportation_type'] == '')
					  				echo 'By Pickup';
								else
									echo $data[0]['transportation_type'];?>
                            </label>
                                  </div>
                               </div>
                         </div>
                                     <div class="form-group">
								<label class="col-lg-3 control-label">Price (<?php echo $data[0]['transportation_type'];?>)</label> 
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
                                         <?php if($data[0]['transportation_type']=='sea') { ?>
                                          <th>Transport Price</th>
                                          <?php } else { ?>
                                             <th>Courier Charge</th>
                                             <?php } ?>
                                          <th>Price</th>
                                        <th>Paking Price</th>
                                        </tr>
										  </thead>
                                          <tbody>
                                          	<?php //printr($data);
											//echo $data[0]['multi_product_quotation_id'];
											
											//$layer_detail = $obj_template->getLayerDetail($data[0]['multi_product_quotation_id']);
											//printr($layer_detail);
											foreach($data as $d)
											{ //printr($d);//echo "d";
												$dataDescArr = json_decode($d['description'],true);	
												//printr($dataDescArr);
												//echo "dataDescArr";
												$layer_detail = $obj_template->getLayerDetail($d['multi_product_quotation_id']);
												//printr($d['multi_product_quotation_id']);
												//$layer[''] = $layer_detail;
												foreach($dataDescArr as $qty=>$val)
												{
													//printr($qty);
													//printr($val['size'][$qty]);
													//echo "val";printr($d['layer']);
													if($val['size']=='')
														$size = $d['width'].'X'.$d['height'].'X'.$d['gusset'];
													else
														$size = $val['size'];
														
													$a[$size][$qty]=$d;
													$a[$size][$qty]['description']=$val;	
													
													
													//printr($layer_detail);
													//$a[$size][$qty]['layer'] = $layer_detail;
													//printr($a);	
													//echo "a";										
												}
																							
											}
                                                    ?>
                                                    <tr>
                                                        
                                                   <?php 
												  	//printr($a);
												   foreach($a as $key=>$array)
												   {	
                                                       foreach($array as $k=>$d) { 
													 		//echo $d['spout'];
														$spout_weight = $obj_template->getSpoutWeight($d['spout']);
														$tintie_weight = $obj_template->getTintieWeight($d['zipper']);
													//	printr($d);
															?>
                                                            <tr>
                                                            <th><?php echo $k;?></th>
                                                            <td>
															   <?php echo ucwords($d['zipper']).' '.ucwords($d['valve']).'</br>'.ucwords($d['spout']).' '.ucwords($d['accessorie']).'<br>';
															   	
																if($data[0]['stock_delivery'] == 'other_country_to_customer')
																{
																	echo '<br><b style="color:#51A351">Total Price (Without Added Stock Price) : </b>'.$d['description']['real_tot_price'].'</br> <b style="color:#51A351">Stock (%) :</b>'.$d['description']['user_stock_per'].'</br><b style="color:#51A351">Total Price (With Stock Price) : </b>'.$d['description']['tot_price_with_stock_price'].'<br><b style="color:#51A351"> Currency Price : '.$d['description']['currencyPrice'] ;
																}
																	
																?>
                                                               
                                                               </td>
                                                             <td><?php echo (int)$d['width'].'X'.(int)$d['height'].'X'.(int)$d['gusset'];?> 
																	<?Php if($d['product_id']!=10){if($d['volume']!='') echo ' ('.$d['volume'].')'; else echo ' (Custom)';} ?>
                                                             <?php 
															 //printr($data[0]['product_id']);die;
																$make_id= $d['description']['make_pouch'];
																$make_nm = $obj_template->getMakeNm($make_id);
																if($data[0]['product_id']==11)
																{
																	echo '<br/><small class="text-muted"> Total Weight : '.$d['description']['totalcalweight'].'</small>';
																}
																else
																{
																	echo '<br/><small class="text-muted"><b style="color:blue;"> Total Weight With Zipper : </b><b>'.$d['description']['total_weight_with_zipper'].' Kgs</b></small>
																	<br/><small class="text-muted"><b style="color:blue;"> Total Weight Without Zipper : </b><b>'.$d['description']['total_weight_without_zipper'].' Kgs</b></small>
																	<br/><small class="text-muted"><b style="color:red;"> Total Weight With Zipper 100 Plus: </b><b>'.$d['description']['total_weight_with_zipper_100_plus'].'</b> Kgs</small>
																	<br/><small class="text-muted"><b style="color:red;"> Total Weight Without Zipper 100 Plus:</b><b>'.$d['description']['total_weight_without_zipper_100_plus'].' Kgs</b></small>';
																}
																echo '<br/><small class="text-muted"> Wastage : '.$d['description']['wastage'] .'</small>
																<br/><small class="text-muted"> Profit : '.$d['description']['profit'].'</small>
																<br/><small class="text-muted"> Profit Type: ';
																if(isset($d['description']['profit_type']) && $d['description']['profit_type']==0) echo 'Rich'; else if($d['description']['profit_type']==1) echo 'Poor'; else echo 'More Poor';
																echo '</small>
																<br/><small class="text-muted"> Make Pouch : '.$make_nm['make_name'].'</small>';
																if($d['spout'] != 'No Spout')
                                                        			echo '<br><small class="text-muted">Total Spout Weight: '.number_format($spout_weight['weight']*$k,3,".","").' KG</small>';
																if($d['zipper'][0] == 'T')
																	echo '<br><small class="text-muted">Total TinTie Weight: '.number_format($tintie_weight['weight']*$k,3,".","").' KG</small>';
																	
																	echo '<br><span style="font-size:13px"><small class="text-muted">Ink Price : '.$d['description']['ink_price'].'</span>';
																	echo '<br><span style="font-size:13px"><small class="text-muted">Ink Solvent Price : '.$d['description']['ink_solvent_price'].'</span>';
														
																	echo '<br><span style="font-size:13px"><small class="text-muted">';
																	if($d['description']['cpp_adhesive']=='1')
																		echo 'CPP Adhesive Price : '.$d['description']['adhesive_price'];
																	else
																		echo 'Adhesive Price : '.$d['description']['adhesive_price'];
																	echo '</span>';
																	if($d['description']['adhesive_solvent_price']!='0.000')
																	echo '<br><span style="font-size:13px"><small class="text-muted">Adhesive Solvent Price : '.$d['description']['adhesive_solvent_price'].'</span>';
																
												?></td>
                                                <td> <?php /*?><?php $layer_detail = $obj_template->getLayerDetail($d['multi_product_quotation_id']); ?><?php */ //printr($layer_detail);?>
                                                         <?php   
																	$i=1; $product_quotation_id='';
																	foreach($layer_detail as $layer)
																	{ $cond =($d['volume']==$layer['volume'] && ($product_quotation_id == $layer['product_quotation_id']));
											  							if($i==1)
											  							    $cond = ($d['volume']==$layer['volume']);
											  							if($cond)
																		{
																			echo '<b>'.$i.' Layer : </b>'.$layer['material_name'].' : '.(int)$layer['material_thickness'].'<br>
																				  <b style="color:#51A351">Price : '.$layer['material_price'].'</b><br><br>';
																			$i++;
																			$product_quotation_id =$layer['product_quotation_id']; 
																		
																		}
																		
																	}?>
                                                            </td>
                                                                <?php if($d['transportation_type']=='By Sea') { ?>
                                                                <td>
																	<?php echo $d['description']['transportPerPouch']; ?>
                                                                </td>
                                                                <?php } else {?>
                                                                <td >
                                                                <?php  //printr($d['description']);
																	if($d['description']['total_weight_with_zipper']!='0')
																	   $courier_INCL=$d['description']['courier_charges']/$d['description']['total_weight_with_zipper'];
																	else
																	   $courier_INCL=$d['description']['courier_charges']/$d['description']['total_weight_without_zipper'];
																
																    if($d['description']['total_weight_with_zipper_100_plus']!='0')
																    {
																	   $courier_INCL_100_plus=$d['description']['courierCharge_100_plus']/$d['description']['total_weight_with_zipper_100_plus'];
																	   $total_courier_charge_100_plus = $courier_INCL_100_plus * $d['description']['total_weight_with_zipper'];
																    }
																	else
																	{
																	   $courier_INCL_100_plus=$d['description']['courierCharge_100_plus']/$d['description']['total_weight_without_zipper_100_plus'];
																	   $total_courier_charge_100_plus = $courier_INCL_100_plus * $d['description']['total_weight_without_zipper'];
																	}
																
																    
																	echo $d['description']['courier_charges']; echo '<br> <b style="color:#0066CC">Basic Courier Price : '.$d['description']['actual_courier_price'].' </b><br><br>Courier Price All INCL : '.number_format($courier_INCL,3).'<br><br><b style="color:red;">Total Courier Charge (100 Plus): </b>'.number_format($total_courier_charge_100_plus,3).' <b style="color:red;">Basic Courier Charge (100 Plus): </b>'.$d['description']['actual_courier_price_100_plus'].'<br><br><b style="color:red;">Courier Price All INCL (100 Plus):</b> '.number_format($courier_INCL_100_plus,3).'<br><br>' ;
																	
																	if(isset($d['description']['totalWeightWithZipper_spout']) && $d['spout'] != 'No Spout')
																	{
																		//formula : (with zip. weight + total spout weight) * spout_weight for by air <br>
																		echo '<b style="color:#0066CC">Calculation of spout pouch with courier total weight : </b><br><br>';
																		echo '('.$d['description']['totalWeightWithZipper_spout'].' + '.number_format($spout_weight['weight']*$k,3,".","").') * '.$spout_weight['weight_temp'].' = '.$d['description']['total_weight_without_zipper'];
																	}
																	?>
                                                                    
                                                                </td>
                                                                <?php }?>
                                                                <td>
                                                               <?php 
															if(isset($d['description']['spout_price'])){
																echo '<b>Spout  : </b>'.$d['description']['spout_price'].'<br>';
															}
															if(isset($d['description']['accessories_price'])){
																echo '<b>Acc  : </b>'.$d['description']['accessories_price'].'<br>';
															}
																echo '<b>Zipper  : </b>'.$d['description']['zipper_price'].'<br>
																<b>Valve Price :</b>'.$d['description']['valve_price'];?>
                                                                
                                                                 </td>
                                                                 <td><?php 	
																 echo  '<b>Pouch Packing Price : </b>'.$d['description']['packing_price'].'<br>';
																	  if(isset($d['description']['spout_additional_packing_price']))
																	  if($d['spout'] != 'No Spout')
																		 echo '<b>Spout Packing Price : </b>'.$d['description']['spout_additional_packing_price'];
																// echo $d['description']['packing_price'];
																			?></td>
                                                            </tr>
                                                    </tr>
                                                    <?php
                                               }
											   }
                                             ?>
                                          </tbody>
										</table>
									  </div>
									</section> 
								</div>
							  </div>                    
                     
                                 <?php /*?> <div>
									<?php printr($obj_session->data['clonecolor']); ?>
	                             </div><?php */?>
                             <div class="form-group" id="footer-div" >
                                <div class="col-lg-9 col-lg-offset-3">
                                  <a class="btn btn-default" href="<?php echo $obj_general->link($rout, 'mod=view&template_id='.$_GET['template_id'], '',1);?>">Cancel</a>  
                                </div>
                             </div>
                         </div> 
                    </div>
                </form>
        	</section>
      	</div>
     </div>
  </section>
</section>
<script>
function cloneColor(template_size_id){
	//alert("hui");
	var clone_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=cloneColor', '',1);?>");
	//alert(gusset_url);
	$.ajax({
		method : 'post',
		url: clone_url,
		data: {template_size_id:template_size_id},
		success: function(response) {
			//alert(response);
				location.reload();
		}
		,
		error: function(){
			return false;	
		}
	});
}
function PasteColor(template_size_id){
	//alert("hui");
	var clone_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=PasteColor', '',1);?>");
	//alert(gusset_url);
	$.ajax({
		method : 'post',
		url: clone_url,
		data: {template_size_id:template_size_id},
		success: function(response) {
			//alert(response);
				location.reload();
		}
		,
		error: function(){
			return false;	
		}
	});
}
$("#product").change(function(){
		
			var val = $(this).val();
			var text = $("#product option[value='"+val+"']").text().toLowerCase();
			//alert(text);
			//alert(text);
		/*	if(text === "roll"){
				$(".gusset").hide();
				$(".option").hide();
				$(".quantity_in").show();
				$(".heightb").html("Repeat Length");
				$("#btn_generate").attr('name','btn_rgenerate');
				//setQuantityHtml('r');
			}else{*/
				$(".gusset").show();
				$(".option").show();
				$(".heightb").html("Height");
				$("#btn_generate").attr('name','btn_generate');
				//setQuantityHtml('p');
			//}
			checkGusset();
		});
		
	
		//setQuantityHtml('p');
		
function checkGusset(){
	//alert("hui");
	
	var gusset_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=checkGussets', '',1);?>");
	//alert(gusset_url);
	
	$.ajax({
		method : 'post',
		url: gusset_url,
		data:'product_id='+$('#product').val(),
		success: function(response) {
			//alert(response);
			if(response==1){
				$('.gusset').show();	
			}else{
				$('.gusset').hide();
			}
		}
	});
	
}
</script>

<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

           

<script>
var count=0;
var product_count = 0;
    
		
		
		

/*function reloadPage(){
	location.reload();
}
*/

$("#same-above").click(function(){
	$("#loading").show();
	if($(this).prop('checked') == true){
		$("#billing-details").slideUp('slow');
	}else{
		$("#billing-details").slideDown('slow');
	}
	//$("#cinfo").slideUp().html(chtml).slideDown();
	$("#loading").fadeOut();
});


function removeProduct(order_product_id){
	
	var remove_product_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=removeProduct', '',1);?>");
	$.ajax({
		url : remove_product_url,
		method : 'post',
		data : {order_product_id : order_product_id},
		success: function(response){
			$('#display-product').html(response);
			$('#more-img-div').html('');
			//$('#display-image-1 img').remove();
			$('#display-image-1 img').attr('src','<?php echo HTTP_SERVER.'images/blank-user64x64.png'; ?>');
			product_count--;
			if(product_count<=0){
				$('#footer-div').hide();	
			}
			//$("#order-form")[0].reset();
			//$('#order-form').trigger("reset
		},
		error: function(){
			return false;	
		}
	});
}

$('#btn-add-product').click(function(){
	
	//$("#add-product-div").find('input').val('');
	//$("#add-product-div").find('select').prop('selectedIndex','0');
	
	//return false;
	//alert("hi");
	if($("#order-form").validationEngine('validate')){
          
		var add_product_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=addHistory', '',1);?>");
		//alert(add_product_url);
		var str = $("form").serialize();
		//alert($('.valve').val());
		$.ajax({
			url : add_product_url,
			method : 'post',
		//	dataType:'json',
      		 data:{str:str},	
			//data :$('#order-form input,#order-form select,#order-form textarea'),
			success: function(response){
				//alert(response);
				var val = $.parseJSON(response);
				//if(response != 0){
				   $('#display-product').html(val.response);
				   $('#templateid').val(val.result);
				   $('#more-img-div').html('');
					//$('#display-image-1 img').remove();
				   $('#display-image-1 img').attr('src','<?php echo HTTP_SERVER.'images/blank-user64x64.png'; ?>');
				   //$("#add-product-div").find('input').val('');
				   $("#add-product-div").find('textarea').val('');
				   //$("#add-product-div").find('select').prop('selectedIndex','0');
				   $('.file-preview').empty().hide();
				   $('#footer-div').show();
				   $('#make').attr('checked', 'checked');
				   product_count++;
				//}
			},
			error: function(){
				//$("html, body").animate({scrollTop:0},600);
				return false;
			}
		});
	}else{
		return false;
	}
});

$('.media-body').on('change','#art-image',function(){
	
	count += 1;
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajaximage', '',1);?>");
	$('#loading').show();
	var img_html = '';
	var file_data = $("#art-image").prop("files")[0];          // Getting the properties of file from file field
	//var file_data = $("#art-image").val();
	var form_data = new FormData();                            // Creating object of FormData class
	form_data.append("file", file_data)              			// Appending parameter named file with properties of file_field to form_data
	form_data.append("product_id", $('#product').val())        // Adding extra parameters to form_data
	form_data.append("image_id",count)
	$.ajax({
		url: url,
		dataType: 'script',
		cache: false,
		contentType: false,
		processData: false,
		data: form_data,                         // Setting the data attribute of ajax with file_data
		type: 'post',
		success : function(response){
			if(response!=0){
				
				img_html += '<div id="preview-'+count+'" class="file-preview-frame">';
                  img_html +='<img class="file-preview-image" src="'+JSON.parse(response)+'">';
                  img_html += '<a class="iremove" href="javascript:void(0);" onClick="removeImage('+count+')">Remove</a>';      
                img_html += '</div>';
				
				$('.file-preview').show();
				$('.file-preview-thumbnails').append(img_html);
				//s$('#display-image-'+count+' img').attr('src',JSON.parse(response));
				$('#loading').remove();
			}else{
				$('#loading').remove();
			}
		}
   });
});

var die_count = 0;

$('.media-body').on('change','#die-line',function(){
	
	die_count += 1;
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=uploadDieLine', '',1);?>");
	//$('#loading').show();
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
			
			if(typeof response.ext != 'undefined'){
				if(response.ext == 'img'){
					
					
					die_html = '<div id="die-preview-'+count+'" class="file-preview-frame">';
					  die_html +='<img class="file-preview-image" src="'+response.name+'">';
					  die_html += '<a class="iremove" href="javascript:void(0);" onClick="removefile('+die_count+')">Remove</a>';      
					die_html += '</div>';
					
					$('.file-preview-die').show();
					$('.file-preview-thumbnails-die').append(die_html);
					//s$('#display-image-'+count+' img').attr('src',JSON.parse(response));
					$('#loading').remove();
					
					
				}else{
					
					die_html = '<div id="die-preview-'+die_count+'" class="file-preview-frame">';
					  die_html +='<img class="file-preview-image" src="<?php echo HTTP_SERVER .'images/pdf_image.jpg'; ?>">';
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
					//s$('#display-image-'+count+' img').attr('src',JSON.parse(response));
					$('#loading').remove();
					
					/*die_html = '<div class="input-group media-body_1" id="die-preview-'+die_count+'">';			
					   die_html +='<a style="text-align:left;" class="btn btn-default btn-block">'+response+'</a>';			
					   die_html +='<span class="input-group-btn">';				
						  die_html +='<a onclick="removefile('+die_count+')" class="btn btn-danger remove_i"><i class="fa fa-times"></i></a>';
					   die_html +='</span>';		
					die_html += '</div>';
					
					$('#append-dieline').append(die_html);
					//s$('#display-image-'+count+' img').attr('src',JSON.parse(response));
					$('#loading').remove();*/
				}
			}else{
				$('#loading').remove();
				set_alert_message('Only .pdf And .jpg Formate Allow','alert-danger','fa fa-warning');
			}
			
			/*if(response!=0){
				
				die_html = '<div class="input-group media-body_1" id="die-preview-'+die_count+'">';			
                   die_html +='<a style="text-align:left;" class="btn btn-default btn-block">'+response+'</a>';			
                   die_html +='<span class="input-group-btn">';				
                   	  die_html +='<a onclick="removefile('+die_count+')" class="btn btn-danger remove_i"><i class="fa fa-times"></i></a>';
                   die_html +='</span>';		
                die_html += '</div>';
				
				
				$('#append-dieline').append(die_html);
				//s$('#display-image-'+count+' img').attr('src',JSON.parse(response));
				$('#loading').remove();
			}else{
				$('#loading').remove();
				set_alert_message('Only .pdf Formate Allow','alert-danger','fa fa-warning');
			}*/
		}
   });
});

function removeImage(count){
	
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=removeImage', '',1);?>");
	$('#loading').show();
	
	$.ajax({
		url: url,
		data: {image_id : count},       
		type: 'post',
		success : function(){
			$('#loading').remove();
			$('#preview-'+count).remove();
			
			if($('.file-preview .file-preview-thumbnails').children().size()==0){
				$('.file-preview').css('display','none');	
			}
		}
	});
}

function removefile(count){
	
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=removeFile', '',1);?>");
	$('#loading').show();
	
	$.ajax({
		url: url,
		data: {die_id : count},       
		type: 'post',
		success : function(){
			$('#loading').remove();
			$('#die-preview-'+count).remove();
			
			if($('.file-preview-die .file-preview-thumbnails-die').children().size()==0){
				$('.file-preview-die').css('display','none');	
			}
			
		}
	});
}

$('.addmore-image').click(function(){
	
	var total_count = parseInt( $(".more_image").size()) + 1;
	
	var html = '';	
	
	html += '<div class="row" style="margin-top:10px;" id="image-row-'+total_count+'">';
	
		html += '<div class="col-lg-9 media more_image">';  
		  html +=  '<div class="bg-light pull-left text-center media-large thumb-large" id="display-image-'+total_count+'">';
				html +='<img src= "<?php echo HTTP_SERVER.'images/blank-user64x64.png'; ?>" class="img-rounded" alt="">';
		  html += '</div>';
		  
		  html += '<div class="media-body">';
		    html += '<input type="file" name="art_image" id="art_image_'+total_count+'" title="Change" class="btn btn-sm btn-info m-b-small" />';	  
			html += '<button type="button" onClick="uploadImage('+total_count+')" class="btn btn-success btn-xs"><i class="fa fa-upload"></i> Upload</button>';
		  html += '</div>';
	    html +='</div>';
		
	   html +='<div class="col-lg-3">';
		 html +='<a class="btn btn-danger btn-xs btn-circle" data-toggle="tooltip" data-placement="top" onClick="removeImage('+total_count+');" title="Remove Image" ><i class="fa fa-minus"></i></a>';
	   html +='</div>';
	
   html +='</div>';
   
   $('#more-img-div').append(html);
	
});

</script> 
<!-- Close : validation script -->

<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>