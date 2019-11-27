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
	$sort_order = 'DESC';	
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
             	<a class="label bg-info" href="<?php echo $obj_general->link($rout, 'mod=view_color', '',1);?>"><i class="fa fa-plus"></i> View Color </a>
                    <?php if($obj_general->hasPermission('edit',$menuId)){ ?> 
                        <a class="label bg-success" style="margin-left:4px;" onclick="formsubmitsetaction('form_list','active','post[]','<?php echo ACTIVE_WARNING;?>')"><i class="fa fa-check"></i> Active</a>
                        <a class="label bg-warning" onclick="formsubmitsetaction('form_list','inactive','post[]','<?php echo INACTIVE_WARNING;?>')"><i class="fa fa-times"></i> Inactive</a>
                        <a class="label bg-danger" onclick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>
                    <?php } ?>   
             </span> 
             
              </header>
          <form name="form_list" id="form_list" method="post">
           <input type="hidden" id="action" name="action" value="" />
          	<div class="table-responsive">
                <table class="table table-striped b-t text-small table-hover">
                  <thead>
                    <tr>
                  	  <th width="20"><input type="checkbox"></th>
                  	  <th>No</th>
                  	  <th>Product Name</th>
                    </tr>
                  </thead>
                  <tbody> 
                  <?php
                  $total_product = $obj_catalogue_category->getActiveProduct();
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
                          $catalogue_category_details = $obj_catalogue_category->getActiveProduct();
                         $i=1; 
                      foreach($catalogue_category_details as $product){ 
				//	printr($product['c_status']);//die; 
                        ?>
                        <tr>
                         
                        	<td><input type="checkbox"></td>
                        	<td><?php echo $i;?></td>
                        	<td><a href="<?php echo $obj_general->link($rout, 'mod=view_color&product_id='.encode($product['product_id']),'',1); ?>" ><?php echo $product['product_name'];?></a></td>
                        
                        
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