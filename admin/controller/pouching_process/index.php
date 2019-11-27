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
if(isset($_GET['pouching_date']))
{
		$job_date = decode($_GET['pouching_date']);
		
}


	
if($display_status) {

//active inactive delete
 if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post']))
{
	if(!$obj_general->hasPermission('delete',$menuId)){
		$display_status = false;
	} else {
		//printr($_POST['post']);die;
		$obj_pouching->updateStatus(2,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
}
elseif(isset($_POST['btn_roll_code']))
{
		//printr($_POST);die;
		$obj_pouching->addroll_code($_POST);
		
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
          			<a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>"><i class="fa fa-plus"></i> New Pouching </a>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
											<?php $operators = $obj_pouching->getOperator();?>
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
											<?php $machines = $obj_pouching->getMachine(); ?>
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
                      <th >pouching Number</th>
                      <th >pouching Date</th> 
                      <th >Job No</th>
                      <th >Operator Name</th>
                      <th>Machine Name</th>                 
                      <th>Remarks</th> 
                        <?php if($obj_session->data['LOGIN_USER_TYPE'] == 1 && $obj_session->data['ADMIN_LOGIN_SWISS'] == 1) 
								{?>	
                      <th>Action</th>
                      <?}else{?>
                      <th></th>
                      <?php }?>
                    </tr>
                  </thead>
                  <tbody>
                  <?php	
				
				  $total_pouching = $obj_pouching->getTotalpouching($filter_data,$job_date);
				  $pagination_data = '';
                  if($total_pouching){
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
					
						   $pouching = $obj_pouching->getpouching($option,$filter_data,$job_date);
				
					  
					  foreach($pouching as $pouch){ 
					 	 if((isset($pouch['remark_pouching'])) && ($pouch['remark_pouching'] =='Other')){
								if($pouch['remark']!='Other')
									$remark=$pouch['remark'];
								else
									$remark='';
							}else{
								$remark=$pouch['remark_pouching'];
							}
					  
					 // printr($pouch);
                        ?>
                        <tr>
                          <td><input type="checkbox" name="post[]" value="<?php echo $pouch['pouching_id'];?>"></td>
                          <td> <a href="<?php echo $obj_general->link($rout, 'mod=view&pouching_id='.encode($pouch['pouching_id']),'',1); ?>" ><?php echo $pouch['pouching_no'];  ?></a></td>
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&pouching_id='.encode($pouch['pouching_id']),'',1); ?>" ><?php echo dateFormat(4,$pouch['pouching_date']);  ?></a></td>
                        
                          <td><a href="<?php echo $obj_general->link($rout, 'mod=view&pouching_id='.encode($pouch['pouching_id']),'',1); ?>" ><?php echo $pouch['job_no'];  ?></a></td>
						  <td><a href="<?php echo $obj_general->link($rout, 'mod=view&pouching_id='.encode($pouch['pouching_id']),'',1); ?>" ><?php echo $pouch['operator_name'];  ?></a></td>
                           <td><a href="<?php echo $obj_general->link($rout, 'mod=view&pouching_id='.encode($pouch['pouching_id']),'',1); ?>" ><?php echo $pouch['machine_name'];  ?></a></td>
                           <td><a href="<?php echo $obj_general->link($rout, 'mod=view&pouching_id='.encode($pouch['pouching_id']),'',1); ?>" ><?php echo $remark;  ?></a></td>
                           
                      
                        	
                          		<td>
                                 <?php if($obj_general->hasPermission('edit',$menuId)){ ?>
                           			<a href="<?php echo $obj_general->link($rout, 'mod=add&pouching_id='.encode($pouch['pouching_id']),'',1); ?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                           		
                           		
                           		<?php }?>
                           		
                           		
                       
                                </td>
                                
                          </tr>
                        
                        <?php 
						
                      }
                        
                        //pagination
                        $pagination = new Pagination();
                        $pagination->total = $total_pouching;
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
function rollcode(pouching_id,pouching_no)
{
	
	$(".u_title").html("Add Roll Code For  "+pouching_no+" " );	
	$("#pouching_no").val(pouching_no);
	$("#pouching_id").val(pouching_id);	
	$("#roll_code_div").modal('show');	
}


*/
</script>           
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>