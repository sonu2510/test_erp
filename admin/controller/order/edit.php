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
	'text' 	=> $display_name.' Edit',
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
$allow_currency_status = $obj_order->allowCurrencyStatus($user_type_id,$user_id);

//Start : edit
$edit = '';
if(isset($_GET['order_id']) && !empty($_GET['order_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$order_id = base64_decode($_GET['order_id']);
		//echo $order_id;
		//die;
		
		$data = $obj_order->getOrder($order_id,$obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);
		
		$currency_data = $obj_order->getOrderCurrency($order_id);

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
        
      <div class="col-sm-12">
        
            <section class="panel">  
            	
                <header class="panel-heading bg-white">
                 <span>Order Detail</span> 
                </header>
              
              <div class="panel-body">
              	<label class="label bg-white m-l-mini">&nbsp;</label>
                	
                 <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Order Number</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo ucwords($data['order_number']);?>
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
                        <label class="col-lg-3 control-label">Order Type</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo ucwords($data['order_type']);?>
                            </label>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Company Name</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo ucwords($data['company_name']);?>
                            </label>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Email</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo ucwords($data['email']);?><br/>
                           
                            <small class="text-muted"><?php echo "Contact Number: " .$data['contact_number']; ?></small>
                            </label>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Shipping Address</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo ucwords($data['address'])." ,".$data['city']." ,".$data['state'];?>
                            </label>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Order Note</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            	<?php echo $data['order_note']; ?>                                
                            </label>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Order Instruction</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            	<?php echo $data['order_instruction']; ?>
                            </label>
                        </div>
                      </div>
                      	
                      <?php 
					  	$order_products = $obj_order->getOrderProducts($order_id);
						//printr($order_products);
						//if($order_products )
						
					 ?>                       
					<div class="col-lg-12"><h4><i class="fa fa-tags"></i> Product Details</h4>
                           <div class="line m-t-large" style="margin-top:-4px;"></div><br/>
                    <div class="form-group">
                        <label class="col-lg-3 control-label"> Product Name</label>
                        <div class="col-lg-9">
                            <label class="control-label normal-font">
                             <?php echo $order_products[0]['product_name']; ?>
                            </label>
                        </div>
                    </div>
					<div class="form-group">
						<div class="table-responsive">
							<table class="table-striped b-t table-color col-lg-12">
								<thead>
									<tr>
                                    	<th>Art Work</th>

                                        <th>Die Line</th>

                                    	<th>Transportation</th>
                                    	<th>Quntity</th>
                                        <th>Option(Printing Effect )</th>
                                        <th>Dimension (Make Pouch)</th>
                                        <th>Layer:Material:Thickness</th>
                                        <th>Action</th>
                                                                         
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $i=0; ?>
                                <?php foreach($order_products as $order_product){
									$i++;

								$order_product_materials = $obj_order->getOrderProductMaterials($order_product['order_product_id']);
								$TotalPrice = $order_product['total_price']+$order_product['gress_price'];
								//printr($order_product);
								?>                                
                                	<tr>
                                    	
                            <!-- .crousel slide -->
						 <td> <?php 
						// printr($order_product_images);
						 $order_product_images = $obj_order->getOrderProductImages($order_product['order_product_id']);
						 $html = '';			
							 if(isset($order_product_images) && !empty($order_product_images)) {
						  $html .='<div class="carousel slide auto" id="c-slide-'.$order_product['order_product_id'].'">
							<ol class="carousel-indicators out">';
							 for($j=0;$j<(count($order_product_images));$j++){ 
							 	$html .='<li data-target="#c-slide-'.$order_product['order_product_id'].'" data-slide-to="'.$j.'" class=""></li>';
							 }
						$html .='</ol>';
						$html .='<div class="carousel-inner" id="carousel-inner_'.$order_product['order_product_id'].'">';
							 $i=0;
							  
							foreach($order_product_images as $image){ 
								if($i==0){
									$html .=' <div class="item active id_'.$order_product['order_product_id'].'" id="item">';
								}else{
									$html .=' <div class="item id_'.$order_product['order_product_id'].'" id="item">';
								}
									$html .='<p class="text-center" id="text-center_'.$order_product['order_product_id'].'"><img class="" alt="Image" width="100" height="100" src="'.HTTP_UPLOAD.'admin/artwork/100_'.$image['image_name'].'"></p>';
								$html .='</div>';
								$i++;
							}
						$html .='</div>
							<a class="left carousel-control" style="width:15px;" href="#c-slide-'.$order_product['order_product_id'].'" data-slide="prev"> <i class="fa fa-chevron-left"></i> </a>
							<a class="right carousel-control" style="width:15px;" href="#c-slide-'.$order_product['order_product_id'].'" data-slide="next"> <i class="fa fa-chevron-right"></i> </a> </div>';
						echo $html;
							 } else {
								 echo '
								 <div class="carousel slide auto" id="c-slide-'.$order_product['order_product_id'].'">
								 <div class="carousel-inner" id="carousel-inner_'.$order_product['order_product_id'].'">
								 <div class="item active" id="item">
								 <p class="text-center">
<img class="" width="100" height="100" src="'.HTTP_UPLOAD.'admin/artwork/blank.jpg" alt="Image">
</p>
								</div>
								</div>
								<a class="left carousel-control" style="width:15px;" href="#c-slide-'.$order_product['order_product_id'].'" data-slide="prev"> <i class="fa fa-chevron-left"></i> </a>
							<a class="right carousel-control" style="width:15px;" href="#c-slide-'.$order_product['order_product_id'].'" data-slide="next"> <i class="fa fa-chevron-right"></i> </a>
								</div>';
								
							 }
							 
						 
						?>
                        <div class="col-lg-9">
                        	<div class="art">
                            	<input type="file" name="art_image2_<?php echo $order_product['order_product_id']; ?>" id="art-image2_<?php echo $order_product['order_product_id']; ?>" title="<i class='fa fa-folder-open-o'></i> Browse" class="btn btn-sm btn-info m-b-small" onchange="add_images(<?php echo $order_product['order_product_id']; ?>)">
                            </div>
                            <div class="artwork_images" id="artid_<?php echo $order_product['order_product_id']; ?>"></div>
                        </div>
                                
                        </td>
                        
                                        <td><?php
                            	$order_product_die_lines = $obj_order->getOrderProductDieLines($order_product['order_product_id']);
								//printr($order_product_die_lines);
								$html = '';
						 
						if(isset($order_product_die_lines) && !empty($order_product_die_lines)) {
						  $html .='<div class="carousel slide auto" id="c-slide-'.$order_product_die_lines[0]['order_product_die_line_id'].'">
							<ol class="carousel-indicators out">';
							 for($j=0;$j<(count($order_product_die_lines));$j++){ 
							 	$html .='<li data-target="#c-slide-'.$order_product_die_lines[0]['order_product_die_line_id'].'" data-slide-to="'.$j.'" class=""></li>';
							 }
						$html .='</ol>';
						$html .='<div class="carousel-inner" id="dieline-carousel-inner_'.$order_product['order_product_id'].'">';
							 $i=0;
							  //printr($order_product_die_lines);
							foreach($order_product_die_lines as $keys=>$image){ 
								if($i==0){
									$html .=' <div class="item active ">';
								}else{
									$html .=' <div class="item">';
								}
									$html .='<p class="text-center"><img class="" alt="Image" width="100" height="100" src="'.HTTP_UPLOAD.'admin/artwork/100_'.$image['name'].'"></p>';
								$html .='</div>';
								$i++;
							}
						$html .='</div>
							<a class="left carousel-control" style="width:15px;" href="#c-slide-'.$order_product_die_lines[0]['order_product_die_line_id'].'" data-slide="prev"> <i class="fa fa-chevron-left"></i> </a>
							<a class="right carousel-control" style="width:15px;" href="#c-slide-'.$order_product_die_lines[0]['order_product_die_line_id'].'" data-slide="next"> <i class="fa fa-chevron-right"></i> </a> </div>';
						echo $html;
						} else {
								 echo '<div class="carousel slide auto" id="c-slide-'.$order_product['order_product_id'].'_1">
								 <div class="carousel-inner" id="dieline-carousel-inner_'.$order_product['order_product_id'].'">
								 <div class="item active">
								 <p class="text-center">
<img class="" width="100" height="100" src="'.HTTP_UPLOAD.'admin/dieline/blank.jpg" alt="Image">
</p>
								</div>
								</div>
								<a class="left carousel-control" style="width:15px;" href="#c-slide-'.$order_product['order_product_id'].'_1" data-slide="prev"> <i class="fa fa-chevron-left"></i> </a>
							<a class="right carousel-control" style="width:15px;" href="#c-slide-'.$order_product['order_product_id'].'_1" data-slide="next"> <i class="fa fa-chevron-right"></i> </a> </div>';
							 }
						 
						?>
                        <div class="col-lg-9">
                                	
                                    <div class="die">
                                        	<input type="file" name="die_image_<?php echo $order_product['order_product_id']; ?>" id="die_image_<?php echo $order_product['order_product_id']; ?>" onchange="add_dieline_images(<?php echo $order_product['order_product_id']; ?>)" title="<i class='fa fa-folder-open-o'></i> Browse" class="btn btn-sm btn-info m-b-small">
                                    </div>
                                    <div class="deiline_images" id="dieid_<?php echo $order_product['order_product_id']; ?>"></div>
                                    
                                    <div class="file-preview-die" style="display:none">
                                       <div class="file-preview-thumbnails-die">
                                            
                                       </div>
                                       <div class="clearfix"></div>
                                    </div>
                                    
                                    <div id="append-dieline"></div>                                    
                                </div>
                           </td>
                           
                           <?php //printr($order_product); ?>
                                    	<th><?php echo (ucwords(decode($order_product['transport_type']))); ?></th>
                                        <th><?php echo ucwords($order_product['quantity']); ?></th>
                                        <td><?php echo ucwords($order_product['zipper_txt']).'<br>'.ucwords($order_product['valve_txt']).'<br>'.ucwords($order_product['spout_txt']).'<br>'.ucwords($order_product['accessorie_txt']).' ('.ucwords($order_product['printing_effect']).')'; ?></td>
                                        <td><?php echo $order_product['width'].'X'.$order_product['height'].'X'.$order_product['gusset']; ?></td>
                                        <td>
										<?php foreach($order_product_materials as $order_product_material){
											//printr($order_product_material);
											?>
											<?php echo '<b>'.$order_product_material['layer'].' Layer :</b>'.' '.$order_product_material['material_name'].': '.floatval($order_product_material['material_thickness']).'<br>'; ?>

										<?php } ?>
                                        </td>
                                        <td>
                        	 	<div class="">
                      	  			<button type="button" name="update_img" onclick="update_order(<?php echo $order_product['order_product_id']; ?>);" id="update_img" class="btn btn-primary" style="display:inline">Update</button>
                        		</div>
                      		</td>
                                        
                                    </tr>
                                    <!-- For ArtWork Images-->
                                    <tr>
                                    <td colspan="7">
                                    <div class="file-preview_<?php echo $order_product['order_product_id']; ?>" style="display:none">
                                    
                                   		<div class="file-preview-thumbnails_<?php echo $order_product['order_product_id']; ?>">
                                            
                                		</div>
                                   		<div class="clearfix"></div>
                                   		<div class="file-preview-status text-center text-success"></div>
                                   		<div class="kv-fileinput-error file-error-message" style="display: none;"></div>
                                	</div>
                                    </td>
                                    </tr>
                                    <!-- For Dieline Images-->
                                    <tr>
                                    <td colspan="7">
                                    <div class="dieline-preview_<?php echo $order_product['order_product_id']; ?>" style="display:none">
                                    
                                   		<div class="dieline-preview-thumbnails_<?php echo $order_product['order_product_id']; ?>">
                                            
                                		</div>
                                   		<div class="clearfix"></div>
                                   		<div class="file-preview-status text-center text-success"></div>
                                   		<div class="kv-fileinput-error file-error-message" style="display: none;"></div>
                                	</div>
                                    </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                  </div>
                  
                    
                  
                   
                    	
                    		
                      
                    <div class="form-group">
                       <div class="col-lg-9 col-lg-offset-0">             
                          <a class="btn btn-default" href="<?php echo $obj_general->link($rout,'', '',1);?>">Cancel</a>
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

<script src="<?php echo HTTP_SERVER;?>js/lightbox/js/lightbox.min.js"></script>
<link href="<?php echo HTTP_SERVER;?>js/lightbox/css/lightbox.css" rel="stylesheet" />



<script>

$("#artwork_images").hide();
$(".artwork_images").hide();
//add artwork images
var count=0;
function add_images(id)
{
//alert(id);
	var row_id = id
	count += 1;
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajaximageArtwork', '',1);?>");
	$('#loading').show();
	var img_html = '';
	var file_data = $('#art-image2_'+row_id).prop("files")[0];   
	//alert(file_data);       // Getting the properties of file from file field
	//var file_data = $("#art-image").val();
	var form_data = new FormData();                            // Creating object of FormData class
	form_data.append("file", file_data)              			// Appending parameter named file with properties of file_field to form_data
	form_data.append("order_id", row_id)        // Adding extra parameters to form_data
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
				$("#artwork_images").show();
				$(".artwork_images").show();
				//$(".carousel-indicators").hide();
					//$("#item").removeClass();
					//$("#item").addClass("item id_'"+row_id+"'");
				
			//	var NAME = document.getElementById("item");
				//var currentClass = NAME.className;
					
				//alert(currentClass);
				//$( "."+currentClass+"" ).addClass("item id_'"+row_id+"'");
				
				//$( ".item" ).last().addClass( "item active" );
				/*if (currentClass == "item active id_'+row_id+'") { // Check the current class name
					NAME.className = "item";   // Set other class name					
				} else {
					//NAME.className = "item ";  // Otherwise, use `second_name`
				}*/
				//alert($( ".item" ).last());
				//$( ".item" ).last().addClass( "item active" );
				//$( ".item" ).last().addClass( "item active" );
				//img_html += '<div id="preview-'+count+'" class="file-preview-frame">';
				//img_html +='<div id="item" class="item active">';
				img_html +='<p class="text-center">';
                img_html +='<img class="" width="50" height="50" src="'+JSON.parse(response)+'">';
				img_html +='</p>';
                // img_html += '<a class="iremove" href="javascript:void(0);" onClick="removeImage('+count+')">Remove</a>';      
                // img_html += '</div>';
				
				//$('.file-preview_'+row_id).show();
				//$('.file-preview-thumbnails_'+row_id).append(img_html);				
				$('#artid_'+row_id).html(img_html);
			//	$( ".item" ).last().addClass( "item active" );
				//s$('#display-image-'+count+' img').attr('src',JSON.parse(response));
				$('#loading').remove();
			
				
			}else{
				$('#loading').remove();
			}
		}
   });	
}
//add dieline images
$("#deiline_images").hide();
$(".deiline_images").hide();
var die_count = 0;
function add_dieline_images(id) {
	die_count += 1;
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajaximageDieline', '',1);?>");
	$('#loading').show();
	var img_html = '';
	var file_data = $("#die_image_"+id).prop("files")[0];          // Getting the properties of file from file field
	//var file_data = $("#art-image").val();
	var form_data = new FormData();                            // Creating object of FormData class
	form_data.append("file", file_data)              			// Appending parameter named file with properties of file_field to form_data
	form_data.append("order_id", id)        // Adding extra parameters to form_data
	form_data.append("die_id",die_count)
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
				$("#deiline_images").show();
				$(".deiline_images").show();
				//img_html += '<div id="preview-'+count+'" class="file-preview-frame">';
				//img_html +='<div class="item">';
				img_html +='<p class="text-center">';
                img_html +='<img class="" width="50" height="50" src="'+JSON.parse(response)+'">';
                //img_html += '<a class="iremove" href="javascript:void(0);" onClick="removeImage('+count+')">Remove</a>';      
                img_html += '</p>';
				
				//$('.dieline-preview_'+id).show();
				//$('.dieline-preview-thumbnails_'+id).append(img_html);
				$('#dieid_'+id).html(img_html);
				//s$('#display-image-'+count+' img').attr('src',JSON.parse(response));
				$('#loading').remove();
			}else{
				$('#loading').remove();
			}
		}
   });
}
//remove selected imagess
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
//update selected images by product
function update_order(id){
	var order_product_id = id;	
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=updateOrder', '',1);?>");			
	$.ajax({
			method: "POST",
			url: url,
			data : {order_product_id : order_product_id},
			success: function(response){
				set_alert_message('Order successfully updated','alert-success','fa fa-check');
				setTimeout(function () { // wait 1 seconds and reload
					location.reload();
				}, 700);
			},
			error: function(){
				return false;
			}
		});	
}
</script>



<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>


<!-- Close : validation script -->

<?php } else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>