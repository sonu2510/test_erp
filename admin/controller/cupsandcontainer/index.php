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

if($display_status) {

if(isset($_POST['action']) && ($_POST['action'] == "active" || $_POST['action'] == "inactive") && isset($_POST['post']) && !empty($_POST['post']))
{	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	} else {
		$status = 0;
		if($_POST['action'] == "active"){
			$status = 1;
		}
		$obj_cupsandcontainer->UpdatecupStatus($status,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
}
else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post']))
{
	//printr($_POST['post']);
	if(!$obj_general->hasPermission('delete',$menuId)){
		$display_status = false;
	} else {
		$obj_cupsandcontainer->UpdatecupStatus(2,$_POST['post']);
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
          <header class="panel-heading bg-white"> <span><?php echo $display_name;?> Listing</span>
          	<span class="text-muted m-l-small pull-right">         			
               <a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>" ><i class="fa fa-plus"></i> New Cup & Container </a>
               
                <?php if($obj_general->hasPermission('edit',$menuId)){ ?>
                        <a class="label bg-success" style="margin-left:4px;" onclick="formsubmitsetaction('form_list','active','post[]','<?php echo ACTIVE_WARNING;?>')"><i class="fa fa-check"></i> Active</a>
                        <a class="label bg-warning" onclick="formsubmitsetaction('form_list','inactive','post[]','<?php echo INACTIVE_WARNING;?>')"><i class="fa fa-times"></i> Inactive</a>
                        <a class="label bg-danger" onclick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>
                    <?php } ?>    
               
            </span>	
          </header>
          
          <!--<div class="panel-body">
            
          </div>-->
          <form name="form_list" id="form_list" method="post">
           <input type="hidden" id="action" name="action" value="" />
          	<div class="table-responsive">
                <table class="table b-t text-small table-hover">
                  <thead>
                    <tr>
                    	<th width="20"><input type="checkbox"></th>
                      <th>Name </th>
                      <th>RMC Price </th>
                      <th>Weight In Kgs / PC</th>
                      <th>Status </th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  	<?php
					$count = $obj_cupsandcontainer->getcount();
					$pagination_data = '';
					if($count){
							if (isset($_GET['page'])) {
								$page = (int)$_GET['page'];
							} else {
								$page = 1;
							}
						$results = $obj_cupsandcontainer->getvalue();	
						foreach($results as $result){
						
						?>
						<tr <?php echo ($result['status']==0) ? 'style=background-color:#FADADF' : '' ; ?>>
                          <td><input type="checkbox" name="post[]" value="<?php echo $result['cup_id'];?>"></td> 
						  <td><?php echo $result['cup_name'];?></td>
						  <td><?php echo $result['basic_price'];?></td>
                          <td><?php echo $result['weight'];?> </td>
                         
						  <td>
                          	<div data-toggle="buttons" class="btn-group">
                                <label class="btn btn-xs btn-success <?php echo ($result['status']==1) ? 'active' : '';?> "> <input type="radio" 
                                 name="status" value="1" id="<?php echo $result['cup_id']; ?>"> <i class="fa fa-check text-active"></i>Active</label>                                   
                                <label class="btn btn-xs btn-danger <?php echo ($result['status']==0) ? 'active' : '';?> "> <input type="radio" 
                                    name="status" value="0" id="<?php echo $result['cup_id']; ?>"> <i class="fa fa-check text-active"></i>Inactive</label> 
                           </div>
                          </td>
						  <td>
                          	<a href="<?php echo $obj_general->link($rout,'mod=add&cup_id='.encode($result['cup_id']),'',1)?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
						   </td>
						</tr>
						<?php
						}
						//pagination
                        $pagination = new Pagination();
                        $pagination->total = $count;
                        $pagination->page = $page;
                        $pagination->limit = LISTING_LIMIT;
                        $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                        $pagination->url = $obj_general->link($rout, '&page={page}', '',1);//HTTP_ADMIN.'index.php?rout='.$rout.'&page={page}';
                        $pagination_data = $pagination->render();
                        //echo $pagination_data;die;
					}else{
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
 <script type="application/javascript">
 $('input[name=status]').change(function(){
	var cup_id = $(this).attr('id');
	var cup_status = this.value;
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout,'&mod=ajax&ajaxfun=UpdateStatus','',1);?>");
	 $.ajax({
		 url : url,
		 type : 'post',
		 data : {cup_id:cup_id,cup_status:cup_status},
		 success : function(responce){
			 //alert(responce);
			 set_alert_message('Successfully Updated',"alert-success","fa-check");	
		 },
		 error : function()
		 {
			set_alert_message('Error During Updation',"alert-warning","fa-warning");   
		 }
	 });
 });
 
 
 </script>    
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>