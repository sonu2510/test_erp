<?php

//jayashree
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
	if(isset($_POST['filter_pouch_volume'])){
		$filter_pouch_volume=$_POST['filter_pouch_volume'];		
	}else{
		$filter_pouch_volume='';
	}	
	if(isset($_POST['filter_quantity'])){
		$filter_quantity=$_POST['filter_quantity'];		
	}else{
		$filter_quantity='';
	}
	if(isset($_POST['filter_status'])){
		$filter_status=$_POST['filter_status'];
	}else{
		$filter_status='';
	}
		
	$filter_data=array(
		'pouch_volume' => $filter_pouch_volume,
		'status' => $filter_status,
		'quantity' => $filter_quantity,
	);
	
	$obj_session->data['filter_data'] = $filter_data;

}
if(isset($_GET['page'])){
	if(isset($_SESSION['filter_data']) && !empty($_SESSION['filter_data'])) {
	$filter_data = ($_SESSION['filter_data']);
	}
}

if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'DESC';	
}

//active inactive delete
if(isset($_POST['action']) && ($_POST['action'] == "active" || $_POST['action'] == "inactive") && isset($_POST['post']) && !empty($_POST['post']))
{
		$status = 0;
		if($_POST['action'] == "active"){
			$status = 1;
		}
		$obj_boxmaster->updateTransportation($status,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	
}else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
	
		$obj_boxmaster->updatepouchStatus(2,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	
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
       
          	<span><?php echo $display_name;?> Listing</span>
          	<span class="text-muted m-l-small pull-right">
          		<?php /*?><?php if($obj_general->hasPermission('add',$menuId)){ ?>	
                 <a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>"><i class="fa fa-plus"></i> Add </a>
                 <?php }?> <?php */?>  
            </span>
               <span class="pull-right">
              <a class="label bg-inverse" href="<?php echo $obj_general->link($rout, 'mod=import', '',1);?>" > <i class="fa fa-print"></i> CSV Import</a>
             </span>
          </header><br/><br/>
           
          
          
          <form name="form_list" id="form_list" method="post">
           <input type="hidden" id="action" name="action" value="" />
          	<div class="table-responsive">
                <table class="table b-t text-small table-hover">
                  <thead>
                    <tr>
                  	  <th>Product Name</th>
                      
                    </tr>
                  </thead>
                  <tbody>
                  <?php	
                  $total_product = $obj_boxmaster->getTotalActiveProducts($filter_data);
				 // echo $total_product;
                  $pagination_data = '';
                  if($total_product){
                        if (isset($_GET['page'])) {
                            $page = (int)$_GET['page'];
                        } else {
                            $page = 1;
                        }
                      //oprion use for limit or and sorting function	
                      $option = array(
                          'sort'  => '',
                          'order' => $sort_order,
                          'start' => ($page - 1) * $limit,
                          'limit' => $limit
                      );
					  
                      $products = $obj_boxmaster->getProducts($option,$filter_data);
					  //printr( $products);
					  foreach($products as $product){
					//	printr($product);
                        ?>
                        <tr>
                        	<td><a href="<?php echo $obj_general->link($rout, 'mod=list&product_id='.encode($product['product_id']), '',1); ;?>"  name="btn_edit" ><?php echo $product['product_name'];
							?></a></td>
                          	
                        </tr>
                        <?php
                      }
                        ?>
                        <tr>
                        	<td><a href="<?php echo $obj_general->link($rout, 'mod=list&product_id='.encode(11), '',1); ;?>"  name="btn_edit" >Plastic Scoop</a></td>
                        </tr>
						<?php
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
<style>
	.inactive{
		//background-color:#999;	
	}
</style>

<script type="application/javascript">
	
	
	$('input[name=status]').change(function() {

		var transport_id=$(this).attr('id');
		var status_value = this.value;
		var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&ajax=updateBoxMasterStatus', '',1);?>");
        //alert(status_url);
		$.ajax({			
			   
			url : status_url,
			type :'post',
			data :{transport_id:transport_id,status_value:status_value},
			success: function(){
				set_alert_message('Successfully Updated',"alert-success","fa-check");					
			},
			error:function(){
				set_alert_message('Error During Updation',"alert-warning","fa-warning");          
			}			
		});
    });


</script>           
