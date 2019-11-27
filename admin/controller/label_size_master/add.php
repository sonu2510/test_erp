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
	'text' 	=> 'Shape List',
	'href' 	=> $obj_general->link($rout.'&mod=shape_index&product_id='.$_GET['product_id'], '', '',1),
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
		$product_id = base64_decode($_GET['product_id']);
		$shape_id = base64_decode($_GET['shape_id']);
		$product_category = $_GET['product_category'];
		$obj_profit->add_label_size_master($product_id,$shape_id,$product_category,$post);  
		//$insert_id = $obj_profit->addProfit($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout,'mod=add&product_id='.$_GET['product_id'].'&shape_id='.$_GET['shape_id'].'&product_category=normal', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){

		$post = post($_POST);
		//printr($post);die;
		$product_id = base64_decode($_GET['product_id']);
		$shape_id = base64_decode($_GET['shape_id']);
		$product_category = $_GET['product_category'];
		$obj_profit->update_label_size_master($product_id,$shape_id,$product_category,$post);  
		   
		
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout,'mod=add&product_id='.$_GET['product_id'].'&shape_id='.$_GET['shape_id'].'&product_category=normal', '',1));
	}
	
$product_id = base64_decode($_GET['product_id']);
$shape_id = base64_decode($_GET['shape_id']);
$product_category = $_GET['product_category'];
$stored_data=$obj_profit->get_label_size_master_details($product_id,$shape_id,$product_category);	

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
                         <b>Pouch Type </b>= <?php echo $_GET['product_category']; ?>
                      </div>
                  </div>
              <?php } ?>
              
              
       
              
              <div class="form-group more_price">
             
                <div class="col-lg-12">
				   <?php 
				     
					?>
                    <div class="col-md-12 profit-row" id="row" >
                          
                      
                    <div class="row ">
                                   
                                     <div class="col-lg-4"><b>Size</b></div>
                                      
                                      <div class="col-lg-1"><b> Sticker width</b></div>
                                      <div class="col-lg-1"><b> Sticker Height</b></div>
                                      <div class="col-lg-2"><b>Minimum Sticker printing Width X Height</b></div>
                                      
                                      <div class="col-sm-2"><b></b></div>
                                      </div>
                        <div class=" second-part">  
							
								
                                <?php 
								    if(empty($stored_data)){
									$size_masters = $obj_profit->getSize($product['product_id']);
									
								?>
								<?php 
                                if(!empty($size_masters)){
                                
                                foreach($size_masters as $size_data){
                                
                                ?>
                                <div class="row">   
                              <?php 
                              $size_details=$obj_profit->Size_detail($size_data['size_master_id']);
                              $zipper_data=$obj_profit->ZipperData($size_details['product_zipper_id']);
                             // printr($zipper_data);
                              
                              ?>
                                    <div class="col-lg-4" >
                                        <div class="col-lg-12">
                                            <input type="text" name="" value="<?php echo '('.$size_data['volume'].') '.$size_data['width'].'X'.$size_data['height'].'X'.$size_data['gusset']; ?> <?php echo $zipper_data['zipper_name']; ?>" class="form-control"  readonly><br>
                                            <input type="hidden" name="size_id[]" value="<?php echo $size_data['size_master_id']; ?>" class="form-control " >
                                        </div>
                                        
                                    </div>
                                    
                                    
                                   
                                    
                                     <div class="col-lg-1" >
                                        <input type="text" name="max_width[]" value="" class="form-control " placeholder="width">
                                    </div>
                                    
                                     <div class="col-lg-1" >
                                        <input type="text" name="max_height[]" value="" class="form-control " placeholder="height">
                                    </div>
                                    
                                     <div class="col-lg-1" >
                                        <input type="text" name="min_width[]" value="" class="form-control " placeholder="Min width">
                                    </div>
                                    
                                     <div class="col-lg-1" >
                                        <input type="text" name="min_height[]" value="" class="form-control " placeholder="Min height">
                                    </div>
                                    
                                    
                                    
                                    
                                </div>
                                <?php 
                                    }
                                }
								    }else{
                                ?>
                                
                                	<?php 
                               
                                foreach($stored_data as $stored_size_data){
                                 $size_details=$obj_profit->Size_detail($stored_size_data['size_master_id']);
                                 $zipper_data=$obj_profit->ZipperData($size_details['product_zipper_id']);
                                ?>
                                <div class="row">   
                              
                                    <div class="col-lg-4" >
                                        <div class="col-lg-12">
                                            <input type="text" name="" value="<?php echo '('.$stored_size_data['volume'].') '.$stored_size_data['width'].'X'.$stored_size_data['height'].'X'.$stored_size_data['gusset']; ?> <?php echo $zipper_data['zipper_name']; ?>" class="form-control"  readonly><br>
                                            <input type="hidden" name="size_id" value="<?php echo $stored_size_data['size_master_id']; ?>" class="form-control " >
                                            <input type="hidden" name="label_size_master_id[]" value="<?php echo $stored_size_data['label_size_master_id']; ?>" class="form-control " >
                                        </div>
                                        
                                    </div>
                                    
                                     <div class="col-lg-1" >
                                        <input type="text" name="max_width[]" value="<?php echo $stored_size_data['max_width']; ?>" class="form-control " placeholder="Max width">
                                    </div>
                                    
                                    <div class="col-lg-1" >
                                        <input type="text" name="max_height[]" value="<?php echo $stored_size_data['max_height']; ?>" class="form-control " placeholder="Max height">
                                    </div>
                                    
                                    <div class="col-lg-1" >
                                        <input type="text" name="min_width[]" value="<?php echo $stored_size_data['min_width']; ?>" class="form-control " placeholder="Min width">
                                    </div>
                                    
                                     <div class="col-lg-1" >
                                        <input type="text" name="min_height[]" value="<?php echo $stored_size_data['min_height']; ?>" class="form-control " placeholder="Min height">
                                    </div>
                                    
                                     
                                    
                                    
                                </div>
                                <?php 
                                    }
                                
								    
                                ?>
                                
                                
                                
                                <?php 
                                
								    }
                                ?>
                         
                        </div>
                      
                    </div>
                    
                   
                  
                    
                    <?php $count++; ?>
                    <?php   ?>
                </div> 
			  </div>
              
              
              <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
               <?php if(!empty($stored_data)){?>
                  	<button type="submit" name="btn_update" id="btn_update" class="btn btn-primary">Update </button>
                <?php } else { ?>
                	<button type="submit" name="btn_save" id="btn_save" class="btn btn-primary">Save </button>	
                <?php } ?> 
                  <a class="btn btn-default" href="<?php echo $obj_general->link($rout.'&mod=shape_index&product_id='.$_GET['product_id'], '', '',1);?>">Cancel</a>
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
	

	
</script> 
<!-- Close : validation script -->

<?php } else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>