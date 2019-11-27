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
	'text' 	=> 'Product List',
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


if(isset($_GET['sort'])){
	$sort_name = $_GET['sort'];	
}else{
	$sort_name = 'product_name';
}

if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'ASC';	
}

$filter_data=array();
$filter_value='';

$class = 'collapse';

if(!isset($_GET['filter_edit'])){
	$filter_edit = 0;
}else{
	$filter_edit = $_GET['filter_edit'];
}

if(!isset($_GET['filter_edit']) || $_GET['filter_edit']==0){
	if(isset($obj_session->data['filter_data'])){
		unset($obj_session->data['filter_data']);	
	}
}

if(isset($obj_session->data['filter_data'])){
	$filter_name = $obj_session->data['filter_data']['name'];
	$filter_status = $obj_session->data['filter_data']['status'];
	$class = '';
	
	$filter_data=array(
		'name' => $filter_name,		
		'status' => $filter_status
	);	
}

if(isset($_POST['btn_filter'])){
	
	$filter_edit = 1;
	$class ='';	
	if(isset($_POST['filter_name'])){
		$filter_name=$_POST['filter_name'];		
	}else{
		$filter_name='';
	}	
	
	if(isset($_POST['filter_status'])){
		$filter_status=$_POST['filter_status'];
	}else{
		$filter_status='';
	}
		
	$filter_data=array(
		'name' => $filter_name,
		'status' => $filter_status
	);
	
	$obj_session->data['filter_data'] = $filter_data;
	
}

//echo $obj_general->link($rout);
if($display_status) {
	
	//active inactive delete
	if(isset($_POST['action']) && ($_POST['action'] == "active" || $_POST['action'] == "inactive") && isset($_POST['post']) && !empty($_POST['post']))
	{
		if(!$obj_general->hasPermission('edit',$menuId)){
			$display_status = false;
		} else {
			$status = 0;
			if($_POST['action'] == "active"){
				$status = 1;
			}
			$obj_product_img->updateStatus($status,$_POST['post']);
			$obj_session->data['success'] = UPDATE;
			page_redirect($obj_general->link($rout, '', '',1));
		}
	}else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
		if(!$obj_general->hasPermission('delete',$menuId)){
			$display_status = false;
		} else {
			//printr($_POST['post']);die;
			$obj_product_img->updateStatus(2,$_POST['post']);
			$obj_session->data['success'] = UPDATE;
			page_redirect($obj_general->link($rout, '', '',1));
		}
	}

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
           <header class="panel-heading">
          	 <span>Product Image Listing</span>
             <span class="text-muted m-l-small pull-right">
             		<?php if($obj_general->hasPermission('edit',$menuId)){ ?>
   								<a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>"><i class="fa fa-plus"></i> New Product Image</a>
                      <?php }
							if($obj_general->hasPermission('edit',$menuId)){ ?>
                        		<a class="label bg-success" onclick="formsubmitsetaction('form_list','active','post[]','<?php echo ACTIVE_WARNING;?>')"><i class="fa fa-check"></i> Active</a>
                        		<a class="label bg-warning" onclick="formsubmitsetaction('form_list','inactive','post[]','<?php echo INACTIVE_WARNING;?>')"><i class="fa fa-times"></i> Inactive</a>
                     <?php }
					 		if($obj_general->hasPermission('delete',$menuId)){ ?>   
                        		<a class="label bg-danger" onclick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>
                    <?php } ?>
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
                  
                  <th width="20"><input type="checkbox"></th> 
                  <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">
                      Name
                      <span class="th-sort">
                         	<a href="<?php echo $obj_general->link($rout, 'sort=product_name'.'&order=ASC', '',1);?>">
                            <i class="fa fa-sort-down text"></i>
                            <a href="<?php echo $obj_general->link($rout, 'sort=product_name'.'&order=DESC', '',1);?>">
                            <i class="fa fa-sort-up text-active"></i>
                      <i class="fa fa-sort"></i></span>
                  </th>
                  
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              <?php
              $total_product = $obj_product_img->getTotalProductImage($obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);
			  $pagination_data = '';
			  if($total_product){
				   	if (isset($_GET['page'])) {
						$page = (int)$_GET['page'];
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
				  $products = $obj_product_img->getProductsImage($option,$obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);
				  //($products);
				  
				  foreach($products as $product){
				  ?>
                    <tr <?php echo ($product['status']==0) ? 'style="background-color:#FADADF" ' : '' ; ?>>
                     <td><input type="checkbox" name="post[]" value="<?php echo $product['product_image_id'];?>"></td>
                      <td><a href="<?php echo $obj_general->link($rout, 'mod=view&product_image_id='.encode($product['product_image_id']),'',1); ;?>"><?php echo $product['product_name'].'<br>';?></a></td>
                      <td>
                      <div data-toggle="buttons" class="btn-group">
                                            <label class="btn btn-xs btn-success <?php echo ($product['status']==1) ? 'active' : '';?> "> <input type="radio" 
                                             name="status" value="1" id="<?php echo $product['product_image_id']; ?>"> <i class="fa fa-check text-active"></i>Active</label>                                   
                                            <label class="btn btn-xs btn-danger <?php echo ($product['status']==0) ? 'active' : '';?> "> <input type="radio" 
                                                name="status" value="0" id="<?php echo $product['product_image_id']; ?>"> <i class="fa fa-check text-active"></i>Inactive</label> 
                                        </div>            
                      </td>
                      <td>
                      		<a href="<?php echo $obj_general->link($rout, 'mod=add&product_image_id='.encode($product['product_image_id']).'&filter_edit='.$filter_edit, '',1); ;?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                       </td>
                    </tr>
                    <?php
				  }
				    
					//pagination
				  	$pagination = new Pagination();
					$pagination->total = $total_product;
					$pagination->page = $page;
					$pagination->limit = $limit;
					$pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
					$pagination->url =$obj_general->link($rout, '&page={page}&limit='.$limit.'&filter_edit=1', '',1);
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
	$('input[name=status]').change(function() {
	//alert("change");
		var product_image_id=$(this).attr('id');
		
		var status_value = this.value;
		var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajaximage&fun=updateImagestatus', '',1);?>");
       //alert(status_url);
		$.ajax({
			url : status_url,
			type :'post',
			data :{product_image_id:product_image_id,status_value:status_value},
			success: function(response){
			//alert(response);
				set_alert_message('Successfully Updated',"alert-success","fa-check");
				 window.setTimeout(function(){location.reload()},1000)					
			},
			error:function(){
				set_alert_message('Error During Updation',"alert-warning","fa-warning");          
			}						
		});
    });
</script>       
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>