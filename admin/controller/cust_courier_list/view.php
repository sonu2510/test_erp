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
	'href' 	=> $obj_general->link($rout, '', '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> 'Zone List',
	'href' 	=> $obj_general->link($rout, 'mod=zoneList&courier_id='.$_GET['courier_id'], '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> 'Zone History',
	'href' 	=> $obj_general->link('dashboard', '', '',1),
	'icon' 	=> 'fa-list',
	'class'	=> 'active',
);

if(!$obj_general->hasPermission('view',$menuId)){
	$display_status = false;
}

//Start : edit
$edit = '';
if(isset($_GET['courier_id']) && !empty($_GET['courier_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$courier_id = decode($_GET['courier_id']);
		$getColoum = 'courier_id,courier_name';
		$courier = $obj_courier->getCourier($courier_id,$getColoum);
		//printr($courier);
	}
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}

if(isset($_GET['sort'])){
	$sort_name = $_GET['sort'];
}else{
	$sort_name='courier_price_history_id';
}

if(isset($_GET['order'])){
	$sort_order = $_GET['order']; 
}else{
	$sort_order = 'DESC';
}

//Close : edit

if($display_status) {
$courier_zone_detail = $obj_courier->getCourierZoneDetail(decode($_GET['zone_id']));
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
          	<span><b><?php echo $courier['courier_name'];?></b> Zone History</span>&nbsp;&nbsp;&nbsp;&nbsp;
            <span><b><?php echo $courier_zone_detail['zone'];?></b> Zone </span>
            <span class="text-muted m-l-small pull-right">
                          
                    
            </span>
            
          </header>
          
          <form name="form_list" id="form_list" method="post">
           <input type="hidden" id="action" name="action" value="" /> 
          
          	<div class="table-responsive">
            <table class="table table-striped b-t text-small table-hover">
              <thead>
                <tr>
                	<th>Sr. No.</th>
                 	 <th>Increment / Decrement / Reset</th>
                  	<th>Value</th>
				  	<th>Date</th>
                </tr>
              </thead>
              <tbody>
              <?php
              $total_courierzone = $obj_courier->getTotalHistoryPrice($courier['courier_id'],$courier_zone_detail['courier_zone_id']);
			  $pagination_data = '';
			  if($total_courierzone){
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
				  $courierZones = $obj_courier->getCourierHistory($courier['courier_id'],$courier_zone_detail['courier_zone_id'],$option);
				  $i = 1;
				  foreach($courierZones as $courierZone){ 
					//[kinjal] : chage if condition for status [1-12-2015 Tue]?>
                    <tr>
                    	<td><?php echo $i;?></td>
                      	<td><?php 	$val = $courierZone['value'];
									if($courierZone['increment_decrement'] == '1')
									{
										echo  'Increment'; 
									}
									elseif($courierZone['increment_decrement'] == '0')
									{
										echo  'Decrement';										
									}
									else
									{
										echo  '<b>Reset</b>';
										$val = '<b>-</b>';
									}
					  		?></td>
                      	<td><?php echo $val; ?></td>
					  	<td><?php echo dateFormat(4,$courierZone['date_added']); ?></td>
                      
                     </tr>
                      
                    <?php
				 $i++; }
				  
					//pagination
				  	$pagination = new Pagination();
					$pagination->total = $total_courierzone;
					$pagination->page = $page;
					$pagination->limit = LISTING_LIMIT;
					$pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
					$pagination->url = $obj_general->link($rout,'mod=zoneList&page={page}&courier_id='.encode($courier['courier_id']).'', '',1);
					$pagination_data = $pagination->render();
              } else{ 
				  echo "<tr><td colspan='5'>No record found !</td></tr>";
			  } ?>
              </tbody>
            </table>
           		 <div class="form-group">
                       <div class="col-lg-9 col-lg-offset-3">             
                          <a class="btn btn-default" href="<?php echo $obj_general->link($rout,'&mod=zoneList&courier_id='.$_GET['courier_id'], '',1);?>">Cancel</a>
                       </div>
                 </div>
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

<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>
