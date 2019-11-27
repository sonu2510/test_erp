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

if(isset($_GET['job_id']) && !empty($_GET['job_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$job_id = base64_decode($_GET['job_id']);
		$job = $obj_job->getJob($job_id);
		$job_dieline = $obj_job->getJobDieline($job_id);
		//printr($job_dieline);
		$edit = 1;
	}
	
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}
//Close : edit

if($display_status){
 //   printr($edit);
	$i ='JOB';

				$new_job_in_no = $obj_job->generatePackingNumber();

				$order_no = $i.$new_job_in_no;
	
	$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
	$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
	
	$addedByInfo = $obj_job->getUser($user_id,$user_type_id);

	//insert user
	if(isset($_POST['btn_save'])){
		$post = post($_POST);
		if(isset($_FILES))
	    $file =$_FILES;
	 //   printr($file['die_line']['name']);die;
	$insert_id = $obj_job->addJob($post,$file['file']['name']);
		$obj_session->data['success'] = ADD;
	page_redirect($obj_general->link($rout, '', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		if(isset($_FILES))
	    $file =$_FILES;
	//printr($post);
	//printr($file);die;
		$obj_job->updateJob($post,$file['file']['name']);
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
	       <?php include("common/breadcrumb.php");		?>	
        </div> 
     
      <div class="col-sm-12">
        <section class="panel">
          <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
            <!-- manirul 11-2-17 -->
              <div class="row">
              <div class="col-lg-8" > 
				<div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Job No</label>
                <div class="col-lg-4">
                  	<input type="text" name="job_no" readonly    value="<?php echo isset($job['job_no'])?$job['job_no']:$order_no;?>" class=  "form-control validate[required]">
                        
                </div>
                
                <label class="col-lg-1 control-label"><span class="required">*</span>Job Date</label>
                <div class="col-lg-4">
                  	<input type="text" name="job_date" id="job_date" data-date-format="yyyy-mm-dd" value="<?php if(isset($job['job_date'])){ echo $job['job_date']; }else{ echo date("Y-m-d"); }  ?>" class="form-control validate[required] datepicker">
              
              </div>
                
                </div>
                
			  			  
               <div class="form-group">
                <label class="col-lg-4 control-label">DieLine <br/><small class="text-muted">(Only .pdf, .png & .jpg format)</small></label>
                <div class="col-lg-8">                    
                    <div class="media-body">
                            <input type="file" multiple="multiple"   name="file[]" id="die-line" title="<i class='fa fa-folder-open-o'></i> Browse" class="btn btn-sm btn-info m-b-small">
                    </div>                    
                    <div class="file-preview-die" style="display:none">
                       <div class="file-preview-thumbnails-die">                            
                       </div>
                       <div class="clearfix"></div>
                    </div>                    
                    <div id="append-dieline"></div>                
                </div>
              </div>
              <?php if(isset($_GET['job_id']) && !empty($_GET['job_id'])){?>


              		  <div class="form-group refresh_div">
                            <label class="col-lg-3 control-label">DieLine <br/><small class="text-muted">(Only .pdf, .png& .jpg format)</small></label>
                            <div class="col-lg-5">
                                <label class="control-label normal-font">
								<?php 
                                 $html = '';			
                                 if(isset($job_dieline) && !empty($job_dieline)) {
									  //$html .='<small><input type="button" name="Delete" value="Delete"></small>';
									  $html .='<div class="carousel slide auto" id="c-slide-'.$job_id.'" style="width: 150px;">
										
										<ol class="carousel-indicators out">';
										 for($j=0;$j<(count($job_dieline));$j++){ 
											$html .='<li data-target="#c-slide-'.$job_id.'" data-slide-to="'.$j.'" class=""></li>';
                                     	  }
										$html .='</ol>';
										$html .='<div class="carousel-inner" style="height: 180px;">';
									    $i=0;
                                      
										foreach($job_dieline as $image){
											//printr( $image);
											$ext = pathinfo($image['job_name'], PATHINFO_EXTENSION);
											//$html .=' <div class="preview_"'.$image['job_dieline_id'].'>';
											if($i==0){
												$html .=' <div class="item active " id="preview_'.$image['job_dieline_id'].'">';
											}else{
												$html .=' <div class="item" id="preview_'.$image['job_dieline_id'].'">';
											}
											//$html .=' <a class="iremove" href="javascript:void(0);" onClick="removefile('.$image['job_dieline_id'].')">Remove</a>';
											$html .='<img class="" style="padding-left: 13px;float: right;margin-left: -16px; " alt="Image" width="30" height="10" src="'.HTTP_UPLOAD.'admin/dielineForJob/download.png" onClick="removefile('.$image['job_dieline_id'].')">';
											if($ext!='pdf')
											{
                                            	$html .='<p class="text-center"><a href="'.HTTP_UPLOAD.'admin/dielineForJob/500_'.$image['job_name'].'" target="_blank"><img class="" alt="Image" width="100" height="100" src="'.HTTP_UPLOAD.'admin/dielineForJob/500_'.$image['job_name'].'"></a></p><center><a href="'.HTTP_UPLOAD.'admin/dielineForJob/500_'.$image['job_name'].'" target="_blank">'.$image['job_name'].'</a></center>';
												
											}
											else
											{
												$html .='<p class="text-center"><a href="'.HTTP_UPLOAD.'admin/dielineForJob/'.$image['job_name'].'" target="_blank"><img class="" alt="Image" width="100" height="100" src="'.HTTP_UPLOAD.'admin/dielineForJob/pdf.jpg"></a></p>
															<center><a href="'.HTTP_UPLOAD.'admin/dielineForJob/'.$image['job_name'].'" target="_blank">'.$image['job_name'].'</a></center>';	
											}
											
                                        	$html .='</div>';
                                        	$i++;
                                    	}
									$html .='</div>
                                    <a class="left carousel-control" style="width:0px;" href="#c-slide-'.$job_id.'" data-slide="prev"> <i class="fa fa-chevron-left"></i> </a>
                                    <a class="right carousel-control" style="width:0px;" href="#c-slide-'.$job_id.'" data-slide="next"> <i class="fa fa-chevron-right"></i> </a> </div>';
                                	echo $html;
                                 } else {
                                    echo '<p><img class="" width="100" height="100" src="'.HTTP_UPLOAD.'admin/dielineForJob/blank.jpg" alt="Image"></p>';
                                 }
                                ?>
                                </label>
                        	</div>
                     	</div>
              <?php }?>
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Job Name</label>
                <div class="col-lg-6">
                  	<input type="text" name="jobname" value="<?php echo isset($job['job_name'])?$job['job_name']:'';?>" class=  "form-control validate[required]">
                        <input type="hidden" name="jobid" value="<?php echo isset($job['job_id'])?$job['job_id']:'';?>" class=  "form-control">
						  <input type="hidden" name="edit" id ="edit" value="<?php echo $edit ;?>" class=  "form-control">
                </div>
              </div> 
            
                <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Product Name</label>
                <div class="col-lg-4">
                  	<input type="text" name="product_name" value="<?php echo isset($job['product_name'])?$job['product_name']:'';?>" class=  "form-control">
                       
                </div>
              </div>

              	<div class="form-group ">
					<label class="col-lg-3 control-label">Job Type </label>
					<div class="col-lg-6">                
						<div  class="checkbox ch1" style="float:left;width: 200px;">
							<label  style="font-weight: normal;">
							  <input type="radio" name="job_type" id="roll_form" checked  value="roll_form"   <?php if(isset($job_id) && ($job['job_type'] == 'roll_form')) { ?> checked="checked" <?php } ?> /> Roll Form
							
						  </label>
					  </div>
					   <div class="checkbox ch2" style="float:left;width: 200px;">
							<label  style="font-weight: normal;">
								<input type="radio" name="job_type" id="pouching" value="pouching"<?php if(isset($job_id) && ($job['job_type']) == 'pouching') { ?> checked="checked" <?php } ?> />Pouching
						   </label>
					  </div>
				   
					</div>
				</div>
			  
			      <div class="form-group option">
                        <label class="col-lg-3 control-label">Type of Pouch</label>
                        <div class="col-lg-9">                
                        	<div  class="checkbox ch1" style="float:left;width: 200px;">
                                <label  style="font-weight: normal;">
                                  <input type="radio" name="pouch_type" id="stock" checked  value="stock"  <?php if(isset($job_id) && ($job['pouch_type'] == 'stock')) { ?> checked="checked" <?php } ?>  /> Stock
								
                              </label>
                          </div>
                           <div class="checkbox ch2" style="float:left;width: 200px;">
                                <label  style="font-weight: normal;">
                                  	<input type="radio" name="pouch_type" id="custom" value="custom"  <?php if(isset($job_id) && ($job['pouch_type'] == 'custom')) { ?> checked="checked" <?php } ?> />Custom
                               </label>
                          </div>
                       
                        </div>
					</div>
			    <div class="form-group option">
                        <label class="col-lg-3 control-label">Print Type</label>
                        <div class="col-lg-9">  
                          <select name="print_type" id ="print_type" class="form-control ">
                             <?php for($i=1;$i<5; $i++){?>
                               <option value="<?php echo $i;?>"  <?php if(isset($job_id) && ($job['print_type'] == $i)) { ?>selected="selected"<?php }?>><?php echo $i;?></option>
                              <?php }?>
                              </select>
                        
                               
                       
                        </div>
					</div>
			  
			  <div class="form-group">
				<label class="col-lg-3 control-label">Country</label>
				<div class="col-lg-4">
				  <select name="country_id" id ="country_id" onchange="get_user_details()" class="form-control ">
					<option value="">Select Country</option>
					<?php 
						   $country = $obj_job->get_country(); ?>
						 <?php
								
                                foreach($country as $c){
                                  // if(isset($addedByInfo['country_id']) && $addedByInfo['country_id'] == $c['country_id']){
                                     //   echo '<option value="'.$c['country_id'].'" selected="selected" >'.$c['country_name'].'</option>';
                                 //   }
									if(isset($job['country_id']) && $job['country_id'] == $c['country_id']){
										 echo '<option value="'.$c['country_id'].'" selected="selected" >'.$c['country_name'].'</option>';
									}
										else{
                                        echo '<option value="'.$c['country_id'].'">'.$c['country_name'].'</option>';
                                    }
                                } ?>
				  </select>
				</div>
			</div>
				<div class="form-group" id ="user_details">
					<?php if( isset($job['country_id']) && $job['country_id'] == '111'){
					if(isset($job_id) && !empty($job['country_id'])){
					
						$data = $obj_job->get_user_details($job['country_id']);
							echo'<label class="col-lg-3 control-label"> User Detail</label>';
							echo'<div class="col-lg-4" id="user_details">';
							echo'<select id="user_details" name="user_details" class="form-control " ><option value="">Select user</option>';
							if($data){	
							foreach($data as $user){	
											if(isset($job['user_details'])&& $job['user_details']==$user['employee_id'] ){
												echo'<option value="'.$user['employee_id'].'" selected="selected"  >'.$user['first_name'].'  '.$user['last_name'].'</option>';
											}
											echo'<option value="'.$user['employee_id'].'">'.$user['first_name'].'  '.$user['last_name'].'</option>';
											}
									}
						 
							echo'</select>';
							echo '</div>';
						
					}}?>
				
				</div>
					
                <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span> Select Product</label>
                        <div class="col-lg-6">
                            <?php
                            $products = $obj_job->getActiveProduct();
                            ?>
                            <select name="product" id="product"  onchange="get_size()" class="form-control validate[required]">
                            <option value="">Select Product</option>
                                <?php
								
                                foreach($products as $product){
                                    if(isset($job['product']) && $job['product'] == $product['product_id']){
                                        echo '<option value="'.$product['product_id'].'" selected="selected" >'.$product['product_name'].'</option>';
                                    }else{
                                        echo '<option value="'.$product['product_id'].'">'.$product['product_name'].'</option>';
                                    }
                                } ?>
                            </select>
                        </div>
                      </div>
			  
				   <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span>Select Size(WXHXG)</label>
                        <div class="col-lg-9" id="size_div" style="float: left; width: 30%;">
                         <?php if(isset($job_id)) {
									//$inv = $obj_job->getSingleInvoice($proforma_in_id);
									//printr($inv); //die;
									$data = $obj_job->getProductSize($job['product']);
									//printr($data); die;
								 ?>
								<select id="size_pro" name="size_pro" class="form-control " onchange="customSize()"><option value="">Select Size</option>
								<?php foreach($data as $item) { ?>
								<option value="<?php echo $item['size_master_id']; ?>"
								<?php if($job['size_pro'] == $item['size_master_id'] ) { echo 'selected="selected"'; } ?> >
								<?php 
								if($item['volume']!=0)
								echo $item['volume']; ?>
								<?php echo $item['width'].'X'.$item['height'].'X'.$item['gusset']; ?></option>
								<?php } ?>
								<option value="0"  <?php if($job['size_pro'] == '0' ) { echo 'selected="selected"'; } ?>  >Custom</option>
								</select>
                        <?php } else { ?>
							<span class="btn btn-danger btn-xs">Please select Product for Size option.</span>
                        <?php } ?>
                         </div>
                      </div>
                      
                  
					  
                       
			    <div <?php echo isset($job_id) &&  $job['size_pro'] == '0' ?'':'style="display:none"';?> id="customSize">
                      <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span>Width</label>
                        <div class="col-lg-3">                         
                             <input type="text" name="width" id="width"  value="<?php echo isset($job['width'])?$job['width']:'';?>" class="form-control validate[required,custom[onlyNumberSp]]" > 
                                                      
                        </div>
                       
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label heightb"><span class="required">*</span> Height</label>
                        <div class="col-lg-3">
                             <input type="text" name="height" id="height" value="<?php echo isset($job['height'])?$job['height']:'';?>" class="form-control validate[required,custom[onlyNumberSp]]">
                        </div>
                      </div>
                      
                      <div class="form-group gusset">
                        <label class="col-lg-3 control-label"><span class="required">*</span> Gusset </label>
                        <div class="col-lg-7">
                             <div class="input-group">
                             	<input type="text" name="gusset" id="gusset_input" value="<?php echo isset($job['gusset'])?$job['gusset']:'';?>" class="form-control validate[required]">
                                	<span class="input-group-btn">
                                    	<button type="button" class="btn btn-danger"> <i class="fa fa-warning"></i> Please enter one side or single gusset only.</button>
                                    </span>  
                             </div> <span id="gussetsugg" style="color:blue;font-size:11px;"></span>   
                        </div>
                      </div>
                      </div>
			  
			  
			    <div class="form-group"  id ="sealing_div" style="display:none">
                <label class="col-lg-3 control-label"><span class="required">*</span> Sealing</label>
                <div class="col-lg-4">
                  	<input type="text" name="sealing" value="<?php echo isset($job['sealing'])?$job['sealing']:'';?>" class=  "form-control">
                       
                </div>
              </div>
		   
			  
			  
               <div class="form-group">
                <label class="col-lg-3 control-label">Printing Option</label>
                <div class="col-lg-4">
				<?php  $printing_option = $obj_job->getActivePrintingEffect();	?>
				
			  <select name="printing_option" id="printing_option" class="form-control" onchange="">
			 <option value="0">Select Printing Option</option>
				  <?php
				  foreach($printing_option as $printing){
						//printr($printing);
					//	printr($job);
                                  if(isset($job['printing_option']) && $job['printing_option'] == $printing['printing_effect_id']){
                                       echo '<option value="'.$printing['printing_effect_id'].'" selected="selected" >'.$printing['effect_name'].'</option>';
                                    }else{
                                        echo '<option value="'.$printing['printing_effect_id'].'">'.$printing['effect_name'].'</option>';
                                   }
                                } ?>
                
                  
                  </select>
                </div>
                </div> 
                 
             
            
             
             
                <div class="form-group">
                <label class="col-lg-3 control-label">Layers</label>
                <div class="col-lg-4">
                  <select name="layers" id="layers" class="form-control" onchange="getMaterial()">
               
                   <?php
					 for($i=1;$i<=5;$i++){ ?>
                    <option value="<?php echo $i;?>" <?php echo (isset($job['layers']) && $job['layers'] == $i)?'selected':'';?> ><?php echo $i; ?></option><?php } ?>
                    </select>
                    </div>
                </div>
               
                   
			<div class="form-group">
					<label class="col-lg-3 control-label"> Material</label>
						<?php if(isset($job_id) && !empty($job['layers'])){
							//$layer_details = $obj_job->getLayerMakeMaterialDetails($job_id);
							//$m=array();
							//$l=array();
										//foreach($layer_details as $details){
											//$m[]=$details['product_item_layer_id'];
											//$l[]=$details['layer_id'];
											
									//	}
										//printr($l);
									//	printr($m);
										
						
							if($job['layers'] > 0){								
							if($job['layers']){	
								echo '<div class="col-lg-9" id="layerdiv">';
								echo'<section class="panel">';
								 echo'<div class="table-responsive">';
									echo'<table class="table table-striped b-t text-small">';
									  echo'<thead>';
										echo'<tr>';
										 echo '<th  width="15%"></th>';
											echo'<th>Material</th>';				 
											echo'<th>Film Size</th>';				 
													 
											echo'</tr>';
										echo'</thead>';
									  echo '<tbody>';
								
											
									  for($i=1;$i<=$job['layers'];$i++){
										
							
										  $layer_materials = $obj_job->getLayerMakeMaterial($i);	
										 $layer_details = $obj_job->getLayerMakeMaterialDetails($job_id,$i);	
										 if(!empty($layer_details)){
										 foreach ($layer_details as $m){
											if($m['layer_id']==$i){											 
												if($layer_materials){
												
													 echo '<tr>';
													 echo '<td><b>'.$i.' Layer</b></td>';
													 echo '<td>';
														echo '<select name="material['.$i.'][material_id]"  id="material_'.$i.'" class="form-control validate[required]">';
																echo'<option value="">Select Material</option>';	
																foreach($layer_materials as $material){
																	if(isset($m['product_item_layer_id']) && $m['product_item_layer_id'] == $material['material_id']){
																			echo'<option value="'.$material['material_id'].'" selected="selected">'.$material['material_name'].'</option>';
																	}else{
																	echo'<option value="'.$material['material_id'].'">'.$material['material_name'].'</option>';
																	
																
																	}
																}
																		
															echo'</select>';
													echo '</td>';
											        	echo '<td>';
                                                       echo '<input type="text" name="material['.$i.'][film_size]" value="'.$m['film_size'].'" class=  "form-control">';
                                                     echo '</td>';
													echo'</tr>';
											  }
										}
										 }
									     }
									  }
									 echo'</tbody>';
									echo'</table>';
								  echo'</div>';
								echo'</section>';
								echo '</div>';
							
						
							}
							
						}
							
						
					}?>
					<div class="col-lg-9" id="layerdiv"></div>
				  </div>
			
            <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> No of Pouch</label>
                <div class="col-lg-4">
                  	<input type="text" name="no_of_pouch" value="<?php echo isset($job['no_of_pouch'])?$job['no_of_pouch']:'';?>" class=  "form-control">
                       
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label"> Cylinder Length</label>
                <div class="col-lg-4">
                  	<input type="text" placeholder="Cylinder Length" name="cylinder_length" value="<?php echo isset($job['cylinder_length'])?$job['cylinder_length']:'';?>" class="form-control">
                       
                </div> 
               </div>
                <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Cylinder Cir-Cum</label>
                <div class="col-lg-4">
                  	<input type="text"  placeholder="Cylinder Cir-Cum" name="cylinder_cir_cum" value="<?php echo isset($job['cylinder_cir_cum'])?$job['cylinder_cir_cum']:'';?>" class=  "form-control">
                       
                </div>
              </div> 
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Job Color</label>
                <div class="col-lg-4">
                  	<input type="text"  placeholder="Job Color" name="job_color" value="<?php echo isset($job['job_color'])?$job['job_color']:'';?>" class=  "form-control">
                       
                </div>
              </div>
		   
			   <div class="form-group">
                <label class="col-lg-3 control-label">Manufacturing Process</label>
                <div class="col-lg-4">
				
                  <?php $process = $obj_job->getProductionProcess(); 
					
						?>
						
						  <select name="m_process" id="m_process" class="form-control" onchange="getPrintingOption();">
						  
						  <?php
						  foreach($process as $pro){
							//	printr($printing);
										   if(isset($job['m_process']) && $job['m_process'] == $pro['production_process_id']){
											   echo '<option value="'.$pro['production_process_id'].'" selected="selected" >'.$pro['production_process_name'].'</option>';
										   }else{
												echo '<option value="'.$pro['production_process_id'].'">'.$pro['production_process_name'].'</option>';
										   }
										} ?>
						
						  
						  </select>
							
                  	
                </div>
               
              </div>
		   
              <div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                <div class="col-lg-4">
                  <select name="status" id="status" class="form-control">
                    <option value="1" <?php echo (isset($job['status']) && $job['status'] == 1)?'selected':'';?> > Active</option>
                    <option value="0" <?php echo (isset($job['status']) && $job['status'] == 0)?'selected':'';?>> Inactive</option>
                  </select>
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
<script src="<?php echo HTTP_SERVER;?>ckeditor3/ckeditor.js"></script>
<script>
jQuery(document).ready(function(){
		var edit =$("#edit").val();
		if(edit == ''){
			getMaterial();
			get_user_details();
	
		}
        
      
    });
	

		
function  get_size(){
var product_id = $('#product').val();
	//var size_id = $('#size').val();
	var size_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getProductSize', '',1);?>");
	$.ajax({
		type: "POST",
		url: size_url,					
		data:{product_id:product_id},
		success: function(json) {
			if(json){
				$("#size_div").html(json);
				//if(product_id==16)
				if(product_id==10)
				{
					$("#size option[value='0']").prop('disabled',true);
				}
				if(product_id=='1' || product_id=='5')
				{
					$("#sealing_div").css("display","block");
				}else{
				    $("#sealing_div").css("display","none");
				}
				/*if((zip_id==5 || zip_id==6 || zip_id==7) && (product_id==1 || product_id==7))
				{
					$("#size option[value='0']").hide();
				}*/
			}else{
				$("#size_div").html('<span class="btn btn-danger btn-xs">Please select Product for Size option.</span>');
			}
			$("#loading").hide();
		}
	});
}	

	function customSize()
		{
			if($('#size_pro').val()==0)
			{
				$("#customSize").show();	
			}
			else
			{
				$("#customSize").hide();
				$("#width").val('');
				$("#height").val('');
				$("#gusset").val('');
			}
			
		
		}
	
	function getMaterial(){
		var layers = $('#layers').val();
		//alert(layers);
			//var size_id = $('#size').val();
			
			var size_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getMaterial', '',1);?>");
			$.ajax({
				type: "POST",
				url: size_url,					
				data:{layers:layers},
				success: function(json) {
					if(json){
						$("#layerdiv").html(json);
					
						}
				}
			});
				
		
		}
	function get_user_details(){
	var country_id = $('#country_id').val();
//	alert(country_id);
	if(country_id == 111){
	//	alert("hii");
			//var size_id = $('#size').val();
			var size_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=get_user_details', '',1);?>");
			$.ajax({
				type: "POST",
				url: size_url,					
				data:{country_id:country_id},
				success: function(json) {
					if(json){
						$("#user_details").html(json);
							$("#user_details").show();
						}
				}
			});
	}else{
		$("#user_details").hide();
		}		
		
	}


	var die_count = 0;

$('.media-body').on('change','#die-line',function(){
	die_count += 1;
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=uploadDieLine', '',1);?>");
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
			console.log(response);
			if(typeof response.ext != 'undefined'){
				if(response.ext == 'img'){
					die_html = '<div id="die-preview-'+die_count+'" class="file-preview-frame">';
					  die_html +='<img class="file-preview-image" src="'+response.name+'">';
					  die_html += '<a class="iremove" href="javascript:void(0);" onClick="removeimg('+die_count+')">Remove</a>';      
					die_html += '</div>';
					$('.file-preview-die').show();
					$('.file-preview-thumbnails-die').append(die_html);
					$('#loading').remove();
				}else{
					die_html = '<div id="die-preview-'+die_count+'" class="file-preview-frame">';
					  die_html +='<img class="file-preview-image" src="<?php echo HTTP_SERVER .'images/pdf_image.jpg'; ?>">';
					  die_html += '<a class="iremove" href="javascript:void(0);" onClick="removeimg('+die_count+')">Remove</a>';      
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
					$('#loading').remove();
				}
			}else{
				$('#loading').remove();
				set_alert_message('Only .pdf And .jpg Formate Allow','alert-danger','fa fa-warning');
			}
		}
   });
});

function removefile(count){
	//alert(count);
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=removeFile', '',1);?>");
	$('#loading').show();
	$.ajax({
		url: url,
		data: {die_id : count},       
		type: 'post',
		success : function(response){
			
			$('#loading').remove();
			$('#preview_'+count).remove();
		}
	});
}
function removeimg(count){
	//alert(count);
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=removeImg', '',1);?>");
	$('#loading').show();
	$.ajax({
		url: url,
		data: {die_id : count},       
		type: 'post',
		success : function(){
		//	alert(count);
		//	alert(re);
			$('#loading').remove();
			$('#die-preview-'+count).remove();
			if($('.file-preview-die .file-preview-thumbnails-die').children().size()==0){
				$('.file-preview-die').css('display','none');	
			}
		}
	});
}
</script>

<!-- Close : validation script -->

<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>