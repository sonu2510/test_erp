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
$class = 'collapse';

$filter_data= array();
if(isset($_POST['btn_filter'])){
	
	$class = '';
		
	$filter_edit = 1;
	$class ='';	
	if(isset($_POST['filter_job'])){
    $filter_job=$_POST['filter_job'];   
  }else{
    $filter_job='';
  } 
  if(isset($_POST['filter_job_no'])){
		$filter_job_no=$_POST['filter_job_no'];		
	}else{
		$filter_job_no='';
	}
	if(isset($_POST['filter_product'])){
		$filter_product=$_POST['filter_product'];		
	}else{
		$filter_product='';
	}	
	
	if(isset($_POST['filter_status'])){
		$filter_status=$_POST['filter_status'];
	}else{
		$filter_status='';
	}
		
	$filter_data=array(
    'job_name' => $filter_job,
		'job_no' => $filter_job_no,
		'product' => $filter_product,
		'status' => $filter_status
	);
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
		//printr($_POST['post']);die;
		$status = 0;
		if($_POST['action'] == "active"){
			$status = 1;
		}
		$obj_job->updateStatus($status,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',2));
	}
}else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
	if(!$obj_general->hasPermission('delete',$menuId)){
		$display_status = false;
	} else {
		//printr($_POST['post']);die;
		$obj_job->updateStatus(2,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',2));
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
          			
                 <a class="label bg-primary" href="<?php echo $obj_general->link($rout, '&mod=add', '',1);?>"><i class="fa fa-plus"></i> New Job </a>
                    <?php if($obj_general->hasPermission('edit',$menuId)){ ?>
                        <a class="label bg-success" style="margin-left:4px;" onclick="formsubmitsetaction('form_list','active','post[]','<?php echo ACTIVE_WARNING;?>')"><i class="fa fa-check"></i> Active</a>
                        <a class="label bg-warning" onclick="formsubmitsetaction('form_list','inactive','post[]','<?php echo INACTIVE_WARNING;?>')"><i class="fa fa-times"></i> Inactive</a>
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
                                <label class="col-lg-2 control-label">Job Name</label>
                                <div class="col-lg-8">
                                  <input type="text" name="filter_job" value="<?php echo isset($filter_job) ? $filter_job : '' ; ?>" placeholder="Job Name" id="input-name" class="form-control" />
                                </div>
                              
                              </div>                             
                          </div>
                          <div class="col-lg-4">
                              <div class="form-group">
                                   <label class="col-lg-2 control-label">Job No</label>
                                <div class="col-lg-6">
                                  <input type="text" name="filter_job_no" value="<?php echo isset($filter_job_no) ? $filter_job_no : '' ; ?>" placeholder="Job No" id="input-name" class="form-control" />
                                </div>
                              
                              </div>                             
                          </div>
                            <div class="col-lg-4">
                                  <div class="form-group">
                                    <label class="col-lg-3 control-label"><span class="required">*</span> Select Product</label> 
                                    <div class="col-lg-6">
                                        <?php
                                        $products = $obj_job->getActiveProduct();
                                        ?>
                                        <select name="filter_product" id="filter_product"  class="form-control ">
                                        <option value="">Select Product</option>
                                            <?php
            								
                                            foreach($products as $product){
                                             
                                                    echo '<option value="'.$product['product_id'].'">'.$product['product_name'].'</option>';
                                                
                                            } ?>
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
                <table class="table b-t text-small table-hover">
                  <thead>
                    <tr>
                      <th width="20"><input type="checkbox"></th>
                      <!--<th>Material Name</th>-->
                       <th>Job No</th>
                      <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">
                      		Job Name
                            <span class="th-sort">
                            	<a href="<?php echo $obj_general->link($rout, 'sort=job_name'.'&order=ASC', '',1);?>">
                                <i class="fa fa-sort-down text"></i>
                                <a href="<?php echo $obj_general->link($rout, 'sort=job_name'.'&order=DESC', '',1);?>">
                                <i class="fa fa-sort-up text-active"></i>
                            <i class="fa fa-sort"></i></span>
                      </th>
                 
                     
                      <th>Product Name</th>
                      <th>Country </th>
                      <th>Layers </th>
                     
                      <th>Pouch Type </th>
                      <th>Print Type </th>
                       <th></th>
                      <th>Status</th>
                      <th>Action</th>
                       
                    </tr>
                  </thead>
                  <tbody>
                  <?php	
                  $total_job = $obj_job->getTotalJob($filter_data);
                  $pagination_data = '';
                  if($total_job){
                        if (isset($_GET['page'])) {
                            $page = (int)$_GET['page'];
                        } else {
                            $page = 1;
                        }
                      //oprion use for limit or and sorting function	
                      $option = array(
                          'sort'  => 'job_id',
                          'order' => $sort_order,
                          'start' => ($page - 1) * $limit,
                          'limit' => $limit
                      );
                  
				//	  printr($filter_data);
                      $jobs = $obj_job->getAllJobs($option,$filter_data);
					 
					  foreach($jobs as $job){ 
					    //   printr($job);
					  $country_name='';
					  $product_name='';
					  if($job['country_id']!=0 ){
							$country_name =$obj_job->get_country_name($job['country_id']);
					  }
					  if($job['product']!='0'){
							$product_name =$obj_job->get_product_name($job['product']);
					  }
					 
				//	    printr($country_name.'  '.$job['country_id']);
					//  printr($country_name);
                        ?>
                        <tr >
                          <td><input type="checkbox" name="post[]" value="<?php echo $job['job_id'];?>"></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&job_id='.encode($job['job_id']),'',1); ?>" ><?php echo $job['job_no'];?></a></td>         
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&job_id='.encode($job['job_id']),'',1); ?>" ><?php echo $job['job_name'];?></a><br> 
						 </td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&job_id='.encode($job['job_id']),'',1); ?>" ><?php echo $product_name;?></a></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&job_id='.encode($job['job_id']),'',1); ?>" ><?php echo $country_name; ?></a></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&job_id='.encode($job['job_id']),'',1); ?>" ><?php echo $job['layers'];?><a></a></td>
                         
                            <td><a href="<?php echo $obj_general->link($rout, 'mod=view&job_id='.encode($job['job_id']),'',1); ?>" ><?php echo $job['pouch_type'];?></a></td>
                            <td><a href="<?php echo $obj_general->link($rout, 'mod=view&job_id='.encode($job['job_id']),'',1); ?>" ><?php echo $job['print_type'];?></a></td>
                               <td>
						  <?php if($job['lamination_status']=='0' && $job['m_process']==3){?>
						   <a href="<?php echo $obj_general->link('lamination', 'mod=add&job_id='.encode($job['job_id']),'',1); ?>"   class="btn btn-info btn-xs">Go For Lamination</a>
						  <?php }
						 
						  ?>
							</td>
                   
                     
                          <td>
                          	<div data-toggle="buttons" class="btn-group">
                                <label class="btn btn-xs btn-success <?php echo ($job['status']==1) ? 'active' : '';?> "> <input type="radio" 
                                 name="status" value="1" id="<?php echo $job['job_id']; ?>"> <i class="fa fa-check text-active"></i>Active</label>                                   
                                <label class="btn btn-xs btn-danger <?php echo ($job['status']==0) ? 'active' : '';?> "> <input type="radio" 
                                    name="status" value="0" id="<?php echo $job['job_id']; ?>"> <i class="fa fa-check text-active"></i>Inactive</label> 
                            </div>
                          
                          </td>
                        
                          <td>	
                                <a href="<?php echo $obj_general->link($rout, 'mod=add&job_id='.encode($job['job_id']),'',1); ?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                                
                           </td>
                        </tr>
                        <?php
                      }
                        
                        //pagination
                        $pagination = new Pagination();
                        $pagination->total = $total_job;
                        $pagination->page = $page;
                        $pagination->limit = $limit;
                        $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                        $pagination->url = $obj_general->link($rout, '&page={page}&limit='.$limit.'', '',1);//HTTP_ADMIN.'index.php?rout='.$rout.'&page={page}';
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
<script type="application/javascript">

	$('input[type=radio][name=status]').change(function() {
	
		//alert($(this).attr('id'));
		var job_id=$(this).attr('id');
		var status_value = this.value;
		
		var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=updateJobStatus', '',1);?>");
        $.ajax({			
			url : status_url,
			type :'post',
			data :{job_id:job_id,status_value:status_value},
			success: function(){
				set_alert_message('Successfully Updated',"alert-success","fa-check");					
			},
			error:function(){
				set_alert_message('Error During Updation',"alert-warning","fa-warning");          
			}			
		});
    });

</script>           
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>