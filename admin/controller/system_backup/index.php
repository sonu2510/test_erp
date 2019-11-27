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

if($display_status) {
	
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
          <header class="panel-heading"> 
		  		<span><?php echo $display_name;?> Listing</span>
          		<span class="text-muted m-l-small pull-right">
                	<?php if($obj_general->hasPermission('add',$menuId)){ ?>
   							<a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>"><i class="fa fa-plus"></i> Add </a>
                    <?php } ?>
					
                </span>
          
          </header>
          
          <form name="form_list" id="form_list" method="post">
           <input type="hidden" id="action" name="action" value="" />
          	<div class="table-responsive">
            <table class="table table-striped b-t text-small">
              <thead>
                <tr>
                  <th width="20"><input type="checkbox"></th>
                  <th>Last Backup Dates</th>
                  <th>User</th>
                  <th>Database Backup</th>
                  <th>File Backup</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
              <?php
              $total_department = $obj_backup->getTotalBackup();
			  //echo $total_department;die;
			  $pagination_data = '';
			  if($total_department){
				   	if (isset($_GET['page'])) {
						$page = $_GET['page'];
					} else {
						$page = 1;
					}
				  //oprion use for limit or and sorting function	
				  $option = array(
				  		'sort'  => 'system_backup_id',
						'order' => 'ASC',
				  		'start' => ($page - 1) * $limit,
						'limit' => $limit
				  );	
				  $backups = $obj_backup->getBackups($option);
				  foreach($backups as $backup){ 
					?>
                    
                    <tr>
                      <td><input type="checkbox" name="post[]" value="<?php echo $backup['system_backup_id'];?>"></td>
                      <td><?php echo date("d-m-y h:i A",strtotime($backup['date_added']));?></td>
                      <td><?php echo $backup['user_name']; ?></td>
                      <td><?php echo ($backup['database_backup'] == 1) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>'; ?></td>
					  <td><?php echo ($backup['file_backup'] == 1) ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>'; ?></td>	
                      <td><label class="label   
                        <?php echo ($backup['status']==1)?'label-success':'label-warning';?>">
                        <?php echo ($backup['status']==1)?'Active':'Inactive';?>
                        </label>
                      </td>                     
                    </tr>
                    <?php
				  }
				    
					//pagination
				  	$pagination = new Pagination();
					$pagination->total = $total_department;
					$pagination->page = $page;
					$pagination->limit = $limit;
					$pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
					$pagination->url = HTTP_ADMIN.'index.php?rout='.$rout.'&page={page}';
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
