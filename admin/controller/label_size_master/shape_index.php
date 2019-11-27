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
	'href' 	=> $obj_general->link($rout, '', '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> 'Shape List',
	'href' 	=> '',
	'icon' 	=> 'fa-list',
	'class'	=> 'active',
);

if(!$obj_general->hasPermission('view',$menuId)){
	$display_status = false;
}

if(isset($_GET['sort'])){
	$sort_name = $_GET['sort'];	
}else{
	$sort_name = 'from_quantity';
}

if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'ASC';	
}

if($display_status) {
$product_id=base64_decode($_GET['product_id']);
$total_products = $obj_profit->getLabelPrintShapes($product_id);
?>
<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-list"></i> <?php echo $display_name;?> Shape Master</h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>   
        
      <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading bg-white"> 
		  	 <span>Shapes Listing </span>
          </header>
          <div class="panel-body">
          </div>
          <form name="form_list" id="form_list" method="post">
           <input type="hidden" id="action" name="action" value="" />
          	<div class="table-responsive">
                <table class="table table-striped b-t text-small table-hover">
                  <thead>
                    <tr>
                  	  <th>Product Name</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                  if($total_products){
               	    $labelshapes = $obj_profit->getLabelPrintShapes($product_id);
                      foreach($labelshapes as $shape){ ?>
                        <tr>
                        	<td><?php echo $shape['shape_name'];?></td>
                          	<td><?php 
                                if($product_id == 3){ ?>
                                        <a href="<?php echo $obj_general->link($rout, 'mod=add&product_id='.$_GET['product_id'].'&shape_id='.encode($shape['shape_master_id']).'&product_category=normal', '',1); ;?>"  name="btn_edit" class="btn btn-info btn-xs">Color / Color Edit</a>
                                        <a href="<?php echo $obj_general->link($rout, 'mod=add&product_id='.$_GET['product_id'].'&shape_id='.encode($shape['shape_master_id']).'&product_category=rectangle', '',1); ;?>"  name="btn_edit" class="btn btn-info btn-xs">Full Rectangle Window Edit</a>  
                                        <a href="<?php echo $obj_general->link($rout, 'mod=add&product_id='.$_GET['product_id'].'&shape_id='.encode($shape['shape_master_id']).'&product_category=oval', '',1); ;?>"  name="btn_edit" class="btn btn-info btn-xs">Oval Window Edit</a>  
                            <?php }else if($product_id == 8){
                                ?>
                                        <a href="<?php echo $obj_general->link($rout, 'mod=add&product_id='.$_GET['product_id'].'&shape_id='.encode($shape['shape_master_id']).'&product_category=rectangle', '',1); ;?>"  name="btn_edit" class="btn btn-info btn-xs">Rectangle Window Edit</a>
                                        <a href="<?php echo $obj_general->link($rout, 'mod=add&product_id='.$_GET['product_id'].'&shape_id='.encode($shape['shape_master_id']).'&product_category=normal', '',1); ;?>"  name="btn_edit" class="btn btn-info btn-xs">Color / Color Edit</a>
                           <?php }else {?>
                                         <a href="<?php echo $obj_general->link($rout, 'mod=add&product_id='.$_GET['product_id'].'&shape_id='.encode($shape['shape_master_id']).'&product_category=normal', '',1); ;?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                           <?php } ?>
                           </td>
                        </tr>
                        <?php
                      }
                  } else{ 
                      echo "<tr><td colspan='5'>No record found !</td></tr>";
                  } ?>
                  </tbody>
                </table>
              </div>
          </form>
          <footer class="panel-footer">
          </footer>
        </section>
      </div>
    </div>
  </section>
</section>
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>