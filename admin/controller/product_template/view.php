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
		//echo $template_id;
		//die;
		
		$data = $obj_template->getTempalte($template_id);
		$currency_data = $obj_template->getIBDetail($data[0]['user']);
		
		if($data[0]['userCurrencyPrice']!='0.00'){
		    $data[0]['userCurrencyPrice']=$data[0]['userCurrencyPrice'];
		    
		}else{
		    $data[0]['userCurrencyPrice']=$currency_data['product_rate'];
		}
	
	    $template_no = $obj_template->gettemplateNo($template_id);
		//die;
		//$currency_data = $obj_order->getOrderCurrency($order_id);
		//printr($template_no);//die;
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
                           
                            <span class="text-muted m-l-small pull-right">
                    	    Product Conversion Rate  : <b>	<?php echo $data[0]['userCurrencyPrice'];?></b>
                            </span> 
                           <span class="text-muted m-l-small pull-right">  <a class="label bg-success" href="javascript:void(0);" onclick="excellink('<?php echo rawurlencode($_GET['template_id']);?>')"><i class="fa fa-print"></i> Excel</a></span>
                          <?php //if($user_type_id==1 && $user_id==1) { ?>
                          <!--<span class="text-muted m-l-small pull-right">  <a class="label bg-danger" href="javascript:void(0);" onclick="excellinksmit()"><i class="fa fa-print"></i> Excel smit</a></span>-->
                           <?php //} ?>
                           
                           
                           <div class="line m-t-large" style="margin-top:-4px;"></div><br/>
                             
                             <div class="form-group">
                                <label class="col-lg-3 control-label">Template No.</label>
                                <div class="col-lg-4">
                                    <label class="control-label normal-font">
                                    
                                    <a href="<?php echo $obj_general->link('template_quotation', '&mod=view&quotation_id='.encode($template_no['multi_product_quotation_id']).'&filter_edit=1', '',1);?>" target="_blank"><?php echo $template_no['multi_quotation_number'];?></a>
                                    </label>
                                </div>
                              </div>   
                             
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
								<div class="col-lg-9 row">
                                	<div class="col-lg-4">
                                         <label class="control-label normal-font">
                            <?php echo $data[0]['product_name'];?>
                            </label>
                                    </div>
                                  
                                        
								</div>
							  </div>  
                          <div class ="form-group">
                           <label class="col-lg-3 control-label">Shipment Country</label>
                                    <div class="col-lg-9 row">
                                    <div class="col-lg-10">
                                      
                            <?php
							
							$countries=json_decode($data[0]['country']);
					  $str='';
					  foreach($countries as $country)
					  {
						  $str .= "country_id = ".$country." OR ";
					  }
					  $countryval = substr($str,0,-3);
					 $country_name = $obj_template->getmultiplecountry($countryval);
					   echo $country_name;?>
                           
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
									echo $data[0]['transportation_type'];
							?>
                            </label>
                                  </div>
                               </div>
                         </div>
                                                        
                      <div class="col-lg-12" id="add-product-div">
                           <h4><i class="fa fa-plus-circle"></i> Add Product</h4>
                        
                           
                           <div class="line m-t-large" style="margin-top:-4px;"></div><br/>
                        
                           <div class="form-group">
                                    
                                    <div class="table-responsive">
                                     <section class="panel">
                                     <table class="table table-striped b-t text-small">
              						<thead>
                 					 <tr>
                                        <th>Type Of Pouch</th>
                                        <th >Size</th>
                                         <th >Dimension<br />WxLxG</th>
										<?php if($data[0]['product_id'] == '18')
										{	?><th >Price (<?php echo $data[0]['currency_code']; ?>)<br >
                                            Qty100+
                                            </th>
                                             <th >Price (<?php echo $data[0]['currency_code']; ?>)<br >
                                           Qty200+
                                            </th>
                                             <th >Price (<?php echo $data[0]['currency_code']; ?>)<br >
                                           Qty500+
                                            </th>
                                             <th >Price (<?php echo $data[0]['currency_code']; ?>)<br >
                                            Qty1000+
                                        </th>
                                        
                                        <?php } else if($data[0]['product_id'] == '61')
										{ ?>
									        <th >Price (<?php echo $data[0]['currency_code']; ?>)<br >
                                                 Qty10000+
                                            </th>
                                             <th >Price (<?php echo $data[0]['currency_code']; ?>)<br >
                                                Qty15000+
                                            </th>
                                            <th >Price (<?php echo $data[0]['currency_code']; ?>)<br >
                                                 Qty20000+
                                            </th>
                                             <th >Price (<?php echo $data[0]['currency_code']; ?>)<br >
                                                Qty30000+
                                            </th>
                                            <th >Price (<?php echo $data[0]['currency_code']; ?>)<br >
                                                 Qty50000+
                                            </th>
                                             <th >Price (<?php echo $data[0]['currency_code']; ?>)<br >
                                                Qty100000+
                                            </th>
									  
									  <?php	}
									  else if($data[0]['product_id'] == '47' || $data[0]['product_id'] == '48')
										{ ?>
									        <th >Price (<?php echo $data[0]['currency_code']; ?>)<br >
                                                 Qty1000+
                                            </th>
                                             <th >Price (<?php echo $data[0]['currency_code']; ?>)<br >
                                                Qty2000+
                                            </th>
                                            <th >Price (<?php echo $data[0]['currency_code']; ?>)<br >
                                                 Qty5000+
                                            </th>
                                             <th >Price (<?php echo $data[0]['currency_code']; ?>)<br >
                                                Qty10000+
                                            </th>
                                            <th >Price (<?php echo $data[0]['currency_code']; ?>)<br >
                                                 Qty50000+
                                            </th>
                                             <th >Price (<?php echo $data[0]['currency_code']; ?>)<br >
                                                Qty100000+
                                            </th>
									  
									  <?php	}
									  
										else
										{?>
                                       
                                       <th >Price (<?php echo $data[0]['currency_code']; ?>)<br >
                                        Qty1000+
                                        </th>
                                         <th >Price (<?php echo $data[0]['currency_code']; ?>)<br >
                                       Qty2000+
                                        </th>
                                         <th >Price (<?php echo $data[0]['currency_code']; ?>)<br >
                                       Qty5000+
                                        </th>
                                         <th >Price (<?php echo $data[0]['currency_code']; ?>)<br >
                                        Qty10000+
                                        </th>
                                        <?php } ?>
                                        
                                       
										
                                         <th width="500px" >
                                       Color
                                        </th>
                                        <?php if($user_type_id==1 && $user_id==1)
										{
										?>
                                         <th width="500px" >
                                       Color Clone
                                        </th>
                                        <?php 
										}
										?>
                                      </tr>
                                        </thead>
                                        <tbody>
                                            
                                             <?php 
											 foreach($data as $dataval)
												{
													//printr($dataval);
													if($dataval['product_id'] == '18')
													{
														$qty100 = $dataval['quantity100'];
														$qty200 = $dataval['quantity200'];
														$qty500 = $dataval['quantity500'];
														$qty1000 = $dataval['quantity1000'];
													}
													else if($dataval['product_id'] == '61')
													{
    													$qty100 = $dataval['quantity10000'];
                                            			$qty200 = $dataval['quantity15000'];
                                            			$qty500 = $dataval['quantity20000'];
                                            			$qty1000 = $dataval['quantity30000'];
                                            			$qty2000 = $dataval['quantity50000'];
                                        				$qty3000 = $dataval['quantity100000'];
													}
													else if($dataval['product_id'] == '47' || $dataval['product_id'] == '48')
													{
    													$qty100 = $dataval['quantity1000'];
                                            			$qty200 = $dataval['quantity2000'];
                                            			$qty500 = $dataval['quantity5000'];
                                            			$qty1000 = $dataval['quantity10000'];
                                            			$qty2000 = $dataval['quantity50000'];
                                        				$qty3000 = $dataval['quantity100000'];
													}
													else
													{
														$qty100 = $dataval['quantity1000'];
														$qty200 = $dataval['quantity2000'];
														$qty500 = $dataval['quantity5000'];
														$qty1000 = $dataval['quantity10000'];
													}
												?>  <tr> 
												<?php	$spout='';
												$accessorie = '';
												if($dataval['spout'] != 'No Spout')
														{
															$spout = 'with '.$dataval['spout'];
														}
														if($dataval['accessorie'] != 'No Accessorie')
														{
															$accessorie = 'with '.$dataval['accessorie'];
														}
														?>
                                                <td><?php echo strtoupper(substr(preg_replace('/(\B.|\s+)/','',$data[0]['product_name']),0,3)).' '.$dataval['zipper'].' '.$dataval['valve'].'<br> '.$spout.' '.$accessorie; ?></td>  
                                                 <td><?php echo $dataval['volume']; ?></td> 
                                                  <td><?php echo $dataval['width'].'X'.$dataval['height'].'X'.$dataval['gusset']; ?></td> 
                                                   
                                                    <td><?php echo $qty100; ?></td> 
                                                     <td><?php echo $qty200; ?></td> 
                                                      <td><?php echo $qty500; ?></td> 
                                                       <td><?php echo $qty1000; ?></td> 
                                                      <?php if($dataval['product_id'] == '61' || $dataval['product_id'] == '47' || $dataval['product_id'] == '48')
                                                            {
                                                                echo '<td>'.$qty2000.'</td>';
                                                                 echo '<td>'.$qty3000.'</td>';
                                                            }
                                                            
                                                        ?>
                                                      <td width="500px"> 	<?php $color_detail = ''; $colorval=json_decode($dataval['color']); //printr($dataval); ?>
                                                        
                                                    <select  name="color" id="color" class="form-control" width="1000px">
														<?php foreach($colorval as $va)
														{
															
															$color_detail=$obj_template->getColor($va);
															// printr($color_detail); 
														?>
															<option value="<?php echo $color_detail[0]['pouch_color_id'];?>"><?php echo $color_detail[0]['color'];?></option>	
													<?php	}
													?>
													</select></td>
                                                     <?php if($user_type_id==1 && $user_id==1)
													{
													?>
                                                    <td><a href="javascript:void(0);" onclick="cloneColor(<?php echo 
													$dataval['product_template_size_id'];?>)"  name="btn_clone" class="btn btn-info btn-xs">Clone</a>
                                                   		
                                                        <a href="javascript:void(0);" onclick="PasteColor(<?php echo 
													$dataval['product_template_size_id'];?>)"  name="btn_clone" class="btn btn-info btn-xs">Paste</a>
                                                   		</td>
                                                     <?php 
													 }
													 ?>							
		 
                                                            </tr>
                                                           <?php
														  
													}
													
													?>
                                        </tbody>
                                     </table>
                                     </section>
                                    </div>
                                  </div>
                                 <?php /*?> <div>
									<?php printr($obj_session->data['clonecolor']); ?>
	                             </div><?php */?>
                             <div class="form-group" id="footer-div" >
                                <div class="col-lg-9 col-lg-offset-3">
                                 <button type="submit" name="btn_save" class="btn btn-primary">Save</button>
                                  <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a> 
                                  <?php if($user_type_id==1 && $user_id==1){?>
                                  <a class="btn btn-primary" href="<?php echo $obj_general->link($rout, 'mod=detail&template_id='.$_GET['template_id'], '',1);?>">Details</a> 
                                  <?php }?>
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
function excellink(id){
		var url = '<?php echo HTTP_SERVER.'word/product_temp_excel.php?mod='.encode('invoice').'&ext='.md5('php');?>&token='+id;
		window.open(url, '_blank');
	return false;
}

function excellinksmit()
{
	
	var add_product_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=smit_data', '',1);?>");
	 $.ajax({
        url: add_product_url, // the url of the php file that will generate the excel file
       	data : {},
		method : 'post',
        success: function(response){
		console.log(response);
			excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(response);
			 $('<a></a>').attr({
							'id':'downloadFile',
							'download': 'stock-report.xls',
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

<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>