<?php

//mansi
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

$limit = LISTING_LIMIT;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}

if(!$obj_general->hasPermission('view',$menuId)){
	$display_status = false;
}

$class = 'collapse';
$filter_data= array();
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
if(isset($_POST['inactive'])){
		$filter_status = 0;
		$filter_proforma_status = 0;
	}
	elseif(isset($_POST['notsave'])){
		$filter_status = '';
		$filter_proforma_status = 1;
	}
	else{
		$filter_status = 1;
		$filter_proforma_status = 0;		
	}
	
if(isset($obj_session->data['filter_data'])){
	$filter_customer_name = $obj_session->data['filter_data']['customer_name'];
	$filter_invoice_number = $obj_session->data['filter_data']['invoice_number'];
	$filter_email = $obj_session->data['filter_data']['email'];
	//$filter_postedby = $obj_session->data['filter_data']['postedby'];
	
	$filter_data=array(
		'customer_name' => $filter_customer_name,
		'invoice_number' => $filter_invoice_number,
		'email' => $filter_email,
		//'postedby' => $filter_postedby,		
	);
}

if(isset($_POST['btn_filter'])){
	
	$class = '';
	$filter_edit = 1;
	$class ='';	
	if(isset($_POST['filter_customer_name'])){
		$filter_customer_name=$_POST['filter_customer_name'];		
	}else{
		$filter_customer_name='';
	}
	if(isset($_POST['filter_invoice_number'])){
		$filter_invoice_number=$_POST['filter_invoice_number'];		
	}else{
		$filter_invoice_number='';
	}
	if(isset($_POST['filter_email'])){
		$filter_email=$_POST['filter_email'];		
	}else{
		$filter_email='';
	}
	/*if(isset($_POST['filter_user_name']))
	{
		$filter_user_name = $_POST['filter_user_name'];
	}else{
		$filter_user_name='';
	}*/
	
	$filter_data=array(
		'customer_name' => $filter_customer_name,
		'invoice_number' => $filter_invoice_number,
		'email' => $filter_email
		//'postedby' =>$filter_user_name		
	);
	
	$obj_session->data['filter_data'] = $filter_data;

} 

if(isset($_GET['page'])){
	if(isset($_SESSION['filter_data']) && !empty($_SESSION['filter_data'])) {
	$filter_data = ($_SESSION['filter_data']);
	}
}

if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'DESC';	
}
//delete
 if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
	//printr($_POST['post']);die;
		$obj_pro_invoice->updateProformaStatus(2,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	
}
$status = '';
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
       		<span> Client Generated Proforma Invoice Listing</span>
          	<span class="text-muted m-l-small pull-right">
          	   
				<!--<a class="label bg-primary" href="<?php //echo HTTP_SERVER.'proforma_invoice/add.php';?>"><i class="fa fa-plus"></i> New Proforma</a>-->
                       <?php  if($obj_general->hasPermission('delete',$menuId)){ ?>
                      <a class="label bg-danger" onclick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>
                    <?php } ?>                    
            </span>
          </header>
          
          <div class="panel-body">
              <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link($rout, '', '',1); ?>">
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
                                <label class="col-lg-5 control-label">Proforma Invoice Number</label>
                                <div class="col-lg-7">   
                                 <input type="text" name="filter_invoice_number" value="<?php echo isset($filter_invoice_number) ? $filter_invoice_number : '' ; ?>" placeholder="Proforma Invoice Number" id="filter_invoice_number" class="form-control" />
                                </div>
                              </div>                             
                          </div>
                      
                          <div class="col-lg-4">
                              <div class="form-group">
                                 <label class="col-lg-5 control-label">Customer Name</label>
                                <div class="col-lg-7">   
                                <input type="text" name="filter_customer_name" value="<?php echo isset($filter_customer_name) ? $filter_customer_name : '' ; ?>" placeholder="Customer Name" id="filter_customer_name" class="form-control" />
                                </div>
                              </div>                             
                          </div>
                          
                           <div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-5 control-label">Posted By</label>
                                <div class="col-lg-7">
                                  <input type="text" name="filter_email" value="<?php echo isset($filter_email) ? $filter_email : '' ; ?>" placeholder="Email" id="filter_email" class="form-control" />
                                </div>
                              </div>                             
                          </div>
                       </div>                     
                 </div>
            
                  <footer class="panel-footer <?php echo $class; ?>">
                    <div class="row">
                       <div class="col-lg-12">
                       <input type="hidden" value="<?php echo $status;?>" id="status" name="status" />
                        <button type="submit" class="btn btn-primary btn-sm pull-right ml5" name="btn_filter"><i class="fa fa-search"></i> Search</button>
                        <a href="<?php echo $obj_general->link($rout, '', '',1); ?>" class="btn btn-info btn-sm pull-right" ><i class="fa fa-refresh"></i> Refresh</a>
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
						////echo $obj_general->link($rout, 'limit='.$display_limit.'&status='.$status, '',1);?>
                        		<option value="<?php echo $obj_general->link($rout, 'limit='.$display_limit, '',1);?>" selected="selected"><?php echo $display_limit; ?></option>				
						<?php } else { ?>
                            	<option value="<?php echo $obj_general->link($rout, 'limit='.$display_limit, '',1);?>"><?php echo $display_limit; ?></option>
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
                     <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">Proforma Invoice Number</th>
                      <th>Invoice Date</th>
                      <th>Final Destination</th>
                      <th>Customer Name</th>
                      <th>Posted By</th>
                      <th>Status</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
              <?php $total_pouch = $obj_pro_invoice->getTotalInvoice($filter_data,$obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);
			  		//printr($total_pouch);
			  
				  $pagination_data = '';
				  
				  if($total_pouch) {
					  	if (isset($_GET['page'])) {
                            $page = (int)$_GET['page'];
						} else {
							$page = 1;
						}
						$option = array(
						  'sort'  => 'proforma_id',
						  'order' => $sort_order,
						  'start' => ($page - 1) * $limit,
						  'limit' => $limit);
						  
					$proformas = $obj_pro_invoice->getInvoices($option, $filter_data,$obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);
					
					
				  if(isset($total_pouch)) {
                  	foreach($proformas as $proforma) {					  
					//printr($proformas);
					?>
                      
                        <tr id="proforma-row-<?php echo $proforma['proforma_id']; ?>" <?php echo ($proforma['status']==0) ? 'style="background-color:#f2dede" ' : '' ; ?>>      
                          <td><input type="checkbox" name="post[]" value="<?php echo $proforma['proforma_id'];?>"></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&proforma_id='.encode($proforma['proforma_id']),'',1); ?>" name="btn_edit" ><?php echo $proforma['pro_in_no']; ?></a></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&proforma_id='.encode($proforma['proforma_id']),'',1); ?>" name="btn_edit" ><?php echo dateFormat(4,$proforma['invoice_date']);?></a></td>
						  <td><a href="<?php echo $obj_general->link($rout, 'mod=view&proforma_id='.encode($proforma['proforma_id']),'',1); ?>" name="btn_edit" ><?php echo $proforma['country_name']; ?></a></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&proforma_id='.encode($proforma['proforma_id']),'',1); ?>" name="btn_edit"><?php echo $proforma['customer_name']; ?></a></td>
                         <?php /*?> <td><a href="<?php echo $obj_general->link($rout, 'mod=view&proforma_id='.encode($proforma['proforma_id']),'',1); ?>" name="btn_edit" ><?php echo $proforma['email']; ?></a></td><?php */?>
                           <td><a class="btn btn-default btn-xs" data-trigger="hover" data-toggle="popover" data-html="true" data-placement="top" title=""><?php echo $proforma['email']; ?></a> </td>
                          <td>
                              <div data-toggle="buttons" class="btn-group">
                                    <label class="btn btn-xs btn-success <?php echo ($proforma['status']==1) ? 'active' : '';?> "> <input type="radio" name="status" value="1" id="<?php echo $proforma['proforma_id']; ?>"> <i class="fa fa-check text-active"></i>Active</label>                                   
                                    <label class="btn btn-xs btn-danger <?php echo ($proforma['status']==0) ? 'active' : '';?> "> <input type="radio" name="status" value="0" id="<?php echo $proforma['proforma_id']; ?>"> <i class="fa fa-check text-active"></i>Inactive</label> 
                                </div>
                           </td>
                          <?php /*?> <td>
                         <?php if($obj_general->hasPermission('edit',$menuId)){ ?> 		
                            <a href="<?php //echo $obj_general->link($rout, 'mod=add&proforma_id='.encode($proforma['proforma_id']),'',1); ?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                            <?php }?>
                           </td>
						   <?php */?>
                            <td><a href="<?php echo $obj_general->link($rout, 'mod=view&proforma_id='.encode($proforma['proforma_id']),'',1); ?>"  name="btn_edit" class="btn btn-info btn-xs">View Invoice</a></td>
                           
                        </tr>
                      <?php
					 }
				  } else {
						  echo '<tr><td colspan="4">No Record Found</td></tr>';
                  } 
				  //pagination
				 // $obj_general->link($rout,'&page={page}&limit='.$limit.'&filter_edit=1', '',1); 
                        $pagination = new Pagination();
						$pagination->total = $total_pouch;
                        $pagination->page = $page;
                        $pagination->limit = $limit;
                        $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                       	$pagination->url = $obj_general->link($rout,'&page={page}&limit='.$limit.'&filter_edit=1', '',1);
						$pagination_data = $pagination->render();
                       } else{ 
                      echo "<tr><td colspan='5'>No record found !</td></tr>";
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
	$('input[name=status]').change(function() {
		var proforma_id=$(this).attr('id');
		var status_value = this.value;
		var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=updateProStatus', '',1);?>");
       	$.ajax({			
			url : status_url,
			type :'post',
			data :{proforma_id:proforma_id,status_value:status_value},
			success: function(){
				set_alert_message('Successfully Updated',"alert-success","fa-check");
				var status = $("#status").val();
				var current_url = $(location).attr('href');
				var url = current_url+'&status='+status;
				window.location.href = url;
			},
			error:function(){
				set_alert_message('Error During Updation',"alert-warning","fa-warning");  
			}
						
		});
    });
</script>                
