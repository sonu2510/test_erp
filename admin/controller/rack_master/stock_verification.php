<?php
// Mode setting for the ADD stock Starts here
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
	'text' 	=> $display_name.' Detail',
	'href' 	=> '',
	'icon' 	=> 'fa-edit',
	'class'	=> 'active',
);
//Close : bradcums

//Start : edit
$edit = '';

//Close : edit

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
if($display_status) {
 
?>

<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-edit"></i> <?php echo $display_name;?></h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div> 
        
      <div class="col-sm-12">
        <section class="panel">
          <header class="panel-heading bg-white"> Inventory Stock Report 
          
             </header>
          <div class="panel-body">
                <!--<form class="form-horizontal" method="post" enctype="multipart/form-data">-->
                	<!--<div class="panel-body">
                	    <div class="form-group">
                	        <?php //$ib_user = $obj_rack_master->getIBUser();?>
                	        <?php /*foreach($ib_user as $user)
                                  { 
                                        $goods_data = $obj_rack_master->getRandomGoodsdata($user['international_branch_id'],'4');//printr($goods_data);//die;
                                        foreach($goods_data as $key=>$goods)
                                        { 
                                            $send_mail = $obj_rack_master->sendMailOfToCountPhysicalStock($goods,$key);
                                        } 
                                  }*/?>
                         </div>
                	</div>-->
                	<div class="panel-body">
                        <?php //if($_SESSION['LOGIN_USER_TYPE'] == 1 && $_SESSION['ADMIN_LOGIN_SWISS'] == 1){ ?>
                            <!--<div class="form-group">
                                <label class="col-lg-3 control-label"><span class="required">*</span>IB User</label>
                                <div class="col-lg-4">
                                   <?php //$ib_user = $obj_rack_master->getIBUser(); ?>
                                   <select name="ib_user" id="ib_user" class="form-control validate[required]">
                                      <option>Select IB User</option>
                                      <?php //foreach($ib_user as $user)
                                            //{ ?>
                                                 <option value="<?php //echo $user['international_branch_id'] ;?>" ><?php //echo $user['first_name'].'  '.$user['last_name']; ?></option>
                                      <?php //}?>
                                   </select>
                                </div>
                                </div>
                           </div>-->
                       <?php //} ?>
                       
                        <?php if($_GET['option']==1)
                                $value ='Random Stock Verification';
                             elseif($_GET['option']==2)
                                $value ='100% Stock Verification'; ?>
                       <div class="form-group">
                            <header class="panel-heading">
                                <label class="col-lg-3 control-label"><span class="required">*</span>Stock Verification By</label>
                                <div class="col-lg-4">
                                    <input type="hidden" name="verify_option" id="verify_option" value="<?php echo $value;?>" class="form-control validate[required]">
                                   <div style="float:left;width: 200px;">
		                                <label  style="font-weight: normal;">
		                                  <input type="radio" name="stock_verify_by" checked="checked" value="Rack Wise"  />  Rack Wise
		                                </label>
		                          </div>
		                           <div style="float:left;width: 200px;">
		                                <label  style="font-weight: normal;">
		                                  	<input type="radio" name="stock_verify_by" value="Size Wise">  Size Wise
		                               </label>
		                          </div>
                                </div>
                            </header>
                       </div>
                       <div class="form-group">
                           
                       </div>
                       <?php $task_detail= $obj_rack_master->getTask($value,'Rack Wise'); ?>
                       <div class="form-group">
                            <header class="panel-heading" style="background: #f1ede8;">
                                  <ul class="nav nav-tabs nav-justified">
                                     <?php if($task_detail)
	                                       {   
	                                           $task_id = array_column($task_detail, 'task_id');
                                               $task_ids = implode(',', '#rack_form'.$task_id);
	                                           $i=1;
	                                           foreach($task_detail as $dt)
        	                                   {   
        	                                       $added_task_details= $obj_rack_master->getTaskDetails($dt['task_id']);
        	                                       $task_id = '';
        	                                       $active = '';
        	                                       if($i=='1')
        	                                            $active = 'active';
        	                                            
        	                                       $rack_name = $obj_rack_master->goods_master_detail($dt['goods_id']);
        	                                       $label = $rack_name['name'].' <b>('.$dt['rack_label'].')</b>';
        	                                       if($dt['task_id']==$added_task_details[0]['task_id'])
        	                                            $label.=' <span style="color:red;">(Data Registred)</span>';
        	                                       
        	                                       echo '<li class="'.$active.'"><a data-toggle="tab" href="#rack'.$i.'"><b>'.$label.'</a></b></li>';
        	                                       $i++;
        	                                   }
	                                       }?>
                                 </ul>
                            </header>
                       </div>
                       <div class="panel-body">
                            <div class="tab-content">
                                <?php if($task_detail)
	                                  {   $i=1;$added_task_details[0]['task_id']=0;$number=$num=0;
	                                        $count_task_detail = count($task_detail);
	                                           $task_id_imploded=implode(",", array_column($task_detail,"task_id")); 
	                                           foreach($task_detail as $dt)
        	                                   {   $active = '';
        	                                       if($i=='1')
        	                                            $active = 'active';
        	                                       
        	                                       $added_task_details= $obj_rack_master->getTaskDetails($dt['task_id']);
        	                                       $task_details= $obj_rack_master->verify_records($dt['task_id']);
        	                                       $rack_name = $obj_rack_master->goods_master_detail($dt['goods_id']);
        	                                       $pallet_details = $dt['row'].'='.$dt['column_no'].'='.$dt['goods_id'];
        	                                       $rack_qty_dis = $obj_rack_master->getRack_qty_dis($pallet_details,$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS'],'','');
        	                                       $label = $rack_name['name'].' <b>('.$dt['rack_label'].')</b>';
        	                                       $count = sizeof($rack_qty_dis->rows);?>
        	                                       
        	                                       <div id="<?php echo 'rack'.$i;?>" class="tab-pane <?php echo $active;?>">
                                                            <section class="panel">
                                                               <div class="panel-body">
                                                                  
                                                                  <form class="form-horizontal" method="post" name="rack_form<?php echo $dt['task_id'];?>" id="rack_form<?php echo $dt['task_id'];?>" class="rack_class<?php echo $i;?>" enctype="multipart/form-data">
                                                                    
                                                                      <div class="form-group">
                                                                            <input type="hidden" name="task_id" id="task_id" value="<?php echo $dt['task_id']; ?>">
                                                                             <div class="table-responsive">
                                                                                <table border="1" class="table table-striped m-b-none text-small">
                                                                                    <thead>
                                                                            	        <tr>
                                                                            	            <th>Rack Name (Pallet)</th>
                                                                            	            <th>Product Code (Description)</th>
                                                                            	            <th>Add Physically Counted Quantity</th>
                                                                            	            <?php if(isset($_GET['verify'])==1){?>
                                                                            	                <th>ERP Stock Quantity</th>
                	                                                                            <th>Difference</th>
                                                                            	                <th>Add Comments</th>
                                                                            	            <?php } ?>
                                                                            	        </tr>
                                                                            	    </thead>
                                                                            	    <tbody>
                                                                             <?php      if($dt['task_id']!=$added_task_details[0]['task_id'])
                                                                                        {
                                                                                            if($rack_qty_dis)
            							                                                    {
            							                                                        $k=1;
                                                                								foreach($rack_qty_dis->rows as $rack)
                                                                								{
                                                                								    $desc = $obj_rack_master->getProductCode($rack['product_code_id']);
                                                                								    $dispatch_qty=$obj_rack_master->gettotaldispatchSales($rack['grouped_s_id'],$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
                                                                									$rm_qty=$rack['tot_qty']-$dispatch_qty['total'];
                                                                									if($rm_qty!=0)
                                                                									{ ?>
                                                                										<tr>
                                                                										      <?php if($k==1)
                                                                										            { ?>
                                                                    	                                                <td rowspan="<?php echo $count;?>"><?php echo $rack_name['name'].' <b>('.$dt['rack_label'].')</b>';?></td>
                                                                										      <?php }  ?>  
                                                                    	                                               <td><?php echo $desc['product_code'];?><br><small><?php echo $desc['description'];?></small><input type="hidden" name="product_code_id[]" id="product_code_id" value="<?php echo $rack['product_code_id']; ?>"></td>																			
                                                                												       <td><input type="text" name="added_qty[]" id="added_qty" value="" class="form-control validate[required]"><input type="hidden" name="original_qty[]" id="original_qty" value="<?php echo $rm_qty;?>"></td>
                                                                												       
                                                                										</tr>
                                                                						<?php		}
                                                                									$k++;
                                                                								}
            							                                                    }
            							                                                 }
            							                                                 else if(isset($_GET['verify'])==1)
            							                                                 {  $k=1;
            							                                                     foreach($task_details as $rack)
                                                                							 { 
                                                                								    $desc = $obj_rack_master->getProductCode($rack['product_code_id']);
                                                                								    $diff = $rack['original_rack_qty']-$rack['physically_counted_qty'];
                                                                								    ?>
                                                                								    <tr>
                                                            										      <?php if($k==1)
                                                            										            { ?>
                                                                	                                                <td rowspan="<?php echo $count;?>"><?php echo $rack_name['name'].' <b>('.$rack['rack_label'].')</b>';?></td>
                                                            										      <?php }  ?>  
                                                                	                                               <td><?php echo $desc['product_code'];?><br><small><?php echo $desc['description'];?></small></td>																			
                                                            												       <td><?php echo $rack['physically_counted_qty'];?></td>
                                                            												       <td><?php echo $rack['original_rack_qty'];?></td>
                                                            												       <td><?php echo $diff ;?></td>
                                                            												       <td>
                                                                												          <?php if($rack['review']=='' && $diff!=0)
                                                                												           { ?>
                                                                												               <input type="hidden" name="detail_id[]" id="detail_id" value="<?php echo $rack['detail_id']; ?>"><textarea class="form-control validate[required]" name="comments[]"></textarea>
                                                                												    <?php  }
                                                                												           else 
                                                                												           { 
                                                                												               echo $rack['review']; 
                                                            												               } ?>
                                                            												      </td>
                                                                									</tr>
                                                                				       <?php $k++;
                                                                							     
                                                                							 }
                                                                							 $num ++;
            							                                                 }
            							                                                 else
            							                                                 {  $k=1;
            							                                                     foreach($added_task_details as $rack)
                                                                							 {
                                                                								    $desc = $obj_rack_master->getProductCode($rack['product_code_id']);
                                                                								    ?>
                                                                										<tr>
                                                                										      <?php if($k==1)
                                                                										            { ?>
                                                                    	                                                <td rowspan="<?php echo $count;?>"><?php echo $rack_name['name'].' <b>('.$rack['rack_label'].')</b>';?></td>
                                                                										      <?php }  ?>  
                                                                    	                                               <td><?php echo $desc['product_code'];?><br><small><?php echo $desc['description'];?></small><input type="hidden" name="product_code_id[]" id="product_code_id" value="<?php echo $rack['product_code_id']; ?>"></td>																			
                                                                												       <td><?php echo $rack['physically_counted_qty'];?></td>
                                                                									   </tr>
                                                                						<?php		
                                                                									$k++;
                                                                								}
                                                                							$number ++;
            							                                                 }?>
                                                                                        
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>                    
                                                                         </div>                    
                                                                         <?php if($dt['task_id']!=$added_task_details[0]['task_id']){?>
                                                                              <div class="form-group">
                                                                                <div class="col-lg-9 col-lg-offset-3">
                                                                                    
                                                                                    <button type="button" name="submit<?php echo $i;?>" id="<?php echo $i;?>" onclick="submit_records(<?php echo $dt['task_id'];?>)" class="btn btn-primary">Submit Records</button>
                                                                                </div>
                                                                       	      </div>
                                                                   	     <?php }
                                                                   	           else if(isset($_GET['verify'])==1 && $dt['submitted_date']=='0000-00-00 00:00:00') {?>
                                                                       	           <div class="form-group">
                                                                                    <div class="col-lg-9 col-lg-offset-3">
                                                                                        <button type="button" name="submit<?php echo $i;?>" id="<?php echo $i;?>" onclick="submit_comments(<?php echo $dt['task_id'];?>)" class="btn btn-primary">Add Comments</button>
                                                                                    </div>
                                                                           	      </div>
                                                                          <?php } ?>
                                                               	    </form>
                                                               </div>
                                                            </section>
                                                        </div>
        	                            <?php           $i++;
        	                                   }
        	                                   if($count_task_detail == $number && !isset($_GET['verify'])==1)
        	                                   {?>
        	                                        <div class="form-group">
                                                        <div class="col-lg-9 col-lg-offset-3">
                                                            <!--<input type="hidden" name="task_id_for_verify" id="task_id_for_verify" value="<?php //echo $task_id_imploded; ?>">-->
                                                            <button type="button" name="verify" id="verify" onclick="verify_records('<?php echo $task_id_imploded;?>')" class="btn btn-primary">Verify Your Stock</button>
                                                        </div>
                                               	    </div>   
	                                 <?php     }
            	                               else if(isset($_GET['verify'])==1 && $count_task_detail == $num)
            	                               { ?>
            	                                     <div class="form-group">
                                                        <div class="col-lg-9 col-lg-offset-3">
                                                            <button type="button" name="close"  onclick="close_task('<?php echo $task_id_imploded;?>')" class="btn btn-primary">Closed Your Task</button>
                                                        </div>
                                               	    </div>    
            	                      <?php    }
	                                   }?>
                            </div>
                       </div>
                       <!--<div class="form-group hide_div" style="display:none;">
                            <label class="col-lg-3 control-label">Task Details</label>
                            <div class="col-lg-8 data_div">
                                
                            </div>
                       </div>-->
                    </div>
               
          </div>
		  
        </section>
        
      </div>
    </div>
  </section>
</section>
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script>
    jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        //alert(<?php //echo $task_ids;?>);
       // jQuery(".rack_class1").validationEngine();
        //getTask();
    });
    /*function getTask()
    {
        var verify_option = $("#verify_option").val();
        var stock_verify_by = $("input:radio[name=stock_verify_by]:checked").val();
        var url = getUrl("<?php //echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getTask', '',1);?>");
    	$.ajax({
			url : url,
			method : 'post',		
			data : {verify_option : verify_option,stock_verify_by:stock_verify_by},
			success: function(response){
				console.log(response);
			//	$(".hide_div").show();
				//$(".data_div").html(response);
			}
			
		});
    }*/
    function submit_records(task_id)
    {
        if($("#rack_form"+task_id).validationEngine('validate'))
	    {   var formData = $("#rack_form"+task_id).serialize();
            var stock_verify_by = $("input:radio[name=stock_verify_by]:checked").val();
            var url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=submitTask', '',1);?>");
        	$.ajax({
    			url : url,
    			method : 'post',		
    			data : {formData : formData,stock_verify_by:stock_verify_by},
    			success: function(response){
    				location.reload();
    			}
    		});
        }
    }
    function verify_records(task_ids)
    {
        var url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=verify_records', '',1);?>");
    	$.ajax({
			url : url,
			method : 'post',		
			data : {task_ids : task_ids},
			success: function(response){
				window.location.href='<?php echo HTTP_SERVER; ?>/admin/index.php?route=rack_masterstock_verification&option=<?php echo $_GET['option'];?>&verify=1';
			}
		});
    }
    function submit_comments(task_id)
    {
        if($("#rack_form"+task_id).validationEngine('validate'))
	    {   var formData = $("#rack_form"+task_id).serialize();
            var stock_verify_by = $("input:radio[name=stock_verify_by]:checked").val();
            var url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=submit_comments', '',1);?>");
        	$.ajax({
    			url : url,
    			method : 'post',		
    			data : {formData : formData,stock_verify_by:stock_verify_by},
    			success: function(response){
    			    location.reload();
    			}
    		});
        }
    }
    function close_task(task_ids)
    {
        var url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=close_task', '',1);?>");
    	$.ajax({
			url : url,
			method : 'post',		
			data : {task_ids : task_ids},
			success: function(response){
				set_alert_message('Your task has been Closed!',"alert-success","fa-check");
				location.reload();
			}
		});
    }
</script> 
<?php  }else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>