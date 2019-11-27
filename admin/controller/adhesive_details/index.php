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
	if(isset($_POST['filter_date'])){
		$filter_date=$_POST['filter_date'];		
	}else{
		$filter_date='';
	}	
	if(isset($_POST['filter_shift'])){
		$filter_shift=$_POST['filter_shift'];		
	}else{
		$filter_shift='';
	}	
	if(isset($_POST['filter_date'])){
		$filter_date=$_POST['filter_date'];		
	}else{
		$filter_date='';
	}	
	
	
		
	$filter_data=array(
		'date' => $filter_date,
		'shift' => $filter_shift
		
		
	);  //  printr($filter_data);//die;
	
}

if(isset($_GET['order'])){
	$sort = $_GET['sort'];	
}else{
	$sort = 'a.adhesive_id';	
}
if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'DESC';	
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
		$obj_adhesive->updateStatus($status,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
}
else if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post']))
{
	if(!$obj_general->hasPermission('delete',$menuId)){
		$display_status = false;
	} else {
		$obj_adhesive->updateStatus(2,$_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '', '',1));
	}
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
          				<a class="label bg-info" href="javascript:void(0);" onclick="csvlink('post[]')"> <i class="fa fa-print"></i> CSV Export</a>
                 <a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=add', '',1);?>"><i class="fa fa-plus">&nbsp;&nbsp;&nbsp;</i>New  </a>
                    <?php if($obj_general->hasPermission('edit',$menuId)){ ?>
                        <a class="label bg-success" style="margin-left:4px;" onclick="formsubmitsetaction('form_list','active','post[]','<?php echo ACTIVE_WARNING;?>')"><i class="fa fa-check"></i> Active</a>
                        <a class="label bg-warning" onclick="formsubmitsetaction('form_list','inactive','post[]','<?php echo INACTIVE_WARNING;?>')"><i class="fa fa-times"></i> Inactive</a>
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
                                      
                                        <label class="col-lg-5 control-label">Date</label>
                                           <div class="col-lg-7">
                                             <input type="text" name="filter_date" id="filter_date" data-date-format="yyyy-mm-dd" value="" class="form-control  datepicker">
                                            </div>
                                      </div>                             
                              </div>
                          <div class="col-lg-3">
                                  <div class="form-group">
                                      <label class="col-lg-5  control-label">Shift</label>
                                      <div class="col-lg-7">                                       
                                          <select name="filter_shift" id="filter_shift" class="form-control validate[required]">
                                           
                                                  <option value="Day">Day</option>
                                                  <option value="Night">Night </option>
                                         
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
                      <th>Date</th>
                      <th>Adhesive No</th>                      
                      <th>Shift</th>
                      <th>Operator Name</th>
                      <th>Machine Name</th>                     
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php	
		  $total_adhesive = $obj_adhesive->getTotalAdhesiveDetails($filter_data);
//                  printr($total_adhesive);die;
		  $pagination_data = '';
                  if($total_adhesive){
                        if (isset($_GET['page'])) {
                            $page = (int)$_GET['page'];
                        } else {
                            $page = 1;
                        }
//                      option use for limit or and sorting function	
                      $option = array(
                          'sort'  => $sort,
                          'order' => $sort_order,
                          'start' => ($page - 1) * $limit,
                          'limit' => $limit
                      );
//                 
		   $adhesive_process = $obj_adhesive->getAdhesiveDetails_ALL($option,$filter_data);
		   //     printr($adhesive_process);
			  foreach($adhesive_process as $ad){ 
			
				
			  
                        ?>
                        <tr <?php echo ($ad['status']==0) ? 'style="background-color:#FADADF" ' : '' ; ?>>
                          <td><input type="checkbox" name="post[]" value="<?php echo $ad['adhesive_id'];?>"></td>
                          <td>
							<a href="<?php echo $obj_general->link($rout, 'mod=view&adhesive_id='.encode($ad['adhesive_id']),'',1); ?>" ><?php  echo dateformat(4, $ad['date']);  ?></a>
						  </td>
                          <td>						  
						   <a href="<?php echo $obj_general->link($rout, 'mod=view&adhesive_id='.encode($ad['adhesive_id']),'',1); ?>" ><?php  echo $ad['adhesive_no'];  ?></a>	
						  
						  </td>   
                          <td>
						   <a href="<?php echo $obj_general->link($rout, 'mod=view&adhesive_id='.encode($ad['adhesive_id']),'',1); ?>" >  <?php  echo $ad['shift'];  ?></a>	
					</td>
                          <td>
						   <a href="<?php echo $obj_general->link($rout, 'mod=view&adhesive_id='.encode($ad['adhesive_id']),'',1); ?>" > <?php  echo $ad['user_name'];  ?></a>	
						 </td>
                          <td>
						   <a href="<?php echo $obj_general->link($rout, 'mod=view&adhesive_id='.encode($ad['adhesive_id']),'',1); ?>" > <?php  echo $ad['machine_name'];  ?></a>	
						 </td>
                        <td>
                              
                                <div data-toggle="buttons" class="btn-group">
                                    <label class="btn btn-xs btn-success <?php  echo ($ad['status']==1) ? 'active' : '';?> "> <input type="radio" 
                                     name="status" value="1" id="<?php  echo $ad['adhesive_id']; ?>"> <i class="fa fa-check text-active"></i>Active</label>                                   
                                    <label class="btn btn-xs btn-danger <?php  echo ($ad['status']==0) ? 'active' : '';?> "> <input type="radio" 
                                        name="status" value="0" id="<?php  echo $ad['adhesive_id']; ?>"> <i class="fa fa-check text-active"></i>Inactive</label> 
                                </div>
                              
						  </td>
                          <td>	
                                <a href="<?php echo $obj_general->link($rout, 'mod=add&adhesive_id='.encode($ad['adhesive_id']),'',1); ?>"  name="btn_edit" class="btn btn-info btn-xs">Edit</a>
                                
                           </td>
                        </tr>
                        
                        <?php
						
                      }
                        //pagination
                        $pagination = new Pagination();
                        $pagination->total = $total_adhesive;
                        $pagination->page = $page;
                        $pagination->limit = $limit;
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

<script type="application/javascript">
    
		$('input[type=radio][name=status]').change(function() {
	
		var adhesive_id=$(this).attr('id');
		var status_value = this.value;
		
		var status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=updateRollStatus', '',1);?>");
        $.ajax({			
			url : status_url,
			type :'post',
			data :{adhesive_id:adhesive_id,status_value:status_value},
			success: function(){
				//alert(adhesive_id);
				//alert(responce);return false;
				
				location.reload();
				set_alert_message('Successfully Updated',"alert-success","fa-check");	
				
			},
			error:function(){
				location.reload();
				set_alert_message('Error During Updation',"alert-warning","fa-warning");          
			}			
		});
    });
	
	
	function csvlink(elemName){
	//alert(elemName);
		elem = document.getElementsByName(elemName);
		var flg = false;
		for(i=0;i<elem.length;i++){
			if(elem[i].checked)
			{
				flg = true;
				break;
			}
		}
	if(flg)
	{
		
			
		var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=csvAdhesive', '',1);?>");
			var formData = $("#form_list").serialize();	
		 $.ajax({
			url: url, // the url of the php file that will generate the excel file
			data : {formData : formData},
			method : 'post',
			success: function(response){
				excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(response);
				 $('<a></a>').attr({
								'id':'downloadFile',
								'download': 'Adhesive.xls',
								'href': excelData,
								'target': '_blank'
						}).appendTo('body');
						$('#downloadFile').ready(function() {
							$('#downloadFile').get(0).click();
						});
			}
			
    });
	}
	else
	{
		//alert("Please select atlease one record");
		$(".modal-title").html("WARNING");
		$("#setmsg").html('Please select atlease one record');
		$("#popbtnok").hide();
		$("#myModal").modal("show");
	}
	
}
</script>           
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>