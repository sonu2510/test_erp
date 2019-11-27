<?php
include("mode_setting.php");

//Start : bradcums
$bradcums = array();
$bradcums[] = array(
	'text' 	=> 'Dashboard',
	'href' 	=> $obj_general->link('dashboard', '', '',1),
	'icon' 	=> 'fa-home',
	'class'	=> '',
);
$mod='';
if(isset($_GET['p']))
	$mod ='&mod=person';
$bradcums[] = array(
	'text' 	=> $display_name.' List',
	'href' 	=> $obj_general->link($rout, $mod, '',1),
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

$limit = LISTING_LIMIT;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}

$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];

//Start : edit
$edit = '';

//Close : edit
if($display_status){
if(isset($_POST['btn_search'])){
		$post = post($_POST);
		$arr=explode('=',$post['filter_module']);

     $menu_id=$arr[1]; 

/*	<option value="1">Proforma Invoice No</option> 
    <option value="2">Sales Invoice No</option> 
    <option value="3">Buyers Order No</option> 
    <option value="4">Amount</option> 
    <option value="5">Custom Order Number</option> 
    <option value="6">Stock Order Number</option> 
    <option value="7">Digital Order Number</option> 
    <option value="8">Multi Quotation Number</option> 
    <option value="9">Digital Quotation Number</option> 
    <option value="10">Enquiry Number or Customer Name</option> */
          if($obj_general->hasPermission('view',$menu_id )){
               //printr($post);
              	if($arr[0]=='1' || $arr[0]=='3' ){
                  $n=0;

                  $title='Proforma Invoice Details';
                  if($arr[0]=='3'){
                     $title='Buyers Order Details';
                    $n=1;
                  }
              	   	$data=$obj_dashboard->getProformaDataForSearch($post['fillter_data'],$n);
              	}else if($arr[0]=='2'){
                  $title='Sales Invoice Details';
                    $data=$obj_dashboard->getSalesDataForSearch($post['fillter_data']);
                }else if($arr[0]=='4'){
                  $title='Proforma Payment Details';
                    $data=$obj_dashboard->getPaymentDataForSearch($post['fillter_data']);
                }else if($arr[0]=='5'){                  

                    $title='Custom Order  Details';
                    $data=$obj_dashboard->getCustomOrderDataForSearch($post['fillter_data']);
                }else if($arr[0]=='6'){                  
  
                    $title='Stock Order  Details';
                    $data=$obj_dashboard->getStockOrderDataForSearch($post['fillter_data']);
                }
                else if($arr[0]=='7'){                  

                    $title='Digital Order  Details';
                    $data=$obj_dashboard->getDigitalOrderDataForSearch($post['fillter_data']);
                }  else if($arr[0]=='8'){                  

                    $title='Multi Quotation  Details';
                    $data=$obj_dashboard->getMultiQuotationDataForSearch($post['fillter_data']);
                }else if($arr[0]=='9'){                  

                    $title='Digital Quotation  Details';
                    $data=$obj_dashboard->getDigitalQuotationDataForSearch($post['fillter_data']);
                }else if($arr[0]=='10'){                  

                    $title='Leads  Details';
                    $data=$obj_dashboard->getLeadsDataForSearch($post['fillter_data']);
                }
          	//	   printr($data);//die;
           //  printr($post);//die;
               
            }
	}


?>

<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-edit"></i> <?php echo $title?></h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div> 
      <div class="col-sm-12">
            <section class="panel"> 
                <header class="panel-heading bg-white">
                 <span>Serarch  Detail</span>             
              
                </header>
              
              <div class="panel-body">
              	<label class="label bg-white m-l-mini">&nbsp;</label>
                	
                 <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">  
                  <?php 
      					   if($arr[0]=='1' || $arr[0]=='3' ){
                      //Proforma Invoice No
                    ?>

                      <div class="table-responsive">
                      <table class="table b-t text-small table-hover">
                        <thead>
                          <tr>
                            <th width="20"><input type="checkbox"></th>
                            <th >Proforma Invoice Number
                            <br><small class="text-muted">Proforma Date</small></th>
                            <th>Final Destination</th>
                            <th>Customer Name <br>
                            <small class="text-muted">Email</small></th>                      
                            <th>Posted By</th>                                       
                            <th>Action</th>  
                            <th></th>
                            <th></th>                          
                          </tr>
                        </thead>
                        <tbody><?php if(!empty($data)){
                          foreach ($data as $proforma) {
                          //  printr($proforma);
                              $proforma_user = $obj_pro_invoice->getUser($proforma['added_by_user_id'],$proforma['added_by_user_type_id']);
                              $currency = $obj_pro_invoice->getCurrencyId($proforma['currency_id']);          
                              $userInfo = $obj_pro_invoice->getUser($proforma['added_by_user_id'], $proforma['added_by_user_type_id']);
                              $edit_link = $obj_general->link('proforma_invoice_product_code_wise', 'mod=add&proforma_id='.encode($proforma['proforma_id']).'&is_delete=0','',1);
                                $view_link = $obj_general->link('proforma_invoice_product_code_wise', 'mod=view&proforma_id='.encode($proforma['proforma_id']).'&is_delete=0','',1);
                            ?>
                            <tr>
                           <td></td>
                            <td><a href="<?php echo $view_link; ?>" target="_blank"><?php echo $proforma['pro_in_no'].'<br>'.dateFormat(4, $proforma['invoice_date']);;?></a></td>                           
                             <td><a href="<?php echo $view_link; ?>" target="_blank"><?php echo $proforma['country_name']; ?><?php if($proforma['customer_dispatch']=='1'){ echo '<br><b>dispatch order directly to customer</b>';} echo '<br><b>Buyers order no : </b>'.$proforma['buyers_order_no'];?></a></td>
                           <td><a href="<?php echo $view_link; ?>"  target="_blank"><?php echo $proforma['customer_name']; ?>
                                <br><small class="text-muted"><?php echo $proforma['email']; ?></small>
                                <br><small class="text-muted">Contact No:

                                 <?php if($proforma['contact_no']!='0' && $proforma['contact_no']!='') echo $proforma['contact_no']; ?></small>
                         </a></td>
                            <td><a class="btn btn-info btn-xs" ><?php echo $userInfo['user_name']; ?></a></td>
                            <td> <a href="<?php echo $edit_link; ?>"    target="_blank" name="btn_edit" class="btn btn-info btn-xs">Edit</a><br></a></td>
                           <td><?php  if($proforma['payment_status']!=1){?>
                              <a href="<?php echo $obj_general->link('proforma_invoice_product_code_wise', 'mod=add_payment&proforma_id=' . encode($proforma['proforma_id']) . '&is_delete=0' , '', 1); ?>"  target="_blank" name="btn_edit" class="btn btn-outline-info btn-sm"> Add Payment </a>                              
                                <?php } ?>
                               <a href="<?php echo $obj_general->link('proforma_invoice_product_code_wise', 'mod=customer_payment&proforma_id=' . encode($proforma['proforma_id']) . '&is_delete=0', '', 1); ?>"  target="_blank" name="btn_edit" class="btn btn-info btn-xs">View Payments</a>
                                <?php //}?>
                                          </td>
                                  </tr>


                          <?php }
                          

                        }?>
                    
                        </tbody>


				               </table>
                       </div>  
                  <?php          

                      }

            else if($arr[0]=='2'){?>
         <div class="table-responsive">
                      <table class="table b-t text-small table-hover">
                       <thead>
                        <tr>
                          <th width="20"><input type="checkbox"></th> 
                              <?php //mansi 20-1-2016 (change for shorting on index page) ?>                    
                           <th>Sales Invoice No </th>
                           <th> Proforma No </th>                                
                           <th >Customer Name </th>                              
                           <th >Customer Order No</th>                          
                           <th >Total </th>
                           <th>Email</th>
                           <th>Final Destination</th>                          
                           <th>Posted By</th>
                      
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tbody><?php if(!empty($data)){
                          foreach ($data as $invoice) {
                          $total_amount = $obj_sales_invoice->gettotalWithoutCyli($invoice['invoice_id']);
                             $addedByData = $obj_sales_invoice->getUser($invoice['user_id'],$invoice['user_type_id']);
                            ?>
                          <tr>
                           <td></td>

                            <td><a href="<?php echo $obj_general->link($rout, 'mod=view&invoice_no='.encode($invoice['invoice_id']).'&status=1&is_delete=0','',1); ?> "   target="_blank"> <?php echo $invoice['invoice_no'];
                                      if(isset($invoice['reorder_date']) && $invoice['reorder_date']!='0000-00-00'){
                                      echo "<br><small style='color:red;'>"."Reorder Date : ".dateFormat(4,$invoice['reorder_date'])."</small>";}?>
                                       <br /><small class="text-muted"><b>Amount With Out Cylinder and Shipping charges : </b>[ <?php echo $total_amount;?> ]</small>
                                       <br /><small class="text-muted"><b>Final Amount : </b>[ <?php echo $invoice['amount_paid'];?> ]</small>
                                        </a>
                          </td>
                            <td>    <a href="<?php echo $obj_general->link('sales_invoice', 'mod=view&invoice_no='.encode($invoice['invoice_id']).'&status=1&is_delete=0','',1); ?> "   target="_blank"> <?php echo $invoice['proforma_no']; ?><?php if($invoice['customer_dispatch']=='1'){ echo '<br><b>dispatch order directly to customer</b>';} ?>
                              </a>
                            </td>
                            <td><a href="<?php echo $obj_general->link('sales_invoice', 'mod=view&invoice_no='.encode($invoice['invoice_id']).'&status=1&is_delete=0','',1); ?> "   target="_blank"><?php echo stripslashes($invoice['customer_name']); ?><br /><small class="text-muted">[ <?php echo dateFormat(4,$invoice['invoice_date']);?> ]</small></a>
                            </td>
                                   
                           <td><a href="<?php echo $obj_general->link('sales_invoice', 'mod=view&invoice_no='.encode($invoice['invoice_id']).'&status=1&is_delete=0','',1); ?> "   target="_blank"> <?php echo $invoice['exporter_orderno']; ?>
                                <br /><small class="text-muted"><b>Buyer's Order/Ref No : </b> <?php echo $invoice['buyers_orderno'];?> </small></a>
                           </td>                                    
                                  
                           <td><a href="<?php echo $obj_general->link('sales_invoice', 'mod=view&invoice_no='.encode($invoice['invoice_id']).'&status=1&is_delete=0','',1); ?> "   target="_blank"><?php echo $invoice['final_total']; ?></a></td>
                           <td><a href="<?php echo $obj_general->link('sales_invoice', 'mod=view&invoice_no='.encode($invoice['invoice_id']).'&status=1&is_delete=0','',1); ?> "   target="_blank"> <?php echo $invoice['email']; ?></a></td>
                                      
                           <td><a href="<?php echo $obj_general->link('sales_invoice', 'mod=view&invoice_no='.encode($invoice['invoice_id']).'&status=1&is_delete=0','',1); ?> "   target="_blank"><?php echo $invoice['country_name']; ?></a></td>
                                   
                         <td><?php $addedByData = $obj_sales_invoice->getUser($invoice['user_id'],$invoice['user_type_id']); ?>              
                                     <a class="btn btn-info btn-xs"><?php echo $addedByData['user_name'];?></a>      
                         </td>                           
                          
                            <td>    <a href="<?php echo $obj_general->link('sales_invoice', 'mod=add&invoice_no='.encode($invoice['invoice_id']).'&is_delete=0','',1); ?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>

                              <?php  $PI_id = $obj_sales_invoice->getPIid($invoice['proforma_no']);  ?>
                                <a href="<?php echo $obj_general->link('proforma_invoice_product_code_wise', 'mod=customer_payment&proforma_id=' . encode($PI_id) . '&is_delete=0', '', 1); ?>"  target="_blank" name="btn_edit" class="label m-l-mini" style="background-color: #ca829df7;">View Payments</a>
                             <a class="label bg-info " onclick="salespdfcls('<?php echo encode($invoice['invoice_id']);?>')" href="javascript:void(0);"><i class="fa fa-print"></i> PDF</a>

                            </td>
                          </tr>
                          <?php }
                          

                        }?>
                    
                        </tbody>


                       </table>
                       </div>  
              <?php   }
               else if($arr[0]=='5' ){?>
                <div class="table-responsive">
                  <table class="table b-t text-small table-hover">
                    <thead>
                    <tr>
                      <th width="20"><input type="checkbox" ></th>
                               
                      <th> Order No.   </th>            
                      <th>Reference No.</th> 
                      <th>Customer Name</th>
                      <th>Company Name</th>
                       <th> Product </th> 
                      <th>Posted By</th>
                      <th>Order Status</th>
                      <th></th>
                    </tr>
                  </thead>
                      <tbody>
                        <tbody><?php if(!empty($data)){
                          foreach ($data as $cust_order) {
                                $multi_quation_id = $obj_custom_order->getmulti_quation_id($cust_order['multi_product_quotation_id']);
                                 $postedByData = $obj_custom_order->getUser($cust_order['added_by_user_id'],$cust_order['added_by_user_type_id']);

                            ?>
                            <tr>
                 <td> <a href="<?php echo $obj_general->link('custom_order', '&mod=view&custom_order_id='.encode($cust_order['multi_custom_order_id']), '',1);?>" target="_blank"><input type="checkbox" name="post[]" value="<?php echo $cust_order['multi_custom_order_id'];?>"></a></td> 
               
                <td> <a href="<?php echo $obj_general->link('custom_order', '&mod=view&custom_order_id='.encode($cust_order['multi_custom_order_id']), '',1);?>" target="_blank"><?php echo $cust_order['multi_custom_order_number'];?>                              
                     <br /><small class="text-muted"><?php echo dateFormat(4,$cust_order['date_added']);?></small>
                      <br /><small class="text-muted"><b>Quo.no - [<?php echo $multi_quation_id['multi_quotation_number'];?>]</b></small>                               
               </a> </td>      
                 <td> <a href="<?php echo $obj_general->link('custom_order', '&mod=view&custom_order_id='.encode($cust_order['multi_custom_order_id']), '',1);?>" target="_blank"><?php echo $cust_order['reference_no'];?></a></td>                       
                
               <td>   <a href="<?php echo $obj_general->link('custom_order', '&mod=view&custom_order_id='.encode($cust_order['multi_custom_order_id']), '',1);?>" target="_blank"><?php echo $cust_order['customer_name'];?><br/>
                    <small class="text-muted"><?php echo $cust_order['country_name']; ?></small>
                     <br /><small class="text-muted"><b>Reference No :- </b><?php echo $cust_order['reference_no'];?></small>
                            
                </a> </td>
                 <td>  <a href="<?php echo $obj_general->link('custom_order', '&mod=view&custom_order_id='.encode($cust_order['multi_custom_order_id']), '',1);?>" target="_blank"><?php echo $cust_order['company_name'];?><br/>
                       <small class="text-muted"><?php echo $cust_order['email']; ?></small>                             
                </a></td>
                <td> <a href="<?php echo $obj_general->link('custom_order', '&mod=view&custom_order_id='.encode($cust_order['multi_custom_order_id']), '',1);?>" target="_blank"><?php echo $cust_order['product_name'];?><br />
                <small class="text-muted"><?php echo $cust_order['layer'].' Layer';?><span style="color:blue"> <?php echo ' '.'['.$cust_order['zipper_txt'].' '.
                $cust_order['valve_txt'].' '.$cust_order['spout_txt'].' '.$cust_order['accessorie_txt'].']';?></span></small><br />
                <b>Transportation By: </b><?php echo $cust_order['transportation'];?>
                   </a>             
                               
                </td>
                <td>    <a class="btn btn-info btn-xs"><?php echo $postedByData['user_name'];?></a></td>
                <td>
                  <?php if($cust_order['accept_decline_status']=='1') 
                                            echo '<a class=" btn-success btn-sm">Accepted Order</a>';
                        elseif($cust_order['accept_decline_status']=='2')
                                            echo '<a class=" btn-danger btn-sm" >Declined Order</a>';
                        elseif($cust_order['accept_decline_status']=='3')
                                 echo '<a class=" bg-primary btn-sm"  onclick=dispatch_order_detail('.$cust_order['multi_custom_order_id'].')>Dispatched Order</a>';
                                 ?>
                    </td>
                  </tr>
                         
                         <?php }}?>
                    
                       



                        </tbody>
                       </table>
                       </div> 





               <?php    }else if($arr[0]=='7' ){?>
                <div class="table-responsive">
                  <table class="table b-t text-small table-hover">
                    <thead>
                    <tr>
                      <th width="20"><input type="checkbox" ></th>
                               
                      <th> Order No.   </th>            
                      <th>Reference No.</th> 
                      <th>Customer Name</th>
                      <th>Company Name</th>
                       <th> Product </th> 
                      <th>Posted By</th>
                      <th>Order Status</th>
                      <th></th>
                    </tr>
                  </thead>
                      <tbody>
                        <tbody><?php if(!empty($data)){
                          foreach ($data as $cust_order) {
                                $multi_quation_id = $obj_custom_order->getmulti_quation_id($cust_order['multi_product_quotation_id']);
                                 $postedByData = $obj_custom_order->getUser($cust_order['added_by_user_id'],$cust_order['added_by_user_type_id']);

                            ?>
                            <tr>
                 <td><input type="checkbox" name="post[]" value="<?php echo $cust_order['multi_custom_order_id'];?>"></td> 
               
                <td><a href="<?php echo $obj_general->link('digital_custom_order', '&mod=view&custom_order_id='.encode($cust_order['multi_custom_order_id']), '',1);?>" target="_blank"><?php echo $cust_order['multi_custom_order_number'];?>                              
                     <br /><small class="text-muted"><?php echo dateFormat(4,$cust_order['date_added']);?></small>
                      <br /><small class="text-muted"><b>Quo.no - [<?php echo $multi_quation_id['multi_quotation_number'];?>]</b></small>                               
               </a> </td>      
                 <td><a href="<?php echo $obj_general->link('digital_custom_order', '&mod=view&custom_order_id='.encode($cust_order['multi_custom_order_id']), '',1);?>" target="_blank"><?php echo $cust_order['reference_no'];?></a></td>                       
                
               <td> <a href="<?php echo $obj_general->link('digital_custom_order', '&mod=view&custom_order_id='.encode($cust_order['multi_custom_order_id']), '',1);?>" target="_blank"> <?php echo $cust_order['customer_name'];?><br/>
                    <small class="text-muted"><?php echo $cust_order['country_name']; ?></small>
                     <br /><small class="text-muted"><b>Reference No :- </b><?php echo $cust_order['reference_no'];?></small>
                            
                 </a></td>
                 <td> <a href="<?php echo $obj_general->link('digital_custom_order', '&mod=view&custom_order_id='.encode($cust_order['multi_custom_order_id']), '',1);?>" target="_blank"><?php echo $cust_order['company_name'];?><br/>
                       <small class="text-muted"><?php echo $cust_order['email']; ?></small>                             
               </a> </td>
                <td><a href="<?php echo $obj_general->link('digital_custom_order', '&mod=view&custom_order_id='.encode($cust_order['multi_custom_order_id']), '',1);?>" target="_blank"><?php echo $cust_order['product_name'];?><br />
                <small class="text-muted"><?php echo $cust_order['layer'].' Layer';?><span style="color:blue"> <?php echo ' '.'['.$cust_order['zipper_txt'].' '.
                $cust_order['valve_txt'].' '.$cust_order['spout_txt'].' '.$cust_order['accessorie_txt'].']';?></span></small>
                               
               </a> </td>
                <td>    <a class="btn btn-info btn-xs"><?php echo $postedByData['user_name'];?></a></td>
                <td>
                  <?php if($cust_order['accept_decline_status']=='1') 
                                            echo '<a class=" btn-success btn-sm">Accepted Order</a>';
                                        elseif($cust_order['accept_decline_status']=='2')
                                            echo '<a class=" btn-danger btn-sm" >Declined Order</a>';
                    elseif($cust_order['accept_decline_status']=='3')
                                 echo '<a class=" bg-primary btn-sm"  onclick=dispatch_order_detail('.$cust_order['multi_custom_order_id'].')>Dispatched Order</a>';
                                 ?>
                    </td>
                  </tr>
                         
                         <?php }}?>
                    
                       



                        </tbody>
                       </table>
                       </div> 





               <?php    }
               else if($arr[0]=='6' ){?>
                <div class="table-responsive">
                  <table class="table b-t text-small table-hover">
                   <thead>
                    <tr> 
                     <th><input type="checkbox"/></th>  
                     <th>Order No</th>                        
                     <th>Date</th>
                     <th>Client Name</th>
                     <th>Buyers Order No </th>                    
                     <th>Reference No</th> 
                     <th>Order Type </th>
                     <th>Shippment Country</th>
                     <th>Transportation</th> 
                     <th align="center">&nbsp;</th>
                     <th>Posted By</th>
                      <th></th> 
                  </tr>
                  </thead>
                      <tbody>
                        <tbody><?php if(!empty($data)){
                          foreach ($data as $order) {
                                  
                   $postedByData = $obj_template->getUser($order['user_id'],$order['user_type_id']);
                $cust_cond='';
                if(isset($order['custom_order_id']))
                {
                  $cust_cond = '&multi_custom_order_id='.encode($order['stock_order_id']);
                }
                $order_qty = $order['total_qty'];
                $total_price = $order['total_price'];
                if(isset($_GET['status']))
                {
                  if($_GET['status']==3 || $_GET['status']==2)
                  {
                    $order_qty = $order['dis_qty'];
                    $total_price = $order['dis_total_price'];
                  }
                }
                   ?>
                               <td><input type="checkbox" name="post[]" value="<?php echo $order['temp_id'].'=='.$order['product_template_order_id'].'=='.$order['client_id'].'=='.$order['gen_order_id'];?>"/></td>
                               <?php //}
                   // } ?>
                           
                  <td><a href="<?php echo $obj_general->link('template_order_test', 'mod=index&client_id='.encode($order['client_id']).'&stock_order_id='.encode($order['stock_order_id']), '',1);?>" target="_blank"><?php echo $order['gen_order_id'];?></a></td>
                <?php            
             $total = $obj_template->totalCount($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],$order['client_id'],0,$order['stock_order_id'],0);
            //printr($total); 
                 ?>                       
                    <td><a href="<?php echo $obj_general->link('template_order_test', 'mod=index&client_id='.encode($order['client_id']).'&stock_order_id='.encode($order['stock_order_id']), '',1);?>" target="_blank"><?php echo dateFormat(4,$order['date_added']);?></a></td>
                                  
                    <td><a href="<?php echo $obj_general->link('template_order_test', 'mod=index&client_id='.encode($order['client_id']).'&stock_order_id='.encode($order['stock_order_id']), '',1);?>" target="_blank">
                          <?php
                          if(isset($_GET['status']) && $_GET['status']==1)
                          {
                            $noteinfo = '';
                            if(!isset($order['custom_order_id']))
                            {
                              $noteorders = $obj_template->GetOrderList($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],'AND t.status = 1 AND sos.status=1','','','',$order['client_id'],'','','','','',$order['stock_order_id']);
                            //printr($noteorders);
                              
                              //$noteinfo .= '<div>';
                              $noteinfo .= '<div>';
                              foreach($noteorders as $noteorder)
                              {
                                $noteinfo .=$noteorder['note'].'<br />';
                              }
                              $noteinfo .= '</div>';
                          }
                          ?>
                         
                           <span class="" style="font-size: 100%; "><?php echo $order['client_name'];?></span>
                          <?php 
                          }
                          else
                          {
                        
                           echo $order['client_name'];
                          }
                          ?>
                        </a>          
                    </td>                                 
                                
                      <td><a href="<?php echo $obj_general->link('template_order_test', 'mod=index&client_id='.encode($order['client_id']).'&stock_order_id='.encode($order['stock_order_id']), '',1);?>" target="_blank"><?php echo $order['buyers_order_no']; ?></a></td>                           
                      <td><a href="<?php echo $obj_general->link('template_order_test', 'mod=index&client_id='.encode($order['client_id']).'&stock_order_id='.encode($order['stock_order_id']), '',1);?>" target="_blank"><?php echo $order['reference_no']; ?></a></td>                                
                      <td><a href="<?php echo $obj_general->link('template_order_test', 'mod=index&client_id='.encode($order['client_id']).'&stock_order_id='.encode($order['stock_order_id']), '',1);?>" target="_blank"><?php echo $order['order_type']; ?></a></td>
                   <td><a href="<?php echo $obj_general->link('template_order_test', 'mod=index&client_id='.encode($order['client_id']).'&stock_order_id='.encode($order['stock_order_id']), '',1);?>" target="_blank"><?php if($order['ship_type'] == '0')
                         {
                           $ship_type = 'Self';
                         }
                         else
                         {
                           $ship_type = 'Client';
                         }
                        echo $order['country_name'].' / '.$ship_type;?><br/>
                   </a>                
                  </td>
                  <td><a href="<?php echo $obj_general->link('template_order_test', 'mod=index&client_id='.encode($order['client_id']).'&stock_order_id='.encode($order['stock_order_id']), '',1);?>" target="_blank"><?php $air = isset($total['tran']['By Air']) ? $total['tran']['By Air'] : '';
                           $sea = isset($total['tran']['By Sea']) ? $total['tran']['By Sea'] : '';
                         $slash ='';
                         if($air!= '' && $sea!='')
                         {
                          $slash = ' / ';
                         }
                          echo $air.''.$slash.''.$sea;?></a></td>
                <td><?php echo '<table cellpadding="0" cellspacing="0"><tr>
                  <td><b>Total : </b>'.$total['total_count']['total'].'</td><td><b>Accepted : </b>'.$total['total_count']['accepted'].'</td><td><b>Decline : </b>'.$total['total_count']['decline'].'</td><td><b>Dispatch : </b>'.$total['total_count']['dispatch'].'</td><td><b>Pending : </b>'.$total['total_count']['pending'].'</td><td><b>Total  Qty : </b>'.$order_qty.'</td><td><b>Total Price : </b>'.$total_price.' '.$order['currency_code'].'</td></tr></table>';?>
                  </td>
                    <td> 
                                      
                     <span class="label bg-info" style="font-size: 100%; "><?php echo $postedByData['user_name'];?></span>
                    </td>                                         
                </tr>                        
                         <?php }}?>   
                        </tbody>
                       </table>
                       </div> 
           <?php    }  else if($arr[0]=='8' ){?>

                          <div class="table-responsive">
                            <table class="table b-t text-small table-hover">
                                     <thead>
                              <tr>
                                <th width="20"><input type="checkbox" ></th>    
                                 <th> Quotation No.</th>
                                <th>Customer Name</th>                              
                                 <th > Product  </th>
                                <?php if($obj_session->data['LOGIN_USER_TYPE']==1 && $obj_session->data['ADMIN_LOGIN_SWISS']==1) { ?>
                                  <th colspan="2">Action</th>
                                <?php } ?> 
                               
                                <th>Posted By</th>
                                <th></th>
                              </tr>
                           </thead>
                                <tbody>
                                  <tbody><?php if(!empty($data)){
                                    foreach ($data as $quotation) {
                                        $postedByData = $obj_quotation->getUser($quotation['added_by_user_id'],$quotation['added_by_user_type_id']);
                                        $expiredate_cust = $obj_quotation->getexpiredate_custmorder($quotation['added_by_user_id'],$quotation['added_by_user_type_id']);
                                        $exp_date=$expiredate_cust['Multi_Quotation_expiry_days'];
                                        $date_added=strtotime($quotation['date_added']);
                                     
                                        $final_date=date('y-m-d', $date_added);
                                        $fin='';
                                        if($exp_date!='')
                                        {
                                          $fin=date('y-m-d',strtotime($final_date."+ {$exp_date} days"));
                                        }
                                        $today=date('y-m-d'); 

                                      ?>
                                         <tr>
                                            <td><input type="checkbox" name="post[]" value="<?php echo $quotation['multi_product_quotation_id'];?>"></td> 
                                          <td>  <a href="<?php echo $obj_general->link('multi_product_quotation', '&mod=view&quotation_id='.encode($quotation['multi_product_quotation_id']), '',1);?>" target="_blank" ><?php echo $quotation['multi_quotation_number'];?>                       <?php if($quotation['use_device']){ 
                                                    echo '<small class="text-muted">[From '.ucwords($quotation['use_device']).']</small>';
                                               } ?> 
                                                    <br /><small class="text-muted"><?php echo dateFormat(4,$quotation['date_added']);?></small>    
                                            </a>  </td>
                                          <td>   <a href="<?php echo $obj_general->link('multi_product_quotation', '&mod=view&quotation_id='.encode($quotation['multi_product_quotation_id']), '',1);?>" target="_blank" > <?php echo $quotation['customer_name'];?><br/>
                                                 <small class="text-muted"><?php echo $quotation['country_name']; ?></small>
                                          </a>
                                          </td>                              
                                         <td>  <a href="<?php echo $obj_general->link('multi_product_quotation', '&mod=view&quotation_id='.encode($quotation['multi_product_quotation_id']), '',1);?>" target="_blank" ><?php echo $quotation['product_name'];?><br />
                                               <small class="text-muted"><?php echo $quotation['layer'].' Layer';?><span style="color:blue"> <?php echo ' '.'['.$quotation['zipper_txt'].' '. $quotation['valve_txt'].' '.$quotation['spout_txt'].' '.$quotation['accessorie_txt'].']';?></span></small><br />
                                
                            
                                          </a></td>
                        
              
                                       <?php      if($quotation['added_by_user_id']!='1' || $quotation['added_by_user_type_id']!='1') {
                                                     if($fin >= $today) { ?>
                                                              <td>
                                                              <a class="btn btn-primary btn-sm" target="_blank" href="<?php echo $obj_general->link('custom_order', '&mod=add&quotation_no='.encode($quotation['multi_quotation_number']), '',1);?>">Place Order</a>
                                                            </td>
                                                        <?php   }
                                              else
                                            {
                                          ?>
                                                             <td>
                                                              <a target="_blank"  class="label bg-warning" style="font-size: 100%; background:#FFC800; margin-left:10px" >Expired</a>
                                                            </td>
                                                        <?php
                                                }
                                            }
                                            else
                                            {
                                          ?>
                                              <td></td>
                                        <?php   
                                            }
                                          ?>
                            

                                        <td>
                                        <a class="btn btn-info btn-xs" ><?php echo $postedByData['user_name'];?></a>
                                       </td>
            
                                         </tr>   
                                                           
                                   <?php }}?>   
                                  </tbody>
                                 </table>
                                 </div> 
               <?php    } else if($arr[0]=='9' ){?>

                          <div class="table-responsive">
                            <table class="table b-t text-small table-hover">
                               <thead>
                                <tr>
                                    <th width="20"><input type="checkbox" ></th>
                                  
                                     <th>Quotation No. </th>
                                      <th>Customer Name</th>
                                      <th>Shipment country  </th>
                                      <th>Product</th>
                                      <th>Action</th>
                                      <th>Posted By</th>
                                </tr>
                              </thead>
                                <tbody>
                                  <tbody><?php if(!empty($data)){
                                    foreach ($data as $quo) {
                                       $postedByData = $obj_quotation->getUser($quo['user_id'],$quo['user_type_id']);
                                       
                                      ?>
                                          <tr>
                                          <td><input type="checkbox" name="post[]" value="<?php echo $quo['digital_quotation_id'];?>"></td> 
                                       
                                        <td><a href="<?php echo $obj_general->link('digital_quotation', '&mod=view&quotation_id='.encode($quo['digital_quotation_id']), '',1);?>" target="_blank"><?php echo $quo['digital_quotation_no'];?><br /><small class="text-muted"><?php echo dateFormat(4,$quo['date_added']);?></small></a></td>

                                        <td><a href="<?php echo $obj_general->link('digital_quotation', '&mod=view&quotation_id='.encode($quo['digital_quotation_id']), '',1);?>" target="_blank"><?php echo $quo['client_name'];?><br /><small class="text-muted"><?php echo $quo['email'];?></small></a></td>

                                        <td><a href="<?php echo $obj_general->link('digital_quotation', '&mod=view&quotation_id='.encode($quo['digital_quotation_id']), '',1);?>" target="_blank"><?php echo $quo['country_name'];?></a></td>
                                          <td><a href="<?php echo $obj_general->link('digital_quotation', '&mod=view&quotation_id='.encode($quo['digital_quotation_id']), '',1);?>" target="_blank"><?php echo $quo['name'];?></a></td>
                                        <td>
                                             <a class="btn btn-primary btn-sm" target="_blank" href="<?php echo $obj_general->link('digital_custom_order', '&mod=add&quotation_no='.encode($quo['digital_quotation_no']), '',1);?>" target="_blank">Place Order</a>
                                        </td>
                                          <td>                  
                                             <a class="btn btn-info btn-xs" ><?php echo $postedByData['user_name'];?></a>
                                           </td>
                                      </tr> 
                                                           
                                   <?php }}?>   
                                  </tbody>
                                 </table>
                                 </div> 
               <?php    }else if($arr[0]=='10' ){?>

                          <div class="table-responsive">
                            <table class="table b-t text-small table-hover">
                               <thead>
                                    <tr>
                                      <th width="20"><input type="checkbox"></th>
                                      <th >	 Enquiry Number      </th>
                                      <th>Company</th>
                                      <th>Name</th>
                                      <th>Date</th>
                                      <th>Email</th>
                                      <th>Mobile/Phone </th>
                                      <th>Industry</th>
                                      <th>Country</th>
                                      <th>Enquiry Source</th>
                                      <th>Posted By</th>
                                      <th>Action</th>
                                    </tr>
                                  </thead>
                                <tbody>
                                  <tbody><?php if(!empty($data)){
                                    foreach ($data as $enquiry) {
                                     
                                                                  ?>
                                                                   <tr>
                                                      <td><a href="<?php echo $obj_general->link('enquiry', 'mod=view&enquiry_id='.encode($enquiry['enquiry_id']), '',1); ?>"target="_blank"><input type="checkbox" name="post[]" value="<?php echo $enquiry['enquiry_id'];?>"></a></td>
                                                      <td>
                            						  	<a href="<?php echo $obj_general->link($rout, 'mod=view&enquiry_id='.encode($enquiry['enquiry_id']), '',1);?>"target="_blank"><?php echo $enquiry['enquiry_number'];?><br />
                                                      	<small class="text-muted"><?php echo ucwords($enquiry['enquiry_for']);?></small></a>
                                                      </td>
                                                      <td><a href="<?php echo $obj_general->link('enquiry', 'mod=view&enquiry_id='.encode($enquiry['enquiry_id']), '',1); ?>"target="_blank"><?php echo $enquiry['company_name'];?></a></td>
                                                      <td><a href="<?php echo $obj_general->link('enquiry', 'mod=view&enquiry_id='.encode($enquiry['enquiry_id']), '',1);?>"target="_blank"><?php echo $enquiry['name'];?></a></td>
                                                      <td><a href="<?php echo $obj_general->link('enquiry', 'mod=view&enquiry_id=' . encode($enquiry['enquiry_id']), '', 1); ?>"target="_blank"><?php echo dateFormat(4, $enquiry['date_added']);  ?></a></td>
                                                      <td><a href="<?php echo $obj_general->link('enquiry', 'mod=view&enquiry_id='.encode($enquiry['enquiry_id']), '',1);?>"target="_blank>"<?php echo $enquiry['email'];?></td></a>
                                                      <td><a href="<?php echo $obj_general->link('enquiry', 'mod=view&enquiry_id='.encode($enquiry['enquiry_id']), '',1);?>"target="_blank">
                            						  <?php echo $enquiry['mobile_number'];?> <br />
                                                      	 <?php if(!empty($enquiry['phone_number'])) { ?>
                                                         	<small><?php echo $enquiry['phone_number']; ?></small>
                                                         <?php } ?></a>   
                                                      </td>
                                                       <td><a href="<?php echo $obj_general->link('enquiry', 'mod=view&enquiry_id='.encode($enquiry['enquiry_id']).$add_url, '',1);?>"target="_blank" ><?php echo $enquiry['industry']; ?></a></td>
                                                      <td><a href="<?php echo $obj_general->link('enquiry', 'mod=view&enquiry_id='.encode($enquiry['enquiry_id']).$add_url, '',1);?>" target="_blank" ><?php echo $enquiry['country_name']; ?></a></td>
                                                      <td><a href="<?php echo $obj_general->link('enquiry', 'mod=view&enquiry_id='.encode($enquiry['enquiry_id']).$add_url, '',1);?>" target="_blank" ><?php echo $enquiry['source']; ?></a></td>
                                                      <td>	<a class="btn btn-info btn-xs" ><?php echo $enquiry['user_name'];?></a> </td> 
                                                       <td> <a href="<?php echo $obj_general->link($rout, 'mod=add&enquiry_id='.encode($enquiry['enquiry_id']), '',1); ;?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a> </td>
                                                  
                                                   </tr>
                                                                                       
                                                 <?php }}?>   
                                  </tbody>
                                 </table>
                                 </div> 
               <?php    } else if($arr[0]=='4'){
                      //Proforma Invoice No
                    ?>

                      <div class="table-responsive">
                      <table class="table b-t text-small table-hover">
                        <thead>
                          <tr>
                            <th width="20"><input type="checkbox"></th>
                            <th >Proforma Invoice Number
                            <br><small class="text-muted">Proforma Date</small></th>
                            <th>Final Destination</th>
                            <th>Customer Name <br>
                            <small class="text-muted">Email</small></th>                      
                            <th>Payment Amount</th>                                       
                            <th>Payment Type</th>                                       
                            <th>Payment Mode</th>                                       
                            <th>Posted By</th>                                       
                            <th>Action</th>  
                            <th></th>
                            <th></th>                          
                          </tr>
                        </thead>
                        <tbody><?php if(!empty($data)){
                          foreach ($data as $proforma) {
                          //  printr($proforma);
                              $proforma_user = $obj_pro_invoice->getUser($proforma['added_by_user_id'],$proforma['added_by_user_type_id']);
                              $currency = $obj_pro_invoice->getCurrencyId($proforma['currency_id']);          
                              $userInfo = $obj_pro_invoice->getUser($proforma['user_id'], $proforma['user_type_id']);
                              $edit_link = $obj_general->link('proforma_invoice_product_code_wise', 'mod=add&proforma_id='.encode($proforma['proforma_id']).'&is_delete=0','',1);
                            ?>
                            <tr>
                           <td></td>
                            <td>  <a href="<?php echo $obj_general->link('proforma_invoice_product_code_wise', 'mod=customer_payment&proforma_id=' . encode($proforma['proforma_id']) . '&is_delete=0', '', 1); ?>"  target="_blank" ><?php echo $proforma['pro_in_no'].'<br>'.dateFormat(4, $proforma['invoice_date']).'<br> Total Amount:'.$proforma['invoice_total'];?></a></td>                           
                             <td> <a href="<?php echo $obj_general->link('proforma_invoice_product_code_wise', 'mod=customer_payment&proforma_id=' . encode($proforma['proforma_id']) . '&is_delete=0', '', 1); ?>"  target="_blank" ><?php echo $proforma['country_name']; ?><?php if($proforma['customer_dispatch']=='1'){ echo '<br><b>dispatch order directly to customer</b>';} echo '<br><b>Buyers order no : </b>'.$proforma['buyers_order_no'];?></a></td>
                           <td> <a href="<?php echo $obj_general->link('proforma_invoice_product_code_wise', 'mod=customer_payment&proforma_id=' . encode($proforma['proforma_id']) . '&is_delete=0', '', 1); ?>"  target="_blank" ><?php echo $proforma['customer_name']; ?>
                                <br><small class="text-muted"><?php echo $proforma['email']; ?></small>
                                <br><small class="text-muted">Contact No:

                                 <?php if($proforma['contact_no']!='0' && $proforma['contact_no']!='') echo $proforma['contact_no']; ?></small>
                        </a> </td>
                         <td> <a href="<?php echo $obj_general->link('proforma_invoice_product_code_wise', 'mod=customer_payment&proforma_id=' . encode($proforma['proforma_id']) . '&is_delete=0', '', 1); ?>"  target="_blank" ><?php echo $proforma['payment_amount']; ?></a></td>
                         <td> <a href="<?php echo $obj_general->link('proforma_invoice_product_code_wise', 'mod=customer_payment&proforma_id=' . encode($proforma['proforma_id']) . '&is_delete=0', '', 1); ?>"  target="_blank" ><?php echo $proforma['payment_type']; ?></a></td>
                         <td> <a href="<?php echo $obj_general->link('proforma_invoice_product_code_wise', 'mod=customer_payment&proforma_id=' . encode($proforma['proforma_id']) . '&is_delete=0', '', 1); ?>"  target="_blank" ><?php echo $proforma['payment_mode']; ?></a></td>
                            <td><a class="btn btn-info btn-xs" ><?php echo $userInfo['user_name']; ?></a></td>
                            <td> <a href="<?php echo $edit_link; ?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a><br></a></td>
                           <td><?php  if($proforma['payment_status']!=1){?>
                              <a href="<?php echo $obj_general->link('proforma_invoice_product_code_wise', 'mod=add_payment&proforma_id=' . encode($proforma['proforma_id']) . '&is_delete=0' , '', 1); ?>"  target="_blank" name="btn_edit" class="btn btn-outline-info btn-sm"> Add Payment </a>                              
                                <?php } ?>
                               <a href="<?php echo $obj_general->link('proforma_invoice_product_code_wise', 'mod=customer_payment&proforma_id=' . encode($proforma['proforma_id']) . '&is_delete=0', '', 1); ?>"  target="_blank" name="btn_edit" class="btn btn-info btn-xs">View Payments</a>
                                <?php //}?>
                                          </td>
                                  </tr>


                          <?php }
                          

                        }?>
                    
                        </tbody>


                       </table>
                       </div>  
                  <?php          

                      }
?>         
                  </form>
               
                    <div class="form-group">
                     <div class="col-lg-9 col-lg-offset-3">                  
                        <a class="btn btn-default" href="<?php echo $obj_general->link('dashboard', '', '',1);?>">Cancel</a>
                  
                   </div>
                     </div>
                     </div>   
              </section>    
           </div>
      </div>
  </section>
</section>
<div class="modal fade" id="track_div" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form class="form-horizontal" method="post" name="tform" id="tform" style="margin-bottom:0px;">
              <div class="modal-header">
               
                <h4 class="modal-title u_title" id="myModalLabel"> Order Tracking Details</h4>
              </div>
              <div class="modal-body">
                 <div class="form-group">
                   <div class="panel-body" id="inv_data">
                   
    
                      
                  </div>
                  </div>
                </div>
                
              

              
              <div class="modal-footer">
                         <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" >Close</button>
                       
                  
              </div>
              </div>
      </form>   
    </div>
  </div>
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<style>
	#enquiry_report tbody tr, #enquiry tr{cursor: pointer; }
	body {zoom : 80%;}
</style>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
  function salespdfcls(invoice_id){     
    
    $(".note-error").remove();
    var url = '<?php echo HTTP_SERVER.'pdf/salesinvoicepdf.php?mod='.encode('salesinvoice').'&token=';?>'+invoice_id+'<?php echo '&status=1&ext='.md5('php').'&n=0';?>';
    //console.log(url);
    window.open(url, '_blank');
  return false;
}
    function dispatch_order_detail(order_id)
    {
        var url = getUrl("<?php echo $obj_general->ajaxLink('custom_order', '&mod=ajax&fun=dispatch_order_detail', '',1);?>");
     
        $.ajax({
      url : url,
      type :'post',
      data :{order_id:order_id},
      success: function(response){
        
        $('#inv_data').html(response);
        
        $("#track_div").modal('show'); 
      }     
    });
    }
</script>

       


<?php } else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>