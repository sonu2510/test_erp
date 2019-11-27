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
	'href' 	=> $obj_general->link($rout,'&mod=index&status=2', '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);

if(!$obj_general->hasPermission('view',$menuId)){
	$display_status = false;
}

$limit = LISTING_LIMIT;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}

if(isset($_GET['sort'])){
	$sort = $_GET['sort'];	
}else{
	$sort= 'date_added';
}

if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'DESC';	
}

$class = 'collapse';
$filter_data=array();

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
	$filter_order = $obj_session->data['filter_data']['order_no'];
	$filter_customer_name = $obj_session->data['filter_data']['customer_name'];
	$filter_date = $obj_session->data['filter_data']['date'];
	
	
	$filter_data=array(
		'order_no' => $filter_order,
		'customer_name' => $filter_customer_name, 
		'date' => $filter_date
		
	);
}

if(isset($_POST['btn_filter'])){
	
	$filter_edit = 1;
	$class = '';	
	if(isset($_POST['filter_order'])){
		$filter_order=$_POST['filter_order'];		
	}else{
		$filter_order='';
	}
	
	if(isset($_POST['filter_date'])){
		$filter_date=$_POST['filter_date'];		
	}else{
		$filter_date='';
	}

	if(isset($_POST['filter_customer_name'])){
		$filter_customer_name=$_POST['filter_customer_name'];
	}else{
		$filter_customer_name='';
	}	
	
	$filter_data=array(
		'order_no' => $filter_order,
		'customer_name' => $filter_customer_name,
		'date' => $filter_date
	);
$obj_session->data['filter_data'] = $filter_data;

	//$obj_session->data['filter_data'] = $filter_data;	
}

if($display_status) {
	
	//active inactive delete
	
	if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
		//printr($_POST['post']);die;
		if(!$obj_general->hasPermission('delete',$menuId)){
			$display_status = false;
		} else {
			//printr($_POST['post']);die;
			foreach($_POST['post'] as $order_id){
				$obj_cylinder->deleteOrder($order_id);
			}
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
		  	<span><?php echo $display_name;?> Listing </span>
          	<span class="text-muted m-l-small pull-right">
            	 
            </span>
          </header>
          <div class="panel-body">
          
                 <!-- <div class="padding-md">
					<div class="clearfix">
						<div class="pull-left">
							<div class="pull-left m-left-sm">
								<h3 class="m-bottom-xs m-top-xs">Simplify Admin</h3>
								<span class="text-muted">endless.themes@gmail.com</span>
							</div>
						</div>
						<div class="pull-right">
							<h5><strong>#00128</strong></h5>
							<strong>21th Jul 2014</strong>
						</div>
					</div>
					<hr>
					<div class="clearfix">
						<div class="pull-left"> 
							<h4>Company Information</h4> 
							<address> 
								<strong>Twitter, Inc.</strong><br> 
								795 Folsom Ave, Suite 600<br> 
								San Francisco, CA 94107<br> 
								<abbr title="Phone">P:</abbr> (123) 456-7890 
							</address> 
						</div>
						<div class="pull-right text-right">
							<h4>Client Information</h4> 
							<address> 
								<strong>Twitter, Inc.</strong><br> 
								795 Folsom Ave, Suite 600<br> 
								San Francisco, CA 94107<br> 
								<abbr title="Phone">P:</abbr> (123) 456-7890 
							</address> 
						</div>
					</div>-->

       <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link
			($rout,'mod=receive_list&order_id='.$_GET['order_id'], '',1); ?>">
                
                <section class="panel pos-rlt clearfix">
              
              
              
              
                <div class="panel-body clearfix <?php echo $class; ?>">        
                      <div class="row">
                        <div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-5 control-label">Order No</label>
                                <div class="col-lg-7">
                                  <input type="text" name="filter_order" value="<?php echo isset($filter_order) ? $filter_order : '' ; ?>" placeholder="Order No" id="input-name" class="form-control" />
                                </div>
                              </div>
                               <div class="form-group">
                                <label class="col-lg-5 control-label">Date</label>
                                <div class="col-lg-7">                                                            
                                
                                 <input type="text" name="filter_date" readonly data-date-format="dd-mm-yyyy" value="<?php echo isset($filter_date) ? $filter_date : '' ; ?>" placeholder="Date" id="input-name" class="input-sm form-control datepicker" />
                                </div>
                              </div>
                              
                          </div>
                          <div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-4 control-label">Customer</label>
                                <div class="col-lg-8">
                                  <input type="text" name="filter_customer_name" value="<?php echo isset($filter_customer_name) ? $filter_customer_name : '' ; ?>" placeholder="Customer Name" id="input-price" class="form-control">
                                </div>
                              </div>                     
                          </div>
                      </div>                      
                  </div>
            
                  <footer class="panel-footer <?php echo $class; ?>">
                    <div class="row">
                       <div class="col-lg-12">
                        <button type="submit" class="btn btn-primary btn-sm pull-right ml5" name="btn_filter"><i class="fa fa-search"></i> Search</button>
                        <a href="<?php echo $obj_general->link($rout, 'mod=receive_list&order_id='.$_GET['order_id'], '',1); ?>" class="btn btn-info btn-sm pull-right" ><i class="fa fa-refresh"></i> Refresh</a>
                       </div> 
                    </div>
                  </footer>                                  
              </section>
           </form>    
           
           <?php /*
           <div class="col-lg-6 pull-left">
           	 	<div class="panel-body text-muted l-h-2x">
                    <span class="badge" style="background-color:#fcf8e3;">&nbsp;</span> <span class="m-r-small">Inactive</span>
                    <span class="badge" style="background-color:#f2dede;">&nbsp;</span> <span class="m-r-small">Not Save</span>
                </div>
           </div> 
           */ ?>	
          
          <form name="form_list" id="form_list" method="post">
           <input type="hidden" id="action" name="action" value="" />
          	<div class="table-responsive">
            <table class="table table-striped  m-top-md" id="dataTable">
               <!--<table id="quotation-row" class="table b-t text-small table-hover">-->
                  <thead>
                    <tr class="bg-dark-blue">
                      <th>Sr. No.</th>                     
                      <th>Company Name</th>
                      <!--<th>Total</th>-->
                      <th>Size</th>
                      <th>Description</th>
                      <th>Posting Date</th>
                      <th>Estimated Receive Date</th>
                      <th>Received Date</th>
                      <th>Vender Name</th>
                      </tr>
                  </thead>
                  <tbody>
                  <?php
				  include('model/product_quotation.php');
				  $obj_quotation = new productQuotation;
					$cond= 'AND status=2';
				  $order_no=decode($_GET['order_id']);
                  $total_orders = $obj_cylinder->getTotalInprocessOrders($obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS'],$filter_data,$order_no,$cond);
					//printr($total_orders);
                  $pagination_data = '';
                  if($total_orders){
                        if (isset($_GET['page'])) {
                            $page = (int)$_GET['page'];
                        } else {
                            $page = 1;
                        }
                      //oprion use for limit or and sorting function	
                      $option = array(
                         'sort'  => $sort,
                         'order' => $sort_order,
                         'start' => ($page - 1) * $limit,
                         'limit' => $limit
                      );	
						
                      $orders = $obj_cylinder->getInprocessOrders($obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS'],$option,$filter_data,$order_no,$cond);
					  
					// printr($orders);
					  //die;
					  $f = 1;
					  
					  //$product_total = $obj_order->getOrderTotal();
                      foreach($orders as $order){
						//printr($order);
						$vender_name=$obj_cylinder->getVendername($order['vander_name']);
						//printr($vender_name);
						  ?>                      
					  	 <tr>
                             <td width="1%"><?php echo $f;?></td>	 
                             <td>
								<?php echo $order['company_name']; ?><br/>
                             	<small class="text-muted"><?php echo date("d-M-y",strtotime($order['date_added'])); ?></small></a>
                             </td>
                             <td>
							 	<?php echo $order['width'].'X'.$order['height'].'X'.$order['gusset']; ?><br/>
                             </td>
                             <td><?php echo $order['discription']; ?></td>
                            <td><?php echo dateFormat(4,$order['cylinder_date']); ?></td>
                              <td><?php echo dateFormat(4,$order['est_receive_date']); ?></td>
                               <td><?php echo dateFormat(4,$order['receive_date']); ?></td>
                               <td><?php echo $vender_name[0]['vander_first_name'].'&nbsp;'.$vender_name[0]['vander_last_name']; ?></td>
                            
                             
                             
                              <?php /*?> <?php
								$postedByData = $obj_quotation->getUser($order['user_id'],$order['user_type_id']);
								$addedByImage = $obj_general->getUserProfileImage($order['user_type_id'],$order['user_id'],'100_');
								$postedByInfo = '';
								$postedByInfo .= '<div class="row">';
									$postedByInfo .= '<div class="col-lg-3"><img src="'.$addedByImage.'"></div>';
									$postedByInfo .= '<div class="col-lg-9">';
									if($postedByData['city']){ $postedByInfo .= $postedByData['city'].', '; }
									if($postedByData['state']){ $postedByInfo .= $postedByData['state'].' '; }
									if(isset($postedByData['postcode'])){ $postedByInfo .= $postedByData['postcode']; }
									$postedByInfo .= '<br>Telephone : '.$postedByData['telephone'].'</div>';
								$postedByInfo .= '</div>';
								$postedByName = $postedByData['first_name'].' '.$postedByData['last_name'];
								str_replace("'","\'",$postedByName);
							?>
								<a class="btn btn-info btn-xs" data-trigger="hover" data-toggle="popover" data-html="true" data-placement="top" data-content='<?php echo $postedByInfo;?>' title="" data-original-title="<b><?php echo $postedByName;?></b>"><?php echo $postedByData['user_name'];?></a><?php */?>
                    </tr>
					  <?php 
							$f++;
						}
                        
                        //pagination
                        $pagination = new Pagination();
                        $pagination->total = $total_orders;
                        $pagination->page = $page;
                        $pagination->limit = $limit;
                        $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                        $pagination->url = $obj_general->link($rout,'mod=receive_list&order_id='.$_GET['order_id'].'&page={page}&limit='.$limit.'&filter_edit=1', '',1);//HTTP_ADMIN.'index.php?rout='.$rout.'&page={page}';
                        $pagination_data = $pagination->render();
                        //echo $pagination_data;die;
                  } else{ 
                      echo "<tr><td colspan='6'>No record found !</td></tr>";
                  } ?>
                  </tbody>
                </table>
              </div>
          </form>
          <footer class="panel-footer">
            <div class="row">
              <div class="col-sm-3 hidden-xs"> </div>
              <?php echo $pagination_data;?>
             
            </div>
          </footer>
        </section>
      </div>
    </div>
  </section>
</section>


<script type="application/javascript">
/*$(".th-sortable").click(function(){
	alert("asdasd");
});*/
function rec(f,order_id)
{	//alert("hiii");
	var id = order_id;
	//alert(id);
	//var res = rec(order_id);alert(res);
	
	var adds_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=updateQuota', '',1);?>");
	//alert(adds_url);
	$.ajax({
				url : adds_url,
				type :'post',
				data :{id:id},
				success: function(response){
					alert(response);		
					set_alert_message('Successfully Received',"alert-success","fa-check");	
					
				},
				error:function(){
					//set_alert_message('Error!',"alert-warning","fa-warning");   
					return false;	       
				}
	});
}
	
	$('.delete-quot a').click(function(){
		var con = confirm("Are you sure you want to delete ?");
		if(con){
			var quotation_id=$(this).attr('id');
			var del_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=deleteQuotation', '',1);?>");
			$('#loading').show();
			$.ajax({
				url : del_url,
				type :'post',
				data :{quotation_id:quotation_id},
				success: function(response){
					if(response==1){
						$('#quotation-row-'+quotation_id).remove();
						set_alert_message('Successfully Deleted',"alert-success","fa-check");	
					}
					$('#loading').hide();								
				},
				error:function(){
					set_alert_message('Error!',"alert-warning","fa-warning");          
				}			
			});
		}
	});
	
	
	$('input[type=radio][name=status]').change(function() {
	
		//alert($(this).attr('id'));
		var quotation_id=$(this).attr('id');
		var status_value = this.value;
		
		var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=updateQuotationStatus', '',1);?>");
        $.ajax({
			url : status_url,
			type :'post',
			data :{quotation_id:quotation_id,status_value:status_value},
			success: function(response){
				if(response==1){
					set_alert_message('Successfully Updated',"alert-success","fa-check");	
				}else{
					set_alert_message('You Don\'t Have Access To Enable Quotation',"alert-warning","fa-warning");						
				}									
			},
			error:function(){
				set_alert_message('sda',"alert-warning","fa-warning");          
			}			
		});

    });


</script>           
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>