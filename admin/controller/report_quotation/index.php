<?php
	include("mode_setting.php");
	
	$filter_data=array();
	
	
	$limit = LISTING_LIMIT;
	
	if(isset($_GET['order']))
	{
		$sort_order=$_GET['order'];
	}
	else
	{
		$sort_order='ASC';
	}
	
	if(isset($_GET['sort']))
	{
		$sort_name = $_GET['sort'];
	}
	else
	{
		$sort_name = 'user_name';
	}
	
	 /*?>if(isset($obj_session->data['filter_data'])){
	$filter_username = $obj_session->data['filter_data']['username'];
	$filter_email = $obj_session->data['filter_data']['email'];
	$filter_status = $obj_session->data['filter_data']['status'];
	$class = '';
	
	$filter_data=array(
		'name' => $filter_name,
		'username' => $filter_username, 
		'email' => $filter_email,
		'status' => $filter_status
	);	
}<?php */?>
?>
<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-users"></i> Quotation Report</h4>
    </div>
    <div class="row">
    	
        <div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>  
        
      <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading">
          	 <span>Quotation Report Listing</span>
             <span class="text-muted m-l-small pull-right">
             		
             </span>
          </header>
          <div class="panel-body"></div>
             
          <form name="form_list" id="form_list" method="post">   
          <input type="hidden" id="action" name="action" value="" /> 
          <div class="table-responsive">
            <table class="table table-striped b-t text-small">
              <thead>
                <tr>
                  <th width="20"><input type="checkbox"></th>
                  <th>User Name</th>
                  <th class="th-sortable">User Type</th>
                  <th class="th-sortable">Active </th>
                  <th>Inactive</th>
                  <th>Not Save</th>
                </tr>
              </thead>
              <tbody>
              <?php
              $user_total = $obj_user->getTotalQuotationUser($filter_data);
			  $pagination_data = '';
			  if($user_total){
				   	if (isset($_GET['page'])) {
						$page = $_GET['page'];
					} else {
						$page = 1;
					}
					

				  //oprion use for limit or and sorting function	
				  $option = array(
				  		'start' => ($page - 1) * $limit,
						'limit' => $limit,
						'sort'  => $sort_name,
						'order' => $sort_order
				  );	
				  $quotations = $obj_user->getQuotationUser($option,$filter_data);
				  if($quotations) {	
				  	foreach($quotations as $quotation){ 
				  ?>
                    <tr>
                      <td><input type="checkbox" name="post[]" value=""></td>
                      <td><?php echo $quotation['user_name'];?></td>
                      <td><?php echo $quotation['type'];?></td>
                      <td><?php echo $quotation['active'];?></td>
                      <td><?php echo $quotation['inactive'];?></td>
                      <td><?php echo $quotation['not_saved'];?></td>
                    <?php
					}
				  
				  }else{
					echo "<tr><td colspan='5'>No record found !</td></tr>"; 
				  }
				    
					//pagination
				  	$pagination = new Pagination();
					$pagination->total = $user_total;
					$pagination->page = $page;
					$pagination->limit = $limit;
					$pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
					$pagination->url = $obj_general->link($rout, '&page={page}', '',1);
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