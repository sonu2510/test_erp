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

$filter_data=array();
$filter_value='';

$class='collapse';

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
	$filter_email = $obj_session->data['filter_data']['email'];
	$filter_status = $obj_session->data['filter_data']['status'];
	$class = '';
	
	$filter_data=array(
		'name' => $filter_name,
		'email' => $filter_email,
		'status' => $filter_status
	);	
}

if(isset($_POST['btn_filter'])){
	
	$filter_edit = 1;
	$class='';		
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
	
	if(isset($_POST['filter_status'])){
		$filter_status=$_POST['filter_status'];
	}else{
		$filter_status='';
	}
		
	$filter_data=array(
		'name' => $filter_name,
		'email' => $filter_email,
		'status' => $filter_status
	);
	
	$obj_session->data['filter_data'] = $filter_data;
	
  	
}


if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'ASC';	
}


if(isset($_GET['sort'])){
	$sort_name = $_GET['sort'];	
}else{
	$sort_name = 'international_branch_id';
}

if($obj_session->data['ADMIN_LOGIN_SWISS']!= '1' && $obj_session->data['LOGIN_USER_TYPE'] != '1')
{
	echo $obj_session->data['ADMIN_LOGIN_SWISS'].'==='. $obj_session->data['LOGIN_USER_TYPE'];
	$branchs = $obj_invoice->getBranchs('','',$obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);
	foreach($branchs as $branch)
	{
		page_redirect($obj_general->link($rout, 'mod=branch_inventory&branch_id='.encode($branch['international_branch_id']).'','',1));
	}
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
		$obj_invoice->updateStatus($status,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
}else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
	if(!$obj_general->hasPermission('delete',$menuId)){
		$display_status = false;
	} else {
		//printr($_POST['post']);die;
		$obj_invoice->updateStatus(2,$_POST['post']);
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
          <header class="panel-heading"> 
		  	
			<span><?php echo $display_name;?> Listing</span>          		
      	</header>
          <div class="panel-body">
            <div class="row text-small">
              
              <?php /* <div class="col-sm-3">
              	  <select class="form-control" id="branch-filter-dropdown">
                 	
                  	<option value="filter_name=">Name</option>
                    <?php if(isset($_GET['filter_email'])) { ?>                   
                    	<option value="filter_email=" selected="selected">Email</option>
                    <?php } else { ?>
                   		<option value="filter_email=">Email</option>
                    <?php } ?>
                  </select>
              
              </div>
                    
              <div class="col-sm-4">
                <div class="input-group">
                  <input type="text" class="input-sm form-control" id="branch-search-textbox" placeholder="Search" value="<?php echo $filter_value; ?>">
                  <span class="input-group-btn">
                  <button class="btn btn-sm btn-white" id="branch-filter-btn" type="button">Go!</button>
                  <button class="btn btn-sm btn-white" id="branch-refresh-btn" type="button"><i class="fa fa-refresh"></i></button>
                  </span> </div>
              </div> */ ?>
              
            </div>
            
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
                                <label class="col-lg-2 control-label">Name</label>
                                <div class="col-lg-10">
                                  <input type="text" name="filter_name" value="<?php echo isset($filter_name) ? $filter_name : '' ; ?>" placeholder="Name" id="input-name" class="form-control" />
                                </div>
                              </div>
                              <div class="form-group">
                                <label class="col-lg-2 control-label">Email</label>
                                <div class="col-lg-10">
                                 <input type="text" name="filter_email" value="<?php echo isset($filter_email) ? $filter_email : '' ; ?>" placeholder="Email" id="input-name" class="form-control" />
                                </div>
                              </div>
                          </div>
                          <?php /*?><div class="col-lg-4">                              
                               <div class="form-group">
                                <label class="col-lg-4 control-label">Status</label>
                                <div class="col-lg-8">
                                  <select name="filter_status" id="input-status" class="form-control">
                                        <option value=""></option>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                   </select>
                                </div>
                              </div>
                          </div><?php */?>                    
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
                 <select class="form-control" id="limit-dropdown" onChange="location=this.value;">
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
                  <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">
                      Name
                      <span class="th-sort">
                       	<a href="<?php echo $obj_general->link($rout, 'sort=ib.user_name'.'&order=ASC', '',1);?>">
                        <i class="fa fa-sort-down text"></i>
                        <a href="<?php echo $obj_general->link($rout, 'sort=ib.user_name'.'&order=DESC', '',1);?>">
                        <i class="fa fa-sort-up text-active"></i>
                      <i class="fa fa-sort"></i></span>
                  </th>
                  
                  <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">
                      Email
                      <span class="th-sort">
                       	<a href="<?php echo $obj_general->link($rout, 'sort=email'.'&order=ASC', '',1);?>">
                        <i class="fa fa-sort-down text"></i>
                        <a href="<?php echo $obj_general->link($rout, 'sort=email'.'&order=DESC', '',1);?>">
                        <i class="fa fa-sort-up text-active"></i>
                      <i class="fa fa-sort"></i></span>
                  </th>
                  <th>Country</th>
                  <th>Default Currency</th>
                </tr>
              </thead>
              <tbody>
              <?php
              $total_branch = $obj_invoice->getTotalBranch($filter_data,$obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);
			  $pagination_data = '';
			  if($total_branch){
				   	if (isset($_GET['page'])) {
						$page = $_GET['page'];
					} else {
						$page = 1;
					}
				  //oprion use for limit or and sorting function	
				  $option = array(
				  		'sort'  => $sort_name,
						'order' => $sort_order,
				  		'start' => ($page - 1) * $limit,
						'limit' => $limit
				  );	
				  $branchs = $obj_invoice->getBranchs($option,$filter_data,$obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);
				  if($branchs) {
				  foreach($branchs as $branch){ 
					//printr($branch);?>
                    <tr>
                      <td><a href="<?php echo $obj_general->link($rout, 'mod=branch_inventory&branch_id='.encode($branch['international_branch_id']).'','',1);?>"><?php echo $branch['name'];?></a></td>
                      <td><a href="<?php echo $obj_general->link($rout, 'mod=branch_inventory&branch_id='.encode($branch['international_branch_id']).'','',1);?>"><?php echo $branch['email'];?></a></td>
                       <td><a href="<?php echo $obj_general->link($rout, 'mod=branch_inventory&branch_id='.encode($branch['international_branch_id']).'','',1);?>"><?php echo $obj_invoice->getCountryName($branch['country_id']);?></a></td>
                      <td><a href="<?php echo $obj_general->link($rout, 'mod=branch_inventory&branch_id='.encode($branch['international_branch_id']).'','',1);?>"><?php echo $obj_invoice->getdefaultcurrencyCode($branch['default_curr']);?></a></td>
                      </tr>
                    <?php
				  }
				  }else{
					  echo "<tr><td colspan='5'>No record found !</td></tr>";	
				  }
					//pagination
				  	$pagination = new Pagination();
					$pagination->total = $total_branch;
					$pagination->page = $page;
					$pagination->limit = $limit;
					$pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
					$pagination->url = $obj_general->link($rout, '&limit='.$limit.'&page={page}', '',1);
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


<script>

	$("#branch-search-textbox").keyup(function(event){
		if(event.keyCode == 13){
			var location;

			location='<?php echo $obj_general->link($rout, '', '',1); ?>';
			location += '&'+$('#asso-filter-dropdown').val()+$('#asso-search-textbox').val();
			
			redirect(location);
		}
	});
	
	$('#branch-filter-btn').click(function(){
		var location;

		location='<?php echo $obj_general->link($rout, '', '',1); ?>';
		location += '&'+$('#branch-filter-dropdown').val()+$('#branch-search-textbox').val();
		
		redirect(location);
		//alert(location);		
	});
	
	$('#branch-refresh-btn').click(function(){
		
		var location='<?php echo $obj_general->link($rout, '', '',1); ?>';
		redirect(location);
	});
	
	
</script>