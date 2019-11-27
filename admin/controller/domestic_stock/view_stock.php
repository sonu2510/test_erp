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
          </header>
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
                  <option value="<?php echo $obj_general->link($rout, '&filter_edit=1&limit='.$display_limit, '',1);?>" selected="selected"><?php echo $display_limit; ?></option>				
						<?php } else { ?>
                            	<option value="<?php echo $obj_general->link($rout, '&filter_edit=1&limit='.$display_limit, '',1);?>"><?php echo $display_limit; ?></option>
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
                <table id="example"  border="1" class="table b-t text-small table-hover">
                  <thead>
                    <tr  class="header">
					  <th>Sr No.</th>
					  <th>Date</th>
                      <th class="th-sortable ">Product Code</th>
                      <th>Description</th>
                      <th>Product Category</th>
                      <th>Store Decription</th>
                      <th>Qty</th>
                      <th>Box No</th>
                      <th>PI No. / STK No.</th>
                      <th>Posted By</th>
                    </tr>
                  </thead>
                 <tbody>
					<?php 
						  $goods_master = $obj_domestic_stock->getStockstatus($user_type_id,$user_id,$filter_data,'');
						  $s=1;
					if(!empty($goods_master)){
						  foreach($goods_master as $goods)
						  {
        						  if($goods['s_description']=='1'){
        						       $view_qty_lbl='<span style="color:green;"><b>Store Qty</b></span>'; $qty=$goods['qty'];
        						  }else{
        						     $view_qty_lbl='<span style="color:red;"><b>Dispatched Qty</b></span>'; $qty=$goods['dispatch_qty'];
        						  }
        						  	$postedByData=$obj_domestic_stock->getUser($goods['user_id'],$goods['user_type_id']);
        							$name = $postedByData['first_name'].' '.$postedByData['last_name'];
        						  ?>
        						        <tr>
							        	        <td><?php echo $s;?></td>
												<td><?php echo dateFormat(4,$goods['date_added']);?></td>
												<td><?php echo $goods['product_code'];?></td>
												<td><?php echo $goods['description'];?></td>
												<td><?php echo $goods['product_name'];?></td>
												<td><?php echo $view_qty_lbl;	?>	</td>
												<td><b><?php echo $qty;	?></b>	</td>
												<td><?php echo $goods['box_no']; ?></td>
												<td><?php echo $goods['invoice_no']; ?></td>
												<td><span style="color:blue;"><b><?php echo  $name; ?></b></span></td>
										</tr>
							<?php $s++;
						  } 
						  
						  
						  	$pagination = new Pagination();
							$pagination->total = $total_goods;
							$pagination->page = $page;
							$pagination->limit = $limit;
							$pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
							$pagination->url = $obj_general->link($rout, '&page={page}&limit='.$limit.'&filter_edit=1', '',1);
							$pagination_data = $pagination->render();
						
					  } else{ 
						  echo "<tr><td colspan='9'>No records found !!!!</td></tr>";
					  } ?>
					  
                  </tbody>
                </table>
              </div>
          </form>
          <footer class="panel-footer">
            <div class="row">
              
          </footer>
        </section>
      </div>
    </div>
  </section>
</section>

<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/select2/select2.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">

<style>
    .dataTables_wrapper .myfilter .dataTables_filter {
        float:left;width:700px;
    }
    .dataTables_wrapper .mylength .dataTables_length {
        float:right
    }
</style>
<script type="application/javascript">
    $(document).ready(function() {
	$('#example').DataTable( {
		dom: 'Bfrtip',
	     lengthMenu: [
            [ 10, 25, 50, -1 ],
            [ '10 rows', '25 rows', '50 rows', 'Show all' ]
        ],
        
	 aoColumnDefs: [{ "bSortable": false, "aTargets":  [ 0, 1, 2, 3,4,5,6 ]  }, 
                { "bSearchable": true, "aTargets": [ 0, 1, 2, 3,4,5,6] }
                ],
		buttons: [
			{	
				extend: 'pdfHtml5',
				orientation: 'portrait',
				pageSize: 'LEGAL',
				footer: 'true',
			
				exportOptions: {
                modifier: {
                    page: ''
                },
            }
				
			}
		],
		 pageLength: 100, 
	
		
} );
    
} );
</script>
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>