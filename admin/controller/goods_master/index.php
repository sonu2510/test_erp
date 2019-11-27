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

$filter_data=array();
$filter_value='';

$class = 'collapse';

if(isset($_POST['btn_filter'])){
	
	$filter_edit = 1;
	$class ='';	
	if(isset($_POST['filter_row'])){
		$filter_row=$_POST['filter_row'];		
	}else{
		$filter_row='';
	}
	
	if(isset($_POST['filter_column'])){
		$filter_column=$_POST['filter_column'];		
	}else{
		$filter_column='';
	}
	
	if(isset($_POST['filter_status'])){
		$filter_status=$_POST['filter_status'];
	}else{
		$filter_status='';
	}
		
	$filter_data=array(
		'column_name' => $filter_column,
		'row' => $filter_row,
		'status' => $filter_status
	);
	
	//$obj_session->data['filter_data'] = $filter_data;
}

if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'DESC';	
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
		$obj_goods_master->updateStatus($status,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
}else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
	if(!$obj_general->hasPermission('delete',$menuId)){
		$display_status = false;
	} else {
		$obj_goods_master->updateStatus(2,$_POST['post']);
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
          			
                 <a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>"><i class="fa fa-plus"></i> New Goods </a>
                    <?php if($obj_general->hasPermission('edit',$menuId)){ ?>
                        <a class="label bg-success" style="margin-left:4px;" onclick="formsubmitsetaction('form_list','active','post[]','<?php echo ACTIVE_WARNING;?>')"><i class="fa fa-check"></i> Active</a>
                        <a class="label bg-warning" onclick="formsubmitsetaction('form_list','inactive','post[]','<?php echo INACTIVE_WARNING;?>')"><i class="fa fa-times"></i> Inactive</a>
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
                                <label class="col-lg-5 control-label">Row </label>
                                <div class="col-lg-7">
                                  <input type="text" name="filter_row" value="<?php echo isset($filter_row) ? $filter_row : '' ; ?>" placeholder="Row" id="row" class="form-control" />
                                </div>
                              </div>                             
                          
                              <div class="form-group">
                                <label class="col-lg-5 control-label">Column</label>
                                <div class="col-lg-7">
                                  <input type="text" name="filter_column" value="<?php echo isset($filter_column) ? $filter_column : '' ; ?>" placeholder="Column" id="columna" class="form-control" />
                                </div>
                              </div>                             
                          </div>
                          
                          <div class="col-lg-4">                              
                               <div class="form-group">
                                <label class="col-lg-5 control-label">Status</label>
                                <div class="col-lg-7">
                                  <select name="filter_status" id="input-status" class="form-control">
                                        <option value=""></option>
                                        <option value="1" <?php echo (isset($filter_status) && $filter_status==1) ? 'selected=selected' : ''; ?>>Active</option>
                                        <option value="0" <?php echo (isset($filter_status) && $filter_status==0 && $filter_status !='' ) ? 'selected=selected' : ''; ?>>Inactive</option>
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
          
          <form name="form_list" id="form_list" method="post">
           <input type="hidden" id="action" name="action" value="" />
          	<div class="table-responsive">
                <table class="table b-t text-small table-hover">
                  <thead>
                    <tr>
                      <th width="20"><input type="checkbox"></th>
                      <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">
                      		 Row
                           <!-- <span class="th-sort">
                            	<a href="<?php echo $obj_general->link($rout, 'sort=row'.'&order=ASC', '',1);?>">
                                <i class="fa fa-sort-down text"></i>
                                <a href="<?php echo $obj_general->link($rout, 'sort=row'.'&order=DESC', '',1);?>">
                                <i class="fa fa-sort-up text-active"></i>
                            <i class="fa fa-sort"></i></span>-->
                   	  </th>
                      <th>Column</th>
                      <th>Rack Name</th>
                      <th>Description</th>
                      <th>Posted By</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                  $total_goods = $obj_goods_master->getTotalGoods($filter_data,$obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);
                  $pagination_data = '';
                  if($total_goods){
                        if (isset($_GET['page'])) {
                            $page = (int)$_GET['page'];
                        } else {
                            $page = 1;
                        }
						
						 if (isset($_GET['sort'])) {
                            $sort_option = $_GET['sort'];
                        } else {
                            $sort_option = 'goods_master_id';
                        }
						
                      //oprion use for limit or and sorting function	
                      $option = array(
                            'sort'  => $sort_option,
                            'order' => $sort_order,
                            'start' => ($page - 1) * $limit,
                            'limit' => $limit
                      );	
                      $goods_master = $obj_goods_master->getGoodsMaster($option,$filter_data,$obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);

					  foreach($goods_master as $goods){
					  $postedByData = $obj_goods_master->getUser($goods['user_id'],$goods['user_type_id']);
					  if($postedByData){ 
                        ?>
                        <tr <?php echo ($goods['status']==0) ? 'style="background-color:#FADADF" ' : '' ; ?>> 
						<td><input type="checkbox" name="post[]" value="<?php echo $goods['goods_master_id'];?>"></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&goods_master_id='.encode($goods['goods_master_id']), '',1); ?>"><?php echo $goods['row'];?></a></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&goods_master_id='.encode($goods['goods_master_id']), '',1);?>"><?php echo $goods['column_name'];?></a></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&goods_master_id='.encode($goods['goods_master_id']), '',1);?>"><?php echo $goods['name'];?></a></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&goods_master_id='.encode($goods['goods_master_id']), '',1);?>"><?php echo ucfirst($goods['description']);?></a></td>
                           <td><?php
									$addedByImage = $obj_general->getUserProfileImage($goods['user_type_id'],$goods['user_id'],'100_');
									$postedByInfo = '';
									$postedByInfo .= '<div class="row">';
										$postedByInfo .= '<div class="col-lg-3"><img src="'.$addedByImage.'"></div>';
										$postedByInfo .= '<div class="col-lg-9">';
										if($postedByData['city']){ $postedByInfo .= $postedByData['city'].', '; }
										if($postedByData['state']){ $postedByInfo .= $postedByData['state'].' '; }
										if(isset($postedByData['postcode'])){ $postedByInfo .= $postedByData['postcode']; }
										$postedByInfo .= '<br>Telephone : '.$postedByData['telephone'].'</div>';
									$postedByInfo .= '</div>';
									$postedByName = $postedByData['first_name'].' '.$postedByData['last_name'];
									str_replace("'","\'",$postedByName);
								?>
								<a class="btn btn-info btn-xs" data-trigger="hover" data-toggle="popover" data-html="true" data-placement="top" data-content='<?php echo $postedByInfo;?>' title="" data-original-title="<b><?php echo $postedByName;?></b>"><?php echo $postedByData['user_name'];?></a></td>
                          <td>
                          	<div data-toggle="buttons" class="btn-group">
                                <label class="btn btn-xs btn-success <?php echo ($goods['status']==1) ? 'active' : '';?> ">
                                	<input type="radio" name="status" value="1" id="<?php echo $goods['goods_master_id']; ?>">
                                 	<i class="fa fa-check text-active"></i>Active
                                </label>
                                     
                                <label class="btn btn-xs btn-danger <?php echo ($goods['status']==0) ? 'active' : '';?> ">
                                	<input type="radio" name="status" value="0" id="<?php echo $goods['goods_master_id']; ?>">
                                    <i class="fa fa-check text-active"></i>Inactive
                                </label> 
                            </div>
                          </td>
                          <td>	
                             <a href="<?php echo $obj_general->link($rout, 'mod=add&goods_master_id='.encode($goods['goods_master_id']),'',1); ?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                                
                         </td>
                        </tr>
                        <?php
                      }
                      } 
                        //pagination
                        $pagination = new Pagination();
                        $pagination->total = $total_goods;
                        $pagination->page = $page;
                        $pagination->limit = $limit;
                        $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                        $pagination->url = $obj_general->link($rout, '&page={page}&limit='.$limit.'', '',1);
                        $pagination_data = $pagination->render();
                  } else{ 
                      echo "<tr><td colspan='9'>No record found !</td></tr>";
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
		$("#loading").show();
		var goods_master_id = $(this).attr('id');
		var status_value = this.value;
		var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=updateGoodsStatus', '',1);?>");
        $.ajax({			
			url : status_url,
			type :'post',
			data :{goods_master_id:goods_master_id,status_value:status_value},
			success: function(){
				$("#loading").hide();
				set_alert_message('Successfully Updated',"alert-success","fa-check");
			},
			error:function(){
				$("#loading").hide();
				set_alert_message('Error During Updation',"alert-warning","fa-warning"); 
			}			
		});
    });

</script>           
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>