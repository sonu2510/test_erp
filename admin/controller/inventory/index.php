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
	$filter_ident_id = $obj_session->data['filter_data']['indent_id'];
	$filter_vander_id= $obj_session->data['filter_data']['vander_id'];
	$filter_added_by_id = $obj_session->data['filter_data']['added_by_id'];
	$class = '';
	
	$filter_data=array(
		'indent_id' => $filter_ident_id,
		'vander_id' => $filter_vander_id, 
		'added_by_id' => $filter_added_by_id,		
	);
}

if(isset($_GET['sort'])){
	$sort_name = $_GET['sort'];
}else{
	$sort_name='c.indent_id';
}

if(isset($_GET['order'])){
	$sort_order = $_GET['order']; 
}else{
	$sort_order = 'DESC';
}

if(isset($_POST['btn_filter'])){
	
	$filter_edit = 1;
	$class ='';
		
	if(isset($_POST['filter_indent_id'])){
		$filter_indent_id=$_POST['filter_indent_id'];		
	}else{
		$filter_indent_id='';
	}
	
	if(isset($_POST['filter_vander_id'])){
		$filter_vander_id=$_POST['filter_vander_id'];		
	}else{
		$filter_vander_id='';
	}
	if(isset($_POST['filter_added_by_id'])){
		$filter_added_by_id=$_POST['filter_added_by_id'];
	}else{
		$filter_added_by_id='';
	}

	$filter_data=array(
		'indent_id' => $filter_indent_id,
		'vander_id' => $filter_vander_id, 
		'added_by_id' => $filter_added_by_id,				
	);
	$obj_session->data['filter_data'] = $filter_data;	
}


if($display_status) {
	
	if(isset($_POST['action']) && ($_POST['action'] == "active" || $_POST['action'] == "inactive") && isset($_POST['post']) && !empty($_POST['post']))
	{
		if(!$obj_general->hasPermission('edit',$menuId)){
			$display_status = false;
		} else {
			$status = 0;
			if($_POST['action'] == "active"){
				$status = 1;
			}
			$obj_inventory->updateStatus($status,$_POST['post']);
			$obj_session->data['success'] = UPDATE;
			page_redirect($obj_general->link($rout, '', '',1));
		}
	}else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
		if(!$obj_general->hasPermission('delete',$menuId)){
			$display_status = false;
		} else {
			$obj_inventory->updateStatus(2,$_POST['post']);
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
		  	<span>
			<?php if(isset($_GET['status']))
				{
					if($_GET['status']==0)
						$d_name='Purchase Indent Listing';	
						if($_GET['status']==1)
						$d_name='Receive Indent Listing';
						if($_GET['status']==2)
						$d_name='Cancel Indent Listing';
						if($_GET['status']==3)
						$d_name = 'Pending Indent Listing';
						if($_GET['status']==4)
						$d_name = 'Approve Indent Listing';
				}
				else
				{
					$d_name='';
				}echo $d_name;?>
                 </span>
			</span>
          	<span class="text-muted m-l-small pull-right">
            	
                <?php if($obj_general->hasPermission('add',$menuId)){ ?>
   							<a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>"><i class="fa fa-plus"></i> New Inventory</a>
                    <?php }?>
                
            </span>
          </header>
          
          <div class="panel-body">
            
              <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link($rout,'mod=index&status='.$_GET['status'], '',1); ?>">
                
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
                                <label class="col-lg-5 control-label">Indent ID</label>
                                <div class="col-lg-7">
                                  <input type="text" name="filter_indent_id" value="<?php echo isset($filter_indent_id) ? $filter_indent_id : '' ; ?>" placeholder="Indent ID" id="input-name" class="form-control" />
                                </div>
                              </div>
                              
                              <?php  $inventory= $obj_inventory->getVander(); //printr($product);?>
                               <div class="form-group">
                                <label class="col-lg-5 control-label">Company Name</label>
                                <div class="col-lg-7">                                    
                                     <select class="form-control" name="filter_vander_id">
                                    	<option value="">Select Company Name</option>
                                        <?php foreach($inventory as $key=>$value)  {?>
                   <?php if(isset($filter_vander_id) && !empty($filter_vander_id) && $filter_vander_id == $value['vander_id']) { ?>
                   <option value="<?php echo $value['vander_id']; ?>" selected="selected"><?php echo $value['company_name'];?></option>
                            <?php } else { ?>
					 <option value="<?php echo $value['vander_id']; ?>"><?php echo $value['company_name'];?></option>
				   <?php }?>
                   <?php }?>        
                                    </select>                           
                                </div>
                              </div>
                              <?php ?>
                        </div>
                        
                        <div class="col-lg-4">
                              <div class="form-group">
                              <?php
						 if(isset($_GET['status']))
						{
							    if($_GET['status']==0)
								{
									 $label='<label class="col-lg-5 control-label">Added By</label>';
                                     	}
								if($_GET['status']==1)
								{
										$label='<label class="col-lg-5 control-label">Received By</label>';
									}
								if($_GET['status']==2)
								{
										$label='<label class="col-lg-5 control-label">Cancle By</label>';
								 }
								if($_GET['status']==3)
								{
                            			$label='<label class="col-lg-5 control-label">Pending By</label>';
									}
						
						if($_GET['status']==4){
						
                        		$label='<label class="col-lg-5 control-label">Approved By</label>';
						 } 
						}
						echo $label;?>
                        <?php $userlist = $obj_inventory->getUserList(); ?>
                                <div class="col-lg-7">
                                  <select class="form-control" name="filter_added_by_id">
                                    	<option value="">Please Select</option>
                                    	<?php 
 										foreach($userlist as $key=>$user) {
										 ?>
                                  <?php if(isset($filter_added_by_id) && !empty($filter_added_by_id) && $filter_added_by_id == $user['user_type_id']."=".$user['user_id']) { ?>
                                            
                 		<option value="<?php echo $user['user_type_id']."=".$user['user_id'];?>" selected="selected"><?php echo $user['user_name']; ?></option>
                                            <?php } else { ?>
                                       <option value="<?php echo $user['user_type_id']."=".$user['user_id'];?>"><?php echo $user['user_name']; ?></option>
                                            <?php } ?>
                                        <?php } ?>                                       
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
                         <a href="<?php echo $obj_general->link($rout,'mod=index&status='.$_GET['status'], '',1); ?>" class="btn btn-info btn-sm pull-right" ><i class="fa fa-refresh"></i> Refresh</a>
                      </div> 
                   </div>
                </footer>                                  
              </section>
          </form>    
			  
              <div class="row">
              	
                <div class="col-lg-3 pull-right">	
                    <select class="form-control" id="limit-dropdown" onchange="location=this.value;">
                     <option value="<?php echo $obj_general->link($rout,'mod=index&status='.$_GET['status'], '',1);?>" selected="selected">--Select--</option>
                    	<?php 
							$limit_array = getLimit(); 
							foreach($limit_array as $display_limit) {
								if($limit == $display_limit) {	 
						?>
                        		<option value="<?php echo $obj_general->link($rout, 'mod=index&status='.$_GET['status'].'&limit='.$display_limit, '',1);?>"
                                 selected="selected"><?php echo $display_limit; ?></option>				
						<?php } else { ?>
                            	<option value="<?php echo $obj_general->link($rout, 'mod=index&status='.$_GET['status'].'&limit='.$display_limit, '',1);?>"><?php echo $display_limit; ?></option>
                        <?php } ?>
                        <?php } ?>
                    </select>
                </div>
                <label class="col-lg-1 pull-right" style="margin-top:5px;">Show</label>	
              </div>                 
          </div>

          <form name="form_list" id="form_list" method="post" action="<?php echo $obj_general->link($rout,'mod=index&status='.$_GET['status'], '',1); ?>">
           <input type="hidden" id="action" name="action" value="" />
          	<div class="table-responsive">
                <table class="table b-t text-small table-hover">
                  <thead>
                    <tr>
                      
                      <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">
                       Indent ID
                          <span class="th-sort">
                               <a href="<?php echo $obj_general->link($rout,'mod=index&status='.$_GET['status'].'&sort=indent_id'.'&order=ASC', '',1);?>">
                               <i class="fa fa-sort-down text"></i>
                               <a href="<?php echo $obj_general->link($rout,'mod=index&status='.$_GET['status'].'&sort=indent_id'.'&order=DESC', '',1);?>">
                               <i class="fa fa-sort-up text-active"></i>
                         <i class="fa fa-sort"></i></span>
                      </th>
                      
                      <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">
                         Vender Name
                         <span class="th-sort">
                               <a href="<?php echo $obj_general->link($rout,'mod=index&status='.$_GET['status'].'&sort=indent_id'.'&order=ASC', '',1);?>">
                               <i class="fa fa-sort-down text"></i>
                               <a href="<?php echo $obj_general->link($rout,'mod=index&status='.$_GET['status'].'&sort=indent_id'.'&order=DESC', '',1);?>">
                               <i class="fa fa-sort-up text-active"></i>
                         <i class="fa fa-sort"></i></span>
                      </th>
                      
                      <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">
                         Company Name
                         <span class="th-sort">
                               <a href="<?php echo $obj_general->link($rout,'mod=index&status='.$_GET['status'].'&sort=indent_id'.'&order=ASC', '',1);?>">
                               <i class="fa fa-sort-down text"></i>
                               <a href="<?php echo $obj_general->link($rout,'mod=index&status='.$_GET['status'].'&sort=indent_id'.'&order=DESC', '',1);?>">
                               <i class="fa fa-sort-up text-active"></i>
                         <i class="fa fa-sort"></i></span>
                      </th>
                       <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">
                         Entry Date
                      </th>
                      <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">
                         Delivery Date
                      </th>  
                             
                        <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">
                         Reminder Date
                      </th>
                      
                       <?php if(isset($_GET['status']) && $_GET['status']==0)
					{ ?>   
                      <th>Action</th>  
					  <?php }?>
                      
                      <?php
						 if(isset($_GET['status']))
						{
							    if($_GET['status']==0)
								{
								?>
									<th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">	 
									    Added By
										</th>
                         	<?php	 }
								if($_GET['status']==1)
								{
							?>
									<th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">	 
										Received By
                                        </th>
							<?php }
								if($_GET['status']==2)
								{
									?>
									<th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">	 
									
										Cancle By
                                        </th>
							<?php	 }
								if($_GET['status']==3)
								{
								?>
                                	<th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">	 
                            			Pending By
                                        </th>
						<?php
								}
								if($_GET['status']==4)
								{
							?>
                            
                            <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">	 
                        		Approved By
                                </th>
                       <?php                            
						}
					 } 
						?>
                      </th> 
                      
                    </tr>
                  </thead>
                  <tbody>
                  <?php
				   if(isset($_GET['status']) && $_GET['status']==0)
					{
						$cond = 'AND pi.status="'.$_GET['status'].'" AND (ph.rec_qty!=0 OR ph.pending_qty!=0)';
							$mod='purchase_indent_list';
						$total_inventory = $obj_inventory->getTotalInventory($cond,$filter_data,$obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],isset($_GET['status']));
						//printr($total_inventory);
						$pagination_data = '';
                  		if($total_inventory){
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
					
						$inventories = $obj_inventory->getInventory($option,$cond,$filter_data,$obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],isset($_GET['status']));
					//	printr($inventories);
						}
					}
					elseif(isset($_GET['status']) && ($_GET['status']==3 || $_GET['status']==1 || $_GET['status']==2 || $_GET['status']==4))
					{
						if($_GET['status']==1)
						{
							$mod='receive_indent_list';
							$cond='AND ph.rec_qty!=0';
							$order='';
						}
						if($_GET['status']==2)
						{
							$mod='cancle_indent_list';
							$cond='AND ph.cancle_qty!=0';
							$order='';
						}
						if($_GET['status']==3)
						{
							$mod ='pending_indent_list';
							$order='ph.purchase_indent_items_id NOT IN(select ph.purchase_indent_items_id from purchase_indent_history as ph where pending_qty=0) AND';
							//$order='';
							$cond='';
						}
						if($_GET['status']==4)
						{
							$mod ='approve_indent_list';
							$cond='AND ph.approve_qty!=0';
							$order='';
						}
						$total_inventory = $obj_inventory->getTotalPending($order,$cond,$filter_data,$obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],isset($_GET['status']));
						$pagination_data = '';
						
                  		if($total_inventory){
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
						$inventories = $obj_inventory->getPending($option,$order,$cond,$filter_data,$obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE'],isset($_GET['status']));
						}
					}
					
					if($total_inventory){
						
                      foreach($inventories as $inventory){ 
					  $postedByData = $obj_inventory->getUser($inventory['user_id'],$inventory['user_type_id']);
					//printr($postedByData);die;
                        ?>
                        
                           <tr id="in-row-<?php echo $inventory['indent_id']; //printr($inventory['indent_id']); ?>">
                       
                          <td><a href="<?php echo $obj_general->link($rout, 'mod='.$mod.'&indent_id='.encode($inventory['indent_id']).'&filter_edit='.$filter_edit, '',1);?>"><?php  $strpad = str_pad($inventory['indent_id'],8,'0',STR_PAD_LEFT);  echo "INDT".$strpad;?></a></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod='.$mod.'&indent_id='.encode($inventory['indent_id']).'&filter_edit='.$filter_edit, '',1);?>"><?php echo $inventory['vander_first_name'].' '.$inventory['vander_last_name'];?></a></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod='.$mod.'&indent_id='.encode($inventory['indent_id']).'&filter_edit='.$filter_edit, '',1);?>"><?php echo $inventory['company_name'];?></a></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod='.$mod.'&indent_id='.encode($inventory['indent_id']).'&filter_edit='.$filter_edit, '',1);?>"><?php echo dateFormat(4,$inventory['added_date']);?></a></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod='.$mod.'&indent_id='.encode($inventory['indent_id']).'&filter_edit='.$filter_edit, '',1);?>"><?php echo dateFormat(4,$inventory['due_date']);?></a></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod='.$mod.'&indent_id='.encode($inventory['indent_id']).'&filter_edit='.$filter_edit, '',1);?>"><?php echo dateFormat(4,$inventory['reminder_date']);?></a>
                          </td>
                          
                          <?php if(isset($_GET['status']) && $_GET['status']==0)
					{ ?>
                          <td class="delete-quot">
                     <a class="btn btn-danger btn-sm" id="<?php echo $inventory['indent_id']; ?>" href="javascript:void(0);"><i class="fa fa-trash-o"></i></a></td>
                     <?php }?>
                     
                          <td>
                             <?php
								$addedByData = $obj_inventory->getUser($inventory['added_by_id'],$inventory['added_by_type_id']);
								
								$addedByImage = $obj_general->getUserProfileImage($inventory['added_by_id'],$inventory['added_by_type_id'],'100_');
								$addedByInfo = '';
								$addedByInfo .= '<div class="row">';
									$addedByInfo .= '<div class="col-lg-3"><img src="'.$addedByImage.'"></div>';
									$addedByInfo .= '<div class="col-lg-9">';
									if($addedByData['city']){ $addedByInfo .= $addedByData['city'].', '; }
									if($addedByData['state']){ $addedByInfo .= $addedByData['state'].' '; }
									if(isset($addedByData['postcode'])){ $addedByInfo .= $addedByData['postcode']; }
									$addedByInfo .= '<br>Telephone : '.$addedByData['telephone'].'</div>';
								$addedByInfo .= '</div>';
								$addedByName = $addedByData['first_name'].' '.$addedByData['last_name'];
								str_replace("'","\'",$addedByName);
							?>
								<a class="btn btn-info btn-xs" data-trigger="hover" data-toggle="popover" data-html="true" data-placement="top" data-content='<?php echo $addedByInfo;?>' title="" data-original-title="<b><?php echo $addedByName;?></b>"><?php echo $addedByData['user_name'];?></a>                            
                          </td>
                          
                        </tr>
                        <?php
                      }
                        
                        //pagination
                        $pagination = new Pagination();
                        $pagination->total = $total_inventory;
                        $pagination->page = $page;
                        $pagination->limit = $limit;
                        $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                        $pagination->url = $obj_general->link($rout,'mod=index&status='.$_GET['status'].'&page={page}&limit='.$limit.'&filter_edit=1', '',1);
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
$('.delete-quot a').click(function(){
		var con = confirm("Are you sure you want to delete ?");
		if(con){
			var indent_id=$(this).attr('id');
			var del_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=deleteIndent', '',1);?>");
			$('#loading').show();
			//alert(indent_id);
			$.ajax({
				url : del_url,
				type :'post',
				data :{indent_id:indent_id},
				success: function(response){
					if(response){
						//alert(response);
						$('#in-row-'+indent_id).remove();
						set_alert_message('Successfully Deleted',"alert-success","fa-check");	
					}
					$('#loading').hide();	
					location.reload();							
				},
				error:function(){
					set_alert_message('Error!',"alert-warning","fa-warning");          
				}			
			});
		}
	});
	
</script>           
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>