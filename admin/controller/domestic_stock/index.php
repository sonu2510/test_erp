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
	'text' 	=> 'Stock Status List',
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

if(isset($_POST['btn_filter'])){
	//printr($_POST);die;
	$filter_edit = 1;
	$class ='';		
	
	if(isset($_POST['filter_product_code'])){
		$filter_product_code=$_POST['filter_product_code'];
	}else{
		$filter_product_code='';
	}
	if(isset($_POST['filter_box'])){
		$filter_box=$_POST['filter_box'];
	}else{
		$filter_box='';
	}
	
	$filter_data=array(		
		'box' => $filter_box,
		'product_code' => $filter_product_code
	);
	
	//$obj_session->data['filter_data'] = $filter_data;
}

if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'DESC';	
}


if($display_status) {

$user_id=$obj_session->data['ADMIN_LOGIN_SWISS'];
$user_type_id=$obj_session->data['LOGIN_USER_TYPE'];

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
       
          	<span >Domestic Stock Listing</span>
          	<span class="text-muted m-l-small pull-right">
                <a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=view_stock', '',1);?>"> View Stock</a>
            </span>
            <span class="text-muted m-l-small pull-right"> 
                <a class="label bg-info" href="<?php echo $obj_general->link($rout, 'mod=list_product', '',1);?>"> Stock List</a> 
            </span>
            <span class="text-muted m-l-small pull-right">
                <a class="label bg-success" href="javascript:void(0);" onclick="excellink()"><i class="fa fa-print"></i>Export Excel</a>
            </span>
          </header>
          <div class="panel-body">
              <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link($rout, '&mod=stock_status', '',1); ?>">
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
								 <label class="col-lg-5 control-label">Product Code</label>
								  <div class="col-lg-7">
									<input type="text" name="filter_product_code" value="<?php echo isset($filter_product_code) ? $filter_product_code : '' ; ?>" placeholder="Product Code" id="input-name" class="form-control" />
								  </div>
							</div>
						 </div>
						 <div class="col-lg-4">
							<div class="form-group">
								<label class="col-lg-5 control-label">Box No</label>
								<div class="col-lg-7">
								  <input type="text" name="filter_box" value="<?php echo isset($filter_box) ? $filter_box : '' ; ?>" placeholder="Box No" id="columna" class="form-control" />
								</div>
						  </div>
						</div>
					 </div>              
                 </div>
            	
                
                  <footer class="panel-footer <?php echo $class; ?>">
                    <div class="row">
                       <div class="col-lg-12">
                        <button type="submit" class="btn btn-primary btn-sm pull-right ml5" name="btn_filter"><i class="fa fa-search"></i> Search</button>
                        <a href="<?php echo $obj_general->link($rout, '&mod=stock_status', '',1); ?>" class="btn btn-info btn-sm pull-right" ><i class="fa fa-refresh"></i> Refresh</a>
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
                  <option value="<?php echo $obj_general->link($rout, '&limit='.$display_limit, '',1);?>" selected="selected"><?php echo $display_limit; ?></option>				
						<?php } else { ?>
                            	<option value="<?php echo $obj_general->link($rout, '&limit='.$display_limit, '',1);?>"><?php echo $display_limit; ?></option>
                        <?php } ?>
                        <?php } ?>
                 </select>
             </div>
                <label class="col-lg-1 pull-right" style="margin-top:5px;">Show</label>	
            </div>   
         </div>
          
          <form name="form_list" id="form_list" method="post">
           <input type="hidden" id="action" name="action" value="" />
          	<div class="table-responsive stock_data">
                <table class="table b-t text-small table-hover">
                  <thead>
                    <tr  class="header">
					  <th>Sr No.</th>
                      <th class="th-sortable ">Product Code</th>
                      <th>Description</th>
                      <th>Product Category</th>
                      <th>Current Stock</th>
                      <th>Box No</th>                     
                    </tr>
                  </thead>
                 <tbody>
					<?php $goods_master_total = $obj_domestic_stock->getproduct_status($user_type_id,$user_id,$filter_data,'');//printr($goods_master_total);
					  $pagination_data = '';
					  if($goods_master_total){
						$total_goods=(count($goods_master_total));
							if (isset($_GET['page'])) {
								$page = (int)$_GET['page'];
							} else {
								$page = 1;
							}
							
							 if (isset($_GET['sort'])) {
								$sort_option = $_GET['sort'];
							} else {
								$sort_option = 'sm.stock_id';
							}
							
						  //oprion use for limit or and sorting function	
						  $option = array(
								'sort'  => $sort_option,
								'order' => $sort_order,
								'start' => ($page - 1) * $limit,
								'limit' => $limit
						  );	
						  $goods_master = $obj_domestic_stock->getproduct_status($user_type_id,$user_id,$filter_data,$option);
						  $s=1;
						  foreach($goods_master as $goods)
						  {
						       //function added & commented by kinjal on (12-10-2019)
							//$dispatch_qty=$obj_domestic_stock->gettotaldispatch($goods['grouped_s_id']);				
							$dispatch_qty=$obj_domestic_stock->gettotaldispatch_list($goods['product_code_id'],$goods['box_no']);
						
							if($dispatch_qty['total']!='')
							{
								$tot_dis_qty=$dispatch_qty['total'];
								$remaining_qty=$goods['qty']-$tot_dis_qty;
							}
							else
							{
								$tot_dis_qty=0;
								$remaining_qty=0;
							}								
						  if($remaining_qty==0 && $tot_dis_qty==0){
									$remaining_qty=$goods['qty'];
						  }
															
													
						  if($remaining_qty!=0){
						  
						  ?>
						  
								<tr class="header" >
								
											
												<td><?php echo $s;?></td>
												<td><?php echo $goods['product_code'];?></td>
												<td><?php echo $goods['description'];?></td>
												<td><?php echo $goods['product_name'];?></td>
												<td><?php  echo $remaining_qty;?></td>
												<td><?php echo $goods['box_no']; ?></td>
											
										
									
								</tr>
								<tr  style="display: none;">
									<td colspan="6">
										<table class="table b-t table-bordered" style="background-color: linen;">
											<thead>
												<tr class="header" style="background-color: gray;color: antiquewhite;">
													<th>Sr. No.</th>
													<th>Product Code</th>
													<th>Descripton</th>
													<th>Qty</th>
													<th>Date</th>
													<th>Posted By</th>
												 </tr>
											</thead>
											<tbody>
												<?php   //function added & commented by kinjal on (12-10-2019)
												        //$stock_dis_data = $obj_domestic_stock->getdispatchdetail($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],$goods['grouped_s_id']); 
												        $stock_dis_data = $obj_domestic_stock->getdispatchdetail_list($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],$goods['product_code_id'],$goods['box_no']); 
													 // printr($stock_dis_data);
													  if(isset($stock_dis_data) && !empty($stock_dis_data))
													  { $k=1;
														foreach($stock_dis_data as $stock)
														{ 
														//	printr($stock);
															//function added & commented by kinjal on (12-10-2019)
															$dispatch_qty=$obj_domestic_stock->gettotaldispatchChild($stock['stock_id']);
															//$dispatch_qty=$obj_domestic_stock->gettotaldispatchChild_list($stock['product_code_id'],$stock['box_no']);
															if($stock['description']==2){$des="<span style='color:red;'><b>Dispatched</b></span>";$qty=$stock['dispatch_qty'];}
															elseif($stock['description']==1){$des="<span style='color:green;'><b>Store</b></span>";$qty=$stock['qty'];}
															else{$des="Goods Return";$qty=$stock['qty'];}
															$postedByData=$obj_domestic_stock->getUser($stock['user_id'],$stock['user_type_id']);
															$name = $postedByData['first_name'].' '.$postedByData['last_name'];?>
															<tr class="header">
																<td><?php echo $k;?></td>
																<td><?php echo $goods['product_code'];?></td>
																<td><?php echo $des;?></td>
																<td><?php echo $qty;?></td>
																<td><?php echo dateFormat(4,$stock['date_added']);?></td>
																<td><?php echo '<span style="color:blue;"><b>'.$postedByData['first_name'].' '.$postedByData['last_name'].'</b></span>';?></td>
															</tr>
															<?php if(isset($dispatch_qty) && !empty($dispatch_qty))
																  {
																	foreach($dispatch_qty as $child)
																	{ 	
																	    $postedByData=$obj_domestic_stock->getUser($child['user_id'],$child['user_type_id']);
																		if($child['description']==2){$desc="<span style='color:red;'><b>Dispatched</b></span>";$qtyc=$child['dispatch_qty'];}
																		elseif($child['description']==1){$desc="<span style='color:green;'><b>Store</b></span>";$qtyc=$child['qty'];}
																		else{$desc="Goods Return";$qtyc=$child['qty'];}?>
																		<tr class="header">
																			<td></td>
																			<td><?php echo $goods['product_code'];?></td>
																			<td><?php echo $desc;?></td>
																			<td><?php echo $qtyc;?></td>
																			<td><?php echo dateFormat(4,$child['date_added']);?></td>
																			<td><?php echo '<span style="color:blue;"><b>'.$postedByData['first_name'].' '.$postedByData['last_name'].'</b></span>';?></td>
																		</tr>
															<?php   }
																  }?>
												<?php   	$k++;
														}
													  }
												?>
												<tr>
												
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
								
							<?php
								$s++;
						
						}
						} 
							//pagination
							$pagination = new Pagination();
							$pagination->total = $total_goods;
							$pagination->page = $page;
							$pagination->limit = $limit;
							$pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
							$pagination->url = $obj_general->link($rout, '&page={page}&filter_edit=1&limit='.$limit.'', '',1);
							$pagination_data = $pagination->render();
					  } else{ 
						  echo "<tr><td colspan='9'>No records found !</td></tr>";
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

<script>
	$(document).ready(function () {
	  //Fixing jQuery Click Events for the iPad
	  var ua = navigator.userAgent,
	  event = ua.match(/iPad/i) ? "touchstart" : "click";
	  if ($('.table').length > 0) {
		$('.table .header').on(event, function () {
		  $(this).toggleClass("active", "").nextUntil('.header').css('display', function (i, v) {
			return this.style.display === 'table-row' ? 'none' : 'table-row';
		  });
		});
	  }
	});
	//# sourceURL=pen.js
	function excellink()
	{
        var response = $(".stock_data").html();;
        excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(response);	
		 $('<a></a>').attr({
					'id':'downloadFile',
					'download': 'Swisspac live stock.xls',
					'href': excelData,
					'target': '_blank'
			}).appendTo('body');
			$('#downloadFile').ready(function() {
				$('#downloadFile').get(0).click();
			});
					    
	}
</script>
<style class="cp-pen-styles">
	.table tr:not(.header) {
		  display: none;
	}
</style>

<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/select2/select2.min.js"></script>
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>