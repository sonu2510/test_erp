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

if(isset($obj_session->data['filter_data'])){
	$filter_name = $obj_session->data['filter_data']['name'];
	$filter_country = $obj_session->data['filter_data']['country'];
	$filter_status = $obj_session->data['filter_data']['status'];
	$class = '';
	
	$filter_data=array(
		'name' => $filter_name,	
		'country' =>$filter_country, 	
		'status' => $filter_status
	);	
}

if(isset($_POST['btn_filter'])){
	
	$filter_edit = 1;
	$class ='';	
	if(isset($_POST['filter_name'])){
		$filter_name=$_POST['filter_name'];		
	}else{
		$filter_name='';
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
		
	$filter_data=array(
		'name' => $filter_name,
		'country' =>$filter_country, 
		'status' => $filter_status
	);
	
	$obj_session->data['filter_data'] = $filter_data;
	
}

//sonu add 17-4-2017 for uk
	$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
	$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
	//$addedByInfo = $obj_custom_order->getUser($user_id,$user_type_id);
//	printr($addedByInfo['country_id'] );
	//end
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
		$obj_exhibition->updateStatus($status,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
}else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
	if(!$obj_general->hasPermission('delete',$menuId)){
		$display_status = false;
	} else {
		//printr($_POST['post']);die;
		$obj_exhibition->updateStatus(2,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
}

?>
<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-users"></i> <?php echo $display_name;?></h4>
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
   							<a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>"><i class="fa fa-plus"></i> New Exhibition </a>
                  	<?php }
					if($obj_general->hasPermission('edit',$menuId)){ ?>
                        <a class="label bg-success" style="margin-left:4px;" onclick="formsubmitsetaction('form_list','active','post[]','<?php echo ACTIVE_WARNING;?>')"><i class="fa fa-check"></i> Active</a>
                        <a class="label bg-warning" onclick="formsubmitsetaction('form_list','inactive','post[]','<?php echo INACTIVE_WARNING;?>')"><i class="fa fa-times"></i> Inactive</a>
                     <?php }
					if($obj_general->hasPermission('delete',$menuId)){ ?>       
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
                   <a href="#" class="panel-toggle text-muted active"> <i class="fa fa-search"></i> Search</a>
                  </header>
              
              
              
                 <div class="panel-body clearfix <?php echo $class; ?>">        
                      <div class="row">
                          <div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-2 control-label">Name</label>
                                <div class="col-lg-8">
                                  <input type="text" name="filter_name" value="<?php echo isset($filter_name) ? $filter_name : '' ; 
								  ?>" placeholder="Name" id="input-name" class="form-control" />
                                </div>
                              </div>                             
                          </div>
                          
                           <div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-2 control-label">Country</label>
                                <div class="col-lg-10">
                                 <?php
										 $sel_country =   (isset($filter_country)) ? $filter_country : '';
                                         $countrys = $obj_general->getCountryCombo($sel_country);
                                         echo $countrys;                 
									?>	  
                                </div>
                              </div>                             
                          </div>
                          
                          <div class="col-lg-4">                              
                               <div class="form-group">
                                <label class="col-lg-3 control-label">Status</label>
                                <div class="col-lg-8">
                                  <select name="filter_status" id="input-status" class="form-control">
                                        <option value=""></option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
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
						?>
                        	
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
            <table class="table table-striped b-t text-small">
              <thead>
                <tr>
                  <th width="20"><input type="checkbox"></th>
                    <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">
                      		Name
                            <span class="th-sort">
                            	<a href="<?php echo $obj_general->link($rout, 'sort=exhibition_name'.'&order=ASC', '',1);?>">
                                <i class="fa fa-sort-down text"></i>
                                <a href="<?php echo $obj_general->link($rout, 'sort=exhibition_name'.'&order=DESC', '',1);?>">
                                <i class="fa fa-sort-up text-active"></i>
                            <i class="fa fa-sort"></i></span>
                      </th>
                  <!--<th>Name</th>-->
                  <th>Country</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              <?php
              $total_exhibition = $obj_exhibition->getTotalExhibition($filter_data);
			  $pagination_data = '';
			  if($total_exhibition){
				   	if (isset($_GET['page'])) {
						$page = $_GET['page'];
					} else {
						$page = 1;
					}
				  //oprion use for limit or and sorting function	
				  $option = array(
				  		'sort'  => 'e.exhibition_id',
						'order' => 'DESC',
				  		'start' => ($page - 1) * LISTING_LIMIT,
						'limit' => LISTING_LIMIT
				  );	
				  
				  $exhibitions = $obj_exhibition->getExhibition($option,$filter_data);
				
				  foreach($exhibitions as $exhibition){ 
				 	//printr($exhibition);
					?>
                  
                    <tr>
                      <td><input type="checkbox" name="post[]" value="<?php echo $exhibition['exhibition_id'];?>"/></td>
                      <td><?php echo $exhibition['exhibition_name'];?></td>
                      <td><?php echo $exhibition['country_name'];?></td>
                      <td>	<div data-toggle="buttons" class="btn-group">
                                <label class="btn btn-xs btn-success <?php echo ($exhibition['status']==1) ? 'active' : '';?> ">
                                	<input type="radio" name="status" value="1" id="<?php echo $exhibition['exhibition_id']; ?>">
                                 	<i class="fa fa-check text-active"></i>Active
                                </label>
                                     
                                <label class="btn btn-xs btn-danger <?php echo ($exhibition['status']==0) ? 'active' : '';?> ">
                                	<input type="radio" name="status" value="0" id="<?php echo $exhibition['exhibition_id']; ?>">
                                    <i class="fa fa-check text-active"></i>Inactive
                                </label> 
                            </div>
                      </td>
                      <td>
                      		<?php if($obj_general->hasPermission('edit',$menuId)){ ?>
                      		<a href="<?php echo $obj_general->link($rout, 'mod=add&exhibition_id='.encode($exhibition['exhibition_id']), '',1); ;?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                           
                           <?php } ?> 
                       </td>
                    </tr>
                    <?php
				  }
				    
					//pagination
				  	$pagination = new Pagination();
					$pagination->total = $total_exhibition;
					$pagination->page = $page;
					$pagination->limit = LISTING_LIMIT;
					$pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
					$pagination->url = HTTP_ADMIN.'index.php?rout='.$rout.'&page={page}';
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
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>
