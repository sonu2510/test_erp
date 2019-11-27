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



/*if($_SESSION['ADMIN_LOGIN_SWISS'] == '1' && $_SESSION['LOGIN_USER_TYPE']== '1')
{
    $conversion = $obj_pro_invoice->currencyConverter("USD","MXN","1234.6770");
    printr('1234.6770  USD = ' . $conversion . ' MXN');
}*/
?>

<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-list"></i> Commission Report</h4>
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
                  <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span>Date From</label>
                            <div class="col-lg-4"> 
                               <div class="col-lg-8">
                                    <input type="text" name="f_date"  value="" placeholder="From Date" class="form-control validate[required]" data-date-format="yyyy-mm-dd" readonly="readonly" id="f_date"/>
                                </div>
                            </div>
                </div> 
               <div class="form-group">           
                   <label class="col-lg-3 control-label"><span class="required">*</span>Date To</label>
                    	<div class="col-lg-4"> 
                            <div class="col-lg-8"> 
                                   <input type="text" name="t_date"  value="" placeholder="To Date" class="form-control validate[required]" data-date-format="yyyy-mm-dd" readonly="readonly" id="t_date" if(isset($_GET['status']) && $_GET['status']==1){ //echo 'disabled';}  />
                             </div>
                        </div>
               </div>
               
              
             <?php if($_SESSION['ADMIN_LOGIN_SWISS'] == '1' && $_SESSION['LOGIN_USER_TYPE']== '1')
    				{?>
    						<div class="form-group">
    							<label class="col-lg-3 control-label"><span class="required">*</span>IB User</label>
    							<div class="col-lg-4">
    							      <div class="col-lg-8"> 
            							<?php $userlist = $obj_pro_invoice->getIBList();?>
            								<select class="form-control validate[required]" name="user_name"  id="user_name"  >
            								 <?php if($_SESSION['ADMIN_LOGIN_SWISS'] != '1' && $_SESSION['LOGIN_USER_TYPE']!= '1'){ echo "disabled=disabled" ;}?> >
            									<option value="">Select User</option>
            									<?php foreach($userlist as $user) { ?>
            												<option value="<?php echo $user['international_branch_id']; ?>"><?php echo $user['user_name']; ?></option>
            									<?php } ?>                                       
            								</select>
            						</div>
    							</div>
    						  </div>
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
    				<?php }else {  ?>
				
        				         <input type="hidden" name="user_name" value="<?php echo $_SESSION['ADMIN_LOGIN_SWISS'];?>"  />
        				         <div class="form-group">
                                    <label class="col-lg-3 control-label">User</label>
                                    <div class="col-lg-4">
                                        <div class="col-lg-8">
                                        <?php $userlist = $obj_pro_invoice->getUserList($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);?>
                        					<select class="form-control" name="emp_name" id="emp_name">
                                                <option value="">Please Select User</option>
                                                <?php foreach($userlist as $user) { 
                                                        $u_name = $obj_pro_invoice->getUser($user['user_id'],$user['user_type_id']);?>
                                                        <option value="<?php echo $user['user_type_id']."=".$user['user_id']; ?>"><?php echo $user['user_name'].' => '.$u_name['name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                		     <?php }?>
		     
		        
             <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                    <button type="button" name="btn_save" id="btn_save" onclick="getreport(1)" class="btn btn-primary">Proceed </button>
                </div>
              </div>
              
              <div class="form-group">
              	<div class="col-lg-12 col-lg-offset tab_data">
                	<div class="panel-body">
                    	<div class="table-responsive">
                        
                        </div>
                        
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
        
        $("#f_date").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
	 $("#t_date").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
        
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
});


	
    function getreport(num)
	{
	    var f_date = $("#f_date").val();
		var t_date = $("#t_date").val();
		
		if($("#form").validationEngine('validate')){ 
			var get_price_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getreport', '',1);?>");
			var formData = $("#form").serialize();
			$.ajax({
					url : get_price_url,
					method : 'post',
					data : {formData : formData,num:num},
					success: function(response){
					    if(num == 1)
					        $(".tab_data").html(response);
					    else
					    {
					        excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(response);	
								 $('<a></a>').attr({
											'id':'downloadFile',
											'download': 'Commission Report.xls',
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
		getreport(n);		
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
				$(".ib_emp").show();
			}
		});
	});
/*	$('#Diff').on('change', function () {
		var value = $(this).val();
		if(value =='2')
		    $(".ib_emp").show();
		else
		   $(".ib_emp").hide(); 
	});*/
	
</script> 
<!-- Close : validation script -->
<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>