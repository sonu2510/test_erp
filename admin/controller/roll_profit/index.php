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
	$sort_name = 'from_kg';
}

if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'ASC';	
}

if($display_status) {

$total_profit = $obj_profit->getTotalRollProfit();
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
		  	<span><?php echo $display_name;?> Listing </span>
          	<span class="text-muted m-l-small pull-right">
            	<a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>"><i class="fa fa-plus"></i>Add </a>			
          	</span>
          </header>
          
          <form name="form_list" id="form_list" method="post">
           <input type="hidden" id="action" name="action" value="" />
          	<div class="table-responsive">
                <table class="table table-striped b-t text-small table-hover">
                  <thead>
                    <tr>
                      <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">
                          From Kg
                          <span class="th-sort">
                            <a href="<?php echo $obj_general->link($rout, 'sort=from_kg'.'&order=ASC', '',1);?>">
                            <i class="fa fa-sort-down text"></i>
                            <a href="<?php echo $obj_general->link($rout, 'sort=from_kg'.'&order=DESC', '',1);?>">
                            <i class="fa fa-sort-up text-active"></i>
                          <i class="fa fa-sort"></i></span>
                      </th>
                      
                      <th class="th-sortable <?php echo ($sort_order=='ASC') ? 'active' : ''; ?> ">
                          To Kg
                          <span class="th-sort">
                            <a href="<?php echo $obj_general->link($rout, 'sort=to_kg'.'&order=ASC', '',1);?>">
                            <i class="fa fa-sort-down text"></i>
                            <a href="<?php echo $obj_general->link($rout, 'sort=to_kg'.'&order=DESC', '',1);?>">
                            <i class="fa fa-sort-up text-active"></i>
                          <i class="fa fa-sort"></i></span>
                      </th>
                     
                      <th>Profit / Kg</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                  if($total_profit){
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
                      $profits = $obj_profit->getRollProfits($option);
                      foreach($profits as $profit){ 
                        ?>
                        <tr>
                        	<td><?php echo $profit['from_kg'];?></td>
                          	<td><?php echo $profit['to_kg'];?></td>
                            <td><?php echo $profit['profit_kg'];?></td>
                          	
                          	<td>
                                <a href="<?php echo $obj_general->link($rout, 'mod=add&profit_id='.encode($profit['product_roll_profit_id']), '',1); ;?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                           </td>
                        </tr>
                        <?php
                      }
                        
                        //pagination
                        $pagination = new Pagination();
                        $pagination->total = $total_profit;
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