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

$class ='collapse';
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
	$filter_order_no = $obj_session->data['filter_data']['order_no'];
	$filter_company_name = $obj_session->data['filter_data']['company_name'];
	$filter_product_name = $obj_session->data['filter_data']['product_name'];
	$class = '';
	
	$filter_data=array(
		'order_no' => $filter_order_no,
		'company_name' => $filter_company_name, 
		'product_name' => $filter_product_name,
		
	);
}

if(isset($_GET['sort'])){
	$sort_name = $_GET['sort'];
}else{
	$sort_name='company_name';
}

if(isset($_GET['order'])){
	$sort_order = $_GET['order']; 
}else{
	$sort_order = 'ASC';
}



if(isset($_POST['btn_filter'])){
	
	$filter_edit = 1;
	$class ='';
		
	if(isset($_POST['filter_order_no'])){
		$filter_order_no=$_POST['filter_order_no'];		
	}else{
		$filter_order_no='';
	}
	
	if(isset($_POST['filter_company_name'])){
		$filter_company_name=$_POST['filter_company_name'];		
	}else{
		$filter_company_name='';
	}
	

	if(isset($_POST['filter_product_name'])){
		$filter_product_name=$_POST['filter_product_name'];
	}else{
		$filter_product_name='';
	} 
   
   
	$filter_data=array(
		'order_no' => $filter_order_no,
		'company_name' => $filter_company_name,
		'product_name' => $filter_product_name
		);
	
	$obj_session->data['filter_data'] = $filter_data;	
		//printr($filter_data);die;
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
			$obj_cylinder->updateStatus($status,$_POST['post']);
			$obj_session->data['success'] = UPDATE;
			page_redirect($obj_general->link($rout, '', '',1));
		}
	}else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
		if(!$obj_general->hasPermission('delete',$menuId)){
			$display_status = false;
		} else {
			//printr($_POST['post']);die;
			$obj_cylinder->updateStatus(2,$_POST['post']);
			$obj_session->data['success'] = UPDATE;
			page_redirect($obj_general->link($rout, '', '',1));
		}
	}
	
$total_cylinder = $obj_cylinder->getTotalCylinder($filter_data);
//printr($total_cylinder);die;
$pagination_data = '';

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
            	
                <?php if($obj_general->hasPermission('add',$menuId)){ ?>
   							<a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>"><i class="fa fa-plus"></i> Add </a>
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
                    <i class="fa fa-search"></i> Search 	
                  </header>
                        
            	<div class="panel-body clearfix <?php echo $class; ?>">        
                      <div class="row">
                        <div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-5 control-label">Order No</label>
                                <div class="col-lg-7">
                                  <input type="text" name="filter_order_no" value="<?php echo isset($filter_order_no) ? $filter_order_no : '' ; ?>" placeholder="Order No" id="input-name" class="form-control" />
                                </div>
                              </div>
                               <div class="form-group">
                                <label class="col-lg-5 control-label">Company Name</label>
                                <div class="col-lg-7">                                                                                           
                                 <input type="text" name="filter_company_name" value="<?php echo isset($filter_company_name) ? $filter_company_name : '' ; ?>" placeholder="company" id="input-name" class="form-control" />                                 
                                </div>
                              </div>
                        
                           
                         <?php $product= $obj_cylinder->getProductList(); //printr($product);?>
                                 <div class="form-group">
                                <label class="col-lg-5 control-label">Product_Name</label>
                                <div class="col-lg-7">                                                                                            
                                 	<select class="form-control" name="filter_product_name">
                                    	<option value="">Select Product Name</option>
                                        <?php foreach($product as $key=>$value)
				   {?>
                   <?php if(isset($filter_product_name) && !empty($filter_product_name) && $filter_product_name == $value['product_id']) { ?>
                   <option value="<?php echo $value['product_id']; ?>" selected="selected"><?php echo $value['product_name']; ?>
                            <?php } else { ?>
					 <option value="<?php echo $value['product_id']; ?>"> <?php echo $value['product_name']; ?></option>;
				   <?php }?>
                   <?php }?>            
                                   
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
                      <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">
                         Order No 
                         <span class="th-sort">
                               <a href="<?php echo $obj_general->link($rout, 'sort=order_no'.'&order=ASC', '',1);?>">
                               <i class="fa fa-sort-down text"></i>
                               <a href="<?php echo $obj_general->link($rout, 'sort=order_no'.'&order=DESC', '',1);?>">
                               <i class="fa fa-sort-up text-active"></i>
                         <i class="fa fa-sort"></i></span>
                      </th>
                      
                      <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">
                         Company Name
                         <span class="th-sort">
                               <a href="<?php echo $obj_general->link($rout, 'sort=company_name'.'&order=ASC', '',1);?>">
                               <i class="fa fa-sort-down text"></i>
                               <a href="<?php echo $obj_general->link($rout, 'sort=company_name'.'&order=DESC', '',1);?>">
                               <i class="fa fa-sort-up text-active"></i>
                         <i class="fa fa-sort"></i></span>
                      </th>
                         <th>Product Name</th>
                      <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">
                        Country Name
                         
                      </th>                      
                      
                   
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
				
                  if($total_cylinder){
                        if (isset($_GET['page'])) {
                            $page = (int)$_GET['page'];
                        } else {
                            $page = 1;
                        }
						$obj_session->data['page'] = $page;
                      //option use for limit or and sorting function	
                      $option = array(
                           'sort'  => $sort_name,
                           'order' => $sort_order,
                           'start' => ($page - 1) * $limit,
                           'limit' => $limit
                      );	
                      $Cylinders = $obj_cylinder->getCylinders($option,$filter_data);
					// printr($Cylinders);
                      foreach($Cylinders as $Cylinder){ 
                        ?>
                        <tr <?php echo ($Cylinder['status']==0) ? 'style="background-color:#FADADF" ' : '' ; ?>>
                          <td><input type="checkbox" name="post[]" value="<?php echo $Cylinder['order_id'];?>"></td>
                          <td><?php echo $Cylinder['order_no'];?></td>
                          
                          <td><?php echo $Cylinder['company_name'];?></td>
                          
                          <td > <?php echo $Cylinder['product_name'];?></td>
                          <td>
						  	<?php echo $Cylinder['country_name'];?>
                         
                          </td>
                          
                          <td>
                                
                                	<div data-toggle="buttons" class="btn-group">
                                	<label class="btn btn-xs btn-success <?php echo ($Cylinder['status']==1) ? 'active' : '';?> "> <input type="radio" 
                                    name="status" value="1" id="<?php echo $Cylinder['order_id']; ?>"> <i class="fa fa-check text-active"></i>Active</label>
                                     
                                	<label class="btn btn-xs btn-danger <?php echo ($Cylinder['status']==0) ? 'active' : '';?> "> <input type="radio" 
                                    name="status" value="0" id="<?php echo $Cylinder['order_id']; ?>"> <i class="fa fa-check text-active"></i>Inactive</label> 
                                </div>
                          
                           </td>
                       
                          <td>
                                <a href="<?php echo $obj_general->link($rout, 'mod=add&order_id='.encode($Cylinder['order_id']).'&filter_edit='.$filter_edit, '',1); ;?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>                                                              
                           </td>
                        </tr>
                        <?php
                      }
                        
                        //pagination
                        $pagination = new Pagination();
                        $pagination->total = $total_cylinder;
                        $pagination->page = $page;
                        $pagination->limit = $limit;
                        $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                        $pagination->url = $obj_general->link($rout, '&page={page}&limit='.$limit.'&filter_edit=1', '',1);//HTTP_ADMIN.'index.php?rout='.$rout.'&page={page}';
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
              <div class="col-sm-3 hidden-xs"> </div>
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
		var order_id=$(this).attr('id');
		var status_value = this.value;
		
		var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=updateCylinderStatus', '',1);?>");
        $.ajax({
			
			url : status_url,
			type :'post',
			data :{order_id:order_id,status_value:status_value},
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