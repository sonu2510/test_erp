<?php
include("mode_setting.php");
$obj_product = new product();

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
	$filter_layer = $obj_session->data['filter_data']['layer'];
	$filter_status = $obj_session->data['filter_data']['status'];
	$class = '';
	
	$filter_data=array(
		'layer' => $filter_layer,		
		'status' => $filter_status
	);	
}

if(isset($_POST['btn_filter'])){
	
	$filter_edit = 1;
	$class ='';	
	if(isset($_POST['filter_layer'])){
		$filter_layer=$_POST['filter_layer'];		
	}else{
		$filter_layer='';
	}	
	
	if(isset($_POST['filter_status'])){
		$filter_status=$_POST['filter_status'];
	}else{
		$filter_status='';
	}
		
	$filter_data=array(
		'layer' => $filter_layer,
		'status' => $filter_status
	);
	
	$obj_session->data['filter_data'] = $filter_data;
	
}

if(isset($_GET['sort'])){
	$sort_name = $_GET['sort'];	
}else{
	$sort_name = 'layer';
}

if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'ASC';	
}

/*if(isset($_GET['del']) && $_GET['del'] != "")
{
	if(!hasPermission('delete',$menuId)){
		$display_status = false;
	} else {
		$where=array('coupon_master_id'=>mysql_real_escape_string(trim($_GET['del'])));
		//$delete = $obj_conn->Delete($table, $where, $limit='', $like=false);
		$set=array("is_delete"=>1);
		$update = $obj_conn->Updatein($table, $set, $where, $like_or_in='in', $oparand='AND');
	}
}
if(isset($_POST['action']) && ($_POST['action'] == "active" || $_POST['action'] == "inactive") && isset($_POST['chk']) && !empty($_POST['chk']))
{
	if(!hasPermission('edit',$menuId)){
		$display_status = false;
	} else {
		//printr($_POST['chk']);die;
		$where=array('coupon_master_id'=>mysql_real_escape_string(trim(implode(",",$_POST['chk']))));
		$status = 0;
		if($_POST['action'] == 'active'){
			$status = 1;
		}
		$set=array('status'=>$status);
		$update = $obj_conn->Updatein($table, $set, $where, $like_or_in='in', $oparand='AND');
	}
}

if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['chk']) && !empty($_POST['chk']))
{
	if(!hasPermission('delete',$menuId)){
		$display_status = false;
	} else {
		$where=array('coupon_master_id'=>mysql_real_escape_string(trim(implode(",",$_POST['chk']))));
		//$obj_conn->Deletein($table, $where, $like_or_in='in', $oparand='AND');
		$set=array("is_delete"=>1);
		$update = $obj_conn->Updatein($table, $set, $where, $like_or_in='in', $oparand='AND');
	}
}*/

if($display_status) {
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
          			
                    <a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>"><i class="fa fa-plus"></i> New Product Layer</a>
                    <?php if($obj_general->hasPermission('edit',$menuId)){ ?>
                        <a class="label bg-success" href="javascript:void(0);"><i class="fa fa-check"></i>Active</a>
                        <a class="label bg-warning" href="javascript:void(0);"><i class="fa fa-times"></i>Inactive</a>
                        <a class="label bg-danger" href="javascript:void(0);"><i class="fa fa-trash-o"></i>Delete</a>
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
                                <label class="col-lg-2 control-label">Layer</label>
                                <div class="col-lg-10">
                                  <input type="text" name="filter_layer" value="<?php echo isset($filter_layer) ? $filter_layer : '' ; ?>" placeholder="layer" id="input-name" class="form-control" />
                                </div>
                              </div>                             
                          </div>
                          <div class="col-lg-4">                              
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
          
          <div class="table-responsive">
            <table class="table table-striped b-t text-small table-hover">
              <thead>
                <tr>
                  <th width="20"><input type="checkbox"></th>
                  
                  <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">
                      Layer
                      <span class="th-sort">
                       	<a href="<?php echo $obj_general->link($rout, 'sort=layer'.'&order=ASC', '',1);?>">
                        <i class="fa fa-sort-down text"></i>
                        <a href="<?php echo $obj_general->link($rout, 'sort=layer'.'&order=DESC', '',1);?>">
                        <i class="fa fa-sort-up text-active"></i>
                      <i class="fa fa-sort"></i></span>
                  </th>                 
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              <?php
              $total_layer = $obj_product->getTotalLayer($filter_data);
			  $pagination_data = '';
			  if($total_layer){
				   	if (isset($_GET['page'])) {
						$page = (int)$_GET['page'];
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
				  $layers = $obj_product->getLayers($option,$filter_data);
				  foreach($layers as $layer){ 
					?>
                    <tr>
                      <td><input type="checkbox" name="post[]" value="<?php echo $layer['product_layer_id'];?>"></td>
                      <td><?php echo $layer['layer'];?></td>
                      <td>
                      	<label class="label <?php echo ($layer['status']==1)?'label-success':'label-warning';?>">
	                        <?php echo ($layer['status']==1)?'Active':'Inactive';?>
                        </label>
                      </td>
                      <td>
                      		<a href="<?php echo $obj_general->link($rout, 'mod=add&layer_id='.encode($layer['product_layer_id']).'&filter_edit='.$filter_edit, '',1); ;?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                       </td>
                    </tr>
                    <?php
				  }
				    
					//pagination
				  	$pagination = new Pagination();
					$pagination->total = $total_layer;
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