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
if(isset($_GET['product_id']) && !empty($_GET['product_id'])){
	if(!$obj_general->hasPermission('view',$menuId)){
		$display_status = false;
	}else{
		$product_id = base64_decode($_GET['product_id']);
		$product = $obj_tool->getProduct($product_id);
		//printr($product);
		$edit = 1;
	}
}else{
	if(!$obj_general->hasPermission('view',$menuId)){
		$display_status = false;
	}
}
//Close : edit

if($display_status){
	//insert user
	if(isset($_POST['btn_save'])){
		$post = post($_POST);		
	//	printr($post);die;
		$insert_id = $obj_tool->addTool($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update']) && $edit){
		$post = post($_POST);
		//printr($post);die;
		$product_id = $product['product_id'];
		$obj_tool->updateTool($product_id,$post);
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
        
      <div class="col-sm-9">
        <section class="panel">
          <header class="panel-heading bg-white"> Size Table In MM </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
              <?php if($edit==1 && !empty($edit)){ ?>
                  <div class="form-group">
                      <label class="col-lg-3 control-label">Product Name</label>
                      <div class="col-lg-9">
                         <label class="control-label normal-font">
                           <?php echo $product['product_name']; ?>
                         </label>   
                      </div>
                  </div>
              <?php } ?>
              
              <table border="0"  width="100%" class="table  b-t text-small">
              	<tr>
                	<td> <b>Width(from) </b> </td>
                   <td><b>Width(to)</b></td>
                   <td><?php if(isset($product) && $product['gusset_available']== 1) {?><b>Gusset</b><?php }?></td>
                 <!--  <td><b>Price</b></td>-->
                 
                </tr>
             
                	<?php 
							 $quantity_tool_prices = $obj_tool->getToolPrices($product_id);
							 if($quantity_tool_prices){
							   $inner_count = 0;
							   foreach($quantity_tool_prices as $quantity_tool_price){
							?>	 <tr><td>
                                        	<?php echo $quantity_tool_price['width_from']; ?>
                                    </td>
                                    <td>
                                    	<?php echo $quantity_tool_price['width_to']; ?>
                      
                         </td>
                                   <?php if(isset($product) && $product['gusset_available']== 1) {?> <td>
                                      <?php echo $quantity_tool_price['gusset']; ?>
                                  </td><?php }?>
                                   <!-- <td>
                                       <?php //echo $quantity_tool_price['price']; ?>                                       
                                  </td>-->
                                    <?php if($inner_count==0){ ?>
                                    <?php } else { ?>
                               </tr>
                                    <?php } ?>
                                <?php $inner_count++; } } else { ?>
                          <tr>  <td></td>
                                    <td>
                                 </td>
                                 <?php if(isset($product) && $product['gusset_available']== 1) {?>
                                    <td
                                   </td>
                                   <?php }?>
                                    <td>
                                 </td>
                                   </tr>
                                <?php } ?>
                                
              
                <tr>
                	<td colspan="6"><div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">           
				   <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a>
                </div>
              </div>
                    </td>
                </tr>
              </table>
            </form>
          </div>
        </section>
        
      </div>
    </div>
  </section>
</section>
<?php } else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>