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

$class = 'collapse';

$filter_data= array();
if(isset($_POST['btn_filter'])){
	
	$class = '';
		
	$filter_edit = 1;
	$class ='';	
	if(isset($_POST['filter_volume'])){
		$filter_volume=$_POST['filter_volume'];		
	}else{
		$filter_volume='';
	}	
	
	if(isset($_POST['filter_status'])){
		$filter_status=$_POST['filter_status'];
	}else{
		$filter_status='';
	}
		
	$filter_data=array(
		'volume' => $filter_volume,
		'status' => $filter_status
	);
	
}

if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'ASC';	
}

if($display_status) {

//active inactive delete
if(isset($_POST['action']) && ($_POST['action'] == "active" || $_POST['action'] == "inactive") && isset($_POST['post']) && !empty($_POST['post']))
{	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	} else {
		$status = 0;
		if($_POST['action'] == "active"){
			$status = 1;
		}
		$laser_prices->updateStatus($status,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
}
else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post']))
{
	if(!$obj_general->hasPermission('delete',$menuId)){
		$display_status = false;
	} else {
		$laser_prices->updateStatus(2,$_POST['post']);
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
          <header class="panel-heading bg-white"> 
       
          	<span><h5>Product Listing<h5></span>
          	<span class="text-muted m-l-small pull-right">
          			
                               
                    
            </span>
           
          </header>
          <br><br>
		<form name="form_list" id="form_list" method="post">
           <input type="hidden" id="action" name="action" value="" />
          	<div class="table-responsive">
                <table class="table b-t text-small table-hover">
                  <thead>
                    <tr>
                      
                     
                      <th>
                      		Product Name
                            
                      </th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php	
				  $total_product = $laser_prices->getTotalProduct();
				  $pagination_data = '';
                  if($total_product){
                        if (isset($_GET['page'])) {
                            $page = (int)$_GET['page'];
                        } else {
                            $page = 1;
                        }
                      //option use for limit or and sorting function	
                      $option = array(
                          'sort'  => 'product_id',
                          'order' => $sort_order,
                          'start' => ($page - 1) * $limit,
                          'limit' => $limit
                      );
					  
                      $products = $laser_prices->getProduct($option);
                     //print_r($volumes);die;
					  
					  foreach($products as $product){ 
                        ?>
                        <tr>
                          <td><?php echo $product['product_name'];?></td>
                          <td>	
                                <a href="<?php echo $obj_general->link($rout, 'mod=laserscoringtypes&product_id='.encode($product['product_id']),'',1); ?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                                
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
                        $pagination->url = $obj_general->link($rout, '&page={page}&limit='.$limit.'', '',1);
                        $pagination_data = $pagination->render();
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
<style>
	.inactive{
		//background-color:#999;	
	}
</style>

<script type="application/javascript">

	$('input[type=radio][name=status]').change(function() {
	
		var volume_id=$(this).attr('id');
		var status_value = this.value;
		
		var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=updateVolumeStatus', '',1);?>");
        $.ajax({			
			url : status_url,
			type :'post',
			data :{volume_id:volume_id,status_value:status_value},
			success: function(){
				//alert(responce);return false;
				//set_alert_message('Successfully Updated',"alert-success","fa-check");	
			},
			error:function(){
				//set_alert_message('Error During Updation',"alert-warning","fa-warning");          
			}			
		});
    });

</script>           
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>