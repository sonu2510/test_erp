<?php
include("mode_setting.php");
$obj_category = new category();

/*if(!hasPermission('view',$menuId)){
	$display_status = false;
}
if(isset($_GET['del']) && $_GET['del'] != "")
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
      <h4><i class="fa fa-list"></i> Category</h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php //include("common/breadcrumb.php");?>	
        </div>   
        
      <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading bg-white">
          	<span>Category Listing</span>
            <a class="btn btn-xs btn-success pull-right" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>">
            	<i class="fa fa-plus"></i> Add Category
            </a>  
          </header>
          <div class="panel-body">
            <div class="row text-small">
              <div class="col-sm-6 m-b-mini">
   					
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
                  <th>Name</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              <?php
              $total_category = $obj_category->getTotalCategory();
			  $pagination_data = '';
			  if($total_category){
				   	if (isset($_GET['page'])) {
						$page = $_GET['page'];
					} else {
						$page = 1;
					}
				  //oprion use for limit or and sorting function	
				  $option = array(
				  		'start' => ($page - 1) * LISTING_LIMIT,
						'limit' => LISTING_LIMIT
				  );	
				  $categorys = $obj_category->getCategorys($option);
				  foreach($categorys as $category){ 
					?>
                    <tr>
                      <td><?php echo $department['department_name'];?></td>
                      <td><label class="label   
                        <?php echo ($department['status']==1)?'label-success':'label-warning';?>">
                        <?php echo ($department['status']==1)?'Active':'Inactive';?>
                        </label>
                      </td>
                      <td>
                      		<a href="<?php echo HTTP_ADMIN;?>index.php?rout=<?php echo $rout;?>&mod=add&department_id=<?php echo encode($department['department_id']);?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                       </td>
                    </tr>
                    <?php
				  }
				    
					//pagination
				  	$pagination = new Pagination();
					$pagination->total = $total_category;
					$pagination->page = $page;
					$pagination->limit = LISTING_LIMIT;
					$pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
					$pagination->url = HTTP_ADMIN.'index.php?rout='.$rout.'&page={page}';
					$pagination_data = $pagination->render();
				    //echo $pagination_data;die;
              } else{ 
				  echo "<tr><td colspan='5'>".NO_RECORD."</td></tr>";
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
	include(SERVER_ADMIN_PATH.'access_denied.php');
}
?>
