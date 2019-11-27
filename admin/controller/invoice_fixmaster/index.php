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

if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}
if(isset($_GET['sort'])){
	$sort_name = $_GET['sort'];
}else{
	$sort_name='fix_master_id';
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
			
			$obj_fixmaster->updateFixmasterStatus($status,$_POST['post']);
			$obj_session->data['success'] = UPDATE;
			page_redirect($obj_general->link($rout, '', '',1));
		}
	}else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
		if(!$obj_general->hasPermission('delete',$menuId)){
			$display_status = false;
		} else {
			//printr($_POST['post']);die;
			$obj_fixmaster->updateFixmasterStatus(2,$_POST['post']);
			$obj_session->data['success'] = UPDATE;
			page_redirect($obj_general->link($rout, '', '',1));
		}
	
	}
$total_fixmaster = $obj_fixmaster->getTotalFixMaster();
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
            	
                <?php /*if($obj_general->hasPermission('add',$menuId)){ ?>
   							<a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>"><i class="fa fa-plus"></i> Add </a>
                    <?php }*/
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
          </div>
          <form method="post" id="form_list" name="form_list">
           <input type="hidden" value="" name="action" id="action">
          	<div class="table-responsive">
                <table class="table b-t text-small table-hover">
                  <thead>
                    <tr>
                      <th width="20"><input type="checkbox">
                      </th>                     
                      <th>Exporter </th>
                      <th>Country of origin of goods</th>
                      <th>Goods description</th>                      
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                  if($total_fixmaster){
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
                      $fixmaster = $obj_fixmaster->getFix($option);
					// printr($fixmaster);die;
                      foreach($fixmaster as $fix){ 
                        ?>
                         <tr <?php echo ($fix['status']==0) ? 'style="background-color:#FADADF" ' : '' ; ?>>
                          <td><input type="checkbox" name="post[]" value="<?php echo $fix['fix_master_id'];?>"></td>
                           <td><?php echo $fix['exporter'];?></td>
                          <td><?php echo $fix['country_origin_goods'];?></td>
                          <td><?php echo $fix['googs_description'];?></td>
                          <td>
                          	
                           		<div data-toggle="buttons" class="btn-group">
                                	<label class="btn btn-xs btn-success <?php echo ($fix['status']==1) ? 'active' : '';?> "> <input type="radio" 
                                    name="status" value="1" id="<?php echo $fix['fix_master_id']; ?>"> <i class="fa fa-check text-active"></i>Active</label>
                                     
                                	<label class="btn btn-xs btn-danger <?php echo ($fix['status']==0) ? 'active' : '';?> "> <input type="radio" 
                                    name="status" value="0" id="<?php echo $fix['fix_master_id']; ?>"> <i class="fa fa-check text-active"></i>Inactive</label> 
                                </div>
                          
                           </td>
                           <td>
                                <a href="<?php echo $obj_general->link($rout, 'mod=add&fix_master_id='.encode($fix['fix_master_id']), '',1); ;?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                           </td>
                        </tr>
                        
                  <?php   }
                        
                        //pagination
                        $pagination = new Pagination();
                        $pagination->total = $total_fixmaster;
                        $pagination->page = $page;
                        $pagination->limit = $limit;
                        $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                        $pagination->url = $obj_general->link($rout, '&page={page}&limit='.$limit.'&filter_edit=1', '',1);
                        $pagination_data = $pagination->render();
                    } else{ 
                      echo "<tr><td colspan='5'>No record found !</td></tr>";
                  }?> </tbody>
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
		var fixmaster_id=$(this).attr('id');
		var status_value = this.value;
		var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=updateInvoiceFixStatus', '',1);?>");
        $.ajax({
			
			url : status_url,
			type :'post',
			data :{fixmaster_id:fixmaster_id,status_value:status_value},
			success: function(){
				//alert(response);
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