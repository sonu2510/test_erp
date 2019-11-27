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

//Start : edit
$edit = '';
if(isset($_GET['product_id']) && !empty($_GET['product_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$product_id = base64_decode($_GET['product_id']);
		$product = $obj_tool->getProduct($product_id);
		//printr($product);
		$edit = 1;
	}
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}
//Close : edit

if($display_status){
	//insert user
	if(isset($_POST['btn_save'])){
		$post = post($_POST);		
	//	printr($post);die;
		$insert_id = $obj_tool->addTool($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		//printr($post);die;
		$product_id = $product['product_id'];
		$obj_tool->updateTool($product_id,$post);
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
        
      <div class="col-sm-9">
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
              <?php } ?>
              
              <table border="0"  width="100%" class="table  b-t text-small">
              	<tr>
                	<td> <b>Width(from)</b> </td>
                   <td><b>Width(to)</b></td>
                   <td><?php if(isset($product) && $product['gusset_available']== 1) {?><b>Gusset</b><?php }?></td>
                   <td><b>Price</b></td>
                   <td></td>
                </tr>
               <tr><td colspan="5">
               <table class="tool-row table  b-t text-small" id="myTable">
                	<?php 
							 $quantity_tool_prices = $obj_tool->getToolPrices($product_id);
							 if($quantity_tool_prices){
							   $inner_count = 0;
							   foreach($quantity_tool_prices as $quantity_tool_price){
							?>	 <tr><td>
                                        	 <input type="text" name="product_details[<?php echo $product_id; ?>][<?php echo $inner_count; ?>][width_from]" value="<?php echo $quantity_tool_price['width_from']; ?>" class="form-control validate[required,custom[number]]" placeholder="Width">
                                    </td>
                                    <td>
                                    	 <input type="text" name="product_details[<?php echo $product_id; ?>][<?php echo $inner_count; ?>][width_to]" value="<?php echo $quantity_tool_price['width_to']; ?>" class="form-control validate[required,custom[number]]" placeholder="width_to">
                        <input type="hidden" name="product_id" id="product_id" value="<?php echo $product_id; ?>" />
                         <input type="hidden" name="gusset_avl" id="gusset_avl" value="<?php echo $product['gusset_available']; ?>" />
                         </td>
                                   <?php if(isset($product) && $product['gusset_available']== 1) {?> <td>
                                        <input type="text" name="product_details[<?php echo $product_id; ?>][<?php echo $inner_count; ?>][gusset]" value="<?php echo $quantity_tool_price['gusset']; ?>" class="form-control validate[required,custom[number]]" placeholder="Gusset">
                                  </td><?php }?>
                                    <td>
                                        <input type="text" name="product_details[<?php echo $product_id; ?>][<?php echo $inner_count; ?>][price]" value="<?php echo $quantity_tool_price['price']; ?>" class="form-control validate[required,custom[number]]" placeholder="Price">
                                        
                                  </td>
                                    
                                    <?php if($inner_count==0){ ?>
                                     <td>
                                            <a class="btn btn-success btn-xs btn-circle addmore" data-toggle="tooltip" data-placement="top" title="Add Profit" ><i class="fa fa-plus"></i></a>
                                   </td>
                                    <?php } else { ?>
                               <td>
                                          <a class="btn btn-danger btn-xs btn-circle remove" data-toggle="tooltip" data-placement="top" title="Remove" ><i class="fa fa-minus"></i></a>
                                </td></tr>
                                    <?php } ?>
                              
                                <?php $inner_count++; } } else { ?>
                                
                          <tr>  <td>
                                        	<input type="text" name="product_details[<?php echo $product_id; ?>][0][width_from]" class="form-control validate[required,custom[number]]" placeholder="Width" value="">
                                      </td>
                                    <td>
                                        	<input type="text" name="product_details[<?php echo $product_id; ?>][0][width_to]" class="form-control validate[required,custom[number]]" placeholder="Width" value="">
                                     <input type="hidden" name="gusset_avl" id="gusset_avl" value="<?php echo $product['gusset_available']; ?>" />
                                    <input type="hidden" name="product_id" id="product_id" value="<?php echo $product_id; ?>" />
                                 </td>
                                 <?php if(isset($product) && $product['gusset_available']== 1) {?>
                                    <td>
                                        <input type="text" name="product_details[<?php echo $product_id; ?>][0][gusset]" value="" class="form-control validate[required,custom[number]]" placeholder="Gusset">
                                   </td>
                                   <?php }?>
                                    <td>
                                        <input type="text" name="product_details[<?php echo $product_id; ?>][0][price]" value="" class="form-control validate[required,custom[number]]" placeholder="Price">
                                 </td>
                                    <td>
                                        <a class="btn btn-success btn-xs btn-circle addmore" data-toggle="tooltip" data-placement="top" title="Add Tool Price" ><i class="fa fa-plus"></i></a>
                                  </td></tr>
                                  
                                <?php } ?>
                                </table>
                     
                </td></tr>
              
                <tr>
                	<td colspan="6"><div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">           
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
		//alert($(this).parent().parent().attr('class'));
		//var quantity = $(this).parent().parent().parent().parent().find('input[type=text]').val();
		var product_id = $('#product_id').val();
		var gusset = $('#gusset_avl').val();
		//var count = $(this).parent().parent().parent().parent().children().size()+2;
		var count = $('#myTable tr').length;
		//alert(rowCount);
		

				html +='<tr><td>';
				  html += '<input type="text" name="product_details['+product_id+']['+count+'][width_from]" value="" class="form-control validate[required,custom[number]]" placeholder="Width">';
				html += '</td>';
				  
				html +='<td>';
				  html += '<input type="text" name="product_details['+product_id+']['+count+'][width_to]" value="" class="form-control validate[required,custom[number]]" placeholder="Width">';
				html +='</td>';
				
			if(gusset == 1)
			{
					html +='<td>';
			   html +='<input type="text" name="product_details['+product_id+']['+count+'][gusset]" value="" class="form-control validate[required,custom[number]]" placeholder="Gusset">';
			html +='</td>';
			}
			html +='<td>';
			   html +='<input type="text" name="product_details['+product_id+']['+count+'][price]" value="" class="form-control validate[required,custom[number]]" placeholder="Price">';
			html +='</td>';
			
			html +='<td>';
			   html +='<a class="btn btn-danger btn-xs btn-circle remove" data-toggle="tooltip" data-placement="top" title="Remove" ><i class="fa fa-minus"></i></a>';
			html +='</td></tr>';

		
		$('.tool-row').append(html);
		
		$(' .remove').click(function(){
			$(this).parent().parent().remove();
		});
		
	});
	
	$(' .remove').click(function(){
		$(this).parent().parent().remove();
	});
	
	</script> 
<!-- Close : validation script -->

<?php } else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>