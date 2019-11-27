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
//printr($limit);
$filter_data=array();
$filter_value='';
$class = 'collapse';

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
$user_id= $user_type_id='';
if(isset($_GET['user_id'])){
	$user_id = decode($_GET['user_id']);	
}
if(isset($_GET['user_type_id'])){
	$user_type_id = decode($_GET['user_type_id']);	
}

if(isset($obj_session->data['filter_data'])){
	$filter_company = $obj_session->data['filter_data']['company'];
	$filter_name = $obj_session->data['filter_data']['customer'];
	$filter_vat = $obj_session->data['filter_data']['vat'];
	$filter_number = $obj_session->data['filter_data']['number'];
	$filter_user_name = $obj_session->data['filter_data']['user_name'];
	$filter_website = $obj_session->data['filter_data']['website'];
	$filter_status = $obj_session->data['filter_data']['status'];
	$filter_email = $obj_session->data['filter_data']['email'];
	
    $filter_data=array(
		'company' => $filter_company,
		'customer' => $filter_name,
		'vat' => $filter_vat,
		'number'=>$filter_number,
		'website' => $filter_website,
		'status' => $filter_status,
		'user_name' => $filter_user_name,
		'email' => $filter_email,
		
	);
}
//printr($filter_data);
if(isset($_POST['btn_filter'])){
	
    $class = '';
	$filter_edit = 1;
	$class ='';
	
	if(isset($_POST['filter_company'])){
		$filter_company=$_POST['filter_company'];		
	}else{
		$filter_company='';
	}
	
	if(isset($_POST['filter_name'])){
		$filter_name=$_POST['filter_name'];		
	}else{
		$filter_name='';
	}
	
	if(isset($_POST['filter_vat'])){
		$filter_vat=$_POST['filter_vat'];		
	}else{
		$filter_vat='';
	}
	if(isset($_POST['filter_number'])){
		$filter_number=$_POST['filter_number'];
	}else{
		$filter_number='';
	}
	
	if(isset($_POST['filter_website'])){
		$filter_website=$_POST['filter_website'];
	}else{
		$filter_website='';
	}
	if(isset($_POST['filter_user_name']))
	{
		$filter_user_name = $_POST['filter_user_name'];
	}else{
		$filter_user_name='';
	}
	if(isset($_POST['filter_status'])){
		$filter_status=$_POST['filter_status'];
	}else{
		$filter_status='';
	}
	if(isset($_POST['filter_email'])){
		$filter_email=$_POST['filter_email'];
	}else{
		$filter_email='';
	}	
	$filter_data=array(
		'company' => $filter_company,
		'customer' => $filter_name,
		'vat' => $filter_vat,
		'number'=>$filter_number,
		'website' => $filter_website,
		'status' => $filter_status,
		'user_name' => $filter_user_name,
		'email' => $filter_email,
		
	);
		$obj_session->data['filter_data'] = $filter_data;
//	printr($filter_data);//die;
}

if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'DESC';	
}
if(isset($_GET['page'])){
	if(isset($_SESSION['filter_data']) && !empty($_SESSION['filter_data'])) {
	$filter_data = ($_SESSION['filter_data']);
	}
}

if($display_status) {

//active inactive delete
if(isset($_POST['action']) && ($_POST['action'] == "active" || $_POST['action'] == "inactive") && isset($_POST['post']) && !empty($_POST['post']))
{
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	} else {
		//printr($_POST['post']);die;
		$status = 0;
		if($_POST['action'] == "active"){
			$status = 1;
		}
		$obj_address->updateStatus($status,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1)); 
	}
}else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
	if(!$obj_general->hasPermission('delete',$menuId)){
		$display_status = false;
	} else {
		//printr($_POST['post']);die;
		$obj_address->updateStatus(2,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
}   $link='';
	if(isset($user_id) && $user_id!=''){
		$link = '&mod=index&user_id='.$_GET['user_id'].'&user_type_id='.$_GET['user_type_id'];
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
          <header class="panel-heading bg-white"> <span><?php echo $display_name;?> Listing</span> 
            <span class="text-muted m-l-small pull-right">
            <?php if(!isset($user_id)){ 
				if($obj_general->hasPermission('add',$menuId)){  ?>
			    <a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>"><i class="fa fa-plus"></i> New Address </a>
				<?php } 
			} ?>
           	
            <?php if($obj_general->hasPermission('edit',$menuId)){ ?>
            <a class="label bg-inverse" style="margin-left:3px;" href="<?php echo $obj_general->link($rout, 'mod=import', '',1);?>"> <i class="fa fa-print"></i> CSV Import</a>
            
            <a class="label bg-success"  onclick="formsubmitsetaction('form_list','active','post[]','<?php echo ACTIVE_WARNING;?>')"><i class="fa fa-check"></i> Active</a>
             <a class="label bg-warning" onclick="formsubmitsetaction('form_list','inactive','post[]','<?php echo INACTIVE_WARNING;?>')"><i class="fa fa-times"></i> Inactive</a> 
             <a class="label bg-danger" onclick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>
            <?php } ?>
            <a class="label bg-info" style="margin-left:3px;" href="javascript:void(0);" onclick="csvlink('post[]')"> <i class="fa fa-print"></i> CSV Export</a>
            </span> </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link($rout, '', '',1); ?>">
              <section class="panel pos-rlt clearfix">
                <header class="panel-heading">
                  <ul class="nav nav-pills pull-right">
                    <li> <a href="#" class="panel-toggle text-muted active"><i class="fa fa-caret-down fa-lg text-active"></i><i class="fa fa-caret-up fa-lg text"></i></a> </li>
                  </ul>
                  <a href="#" class="panel-toggle text-muted active"> <i class="fa fa-search"></i> Search </a> </header>
                <div class="panel-body clearfix <?php echo $class; ?>">
                  <div class="row">
                     
                     <div class="col-lg-4">
                      <div class="form-group">
                        <label class="col-lg-5 control-label">Company Name</label>
                        <div class="col-lg-7">
                          <input type="text" name="filter_company" value="<?php echo isset($filter_company) ? $filter_company : '' ; ?>" placeholder="Company Name" id="input-name" class="form-control" />
                        </div>
                      </div>
                    </div>
                    
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label class="col-lg-5 control-label">Contact Name </label>
                        <div class="col-lg-7">
                          <input type="text" name="filter_name" value="<?php echo isset($filter_name) ? $filter_name : '' ; ?>" placeholder="Contact Name" id="input-name" class="form-control" />
                        </div>
                      </div>
                    </div>
                      
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-5 control-label">Posted By User</label>
                                <?php							
									$userlist = $obj_address->getUserList();
								?>
                                <div class="col-lg-7">
                                	<select class="form-control" name="filter_user_name">
                                    	<option value="">Please Select</option>
                                    	<?php foreach($userlist as $user) { ?>
                                    	    
                                        	<?php 
                                        	      $splitdata=explode("=",$filter_user_name);
                                        	if(!empty($splitdata) && $splitdata[0] == $user['user_type_id'] && $splitdata[1] ==$user['user_id']) { ?>
                                            
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
                        <label class="col-lg-5 control-label">Status</label>
                        <div class="col-lg-7">
                          <select name="filter_status" id="input-status" class="form-control">
                            <option value=""></option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                          </select>
                        </div>
                      </div>
                    </div>
                 </div>
                 
                  <div class="row">
                  <div class="col-lg-4">
                      <div class="form-group">
                        <label class="col-lg-5 control-label">Email</label>
                        <div class="col-lg-7">
                          <input type="text" name="filter_email" value="<?php echo isset($filter_email) ? $filter_email : '' ; ?>" placeholder="Email" id="input-name" class="form-control" />
                        </div>
                      </div>
                    </div>
                    
                
                    <!--<div class="col-lg-4">
                      <div class="form-group">
                        <label class="col-lg-5 control-label">Phone Number</label>
                        <div class="col-lg-7">
                          <input type="text" name="filter_number" value="<?php //echo isset($filter_number) ? $filter_number : '' ; ?>" placeholder="Phone Number" id="input-name" class="form-control" />
                        </div>
                      </div>
                    </div>-->
                    
                  <div class="col-lg-4">
                      <div class="form-group">
                        <label class="col-lg-5 control-label">Vat No.</label>
                        <div class="col-lg-7">
                          <input type="text" name="filter_vat" value="<?php echo isset($filter_vat) ? $filter_vat : '' ; ?>" placeholder="Vat NO" id="input-name" class="form-control" />
                        </div>
                      </div>
                    </div>
                  
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label class="col-lg-5 control-label">Website</label>
                        <div class="col-lg-7">
                          <input type="text" name="filter_website" value="<?php echo isset($filter_website) ? $filter_website : '' ; ?>" placeholder="Website" id="input-name" class="form-control" />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <footer class="panel-footer <?php echo $class; ?>">
                  <div class="row">
                    <div class="col-lg-12">
                      <button type="submit" class="btn btn-primary btn-sm pull-right ml5" name="btn_filter"><i class="fa fa-search"></i> Search</button>
                      <a href="<?php echo $obj_general->link($rout, '', '',1); ?>" class="btn btn-info btn-sm pull-right" ><i class="fa fa-refresh"></i> Refresh</a> </div>
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
                  <option value="<?php echo $obj_general->link($rout, $link.'&limit='.$display_limit.'&filter_edit=1', '',1);?>" selected="selected"><?php echo $display_limit; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $obj_general->link($rout, $link.'&limit='.$display_limit.'&filter_edit=1', '',1);?>"><?php echo $display_limit; ?></option>
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
                    <th>Company Name</th>
                    <th>Contact Name</th>
                    <th>Email ID</th>
                    <th>Vat No.</th>
                    <th>Designation</th>
                    <th>Department</th>
                    <th>Website</th>
                    <?php if(!isset($user_id)){ ?>
                        <th>Status</th>
                       
                        <th>Action</th>
					<?php } ?>
					 <th>Posted By</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $total_address = $obj_address->get_customer_total_address($filter_data,$user_id,$user_type_id);
                  //printr($total_address);
                  $pagination_data = '';
                  if($total_address){
                                if (isset($_GET['page'])) {
                                    $page = (int)$_GET['page'];
        			} else {
                                    $page = 1;
        			}
                     
                        //printr($page);
			if (isset($_GET['sort'])) {
                            $sort_option = $_GET['sort'];
							//printr($sort_option);die;
                        } else {
                            $sort_option = 'e.address_book_id';
							//printr($sort_option);die;
                        }
			//printr($sort_option);			
                       // printr($page);			
                      //oprion use for limit or and sorting function	
                      $option = array(
                            'sort'  => $sort_option,
                            'order' => $sort_order,
                            'start' => ($page - 1) * $limit,
                            'limit' => $limit
                      );
                      //printr($page);
                      //printr(($page - 1) * $limit);
		//	printr($filter_data);die;	
                      $customer_address = $obj_address->get_customer_address($option,$filter_data,$user_id,$user_type_id);
                       // printr($customer_address);die;
					  
		  foreach($customer_address as $address){ 
				//	printr($address);
                        ?>
                  <tr>
                  <!--<a href="<?php //echo  $obj_general->link($rout, 'mod=view&address_book_id='.encode($address['address_book_id']), '',1); ?>">-->
                    <td><?php //$address['address_book_id']; ?>
                      <input type="checkbox" name="post[]" value="<?php echo $address['address_book_id'];?>">
                      </a></td>
                    <td><a href="<?php echo $obj_general->link($rout, 'mod=view&address_book_id='.encode($address['address_book_id']), '',1);?>"><?php echo $address['company_name'];?><br />
                      <small class="text-muted">
                      <?php //echo ucwords($enquiry['enquiry_for']);?>
                      </small></a></td>
                    <td><a href="<?php echo $obj_general->link($rout, 'mod=view&address_book_id='.encode($address['address_book_id']), '',1); ?>"><?php echo $address['contact_name'];?></a></td>
                    <td><a href="<?php echo $obj_general->link($rout, 'mod=view&address_book_id='.encode($address['address_book_id']), '',1); ?>"><?php echo $address['email_1'];?></a></td>
                    <td><a href="<?php echo $obj_general->link($rout, 'mod=view&address_book_id='.encode($address['address_book_id']), '',1); ?>"><?php echo $address['vat_no'];?></a></td>
                    <td><a href="<?php echo $obj_general->link($rout, 'mod=view&address_book_id='.encode($address['address_book_id']), '',1);?>"><?php echo $address['designation'];?></a></td>
                    <td><a href="<?php echo $obj_general->link($rout, 'mod=view&address_book_id='.encode($address['address_book_id']), '',1);?>"><?php echo $address['department'];?></td>
                      </a>
                    <!--<td><a href="<?php //echo $obj_general->link($rout, 'mod=view&address_book_id='.encode($address['address_book_id']), '',1);?>">
						  <?php //echo $enquiry['mobile_number'];?> <br />
                          	 <?php //if(!empty($enquiry['phone_number'])) { ?>
                             	<small><?php //echo $enquiry['phone_number']; ?></small>
                             <?php //} ?></a>   
                          </td>-->
                          
                    <td><a href="<?php echo $obj_general->link($rout, 'mod=view&address_book_id='.encode($address['address_book_id']), '',1);?>"><?php echo $address['website']; ?></a></td>
                
                <?php if(!isset($user_id)){ ?>
                    <td><div data-toggle="buttons" class="btn-group">
                        <label class="btn btn-xs btn-success <?php echo ($address['status']==1) ? 'active' : '';?> ">
                          <input type="radio" name="status" value="1" id="<?php echo $address['address_book_id']; ?>">
                          <i class="fa fa-check text-active"></i>Active </label>
                        <label class="btn btn-xs btn-danger <?php echo ($address['status']==0) ? 'active' : '';?> ">
                          <input type="radio" name="status" value="0" id="<?php echo $address['address_book_id']; ?>">
                          <i class="fa fa-check text-active"></i>Inactive </label>
                      </div></td>
                       
                    
                    <td><?php if($obj_general->hasPermission('edit',$menuId)){ ?>
                      <a href="<?php echo $obj_general->link($rout, 'mod=add&address_book_id='.encode($address['address_book_id']), '',1); ;?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                      <?php }?></td>
                    
                    <?php } ?>
                    <td>
                          	
                          	<?php
									$postedByData = $obj_address->getUser($address['address_user_id'],$address['address_user_type_id']);
									$addedByImage = $obj_general->getUserProfileImage($address['address_user_type_id'],$address['address_user_id'],'100_');
									$postedByInfo = '';
									if($addedByImage){
									$postedByInfo .= '<div class="row">';
										$postedByInfo .= '<div class="col-lg-3"><img src="'.$addedByImage.'"></div>';
										$postedByInfo .= '<div class="col-lg-9">';
										if($postedByData['city']){ $postedByInfo .= $postedByData['city'].', '; }
										if($postedByData['state']){ $postedByInfo .= $postedByData['state'].' '; }
										if(isset($postedByData['postcode'])){ $postedByInfo .= $postedByData['postcode']; }
										$postedByInfo .= '<br>Telephone : '.$postedByData['telephone'].'</div>';
									$postedByInfo .= '</div>';
									}
								?>
								
                                 
								<a class="btn btn-info btn-xs" data-trigger="hover" data-toggle="popover" data-html="true" data-placement="top" 
								data-content='<?php echo $postedByInfo;?>' title="" data-original-title='<b><?php echo $postedByData['first_name'].' '.$postedByData['last_name'];;?></b>' href="javascript:void(0);"><?php echo $postedByData['first_name'].' '.$postedByData['last_name'];;?></a>
                          	
                          
                          <?php /*
                                <a href="<?php echo $obj_general->link($rout, 'mod=view&enquiry_id='.encode($enquiry['enquiry_id']), '',1); ;?>"  name="btn_edit" class="btn btn-info btn-xs"><?php echo $enquiry['user_name']; ?></a>
								*/ ?>
                           </td>
                  </tr>
                  <?php
                      }
                        
                        //pagination
                        $pagination = new Pagination();
                        $pagination->total = $total_address;
                        $pagination->page = $page;
                        $pagination->limit = $limit;
                        $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                        $pagination->url = $obj_general->link($rout, $link.'&page={page}&limit='.$limit.'&filter_edit=1', '',1);
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
              <?php echo $pagination_data;?> </div>
          </footer>
        </section>
      </div>
    </div>
  </section>
</section>
<script type="application/javascript">

	$('input[type=radio][name=status]').change(function() {
		$("#loading").show();
		var address_id = $(this).attr('id');
		//alert(address_id);
		var status_value = this.value;
		//alert(status_value);
		var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=update_address_status', '',1);?>");
        $.ajax({			
			url : status_url,
			type :'post',
			data :{address_id:address_id,status_value:status_value},
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
		var csv_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=csv_address', '',1);?>");
		var formData = $("#form_list").serialize();	
		$.ajax({
				url : csv_url,
				type :'post',
				data :{formData:formData},
				success: function(re){
				//alert(re);
			//$('#test').html(re);
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
					//set_alert_message('Error During Updation',"alert-warning","fa-warning");          
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
</script>
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>
