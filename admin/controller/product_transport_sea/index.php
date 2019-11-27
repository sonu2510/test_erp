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
	'href' 	=> '',
	'icon' 	=> 'fa-list',
	'class'	=> 'active',
);

if(!$obj_general->hasPermission('view',$menuId)){
	$display_status = false;
}

if($display_status) {
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
        
      <div class="col-lg-6">
        <section class="panel">
          <header class="panel-heading bg-white"> 
		  	 <span><?php echo $display_name;?> Listing - Width </span>
          	 <span class="text-muted m-l-small pull-right">
             	<a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=width', '',1);?>"><i class="fa fa-plus"></i> New Product Tansport Sea </a>
          	 </span>
          </header>
          
          <form name="form_list" id="form_list" method="post">
           <input type="hidden" id="action" name="action" value="" />
          	<div class="table-responsive">
                <table class="table table-striped b-t text-small table-hover">
                  <thead>
                    <tr>
                      <th>From Width</th>
                      <th>To WIdth</th>
                      <th>Price</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
				  $total_width = $obj_sea_transport->getTotalTransportWidth();
				  $pagination_data = '';
                  if($total_width){
                        if (isset($_GET['page'])) {
                            $page = (int)$_GET['page'];
                        } else {
                            $page = 1;
                        }
                      //oprion use for limit or and sorting function	
                      $option = array(
                            'sort'  => 'price',
                            'order' => 'ASC',
                            'start' => ($page - 1) * LISTING_LIMIT,
                            'limit' => LISTING_LIMIT
                      );	
                      $widths = $obj_sea_transport->getTransportWidths($option);
                      foreach($widths as $width){ 
                        ?>
                        <tr>
                          <td><?php echo $width['from_width'];?></td>
                          <td><?php echo $width['to_width'];?></td>
                          <td><?php echo $width['price'];?></td>
                          <td>
                                <a href="<?php echo $obj_general->link($rout, 'mod=width&width_id='.encode($width['product_transport_sea_width_id']), '',1); ;?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                           </td>
                        </tr>
                        <?php
                      }
                        
                        //pagination
                        $pagination = new Pagination();
                        $pagination->total = $total_width;
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
      
      <div class="col-lg-6">
        <section class="panel">
          <header class="panel-heading bg-white"> 
		  	<span><?php echo $display_name;?> Listing - Height </span>
            <span class="text-muted m-l-small pull-right">
            	<a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=height', '',1);?>"><i class="fa fa-plus"></i> New Product Tansport Sea  </a>
            </span>
          </header>
         
          <form name="form_list" id="form_list" method="post">
           <input type="hidden" id="action" name="action" value="" />
          	<div class="table-responsive">
                <table class="table table-striped b-t text-small table-hover">
                  <thead>
                    <tr>
                      <th>From Height</th>
                      <th>To Height</th>
                      <th>Price</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
				  $total_height = $obj_sea_transport->getTotalTransportHeight();
				  $pagination_data = '';
                  if($total_height){
                        if (isset($_GET['page'])) {
                            $page = (int)$_GET['page'];
                        } else {
                            $page = 1;
                        }
                      //oprion use for limit or and sorting function	
                      $option = array(
                            'sort'  => 'price',
                            'order' => 'ASC',
                            'start' => ($page - 1) * LISTING_LIMIT,
                            'limit' => LISTING_LIMIT
                      );	
                      $heights = $obj_sea_transport->getTransportHeights($option);
                      foreach($heights as $height){ 
                        ?>
                        <tr>
                          <td><?php echo $height['from_height'];?></td>
                          <td><?php echo $height['to_height'];?></td>
                          <td><?php echo $height['price'];?></td>
                          <td>
                                <a href="<?php echo $obj_general->link($rout, 'mod=height&height_id='.encode($height['product_transport_sea_height_id']), '',1); ;?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                           </td>
                        </tr>
                        <?php
                      }
                        
                        //pagination
                        $pagination = new Pagination();
                        $pagination->total = $total_width;
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