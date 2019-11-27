<?php
include("mode_setting.php");

$bradcums = array();
$bradcums[] = array(
	'text' 	=> 'Dashboard',
	'href' 	=> $obj_general->link('dashboard', '', '',1),
	'icon' 	=> 'fa-home',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> $display_name,
	'href' 	=> $obj_general->link($rout, '', '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);

$edit = '';
 
$limit = LISTING_LIMIT;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}

if($display_status){

if(isset($_GET['domain_data_id']) && !empty($_GET['domain_data_id'])){
	if(!$obj_general->hasPermission('view',$menuId)){
		$display_status = false;
	}else{
			
		$domain_data_id = decode($_GET['domain_data_id']);
		$product = $obj_website_list->getAddWeb($domain_data_id);
		
	}
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
} 
$bradcums[] = array(
	'text' 	=> 'Enquiry Detail List',
	
	'href' 	=> $obj_general->link($rout, '&mod=view_enquiry&domain_name='.encode($product['domain_name']), '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> $display_name.' View',
	'href' 	=> '',
	'icon' 	=> 'fa-list',
	'class'	=> 'active',
);
	
?>
<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-edit"></i> <?php echo $display_name; ?></h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div> 
     
      <div class="col-sm-8">
        <section class="panel">
          <header class="panel-heading bg-white"> <?php echo $display_name;?> View </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
 
               <div class="form-group">
                <label class="col-lg-3 control-label">Name :</label>
                <div class="col-lg-4">
                 <label class="control-label normal-font">
					 <?php echo $product['name'];?>
                    </label>
                 </div>
              </div> 
              
            	<div class="form-group">
                <label class="col-lg-3 control-label">Company Name:</label>
                <div class="col-lg-4">
              		 <label class="control-label normal-font">
              		 <?php echo $product['company_name'];?>
                     </label>
                </div>
              </div> 
                
                <div class="form-group">
                	<label class="col-lg-3 control-label">Address: </label>
                    <div class="col-lg-4">
                    	<label class="control-label normal-font">
						<?php echo $product['address']; ?> 
                        </label>
                    </div>
				</div>
                
                <div class="form-group">
                	<label class="col-lg-3 control-label">Country: </label>
                    <div class="col-lg-4">
                    <label class="control-label normal-font">
                    	<?php echo $product['country']; ?>
                    </label>
					
                    </div>
				 </div> 
                <div class="form-group">
                	<label class="col-lg-3 control-label">State: </label>
                    <div class="col-lg-4">
                    <label class="control-label normal-font">
                    	<?php echo $product['state']; ?>
                    </label>
					
                    </div>
				 </div>
				 
				 <div class="form-group">
                	<label class="col-lg-3 control-label">City: </label>
                    <div class="col-lg-4">
                    <label class="control-label normal-font">
                    	<?php echo $product['city']; ?>
                    </label>
					
                    </div>
				 </div>
				 
             	<div class="form-group">
                	<label class="col-lg-3 control-label">Phone no: </label>
                    <div class="col-lg-4">
                    <label class="control-label normal-font">
						<?php echo $product['phone_no']; ?>
                        </label>
                    </div>
			    </div>
				
                 <div class="form-group">
                	<label class="col-lg-3 control-label">Email: </label>
                    <div class="col-lg-4">
                    <label class="control-label normal-font">
                    	<?php echo $product['email']; ?>
                      </label>  
                    </div>
				 </div>
                 
                 <div class="form-group">
                	<label class="col-lg-3 control-label">Name of the product to be filled inside the bags: </label>
                    <div class="col-lg-4">
                     <label class="control-label normal-font">
                    	<?php echo $product['product_name']; ?>
                      </label> 
                    </div>
				 </div>
                 
                 <div class="form-group">
                	<label class="col-lg-3 control-label">Weight to be filled in each bags: </label>
                    <div class="col-lg-4">
                    <label class="control-label normal-font">
                    	<?php echo $product['weight']; ?>
                      </label>
                    </div>
				 </div>
                	
                 <div class="form-group">
                	<label class="col-lg-3 control-label">Number of bags/rolls required: </label>
                    <div class="col-lg-4">
                     <label class="control-label normal-font">
                    	<?php echo $product['number_bags']; ?>
                      </label>
                    </div>
				 </div>
                
                 <div class="form-group">
                	<label class="col-lg-3 control-label">Message: </label>
                    <div class="col-lg-4">
                    <label class="control-label normal-font">
                    <?php echo $product['message']; ?>
                    </label>
                    </div>
				 </div>
                
                 <div class="form-group">
                 <div class="col-lg-9 col-lg-offset-3">
                 	<a class="btn btn-default" href="<?php echo $obj_general->link($rout, 'mod=view_enquiry&domain_name='.encode($product['domain_name']), '',1);?>">Cancel</a>
                 </div> 
                 </div>   
         
       		</form>
          </div>
        </section>
        
      </div>
    </div>
  </section>
</section>

<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<?php 
 } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>




