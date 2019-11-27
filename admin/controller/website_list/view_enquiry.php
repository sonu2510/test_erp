<?php
include("mode_setting.php");
//[kinjal]:
$bradcums = array();
$bradcums[] = array(
	'text' 	=> 'Dashboard',
	'href' 	=> $obj_general->link('dashboard', '', '',1),
	'icon' 	=> 'fa-home',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> $display_name,
	'href' 	=> $obj_general->link($rout, '', '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> 'Enquiry Detail',
	'href' 	=> '',
	'icon' 	=> 'fa-list',
	'class'	=> 'active',
);

if(!$obj_general->hasPermission('view',$menuId)){
	$display_status = false;
}

$filter_data=array();
$filter_value='';

$class='collapse';
$limit = LISTING_LIMIT;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}
// for delete enquiries
if(isset($_POST['action']) && $_POST['action'] == "delete" && isset($_POST['post']) && !empty($_POST['post']))
{

	if(!$obj_general->hasPermission('delete',$menuId)){
		$display_status = false;
	} else {
		$obj_website_list->updateStatus($_POST['post']);
		$obj_session->data['success'] = UPDATE;
		page_redirect($obj_general->link($rout, '&mod=view_enquiry&domain_name='.$_GET['domain_name'], '',1));
	}
	
}

if(!isset($_GET['filter_edit'])){
	$filter_edit = 0;
}else{
	$filter_edit = $_GET['filter_edit'];
}

if(!isset($_GET['filter_edit']) || $_GET['filter_edit']==0){
	if(isset($obj_session->data['filter_data'])){
		unset($obj_session->data['filter_data']);	
	}
}

if(isset($obj_session->data['filter_data'])){
	$filter_name = $obj_session->data['filter_data']['product_name'];
	$filter_date = $obj_session->data['filter_data']['date_added'];
	$filter_data=array(
		'product_name' => $filter_name,
		'date_added' => $filter_date
	);
		
}

if(isset($_POST['btn_filter']))
{
	$filter_edit = 1;
	//$class='';		
	if(isset($_POST['filter_name'])){
		$filter_name=$_POST['filter_name'];		
	}else{
		$filter_name='';
	}
	
	if(isset($_POST['filter_date'])){
		$filter_date=$_POST['filter_date'];		
	}else{
		$filter_date='';
	}
	
	$filter_data=array(
		'product_name' => $filter_name,
		'date_added' => $filter_date
	);
	
	$obj_session->data['filter_data'] = $filter_data;
}

if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'DESC';	
}
if(isset($_GET['sort'])){
	$sort_name = $_GET['sort'];	
}else{
	$sort_name = 'domain_data_id';
}

if($display_status) {

	 $domain_name = base64_decode($_GET['domain_name']);
	 //$addedByInfo = $obj_invoice->getUser($user_id,$user_type_id);
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
		  			<span><?php echo $display_name;?> Listing : </span>
                    <span style="color:#ff0000"><b>( <?php echo ($domain_name); ?> )</b></span>
                    <span class="text-muted m-l-small pull-right">
                    <?php if($obj_general->hasPermission('edit',$menuId)){ ?>
					        <a class="label bg-danger" onclick="formsubmitsetaction('form_list','delete','post[]','<?php echo DELETE_WARNING;?>')"><i class="fa fa-trash-o"></i> Delete</a>
                             <a class="label bg-success exlcls" href="javascript:void(0);" id="excel_link"><i class="fa fa-print"></i> Excel</a>
                             <a class="label bg-warning"  onClick="sendEmailFunction('post[]')" ><i class="fa fa-envelope"></i> Send Mail</a>
                              <!--<a class="label bg-info pdfcls" href="javascript:void(0);"><i class="fa fa-print"></i> PDF</a>-->
                    <?php } ?>                      
                    
            </span>
                 </header>
          <div class="panel-body">
            <div class="row text-small">
		  </div>   
                    
           <?php // searching part ?>          
           <form class="form-horizontal" method="post" action="<?php echo $obj_general->link($rout, '&mod=view_enquiry&domain_name='.$_GET['domain_name'], '',1); ?>">  <?php //data-validate="parsley" ?>
                
                <section class="panel pos-rlt clearfix">
                  <header class="panel-heading">
                    <ul class="nav nav-pills pull-right">
                      <li> <a href="#" class="panel-toggle text-muted active"><i class="fa fa-caret-down fa-lg text-active"></i><i class="fa fa-caret-up fa-lg text"></i></a> </li>
                    </ul>
                    <i class="fa fa-search"></i> Search
                  </header>
	
                 <div class="panel-body clearfix <?php echo $class; ?>">        
                      <div class="row">
                        <div class="col-lg-5">
                              <div class="form-group">
                                <label class="col-lg-3 control-label">Product Name</label>
                                <div class="col-lg-8">
                                  <input type="text" name="filter_name" value="<?php echo isset($filter_name) ? $filter_name : '' ; ?>" placeholder="Product Name" id="input-name" class="form-control" />
                                </div>
                              </div>
                         </div>
                         <div class="col-lg-5">
                              <div class="form-group">
                                <label class="col-lg-2 control-label">Date</label>
                                <div class="col-lg-6">
                                  <input type="text" name="filter_date" data-date-format="yyyy-mm-dd" value="<?php echo isset($filter_date) ? $filter_date : '' ; ?>" placeholder="Date" id="input-date" class="form-control datepicker" readonly="readonly"/>
                                </div>
                              </div>
                        </div>     
                      </div>                     
                 </div>
            
                  <footer class="panel-footer <?php echo $class; ?>">
                    <div class="row">
                       <div class="col-lg-12">
                        <button type="submit" class="btn btn-primary btn-sm pull-right ml5" name="btn_filter"><i class="fa fa-search"></i> Search</button>
                        <a href="<?php echo $obj_general->link($rout, '&mod=view_enquiry&domain_name='.$_GET['domain_name'], '',1); ?>" class="btn btn-info btn-sm pull-right" ><i class="fa fa-refresh"></i> Refresh</a>
                       </div> 
                    </div>
                  </footer>                                  
              </section>
          </form>         
                    
            <div class="row">
             <div class="col-lg-3 pull-right">	
                 <select class="form-control" id="limit-dropdown" onChange="location=this.value;">
                    	<?php 
							$limit_array = getLimit(); 
							foreach($limit_array as $display_limit) {
								if($limit == $display_limit) {	 
						?>
                        		<option value="<?php echo $obj_general->link($rout, '&mod=view_enquiry&domain_name='.$_GET['domain_name'].'&limit='.$display_limit, '',1);?>" selected="selected"><?php echo $display_limit; ?></option>				
						<?php } else { ?>
                            	<option value="<?php echo $obj_general->link($rout, '&mod=view_enquiry&domain_name='.$_GET['domain_name'].'&limit='.$display_limit, '',1);?>"><?php echo $display_limit; ?></option>
                                
                        <?php } ?>
                        <?php } ?>
                 </select>
             </div>
                <label class="col-lg-1 pull-right" style="margin-top:5px;">Show</label>	
           </div>
           </div>         
                    
            <form name="form_list" id="form_list" method="post">
            
            <input type="hidden" id="excel_data"  name ="excel_data" value='<?php echo $domain_name;?>' />
            
            <input type="hidden" id="action" name="action" value="" />
             
              <?php 
			  
			    $total = $obj_website_list->getTotalproduct($filter_data,$domain_name);
					  
					  $pagination_data = '';
					  if($total){
							if (isset($_GET['page'])) {
								$page = $_GET['page'];
							} else {
								$page = 1;
							}
						  //oprion use for limit or and sorting function	
						  $option = array(
								'sort'  => $sort_name,
								'order' => $sort_order,
								'start' => ($page - 1) * $limit,
								'limit' => $limit
						  );	
							//printr($option);
						 $product_list = $obj_website_list->getProduct($option,$filter_data,$domain_name);
						  $i=1;
					//	 printr($product_list); 
						 //<finish limit)
						 ?>
						
						 <?php
						 
						   $view_btn = 1;
				
						  $html = $obj_website_list->view_webList($product_list,$view_btn);
							echo $html;?>
							
			<?php			//pagination
				  	$pagination = new Pagination();
					$pagination->total = $total;
					$pagination->page = $page;
					$pagination->limit = $limit;
					$pagination->text = 'Showing {start} to {end} of {total} ({pages} Pages)';
					$pagination->url = $obj_general->link($rout, '&mod=view_enquiry&domain_name='.$_GET['domain_name'].'&limit='.$limit.'&page={page}', '',1);
					$pagination_data = $pagination->render();
              } else{ 
				  echo "<tr><td colspan='5'>No record found !</td></tr>";
			  } 
					?>
         <?php // view part is on model page...  ?>
         
          <div class="form-group">
                    <div class="col-lg-12">
                    	<div id="results_box"></div>
                         <div id="pagination_controls"></div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                    <div class="table-responsive col-lg-offset-5">
            
                        <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a>
                   
           			</div>
                    </div>
                    
          <footer class="panel-footer">
            <div class="row">
              <div class="col-sm-4 hidden-xs"> </div>
              	<?php  echo $pagination_data;?>
            </div>
          </footer>    
           </section>
      </div>
    </div>
  </section>
</section> 
      
      
        
<!-- Modal -->
<div class="modal fade" id="smail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="sform" id="sform" style="margin-bottom:0px;">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <input type="hidden" name="send_data" id="send_data" value="" />
              
                <h4 class="modal-title" id="myModalLabel">Send Email</h4>
              </div>
              <div class="modal-body">
                   <div class="form-group">
                        <label class="col-lg-3 control-label">Email</label>
                        <div class="col-lg-8">
                             <input type="text" name="smail" id="mail_txt" placeholder="Email" value="" class="form-control validate[required,custom[email]]">
                        </div>
                     </div>               </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="button" name="btn_sendemail" class="btn btn-primary btn-sm" onclick="btn_sendemail_to()">Send</button>
              </div>
   		</form>   
    </div>
  </div>
</div>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script type="application/javascript">
	
	/*$(".sendmailcls").click(function(){			
			$(".note-error").remove();
			$("#smail").modal('show');			
			return false;
	});*/
       function sendEmailFunction(elemName)
	   {
		   
		   
		 //console.log(elemName);
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
				
				
				var formData = $("#form_list").serialize();	
				$("#send_data").val(formData);
				$("#smail").modal('show');
				
				
			}
			else
			{
				$(".modal-title").html("WARNING");
				$("#setmsg").html('Please select atlease one record');
				$("#popbtnok").hide();
				$("#myModal").modal("show");
			}
		   		   
	   }
		
		function btn_sendemail_to()
		{
			//alert("kji");
			var email_id = $("#mail_txt").val();
			var send_data = $("#send_data").val();
			//alert(send_data);
			if(email_id != '')	
			{
				
				var add_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=send_email_enq', '',1);?> ");
				 $.ajax({
				url: add_url, // the url of the php file that will generate the excel file
				data : {send_data : send_data, email_id:email_id},
				method : 'post',
				success: function(response){
						
					$("#myModal").modal("hide");
					set_alert_message('Successfully send your mail',"alert-success","fa fa-check");
					
				}
				
			});
			}
		}
	
	$(document).ready(function() {
			
			//$("#").datepicker({
			$("#input-date").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
	
	});
	
	// mansi (for generating excel sheet button)
	$("#excel_link").click(function(){
	var add_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=excel_data', '',1);?> ");
	var post_arr = $('#excel_data').val();
	//alert(post_arr);
	 $.ajax({
        url: add_url, // the url of the php file that will generate the excel file
        data : {post_arr : post_arr},
		method : 'post',
        success: function(response){
		//alert(response);
			excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(response);
			//alert(excelData);
			 $('<a></a>').attr({
							'id':'downloadFile',
							'download': post_arr+'.xls',
							'href': excelData,
							'target': '_blank'
					}).appendTo('body');
					$('#downloadFile').ready(function() {
						$('#downloadFile').get(0).click();
					});
        }
		
    });
});	

</script>
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>