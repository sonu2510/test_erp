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

$total_ink = $obj_ink->getTotalInk();
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
          <header class="panel-heading bg-white"> <?php echo $display_name;?> Listing</header>
          <div class="panel-body">
            <div class="row text-small">
              <div class="col-sm-6 m-b-mini">
              		<?php //if(!$total_ink) { ?>
   					 <a class="btn btn-primary btn-sm btn-circle active" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>"><i class="fa fa-plus"></i>Add </a>
                     <?php // } ?>
              </div>                   
            </div>
          </div>
          <form name="form_list" id="form_list" method="post">
           <input type="hidden" id="action" name="action" value="" />
          	<div class="table-responsive">
                <table class="table table-striped b-t text-small">
                  <thead>
                    <tr>
                      <th>Price</th>
                      <!--ruchi-->
                      <th>Make Type</th>
                      <th>Status</th>
                      <!--ruchi-->
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                  $pagination_data = '';
                  if($total_ink){
                        if (isset($_GET['page'])) {
                            $page = (int)$_GET['page'];
                        } else {
                            $page = 1;
                        }
                      //oprion use for limit or and sorting function	
                      $option = array(
                            'sort'  => 'ink_master_id',
                            'order' => 'ASC',
                            'start' => ($page - 1) * LISTING_LIMIT,
                            'limit' => LISTING_LIMIT
                      );	
                      $inks = $obj_ink->getInks($option);
                      foreach($inks as $ink){ 
                        ?>
                        <tr>
                          <td><?php echo $ink['price'];?></td>
                          <!--ruchi-->
                          <td><?php echo $ink['make_name'];?></td>
                          <td><label class="label   
							<?php echo ($ink['status']==1)?'label-success':'label-warning';?>">
							<?php echo ($ink['status']==1)?'Active':'Inactive';?>
							</label>
                          </td>
                          <!--ruchi-->
                          <td>
                                <a href="<?php echo $obj_general->link($rout, 'mod=add&ink_id='.encode($ink['ink_master_id']), '',1); ;?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                           </td>
                        </tr>
                        <?php
                      }
                        
                        //pagination
                        $pagination = new Pagination();
                        $pagination->total = $total_ink;
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