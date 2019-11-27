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

$class = 'collapse';

$filter_data=array();
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
	$filter_vendor_name = $obj_session->data['filter_data']['vendor_name'];
	$filter_inward_no = $obj_session->data['filter_data']['filter_inward_no'];
	$filter_inward_size = $obj_session->data['filter_data']['filter_inward_size'];
	$filter_raw_material = $obj_session->data['filter_data']['filter_raw_material'];
	$filter_roll_name = $obj_session->data['filter_data']['filter_roll_name'];
	
	
	$class = '';
	
	$filter_data=array(
		'vendor_name' => $filter_vendor_name,
		'inward_no' => $filter_inward_no, 
		'inward_size' => $filter_inward_size, 
		'product_name' => $filter_raw_material,
		'roll_no' => $filter_roll_name, 		
	);
}


if(isset($_POST['btn_filter'])){
	
	$filter_edit = 1;
	$class ='';
		
	if(isset($_POST['vendor_name'])){
		$filter_vendor_name=$_POST['vendor_name'];		
	}else{
		$filter_vendor_name='';
	}
	
	if(isset($_POST['filter_inward_no'])){
		$filter_inward_no=$_POST['filter_inward_no'];		
	}else{
		$filter_inward_no='';
	}
	
	if(isset($_POST['filter_inward_size'])){
		$filter_inward_size=$_POST['filter_inward_size'];		
	}else{
		$filter_inward_size='';
	}
	if(isset($_POST['filter_raw_material'])){
		$filter_raw_material=$_POST['filter_raw_material'];		
	}else{
		$filter_raw_material='';
	}
	if(isset($_POST['filter_roll_name'])){
		$filter_roll_name=$_POST['filter_roll_name'];		
	}else{
		$filter_roll_name='';
	}
	
	$filter_data=array(
		'vendor_name' => $filter_vendor_name,
		'inward_no' => $filter_inward_no, 
		'inward_size' => $filter_inward_size, 
		'product_name' => $filter_raw_material, 
		'roll_no' => $filter_roll_name, 
				
	);
	
	$obj_session->data['filter_data'] = $filter_data;	
}

if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'DESC';	
}

if($display_status) {

//active inactive delete
if(isset($_POST['action']) && ($_POST['action'] == "active" || $_POST['action'] == "inactive") && isset($_POST['post']) && !empty($_POST['post']))
{	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	} else {
		$status = 0;
		if($_POST['action'] == "active"){
			$status = 1;
		}
		$obj_product_inward->updateStatus($status,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
}
else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post']))
{
	if(!$obj_general->hasPermission('delete',$menuId)){
		$display_status = false;
	} else {
		$obj_product_inward->updateStatus(2,$_POST['post']);
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
       
          	<span><?php echo $display_name;?> Listing</span>
          	<span class="text-muted m-l-small pull-right">
          			
                 <a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>"><i class="fa fa-plus"></i> New Product</a>
                    <?php if($obj_general->hasPermission('edit',$menuId)){ ?>
                        <a class="label bg-success" style="margin-left:4px;" onClick="formsubmitsetaction('form_list','active','post[]','<?php echo ACTIVE_WARNING;?>')"><i class="fa fa-check"></i> Active</a>
                        <a class="label bg-warning" onClick="formsubmitsetaction('form_list','inactive','post[]','<?php echo INACTIVE_WARNING;?>')"><i class="fa fa-times"></i> Inactive</a>
                        <a class="label bg-danger" onClick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>
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
								
								
							   <div class="form-group">
									<label class="col-lg-1 control-label">Inward No</label>
										<div class="col-lg-2">
								        	<input type="text" name="filter_inward_no" id="filter_inward_no" class="form-control" />
										</div>
										
								<div class="form-group">
									<label class="col-lg-1 control-label">Roll No </label>
									   <div class="col-lg-1">
											<input type="text" name="filter_roll_name" id="filter_roll_name" class="form-control" />
										</div>
										
								<div class="form-group">
									<label class="col-lg-1 control-label">Raw Material</label>
									   <div class="col-lg-2">
											<input type="text" name="filter_raw_material" id="filter_raw_material" class="form-control" />
										</div>
								<div class="form-group">
									<label class="col-lg-1 control-label">Inward size</label>
									   <div class="col-lg-1">
											<input type="text" name="filter_inward_size" id="filter_inward_size" class="form-control" />
										</div>
												
								</div>
					<div class="form-group">
                             <label class="col-lg-1 control-label">vendor Name</label>
                     		   <div class="col-lg-2">
									<?php $vendors = $obj_product_inward->getVendors(); ?>
                                    <select name="vendor_name" class="form-control">
                                    <option value="">Vendor Name</option>
                                        <?php  foreach($vendors as $vendor){
											if(isset($filter_vendor_name) && !empty($filter_vendor_name) && $filter_vendor_name == $vendor['vendor_info_id']) {
                                   ?>
                                                <option value="<?php echo $vendor['vendor_info_id']; ?>" selected="selected" ><?php echo $vendor['vender_first_name'],' ',$vendor['vender_last_name']; ?></option>
                                            <?php }else{ ?>
                                                <option value="<?php echo $vendor['vendor_info_id']; ?>"> <?php echo $vendor['vender_first_name'],' ',$vendor['vender_last_name']; ?></option>
                                            <?php }
                                        } ?>
                                         
                                    </select>
                       			 
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
	                  <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?>">Inward No
                      		<span class="th-sort">
                            	<a href="<?php echo $obj_general->link($rout, 'sort='.'&order=ASC', '',1);?>">
                                <i class="fa fa-sort-down text"></i>
                                <a href="<?php echo $obj_general->link($rout, 'sort='.'&order=DESC', '',1);?>">
                                <i class="fa fa-sort-up text-active"></i>
                            <i class="fa fa-sort"></i></span>
                      </th>
                      <th>Roll No</th>
                      <th>Vendor Name</th>
                      <th>Raw material</th>
                      <th>Quantity </th>
                      <th>Size</th>
                      <th>Status</th>
					  <th></th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php	
				  $total_pro = $obj_product_inward->getTotalProduct($filter_data);
				  $pagination_data = '';
                  if($total_pro){
                        if (isset($_GET['page'])) {
                            $page = (int)$_GET['page'];
                        } else {
                            $page = 1;
                        }
                      //option use for limit or and sorting function	
                      $option = array(
                          'sort'  =>'product_inward_id',
                          'order' => $sort_order,
                          'start' => ($page - 1) * $limit,
                          'limit' => $limit
                      );
                      $product_detail = $obj_product_inward->getProducts($option,$filter_data);
					  foreach($product_detail as $pro){ 
                        ?>
                        <tr <?php echo ($pro['status']==0) ? 'style="background-color:#FADADF" ' : '' ; ?>>
                          <td><input type="checkbox" name="post[]" value="<?php echo $pro['product_inward_id'];?>"></td>
                           
                          <td><?php echo $pro['inward_no'];?>
						  <br><small><?php echo dateFormat(4,$pro['inward_date']);?></small>
						  </td>
                          <td><?php echo $pro['roll_no'];?></td>         
                          <td><?php echo $pro['vender_name'];?></td>
                          
                          <td><?php echo $pro['product_name'];?></td>
                          
                           <td><?php echo $pro['qty'].' '.$pro['unit'];?></td>
                           <td><?php echo  $pro['inward_size']; ?></td>
                          <td>
                          
                          	<div data-toggle="buttons" class="btn-group">
                                <label class="btn btn-xs btn-success <?php echo ($pro['status']==1) ? 'active' : '';?> "> <input type="radio" 
                                 name="status" value="1" id="<?php echo $pro['product_inward_id']; ?>"> <i class="fa fa-check text-active"></i>Active</label>                                   
                                <label class="btn btn-xs btn-danger <?php echo ($pro['status']==0) ? 'active' : '';?> "> <input type="radio" 
                                    name="status" value="0" id="<?php echo $pro['product_inward_id']; ?>"> <i class="fa fa-check text-active"></i>Inactive</label> 
                            </div>
                          
                          </td>
						  <td><a href="<?php echo $obj_general->link('slitting', 'mod=add&roll_id='.encode($pro['product_inward_id']),'',1); ?>"   class="btn btn-info btn-xs">Go For Slitting</a>
						  </td>
                          <td>	
                                <a href="<?php echo $obj_general->link($rout, 'mod=add&product_inward_id='.encode($pro['product_inward_id']),'',1); ?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                                
                           </td>
                        </tr>
                        <?php
                      }
                        //pagination
                        $pagination = new Pagination();
                        $pagination->total = $total_pro;
                        $pagination->page = $page;
                        $pagination->limit = LISTING_LIMIT;
                        $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                        $pagination->url = $obj_general->link($rout, '&page={page}&limit='.$limit.'', '',1);
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

<script type="application/javascript">

	$('input[type=radio][name=status]').change(function() {
	
		var product_inward_id=$(this).attr('id');
		var status_value = this.value;
		
		var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=updateProductStatus', '',1);?>");
        $.ajax({			
			url : status_url,
			type :'post',
			data :{product_inward_id:product_inward_id,status_value:status_value},
			success: function(responce){

				location.reload();
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