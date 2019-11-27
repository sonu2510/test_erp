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
	'href' 	=> $obj_general->link($rout, '', '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> 'Catalogue Category List',
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

$class = 'collapse';
$filter_data= array();
if(isset($_POST['btn_filter'])){
	//printr($_POST);
	$class = '';
		
	$filter_edit = 1;
	$class ='';	
	if(isset($_POST['filter_catalogue_category_name'])){
             $filter_catalogue_category_name=$_POST['filter_catalogue_category_name'];   
     }else{
        $filter_catalogue_category_name='';
      } 

	if(isset($_POST['filter_product'])){
		$filter_product=$_POST['filter_product'];		
	}else{
		$filter_product='';
	}	
	
	if(isset($_POST['filter_catagory'])){
		$filter_catagory=$_POST['filter_catagory'];
	}else{
		$filter_catagory='';
	}
		
	$filter_data=array(
        'catalogue_category_name' => $filter_catalogue_category_name,
		'product_id' => $filter_product,
		'color_catagory_id' => $filter_catagory
	);
//	printr($filter_data);
}




if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'ASC';	
}


if($display_status) {
	
	if(isset($_POST['action']) && ($_POST['action'] == "active" || $_POST['action'] == "inactive") && isset($_POST['post']) && !empty($_POST['post']))
{
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	} else {
//	printr($_POST['post']);die; 
		$status = 0; 
		if($_POST['action'] == "active"){
			$status = 1;
		} 
		$obj_catalogue_category->updateStatus($status,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
}else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
	if(!$obj_general->hasPermission('delete',$menuId)){
		$display_status = false;
	} else {
		//printr($_POST['post']);die;
		$obj_catalogue_category->updateStatus(2,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
}
	
	

//printr($total_productname);die;
$pagination_data = '';
?>
<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-list"></i>  Add Catalogue Category </h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>   
        
      <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading bg-white"> 
		  	 <span>Catalogue Category List  </span>
		  	 
             <span class="text-muted m-l-small pull-right">
             	<a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>"><i class="fa fa-plus"></i> Add category </a>
             	<a class="label bg-info" href="<?php echo $obj_general->link($rout, 'mod=list_product', '',1);?>"><i class="fa fa-plus"></i> View Color </a>
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
                   <a href="#" class="panel-toggle text-muted active"> <i class="fa fa-search"></i> Search</a>
                  </header>
              
              
              
                 <div class="panel-body clearfix <?php echo $class; ?>">        
                      <div class="row">
                          <div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-2 control-label">Catalogue Category Name</label>
                                <div class="col-lg-8">
                                  <input type="text" name="filter_catalogue_category_name" value="<?php echo isset($filter_catalogue_category_name) ? $filter_catalogue_category_name: '' ; ?>" placeholder="Catalogue Category Name" id="input-name" class="form-control" />
                                </div>
                              
                              </div>                             
                          </div>
                      
                    <div class="col-lg-4">
                                  <div class="form-group">
                                    <label class="col-lg-3 control-label"><span class="required">*</span> Select Product</label> 
                                    <div class="col-lg-6">
                                        <?php
                                        $products = $obj_catalogue_category->getActiveProduct();
                                        ?>
                                        <select name="filter_product" id="filter_product"  class="form-control ">
                                        <option value="">Select Product</option>
                                            <?php
            								
                                            foreach($products as $product){
                                             
                                                    echo '<option value="'.$product['product_id'].'">'.$product['product_name'].'</option>';
                                                
                                            } ?>
                                        </select>
                                    </div>
                              </div>
                      </div>
                      <div class="col-lg-4">
                                  <div class="form-group">
                                    <label class="col-lg-3 control-label"><span class="required">*</span> Category</label> 
                                    <div class="col-lg-6">
                                        <?php
                        								
                                           $color_category = $obj_catalogue_category->color_category();   
                        				  ?>     
                                          <select name="filter_catagory" id="filter_catagory" class="form-control validate">    
                                           
                                           <option value="">Select Color Category</option>
                                                       <?php  
                        								 foreach($color_category as $c){                                  		
                                                                if(isset($color['color_catagory_id']) && $color['color_catagory_id'] == $c['color_catagory_id']){
                                                                    echo '<option value="'.$c['color_catagory_id'].'" selected="selected" >'.$c['color_name'].'</option>';
                                                                }else{
                                                                    echo '<option value="'.$c['color_catagory_id'].'">'.$c['color_name'].'</option>';
                                                                }
                                                            } ?>
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
                  	  <th>No</th>
                  	  <th>Catalogue Category Name</th>
                  	  <th>Product Name</th>
                  	  <th>Category Name</th>
                  	
                  	  <th>Status</th>
                      <th colspan="2"><center>Action</center></th>
                    </tr>
                  </thead>
                  <tbody> 
                  <?php
                  $total_product = $obj_catalogue_category->getTotalCatalogue_category($filter_data);
                    $pagination_data = '';
                      if($total_product){
                            if (isset($_GET['page'])) {
                                $page = (int)$_GET['page'];
                            } else {
                                $page = 1;
                            }
                          //oprion use for limit or and sorting function	
                          $option = array(
                              'sort'  => 'c.catalogue_category_id',
                              'order' => $sort_order,
                              'start' => ($page - 1) * $limit,
                              'limit' => $limit
                          );
                      
    				//	  printr($filter_data);
                          $catalogue_category_details = $obj_catalogue_category->getCatalogue_category($option,$filter_data);
                         $i=1; 
                      foreach($catalogue_category_details as $product){ 
				//	printr($product['c_status']);//die; 
                        ?>
                        <tr <?php echo ($product['c_status']==0) ? 'style="background-color:#FADADF" ' : '' ; ?>>
                            <td><input type="checkbox" name="post[]" value="<?php echo $product['catalogue_category_id'];?>"></td>
                        	<td><?php echo $i;?></td>
                        	<td><?php echo $product['catalogue_category_name'];?></td>
                        	<td><?php echo $product['product_name'];?></td>
                        	<td><?php echo $product['color_name'];?></td>
                        
                          	 <td>
                          
                          	<div data-toggle="buttons" class="btn-group">
                                <label class="btn btn-xs btn-success <?php echo ($product['c_status']==1) ? 'active' : '';?> "> <input type="radio" 
                                 name="status" value="1" id="<?php echo $product['catalogue_category_id']; ?>"> <i class="fa fa-check text-active"></i>Active</label>                                   
                                <label class="btn btn-xs btn-danger <?php echo ($product['c_status']==0) ? 'active' : '';?> "> <input type="radio" 
                                    name="status" value="0" id="<?php echo $product['catalogue_category_id']; ?>"> <i class="fa fa-check text-active"></i>Inactive</label> 
                            </div>
                          
                          </td>
                          	<td> 
                                <a href="<?php echo $obj_general->link($rout, 'mod=add&catalogue_category_id='.encode($product['catalogue_category_id']), '',1); ;?>"  name="btn_edit" class="btn btn-info btn-xs">Edit </a>
                           </td> 
                           <td>
                                <a href="<?php echo $obj_general->link($rout, 'mod=add_color&catalogue_category_id='.encode($product['catalogue_category_id']), '',1); ;?>"  name="btn_color" class="btn btn-info btn-xs">Add Color </a>
                           </td>	
                        
                        </tr>
                        <?php
                        $i++;
                      }
                        
                        //pagination
                         $pagination = new Pagination();
                        $pagination->total = $total_product;
                        $pagination->page = $page;
                        $pagination->limit = LISTING_LIMIT;
                        $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                        $pagination->url = $obj_general->link($rout, '&page={page}&limit='.$limit.'', '',1);//HTTP_ADMIN.'index.php?rout='.$rout.'&page={page}';
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


<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
	<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script >   


$('input[type=radio][name=status]').change(function() {
	 
		alert($(this).attr('id'));
		var color_id=$(this).attr('id');
		var status_value = this.value;
		
		var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=updateStatus', '',1);?>");
        $.ajax({			
			url : status_url,
			type :'post',
			data :{color_id:color_id,status_value:status_value},
			success: function(){
				set_alert_message('Successfully Updated',"alert-success","fa-check");					
			},
			error:function(){
				set_alert_message('Error During Updation',"alert-warning","fa-warning");          
			}			
		});
    });

</script>           
<?php
} else {
	include(DIR_ADMIN.'access_denied.php');
}
?>