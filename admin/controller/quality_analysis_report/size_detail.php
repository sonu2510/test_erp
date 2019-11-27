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
	'text' 	=>' Product Detail ',
	'href' 	=> $obj_general->link($rout, '', '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);
$bradcums[] = array(
	'text' 	=> ' Product Size Detail',
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
		$product = $obj_quality_report->getProduct($product_id);
		//printr($product);
		$edit = 1;
		//printr($product_id);die;
	}
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}
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
      <h4><i class="fa fa-edit"></i> <?php echo $display_name;?></h4>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <?php include("common/breadcrumb.php");?>
      </div>
      <div class="col-sm-11">
        <section class="panel">
          <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
            <span class="text-muted m-l-small pull-right">
             	<a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add&product_id='.encode($product_id), '',1);?>"><i class="fa fa-plus"></i> Add New Report </a>
             </span>


          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
              <?php if($edit==1 && !empty($edit)){ ?>
              <div class="form-group">
                <label class="col-lg-5 control-label">Product Name</label>
                <div class="col-lg-7">
                  <label class="control-label normal-font"> <?php echo $product['product_name']; ?> </label>
                </div>
              </div>
              <?php } 
			  if($product_id=='10')
			  	$gusset='Flap';
			else
				$gusset='Gusset';
				?>
              <table border="0"  width="80%" class="table  b-t text-small" >
                <tr>
            
 				  <td><b>Zipper Name </b> </td>
 				    <td><b>Volume (Width x Height x Gusset) </b> </td>
                
               </tr>

                <tr>

                    
                      <?php 
                      $quantity_tool_prices = $obj_quality_report->getSize($product_id);
								if($quantity_tool_prices){
							   		
							   		foreach($quantity_tool_prices as $quantity_tool_price)

							   		{
							   		 ?>
                     
                     
		                        <td>    <a href="<?php echo $obj_general->link($rout, 'mod=catagory_detail&product_id='.encode($product_id).'&size_id='.encode($quantity_tool_price['size_master_id']).'', '',1); ;?>"  > <?php  echo    $quantity_tool_price['zipper_name']; ?> </a></td>  
		                         <td>     <a href="<?php echo $obj_general->link($rout, 'mod=catagory_detail&product_id='.encode($product_id).'&size_id='.encode($quantity_tool_price['size_master_id']).'', '',1); ;?>"  ><?php  echo    $quantity_tool_price['volume'].' ('.$quantity_tool_price['width'].' x '.$quantity_tool_price['height'].' x '.$quantity_tool_price['gusset'].')'; ?></a> </td>
		                      
                       
                      </tr>
                      <?php } }?>
                 
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
