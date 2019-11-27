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
	
	if(isset($_POST['filter_operator_id'])){
		$filter_operator_id=$_POST['filter_operator_id'];
	}else{
		$filter_operator_id='';
	}
	if(isset($_POST['filter_machine_id'])){
		$filter_machine_id=$_POST['filter_machine_id'];
	}else{
		$filter_machine_id='';
	}
		
	$filter_data=array(
		'job_name' => $filter_job_name,
		'operator_id' => $filter_operator_id,
		'machine_id'=>$filter_machine_id
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
if(isset($_GET['slitting_date']))
{
		$job_date = decode($_GET['slitting_date']);
		
}


	
if($display_status) {

//active inactive delete
 if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post']))
{
	if(!$obj_general->hasPermission('delete',$menuId)){
		$display_status = false;
	} else {
		//printr($_POST['post']);die;
		$obj_slitting->updateStatus(2,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
}
elseif(isset($_POST['btn_roll_code']))
{
		//printr($_POST);die;
		$obj_slitting->addroll_code($_POST);
		
}	
	$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
	$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
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
          			
                  <?php if($obj_general->hasPermission('edit',$menuId)){ ?>	

                 <a class="label bg-danger" onclick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>
                <?php }?>                     
                    
            </span>
           
          </header>
          
          <div class="panel-body">
              <form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo $obj_general->link($rout, '', '',1); ?>">
                <section class="panel pos-rlt clearfix">
                  <header class="panel-heading">
                    <ul class="nav nav-pills pull-right">
                      <li> <a href="#" class="panel-toggle text-muted active"><i class="fa fa-caret-down fa-lg text-active"></i><i class="fa fa-caret-up fa-lg text"></i></a> </li>
                    </ul>
                <a href="#" class="panel-toggle text-muted active"> <i class="fa fa-search"></i> Search</a>
                  </header>
              
              
              
                 <div class="panel-body clearfix <?php echo $class; ?>">        
                       
                      <div class="row">
                               <div class="col-lg-3">
                                      <div class="form-group">
                                        <label class="col-lg-5 control-label">Job Name</label>
                                           <div class="col-lg-7">
                                              <input type="text" name="filter_job_name" value="" placeholder="Job Name" id="input-name" class="form-control" />
                                            </div>
                                      </div>                             
                              </div>
                            <div class="col-lg-3">
                            	   <div class="form-group">
                                	 <label class="col-lg-5  control-label">Operator Name</label>
                              			  <div class="col-lg-7">
											<?php $operators = $obj_slitting->getOperator();?>
                                            <select name="filter_operator_id" id="filter_operator_id" class="form-control ">
                                                <option value="">Select Operator</option>
                                                    <?php
                                                    foreach($operators as $operator){ ?>
                                                        <option value="<?php echo $operator['employee_id'];?>">
                                                        <?php echo $operator['operator_name']; ?></option>
                                                        <?php } ?> 
                                             </select>
                                  </div>
                               </div>
                        	</div>	
                            
                             <div class="col-lg-3">
                            	   <div class="form-group">
                                	 <label class="col-lg-5  control-label">Machine Name</label>
                              			  <div class="col-lg-7">
											<?php $machines = $obj_slitting->getMachine(); ?>
                                            <select name="filter_machine_id" id="filter_machine_id" class="form-control ">
                                                <option value="">Select Machine Name</option>
                                                    <?php
                                                    foreach($machines as $mcn){ ?>
                                                        <option value="<?php echo $mcn['machine_id'];?>">
                                                        <?php echo $mcn['machine_name']; ?></option>
                                                        <?php } ?> 
                                             </select>
                                  </div>
                               </div>
                        	</div>	                        
                      	</div>                     
               		  </div>
            
                  <footer class="panel-footer <?php echo $class; ?>">
                    <div class="row">
                       <div class="col-lg-12">
                        <button type="submit" class="btn btn-primary btn-sm pull-right ml5" name="btn_filter"><i class="fa fa-search"></i> Search</button>
                        <a href="<?php echo $obj_general->link($rout, '', '',1); ?>" class="btn btn-info btn-sm pull-right" ><i class="fa fa-refresh"></i> Refresh</a>
                       </div> 
                    </div>
                  </footer>                                  
              </section>
         	</form>
            
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
                <table id="example" class="table b-t text-small table-hover">
                  <thead>
                    <tr>
                      <th><input type="checkbox"  style="width:10px;"  ></th>
                      <th >Slitting Number</th>
                      <th >Slitting Date</th>            
                      <th >Roll no/ Roll Code  Details <br> Size </th>    
                      <th >Operator Name</th>
                      <th>Machine Name</th>
                       <th></th>
                      <th>Remarks</th> 
                       
                      <th>Action</th>
                      
                 
                    
                    </tr>
                  </thead>
                  <tbody>
                  <?php	
				
				  $total_slitting = $obj_slitting->getTotalSlitting($filter_data,$job_date);
				  $pagination_data = '';
                  if($total_slitting){
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
					
						 $slitting = $obj_slitting->getSlitting($option,$filter_data,$job_date);
				
					  
					  foreach($slitting as $slit){ 
					   if((isset($slit['remarks_slitting'])) && ($slit['remarks_slitting'] =='Other')){
								if($slit['remark']!='Other')
									$remark=$slit['remark'];
								else
									$remark='';
							}else{
								$remark=$slit['remarks_slitting'];
							}
						if($slit['slitting_status']==0){
								$printing_details = $obj_slitting->getPrintingDetails($slit['roll_code_id']);
								$roll_code=$printing_details['roll_code'];
								$roll_size=$printing_details['roll_size'];
								$label='Printing Roll';
							}else if($slit['slitting_status']==1){
								$lamination_details = $obj_slitting->getLamination_details($slit['roll_code_id']);
								$roll_code=$lamination_details['roll_code'];
								$roll_size=$lamination_details['roll_size'];
								$label='Lamination Roll';
							}else{
								$roll_details = $obj_slitting->getRoll_details($slit['roll_code_id']);
								$roll_code= $roll_details['roll_no'];
								$roll_size= $roll_details['inward_size'];
								$label='Inward Roll';
							}
					  
					//  printr($slit);
                        ?>
                        <tr> 
                          <td><input type="checkbox" style="width:10px;"  name="post[]" value="<?php echo $slit['slitting_id'];?>"></td>
                          <td> <a href="<?php echo $obj_general->link($rout, 'mod=view&slitting_id='.encode($slit['slitting_id']),'',1); ?>" >SLITTING NO-<?php echo $slit['slitting_no'];  ?></a></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&slitting_id='.encode($slit['slitting_id']),'',1); ?>" ><?php echo dateFormat(4,$slit['slitting_date']);  ?></a></td>
                          <td> 
						  
						  
						  
						  <a href="<?php echo $obj_general->link($rout, 'mod=view&slitting_id='.encode($slit['slitting_id']),'',1); ?>" ><b> <?php echo $label;?></b> <br><span style="color:#f92c09" ><?php echo $roll_code;  ?></span></a>
						  
						</td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&slitting_id='.encode($slit['slitting_id']),'',1); ?>" ><?php echo $slit['operator_name'];  ?></a></td>
                           <td><a href="<?php echo $obj_general->link($rout, 'mod=view&slitting_id='.encode($slit['slitting_id']),'',1); ?>" ><?php echo $slit['machine_name'];  ?></a></td>
                           <td> <a href="<?php echo $obj_general->link('pouching_process', 'mod=add&slitting_id='.encode($slit['slitting_id']),'',1); ?>"   class="btn btn-info btn-xs">Go For Pouching</a>
                                 </a></td>
                           <td><a href="<?php echo $obj_general->link($rout, 'mod=view&slitting_id='.encode($slit['slitting_id']),'',1); ?>" ><?php echo $slit['remark'];  ?></a></td>
                        
                      
                        	
                          		<td>
                                 <?php if($obj_general->hasPermission('edit',$menuId)){ ?>
                           			<a href="<?php echo $obj_general->link($rout, 'mod=add&slitting_id='.encode($slit['slitting_id']),'',1); ?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                           		
                           		
                           		<?php }?>
                           		
                           		
                       
                                </td>
                                
                          </tr>
                        
                        <?php 
						
                      }
                        
                        //pagination
                        $pagination = new Pagination();
                        $pagination->total = $total_slitting;
                        $pagination->page = $page;
                        $pagination->limit = LISTING_LIMIT;
                        $pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
                        $pagination->url = $obj_general->link($rout, '&page={page}&limit='.$limit.'', '',1);
                        $pagination_data = $pagination->render();
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
				orientation: 'portrait',
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

	//$('input[type=radio][name=status]').change(function() {
//	
//		var roll_id=$(this).attr('id');
//		var status_value = this.value;
//		
//		var status_url = getUrl("<?php //echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=updateRollStatus', '',1);?>");
//        $.ajax({			
//			url : status_url,
//			type :'post',
//			data :{roll_id:roll_id,status_value:status_value},
//			success: function(){
//				//alert(roll_id);
//				//alert(responce);return false;
//				//set_alert_message('Successfully Updated',"alert-success","fa-check");	
//			},
//			error:function(){
//				//set_alert_message('Error During Updation',"alert-warning","fa-warning");          
//			}			
//		});
//    });
//	

/*
function rollcode(slitting_id,slitting_no)
{
	
	$(".u_title").html("Add Roll Code For  "+slitting_no+" " );	
	$("#slitting_no").val(slitting_no);
	$("#slitting_id").val(slitting_id);	
	$("#roll_code_div").modal('show');	
}


*/
</script>           
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>