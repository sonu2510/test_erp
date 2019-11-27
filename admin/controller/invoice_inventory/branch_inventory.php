<?php  //echo $_GET['branch_id'];
include("mode_setting.php");
//[kinjal]:
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
	'text' 	=> 'Branchwise Inventory List',
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

$class ='collapse';
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
	$filter_product_code = $obj_session->data['filter_data']['product_code'];
	$filter_description = $obj_session->data['filter_data']['description'];
	$class = '';	
	$filter_data=array(
		'product_code' => $filter_product_code,
		'description' => $filter_description,  
	);
}
if(isset($_GET['sort'])){
	$sort_name = $_GET['sort'];
}else{
	$sort_name='product_code_id';
}

if(isset($_GET['order'])){
	$sort_order = $_GET['order']; 
}else{
	$sort_order = 'ASC';
}

if(isset($_POST['btn_filter'])){
	$filter_edit = 1;
	$class ='';
	if(isset($_POST['filter_product_code'])){
		$filter_product_code=$_POST['filter_product_code'];		
	}else{
		$filter_product_code='';
	}
	
	if(isset($_POST['filter_description'])){
		$filter_description=$_POST['filter_description'];		
	}else{
		$filter_description='';
	}
	
	$filter_data=array(
		'product_code' => $filter_product_code,
		'description' => $filter_description, 
	);
	$obj_session->data['filter_data'] = $filter_data;	
}

if($display_status) {
	//active inactive delete
	$user_type_id = '4';
	$user_id = base64_decode($_GET['branch_id']);
	$addedByInfo = $obj_invoice->getUser($user_id,$user_type_id);
	//echo $user_type_id;
	//[kinjal] : for excel sheet 
	/*$data = array('f_date'=>'2016-06-1','t_date'=>'2016-06-29');
	$dd = $obj_invoice->addInventory($data,24);
	printr($dd);*/
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
   							<a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>"><i class="fa fa-plus"></i> New Invoice </a> &nbsp;
                    <?php }  ?>
                       <a class="label bg-info" href="javascript:void(0);" onclick="csvlink('post[]')"> <i class="fa fa-print"></i> CSV Export</a>
                      <a class="label bg-inverse" href="<?php echo $obj_general->link($rout, 'mod=import', '',1);?>" > <i class="fa fa-print"></i> CSV Import</a>
					<?php if($obj_general->hasPermission('edit',$menuId)){ ?>
                        <a class="label bg-success" style="margin-left:4px;" onclick="formsubmitsetaction('form_list','active','post[]','<?php echo ACTIVE_WARNING;?>')"><i class="fa fa-check"></i> Active</a>
                        <a class="label bg-warning" onclick="formsubmitsetaction('form_list','inactive','post[]','<?php echo INACTIVE_WARNING;?>')"><i class="fa fa-times"></i> Inactive</a>
                     <?php }
					if($obj_general->hasPermission('delete',$menuId)){ ?>       
                        <a class="label bg-danger" onclick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>
                    <?php } ?> <?php */?>   
                    <?php if($obj_general->hasPermission('edit',$menuId)){ ?>
                         <span class="text-muted m-l-small pull-right">
                             <a class="label bg-success" href="javascript:void(0);" onclick="inventory_data()" ><i class="fa fa-print"></i> Inventory Report</a>
                             <a class="label bg-success" href="javascript:void(0);" onclick="inventory_report()" ><i class="fa fa-print"></i> Inventory Full Report</a>
                         </span>
                   <?php } ?>            
            		</span>
          		</header>          
          		<div class="panel-body">
            	    <form class="form-horizontal" method="post" data-validate="parsley" name="iform" id="iform" action="<?php echo $obj_general->link($rout, '&mod=branch_inventory&branch_id='.$_GET['branch_id'], '',1); ?>">
                		<section class="panel pos-rlt clearfix">
                  			<header class="panel-heading">
                    			<ul class="nav nav-pills pull-right">
                      				<li> <a href="#" class="panel-toggle text-muted active"><i class="fa fa-caret-down fa-lg text-active"></i><i class="fa fa-caret-up fa-lg text"></i></a> 
                                    </li>
                    			</ul>
			                    <i class="fa fa-search"></i> Search 	
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
                                                <label class="col-lg-5 control-label">Description</label>
                                                <div class="col-lg-7">                                                                                           
                                                 <input type="text" name="filter_description" value="<?php echo isset($filter_description) ? $filter_description : '' ; ?>" placeholder="Description" id="input-name" class="form-control" />                                 
                                                </div>
                                          </div>
                                       </div>
                      			</div>       
							</div>        
			                <footer class="panel-footer <?php echo $class; ?>">
			                   <div class="row">
            			          <div class="col-lg-12">
                        			<button type="submit" class="btn btn-primary btn-sm pull-right ml5" name="btn_filter"><i class="fa fa-search"></i> Search</button>
			                         <a href="<?php echo $obj_general->link($rout, '&mod=branch_inventory&branch_id='.$_GET['branch_id'], '',1); ?>" class="btn btn-info btn-sm pull-right" ><i class="fa fa-refresh"></i> Refresh</a>
            			          </div> 
                   				</div>
			                </footer>                                  
            		  </section>
          			</form>    			  
		            <div class="row">
        		        <div class="col-lg-3 pull-right">	
                		    <select class="form-control" id="limit-dropdown" onchange="location=this.value;">
			                     <option value="<?php echo $obj_general->link($rout, '', '',1);?>" selected="selected">--Select--</option>
            		       	<?php $limit_array = getLimit(); 
									foreach($limit_array as $display_limit) {
										if($limit == $display_limit) {	 ?>
                        	           		<option value="<?php echo $obj_general->link($rout, '&mod=branch_inventory&branch_id='.$_GET['branch_id'].'&limit='.$display_limit, '',1);?>" selected="selected"><?php echo $display_limit; ?></option>				
									<?php } else { ?>
                            				<option value="<?php echo $obj_general->link($rout, '&mod=branch_inventory&branch_id='.$_GET['branch_id'].'&limit='.$display_limit, '',1);?>"><?php echo $display_limit; ?></option>
			                        <?php } 
								  } ?>
                    		</select>
                		</div>
                		<label class="col-lg-1 pull-right" style="margin-top:5px;">Show</label>	
              		</div>                 
          		</div>
		        <form name="form_list" id="form_list" method="post">
		           <input type="hidden" id="action" name="action" value="" />
        		   <div class="table-responsive">
		                <table class="table b-t text-small table-hover">
        		          <thead>
                		    <tr>
		                      <th rowspan="2">Product Code</th>
        		              <th rowspan="2">Description</th>
                              <th rowspan="2" id="open_stock" >Opening Stock Qty </th>
                              <th rowspan="2" id="open_stock" > Rate For Opening Stock Qty</th>
                              <th colspan="3" style="text-align:center">Invoice Inventory</th>
                              <th colspan="4" style="text-align:center">Rack Inventory</th>
                               <?php if((($_SESSION['ADMIN_LOGIN_SWISS']=='59' || $_SESSION['ADMIN_LOGIN_SWISS']=='62' || $_SESSION['ADMIN_LOGIN_SWISS']=='50' || $_SESSION['ADMIN_LOGIN_SWISS']=='49') && ($_SESSION['LOGIN_USER_TYPE']=='2')) || 
									 			($_SESSION['ADMIN_LOGIN_SWISS']=='10' && $_SESSION['LOGIN_USER_TYPE']=='4')) {?>
                              			<th colspan="2">Physical Stock</th>
                                   <?php } ?>
                             </tr>
                             <tr>
                		      <th>Purchase Qty</th>
                              <th>Sales Qty</th>
                              <th>Balanced Qty</th>
                              <th>Rack Store Qty</th>
                              <th>Rack Dispatched Qty</th>
                              <th  style="background-color: #b7e4f3;" >Rack Remaining Qty</th>
                              <th>Rack Status</th>
                              <th></th>
                            <?php if($_SESSION['ADMIN_LOGIN_SWISS']=='49' && $_SESSION['LOGIN_USER_TYPE']=='2'){?>   
                               <th colspan="2">Employee</th>
                               <th>Auditor</th>
                             <?php } ?>
        		            </tr>
                		  </thead>
	                 	  <tbody>
                  			<?php 
									$pop = $obj_invoice->getPopularProduct($user_type_id,$user_id);
									$tot = $obj_invoice->getInvoice($user_type_id,$user_id,'',$filter_data);
								
							        $total = count($tot);
							$pagination_data = '';
                 			if($total){
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
							  
							  $invoices = $obj_invoice->getInvoice($user_type_id,$user_id,$option,$filter_data);
							
							  	?>
								<input type="hidden" name="total_count" id="total_count" value="<?php echo count($invoices);?>">
							<?php
							if($invoices !='')
							{$i=1;
                      			foreach($invoices as $invoice){
									$sales_qty = $obj_invoice->getSalesQty($invoice['product_code_id'],$user_type_id,$user_id,'','');
									$purchase_qty = $obj_invoice->getPursQty($invoice['product_code_id'],$user_type_id,$user_id,'','');
									$rack_qty = $obj_invoice->getRackQty($invoice['product_code_id'],$user_type_id,$user_id,'','');
									$rack_qty_new = $obj_invoice->getRackQtyNew($invoice['product_code_id'],$user_type_id,$user_id,'','');
									
									$dis_qty='';
									if(!empty($rack_qty) && $rack_qty['grouped_s_id'] != '')
									{	
										$dispatch_qty=$obj_invoice->gettotaldispatch($rack_qty['grouped_s_id'],$user_type_id,$user_id);
										$dis_qty =	isset($dispatch_qty['total']) ? $dispatch_qty['total']: '' ; 
									}
									$rac_qty = isset($rack_qty['tot_qty']) ? $rack_qty['tot_qty'] : '';
									$pro_c_id = isset($invoice['product_code']) ? $invoice['product_code'] : '';
									$desc = isset($invoice['description']) ? $invoice['description'] : '';
									$p_qty = isset($invoice['pur_qty']) ? $invoice['pur_qty'] : '';
									
									  $total_purchase_qty= $purchase_qty['pur_qty']+$invoice['opening_qty'];
									
									?>
                       				<tr id="product_code_row<?php echo $i;?>">                        
                          				<td id="product_code_td"> <a href="<?php echo $obj_general->link($rout, 'mod=view&product_code_id='.encode($invoice['product_code_id']).'&branch_id='.$_GET['branch_id'].'','',1); ?>" > 
                                            <?php echo $pro_c_id;?></a></td>
                                      <td id="desc_td"><a href="<?php echo $obj_general->link($rout, 'mod=view&product_code_id='.encode($invoice['product_code_id']).'&branch_id='.$_GET['branch_id'].'','',1); ?>" > <?php echo $desc; ?></a></td>
                                      
                                    
                                        
                                        <input type="hidden" class="form-control"  id="open_stock_t<?php echo $i;?>" name="open_stock_t<?php echo $i;?>" onchange="opening_stock(<?php echo $i;?>,<?php echo $invoice['product_code_id'] ;?>)" 
                                       <?php   if($invoice['opening_qty']==0) { ?> style="display:none;" <?php } else {?> readonly="readonly" <?php } ?>   value="<?php echo isset($invoice['opening_qty'])?$invoice['opening_qty']:'' ;?>" > 
                                    
                                       <td> <input type="textbox" class="form-control"  id="open_stock_c<?php echo $i;?>" name="open_stock_c<?php echo $i;?>" onchange="opening_stockcountry(<?php echo $i;?>,<?php echo $invoice['product_code_id'] ;?>)" 
                                       <?php   if($invoice['opening_qty']==0  )  { } else {?> readonly="readonly" <?php } ?>   value="<?php echo isset($invoice['opening_qty'])?$invoice['opening_qty']:'' ;?>" > </td> 
                                       <td> <input type="textbox" class="form-control"  id="open_stock_rate<?php echo $i;?>" name="open_stock_rate<?php echo $i;?>" onchange="opening_stockrate(<?php echo $i;?>,<?php echo $invoice['product_code_id'] ;?>)" 
                                       <?php   if($invoice['opening_value']==0) { } else {?> readonly="readonly" <?php } ?>   value="<?php echo isset($invoice['opening_value'])?$invoice['opening_value']:'' ;?>" > </td>
                                        <td><?php  echo $purchase_qty['pur_qty']; ?><input type="hidden" name="purchase_qty<?php echo $i;?>"id="purchase_qty<?php echo $i;?>" value="<?php echo $purchase_qty['pur_qty']; ?>"/> </td>
                          			  <td><?php echo $sales_qty['qty']; ?></td>
                                      <td><?php echo ($total_purchase_qty - $sales_qty['qty']); ?><input type="hidden" name="purchase_balance_qty<?php echo $i;?>" id="purchase_balance_qty<?php echo $i;?>" value="<?php echo $total_purchase_qty - $sales_qty['qty']; ?>"/></td>
                                      <td><?php echo $rac_qty;?><input type="hidden" name="rack_qty<?php echo $i;?>" id="rack_qty<?php echo $i;?>" value="<?php echo $rac_qty; ?>"/></td>
                                      <td><?php echo $dis_qty; ?></td>
                                      <td  style="background-color: #b7e4f3;"><?php echo $rac_qty - $dis_qty;?><input type="hidden" name="rack_balance_qty<?php echo $i;?>" id="rack_balance_qty<?php echo $i;?>" value="<?php echo $rac_qty - $dis_qty; ?>"/></td>
                                      <td>
                                          <?php 
                                                echo '<table border="1">
    													<th>Rack Name</th>
    													<th>Rack Position</th>
    													<th>Qty</th>';
                                                            if(!empty($rack_qty_new))
            												{
            													foreach($rack_qty_new as $rack)
            													{
            														$d=1;
            														$rc = $rack['row'].'@'.$rack['column_name'];
            														for($i=1;$i<=$rack['g_row'];$i++)
            														{
            															for($r=1;$r<=$rack['g_col'];$r++) 
            															{
            																$n = $i.'@'.$r;
            																if($rc==$n)
            																{
            																	$col_row = $rc;
            																	$k=$d;
            															    	$r_no[]=$k;	
            																}
            																$d++;
            															}
            														}
            														$dispatch_qty=$obj_invoice->gettotaldispatch($rack['stock_id'],$user_type_id,$user_id);
            														$lable = $obj_invoice->getLabel($col_row,$rack['goods_master_id']);
            														$rm_qty=$rack['store_qty']-$dispatch_qty['total'];
            														$l =$k;
            														if($lable!='')
            														    $l = $lable;
            														echo '<tr><td>'.$rack['name'].'</td>
            																		<td align="center">'.$l.'</td>
            																		<td>'.$rm_qty.'</td></tr>';
            														$a[]=$k;
            														$r_qty[] =$rm_qty.'='.$k;
            													}
            												}
            									echo '</table>';
                                          ?>
                                          
                                      </td>
                                       <td> <?php if($invoice['opening_qty']!='' && $invoice['opening_qty']!=0 || $invoice['opening_qty'] !='' && $invoice['opening_qty']!==0 ||$invoice['opening_value']!='' && $invoice['opening_value']!==0)
                                           {
                                                $edit=1;
                                           } 
                                           else
                                           {
                                                $edit=0;
                                           ?> 
                                             <input type="button" id="btn_add<?php echo $i;?>" name="btn_add<?php echo $i;?>" onclick="add_record(<?php echo $i;?>,<?php echo $edit;?>)" value="Add" class="btn btn-info btn-xs" />
                                            <?php } //10 49?>
                         			 </td>
                                     <?php if((($_SESSION['ADMIN_LOGIN_SWISS']=='59' || $_SESSION['ADMIN_LOGIN_SWISS']=='62' || $_SESSION['ADMIN_LOGIN_SWISS']=='50' || $_SESSION['ADMIN_LOGIN_SWISS']=='49') && ($_SESSION['LOGIN_USER_TYPE']=='2')) || 
									 			($_SESSION['ADMIN_LOGIN_SWISS']=='10' && $_SESSION['LOGIN_USER_TYPE']=='4')) {?>
                                                 <td> <?php $stock_qty = $obj_invoice->getPhyStockqty($invoice['product_code_id']); ?>
                                                    <span <?php if(!isset($stock_qty['stock_qty']) || $stock_qty['y_n_status']== '0') { ?> style="display:none;"<?php } ?>><?php echo dateFormat('4',$stock_qty['date_added']);?></span>
                                                    <input type="text" name="phy_stock_qty_<?php echo $i;?>" id="phy_stock_qty_<?php echo $i;?>" class="form-control validate[required,custom[number],min[1]]" value="<?php echo isset($stock_qty['stock_qty']) ? $stock_qty['stock_qty'] :'' ; ?>" <?php if(!isset($stock_qty['stock_qty']) || $stock_qty['y_n_status']== '0') { ?> style="display:none;"<?php } ?> onchange="add_phy_stock(<?php echo $i;?>,<?php echo $invoice['product_code_id'];?>,'')"  readonly="readonly" />
                                                 </td>
                                                 <td>
                                                    <!--for yes = 0 & no = 1 (table) vijay id = user_id 59 and type_id = 2-->
                                                    <input type="button" id="btn_yes<?php echo $i;?>" name="btn_yes<?php echo $i;?>" onclick="add_yes(<?php echo $i;?>,<?php echo $invoice['product_code_id'];?>)" value="Yes" class="btn btn-xs bg-primary" />
                                                     <br /><input type="button" id="btn_no<?php echo $i;?>" name="btn_no<?php echo $i;?>" onclick="add_no(<?php echo $i;?>,<?php echo $invoice['product_code_id'];?>)" value="No" class="btn btn-xs bg-primary" />
                                                 </td>
                                                 <?php if($_SESSION['ADMIN_LOGIN_SWISS']=='49' && $_SESSION['LOGIN_USER_TYPE']=='2'){?>   
                                                 		<td> <?php $emp_stock_qty = $obj_invoice->getPhyStockqty($invoice['product_code_id'],$n=1); ?>
                                                    		<span <?php if(!isset($emp_stock_qty['stock_qty']) || $emp_stock_qty['y_n_status']== '0') { ?> style="display:none;"<?php } ?>><?php echo dateFormat('4',$emp_stock_qty['date_added']);?></span>
                                                            <input type="text" name="emp_stock_qty<?php echo $i;?>" id="emp_stock_qty<?php echo $i;?>" class="form-control validate[required,custom[number],min[1]]" value="<?php echo isset($emp_stock_qty['stock_qty']) ? $emp_stock_qty['stock_qty'] :'' ; ?>" <?php if(!isset($emp_stock_qty['stock_qty']) || $emp_stock_qty['y_n_status']== '0') { ?> style="display:none;"<?php } ?> readonly="readonly" />
                                                 </td>
                                      <?php } } ?>
                                      
		                        </tr>
                        		<?php $i++;} ?>
							
								<?php 
								$pagination = new Pagination();
								$pagination->total = $total;
								$pagination->page = $page;
								$pagination->limit = $limit;
								$pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
								$pagination->url = $obj_general->link($rout, '&mod=branch_inventory&branch_id='.$_GET['branch_id'].'&page={page}&limit='.$limit.'&filter_edit=1', '',1);
								$pagination_data = $pagination->render();
                    		} 
                 			}
							else
							{ 
								echo "<tr><td colspan='5'>No record found !</td></tr>"; 
							} 
							?>
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
              <div id="test"></div>
            </section>
        </div>
    </div>
  </section>
</section>


<div class="modal fade" id="smail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
  <div class="modal-dialog" style="width:30%;height:40%">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="frm_inventory" id="frm_inventory" style="margin-bottom:0px;">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Inventory Report Details</h4>
              </div>
              <br />
           <?php /*?><div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Date From</label>
                <div class="col-lg-4">
                  <input type="text" class="form-control validate[required]" name="f_date" value="" placeholder="From Date" class="span2 form-control" data-date-format="yyyy-mm-dd" readonly="readonly"  id="f_date"/>
                    </div>
              </div><?php */?>
              
              <div class="modal-body">
                   <div class="form-group">
                        <label class="col-lg-3 control-label"> Date From </label>
                        <div class="col-lg-8">
               			 <input type="text" name="f_date" id="f_date" value="<?php echo date("Y-m-d");?>"  data-format="YYYY-MM-DD"  data-template="D MMM YYYY" 
                         placeholder="Date"  class="combodate form-control"/>
                		</div>
                     </div> 
              </div>
              
              
              <div class="modal-body">
                   <div class="form-group">
                        <label class="col-lg-3 control-label"> Date To </label>
                        <div class="col-lg-8">
               			 <input type="text" name="t_date" id="t_date" value="<?php echo date("Y-m-d");?>"  data-format="YYYY-MM-DD"  data-template="D MMM YYYY" 
                         placeholder="Date"  class="combodate form-control"/>
                		</div>
                     </div> 
              </div>
              
              <?php /*?><div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Date To</label>
                <div class="col-lg-4">
                 <input type="text" class="form-control validate[required]" name="t_date" value="" placeholder="To Date" class="span2 form-control" data-date-format="yyyy-mm-dd" readonly="readonly" id="t_date"/>
                </div>
              </div><?php */?>
              <br />
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <?php /*?><button type="button" onclick="savedispatch()" name="btn_decline" class="btn btn-warning">Save</button><?php */?>
                <button type="button" name="btn_pro" onclick="addInventory()" id="excel_link" class="btn btn-primary">Proceed</button>
              </div>
   		</form>   
   
    </div>
  </div>
</div>

<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

<script type="application/javascript">



	 jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
      	var count=$("#total_count").val();
		//alert(count);
		//debugger;
		for(var i=1;i<=count;i++)
		{
			//var purchase_value=$("#purchase_qty"+i).val();
			var rack_value=$("#rack_qty"+i).val();
			
			// mansi 
			var total=$("#purchase_balance_qty"+i).val();
			//alert(total);
			//var total = purchase_value+opening_stock_qty;
			var rack_qty=$("#rack_balance_qty"+i).val();
			//alert(rack_qty);
			if(total!=rack_qty )
			{
				$("#product_code_row"+i).closest("tr").css("background-color","pink");
				$("#product_code_row"+i).find('td:eq(1)') .css('background-color', 'pink');
				
			}
		}
    });


	$('input[name=status]').change(function() {
		var invoice_no=$(this).attr('id');
		var status_value = this.value;
		var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=updateInvoice', '',1);?>");
		$.ajax({
			url : status_url,
			type :'post',
			data :{invoice_no:invoice_no,status_value:status_value},
			success: function(){
				set_alert_message('Successfully Updated',"alert-success","fa-check");					
			},
			error:function(){
				set_alert_message('Error During Updation',"alert-warning","fa-warning");          
			}						
		});
    });
	function csvlink(elemName){
		elem = document.getElementsByName(elemName);
		var flg = false;
		for(i=0;i<elem.length;i++){
			if(elem[i].checked)
			{
				flg = true;
				break;
			}
		}
	if(flg)
	{
		var csv_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=csvInvoice', '',1);?>");
		var formData = $("#form_list").serialize();	
		$.ajax({
				url : csv_url,
				type :'post',
				data :{formData:formData},
				success: function(re){
				  csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(re);	
				 $('<a></a>').attr({
							'id':'downloadFile',
							'download': 'Invoice_CSV.csv',
							'href': csvData,
							'target': '_blank'
					}).appendTo('body');
					$('#downloadFile').ready(function() {
						$('#downloadFile').get(0).click();
					});
					
				},
				error:function(){
				}						
			});		
	}
	else
	{
		$(".modal-title").html("WARNING");
		$("#setmsg").html('Please select atlease one record');
		$("#popbtnok").hide();
		$("#myModal").modal("show");
	}
	
}

// mansi(for opening stock)
		
//function add_record(i,edit)		 
//{
//  		data= $("#open_stock_t"+i).val();
//		datac= $("#open_stock_c"+i).val();
//		datar=$("#open_stock_rate").val();
//		
//	//alert(data);
//	if(edit=='1')
//		$("#open_stock_t"+i).removeAttr('Readonly','Readonly');
//		$("#open_stock_c"+i).removeAttr('Readonly','Readonly');
//		$("#open_stock_rate"+i).removeAttr('Readonly','Readonly');
//	
//		$("#btn_add"+i).hide();
//		$("#btn_edit"+i).show();
//		//if($data==0)
//		$("#open_stock_t"+i).show();
//		$("#open_stock_c"+i).show();
//		
//   		$("#open_stock").show();
//		
//}	
	 
function opening_stock(i,product_code_id){
	//alert(product_code_id);

		var open_qty=$('#open_stock_t'+i).val();
		//alert(open_qty);
		//v
		var qty_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=openingStockQty', '',1);?>");
		$.ajax({
			url : qty_url,
			type :'post',
			data :{open_qty:open_qty,product_code_id:product_code_id},
			success: function(response){
				//alert(response);
			
				set_alert_message('Successfully Updated',"alert-success","fa-check");
					window.setTimeout(function(){location.reload()},500);					
			},
			error:function(){
				set_alert_message('Error During Updation',"alert-warning","fa-warning");          
			}						
		});
  
	}
	//sonu added 8/12/2016
	function opening_stockcountry(i,product_code_id){
	//alert(product_code_id);

		var open_qty_by_country =$('#open_stock_c'+i).val();
		var branch_id = <?php echo decode($_GET['branch_id']); ?>;
		//alert(open_qty_by_country);
		//v
		var qty_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=openingStockQtybycountry', '',1);?>");
		$.ajax({
			url : qty_url,
			type :'post',
			data :{open_qty_by_country:open_qty_by_country,product_code_id:product_code_id,branch_id:branch_id},
			success: function(response){
				//alert(response);
				//console.log(response);
			
				set_alert_message('Successfully Updated',"alert-success","fa-check");
				window.setTimeout(function(){location.reload()},500);					
			},
			error:function(){
				set_alert_message('Error During Updation',"alert-warning","fa-warning");          
			}						
		});
  
	}

function inventory_data()
{
	$("#smail").modal('show');
	
}
function inventory_report()
{
	var branch_id = <?php echo decode($_GET['branch_id']); ?>;
//	alert(branch_id);
	var inventory_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=Report', '',1);?>");
		$.ajax({
			url : inventory_url,
			method : 'post',
			data : {branch_id : branch_id},
			success: function(response){
				//alert(response);
				//console.log(response);
				//$("#smail").modal('hide');
			excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(response);
				 $('<a></a>').attr({
								'id':'downloadFile',
								'download': 'inventory-full-report.xls',
								'href': excelData,
								'target': '_blank'
						}).appendTo('body');
						$('#downloadFile').ready(function() {
							$('#downloadFile').get(0).click();
						});
			
			},
			error: function(){
				return false;	
			}
		});
}

	/*$("#excel_link").click(function(){
	var add_product_url = getUrl("<?php //echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=stock_data', '',1);?>");
	var post_arr = $('#stock_data').val();
	 $.ajax({
        url: add_product_url, // the url of the php file that will generate the excel file
       	data : {post_arr : post_arr},
		method : 'post',
        success: function(response){
		//alert(response);
			excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(response);
			 $('<a></a>').attr({
							'id':'downloadFile',
							'download': 'inventory-report.xls',
							'href': excelData,
							'target': '_blank'
					}).appendTo('body');
					$('#downloadFile').ready(function() {
						$('#downloadFile').get(0).click();
					});
        }
		
    });
});	*/

function addInventory()
{
	if($("#frm_inventory").validationEngine('validate')){
		var branch_id = <?php echo decode($_GET['branch_id']); ?>;
		//alert(branch_id);
		var formData = $("#frm_inventory").serialize();
		
		var inventory_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=addInventory', '',1);?>");
		$.ajax({
			url : inventory_url,
			method : 'post',
			data : {formData : formData,branch_id:branch_id},
			success: function(response){
				//alert(response);
				//console.log(response);
				$("#smail").modal('hide');
				excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(response);
				 $('<a></a>').attr({
								'id':'downloadFile',
								'download': 'inventory-report.xls',
								'href': excelData,
								'target': '_blank'
						}).appendTo('body');
						$('#downloadFile').ready(function() {
							$('#downloadFile').get(0).click();
						});
			
			},
			error: function(){
				return false;	
			}
		});
	}
	else
	{
		alert('Please Fill Form');
	}
}	
function add_no(i,product_code_id)
{
	 $("#phy_stock_qty_"+i).show();
	 $("#phy_stock_qty_"+i).removeAttr('Readonly','Readonly');
}
function add_phy_stock(i,product_code_id,yes)
{
	//alert(yes);
	 var phy_stock_qty = $('#phy_stock_qty_'+i).val();
	 var qty_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=addPhyStock', '',1);?>");
	 $.ajax({
			url : qty_url,
			method : 'post',
			data : {phy_stock_qty: phy_stock_qty, product_code_id:product_code_id, yes:yes},
			success: function(response){
				//console.log(response);
				
				set_alert_message('Successfully Inserted',"alert-success","fa-check");
				window.setTimeout(function(){location.reload()},500);
			
			},
			error: function(){
				return false;	
			}
		});
}
function add_yes(i,product_code_id)
{
	add_phy_stock(i,product_code_id,yes=0);
}
function opening_stockrate(i,product_code_id){
	//alert(product_code_id);

		var open_stock_rate =$('#open_stock_rate'+i).val();
		var branch_id = <?php echo decode($_GET['branch_id']); ?>;
		///alert(open_stock_rate);
		//v
		var qty_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=openingStockQtybyrate', '',1);?>");
		$.ajax({
			url : qty_url,
			type :'post',
			data :{open_stock_rate:open_stock_rate,product_code_id:product_code_id,branch_id:branch_id},
			success: function(response){
				//alert(response);
				//console.log(response);
			
				set_alert_message('Successfully Updated',"alert-success","fa-check");
				window.setTimeout(function(){location.reload()},500);					
			},
			error:function(){
				set_alert_message('Error During Updation',"alert-warning","fa-warning");          
			}						
		});
  
	}
</script>           
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>