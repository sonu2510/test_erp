<?php
//priya (25-4-2015)for view of perticular rack detail.
include("mode_setting.php");

//Start : bradcums
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
	'text' 	=> $display_name.' Detail',
	'href' 	=> '',
	'icon' 	=> 'fa-edit',
	'class'	=> 'active',
);
//Close : bradcums
$class = 'collapse';
//Start : edit
$filter_data=array();
$edit = '';
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
	$sort_order = 'ASC';	
}

 $user_id=$obj_session->data['ADMIN_LOGIN_SWISS'];
 $user_type_id=$obj_session->data['LOGIN_USER_TYPE'];

if(isset($_GET['goods_id']) && !empty($_GET['goods_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$goods_id = base64_decode($_GET['goods_id']);
		$arr=explode('-',$_GET['data']);
		$user_id=$obj_session->data['ADMIN_LOGIN_SWISS'];
		$user_type_id=$obj_session->data['LOGIN_USER_TYPE'];
		$goods_data = $obj_goods_master->getGoodsData($goods_id);
		
		//printr($stock_data);
		//die;
		
	}
}
if(!isset($_GET['filter_edit']) || $_GET['filter_edit']==0){
	if(isset($obj_session->data['filter_data'])){
		unset($obj_session->data['filter_data']);	
	}
}
if(isset($obj_session->data['filter_data'])){
	$proforma_no = $obj_session->data['filter_data']['proforma_no'];
	$invoice_no = $obj_session->data['filter_data']['invoice_no'];
	$orderno = $obj_session->data['filter_data']['orderno'];
	$company_name = $obj_session->data['filter_data']['company_name'];
	$date = $obj_session->data['filter_data']['date'];
	$product_code = $obj_session->data['filter_data']['product_code'];
	$class = '';
	
		$filter_data=array(
		'proforma_no' => $proforma_no,
		'invoice_no' => $invoice_no, 
		'orderno' => $orderno,
		'company_name' => $company_name,
		'date' => $date,
		'product_code' => $product_code,	
		
		
	);
}
if(isset($_POST['btn_filter'])){
	$filter_edit = 1;
	$class = '';	
	if(isset($_POST['proforma_no'])){
		$proforma_no=$_POST['proforma_no'];		
	}else{
		$proforma_no='';
	}
	if(isset($_POST['invoice_no'])){
		$invoice_no=$_POST['invoice_no'];		
	}else{
		$invoice_no='';
	}
	if(isset($_POST['orderno'])){
		$orderno=$_POST['orderno'];
	}else{
		$orderno='';
	}	
	if(isset($_POST['company_name'])){
		$company_name=$_POST['company_name'];
	}else{
		$company_name='';
	}
	if(isset($_POST['date'])){
		$date=$_POST['date'];
	}else{
		$date='';
	}
	if(isset($_POST['product_code']))
	{
		$product_code = $_POST['product_code'];
	}else{
		$product_code='';
	}

	$filter_data=array(
		'proforma_no' => $proforma_no,
		'invoice_no' => $invoice_no, 
		'orderno' => $orderno,
		'company_name' => $company_name,
		'date' => $date,
		'product_code' => $product_code,	
	);
	$obj_session->data['filter_data'] = $filter_data;	
	//$obj_rack_master->showInvoice($invoice_no);	
}
if($display_status){
   
if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
	if(!$obj_general->hasPermission('delete',$menuId)){
		$display_status = false;
	} else {
	//	printr($_POST['post']);
		//die;
		$obj_rack_master->deleterecord($_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '&mod=rack_detail&data='.$_GET['data'].'&goods_id='.$_GET['goods_id'], '',1));
	}
}
	
?>
<section id="content">
	<section class="main padder">
		<div class="clearfix">
			<h4><i class="fa fa-edit"></i> <?php echo $display_name;?></h4>
		</div>
		<div class="row">
			<div class="col-lg-12">
			<?php include("common/breadcrumb.php");?>	
			</div> 
		<div class="col-sm-12">
        
            <section class="panel">  
            
                <header class="panel-heading bg-white">
                    <span> Rack Detail</span> 
                    <span class="text-muted m-l-small pull-right">
          			<?php if($obj_general->hasPermission('edit',$menuId)){ ?>
                    
                         <a class="label bg-danger" onclick="deleterecord()"> <i class="fa fa-trash-o"></i> Delete</a>
                    <?php } ?>    
                     <a class="label bg-info" onclick="shift_stock_detail()"> <i class="fa fa-trash-o"></i> Shift Stock</a>
                    
            </span>
                 </header>
                 
               <div class="panel-body">
              <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link($rout, '&mod=rack_detail&data='.$_GET['data'].'&goods_id='.$_GET['goods_id'], '',1); ?>">
                <section class="panel pos-rlt clearfix">
                  <header class="panel-heading">
                    <ul class="nav nav-pills pull-right">
                      <li> <a href="#" class="panel-toggle text-muted active"><i class="fa fa-caret-down fa-lg text-active"></i><i class="fa fa-caret-up fa-lg text"></i></a> </li>
                    </ul>
                    <i class="fa fa-search"></i> Search
                  </header>
              
              
                 <div class="panel-body clearfix <?php echo $class; ?>">        
                      <div class="row">
                      <div class="col-lg-4">
                              <div class="form-group">
                               <label class="col-lg-4 control-label">Proforma No</label>
                        		<div class="col-lg-6">
                            	<input type="text" name="proforma_no" id="proforma_no" class="form-control validate" value="<?php echo isset($proforma_no) ? $proforma_no : '' ; ?>">
                        		</div>
                              </div>                             
                          
                              <div class="form-group">
                                <label class="col-lg-4 control-label" >Invoice No</label>
                                <div class="col-lg-6">
                                    <input type="text" name="invoice_no" id="invoice_no" class="form-control validate" value="<?php echo isset($invoice_no) ? $invoice_no : '' ; ?>">
                                </div>
                              </div> 
                                
                           </div>
                          
                          <div class="col-lg-4">                              
                               <div class="form-group">
                                 <label class="col-lg-4 control-label">Order No</label>
                        		<div class="col-lg-6">
                           		 <input type="text" name="orderno" id="orderno" class="form-control validate" value="<?php echo isset($orderno) ? $orderno : '' ; ?>">
                        		</div>  
                              </div>
                              
                               <div class="form-group">
                                 <label class="col-lg-4 control-label">Company Name</label>
                                <div class="col-lg-6">
                                    <input type="text" name="company_name" id="company_name" class="form-control validate" value="<?php echo isset($company_name) ? $company_name : '' ; ?>">
                                </div>
                              </div>
                          </div>    
                          
                             <div class="col-lg-4">                              
                               <div class="form-group">
                                  <label class="col-lg-4 control-label"> Date </label>
                                    <div class="col-lg-6">
                                     <input type="text" name="date"  id="date" readonly="readonly" data-date-format="yyyy-mm-dd" 
                                     value="" placeholder="Date" class="input-sm form-control datepicker" />
                                    </div>
                              </div>
                          
                            <div class="form-group">
                                  <label class="col-lg-4 control-label"> Product Code</label>
                                    <div class="col-lg-6">
                                     <input type="text" name="product_code"  id="product_code" class="form-control validate" value="<?php echo isset($product_code) ? $product_code : '' ; ?>" />
                                    </div>
                              </div>
                           </div> 
                          </div>
                     
                
                  <footer class="panel-footer <?php echo $class; ?>">
                    <div class="row">
                       <div class="col-lg-12">
                        <button type="submit" class="btn btn-primary btn-sm pull-right ml5" name="btn_filter"><i class="fa fa-search"></i> Search</button>
                        <a href="<?php echo $obj_general->link($rout, '&mod=rack_detail&data='.$_GET['data'].'&goods_id='.$_GET['goods_id'], '',1); ?>" class="btn btn-info btn-sm pull-right" ><i class="fa fa-refresh"></i> Refresh</a>
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
                  <option value="<?php echo $obj_general->link($rout, '&mod=rack_detail&data='.$_GET['data'].'&goods_id='.$_GET['goods_id'].'&limit='.$display_limit, '',1);?>" selected="selected"><?php echo $display_limit; ?></option>				
						<?php } else { ?>
                            	<option value="<?php echo $obj_general->link($rout, '&mod=rack_detail&data='.$_GET['data'].'&goods_id='.$_GET['goods_id'].'&limit='.$display_limit, '',1);?>"><?php echo $display_limit; ?></option>
                        <?php } ?>
                        <?php } ?>
                 </select>
             </div>
                <label class="col-lg-1 pull-right" style="margin-top:5px;">Show</label>	
            </div>   
          </div>
                 
                 
                 	<div class="panel-body">
                		<form name="form_list" id="form_list" method="post">
                           <input type="hidden" id="action" name="action" value="" />
                       
                            <div class="table-responsive">
                                <table id="quotation-row" class="table b-t text-small table-hover">
                                  <thead>
                                    <tr>
                                      <th><input type="checkbox" value=""></th>
                                      <th>Sr. No.</th> 
                                      <th>Product Name</th>  
                                      
                                             <?php /*?><span class="th-sort">
                                            <a href="<?php echo $obj_general->link($rout, '&mod=rack_detail&data='.$_GET['data'].'&goods_id='.$_GET['goods_id'].'&sort=date_added'.'&order=ASC', '',1);?>">
                                                <i class="fa fa-sort-down text"></i>
                                                
                                            <a href="<?php echo $obj_general->link($rout, '&mod=rack_detail&data=1-1&goods_id=Nw==&sort=date_added'.'&order=DESC', '',1);?>">
                                            <i class="fa fa-sort-up text-active"></i>
                                        <i class="fa fa-sort"></i></span><?php */?>
                                      
                                      
                                          <?php /*?> <span class="th-sort">
                                            <a href="<?php echo $obj_general->link($rout, '&mod=rack_detail&data=1-1&goods_id=Nw==&sort=date_added'.'&order=ASC', '',1);?>">
                                                <i class="fa fa-sort-down text"></i>
                                                
                                            <a href="<?php echo $obj_general->link($rout, '&mod=rack_detail&data=1-1&goods_id=Nw==&sort=date_added'.'&order=DESC', '',1);?>">
                                            <i class="fa fa-sort-up text-active"></i>
                                        <i class="fa fa-sort"></i></span><?php */?>
                                      
                                                      
                                      <th>Description</th>
                                      <th>Store Qty</th>
                                      <th>Total Dispatch Qty</th>
                                      <th>Remaining Qty</th>
                                    
                                    <?php /*?>   <th>Date</th><?php */?>
                                     <!-- <th><?php /*?>Posted By<?php */?></th>
                                   	  <th>Action</th>-->
                                      <th></th>
                                     <?php /*?><?php if($tot_dis_qty>0) {?>
                                     <th></th>
                                     <?php } ?><?php */?>
                                    </tr>
                                  </thead>
                                  <tbody>

                    	    
						 <?php 
						
						  $pagination_data = '';
						  $total_records='';
						  
						   $total_stock_data = $obj_rack_master->getrackdetail($user_id,$user_type_id,$goods_id,$arr[0],$arr[1],'',$filter_data);
						   
						   if(!empty($total_stock_data))
						   {
						   	$total_records=count($total_stock_data->rows);
						   }
						   //echo $total_records;
							if($total_records!=0){
							if (isset($_GET['page'])) {
								$page = (int)$_GET['page'];
							} else {
								$page = 1;
							}
							 $start_num =((($page*$limit)-$limit)+1);
					  		$f = 0;
					  		$slNo = $f+$start_num;
                      //oprion use for limit or and sorting function	
                      $option = array(
                            'sort'  => $sort,
                            'order' => $sort_order,
                            'start' => ($page - 1) * $limit,
                            'limit' => $limit,
							
                      );	
						 $stock_data = $obj_rack_master->getrackdetail($user_id,$user_type_id,$goods_id,$arr[0],$arr[1],$option,$filter_data);
					//	printr($stock_data);
						 if(isset($stock_data) && !empty($stock_data)){
							 foreach($stock_data->rows as $stock){ 
							  
						        	$desc = $obj_rack_master->getProductCode($stock['product_code_id']);
							        $dispatch_qty=$obj_rack_master->gettotaldispatch($stock['grouped_s_id']);
							 
							if($dispatch_qty['total']!='')
							{
								$tot_dis_qty=$dispatch_qty['total'];
								$remaining_qty=$stock['tot_qty']-$tot_dis_qty;
							
							}
							else
							{
								$tot_dis_qty=0;
								$remaining_qty=0;
							}
							 	
							
							if($stock['description']==1){$des="Store";}
								elseif($stock['description']==3){$des="Goods Returns";}
								
							   			$user_id=$stock['user_id'];
										$user_type_id=$stock['user_type_id'];
							   			$postedByData=$obj_rack_master->getUser($user_id,$user_type_id);
									$addedByImage = $obj_general->getUserProfileImage($user_type_id,$user_id,'100_');
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
								 
								  <tr> 
                                  

							  <td><input type="checkbox" name="stock_detail[]" value="<?php echo $stock['stock_id'];?>" /></td>
							  <td><?php echo $slNo++;?></td>
            							  <input type="hidden" name="grouped_s_id_<?php echo $stock['stock_id'];?>" id="grouped_s_id_<?php echo $stock['stock_id'];?>" value="<?php echo $stock['grouped_s_id'];?>" />
            							  <input type="hidden" name="goods_id" id="goods_id" value="<?php echo $goods_id;?>" />
            							  <input type="hidden" name="row" id="row" value="<?php echo $arr[0];?>" />
            							  <input type="hidden" name="col" id="col" value="<?php echo $arr[1];?>" />
            							  <input type="hidden" name="product_code_id_shift_<?php echo $stock['stock_id'];?>" id="product_code_id_shift_<?php echo $stock['stock_id'];?>" value="<?php echo $stock['product_code_id'];?>" />
            							  <input type="hidden" name="group_stock_id_<?php echo $stock['stock_id'];?>" id="group_stock_id_<?php echo $stock['stock_id'];?>" value="<?php echo $group_stock_id;?>" />
                               <td width="18%"><?php echo $desc['product_code'].'<b><br> '.$desc['description'].'</b>'; ?></td>
                              <td><?php echo $des; ?></td>
                              
							 <td><?php echo $stock['tot_qty'] ;?></td>
                              <td><?php echo $tot_dis_qty ;?></td>
                              <td><?php if($remaining_qty==0 && $tot_dis_qty==0)
							  $remaining_qty=$stock['tot_qty'];
							   echo $remaining_qty; 
							  //echo $stock['remaining'];?>	 <input type="hidden" name="remaining_qty_<?php echo $stock['stock_id'];?>" id="remaining_qty_<?php echo $stock['stock_id'];?>" value="<?php echo $remaining_qty;?>" /></td>
							  <?php /*?><td>
                              	<?php echo dateFormat(4,$stock['date_added']); ?>
							  </td><?php */?>
                             
                			
                              	
							 
                             <?php $valve="'".$desc['valve']."'"; $group_stock_id="'".$stock['grouped_stock_id']."'";?>
						<?php /*?>	 <td> 	<a class="btn btn-info btn-xs" data-trigger="hover" data-toggle="popover" data-html="true" data-placement="top" data-content='<?php echo $postedByInfo;?>' title="" data-original-title="<b><?php echo $postedByName;?></b>"><?php echo $postedByData['user_name'];?></a></td><?php */?>
							  <?php //echo $postedByData['user_name'];//$postedByData['first_name'].' '.$postedByData['last_name'];?>
                             
                             
                                <?php  if(($obj_session->data['LOGIN_USER_TYPE'] == 1 && $obj_session->data['ADMIN_LOGIN_SWISS'] == 1) || ($obj_session->data['LOGIN_USER_TYPE'] == 2  && $obj_session->data['ADMIN_LOGIN_SWISS'] == 144)) {?>
                               <td >
                            <?php if( $remaining_qty!=0) {?><input type="button" id="approve<?php echo $slNo;?>" class="btn btn-success" 
                              onclick="dispatch_qty(<?php echo $group_stock_id.','.$stock['product'].','.$stock['goods_id'].','.$stock['row'].','.$stock['column_name'].','.$stock['qty'].','.$valve.','.$desc['product_zipper_id'].','.$desc['product_spout_id'].','.$desc['make_id'].','.$desc['pouch_color_id'].',1,'.$desc['product_accessorie_id'].','.$remaining_qty.','.$desc['product_code_id'];?>)" value="Dispatch" name="approve<?php echo $slNo;?>">
                               </td> 
                                
                               <?php }
                             ?>
                            <?php } ?> 
                        
                            <td>
                            <?php // mansi 25-12-2015 (for stock detail)?>
                              <a href="<?php echo $obj_general->link($rout, '&mod=stock_detail&stock_id='.encode($stock['grouped_s_id']),'',1); ?>" class="btn btn-info btn-sm pull-right" >
                               Stock Detail</a>
                             </td>
                             <td>
                            <?php if($tot_dis_qty>0) { ?>
                               <a href="<?php echo $obj_general->link($rout, '&mod=view_dispatch&stock_id='.encode($stock['grouped_s_id']),'',1); ?>" class="btn btn-info btn-sm pull-right" >
                               View Dispatch</a>
                              <?php }?>
                              </td>
                              
							</tr>
								 
							<?php	
							
							 }?>
                                      
                        
                       <?php }
					   	$pagination = new Pagination();
                        $pagination->total = $total_records;
                        $pagination->page = $page;
                        $pagination->limit = $limit;
                        $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                        $pagination->url = $obj_general->link($rout,'&mod=rack_detail&data='.$_GET['data'].'&goods_id='.$_GET['goods_id'].'&page={page}&limit='.$limit.'&filter_edit=1', '',1);//HTTP_ADMIN.'index.php?rout='.$rout.'&page={page}';
                        $pagination_data = $pagination->render();
						}
                         else{
						 echo "No record Found";
						 }
                        ?>
                        </tbody>
                             </table>
                             <div class="form-group">
                            <div class="col-lg-9 col-lg-offset-3">  
                            <?php if(isset($stock_data->row))
								{
									$goods_id=$stock_data->row['goods_id'];
								}
								else
								{
									$goods_id='';
								}?>          
                              <?php /*?>  <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '&mod=view&goods_master_id='.encode($goods_id), '',1);?>">Cancel</a><?php */?>
                            </div>
                        </div>
                       </div>
                 </form>
                 <footer class="panel-footer">
                <div class="row">
                  <div class="col-sm-2 hidden-xs"> </div>
                    <?php echo $pagination_data;?>
                </div>
              </footer>
                </div>
             </section>
         </div>
      </div>
     </section>
</section>

<div class="modal fade" id="shift_stock" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:70%;">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="shift_stock_div" id="shift_stock_div" style="margin-bottom:0px;">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
               
        
                <input type="hidden" name="grouped_s_id" id="grouped_s_id" value=""/>
          
                <h4 class="modal-title" id="myModalLabel">Stock Shift details</h4>
              </div>
              <div class="modal-body">
                   <div class="form-group" id="shift_div">
                        
                    </div> 
                       <div class="form-group"> 
                        <label class="col-lg-2 control-label"><span class="required">*</span>Rack name</label>
                        <div class="col-lg-4">
                           <?php $goods_master = $obj_goods_master->getGoodsMaster('','',$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']); ?>
                           <select name="rack_name" id="rack_name_dispatch" onchange="get_pallet_box()"  class="form-control validate[required]" required >
                              <option>Select Pallet</option>
                              <?php foreach($goods_master as $gd)
                                 { ?>
                              <option value="<?php echo $gd['row'].'='.$gd['column_name'].'='.$gd['goods_master_id'] ;?>" ><?php echo $gd['name']; ?></option>
                              <?php }?>
                           </select>
                        </div>
                         <label class="col-lg-2 control-label"><span class="required">*</span>Rack Position</label>
                        <div class="col-lg-4" id="rack_num">
                        </div>
                     </div>
                     
                     
              </div> 
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="button"  name="btn_shift" onclick="savedispatch_stock_shift()" class="btn btn-info">Shift</button>
              </div>
   		</form>   
    </div>
  </div>
</div>


<div class="modal fade in" id="myModal1" aria-hidden="false" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">WARNING</h4>
            </div>
            <div class="modal-body">
                <p id="setmsg">Please select atlease one record</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" name="popbtnok" id="popbtnok" class="btn btn-primary" style="display: none;">Ok</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<div class="modal fade in" id="delete_model" aria-hidden="false" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">WARNING</h4>
            </div>
            <div class="modal-body">
                <p id="setmsg">Please select atlease one record</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" name="popbtnok" id="popbtnok" class="btn btn-primary" style="display: none;">Ok</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<div class="modal fade" id="smail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
  <div class="modal-dialog" style="width:30%;height:40%">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="sform" id="sform" style="margin-bottom:0px;">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Dispatch Details</h4>
              </div>
            <?php /*?>  <div class="modal-body">
                   <div class="form-group">
                        <label class="col-lg-3 control-label">Proforma Invoice No</label>
                        <div class="col-lg-8">
                      	<input type="text" name="proforma_no" id="proforma_no" value="" class="form-control "/>
                       </div>
                     </div> 
              </div>
            <div class="modal-body">
           <div class="form-group"> 
           		<label class="col-lg-3 control-label"><span class="required">*</span>Invoice No</label> 
                <div class="col-lg-8">
               <!-- <input type="text" name="invoice_no" id="invoice_no_model" value="" class="form-control validate[required]" onchange="check_no()">-->
                <input type="text" name="invoice_no" id="invoice_no_model" value="" class="form-control">
                </div>
           </div>
           </div>
            <div class="modal-body">
           <div class="form-group"> 
           		<label class="col-lg-3 control-label"><span class="required">*</span>Customer Order No</label> 
                <div class="col-lg-8">
                <input type="text" name="order_no" id="order_no" value="" class="form-control ">
                </div>
           </div>
           </div>
           <div class="modal-body">
           <div class="form-group"> 
           		<label class="col-lg-3 control-label">My Order No</label> 
                <div class="col-lg-8">
                <input type="text" name="my_order_no" id="my_order_no" value="" class="form-control ">
                </div>
           </div>
           </div>
           <div class="modal-body">
           <div class="form-group"> 
           		<label class="col-lg-3 control-label"><span class="required">*</span>Box No</label> 
                <div class="col-lg-8">
                <input type="text" name="box_no" id="box_no" value="" class="form-control ">
                </div>
           </div>
           </div>
           <div class="modal-body">
           <div class="form-group"> 
           		<label class="col-lg-3 control-label">Container No</label> 
                <div class="col-lg-8">
                <input type="text" name="container_no" id="container_no" value="" class="form-control ">
                </div>
           </div>
           </div>
           <div class="modal-body">
           <div class="form-group"> 
           		<label class="col-lg-3 control-label">Company Name</label> 
                <div class="col-lg-8">
                <input type="text" name="company_name" id="company_name" value="" class="form-control ">
                </div>
           </div>
           </div>
            <div class="modal-body">
           <div class="form-group"> 
           		<label class="col-lg-3 control-label">Track Id</label> 
                <div class="col-lg-8">
                <input type="text" name="track_id" id="track_id" value="" class="form-control ">
                </div>
           </div>
           </div>
            <div class="modal-body">
           <div class="form-group"> 
           		<label class="col-lg-3 control-label">Courier</label> 
                <div class="col-lg-8">
                 <?php echo $obj_rack_master->getCourierCombo();?>
                </div>
           </div>
           </div>
            <div class="modal-body">
           <div class="form-group"> 
           		<label class="col-lg-3 control-label">Courier Amount</label> 
                <div class="col-lg-8">
                <input type="text" name="courier_amount" id="courier_amount" value="" class="form-control ">
                </div>
           </div>
           </div><?php */?>
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
          	</div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="button" onclick="savedispatch()" name="btn_decline" class="btn btn-warning">Save</button>
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
        jQuery("#sform").validationEngine();
});

function dispatch_qty(grouped_stock_id,product,goods_id,row,column,grouped_qty,valve,zipper_id,spout_id,make_id,color_id,size_id,accessorie_id,remaining_qty,product_code_id)
{
//alert(product_code_id);
	$("#smail").modal('show');
	$("#stock_id").val(grouped_stock_id);
	$("#product").val(product);
	$("#goods_id").val(goods_id);
	$("#grouped_qty").val(grouped_qty);
	
	$("#valve_id").val(valve);
	$("#zipper_id").val(zipper_id);
	$("#spout_id").val(spout_id);
	$("#make_id").val(make_id);
	$("#color_id").val(color_id);
	$("#size_id").val(size_id);
	$("#accessorie_id").val(accessorie_id);
	$("#remaining_qty").val(remaining_qty);
	$("#row_column").val(row+'-'+column);
	$("#product_code_id").val(product_code_id);			
}
function savedispatch()
{
	var remaining_qty = parseInt($("#remaining_qty").val());
	var dispatch_qty = parseInt($("#dispatch_qty").val());
	//var tot_qty = parseInt()
	//alert(dispatch_qty);
		//alert(remaining_qty);
		/*if(tot_qty != dispatch_qty)
		{
			alert('Please Enter Proper Qty!! '+dispatch_qty+'. ');
		}*/
	
	if(dispatch_qty>remaining_qty)
	{
		alert('Your Remaining Qty is '+remaining_qty+'. Please Enter Proper Qty!! ');
	}
	else
	{
		
		check_no();
		if($("#sform").validationEngine('validate'))
		{	
			//alert("noo");
			var label_url = getUrl("<?php  echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=savedispatch', '',1);?>");
			var formData = $("#sform").serialize();
			$.ajax({
				type: "POST",
				url: label_url,
				data:{formData : formData}, 
				success: function(response) {
			//alert(response);
					set_alert_message('Successfully Added',"alert-success","fa-check");
					window.setTimeout(function(){location.reload()},1000)
				}
			});
		}
	}	

}
function savedispatch_stock_shift()
{
	
		if($("#shift_stock_div").validationEngine('validate'))
		{	
			//alert("noo");
			var label_url = getUrl("<?php  echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=savedispatch_stock_shift', '',1);?>");
			var formData = $("#shift_stock_div").serialize();
			$.ajax({
				type: "POST",
				url: label_url,
				data:{formData : formData}, 
				success: function(response) {
			//alert(response);
				
					window.setTimeout(function(){location.reload()},1000)
						set_alert_message('Successfully Shifted',"alert-success","fa-check");
				}
			});
		}
}

function check_no()
{
	//alert("dsf");
	var inv_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=checkInvoice', '',1);?>");
			var inv_val = $("#invoice_no_model").val();
			var product_code_id = $("#product_code_id").val();
			//alert(product_code_id);
			$.ajax({
				type: "POST",
				url: inv_url,
				data:{inv_val : inv_val, product_code_id:product_code_id}, 
				success: function(response) {
					//alert(response);
					if(response == 0)
					{
						//alert('Please Generate Sales Invoice');
						//$("#smail").modal('hide');
						$("#invoice_no_model").val("");
						  $("#sform").submit(function(e){
                			e.preventDefault();
            			});

					}
					if(response != 1 && response != 0)
					{
						//alert('Your Order Is Already Dispatched On This Invoice No:'+ response);
						$("#smail").modal('hide');
						$("#invoice_no_model").val("");
					}
					//set_alert_message('Successfully Added',"alert-success","fa-check");
					//window.setTimeout(function(){location.reload()},1000)
				}
			});
}

/*$("#order_no").change(function(){
	//alert("dsf");
	var no_url = getUrl("<?php  //echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=checkNo', '',1);?>");
			var no_url = $("#order_no").val();
			//alert(inv_val);
			$.ajax({
				type: "POST",
				url: no_url,
				data:{no_url : no_url}, 
				success: function(response) {
					//alert(response);
					if(response == 0)
					{
						alert('Please Give Same Customer No');
						$("#smail").modal('hide');
						$("#order_no").val("");
					}
					//set_alert_message('Successfully Added',"alert-success","fa-check");
					//window.setTimeout(function(){location.reload()},1000)
				}
			});
});*/

function check_qty_Shift(stock_id,qty){
    var shift_qty=$('#shift_qty_'+stock_id).val();
     if(shift_qty>qty)
	  {
		alert('Your Rack Qty is '+qty+'. Please Enter Proper Qty!! ');
		$('#shift_qty_'+stock_id).val('');
	  }
}

function get_pallet_box()
{
	var rack_val=$("#rack_name_dispatch").val();
	var arr = rack_val.split('=');
	var row = arr[0]; 
	var col = arr[1];
	var goods_master_id = arr[2];
	var order_status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getLabel_pallet_shift', '',1);?>");
	$.ajax({
			url : order_status_url,
			method : 'post',
			data : {row:row,col:col,goods_master_id:goods_master_id},
			success: function(response){
				$("#rack_num").html(response);
		},
		error: function(){
			return false;	
		}
	}); 

//	$("#rack_num").html(sel);
	
}


function shift_stock_detail(){
    var stock_id = [];
    var group_id = [];
    $.each($("input:checkbox:checked"), function(){ // [name='stock_detail[]']           
        if($(this).val()!='')
        {
            stock_id.push($(this).val());
           var  id1=$(this).val();
            var id=$("#grouped_s_id_"+id1).val();
            var remaining_qty=$("#remaining_qty_"+id1).val();
            var product_code_id=$("#product_code_id_shift_"+id1).val();
         
            var g_id=id1+'=='+id+'=='+remaining_qty+'=='+product_code_id;
            group_id.push(g_id);
        }
    });
    
    //alert(group_id);
    var goods_id=$("#goods_id").val();
    var row=$("#row").val();
    var col=$("#col").val();
   if(group_id!=''){
    	var no_url = getUrl("<?php  echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=shift_stock_detail', '',1);?>");
			$.ajax({ 
				type: "POST",
				url: no_url,
				data:{stock_id:stock_id,goods_id:goods_id,row:row,col:col,group_id:group_id},  
				success: function(response) {
				    $('#shift_div').html(response);
					$("#shift_stock").modal('show');
				}
			});
    }else{
    	$("#myModal1").modal('show');
    }
    
}function deleterecord(){
    var stock_id = [];
    $.each($("input[type='checkbox']:checked"), function(){            
        stock_id.push($(this).val());
    });
    
   if(stock_id!=''){
    	var no_url = getUrl("<?php  echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=deleterecord', '',1);?>");
			$.ajax({ 
				type: "POST",
				url: no_url,
				data:{stock_id:stock_id},  
				success: function(response) {
			    	set_alert_message('Successfully Deleted',"alert-success","fa-check");
					window.setTimeout(function(){location.reload()},1000)
				}
			});
    }else{
    	
    	$("#delete_model").modal('show');
    }
    
}
</script>


<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>