<?php

//jayashree
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

$limit = LISTING_LIMIT;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
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
$user_type_id = $obj_session->data['LOGIN_USER_TYPE'] ;
$user_id = $obj_session->data['ADMIN_LOGIN_SWISS'];
// modify by [kinjal] on (17/10/2016)
$total_currency = $obj_currency->getTotalCurrency($user_id,$user_type_id);
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
            	<a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>"><i class="fa fa-plus"></i> Add </a>
                    <?php 
					if($obj_general->hasPermission('edit',$menuId)){ ?>
                        <a class="label bg-success" style="margin-left:4px;" onclick="formsubmitsetaction('form_list','active','post[]','<?php echo ACTIVE_WARNING;?>')"><i class="fa fa-check"></i> Active</a>
                        <a class="label bg-warning" onclick="formsubmitsetaction('form_list','inactive','post[]','<?php echo INACTIVE_WARNING;?>')"><i class="fa fa-times"></i> Inactive</a>
                     <?php }
					/*if($obj_general->hasPermission('delete',$menuId)){ ?>       
                        <a class="label bg-danger" onclick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>
                    <?php }*/ ?>
            </span>
          </header>
          
          <form name="form_list" id="form_list" method="post">
           <input type="hidden" id="action" name="action" value="" />
          	<div class="table-responsive">
            	
                <table class="table table-striped b-t text-small">
                  <thead>
                    <tr>
                      <th width="20"><input type="checkbox"></th>
                      <th>Currency Code</th>
                      <th>Currency Name</th>
                      <th>Price / 
                       <?php
					 //$count = $obj_currency->getTotalcurrencyname($user);
					//if($count)
					if($user_type_id != 2)
					{
						$currname=$obj_currency->getcurrencyname($user_id,$user_type_id);
						//printr($currname);
						//die;
						foreach($currname as $name)
						{
						echo $name['currency_code'];
						}
					}
					else
					{
						echo "No Record Found";
					}
				?></th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                <input type="text" name="user_type_id" id="user_type_id" value="<?php echo $user_type_id?>" hidden/>
              	<input type="text" name="user_id" id="user_id" value="<?php echo $user_id?>" hidden/>
                  <?php
                  if($total_currency){
                        if (isset($_GET['page'])) {
                            $page = (int)$_GET['page'];
                        } else {
                            $page = 1;
                        }
                      //oprion use for limit or and sorting function	
                      $option = array(
                            'sort'  => 'currency',
                            'order' => 'ASC',
                            'start' => ($page - 1) * LISTING_LIMIT,
                            'limit' => LISTING_LIMIT
                      );	
                      // modify by [kinjal] on (17/10/2016)
					  $count = $obj_currency->getTotalCurrency($user_id,$user_type_id);
					  //echo $count;
					  if($count)
					  {
					    $currencys = $obj_currency->getCurrencys($option,$user_id,$user_type_id);
					 	//printr($currencys);die;
                        foreach($currencys as $currency){ 
                        ?>
                        <tr>
                          <td><input type="checkbox" name="post[]" value="<?php echo $currency['currency_id'];?>"></td>
                          <td><?php echo $currency['currency_code'];?></td>
                          <td><?php echo $currency['currency_name'];?></td>
                          <td><?php echo $currency['price'];?></td>
                          <td><label class="label   
							<?php echo ($currency['status']==1)?'label-success':'label-warning';?>">
                            <?php echo ($currency['status']==1)?'Active':'Inactive';?>
                            </label>
                          </td>
                          <td>
                                <a href="<?php echo $obj_general->link($rout, 'mod=add&currency_id='.$currency['currency_id'], '',1); ;?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                           </td>
                        </tr>
                        <?php
                      }
					  }
                        
                        //pagination
                        $pagination = new Pagination();
                        $pagination->total = $total_currency;
                        $pagination->page = $page;
                        $pagination->limit = LISTING_LIMIT;
                        $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                        $pagination->url = $obj_general->link($rout, '&page={page}&limit='.$limit.'', '',1);
                        $pagination_data = $pagination->render();
                        
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