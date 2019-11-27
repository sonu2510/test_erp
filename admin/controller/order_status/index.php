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

if($display_status) {


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
               <a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>" ><i class="fa fa-plus"></i> New Order Status </a>
            </span>	
          </header>
          
        
          <form name="form_list" id="form_list" method="post">
           <input type="hidden" id="action" name="action" value="" />
          	<div class="table-responsive">
                <table class="table table-striped b-t text-small table-hover">
                  <thead>
                    <tr>
                      <th>Name </th>
                      <th>Status </th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  	<?php
					
					$rescount = $obj_order_status->getcountstatus();
					$pagination_data = '';
			  		if($rescount){
				   	if (isset($_GET['page'])) {
						$page = $_GET['page'];
					}else {
						$page = 1;
					}
				}
					if($rescount)
					{
						$resorders = $obj_order_status->getvalue();
						foreach($resorders as $order){
						?>
						<tr>
						  <td><?php echo $order['status_name'];?></td>
						  <td><?php echo ($order['status']==1 ? '<label class="label label-success">Active</label>' : '<label class="label label-danger">Inactive</label>');?></td>
						  <td>
							<?php $orderid = $order['order_status_id'];?>
								<a href="<?php echo $obj_general->link($rout,'mod=add&order_id='.$orderid,'',1)?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
						   </td>
						</tr>
						<?php
						}
						 //pagination
                        $pagination = new Pagination();
                        $pagination->total = $rescount;
                        $pagination->page = $page;
                        $pagination->limit = LISTING_LIMIT;
                        $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                        $pagination->url = $obj_general->link($rout, '&page={page}', '',1);
                        $pagination_data = $pagination->render();
                        //echo $pagination_data;die;
					}else{
						echo "<tr><td colspan='3'>No record found !</td></tr>";
					}
					?>
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
//alert("hi");
	var orderid = $(this).attr('id');
	var orderstatus = this.value;
	var orderurl = getUrl("<?php echo $obj_general->ajaxLink($rout,'&mod=ajax&ajaxfunc=UpdateOrderStatus','',1);?>"); 
	$.ajax({
				url : orderurl,
				type : 'POST',
				data : {orderid:orderid,orderstatus:orderstatus},
				success : function(){
					set_alert_message('UPDATED SUCCESSFULLY',"alert-success","fa-check");
				},
				error : function()
				{
					set_alert_message('ERROR IN UPDATION',"alert-warning","fa-warning");
				}
		});
	});
    
</script>
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>