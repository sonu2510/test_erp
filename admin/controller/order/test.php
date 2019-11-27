<?php include("mode_setting.php");

// Make the script run only if there is a page number posted to this script
if(isset($_POST['pn'])){
	$rpp = preg_replace('#[^0-9]#', '', $_POST['rpp']);
	$last = preg_replace('#[^0-9]#', '', $_POST['last']);
	$pn = preg_replace('#[^0-9]#', '', $_POST['pn']);
	// This makes sure the page number isn't below 1, or more than our $last page
	if ($pn < 1) { 
    	$pn = 1; 
	} else if ($pn > $last) { 
    	$pn = $last; 
	}
	$order_id =$_GET['order_id'];
	?>
	<div class="form-group">
                         <div class="col-lg-12"><h4><i class="fa fa-tags"></i> Order History</h4>
                            <div class="line m-t-large" style="margin-top:-4px;"></div><br/>
                            <?php
                               
                              //printr($history_data);die;
							  $history_count = $obj_order->gettotalcountOrderHistories($order_id);
							  
							 $last = ceil($history_count/$rpp);
                             ?>
                             <section class="panel">
                              <div class="table-responsive">
                                <table class="table table-responsive table-striped b-t text-small">
                                  <thead>
                                     <tr>
                                         <th>Order Status</th>
                                         <th>User</th>
                                         <th>Date Added</th>
                                         <th>Note</th>
                                      </tr>
                                   </thead>

                                   <tbody id="history-body">
                                   <?php 
								   
								   $pagination_data = '';
                  					if($history_count){
                        				if (isset($_POST['pn'])) {
                            				$page = (int)$_POST['pn'];
											
                        				} else {
                           					 $page = 1;
                       					 }
								   		 $option = array(
                          				
                          			     'start' => ($page - 1) * $rpp,
                          				'limit' => $rpp
                     					 );
					  
					  				$history_data = $obj_order->getOrderHistories($order_id,$option);	
									//printr($option);
								   if($history_data) { 
								   
								   		foreach($history_data as $hdata)
										{
											
									?>
                                      <tr>
                                         <td><?php echo $hdata['status_name'];?></td>
                                         <td><?php echo $hdata['user_name']; ?></td>
                                         <td><?php echo date("d-M-y",strtotime($hdata['date_added'])); ?></td>
                                         <td><?php echo $hdata['note'];?></td>
                                      </tr>
                                      <?php
										}
										   $pagination = new ajaxpagination();
				   //printr($pagination);
				  // die;
				  //echo $history_count;
                        $pagination->total =$history_count;
                        $pagination->page = $page;
                        $pagination->limit = $rpp;
                        $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                       // $pagination->url = $obj_general->link($rout, '&mod=view&page={page}&limit='.$limit.'', '',1);//HTTP_ADMIN.'index.php?rout='.$rout.'&page={page}';
                        $pagination_data = $pagination->render();
					// printr($pagination_data);
				  // die;
										
								   }
								   else
								   {
										?>
                                        <tr id="no-history">
                                    	<td colspan="5">No History Data Available</td>
                                    </td> 
                                    <?php
								   }
									}
								   ?>
                                        
                                   </tbody>
                                </table> 
                                </div>
                             </section>
                        </div>
                    </div>
                      <footer class="panel-footer">
            <div class="row">
              <div class="col-sm-4 hidden-xs"> </div>
              <?php echo $pagination_data;?>
             
            </div>
          </footer>
     <?php           
}
?>