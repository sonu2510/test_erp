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

if(isset($_GET['sort'])){
	$sort_name = $_GET['sort'];	
}else{
	$sort_name = 'effect_name';
}

if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'ASC';	
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
		$obj_sheet->updateStatus($status,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
}else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
	if(!$obj_general->hasPermission('delete',$menuId)){
		$display_status = false;
	} else {
		$obj_sheet->updateStatus(2,$_POST['post']);
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
		  	<span><?php echo $display_name;?> Listing </span>
            
            <span class="text-muted m-l-small pull-right">
          			
                 <a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>"><i class="fa fa-plus"></i> New Sticker Sheet </a>
                    <?php if($obj_general->hasPermission('edit',$menuId)){ ?>
                        <a class="label bg-success" style="margin-left:4px;" onclick="formsubmitsetaction('form_list','active','post[]','<?php echo ACTIVE_WARNING;?>')"><i class="fa fa-check"></i> Active</a>
                        <a class="label bg-warning" onclick="formsubmitsetaction('form_list','inactive','post[]','<?php echo INACTIVE_WARNING;?>')"><i class="fa fa-times"></i> Inactive</a>
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
                          Sheet Name
                          <span class="th-sort">
                            <a href="<?php echo $obj_general->link($rout, 'sort=effect_name'.'&order=ASC', '',1);?>">
                            <i class="fa fa-sort-down text"></i>
                            <a href="<?php echo $obj_general->link($rout, 'sort=effect_name'.'&order=DESC', '',1);?>">
                            <i class="fa fa-sort-up text-active"></i>
                          <i class="fa fa-sort"></i></span>
                      </th>
                      
                      <th>Size</th>
                      <th>Price Per Sheet (In Rs)</th>
                      <th>Weight (In KG)</th>
                      <th>Printing cost (In Rs)</th>
                      <th>Wastage</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                  $total = $obj_sheet->getSheets();
                  $total_sheet = count($total);
                  $pagination_data = '';
                  if($total_sheet){
                        if (isset($_GET['page'])) {
                            $page = (int)$_GET['page'];
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
                      $sheets = $obj_sheet->getSheets($option);
                      foreach($sheets as $sheet){ 
                        ?>
                        <tr>
                          <td><input type="checkbox" name="post[]" value="<?php echo $sheet['sheet_id'];?>"></td>
                          <td><?php echo ucwords($sheet['sheet_name']);?></td>
                          <td><?php echo $sheet['width'].' mm (W) X '.$sheet['height'].' mm (H)';?></td>
                          <td><?php echo $sheet['price'];?></td>
                          <td><?php echo $sheet['weight'];?></td>
                          <td><?php echo $sheet['printing_cost'];?></td>
                          <td><?php echo $sheet['wastage'].' %';?></td>
                          <td>
                            <label class="label <?php echo ($sheet['status']==1)?'label-success':'label-warning';?>">
                                <?php echo ($sheet['status']==1)?'Active':'Inactive';?>
                            </label>
                          </td>
                          <td>
                                <a href="<?php echo $obj_general->link($rout, 'mod=add&sheet_id='.encode($sheet['sheet_id']), '',1); ;?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                          </td>
                        </tr>
                        <?php
                      }
                        
                        //pagination
                        $pagination = new Pagination();
                        $pagination->total = $total_effect;
                        $pagination->page = $page;
                        $pagination->limit = LISTING_LIMIT;
                        $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                        $pagination->url = $obj_general->link($rout, '&page={page}', '',1);//HTTP_ADMIN.'index.php?rout='.$rout.'&page={page}';
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
<script type="application/javascript">
/*$(".th-sortable").click(function(){
	alert("asdasd");
});*/
</script>           
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>