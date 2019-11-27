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
	'text' 	=> $display_name,
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
	$filter_name = $obj_session->data['filter_data']['domain_name'];

	$filter_data=array(
		'domain_name' => $filter_name,
	);	
}

if(isset($_POST['btn_filter']))
{
	$filter_edit = 1;
	//$class='';		
	if(isset($_POST['filter_name'])){
		$filter_name=$_POST['filter_name'];		
	}else{
		$filter_name='';
	}
	
	$filter_data=array(
		'domain_name' => $filter_name
	);
	
	$obj_session->data['filter_data'] = $filter_data;
}

if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'DESC';	
}
if(isset($_GET['sort'])){
	$sort_name = $_GET['sort'];	
}else{
	$sort_name = 'last_enquiry_date';
}

if($display_status) {
//printr($sort_order);
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
		  </div>
         
         <?php // searching part ?>
          <form class="form-horizontal" method="post" action="<?php echo $obj_general->link($rout, '', '',1); ?>">  <?php //data-validate="parsley" ?>
                
                <section class="panel pos-rlt clearfix">
                  <header class="panel-heading">
                    <ul class="nav nav-pills pull-right">
                      <li> <a href="#" class="panel-toggle text-muted active"><i class="fa fa-caret-down fa-lg text-active"></i><i class="fa fa-caret-up fa-lg text"></i></a> </li>
                    </ul>
                    <i class="fa fa-search"></i> Search
                  </header>
	
                 <div class="panel-body clearfix <?php echo $class; ?>">        
                      <div class="row">
                        <div class="col-lg-5">
                              <div class="form-group">
                                <label class="col-lg-3 control-label">Website List</label>
                                <div class="col-lg-9">
                                  <input type="text" name="filter_name" value="<?php echo isset($filter_name) ? $filter_name : '' ; ?>" placeholder="Website List" id="input-name" class="form-control" />
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
                	<th> Website List </th>
                    <th> Last Enquiry Date </th>
                    <th> Total Enquiry </th>
              		<th> </th>
             	</tr>
                </thead> 
              	<tbody>
                <?php
				//printr($filter_data);//die;
              $total = $obj_website_list->getTotalwebsite($filter_data);
			  $pagination_data = '';
			  if($total){
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
              		//printr($option);
				 $weblist = $obj_website_list->getweb($option,$filter_data);
				 $i=1;
				 	
					foreach($weblist as $web){ 
					//printr($weblist);
					?>
					<tr>
                    
						<td> <?php echo $web['domain_name'];?> </td>
                        <td>  <?php echo dateFormat(4,$web['last_enquiry_date']);?> </td>
                        <td>  <?php echo $web['total'];?> </td>
                        <td> 
                       <a class="btn btn-success" name="view_enquiry" id="view_enquiry" value="View Enquiry" href="<?php echo $obj_general->link($rout, 'mod=view_enquiry&domain_name='.encode($web['domain_name']), '',1);?> "> View Enquiry </a></td>
					</tr>
                    
					<?php $i++; }
					
              		//pagination
				  	$pagination = new Pagination();
					$pagination->total = $total;
					$pagination->page = $page;
					$pagination->limit = $limit;
					$pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
					$pagination->url = $obj_general->link($rout, '&limit='.$limit.'&page={page}', '',1);
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

<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>


