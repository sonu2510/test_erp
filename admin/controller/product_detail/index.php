<?php
include("mode_setting.php");
$obj_product = new productDetail();

$bradcums = array();
$bradcums[] = array(
	'text' 	=> 'Dashboard',
	'href' 	=> $obj_general->link('dashboard', '', '',1),
	'icon' 	=> 'fa-home',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> 'Product List',
	'href' 	=> '',
	'icon' 	=> 'fa-list',
	'class'	=> 'active',
);

if(!$obj_general->hasPermission('view',$menuId)){
	$display_status = false;
}
/*if(isset($_GET['del']) && $_GET['del'] != "")
{
	if(!hasPermission('delete',$menuId)){
		$display_status = false;
	} else {
		$where=array('coupon_master_id'=>mysql_real_escape_string(trim($_GET['del'])));
		//$delete = $obj_conn->Delete($table, $where, $limit='', $like=false);
		$set=array("is_delete"=>1);
		$update = $obj_conn->Updatein($table, $set, $where, $like_or_in='in', $oparand='AND');
	}
}
if(isset($_POST['action']) && ($_POST['action'] == "active" || $_POST['action'] == "inactive") && isset($_POST['chk']) && !empty($_POST['chk']))
{
	if(!hasPermission('edit',$menuId)){
		$display_status = false;
	} else {
		//printr($_POST['chk']);die;
		$where=array('coupon_master_id'=>mysql_real_escape_string(trim(implode(",",$_POST['chk']))));
		$status = 0;
		if($_POST['action'] == 'active'){
			$status = 1;
		}
		$set=array('status'=>$status);
		$update = $obj_conn->Updatein($table, $set, $where, $like_or_in='in', $oparand='AND');
	}
}

if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['chk']) && !empty($_POST['chk']))
{
	if(!hasPermission('delete',$menuId)){
		$display_status = false;
	} else {
		$where=array('coupon_master_id'=>mysql_real_escape_string(trim(implode(",",$_POST['chk']))));
		//$obj_conn->Deletein($table, $where, $like_or_in='in', $oparand='AND');
		$set=array("is_delete"=>1);
		$update = $obj_conn->Updatein($table, $set, $where, $like_or_in='in', $oparand='AND');
	}
}*/

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
        
      <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading bg-white"> <?php echo $display_name;?> Listing</header>
          <div class="panel-body">
            <div class="row text-small">
              <div class="col-sm-6 m-b-mini">
   					<a class="btn btn-primary btn-sm btn-circle active" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>"><i class="fa fa-plus"></i>Add</a>
                    <?php if($obj_general->hasPermission('edit',$menuId)){ ?>
                        <a class="btn btn-success btn-sm btn-circle" href="javascript:void(0);"><i class="fa fa-check"></i>Active</a>
                        <a class="btn btn-warning btn-sm btn-circle" href="javascript:void(0);"><i class="fa fa-times"></i>Inactive</a>
                        <a class="btn btn-danger btn-sm btn-circle" href="javascript:void(0);"><i class="fa fa-trash-o"></i>Delete</a>
                    <?php } ?>
              </div>      
              <div class="col-sm-4">
                <div class="input-group">
                  <input type="text" class="input-sm form-control" placeholder="Search">
                  <span class="input-group-btn">
                  <button class="btn btn-sm btn-white" type="button">Go!</button>
                  </span> </div>
              </div>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-striped b-t text-small">
              <thead>
                <tr>
                  <th width="20"><input type="checkbox"></th>
                  <th>Name</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              <?php
              $total_product = $obj_product->getTotalProduct();
			  $pagination_data = '';
			  if($total_product){
				   	if (isset($_GET['page'])) {
						$page = (int)$_GET['page'];
					} else {
						$page = 1;
					}
				  //oprion use for limit or and sorting function	
				  $option = array(
				  		'sort'  => 'product_name',
						'order' => 'ASC',
				  		'start' => ($page - 1) * LISTING_LIMIT,
						'limit' => LISTING_LIMIT
				  );	
				  $products = $obj_product->getProducts($option);
				  foreach($products as $product){ 
					?>
                    <tr>
                      <td><input type="checkbox" name="post[]" value="<?php echo $product['product_id'];?>"></td>
                      <td><?php echo $product['product_name'];?></td>
                      <td>
                      	<label class="label <?php echo ($product['status']==1)?'label-success':'label-warning';?>">
	                        <?php echo ($product['status']==1)?'Active':'Inactive';?>
                        </label>
                      </td>
                      <td>
                      		<a href="<?php echo $obj_general->link($rout, 'mod=add&product_id='.encode($product['product_id']), '',1); ;?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
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
					$pagination->url = HTTP_ADMIN.'index.php?rout='.$rout.'&page={page}';
					$pagination_data = $pagination->render();
				    //echo $pagination_data;die;
              } else{ 
				  echo "<tr><td colspan='5'>No record found !</td></tr>";
			  } ?>
              </tbody>
            </table>
          </div>
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
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>