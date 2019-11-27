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
		$product = $obj_profit->getProduct($product_id);
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
		//printr($post);die;
		$insert_id = $obj_profit->addProfit($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		//printr($post);die;
		$product_id = $product['product_id'];
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
                           <?php echo $product['product_name'].'&nbsp;&nbsp;&nbsp;';if($product_id == '7'){ echo '<b>Formula : (Width+Gusset+Gusset+15) X (Height+Gusset+15)</b>';}?>
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
                                	<div class="col-lg-5">
                                    	<b>Size(from)</b>
                                    </div>
                                    
                                    <div class="col-lg-5">
                                    	<b>Size(to)</b>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-1">
                            
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
					   //printr($quantity);
					?>
                    <div class="row profit-row">
                      
                     
                        <div class="col-sm-2 parent-div">
                            <input type="text" readonly="readonly" name="product_details[<?php echo $quantity['quantity']; ?>][0][quantity]" value="<?php echo $quantity['quantity']; ?>" class="form-control validate[required,custom[number]]"><br />
                            <input type="hidden" name="product_details[<?php echo $quantity['quantity']; ?>][0][quantity_id]" value="<?php echo $quantity['product_quantity_id']; ?>" />
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
                                  
                              <?php /*?>    <div class="row">  <?php */?>
                                    
                                    <div class="col-lg-5">
                                        <div class="col-lg-5">
                                        	<input type="text" name="product_details[<?php echo $quantity['quantity']; ?>][<?php echo $inner_count; ?>][size_from]" class="form-control validate[required,custom[number]]" placeholder="Size" value="<?php echo $quantity_profit_price['size_from']; ?>">
                                        </div>
                                        
                                        <div class="col-lg-5">
                                        	<input type="text" name="product_details[<?php echo $quantity['quantity']; ?>][<?php echo $inner_count; ?>][size_to]" class="form-control validate[required,custom[number]]" placeholder="Size" value="<?php echo $quantity_profit_price['size_to']; ?>">
                                        </div>
                                        
                                         <input type="hidden" name="product_details[<?php echo $quantity['quantity']; ?>][<?php echo $inner_count; ?>][quantity_id]" value="<?php echo $quantity['product_quantity_id']; ?>" />
                                      
                                    </div>
                                    
                               <?php /*?> </div><?php */?>
                                
                                <br />
                                   
								<?php /*?><div class="row k"><?php */?>
                                     
                                            
                                            <div class="col-lg-2">
                                           <?php $all_zipper = $obj_profit->getActiveZipper();
                                                    foreach($all_zipper as $zipper)
                                                    {?>
                                                       		<label ><?php echo $zipper['zipper_name'];?></label>
                                                            <input type="text" name="product_details[<?php echo $quantity['quantity']; ?>][<?php echo $inner_count; ?>][<?php echo $zipper['zipper_name'];?>]" class="form-control validate[required,custom[number]]" placeholder="<?php echo $zipper['zipper_name'];?>" value="<?php //echo $quantity_profit_price['zipper']; ?>">
                                                            
                                                        
                                             <?php } ?>
                                             <input type="hidden" id="zip_arr" value='<?php echo json_encode($all_zipper);?>' />
                                         </div>
                                         
                                           <div class="col-lg-2">
                                           <?php $all_spout = $obj_profit->getActiveProductSpout();
                                                    foreach($all_spout as $spout)
                                                    {?>
                                                       		<label ><?php echo $spout['spout_name'];?></label>
                                                            <input type="text" name="product_details[<?php echo $quantity['quantity']; ?>][<?php echo $inner_count; ?>][<?php echo $spout['spout_name'];?>]" class="form-control validate[required,custom[number]]" placeholder="<?php echo $spout['spout_name'];?>" value="<?php //echo $quantity_profit_price['zipper']; ?>">
                                                            
                                                        
                                             <?php } ?>
                                             <input type="hidden" id="spout_arr" value='<?php echo json_encode($all_spout);?>' />
                                           </div>
                                           
                                             <div class="col-lg-2">
                                              <?php $all_acc = $obj_profit->getActiveProductAccessorie();
                                                    foreach($all_acc as $acc)
                                                    {?>
                                                       		<label ><?php echo $acc['product_accessorie_name'];?></label>
                                                            <input type="text" name="product_details[<?php echo $quantity['quantity']; ?>][<?php echo $inner_count; ?>][<?php echo $acc['product_accessorie_name'];?>]" class="form-control validate[required,custom[number]]" placeholder="<?php echo $acc['product_accessorie_name'];?>" value="<?php //echo $quantity_profit_price['zipper']; ?>">
                                                            
                                                        
                                             <?php } ?>
                                             <input type="hidden" id="acc_arr" value='<?php echo json_encode($all_acc);?>' />
                                           </div>
                                           
                                             <div class="col-lg-2">
                                              <?php $all_tintie = $obj_profit->getActiveProductTinTie();
                                                    foreach($all_tintie as $tintie)
                                                    {?>
                                                       		<label ><?php echo $tintie['tintie_name'];?></label>
                                                            <input type="text" name="product_details[<?php echo $quantity['quantity']; ?>][<?php echo $inner_count; ?>][<?php echo $tintie['tintie_name'];?>]" class="form-control validate[required,custom[number]]" placeholder="<?php echo $tintie['tintie_name'];?>" value="<?php //echo $quantity_profit_price['zipper']; ?>">
                                                            
                                                        
                                             <?php } ?>
                                              <input type="hidden" id="tintie_arr" value='<?php echo json_encode($all_tintie);?>' />
                                           </div>
                                            
                                            
                                           <?php if($inner_count==0){ ?>
                                        
                                          <div class="col-lg-1">
                                               
                                                <a class="btn btn-success btn-xs btn-circle addmore" data-toggle="tooltip" data-placement="top" title="Add Profit" ><i class="fa fa-plus"></i></a>
                                          </div>
                                            
                                        <?php } else { ?>
                                            
                                           <div class="col-lg-1">
                                              
                                              <a class="btn btn-danger btn-xs btn-circle remove" data-toggle="tooltip" data-placement="top" title="Remove" ><i class="fa fa-minus"></i></a>
                                           </div>
                                        
                                    <?php } ?>
                                         
                                         
                                           
                                    <?php /*?></div><?php */?>
                                   
                                    </div><br />
                                    
                                    
                                       
                                    <!-- [kinjal] end-->
                                    
                                  <?php /*?> <div class="add"></div><?php */?>
                               
                                <?php $inner_count++; } ?>  <?php } else { ?>
                                
                                <?php /*?><div class="row">
                                    <?php */?>
                                    
                                    <div class="col-lg-5">
                                        <div class="col-lg-5">
                                        	<input type="text" name="product_details[<?php echo $quantity['quantity']; ?>][0][size_from]" class="form-control validate[required,custom[number]]" placeholder="Size" value="">
                                        </div>
                                        
                                        <div class="col-lg-5">
                                        	<input type="text" name="product_details[<?php echo $quantity['quantity']; ?>][0][size_to]" class="form-control validate[required,custom[number]]" placeholder="Size" value="">
                                        </div>
                                          
                                       
                                        
                                        <input type="hidden" name="product_details[<?php echo $quantity['quantity']; ?>][0][quantity_id]" value="<?php echo $quantity['product_quantity_id']; ?>" />
                                    </div>
                                    
                                    
                              <?php /*?>  </div><?php */?>
                                
                                  <br />
                                   
								<!--<div class="row">-->
                                     
                                            <div class="col-lg-2">
                                           <?php $all_zipper = $obj_profit->getActiveZipper();
                                                    foreach($all_zipper as $zipper)
                                                    {?>
                                                       		<label ><?php echo $zipper['zipper_name'];?></label>
                                                            <input type="text" name="product_details[<?php echo $quantity['quantity']; ?>][0][<?php echo $zipper['zipper_name'];?>]" class="form-control validate[required,custom[number]]" placeholder="<?php echo $zipper['zipper_name'];?>" value="<?php //echo $quantity_profit_price['zipper']; ?>">             
                                             <?php } ?>
                                               <input type="hidden" id="zip_arr" value='<?php echo json_encode($all_zipper);?>' />
                                         </div>
                                         
                                           <div class="col-lg-2">
                                           <?php $all_spout = $obj_profit->getActiveProductSpout();
                                                    foreach($all_spout as $spout)
                                                    {?>
                                                       		<label ><?php echo $spout['spout_name'];?></label>
                                                            <input type="text" name="product_details[<?php echo $quantity['quantity']; ?>][0][<?php echo $spout['spout_name'];?>]" class="form-control validate[required,custom[number]]" placeholder="<?php echo $spout['spout_name'];?>" value="<?php //echo $quantity_profit_price['zipper']; ?>">              
                                             <?php } ?>
                                             <input type="hidden" id="spout_arr" value='<?php echo json_encode($all_spout);?>' />
                                           </div>
                                           
                                             <div class="col-lg-2">
                                              <?php $all_acc = $obj_profit->getActiveProductAccessorie();
                                                    foreach($all_acc as $acc)
                                                    {?>
                                                       		<label ><?php echo $acc['product_accessorie_name'];?></label>
                                                            <input type="text" name="product_details[<?php echo $quantity['quantity']; ?>][0][<?php echo $acc['product_accessorie_name'];?>]" class="form-control validate[required,custom[number]]" placeholder="<?php echo $acc['product_accessorie_name'];?>" value="<?php //echo $quantity_profit_price['zipper']; ?>">
                                                            
                                                        
                                             <?php } ?>
                                             <input type="hidden" id="acc_arr" value='<?php echo json_encode($all_acc);?>' />
                                           </div>
                                           
                                             <div class="col-lg-2">
                                              <?php $all_tintie = $obj_profit->getActiveProductTinTie();
                                                    foreach($all_tintie as $tintie)
                                                    {?>
                                                       		<label ><?php echo $tintie['tintie_name'];?></label>
                                                            <input type="text" name="product_details[<?php echo $quantity['quantity']; ?>][0][<?php echo $tintie['tintie_name'];?>]" class="form-control validate[required,custom[number]]" placeholder="<?php echo $tintie['tintie_name'];?>" value="<?php //echo $quantity_profit_price['zipper']; ?>">
                                                            
                                                        
                                             <?php } ?>
                                             <input type="hidden" id="tintie_arr" value='<?php echo json_encode($all_tintie);?>' />
                                           </div>
                                      
                                        <div class="col-lg-1">
                                       		 <a class="btn btn-success btn-xs btn-circle addmore" data-toggle="tooltip" data-placement="top" title="Add Profit" ><i class="fa fa-plus"></i></a>
                                   		 </div>
                                  
                                   <!-- </div>-->
                                   
                                    <br />
                                <?php } ?>
                         
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
		//var count = $(this).parent().parent().parent().parent().parent().children().size()+1;
		var count = $(this).parent().parent().parent().children().size()+1;
		//var count = $(this).parent().parent().parent().parent().children().size()+1;
		alert(count);
		//alert($(this).parent().parent().parent().children().size()+1);
		var zip_arr = jQuery.parseJSON($('#zip_arr').val());
		//alert(zip_arr);
		var spout_arr = jQuery.parseJSON($('#spout_arr').val());
		var acc_arr = jQuery.parseJSON($('#acc_arr').val());
		var tintie_arr = jQuery.parseJSON($('#tintie_arr').val());
		html += '<br /><br />';
			html += '<div class="row">';
			
			html +='<div class="col-lg-5">';	
					html +='<div class="col-lg-5">';
						  html += '<input type="text" name="product_details['+quantity+']['+count+'][size_from]" value="" class="form-control validate[required,custom[number]]" placeholder="Size">';
						html += '</div>';
						  
						html +='<div class="col-lg-5">';
						  html += '<input type="text" name="product_details['+quantity+']['+count+'][size_to]" value="" class="form-control validate[required,custom[number]]" placeholder="Size">';
					html +='</div>';
			
				html +='</div>';
				
				html +='<input type="hidden" name="product_details['+quantity+']['+count+'][quantity_id]" value="'+quantity_id+'" />';
				
			//html +='</div>';
			html +='</div>';
		
			html += ' <br /><div class="row k">';
				html += '<div class="col-lg-2">';
					for(var i=0;i<zip_arr.length;i++)
					{
						html +='<label >'+zip_arr[i].zipper_name+'</label><input type="text" name="product_details['+quantity+']['+count+']['+zip_arr[i].zipper_name+']" class="form-control validate[required,custom[number]]" placeholder="'+zip_arr[i].zipper_name+'" value="<?php //echo $quantity_profit_price['zipper']; ?>">';
					} 
				html +='</div>';
				
			//html +='</div>';	
				
				html += '<div class="col-lg-2">';
					for(var i=0;i<spout_arr.length;i++)
					{
						html +='<label >'+spout_arr[i].spout_name+'</label><input type="text" name="product_details['+quantity+']['+count+']['+spout_arr[i].spout_name+']" class="form-control validate[required,custom[number]]" placeholder="'+spout_arr[i].spout_name+'" value="<?php //echo $quantity_profit_price['zipper']; ?>">';
					} 
				html +='</div>';
				
				html += '<div class="col-lg-2">';
					for(var i=0;i<acc_arr.length;i++)
					{
						html +='<label >'+acc_arr[i].product_accessorie_name+'</label><input type="text" name="product_details['+quantity+']['+count+']['+acc_arr[i].product_accessorie_name+']" class="form-control validate[required,custom[number]]" placeholder="'+acc_arr[i].product_accessorie_name+'" value="<?php //echo $quantity_profit_price['zipper']; ?>">';
					} 
				html +='</div>';
				
				html += '<div class="col-lg-2">';
					for(var i=0;i<tintie_arr.length;i++)
					{
						html +='<label >'+tintie_arr[i].tintie_name+'</label><input type="text" name="product_details['+quantity+']['+count+']['+tintie_arr[i].tintie_name+']" class="form-control validate[required,custom[number]]" placeholder="'+tintie_arr[i].tintie_name+'" value="<?php //echo $quantity_profit_price['zipper']; ?>">';
					} 
				html +='</div>';
				
				
				html +='<div class="col-lg-1">';
			   		html +='<a class="btn btn-danger btn-xs btn-circle remove" data-toggle="tooltip" data-placement="top" title="Remove" ><i class="fa fa-minus"></i></a>';
				html +='</div>';
			
			html +='</div>';		
		//html +='</div>';	
		//$('.profit-row .second-part .addmore').parent().append(html);
		//$(this).parent().parent().parent().append(html);
		//var myClass = 'addtional-row';
		$(this).parent().parent().parent().append(html);
		//$('.profit-row .addmore').parent().parent().parent().append(html);
		//$(this).parent().parent().parent().append(html);
		$('.addtional-row .remove').click(function(){
			//alert("hii");
			$(this).parent().parent().remove();
		});
		
	});
	
	$('.addtional-row .remove').click(function(){
		//alert("kii");
		$(this).parent().parent().remove();
	});
	
	</script> 
<!-- Close : validation script -->

<?php } else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>