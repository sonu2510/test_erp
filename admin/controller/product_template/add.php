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
if(!$obj_general->hasPermission('add',$menuId)){
	$display_status = false;
}
//Close : edit

$quantity_error = '';
if($display_status){
	$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
	$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
	if(isset($_POST['btn_save'])){
		//printr($_POST);die;
		//echo "Sdada";die;
		$order_id = $obj_template->addTemplate($_POST['templateid']);
		page_redirect($obj_general->link($rout, '', '',1));
	}
	if(isset($_POST['btn_update'])){
		//echo "Sdada";die;
		
		$order_id = $obj_template->updateTemplate($_POST);
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	
$template_id = 0;
if(isset($_GET['template_id']) && !empty($_GET['template_id'])){
	//if(!$obj_general->hasPermission('edit',$menuId)){
	//	$display_status = false;
	//}else{
		$template_id = base64_decode($_GET['template_id']);
		$product = $_GET['product_id'];
		$template = $obj_template->getTemplateInfo($template_id);
		
		$detailslist = $obj_template->getaddProductDetails($template_id,$product);
		//printr($detailslist);//die;
		//$edit = 1;
	//}
//}else{
	//if(!$obj_general->hasPermission('add',$menuId)){
		//$display_status = false;
	//}
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
        
        <div class="col-lg-10">
        	<section class="panel">
              <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
              <form class="form-horizontal" method="post" name="form" id="order-form" enctype="multipart/form-data">
                    <div class="panel-body">
                    	<div class="col-lg-6" style="width:100%">
                           <h4><i class="fa fa-edit"></i> General Details</h4>
                             
                           <div class="line m-t-large" style="margin-top:-4px;"></div><br/>
                          
                           <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span> Title</label>
                        <div class="col-lg-8">
                             <input type="text" name="title" placeholder="Template Title" value="<?php echo isset($template['title']) ? $template['title'] : '';?>" class="form-control validate[required]">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span> Select Product</label>
                         
                        <div class="col-lg-8">
                            <?php
                            $products = $obj_template->getActiveProduct();
                            ?>
                            <select name="product" id="product" class="form-control validate[required]" >
                                <?php
								$sel_product= isset($template['product_name'])?$template['product_name']:'';
					            foreach($products as $product){
                                    if($sel_product && $sel_product == $product['product_id']){
                                        echo '<option value="'.$product['product_id'].'" selected="selected" >'.$product['product_name'].'</option>';
                                    }else{
                                        echo '<option value="'.$product['product_id'].'">'.$product['product_name'].'</option>';
                                    }
                                } ?>
                            </select>
                        </div>
                      </div>
                    
                       <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span> Shipment Country</label>
                        <div class="col-lg-8">
                       
                        	<?php /*?><?php
							$selCountry = ''; 
							if(isset($template['country']) && $template['country']){
								$selCountry = $template['country'];
							}
							echo $obj_general->getCountryCombo($selCountry);//214?><?php */?>
                            <?php 
							$countries=$obj_template->getCountry();
							?>
                            
                            <select name="country_id[]" id="country_id"  multiple="multiple" >
                            
                             <?php 
							 $country_val= isset($template['country'])?$template['country']:'';
							 
							 $country_id = json_decode($country_val);
							
							 foreach($countries as $country)
							 {	  if(in_array( $country['country_id'],$country_id)){
									    echo '<option value="'.$country['country_id'].'" selected="selected" >'.
										$country['country_name'].'</option>';
                                    }else{
                                        echo '<option value="'.$country['country_id'].'">'.$country['country_name'].'</option>';
                                    }
							 
							}
							 ?>
                            </select>
                        </div>
                        
                      </div>
                       <div class="form-group">
                        <label class="col-lg-3 control-label" id="countrylabel" style="display:none"> </label>
                        <div style="display:none" id="country_span" name="country_span">
                        
                         </div>
                      </div>
                      
                      
                        <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span> For User</label>
                        <div class="col-lg-8">
                        	<?php
							  $users = $obj_template->getInternational();
							//printr($users);
							 //die;
                            ?>
                            <select name="user" id="user" class="form-control validate[required]" >
                               <option value="">Select User</option>
                                <?php
								$sel_user= isset($template['user'])?$template['user']:'';
					            foreach($users as $user){
                                    if($sel_user && $sel_user == $user['international_branch_id']){
                                        echo '<option value="'.$user['international_branch_id'].'" selected="selected" >'.
										$user['first_name'] . " " .$user['last_name'].'</option>';
                                    }else{
                                        echo '<option value="'.$user['international_branch_id'].'">'.$user['first_name'] . " " .$user['last_name'].'</option>';
                                    }
                                }
                              ?>  
                            </select>
						</div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span> Currency</label>
                        <div class="col-lg-8">
                        	<?php
							  $currency = $obj_template->getCurrency();
							// printr($internationalassociates);
							 //die;
                            ?>
                            <select name="currency" id="currency" class="form-control validate[required]" >
                               <option value="">Select Currency</option>
                                <?php
								$sel_curr= isset($template['currency'])?$template['currency']:'';
					            foreach($currency as $curr){
                                    if($sel_curr && $sel_curr == $curr['currency_id']){
                                        echo '<option value="'.$curr['currency_id'].'" selected="selected" >'.$curr['currency_code'].'</option>';
                                    }else{
                                        echo '<option value="'.$curr['currency_id'].'">'.$curr['currency_code'].'</option>';
                                    }
                                }
                               // foreach($currency as $curr){
									//printr($curr);
								?>
                            </select>
						</div>
                      </div>
                           
                      <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span> Transportation Type</label>
                        <div class="col-lg-9">                
                        	<div  style="float:left;width: 200px;">
                             
                                <?php if(isset($template['transportation_type']) && $template['transportation_type'] == 'By Sea')
								{
									?>
                                       <label  style="font-weight: normal;">
								 <input type="radio" name="transport" id="transport" value="By Air"  >
                                By Air	
                                 </label>	
                                
                                  <label  style="font-weight: normal;">
                                  	<input type="radio" name="transport" id="transport" value="By Sea" checked="checked">
                              	 By Sea
                                 </label> 
                                    <label  style="font-weight: normal;">
                                 <input type="radio" name="transport" id="transport" value="By Pickup"  >
                                By Pickup	
                                 </label>	
							<?php	}
							elseif(isset($template['transportation_type']) && $template['transportation_type'] == 'By Air')
							{
								?>
                                 <input type="radio" name="transport" id="transport" value="By Air" checked="checked" >
                                By Air	
                                 </label>	
                                  <label  style="font-weight: normal;">
                                  	<input type="radio" name="transport" id="transport" value="By Sea">
                              	 By Sea
                                 </label>
                                  <label  style="font-weight: normal;">
                                 <input type="radio" name="transport" id="transport" value="By Pickup"  >
                                By Pickup	
                                 </label>	
                               <?php
							}
							else
							{
								?>
                                 <input type="radio" name="transport" id="transport" value="By Air" >
                                By Air	
                                 </label>	
                                  <label  style="font-weight: normal;">
                                  	<input type="radio" name="transport" id="transport" value="By Sea">
                              	 By Sea
                                 </label>
                                  <label  style="font-weight: normal;">
                                 <input type="radio" name="transport" id="transport" value="By Pickup"  checked="checked" >
                              By Pickup	
                                 </label>	
                               <?php
							}
							?>
                            </div> 
                        </div>
                      </div>
                      
                      <div class="col-lg-12" id="add-product-div">
                           <h4><i class="fa fa-plus-circle"></i> Add Product</h4>
                           
                           <div class="line m-t-large" style="margin-top:-4px;"></div><br/>
                           <div class="form-group">
                                    <label class="col-lg-3 control-label"><span class="required">*</span>Size</label>
                                    <div class="col-lg-8 table-responsive">
                                     <section class="panel">
                                     <table class="table table-striped b-t text-small">
                                        <thead>
                                            <tr>
                                                <th width="20%" class="text-center">Width</th>
                                                <th width="20%" class="text-center">Height</th>
                                                 <?php if(isset($_GET['product_id']) && $_GET['product_id'] != '18')
														{ 
															echo '<th width="20%" class="text-center">Gusset</th>';
														} ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><input type="text" name="width" id="width" value="" class="form-control validate[required,custom[onlyNumberSp]] test"></td>
                                                <td><input type="text" name="height" id="height" value="" class="form-control validate[required,custom[onlyNumberSp]]"></td>
                                                <?php if(isset($_GET['product_id']) && $_GET['product_id'] != '18')
														{ 
															echo '<td><input type="text" name="gusset" id="gusset" class="form-control validate[required,custom[onlyNumberSp]] gusset"></td>';
														} ?>				
                                                
                                                 
                                            </tr>
                                        </tbody>
                                     </table>
                                     </section>
                                    </div>
                                  </div>
                                   <?php
                                  $volumes = $obj_template->getActiveProductVolume();
                                  if($volumes){
                                      ?>
                                      <div class="form-group">
                                        <label class="col-lg-3 control-label">Volume</label>
                                        <div class="col-lg-8" id="squantity">
                                           <select name="volume" id="volume" class="form-control">
                                                <?php 
                                                  foreach($volumes as $volume) {
                                                ?>
                                                    <option value="<?php echo $volume['volume']; ?>"><?php echo $volume['volume']; ?></option>
                                                <?php } ?>    
                                           </select>
                                        </div>
                                      </div>
                                      <?php
                                  } ?>
                                  
                                <?php /*?>  <div class="form-group quantity_in">
                                    <label class="col-lg-3 control-label">Quantity In</label>
                                    <div class="col-lg-7">
                                         <select name="quantity_type" id="quantity_type" class="form-control">
                                            <option value="meter">Meter</option>
                                            <option value="kg">Kgs</option>
                                            <option value="pieces">Pieces</option>
                                        </select>
                                    </div>
                                  </div><?php */?>
                              
                            
                        
                <?php /*?>        <?php
						 $quantities = $obj_template->getQuantities();
						 //printr($quantities);
						 
						// die;
						 foreach($quantities as $quantity)
						 {
						?>
                        	<div class="checkbox" style="float: left; width: 30%;"> 
                             <label>
                            <input type="checkbox" name="quantity[]" id="quantity" value="<?php echo encode($quantity['quantity']) ;?>" />
                            
							<?php echo $quantity['quantity'] ;?>
                             </label>
                             </div>
                           
                          <?php
							
						 }
						 ?>
                  	
                        </div><?php */?>
                      </div>
                      
                     <div class="form-group">
                                    <label class="col-lg-3 control-label"><span class="required">*</span>Quantity</label>
                                    <div class="col-lg-8 table-responsive" style="width: 75%;">
                                     <section class="panel">
                                     <table class="table table-striped b-t text-small">
                                        <thead>
                                            <tr>
                                           <?php if(isset($_GET['product_id']) && $_GET['product_id'] == '18')
												{ ?>
                                                	
                                                    <th class="text-center"> 100+</th>
                                                    <th class="text-center"> 200+</th>
                                                    <th class="text-center">500+</th>
                                                     <th class="text-center">1000+</th>
										 <?php	}
										        else if(isset($_GET['product_id']) && $_GET['product_id'] == '61')
												{ ?>
                                                	
                                                    <th class="text-center"> 10000+</th>
                                                    <th class="text-center"> 15000+</th>
                                                    <th class="text-center">20000+</th>
                                                     <th class="text-center">30000+</th>
                                                      <th class="text-center">50000+</th>
                                                       <th class="text-center">100000+</th>
										 <?php	}
										        else if(isset($_GET['product_id']) && ($_GET['product_id'] == '47' ||  $_GET['product_id'] == '48'))
												{ ?>
                                                	
                                                    <th class="text-center"> 1000+</th>
                                                    <th class="text-center"> 2000+</th>
                                                    <th class="text-center">5000+</th>
                                                     <th class="text-center">10000+</th>
                                                      <th class="text-center">50000+</th>
                                                       <th class="text-center">100000+</th>
										 <?php	}
												else
												{	?>
                                                	 <th class="text-center"> 1000+</th>
                                               	 	<th class="text-center"> 2000+</th>
                                               		 <th class="text-center">5000+</th>
                                                 	 <th class="text-center">10000+</th>
													
										  <?php } ?>
                                               
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><input type="text" name="first" id="first" value="" class="form-control validate"></td>  
                                                <td><input type="text" name="second" id="second" value="" class="form-control validate" ></td>				
                                                <td><input type="text" name="third" id="third" class="form-control validate" ></td>
                                                <td><input type="text" name="fourth" id="fourth" class="form-control validate"></td>
                                               <?php if(isset($_GET['product_id']) && ($_GET['product_id'] == '61' || $_GET['product_id'] == '47' ||  $_GET['product_id'] == '48')) {
                                                       echo '<td><input type="text" name="fifth" id="fifth" class="form-control validate"></td>
                                                            <td><input type="text" name="sixth" id="sixth" class="form-control validate"></td>'; 
                                                    }?>
                                            </tr>
                                        </tbody>
                                     </table>
                                     </section>
                                    </div>
                                  </div>  
                                  
                             <div class="form-group option">
                        <label class="col-lg-3 control-label">Valve</label>
                        <div class="col-lg-9">                
                        	<div  style="float:left;width: 200px;">
                                <label  style="font-weight: normal;">
                                  <input type="radio" name="valve[]" id="nv" checked="checked" value="No Valve"  class="valve" >
                                 No Valve
                                 </label>
                            
                                <label  style="font-weight: normal;">
                                  	<input type="radio" name="valve[]" id="wv" value="With Valve" class="valve">
                              	   With Valve
                                 </label>
                              </div> 
                        </div>
                      </div>
                      
                       <?php
						  $zippers = $obj_template->getActiveProductZippers();
						 // printr($accessories);
						 // die;
						  if($zippers){
							  ?>
							  <div class="form-group option">
									<label class="col-lg-3 control-label">Zipper</label>
									<div class="col-lg-9">
									   <?php
									   $accessorieTxt = '';
									   $i=1;
										foreach($zippers as $zipper){
										   $accessorieTxt .= '<div style="float:left;width: 100px;">';
												$accessorieTxt .= '<label  style="font-weight: normal;">';
												if($zipper['product_zipper_id'] == 2 )
												{
													$accessorieTxt .= '<input type="radio" name="zipper[]" value="'.$zipper['zipper_name'].'" 
													id="zipper_'.$i.'" checked="checked" class="zipper">';
												}
												else
												{
													$accessorieTxt .= '<input type="radio" name="zipper[]" value="'.$zipper['zipper_name'].'" 
													id="zipper_'.$i.'" class="zipper">';
												}
													$accessorieTxt .= ''.$zipper['zipper_name'];
												$accessorieTxt .= '</label>';
											$accessorieTxt .= '</div>';
											$i++;
										}
										echo $accessorieTxt;
										?>
									</div>
								</div>
								<?php
							} ?>
                      <?php
                      $spouts = $obj_template->getActiveProductSpout();
					  if($spouts){
						  ?>
                      	  <div class="form-group option">
                                <label class="col-lg-3 control-label">Spout</label>
                                <div class="col-lg-9">
                                   <?php
                                   $spoutsTxt = '';
								     $i=1;
                                    foreach($spouts as $spout){
                                       $spoutsTxt .= '<div  style="float:left;width: 100px;">';
                                            $spoutsTxt .= '<label  style="font-weight: normal;">';
											if($spout['product_spout_id'] == 1 )
											{
                                                $spoutsTxt .= '<input type="radio" name="spout[]" value="'.$spout['spout_name'].'" 
												id="spout_'.$i.'" class="spout" checked="checked">';
											}
											else 
											{
												$spoutsTxt .= '<input type="radio" name="spout[]" value="'.$spout['spout_name'].'" 
												id="spout_'.$i.'" class="spout" >';
											}
                                            $spoutsTxt .= ''.$spout['spout_name'].'</label>';
                                        $spoutsTxt .= '</div>';
									$i++;
                                    }
                                    echo $spoutsTxt;
                                    ?>
                                </div>
                            </div>
                      	  	<?php
					  	} ?>
                        
                        <?php
						  $accessories = $obj_template->getActiveProductAccessorie();
						  if($accessories){
							  ?>
							  <div class="form-group option">
									<label class="col-lg-3 control-label">Accessorie</label>
									<div class="col-lg-9">
									   <?php
									   $accessorieTxt = '';
									   $i=1;
										foreach($accessories as $accessorie){
										   $accessorieTxt .= '<div style="float:left;width: 100px;">';
												$accessorieTxt .= '<label  style="font-weight: normal;">';
												if($accessorie['product_accessorie_id'] == 4 )
												{
													$accessorieTxt .= '<input type="radio" name="accessorie[]" class="accessorie"
													value="'.$accessorie['product_accessorie_name'].'" id="accessorie_'.$i.'" checked="checked">';
												}
												else
												{
													$accessorieTxt .= '<input type="radio" name="accessorie[]" class="accessorie" 
													value="'.$accessorie['product_accessorie_name'].'" id="accessorie_'.$i.'">';
												}
													$accessorieTxt .= ''.$accessorie['product_accessorie_name'];
												$accessorieTxt .= '</label>';
											$accessorieTxt .= '</div>';
											$i++;
										}
										echo $accessorieTxt;
										?>
									</div>
								</div>
								<?php
							} ?>
                            
                             <?php
                      $colors = $obj_template->getActiveColor();
					  if($colors){
						  ?>
                      	  <div class="form-group option">
                                <label class="col-lg-3 control-label">Color</label>
                                <div class="col-lg-9">
                                   <?php
                                   $spoutsTxt = '';
								   $i=0;
                                    foreach($colors as $color){
										//printr($color);
										//die;
                                       $spoutsTxt .= '<div style="float:left;width: 150px;">';
                                            $spoutsTxt .= '<label  style="font-weight: normal;">';
											if($color['pouch_color_id'] == 1 )
											{
                                                $spoutsTxt .= '<input type="checkbox" id="color'.$i.'" name="color[]" value="'.$color['pouch_color_id'].'" checked="checked" class="colortemp" >';
											}
											else 
											{
												$spoutsTxt .= '<input type="checkbox" id="color'.$i.'" name="color[]" value="'.$color['pouch_color_id'].'" class="colortemp" >';
											}
                                            $spoutsTxt .= ''.$color['color'].'</label>';
                                        $spoutsTxt .= '</div>';
										$i++;
                                    }
                                    echo $spoutsTxt;
                                    ?>
                                </div>
                            </div>
                      	  	<?php
					  	} ?>
                             <div class="file-preview" style="display:none">
                                   		<div class="file-preview-thumbnails">
                                		</div>
                                   		<div class="clearfix"></div>
                                   		<div class="file-preview-status text-center text-success"></div>
                                   		<div class="kv-fileinput-error file-error-message" style="display: none;"></div>
                                	</div>
                                </div>
                              </div>
                              <div><input type="hidden" name="templateid" id="templateid" value="<?php echo $template_id;?>" /></div>
                             <div class="form-group">
                                <div class="col-lg-9 col-lg-offset-3">
                                  <button type="button" id="btn-add-product" class="btn btn-primary">Add Product</button> 
                                    
                                  <button type="button" style="display:none" name="btn-update-product" id="btn-update-product" class="btn btn-primary">Update Product</button> 	<a  id="btn-all-check" class="label bg-success selectall mt5"  onclick="javascript:checkall('form', true)">Select All</a>
            <a id="btn-all-uncheck" class="label bg-warning unselectall mt5"  onclick="javascript:uncheckall('form', true)">Unselect All</a>                    </div>
                             </div>
                               <div id="display-product">
                             <?php if(isset($template['product_template_id']))
							 	{
							 ?>
							<div class="table-responsive">
                           <table class="table table-bordered">
							<thead>
                                <tr>
                                    <th>Type Of Pouch</th>
                                    <th>Size</th>
                                    <th >Dimension<br />
                                    WxLxG
                                    </th>
                                    <?php 
											if(isset($_GET['product_id']) && $_GET['product_id'] == '18')
											{
												$qty100 = ' Qty100+';
												$qty200 = ' Qty200+';
												$qty500 = ' Qty500+';;
												$qty1000 = ' Qty1000+';
											}
											else if(isset($_GET['product_id']) && $_GET['product_id'] == '61')
											{
												$qty100 = ' Qty10000+';
                                				$qty200 = ' Qty15000+';
                                				$qty500 = ' Qty20000+';;
                                				$qty1000 = ' Qty30000+';
                                				$qty2000 = ' Qty50000+';
                                				$qty3000 = ' Qty100000+';
											}
											else if(isset($_GET['product_id']) && ($_GET['product_id'] == '47' || $_GET['product_id'] == '48'))
                                			{
                                				$qty100 = ' Qty1000+';
                                				$qty200 = ' Qty2000+';
                                				$qty500 = ' Qty5000+';;
                                				$qty1000 = ' Qty10000+';
                                				$qty2000 = ' Qty50000+';
                                				$qty3000 = ' Qty100000+';
                                			}
											else
											{
												$qty100 = ' Qty1000+';
												$qty200 = ' Qty2000+';
												$qty500 = ' Qty5000+';
												$qty1000 = ' Qty10000+';
											}
									 ?>
                                    
                                    <th >Price <?php 
									$currency = $obj_template->getCurrency();
									 foreach($currency as $curr){
                                    if($sel_curr && $sel_curr == $curr['currency_id']){
                                        echo "(".$curr['currency_code'].")";
                                    }}
										echo '<br>'.$qty100;
									 ?>
                                    </th>
                                    <th>Price <?php 
									$currency = $obj_template->getCurrency();
									 foreach($currency as $curr){
                                    if($sel_curr && $sel_curr == $curr['currency_id']){
                                        echo "(".$curr['currency_code'].")";
                                    }}
										echo '<br>'.$qty200;
									 ?> 
                                   
                                    </th>
                                    <th>Price <?php 
									$currency = $obj_template->getCurrency();
									 foreach($currency as $curr){
                                    if($sel_curr && $sel_curr == $curr['currency_id']){
                                        echo "(".$curr['currency_code'].")";
                                    }}
										echo '<br>'.$qty500;
									 ?> 
                                    </th>	 
                                    <th >Price <?php 
									$currency = $obj_template->getCurrency();
									 foreach($currency as $curr){
                                        if($sel_curr && $sel_curr == $curr['currency_id']){
                                            echo "(".$curr['currency_code'].")";
                                        }
									    
									 }
										echo '<br>'.$qty1000;
									 ?>
                                    </th>
                                    <?php if(isset($_GET['product_id']) && ($_GET['product_id'] == '61' || $_GET['product_id'] == '47' || $_GET['product_id'] == '48' )) { ?>
                                    <th >Price <?php 
									$currency = $obj_template->getCurrency();
									 foreach($currency as $curr){
                                        if($sel_curr && $sel_curr == $curr['currency_id']){
                                            echo "(".$curr['currency_code'].")";
                                        }
									    
									 }
										echo '<br>'.$qty2000;
									 ?>
                                    </th>
                                    <th >Price <?php 
									$currency = $obj_template->getCurrency();
									 foreach($currency as $curr){
                                        if($sel_curr && $sel_curr == $curr['currency_id']){
                                            echo "(".$curr['currency_code'].")";
                                        }
									    
									 }
										echo '<br>'.$qty3000;
									 ?>
                                    </th>
                                    
                                    <?php } ?>
                                    
                                    <th>
                                    Color
                                    </th>
                                    <th>Action</th>
                                </tr> 
							</thead>
							<tbody>
                            <?php  
							foreach($detailslist as $details){
			 				
								if(isset($_GET['product_id']) && $_GET['product_id'] == '18')
								{
									$qty100 = $details['quantity100'];
									$qty200 = $details['quantity200'];
									$qty500 = $details['quantity500'];
									$qty1000 = $details['quantity1000'];
								}
								else if(isset($_GET['product_id']) && $_GET['product_id'] == '61')
								{
									$qty100 = $details['quantity10000'];
                        			$qty200 = $details['quantity15000'];
                        			$qty500 = $details['quantity20000'];
                        			$qty1000 = $details['quantity30000'];
                        			$qty2000 = $details['quantity50000'];
                    				$qty3000 = $details['quantity100000'];
								}
								else if(isset($_GET['product_id']) && ($_GET['product_id'] == '47' || $_GET['product_id'] == '48'))
                                {
                                	$qty100 = $details['quantity1000'];
                                	$qty200 = $details['quantity2000'];
                                	$qty500 = $details['quantity5000'];
                                	$qty1000 = $details['quantity10000'];
                                	$qty2000 = $details['quantity50000'];
                                	$qty3000 = $details['quantity100000'];
                                }
								else
								{
									$qty100 = $details['quantity1000'];
									$qty200 = $details['quantity2000'];
									$qty500 = $details['quantity5000'];
									$qty1000 = $details['quantity10000'];
								}
							
							
							
			 				$spout='';
							$accessorie = '';
							if($details['spout'] != 'No Spout')
									{
										$spout = 'with '.$details['spout'];
									}
									if($details['accessorie'] != 'No Accessorie')
									{
										$accessorie = 'with '.$details['accessorie'];
									}
									
                                       $dataresult =  strtoupper(substr(preg_replace('/(\B.|\s+)/','',$details['product_name']),0,5)).
									   ' '.$details['zipper'].' '.$details['valve'].'<br> '.$spout.' '.$accessorie; 
						?>
                                                 
						<tr id='<?php echo $details['product_template_size_id'];?>'>
                            <td><?php echo $dataresult;?></td>
                            <td><?php echo $details['volume'];?></td>
                            <td><?php echo $details['width'].'X'.$details['height'].'X'.$details['gusset'];?></td>
                            <td><?php echo $qty100; ?></td> 
                            <td><?php echo $qty200; ?></td> 
                            <td><?php echo $qty500; ?></td> 
                            <td><?php echo $qty1000; ?></td>
                            <?php if(isset($_GET['product_id']) && ($_GET['product_id'] == '61' || $_GET['product_id'] == '47' || $_GET['product_id'] == '48'))
                                {
                                    echo '<td>'.$qty2000.'</td>';
                                     echo '<td>'.$qty3000.'</td>';
                                }
                                
                            ?>
                            <td>			
                            <?php $colorval=json_decode($details['color']);	
                            $color_detail='';
                            ?>
                            <select  name="color_combo" id="color_combo" class="form-control">
                            <?php foreach($colorval as $value)
                            {
                                //printr($value);
                                $color_detail=$obj_template->getColor($value);
                            ?>
                                <option value="<?php echo $color_detail[0]['pouch_color_id'];?>"><?php echo $color_detail[0]['color'];?></option>
                            <?php	
                            }
                            ?>
                            </select></td>
                            <td class="del-product">
                            	
                             <!--   <a class="btn btn-danger btn-sm" href="javascript:void(0);" 
                                onClick="removeTemplate(<?php //echo $details['product_template_size_id'];?>)"><i class="fa fa-trash-o"></i></a>-->
                                <a href="javascript:void(0);" 
                                onClick="getTemplate(<?php echo $details['product_template_size_id'];?>)" class="btn btn-info btn-xs">Edit</a>
                            </td>
                        </tr> 
                        <?php }
                        ?>
                         </tbody>
                        </table>
                        </div>
                             <?php 
								}
								?><input type="hidden" name="template_size_id" id="template_size_id" value="" />
                                 </div> 
                            <!-- <div id="display-product"></div>-->
                             <div class="line line-dashed m-t-large"></div>
                             <div class="form-group" id="footer-div" style="display:none">
                                <div class="col-lg-9 col-lg-offset-3">
                                 <button type="submit" name="btn_save" class="btn btn-primary">Save</button>
                                  <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a>  
                                </div>
                             </div>
                             <?php if(isset($template['product_template_id']))
							 {
								 ?>
                             <div class="form-group" id="update-div" style="display:inline">
                                <div class="col-lg-9 col-lg-offset-3">
                                 <button type="submit" name="btn_update" id="btn_update" class="btn btn-primary">Update</button>
                                  <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a>  
                                </div>
                             </div>
							<?php  }?>
                         </div> 
                    </div>
                </form>
        	</section>
      	</div>
     </div>
  </section>
</section>
<script>
$("#product").change(function(){
		
			var val = $(this).val();
			var text = $("#product option[value='"+val+"']").text().toLowerCase();
				$(".gusset").show();
				$(".option").show();
				$(".heightb").html("Height");
				$("#btn_generate").attr('name','btn_generate');
			checkGusset();
});
		
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
    <link rel="stylesheet" href="<?php echo HTTP_SERVER;?>css/multiple-select.css" />
<script src="<?php echo HTTP_SERVER;?>js/jquery.multiple.select.js"></script>
<script>
    $(function() {
        $('#country_id').change(function() {
            console.log($(this).val());
        }).multipleSelect({
            width: '100%',
        });
    });
</script>
<script>
var count=0;
var product_count = 0;

function removeTemplate(template_size_id){
	//alert('#'+template_size_id);
	var remove_template_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=removeTemplate', '',1);?>");
	$.ajax({
		url : remove_template_url,
		method : 'post',
		data : {template_size_id : template_size_id},
		success: function(){
			$('#'+template_size_id).hide();	
		},
		error: function(){
			return false;	
		}
	});
}

function getTemplate(template_size_id){
	//console.log(template_size_id);
	var edit_template_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getTemplate', '',1);?>");
	$.ajax({
		url : edit_template_url,
		method : 'post',
		data : {template_size_id : template_size_id},
		success: function(response){
			console.log(response);
			var val = $.parseJSON(response);	
			//console.log($('#product').val());
			$('#btn-add-product').hide();
			$('#btn-update-product').show();
			 $('#width').val(val.width);
			 $('#height').val(val.height);
			 $('#gusset').val(val.gusset);
			 $("#volume").val(val.volume);
			 if($('#product').val()==18)
			 {
			     $('#first').val(val.quantity100);
				 $('#second').val(val.quantity200);
				 $('#third').val(val.quantity500);
				 $("#fourth").val(val.quantity1000); 
			 }
			 else if($('#product').val()==61)
			 {
			     $('#first').val(val.quantity10000);
				 $('#second').val(val.quantity15000);
				 $('#third').val(val.quantity20000);
				 $("#fourth").val(val.quantity30000);
				 $("#fifth").val(val.quantity50000); 
				 $("#sixth").val(val.quantity100000); 
			 }
			 else if($('#product').val()==47 || $('#product').val()==48)
			 {
			     $('#first').val(val.quantity1000);
				 $('#second').val(val.quantity2000);
				 $('#third').val(val.quantity5000);
				 $("#fourth").val(val.quantity10000);
				 $("#fifth").val(val.quantity50000); 
				 $("#sixth").val(val.quantity100000); 
			 }
			 else
			 {
    			 if(val.quantity100=='0' && val.quantity200=='0' && val.quantity500=='0')
    			 {
    			 	 $('#first').val(val.quantity100);
    				 $('#second').val(val.quantity200);
    				 $('#third').val(val.quantity500);
    				 $("#fourth").val(val.quantity1000);
    			 	
    			 }
    			 else
    			 {
    			 	 $('#first').val(val.quantity1000);
    				 $('#second').val(val.quantity2000);
    				 $('#third').val(val.quantity5000);
    				 $("#fourth").val(val.quantity10000);
    			 }
			 }
			  $("#template_size_id").val(val.product_template_size_id);
		 var valve=val.valve;
		 var span =$(".valve");
		 var spout=val.spout;
		 //alert(spout);
		 var spoutclass = $(".spout");
		 var accessorie = val.accessorie;
		  var accessorieclass = $(".accessorie");
		 var zipper = val.zipper;
		  var zipperclass = $(".zipper");
		  var color = $.parseJSON(val.color);
		
		 
		  var colorclass = $(".colortemp");
		 
for (var i = 0; i < span.length; ++i) {
if(span[i].value == valve)
{
	$('#'+span[i].id).attr("checked", true);
 //alert(span[i].id);
}
else
{
 $(span[i].id).checked = false;  
}
  
}

for (var i = 0; i < spoutclass.length; ++i) {
if(spoutclass[i].value == spout)
{
	$('#'+spoutclass[i].id).attr("checked", true);
 	//alert(spoutclass[i].id);
}
else
{
 $(spoutclass[i].id).checked = false;  
}
  
}
for (var i = 0; i < accessorieclass.length; ++i) {
if(accessorieclass[i].value == accessorie)
{
	$('#'+accessorieclass[i].id).attr("checked", true);
 //alert(span[i].id);
}
else
{
 $(accessorieclass[i].id).checked = false;  
}
  
}
for (var i = 0; i < zipperclass.length; ++i) {
if(zipperclass[i].value == zipper)
{
	$('#'+zipperclass[i].id).attr("checked", true);
 //alert(span[i].id);
}
else
{
 $(zipperclass[i].id).checked = false;  
}
  
}
 for (var i = 0; i < colorclass.length; ++i)
{
	 $('#'+colorclass[i].id).prop("checked", false);
}

 // alert(color[i]);
 for (var i = 0; i < color.length; ++i) {

for (var k = 0; k < colorclass.length; ++k) {
	//alert(colorclass.length);
		if(colorclass[k].value == color[i])
		{
			$('#'+colorclass[k].id).prop("checked", true);
		}		
}
 }
	},
		error: function(){
			return false;	
		}
	});
}
$("#order-form").submit(function(event){
	
	var country_id = $("#country_id").val();
		if(country_id==null)
		{
			$("#countrylabel").show();
			$("#country_span").show();
			
			$("#country_span").html("<span class='btn btn-danger btn-xs'>Please select Shipment Country</span>");
			event.preventDefault();
	
		}
	
});
$('#btn-add-product').click(function(){
	if($("#order-form").validationEngine('validate')){
		var add_product_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=addHistory', '',1);?>");
		var country_id = $("#country_id").val();
		if(country_id!=null)
		{
		var str = $("form").serialize();
		
		$.ajax({
			url : add_product_url,
			method : 'post',
      		 data:{str:str},	
			success: function(response){
				console.log(response);
				var val = $.parseJSON(response);	
				   $('#display-product').html(val.response);
				 //  alert( $('#display-product'));
				   $('#templateid').val(val.result);
				   $('#footer-div').show();
			},
			error: function(){
				return false;
			}
		});
		}
		else
		{
			$("#countrylabel").show();
			$("#country_span").show();
			
			$("#country_span").html("<span class='btn btn-danger btn-xs'>Please select Shipment Country</span>");
			
		}
		
	}else{
		return false;
	}
});
function reloadPage(){
	location.reload();
}
$('#btn-update-product').click(function(){
	if($("#order-form").validationEngine('validate')){
		var add_product_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=updateTemplate', '',1);?>");
		//alert(add_product_url);
		var str = $("form").serialize();
		//alert($('.valve').val());
		$.ajax({
			url : add_product_url,
			method : 'post',
      		 data:{str:str},	
			success: function(response){
				//alert(response);
				set_alert_message('Successfully Updated',"alert-success","fa-check");
				//$("form").serialize();
			 reloadPage();

			},
			error: function(){
				set_alert_message('Error!',"alert-warning","fa-warning"); 
			}
		});
	}else{
		return false;
	}
});
</script> 
<script language="JavaScript">

	
//function checkall(checkEm) {
//	alert('hii');
//    var cbs = document.getElementsByName('color');
//    for (var i = 0; i < cbs.length; i++) {
//        if (cbs[i].type == 'checkbox') {
//            if (cbs[i].name == 'color[]') {
//                cbs[i].checked = checkEm;
//            }
//        }
//    }
//}
//}
//function checkall(color) {
//	alert("hi");
//	
//  checkboxes = document.form.getElementsByName('color');
//  for (var checkbox in checkboxes)
//    checkbox.checked = color.checked;
//}
function checkall(formname, checktoggle)
{
	
	
     var checkboxes = new Array();
      checkboxes = document[formname].getElementsByTagName('input');
      for (var i = 0; i < checkboxes.length; i++) {
          if (checkboxes[i].type === 'checkbox') {
               checkboxes[i].checked = checktoggle;
          }
      }
}
function uncheckall(formname, checktoggle)
{
	
	
     var checkboxes = new Array();
      checkboxes = document[formname].getElementsByTagName('input');
      for (var i = 0; i < checkboxes.length; i++) {
          if (checkboxes[i].type === 'checkbox') {
               checkboxes[i].checked = '';
          }
      }
}

</script>

<!-- Close : validation script -->
<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>