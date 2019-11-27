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
if(!isset($_GET['filter_edit']) || $_GET['filter_edit']==0){
	if(isset($obj_session->data['filter_data'])){
		unset($obj_session->data['filter_data']);	
	}
}
$limit = LISTING_LIMIT;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}
$history_id = isset($_GET['history_id'])?$_GET['history_id']:'';
$history = base64_decode($history_id);
$class = 'collapse';
$filter_data=array();
if(isset($obj_session->data['filter_data'])){
	$filter_stock_order_id = $obj_session->data['filter_data']['stock_order_id'];
		$filter_user_name = $obj_session->data['filter_data']['postedby'];
	$filter_date = $obj_session->data['filter_data']['date'];
	$class = '';
	
	$filter_data=array(
		'stock_order_id' => $filter_stock_order_id,
		'date' => $filter_date, 
		'postedby' => $filter_user_name,
	);
}
if(isset($_POST['btn_filter'])){
	
	$filter_edit = 1;
	$class = '';	
	if(isset($_POST['filter_order'])){
		$filter_order=$_POST['filter_order'];	
			
	}else{
		$filter_order='';
	}
	
	if(isset($_POST['filter_date'])){
		$filter_date=$_POST['filter_date'];		
	}else{
		$filter_date='';
	}
	if(isset($_POST['filter_user_name']))
	{
		$filter_user_name = $_POST['filter_user_name'];
	}else{
		$filter_user_name='';
	}
	
	$filter_data=array(
		'stock_order_id' => $filter_order,
		'date' => $filter_date, 
		'postedby' => $filter_user_name,
	);
	
	$obj_session->data['filter_data'] = $filter_data;	
}
if(isset($_POST['btn_checkout'])){
		$status = '1';
		$checkoutorder = $obj_template->Checkoutrecords($status,$_POST);
		page_redirect($obj_general->link($rout, 'mod=cartlist_view&status=0', '',1));
	}

if($display_status) {
	$checkNewCartPermission = $obj_template->checkNewCartPermission($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE']);
	$orderLimit = $obj_template->orderLimit($obj_session->data['ADMIN_LOGIN_SWISS'],$obj_session->data['LOGIN_USER_TYPE']);
	$permission = '';
	for($i=1;$i<$orderLimit;$i++)
	{
		if($checkNewCartPermission[0]['order_s_no'] == $i)
		{
			$permission =$i+1;
		}		
	}
	if($checkNewCartPermission[0]['order_s_no'] == '')
	{
		$permission =1;
	}
	
	if(isset($_POST['action']) && $_POST['action'] == "sendemail" && isset($_POST['post']) && !empty($_POST['post'])){
		if(!$obj_general->hasPermission('add',$menuId)){
			$display_status = false;
		} else {
			foreach($_POST['post'] as $gen_order_id){
				$obj_quotation->deleteQuotation($gen_order_id);
			}
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
		  	<span>Email History Listing</span>
          	<span class="text-muted m-l-small pull-right">
            	
          
            </span>
          </header>
             <div class="panel-body">
                        <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link($rout, 'mod=email_history', '',1); ?>">
                        <section class="panel pos-rlt clearfix">
            		    <header class="panel-heading">
                    		<ul class="nav nav-pills pull-right">
                      		<li> <a href="#" class="panel-toggle text-muted active"><i class="fa fa-caret-down fa-lg text-active"></i>
                            <i class="fa fa-caret-up fa-lg text"></i></a> </li>
                   	    	</ul>
                    		<i class="fa fa-search"></i> Search
                  		</header>
                        
                  	<div class="panel-body clearfix <?php echo $class; ?>">        
                     	 <div class="row">
                        	<div class="col-lg-4">
                            	  <div class="form-group">
                                		<label class="col-lg-5 control-label">Order No</label>
                                		<div class="col-lg-7">
                                  		<input type="text" name="filter_order" value="<?php echo isset($filter_order) ? $filter_order : '' ; ?>" placeholder="Order NO" id="input-name" class="form-control" />
                                		</div>
                              		</div>
                               		<div class="form-group">
                                		<label class="col-lg-5 control-label">Date</label>
                                		<div class="col-lg-7">                                                            
                                             <input type="text" name="filter_date" readonly="readonly" data-date-format="dd-mm-yyyy" 
                                             value="<?php echo isset($filter_date) ? $filter_date : '' ; ?>" 
                                             placeholder="Date" id="input-name" class="input-sm form-control datepicker" />
                                		</div>
                              		</div>
                                        </div>
						<div class="col-lg-4">
                              <div class="form-group">
                                <label class="col-lg-5 control-label">Posted By User</label>                                
                                <?php							
									$userlist = $obj_template->getUserList();
									//printr($userlist);
									//die;
								?>
                                <div class="col-lg-7">
                                	<select class="form-control" name="filter_user_name">
                                    	<option value="">Please Select</option>
                                    	<?php 
									$val = explode("=",$filter_user_name);
										foreach($userlist as $user) { ?>
                                    <?php if(isset($filter_user_name) && !empty($val[0]) && $val[0] == $user['user_type_id'] && $val[1]==$user['user_id'] ) 																															                                      { ?>                                       
                                    			<option value="<?php echo $user['user_type_id']."=".$user['user_id']; ?>" selected="selected">
												<?php echo $user['user_name']; ?></option>
                                            <?php } else { ?>
                                            	<option value="<?php echo $user['user_type_id']."=".$user['user_id']; ?>"><?php echo $user['user_name']; ?></option>
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
                        <input type="hidden" value="<?php echo $status;?>" id="status" name="status" />
                        <button type="submit" class="btn btn-primary btn-sm pull-right ml5" name="btn_filter"><i class="fa fa-search"></i> Search</button>
                        <a href="<?php echo $obj_general->link($rout, 'mod=email_history', '',1); ?>" class="btn btn-info btn-sm pull-right" ><i class="fa fa-refresh"></i> Refresh</a>
                       </div> 
                    </div>
                  </footer>                                  
              </section>
           </form>    
            <div class="col-lg-3 pull-right">	
                <select class="form-control" id="limit-dropdown" onchange="location=this.value;">
                <option value="<?php echo $obj_general->link($rout, '', '',1);?>" selected="selected">--Select--</option>	
					<?php 
                        $limit_array = getLimit(); 
                        foreach($limit_array as $display_limit) {
                            if($limit == $display_limit) {	 
                    ?>
                            <option value="<?php echo $obj_general->link($rout, 'mod=email_history'.'&limit='.$display_limit, '',1);?>" selected="selected"><?php echo $display_limit; ?></option>				
                    <?php } else { ?>
                            <option value="<?php echo $obj_general->link($rout, 'mod=email_history'.'&limit='.$display_limit, '',1);?>"><?php echo $display_limit; ?></option>
                    <?php } ?>
                    <?php } ?>
                 </select>
           </div>
             <label class="col-lg-1 pull-right" style="margin-top:5px;">Show</label>             
          </div>
          	<div class="table-responsive">
           <form name="form_list" id="form_list" method="post">
           <input type="hidden" id="action" name="action" value="" />
          	<div class="table-responsive">
                <table id="quotation-row" class="table b-t text-small table-hover">
                  <thead>
                    <tr>
                     
                       <th>Order No</th>
                      <th>Date</th>
                    <th>Posted By</th>
                    
                  </tr>
                  </thead>
                  <tbody>
                  <?php //printr($filter_data);
				  //die;
				  if (isset($_GET['page'])) {
                            $page = (int)$_GET['page'];
                        } else {
                            $page = 1;
                        }
				 $total_order = $obj_template->GetEmailHistoryList($filter_data);
				 $total_orders = count($total_order);
				  	  $pagination_data = '';
                      if($total_order!=''){
                  $orders = $obj_template->GetEmailHistoryList($filter_data);
					 foreach($orders as $order){  
							?>
                       
              <td>  <a href="<?php echo $obj_general->link($rout, 'mod=email_his&group_id='.encode($order['group_id']), '',1);?>"><?php  echo $order['gen_stock_order_id'];?></a>
                     		</td>
							  <td><a href="<?php echo $obj_general->link($rout,'mod=email_his&group_id='.encode($order['group_id']),'',1);?>"><?php echo dateFormat(4,$order['date']);?></a></td>
									
                                    <td>  <?php
                                     $postedByData = $obj_template->getUser($order['user_id'],$order['user_type_id']);
                                     $addedByImage = $obj_general->getUserProfileImage($order['user_type_id'],$order['user_id'],'100_');
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
                                     <a  data-trigger="hover" data-toggle="popover" data-html="true" data-placement="top" data-content='<?php echo $postedByInfo;?>' title="" data-original-title="<b><?php echo $postedByName;?></b>">
                                     <span class="label bg-info" style="font-size: 100%; "><?php echo $postedByData['user_name'];?></span>
                                     </a> </td>
                                     
                            </tr>
							 <input type="hidden" name="product_template_order_id" id="product_template_order_id" value="<?php echo $value;?>" />
                        <input type="hidden" name="template_order_id" id="template_order_id" value="<?php echo $temp_order_id;?>" />
              </div>
                         <?php 
					 }
						  $pagination = new Pagination();
                                    $pagination->total = $total_orders;
                                    $pagination->page = $page;
                                    $pagination->limit = $limit;
                                    $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                                    $pagination->url = $obj_general->link($rout,'&mod=email_history&page={page}&limit='.$limit, '',1);//HTTP_ADMIN.'index.php?rout='.$rout.'&page={page}';
                                    $pagination_data = $pagination->render();
                                    //echo $pagination_data;die;
						 } else{ 
                            echo "No record found !";
                         } ?>
                   </div>
                    </tbody>
                </table>
                 </form> 
                  <footer class="panel-footer">
                        <div class="row">
                            <div class="col-sm-3 hidden-xs"> </div>
                            <?php echo $pagination_data;?>
                        </div>
                    </footer>
            </div>
         </section>
         </div>
        </div>
     </section>
</section>
<script type="application/javascript">
function reloadPage(){
	location.reload();
}
function removeTemplateOrder(template_order_id){
	var remove_templateorder_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=removeTemplateOrder', '',1);?>");
	$.ajax({
		url : remove_templateorder_url,
		method : 'post',
		data : {template_order_id : template_order_id},
		success: function(response){
			set_alert_message('Successfully Deleted',"alert-success","fa-check");
			reloadPage();	
		},
		error: function(){
			return false;	
		}
	});
	}
</script>         
<style>
pre {
display: inline;
 padding:0px; 
 margin: 0 0 0px; 
font-size: 13px;
 line-height: 0; 
 word-break:normal; 
 word-wrap:normal; 
 background-color:transparent; 
 border: 0px solid #ccc; 
 border-radius: 0px; 
}
</style>  
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>