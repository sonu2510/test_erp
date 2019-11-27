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
	'text' 	=> 'Product Order List',
	'href' 	=> '',
	'icon' 	=> 'fa-list',
	'class'	=> 'active',
);

if(!$obj_general->hasPermission('view',$menuId)){
	$display_status = false;
}

//$class ='collapse';
$filter_data=array();

if(!isset($_GET['filter_edit'])){
	$filter_edit = 0;
}else{
	$filter_edit = $_GET['filter_edit'];
}

$limit = LISTING_LIMIT;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}
if(!isset($_GET['filter_edit']) || $_GET['filter_edit']==0){
	if(isset($obj_session->data['filter_data'])){
		unset($obj_session->data['filter_data']);	
	}
}
$class='collapse';
if(isset($_GET['sort'])){
	$sort_name = $_GET['sort'];
}else{
	$sort_name='product_id';
}

if(isset($_GET['order'])){
	$sort_order = $_GET['order']; 
}else{
	$sort_order = 'ASC';
}
$total_stock = $obj_inventory->getTotalProductStk($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE']);

$pagination_data = '';
?>
<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-list"></i>Product Order List</h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>   
        
      <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading bg-white"> 
		  	<span>Product Order Listing</span>
            <span class="text-muted m-l-small pull-right">
            	
                <?php if($obj_general->hasPermission('add',$menuId)){ ?>
   							<a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add_stock', '',1);?>"><i class="fa fa-plus"></i> Add </a>
                    <?php }?>
                    </span>
          </header>
         
            	  <div class="panel-body">
          <form name="form_list" id="form_list" method="post" action="<?php echo $obj_general->link($rout,'mod=listItems', '',1); ?>">
           <input type="hidden" id="action" name="action" value="" />
          	<div class="table-responsive">
                
                      <?php /*?><?php $k=2; $t="product_spout";?><?php */?>
      			<?php	
				if($total_stock)
				{
                        if (isset($_GET['page'])) {
                            $page = (int)$_GET['page'];
                        } else {
                            $page = 1;
                        }
						$obj_session->data['page'] = $page;
                      //option use for limit or and sorting function	
                      $option = array(
                           'sort'  => $sort_name,
                           'order' => $sort_order,
                           'start' => ($page - 1) * $limit,
                           'limit' => $limit
                      );	
                      $stock = $obj_inventory->getProductStk($option,$obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE']); 
					//printr($stock);
					$n=1;
					?>
                <div class="panel-group m-b" id="accordion2">
					<?php
					 foreach($stock as $stk)
					 {	//$o_no=$stk['order_no'];
						 ?>
					 	   <div class="panel"> <div class="panel-heading"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse_<?php echo $n;?>" onclick="div_hide(<?php echo $n;?>,<?php echo '\''.$stk['order_no'].'\''; ?>)"><?php echo $stk['order_no'];?> </a> </div> <div id="collapse_<?php echo $n;?>" class="panel-collapse collapse"> 
                           <div class="panel-body text-small" id="tabletr_<?php echo $n;?>">
                          </div> </div> </div>
			<?php $n++;}?>
        	</div>
			<?php }?>
              </div>
          </form>
        </section>
      </div>
    </div>
  </section>
</section>
<script type="application/javascript">
jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#form_list").validationEngine();
});
function test()
{
	alert('asf');
}
function div_hide(tnm,id)
{
	//alert(tnm);

	 var indent_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=displayStk', '',1);?>");
	 $.ajax({
			url : indent_url,
			method : 'POST',
				data:{tnm:tnm,id:id},
				success: function(response){
					//alert(response);
					$("#tabletr_"+tnm).html(response);
				
				},
				error: function(){
					return false;	
				}
				});
}
</script>           
