<?php
include("mode_setting.php");
if(isset($_GET['user_id']) && !empty($_GET['user_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$user_id = base64_decode($_GET['user_id']);
	
	}
}
if(isset($_GET['user_type_id']) && !empty($_GET['user_type_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$user_type_id = base64_decode($_GET['user_type_id']);
	
	}
}
$bradcums = array();
$bradcums[] = array(
	'text' 	=> 'Dashboard',
	'href' 	=> $obj_general->link('dashboard', '', '',1),
	'icon' 	=> 'fa-home',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> $display_name .'details',
	'href' 	=> $obj_general->link($rout, '', '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> $display_name,
	'href' 	=> $obj_general->link($rout, '', '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);
if(!$obj_general->hasPermission('view',$menuId)){
	$display_status = false;
}
$edit = '';
if(isset($_GET['user_id']) && !empty($_GET['user_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$user_id = base64_decode($_GET['user_id']);
	
	}
}
if(isset($_GET['user_type_id']) && !empty($_GET['user_type_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$user_type_id = base64_decode($_GET['user_type_id']);
	
	}
}

if($display_status) {

$total_product = $obj_pricelist->getTotalProducts();
//printr($total_productname);die;
$pagination_data = '';
?>
<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-list"></i> <?php echo $display_name;?></h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>   
        
      <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading bg-white"> 
		  	 <span><?php echo $display_name;?> Listing </span>
           
              <span class="pull-right">
              <a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=qty_page&user_id='.$_GET['user_id'].'&user_type_id='.$_GET['user_type_id'], '',1);?>" > <i class="fa fa-print"></i>Add Qty For Price</a>
              <a class="label bg-inverse" href="<?php echo $obj_general->link($rout, 'mod=import', '',1);?>" > <i class="fa fa-print"></i> CSV Import</a>
             </span>
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
                  if($total_product){
                        if (isset($_GET['page'])) {
                            $page = (int)$_GET['page'];
                        } else {
                            $page = 1;
                        }
                      //oprion use for limit or and sorting function	
                      $option = array(
                            'sort'  => 'p.product_name',
                            'order' => 'ASC',
                            'start' => ($page - 1) * LISTING_LIMIT,
                            'limit' => LISTING_LIMIT
                      );	
                      $products = $obj_pricelist->getProducts($option);
                      foreach($products as $product){ 
					  //printr($products);die;
                        ?>
                        <tr>
                        	<td><?php echo $product['product_name'];
							//printr($size);die;?></td>
                          	<td>
                                <a href="<?php echo $obj_general->link($rout, 'mod=add&product_id='.encode($product['product_id']).'&user_id='.encode($user_id).'&user_type_id='.encode($user_type_id), '',1); ;?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                           </td>
                        </tr>
                        <?php
                      }
                        
                        //pagination
                        $pagination = new Pagination();
                        $pagination->total = $total_product;
                        $pagination->page = $page;
                        $pagination->limit = LISTING_LIMIT;
                        $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                        $pagination->url = $obj_general->link($rout, '&page={page}', '',1);//HTTP_ADMIN.'index.php?rout='.$rout.'&page={page}';
                        $pagination_data = $pagination->render();
                        //echo $pagination_data;die;
                  } else{ 
                      echo "<tr><td colspan='5'>No record found !</td></tr>";
                  } ?>
                  </tbody>
                </table>
              </div>
          </form>
          <footer class="panel-footer">
            <div class="row">
              <div class="col-sm-4 hidden-xs"> </div>
              <?php echo $pagination_data;?>
             
            </div>
          </footer>
        </section>
      </div>
    </div>
  </section>
</section>
<script type="application/javascript">
/*$(".th-sortable").click(function(){
	alert("asdasd");
});*/
</script>           
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>