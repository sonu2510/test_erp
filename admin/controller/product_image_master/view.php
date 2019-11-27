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
	'text' 	=> $display_name.' List',
	'href' 	=> $obj_general->link('product', '', '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> $display_name.' Detail',
	'href' 	=> '',
	'icon' 	=> 'fa-edit',
	'class'	=> 'active', 
);

if(isset($_GET['product_image_id']) && !empty($_GET['product_image_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$product_image_id = base64_decode($_GET['product_image_id']);
		//$product = $obj_product_img->getProduct($product_image_id);
		$product_img = $obj_product_img->getProductimg($product_image_id);
		//printr($product_img);
		$edit = 1;
	}
	
}

//printr($product_img);

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
            
                <header class="panel-heading bg-white">
                 <span>Product Image</span> 
                </header>
              
            
              <div class="panel-body">
                 <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Product</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                             <?php echo $product_img['product_name'];?>
                            </label>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Product Image</label>
                      					 
                                           <?php  if(isset($product_img['product_image_url'])){ ?>
                                           <div id="preview" class="file-preview-frame">
                                            <img class="file-preview-image" src=" <?php echo $product_img['product_image_url'];?>">
                                           </div>
                                            <?php } else {?>
                                            <div class="col-lg-4">
                                                    <label class="control-label normal-font">
                                                    <?php echo 'No Image Found';?>
                                                    </label>
                                                </div>
                                            
                                          <?php  }?>
                                		 
                          </div>
                     
                      <div class="form-group">
        					<div class="col-lg-9 col-lg-offset-3">                
          						<a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a>
        					</div>
        				</div>    
                    
                     
                       
                          <!--  <div class="form-group">
                                <div class="col-lg-9 pull-right">
                                    <a class="btn btn-primary" id="add-history"><i class="fa fa-plus"></i> Add History</a>
                                </div>
                          	</div>-->
                            
                         </div>
                         
                      </div>      
                      
                  
              
                  
                 
                </section>    
      </div>
    </div>
  </section>
</section>










