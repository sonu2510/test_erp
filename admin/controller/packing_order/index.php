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


$limit = 20;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
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
	$filter_order = $obj_session->data['filter_data']['ref_order_no'];
	$filter_name = $obj_session->data['filter_data']['cust_nm'];
	$filter_email = $obj_session->data['filter_data']['cust_email'];
	$filter_product_code = $obj_session->data['filter_data']['product_code'];
	$pro_in_no = $obj_session->data['filter_data']['pro_in_no'];
	$filter_user_name = $obj_session->data['filter_data']['postedby'];
	
	$class = '';
	
	$filter_data=array(
		'ref_order_no' => $filter_order,
		'cust_nm' => $filter_name,
		'cust_email' => $filter_email,
		'product_code' => $filter_product_code,
		'pro_in_no' =>$pro_in_no,
		'postedby' =>$filter_user_name	
	);
}

if(isset($_POST['btn_filter'])){
	
	$filter_edit = 1;
	$class ='';	
	if(isset($_POST['filter_product_code'])){
		$filter_product_code=$_POST['filter_product_code'];		
	}else{
		$filter_product_code='';
	}if(isset($_POST['pro_in_no'])){
		$pro_in_no=$_POST['pro_in_no'];		
	}else{
		$pro_in_no='';
	}
	
	if(isset($_POST['ref_order_no'])){
		$filter_order=$_POST['ref_order_no'];		
	}else{
		$filter_order='';
	}
	if(isset($_POST['cust_email'])){
		$filter_email=$_POST['cust_email'];		
	}else{
		$filter_email ='';
	}
	if(isset($_POST['cust_nm'])){
		$filter_name=$_POST['cust_nm'];		
	}else{
		$filter_name='';
	}
	if(isset($_POST['filter_user_name']))
	{
		$filter_user_name = $_POST['filter_user_name'];
	}else{
		$filter_user_name='';
	}	
	$filter_data=array(
		'ref_order_no' => $filter_order,
		'cust_nm' => $filter_name,
		'cust_email' => $filter_email,
		'pro_in_no' => $pro_in_no,
		'product_code' => $filter_product_code,
		'postedby' =>$filter_user_name		
	);
	//
	
	$obj_session->data['filter_data'] = $filter_data;
}
//printr($_GET['filter_edit']);
if(isset($_GET['sort'])){
	if($_GET['sort']=='ref_order_no')
	    $sort_name = 'CAST('.$_GET['sort'].' AS decimal)';
	else
	    $sort_name = $_GET['sort'];
}else{
	$sort_name='p.packing_order_id';
}
if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'DESC';	
}


if($display_status) {


//active inactive delete
if(isset($_POST['action']) && ($_POST['action'] == "active" || $_POST['action'] == "inactive") && isset($_POST['post']) && !empty($_POST['post']))
{
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	} else {
		$status = 0;
		if($_POST['action'] == "active"){
			$status = 1;
		}
		$obj_source->updateStatus($status,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
}else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
	if(!$obj_general->hasPermission('delete',$menuId)){
		$display_status = false;
	} else {
		//printr($_POST['post']);die;
		$obj_source->updateStatus(2,$_POST['post']);
		$obj_session->data['success'] = DELETE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
}


if(isset($_POST['btn_payment']))
{
		$post = post($_POST);	
	//	printr($post);
		$update_id = $obj_source->InsertPayment_detail($post,ADMIN_EMAIL);	
		//$obj_session->data['success'] = ADD;
	    //page_redirect($obj_general->link($rout, 'mod=index&is_delete=0'.$add_url, '', 1));
		
}
$gen_pro_as=0;
if(isset($_GET['gen_pro_as'])&& $_GET['gen_pro_as']==2)		
	$gen_pro_as = 2;
else if(isset($_GET['gen_pro_as'])&& $_GET['gen_pro_as']==1)
	$gen_pro_as = 1;

////////$check = $obj_pro_invoice->gen_sales_invoice('14673');
//printr($check);
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
          			
                <a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>"><i class="fa fa-plus"></i> New Payment </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			         <?php if($_SESSION['ADMIN_LOGIN_SWISS']=='10' && $_SESSION['LOGIN_USER_TYPE']=='4'){?>
						<a class="label bg-inverse" href="<?php echo $obj_general->link($rout, 'mod=import', '',1);?>" > <i class="fa fa-print"></i> CSV Import</a>
					 <?php } ?>
				 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a class="label bg-info" href="javascript:void(0);" onclick="csvlink('post[]')"> <i class="fa fa-print"></i> CSV Export</a>
				<a class="label bg-danger" onclick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>
                                       
                    
            </span>
           
          </header>
		  
          <div class="panel-body">
              <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link($rout, '&gen_pro_as='.$gen_pro_as, '',1); ?>">
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
                                <label class="col-lg-3 control-label">Refrence No</label>
                                <div class="col-lg-8">
                                  <input type="text" name="ref_order_no" value="<?php echo isset($filter_order) ? $filter_order : '' ; ?>" placeholder="Refrence No" id="input-name" class="form-control" />
                                </div>
                              </div>                             
                          </div>
                         <div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-3 control-label">Customer Name</label>
                                <div class="col-lg-8">
                                  <input type="text" name="cust_nm" value="<?php echo isset($filter_name) ? $filter_name : '' ; ?>" placeholder="Customer Name" id="input-name" class="form-control" />
                                </div>
                              </div>                             
                          </div>
                       
				<div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-3 control-label">Email</label>
                                <div class="col-lg-8">
                                  <input type="text" name="cust_email" value="<?php echo isset($filter_email) ? $filter_email : '' ; ?>" placeholder="Email" id="input-name" class="form-control" />
                                </div>
                              </div>                             
                          </div>		
						
                          	  
                 </div>
				<div class="row">
                          <div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-3 control-label">Product Code</label>
                                <div class="col-lg-8">
                                  <input type="text" name="filter_product_code" value="<?php echo isset($filter_product_code) ? $filter_product_code : '' ; ?>" placeholder="Product Code" id="input-name" class="form-control" />
                                </div>
                              </div>                           
                          </div>
                          <div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-3 control-label">Posted By User</label>
                                <?php
                                $splitdata=array();
            			  		if(isset($filter_user_name))
            					{
            			   			$splitdata=explode("=",$filter_user_name);
            			  		 	//printr($splitdata[0]);
            					 }
									$userlist = $obj_source->getUserList();
								?>
                                <div class="col-lg-8">
                                	<select class="form-control" name="filter_user_name">
                                    	<option value="">Please Select</option>
                                    	<?php foreach($userlist as $user) { ?>
                                        	<?php  if(isset($splitdata[0]) && $splitdata[0] == $user['user_type_id'] && $splitdata[1] ==$user['user_id']) { ?>
                                            
                                    			<option value="<?php echo $user['user_type_id']."=".$user['user_id']; ?>" selected="selected"><?php echo $user['user_name']; ?></option>
                                            <?php } else { ?>
                                            	<option value="<?php echo $user['user_type_id']."=".$user['user_id']; ?>"><?php echo $user['user_name']; ?></option>
                                            <?php } ?>
                                        <?php } ?>                                       
                                    </select>
                                </div>
                       
                              </div>                              
                          </div>
                          <div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-3 control-label">Proforma Inv. No.</label>
                                <div class="col-lg-8">
                                  <input type="text" name="pro_in_no" value="<?php echo isset($pro_in_no) ? $pro_in_no : '' ; ?>" placeholder="Proforma Invoice no" id="Proforma Invoice no" class="form-control" />
                                </div>
                              </div>                             
                          </div>
                 </div>
                 	
				
                  <footer class="panel-footer <?php echo $class; ?>">
                    <div class="row">
                       <div class="col-lg-12">
                        <input type="hidden" value="<?php echo $gen_pro_as;?>" id="gen_pro_as" name="gen_pro_as" />
                        <button type="submit" class="btn btn-primary btn-sm pull-right ml5" name="btn_filter"><i class="fa fa-search"></i> Search</button>
                        <a href="<?php echo $obj_general->link($rout, '&gen_pro_as='.$gen_pro_as, '',1); ?>" class="btn btn-info btn-sm pull-right" ><i class="fa fa-refresh"></i> Refresh</a>
                       </div> 
                    </div>
                  </footer>                                  
              </section>
         	</form>
         	
              <!--<form class="form-horizontal" method="post" data-validate="parsley" action="<?php //echo $obj_general->link($rout, 'mod=index', '',1); ?>">
                   <div class=" pull-left">
                   	 	<div class="panel-body text-muted l-h-2x">
                         <button  type="submit" class="btn btn-primary btn-sm pull-right ml5" name="swisspac" style="background-color:#81C267"><i></i>Swiss Pac Invoices</button>
                         <button  type="submit" class="btn btn-primary btn-sm pull-right ml5" name="clifton" style="background-color:#CBC6AB"><i ></i>Clifton Invoices</button> 
                        </div>
                   </div>
               </form>-->
               
              <div class="row">
             <div class="col-lg-3 pull-right">	
                 <select class="form-control" id="limit-dropdown" onchange="location=this.value;">
                 <option value="<?php echo $obj_general->link($rout, '', '',1);?>" selected="selected">--Select--</option>
                    	<?php 
							$limit_array = getLimit(); 
							foreach($limit_array as $display_limit) {
								if($limit == $display_limit) {	 
            						?>
                                    		<option value="<?php echo $obj_general->link($rout, 'limit='.$display_limit.'&gen_pro_as='.$gen_pro_as, '',1);?>" selected="selected"><?php echo $display_limit; ?></option>				
    						<?php } else { ?>
                                	<option value="<?php echo $obj_general->link($rout, 'limit='.$display_limit.'&gen_pro_as='.$gen_pro_as, '',1);?>"><?php echo $display_limit; ?></option>
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
                <table class="table b-t text-small table-hover">
                  <thead>
                    <tr>
                      <th width="20"><input type="checkbox"></th>
					 
                      <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">Order No  <br> Proforma Number&nbsp;/Refrence Order No&nbsp;
                            <span class="th-sort">
                        		<a href="<?php echo $obj_general->link($rout, 'sortsort=order_no'.'&order=ASC', '',1);?>">
                            	<i class="fa fa-sort-down text"></i>
                                
                                <a href="<?php echo $obj_general->link($rout, 'sort=order_no'.'&order=DESC', '',1);?>">
                                <i class="fa fa-sort-up text-active"></i>
                        	<i class="fa fa-sort"></i></span>
                    </th>
                     
                      <th>Customer Detail<br><small class="text-muted">Email</small><br><small class="text-muted">RFC No.</small><br>Posted By</th>
                      <th>Description /Normal Rate / Express Rate / Qty /Pedimento Mexico</th>
                      <th>Total Price<br><small class="text-muted">Shipping Cost</small></th>
                      <th>Invoice Info</th>
                      <th>Delivery & Contact Details</th>
					  <th>Dispatched Date / Courier /Tracking Details</th>
					  <th>Action</th>
                     
                    </tr>
                  </thead>
                  <tbody>
                 
                 <?php
                  $pagination_data = ''; //echo $gen_pro_as;
				  $order_total = $obj_source->getTotal_Packing_Order($filter_data,$gen_pro_as);
			  
                  if(!empty($order_total)){
                        if (isset($_GET['page'])) {
                            $page = (int)$_GET['page'];
                        } else {
                            $page = 1;
                        }
                      //oprion use for limit or and sorting function	
                      $option = array(
                             'sort'  => $sort_name,
                            'order' => $sort_order,
                            'start' => ($page - 1) * $limit,
                            'limit' => $limit,
                            'gen_pro_as' => $gen_pro_as
                      );	
					  $packing_order_for = $obj_source->get_packing_order_detail($filter_data,$option,$gen_pro_as);
					//printr($packing_order_for);
					  foreach($packing_order_for as $packing_order){
						  $proforma=$obj_source->getProformaInvoiceId($packing_order['packing_order_id']);
						  $currency=$obj_source->getcurrencyName($proforma['currency_id']);
						 
                        ?>
                        <tr>
                           <td><input type="checkbox" name="post[]" value="<?php echo $packing_order['packing_order_id'];?>"></td>
                          <td><?php echo  $packing_order['order_no'] .'<br>'.$packing_order['pro_in_no'];?><hr>
                                <?php echo $packing_order['ref_order_no'];?><br><small>
                              <?php
                                if($packing_order['order_date']!='0000-00-00')
                                    echo   dateformat('4',$packing_order['order_date']);?></small></td>
                        <td ><?php echo $packing_order['cust_nm'];?><br><small class="text-muted"><?php echo $packing_order['email'];?></small><br><small class="text-muted"><?php echo $packing_order['rfc_no'];?></small>
                        <hr>	<?php	$userInfo = $obj_source->getUser($packing_order['user_id'],$packing_order['user_type_id']);							
								//printr($packing_order['payment_status']);
								$addedByImage = $obj_general->getUserProfileImage($packing_order['user_id'],$packing_order['user_type_id'],'100_');
								$addedByInfo = '';
								$addedByInfo .= '<div class="row">';
									$addedByInfo .= '<div class="col-lg-3"><img src="'.$addedByImage.'"></div>';
									$addedByInfo .= '<div class="col-lg-9">';
									if($userInfo['city']){ $addedByInfo .= $userInfo['city'].', '; }
									if($userInfo['state']){ $addedByInfo .= $userInfo['state'].' '; }
									if(isset($userInfo['postcode'])){ $addedByInfo .= $userInfo['postcode']; }
									$addedByInfo .= '<br>Telephone : '.$userInfo['telephone'].'</div>';
								$addedByInfo .= '</div>';
								$addedByName = $userInfo['first_name'].' '.$userInfo['last_name'];
								str_replace("'","\'",$addedByName);
							?>
								<a class="btn btn-info btn-xs" data-trigger="hover" data-toggle="popover" data-html="true" data-placement="top" data-content='<?php echo $addedByInfo;?>' title="" data-original-title="<b><?php echo $addedByName;?></b>"><?php echo $userInfo['user_name'];?></a>
                          
						
                        </td>
                          <td style="width: 30%;">
                             
							<table>
								<?php $packing_pro = $obj_source->getProformaInvoice( $packing_order['packing_order_id']);
								 //  printr($packing_order);
										if(isset($packing_pro) & !empty($packing_pro))
										{
											foreach($packing_pro as $pack)
											{
												
												?>
												<tr>
													<td><?php echo '<b>'.$pack['product_code'].'</b><br>'.$pack['pro_dec'];?></td>
													<td><?php echo $pack['rate'];?></td>
													<td><?php echo $pack['express_rate'];?></td>
													<td><?php echo $pack['quantity'];?></td>
													<td><?php echo $pack['pedimento_mexico'];?></td>
												<tr>
											<?php }
										}?>
							</table>
						  
						  </td>
						  <td><?php echo $currency['currency_code'].' '.$packing_order['payment_amount'];?><br><small class="text-muted"><?php echo $packing_order['freight_charges'];?></small></td>
						  <td style="width: 2%;"><?php echo nl2br($packing_order['billing_order_address']);?></td>
						   <td style="width: 20%;"><?php echo nl2br($packing_order['delivery_address']);?>
						    <?php 
						        $country = $obj_pro_invoice->getCountry($packing_order['destination']);
						        if($packing_order['destination']!='0') { echo '<br><b> Destination : </b>'.$country['country_name'];}?>
						    </td>
						    <td><?php 
						        if($packing_order['dispatched_date']!='0000-00-00')
						            echo dateformat('4',$packing_order['dispatched_date']);?><hr>
							        <?php echo $packing_order['courier'];?><hr>
							    <?php echo $packing_order['tracking_details'];?></td>
					
						  <td> 
							<a href="<?php echo $obj_general->link($rout, 'mod=add&packing_order_id='.encode($packing_order['packing_order_id']),'',1); ?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
							<?php if($packing_order['pay_status']=='0'){?>
								<a class="btn btn-outline-info btn-sm" href="<?php echo $obj_general->link($rout, 'mod=add_cust_detail&packing_order_id='.encode($packing_order['packing_order_id']),'',1); ?>" >Customer Details </a>
								<?php }?>
								
							<div data-toggle="buttons" class="btn-group">
					   	       
					   	       
					   	       <label class="btn btn-xs <?php if($packing_order['invoiced_status']=='1'){echo "active btn-success";} else if($packing_order['invoiced_status']=='0'){echo " btn-danger";} ?>" onclick="invoiced_status_1(<?php echo $packing_order['packing_order_id']; ?>,<?php echo $packing_order['invoiced_status']; ?>);"><input type="radio" name="status_INVOICE" value="1" > <?php if($packing_order['invoiced_status']=='1'){echo"<i class='fa fa-check'>";} else if($packing_order['invoiced_status']=='0'){ echo"<i class='fa fa-times'>";} ?></i>    Invoiced</label> 
                                     
                                	
                            </div>	
                    <?php 
                            
                             $check_sales_qty = $obj_pro_invoice->checkSalesQty($proforma['proforma_id'], $proforma['added_by_user_type_id'], $proforma['added_by_user_id'], $proforma['pro_in_no']);
        
                            
                           if(($packing_order['sales_status']=='0' && empty($check_sales_qty)) || (empty($packing_pro) && $packing_order['sales_status']=='0')) {
                           if ($packing_order['dispatched_date']!='' && $packing_order['tracking_details']!=''){?>
                                 <a class="btn btn-sm" style="background-color:#0D63B9"  onclick="generate_sales_inv(<?php echo $packing_order['packing_order_id']; ?>)" >Generate Invoice</a>
                            <?php } }
                                elseif($packing_order['sales_status']=='1'){
                                ?>
                                    
									
								<?php
								}
                            else{
                              ?>
                                <a class="btn btn-primary btn-sm" onclick="check_stock_qty(<?php echo $proforma['proforma_id']; ?>, '<?php echo $proforma['pro_in_no']; ?>',<?php echo $proforma['added_by_user_type_id']; ?>,<?php echo $proforma['added_by_user_id']; ?>)">Check Stock</a>
        
                            <?php }?>
						</td>	
						
						   
                        </tr>
                        <?php
                      }

                        //pagination
                        $pagination = new Pagination();
                        $pagination->total = $order_total;
                        $pagination->page = $page;
                        $pagination->limit = $limit;
                        $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                        $pagination->url = $obj_general->link($rout, '&page={page}&limit='.$limit.'&filter_edit=1&gen_pro_as='.$gen_pro_as, '',1);
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
 <div class="modal fade" id="form_con" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
   <div class="modal-content">
      <form class="form-horizontal" method="post" name="form" id="conform_form" style="margin-bottom:0px;">
          <div class="modal-header title">
                <h4 class="modal-title" id="myModalLabel"><span id="pro"></span></h4>
              </div>
            <div class="modal-body">
              <input name="packing_order_id" id="packing_order_id" value=""  type="hidden"/>
                <h4 class="streamlined_title"> Sure !!! <br /><br />
                            Do you want to generate Sales Invoice ?</h4>
            </div> 
             <div class="modal-footer">
                <button type="button" name="btn_submit1" class="btn btn-primary" onclick="generate_sales()">Yes</button>
                 <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
              </div>
        </form>
     </div>
    </div>
</div>
<div class="modal fade" id="form_con1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:46%;">
    <div class="modal-content">
   	 <form class="form-horizontal" method="post" name="form" id="ckeck_stock" style="margin-bottom:0px;">
              <div class="modal-header">
                   	 	<h4 class="dispatch" id="myModalLabel">Stock Details For <!--kavita 10-4-2017--><span id="pr_no" style=""></span><!--END--></h4>
                  	<!-- <input type="hidden" name="product_code_id" id="product_code_id" value=""  />-->
              </div>
               <div class="modal-body">
               <input name="stock_detail_id" id="stock_detail_id" value=""  type="hidden"/>             
                    <div class="table-responsive">                      
                       	
        			<table class="table table-striped m-b-none text-small">
        				<thead>
        					<tr>
        					<th>Product Code</th>
        					<th>Proforma Qty</th>
        					<th>Stock Qty</th>
        					</tr>
        				</thead>
                       <tbody id="stock_data">
        
                       </tbody>
                        </table>
                </div>
             </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
               
              </div>
   		</form>   
    </div>
  </div>
</div>
<script type="application/javascript">
function csvlink(elemName){
	//alert(elemName);
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
		
			
		var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=csvPacking', '',1);?>");
			var formData = $("#form_list").serialize();	
		 $.ajax({
			url: url, // the url of the php file that will generate the excel file
			data : {formData : formData},
			method : 'post',
			success: function(response){
				excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(response);
				 $('<a></a>').attr({
								'id':'downloadFile',
								'download': 'packing order.xls',
								'href': excelData,
								'target': '_blank'
						}).appendTo('body');
						$('#downloadFile').ready(function() {
							$('#downloadFile').get(0).click();
						});
			}
			
    });
	}
	else
	{
		//alert("Please select atlease one record");
		$(".modal-title").html("WARNING");
		$("#setmsg").html('Please select atlease one record');
		$("#popbtnok").hide();
		$("#myModal").modal("show");
	}
	
}
function invoiced_status_1(packing_order_id,invoiced_status)
{
    //alert(packing_order_id);
	var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=invoiced_status_update', '',1);?>");
        $.ajax({			
			url : status_url,
			type :'post',
			data :{packing_order_id:packing_order_id,invoiced_status:invoiced_status},
			success: function(){
			
			    location.reload();
			}
		});
}
function generate_sales_inv(packing_order_id)
  {
    $(".note-error").remove();
    $("#packing_order_id").val(packing_order_id);
    $("#form_con").modal("show");
  }
  function generate_sales()
  {
    $("#form_con").modal("hide");
    var packing_order_id = $("#packing_order_id").val();
    var gen_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=gen_sales', '',1);?>");
    $.ajax({      
      url : gen_url,
      type :'post',
      data :{packing_order_id:packing_order_id},
      success: function(response){
        //alert(response);
          //window.location.href='<?php echo HTTP_SERVER; ?>/admin/index.php?route=sales_invoice&mod=add&invoice_no='+response+'&is_delete=0';
          window.location.href='<?php echo HTTP_SERVER; ?>/admin/index.php?route=proforma_invoice_product_code_wise&mod=dis_stock&invoice_no='+response+'&is_delete=0';
        
      },
      
            
    });
  }
  function check_stock_qty(proforma_id,pr_no,user_type_id,user_id)
  {
    
    $("#form_con1").modal('show');
    <!--kavita 10-4-2017-->
    $("#pr_no").html(pr_no);
    <!--END kavita-->
    var stk_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=checkStock', '',1);?>");
    $.ajax({      
      url : stk_url,
      type :'post',
      data :{proforma_id:proforma_id,pr_no:pr_no,user_type_id:user_type_id,user_id:user_id},
      success: function(response){
       
          $('#stock_data').html(response);
        
      },
      
            
    });
  }
</script>           
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>
