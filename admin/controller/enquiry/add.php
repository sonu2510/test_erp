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
$address_id='0';
$add_url='';
if(isset($_GET['address_book_id']))
{
    $address_id = decode($_GET['address_book_id']);
    $add_url = '&address_book_id='.$_GET['address_book_id'];
}
$bradcums[] = array(
	'text' 	=> $display_name.' List',
	'href' 	=> $obj_general->link($rout, $add_url, '',1),
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

	
	$addedByInfo = $obj_enquiry->getUser($user_id,$user_type_id);

//Start : edit
$edit = '';
$validation = ',custom[email]';
if(isset($_GET['enquiry_id']) && !empty($_GET['enquiry_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$enquiry_id = base64_decode($_GET['enquiry_id']);
		$enquiry = $obj_enquiry->getEnquiry($enquiry_id);		
	//	printr($enquiry);
		$edit = 1;
	}
		$validation = '';
}
else if(isset($_GET['request_id']) && !empty($_GET['request_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$request_id = base64_decode($_GET['request_id']);
		$enquiry = $obj_enquiry->getCustomerEnquiry($request_id);		
		$enquiry['first_name']=$enquiry['contact_name'];
		$enquiry['mobile_number']=$enquiry['phone_no'];
		$enquiry['customer_address']=$enquiry['address'];
		$enquiry['number_of_pouch_req']=$enquiry['num_bag'];
		$enquiry['country_id']=$addedByInfo['country_id'];
		
		$edit = 1;
	}
	$validation = '';
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}
//Close : edit

//Close : editx


if($display_status){
	//insert 
	if(isset($_POST['btn_save'])){
		$post = post($_POST);		
		$insert_id = $obj_enquiry->addEnquiry($post);
	    $obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	if(isset($_POST['btn_save_new'])){
		$post = post($_POST);		
		$insert_id = $obj_enquiry->addEnquiry($post);
	    $obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, 'mod=add', '',1));
	}
	if(isset($_POST['btn_update'])){
		$post = post($_POST);	
	 //  printr($post);	//die;
		
		$update_id = $obj_enquiry->updateEnquiryrecode($post);	
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
		

	}
	
	//printr($addedByInfo);
	
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
      <div class="col-sm-8">
        <section class="panel">
          <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="form_nm" enctype="multipart/form-data">
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Enquiry For</label>
                <div class="col-lg-8">
                  <div class="radio  radio-group">
                    <label class="radio-custom">
                      		<input type="radio" class="required" value="stock" name="enquiry_for" <?php if(isset($enquiry) && ($enquiry['enquiry_for']) == 'stock') { ?> checked="checked" <?php } else { echo 'checked="checked"'; } ?>>
                      		<i class="fa fa-circle-o"></i> Stock </label>
                    <label class="radio-custom" style="margin-left:2px;">
                      		<input type="radio" class="required" value="custom" name="enquiry_for" <?php if(isset($enquiry) && ($enquiry['enquiry_for']) == 'custom') { ?> checked="checked" <?php } ?>>
                     		 <i class="fa fa-circle-o"></i> Custom </label>  
                     <label class="radio-custom">
                      		<input type="radio" class="required" value="digital print" name="enquiry_for" <?php if(isset($enquiry) && ($enquiry['enquiry_for']) == 'digital print') { ?> checked="checked" <?php }?>>
                      		<i class="fa fa-circle-o"></i> Digital print </label>
                   
                  </div>
                </div>
              </div>
               <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Email</label>
                <div class="col-lg-8">
                  <input type="text" name="email" id="email" value="<?php echo isset($enquiry['email'])?$enquiry['email']:'';?>" class="form-control validate[required<?php echo $validation;?>]">
                  <span style="color:red;" id="email_msg"></span>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Company Name</label>
                <div class="col-lg-8">
				 <input type="hidden" name="edit_value" id="edit_value" value="<?php echo $edit;?>" />
                  <input type="text" name="company_name" id="company_name" value="<?php echo isset($enquiry['company_name'])?$enquiry['company_name']:'';?>" class="form-control validate[required]">
                  <input type="hidden" name="enquiry_id"  id="enquiry_id" value="<?php echo isset($enquiry['enquiry_id'])?$enquiry['enquiry_id']:'';?>" class="form-control validate[required]"  />
                    <!-- kavita -->    
                   <input type="hidden" name="company_name_id"  value="<?php echo isset($enquiry['company_name_id']) ? $enquiry['company_name_id'] : ''; ?>" id="company_name_id" class="form-control " />
                     <input type="hidden" name="company_address_id"  value="" id="company_address_id" class="form-control " />
                                         
                                        <div id="ajax_response"></div>
                 </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">First Name</label>
                <div class="col-lg-8">
                  <input type="text" name="first_name" value="<?php echo isset($enquiry['first_name'])?$enquiry['first_name']:'';?>" class="form-control validate">
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Last Name</label>
                <div class="col-lg-8">
                  <input type="text" name="last_name" value="<?php echo isset($enquiry['last_name'])?$enquiry['last_name']:'';?>" class="form-control validate">
                </div>
              </div>
               <div class="form-group">
                    <label class="col-lg-3 control-label">Customer Address</label>
                        <div class="col-lg-8">
                      	  <textarea class="form-control" id="customer_address" name="customer_address"><?php if(isset($enquiry['customer_address'])) 


                       		 { echo $enquiry['customer_address']; } ?></textarea>
                        </div>
                  </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Phone Number</label>
                <div class="col-lg-8">
                  <input type="text" name="phone_number" value="<?php echo isset($enquiry['phone_number'])?$enquiry['phone_number']:'';?>" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Mobile Number</label>
                <div class="col-lg-8">
                  <input type="text" name="mobile_number" id="mobile_number" value="<?php echo isset($enquiry['mobile_number'])?$enquiry['mobile_number']:'';?>" class="form-control validate[required]">
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Fax</label>
                <div class="col-lg-8">
                  <input type="text" name="fax" value="<?php echo isset($enquiry['fax'])?$enquiry['fax']:'';?>" class="form-control" />
                </div>
              </div>
             
              

<!--kavita:24-2-2017--> 
		  <div class="form-group">
                                <label class="col-lg-3 control-label">Industry </label>
                                <div class="col-lg-6">
                                    <select name="industry" id="industry" class="form-control">
                                        <option value="">Select Industry</option>
                                        <option value="0">Other</option>
                                    	<?php
                                        
                                                $industrys = $obj_enquiry ->getIndustrys();
                                                foreach($industrys as $industry){
                                                        echo '<option value="'.$industry['enquiry_industry_id'].'">'. $industry['industry'].'</option>';
                                                }
                                                ?>  
                                    </select>
                                </div>
                        </div>      
            <div class="form-group">  
            <label class="col-lg-3 control-label"><span class="required">*</span>Country</label>
            <div class="col-lg-8">
            
            		<?php 	if(isset($enquiry)&& !empty($enquiry)){
								$sel_country = (isset($enquiry['country_id']))?$enquiry['country_id']:'';
								}else{
									$sel_country = $addedByInfo['country_id'];
								}
							
                            $countrys = $obj_general->getCountryCombo($sel_country);
                            echo $countrys;
                            ?>
                </div>
              </div>  
                
              <div class="form-group">
                <label class="col-lg-3 control-label">Website</label>
                <div class="col-lg-8">
                  <input type="text" name="website" value="<?php echo isset($enquiry['website'])?$enquiry['website']:'';?>" class="form-control"/>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Enquiry Source</label>
                <div class="col-lg-4">
                  <select name="enquiry_source_id" class="form-control validate[required]"  id="enquiry_source_id" onchange="getenquiry_source_value()">
                  <option value="" >Select Source </option>
                    <?php 
				   	   $enquiry_sources = $obj_enquiry->getEnquirySources(); 
					   foreach($enquiry_sources as $enquiry_source){
									if(isset($enquiry)&& !empty($enquiry)){
										$enquiry_source_id = (isset($enquiry['enquiry_source_id']))?$enquiry['enquiry_source_id']:'';
									}/*else{
										$enquiry_source_id = '1';
									}
							*/
				   ?>
                    <option value="<?php echo $enquiry_source['enquiry_source_id']; ?>" <?php echo (isset($enquiry_source_id) && $enquiry_source_id == $enquiry_source['enquiry_source_id'])?'selected':'';?> ><?php echo $enquiry_source['source']; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
          

                <div class="form-group" id="exhibition">
                    <label class="col-lg-3 control-label">Enquiry Source Details</label>
                    <div class="col-lg-4">
                        <select name="exhibition_name" class="form-control" id="exhibition_name">
                            <?php
                            $exhibitions = $obj_enquiry->getExhibitions();
                           ?><option value="0" >Select Exhibition </option>
                           <?php  foreach ($exhibitions as $exhibition) {
                                ?>
                                <option value="<?php echo $exhibition['exhibition_id']; ?>" <?php echo (isset($enquiry['exhibition_name']) && $enquiry['exhibition_name'] == $exhibition['exhibition_id']) ? 'selected' : ''; ?> ><?php echo $exhibition['exhibition_name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                    <div class="form-group" id="source_details">
                        <label class="col-lg-3 control-label">Enquiry Source Details</label>
                        <div class="col-lg-4">
                            <input type="text" name="enquiry_source_details" id="enquiry_source" value="" class="form-control" />
                        </div>
                    </div>
  
              
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Enquiry Type</label>
                <div class="col-lg-4">
                  <select name="enquiry_type" class="form-control">
                    <option value="hot" <?php echo (isset($enquiry['enquiry_type']) && $enquiry['enquiry_type'] == 'hot')?'selected':'';?> > Hot</option>
                    <option value="warm" <?php echo (isset($enquiry['enquiry_type']) && $enquiry['enquiry_type'] == 'warm')?'selected':'';?>> Warm</option>
                    <option value="cold" <?php echo (isset($enquiry['enquiry_type']) && $enquiry['enquiry_type'] == 'cold')?'selected':'';?>> Cold</option>
                  </select>
                  <!--<div class="line m-t-large"></div>-->
                </div>
              </div>
              
              <div class="form-group">
              	<div class="col-lg-12">
              		<div class="line m-t-large"></div>
                </div>
              </div>
              
              <!--<div class="form-group" id="product-div">
                <div class="col-lg-1"></div>
                <div class="col-lg-10">
                  <section class="panel">
                    <section class="panel-body">-->
                     <div class="form-group">              
                   	    <label class="col-lg-3 control-label">Number of bags required</label>
                        <div class="col-lg-8">
                          <input type="text" name="number_of_pouch_req" value=" <?php echo isset($enquiry['number_of_pouch_req'])?$enquiry['number_of_pouch_req']:'';?>"class="form-control">
                          
                        </div>
                     </div>
                     <div class="form-group">              
                   	    <label class="col-lg-3 control-label">Product To Fill In Inside</label>
                        <div class="col-lg-8">
                          <input type="text" name="filling" value=" <?php echo isset($enquiry['filling'])?$enquiry['filling']:'';?>"class="form-control">
                          
                        </div>
                     </div>
                     <div class="form-group">              
                   	    <label class="col-lg-3 control-label">Weight To Fill In Each Bag</label>
                        <div class="col-lg-8">
                          <input type="text" name="weight" value=" <?php echo isset($enquiry['weight'])?$enquiry['weight']:'';?>"class="form-control">
                          
                        </div>
                     </div>
                     <?php $material_combination = explode(',',$enquiry['material_combination']); ?>
                     <div class="form-group">              
                   	    <label class="col-lg-3 control-label">Material Combination</label>
                        <div class="col-lg-3">
                             <label> <input type="checkbox" class="" <?php if(in_array("Window", $material_combination)){ echo "checked=checked";}?> value="Window"  name="material_combination[]">Window</label>  
                            <label>  <input type="checkbox" class="" <?php if(in_array("Foil (No window)", $material_combination)){ echo "checked=checked";}?> value="Foil (No window)"  name="material_combination[]">Foil (No window)</label>  
                        </div>
                     </div>
                      <div class="form-group" style="display:none;">
                          <label class="col-lg-3 control-label">Remarks</label>
                            <div class="col-lg-8">
                              <textarea class="form-control" row="10" col="15" name="remark"><?php echo isset($enquiry['remark'])?$enquiry['remark']:'';?> </textarea>
                            </div>
                      </div>
                      <?php
					
						  if( $edit=='1' && $enquiry['products'] ){
											 $enquiry_product = $enquiry['products'];
										 }
										 else
										 {   
										     $size_array = array();
											 $printing_option_array = array();
											 $printing_effect_array = array();
											 $valve_array = array();
											 $zipper_array = array();
											 $spout_array = array();
											 $enquiry_product[]= array(												
												'product_id' => '',
												'product_name' => '',
												'no_of_pouches' => '',
												'sample_sent_date' => '',						
												'size' =>$size_array,
												'printing_option' => $printing_option_array,
												'printing_effect' => $printing_effect_array,
												'valve' => $valve_array,
												'zipper' => $zipper_array,
												'spout' => $spout_array
											);	 
										}
					//printr($enquiry_product);
				   if(!empty($enquiry_product)){
					$inner_count = 0;		   	
				foreach($enquiry_product as $en_product){
										 //printr($en_product);
									?>
                   <div class="form-group product-div" id="product-<?php echo  $inner_count;?>" style="display:none;">
                     		
                    <div class="form-group">
                        <label class="col-lg-3 control-label">Product Name</label>
                        
                        <div class="col-lg-7">
                         
                          <select class="form-control validate[required]"  id="product-name" name="nproducts[<?php echo $inner_count; ?>][product_id]" onchange = "getsize('<?php echo $inner_count; ?>')">
                            <?php 
								  $products = $obj_enquiry->getProducts(); 
								  if($products){
									foreach($products as $product) {
											if(isset($enquiry)&& !empty($enquiry)){
										$product_id = $en_product['product_id'];
									}else{
										$product_id = '3';
									}
									?>
								<option value="<?php echo $product['product_id']; ?>" <?php echo (isset($product['product_id']) && ($product['product_id'] == $product_id) )?'selected':'';?> ><?php echo $product['product_name']; ?></option>
								<?php } } ?>
                          </select>
                          
                        
                        </div>
                         <?php if($edit ){ ?>
                         	 <div class="col-lg-2">
                      		  	<a onclick="removeProduct(<?php echo  $inner_count.','. $enquiry['enquiry_id'].','.$en_product['product_enquiry_id'];?>)" data-original-title="Remove Product" class="btn btn-danger btn-xs btn-circle" data-toggle="tooltip" data-placement="top" title="">
                    		  	<i class="fa fa-minus"></i></a>
                             </div>
      						<?php }?>	
                      <input type="hidden" name="nproducts[<?php echo $inner_count; ?>][product_enquiry_id]"  id="product_enquiry_id" value="<?php echo isset($en_product['product_enquiry_id'])? $en_product['product_enquiry_id'] : ''; ?>" class="form-control validate[required]"  />
                      </div>
               
                        
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Printing Option</label>
                        <div class="col-lg-9">
                          <div class="checkbox col-lg-6">
                           
                             <?php 
							 //printr($en_product['printing_option']);
							 $p_option=array();
							 if($en_product['printing_option'])
							 {
								  foreach($en_product['printing_option'] as $key=> $option)
								 {
									
									$p_option[] = $option['printing_option'];
									
								 }
								
							 }
							 else{
								 	 $enquiry_product_option = array(
									 'printing_option' => '',
									 'product_enquiry_id'=>'',
									 );
								 } 
								//printr($p_option);
							  ?> 
                                                                          
                               <label>              
                              <input type="checkbox" class="validate[minCheckbox[1]]" value="With Printing" <?php if(in_array("With Printing", $p_option)){ echo "checked=checked";}else{echo 'hi';}?> name="nproducts[<?php echo $inner_count; ?>][product_printing_option][]">
                              With Printing</label>
                          </div>
                          <div class="checkbox col-lg-6">
                            <label>
                              <input type="checkbox" class="validate[minCheckbox[1]]" value="Without Printing"<?php if(in_array("Without Printing", $p_option)){ echo "checked=checked";}?> name="nproducts[<?php echo $inner_count; ?>][product_printing_option][]">
                              Without Printing</label>
                           
                          </div>
                        </div>
                      </div>
                      <?php /* 
                          <div class="form-group">  
                            <label class="col-lg-3 control-label">Color</label>
                            <div class="col-lg-9">
                            	<?php 
									$product_colors = $obj_enquiry->getProductColors(); 
									if($product_colors) {
									foreach($product_colors as $product_color) {
                                ?>
                                	<div class="checkbox col-lg-4">
                            			<label><input type="checkbox" class="validate[minCheckbox[1]]" value="<?php echo $product_color['pouch_color_id']; ?>" name="nproducts[0][product_color][]"><?php echo " ".$product_color['color']; ?></label>
                                    </div>    
                                <?php } } ?>
                             </div>
                           </div> 
                           */ ?>
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Printing Effect</label>
                        <div class="col-lg-9">
                         
                          <?php 
							 //printr($en_product['printing_option']);
							 $p_effect=array();
							 if($en_product['printing_effect'])
							 {
								  foreach($en_product['printing_effect'] as $key=> $effect)

								 {
									
									$p_effect[] = $effect['printing_effect_id'];
									
								 }
								
							 }
							 else{
								 	 $enquiry_product_effect = array(
									 'printing_effect' => '',
									 'printing_effect_id'=>'',
									 );
								 } 
								$printing_effects = $obj_enquiry->getActivePrintingEffectEnquiry(); 
									if($printing_effects) {
									foreach($printing_effects as $printing_effect) {
								//printr($p_effect);
									
									
                                ?>
                          <div class="checkbox col-lg-6">
                            <label>
                              <input type="checkbox" class="validate[minCheckbox[1]]" value="<?php echo $printing_effect['printing_effect_id']; ?> "<?php if(in_array($printing_effect['printing_effect_id'], $p_effect)){ echo "checked=checked";}?> name="nproducts[<?php echo $inner_count; ?>][product_printing_effect][]">
                              <?php echo " ".$printing_effect['effect_name']; ?></label>
                          </div>
                          <?php } }  ?>
                        </div>
                      </div>
                      <div class="form-group" id = "volume_div">
                        <label class="col-lg-3 control-label">Volume</label>
                        <div class="col-lg-9">
                          <?php      
						    $p_size=array();
							 if($en_product['size'])
							 {
								  foreach($en_product['size'] as $key=> $size)
								 {
									
									$p_size[] = $size['pouch_volume_id'];
									
								 }
								
							 }
							 else{
								 	 $enquiry_product_size = array(
									 'size' => '',
									 'pouch_volume_id'=>'',
									 );
								 }  
							
						  
						  
									$product_volumes = $obj_enquiry->get_size($en_product['product_id']); 
									///printr($product_volumes);
									if($product_volumes) {
									foreach($product_volumes as $product_volume) {
                                ?>
                          <div class="checkbox col-lg-4">
                            <label>
                              
                              <input type="checkbox" id ="volume_<?php echo $product_volume['pouch_volume_id']; ?>" class="validate[minCheckbox[1]]" value="<?php echo $product_volume['pouch_volume_id']; ?>"<?php if(in_array($product_volume['pouch_volume_id'], $p_size)){ echo "checked=checked";}?> name="nproducts[<?php echo $inner_count; ?>][product_volume][]">
                              <?php echo $product_volume['volume']; ?></label>
                          </div>
                          <?php } } ?>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Valve</label>
                        <div class="col-lg-9">
                          <div class="checkbox col-lg-6">
                            <label>
                            
                            <?php 
							  $p_valve=array();
							 if($en_product['valve'])
							 {
								  foreach($en_product['valve'] as $key=> $valve)
								 {
									
									$p_valve[] = $valve['valve'];
									
								 }
								
							 }
							 else{
									$p_valve[] ='Without Valve';
								 	 $enquiry_product_valve = array(
									 'valve' => '',
									 
									 );
								 } 
							
						  ?>
							
                              <input type="checkbox" class="validate[minCheckbox[1]]" value="With Valve" <?php if(in_array("With Valve", $p_valve)){ echo "checked=checked";}?>
                                        name="nproducts[<?php echo $inner_count; ?>][product_valve][]">
                              With Valve</label>
                          </div>
                          <div class="checkbox col-lg-6">
                            <label>
                              <input type="checkbox" class="validate[minCheckbox[1]]" value="Without Valve" <?php if(in_array("Without Valve", $p_valve)){ echo "checked=checked";}?> name="nproducts[<?php echo $inner_count; ?>][product_valve][]">
                              No Valve</label>
                            

                          </div>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Zipper</label>
                        <div class="col-lg-9">
                          <?php
						    $p_zipper=array();
							 if($en_product['zipper'])
							 {
								  foreach($en_product['zipper'] as $key=> $zipper)
								 {
									
									$p_zipper[] = $zipper['product_zipper_id'];
									
								 }
								
							 }
							 else{
									$p_zipper[] ='1';
								 	 $enquiry_product_zipper = array(
									 'zipper' => '',
									 'product_zipper_id'=>'',
									 );
								 } 
							$zippers = $obj_enquiry->getActiveProductZippers();
									if($zippers) {
									foreach($zippers as $zipper) {
                                 ?>
                          <div class="checkbox col-lg-6">
                            <label>
                              <input type="checkbox" class="validate[minCheckbox[1]]" value="<?php echo $zipper['product_zipper_id']; ?>"<?php if(in_array($zipper['product_zipper_id'], $p_zipper)){ echo "checked=checked";}?>  name="nproducts[<?php echo $inner_count; ?>][product_zipper][]">
                              <?php echo $zipper['zipper_name']; ?></label>
                          </div>
                          <?php }}  ?>
                        </div>
                      </div>
                      
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Spout</label>
                        <div class="col-lg-9">
                        
                        	<?php
							 $p_spout=array();
							 if($en_product['spout'])
							 {
								  foreach($en_product['spout'] as $key=> $spout)
								 {
									
									$p_spout[] = $spout['product_spout_id'];
									
								 }
								
							 }
							 else{
									$p_spout[] ='1';
								 	 $enquiry_product_spout= array(
									 'spout' => '',
									 'product_spout_id'=>'',
									 );
								 } 

							
							
								$spouts = $obj_enquiry->getActiveProductSpout();
								if($spouts) {
								foreach($spouts as $spout) {
                            ?>
                          
                          <div class="checkbox col-lg-6">
                            <label>
                              <input type="checkbox" class="validate[minCheckbox[1]]" value="<?php echo $spout['product_spout_id']; ?>"<?php if(in_array($spout['product_spout_id'], $p_spout)){ echo "checked=checked";}?>  name="nproducts[<?php echo $inner_count; ?>][product_spout][]">
                              <?php echo $spout['spout_name']; ?>
                            </label>
                          </div>
                          <?php }} ?>
                        </div>
                      </div>
               	
                 
                  <div class="form-group">              
                   	 <label class="col-lg-3 control-label">Customer Quantity </label>
                        <div class="col-lg-3">
                          <input type="text" name="nproducts[<?php echo $inner_count; ?>][number_of_pouch]" value=" <?php echo isset($en_product['no_of_pouches'])?$en_product['no_of_pouches']:'';?>"class="form-control">
                          
                        </div>
                            <label class="col-lg-3 control-label">Samples Sent On Date</label>
                         <div class="col-lg-3">
                           <input type="text" name="nproducts[<?php echo $inner_count; ?>][send_date]" readonly data-date-format="yyyy-mm-dd" value="<?php echo isset($en_product['sample_sent_date'])?$en_product['sample_sent_date']:'';?>" placeholder="Date" id="input-date_0" class="input-sm form-control datepicker"  />
                        </div>
                       
                        </div>
                          <div class="form-group">
                              <label class="col-lg-3 control-label">Remarks</label>
                                <div class="col-lg-8">
                                  <textarea class="form-control" row="10" col="15" name="nproducts[<?php echo $inner_count; ?>][remark_note]"><?php echo isset($en_product['remark_note'])?$en_product['remark_note']:'';?> </textarea>
                                </div>
                          </div>
                     </div>   
                        <!-- <div class="line m-t-large" style="margin-top:10px;" style="display:none;"></div>-->
                      <?php    
				
                $inner_count++; 
				 } }?>            
                    <!--</section>
                  </section>
                </div>
              </div> -->
              
              <div id="add-more"></div>
              
              
              <?php  /*?><div class="form-group">
                	<div class="col-lg-10"></div>
                	<div class="col-lg-2">
                	<a title="" data-placement="top" data-toggle="tooltip" class="btn btn-success btn-xs addmore-product" data-original-title="Add Product">
                    	<i class="fa fa-plus"></i> Add Product
                     </a>
                    </div>
              </div><?php */?>
               <div class="line m-t-large" style="margin-top:10px;"></div>
              <div class="form-group" style="display:none;">
              	<div class="col-lg-10">
               
                </div>
             
                <div class="col-lg-2">
                    <a title="" data-placement="top" data-toggle="tooltip" class="btn btn-success btn-xs addmore-product" data-original-title="Add Product">
                    <i class="fa fa-plus"></i> Add more
                    </a>
                </div> 
			
              <?php /*?>  <?php } else { ?>
                 <div class="col-lg-2">
                     <a class="btn btn-danger btn-xs btn-circle remove" data-toggle="tooltip" data-placement="top" title="Remove" onclick="remove(<?php echo $enquiry['enquiry_id'];?>)">
                     <i class="fa fa-minus"></i></a>
                    </a>
                    
                </div>
                <?php }?><?php */?>
              </div>
           
			
			
                <div class="form-group">
                <label class="col-lg-3 control-label">Follow Up</label>  
                <div class="col-lg-4">
                  <input type="text" name="followup_date" readonly data-date-format="yyyy-mm-dd" value="" placeholder="Date" id="followup_date" class="input-sm form-control datepicker validate[required]"/>
                           </div>
                    </div>
                           
				<!--<---kavita:24-2-2017--->
				<!--<div class="form-group" style="display:none;">		
                <label class="col-lg-3 control-label">Reminder</label>
                <div class="col-lg-4">
                  <select name="reminder" class="form-control">
                    <option value="2" <?php //echo (isset($enquiry['reminder']) && $enquiry['reminder'] == '2')?'selected':'';?> > Before 2 Days</option>
                    <option value="3" <?php //echo (isset($enquiry['reminder']) && $enquiry['reminder'] == '3')?'selected':'';?>> Before 3 Days</option>
                    <option value="5" <?php //echo (isset($enquiry['reminder']) && $enquiry['reminder'] == '5')?'selected':'';?>> Before 5 Days</option>
                  </select>
                 
                </div>
                </div>-->

								
        
                                   
              
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Enquiry specs</label>
                <div class="col-lg-8">
                  <textarea class="form-control" row="10" col="15" name="enquiry_note"></textarea>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                <div class="col-lg-4">
                  <select name="status" id="status" class="form-control">
                    <option value="1" <?php echo (isset($industry['status']) && $industry['status'] == 1)?'selected':'';?> > Active</option>
                    <option value="0" <?php echo (isset($industry['status']) && $industry['status'] == 0)?'selected':'';?>> Inactive</option>
                  </select>
                </div>
              </div>
             
              <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                  <?php if(($edit) && (!isset($_GET['request_id']) && empty($_GET['request_id']))){?>
                  <button type="submit" name="btn_update" id="btn_update" class="btn btn-primary">Update </button>
                  <?php } else { ?>
                        <button type="submit" name="btn_save" id="btn_save" class="btn btn-primary">Save </button>
                        <button type="submit" name="btn_save_new" id="btn_save_new" class="btn btn-primary">Save & New</button>
                  <?php }  ?>
                  <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '',$add_url, '',1);?>">Cancel</a>
                   </div>
              </div>
            </form>
          </div>
        </section>
      </div>
    </div>
  </section>
</section>
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

        #ajax_response,#ajax_response{
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

    </style>
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
    jQuery(document).ready(function(){
		
	jQuery("#form_nm").validationEngine();
	 getenquiry_source_value();
	//	$("#source_details").show();
        //   $("#exhibition").hide();
        // binds form submission and fields to the validation engine
        
		jQuery(".product-div").validationEngine();
			
		 edit = $("#edit_value").val();
			if(edit!='1'){
				getsize(0);
			}
		
		
    });
	
	
	$(document).ready(function() {
			
		$("#input-date_0").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
		$("#followup_date").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
		//$("#input-date").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
	
		});
	
	function removeProduct(count,enquiry_id,product_enquiry_id){
		//alert(count);
		//alert(enquiry_id);
		//alert(product_enquiry_id);
		$('#product-'+count).remove();
		var remove_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=remove', '',1);?>");
			$.ajax({
				url : remove_url,
				method : 'post',
				data : {enquiry_id : enquiry_id,product_enquiry_id:product_enquiry_id},
				success: function(response){
				//alert(response);	
				
				},
				error: function(){
					return false;	
				}
		});	
	}

	$('.addmore-product').click(function(){
		//alert("add more");
		var count = $('.product-div').length;
		//var c = $('#count_div').length + 1;
		//alert(count);
		var html = '';
		//html
		html += '<div class="form-group product-div" id="product-'+count+'">'; 
        html +='<div class="line m-t-large" style="margin-top:10px;"></div>';        
          html +='<div class="col-lg-1"></div>';
          html +='<div class="col-lg-10">';
          
               
			   html +='<div class="form-group">'; 
                 html +='<label class="col-lg-3 control-label">Product</label>';
                 html +='<div class="col-lg-8">';
                   html +='<select class="form-control validate[required]" id="product-name" name="nproducts['+count+'][product_id]">';
                     		<?php 
                             $products = $obj_enquiry->getProducts(); 
                             foreach($products as $product) {
                            ?>
                          html +='<option value="<?php echo $product['product_id']; ?>"><?php echo $product['product_name']; ?></option>';
                            <?php } ?>
                    html +='</select>';
                 html +='</div>';
               html +='</div>';
                 
               
			    html +='<div class="form-group">';   
                    html +='<label class="col-lg-3 control-label">Printing Option</label>';
                    html +='<div class="col-lg-9">';
                          html +='<div class="checkbox col-lg-6">';
                            html +='<label><input type="checkbox" class="validate[minCheckbox[1]]" value="With Printing" name="nproducts['+count+'][product_printing_option][]">With Printing</label>';
                          html +='</div>';
                                 
                          html +='<div class="checkbox col-lg-6">';
                            html +='<label><input type="checkbox" class="validate[minCheckbox[1]]" value="Without Printing" name="nproducts['+count+'][product_printing_option][]">Without Printing</label>';
                          html +='</div>';    
                    html +='</div>';
                html +='</div>';
				
				
				 html +='<div class="form-group">';   
                    html +='<label class="col-lg-3 control-label">Printing Effect</label>';
                    html +='<div class="col-lg-9">';
					
						  <?php		
							$printing_effects = $obj_enquiry->getActivePrintingEffectEnquiry(); 
							if($printing_effects) {
							foreach($printing_effects as $printing_effect) {
                          ?>
					
                          html +='<div class="checkbox col-lg-6">';
                            html +='<label><input type="checkbox" class="validate[minCheckbox[1]]" value="<?php echo $printing_effect['printing_effect_id']; ?>" name="nproducts['+count+'][product_printing_effect][]"><?php echo $printing_effect['effect_name']; ?></label>';
                          html +='</div>';
                                 
                         <?php } } ?>
                    html +='</div>';
                html +='</div>';
			   
			               
               html +='<div class="form-group">'; 
                   html +='<label class="col-lg-3 control-label">Volume</label>';
                   html +='<div class="col-lg-9">';
                       		<?php 
								$product_volumes = $obj_enquiry->getProductVolumes();
								if($product_volumes){ 
								foreach($product_volumes as $product_volume) {
                            ?>
                            html +='<div class="checkbox col-lg-4">';
                            	html +='<label><input type="checkbox" class="validate[minCheckbox[1]]" value="<?php echo $product_volume['pouch_volume_id']; ?>" name="nproducts['+count+'][product_volume][]"><?php echo $product_volume['volume']; ?></label>';
                            html +='</div>';    
                                <?php } } ?>
                   html +='</div>';
                html +='</div>';
                  
				html +='<div class="form-group">';   
                    html +='<label class="col-lg-3 control-label">Valve</label>';
                    html +='<div class="col-lg-9">';
                          html +='<div class="checkbox col-lg-6">';
                            html +='<label><input type="checkbox" class="validate[minCheckbox[1]]" value="With Valve" name="nproducts['+count+'][product_valve][]">With Valve</label>';
                          html +='</div>';
                                 
                          html +='<div class="checkbox col-lg-6">';
                            html +='<label><input type="checkbox" class="validate[minCheckbox[1]]" value="Without Valve" name="nproducts['+count+'][product_valve][]">Without Valve</label>';
                          html +='</div>';    
                    html +='</div>';
                html +='</div>';
				
				html +='<div class="form-group">';   
                    html +='<label class="col-lg-3 control-label">Zipper</label>';
                    html +='<div class="col-lg-9">';
                         <?php 
							$zippers = $obj_enquiry->getActiveProductZippers();
							if($zippers) {
							foreach($zippers as $zipper) {
                         ?>
                            html +='<div class="checkbox col-lg-6">';
                            	html +='<label><input type="checkbox" class="validate[minCheckbox[1]]" value="<?php echo $zipper['product_zipper_id']; ?>" name="nproducts['+count+'][product_zipper][]"><?php echo $zipper['zipper_name']; ?></label>';
                            html +='</div>';    
                         <?php } } ?>  
                    html +='</div>';
                html +='</div>';
                
				
				html +='<div class="form-group">';   
                    html +='<label class="col-lg-3 control-label">Spout</label>';
                    html +='<div class="col-lg-9">';
					      <?php	 	
							$spouts = $obj_enquiry->getActiveProductSpout();
							if($spouts) {
							foreach($spouts as $spout) {
                          ?>	
						  						
                          html +='<div class="checkbox col-lg-6">';
                            html +='<label><input type="checkbox" class="validate[minCheckbox[1]]" value="<?php echo $spout['product_spout_id']; ?>" name="nproducts['+count+'][product_spout][]"><?php echo $spout['spout_name']; ?></label>';
                          html +='</div>';
						  
                          <?php } } ?>       
                             
                    html +='</div>';
                html +='</div>';
				  html +='<div class="form-group">';              
					  html +='<label class="col-lg-3 control-label">No.Of Items</label>';
							html +=' <div class="col-lg-3">';
								  html +='<input type="text" name="nproducts['+count+'][number_of_pouch]" value=""class="form-control">';
							html +='   </div>';
								   html +='<label class="col-lg-3 control-label">Sample Sent On Date</label>';
										 html +='<div class="col-lg-3">';
											 html +='<input type="text" name="nproducts['+count+'][send_date]" readonly="readonly" data-date-format="yyyy-mm-dd" value="" placeholder="Date" id="input-date_'+count+'" class="input-sm form-control datepicker" onclick="show_date('+count+')" />';
										 html +='</div>';
								   
							html +='</div>';
								  
						   html +=' <div class="form-group">';
									html +='<label class="col-lg-3 control-label">Remark</label>';
									 html +=' <div class="col-lg-8">';
										html +='<textarea class="form-control" row="10" col="15" name="nproducts['+count+'][remark_note]"></textarea>';
									  html +='</div>';
								html +='</div>';
				             	
           
        html +='</div>';
		
		html += '<div class="col-lg-1">';
             html +='<a onclick="removeProduct('+count+')" data-original-title="Remove Product" class="btn btn-danger btn-xs btn-circle" data-toggle="tooltip" data-placement="top" title=""><i class="fa fa-minus"></i></a>';
        html += '</div>';
		
      html +='</div>';
	  
	  
	  $('#add-more').append(html);
	  show_date(count);

});

function show_date(n)
{
	$("#input-date_"+n).datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
}

//mani change 14-4-2017
   
	
	var currentRequest1 = null;
	$("#company_name").keyup(function (event) {
		var keyword = $("#company_name").val();
		//alert(keyword);
		if (keyword.length)
		{
			if (event.keyCode != 40 && event.keyCode != 38)
			{
				var product_code_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=company_detail', '', 1); ?>");
				$("#loading").css("visibility", "visible");
				currentRequest1 = $.ajax({
					type: "POST",
					url: product_code_url,
					data: "company_name=" + keyword,
					beforeSend : function()    {           
                        if(currentRequest1 != null) {
                            currentRequest1.abort();
                        }
                    },
					success: function (msg) {
						var msg = $.parseJSON(msg);
						var div = '<ul class="list">';

						if (msg.length > 0)
						{
							for (var i = 0; i < msg.length; i++)
							{
							div = div + '<li><a href=\'javascript:void(0);\' company_name_id="' + msg[i].address_book_id + '" c_address="' + msg[i].c_address + '"  c_id="' + msg[i].company_address_id + '"f_id="' + msg[i].factory_address_id + '"company_name="' + msg[i].company_name + '" website="' + msg[i].website + '" email_1="' + msg[i].email_1 + '" industry="' + msg[i].industry + '" country="'+msg[i].country+'" phone_no="'+msg[i].phone_no+'" ><span class="bold" >' + msg[i].company_name + '</span></a></li>';
							} 
						}

						div = div + '</ul>';
                                              //  alert(div);
						if (msg != 0)
							$("#ajax_response").fadeIn("slow").html(div); 
						else
						{
							$("#ajax_response").fadeIn("slow");
							$("#ajax_response").html('<div style="text-align:left;">No Matches Found</div>');
							$("#website").val('');
						//	$("#email").val('');
							$("#customer_address ").val('');
							$("#company_name_id").val('');
							$("#company_address_id").val('');
							$("#factory_address_id").val('');
							$("#country_id").val('');
							$("#mobile_number").val('');
							
							
						}
						$("#loading").css("visibility", "hidden");
					}
				});
			} else
			{
				switch (event.keyCode)
				{
					case 40:
						{
							found = 0;
							$(".list li").each(function () {
								if ($(this).attr("class") == "selected")
									found = 1;
							});
							if (found == 1)
							{
								var sel = $(".list li[class='selected']");
								sel.next().addClass("selected");
								sel.removeClass("selected");
							} else
								$(".list li:first").addClass("selected");
							if ($(".list li[class='selected'] a").text() != '')
							{
								$("#company_name").val($(".list li[class='selected'] a").text());
								$("#website").val($(".list li[class='selected'] a").attr("website"));
								$("#email").val($(".list li[class='selected'] a").attr("email_1"));
								$("#customer_address").val($(".list li[class='selected'] a").attr("c_address"));
								$("#company_name_id").val($(".list li[class='selected'] a").attr("company_name_id"));
								$("#company_address_id").val($(".list li[class='selected'] a").attr("c_id"));
								$("#factory_address_id").val($(".list li[class='selected'] a").attr("f_id"));
                                $("#industry").val($(".list li[class='selected'] a").attr("industry"));
								$("#country_id").val($(".list li[class='selected'] a").attr("country"));
								$("#mobile_number").val($(".list li[class='selected'] a").attr("phone_no"));
								
							}
						}
						break;
					case 38:
						{
							found = 0;
							$(".list li").each(function () {
								if ($(this).attr("class") == "selected")
									found = 1;
							});
							if (found == 1)
							{
								var sel = $(".list li[class='selected']");
								sel.prev().addClass("selected");
								sel.removeClass("selected");
							} else
								$(".list li:last").addClass("selected");
							if ($(".list li[class='selected'] a").text() != '')
							{
								$("#company_name").val($(".list li[class='selected'] a").text());
								$("#website").val($(".list li[class='selected'] a").attr("website"));
								$("#email").val($(".list li[class='selected'] a").attr("email_1"));
								$("#customer_address").val($(".list li[class='selected'] a").attr("c_address"));
								$("#company_name_id").val($(".list li[class='selected'] a").attr("company_name_id"));
								$("#company_address_id").val($(".list li[class='selected'] a").attr("c_id"));
								$("#factory_address_id").val($(".list li[class='selected'] a").attr("f_id"));
                                $("#industry").val($(".list li[class='selected'] a").attr("industry"));
								$("#country_id").val($(".list li[class='selected'] a").attr("country"));
								$("#mobile_number").val($(".list li[class='selected'] a").attr("phone_no"));
							}
						}
						break;
				}
			}
		} else
		{
			$("#ajax_response").fadeOut('slow');
			$("#ajax_response").html("");
		}
	});

	$('#company_name').keydown(function (e) {
		if (e.keyCode == 9) {
			$("#ajax_response").fadeOut('slow');
			$("#ajax_response").html("");
		}
	});

	$("#ajax_response").mouseover(function () {
		$(this).find(".list li a:first-child").mouseover(function () {
			$("#website").val($(this).attr("website"));
			$("#email").val($(this).attr("email_1"));
			$("#customer_address").val($(this).attr("c_address"));
			$("#company_name_id").val($(this).attr("company_name_id"));
			$("#company_address_id").val($(this).attr("c_id"));
			$("#industry").val($(this).attr("industry"));
			$("#country_id").val($(this).attr("country"));
			$("#mobile_number").val($(this).attr("phone_no"));
			$(this).addClass("selected");
		});
		$(this).find(".list li a:first-child").mouseout(function () {
			$(this).removeClass("selected");
			$("#website").val('');
			//$("#email").val('');
			$("#customer_address").val('');
			$("#company_name_id").val('');
			$("#company_address_id").val('');
			$("#industry").val('');
			$("#country_id").val('');
			$("#mobile_number").val('');
		});
		$(this).find(".list li a:first-child").click(function () {
			if($(this).attr("website")!='null')
			   $("#website").val($(this).attr("website"));
			else
				$("#website").val('');
			
			if($(this).attr("email_1")!='null')
				$("#email").val($(this).attr("email_1"));
			else{
				$("#email").val('');
			}
			if($(this).attr("c_address")!='null')
				$("#customer_address").val($(this).attr("c_address"));
			else
				$("#customer_address").val('');
			
			if($(this).attr("phone_no")!='null')
				$("#mobile_number").val($(this).attr("phone_no"));
			else
				$("#mobile_number").val('');
			
			$("#country_id").val($(this).attr("country"));
			
			$("#company_name_id").val($(this).attr("company_name_id"));
			$("#company_address_id").val($(this).attr("c_id"));
			$("#industry ").val($(this).attr("industry"));
			$("#company_name").val($(this).text());
			$("#ajax_response").fadeOut('slow');
			$("#ajax_response").html("");
		});
	});
	 
//kavita31-3-2017

function getenquiry_source_value()
{
	var val = $("#enquiry_source_id option:selected").val();
	//alert(val);

	if (val == '7')
	{
		$("#exhibition").show();
		$("#source_details").hide();

	} else
	{
		$("#source_details").show();
		$("#exhibition").hide();
	}
}
//end31-3-2017

function getsize(count){
	var product_id = $('#product-name').val();
	//alert(product_id);
	//alert(count);
		//alert(enquiry_id);
		//alert(product_enquiry_id);
		//$('#volume').removeAttr('checked');
		var size_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=get_size', '',1);?>");
			$.ajax({
				url : size_url,
				method : 'post',
				data : {product_id : product_id,count:count},
				success: function(response){
				//alert(response);
				
				//console.log(response);	
				$('#volume_div').html(response);
				//var val ='value='+response;
			//	$('input:checkbox').removeAttr('checked');
				//$("#volume:checkbox[value="+response+"]").prop("checked","true");
				// $('#volume_'+response).prop("checked","true");
				},
				error: function(){
					return false;	
				}
		});	
	
}
$("#email").change(function(){
    var email = $(this).val();
    var url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=check_customer', '',1);?>");
    $.ajax({
				url : url,
				method : 'post',
				data : {email : email},
				success: function(response){
				    if(response!='0')
				    {
				        $("#email_msg").html(response);
				        $("#email").val('');
				    }
				},
				error: function(){
					return false;	
				}
		});	
});
</script> 
<!-- Close : validation script -->

<?php
 } 
else
{  
		include(DIR_ADMIN.'access_denied.php');
		
}
?>


