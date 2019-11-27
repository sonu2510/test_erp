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
if(isset($_GET['address_book_id']) && !empty($_GET['address_book_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$address_book_id = base64_decode($_GET['address_book_id']);
		$address_book = $obj_address->all_customer_address($address_book_id);		
		//printr($address_book);die;
		$edit = 1;
	}
	
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}
//Close : edit

if($display_status){
	//insert 
	if(isset($_POST['btn_save'])){
		$post = post($_POST);		
		//printr($post);die;
		$rand = rand(1,9999);
		
		$profile_name = $_FILES['customer_logo']['name'];
		$random_no = $rand.'_'.$profile_name;
		$profile_size = $_FILES['customer_logo']['size'];
		$profile_type = $_FILES['customer_logo']['type'];
		$profile_old_path = $_FILES['customer_logo']['tmp_name'];
		$profile_new_path = 'upload_logo/'.$rand.'_'.$profile_name;
		move_uploaded_file($profile_old_path,$profile_new_path);
		$insert_id = $obj_address->add_product_customer($post,$profile_new_path);
		
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	if(isset($_POST['btn_update'])){
		$post = post($_POST);	
		//printr($post);die;
		$rand = rand(1,9999);
		$profile_name = $_FILES['customer_logo']['name'];
		$profile_size = $_FILES['customer_logo']['size'];
		$profile_type = $_FILES['customer_logo']['type'];
		$profile_old_path = $_FILES['customer_logo']['tmp_name'];
		$profile_new_path = 'upload_logo/'.$rand.'_'.$profile_name;
		move_uploaded_file($profile_old_path,$profile_new_path);
		
		if($profile_name == '')
		{
			$update_id = $obj_address->update_address_recode($post);	
		}
		else
		{
			$update_id = $obj_address->update_logo($post,$profile_new_path);	
		}
			
		$obj_session->data['success'] = ADD;
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
    <div class="col-sm-8">
      <section class="panel">
      <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
      <div class="panel-body">
      <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
        <div class="form-group">
          <label class="col-lg-3 control-label"><span class="required">*</span> Company Name</label>
          <div class="col-lg-8">
            <input type="text" name="company_name" id="name" value="<?php echo isset($address_book['company_name'])?$address_book['company_name']:'';?>" class="form-control validate[required]">
            <input type="hidden" name="address_id" id="address_id" value="<?php echo isset($address_book)?$address_book['address_book_id']:''; ?>" class="form-control validate[required]"  />
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-3 control-label">Contact Name</label>
          <div class="col-lg-8">
            <input type="text" name="contact_name" value="<?php echo isset($address_book['contact_name'])?$address_book['contact_name']:'' ?>" class="form-control validate">
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-3 control-label">Designation</label>
          <div class="col-lg-8">
            <input type="text" name="designation" value="<?php echo isset($address_book['designation'])?$address_book['designation']:'' ?>" class="form-control validate">
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-3 control-label">Department</label>
          <div class="col-lg-8">
            <input type="text" name="department" value="<?php echo isset($address_book['department'])?$address_book['department']:'' ?>" class="form-control">
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-3 control-label"><span class="required">*</span>Vat No</label>
          <div class="col-lg-3">
            <input type="text" name="vat_no" value="<?php echo isset($address_book['vat_no'])?$address_book['vat_no']:'' ?>" class="form-control">
          </div>
        </div>
        <div class="form-group">
          <div class="col-lg-12">
            <div class="line m-t-large"></div>
          </div>
        </div>
          
        <?php
		  //printr($address_book);
						  if( $edit=='1' && $address_book['company']){
							  //printr($address_book);
											 $address_company = $address_book['company'];
											 //printr($address_company);die;
										 }
										 else
										 {   
										     $company_array = array();
											 //printr($company_array);
											 $address_company[]= array(												
												'company_address_id' => '',
												'c_address' => '',
												'city' => '',
												'state'=>'',
												'country' =>'',
												'pincode' => '',
												'phone_no'=>'',
												'email_1' =>'',
												'email_2' => '',
												'date_added' => '',
												'date_modify' => '',
												'is_delete' => '',
												'company' =>$company_array,
											);	 
										}
										
									
					//printr($address_product);
				   if(!empty($address_company)){
					  $inner_count = 0;
				foreach($address_company as $add_company){
				 //printr($add_company);
									?>
        <div class="form-group customer-div" id="company-<?php echo  $inner_count;?>">
           <header style="margin-left:80px;"><b>Company :</b></header>
           <div class="form-group">
            <label class="col-lg-3 control-label"><span class="required">*</span>Address</label>
            <div class="col-lg-8">
              <textarea class="form-control" row="8" col="15" id="company[<?php echo $inner_count;?>]" name="company[<?php echo $inner_count;?>][c_address]"><?php echo isset($add_company['c_address'])?$add_company['c_address']:'';?></textarea>
            </div>
            <?php if($edit){ 
			?>
            <div class="col-lg-1"> <a onclick="remove_company(<?php echo  $inner_count.','. $add_company['company_address_id'].','.$add_company['is_delete'];?>)" data-original-title="Remove Details" class="btn btn-danger btn-xs btn-circle" data-toggle="tooltip" data-placement="top" title=""> <i class="fa fa-minus"></i></a></div>
            <?php }?>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label">City</label>
            <div class="col-lg-8">
              <input type="text" name="company[<?php echo $inner_count;?>][city]" value="<?php echo isset($add_company)?$add_company['city']:'';?>" class="form-control">
              <input type="hidden" name="company[<?php echo $inner_count;?>][company_address_id]" id="company_id" value="<?php echo isset($add_company)?$add_company['company_address_id']:''; ?>" class="form-control">
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label">State</label>
            <div class="col-lg-8">
              <input type="text" name="company[<?php echo $inner_count;?>][state]" value="<?php echo isset($add_company)?$add_company['state']:'';?>" class="form-control">
            </div>
          </div>
           <div class="form-group">
            <label class="col-lg-3 control-label"><span class="required">*</span>Country</label>
            <div class="col-lg-4">
              <select name="company[<?php echo $inner_count;?>][country]"  class="form-control validate[required]">
                <option value="">Select Country</option>
                <?php 
				   	   $country = $obj_address->get_country(); 
					   //printr($country);die;
					   foreach($country as $country){	
				 ?>
                <option value="<?php echo $country['country_id']; ?>" <?php echo isset($country['country_id']) && ($country['country_id']) == $add_company['country']?'selected':'';?>><?php echo $country['country_name']; ?></option>
                <?php }?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label">Pincode / Zipcode</label>
            <div class="col-lg-8">
              <input type="text" name="company[<?php echo $inner_count;?>][pincode]" value="<?php echo isset($add_company)?$add_company['pincode']:''; ?>" class="form-control">
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label">Phone Number</label>
            <div class="col-lg-8">
              <input type="text" name="company[<?php echo $inner_count;?>][phone_no]" value="<?php echo isset($add_company)?$add_company['phone_no']:''; ?>" class="form-control">
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label email"><span class="required">*</span>Email Id </label>
            <div class="col-lg-8">
              <input type="text" name="company[<?php echo $inner_count;?>][email_1]" value="<?php echo isset($add_company)?$add_company['email_1']:''; ?>" class="form-control validate[required]">
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label email"><span class="required"></span>Email - 2</label>
            <div class="col-lg-8">
              <input type="text" name="company[<?php echo $inner_count;?>][email_2]" value="<?php echo isset($add_company)?$add_company['email_2']:''; ?>" class="form-control validate[]">
            </div>
          </div>
          <?php    
			    $inner_count++; 
				 } }?>
                 <div id="add-more"></div>
                 <div class="form-group" style="margin-left:800px;">
          <div class="col-lg-12">
            <div class="col-lg-2"><a title="" data-placement="top" data-toggle="tooltip" class="btn btn-success btn-xs addmore-company" data-original-title="Add Company"> <i class="fa fa-plus"></i> Add</a> </div>
          </div>
        </div>
          <div class="line m-t-large" style="margin-top:10px;"></div>
        </div>
        
                 
        
        <?php
		 // printr($address_book);
						  if( $edit=='1' && $address_book['factory']){
											 $address_factory = $address_book['factory'];
										 }
										 else
										 {   
										     $factory_array = array();
											 $address_factory[]= array(
											 	'f_address' => '',												
												'city' => '',
												'state' => '',
												'country' => '',
												'pincode' => '',	
												'phone_no' => '',
												'email_1' => '',
												'email_2' => '',
												'date_added' => '',
												'date_modify' => '',
												'is_delete' => '',
												'factory_address_id' => '',
												'factory' =>$factory_array,
											);	 
										}
					//printr($address_factory);
				   if(!empty($address_factory)){
					   	 $f_count = 0;
				foreach($address_factory as $add_factory){
				// printr($add_factory);
									?>
        <div class="form-group factory-div" id="factory-<?php echo  $f_count;?>">
          <header style="margin-left:80px;"><b>Factory :</b></header>
          <div class="form-group">
            <label class="col-lg-3 control-label"><span class="required">*</span>Address</label>
            <div class="col-lg-8">
              <textarea class="form-control" row="8" col="15" id="factory[<?php echo $f_count;?>]" name="factory[<?php echo $f_count;?>][f_address]"><?php echo isset($add_factory['f_address'])?$add_factory['f_address']:'';?></textarea>
            </div>
            <?php if($edit){ 
			//printr($add_factory)?>
            <div class="col-lg-1"> <a onclick="remove_factory(<?php echo  $f_count.','. $add_factory['factory_address_id'].','.$add_factory['is_delete'];?>)" data-original-title="Remove Details" class="btn btn-danger btn-xs btn-circle" data-toggle="tooltip" data-placement="top" title=""> <i class="fa fa-minus"></i></a> </div>
            <?php }?>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label">City</label>
            <div class="col-lg-8">
              <input type="text" name="factory[<?php echo $f_count;?>][city]" value="<?php echo isset($add_factory)?$add_factory['city']:''; ?>" class="form-control">
              <input type="hidden" name="factory[<?php echo $f_count;?>][factory_address_id]" id="factory_id" value="<?php echo isset($add_factory)?$add_factory['factory_address_id']:''; ?>" class="form-control">
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label">State</label>
            <div class="col-lg-8">
              <input type="text" name="factory[<?php echo $f_count;?>][state]" value="<?php echo isset($add_factory)?$add_factory['state']:''; ?>" class="form-control">
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label"><span class="required">*</span>Country</label>
            <div class="col-lg-4">
              <select name="factory[<?php echo $f_count;?>][country]"  class="form-control validate[required]">
                <option value="">Select Country</option>
                <?php 
				   	   $country = $obj_address->get_country(); 
					   //printr($country);die;
					   foreach($country as $country){	
				   ?>
                <option value="<?php echo $country['country_id']; ?>" <?php echo isset($country) && ($country['country_id'])== $add_factory['country']?'selected':'';?>><?php echo $country['country_name']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label">Pincode / Zipcode</label>
            <div class="col-lg-8">
              <input type="text" name="factory[<?php echo $f_count;?>][pincode]" value="<?php echo isset($add_factory)?$add_factory['pincode']:''; ?>" class="form-control">
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label">Phone Number</label>
            <div class="col-lg-8">
              <input type="text" name="factory[<?php echo $f_count;?>][phone_no]" value="<?php echo isset($add_factory)?$add_factory['phone_no']:'';?>"  class="form-control">
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label email"><span class="required">*</span>Email </label>
            <div class="col-lg-8">
              <input type="text" name="factory[<?php echo $f_count;?>][email_1]" value="<?php echo isset($add_factory)?$add_factory['email_1']:''; ?>"  class="form-control validate[required]">
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-3 control-label email"><span class="required"></span>Email - 2</label>
            <div class="col-lg-8">
              <input type="text" name="factory[<?php echo $f_count;?>][email_2]"  value="<?php echo isset($add_factory)?$add_factory['email_2']:''; ?>"  class="form-control validate[]">
            </div>
          </div>
           <?php   
		 $f_count++;	
			}}
		  ?>
       <div id="add-fac"></div>
        <div class="form-group" style="margin-left:800px;">
          <div class="col-lg-12">
            <div class="col-lg-2"> <a title="" data-placement="top" data-toggle="tooltip" class="btn btn-success btn-xs addmore-factory" data-original-title="Add Factory"> <i class="fa fa-plus"></i> Add </a> </div>
          </div>
        </div>
          <div class="line m-t-large" style="margin-top:10px;"></div>
        </div>
       
        <div class="form-group">
          <label class="col-lg-3 control-label email">Website </label>
          <div class="col-lg-8">
            <input type="text" name="website" value="<?php echo isset($address_book['website'])?$address_book['website']:'';?>" class="form-control">
          </div>
        </div>
        <?php
				$industrys = $obj_address ->getIndustrys();
				//printr($industrys);	?>
        <div class="form-group">
          <label class="col-lg-3 control-label">Customer Product</label>
          <div class="col-lg-4">
            <select name="industry" class="form-control">
				<?php 
               echo '<option value="">Selected Product</option>';
                foreach($industrys as $industry){
                   // printr($industry);
                    if(strtolower($industry['enquiry_industry_id'])==strtolower($address_book['industry']))
                    //if(isset($industry['enquiry_industry_id'])==$address_book['industry'])
                    				echo '<option value="'.$industry['enquiry_industry_id'].'" selected=selected>'. $industry['industry'].'</option>';
					else
                    	echo '<option value="'.$industry['enquiry_industry_id'].'">'. $industry['industry'].'</option>';
                }
                ?>
            </select>
          </div>
        </div>
        <br/>
        <div class="form-group">
            <label class="col-lg-3 control-label"><span class="required"></span>Exhibition Name</label>
            <div class="col-lg-4">
              <select name="exhibition"  class="form-control ">
                <option value="">Select Name</option>
                <?php 
				   	   $exhibition = $obj_address->exhibition_name(); 
					   foreach($exhibition as $name){	
				   ?>
                <option value="<?php echo $name['exhibition_id']; ?>" <?php echo isset($address_book) && ($address_book['exhibition_id'])== $name['exhibition_id']?'selected':'';?>><?php echo $name['exhibition_name']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        <div class="form-group">
          <label class="col-lg-3 control-label">Remark</label>
          <div class="col-lg-8">
            <textarea class="form-control" row="10" col="15" name="remark"><?php echo isset($address_book['remark'])?$address_book['remark']:'';?></textarea>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-3 control-label">Logo</label>
          <div class="col-lg-8 media">
            <div class="bg-light pull-left text-center media-large thumb-large" id="logo"> <img src="<?php echo isset($address_book['logo'])?$address_book['logo']:''; ?>" alt=""> </div>
            <div class="media-body">
              <input type="file" name="customer_logo" title="Change" class="btn btn-sm btn-info m-b-small">
              <br>
              <?php if($edit==1){ ?>
              <button type="button" class="btn btn-sm btn-default" id="logo_del" value="<?php echo $address_book['address_book_id'] ?>">Delete</button>
			  <?php } ?>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-3 control-label">Status</label>
          <div class="col-lg-4">
            <select name="status" id="status" class="form-control">
              <option value="1" <?php echo (isset($address_book['status']) && $address_book['status'] == 1)?'selected':'';?> > Active</option>
              <option value="0" <?php echo (isset($address_book['status']) && $address_book['status'] == 0)?'selected':'';?>> Inactive</option>
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
            <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a> </div>
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
<script type="text/javascript">
    jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#form").validationEngine();
		jQuery(".company-div").validationEngine();
			
		
		
    });
	
	
	/*$(document).ready(function() {
	
		$("#input-date_0").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
		$("#followup_date").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
		//$("#input-date").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
	
		});*/
	
	function remove_company(count,company_id,is_delete){
		$('#company-'+count).remove();
		var remove_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=remove_company', '',1);?>");
			$.ajax({
				url : remove_url,
				method : 'post',
				data : {company_id : company_id,is_delete:is_delete},
				success: function(response){
				//alert(response);	
				
				},
				error: function(){
					return false;	
				}
		});	
	}
	

		
	
	function remove_factory(count,factory_id,is_delete){
		$('#factory-'+count).remove();
		var remove_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=remove_factory', '',1);?>");
			$.ajax({
				url : remove_url,
				method : 'post',
				data : {factory_id : factory_id,is_delete:is_delete},
				success: function(response){
				//alert(response);	
				
				},
				error: function(){
					return false;	
				}
		});	
	}
	
	  $(document).ready(function(){
	$('#logo_del').click(function(){
		var del = $('#logo_del').val();
			//alert(del);
			var remove_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=remove_logo', '',1);?>");
				$.ajax({
					url : remove_url,
					method : 'post',
					data : {del : del},
					success: function(response){
					$('#logo').hide();	
					}
			});	
	})
})           
				    
	$('.addmore-company').click(function(){
		//alert("add more");
		var count = $('.customer-div').length;
		//alert(count);
		var html = '';
		//html
		html += '<div class="form-group customer-div" id="company-'+count+'">'; 
        html +='<div class="line m-t-large" style="margin-top:10px;"></div>';        
          html +='<div class="col-lg-1"></div>';
          html +='<div class="col-lg-12">';
          
    		  html +='<header style="margin-left:40px;"><b>Company :</b></header>';
			  html +=' <div class="form-group" style="margin-right:30px;">';
									html +=' <label class="col-lg-3 control-label"><span class="required">*</span>Address</label>';
									 html +='  <div class="col-lg-8">';
										html +='<textarea class="form-control" row="8" col="15" name="company['+count+'][c_address]"></textarea>';
									  html +='</div>';
									  
									  html += '<div class="col-lg-1">';
             html +='<a onclick="remove_company('+count+')" data-original-title="Remove Product" class="btn btn-danger btn-xs btn-circle" data-toggle="tooltip" data-placement="top" title=""><i class="fa fa-minus"></i></a>';
        html += '</div>';
				html +='</div>';
				
			   html +=' <div class="form-group" style="margin-right:30px;">';
									html +='  <label class="col-lg-3 control-label">City</label>';
									 html +=' <div class="col-lg-9">';
										html +=' <input type="text" name="company['+count+'][city]" class="form-control">';
										html +='<input type="hidden" name="company['+count+'][company_address_id]" id="company_id" class="form-control">';
									  html +='</div>';
				html +='</div>';
				
				
				html +=' <div class="form-group" style="margin-right:30px;">';
									html +='<label class="col-lg-3 control-label">State</label>';
									 html +=' <div class="col-lg-9">';
										html +=' <input type="text" name="company['+count+'][state]" class="form-control">';
									  html +='</div>';
				html +='</div>';
		   
		   
		   html +='<div class="form-group" style="margin-right:30px;">'; 
                 html +='<label class="col-lg-3 control-label">Country</label>';
                 html +='<div class="col-lg-6">';
                   html +='<select class="form-control validate[required]" name="company['+count+'][country]">';
				    html +='<option value="">Select Country</option>';
                     		 <?php 
							   $country = $obj_address->get_country(); 
							   foreach($country as $country){	
					   		?>
						 	
                          html +='<option value="<?php echo $country['country_id']; ?>"><?php echo $country['country_name']; ?></option>';
                            <?php } ?>
                    html +='</select>';
                 html +='</div>';
               html +='</div>';
		   
		          
		   html +=' <div class="form-group" style="margin-right:30px;">';
									html +=' <label class="col-lg-3 control-label">Pincode / Zipcode</label>';
									 html +=' <div class="col-lg-9">';
										html +='<input type="text" name="company['+count+'][pincode]"  class="form-control">';
									  html +='</div>';
				html +='</div>';
		   
		   
		    html +=' <div class="form-group" style="margin-right:30px;">';
									html +=' <label class="col-lg-3 control-label">Phone Number</label>';
									 html +=' <div class="col-lg-9">';
										html +='<input type="text" name="company['+count+'][phone_no]"  class="form-control">';
									  html +='</div>';
				html +='</div>';
				
				  
				  html +=' <div class="form-group" style="margin-right:30px;">';
									html +='<label class="col-lg-3 control-label email"><span class="required">*</span>Email </label>';
									 html +=' <div class="col-lg-9">';
										html +='<input type="text" name="company['+count+'][email_1]"  class="form-control">';
									  html +='</div>';
				html +='</div>';
				
				html +=' <div class="form-group" style="margin-right:30px;">';
									html +=' <label class="col-lg-3 control-label email"><span class="required"></span>Email - 2</label>';
									 html +=' <div class="col-lg-9">';
										html +='<input type="text" name="company['+count+'][email_2]"  class="form-control">';
									  html +='</div>';
				html +='</div>';
				
        html +='</div>';
	  
	  html +='<div class="line m-t-large" style="margin-top:10px;"></div>'; 
	  
	  $('#add-more').append(html);
	  //show_date(count);

});

$('.addmore-factory').click(function(){
		//alert("add more");
		var count = $('.factory-div').length;
		//alert(count);
		var html = '';
		//html
		html += '<div class="form-group factory-div" id="factory-'+count+'">'; 
        html +='<div class="line m-t-large" style="margin-top:10px;"></div>';    
		    
          html +='<div class="col-lg-1"></div>';
		  
          html +='<div class="col-lg-12">';
          	  html +='<header style="margin-left:40px;"><b>Factory :</b></header>';
			  html +=' <div class="form-group" style="margin-right:30px;">';
									html +=' <label class="col-lg-3 control-label"><span class="required">*</span>Address</label>';
									 html +='  <div class="col-lg-8">';
										html +='<textarea class="form-control" row="8" col="15" name="factory['+count+'][f_address]"></textarea>';
									  html +='</div>';
									  html += '<div class="col-lg-1">';
             html +='<a onclick="remove_factory('+count+')" data-original-title="Remove Factory" class="btn btn-danger btn-xs btn-circle" data-toggle="tooltip" data-placement="top" title=""><i class="fa fa-minus"></i></a>';
        html += '</div>';
				html +='</div>';
					
			   html +=' <div class="form-group" style="margin-right:30px;">';
									html +='  <label class="col-lg-3 control-label">City</label>';
									 html +=' <div class="col-lg-9">';
										html +='<input type="text" name="factory['+count+'][city]" class="form-control">';
									  html +='</div>';
				html +='</div>';
				
				
				html +=' <div class="form-group" style="margin-right:30px;">';
									html +='<label class="col-lg-3 control-label">State</label>';
									 html +=' <div class="col-lg-9">';
										html +=' <input type="text" name="factory['+count+'][state]" class="form-control">';
									  html +='</div>';
				html +='</div>';
		   
		   
		   html +='<div class="form-group" style="margin-right:30px;">'; 
                 html +='<label class="col-lg-3 control-label">Country</label>';
                 html +='<div class="col-lg-6">';
                   html +='<select class="form-control validate[required]" name="factory['+count+'][country]">';
				    html +='<option value="">Select Country</option>';
                     		 <?php 
							   $country = $obj_address->get_country(); 
							   foreach($country as $country){	
					   		?>
						 	
                          html +='<option value="<?php echo $country['country_id']; ?>"><?php echo $country['country_name']; ?></option>';
                            <?php } ?>
                    html +='</select>';
                 html +='</div>';
               html +='</div>';
		   
		          
		   html +=' <div class="form-group" style="margin-right:30px;">';
									html +=' <label class="col-lg-3 control-label">Pincode / Zipcode</label>';
									 html +=' <div class="col-lg-9">';
										html +='<input type="text" name="factory['+count+'][pincode]"  class="form-control">';
									  html +='</div>';
				html +='</div>';
		   
		   
		    html +=' <div class="form-group" style="margin-right:30px;">';
									html +=' <label class="col-lg-3 control-label">Phone Number</label>';
									 html +=' <div class="col-lg-9">';
										html +='<input type="text" name="factory['+count+'][phone_no]"  class="form-control">';
									  html +='</div>';
				html +='</div>';
				
				  
				  html +=' <div class="form-group" style="margin-right:30px;">';
									html +='<label class="col-lg-3 control-label email"><span class="required">*</span>Email </label>';
									 html +=' <div class="col-lg-9">';
										html +='<input type="text" name="factory['+count+'][email_1]"  class="form-control">';
									  html +='</div>';
				html +='</div>';
				
				html +=' <div class="form-group" style="margin-right:30px;">';
									html +=' <label class="col-lg-3 control-label email"><span class="required"></span>Email - 2</label>';
									 html +=' <div class="col-lg-9">';
										html +='<input type="text" name="factory['+count+'][email_2]"  class="form-control">';
									  html +='</div>';
				html +='</div>';
				
        html +='</div>';
	  
	  html +='<div class="line m-t-large" style="margin-top:10px;"></div>'; 
	  
	  $('#add-fac').append(html);
	  //show_date(count);

});

/*function show_date(n)
{
	$("#input-date_"+n).datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
}*/


</script> 
<!-- Close : validation script -->

<?php
 } 
else
{  
		include(DIR_ADMIN.'access_denied.php');
		
}
?>
