<?php

//jayashree
include("mode_setting.php");

//Start : bradcums
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
	'text' 	=> $display_name.' Add',
	'href' 	=> '',
	'icon' 	=> 'fa-edit',
	'class'	=> 'active',
);

$limit = LISTING_LIMIT;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}
//Close : bradcums

if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'DESC';	
}
//Start : edit
$edit = '';

if(isset($_POST['action']) && ($_POST['action'] == "active" || $_POST['action'] == "inactive") && isset($_POST['post']) && !empty($_POST['post']))
{
		$status = 0;
		if($_POST['action'] == "active"){
			$status = 1;
		}
		$obj_boxmaster->updateTransportation($status,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, 'mod=list&product_id='.$_GET['product_id'], '',1));
	
}else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
	
		$obj_boxmaster->updatepouchStatus(2,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, 'mod=list&product_id='.$_GET['product_id'], '',1));
	
}

$filter_data= array();
if(isset($_GET['product_id']) && !empty($_GET['product_id'])){
		$product_id = base64_decode($_GET['product_id']);
		
		//$transport = $obj_boxmaster->getPouchData($product_id );
		$edit = 1;
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
       <?php $product_nm = $obj_boxmaster->getProductName($product_id);
	   		//printr($product_nm); ?>
          	<span><?php echo ucwords($product_nm['product_name']);?> Listing</span>
          	<span class="text-muted m-l-small pull-right">
          		  <?php if($obj_general->hasPermission('add',$menuId)){ ?>	
                 <a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add&product_id='.$_GET['product_id'], '',1);?>"><i class="fa fa-plus"></i> Add </a>
                    <?php }if($obj_general->hasPermission('edit',$menuId)){ ?>
                        <a class="label bg-success" style="margin-left:4px;" onclick="formsubmitsetaction('form_list','active','post[]','<?php echo ACTIVE_WARNING;?>')"><i class="fa fa-check"></i> Active</a>
                        <a class="label bg-warning" onclick="formsubmitsetaction('form_list','inactive','post[]','<?php echo INACTIVE_WARNING;?>')"><i class="fa fa-times"></i> Inactive</a>
                        <a class="label bg-danger" onclick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>
                    <?php } ?>                      
                    
            </span>
           
          </header>
          
         
          
          <form name="form_list" id="form_list" method="post">
           <input type="hidden" id="action" name="action" value="" />
          	<div class="table-responsive">
                <table class="table b-t text-small table-hover">
                  <thead>
                    <tr>
                      <th width="20"><input type="checkbox"></th>
                      <!--<th>Material Name</th>-->
                      
                      <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">
                      		Pouch Volume
                           <!--span class="th-sort">
                            	<a href="<?php echo $obj_general->link($rout, 'sort=country'.'&order=ASC', '',1);?>">
                                <i class="fa fa-sort-down text"></i>
                                <a href="<?php echo $obj_general->link($rout, 'sort=country'.'&order=DESC', '',1);?>">
                                <i class="fa fa-sort-up text-active"></i>
                            <i class="fa fa-sort"></i></span-->
                      </th>
                      <th>Stock Quantity</th>
                      <th>Custom Quantity</th>
                      <!--<th>Box Volume</th>-->
                      <th>Stock Box Weight</th>
                      <th>Custom Box Weight</th>
                      <th>Stock Net Weight</th>
                      <th>Custom Net Weight</th>
                       <th>Options</th>
                       <!--[kinjal]: add for transport feild [20 apr(12:48 pm)] -->
                       <th>Transport By</th> 
                      <th>Status</th>
                      <?php if($obj_general->hasPermission('edit',$menuId)){ ?> 	
                      <th>Action</th>
                      <?php } ?>
                    </tr>
                  </thead>
                  <tbody>
                  <?php	
				  
				  $total_pouch = $obj_boxmaster->getTotalProduct($filter_data,$product_id);
					//echo $product_id;exit;
				  $pagination_data = '';
				  
				  if($total_pouch) {
					  
					  if (isset($_GET['page'])) {
                            $page = (int)$_GET['page'];
                        } else {
                            $page = 1;
                        }
						$option = array(
                          'sort'  => 'pouch_id',
                          'order' => $sort_order,
                          'start' => ($page - 1) * $limit,
                          'limit' => $limit
                      );


					//echo $product_id;exit;
				  $box_masteres = $obj_boxmaster->getPouches($option, $filter_data,$product_id);
				  if(isset($total_pouch) && !empty($total_pouch)) {
                  foreach($box_masteres as $box_master) {
                    $accessorie= $obj_boxmaster->getCurrentAccessorie(decode($box_master['accessorie']));
                    $make= $obj_boxmaster->getCurrentMake($box_master['make_pouch']);
				//	  printr($accessorie);
				if($accessorie['product_accessorie_name']!='No Accessorie'){
				    $accessorie['product_accessorie_name']=$accessorie['product_accessorie_name'];
				}else{
				    $accessorie['product_accessorie_name']='';
				}
					  ?>
                        <tr <?php echo ($box_master['status']==0) ? 'style="background-color:#FADADF" ' : '' ; ?>>                        
                          <td><input type="checkbox" name="post[]" value="<?php echo $box_master['pouch_id'];?>"></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&pouch_id='.encode($box_master['pouch_id']),'',1); ?>"><?php  $measurement=$obj_boxmaster->getMeasurementName($box_master['pouch_volume_type']);  echo $box_master['pouch_volume'].' '.$measurement['measurement']; ?></a></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&pouch_id='.encode($box_master['pouch_id']),'',1); ?>"><?php echo $box_master['quantity']; ?></a></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&pouch_id='.encode($box_master['pouch_id']),'',1); ?>"><?php echo $box_master['cust_quantity']; ?></a></td>
                        <!--  <td><?php /*?><?php $box_volume_type=$obj_boxmaster->getMeasurementName($box_master['box_volume_type']);  echo $box_master['box_volume'].' '.$box_volume_type['measurement']; ?><?php */?></td>-->
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&pouch_id='.encode($box_master['pouch_id']),'',1); ?>"><?php $box_weight_type=$obj_boxmaster->getMeasurementName($box_master['box_weight_type']); echo $box_master['box_weight'].' '.$box_weight_type['measurement']; ?></a></td>
                         
                         <td><a href="<?php echo $obj_general->link($rout, 'mod=view&pouch_id='.encode($box_master['pouch_id']),'',1); ?>"><?php $box_weight_type=$obj_boxmaster->getMeasurementName($box_master['cust_box_weight_type']); echo $box_master['cust_box_weight'].' '.$box_weight_type['measurement']; ?></a></td>
                          
                           <td><a href="<?php echo $obj_general->link($rout, 'mod=view&pouch_id='.encode($box_master['pouch_id']),'',1); ?>"><?php $box_weight_type=$obj_boxmaster->getMeasurementName($box_master['net_weight_type']); echo $box_master['net_weight'].' '.$box_weight_type['measurement']; ?></a></td>
                           
                            <td><a href="<?php echo $obj_general->link($rout, 'mod=view&pouch_id='.encode($box_master['pouch_id']),'',1); ?>"><?php $box_weight_type=$obj_boxmaster->getMeasurementName($box_master['cust_net_weight_type']); echo $box_master['cust_net_weight'].' '.$box_weight_type['measurement']; ?></a></td>
                          
                         <td><a href="<?php echo $obj_general->link($rout, 'mod=view&pouch_id='.encode($box_master['pouch_id']),'',1); ?>"><?php $zipper=$obj_boxmaster->getCurrentZipper(decode($box_master['zipper']));echo $zipper['zipper_name'].' | &nbsp;&nbsp;'.$accessorie['product_accessorie_name'].' | &nbsp;&nbsp;'.$box_master['valve'].' | &nbsp;&nbsp;'.$make['make_name'];?></a></td>
                         <!--[kinjal]: add td for transport feild [20 apr(12:51 pm)] -->
                         <td><a href="<?php echo $obj_general->link($rout, 'mod=view&pouch_id='.encode($box_master['pouch_id']),'',1); ?>">
                         <?php echo ucwords(decode($box_master['transportation'])); ?></a></td>
                          <td>
                          
                          	<div data-toggle="buttons" class="btn-group">
                                <label class="btn btn-xs btn-success <?php echo ($box_master['status']==1) ? 'active' : '';?> "> <input type="radio" 
                                 name="status" value="1" id="<?php echo $box_master['pouch_id']; ?>"> <i class="fa fa-check text-active"></i>Active</label>                                   
                                <label class="btn btn-xs btn-danger <?php echo ($box_master['status']==0) ? 'active' : '';?> "> <input type="radio" 
                                    name="status" value="0" id="<?php echo $box_master['pouch_id']; ?>"> <i class="fa fa-check text-active"></i>Inactive</label> 
                            </div>
                          
                          </td>
                          <td>	<?php if($obj_general->hasPermission('edit',$menuId)){ ?> 	
                                <a href="<?php echo $obj_general->link($rout, 'mod=add&product_id='.encode($product_id).'&pouch_id='.encode($box_master['pouch_id']),'',1); ?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                                <?php } ?>
                           </td>
                        </tr>
                      <?php
					 
					  
					  }
				  } else {
						  echo '<tr><td colspan="4">No Record Found</td></tr>';
                  } 
				  //pagination
                        $pagination = new Pagination();
						//printr($pagination);
                        $pagination->total = $total_pouch;
                        $pagination->page = $page;
                        $pagination->limit = $limit;
                        $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                        $pagination->url = $obj_general->link($rout, '&page={page}&limit='.$limit.'&mod=list&product_id='.$_GET['product_id'], '',1);//HTTP_ADMIN.'index.php?rout='.$rout.'&page={page}';
                        $pagination_data = $pagination->render();
                        //echo $pagination_data;die;
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

<script type="application/javascript">
	
	
	$('input[name=status]').change(function() {

		var transport_id=$(this).attr('id');
		var status_value = this.value;
		var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&ajax=updateBoxMasterStatus', '',1);?>");
        //alert(status_url);
		$.ajax({			
			   
			url : status_url,
			type :'post',
			data :{transport_id:transport_id,status_value:status_value},
			success: function(){
				set_alert_message('Successfully Updated',"alert-success","fa-check");					
			},
			error:function(){
				set_alert_message('Error During Updation',"alert-warning","fa-warning");          
			}			
		});
    });


</script>           
