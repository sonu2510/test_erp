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

$all_emp=0;$all_url='';
if(isset($_GET['all_emp']))
{
	$all_url='&all_emp=1';
	$all_emp=1;
}



$filter_value='';

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
if(isset($obj_session->data['filter_data'])){
	$filter_number = $obj_session->data['filter_data']['filter_number'];
	$filter_company = $obj_session->data['filter_data']['filter_company'];
	$filter_name = $obj_session->data['filter_data']['filter_name'];
	$filter_email = $obj_session->data['filter_data']['filter_email'];
	$filter_industry = $obj_session->data['filter_data']['enquiry_industry_id'];
	$filter_country = $obj_session->data['filter_data']['country_id'];
	$filter_status = $obj_session->data['filter_data']['filter_status'];
	$filter_user_name = $obj_session->data['filter_data']['filter_user_name'];
	
		$filter_data=array(
		'enquiry_number' => $filter_number,
		'company' => $filter_company,
		'customer' => $filter_name,
		'email' => $filter_email,
		'industry'=>$filter_industry,
		'country' => $filter_country,
		'status' => $filter_status,
		'postedby' => $filter_user_name,
		//'filter_ib_user_name'=> $filter_ib_user_name
	);
}
if(isset($_POST['btn_filter'])){
	
	$filter_edit = 1;
	$class ='';	
	if(isset($_POST['filter_number'])){
		$filter_number=$_POST['filter_number'];		
	}else{
		$filter_number='';
	}
	
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
	
	if(isset($_POST['filter_email'])){
		$filter_email=$_POST['filter_email'];		
	}else{
		$filter_email='';
	}
	if(isset($_POST['enquiry_industry_id'])){
		$filter_industry=$_POST['enquiry_industry_id'];
	}else{
		$filter_industry='';
	}
	
		
	if(isset($_POST['country_id'])){
		$filter_country=$_POST['country_id'];
	}else{
		$filter_country='';
	}
	
	if(isset($_POST['filter_status'])){
		$filter_status=$_POST['filter_status'];
	}else{
		$filter_status='';
	}
	if(isset($_POST['filter_user_name']))
	{
		$filter_user_name = $_POST['filter_user_name'];
	}else{
		$filter_user_name='';
	}
		
	$filter_data=array(
		'enquiry_number' => $filter_number,
		'company' => $filter_company,
		'customer' => $filter_name,
		'email' => $filter_email,
		'industry'=>$filter_industry,
		'country' => $filter_country,
		'status' => $filter_status,
		'postedby' => $filter_user_name,
		//'filter_ib_user_name'=> $filter_ib_user_name
	);
	
	$obj_session->data['filter_data'] = $filter_data;
}
if(isset($_GET['page'])){
	if(isset($_SESSION['filter_data']) && !empty($_SESSION['filter_data'])) {
	$filter_data = ($_SESSION['filter_data']);
	}
}
//printr($filter_data);
if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'DESC';	
}
$address_id = '0';
$add_url='';
if (isset($_GET['address_book_id'])) {
    $address_id = decode($_GET['address_book_id']);
    $add_url='&address_book_id='.$_GET['address_book_id'];
}

//$send_email = $obj_enquiry->sendemailenquiry();


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
		$obj_enquiry->updateStatus($status,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, ''.$add_url, '',1)); 
	}
}else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
	if(!$obj_general->hasPermission('delete',$menuId)){
		$display_status = false;
	} else {
		//printr($_POST['post']);die;
		$obj_enquiry->updateStatus(2,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, ''.$add_url, '',1));
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
       
          	<span><?php echo $display_name;?> Listing</span>
          	<span class="text-muted m-l-small pull-right">
          			
                 <a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add'.$add_url, '',1);?>"><i class="fa fa-plus"></i> New Enquiry </a>
                    <?php if($obj_general->hasPermission('edit',$menuId)){ ?>
                        <a class="label bg-success" style="margin-left:4px;" onclick="formsubmitsetaction('form_list','active','post[]','<?php echo ACTIVE_WARNING;?>')"><i class="fa fa-check"></i> Active</a>
                        <a class="label bg-warning" onclick="formsubmitsetaction('form_list','inactive','post[]','<?php echo INACTIVE_WARNING;?>')"><i class="fa fa-times"></i> Inactive</a>
                        <a class="label bg-danger" onclick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>
                    <?php } ?>                      
                    
            </span>
           
          </header>
          <div class="panel-body">
              <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link($rout, ''.$add_url, '',1); ?>">
                <section class="panel pos-rlt clearfix">
                  <header class="panel-heading">
                    <ul class="nav nav-pills pull-right">
                      <li> <a href="#" class="panel-toggle text-muted active"><i class="fa fa-caret-down fa-lg text-active"></i><i class="fa fa-caret-up fa-lg text"></i></a> </li>
                    </ul>
                <a href="#" class="panel-toggle text-muted active"> <i class="fa fa-search"></i> Search </a>
                  </header>
              
              
              
                 <div class="panel-body clearfix <?php echo $class; ?>">        
                      <div class="row">
                          <div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-5 control-label">Enquiry Number </label>
                                <div class="col-lg-7">
                                  <input type="text" name="filter_number" value="<?php echo isset($filter_number) ? $filter_number : '' ; ?>" placeholder="Number" id="input-name" class="form-control" />
                                </div>
                              </div>                             
                          </div>
                          
                          
                          <div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-5 control-label">Company Name</label>
                                <div class="col-lg-7">
                                  <input type="text" name="filter_company" value="<?php echo isset($filter_company) ? $filter_company : '' ; ?>" placeholder="Company" id="input-name" class="form-control" />
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
                                <label class="col-lg-5 control-label">Client Name</label>
                                <div class="col-lg-7">
                                  <input type="text" name="filter_name" value="<?php echo isset($filter_name) ? $filter_name : '' ; ?>" placeholder="Name" id="input-name" class="form-control" />
                                </div>
                              </div>                             
                          </div>
                          
                          <div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-5 control-label">Email</label>
                                <div class="col-lg-7">
                                  <input type="text" name="filter_email" value="<?php echo isset($filter_email) ? $filter_email : '' ; ?>" placeholder="Email" id="input-name" class="form-control" />
                                </div>
                              </div>                             
                          </div>
                          
                          
                          
                           
                          <div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-5 control-label">Country</label>
                                <div class="col-lg-7">
                                  <?php 
									$sel_country = (isset($filter_country))?$filter_country:'';
									$countrys = $obj_general->getCountryCombo($sel_country);
									echo $countrys;
								   ?>   
                                </div>
                              </div>                             
                          </div>                  
                      </div>      
                   <div class="row">
                                         
                                         <div class="col-lg-4">
                                       
                                                <div class="form-group">
                                                    <label class="col-lg-5 control-label">User</label>
                                                    <div class="col-lg-7">
                                                    <?php							
														$userlist = $obj_enquiry->getUserList();
													?>
                                					<select class="form-control" name="filter_user_name">
                                    					<option value="">Please Select</option>
                                    					<?php foreach($userlist as $user) { ?>
                                        				<?php if(isset($filter_usert_name) && !empty($filter_user_name) && $filter_user_name == $user['user_name']) { ?>
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
                        <button type="submit" class="btn btn-primary btn-sm pull-right ml5" name="btn_filter"><i class="fa fa-search"></i> Search</button>
                        <a href="<?php echo $obj_general->link($rout, ''.$add_url, '',1); ?>" class="btn btn-info btn-sm pull-right" ><i class="fa fa-refresh"></i> Refresh</a>
                       </div> 
                    </div>
                  </footer>                                  
              </section>
         	</form>
          
              <div class="row">
           
								<!-- <div class="pull-left">
								    <div class="panel-body text-muted l-h-2x">-->
               
								<?php /*
								    $show_per = $obj_enquiry->getMenuPermission();
									if($show_per=='1' AND $_SESSION['LOGIN_USER_TYPE']=='2')
									{ */?>
											<!--<a href="<?php //echo $obj_general->link($rout, 'mod=index&all_emp=1','',1); ?>" class="btn btn-primary btn-sm pull-right ml5" name="daywise" style="background-color:#CBC6AB"> All Leads</a>
											<a href="<?php //echo $obj_general->link($rout, 'mod=index','',1); ?>" class="btn btn-primary btn-sm pull-right ml5" name="alljobs" style="background-color:#81C267">Your Leads</a>-->
                
								<?php	
									//}
									?>
									<!--</div>
								</div>-->
            
            
             <div class="col-lg-3 pull-right">	
                 <select class="form-control" id="limit-dropdown" onchange="location=this.value;">
                 <option value="<?php echo $obj_general->link($rout, ''.$add_url, '',1);?>" selected="selected">--Select--</option>
                    	<?php 
							$limit_array = getLimit(); 
							foreach($limit_array as $display_limit) {
								if($limit == $display_limit) {	 
						?>
                        	
                        		<option value="<?php echo $obj_general->link($rout, 'limit='.$display_limit.$add_url, '',1);?>" selected="selected"><?php echo $display_limit; ?></option>				
						<?php } else { ?>
                            	<option value="<?php echo $obj_general->link($rout, 'limit='.$display_limit.$add_url, '',1);?>"><?php echo $display_limit; ?></option>
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
                      <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">
                      		 Enquiry Number 
                            <span class="th-sort">
                            	<a href="<?php echo $obj_general->link($rout, 'sort=enquiry_number'.$add_url .'&order=ASC', '',1);?>">
                                <i class="fa fa-sort-down text"></i>
                                <a href="<?php echo $obj_general->link($rout, 'sort=enquiry_number'.$add_url .'&order=DESC', '',1);?>">
                                <i class="fa fa-sort-up text-active"></i>
                            <i class="fa fa-sort"></i></span>
                      </th>
                      <th>Company</th>
                      <th>Name</th>
                      <th>Date</th>
                      <th>Email</th>
                      <th>Mobile/Phone </th>
                      <th>Industry</th>
                      <th>Country</th>
                      <th>Enquiry Source</th>
                      <th>Status</th>                     
                      <th>Posted By</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php //printr($filter_data);
                  $total_enquiry = $obj_enquiry->getTotalEnquiry($filter_data,$address_id,$all_emp);
                  //printr($total_enquiry);
				  $pagination_data = '';
                  if($total_enquiry){
                        if (isset($_GET['page'])) {
                            $page = (int)$_GET['page'];
                        } else {
                            $page = 1;
                        }
						
						 if (isset($_GET['sort'])) {
                            $sort_option = $_GET['sort'];
                        } else {
                            $sort_option = 'enquiry_id';
                        }
						
                      //oprion use for limit or and sorting function	
                      $option = array(
                            'sort'  => $sort_option,
                            'order' => $sort_order,
                            'start' => ($page - 1) * $limit,
                            'limit' => $limit
                      );	
                      $enquiries = $obj_enquiry->getEnquiries($option,$filter_data,$address_id,$all_emp);
                      //printr($enquiries);die;
					  
					  foreach($enquiries as $enquiry){ 
                        ?>
                        <tr>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&enquiry_id='.encode($enquiry['enquiry_id']).$add_url, '',1); ?>"><input type="checkbox" name="post[]" value="<?php echo $enquiry['enquiry_id'];?>"></a></td>
                          <td>
						  	<a href="<?php echo $obj_general->link($rout, 'mod=view&enquiry_id='.encode($enquiry['enquiry_id']).$add_url, '',1);?>"><?php echo $enquiry['enquiry_number'];?><br />
                          	<small class="text-muted"><?php echo ucwords($enquiry['enquiry_for']);?></small></a>
                          </td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&enquiry_id='.encode($enquiry['enquiry_id']).$add_url, '',1); ?>"><?php echo $enquiry['company_name'];?></a></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&enquiry_id='.encode($enquiry['enquiry_id']).$add_url, '',1);?>"><?php echo $enquiry['name'];?></a></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&enquiry_id=' . encode($enquiry['enquiry_id']).$add_url, '', 1); ?>"><?php echo dateFormat(4, $enquiry['date_added']);  ?></a></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&enquiry_id='.encode($enquiry['enquiry_id']).$add_url, '',1);?>"><?php echo $enquiry['email'];?></td></a>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&enquiry_id='.encode($enquiry['enquiry_id']).$add_url, '',1);?>">
						  <?php echo $enquiry['mobile_number'];?> <br />
                          	 <?php if(!empty($enquiry['phone_number'])) { ?>
                             	<small><?php echo $enquiry['phone_number']; ?></small>
                             <?php } ?></a>   
                          </td>
                           <td><a href="<?php echo $obj_general->link($rout, 'mod=view&enquiry_id='.encode($enquiry['enquiry_id']).$add_url, '',1);?>"><?php echo $enquiry['industry']; ?></a></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&enquiry_id='.encode($enquiry['enquiry_id']).$add_url, '',1);?>"><?php echo $enquiry['country_name']; ?></a></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&enquiry_id='.encode($enquiry['enquiry_id']).$add_url, '',1);?>"><?php echo $enquiry['source']; ?></a></td>
                          <td>
                          	<div data-toggle="buttons" class="btn-group">
                                <label class="btn btn-xs btn-success <?php echo ($enquiry['status']==1) ? 'active' : '';?> ">
                                	<input type="radio" name="status" value="1" id="<?php echo $enquiry['enquiry_id']; ?>">
                                 	<i class="fa fa-check text-active"></i>Active
                                </label>
                                     
                                <label class="btn btn-xs btn-danger <?php echo ($enquiry['status']==0) ? 'active' : '';?> ">
                                	<input type="radio" name="status" value="0" id="<?php echo $enquiry['enquiry_id']; ?>">
                                    <i class="fa fa-check text-active"></i>Inactive
                                </label> 
                            </div>
                          </td>
                          <td>
                          	
                          	<?php
									$postedByData = $obj_enquiry->getUser($enquiry['user_id'],$enquiry['user_type_id']);
									$addedByImage = $obj_general->getUserProfileImage($enquiry['user_type_id'],$enquiry['user_id'],'100_');
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
								data-content='<?php echo $postedByInfo;?>' title="" data-original-title='<b><?php echo $postedByData['first_name'].' '.$postedByData['last_name'];;?></b>' href="javascript:void(0);"><?php echo $enquiry['user_name'];?></a>
                          	
                          
                          <?php /*
                                <a href="<?php echo $obj_general->link($rout, 'mod=view&enquiry_id='.encode($enquiry['enquiry_id']), '',1); ;?>"  name="btn_edit" class="btn btn-info btn-xs"><?php echo $enquiry['user_name']; ?></a>
								*/ ?>
                           </td>
                         
                            <td>
                           	      <?php if($obj_general->hasPermission('edit',$menuId)){ ?>
							   <a href="<?php echo $obj_general->link($rout, 'mod=add&enquiry_id='.encode($enquiry['enquiry_id']).$add_url, '',1); ;?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
								<?php }?>	
                               </td>
                        </tr>
                        <?php
                      }
                        
                        //pagination
                        $pagination = new Pagination();
                        $pagination->total = $total_enquiry;
                        $pagination->page = $page;
                        $pagination->limit = $limit;
                        $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                        $pagination->url = $obj_general->link($rout, '&page={page}&limit='.$limit.''.$add_url.''.$all_url.'&filter_edit=1', '',1);
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
<script type="application/javascript">

	$('input[type=radio][name=status]').change(function() {
		$("#loading").show();
		var enquiry_id = $(this).attr('id');
		var status_value = this.value;
		var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=updateEnquiryStatus'.$add_url, '',1);?>");
        $.ajax({			
			url : status_url,
			type :'post',
			data :{enquiry_id:enquiry_id,status_value:status_value},
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

</script>           
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>
