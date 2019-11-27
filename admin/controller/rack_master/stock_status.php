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
	if(isset($_POST['filter_color'])){
		$filter_color=$_POST['filter_color'];		
	}else{
		$filter_color='';
	}
	
	if(isset($_POST['filter_column'])){
		$filter_column=$_POST['filter_column'];		
	}else{
		$filter_column='';
	}
	
	if(isset($_POST['filter_product'])){
		$filter_product=$_POST['filter_product'];
	}else{
		$filter_product='';
	}
	
	if(isset($_POST['filter_product_code'])){
		$filter_product_code=$_POST['filter_product_code'];
	}else{
		$filter_product_code='';
	}
	
	$filter_data=array(
		'color_name' => $filter_color,
		'column_name' => $filter_column,
		'product' => $filter_product,
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
       
          	<span >Stock Status Listing</span>
            
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
                                <label class="col-lg-5 control-label">Color </label>
                                <div class="col-lg-7">
                                  <?php $colors = $obj_rack_master->getPouchColor(); ?>
									<select name="filter_color" id="filter_color" class="form-control validate[required]">
										<option value="">Select Color</option>
											<?php  foreach($colors as $color){
												if(isset($filter_color) && !empty($filter_color) && $filter_color == $color['pouch_color_id']){ ?>
													<option value="<?php echo $color['pouch_color_id']; ?>" selected="selected" ><?php echo $color['color']; ?></option>
												<?php }else{ ?>
													<option value="<?php echo $color['pouch_color_id']; ?>" <?php if(isset($product_code_id) && ($product_code['color'] == $color['pouch_color_id'])) { echo 'selected="selected"';}?> > <?php echo $color['color']; ?></option>
												<?php }
											} ?>
										</select>		
                                </div>
                              </div>
							</div>  
                         <div class="col-lg-4">
                                <div class="form-group">
                                     <label class="col-lg-5 control-label">Product Code</label>
                                      <div class="col-lg-7">
                                        <input type="text" name="filter_product_code" value="<?php echo isset($filter_product_code) ? $filter_product_code : '' ; ?>" placeholder="Product Code" id="input-name" class="form-control" />
                                      </div>
                                </div>
                             </div>
						 </div>
                          <div class="row">
                            <div class="col-lg-4">
							<div class="form-group">
                                <label class="col-lg-5 control-label">Box No</label>
                                <div class="col-lg-7">
                                  <input type="text" name="filter_column" value="<?php echo isset($filter_column) ? $filter_column : '' ; ?>" placeholder="Box No" id="columna" class="form-control" />
                                </div>
                              </div> </div>
							  <div class="col-lg-4">
							<div class="form-group">
                                <label class="col-lg-5 control-label">Product Category</label>
                                <div class="col-lg-7">
                                  <?php $products = $obj_rack_master->getProductCategory(); ?>
                                    <select name="filter_product" class="form-control">
                                    <option value="">Select Product</option>
                                        <?php  foreach($products as $product){
											if(isset($filter_product) && !empty($filter_product) && $filter_product == $product['product_id']) {
                                   ?>
                                                <option value="<?php echo $product['product_id']; ?>" selected="selected" ><?php echo $product['product_name']; ?></option>
                                            <?php }else{ ?>
                                                <option value="<?php echo $product['product_id']; ?>"> <?php echo $product['product_name']; ?></option>
                                            <?php }
                                        } ?>
                                         <?php if(isset($filter_product))
                                            $id=$filter_product;
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
                        <a href="<?php echo $obj_general->link($rout, '&mod=stock_status', '',1); ?>" class="btn btn-info btn-sm pull-right" ><i class="fa fa-refresh"></i> Refresh</a>
                       </div> 
                    </div>
                  </footer>  
                                                  
              </section>
         	</form>
          
            <div class="row">
             <div class="col-lg-3 pull-right">	
                 <select class="form-control" id="limit-dropdown" onchange="location=this.value;">
                 <option value="<?php echo $obj_general->link($rout, '&mod=stock_status', '',1);?>" selected="selected">--Select--</option>
                    	<?php 
							$limit_array = getLimit(); 
							foreach($limit_array as $display_limit) {
								if($limit == $display_limit) {	 
						?>
                  <option value="<?php echo $obj_general->link($rout, '&mod=stock_status&limit='.$display_limit, '',1);?>" selected="selected"><?php echo $display_limit; ?></option>				
						<?php } else { ?>
                            	<option value="<?php echo $obj_general->link($rout, '&mod=stock_status&limit='.$display_limit, '',1);?>"><?php echo $display_limit; ?></option>
                        <?php } ?>
                        <?php } ?>
                 </select>
             </div>
                <label class="col-lg-1 pull-right" style="margin-top:5px;">Show</label>	
            </div>   
         <!-- <a href="#" data-toggle="popover" data-html="true" data-placement="top" data-content='<a href="<?php //echo $obj_general->link($rout, "mod=view&goods_master_id=".encode($goods['goods_master_id']), "",1); ?>"><?php //echo "test data";?></a>'>Notifications</a>
          --></div>
          
          <form name="form_list" id="form_list" method="post">
           <input type="hidden" id="action" name="action" value="" />
          	<div class="table-responsive">
                <table class="table b-t text-small table-hover">
                  <thead>
                    <tr>
					  <th>Sr No.</th>
                      <th class="th-sortable ">
                      		Product Code
                   	  </th>
                      <th>Description</th>
                      <th>Product Category</th>
                      <th>Current Stock</th>
                      <th>Box No</th>                     
                    </tr>
                  </thead>
                  <tbody>
                  <?php
					
                  $goods_master_total = $obj_rack_master->getproduct_status($user_type_id,$user_id,$filter_data,'');
				  $total_goods=(count($goods_master_total));
                  $pagination_data = '';
                  if($total_goods){
                        if (isset($_GET['page'])) {
                            $page = (int)$_GET['page'];
                        } else {
                            $page = 1;
                        }
						
						 if (isset($_GET['sort'])) {
                            $sort_option = $_GET['sort'];
                        } else {
                            $sort_option = 'goods_master_id';
                        }
						
                      //oprion use for limit or and sorting function	
                      $option = array(
                            'sort'  => $sort_option,
                            'order' => $sort_order,
                            'start' => ($page - 1) * $limit,
                            'limit' => $limit
                      );	
					  $goods_master = $obj_rack_master->getproduct_status($user_type_id,$user_id,$filter_data,$option);
						$s=1;
					  foreach($goods_master as $goods){
					 //printr($goods);
					 // if($postedByData){ 
                        ?>
                        <tr> 
						<td><?php echo $s;?></td>
                          <td><?php echo $goods['product_code'];?></td>
                          <td><?php echo $goods['description'];?></td>
                          <td><?php echo $goods['product_name'];?></td>
                          <td><?php
							$dispatch_qty=$obj_rack_master->gettotaldispatch($goods['grouped_s_id']);
								//printr($dispatch_qty);
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
						  if($remaining_qty==0 && $tot_dis_qty==0)
									$remaining_qty=$goods['qty'];
								
							   echo $remaining_qty; 
							 ?></td>
                          <td>
							<?php
								  $d=1;
									//printr($goods);
									$rc = $goods['row'].'@'.$goods['column_name'];
									//printr($rc);
										for($i=1;$i<=$goods['g_row'];$i++)
											{
												for($r=1;$r<=$goods['g_col'];$r++) 
													{
														$n = $i.'@'.$r;
														//printr($rc.'@'.$n);
														if($rc==$n)
														{
															$k=$d;							
															//echo $k.'==</br>';
														}
														$d++;
													}
										
											} 
									if($goods['rack_label']!='')
									    echo $goods['rack_label'];
									else
									    echo $k;?>
						  
						  </td>
                          
                         
                        </tr>
                        <?php
                      //}
					  $s++;
                      } 
                        //pagination
                        $pagination = new Pagination();
                        $pagination->total = $total_goods;
                        $pagination->page = $page;
                        $pagination->limit = $limit;
                        $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                        $pagination->url = $obj_general->link($rout, '&mod=stock_status&page={page}&limit='.$limit.'', '',1);
                        $pagination_data = $pagination->render();
                  } else{ 
                      echo "<tr><td colspan='9'>No record found !</td></tr>";
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


<div class="modal fade" id="gen_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:55%;">
    <div class="modal-content">
    
    	<form class="form-horizontal" method="post" name="credit_note" id="credit_note" style="margin-bottom:0px;">
              <div class="modal-header">
                   	<h4 class="dispatch" id="myModalLabel">Purchase Invoice List</h4>
              </div>
              
               <div class="modal-body">
                    <div class="form-group table_data">
                    	
                    </div>
              </div>
              
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" onclick="reload_pur()">Close</button>
              </div>
   		</form>   
    </div>
  </div>
</div>

<div class="modal fade" id="product_list" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:80%;">
    <div class="modal-content">
    
    	<form class="form-horizontal" method="post" name="credit_note" id="credit_note" style="margin-bottom:0px;">
              <div class="modal-header">
                   	<h4 class="dispatch" id="myModalLabel"><span id="span_inv_no"></span></h4>
              </div>
              
               <div class="modal-body">
                    <div class="form-group pro_data">
                    	
                    </div>
              </div>
              
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
              </div>
   		</form>   
    </div>
  </div>
</div>


<div class="modal fade" id="gen_modal_sales" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:55%;">
    <div class="modal-content">
    
    	<form class="form-horizontal" method="post" name="credit_note" id="credit_note" style="margin-bottom:0px;">
              <div class="modal-header">
                   	<h4 class="dispatch" id="myModalLabel">Sales Invoice List</h4>
              </div>
              
               <div class="modal-body">
                    <div class="form-group sales_table_data">
                    	
                    </div>
              </div>
              
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
              </div>
   		</form>   
    </div>
  </div>
</div>

<div class="modal fade" id="product_list_sales" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:80%;">
    <div class="modal-content">
    
    	<form class="form-horizontal" method="post" name="credit_note" id="credit_note" style="margin-bottom:0px;">
              <div class="modal-header">
                   	<h4 class="dispatch" id="myModalLabel"><span id="span_inv_no_sales"></span></h4>
              </div>
              
               <div class="modal-body">
                    <div class="form-group sales_data">
                    	
                    </div>
              </div>
              
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
              </div>
   		</form>   
    </div>
  </div>
</div>

<div class="modal fade" id="gen_modal_credit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:55%;">
    <div class="modal-content">
    
    	<form class="form-horizontal" method="post" name="credit_note" id="credit_note" style="margin-bottom:0px;">
              <div class="modal-header">
                   	<h4 class="dispatch" id="myModalLabel">Credit Invoice List</h4>
              </div>
              
               <div class="modal-body">
                    <div class="form-group credit_table_data">
                    	
                    </div>
              </div>
              
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" onclick="reload_pur()">Close</button>
              </div>
   		</form>   
    </div>
  </div>
</div>

<div class="modal fade" id="product_list_credit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:80%;">
    <div class="modal-content">
    
    	<form class="form-horizontal" method="post" name="credit_note" id="credit_note" style="margin-bottom:0px;">
              <div class="modal-header">
                   	<h4 class="dispatch" id="myModalLabel"><span id="span_inv_no_credit"></span></h4>
              </div>
              
               <div class="modal-body">
                    <div class="form-group credit_data">
                    	
                    </div>
              </div>
              
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
              </div>
   		</form>   
    </div>
  </div>
</div>
<div class="modal fade" id="smail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
  <div class="modal-dialog" style="width:30%;height:40%">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="sform" id="sform" style="margin-bottom:0px;">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Dispatch Details</h4>
              </div>
              <div class="modal-body">
                   <div class="form-group">
                        <label class="col-lg-3 control-label">Proforma Invoice No</label>
                        <div class="col-lg-8">
                      	<input type="text" name="proforma_no" id="proforma_no" value="" class="form-control validate"/>
                       </div>
                     </div> 
              </div>
            <div class="modal-body">
           <div class="form-group"> 
           		<label class="col-lg-3 control-label"><span class="required">*</span>Invoice No</label> 
                <div class="col-lg-8">
                <input type="text" name="invoice_no" id="invoice_no_model" value="" class="form-control validate[required]">
                </div>
           </div>
           </div>
        <?php /*?>    <div class="modal-body">
           <div class="form-group"> 
           		<label class="col-lg-3 control-label"><span class="required">*</span>Customer Order No</label> 
                <div class="col-lg-8">
                <input type="text" name="order_no" id="order_no" value="" class="form-control validate[required]">
                </div>
           </div>
           </div>
           <div class="modal-body">
           <div class="form-group"> 
           		<label class="col-lg-3 control-label">My Order No</label> 
                <div class="col-lg-8">
                <input type="text" name="my_order_no" id="my_order_no" value="" class="form-control validate">
                </div>
           </div>
           </div>
           <div class="modal-body">
           <div class="form-group"> 
           		<label class="col-lg-3 control-label"><span class="required">*</span>Box No</label> 
                <div class="col-lg-8">
                <input type="text" name="box_no" id="box_no" value="" class="form-control validate[required]">
                </div>
           </div>
           </div>
           <div class="modal-body">
           <div class="form-group"> 
           		<label class="col-lg-3 control-label"><span class="required">*</span>Container No</label> 
                <div class="col-lg-8">
                <input type="text" name="container_no" id="container_no" value="" class="form-control validate[required]">
                </div>
           </div>
           </div><?php */?>
           <div class="modal-body">
           <div class="form-group"> 
           		<label class="col-lg-3 control-label">Company Name</label> 
                <div class="col-lg-8">
                <input type="text" name="company_name" id="company_name" value="" class="form-control validate">
                </div>
           </div>
           </div>
         <?php /*?>   <div class="modal-body">
           <div class="form-group"> 
           		<label class="col-lg-3 control-label">Track Id</label> 
                <div class="col-lg-8">
                <input type="text" name="track_id" id="track_id" value="" class="form-control validate">
                </div>
           </div>
           </div><?php */?>
            <div class="modal-body">
           <div class="form-group"> 
           		<label class="col-lg-3 control-label"><span class="required">*</span>Courier</label> 
                <div class="col-lg-8">
                 <?php echo $obj_rack_master->getCourierCombo();?>
                </div>
          	 </div>
            </div>
            <div class="modal-body" id="courier_add">
        
           </div>
          
            <div class="modal-body">
           <div class="form-group"> 
           		<label class="col-lg-3 control-label">Courier Amount</label> 
                <div class="col-lg-8">
                <input type="text" name="courier_amount" id="courier_amount" value="" class="form-control validate">
                </div>
           </div>
           </div>
            <div class="modal-body">
           <div class="form-group"> 
           		<label class="col-lg-3 control-label"><span class="required">*</span> Qty</label> 
                <div class="col-lg-8">
                <input type="text" name="dispatch_qty" id="dispatch_qty" value="" placeholder="Dispatch Qty"  class="form-control validate[required]"/>
                </div>
           </div>
           </div>
          
              <div class="modal-body">
                   <div class="form-group">
                        <label class="col-lg-3 control-label"> Date </label>
                        <div class="col-lg-8">
               			 <input type="text" name="date" id="date" value="<?php echo date("Y-m-d");?>"  data-format="YYYY-MM-DD"  data-template="D MMM YYYY" 
                         placeholder="Date"  class="combodate form-control"/>
                		</div>
                     </div> 
              </div>
                            
             <div><input type="hidden" name="stock_id" id="stock_id" value="">
        		 <input type="hidden" name="product" id="product" value="">
                 <input type="hidden" name="goods_id" id="goods_id" value="" />
                 <input type="hidden" name="row_column" id="row_column" value="" />
                  <input type="hidden" name="grouped_qty" id="grouped_qty" value="" />
                 
                 <input type="hidden" name="valve_id" id="valve_id" value="">
        		 <input type="hidden" name="zipper_id" id="zipper_id" value="">
                 <input type="hidden" name="spout_id" id="spout_id" value="" />
                 <input type="hidden" name="make_id" id="make_id" value="" />
                 <input type="hidden" name="color_id" id="color_id" value="">
        		 <input type="hidden" name="size_id" id="size_id" value="">
                 <input type="hidden" name="accessorie_id" id="accessorie_id" value="" />
                 <input type="hidden" name="remaining_qty" id="remaining_qty" value="" />
				<input type="hidden" name="product_code_id" id="product_code_id" value="" />
                
                <input type="hidden" name="alldata" id="alldata" value="" />
                <input type="hidden" name="invoice_product_id" id="invoice_product_id" value="" />
                <input type="hidden" name="invoice_id" id="invoice_id" value="" />
                <input type="hidden" name="product_id" id="product_id" value="" />
                <input type="hidden" name="sales_qty" id="sales_qty" value="" />
          	</div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="button" onclick="savedispatch()" name="btn_decline" class="btn btn-warning">Save</button>
              </div>
   		</form>   
    </div>
  </div>
</div>
<style>
.share-btn-count:after {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: -6px;
    border: 6px solid transparent;
    border-top-color: #e6eff5;
}

.share-btn {
 cursor: pointer;
  display: inline-block;
  vertical-align: top;
  position: relative;
  margin: 0 20px;
  padding-top: 25px;
  font-weight: bold;
  text-align: center;
  text-decoration: none;
  border-radius: 8px;
  @include box-shadow(0 2px 2px rgba(black, .2));

  &:active {
    margin-top: 3px;

    .share-btn-action {
      padding-bottom: 3px;
      @include box-shadow(inset 0 -3px rgba(black, .15), inset 0 -1px rgba(black, .15));

      &:after { bottom: 3px; }
    }
  }
}

.share-btn-count {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  line-height: 29px;
  font-size: 19px;
  letter-spacing: -1px;
  color: #555;
  text-shadow: 0 1px white;
  background: #e6eff5;
  border-width: 1px 1px 0;
  border-style: solid;
  border-color: #c5c5c5 #bbb;
  border-radius: 8px 8px 0 0;
  @include linear-gradient(top, rgba(black, .03), transparent 40%);
  @include box-shadow(inset 0 1px rgba(white, .5), 0 1px rgba(black, .18), 0 2px rgba(black, .07));

  &:before, &:after {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: -6px;
    border: 6px solid transparent;
    border-top-color: #e6eff5;
  }

  &:before {
    margin-left: -7px;
    margin-top: 1px;
    border-width: 7px;
    border-top-color: rgba(black, .07);
  }
}

.share-btn-action {
  display: block;
  position: relative;
  line-height: 25px;
  padding: 2px 0 6px;
  font-size: 10px;
  color: white;
  text-shadow: 0 1px 1px rgba(black, .4);
  border: solid rgba(black, .18);
  border-width: 0 1px;
  border-radius: 0 0 8px 8px;

  &:before {
    content: '';
    display: inline-block;
    vertical-align: top;
    margin: 8px 2px 0 0;
    width: 18px;
    height: 18px;
    background-image: url('../img/icons.png');
  }

  &:after {
    content: '';
    position: absolute;
    top: 0;
    bottom: 6px;
    left: 0;
    right: 0;
    border-radius: 0 0 6px 6px;
    @include box-shadow(inset 0 -1px 2px rgba(white, .25));
  }
}

.share-btn-tweet {
  background: #83cfe8;
  @include linear-gradient(top, #83cfe8, #6ebbd4);
  @include box-shadow(inset 0 -6px rgba(black, .16), inset 0 -1px rgba(black, .15));

  &:before { margin-left: -3px; }
  &:after { @include box-shadow(inset 0 -1px 2px rgba(white, .5)); }

  + .share-btn-count {
    @include box-shadow(inset 0 1px rgba(white, .5), 0 1px rgba(black, .12), 0 2px rgba(black, .04));
    &:before { border-top-color: rgba(black, .05); }
  }
}

.share-btn-like {
  background: #6480bd;
  @include linear-gradient(top, #6480bd, #3c5894);
  @include box-shadow(inset 0 -6px rgba(black, .15), inset 0 -1px rgba(black, .2));

  &:before { background-position: -18px 0; }
}

.share-btn-plus {
  background: #626262;
  @include linear-gradient(top, #626262, #404040);
  @include box-shadow(inset 0 -6px rgba(black, .08), inset 0 -1px rgba(black, .3));

  &:before { display: none; }
}
.a-btn{
	background: #80a9da;
    background: linear-gradient(top, #80a9da 0%,#6f97c5 100%);
    padding-left: 20px;
    padding-right: 80px;
    height: 38px;
    display: inline-block;
    position: relative;
    border: 1px solid #5d81ab;
    box-shadow: 
		0px 1px 1px rgba(255,255,255,0.8) inset, 
		1px 1px 3px rgba(0,0,0,0.2), 
		0px 0px 0px 4px rgba(188,188,188,0.5);
    border-radius: 20px;
    float: center;
    clear: both;
    margin: -2px 0px;
    overflow: hidden;
    transition: all 0.3s linear;
}
.a-btn-text{
    padding-top: 5px;
    display: block;
    font-size: 18px;
    white-space: nowrap;
    text-shadow: 0px 1px 1px rgba(255,255,255,0.3);
    color: #446388;
    transition: all 0.2s linear;
}
.a-btn-slide-text{
    position:absolute;
    height: 100%;
    top: 0px;
    right: 52px;
    width: 0px;
    background: #63707e;
    text-shadow: 0px -1px 1px #363f49;
    color: #fff;
    font-size: 18px;
    white-space: nowrap;
    text-transform: uppercase;
    text-align: left;
    text-indent: 10px;
    overflow: hidden;
    line-height: 38px;
    box-shadow: 
		-1px 0px 1px rgba(255,255,255,0.4), 
		1px 1px 2px rgba(0,0,0,0.2) inset;
    transition: width 0.3s linear;
}
.a-btn-icon-right{
    position: absolute;
    right: 0px;
    top: 0px;
    height: 100%;
    width: 52px;
    border-left: 1px solid #5d81ab;
    box-shadow: 1px 0px 1px rgba(255,255,255,0.4) inset;
}
.a-btn-icon-right span{
    width: 38px;
    height: 38px;
    opacity: 0.7;
    position: absolute;
    left: 50%;
    top: 50%;
    margin: -20px 0px 0px -20px;
    background: transparent url(http://tympanus.net/Tutorials/AnimatedButtons/images/arrow_right.png) no-repeat 50% 55%;
    transition: all 0.3s linear;
}
.a-btn:hover{
    padding-right: 180px;
    box-shadow: 0px 1px 1px rgba(255,255,255,0.8) inset, 1px 1px 3px rgba(0,0,0,0.2);
}
.a-btn:hover .a-btn-text{
    text-shadow: 0px 1px 1px #5d81ab;
    color: #fff;
}
.a-btn:hover .a-btn-slide-text{
    width: 100px;
}
.a-btn:hover .a-btn-icon-right span{
    opacity: 1;
}
.a-btn:active {
    position: relative;
    top: 1px;
    background: #5d81ab;
    box-shadow: 1px 1px 2px rgba(0,0,0,0.4) inset;
    border-color: #80a9da;
}
</style>

<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/select2/select2.min.js"></script>
<script type="application/javascript">
			

	$('input[type=radio][name=status]').change(function() {
		$("#loading").show();
		var goods_master_id = $(this).attr('id');
		var status_value = this.value;
		var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=updateGoodsStatus', '',1);?>");
        $.ajax({			
			url : status_url,
			type :'post',
			data :{goods_master_id:goods_master_id,status_value:status_value},
			success: function(){
				$("#loading").hide();
				set_alert_message('Successfully Updated',"alert-success","fa-check");
			},
			error:function(){
				$("#loading").hide();
				set_alert_message('Error During Updation',"alert-warning","fa-warning"); 
			}			
		});
    });

function show_pur()
{
		var data_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=purList', '',1);?>");
		$.ajax({
			url : data_url,
			method : 'post',
			data : {},
			success: function(response){
				
				$(".table_data").html(response);
				
			},
			error:function(){
			}	
		});
		$("#gen_modal").modal("show");
}
function showPurProduct(invoice_id,invoice_no)
{	
		$("#span_inv_no").html(invoice_no);
		
		var data_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=showPurProduct', '',1);?>");
		$.ajax({
			url : data_url,
			method : 'post',
			data : {invoice_id : invoice_id},
			success: function(response){
				console.log(response);
				$(".pro_data").html(response);
				
			},
			error:function(){
			}	
		});
		$("#product_list").modal("show");
		
			
}
function getGenId(invoice_product_id)
{
	if($("#check_"+invoice_product_id).prop('checked') == true)
	{
		$("#rack_"+invoice_product_id).show();
		$("#box_sel"+invoice_product_id).show();
	}
	else
	{
		$("#rack_"+invoice_product_id).hide();
		$("#box_sel"+invoice_product_id).hide();
	}
}
function getGenSalesId(invoice_sales_id)
{
	if($("#sales_"+invoice_sales_id).prop('checked') == true)
	{
		$("#rack_sales_"+invoice_sales_id).show();
	}
	else
	{
		$("#rack_sales_"+invoice_sales_id).hide();
		$("#pallet_sales_"+invoice_sales_id).hide();
		$("#btn_done_sales_"+invoice_sales_id).hide();
	}
}
function show_sales()
{
		var data_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=salesList', '',1);?>");
		$.ajax({
			url : data_url,
			method : 'post',
			data : {},
			success: function(response){
				
				
				$(".sales_table_data").html(response);
				
			},
			error:function(){
			}	
		});
		$("#gen_modal_sales").modal("show");
}
function showSalesProduct(invoice_id,invoice_no,proforma_no,customer_name)
{	
		$("#span_inv_no_sales").html(invoice_no);
		var data_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=showSalesProduct', '',1);?>");
		$.ajax({
			url : data_url,
			method : 'post',
			data : {invoice_id : invoice_id, invoice_no: invoice_no, proforma_no: proforma_no, customer_name: customer_name},
			success: function(response){
				
				//console.log(response);
				$(".sales_data").html(response);
				
			},
			error:function(){
			}	
		});
		$("#product_list_sales").modal("show");
		
			
}
function show_credit()
{
		var data_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=creditList', '',1);?>");
		$.ajax({
			url : data_url,
			method : 'post',
			data : {},
			success: function(response){
				
				
				$(".credit_table_data").html(response);
				
			},
			error:function(){
			}	
		});
		$("#gen_modal_credit").modal("show");
}
function showcreditnote(invoice_id,invoice_no)
{	
		$("#span_inv_no_credit").html(invoice_no);
		
		var data_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=showcreditProduct', '',1);?>");
		$.ajax({
			url : data_url,
			method : 'post',
			data : {invoice_id : invoice_id},
			success: function(response){
				
				$(".credit_data").html(response);
				
			},
			error:function(){
			}	
		});
		$("#product_list_credit").modal("show");
		
			
}
function get_pallet(invoice_product_id)
{
	var rack_val=$("#rack_"+invoice_product_id).val();
	var arr = rack_val.split('=');
	var row = arr[0];
	var col = arr[1];
	var goods_master_id = arr[2];
	var sel = '';
	 sel+= '<select name="pallet_'+invoice_product_id+'" id="pallet_'+invoice_product_id+'" style="width: inherit;" class="form-control"><option>Select Rack</option>';
				var d = 1;
				for(var i=1;i<=row;i++)
				{
					for(var r=1;r<=col;r++) 
					{
						 sel+= '<option value="'+i+'='+r+'='+goods_master_id+'">'+d+'</option>';
						d++;
					}
				}
	  sel+= '</select>';
	
	$("#rack_number_"+invoice_product_id).html(sel);
	$("#qty_insert_"+invoice_product_id).show();
    $("#btn_done_"+invoice_product_id).show();
}
function add_rack_(invoice_product_id,invoice_id)
{
	var span_inv_no = $("#span_inv_no").html();
	var rack_val=$("#rack_"+invoice_product_id).val();
	var pallet_val=$("#pallet_"+invoice_product_id).val();
	var invoice_no = $("#span_inv_no").text();
	var pur_qty = parseInt($("#rack_rem_purqty_"+invoice_product_id).val());
	var store_qty = parseInt($("#qty_insert_"+invoice_product_id).val());
	
	if(store_qty>pur_qty)
	{
		alert('Your Remaining Qty is '+pur_qty+'. Please Enter Proper Qty!! ');
	}
	else
	{
		if(rack_val != '' && pallet_val!='')
		{
			var order_status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=addRack', '',1);?>");
					$.ajax({
							url : order_status_url,
							method : 'post',
							data : {pallet_val:pallet_val,invoice_product_id:invoice_product_id,invoice_id:invoice_id,span_inv_no:span_inv_no,pur_qty:pur_qty,store_qty:store_qty},
							success: function(response){
								//console.log(response);
								set_alert_message('Successfully Added',"alert-success","fa-check");
								showPurProduct(invoice_id,invoice_no)
								//$("#product_list").modal("hide");
						},
						error: function(){
							return false;	
						}
					});
		}
	}
}
function get_pallet_sales(invoice_product_id,product_code_id)
{
	var rack_val=$("#rack_sales_"+invoice_product_id).val();
	var arr = rack_val.split('=');
	var row = arr[0];
	var col = arr[1];
	var goods_master_id = arr[2];
	var sel = '';
	 sel+= '<select name="pallet_sales_'+invoice_product_id+'" id="pallet_sales_'+invoice_product_id+'" style="width: inherit;" class="form-control"><option>Select Rack</option>';
				var d = 1;
				for(var i=1;i<=row;i++)
				{
					for(var r=1;r<=col;r++) 
					{
						 sel+= '<option value="'+i+'='+r+'='+goods_master_id+'">'+d+'</option>';
						d++;
					}
				}
	  sel+= '</select>';
	
	$("#rack_number_sales_"+invoice_product_id).html(sel);
    $("#btn_done_sales_"+invoice_product_id).show();
	 $("#in_qty_"+invoice_product_id).show();
	
}
function check_qty(invoice_product_id,sale_qty)
{
	var in_qty=$("#in_qty_"+invoice_product_id).val();
	//alert(in_qty);
	if(parseInt(sale_qty) < parseInt(in_qty))
	{
		alert("Please Insert Less Than Sales Qty");
		$("#in_qty_"+invoice_product_id).val('');
	}
	
}
function dis_rack_sales(invoice_product_id,invoice_no,perfoma_no,customer_name,sales_qty,product_id,product_code_id,invoice_id)
{
	$("#smail").modal('show');
	//alert(invoice_no);
	$("#invoice_no_model").val(invoice_no);
	$("#proforma_no").val(perfoma_no);
	$("#company_name").val(customer_name);
	$("#invoice_no_model").attr('readonly','readonly');
	var alldata=$("#pallet_sales_"+invoice_product_id).val();
	//alert(alldata);
	$("#alldata").val(alldata);
	$("#sales_qty").val(sales_qty);
	$("#invoice_product_id").val(invoice_product_id);
	$("#product_id").val(product_id);
	$("#product_code_id").val(product_code_id);
	$("#invoice_id").val(invoice_id);
	
}
function savedispatch()
{
	var sales_qty = parseInt($("#sales_qty").val());
	var dispatch_qty = parseInt($("#dispatch_qty").val());
	var invoice_id = $("#invoice_id").val();
	var invoice_no = $("#invoice_no_model").val();
	var proforma_no = $("#proforma_no").val();
	var company_name = $("#company_name").val();
	//alert(invoice_id);
		
	if(dispatch_qty>sales_qty)
	{
		alert('Your Remaining Qty is '+sales_qty+'. Please Enter Proper Qty!! ');
	}
	else
	{
		if($("#sform").validationEngine('validate'))
		{	
			var label_url = getUrl("<?php  echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=savedispatch_racknotify', '',1);?>");
			var formData = $("#sform").serialize();
			$.ajax({
				type: "POST",
				url: label_url,
				data:{formData : formData}, 
				success: function(response) {
					//console.log(response);
					//alert(response);
					set_alert_message('Successfully Dispatched',"alert-success","fa-check");
					//window.setTimeout(function(){location.reload()},1000)
					//$("#product_list_sales").modal('hide');
					$("#smail").modal('hide');
					$('#smail').on('hidden.bs.modal', function () {
						$('#smail .modal-body').find('lable,input,textarea,select').val('');
					});
					//$('#date').combodate('setValue', "12/12/2016");
					$(".day").addClass('validate[required]');
					$(".month").addClass('validate[required]');
					$(".year").addClass('validate[required]');
					showSalesProduct(invoice_id,invoice_no,proforma_no,company_name);
					
				}
			});
		}
	}
	
}
function add_creditnote_recode_(sales_credit_note_id,invoice_id,product_id,product_code_id)
{
	//alert(product_code_id);
	//alert(product_id);
	var span_inv_no = $("#span_inv_no_credit").html();
	var sales_credit_note_id = $("#sales_credit_note_id_"+sales_credit_note_id).val();
	var rack_val=$("#rack_"+sales_credit_note_id).val();
	var pallet_val=$("#pallet_"+sales_credit_note_id).val();
	//alert(rack_val);
	//alert(pallet_val);
	var pur_qty = parseInt($("#sales_rem_purqty_"+sales_credit_note_id).val());
	var invoice_no = $("#span_inv_no").text();
	var store_qty = parseInt($("#qty_insert_"+sales_credit_note_id).val());
	
	if(rack_val != '' && pallet_val!='')
		{
			var order_status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=add_credit_note', '',1);?>");
					$.ajax({
							url : order_status_url,
							method : 'post',
							data : {pallet_val:pallet_val,invoice_id:invoice_id,span_inv_no:span_inv_no,store_qty:store_qty,pur_qty:pur_qty,sales_credit_note_id:sales_credit_note_id,product_id:product_id,product_code_id:product_code_id},
							success: function(response){
								console.log(response);
								set_alert_message('Successfully Added',"alert-success","fa-check");
								showcreditnote(invoice_id,span_inv_no)
								//$("#product_list").modal("hide");
						},
						error: function(){
							return false;	
						}
					});
	
	}
}
function reload_pur()
{
	location.reload();
}
// add by sonu 29-3-2017 
function getcourier_value()
{
	
	var val=$( "#courier_id option:selected" ).val();
	html='';
	if(val == '0')
	{
		   html +='<div class="form-group option">';
                html +=' <label class="col-lg-3 control-label">Other Courier</label>';
                   html +='<div class="col-lg-9">';                
                      html +='<div  class="checkbox ch1" style="float:left;width: 200px;">';
                            html +='<label  style="font-weight: normal;">';
                              html +='<input type="radio" name="courier" id="courier_post" value="6" checked="checked"/> Post';
                              html +=' </label>';
                          html +='</div>';
                            html +='<div class="checkbox ch2" style="float:left;width: 200px;">';
                             html +='<label  style="font-weight: normal;">';
                                html +='<input type="radio" name="courier" id="courier_customer" value="7" /> Customer Take';
								 html +='</label>';
                          html +='</div>';                
                       
              html +='</div>';
			 $('#courier_add').append(html);
	}else
	{
		   html +='';
		  $('#courier_add').remove(html);
	}
	 
	//alert(val);
	//alert(html);
}

</script>           
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>