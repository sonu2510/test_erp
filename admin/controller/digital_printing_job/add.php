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

if(isset($_GET['digital_printing_id']) && !empty($_GET['digital_printing_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$digital_printing_id = base64_decode($_GET['digital_printing_id']);
		$job_detail = $obj_digital_printing->getJobDetail($digital_printing_id);
		
		//printr($job_detail);
	
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
	
		$file = post($_FILES['die_line']);
	//	printr($post);//die;
	//	printr($file);die;
		$insert_id = $obj_digital_printing->addDigitalJob($file,$post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		//printr($post);
		
		//die;
		$file = post($_FILES['die_line']);
		if((isset($file['name'])) && !empty($file['name'])){
				$file =$file['name'];
		}else{
			$file = $job_detail['dieline_name'];
		}
	//	printr($file);die;
		$digital_printing_id = base64_decode($_GET['digital_printing_id']);
		$obj_digital_printing->updatedigital_printing($file,$digital_printing_id,$post);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	$job_latest_id=0;
	$latest_digital_printing_id = $obj_digital_printing->getlatestjobid();
	if(!empty($latest_digital_printing_id))
		$job_latest_id=$latest_digital_printing_id;
	
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
                     <div class="form-group">
                        <label class="col-lg-2 control-label">Design <br/><small class="text-muted">(Only .pdf & .jpg format)</small></label>
                        <div class="col-lg-3">                    
                            <div class="media-body">
                                    <input type="file" name="die_line" id="die-line" title="<i class='fa fa-folder-open-o'></i> Browse" class="btn btn-sm btn-info m-b-small">
                            </div>    <?php if($edit=='1'&& !empty($job_detail['dieline_name'])){?>   <div ><?php
						   	$ext = pathinfo($job_detail['dieline_name'], PATHINFO_EXTENSION);
							$html ='';
                           if($ext!='pdf')
											{
                                            	$html .='<p class=""><a href="'.HTTP_UPLOAD.'admin/DigitalPrinting/'.$job_detail['dieline_name'].'" target="_blank">
												<img class="" alt="Image" width="100" height="100" src="'.HTTP_UPLOAD.'admin/DigitalPrinting/'.$job_detail['dieline_name'].'"></a></p><a href="'.HTTP_UPLOAD.'admin/DigitalPrinting/'.$job_detail['dieline_name'].'" target="_blank">'.$job_detail['dieline_name'].'</a>';
												
											}
											else
											{
												$html .='<p class=""><a href="'.HTTP_UPLOAD.'admin/pdfDigitalPrinting/'.$job_detail['dieline_name'].'" target="_blank"><img class="" alt="Image" width="100" height="100" src="'.HTTP_UPLOAD.'admin/pdfDigitalPrinting/pdf.jpg"></a></p>
															<a href="'.HTTP_UPLOAD.'admin/pdfDigitalPrinting/'.$job_detail['dieline_name'].'" target="_blank">'.$job_detail['dieline_name'].'</a>';	
											}echo $html;?></div>  <?php }?>                 
                            <div class="file-preview-die" style="display:none">
                               <div class="file-preview-thumbnails-die">                            
                               </div>   
                               <div class="clearfix"></div>
                            </div>                    
                            <div id="append-dieline"></div>   
                                      
                        </div>
                         <label class="col-lg-1 control-label"><span class="required">*</span>Shift</label>
                     <div class="col-lg-2">
                  	<div  style="float:left;width: 200px;">
                                <label  style="font-weight: normal;">
                                  <input type="radio" name="shift" id="day" value="Day" checked="checked" <?php if(isset($digital_printing_id) && ($job_detail['shift'] == 'Day')) { echo 'checked=checked'; } ?>/> Day
                              </label>
                            
                                <label style="font-weight: normal;">
                                  	<input type="radio" name="shift" id="night" value="Night" <?php if(isset($digital_printing_id) && ($job_detail['shift'] == 'Night')) { echo 'checked=checked'; }?> />Night
                              </label>
                      </div>
                </div>
             
                      </div>
                     <div class="form-group">
                        <label class="col-lg-2 control-label"><span class="required">*</span>Digital Printing  No</label>
                        <div class="col-lg-2">
                             <input type="hidden" name="edit_value" id="edit_value" value="<?php echo $edit;?>" />
                            <input type="text" name="lamination_no" readonly="readonly" value="<?php echo isset($job_detail['digital_printing_id'])?$job_detail['digital_printing_id']:$job_latest_id+1;?>" class="form-control validate[required]">
                        </div>
                         <label class="col-lg-2 control-label"><span class="required">*</span>Digital Printing  Date</label>
                        <div class="col-lg-2">
                            <input type="text" name="lamination_date" id="lamination_date" data-date-format="yyyy-mm-dd" value="<?php if(isset($job_detail['digital_printing_date'])){ echo $job_detail['digital_printing_date']; }else{ echo date("Y-m-d"); }  ?>" class="form-control validate[required] datepicker">
                        </div>
                    
                      </div>
             
                     <div class="form-group">
                        <label class="col-lg-2 control-label"><span class="required">*</span>Job Name</label>
                        <div class="col-lg-6">
                            <input type="text" name="job_name_text" id="job_name_text" value="<?php echo isset($job_detail['job_name'])?$job_detail['job_name']:'';?>" class="form-control validate[required]" <?php if($edit=='1'){?> readonly="readonly"<?php }?> onchange="get_screen_size();">
                            <input type="hidden" name="job_name" id="job_name" value="<?php echo isset($job_detail['job_name'])?$job_detail['job_name']:'';?>" />
                             <input type="hidden" name="colors_front" id="colors_front" value="<?php echo isset($job_detail['number_of_color_front'])?$job_detail['number_of_color_front']:'';?>" />
                              <input type="hidden" name="colors_back" id="colors_back" value="<?php echo isset($job_detail['number_of_color_back'])?$job_detail['number_of_color_back']:'';?>" />
                            
                            <div id="ajax_response"></div>
                  		  </div>
               	   </div>
                     <div class="form-group">
                                    <label class="col-lg-2 control-label"><span class="required">*</span>Country</label>
                                    <div class="col-lg-3">								 
                                     <select name="shipping_country_id" id="shipping_country_id" class="form-control">
                                     <?php 
                                       $countrys = $obj_digital_printing->getCountries();
                                       foreach($countrys as $country){
                                           if(isset($_GET['digital_printing_id']) && $country['country_id'] == $job_detail['country_id']){
                                         ?>	
                                            	<option value="<?php echo $country['country_id']; ?>"  selected="selected" ><?php echo $country['country_name']; ?></option>
                                         <?php  } if( $country['country_id'] == '111'){
                                         ?>	
                                            	<option value="<?php echo $country['country_id']; ?>"  selected="selected" ><?php echo $country['country_name']; ?></option>
                                         <?php  }
										 else
										 {?>
                                                <option value="<?php echo $country['country_id']; ?>" ><?php echo $country['country_name']; ?></option>
                                     
                                     <?php }
                                        } ?>  
                                     </select>										
                                    </div>
                                  </div> 
               <div class="line line-dashed m-t-large"></div>
              	
              		 <div class="form-group">
                            <label class="col-lg-2 control-label"><span class="required">*</span>Number Of Color In Front</label>
                            <div class="col-lg-2">                        
                             <input type="text" name="color_front"  readonly="readonly"  id="color_front" onchange="get_color_size_front();"  value="<?php echo isset($job_detail['number_of_color_front'])?$job_detail['number_of_color_front']:'';?>" class="form-control validate[required]"/>
                             </div>
                             <label class="col-lg-2 control-label"><span class="required">*</span>Number Of Color In Back</label>
                                  <div class="col-lg-2">                        
                                      <input type="text" name="color_back"  readonly="readonly" id="color_back" onchange ="get_color_size_back();"  value="<?php echo isset($job_detail['number_of_color_back'])?$job_detail['number_of_color_back']:'';?>" class="form-control validate[required]"/>
                                   </div>
                        
              	    	</div> 
                
                
                
                    <div class="form-group">
                  		  <label class="col-lg-2 control-label"><span class="required">*</span>Screen  Size</label>
                          
                 	  		 <div class="col-lg-3" id="add-more-color_size_front">
                           	   <?php
				
								   if(isset($job_detail['size_front'])&& !empty($job_detail['size_front']) ){
										$p = json_decode($job_detail['size_front']);
										foreach($p as $size_front){
										
								 		?>		   
                              			   <div class="form-group"><input type="text" name="color_size_front[]" id="color_size_front"   value="<?php echo $size_front;?>" class="form-control validate[required]"></div>
							
                    			<?php	  }}else{?> 
                                			<div class="form-group"><input type="text" name="color_size_front[]" id="color_size_front"   value="" class="form-control validate[required]"></div>
								<?php }?>	
                  	 		 </div>
                      
                       
                  		  <label class="col-lg-3 control-label"><span class="required">*</span> Screen Size</label>
                           
                 	 		  <div class="col-lg-2" id="add-more-color_size_back">
                              	   <?php
				
								   if(isset($job_detail['size_back'])&& !empty($job_detail['size_back']) ){
										$p_back = json_decode($job_detail['size_back']);
										foreach($p_back as $size_back){
										
								 		?>	
                    					<div class="form-group"><input type="text" name="color_size_back[]" id="color_size_back"   value="<?php echo $size_back;?>" class="form-control validate[required]"></div>
                                	<?php	  }}else{?> 
                                  <div class="form-group"><input type="text" name="color_size_back[]" id="color_size_back"   value="" class="form-control validate[required]"></div>
                                    <?php }?>	
                  	 		 </div>
                            
                   
                      </div>
                    <div class="line line-dashed m-t-large"></div>
                  
              		
                
                
                 
                     <div class="form-group">
                            <label class="col-lg-2 control-label"><span class="required">*</span>Style Of Bag </label>
                            <div class="col-lg-2">
                                
                             <input type="text" name="style_bag"  id="style_bag"  placeholder="Bag Name " value="<?php echo isset($job_detail['style_bag'])?$job_detail['style_bag']:'';?>" class="form-control validate[required]">
                            </div>
                           
                  	</div>
                     <div class="form-group">
                        <label class="col-lg-2 control-label"><span class="required">*</span>Size Of Bag </label>
                        <div class="col-lg-2">
                            
                         <input type="text" name="size_bag"  id="size_bag"  placeholder="Size Of Bag"  value="<?php echo isset($job_detail['size_bag'])?$job_detail['size_bag']:'';?>" class="form-control validate[required]">
                  		  </div>
                  </div>
               <div class="line line-dashed m-t-large"></div>
                <div class="form-group">
                   
                     <label class="col-lg-2 control-label"><span class="required">*</span> Final Qty  Recieve</label>
                    <div class="col-lg-2">
                        <input type="text" name="qty_recieve"  id="qty_recieve"   value="<?php echo isset($job_detail['final_qty_recive'])?$job_detail['final_qty_recive']:''?>" class="form-control validate[required]">
                    </div>
                     <label class="col-lg-2 control-label"><span class="required">*</span>Final Qty Printed</label>
                    <div class="col-lg-2">
                        
                        <input type="text" name="qty_printed"  id="qty_printed"   value="<?php echo isset($job_detail['final_qty_printed'])?$job_detail['final_qty_printed']:'';?>" class="form-control validate[required]">
                    </div>
                     <label class="col-lg-2 control-label"><span class="required">*</span> Final Qty  Return</label>
                    <div class="col-lg-2">
                        <input type="text" name="qty_return"  id="qty_return"   value="<?php echo isset($job_detail['final_qty_return'])?$job_detail['final_qty_return']:'';?>" class="form-control validate[required]">
                    </div>
            		
              </div>
             	<div class="form-group">
                    <label class="col-lg-2 control-label"><span class="required">*</span>Qty Wastage</label>
                    <div class="col-lg-2">
                        
                     <input type="text" name="total_wastage"  id="total_wastage"  readonly="readonly"  value="<?php echo isset($job_detail['f_wastage_per'])?$job_detail['f_wastage_per']:'';?>" class="form-control ">
                    </div>                   
              </div>
               <div class="line line-dashed m-t-large"></div>          
              
              <div class="form-group">
                        <label class="col-lg-2 control-label"><span class="required">*</span> Screen Making Operator Name</label>
                        <div class="col-lg-3">
                            <?php $operators = $obj_digital_printing->getOperator();
                            //printr($job_detail);?>
                            <select name="screen_operator" id="screen_operator" class="form-control validate[required]">
                                <option value="">Select Operator</option>
                                    <?php
                            
                                    
                                    foreach($operators as $operator){ ?>
                                        <option value="<?php echo $operator['employee_id']; ?>"
                                        <?php if(isset($digital_printing_id) && ($operator['employee_id'] == $job_detail['screen_making_operator'])) { echo 'selected="selected"';}?> > <?php echo $operator['first_name'].' '.$operator['last_name']; ?></option>
                                        <?php } ?> 
                             </select>
                		  </div>
                   <label class="col-lg-2 control-label"><span class="required">*</span> Printing Operator Name</label>
                		<div class="col-lg-3">
							<?php $operators = $obj_digital_printing->getOperator_Printing();
						//printr($job_detail);?>
                           <select name="printing_operator" id="printing_operator" class="form-control validate[required]">
                            <option value="">Select Operator</option>
                                <?php
                        
                                
                                foreach($operators as $operator){ ?>
                                    <option value="<?php echo $operator['employee_id']; ?>"
                                    <?php if(isset($digital_printing_id) && ($operator['employee_id'] == $job_detail['printing_oparator'])) { echo 'selected="selected"';}?> > <?php echo $operator['first_name'].' '.$operator['last_name']; ?></option>
                                    <?php } ?> 
                         </select>
                	  </div>
                 
           	    </div>  
              
               <div class="form-group">
                    <label class="col-lg-2 control-label"><span class="required">*</span> Job Start Time</label>
                    <div class="col-lg-2">
                        <input type="time" name="start_time" value="<?php echo isset($job_detail['start_time'])?$job_detail['start_time']:'';?>" class="form-control validate[required]">
                    </div>
                    <label class="col-lg-3 control-label"><span class="required">*</span>  Job End Time</label>
                    <div class="col-lg-2">
                        <input type="time" name="end_time" value="<?php echo isset($job_detail['end_time'])?$job_detail['end_time']:'';?>" class="form-control validate[required]">
                    </div>
              </div>
                <div class="line line-dashed m-t-large"></div>
                
							<?php 
						
						  if( $edit=='1' && $job_detail['ink_use_front'] ){
											 $ink_use_front = $job_detail['ink_use_front'];
										 }
										 else
										 {   
										    
											 $ink_use_front[]= array(
												'digital_pri_ink_id'=>'',											
												'ink_id' => '',
												'qty_ink_recive' => '',
												'qty_ink_used' => '',
												'qty_ink_return' => '',						
												'total_wastage_front' =>'',
												
											);	 
										}
					//printr($enquiry_product);
				   if(!empty($ink_use_front)){
						 $front_count = '0';	
							$front_count =0;
							foreach($ink_use_front as $ink_front){
								// $front_count++;
							//printr($ink_front_count);
							?>
                           <div class="form-group ink_front-div" id="ink_front-<?php echo  $front_count;?>"> 
                                  <div class="form-group ">
                                <div class="col-lg-10">
                           
                            </div>
                               <?php if($edit =='1' ){ ?>
                         	 <div class="col-lg-2">
                      		  	<a onclick="RemoveInkFront(<?php echo  $front_count.','. $job_detail['digital_printing_id'].','.$ink_front['digital_pri_ink_id'];?>)" data-original-title="Remove Product" class="btn btn-danger btn-xs btn-circle" data-toggle="tooltip" data-placement="top" title="">
                    		  	<i class="fa fa-minus"></i></a>
                             </div>
      						<?php }?>  
                            </div>
                          
                                  <div class="form-group">
                            <label class="col-lg-2 control-label"><span class="required">*</span>Ink Used Front</label>
                            <div class="col-lg-3">
                            <input type="hidden" name="ink_use_front[<?php echo  $front_count;?>][digital_pri_ink_id]" id="digital_pri_ink_id_<?php echo  $front_count;?>" value="<?php echo $ink_front['digital_pri_ink_id'];?>" />
                                <?php $operators = $obj_digital_printing->getINK();
                            ?>
                                <select  id="ink_use_front_<?php echo  $front_count;?>"  name="ink_use_front[<?php echo  $front_count;?>][ink_id]" class="form-control validate[required]">
                                    <option value="">Select Ink</option>
                                        <?php
                                        foreach($operators as $operator){ ?>
                                            <option value="<?php echo $operator['product_item_id']; ?>"
                                            <?php if(isset($digital_printing_id) && ($operator['product_item_id'] == $ink_front['ink_id'])) { echo 'selected="selected"';}?> > <?php echo $operator['product_code']; ?></option>
                                            <?php } ?> 
                                  </select>
                              </div>
                             
                             
                           </div>  
                            <div class="form-group">
                            <label class="col-lg-2 control-label"><span class="required">*</span>Qty Ink Receive</label>
                            <div class="col-lg-2">
                                
                                <input type="text" name="ink_use_front[<?php echo  $front_count;?>][qty_ink_recive]" id="qty_ink_recive_<?php echo  $front_count;?>" onchange="wastange_data_ink_front('<?php echo  $front_count;?>')"   value="<?php echo isset($ink_front['qty_ink_recive'])?$ink_front['qty_ink_recive']:'';?>" class="form-control validate[required]">
                            </div>
                             <label class="col-lg-2 control-label"><span class="required">*</span>Qty Ink Used</label>
                            <div class="col-lg-2">
                                <input type="text" name="ink_use_front[<?php echo  $front_count;?>][qty_ink_used]"  id="qty_ink_used_<?php echo  $front_count;?>" onchange="wastange_data_ink_front('<?php echo  $front_count;?>')"   value="<?php echo isset($ink_front['qty_ink_used'])?$ink_front['qty_ink_used']:'';?>" class="form-control validate[required]">
                            </div>
                             <label class="col-lg-2 control-label"><span class="required">*</span>Qty Ink Return</label>
                            <div class="col-lg-2">
                                <input type="text" name="ink_use_front[<?php echo  $front_count;?>][qty_ink_return]"  id="qty_ink_return_<?php echo  $front_count;?>" onchange="wastange_data_ink_front('<?php echo  $front_count;?>')"    value="<?php echo isset($ink_front['qty_ink_return'])?$ink_front['qty_ink_return']:'';?>" class="form-control validate[required]">
                            </div>
                        
                          </div>
                          	<div class="form-group">
                                      <label class="col-lg-2 control-label"><span class="required">*</span>Wastage</label>
                                      <div class="col-lg-2">
                                
                                   <input type="text" name="ink_use_front[<?php echo  $front_count;?>][total_wastage_front]"  id="total_wastage_front_<?php echo  $front_count;?>"  readonly="readonly"  value="<?php echo isset($ink_front['ink_wastage'])?$ink_front['ink_wastage']:'';?>" class="form-control ">
                                   </div>                   
            		 		 </div>
                              </div>
                              
                              <?php
							  $front_count++;
  							   }
							  	
							 		
							  }?>
                               
                                  <div class="form-group ">
                                <div class="col-lg-10">
                           
                            </div>
                             
                                <div class="col-lg-2">
                                    <a title="" data-placement="top" data-toggle="tooltip" class="btn btn-success btn-xs addmore-ink_front" data-original-title="Add Ink Front">
                                    <i class="fa fa-plus"></i> Add INK Front
                                    </a>
                                </div>
                            
                            </div>
                             <div id="add-more-front"></div>
               	 <div class="line line-dashed m-t-large"></div>
               
							  <?php
							 
						
						  if( $edit=='1' && $job_detail['ink_use_back'] ){
											 $ink_use_back = $job_detail['ink_use_back'];
										 }
										 else
										 {   
										    
											 $ink_use_back[]= array(
												'digital_pri_ink_back_id'=>'',											
												'ink_id' => '',
												'qty_ink_recive' => '',
												'qty_ink_used' => '',
												'qty_ink_return' => '',						
												'total_wastage_front' =>'',
												
											);	 
										}
							   if(!empty($ink_use_back)){
						   $ink_back_count = '0';	   	
							foreach($ink_use_back as $ink_back){
                            //    printr($ink_back );
							
							?>
							 
                             <div class="form-group ink_back-div" id="ink_back-<?php echo  $ink_back_count;?>">  
                           <div class="form-group ">
                                <div class="col-lg-10">
                           
                            </div>
                                 <?php if($edit =='1' ){ ?>
                         	 <div class="col-lg-2">
                      		  	<a onclick="RemoveInkBack(<?php echo  $ink_back_count.','. $ink_back['digital_printing_id'].','.$ink_back['digital_pri_ink_back_id'];?>)" data-original-title="Remove Product" class="btn btn-danger btn-xs btn-circle" data-toggle="tooltip" data-placement="top" title="">
                    		  	<i class="fa fa-minus"></i></a>
                             </div>
      					<?php }?>
                            </div>
                            <div class="form-group">
                          
                               <label class="col-lg-2 control-label"><span class="required">*</span> Ink Used Back</label>
                            <div class="col-lg-3">
                             <input type="hidden" name="ink_use_back[<?php echo  $ink_back_count;?>][digital_pri_ink_back_id]" id="digital_pri_ink_back_id_<?php echo  $ink_back_count;?>" value="<?php echo $ink_back['digital_pri_ink_back_id'];?>" />
                                <?php $operators = $obj_digital_printing->getINK();
                                //printr($operators);?>
                                <select name="ink_use_back[<?php echo  $ink_back_count;?>][ink_id]" id="ink_use_back_<?php echo  $ink_back_count;?>" class="form-control validate[required]">
                                    <option value="">Select Ink</option>
                                        <?php
                                
                                        
                                        foreach($operators as $operator){ ?>
                                            <option value="<?php echo $operator['product_item_id']; ?>"
                                            <?php if(isset($digital_printing_id) && ($operator['product_item_id'] == $ink_back['ink_id'])) { echo 'selected="selected"';}?> > <?php echo $operator['product_code']; ?></option>
                                            <?php } ?> 
                                 </select>
                              </div>
                             
                           </div>  
                          
                            <div class="form-group">
                                <label class="col-lg-2 control-label"><span class="required">*</span>Qty Ink Receive</label>
                                <div class="col-lg-2">
                                    
                                    <input type="text" name="ink_use_back[<?php echo  $ink_back_count;?>][qty_ink_recive]"  id="back_qty_ink_recive_<?php echo  $ink_back_count;?>"  onchange="wastange_data_ink_back('<?php echo  $ink_back_count;?>')"  value="<?php echo isset($ink_back['qty_ink_recive'])?$ink_back['qty_ink_recive']:'';?>" class="form-control validate[required]">
                                </div>
                                 <label class="col-lg-2 control-label"><span class="required">*</span>Qty Ink Used</label>
                                <div class="col-lg-2">
                                    <input type="text" name="ink_use_back[<?php echo  $ink_back_count;?>][qty_ink_used]"  id="back_qty_ink_used_<?php echo  $ink_back_count;?>"  onchange="wastange_data_ink_back('<?php echo  $ink_back_count;?>')"  value="<?php echo isset($ink_back['qty_ink_used'])?$ink_back['qty_ink_used']:'';?>" class="form-control validate[required]">
                                </div>
                                 <label class="col-lg-2 control-label"><span class="required">*</span>Qty Ink Return</label>
                                <div class="col-lg-2">
                                    <input type="text" name="ink_use_back[<?php echo  $ink_back_count;?>][qty_ink_return]"  id="back_qty_ink_return_<?php echo  $ink_back_count;?>"  onchange="wastange_data_ink_back('<?php echo  $ink_back_count;?>')"  value="<?php echo isset($ink_back['qty_ink_return'])?$ink_back['qty_ink_return']:'';?>" class="form-control validate[required]">
                                </div>
                                  </div>
                             	<div class="form-group">
                                      <label class="col-lg-2 control-label"><span class="required">*</span> Wastage</label>
                                      <div class="col-lg-2">
                                
                                   <input type="text" name="ink_use_back[<?php echo  $ink_back_count;?>][total_wastage_back]"  id="total_wastage_back_<?php echo  $ink_back_count;?>"  readonly="readonly"  value="<?php echo isset($ink_back['ink_wastage'])?$ink_back['ink_wastage']:'';?>" class="form-control ">
                                   </div>                   
            		 		 </div>
                        
                                     
                            </div>
                                 <?php  $ink_back_count++;
								  }
								
								 }?>
                            
                             <div class="form-group ">
                                <div class="col-lg-10">
                           
                                  </div>
                           
                                <div class="col-lg-2">
                                    <a title="" data-placement="top" data-toggle="tooltip" class="btn btn-success btn-xs addmore-ink_back" data-original-title="Add Product">
                                    <i class="fa fa-plus"></i> Add INK Back
                                    </a>
                                </div> 
                            
                            </div>  
                    <div id="add-more-back"></div>
                <div class="line line-dashed m-t-large"></div>
                    <div class="form-group">
                     <label class="col-lg-2 control-label"><span class="required">*</span>Job Due Date</label>
             		   <div class="col-lg-2">
                  			<input type="text" name="job_due_date" id="job_due_date" data-date-format="yyyy-mm-dd" value="<?php if(isset($job_detail['job_due_date'])){ echo $job_detail['job_due_date']; }else{ echo date("Y-m-d"); }  ?>" class="form-control validate[required] datepicker">
                </div>
                    </div>
					
				<div class="form-group">
					<label class="col-lg-2 control-label">Remark</label>
				<div class="col-lg-4">
                  <textarea class="form-control" row="10" col="30" name="remark"><?php echo isset($job_detail['remark'])?$job_detail['remark']:'';?></textarea>
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
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<style type="text/css">
#ajax_response, #ajax_res,#ajax_return{
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
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script>
jQuery(document).ready(function(){
	 jQuery("#form").validationEngine();
	
	 $("#job_date").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
	 
});


$("#qty_recieve, #qty_printed, #qty_return").change(function(){
	var qty_recieve = $("#qty_recieve").val();
	var qty_printed = $("#qty_printed").val();
	var qty_return = $("#qty_return").val();
	//alert(qty_recieve+'#qty_recieve'+ qty_printed+'#qty_printed'+ qty_return +'#qty_return')
	if(qty_recieve!='' && qty_printed!='' )
	{
		
		var total = qty_recieve-qty_printed;
			
		if(qty_return!=''){
			var wastage = total - qty_return;
			$("#total_wastage").val(wastage);
		}
	}
});
	function wastange_data_ink_front(count){
	//	alert(count);
	var qty_ink_recive = $('#qty_ink_recive_'+count).val();
	var qty_ink_used = $('#qty_ink_used_'+count).val();
	var qty_ink_return = $('#qty_ink_return_'+count).val();
	if(qty_ink_recive!='' && qty_ink_used!='')
	{	//alert('#total_wastage_'+count);
		
		var total = qty_ink_recive-qty_ink_used;
		if(qty_ink_return!='')
			var wastage = total - qty_ink_return;		
			$('#total_wastage_front_'+count).val(wastage);	
		

	}
}
function wastange_data_ink_back(count){
	//alert(count);
	var qty_ink_recive = $('#back_qty_ink_recive_'+count).val();
	var qty_ink_used = $('#back_qty_ink_used_'+count).val();
	var qty_ink_return = $('#back_qty_ink_return_'+count).val();
	//alert(qty_ink_recive+'qty_recieve'+ qty_ink_used+'qty_printed'+ qty_ink_return +'qty_return')
	
	if(qty_ink_recive!='' && qty_ink_used!='')
	{	//alert('#total_wastage_'+count);
		
		var total = qty_ink_recive-qty_ink_used;
		if(qty_ink_return!='')
			var wastage = total - qty_ink_return;		
			$('#total_wastage_back_'+count).val(wastage);	
		

	}
}


$("#job_name_text").focus();
	var offset = $("#product_item_id").offset();
	var width = $("#holder").width();
	$("#ajax_response").css("width",width);
	
	$("#job_name_text").keyup(function(event){		
		
		 var keyword = $("#job_name_text").val();
		// alert(keyword);
		 if(keyword.length)
		 {	
			 if(event.keyCode != 40 && event.keyCode != 38 )
			 {
				
				 var product_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=job_detail', '',1);?>");
				//  alert(product_url);
				 $("#loading").css("visibility","visible");
				 $.ajax({
				   type: "POST",
				   url: product_url,
				   data: "job="+keyword,
				   success: function(msg){	
				 var msg = $.parseJSON(msg);
				   //console.log(msg);
				   var div='<ul class="list">';
				 
					if(msg.length>0)
					{ 	
						for(var i=0;i<msg.length;i++)
						{	
							div =div+'<li><a href=\'javascript:void(0);\'  colors_back="'+msg[i].back_color+'"  colors_front="'+msg[i].front_color+'"  id="'+msg[i].job_id+'" job_name="'+msg[i].job_name+'" ><span class="bold" >'+msg[i].job_name+'</span></a></li>';
						}
					}
					
					div=div+'</ul>';
				//alert(div);
					if(msg != 0)
					  $("#ajax_response").fadeIn("slow").html(div);
					else
					{
						$("#ajax_response").fadeIn("slow");	
						$("#ajax_response").html('<div style="text-align:left;">No Matches Found</div>');
				  		$("#job_name").val('');
						$("#colors_front").val('');
						$("#colors_back").val('');
						$("#color_front").val('');
						$("#color_back").val('');
						
					}
					$("#loading").css("visibility","hidden");
				   }
				 });
			 }
			 else
			 {				
				switch (event.keyCode)
				{
				 case 40:
				 {
					  found = 0;
					  $(".list li").each(function(){
						 if($(this).attr("class") == "selected")
							found = 1;
					  });
					  if(found == 1)
					  {
						var sel = $(".list li[class='selected']");
						sel.next().addClass("selected");
						sel.removeClass("selected");										
					  }
					  else
						$(".list li:first").addClass("selected");
						if($(".list li[class='selected'] a").text()!='')
						{
							$("#job_name_text").val($(".list li[class='selected'] a").text());
							$("#job_name").val($(".list li[class='selected'] a").attr("id"));
						
							$("#colors_front").val($(".list li[class='selected'] a").attr("colors_front"));
							$("#colors_back").val($(".list li[class='selected'] a").attr("colors_back"));
							$("#color_front").val($(".list li[class='selected'] a").attr("colors_front"));
							$("#color_back").val($(".list li[class='selected'] a").attr("colors_back"));
						}
				}
				 break;
				 case 38:
				 {
					  found = 0;
					  $(".list li").each(function(){
						 if($(this).attr("class") == "selected")
							found = 1;
					  });
					  if(found == 1)
					  {
						var sel = $(".list li[class='selected']");
						sel.prev().addClass("selected");
						sel.removeClass("selected");
					  }
					  else
						$(".list li:last").addClass("selected");
						if($(".list li[class='selected'] a").text()!='')
						{
							$("#job_name_text").val($(".list li[class='selected'] a").text());
							$("#job_name").val($(".list li[class='selected'] a").attr("id"));
							
							$("#colors_front").val($(".list li[class='selected'] a").attr("colors_front"));
							$("#colors_back").val($(".list li[class='selected'] a").attr("colors_back"));
							
							$("#color_front").val($(".list li[class='selected'] a").attr("colors_front"));
							$("#color_back").val($(".list li[class='selected'] a").attr("colors_back"));
						}
				 }
				 break;				 
				}
			 }
		 }
		 else
		 {
			$("#ajax_response").fadeOut('slow');
			$("#ajax_response").html("");
		 }
	});
	
	$('#job_name_text').keydown( function(e) {
		if (e.keyCode == 9) {
			 $("#ajax_response").fadeOut('slow');
			 $("#ajax_response").html("");
		}
	});

	$("#ajax_response").mouseover(function(){
			
				$(this).find(".list li a:first-child").mouseover(function () {
					  $("#job_name").val($(this).attr("id"));
					    $("#colors_back").val($(this).attr("colors_back"));
						  $("#colors_front").val($(this).attr("colors_front"));
						   $("#color_back").val($(this).attr("colors_back"));
						  $("#color_front").val($(this).attr("colors_front"));
					  $(this).addClass("selected");
				});
				$(this).find(".list li a:first-child").mouseout(function () {
					  $(this).removeClass("selected");
					  $("#job_name").val('');
					    $("#colors_front").val('');
						  $("#colors_back").val('');
						     $("#color_front").val('');
						  $("#color_back").val('');
						  
				});
				$(this).find(".list li a:first-child").click(function () {
							
					  $("#job_name").val($(this).attr("id"));					  
					   $("#colors_front").val($(this).attr("colors_front"));					  
					      $("#colors_back").val($(this).attr("colors_back"));
						   $("#color_front").val($(this).attr("colors_front"));					  
					      $("#color_back").val($(this).attr("colors_back"));										  
					  $("#job_name_text").val($(this).text());
					 $("#ajax_response").fadeOut('slow');
					  $("#ajax_response").html("");
							
					
				//	alert("hii");
					
						
					
				});
				
			});
			
			




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
					  die_html += '<a class="iremove" href="javascript:void(0);" onClick="removefile('+die_count+')">Remove</a>';      
					die_html += '</div>';
					$('.file-preview-die').show();
					$('.file-preview-thumbnails-die').append(die_html);
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
function removeImage(count){
	//alert(count);
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=removeImage', '',1);?>");
	$('#loading').show();
	$.ajax({
		url: url,
		data: {art_id : count},       
		type: 'post',
		success : function(){
		//	alert(count);
		//	alert(re);
			$('#loading').remove();
			$('#preview-'+count).remove();
			if($('.file-preview .file-preview-thumbnails').children().size()==0){
				$('.file-preview').css('display','none');	
			}
		}
	});
}


 //$("#color_front").focus(function(){
	 function get_screen_size(){
       //[kinjal] : on 26-6-2017
	  $("#add-more-color_size_front .appended").remove();
	  $("#add-more-color_size_back .appended1").remove();
		
	   get_color_size_front();
	   get_color_size_back()
	   
	 }
   // });

	
	function get_color_size_front(){
		
		//console.log("hiiii");
		var html ='';	
		var number = $("#color_front").val();
		var color_no = $("#colors_front").val();
		if(number<=color_no){					
				}else{
					$("#color_front").val('');
					number=0;
				}
	
		var html = '';
			var i;
			if(number != '0'){			
				for(i = 1; i < number; ++i) {
					
  					html += '<div class="form-group appended"><input type="text" name="color_size_front['+i+']" id="color_size_front['+i+']"   value="" class="form-control validate[required] "></div>';
			
				}
			  $('#add-more-color_size_front').append(html);
			  
			}
		
		
		
	}
		function get_color_size_back(){
		
		var html ='';
		var number = $("#color_back").val();
		//alert(number+'back');
		 
		//alert(count);
		//count = number -1;
		var html = '';
			var i;

				for(i = 1; i < number; ++i) {
  				html += '<div class="form-group appended1" ><input type="text" name="color_size_back['+i+']" id="color_size_back"   value="" class="form-control validate[required] "></div>';
				 
			//alert(html);
					}
		
		 $('#add-more-color_size_back').append(html);
		
	}
	
	
	
	
 	function RemoveInkFront(count,digital_printing_id,digital_printing_ink_id){
		//alert(count);
		//alert(enquiry_id);
		//alert(product_enquiry_id);
		$('#ink_front-'+count).remove();
		var remove_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=remove_ink_front', '',1);?>");
			$.ajax({
				url : remove_url,
				method : 'post',
				data : {digital_printing_id : digital_printing_id,digital_printing_ink_id:digital_printing_ink_id},
				success: function(response){
				//alert(response);	
				
				},
				error: function(){
					return false;	
				}
		});	
	}

	$('.addmore-ink_front').click(function(){
		//alert("add more");ink_front
		var count = $('.ink_front-div').length;
		//var c = $('#count_div').length + 1;
		//alert(count);
		var html = '';
		//html
		html += '<div class="form-group ink_front-div" id="ink_front-'+count+'">';
		html += ' <div class="form-group ">';
          html += '<div class="col-lg-10">';
           html += '</div>';
            html += '<div class="col-lg-2">';
			  html +='<a onclick="RemoveInkFront('+count+')" data-original-title="Remove Product" class="btn btn-danger btn-xs btn-circle" data-toggle="tooltip" data-placement="top" title=""><i class="fa fa-minus"></i></a>';
              html += '</div>'; 
              html += '</div>'; 
              	html += '<div class="form-group">';
               html += ' <label class="col-lg-2 control-label"><span class="required">*</span> Ink Used Front</label>';
               html += '  <div class="col-lg-3">';
				 html +='<select class="form-control validate[required]" id="ink_use_front_'+count+'" name="ink_use_front['+count+'][ink_id]">';
                     		<?php 
                            $operators = $obj_digital_printing->getINK();
                              foreach($operators as $operator){ ?>
                       
                          html +='<option value="<?php echo $operator['product_item_id']; ?>"><?php echo $operator['product_code'];?></option>';
                            <?php } ?>
                    html +='</select>';
				  html +='</div>'; 
               html +=' </div>';  
               	 html +='<div class="form-group">';
                 html +='<label class="col-lg-2 control-label"><span class="required">*</span>Qty Ink Receive</label>';
                html +='<div class="col-lg-2">';                  	
                  html +='<input type="text" name="ink_use_front['+count+'][qty_ink_recive]" id="qty_ink_recive_'+count+'"  onchange="wastange_data_ink_front('+count+')" value="" class="form-control validate[required]">';
                html +='</div>';
                 html +='<label class="col-lg-2 control-label"><span class="required">*</span>Qty Ink Used</label>';
              		 html +='<div class="col-lg-2">';
                   		html +='<input type="text" name="ink_use_front['+count+'][qty_ink_used]"  id="qty_ink_used_'+count+'"  onchange="wastange_data_ink_front('+count+')"  value="" class="form-control validate[required]">';
                html +='</div>';
                html +='<label class="col-lg-2 control-label"><span class="required">*</span>Qty Ink Return</label>';
               		 html +='<div class="col-lg-2">';
                  	 html +='<input type="text" name="ink_use_front['+count+'][qty_ink_return]"  id="qty_ink_return_'+count+'" onchange="wastange_data_ink_front('+count+')"   value="" class="form-control validate[required]">';
                html +=' </div>';            
              html +='</div>';
			    html +='<div class="form-group"> '; 
				html +='<label class="col-lg-2 control-label"><span class="required">*</span>Wastage</label> '; 
				 html +='<div class="col-lg-2"> ';                                 
			 		 html +='<input type="text" name="ink_use_front['+count+'][total_wastage_front]"    readonly="readonly" id="total_wastage_front_'+count+'"  value="" class="form-control "/>'; 
				  html +='</div> ';                  
				 html +='</div> '; 
            	 html +='</div> ';

		
			
     	
	  
	  
	  $('#add-more-front').append(html);
	//  show_date(count);

});
function RemoveInkBack(count,digital_printing_id,digital_printing_ink_back_id){
		//alert(count);
		//alert(enquiry_id);
		//alert(product_enquiry_id);
		$('#ink_back-'+count).remove();
		var remove_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=remove_ink_back', '',1);?>");
			$.ajax({
				url : remove_url,
				method : 'post',
				data : {digital_printing_id : digital_printing_id,digital_printing_ink_back_id:digital_printing_ink_back_id},
				success: function(response){
				//alert(response);	
				
				},
				error: function(){
					return false;	
				}
		});	
	}

$('.addmore-ink_back').click(function(){
		//alert("add more");ink_front
		var count = $('.ink_back-div').length;
		//var c = $('#count_div').length + 1;
		//alert(count);
		var html = '';
		//html
		html += '<div class="form-group ink_back-div" id="ink_back-'+count+'">';
		html += ' <div class="form-group ">';
        html += '<div class="col-lg-10">';
        html += '</div>';
        html += '<div class="col-lg-2">';
			  html +='<a onclick="RemoveInkBack('+count+')" data-original-title="Remove Product" class="btn btn-danger btn-xs btn-circle" data-toggle="tooltip" data-placement="top" title=""><i class="fa fa-minus"></i></a>';
              html += '</div>'; 
              html += '</div>'; 
              	html += '<div class="form-group">';
               html += ' <label class="col-lg-2 control-label"><span class="required">*</span> Ink Used Back</label>';
               html += '  <div class="col-lg-3">';
				 html +='<select class="form-control validate[required]" id="ink_use_back_'+count+'" name="ink_use_back['+count+'][ink_id]">';
                     		<?php 
                            $operators = $obj_digital_printing->getINK();
                              foreach($operators as $operator){ ?>
                       
                          html +='<option value="<?php echo $operator['product_item_id']; ?>"><?php echo $operator['product_code']; ?></option>';
                            <?php } ?>
                    html +='</select>';
				  html +='</div>'; 
               html +=' </div>';  
               	 html +='<div class="form-group">';
                 html +='<label class="col-lg-2 control-label"><span class="required">*</span>Qty Ink Receive</label>';
                html +='<div class="col-lg-2">';                  	
                  html +='<input type="text" name="ink_use_back['+count+'][qty_ink_recive]" id="back_qty_ink_recive_'+count+'"  onchange="wastange_data_ink_back('+count+')" value="" class="form-control validate[required]">';
                html +='</div>';
                 html +='<label class="col-lg-2 control-label"><span class="required">*</span>Qty Ink Used</label>';
              		 html +='<div class="col-lg-2">';
                   		html +='<input type="text" name="ink_use_back['+count+'][qty_ink_used]"  id="back_qty_ink_used_'+count+'" onchange="wastange_data_ink_back('+count+')"  value="" class="form-control validate[required]">';
                html +='</div>';
                html +='<label class="col-lg-2 control-label"><span class="required">*</span>Qty Ink Return</label>';
               		 html +='<div class="col-lg-2">';
                  	 html +='<input type="text" name="ink_use_back['+count+'][qty_ink_return]"  id="back_qty_ink_return_'+count+'" onchange="wastange_data_ink_back('+count+')"  value="" class="form-control validate[required]">';
                html +=' </div>';            
              html +='</div>';
			  html +='<div class="form-group"> '; 
				html +='<label class="col-lg-2 control-label"><span class="required">*</span>Wastage</label> '; 
				 html +='<div class="col-lg-2"> ';                                 
			 		 html +='<input type="text" name="ink_use_back['+count+'][total_wastage_back]"  id="total_wastage_back_'+count+'"  readonly="readonly"  value="" class="form-control "/>'; 
				  html +='</div> ';                  
				 html +='</div> '; 
            	 html +='</div> ';

		
			
     	
	  
	  
	  $('#add-more-back').append(html);
	//  show_date(count);

});

</script>
<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>