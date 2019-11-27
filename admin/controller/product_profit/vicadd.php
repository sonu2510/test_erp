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
		//chnage
		if($product_id == '19')
			$product_id='4';
		//chnage End
		$product = $obj_profit->getProduct($product_id);
		
		//printr($product_id);
		
		
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
		//printr($post);die;
		$insert_id = $obj_profit->addProfit($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
	
		//$product_id = $product['product_id'];
		
		//chnage
			$product_id = base64_decode($_GET['product_id']);
		//chnage End
		//echo $product_id;die;
		
		$obj_profit->updateProfit($product_id,$post);
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
        
      <div class="col-sm-12">
        <section class="panel">
          <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
              <?php if($edit==1 && !empty($edit)){ ?>
                  <div class="form-group">
                      <label class="col-lg-3 control-label">Product Name</label>
                      <div class="col-lg-9">
                         <label class="control-label normal-font">
                           <?php echo $product['product_name'].'&nbsp;&nbsp;&nbsp;';
						   if($product_id == '7'){ echo '<b>Formula : (Width+Gusset+Gusset+15) X (Height+Gusset+15)</b>';}
						   elseif($product_id == '1'){ echo '<b>Formula : (Width+Gusset+Gusset+10) X Height </b>';}
						   elseif($product_id == '3'){ echo '<b>Formula : ((Width) X (Height+Gusset)) X 2</b>';}
						   elseif($product_id == '4' || $product_id == '10'){ echo '<b>Formula : ((Height X 2) + Flap + 50 Trim)</b>';}
						   elseif($product_id == '9'){ echo '<b>Formula : (((Width+10) + (Gusset+Gusset+10)) X 2 ) +35</b>';}
						   elseif($product_id == '5'){ echo '<b>Formula : (Width X 2) + 50</b>';} ?>
                         </label>   
                      </div>
                  </div>
              <?php } ?>
              
              
              <div class="form-group">
                <label class="col-lg-1 control-label"></label>
                <div class="col-lg-11">
                	<div class="row">
                		<div class="col-sm-2">
                    		<b>Quantity</b>
                    	</div>
                        
                        <div class="col-sm-10">  
                            <div class="col-lg-5">
                                <div class="row">
                                	<div class="col-lg-6">
                                    	<b>Size(from)</b>
                                    </div>
                                    
                                    <div class="col-lg-6">
                                    	<b>Size(to)</b>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <b>Profit</b>
                            </div>
                            <div class="col-lg-2">
                                <b>+/- Qty</b>
                            </div>
                             <div class="col-lg-2">
                                <b>Wastage Percentage</b>
                            </div>
                            <div class="col-lg-1">
                            
                        	</div>
                        </div>
                        
                   </div> 
                </div>
              </div>
              
              <div class="form-group more_price">
                <label class="col-lg-1 control-label"></label>
                <div class="col-lg-11">
				   <?php 
				     $quantity_data = $obj_profit->getQuantityData();
					 if($quantity_data) {
						$count = 0;  
					   foreach($quantity_data as $quantity){
					?>
                    <div class="row profit-row">
                      
                     
                        <div class="col-sm-2 parent-div">
                            <input type="text" readonly="readonly" name="product_details[<?php echo $quantity['quantity']; ?>][0][quantity]" value="<?php echo $quantity['quantity']; ?>" class="form-control validate[required,custom[number]]"><br />
                            <input type="hidden" name="product_details[<?php echo $quantity['quantity']; ?>][0][quantity_id]" value="<?php echo $quantity['product_quantity_id']; ?>" />
                            <?php 
							if($product_id == '10')
							{
							 	  echo '6 inch X 9 inch ( +Flap ) <br>';
								  echo '7.5 inch X 10.5 inch ( +Flap ) <br>';
								  echo '10 inch X 13 inch ( +Flap )<br>';
								  echo '12 inch X 15.5 inch ( +Flap )<br>';
								  echo '14 inch X 19 inch ( +Flap )';
							}
							?>
                        </div>
                     
                      
                      
                        <div class="col-sm-10 second-part">  
							
								<?php 
                                 $quantity_profit_prices = $obj_profit->getProfitPrices($product_id,$quantity['product_quantity_id']);
                                 if($quantity_profit_prices){
								   $inner_count = 0;
                                   foreach($quantity_profit_prices as $quantity_profit_price){
									   //printr($quantity_profit_price);
                                ?>	
                                <div class="row addtional-row">
                                    
                                    
                                    <div class="col-lg-5">
                                        <div class="col-lg-6">
                                        	<input type="text" name="product_details[<?php echo $quantity['quantity']; ?>][<?php echo $inner_count; ?>][size_from]" class="form-control validate[required,custom[number]]" placeholder="Size" value="<?php echo $quantity_profit_price['size_from']; ?>">
                                        </div>
                                        
                                        <div class="col-lg-6">
                                        	<input type="text" name="product_details[<?php echo $quantity['quantity']; ?>][<?php echo $inner_count; ?>][size_to]" class="form-control validate[required,custom[number]]" placeholder="Size" value="<?php echo $quantity_profit_price['size_to']; ?>">
                                        </div>
                                        
                                        <input type="hidden" name="product_details[<?php echo $quantity['quantity']; ?>][<?php echo $inner_count; ?>][quantity_id]" value="<?php echo $quantity['product_quantity_id']; ?>" />
                                    </div>
                                    
                                    <div class="col-lg-2">
                                        <input type="text" name="product_details[<?php echo $quantity['quantity']; ?>][<?php echo $inner_count; ?>][profit]" value="<?php echo $quantity_profit_price['profit']; ?>" class="form-control validate[required,custom[number]]" placeholder="Profit">
                                    </div>
                                    <div class="col-lg-2">
                                        <input type="text" name="product_details[<?php echo $quantity['quantity']; ?>][<?php echo $inner_count; ?>][plus_minus]" value="<?php echo $quantity_profit_price['plus_minus_quantity']; ?>" class="form-control validate[required,custom[number]]" placeholder="Qty">
                                    </div>
                                    <!-- [kinjal]: add for dynamic wastage[30 apr 2015(2:54pm)] -->
                                        <div class="col-lg-2">
                                         <input type="text" name="product_details[<?php echo $quantity['quantity']; ?>][<?php echo $inner_count; ?>][wastage]" value="<?php echo $quantity_profit_price['wastage_per'] ; ?>" class="form-control validate[required,custom[number]]" placeholder="Wastage Percentage">
                                        </div>
                                    <!-- [kinjal] end-->
                                    
                                    <?php if($inner_count==0){ ?>
                                        
                                      <div class="col-lg-1">
                                            <a class="btn btn-success btn-xs btn-circle addmore" data-toggle="tooltip" data-placement="top" title="Add Profit" ><i class="fa fa-plus"></i></a>
                                      </div>
                                        
                                    <?php } else { ?>
                                        
                                       <div class="col-lg-1">
                                          <a class="btn btn-danger btn-xs btn-circle remove" data-toggle="tooltip" data-placement="top" title="Remove" ><i class="fa fa-minus"></i></a>
                                       </div>
                                        
                                    <?php } ?>
                                </div>
                                <?php $inner_count++; } } else { ?>
                                
                                <div class="row">
                                    
                                    
                                    <div class="col-lg-5">
                                        <div class="col-lg-6">
                                        	<input type="text" name="product_details[<?php echo $quantity['quantity']; ?>][0][size_from]" class="form-control validate[required,custom[number]]" placeholder="Size" value="">
                                        </div>
                                        
                                        <div class="col-lg-6">
                                        	<input type="text" name="product_details[<?php echo $quantity['quantity']; ?>][0][size_to]" class="form-control validate[required,custom[number]]" placeholder="Size" value="">
                                        </div>
                                        
                                        <input type="hidden" name="product_details[<?php echo $quantity['quantity']; ?>][0][quantity_id]" value="<?php echo $quantity['product_quantity_id']; ?>" />
                                    </div>
                                    
                                    
                                    <div class="col-lg-2">
                                        <input type="text" name="product_details[<?php echo $quantity['quantity']; ?>][0][profit]" value="" class="form-control validate[required,custom[number]]" placeholder="Profit">
                                    </div>
                                  
                                    <div class="col-lg-2">
                                        <input type="text" name="product_details[<?php echo $quantity['quantity']; ?>][0][plus_minus]" value="" class="form-control validate[required,custom[number]]" placeholder="Qty">
                                    </div>
                                    
                                      <!-- [kinjal]: add for dynamic wastage[30 apr 2015(2:54pm)] -->
                                    <div class="col-lg-2">
                                        <input type="text" name="product_details[<?php echo $quantity['quantity']; ?>][0][wastage]" value="" class="form-control validate[required,custom[number]]" placeholder="Wastage Percentage">
                                    </div>
                                    <!--[kinjal] end -->
                                    
                                    <div class="col-lg-1">
                                        <a class="btn btn-success btn-xs btn-circle addmore" data-toggle="tooltip" data-placement="top" title="Add Profit" ><i class="fa fa-plus"></i></a>
                                    </div>
                                </div>
                                <?php } ?>
                         
                        </div>
                      
                    </div>
                    
                   
                    <br/>
                    
                    <?php $count++; ?>
                    <?php } } ?>
                </div>
			  </div>
              
              
              <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                <?php if($edit){?>
                  	<button type="submit" name="btn_update" id="btn_update" class="btn btn-primary">Update </button>
                <?php } else { ?>
                	<button type="submit" name="btn_save" id="btn_save" class="btn btn-primary">Save </button>	
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
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

<script>
    jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#form").validationEngine();
    });
	
	$('.profit-row .addmore').click(function(){
		var html = '';
		//alert($(this).parent().parent().attr('class'));
		var quantity = $(this).parent().parent().parent().parent().find('input[type=text]').val();
		var quantity_id = $(this).parent().parent().parent().parent().find('input[type=hidden]').val();
		var count = $(this).parent().parent().parent().children().size()+1;
		//alert(count);
		html += '<div class="row addtional-row">';
			
			html +='<div class="col-lg-5">';	
				html +='<div class="col-lg-6">';
				  html += '<input type="text" name="product_details['+quantity+']['+count+'][size_from]" value="" class="form-control validate[required,custom[number]]" placeholder="Size">';
				html += '</div>';
				  
				html +='<div class="col-lg-6">';
				  html += '<input type="text" name="product_details['+quantity+']['+count+'][size_to]" value="" class="form-control validate[required,custom[number]]" placeholder="Size">';
				html +='</div>';
				
				html +='<input type="hidden" name="product_details['+quantity+']['+count+'][quantity_id]" value="'+quantity_id+'" />';
			html +='</div>';
				
				
			html +='<div class="col-lg-2">';
			   html +='<input type="text" name="product_details['+quantity+']['+count+'][profit]" value="" class="form-control validate[required,custom[number]]" placeholder="Profit">';
			html +='</div>';
			
			html +='<div class="col-lg-2">';
			   html +='<input type="text" name="product_details['+quantity+']['+count+'][plus_minus]" value="" class="form-control validate[required,custom[number]]" placeholder="Qty">';
			html +='</div>';
			//[kinjal]: add for dynamic wastage[30 apr 2015(2:54pm)]
			html +='<div class="col-lg-2">';
			   html +='<input type="text" name="product_details['+quantity+']['+count+'][wastage]" value="" class="form-control validate[required,custom[number]]" placeholder="Wastage Percentage">';
			html +='</div>';
			//[kinjal] end
			html +='<div class="col-lg-1">';
			   html +='<a class="btn btn-danger btn-xs btn-circle remove" data-toggle="tooltip" data-placement="top" title="Remove" ><i class="fa fa-minus"></i></a>';
			html +='</div>';
		
		html +='</div>';
		$(this).parent().parent().parent().append(html);
		
		$('.addtional-row .remove').click(function(){
			$(this).parent().parent().remove();
		});
		
	});
	
	$('.addtional-row .remove').click(function(){
		$(this).parent().parent().remove();
	});
	
	</script> 
<!-- Close : validation script -->

<?php } else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>