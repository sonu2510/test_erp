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
	'text' 	=> 'Product Type List',
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
	$filter_desc = $obj_session->data['filter_data']['filter_desc'];
	$filter_name = $obj_session->data['filter_data']['filter_name'];
	$class = '';
	
	$filter_data=array(
		'Product Description' => $filter_desc,		
		'Product Name' => $filter_name
	);	
}

if(isset($_POST['btn_filter'])){
	
	$filter_edit = 1;
	$class ='';	
	if(isset($_POST['filter_desc'])){
		$filter_desc=$_POST['filter_desc'];		
	}else{
		$filter_desc='';
	}	
	
	if(isset($_POST['filter_name'])){
		$filter_name=$_POST['filter_name'];
	}else{
		$filter_name='';
	}
		
	$filter_data=array(
		'filter_desc' => $filter_desc,
		'filter_name' => $filter_name
	);
	
	$obj_session->data['filter_data'] = $filter_data;
	
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

if($display_status) {

if(isset($_POST['action']) && ($_POST['action'] == "active" || $_POST['action'] == "inactive") && isset($_POST['post']) && !empty($_POST['post']))
{	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	} else {
		$status = 0;
		if($_POST['action'] == "active"){
			$status = 1;
		}
		$obj_product->UpdateProductStatus($status,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
}
else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post']))
{
	//printr($_POST['post']);
	if(!$obj_general->hasPermission('delete',$menuId)){
		$display_status = false;
	} else {
		$obj_product->UpdateProductStatus(2,$_POST['post']);
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
          <header class="panel-heading bg-white"> <span>Product Type Listing</span>
          	<span class="text-muted m-l-small pull-right">         			
               <?php /*?><a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>" ><i class="fa fa-plus"></i> New Product </a>
               
                <?php if($obj_general->hasPermission('edit',$menuId)){ ?>
                        <a class="label bg-success" style="margin-left:4px;" onclick="formsubmitsetaction('form_list','active','post[]','<?php echo ACTIVE_WARNING;?>')"><i class="fa fa-check"></i> Active</a>
                        <a class="label bg-warning" onclick="formsubmitsetaction('form_list','inactive','post[]','<?php echo INACTIVE_WARNING;?>')"><i class="fa fa-times"></i> Inactive</a>
                        <a class="label bg-danger" onclick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>
                    <?php } ?> <?php */?>   
               
            </span>	
          </header>    
          
           <form name="form_list" id="form_list" method="post">
           <input type="hidden" id="action" name="action" value="" />
          	<div class="table-responsive">
                <table class="table b-t text-small table-hover">
                  <thead>
                    <tr>
                      <th>Product Type</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
          			<?php /*?><?php
					
					//$count = $obj_product->getcount($filter_data);
					//$pagination_data = '';
					if($count){
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
							//printr($option);
							
						$results = $obj_product->getvalue($option,$filter_data);	
						foreach($results as $result){
						//printr($results);
						?><?php */?>
						<tr>
                              <td>Plastic</td>
                              <td><a href="<?php echo $obj_general->link($rout,'mod=combination&product_type_id='.encode(1),'',1)?>"  name="btn_edit" class="btn btn-info btn-xs">Combination</a></td>
                        </tr> 
                        <tr>
                              <td>Paper</td>
                              <td><a href="<?php echo $obj_general->link($rout,'mod=combination&product_type_id='.encode(2),'',1)?>"  name="btn_edit" class="btn btn-info btn-xs">Combination</a></td>
                        </tr> 
                        <tr>
                              <td>Paper With Oval window</td>
                              <td><a href="<?php echo $obj_general->link($rout,'mod=combination&product_type_id='.encode(3),'',1)?>"  name="btn_edit" class="btn btn-info btn-xs">Combination</a></td>
                        </tr>  
						 
						
						<?php /*?><?php
						}
						//pagination
                        $pagination = new Pagination();
                        $pagination->total = $count;
                        $pagination->page = $page;
                        $pagination->limit = LISTING_LIMIT;
                        $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                        $pagination->url = $obj_general->link($rout, '&page={page}', '',1);//HTTP_ADMIN.'index.php?rout='.$rout.'&page={page}';
                        $pagination_data = $pagination->render();
                        //echo $pagination_data;die;
					}else{
						echo "<tr><td colspan='5'>No record found !</td></tr>";
					} ?><?php */?>
                  </tbody>
                </table>
              </div>
          </form>
          <footer class="panel-footer">
            <div class="row">
              <div class="col-sm-4 hidden-xs"> </div>
              <?php //echo $pagination_data;?>
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
          
          
          
          
          
          
          
          
          
          