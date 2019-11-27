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

//active inactive delete
/*if(isset($_POST['action']) && ($_POST['action'] == "active" || $_POST['action'] == "inactive") && isset($_POST['post']) && !empty($_POST['post']))
{
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	} else {
		//printr($_POST['post']);die;
		$status = 0;
		if($_POST['action'] == "active"){
			$status = 1;
		}
		$obj_product->updateStatus($status,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
}else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
	if(!$obj_general->hasPermission('delete',$menuId)){
		$display_status = false;
	} else {
		//printr($_POST['post']);die;
		$obj_product->updateStatus(2,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
}*/
$total_cyliners = $obj_cylinder->getTotalCylinder();
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
          	<span class="text-muted m-l-small pull-right">
            	<?php if(!$total_cyliners){ ?>
   				 	<a class="btn btn-success btn-sm btn-circle" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>">
                       	<i class="fa fa-plus"></i> Add
                    </a>
				<?php } ?>
            </span>
          
          </header>
         
                    <?php /*if($obj_general->hasPermission('edit',$menuId)){ ?>
                        <a class="btn btn-success btn-sm btn-circle" onclick="formsubmitsetaction('form_list','active','post[]','<?php echo ACTIVE_WARNING;?>')"><i class="fa fa-check"></i>Active</a>
                        <a class="btn btn-warning btn-sm btn-circle" onclick="formsubmitsetaction('form_list','inactive','post[]','<?php echo INACTIVE_WARNING;?>')"><i class="fa fa-times"></i>Inactive</a>
                        <a class="btn btn-danger btn-sm btn-circle" onclick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i>Delete</a>
                    <?php } */ ?>
    
          <form name="form_list" id="form_list" method="post">
           <input type="hidden" id="action" name="action" value="" />
          	<div class="table-responsive">
                <table class="table table-striped b-t text-small">
                  <thead>
                    <tr>
                      <th>Price</th>
                      <th>Type</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                  
                  $pagination_data = '';
                  if($total_cyliners){
                        if (isset($_GET['page'])) {
                            $page = (int)$_GET['page'];
                        } else {
                            $page = 1;
                        }
                      //oprion use for limit or and sorting function	
                      $option = array(
                            'sort'  => 'product_cylinder_vendor_id',
                            'order' => 'DESC',
                            'start' => 0,
                            'limit' => 2
                      );	
                      $productCylinders = $obj_cylinder->getProductCylindersNew($option);
                      foreach($productCylinders as $cylinder){ 
                        ?>
                        <tr>
                          <td><?php echo $cylinder['price'];?></td>
                          <td><?php
						  if($cylinder['type'] == 1){ echo "India";}
						  else{ echo "International"; }
						  ?></td>
                          <td>
                                <a href="<?php echo $obj_general->link($rout, 'mod=add&cylinder_id='.encode($cylinder['product_cylinder_vendor_id']), '',1); ;?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                           </td>
                        </tr>
                        <?php
                      }
                        
                        //pagination
                        $pagination = new Pagination();
                        $pagination->total = $total_cyliners;
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