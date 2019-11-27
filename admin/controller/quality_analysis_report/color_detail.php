<?php
include("mode_setting.php");
if(isset($_GET['product_id']) && !empty($_GET['product_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$product_id = base64_decode($_GET['product_id']);
		$product = $obj_quality_report->getProduct($product_id);
		//printr($product);
		
		//printr($product_id);die;
	}
} if(isset($_GET['size_id']) && !empty($_GET['size_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$size_id = base64_decode($_GET['size_id']);	
	}
} if(isset($_GET['category_id']) && !empty($_GET['category_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$category_id = base64_decode($_GET['category_id']);	
		   $Color_category = $obj_quality_report->getColor_category_Color($category_id);
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
	'text' 	=> 'Product Detail  ',
	'href' 	=> $obj_general->link($rout, '', '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);
$bradcums[] = array(
	'text' 	=> ' Product Size Detail ',
	'href' 	=> $obj_general->link($rout, 'mod=size_detail&product_id='.encode($product_id).'&size_id='.encode($size_id).'', '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);$bradcums[] = array(
	'text' 	=> ' color Category  Detail',
	'href' 	=> $obj_general->link($rout, 'mod=catagory_detail&product_id='.encode($product_id).'&size_id='.encode($size_id).'&category_id='.encode($category_id), '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);
$bradcums[] = array(
	'text' 	=> 'color Detail',
	'href' 	=> '',
	'icon' 	=> 'fa-edit',
	'class'	=> 'active',
);
//Close : bradcums
//Start : edit
$edit = '';

//printr($product_id);
//Close : edit
if($display_status){
	//insert user
	if(isset($_POST['btn_save'])){
		$post = post($_POST);
//printr($post);die;		
		$insert_id = $obj_tool->addTool($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		//printr($post);die;
		$product_id = $product['product_id'];
		//printr($product_id);
		$obj_quality_report->updateTool($product_id,$post);
		
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
?>

<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-edit"></i> Color Category List</h4>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <?php include("common/breadcrumb.php");?>
      </div>
      <div class="col-sm-11">
        <section class="panel">
          <header class="panel-heading bg-white"> Color Category  Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
          
              <div class="form-group">
                <label class="col-lg-2 control-label">Product Name</label>
                <div class="col-lg-4">
                  <label class="control-label normal-font"> <?php echo $product['product_name']; ?> </label>
                </div>
             
                <label class="col-lg-1 control-label">Category Name</label>
                <div class="col-lg-3">
                  <label class="control-label normal-font"> <?php echo $Color_category['category']; ?> </label>
                </div>
              </div>
			   <div class="form-group">
				<label class="col-lg-2 control-label">Size Detail</label>
                <div class="col-lg-4">
					<?php $size=$obj_quality_report->getsizeDetail(decode($_GET['size_id']));?>
                  <label class="control-label normal-font"> <?php echo  $size['zipper_name'].'  '.$size['volume'].' ('.$size['width'].' x '.$size['height'].' x '.$size['gusset'].')'; ?> </label>
                </div>
			 </div>
              <table border="0"  width="80%" class="table  b-t text-small" >
                <tr>
            
 				  <td><b>Color Name </b> </td>
 				    
                
               </tr>

             

                    
                      <?php 
                      $Color_category = $obj_quality_report->getColor_category_Color($category_id);
                  //    printr($Color_category);
                      	$p = explode(',',$Color_category['color']);
                      		 if(!empty($p)){
									 foreach($p as $s){
										 //	printr($s);
										 	$Color_name= $obj_quality_report->getColorName($s);
										 	//printr($Color_name);
								
								?>
                        <tr>
                     
		                        <td>   <a href="<?php echo $obj_general->link($rout, 'mod=view&product_id='.encode($product_id).'&size_id='.encode($size_id).'&category_id='.encode($category_id).'&color_id='.encode($Color_name['pouch_color_id']), '',1);?>"  >		<?php  echo $Color_name['color']; ?>                        	
		                       
		                        </a> </td>  
		                        
		                      
                       
                      </tr>
                 	<?php }}?>
                 
                    </table></td>
                </tr>
                <tr>
               
                </tr>
              </table>
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
  
	</script>
<!-- Close : validation script -->
<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>
