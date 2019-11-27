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
//printr($menuId);
if(!$obj_general->hasPermission('view',$menuId)){
	$display_status = false;
}
$option=array();
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
//[sonu] (18-4-2017) for get address_book_id wise data
$add_book_id='0';
$add_url='';
if(isset($_GET['address_book_id']))
{
		$add_book_id = decode($_GET['address_book_id']);
		$add_url = '&address_book_id='.$_GET['address_book_id'];
}
//printr($add_book_id );

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
	$filter_quotation = $obj_session->data['filter_data']['quotation_no'];
	$filter_date = $obj_session->data['filter_data']['date'];
	$filter_customer_name = $obj_session->data['filter_data']['customer_name'];
  $filter_customer_email = $obj_session->data['filter_data']['customer_email'];
	$filter_product_name = $obj_session->data['filter_data']['product_name'];
	$filter_country = $obj_session->data['filter_data']['country'];
	$filter_postedby = $obj_session->data['filter_data']['postedby'];
	$class = '';
	
	$filter_data=array(
		'quotation_no' => $filter_quotation,
		'date' => $filter_date, 
		'customer_name' => $filter_customer_name,
    'customer_email' => $filter_customer_email,
		'product_name' => $filter_product_name,
		'country' => $filter_country,
		'postedby' => $filter_postedby,
	);
}
$status = '';
if($obj_session->data['LOGIN_USER_TYPE']==1 && $obj_session->data['ADMIN_LOGIN_SWISS']==1) {
	if(isset($_POST['inactive']) || (isset($_GET['status']) && $_GET['status'] == 1) || (isset($_POST['status']) && $_POST['status'] == 1) ){
		$con= " AND dqi.quotation_status = 1  AND dqi.status='0'";
		$cond=" AND dqi.quotation_status = 1  AND dqi.status='0' ";
		$status = '1';
	}
	elseif(isset($_POST['notsave']) || (isset($_GET['status'])  && $_GET['status']== 2) || (isset($_POST['status']) && $_POST['status'] == 2)){
		$con= " AND dqi.quotation_status = 0  ";
		$cond=" AND dqi.quotation_status = 0  ";
		$status = '2';
	}
	else{
		$con= " AND dqi.quotation_status = 1  AND dqi.status='1'";
		$cond=" AND dqi.quotation_status = 1  AND dqi.status='1' ";
		$status = '0';
	}
}
else
{
	$con= " AND dqi.quotation_status = 1";
	$cond=" AND dqi.quotation_status = 1 ";	
}
if(isset($_POST['btn_filter'])){
	$filter_edit = 1;
	$class = '';	
	if(isset($_POST['filter_quotation'])){
		$filter_quotation=$_POST['filter_quotation'];		
	}else{
		$filter_quotation='';
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
  	if(isset($_POST['filter_customer_email'])){
    $filter_customer_email=$_POST['filter_customer_email'];
  }else{
    $filter_customer_email='';
  } 
	if(isset($_POST['filter_product_name'])){
		$filter_product_name=$_POST['filter_product_name'];
	}else{
		$filter_product_name='';
	}
	if(isset($_POST['country_id'])){
		$filter_country=$_POST['country_id'];
	}else{
		$filter_country='';
	}
	if(isset($_POST['filter_user_name']))
	{
		$filter_user_name = $_POST['filter_user_name'];
	}else{
		$filter_user_name='';
	}
	$filter_data=array(
		'quotation_no' => $filter_quotation,
		'date' => $filter_date, 
		'customer_name' => $filter_customer_name,
    'customer_email' => $filter_customer_email,
		'product_name' => $filter_product_name,
		'country' => $filter_country,
		'postedby' => $filter_user_name,	
	);
	$obj_session->data['filter_data'] = $filter_data;		
}
if($display_status) {


	if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
		if(!$obj_general->hasPermission('delete',$menuId)){
			$display_status = false;
		} else {
			foreach($_POST['post'] as $quotation_id){
			//	printr($_POST['post']);die;
				$obj_label_quotation->deleteQuotation($quotation_id);
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
            	<?php if($obj_general->hasPermission('add',$menuId)){ ?>
   					<a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add'.$add_url, '',1);?>"><i class="fa fa-plus"></i> New Quotation </a>
                <?php } ?>
                <?php if($obj_general->hasPermission('delete',$menuId)){ ?>   
                      <a class="label bg-danger" style="margin-left:4px;" onclick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>
                <?php } ?>      
            </span>
          </header>
          <div class="panel-body">
            
             <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link($rout,$add_url, '',1); ?>">
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
                                <label class="col-lg-5 control-label">Quotation No</label>
                                <div class="col-lg-7">
                                  <input type="text" name="filter_quotation" value="<?php echo isset($filter_quotation) ? $filter_quotation : '' ; ?>" placeholder="Name" id="input-name" class="form-control" />
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
                             <div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-5 control-label">Email</label>
                                <div class="col-lg-7">                                                            
                                
                                 <input type="text" name="filter_customer_email" value="<?php echo isset($filter_customer_email) ? $filter_customer_email : '' ; ?>" placeholder="Customer Email" id="input-price" class="form-control">
                                </div>
                              </div>
                            </div>
                        </div>
                           <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                      <label class="col-lg-5 control-label">Product</label>
                                      <?php	 	$products = $obj_label_quotation->getProductList();	?>
                                      <div class="col-lg-7">
                                      	<select class="form-control" name="filter_product_name">
                                          	<option value="">Please Select</option>
                                          	<?php foreach($products as $product) { ?>
                                              	<?php if(isset($filter_product_name) && !empty($filter_product_name) && $filter_product_name == $product['product_name']) { ?>
                                          			<option value="<?php echo $product['product_name']; ?>" selected="selected"><?php echo $product['product_name']; ?></option>
                                                  <?php } else { ?>
                                                  	<option value="<?php echo $product['product_name']; ?>"><?php echo $product['product_name']; ?></option>
                                                  <?php } ?>
                                              <?php } ?>                                       
                                          </select>
                                      </div>
                                    </div>                              
                                </div>
                           <?php 
  			  				           	if(isset($filter_user_name))
  							               	{
  			   						            $splitdata=explode("=",$filter_user_name);
  			  		 		            	}
  			  		           		?>
                           <div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-5 control-label">Posted By User</label>
                                <?php							
								                	$userlist = $obj_label_quotation->getUserList();
							                   	?>
                                <div class="col-lg-7">
                                	<select class="form-control" name="filter_user_name">
                                    	<option value="">Please Select</option>
                                    	<?php foreach($userlist as $user) { ?>
                                        	<?php if(isset($splitdata) && $splitdata[0] == $user['user_type_id'] && $splitdata[1] ==$user['user_id']) { ?>
                                            
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
                                <label class="col-lg-5 control-label">Date</label>
                                <div class="col-lg-7">                                                            
                                
                                 <input type="text" name="filter_date" readonly="readonly" data-date-format="dd-mm-yyyy" value="<?php echo isset($filter_date) ? $filter_date : '' ; ?>" placeholder="Date" id="input-name" class="input-sm form-control datepicker" />
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
            <?php if($obj_session->data['LOGIN_USER_TYPE']==1 && $obj_session->data['ADMIN_LOGIN_SWISS']==1) { ?>
            <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link($rout, '', '',1); ?>">
           <div class=" pull-left">
           	 	<div class="panel-body text-muted l-h-2x">
               
                 <button  type="submit" class="btn btn-primary btn-sm pull-right ml5" name="notsave" style="background-color:#EAA7A7"><i ></i> Not Save</button> 
                 <button  type="submit" class="btn btn-primary btn-sm pull-right ml5" name="inactive" style="background-color:#CBC6AB"><i></i> Inactive</button>
                 <button  type="submit" class="btn btn-primary btn-sm pull-right ml5" name="active" style="background-color:#81C267"><i></i> Active</button>
                </div>
           </div> 
           <?php }?>
           </form>
           <div class="col-lg-3 pull-right">	
                <select class="form-control" id="limit-dropdown" onchange="location=this.value;">
                <option value="<?php echo $obj_general->link($rout, '&status='.$status, '',1);?>" selected="selected">--Select--</option>	
					<?php 
                        $limit_array = getLimit(); 
                        foreach($limit_array as $display_limit) {
                            if($limit == $display_limit) {	 
                    ?>
                       		 
                            <option value="<?php echo $obj_general->link($rout, 'limit='.$display_limit.'&status='.$status.$add_url, '',1);?>" selected="selected"><?php echo $display_limit; ?></option>				
                    <?php } else { ?>
                            <option value="<?php echo $obj_general->link($rout, 'limit='.$display_limit.'&status='.$status.$add_url, '',1);?>"><?php echo $display_limit; ?></option>
                    <?php } ?>
                    <?php } ?>
                 </select>
           </div>
             <label class="col-lg-1 pull-right" style="margin-top:5px;">Show</label>             
          </div>
          <form name="form_list" id="form_list" method="post">
           <input type="hidden" id="action" name="action" value="" />
          	<div class="table-responsive">
                <table class="table table-striped b-t text-small table-hover">
                  <thead>
                    <tr>
                        <th width="20"><input type="checkbox" ></th>
                        <th>Sr. No. </th>
                         <th>Quotation No. </th>
                          <th>Customer Name</th>
                          <th>Shipment country  </th>
                      	  <th>Product</th>
                          <th>Action</th>
                          <th>Posted By</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php $Total_quo = $obj_label_quotation->getQuotationData($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],$cond,$filter_data,$option);
                   $total_quo = count($Total_quo);
                   $i=1;
                  if($total_quo){
                        if (isset($_GET['page'])) {
                            $page = (int)$_GET['page'];
                        } else {
                            $page = 1;
                        }
                      //oprion use for limit or and sorting function	
                      $option = array(
                            'sort'  => 'dqi.label_quotation_id',
                            'order' => 'DESC',
                            'start' => ($page - 1) * LISTING_LIMIT,
                            'limit' => LISTING_LIMIT
                      );	
                    $data_quo = $obj_label_quotation->getQuotationData($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],$cond,$filter_data,$option);
                    
                 //   printr($data_quo);
                    foreach($data_quo as $quo){
                            $postedByData = $obj_label_quotation->getUser($quo['user_id'],$quo['user_type_id']);
                        ?>
                
                        <tr  <?php echo ($quo['quotation_status']==0) ? 'style="background-color:#f2dede" ' : '' ; ?>> 
                            <td><input type="checkbox" name="post[]" value="<?php echo $quo['label_quotation_id'];?>"></td>	
                            <td><a href="<?php echo $obj_general->link($rout, '&mod=view&quotation_id='.encode($quo['label_quotation_id']).'&filter_edit='.$filter_edit, '',1);?>"><?php echo $i;?></a></td>
                        	<td><a href="<?php echo $obj_general->link($rout, '&mod=view&quotation_id='.encode($quo['label_quotation_id']).'&filter_edit='.$filter_edit, '',1);?>"><?php echo $quo['quotation_no'];?><br /><small class="text-muted"><?php echo dateFormat(4,$quo['date_added']);?></small></a></td>

                        	<td><a href="<?php echo $obj_general->link($rout, '&mod=view&quotation_id='.encode($quo['label_quotation_id']).'&filter_edit='.$filter_edit, '',1);?>"><?php echo $quo['client_name'];?><br /><small class="text-muted"><?php echo $quo['email'];?></small></a></td>

                          <td><a href="<?php echo $obj_general->link($rout, '&mod=view&quotation_id='.encode($quo['label_quotation_id']).'&filter_edit='.$filter_edit, '',1);?>"><?php echo $quo['country_name'];?></a></td>
                          	<td><a href="<?php echo $obj_general->link($rout, '&mod=view&quotation_id='.encode($quo['label_quotation_id']).'&filter_edit='.$filter_edit, '',1);?>"><?php echo $quo['name'];?></a></td>
                          <td>
                              <!-- <a class="btn btn-primary btn-sm" target="_blank" href="<?php //echo $obj_general->link('digital_custom_order', '&mod=add&quotation_no='.encode($quo['digital_quotation_no']), '',1);?>">Place Order</a>-->
                          </td>
                          	<td><?php
									$addedByImage = $obj_general->getUserProfileImage($quo['user_type_id'],$quo['user_id'],'100_');
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
								<a class="btn btn-info btn-xs" data-trigger="hover" data-toggle="popover" data-html="true" data-placement="top" data-content='<?php echo $postedByInfo;?>' title="" data-original-title="<b><?php echo $postedByName;?></b>"><?php echo $postedByData['user_name'];?></a>
							 </td>
                        </tr>
                        <?php $i++;
                      }
                        //pagination
                        $pagination = new Pagination();
                        $pagination->total = $total_quo;
                        $pagination->page = $page;
                        $pagination->limit = LISTING_LIMIT;
                        $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                        $pagination->url = $obj_general->link($rout, '&page={page}', '',1);
                        $pagination_data = $pagination->render();
                  } else{ 
                      echo "<tr><td colspan='5'>No record found !</td></tr>";
                  } ?>
                  </tbody>
                </table>
              </div>
          </form>
          <footer class="panel-footer">
            <div class="row">
              <div class="col-sm-2 hidden-xs"> </div>
              	<?php echo $pagination_data;?>
            </div>
          </footer>
        </section>
      </div>
    </div>
  </section>
</section>
<script type="application/javascript"> 


	$('input[type=radio][name=status]').change(function() {
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