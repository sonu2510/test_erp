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
			$obj_product->updateStatus($status,$_POST['post']);
			$obj_session->data['success'] = UPDATE;
			page_redirect($obj_general->link($rout, '', '',1));
		}
	}else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
		//printr($_POST['post']);die;
		if(!$obj_general->hasPermission('delete',$menuId)){
			$display_status = false;
		} else {
			//printr($_POST['post']);die;
			$obj_product->updateStatus(2,$_POST['post']);
			$obj_session->data['success'] = UPDATE;
			page_redirect($obj_general->link($rout, '', '',1));
		}
	}

$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];

?>

<section id="content" class="localizationTool">
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
          			
                    
                    <?php if($user_type_id==1 && $user_id==1)
					 {?>
					 		<a class="label bg-info" onclick="CloneData()" ><i class="fa fa-copy"></i> Clone Model</a>
                    <?php  }
					
					if($obj_general->hasPermission('add',$menuId)){ ?>
   							<a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>"><i class="fa fa-plus"></i> New Product </a>
                    <?php }
					if($obj_general->hasPermission('edit',$menuId)){ ?>
                        <a class="label bg-success" style="margin-left:4px;" onclick="formsubmitsetaction('form_list','active','post[]','<?php echo ACTIVE_WARNING;?>')"><i class="fa fa-check"></i> Active</a>
                        <a class="label bg-warning" onclick="formsubmitsetaction('form_list','inactive','post[]','<?php echo INACTIVE_WARNING;?>')"><i class="fa fa-times"></i> Inactive</a>
                     <?php }
					if($obj_general->hasPermission('delete',$menuId)){ ?>       
                        <a class="label bg-danger" onclick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>
                    <?php } ?>
                    
            </span>
          </header>
          <div class="panel-body">
            	
                <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link($rout, '', '',1); ?>">
                
                <section class="panel pos-rlt clearfix">
                  <header class="panel-heading">
                    <ul class="nav nav-pills pull-right">
                      <li> <a href="#" class="panel-toggle text-muted active"><i class="fa fa-caret-down fa-lg text-active"></i><i class="fa fa-caret-up fa-lg text"></i></a> </li>
                    </ul>
                   <a href="#" class="panel-toggle text-muted active"> <i class="fa fa-search"></i> Search</a>
                  </header>
              
              
              
                 <div class="panel-body clearfix <?php echo $class; ?>">        
                      <div class="row">
                          <div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-2 control-label">Name</label>
                                <div class="col-lg-10">
                                  <input type="text" name="filter_name" value="<?php echo isset($filter_name) ? $filter_name : '' ; 
								  ?>" placeholder="Name" id="input-name" class="form-control" />
                                </div>
                              </div>                             
                          </div>
                          <div class="col-lg-4">                              
                               <div class="form-group">
                                <label class="col-lg-4 control-label">Status</label>
                                <div class="col-lg-8">
                                  <select name="filter_status" id="input-status" class="form-control">
                                        <option value=""></option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                   </select>
                                </div>
                              </div>
                          </div>                    
                      </div>                     
                 </div>
            
                  <footer class="panel-footer <?php echo $class; ?>">
                    <div class="row">
                       <div class="col-lg-12">
                        <button type="submit" class="btn btn-primary btn-sm pull-right ml5" name="btn_filter"><i class="fa fa-search"></i> Search</button>
                        <a href="<?php echo $obj_general->link($rout, '', '',1); ?>" class="btn btn-info btn-sm pull-right" ><i class="fa fa-refresh"></i> Refresh</a>
                       </div> 
                    </div>
                  </footer>                                  
              </section>
          </form>
          
          <div class="row">
             
             <div class="col-lg-3 pull-right">	
                 <select class="form-control" id="limit-dropdown" onchange="location=this.value;">
                 <option value="<?php echo $obj_general->link($rout, '', '',1);?>" selected="selected">--Select--</option>
                    	<?php 
							$limit_array = getLimit(); 
							foreach($limit_array as $display_limit) {
								if($limit == $display_limit) {	 
						?>
                        	
                        		<option value="<?php echo $obj_general->link($rout, 'limit='.$display_limit, '',1);?>" selected="selected"><?php echo $display_limit; ?></option>				
						<?php } else { ?>
                            	<option value="<?php echo $obj_general->link($rout, 'limit='.$display_limit, '',1);?>"><?php echo $display_limit; ?></option>
                        <?php } ?>
                        <?php } ?>
                 </select>
             </div>
                <label class="col-lg-1 pull-right" style="margin-top:5px;">Show</label>	
           </div>
                
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
			  
			  //$clone = $obj_product->cloneData();
			  
              $total_product = $obj_product->getTotalProduct($filter_data);
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
				  $products = $obj_product->getProducts($option,$filter_data);
				  //printr($products);
				  //die;
				  foreach($products as $product){ 
				  //printr($product['product_id']);
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
                      		<a href="<?php echo $obj_general->link($rout, 'mod=add&product_id='.encode($product['product_id']).'&filter_edit='.$filter_edit, '',1); ;?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                       </td>
                       <td>
                      		<a href="<?php echo $obj_general->link($rout, 'mod=watch_list&product_id='.encode($product['product_id']).'&filter_edit='.$filter_edit, '',1); ;?>"  name="btn_watch_list" class="btn btn-primary">Watch List</a>
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

<?php // clone model ?>

<div class="modal fade" id="clone_Data" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="clone_form" id="clone_form" style="margin-bottom:0px;">
              <div class="modal-header title">
               <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Clone Model : <span id="pro"></span></h4>
              </div>
           
              <div class="modal-body">
                   <div class="form-group">
                                  <!--<input type="hidden" name="product_code" id="product_code" value="" />
                                  <input type="hidden" name="product_code_id" id="product_code_id" value="" />-->
                		<label class="col-lg-1 control-label" id="copy_from_label" >Copy From:</label><label class="col-lg-3 control-label"  style="width:15%"  >Select Product</label>
                       <div class="col-lg-8">
                            <?php $products = $obj_product->getProductActive(); ?>
                            <select name="product" id="product" class="form-control validate[required]">
                            <option value="">Select Product</option>
                                <?php  foreach($products as $product){ ?>
                                    
                                        <option value="<?php echo $product['product_id']; ?>" <?php if(isset($product_code['product']) && ($product_code['product'] == $product['product_id'])) { echo 'selected="selected"';}?> > <?php echo $product['product_name']; ?></option>
                                    <?php
                                } ?>
                                 <?php if(isset($product_code['product']))
									$id=$product_code['product'];
								elseif(isset($product['product_id']))
									$id=$product['product_id'];
								?>
                            </select>
                        </div><br>
                        
                    </div>
                 </div>       
                        
              <div class="modal-body">
                   <div class="form-group">
                   
                    <label class="col-lg-1 control-label" id="volume_from_label" >Copy To:</label><label class="col-lg-1 control-label" id="volume_from_label" >Product:</label>
                        <div class="col-lg-3">
              		 		<input type="text" name="product_to" style="margin-left: 20px;" id="product_to" class="form-control validate" >
                        </div>
                        
                    <label class="col-lg-3 control-label" >Abbrevation:</label>
                        <div class="col-lg-3">
                        	<input type="text" name="abb_to" style="margin-left: 20px;" id="abb_to" class="form-control validate" >
                    	</div>
             		</div>
              </div>
             
            <!--	<center><div id="capacity"></div></center>-->
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="button" onclick="clone_product()" name="btn_submit1" class="btn btn-warning">Clone</button>
              </div>
   		</form>   
    </div>
  </div>
</div>

<?php // end of clone model ?>

<script>

function CloneData() 
{
	$("#clone_Data").modal('show');
}
	
function clone_product()
{
		
		var formData = $("#clone_form").serialize();
			var product_code_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=clone_Product', '',1);?>");
			$.ajax({
				url : product_code_url,
				method : 'post',
				data : {formData : formData},
				success: function(response){
					set_alert_message('Your Data Is Successfully Cloned',"alert-success","fa-check");
					window.setTimeout(function(){location.reload()},100)
				},
				error: function(){
					return false;	
				}
			});
	
}
	
</script>
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>