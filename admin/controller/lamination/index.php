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
                         <!--   <div class="col-lg-3">
                            	   <div class="form-group">
                                	 <label class="col-lg-5  control-label">Operator Name</label>
                              			  <div class="col-lg-7">
											<?php $operators = $obj_lamination->getOperator();?>
                                            <select name="filter_operator_id" id="filter_operator_id" class="form-control ">
                                                <option value="">Select Operator</option>
                                                    <?php
                                                    foreach($operators as $operator){ ?>
                                                  
                                                        <option value="<?php echo $operator['employee_id']; ?>">
                                                        <?php echo $operator['operator_name']; ?></option>
                                                        <?php } ?> 
                                             </select>
                                  </div>
                               </div>
                        	</div>-->	               
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
           
          
          
          <form name="form_list" id="form_list" method="post">
           <input type="hidden" id="action" name="action" value="" />
          	<div class="table-responsive">
                <table class="table b-t text-small table-hover">
                  <thead>
                    <tr>
                      <th width="20"><input type="checkbox"></th>
                      <th >Lamination Number</th>
                      <th >Job Number<br>Job Date</th>
                              
                      <th >Job Name</th>  
                      <th >Remark</th> 
                 
			
					  <th><th>
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
				
                  //      printr($job['status']);
						
						?>
                        <tr <?php echo ($job['status']==0) ? 'style="background-color:#FADADF" ' : '' ; ?>>
                          <td><input type="checkbox" name="post[]" value="<?php echo $job['lamination_id'];?>"></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&lamination_id='.encode($job['lamination_id']),'',1); ?>" ><?php echo  'LAMINATION NO-'. $job['lamination_no'];  ?> </a></td>
						  <td><a href="<?php echo $obj_general->link($rout, 'mod=view&lamination_id='.encode($job['lamination_id']),'',1); ?>" ><?php echo   $job['job_no'];  ?><br><span style="color:#f92c09" ><?php echo dateFormat(4,$job['lamination_date']);  ?> </span></a></td>
                         
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&lamination_id='.encode($job['lamination_id']),'',1); ?>" ><?php echo $job['job_name'];  ?></a></td>                          
						  
						  <td><a href="<?php echo $obj_general->link($rout, 'mod=view&lamination_id='.encode($job['lamination_id']),'',1); ?>" ><?php echo 	$remark;  ?></a></td>
							
                          
                         <td>
                              
                                <div data-toggle="buttons" class="btn-group">
                                    <label class="btn btn-xs btn-success <?php echo ($job['status']==1) ? 'active' : '';?> "> <input type="radio" 
                                     name="status" value="1" id="<?php echo $job['lamination_id']; ?>"> <i class="fa fa-check text-active"></i>   Accept</label>                                   
                                    <label class="btn btn-xs btn-danger <?php echo ($job['status']==0) ? 'active' : '';?> "> <input type="radio" 
                                        name="status" value="0" id="<?php echo $job['lamination_id']; ?>"> <i class="fa fa-times"></i>  Reject</label> 
                                </div>
                              
						  </td>
						  
						    <td><?php 
						  $layer_details= $obj_lamination->getLayerDetails($job['lamination_id']);
						  $count =count($layer_details);
						  $job_layer_details_roll = $obj_lamination->getLayerJObMaterialDetails($job['job_id']);	
						    $layer =count($job_layer_details_roll);
							//printr($count.'=='.$layer);
							//$layer=4;
						?>
						      
                                <div data-toggle="buttons" class="btn-group">
									
							<?php if(isset($job['pass_no']))  { 
							
										if($job['pass_no']==1){ ?>										
											<span  class="label bg-success btn-sm" style="font-size: 90%" ><i id="layer2_1"  class="fa fa-check"></i> First Pass</span>
										<?php }elseif($job['pass_no']==2){ ?>										
											<span  class="label bg-success btn-sm" style="font-size: 90%" ><i id="layer2_1"  class="fa fa-check"></i> First Pass</span>
											<span  class="label bg-success btn-sm" style="font-size: 90%" ><i id="layer2_1"  class="fa fa-check"></i> Second Pass</span>																					
										<?php }elseif($job['pass_no']==3){ ?>
											<span  class="label bg-success btn-sm" style="font-size: 90%" ><i id="layer2_1"  class="fa fa-check"></i> First Pass</span>
											<span  class="label bg-success btn-sm" style="font-size: 90%" ><i id="layer2_1"  class="fa fa-check"></i> Second Pass</span>		
											<span  class="label bg-success btn-sm" style="font-size: 90%" ><i id="layer2_1"  class="fa fa-check"></i> Third Pass</span>		
									<?php }elseif($job['pass_no']==4){ ?>
											<span  class="label bg-success btn-sm" style="font-size: 90%" ><i id="layer2_1"  class="fa fa-check"></i> First Pass</span>
											<span  class="label bg-success btn-sm" style="font-size: 90%" ><i id="layer2_1"  class="fa fa-check"></i> Second Pass</span>		
											<span  class="label bg-success btn-sm" style="font-size: 90%" ><i id="layer2_1"  class="fa fa-check"></i> Third Pass</span>	
											<span  class="label bg-success btn-sm" style="font-size: 90%" ><i id="layer2_1"  class="fa fa-check"></i> Fourth Pass</span>	
									<?php }
							}			
							
							?>
							
							<?php /* if($layer==2)
									{?>
										
										 <span  class="label bg-success btn-sm" style="font-size: 90%" ><i id="layer2_1"  class="fa fa-check"></i> First Pass</span>
										   
										   <?php if($count==1){
														$cls2="bg-danger";
														$icls2 = "fa fa-times";
												 } else{
													   $cls2="bg-success";
													   $icls2 = "fa fa-check";
												} ?>
										   <span class="label <?php echo $cls2;?>  btn-sm" style="font-size: 90%" ><i id="layer2_1" class="<?php echo $icls2; ?>"></i> Second Pass</span> 
									<?php }
									if($layer==3){ ?>
								
													 <span class="label bg-success btn-sm" style="font-size: 90%" ><i id="layer3_1" class="fa fa-check"></i> First Pass</span>
													   <?php  if($count==1){
																	$cls3="bg-danger";
																	$icls3 = "fa fa-times";
															 } else{
																   $cls3="bg-success";
																   $icls3 = "fa fa-check";
															} 
															?>
													   <span class="label <?php echo $cls3;?> btn-sm" style="font-size: 90%" ><i id="layer3_2" class="<?php echo $icls3; ?>"></i> Second Pass</span> 
														<?php if($count==1 || $count==2 ){
																	$cls="bg-danger";
																	$icls = "fa fa-times";
															 } else{
																   $cls="bg-success";
																   $icls = "fa fa-check";
															} ?>
									 
													<span class="label <?php echo $cls;?> btn-sm" style="font-size: 90%" ><i id="layer3_3" class="<?php echo $icls;?>"></i> Third Pass</span>
									<?php }
									if($layer==4){?>
										  <span class="label bg-success btn-sm" style="font-size: 90%" ><i id="layer4_1" class="fa fa-check"></i> First Pass</span>
										   <span class="label <?php if($count==1){ echo"bg-danger";}else{echo "bg-success";} ?>  btn-sm" style="font-size: 90%" ><i id="layer4_2" class="<?php if($count==1){echo "fa fa-times";}else{echo "fa fa-check";} ?>"></i> Second Pass</span> 
										   <span class="label <?php if($count==1 || $count==2 ){ echo"bg-danger";}else{echo "bg-success";} ?>  btn-sm" style="font-size: 90%" ><i id="layer4_3" class="<?php if($count==1 || $count==2 ){echo "fa fa-times";}else{echo "fa fa-check";} ?>"></i> Third Pass</span>
										   <span class="label <?php if($count==1 || $count==2 ||$count==3){ echo"bg-danger";}else{echo "bg-success";} ?>  btn-sm" style="font-size: 90%" ><i id="layer24_4" class="<?php if($count==1 || $count==2 ||$count==3){echo "fa fa-times";}else{echo "fa fa-check";} ?>"></i> Fourth Pass</span>
								<?php }  */?>
								</div>
                          
						  </td>
						  
						  <td>	     
								 <?php if(isset($job['roll_code_status'])&& $job['roll_code_status'] == '0' && $job['roll_code']==''){?>
								   <a class="label label-default btn-sm"  onclick="rollcode('<?php echo $job['lamination_id'];?>','<?php echo $job['lamination_no'];  ?>','<?php echo $job['job_name'];  ?>');" id="remark">Roll Code</a>
								   <?php }{?>
						  
								   <a class="badge bg-success" href="<?php echo $obj_general->link($rout, 'mod=view&job_id='.encode($job['job_id']),'',1); ?>" ><?php echo $job['roll_code'];  ?></a><?php } ?>
								    <?php if(isset($job['roll_code_status']) && $job['roll_code_status'] == '0'  && $job['roll_code']==''){} else if($job['status']=='1') {?>
								   <a href="<?php echo $obj_general->link('slitting', 'mod=add&lamination_id='.encode($job['lamination_id']),'',1); ?>"   class="btn btn-info btn-xs">Go For Slitting</a><?php }else {} ?>
                           </td>
                          <td>	
                                <a href="<?php echo $obj_general->link($rout, 'mod=add&lamination_id='.encode($job['lamination_id']),'',1); ?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
							</td>
						   
                        </tr>
                        
                        <?php
						
                      }
                        
                        //pagination
                        $pagination = new Pagination();
                        $pagination->total = $total_job;
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