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
	'text' 	=> $display_name.' List ',
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
//Start : edit
$edit = '';
if(isset($_GET['product_id']) && !empty($_GET['product_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$product_id = base64_decode($_GET['product_id']);
		$product = $obj_size->getProduct($product_id);
		$edit = 1;
	}
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}
//printr($product_id);
//Close : edit
if($display_status){
	//insert user
	if(isset($_POST['btn_save'])){
		$post = post($_POST);		
		$insert_id = $obj_tool->addTool($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		//printr($post);die;
		$product_id = $product['product_id'];
		$obj_size->updateTool($product_id,$post);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
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
      <div class="col-sm-11">
        <section class="panel">
          <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
            
              <?php if($edit==1 && !empty($edit)){ ?>
                  <div class="form-group">
                      <label class="col-lg-3 control-label">Product Name</label>
                      <div class="col-lg-9">
                         <label class="control-label normal-font">
                           <?php echo $product['product_name']; ?>
                         </label>   
                      </div>
                  </div>
              <?php } 
			  if($product_id=='10')
			  	$gusset='Flap';
			else
				$gusset='Gusset';
				?>
              <div class="table-responsive"> 
              <table border="0"  width="100%" class="table  b-t text-small">
              	<tr >
                	 <td align="center">&emsp;&emsp;&emsp;<b>Zipper</b> </td>
                  <td>&emsp;&emsp;</td>
 				  <td><b>Volume</b> </td>
                  <td><b>Width</b>&emsp;</td>
                  <td><b>Height</b>&emsp;</td>
                  <td><?php if(isset($product) && $product['gusset_available']== 1) { echo '<b>'.$gusset.'</b>'; }?>&emsp;&emsp;</td>
                  <td><?php if(isset($product) && $product['weight_available']== 1) {?>
                    <b>Weight</b>
                    <?php }?></td>
                   	<td></td>
                </tr>
               	<tr>
                	<td colspan="6">
              			<table class="tool-row table  b-t text-small" id="myTable">
                			<?php 
							 $quantity_tool_prices = $obj_size->getToolPrices($product_id);
							  $zipper = $obj_size->getZipper();
        							                   array_walk_recursive($zipper, function(&$item, $key){
        													if(!mb_detect_encoding($item, 'utf-8', true)){
        															$item = utf8_encode($item);
        													}
        												}); 
        												 
        												?>
                              
                              <?php
								 if($quantity_tool_prices){
									 $quantity_tool_prices =$quantity_tool_prices;
								 }
								 else
								 {
									$quantity_tool_prices[]= array(
											'size_master_id' => '',
											'product_id' => '',
											'product_zipper_id' => '',
											'volume' => '',
											'width' => '',
											'height' => '',
											'gusset' => '',
											'weight' => '',
											'date_added' => '',
											'date_modify' =>'',
											'status' => ''
										);
								}
								if($quantity_tool_prices){
							   		$inner_count = 0;
							   		foreach($quantity_tool_prices as $quantity_tool_price)
							   		{
									?>	 
                                   
                                    	<tr>
                                         <input type="hidden" name="product_details[<?php echo $product_id; ?>][<?php echo $inner_count; ?>][size_master_id]" value="<?php echo $quantity_tool_price['size_master_id'];?>" />
                              				<td><input type="hidden" id="zip_arr" value='<?php echo json_encode($zipper);?>' />
                           						<select name="product_details[<?php echo $product_id; ?>][<?php echo $inner_count; ?>][product_zipper_id]" class="form-control validate[required]" >
                                                    <option value="">Select Zipper</option>
                                                    <?php  foreach($zipper as $zip)
                                                       {
                                                           if($zip['product_zipper_id']==$quantity_tool_price['product_zipper_id'])
                                                            echo '<option value="'.$zip['product_zipper_id'].'" selected="selected">'.$zip['zipper_name'].'</option>';
                                                           else
                                                                echo '<option value="'.$zip['product_zipper_id'].'">'.$zip['zipper_name'].'</option>';
                                                       }
                                                       ?>
                           						</select>
                            				</td>
                           					<td>
                                        		<input type="text" name="product_details[<?php echo $product_id; ?>][<?php echo $inner_count; ?>][volume]" value="<?php echo $quantity_tool_price['volume']; ?>" class="form-control validate[required]" placeholder="Volume">
                                    		</td>
                                    		<td>
                                    	 		<input type="text" name="product_details[<?php echo $product_id; ?>][<?php echo $inner_count; ?>][width]" value="<?php echo $quantity_tool_price['width']; ?>" class="form-control validate[required]" placeholder="Width">
                         					</td>
                                    		<td>
                                        		<input type="text" name="product_details[<?php echo $product_id; ?>][<?php echo $inner_count; ?>][height]" value="<?php echo $quantity_tool_price['height']; ?>" class="form-control validate[required]" placeholder="Height">
                                         		<input type="hidden" name="product_id" id="product_id" value="<?php echo $product_id; ?>" />
                         						<input type="hidden" name="gusset_avl" id="gusset_avl" value="<?php echo $product['gusset_available']; ?>" />
                                                <input type="hidden" name="weight_avl" id="weight_avl" value="<?php echo $product['weight_available']; ?>" />
                                  </td> 
                                    <?php if(isset($product) && $product['gusset_available']== 1) {?>
                                    <td>
                                        <input type="text" name="product_details[<?php echo $product_id; ?>][<?php echo $inner_count; ?>][gusset]" value="<?php echo $quantity_tool_price['gusset']; ?>" class="form-control validate[required]" placeholder="<?php echo $gusset;?>">
                                  </td><?php }?>
                                  <?php if(isset($product) && $product['weight_available'] == 1) {?>
                                    <td>
                                        <input type="text" name="product_details[<?php echo $product_id; ?>][<?php echo $inner_count; ?>][weight]" value="<?php echo $quantity_tool_price['weight']; ?>" class="form-control validate[required]" placeholder="Weight">
                                  </td><?php }?>
                                    <?php if($inner_count==0){ ?>
                                     <td>
                                            <a class="btn btn-success btn-xs btn-circle addmore" data-toggle="tooltip" data-placement="top" title="Add Profit" ><i class="fa fa-plus"></i></a>
                                   </td>
                                    <?php } else { ?>
                               <td>
                                          <a class="btn btn-danger btn-xs btn-circle remove" data-toggle="tooltip" data-placement="top" title="Remove" onclick="remove_row(<?php echo $quantity_tool_price['size_master_id'];?>)"><i class="fa fa-minus"></i></a>
                                </td></tr>
                                    <?php } ?>
                              
                                <?php $inner_count++; } } ?>
                                </table>
                </td></tr>
                <tr>
                	<td colspan="6"><div class="form-group">
                        <div class="col-lg-9 col-lg-offset-3">  
                         <a class="btn btn-success btn-xs btn-circle addmore" data-toggle="tooltip" data-placement="top" title="Add Profit" ><i class="fa fa-plus"></i></a>         
                        <?php if($edit){?>
                            <button type="submit" name="btn_update" id="btn_update" class="btn btn-primary">Update </button>
                        <?php } else { ?>
                            <button type="submit" name="btn_save" id="btn_save" class="btn btn-primary">Save </button>	
                        <?php } ?>  
                          <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a>
                        </div>
                      </div>
                    </td>
                </tr>
              </table>
              </div>
            </form>
          </div>
        </section>
        
      </div>
    </div>
  </section>
</section>
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

<script>
    jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#form").validationEngine();
    });
	
	$(' .addmore').click(function(){
		var html = '';
		var product_id = $('#product_id').val();
		var gusset = $('#gusset_avl').val();
		var weight = $('#weight_avl').val();
		var count = $('#myTable tr').length;
		if(product_id == '10')
			var g = 'Flap';
		else
			var g = 'Gusset';
		var arr = jQuery.parseJSON($('#zip_arr').val());
	
				html +='<tr><td>';
				  html += '<select name="product_details['+product_id+']['+count+'][product_zipper_id]" class="form-control validate[required]" ><option value="">Select Zipper</option>';
				  for(var i=0;i<arr.length;i++)
				  {
				 html += '<option value="'+arr[i].product_zipper_id+'">'+arr[i].zipper_name+'</option>';
				}
				  html +=  '</select>';							
				html += '</td>';
				
				html +='<td>';
				  html += '<input type="text" name="product_details['+product_id+']['+count+'][volume]" value="" class="form-control validate[required]" placeholder="volume">';
				html += '</td>';
				  
				html +='<td>';
				  html += '<input type="text" name="product_details['+product_id+']['+count+'][width]" value="" class="form-control validate[required,custom[number]]" placeholder="Width">';
				html +='</td>';
				
			
					html +='<td>';
			   html +='<input type="text" name="product_details['+product_id+']['+count+'][height]" value="" class="form-control validate[required,custom[number]]" placeholder="Height">';
			html +='</td>';
			
			if(gusset == 1)
			{
					html +='<td>';
			   html +='<input type="text" name="product_details['+product_id+']['+count+'][gusset]" value="" class="form-control validate[required,custom[number]]" placeholder='+g+'>';
			html +='</td>';
			}
			
			if(weight == 1)
			{
					html +='<td>';
			   html +='<input type="text" name="product_details['+product_id+']['+count+'][weight]" value="" class="form-control validate[required,custom[number]]" placeholder="Weight">';
			html +='</td>';
			}
			
			html +='<td>';
			   html +='<a class="btn btn-danger btn-xs btn-circle remove" data-toggle="tooltip" data-placement="top" title="Remove"><i class="fa fa-minus"></i></a>';
			html +='</td></tr>';

		
		$('.tool-row').append(html);
		
		$('.remove').click(function(){
			$(this).parent().parent().remove();
		});
		
	});
	
	$('.remove').click(function(){
		$(this).parent().parent().remove();
	});
	
	function remove_row(size_master_id)
	{
		var remove_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=remove', '',1);?>");
			$.ajax({
				url : remove_url,
				method : 'post',
				data : {size_master_id : size_master_id},
				success: function(response){
					
				},
				error: function(){
					return false;	
				}
		});
	} 
	</script> 
<!-- Close : validation script -->

<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>