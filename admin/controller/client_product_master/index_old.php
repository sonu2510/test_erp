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
          <header class="panel-heading bg-white"> <span><?php echo $display_name;?> Listing</span>
          	<span class="text-muted m-l-small pull-right">         			
               <a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>" ><i class="fa fa-plus"></i> New Product </a>
               
                <?php if($obj_general->hasPermission('edit',$menuId)){ ?>
                        <a class="label bg-success" style="margin-left:4px;" onclick="formsubmitsetaction('form_list','active','post[]','<?php echo ACTIVE_WARNING;?>')"><i class="fa fa-check"></i> Active</a>
                        <a class="label bg-warning" onclick="formsubmitsetaction('form_list','inactive','post[]','<?php echo INACTIVE_WARNING;?>')"><i class="fa fa-times"></i> Inactive</a>
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
                    <i class="fa fa-search"></i> Search
                  </header>
              
              
              
                 <div class="panel-body clearfix <?php echo $class; ?>">    
                    
                      <div class="row">
                      	 <div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-2 control-label">Product Description</label>
                                <div class="col-lg-10">
                                  <input type="text" name="filter_desc" value="<?php echo isset($filter_desc) ? $filter_desc : '' ; ?>" placeholder="Product Description" id="input-name" class="form-control" />
                                </div>
                              </div>                             
                          </div>
                          
                          <div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-5 control-label"><span class="required">*</span> Select Product</label>
                     		   <div class="col-lg-7">
									<?php $products = $obj_product->getActiveProductSearch(); ?>
                                    <select name="filter_name" class="form-control">
                                    <option value="">Select Product</option>
                                        <?php  foreach($products as $product){
											if(isset($filter_name) && !empty($filter_name) && $filter_name == $product['product_id']) {
                                   ?>
                                                <option value="<?php echo $product['product_id']; ?>" selected="selected" ><?php echo $product['product_name']; ?></option>
                                            <?php }else{ ?>
                                                <option value="<?php echo $product['product_id']; ?>"> <?php echo $product['product_name']; ?></option>
                                            <?php }
                                        } ?>
                                         <?php if(isset($filter_name))
                                            $id=$filter_name;
                                        elseif(isset($product['product_id']))
                                            $id=$product['product_id'];
                                        ?>
                                    <option value="11" <?php if(isset($id) && ($id  == '11')) echo  'selected="selected"';?>>Plastic Scoop</option>
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
                <table class="table b-t text-small table-hover">
                  <thead>
                    <tr>
                      <th width="20"><input type="checkbox"></th>
                      <th>Product Description </th>
                      <th>Product Name </th>
                      <th>Status </th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
          			<?php
					
					$count = $obj_product->getcount($filter_data);
					$pagination_data = '';
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
						?>
						<tr <?php echo ($result['status']==0) ? 'style=background-color:#FADADF' : '' ; ?>>
                          <td><input type="checkbox" name="post[]" value="<?php echo $result['client_product_id'];?>"></td> 
						  <td><?php echo $result['product_desc'];?></td>
						  <td><?php echo $result['product_name'];?></td>
                         
						  <td>
                          	<div data-toggle="buttons" class="btn-group">
                                <label class="btn btn-xs btn-success <?php echo ($result['status']==1) ? 'active' : '';?> "> <input type="radio" 
                                 name="status" value="1" id="<?php echo $result['client_product_id']; ?>"> <i class="fa fa-check text-active"></i>Active</label>                                   
                                <label class="btn btn-xs btn-danger <?php echo ($result['status']==0) ? 'active' : '';?> "> <input type="radio" 
                                    name="status" value="0" id="<?php echo $result['client_product_id']; ?>"> <i class="fa fa-check text-active"></i>Inactive</label> 
                           </div>
                          </td>
						  <td>
                          	<a href="<?php echo $obj_general->link($rout,'mod=add&client_product_id='.encode($result['client_product_id']),'',1)?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
						   </td>
						</tr>
						<?php
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
 $('input[name=status]').change(function(){
	var client_product_id = $(this).attr('id');
	var product_status = this.value;
	var product_url = getUrl("<?php echo $obj_general->ajaxLink($rout,'&mod=ajax&ajaxfun=UpdateStatus','',1);?>");
	 $.ajax({
		 url : product_url,
		 type : 'post',
		 data : {client_product_id:client_product_id,product_status:product_status},
		 success : function(responce){
			 //alert(responce);
			 set_alert_message('Successfully Updated',"alert-success","fa-check");	
		 },
		 error : function()
		 {
			set_alert_message('Error During Updation',"alert-warning","fa-warning");   
		 }
	 });
 });         
          
 </script>    
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?> 