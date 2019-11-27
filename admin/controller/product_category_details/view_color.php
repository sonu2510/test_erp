<?php
include("mode_setting.php");
$edit = '';
if(isset($_GET['product_id']) && !empty($_GET['product_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		
		$product_id= base64_decode($_GET['product_id']);
    	$color_details = $obj_catalogue_category->getCatalogue_category_Color_Details($product_id);	
		$edit = 0;
	//	printr($color_details);
	} 
	
}else{ 
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	} 
}


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
	'text' 	=> 'Product  List',
	'href' 	=> $obj_general->link($rout, 'mod=list_product', '',1),
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

 
if($display_status){ 

	
	

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
              <div class="col-lg-12" > 	<?php foreach($color_details as $details){
              	$color = $obj_catalogue_category->getCatalogue_categoryDetails($details['catalogue_category_id']);	
              ?>
            <table id="MyStretchGrid" class="table table-striped b-t text-small">
                 	<tr>
                 	   <center> <header class="panel-heading bg bg-inverse"> <?php echo $details['catalogue_category_name'];?> </header></center>
                 	 
                	</tr>	
        	<?php   $selected_color = array();
                     if(isset($color['color']) && $color['color']){
                        $selected_color=explode(",",$color['color']);
                     }
                     $selected_size = array();
                     
                     if(isset($color['size_master_id']) && $color['size_master_id']){
                         
                        $selected_size=explode(",",$color['size_master_id']);
                     }
                     
                     ?>
                     
                     
               	<tr>
               	     <th>No</th>
               	     <th>Color Name</th>
               	    <?php foreach($selected_size as $size){
               	         $size_volume=$obj_catalogue_category->getSizeData($size);
               	        echo'<th>'.$size_volume['volume'].'<br>'.$size_volume['zipper_name'].'</th>';
               	    }?>
               	 
               	</tr>
               	 <?php 
               	  $i=1;
               	 foreach($selected_color as $color){
               	    $color_name=$obj_catalogue_category->color_name($color);
               	 //  printr($category_color_details);
               	 ?>
               	        <tr >  
               	            <td><?php echo $i;?></td>
               	            <td><?php echo $color_name;?></td>   
               	               <?php 
               	               
               	               foreach($selected_size as $size){
               	                     $size_volume=$obj_catalogue_category->getSizeData($size);
               	                     $category_color_details = $obj_catalogue_category->getCategoryColorDetails($details['catalogue_category_id'],$size,$color);
               	                     $product_code_id = $obj_catalogue_category->getProductcode($size,$color);	
               	                $result = $obj_catalogue_category->getdomesticStock($product_code_id); 
               	                       $rm_qty=0;
               	                        if(isset($result['grouped_s_id'])){
                                    		$dispatch_qty=$obj_catalogue_category->getdomesticStockDispatch($result['grouped_s_id']);
                                    		$rm_qty= ($result['qty']-$dispatch_qty);
                                    		if($rm_qty!='0'){
                                    	    	$rm_qty=$rm_qty;
                                    		}
               	                        }
               	                         $sel_color = array();								   
                                            if(isset($category_color_details['color']) && $category_color_details['color']){
                                                $sel_color=explode(",",$category_color_details['color']);
                                            }
                                        
               	                         
               	                        if(($size ==$category_color_details['size_master_id']) && (in_array($color,$sel_color))){
               	                            
               	                               $check=$rm_qty;
               	                               $style='style="background-color:#bdceab" ';
               	                             
               	                        }else{
               	                               $check='<i class="fa fa-times text-danger"></i>';
               	                               $style='';
               	                        }
               	                     ?> 
               	                       <td <?php echo $style;?>><b><u><?php echo $check;?></u></b></td>
               	         <?php    }?> 
               	         
               	             
               	       
               	        </tr>
               	        
               	        
               	       
               	  <?php  $i++;  }?>
               	</table>
             	   
              <?php }?>
         
         
              <div class="form-group">
              <div class="col-lg-9 col-lg-offset-3">
                  <a class="btn btn-default" href="<?php echo $obj_general->link($rout,'mod=list_product', '',1);?>">Cancel</a>
             
                </div>
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

</script>
<!-- Close : validation script -->

<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>