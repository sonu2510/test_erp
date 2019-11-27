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
//printr($limit);
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}
if($display_status) {
//$tax = $obj_tax_calender->addTax($post);
//$pagination_data = '';

//active inactive delete
if(isset($_POST['action']) && ($_POST['action'] == "active" || $_POST['action'] == "inactive") && isset($_POST['post']) && !empty($_POST['post']))
{	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	} else {
		$status = 0;
		if($_POST['action'] == "active"){
			$status = 1;
		}
		$obj_tax_calender->updateStatus($status,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
}
else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post']))
{
	if(!$obj_general->hasPermission('delete',$menuId)){
		$display_status = false;
	} else {
		$obj_tax_calender->updateStatus(2,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
}

//$total_tax = $obj_tax_calender->getTotalTax();

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
          			
                 <a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>"><i class="fa fa-plus"></i> New Tax Calender </a>
                    <?php if($obj_general->hasPermission('edit',$menuId)){ ?>
                        <a class="label bg-success" style="margin-left:4px;" onclick="formsubmitsetaction('form_list','active','post[]','<?php echo ACTIVE_WARNING;?>')"><i class="fa fa-check"></i> Active</a>
                        <a class="label bg-warning" onclick="formsubmitsetaction('form_list','inactive','post[]','<?php echo INACTIVE_WARNING;?>')"><i class="fa fa-times"></i> Inactive</a>
                        <a class="label bg-danger" onclick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>
                    <?php } ?>                      
                    
            </span>
          </header>
          
           		  
          <div class="panel-body">
          
           <div class="row">
             <div class="col-lg-3 pull-right">	
                 <select class="form-control" id="limit-dropdown" onchange="location=this.value;">
                 <option value="<?php echo $obj_general->link($rout, '', '',1);?>" selected="selected">--Select--</option>
                    	<?php 
							$limit_array = getLimit(); 
							foreach($limit_array as $display_limit) {
								if($limit == $display_limit) {	 
								?>
                        			<option value="<?php echo $obj_general->link($rout, 'limit='.$display_limit, '',1);?>" selected="selected"><?php echo $display_limit; ?></option>				
								<?php } else { ?>
                            		<option value="<?php echo $obj_general->link($rout, 'limit='.$display_limit, '',1);?>"><?php echo $display_limit; ?></option>
                        		<?php } ?>
                        <?php } ?>
                 </select>
             </div>
                <label class="col-lg-1 pull-right" style="margin-top:5px;">Show</label>	
           </div>   

          </div>
        
          <!--<div class="panel-body">
            
          </div>-->
          <form name="form_list" id="form_list" method="post">
           <input type="hidden" id="action" name="action" value="" />
          	<div class="table-responsive">
                <table class="table b-t text-small table-hover">
                  <thead>
                    <tr>
                      <th width="20"><input type="checkbox"></th>
                      <th>Date</th>
                      <th>Description</th>
                      <th>Reminder Date</th>
                      <th>Last Reminder Date</th>
                      <th>Status</th>
                      <th>Action</th>
                      <th> </th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
				  $tax_total =$obj_tax_calender->getTotalTax();
				   $pagination_data = '';
				  //printr($tax_total);
				  //printr($limit);
				  if($tax_total){
                        if (isset($_GET['page'])) {
                            $page = (int)$_GET['page'];
                        } else {
                            $page = 1;
                        }
                      //oprion use for limit or and sorting function	
                      $option = array(
                            'sort'  => 'tax_aus_calender_id',
                            'order' => 'ASC',
                            'start' => ($page - 1) *  $limit,
                            'limit' =>  $limit
                      );	
                      
					 // printr($option);
                      $taxes = $obj_tax_calender->getTax($option);
					//printr($taxes);
						$i=1;
					  foreach($taxes as $tax){ 
                        ?>
                       <tr <?php echo ($tax['status']==0) ? 'style=background-color:#FADADF' : '' ; ?> >
                          <td><input type="checkbox" name="post[]" value="<?php echo $tax['tax_aus_calender_id'];?>"></td>
                          <td><?php echo dateFormat(4,$tax['date']);?></td>
                          <td><?php echo $tax['description'];?></td>
                          <td><?php echo dateFormat(4,$tax['remainder_date']);?></td>
                          <td><?php echo dateFormat(4,$tax['last_remainder_date']);?></td>
                          <td>
                          <div data-toggle="buttons" class="btn-group">
                                <label class="btn btn-xs btn-success <?php echo ($tax['status']==1) ? 'active' : '';?> "> <input type="radio" 
                                 name="status" value="1" id="<?php echo $tax['tax_aus_calender_id']; ?>"> <i class="fa fa-check text-active"></i>Active</label>                                   
                                <label class="btn btn-xs btn-danger <?php echo ($tax['status']==0) ? 'active' : '';?> "> <input type="radio" 
                                    name="status" value="0" id="<?php echo $tax['tax_aus_calender_id']; ?>"> <i class="fa fa-check text-active"></i>Inactive</label> 
                           </div>
                           </td>
                           <td>
                                <a href="<?php echo $obj_general->link($rout, 'mod=add&tax_aus_calender_id='.encode($tax['tax_aus_calender_id']), '',1); ;?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                          </td>
                          
                           <?php
                           if($_SESSION['ADMIN_LOGIN_SWISS']=='1' && $_SESSION['LOGIN_USER_TYPE']=='1')
                           {
						    ?>
                          
                          <td> <input type="button" name="send_mail<?php echo $i;?>" id="send_mail<?php echo $i;?>" value="Send Mail"  class="btn btn-success" onclick="acceptmail(<?php echo $i;?>,<?php echo $tax['tax_aus_calender_id'];?>)" />
                          </td>
                          
                          <?php } ?>
                          
                        </tr>
                        
                        <input type="hidden" name="admin" id="admin" value="<?php echo ADMIN_EMAIL;?>" />
                        <?php
                   			$i++;  }
                        
                      // pagination
                        $pagination = new Pagination();
                        $pagination->total = $tax_total;
                        $pagination->page = $page;
                        $pagination->limit = $limit;
                        $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                        $pagination->url = $obj_general->link($rout, '&page={page}&limit='.$limit.'', '',1);//HTTP_ADMIN.'index.php?rout='.$rout.'&page={page}';
                        $pagination_data = $pagination->render();
                       /// echo $pagination_data;die;
						
						
						
						//include("cronjob.php");
                  } 
				  else{ 
                      echo "<tr><td colspan='5'>No record found !</td></tr>";
                  } ?>
                  </tbody>
                </table>
              </div>
          </form>
          <footer class="panel-footer">
            <div class="row">
              <div class="col-sm-4 hidden-xs"> </div>
                <?php echo $pagination_data; ?>
             
            </div>
          </footer>
          
        </section>
      </div>
    </div>
  </section>
</section>

<style>
	.inactive{
		background-color:#999;	
	}
</style>  
<script type="application/javascript">
	   
	   $('input[type=radio][name=status]').change(function() {
	
		var tax_id=$(this).attr('id');
		var status_tax = this.value;
		
		var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=updateAustax', '',1);?>");
        $.ajax({			
			url : status_url,
			type :'post',
			data :{tax_id:tax_id,status_tax:status_tax},
			success: function(){
				//alert(responce);return false;
				set_alert_message('Successfully Updated',"alert-success","fa-check");	
			},
			error:function(){
				set_alert_message('Error During Updation',"alert-warning","fa-warning");          
			}			
		});
    });

function acceptmail(id,tax_aus_calender_id)
{	
	alert(tax_aus_calender_id);
	$(".note-error").remove();
	
	var adminEmail = $("#admin").val();
	
	$('#loading').show();	
	var email_status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=sendEmail', '',1);?>");
	$.ajax({
		url : email_status_url,
		method : 'post',
		data : {tax_aus_calender_id : tax_aus_calender_id,adminEmail:adminEmail},
		success: function(response){
			alert(response);
			set_alert_message('Successfully Mail Sent',"alert-success","fa-check");
			$('#loading').hide();
			 window.setTimeout(function(){location.reload()},1000)
			},
			error: function(){
				return false;	
			}
		});
		
	
}	

</script>   
<?php 
}
 else {
	include(DIR_ADMIN.'access_denied.php');
}
?>