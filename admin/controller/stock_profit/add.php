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
		/*if($product_id == '22')
			$product_id='3';*/
		//chnage End
		
		$product = $obj_profit->getProduct($product_id);
		//printr($product);die;
		
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
		//printr($post);
		$product_id = $product['product_id'];
		
		//$product_id = '22';
		
		$obj_profit->updateProfit($product_id,$post);
		//die;
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
                           <?php echo $product['product_name']; ?>
                         </label>   
                      </div>
                  </div>
              <?php } ?>
              
              
       
              
              <div class="form-group more_price">
             
                <div class="col-lg-12">
				   <?php 
				     $quantity_data = $obj_profit->getQuantityData();
					 if($quantity_data) {
						$count = 0;  
					   foreach($quantity_data as $quantity){
						   //printr($quantity);
					?>
                    <div class="col-md-3 profit-row" id="row_<?php echo $quantity['template_quantity_id']; ?>" >
                          
                      
                     
                     <!--   <div class="col-sm-2 parent-div">
                            <input type="text" readonly="readonly" name="product_details[<?php //echo $quantity['quantity']; ?>][0][quantity]" value="<?php //echo $quantity['quantity']; ?>" class="form-control validate[required,custom[number]]">
                            <input type="hidden" name="product_details[<?php //echo $quantity['quantity']; ?>][0][quantity_id]" value="<?php //echo $quantity['template_quantity_id']; ?>" />
                        </div>
                     -->
                      
                           <div class="row ">
                            <div class="col-lg-2"><b>Quantity</b></div>
                                    <div class="col-lg-4"> <input type="text" readonly="readonly" name="product_details[<?php echo $quantity['quantity']; ?>][0][quantity]" value="<?php echo $quantity['quantity']; ?>" class="form-control validate[required,custom[number]]"></div>
                             <div class="col-lg-1"></div>       
                                      </div>
                    <div class="row ">
                                   
                                     <div class="col-lg-4"><b>Size</b></div>
                                      <div class="col-lg-2"><b>Profit (Rich)</b></div>
                                      <div class="col-lg-2"><b>Profit (Poor)</b></div>
                                      <div class="col-lg-2"><b>Profit (More Poor)</b></div>
                                      <div class="col-sm-2"><b></b></div>
                                      </div>
                        <div class=" second-part">  
							
								<?php  
                                 $quantity_profit_prices = $obj_profit->getProfitPrices($product_id,$quantity['template_quantity_id']);
								 //printr($quantity_profit_prices);
								 //die;
								 $size_masters = $obj_profit->getSize($product['product_id']);
								// printr($size_masters);
								// die;
                                 if($quantity_profit_prices){
								   $inner_count = 0;
								   $inner_count2 = 1;
									$totalSelectBox = count($quantity_profit_prices);
									$totalOption = count($size_masters);
									
									//echo $totalSelectBox.'<br>'.$totalOption;
                                   foreach($quantity_profit_prices as $quantity_profit_price){
									   if($totalSelectBox >= $totalOption) {
										$style = 'style="display:none;"';
									}
									else {
										$style = 'style=""';
									}

                                ?>	
                                <div class="row addtional-row">
                                    
                                    
                                    <div class="col-lg-4" style="width: 30%;">
                                       <?php //printr($size_masters);
                                            	$Size_detail = $obj_profit->Size_detail($quantity_profit_price['size_master_id']);
                                            		$zipperData = $obj_profit->ZipperData($Size_detail['product_zipper_id']);
                                            	
                                            	 $addedByInfo = '';
												$addedByInfo .= '<div class="row">';
												$addedByInfo .= '<div class="col-lg-3"> Size </div>';
												$addedByInfo .= '<div class="col-lg-9">'.$Size_detail['width'].'X'.$Size_detail['height'].'X'.$Size_detail['gusset'].'';
												if($zipperData['zipper_name'] != '') $addedByInfo.=' ('.$zipperData['zipper_name'].')';
												$addedByInfo .= '</div>';
												$addedByInfo .= '</div>';
                                            ?>
                                            <a class="" data-trigger="hover" data-toggle="popover" data-html="true" data-placement="top" data-content='<?php echo $addedByInfo;?>' title="" data-original-title="<b><?php echo $Size_detail['volume'];?></b>">
                                        <?php //printr($quantity_profit_price); ?>
                                        	<select class="form-control validate[required] size_<?php echo $quantity_profit_price['stock_profit_id']; ?>" name="product_details[<?php echo $quantity['quantity']; ?>][<?php echo $inner_count; ?>][size_master_id]" id="size_<?php echo $quantity['template_quantity_id']; ?>_<?php echo $inner_count2; ?>">
                                            
                                            	<!--option value="">Select Please</option-->
                                                <!--option value="<?php echo $quantity_profit_price['stock_profit_id']; ?>"><?php echo $quantity_profit_price['width'].'X'.$quantity_profit_price['height'].'X'.$quantity_profit_price['gusset']; ?></option-->
                                                <?php
													foreach($size_masters as $size_master ) {
														$zipperData = $obj_profit->ZipperData($size_master['product_zipper_id']);
												?>
                                                <option value="<?php echo $size_master['size_master_id']; ?>" <?php if($size_master['size_master_id'] == $quantity_profit_price['size_master_id'] ) { ?> selected="selected" <?php } ?>  id="option_<?php echo $quantity['template_quantity_id']; ?>"> <?php echo '('.$size_master['volume'].') '. $size_master['width'].'X'.$size_master['height'].'X'.$size_master['gusset'];
												if($zipperData['zipper_name'] != '') echo ' ('.$zipperData['zipper_name'].')';
												 ?></option>
                                                <?php } ?>
                                            </select>    
                                            
                                        </div>
                                        <input type="hidden" id="qid_<?php echo $quantity['template_quantity_id']; ?>" name="qid_<?php echo $quantity['template_quantity_id']; ?>" value="<?php echo $inner_count; ?>" />
                                        <input type="hidden" name="product_details[<?php echo $quantity['quantity']; ?>][<?php echo $inner_count; ?>][quantity_id]" value="<?php echo $quantity['template_quantity_id']; ?>" />
                                  
                                      </a>
                                    <div class="col-lg-3" style="width: 20%;">
                                        <input type="text" name="product_details[<?php echo $quantity['quantity']; ?>][<?php echo $inner_count; ?>][profit]" value="<?php echo $quantity_profit_price['profit']; ?>" class="form-control " placeholder="Profit">
                                    </div>
                                    
                                     <div class="col-lg-3" style="width: 20%;">
                                        <input type="text" name="product_details[<?php echo $quantity['quantity']; ?>][<?php echo $inner_count; ?>][profit_poor]" value="<?php echo $quantity_profit_price['profit_poor']; ?>" class="form-control " placeholder="Profit">
                                    </div>
                                    
                                    <div class="col-lg-3" style="width: 20%;">
                                        <input type="text" name="product_details[<?php echo $quantity['quantity']; ?>][<?php echo $inner_count; ?>][profit_more_poor]" value="<?php echo $quantity_profit_price['profit_more_poor']; ?>" class="form-control " placeholder="Profit">
                                    </div>
                                    
                                    <?php if($inner_count==0){ ?>
                                        
                                      <div class="col-sm-1">
                                            <a class="btn btn-success btn-xs btn-circle addmore" data-toggle="tooltip" data-placement="top" title="Add Profit" onclick="addmore_profit(<?php echo $quantity['template_quantity_id']; ?>,  <?php echo $quantity['quantity']; ?>)" id="addmore_<?php echo $quantity['template_quantity_id']; ?>" <?php echo $style; ?> ><i class="fa fa-plus"></i></a>
                                      </div>
                                        
                                    <?php } else { ?>
                                        
                                       <div class="col-sm-1">
                                          <a class="btn btn-danger btn-xs btn-circle remove" data-toggle="tooltip" data-placement="top" title="Remove" onclick="remove_profit(<?php echo $quantity['template_quantity_id']; ?>)" ><i class="fa fa-minus"></i></a>
                                       </div>
                                        
                                    <?php } ?>
                                </div>
                                <?php $inner_count++;$inner_count2++; } } else { ?>
                                <?php 
								$size = 1;
									$size_masters = $obj_profit->getSize($product['product_id']);
									
								?>
                                <div class="row">                                    
                                    <div class="col-lg-4" style="width: 30%;">
                                        <div class="col-lg-12">
                                        
                                        	<select class="form-control validate[required] size_<?php echo $quantity['template_quantity_id']; ?>" name="product_details[<?php echo $quantity['quantity']; ?>][0][size_master_id]" id="size_<?php echo $quantity['template_quantity_id']; ?>_1">
                                            	<!--option value="">Select Please</option-->
                                                <?php
													foreach($size_masters as $size_master ) {
													$zipperData = $obj_profit->ZipperData($size_master['product_zipper_id']);
												?>
                                                <option value="<?php echo $size_master['size_master_id']; ?>" id="option_<?php echo $quantity['template_quantity_id']; ?>"><?php echo '('.$size_master['volume'].') '.$size_master['width'].'X'.$size_master['height'].'X'.$size_master['gusset']; 
												if($zipperData['zipper_name'] != '') echo ' ('.$zipperData['zipper_name'].')';?></option>
                                                <?php } ?>
                                            </select>
                                        
                                        </div>
                                        
                                       
                                        <!--input type="hidden" id="qid_<?php echo $quantity['template_quantity_id']; ?>" name="qid_<?php echo $quantity['template_quantity_id']; ?>" value="<?php echo $size; ?>" /-->
                                        <input type="hidden" name="product_details[<?php echo $quantity['quantity']; ?>][0][quantity_id]" value="<?php echo $quantity['template_quantity_id']; ?>" />
                                    </div>
                                    
                                    
                                    <div class="col-lg-3" style="width: 20%;">
                                        <input type="text" name="product_details[<?php echo $quantity['quantity']; ?>][0][profit]" value="" class="form-control " placeholder="Profit">
                                    </div>
                                    
                                    <div class="col-lg-3" style="width: 20%;">
                                        <input type="text" name="product_details[<?php echo $quantity['quantity']; ?>][0][profit_poor]" value="" class="form-control " placeholder="Profit">
                                    </div>
                                   
                                   <div class="col-lg-3" style="width: 20%;">
                                        <input type="text" name="product_details[<?php echo $quantity['quantity']; ?>][0][profit_more_poor]" value="" class="form-control " placeholder="Profit">
                                    </div>
                                    
                                    <div class="col-sm-1">
                                        <a class="btn btn-success btn-xs btn-circle addmore" data-toggle="tooltip" data-placement="top" title="Add Profit" onclick="addmore_profit_new(<?php echo $quantity['template_quantity_id']; ?>, <?php echo $quantity['quantity']; ?>)" id="addmore_<?php echo $quantity['template_quantity_id']; ?>" ><i class="fa fa-plus"></i></a>
                                    </div>
                                </div>
                                <?php $size++; } ?>
                         
                        </div>
                      
                    </div>
                    
                   
                  
                    
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

		/*var html = '';

		var quantity = $(this).parent().parent().parent().parent().find('input[type=text]').val();
		var quantity_id = $(this).parent().parent().parent().parent().find('input[type=hidden]').val();
		var count = $(this).parent().parent().parent().children().size();
		alert(count);

		var new_count = (count-1);

		var size = document.getElementById("size_"+new_count);

		
		html += '<div class="row addtional-row">';
			
			html +='<div class="col-lg-6">';	
				html +='<div class="col-lg-12">';
				html += '<select class="form-control validate[required] " id="size_'+count+'"  name="product_details['+quantity+']['+count+'][size_master_id]" >';
				html += size.innerHTML;
				html += '</select>';				
				html += '</div>';				
				
				html +='<input type="hidden" name="product_details['+quantity+']['+count+'][quantity_id]" value="'+quantity_id+'" />';
			html +='</div>';
				
				
			html +='<div class="col-lg-2">';
			   html +='<input type="text" name="product_details['+quantity+']['+count+'][profit]" value="" class="form-control validate[required,custom[number]]" placeholder="Profit">';
			html +='</div>';
			
			html +='<div class="col-lg-1">';
			   html +='<a class="btn btn-danger btn-xs btn-circle remove" data-toggle="tooltip" data-placement="top" title="Remove" ><i class="fa fa-minus"></i></a>';
			html +='</div>';
		
		html +='</div>';
		$(this).parent().parent().parent().append(html);*/	
		$('.addtional-row .remove').click(function(){			
			//$('.addmore').show();
			$(this).parent().parent().remove();
		});
		
		
		
		
	});
	function remove_profit(id) {		
		$('#addmore_'+id).show();
	}
	$('.addtional-row .remove').click(function(){

		$(this).parent().parent().remove();

	});
	
	function addmore_profit(quantity_id, quantity) {	

	var html = '';
		var count = quantity_id;

		var countSelectbox = $('#row_'+quantity_id+' > .second-part > .row').length;

		var size_id = countSelectbox+1;
		//var countAddmore = $('select#size_'+quantity_id+' option#option_'+quantity_id+'').length;

		var countAddmore = $('select#size_'+quantity_id+'_'+countSelectbox+' option#option_'+quantity_id+'').length;
		var countAddmore2 = $('select#size_'+quantity_id+'_1 option#option_'+quantity_id+'').length;


		var size = document.getElementById("size_"+quantity_id+"_"+countSelectbox);

		
		html += '<div class="row addtional-row">';
			
			html +='<div class="col-lg-4" style="width: 30%;">';	
			
				html += '<select class="form-control validate[required] " id="size_'+quantity_id+'_'+size_id+'"  name="product_details['+quantity+']['+countSelectbox+'][size_master_id]" >';
				html += size.innerHTML;
				html += '</select>';				
				html += '</div>';				
				
				html +='<input type="hidden" name="product_details['+quantity+']['+countSelectbox+'][quantity_id]" value="'+quantity_id+'" />';
		
				
				
			html +='<div class="col-lg-3" style="width: 20%;">';
			   html +='<input type="text" name="product_details['+quantity+']['+countSelectbox+'][profit]" value="" class="form-control " placeholder="Profit">';
			html +='</div>';
			
			html +='<div class="col-lg-3" style="width: 20%;">';
			   html +='<input type="text" name="product_details['+quantity+']['+countSelectbox+'][profit_poor]" value="" class="form-control " placeholder="Profit">';
			html +='</div>';
			
			html +='<div class="col-lg-3" style="width: 20%;">';
			   html +='<input type="text" name="product_details['+quantity+']['+countSelectbox+'][profit_more_poor]" value="" class="form-control " placeholder="Profit">';
			html +='</div>';
			
			html +='<div class="col-lg-1">';
			   html +='<a class="btn btn-danger btn-xs btn-circle remove" onclick="remove_profit('+count+')" data-toggle="tooltip" data-placement="top" title="Remove" ><i class="fa fa-minus"></i></a>';
			html +='</div>';
		
		html +='</div>';

		//var row_id = quantity_id-1;
		$('#row_'+quantity_id+' > .second-part ').append(html);
		if(countAddmore2 < (countSelectbox+2)) {
			$('#addmore_'+quantity_id).hide();
		}
		var size_id2 = countSelectbox+1; 


//		$( "#myselect option:selected" ).text()
		var option = $("#size_"+quantity_id+"_"+countSelectbox+" option:selected").text();
			var id = $('#size_'+quantity_id+'_'+countSelectbox+'').val();
			var new_id = +id+1;
			$("#size_"+quantity_id+"_"+size_id2+" option[value='"+id+"']").remove();
			$("#size_"+size_id2+" option[value='"+new_id+"']").attr("selected","selected");
		
	
	}
	function addmore_profit_new(quantity_id, quantity) {
		//	var bla = $('#qid_'+quantity_id).val();


		var html = '';
		var count = quantity_id;
		
		var countSelectbox = $('#row_'+quantity_id+' > .second-part > .row').length;

		var size_id = countSelectbox+1;
		var countAddmore = $('select#size_'+quantity_id+'_'+countSelectbox+' option#option_'+quantity_id+'').length;
		var countAddmore2 = $('select#size_'+quantity_id+'_1 option#option_'+quantity_id+'').length;
		var size = document.getElementById("size_"+quantity_id+"_"+countSelectbox);

		
		html += '<div class="row addtional-row">';
			
			html +='<div class="col-lg-4" style="width: 30%;">';	
			
				html += '<select class="form-control validate[required] " id="size_'+quantity_id+'_'+size_id+'"  name="product_details['+quantity+']['+countSelectbox+'][size_master_id]" >';
				html += size.innerHTML;
				html += '</select>';				
				html += '</div>';				
				
				//html += '<input type="hidden" id="qid_'+size_id+'" name="qid_'+size_id+'" value="'+size_id+'" />'
				html +='<input type="hidden" name="product_details['+quantity+']['+countSelectbox+'][quantity_id]" value="'+quantity_id+'" />';
		
				
				
			html +='<div class="col-lg-3" style="width: 20%;">';
			   html +='<input type="text" name="product_details['+quantity+']['+countSelectbox+'][profit]" value="" class="form-control " placeholder="Profit">';
			html +='</div>';
			
			html +='<div class="col-lg-3" style="width: 20%;">';
			   html +='<input type="text" name="product_details['+quantity+']['+countSelectbox+'][profit_poor]" value="" class="form-control " placeholder="Profit">';
			html +='</div>';
			
			html +='<div class="col-lg-3" style="width: 20%;">';
			   html +='<input type="text" name="product_details['+quantity+']['+countSelectbox+'][profit_more_poor]" value="" class="form-control " placeholder="Profit">';
			html +='</div>';
			
			html +='<div class="col-lg-1">';
			   html +='<a class="btn btn-danger btn-xs btn-circle remove" data-toggle="tooltip" data-placement="top" title="Remove" ><i class="fa fa-minus"></i></a>';
			html +='</div>';
		
		html +='</div>';
		$('#row_'+quantity_id+' > .second-part ').append(html);
		
		var totalAdd = countSelectbox+1;

		if(countAddmore2 <= totalAdd) {
			$('#addmore_'+quantity_id).hide();
		}
		var size_id2 = countSelectbox+1; 
//		$( "#myselect option:selected" ).text()
		var option = $("#size_"+quantity_id+"_"+countSelectbox+" option:selected").text();
			var id = $('#size_'+quantity_id+'_'+countSelectbox+'').val();
			var new_id = +id+1;
			$("#size_"+quantity_id+"_"+size_id2+" option[value='"+id+"']").remove();
			$("#size_"+size_id2+" option[value='"+new_id+"']").attr("selected","selected");
//		$("#size_"+size_id2+" option[value='']").attr("selected","selected");
	}
	
	</script> 
<!-- Close : validation script -->

<?php } else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>