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


$filter_data=array();
$filter_value='';
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
$class = 'collapse';
if(isset($obj_session->data['filter_data'])){
	$filter_company = $obj_session->data['filter_data']['company'];
	$filter_name = $obj_session->data['filter_data']['customer'];
	$filter_email = $obj_session->data['filter_data']['email'];
	$filter_phone_no = $obj_session->data['filter_data']['filter_phone_no'];
    $filter_whatsapp = $obj_session->data['filter_data']['filter_whatsapp'];
    $filter_address = $obj_session->data['filter_data']['filter_address'];
	$filter_postedby = $obj_session->data['filter_data']['postedby'];

		$filter_data=array(
		'company' => $filter_company,
		'customer' => $filter_name,
		'email' => $filter_email,
		'filter_phone_no' => $filter_phone_no,
		'filter_whatsapp' => $filter_whatsapp,
		'filter_address' => $filter_address,
		'postedby' => $filter_user_name,
	
	);
}
if(isset($_POST['btn_filter'])){
	
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
	
	if(isset($_POST['filter_email'])){
		$filter_email=$_POST['filter_email'];		
	}else{
		$filter_email='';
	}
 
	if(isset($_POST['filter_user_name']))
	{
		$filter_user_name = $_POST['filter_user_name'];
	}else{
		$filter_user_name='';
	}
	if(isset($_POST['filter_phone_no']))
	{
		$filter_phone_no = $_POST['filter_phone_no'];
	}else{
		$filter_phone_no='';
	}
	if(isset($_POST['filter_whatsapp']))
	{
		$filter_whatsapp = $_POST['filter_whatsapp'];
	}else{
		$filter_whatsapp='';
	}	if(isset($_POST['filter_address']))
	{
		$filter_address = $_POST['filter_address'];
	}else{
		$filter_address='';
	}
		
	$filter_data=array(
		'company' => $filter_company,
		'customer' => $filter_name,
		'email' => $filter_email,
		'filter_phone_no' => $filter_phone_no,
		'filter_whatsapp' => $filter_whatsapp,
		'filter_address' => $filter_address,
		'postedby' => $filter_user_name,
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
		page_redirect($obj_general->link($rout, 'mod=customer_enquiry'.$add_url, '',1)); 
	}
}else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
	if(!$obj_general->hasPermission('delete',$menuId)){
		$display_status = false;
	} else {
		//printr($_POST['post']);die;
		$obj_enquiry->updateStatus(2,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, 'mod=customer_enquiry'.$add_url, '',1));
	}
}
	
?>
<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-list"></i> Customer Leads </h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>   
        
      <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading bg-white"> 
       
          	<span>Customer Leads Listing</span>
          
           
          </header>
          <div class="panel-body">
              <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link($rout,'&mod=customer_enquiry'.$add_url, '',1); ?>">
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
                                <label class="col-lg-5 control-label">Company Name</label>
                                <div class="col-lg-7">
                                  <input type="text" name="filter_company" value="<?php echo isset($filter_company) ? $filter_company : '' ; ?>" placeholder="Company" id="input-name" class="form-control" />
                                </div>
                              </div>                             
                          </div>
                           <div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-5 control-label">Customer Name</label>
                                <div class="col-lg-7">
                                  <input type="text" name="filter_name" value="<?php echo isset($filter_name) ? $filter_name : '' ; ?>" placeholder="Name" id="input-name" class="form-control" />
                                </div>
                              </div>                             
                          </div>
                          <div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-5 control-label">Mobile/Phone No</label>
                                <div class="col-lg-7">
                                  <input type="text" name="filter_phone_no" value="<?php echo isset($filter_name) ? $filter_phone_no : '' ; ?>" placeholder="Mobile/Phone No" id="input-name" class="form-control" />
                                </div>
                              </div>                             
                          </div>
                          
                          
                                       
                      </div>
                      
                      <div class="row">
                          
                      
                          <div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-5 control-label">Whatsapp Number</label>
                                <div class="col-lg-7">
                                  <input type="text" name="filter_whatsapp" value="<?php echo isset($filter_whatsapp) ? $filter_whatsapp : '' ; ?>" placeholder="Whatsapp Number" id="filter_whatsapp" class="form-control" />
                                </div>
                              </div>                             
                          </div> 
                          <div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-5 control-label">Address/Pin code</label>
                                <div class="col-lg-7">
                                  <input type="text" name="filter_address" value="<?php echo isset($filter_address) ? $filter_address : '' ; ?>" placeholder="Address/Pin code" id="filter_address" class="form-control" />
                                </div>
                              </div>                             
                          </div>
                          
                                            
                         <div class="col-lg-4">
                       
                                <div class="form-group">
                                    <label class="col-lg-5 control-label">User</label>
                                    <div class="col-lg-7">
                                    <?php							
										$userlist = $obj_enquiry->getUserListReport();
										$split= explode('=',$filter_user_name);
									?>
                					<select class="form-control" name="filter_user_name"  id="chosen_data">
                    					<option value="">Please Select</option>
                    					<?php foreach($userlist as $user) { //printr($split[1]==$user['employee_id']);	?>
                        			
                        				<?php if(!empty($split) && $split[0].'='.$split[1]=='2='.$user['employee_id']) { ?>
                    					    <option value="<?php echo "2=".$user['employee_id']; ?>" selected="selected"><?php echo $user['name'].' => ('.$user['user_name'].')'; ?></option>
                            			<?php } else { ?>
                            			    <option value="<?php echo "2=".$user['employee_id']; ?>"><?php echo $user['name'].' => ('.$user['user_name'].')'; ?></option>
                           				 <?php } ?>
                        				<?php } ?>                                       
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
                                  <input type="text" name="filter_email" value="<?php echo isset($filter_email) ? $filter_email : '' ; ?>" placeholder="Email" id="filter_email" class="form-control" />
                                </div>
                              </div>                             
                          </div> 
                                             
                         
                          
                           
                                     
                      </div> 
                 
                  </div>
                    
                  <footer class="panel-footer <?php echo $class; ?>">
                    <div class="row">
                       <div class="col-lg-12">
                        <button type="submit" class="btn btn-primary btn-sm pull-right ml5" name="btn_filter"><i class="fa fa-search"></i> Search</button>
                        <a href="<?php echo $obj_general->link($rout, 'mod=customer_enquiry'.$add_url, '',1); ?>" class="btn btn-info btn-sm pull-right" ><i class="fa fa-refresh"></i> Refresh</a>
                       </div> 
                    </div>
                  </footer>                                  
              </section>
         	</form>
          
              <div class="row">
           
							
            
             <div class="col-lg-3 pull-right">	
                 <select class="form-control" id="limit-dropdown" onchange="location=this.value;">
                 <option value="<?php echo $obj_general->link($rout, 'mod=customer_enquiry'.$add_url, '',1);?>" selected="selected">--Select--</option>
                    	<?php 
							$limit_array = getLimit(); 
							foreach($limit_array as $display_limit) {
								if($limit == $display_limit) {	 
						?>
                        	
                        		<option value="<?php echo $obj_general->link($rout, 'mod=customer_enquiry&limit='.$display_limit.$add_url, '',1);?>" selected="selected"><?php echo $display_limit; ?></option>				
						<?php } else { ?>
                            	<option value="<?php echo $obj_general->link($rout, 'mod=customer_enquiry&limit='.$display_limit.$add_url, '',1);?>"><?php echo $display_limit; ?></option>
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
                      <th>Customer Name</th>
                      <th>Date</th>
                      <th>Email</th>
                      <th>Address/Pin code</th>
                      <th>Mobile/Phone No </th>
                      <th>Posted By</th>
                      <th></th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php //printr($filter_data);//die;
                  $total_enquiry = $obj_enquiry->getTotalCustomerEnquiry($filter_data,$address_id,$all_emp);
            
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
                            $sort_option = 'reuest_id';
                        }
						
                      //oprion use for limit or and sorting function	
                      $option = array(
                            'sort'  => $sort_option,
                            'order' => $sort_order,
                            'start' => ($page - 1) * $limit,
                            'limit' => $limit
                      );	
                      $enquiries = $obj_enquiry->getCustomerEnquiries($option,$filter_data,$address_id,$all_emp);
                
					   
					  foreach($enquiries as $enquiry){
					    //  printr($enquiry); 
                        ?>
                        <tr >
                          <td><input type="checkbox" name="post[]" value="<?php echo $enquiry['reuest_id'];?>"></td>
                          
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view_customer_leads&reuest_id='.encode($enquiry['reuest_id']).$add_url, '',1); ?>"><?php echo $enquiry['company_name'];?></a></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view_customer_leads&reuest_id='.encode($enquiry['reuest_id']).$add_url, '',1);?>"><?php echo $enquiry['contact_name'];?></a></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view_customer_leads&reuest_id='.encode($enquiry['reuest_id']).$add_url, '', 1); ?>"><?php echo dateFormat(4, $enquiry['date_added']);  ?></a></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view_customer_leads&reuest_id='.encode($enquiry['reuest_id']).$add_url, '',1);?>"><?php echo $enquiry['email'];?></td></a>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view_customer_leads&reuest_id='.encode($enquiry['reuest_id']).$add_url, '',1);?>"><?php echo str_replace('\n','<br>',$enquiry['address']);?></td></a>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view_customer_leads&reuest_id='.encode($enquiry['reuest_id']).$add_url, '',1);?>"></a><?php echo $enquiry['phone_no'];?> </a> </td>
                           
                          <td>
                          	
                          	<?php
                          	
                          
									$postedByData = $obj_enquiry->getUser($enquiry['sales_emp_id'],'2');
								//		printr($postedByData);
									$addedByImage = $obj_general->getUserProfileImage('2',$enquiry['sales_emp_id'],'100_');
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
								data-content='<?php echo $postedByInfo;?>' title="" data-original-title='<b><?php echo $postedByData['first_name'].' '.$postedByData['last_name'];;?></b>' href="javascript:void(0);"><?php echo $postedByData['first_name'].' '.$postedByData['last_name'];?></a>
                          	
                          
                       
                           </td>
                           <td><?php if($enquiry['card_name']!=''){
                               echo'<i class="fa fa-paperclip" title="With Attachment" style="font-size:200%;"> </i> ';
                           }?></td>
                           <td><button type="button"  id="enquiry_add" onclick="add_to_leads('<?php echo encode($enquiry['reuest_id']);?>')"class="btn btn-primary btn-xs">Add To Leads</button></td>
                        </tr>
                        <?php
                      }
                        
                        //pagination
                        $pagination = new Pagination();
                        $pagination->total = $total_enquiry;
                        $pagination->page = $page;
                        $pagination->limit = $limit;
                        $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                        $pagination->url = $obj_general->link($rout, 'mod=customer_enquiry&page={page}&limit='.$limit.''.$add_url.''.$all_url.'&filter_edit=1', '',1);
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

<style>
     .chosen-container.chosen-container-single {
    width: 300px !important; 
}
</style>
<script src="https://harvesthq.github.io/chosen/chosen.jquery.js" type="text/javascript"></script>
<link rel="stylesheet" href="https://harvesthq.github.io/chosen/chosen.css" type="text/css"/> 
<script>
jQuery(document).ready(function(){
	  
	   $("#chosen_data").chosen();
	   
});
function add_to_leads(reuest_id){
    window.location.href='<?php echo HTTP_SERVER; ?>/admin/index.php?route=enquiry&mod=add&request_id='+reuest_id+'&status=1+&is_delete=0';
}
</script>
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>
