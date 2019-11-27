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
	$filter_product_name = $obj_session->data['filter_data']['product_name'];
	$filter_country = $obj_session->data['filter_data']['country'];
	$filter_postedby = $obj_session->data['filter_data']['postedby'];
	$class = '';
	
	$filter_data=array(
		'quotation_no' => $filter_quotation,
		'date' => $filter_date, 
		'customer_name' => $filter_customer_name,
		'product_name' => $filter_product_name,
		'country' => $filter_country,
		'postedby' => $filter_postedby,
	);
}
$status = '';


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
		'product_name' => $filter_product_name,
		'country' => $filter_country,
		'postedby' => $filter_user_name,	
	);
	$obj_session->data['filter_data'] = $filter_data;		
}
if($display_status) {
	//active inactive delete
	if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
		if(!$obj_general->hasPermission('delete',$menuId)){
			$display_status = false;
		} else {
			foreach($_POST['post'] as $quotation_id){
				//printr($_POST['post']);
				$obj_quotation->deleteQuotation($quotation_id);
			}
			$obj_session->data['success'] = UPDATE;
			page_redirect($obj_general->link($rout, '', '',1));
		}
	}
	/*if($obj_session->data['ADMIN_LOGIN_SWISS']=='27' && $obj_session->data['LOGIN_USER_TYPE']='4')
                  {
                   	$quotations_test = $obj_quotation->getQuotations($obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS'],'','');	
						printr($quotations_test );
					}*/
	
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
                               <div class="form-group">
                                <label class="col-lg-5 control-label">Date</label>
                                <div class="col-lg-7">                                                            
                                
                                 <input type="text" name="filter_date" readonly="readonly" data-date-format="dd-mm-yyyy" value="<?php echo isset($filter_date) ? $filter_date : '' ; ?>" placeholder="Date" id="input-name" class="input-sm form-control datepicker" />
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
                              
                               <div class="form-group">
                                <label class="col-lg-4 control-label">Country</label>
                                <div class="col-lg-8">
                                	<?php
										$sel_country = (isset($branch['country_id']))?$branch['country_id']:''; 
										$countrys = $obj_general->getCountryCombo($sel_country);
										echo $countrys;                   
									?>	             	
                                </div>
                              </div>
                          </div>
                          
                          <div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-5 control-label">Product</label>
                                <?php							
									$products = $obj_quotation->getActiveProduct();
								?>
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
									$userlist = $obj_quotation->getUserList();
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
                <table id="quotation-row" class="table b-t text-small table-hover">
                  <thead>
                    <tr>
                      <th width="20"><input type="checkbox" ></th>
                      <th>Sr. No.</th>                     
                       <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">
                      		Quotation No.
                            <span class="th-sort">
                            	<a href="<?php echo $obj_general->link($rout, 'sort=date_added'.'&order=ASC'.$add_url, '',1);?>">
                                	<i class="fa fa-sort-down text"></i>
                                    
                                <a href="<?php echo $obj_general->link($rout, 'sort=date_added'.'&order=DESC'.$add_url, '',1);?>">
                                <i class="fa fa-sort-up text-active"></i>
                            <i class="fa fa-sort"></i></span>
                      </th>
                    
                      <th>Customer Name</th>
                    
                       <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">
                      		Product
                            <span class="th-sort">
                            	<a href="<?php echo $obj_general->link($rout, 'sort=product_name'.'&order=ASC'.$add_url, '',1);?>">
                                <i class="fa fa-sort-down text"></i>
                                <a href="<?php echo $obj_general->link($rout, 'sort=product_name'.'&order=DESC'.$add_url, '',1);?>">
                                <i class="fa fa-sort-up text-active"></i>
                            <i class="fa fa-sort"></i></span>
                      </th>
                       <?php if(isset($status) && $status != 2){?>
                      <th>Status</th>
                      <?php }?>
                      <?php if($obj_session->data['LOGIN_USER_TYPE']==1 && $obj_session->data['ADMIN_LOGIN_SWISS']==1) { ?>
                      	<th colspan="2">Action</th>
                      <?php } ?> 
                      <th></th>
                      <th>Posted By</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                  $user_id =$obj_session->data['ADMIN_LOGIN_SWISS'];
                
                  $total_quotation = $obj_quotation->getQuotationsforcheckcustomorder($obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS'],$filter_data,$cond,$add_book_id);
                  
                  $pagination_data = '';
                  if($total_quotation!=0){
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
                            'limit' => $limit,
							'con' =>$con, 
							'cond' =>$cond,
                      );	
                       $quotations = $obj_quotation->getQuotationsforcheckcustomorder($obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS'],$option,$filter_data,$add_book_id);	
				//	printr($quotations);die;
					  $start_num =((($page*$limit)-$limit)+1);
					  $f = 0;
					  $slNo = $f+$start_num;
					  foreach($quotations as $quotation){ 
					  	$postedByData = $obj_quotation->getUser($quotation['added_by_user_id'],$quotation['added_by_user_type_id']);
						$expiredate_cust = $obj_quotation->getexpiredate_custmorder($quotation['added_by_user_id'],$quotation['added_by_user_type_id']);
						$exp_date=$expiredate_cust['Multi_Quotation_expiry_days'];
						$date_added=strtotime($quotation['date_added']);
						//$fin=date('Y-m-d',strtotime($quotation['date_added'],"+$exp_date days"));
						
						
						$final_date=date('y-m-d', $date_added);
						$fin='';
						if($exp_date!='')
						{
							$fin=date('y-m-d',strtotime($final_date."+ {$exp_date} days"));
							
							
						}
						//echo $fin;
						$today=date('y-m-d'); 
						
						if($postedByData){  ?>                           
                            <?php if($quotation['quotation_status']==0){ //for not saved?>
                            	<tr id="quotation-row-<?php echo $quotation['multi_product_quotation_id']; ?>" style="background-color:#f2dede">
                            <?php } else { //for inactive?>
                            	<tr id="quotation-row-<?php echo $quotation['multi_product_quotation_id']; ?>" <?php echo ($quotation['status']==0) ? 'style="background-color:#fcf8e3" ' : '' ; ?>>
                            <?php } ?>
                              <td><input type="checkbox" name="post[]" value="<?php echo $quotation['multi_product_quotation_id'];?>"></td>	
							  <td width="1%"><?php echo $slNo++;?></td>
							  <td>
								<a href="<?php echo $obj_general->link($rout, '&mod=view&quotation_id='.encode($quotation['multi_product_quotation_id']).'&filter_edit='.$filter_edit.$add_url, '',1);?>"><?php echo $quotation['multi_quotation_number'];?>
                                <?php if($quotation['use_device']){ 
										echo '<small class="text-muted">[From '.ucwords($quotation['use_device']).']</small>';
								 } ?>	
									<br /><small class="text-muted"><?php echo dateFormat(4,$quotation['date_added']);?></small>
                                </a>
							  </td>
							 <td>
                 			 <a href="<?php echo $obj_general->link($rout, '&mod=view&quotation_id='.encode($quotation['multi_product_quotation_id']).'&filter_edit='.$filter_edit.$add_url, '',1);?>">            	 
							  	  <?php echo $quotation['customer_name'];?><br/>
                              	  <small class="text-muted"><?php echo $quotation['country_name']; ?></small>
                              </a>  	
                              </td>
                              
							  <td>
                              <a href="<?php echo $obj_general->link($rout, '&mod=view&quotation_id='.encode($quotation['multi_product_quotation_id']).'&filter_edit='.$filter_edit.$add_url, '',1);?>">
								<?php echo $quotation['product_name'];?><br />
								<small class="text-muted"><?php echo $quotation['layer'].' Layer';?><span style="color:blue"> <?php echo ' '.'['.$quotation['zipper_txt'].' '.
								$quotation['valve_txt'].' '.$quotation['spout_txt'].' '.$quotation['accessorie_txt'].']';?></span></small><br />
                                
                                </a>
							  </td>
                              <?php if(isset($status) && $status != 2){?>
                              <td>
                              	<div data-toggle="buttons" class="btn-group">
                                	<label class="btn btn-xs btn-success <?php echo ($quotation['status']==1) ? 'active' : '';?> "> <input type="radio" 
                                    name="status" value="1" id="<?php echo $quotation['multi_product_quotation_id']; ?>"> <i class="fa fa-check text-active"></i>Active</label>
                                     
                                	<label class="btn btn-xs btn-danger <?php echo ($quotation['status']==0) ? 'active' : '';?> "> <input type="radio" 
                                    name="status" value="0" id="<?php echo $quotation['multi_product_quotation_id']; ?>"> <i class="fa fa-check text-active"></i>Inactive</label>
                                	</div>
                              </td>
                              <?php }?>
                              <?php if($obj_session->data['LOGIN_USER_TYPE']==1 && $obj_session->data['ADMIN_LOGIN_SWISS']==1) { ?>
                              
                                  <td class="delete-quot">
                                    <a class="btn btn-danger btn-sm" id="<?php echo $quotation['multi_product_quotation_id']; ?>" href="javascript:void(0);"><i class="fa fa-trash-o"></i></a>
                                    </td>
                                    <?php
									}
									
									if($quotation['added_by_user_id']!='1' || $quotation['added_by_user_type_id']!='1') {
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
                            <td><a  class="btn btn-info btn-xs" style="font-size: 100%; margin-left:10px" onclick="clone_mutli_quo(<?php echo $quotation['multi_product_quotation_id']; ?>)" >Clone</a></td>

							  <td> 
                              
								<?php
									$addedByImage = $obj_general->getUserProfileImage($quotation['added_by_user_type_id'],$quotation['added_by_user_id'],'100_');
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
							<?php
							
						}
                      }
                        //pagination
                        $pagination = new Pagination();
                        $pagination->total = $total_quotation;
                        $pagination->page = $page;
                        $pagination->limit = $limit;
                        $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                        $pagination->url = $obj_general->link($rout,'&page={page}&limit='.$limit.'&status='.$status.'&filter_edit=1'.$add_url, '',1);
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
    function clone_mutli_quo(multi_product_quotation_id)
	{
		var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=clone_mutli_quo', '',1);?>");
		$.ajax({
			url : status_url,
			type :'post',
			data :{multi_product_quotation_id:multi_product_quotation_id},
			success: function(response){
				//console.log(response);
				//if(response==1){
					set_alert_message('Successfully Clone',"alert-success","fa-check");	
				//}else{
					//set_alert_message('You Don\'t Have Access To Enable Quotation',"alert-warning","fa-warning");						
				//}									
			},
			error:function(){
				set_alert_message('sda',"alert-warning","fa-warning");          
			}			
		});
	}
</script>           
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>