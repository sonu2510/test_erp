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

if(isset($_GET['sort'])){
	$sort_name = $_GET['sort'];
}else{
	$sort_name='courier_name';
}

if(isset($_GET['order'])){
	$sort_order = $_GET['order']; 
}else{
	$sort_order = 'ASC';
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
		$obj_courier->updateStatus($status,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
}else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
	if(!$obj_general->hasPermission('delete',$menuId)){
		$display_status = false;
	} else {
		//printr($_POST['post']);die;
		$obj_courier->updateStatus(2,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
}	
/*if(isset($_POST['action']) && $_POST['action'] == "add_rec" && isset($_POST['post']) && !empty($_POST['post'])){
	
	//printr("dfdgdgdg");die;
	$insert_id = $obj_courier->addRecordsCloneOfTnt();
	
	
}*/
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
            	
                <!-- <a class="label bg-danger" onclick="formsubmitsetaction('form_list','add_rec','post[]','')"><i class="fa fa-trash-o"></i> add</a>-->
            	<?php if($obj_general->hasPermission('add',$menuId)){ ?>
   							<a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>"><i class="fa fa-plus"></i> New Courier </a>
                    <?php } if($obj_general->hasPermission('edit',$menuId)){ ?>
                        <a class="label bg-success" style="margin-left:4px;" onclick="formsubmitsetaction('form_list','active','post[]','<?php echo ACTIVE_WARNING;?>')"><i class="fa fa-check"></i> Active</a>
                        <a class="label bg-warning" onclick="formsubmitsetaction('form_list','inactive','post[]','<?php echo INACTIVE_WARNING;?>')"><i class="fa fa-times"></i> Inactive</a>
                     <?php } if($obj_general->hasPermission('delete',$menuId)){ ?>       
                        <a class="label bg-danger" onclick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>
                    <?php } ?>
            </span>
          </header>
          <!--<div class="panel-body">
            
          </div>-->
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
                      <a href="<?php echo $obj_general->link($rout, 'sort=courier_name'.'&order=ASC', '',1);?>">
                      <i class="fa fa-sort-down text"></i>
                      <a href="<?php echo $obj_general->link($rout, 'sort=courier_name'.'&order=DESC', '',1);?>">
                      <i class="fa fa-sort-up text-active"></i>
                    <i class="fa fa-sort"></i></span>
                  </th>
                  
                  <th>Contact Detail</th>
                  
                  <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">
                    Fuel Surcharge (%)
                    <span class="th-sort">
                      <a href="<?php echo $obj_general->link($rout, 'sort=fuel_surcharge'.'&order=ASC', '',1);?>">
                      <i class="fa fa-sort-down text"></i>
                      <a href="<?php echo $obj_general->link($rout, 'sort=fuel_surcharge'.'&order=DESC', '',1);?>">
                      <i class="fa fa-sort-up text-active"></i>
                    <i class="fa fa-sort"></i></span>
                  </th>
                   
                  <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">
                    Service Tax (%)
                    <span class="th-sort">
                      <a href="<?php echo $obj_general->link($rout, 'sort=service_tax'.'&order=ASC', '',1);?>" >
                      <i class="fa fa-sort-down text"></i>
                      <a href="<?php echo $obj_general->link($rout, 'sort=service_tax'.'&order=DESC', '',1);?>" >
                      <i class="fa fa-sort-up text-active"></i>
                    <i class="fa fa-sort"></i></span>
                  </th>                 
                  
                  <th>Handling Charge (Rs.)</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              <?php
              $total_courier = $obj_courier->getTotalCourier();
			  $pagination_data = '';
			  if($total_courier){
				   	if (isset($_GET['page'])) {
						$page = $_GET['page'];
					} else {
						$page = 1;
					}
				  //oprion use for limit or and sorting function	
				  $option = array(
				  		'sort'  => $sort_name,
						'order' => $sort_order,
				  		'start' => ($page - 1) * LISTING_LIMIT,
						'limit' => LISTING_LIMIT
				  );	
				  $couriers = $obj_courier->getCouriers($option);
				 foreach($couriers as $courier){ 
					?>
                    <tr>
                      <td><input type="checkbox" name="post[]" value="<?php echo $courier['couriers_id'];?>"></td>
                      <td><?php echo $courier['courier_name'];?></td>
                      <td>
					  	<?php
							if($courier['contact_person']){
                        		echo 'Contact Person : '.$courier['contact_person'].'<br>';
							}
							if($courier['email']){
								echo '<small>Contact Email : '.$courier['email'].'</small><br>';
							}
							if($courier['telephone']){
								echo '<small>Contact Telephone : '.$courier['telephone'].'</small><br>';
							}
						?>
                      </td>
                      <td><?php echo $courier['fuel_surcharge'];?></td>
                      <td><?php echo $courier['service_tax'];?></td>
                      <td><?php echo $courier['handling_charge'];?></td>
                      <td><label class="label   
                        <?php echo ($courier['status']==1)?'label-success':'label-warning';?>">
                        <?php echo ($courier['status']==1)?'Active':'Inactive';?>
                        </label>
                      </td>
                      <td>
                      		<a href="<?php echo $obj_general->link($rout, 'mod=add&courier_id='.encode($courier['courier_id']), '',1); ;?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                            <a href="<?php echo $obj_general->link($rout, 'mod=zoneList&courier_id='.encode($courier['courier_id']), '',1);?>"  name="btn_permission" class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="left" title="Click her for add zone, zone country and zone wise pricing!">Zone</a>
                       </td>
                    </tr>
                    <?php
				  }
				    
					//pagination
				  	$pagination = new Pagination();
					$pagination->total = $total_courier;
					$pagination->page = $page;
					$pagination->limit = LISTING_LIMIT;
					$pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
					$pagination->url = $obj_general->link($rout, '&page={page}', '',1);
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
