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
	}
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}

if(isset($_GET['sort'])){
	$sort_name = $_GET['sort'];
}else{
	$sort_name='zone';
}

if(isset($_GET['order'])){
	$sort_order = $_GET['order']; 
}else{
	$sort_order = 'ASC';
}

//Close : edit

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
/*else if(isset($_POST['action']) && $_POST['action'] == "priceedit" && isset($_POST['post']) && !empty($_POST['post'])){
	/*if(!$obj_general->hasPermission('delete',$menuId)){
		$display_status = false;
	} else {*/
		//printr($_POST['post']);die;
		/*$obj_courier->updateStatus(2,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));*/
	//}
//}*/	
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
          	<span><b><?php echo $courier['courier_name'];?></b> Zone Listing</span>
            
            <span class="text-muted m-l-small pull-right">
            	<?php if($_SESSION['ADMIN_LOGIN_SWISS'] == '1' && $_SESSION['LOGIN_USER_TYPE'] == '1'){ ?>
            		 <a class="label btn-inverse" onclick="price_edit(<?php echo $courier['courier_id'];?>)"><i class="fa fa-pencil-square-o"></i> Price Change</a>
                     <?php /*?> <a class="label btn-inverse" onclick="price_reset(<?php echo $courier['courier_id'];?>)"><i class="fa fa-pencil-square-o"></i> Price Reset</a><?php */?>
                 <?php } ?>
                    <?php if($obj_general->hasPermission('add',$menuId)){ ?>
   							<a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=zoneAdd&courier_id='.encode($courier['courier_id']), '',1);?>"><i class="fa fa-plus"></i> Add </a>
                    <?php } if($obj_general->hasPermission('edit',$menuId)){ ?>
                        <a class="label bg-success" style="margin-left:4px;" onclick="formsubmitsetaction('form_list','active','post[]','<?php echo ACTIVE_WARNING;?>')"><i class="fa fa-check"></i> Active</a>
                        <a class="label bg-warning" onclick="formsubmitsetaction('form_list','inactive','post[]','<?php echo INACTIVE_WARNING;?>')"><i class="fa fa-times"></i> Inactive</a>
                     <?php } if($obj_general->hasPermission('delete',$menuId)){ ?>       
                        <a class="label bg-danger" onclick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>
                    <?php } ?>                    
                    
            </span>
            
          </header>
          
          <form name="form_list" id="form_list" method="post">
           <input type="hidden" id="action" name="action" value="" /> 
          
          	<div class="table-responsive">
            <table class="table table-striped b-t text-small table-hover">
              <thead>
                <tr>
                  <th width="20"><input type="checkbox"></th>
                 
                  <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">
                    Name
                    <span class="th-sort">
                      <a href="<?php echo $obj_general->link($rout, 'mod=zoneList&courier_id='.$_GET['courier_id'].'&sort=zone'.'&order=ASC', '',1);?>">
                      <i class="fa fa-sort-down text"></i>
                      <a href="<?php echo $obj_general->link($rout, 'mod=zoneList&courier_id='.$_GET['courier_id'].'&sort=zone'.'&order=DESC', '',1);?>">
                      <i class="fa fa-sort-up text-active"></i>
                    <i class="fa fa-sort"></i></span>
                  </th>
                  <th>Latest History</th>
                  <th>Status</th>
                  <th>Action</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
              <?php
			 // printr($courier);
              $total_courierzone = $obj_courier->getTotalCourierZone($courier['courier_id']);
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
				  $courierZones = $obj_courier->getCourierZones($courier['courier_id'],$option);
				  foreach($courierZones as $courierZone){ 
				//  printr($courierZone);
					$history =  $obj_courier->getLatestHistory($courier['courier_id'],$courierZone['courier_zone_id']);
				//printr($history);
					?>
                    <tr>
                      <td><input type="checkbox" class="post_val" name="post[]" value="<?php echo $courierZone['courier_zone_id'];?>"></td>
                      <td><?php echo $courierZone['zone'];?></td>
                      <td><?php //[kinjal] : chage if condition for status [1-12-2015 Tue]
					  			if(isset($history) && !empty($history))
								{
									$val = ' : '.$history['value'];
									if($history['increment_decrement'] == '1')
									{
										$status = 'Incremented'; 
									}
									else if($history['increment_decrement'] == '0')
									{
										$status = 'Decremented';
									}
									else
									{
										$status = '<b>Reset</b>';
										$val ='';
									}
									echo $status.'<b> Value '.$val.'</b><br>';
									echo '<small>On Date ['.dateFormat(4,$history['date_added']).']</small>';
								}?></td>
                      <td><label class="label   
                        <?php echo ($courierZone['status']==1)?'label-success':'label-warning';?>">
                        <?php echo ($courierZone['status']==1)?'Active':'Inactive';?>
                        </label>
                      </td>
                      <td>
                      		<a href="<?php echo $obj_general->link($rout, 'mod=zoneAdd&courier_id='.encode($courierZone['courier_id']).'&zone_id='.encode($courierZone['courier_zone_id']), '',1); ;?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                       </td>
                       <td><a href="<?php echo $obj_general->link($rout, 'mod=view&courier_id='.encode($courierZone['courier_id']).'&zone_id='.encode($courierZone['courier_zone_id']), '',1); ;?>"  name="btn_history" class="btn btn-xs bg-primary">View History</a></td>
                    </tr>
                    <?php
				  }
				  
					//pagination
				  	$pagination = new Pagination();
					$pagination->total = $total_courierzone;
					$pagination->page = $page;
					$pagination->limit = LISTING_LIMIT;
					$pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
					$pagination->url = $obj_general->link($rout,'mod=zoneList&page={page}&courier_id='.encode($courier['courier_id']).'', '',1);
					$pagination_data = $pagination->render();
				    //echo $pagination_data;die;
              } else{ 
				  echo "<tr><td colspan='5'>No record found !</td></tr>";
			  } ?>
              </tbody>
            </table>
            <div id="query"></div>
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
<div class="modal fade" id="smail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="sform" id="sform" style="margin-bottom:0px;">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
               
                <input type="hidden" name="courier_zone_id" id="courier_zone_id" value="" />
                <input type="hidden" name="courier_id" id="courier_id" value=""/>
                
                <h4 class="modal-title" id="myModalLabel">Zone Price Edit</h4>
              </div>
              <div class="modal-body">
                  
                   <div class="form-group">
                  		
                      <div class="col-lg-8">
                       <label class="col-lg-4 control-label"></label>
                       <div style="float:left;width: 200px;" class="">
                            <label style="font-weight: normal;">
                            <input id="inc_dec" type="radio" value="1" name="inc_dec" checked="checked">
                                Increment
                            </label>&nbsp;&nbsp;&nbsp;&nbsp;
                       
                            <label style="font-weight: normal;">
                            <input id="inc_dec" type="radio" value="0" name="inc_dec">
                                Decrement
                            </label>
                        </div>
                 	 </div>
                   
                   </div>
                   
                    <div class="form-group">
                   
                   	    <div class="col-lg-8">
                              <label class="col-lg-3 control-label">Value</label>
                           
                                <div class="col-lg-8">
                                     <input type="text" name="price_val" id="price_val" placeholder="Enter Your Value" class="form-control validate[required]"></textarea>
                                </div>
                         </div>
                     
                     </div> 
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="button" onclick="updatepriceval()" name="btn_decline" class="btn btn-success">Submit</button>
              </div>
   		</form>   
    </div>
  </div>
</div>
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>


<script type="application/javascript">
jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#sform").validationEngine();
});

/*function price_edit()
{
	//$("#smail").modal('show');
	$('input[type=checkbox][name=post]').each(function() 
	{    
    if($(this).is(':checked'))
      alert($(this).val());
	});
}*/
function price_edit(courier_id)
{
	var i = 0;
       var arr = [];
       $('.post_val:checked').each(function () {
           arr[i++] = $(this).val();
       });  
	    $("#courier_zone_id").val(arr);
		$("#courier_id").val(courier_id);
	
	if(arr == '')
	{
		//alert("Please Select Atleast One Record");
		$(".modal-title").html("WARNING");
		$("#setmsg").html('Please select atlease one record');
		$("#popbtnok").hide();
		$("#myModal").modal("show");
	}
	else
	{
		$("#smail").modal('show');
	}
	
}
function price_reset(courier_id)
{
	//alert(courier_id);
	var i = 0;
       var arr = [];
       $('.post_val:checked').each(function () {
           arr[i++] = $(this).val();
       });  
	   
	    var courier_zone_id = arr;
		var courier_id = courier_id;
		//alert(courier_zone_id);
	
	if(arr == '')
	{
		$(".modal-title").html("WARNING");
		$("#setmsg").html('Please select atlease one record');
		$("#popbtnok").hide();
		$("#myModal").modal("show");
		//alert("Please Select Atleast One Record");
	}
	else
	{
		$("#loading").show();
		var reset_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=reset_zone_price', '',1);?>");
		$.ajax({
					url : reset_url,
					method : 'post',		
					data : {courier_zone_id : courier_zone_id,courier_id:courier_id },
					success: function(response){
							//alert(response);
							
							$("#loading").hide();
							$("#query").html(response);
							set_alert_message('Successfully Reset Your Records',"alert-success","fa-check");
							window.setTimeout(function(){location.reload()},500)
							
					}
					
			});
		
	}
	
}
function updatepriceval()
{
	if($("#sform").validationEngine('validate')){ 
			var add_product_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=increment_decrement_price', '',1);?>");
				var formData = $("#sform").serialize();
				$.ajax({
					url : add_product_url,
					method : 'post',		
					data : {formData : formData},
					success: function(response){
						
							set_alert_message('Successfully Updated',"alert-success","fa-check");
							window.setTimeout(function(){location.reload()},500)
							$("#smail").modal('hide');
						
					}
					
			});
		}
}
</script>
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>
