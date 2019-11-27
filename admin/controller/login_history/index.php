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
			$obj_currency->updateStatus($status,$_POST['post']);
			$obj_session->data['success'] = UPDATE;
			page_redirect($obj_general->link($rout, '', '',1));
		}
	}else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post'])){
		if(!$obj_general->hasPermission('delete',$menuId)){
			$display_status = false;
		} else {
			//printr($_POST['post']);die;
			$obj_currency->updateStatus(2,$_POST['post']);
			$obj_session->data['success'] = UPDATE;
			page_redirect($obj_general->link($rout, '', '',1));
		}
	}

$total_history = $obj_login_history->getTotalHistory();
//$obj_login_history->insertDuration($_SESSION['history_id']);
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
            	
              <?php if($obj_general->hasPermission('delete',$menuId)){ ?>       
                    <a class="label bg-danger" onclick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>
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
                      <th>User</th>
                      <th>Email</th>
                      <th>Date</th>
                      <th>Currently Login</th>
                      <th>Duration</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                  if($total_history){
                        if (isset($_GET['page'])) {
                            $page = (int)$_GET['page'];
                        } else {
                            $page = 1;
                        }
                      //oprion use for limit or and sorting function	
                      $option = array(
                          'sort'  => 'login_history_id',
                          'order' => 'DESC',
                          'start' => ($page - 1) * LISTING_LIMIT,
                          'limit' => LISTING_LIMIT
                      );	
                      $reports = $obj_login_history->getLoginReports($option);
					  //printr($reports);die;
                      foreach($reports as $report){ 
                        ?>
                       
                        <tr style="cursor:pointer" class="heading-row">
                          <td><input type="checkbox" name="post[]" value="<?php echo $report['login_history_id'];?>"></td>
                          <td><?php echo $report['user_name'];?></td>
                          <td><?php echo $report['email'];?></td>
                          <td><?php echo date("d-M-Y,h:i A",strtotime($report['last_login']));?></td>
                          <td><label class="label   
							<?php echo ($report['login_status']==1)?'label-success':'label-warning';?>">
                            <?php echo ($report['login_status']==1)?'Yes':'No';?>
                            </label>
                          </td>
                          <td><?php echo $report['login_duration'];?></td>
                        </tr>
                        <tr class="collapse-row" style="display:none">
                        	<td colspan="6">
                            	Name : <?php echo $report['name'];?> <br/>
                            	Ip: <?php echo $report['ip']; ?><br/>	
                            	Browser : <?php echo $report['browser']; ?><br/>
                            </td>   
                        </tr>
                       
                        <?php
                      }
                        //echo $_SESSION['history_id'];die;
                        //pagination
                        $pagination = new Pagination();
                        $pagination->total = $total_history;
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
	$('.heading-row').click(function(){
		if($(this).hasClass('visible')){
			$(this).removeClass('visible');
			$(this).next('.collapse-row').fadeOut('slow');
		}else{
			$(this).addClass('visible');
			$(this).next('.collapse-row').fadeIn('slow');
		}
		//$('.collapse-row').show();
	});

</script>           
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>