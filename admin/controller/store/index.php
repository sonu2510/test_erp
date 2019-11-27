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


$limit = LISTING_LIMIT;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}


if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'ASC';	
}


if(isset($_GET['sort'])){
	$sort_name = $_GET['sort'];	
}else{
	$sort_name = 'store_setting_id';
}

if($display_status) {
	
//active inactive delete
if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
	if(!$obj_general->hasPermission('delete',$menuId)){
		$display_status = false;
	} else {
		//printr($_POST['post']);die;
		
		$obj_store->deleteStore($_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
}	
?>
<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-users"></i> <?php echo $display_name;?></h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>   
        
      <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading"> 
		  	
			<span><?php echo $display_name;?> Listing</span>          		
      		<span class="text-muted m-l-small pull-right">
             		
                    <?php if($obj_general->hasPermission('add',$menuId)){ ?>
                    		<a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>"><i class="fa fa-plus"></i> Add </a>     
                     <?php } if($obj_general->hasPermission('delete',$menuId)){ ?>       
                        <a class="label bg-danger" style="margin-left:4px;" onclick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>
                    <?php } ?>                   
             </span>   
          </header>
          <div class="panel-body">
          
          </div>
          
          
          <form name="form_list" id="form_list" method="post">
           <input type="hidden" id="action" name="action" value="" /> 
          	<div class="table-responsive">
            <table class="table table-striped b-t text-small">
              <thead>
                <tr>
                  <th width="20"><input type="checkbox"></th>

                  <th>Store Name</th>
                  <th>Store Url</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              <?php
              $total_store = $obj_store->getTotalStore();
			  $pagination_data = '';
			  if($total_store){
				   	if (isset($_GET['page'])) {
						$page = $_GET['page'];
					} else {
						$page = 1;
					}
				  //oprion use for limit or and sorting function	
				  $option = array(
				  	'sort'  => $sort_name,
					'order' => $sort_order,
				  	'start' => ($page - 1) * $limit,
					'limit' => $limit
				  );	
				  $stores = $obj_store->getStores($option);
				  //printr($stores);die;
				  if($stores) {
					  foreach($stores as $store_data){ 
						?>
						<tr>
						  <td><input type="checkbox" name="post[]" value="<?php echo $store_data['store_id'];?>"></td>
						  <td><?php echo $store_data['name'];?></td>
						  <td><?php echo $store_data['url'];?></td>
						  <td><label class="label   
							<?php echo ($store_data['status']==1)?'label-success':'label-warning';?>">
							<?php echo ($store_data['status']==1)?'Active':'Inactive';?>
							</label>
						  </td>
						  <td>
							<a href="<?php echo $obj_general->link($rout, 'mod=add&store_id='.encode($store_data['store_id']), '',1); ;?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
						  </td>
						</tr>
						<?php
					  }
				  }else{
					  echo "<tr><td colspan='5'>No record found !</td></tr>";	
				  }
					//pagination
				  	$pagination = new Pagination();
					$pagination->total = $total_store;
					$pagination->page = $page;
					$pagination->limit = $limit;
					$pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
					$pagination->url = $obj_general->link($rout, '&page={page}', '',1);
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
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>