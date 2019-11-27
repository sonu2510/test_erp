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

$class = 'collapse';

$filter_data= array();
if(isset($_POST['btn_filter'])){
	
	$class = '';
		
	$filter_edit = 1;
	$class ='';	
	if(isset($_POST['filter_job_name'])){
		$filter_job_name=$_POST['filter_job_name'];		
	}else{
		$filter_job_name='';
	}	
	
/*	if(isset($_POST['filter_operator_id'])){
		$filter_operator_id=$_POST['filter_operator_id'];
	}else{
		$filter_operator_id='';
		'operator_id' => $filter_operator_id
	}*/
		
	$filter_data=array(
		'job_name' => $filter_job_name,
		
	);
	
}

if(isset($_GET['order'])){
	$sort = $_GET['sort'];	
}else{
	$sort = 'job_id';	
}
if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'DESC';	
}
$add_book_id='0';
$add_url='';
if(isset($_GET['address_book_id']))
{
		$add_book_id = decode($_GET['address_book_id']);
		$add_url = '&address_book_id='.$_GET['address_book_id'];
}
$job_date='';
if(isset($_GET['job_date']))
{
		$job_date = decode($_GET['job_date']);
		
}


	
if($display_status) {

//active inactive delete
if(isset($_POST['action']) && ($_POST['action'] == "active" || $_POST['action'] == "inactive") && isset($_POST['post']) && !empty($_POST['post']))
{	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	} else {
		$status = 0;
		if($_POST['action'] == "active"){
			$status = 1;
		}
		$obj_lamination->updateStatus($status,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
}
else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post']))
{
	if(!$obj_general->hasPermission('delete',$menuId)){
		$display_status = false;
	} else {
		$obj_lamination->updateStatus(2,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
}
elseif(isset($_POST['btn_roll_code']))
{
		//printr($_POST);die;
		$obj_lamination->addroll_code($_POST);
		
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
       
          	<span><?php echo $display_name;?> Listing</span>
          	<span class="text-muted m-l-small pull-right">
          			
             
                    <?php if($obj_general->hasPermission('delete',$menuId)){ ?>                    
                        <a class="label bg-danger" onclick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>
                    <?php  } ?>                      
                    
            </span>
           
          </header>
          
          <div class="panel-body">
            
            
            <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link($rout, $add_url, '',1); ?>">
          
           <div class=" pull-left">
           	 	<div class="panel-body text-muted l-h-2x">
               
                 
               
                 <a href="<?php echo $obj_general->link($rout, 'mod=day_wise','',1); ?>" class="btn btn-primary btn-sm pull-right ml5" name="daywise" style="background-color:#CBC6AB"> Day Wise</a>
                 <a href="<?php echo $obj_general->link($rout, '', '',1); ?>" class="btn btn-primary btn-sm pull-right ml5" name="alljobs" style="background-color:#81C267"> All Jobs</a>
                
                </div>
           </div> 
         
           </form>
           
          
          </div>
           
          
          
          <form name="form_list" id="form_list" method="post">
           <input type="hidden" id="action" name="action" value="" />
          	<div class="table-responsive">
                <table id="example"  class="table b-t text-small table-hover">
                  <thead>
                    <tr>
                      <th width="20"><input type="checkbox" style="width:10px;"></th>
                      <th>Lamination Number<br>Job Date</th>
                      <th>Job Name</th>  
                      <th>Added Date</th>
                      <th>Lamination  Date</th>
                      <th>Remark</th>
                      <th>Machine name</th>
                     <th>No. of Pass</th>
                      <th>Status<th>
                      <th>Roll Code</th>						
                      <th>Action</th>

					  
                    </tr>
                  </thead>
                  <tbody>
                  <?php	
				
				  $total_job = $obj_lamination->getTotalLamination($filter_data,$job_date);
				  $pagination_data = '';
                  if($total_job){
                        if (isset($_GET['page'])) {
                            $page = (int)$_GET['page'];
                        } else {
                            $page = 1;
                        }
                      //option use for limit or and sorting function	
                      $option = array(
                          'sort'  => $sort,
                          'order' => $sort_order,
                          'start' => ($page - 1) * $limit,
                          'limit' => $limit
                      );
					  
					  
					 //printr($option);
					
						   $jobs = $obj_lamination->getLamination($option,$filter_data,$job_date);
				
					  
					  foreach($jobs as $job){ 
					
					if((isset($job['remark_lamination'])) && ($job['remark_lamination']=='Other')){
								
								if($job['remark']!='Other')
									$remark=$job['remark'];
								else
									$remark='';
							}else{
								
									$remark=$job['remark_lamination'];
								
							}
							
						
						  $layer_details= $obj_lamination->getLayerDetails($job['lamination_id']);
						  
					//	  printr($layer_details);
						  $count =count($layer_details);
						  $job_layer_details_roll = $obj_lamination->getLayerJObMaterialDetails($job['job_id']);	
						  $layer =count($job_layer_details_roll);
						
					
				
                     //   printr($job);
						
						?>
                        <tr <?php echo ($job['status']==0) ? 'style="background-color:#FADADF" ' : '' ; ?>>
                          <td><input  type="checkbox" style="width:10px;" name="post[]" value="<?php echo $job['lamination_id'];?>"></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&lamination_id='.encode($job['lamination_id']),'',1); ?>" ><?php echo  'LAMINATION NO-'. $job['lamination_no'];  ?></a></td>
						  <td><a href="<?php echo $obj_general->link($rout, 'mod=view&lamination_id='.encode($job['lamination_id']),'',1); ?>" ><?php echo   $job['job_name'];  ?><br><b>Job No : <?php echo   $job['job_no'];  ?></b></a></td>
						  <td><a href="<?php echo $obj_general->link($rout, 'mod=view&lamination_id='.encode($job['lamination_id']),'',1); ?>" ><b><span style="color:#f92c09" ><?php echo dateFormat(4,$job['date_added']);  ?></span></b></a></td>
						  <td><a href="<?php echo $obj_general->link($rout, 'mod=view&lamination_id='.encode($job['lamination_id']),'',1); ?>" ><b><span style="color:#f92c09" ><?php echo dateFormat(4,$layer_details[1]['layer_date']);  ?></span></b></a></td>
                    		  <td><a href="<?php echo $obj_general->link($rout, 'mod=view&lamination_id='.encode($job['lamination_id']),'',1); ?>" ><?php echo 	$remark;  ?></a></td>
                    		  <td><a href="<?php echo $obj_general->link($rout, 'mod=view&lamination_id='.encode($job['lamination_id']),'',1); ?>" ><?php echo 	$job['machine_name'];  ?></a></td>
                    		  <td><a href="<?php echo $obj_general->link($rout, 'mod=view&lamination_id='.encode($job['lamination_id']),'',1); ?>" ><?php echo 	$job['pass_no'];  ?></a></td>
							
                        
                         <td>
                              
                                <div data-toggle="buttons" class="btn-group">
                                    <label class="btn btn-xs btn-success <?php echo ($job['status']==1) ? 'active' : '';?> "> <input type="radio" 
                                     name="status" value="1" id="<?php echo $job['lamination_id']; ?>"> <i class="fa fa-check text-active"></i>   Accept</label>                                   
                                    <label class="btn btn-xs btn-danger <?php echo ($job['status']==0) ? 'active' : '';?> "> <input type="radio" 
                                        name="status" value="0" id="<?php echo $job['lamination_id']; ?>"> <i class="fa fa-times"></i>  Reject</label> 
                                </div>
                              
						  </td>
						  
						    <td>
						      
                         
                          
						  </td>
						  
						  <td>	     
								 <?php if(isset($job['roll_code_status'])&& $job['roll_code_status'] == '0' && $job['roll_code']==''){?>
								   <a class="label label-default btn-sm"  onclick="rollcode('<?php echo $job['lamination_id'];?>','<?php echo $job['lamination_no'];  ?>','<?php echo addslashes($job['job_name']);  ?>');" id="remark">Roll Code</a>
								   <?php }{?>
						  
								   <a class="badge bg-success" href="<?php echo $obj_general->link($rout, 'mod=view&job_id='.encode($job['job_id']),'',1); ?>" ><?php echo $job['roll_code'];  ?></a><?php } ?>
								    <?php if(isset($job['roll_code_status']) && $job['roll_code_status'] == '0'  && $job['roll_code']==''){} else if($job['status']=='1') {?>
								  <br> <a href="<?php echo $obj_general->link('slitting_process', 'mod=add&lamination_id='.encode($job['lamination_id']),'',1); ?>"   class="btn btn-info btn-xs">Go For Slitting</a><?php }else {} ?>
                           </td>
                          <td>	
                                <a href="<?php echo $obj_general->link($rout, 'mod=add&lamination_id='.encode($job['lamination_id']),'',1); ?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
							</td>
						   
                        </tr>
                        
                        <?php
						
                      }
                     
                     } else{ 
                      echo "<tr><td colspan='5'>No record found !</td></tr>";
                  } 
				  ?>
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

 <div class="modal fade" id="roll_code_div" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="tform" id="tform" style="margin-bottom:0px;">
              <div class="modal-header">
               
                <h4 class="modal-title u_title" id="myModalLabel"></h4>
              </div>
              <div class="modal-body">
                 <div class="form-group">
                          <div class="form-group option">
					<label class="col-lg-3 control-label">Roll Code </label>
                        <div class="col-lg-8">
                             <input type="text" name="roll_code" id="roll_code" value="" class="form-control">
                        </div>
						</div>
						   <div class="form-group option">
						<label class="col-lg-3 control-label">Roll Size </label>
                        <div class="col-lg-6">
                             <input type="text" name="roll_size" id="roll_size" value="" class="form-control">
                        </div>
                          
                           <input type ="hidden" name="lamination_no" id="lamination_no" value="" />
                            <input type ="hidden" name="lamination_id" id="lamination_id" value="" />
                           
                        </div>
                     </div>
                    	
                </div>  
              <div class="modal-footer">
                   		   <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" >Close</button>
                   		   <button type="submit" name ="btn_roll_code" id="btn_roll_code" class="btn btn-primary">Add</button>
                  
              </div>
   		</form>   
    </div>
  </div>
</div>
<style>
	.inactive{
		//background-color:#999;	
	}
</style>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">
<script type="application/javascript">
$(document).ready(function() {
  $('#example').DataTable( {
    dom: 'Bfrtip',
       lengthMenu: [
            [ 10, 25, 50, -1 ],
            [ '10 rows', '25 rows', '50 rows', 'Show all' ]
        ],
   aoColumnDefs: [{ "bSortable": false, "aTargets":  [ 0, 1, 2, 3,4,5,6 ]  }, 
                { "bSearchable": true, "aTargets": [ 0, 1, 2, 3,4,5,6] }
                ],
    buttons: [
      { 
        extend: 'pdfHtml5',
        orientation: 'landscape',
        pageSize: 'LEGAL',
        footer: 'true',
      
        exportOptions: {
                modifier: {
                    page: ''
                },
            }
        
      }
    ],
    
} );
    
} );
</script>
<script type="application/javascript">

	$('input[type=radio][name=status]').change(function() {
	
		var roll_id=$(this).attr('id');
		var status_value = this.value;
		
		var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=updateRollStatus', '',1);?>");
        $.ajax({			
			url : status_url,
			type :'post',
			data :{roll_id:roll_id,status_value:status_value},
			success: function(){
				//alert(roll_id);
				//alert(responce);return false;
				//set_alert_message('Successfully Updated',"alert-success","fa-check");	
			},
			error:function(){
				//set_alert_message('Error During Updation',"alert-warning","fa-warning");          
			}			
		});
    });
function rollcode(lamination_id,lamination_no,job_name)
{
	
	$(".u_title").html("Add Roll Code For  <b>"+job_name+"</b> " );	
	$("#lamination_no").val(lamination_no);
	$("#lamination_id").val(lamination_id);	
	$("#roll_code_div").modal('show');	
}


</script>           
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>