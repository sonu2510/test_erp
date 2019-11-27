<?php
include("mode_setting.php");

//Start : bradcums
$bradcums = array();
$bradcums[] = array(
	'text' 	=> 'Dashboard',
	'href' 	=> $obj_general->link('dashboard', '', '',1),
	'icon' 	=> 'fa-home',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> $display_name.' & Detail',
	'href' 	=> '',
	'icon' 	=> 'fa-list',
	'class'	=> 'active',
);
//Close : bradcums

$edit = '';

if($display_status){


?>

<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-list"></i> Sales Invoice Report</h4>
    </div>
    <div class="row">
    	
        <div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>
        
      <div class="col-sm-12">
       <section class="panel">
          <header class="panel-heading bg-white"> 
		  	<span><?php echo $display_name;?> </span>
          	<span class="text-muted m-l-small pull-right">
            </span>
          </header>

          <div class="panel-body"></div>
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
              <?php $n=1;
               if(!isset($_GET['status']) && $_GET['status']!=1)
               {
              ?>
                   <div class="form-group">
                    <label class="col-lg-3 control-label">Month</label>
                        <div class="col-lg-4"> 
                           <div class="col-lg-9">
                              <select class="form-control" name="m_value" id="m_value" >
                                    <option value="1">Monthly Basis</option>
                                    <option value="2">Quarterly Basis</option>
                                    <option value="3">Full Year</option>
                                    <option value="4">Half Year</option>                                                            
                       		 </select>
                            </div>
                        </div>
                   </div>
                   <div class="form-group">
                    <label class="col-lg-3 control-label">Full Invoice Report</label>
                        <div class="col-lg-4"> 
                           <div class="col-lg-9">
                              <div class="checkbox">
                                  <label class="checkbox-custom">
                                    <input type="checkbox" name="full_invoice" value="1">
                                    <i class="fa fa-square-o"></i> (eg: Invoice Deleted , Active , Inactive etc ....)</label>
                                </div>
                            </div>
                        </div>
                   </div> 
              <?php }
              ?>
                   
              <div class="form-group">
                    <label class="col-lg-3 control-label">Date From</label>
                        <div class="col-lg-4"> 
                           <div class="col-lg-8">
                                <input type="text" name="f_date"  value="" placeholder="From Date" class="form-control validate[required]" data-date-format="yyyy-mm-dd" readonly="readonly" id="f_date"/>
                            </div>
                        </div>
                   </div> 
                   <div class="form-group">           
                       <label class="col-lg-3 control-label">Date To</label>
                        	<div class="col-lg-4"> 
                                <div class="col-lg-8"> 
                                       <input type="text" name="t_date"  value="" placeholder="To Date" class="form-control validate[required]" data-date-format="yyyy-mm-dd" readonly="readonly" id="t_date" if(isset($_GET['status']) && $_GET['status']==1){ //echo 'disabled';}  />
                                 </div>
                            </div>
                   </div>
               
              <?php if(isset($_GET['status']) && $_GET['status']==1)
                    { ?>
                        <div class="form-group">           
                               <label class="col-lg-3 control-label">Report Types</label>
                                	<div class="col-lg-4"> 
                                        <div class="col-lg-8"> 
                                               <select name="Diff" class="form-control" id="Diff">
                                                   <option value="0">Sales Tax Report</option>
                                                   <option value="1">New vs Returning Customers Report</option>
                                                   <option value="2">Segment Wise Report</option>
                                               </select>
                                         </div>
                                    </div>
                           </div>
             <?php  }?>
             <?php if(isset($_GET['status']) && $_GET['status']==2)
                    { ?>
                        <div class="form-group">           
                           <label class="col-lg-3 control-label">Report Types</label>
                            	<div class="col-lg-4"> 
                                    <div class="col-lg-8"> 
                                           <select name="Diff" class="form-control" id="Diff">
                                               <option value="0">New vs Returning Customers Report</option>
                                               <option value="1">Industry Wise Report</option>
                                               <option value="2">Top Most Customers</option>
                                               <option value="3">Highest No. of Customers (Sales Person Wise)</option>
                                               <option value="4">Highest No. of Customers (State Wise)</option>
                                           </select>
                                     </div>
                                </div>
                       </div>
                        
                        <?php 
                        if($_SESSION['ADMIN_LOGIN_SWISS'] == '1' && $_SESSION['LOGIN_USER_TYPE']== '1')
    					{?>
    						<div class="form-group">
    							<label class="col-lg-3 control-label"><span class="required">*</span>IB User</label>
    							<div class="col-lg-4">
    							      <div class="col-lg-8"> 
            							<?php $userlist = $obj_salesreport->getIBList();?>
            								<select class="form-control  validate[required]" name="user_name"  id="user_name"  >
            								 <?php if($_SESSION['ADMIN_LOGIN_SWISS'] != '1' && $_SESSION['LOGIN_USER_TYPE']!= '1'){ echo "disabled=disabled" ;}?> >
            									<option value="">Select User</option>
            									<?php foreach($userlist as $user) { 
            											?>
            												<option value="<?php echo "4=".$user['international_branch_id']; ?>"><?php echo $user['user_name']; ?></option>
            											
            									<?php } ?>                                       
            								</select>
            						</div>
    							</div>
    						  </div>
    				<?php } ?>
				
            				<div class="form-group ib_emp" style="display:none;">
            					<label class="col-lg-3 control-label">IB Employee</label>
            					<div class="col-lg-4">
            					   <div class="col-lg-8"> 
                						<select class="form-control" name="emp_name" id="emp_name">
                						
                							<option value="">Select User</option>
                							                                       
                						</select>
                				 </div>
            					</div>
            				  </div>
                            
		     <?php  } 
		     if(!isset($_GET['status']) && $_GET['status']!=1 && (($_SESSION['ADMIN_LOGIN_SWISS'] == '1' && $_SESSION['LOGIN_USER_TYPE']== '1')|| $_SESSION['LOGIN_USER_TYPE']=='4'))
		     {// ?>
		         <div class="form-group">
                    <label class="col-lg-3 control-label">User</label>
                    <div class="col-lg-3">
                    <?php $userlist = $obj_salesreport->getUserList($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);?>
    					<select class="form-control" name="user_name_new" id="user_name_new">
                            <option value="">Please Select User</option>
                            <?php foreach($userlist as $user) { 
                                    $u_name = $obj_salesreport->getUser($user['user_id'],$user['user_type_id']);?>
                                    <option value="<?php echo $user['user_type_id']."=".$user['user_id']; ?>"><?php echo $user['user_name'].' => '.$u_name['name']; ?></option>
                                  
                                    
                            <?php } ?>
                        </select>
                    </div>
                </div>
		     <?php }?>
		     
		        
             <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                    <?php $offset = '1';
                  if(isset($_GET['status']) && $_GET['status']==1)
                     { ?>
                        <button type="button" name="btn_save" id="btn_save" onclick="getreport(<?php echo $n;?>)" class="btn btn-primary">Proceed </button>
                    <?php  }
                    else if(isset($_GET['status']) && $_GET['status']==2)
                     { $offset = '3';?>
                        <button type="button" name="btn_save" id="btn_save" onclick="getreport1(<?php echo $n;?>)" class="btn btn-primary">Proceed </button>
                    <?php  }
                    else
                          {
                      ?>
                         <button type="button" name="btn_save" id="btn_save" onclick="get_sheet(<?php echo $n;?>)" class="btn btn-primary">Submit </button>
                    <?php } 
                    ?>
                	 <!-- <a class="btn btn-default" href="<?php //echo $obj_general->link($rout, '', '',1);?>">Cancel</a> -->
                </div>
              </div>
              
               
					
				
					

              <div class="form-group">
              	<div class="col-lg-10 col-lg-offset-<?php echo $offset;?> tab_data">
                	<div class="panel-body">
                    	<div class="table-responsive">
                        </div>
                        
                    </div> 
                    
                </div>
              
              </div>
              <div class="form-group">
              	<div class="col-lg-4 col-lg-offset-0 ">
                	<div class="panel-body">
                    	<div id="chartContainer" style="height: 370px; width: 100%;"></div>
			             <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
                    </div>
                </div>
                <div class="col-lg-4 col-lg-offset-0 ">
                    <div class="panel-body">
                    	<div id="chartContainer_prev" style="height: 370px; width: 100%;"></div>
                    </div>
                </div>
                <div class="col-lg-4 col-lg-offset-0 ">
                    <div class="panel-body">
                    	<div id="chartContainer_corre" style="height: 370px; width: 100%;"></div>
                    </div>
                </div>
              
              </div>
               
               
              </div>
            </form>
          </div>
        </section>
        
        
      </div>
    </div>
  </section>
</section>
<style type="text/css">
.btn-on.active {
    background: none repeat scroll 0 0 #3fcf7f;
}
.btn-off.active{
	background: none repeat scroll 0 0 #3fcf7f;
	border: 1px solid #767676;
	color: #fff;
}
#ajax_response, #ajax_return{
	border : 1px solid #13c4a5;
	background : #FFFFFF;
	position:relative;
	display:none;
	padding:2px 2px;
	top:auto;
	border-radius: 4px;
}
#holder{
	width : 350px;
}
.list {
	padding:0px 0px;
	margin:0px;
	list-style : none;
}
.list li a{
	text-align : left;
	padding:2px;
	cursor:pointer;
	display:block;
	text-decoration : none;
	color:#000000;
}
.selected{
	background : #13c4a5;
}
.bold{
	font-weight:bold;
	color: #227442;
}
.canvas{
    display:inline;
}
</style>
<!-- Start : validation script <script src="<?php echo HTTP_SERVER;?>js/pie_chart.js"></script>-->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

<style type="text/css">
@media (max-width: 400px) {
  .chunk {
    width: 100% !important;
  }
}
</style>
<script>
  
      
  
	
  jQuery(document).ready(function(){
      
        // binds form submission and fields to the validation engine
        jQuery("#form").validationEngine();
		var status = <?php echo isset($_GET['status']) ? $_GET['status'] : '0'; ?>;
	  	
	  	if(status == 0)
	  	{
    	  	 var nowTemp = new Date();
    		 var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
    		
    	    var checkin = $('#f_date').datepicker({
    			   			onRender: function(date) {
        		return date.valueOf() < now.valueOf() ? '' : '';
        		}
        	}).on('changeDate', function(ev) {
    			if (ev.date.valueOf() <= checkout.date.valueOf()) {
    				var newDate = new Date(ev.date);
    				var m_value = $('#m_value option:selected').val();
    
    					if(m_value == 1)				
    						newDate.setDate(newDate.getDate()+30);
    					 else if(m_value == 2)
    						newDate.setFullYear(newDate.getFullYear(), newDate.getMonth()+3);				
    					 else if(m_value == 3)
    						 newDate.setFullYear(newDate.getFullYear()+1);					
    					 else  if(m_value == 4)
    						newDate.setFullYear(newDate.getFullYear(), newDate.getMonth()+6);
    					 
    					checkout.setValue(newDate);
        		}
        		checkin.hide();
        		$('#t_date')[0].focus();
        	}).data('datepicker');
        	var checkout = $('#t_date').datepicker({
    			
        		onRender: function(date) {
    				if(checkin.date.valueOf() > date.valueOf())
    						return 'disabled';
    					else
    						return '';
    			   // $("#t_date").attr('disabled', 'disabled');	
        		}
        	}).on('changeDate', function(ev) {
        		checkout.hide();
        	}).data('datepicker');
        }
        else
        { //onRender: function(date) {     		}
         	
            $('#f_date').datepicker({}).on('changeDate', function(ev) {
    
               var fromdate=$('#f_date').val();
               
               var date = new Date(fromdate);
            
                var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
            
                var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
            
                 $('#t_date').datepicker('setValue', convert(lastDay));  
                
                $(this).datepicker('hide');
    
            });
            function convert(str) {
    
                var date = new Date(str),
            
                    mnth = ("0" + (date.getMonth()+1)).slice(-2),
            
                    day  = ("0" + date.getDate()).slice(-2);
            
                return [ date.getFullYear(),mnth,day ].join("-");
            
            }
        }
       
});

	function get_sheet(n)
	{
		var f_date = $("#f_date").val();
		var t_date = $("#t_date").val();
		var m_value = $("#m_value option:selected").val();
		var full_inv = $('input[name="full_invoice"]:checked').val();
		var user_name = $('#user_name_new option:selected').val();
		//console.log(full_inv);
		if($("#form").validationEngine('validate')){ 
			var get_price_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=get_sheet', '',1);?>");	
			$.ajax({
						url : get_price_url,
						method : 'post',
						data : {f_date : f_date, t_date:t_date,m_value:m_value, n:n,full_inv:full_inv,user_name:user_name},
						success: function(response){
							if(n == 1)
							{
								$(".tab_data").html(response);
							}
							else
							{
								 excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(response);	
								 $('<a></a>').attr({
											'id':'downloadFile',
											'download': 'Record_Year.xls',
											'href': excelData,
											'target': '_blank'
									}).appendTo('body');
									$('#downloadFile').ready(function() {
										$('#downloadFile').get(0).click();
									});
							}
						},
						error: function(){
							return false;	
						}
					});
		
		}		
	}

	
	function get_report()
	{
		var n=0;
		get_sheet(n);		
	}
	function getreports()
	{
		var n=0;
		getreport(n);		
	}
	function getreport(num)
	{
	    var f_date = $("#f_date").val();
		var t_date = $("#t_date").val();
		var check_value  = $("#Diff option:selected").val();
		
		if($("#form").validationEngine('validate')){ 
			var get_price_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getreport', '',1);?>");	
			$.ajax({
					url : get_price_url,
					method : 'post',
					data : {f_date : f_date, t_date:t_date,check:check_value, num:num},
					success: function(response){
						
						
						var val = $.parseJSON(response);
						
						if(num== 1)
						{
							$(".tab_data").html(val.html);
						}
						else
						{
						    if(check_value == '1')
						        var id ='downloadFileCust';
						    else if(check_value == '0')
						        var id ='downloadFile';
						    else if(check_value == '2')
						        var id ='downloadFileSeg';
						        
							excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(val.html);	
							 
    							 $('<a></a>').attr({
    										'id':id,
    										'download': 'Customer Report.xls',
    										'href': excelData,
    										'target': '_blank'
    								}).appendTo('body');
    								$('#'+id).ready(function() {
    									$('#'+id).get(0).click();
    								});
						}
						
						if(check_value == 0)
						{
							var chart = new CanvasJS.Chart("chartContainer", {
            						animationEnabled: true,
            						backgroundColor : "beige",
            						exportEnabled : true,
            						title: {
                                		text: val.current_month
                                	},
            						data: [{
            							type: "pie",
            							yValueFormatString: "#,##0.00\"%\"",
            							indexLabel: "{label} ({y})",
            							dataPoints: val.dataPoints
            						}]
            					});
            					
            					chart.render();
            					
            					var chart_pre = new CanvasJS.Chart("chartContainer_prev", {
            						animationEnabled: true,
            						backgroundColor : "beige",
            						exportEnabled : true,
            						title: {
                                		text: val.prev_month
                                	},
            						data: [{
            							type: "pie",
            							yValueFormatString: "#,##0.00\"%\"",
            							indexLabel: "{label} ({y})",
            							dataPoints: val.dataPoints_pre
            						}]
            					});
            					
            					chart_pre.render();
            					
            					var chart_corr = new CanvasJS.Chart("chartContainer_corre", {
            						animationEnabled: true,
            						backgroundColor : "beige",
            						exportEnabled : true,
            						title: {
                                		text: val.corr_month
                                	},
            						data: [{
            							type: "pie",
            							yValueFormatString: "#,##0.00\"%\"",
            							indexLabel: "{label} ({y})",
            							dataPoints: val.dataPoints_corr
            						}]
            					});
            					
            					chart_corr.render();
						}
						else
						    $("#chartContainer,#chartContainer_corre,#chartContainer_prev").html('');
					},
					error: function(){
						return false;	
					}
			});
				
            
		
		}
	}
	/*$('*[data-poload]').click(function() {
        console.log('hiiii');
        var e = $(this);
        e.popover({
        	trigger: 'manual',
            html: true,
           
        }).popover('toggle');
    });*/
    
    function getreportpdf()
    {			
		var f_date = $("#f_date").val();
		var t_date = $("#t_date").val();
		var check_value  = $("#Diff option:selected").val();
		
		$(".note-error").remove();
		//var url = '<?php //echo HTTP_SERVER.'pdf/sales_tax_chartpdf.php?mod='.encode('sales_tax_chart').'&token='.rawurlencode($_GET['check_value']).'&f_date='.rawurlencode($_GET['f_date']).'&t_date='.rawurlencode($_GET['f_date']).'&ext='.md5('php');?>';
		var url = '<?php echo HTTP_SERVER.'pdf/sales_tax_chartpdf.php?mod='.encode('sales_tax_chart').'&token=0&ext='.md5('php');?>';
		window.open(url, '_blank');
		return false;
    }
    $('#user_name').on('change', function () {
		var ib = $(this).val();
		var name = $("#user_name option:selected").text();
		//console.log(text);
		var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getEmpList', '',1);?>");
		$.ajax({
			method: "POST",
			url:url,
			data:{ib:ib,name:name},
			success: function(response)
			{  
				$("#emp_name").html(response);				
			}
		});
	});
	$('#Diff').on('change', function () {
		var value = $(this).val();
		if(value =='2')
		    $(".ib_emp").show();
		else
		   $(".ib_emp").hide(); 
	});
	function getreport1(num)
	{
	    var formData = $("#form").serialize();
	    var check_value  = $("#Diff option:selected").val();
	    if(check_value == '2')
	        var emp  = $("#emp_name option:selected").val();
	    else
	        var emp='';
	    if($("#form").validationEngine('validate')){ 
			var get_price_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getreport1', '',1);?>");	
			$.ajax({
					url : get_price_url,
					method : 'post',
					data : {formData:formData,num:num},
					success: function(response){
					    var val = $.parseJSON(response);
					    //console.log(val);
					    if(num== 1)
						{
							$(".tab_data").html(val.html);
						}
						else
						{
						    if(check_value == '0')
						        var id ='newVsRepeated';
						    else if(check_value == '2')
						    {
						        var id ='TopCustomer';
						        if(emp!='')
						            var id ='TopCustomerBySalesPerson';
						    }
						    else if(check_value == '1')
						        var id ='Industry';
						   else if(check_value == '3')
						        var id ='HighestCust-SalesPersonWise';
						   else if(check_value == '4')
						        var id ='HighestCust-StateWise';
						    excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(val.html);	
						 
							 $('<a></a>').attr({
										'id':id,
										'download': id+'.xls',
										'href': excelData,
										'target': '_blank'
								}).appendTo('body');
								$('#'+id).ready(function() {
									$('#'+id).get(0).click();
								});
						}
						 if(check_value == '3' || check_value == '4')
						 {
						    var chart = new CanvasJS.Chart("chartContainer_prev", {
        						animationEnabled: true,
        						backgroundColor : "beige",
        						exportEnabled : true,
        						title: {
                            		text: val.current_month
                            	},
        						data: [{
        							type: "pie",
        							yValueFormatString: "#,##0\"\"",
        							indexLabel: "{label} ({y})",
        							dataPoints: val.dataPoints
        						}]
        					});
        					
        					chart.render();
						 }
						 else
						    $("#chartContainer_prev").html('');
					}
			});
	    }
	}
	function getreports1()
	{
		var n=0;
		getreport1(n);		
	}
</script> 
<!-- Close : validation script -->
<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>