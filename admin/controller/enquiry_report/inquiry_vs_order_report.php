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
	'text' 	=> $display_name.'',
	'href' 	=> '',
	'icon' 	=> 'fa-list',
	'class'	=> 'active',
);
$menuId=343;
if(!$obj_general->hasPermission('view',$menuId)){
	$display_status = false;
}

$class = 'collapse';
$excel='';
if($display_status) {
    
    if(isset($_POST['btn_pro']))
    {
        $data_report = $obj_enquiry->getContactVsOrderReport($_POST);
        $excel="<span class='text-muted m-l-small pull-right'><a class='label bg-success' href='javascript:void(0);' id='excel_link'><i class='fa fa-print'></i> Excel</a></span>";
    }
?>
<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-list"></i> Inquiry Registered Vs Order Received Report</h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>   
        
      <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading bg-white"> 
       
          	<span>Inquiry Registered Vs Order Received Report</span>
          	<span class="text-muted m-l-small pull-right">
          			
            </span>
           
          </header>
          
          <div class="panel-body"></div>
			 <form class="form-horizontal" method="post" name="frm_add" id="frm_add" enctype="multipart/form-data" action="<?php //echo $obj_general->link($rout, 'mod=view', '',1);?>">
              
			<?php if($_SESSION['ADMIN_LOGIN_SWISS'] == '1' && $_SESSION['LOGIN_USER_TYPE']== '1')
					{?>
						<div class="form-group">
							<label class="col-lg-3 control-label"><span class="required">*</span>IB User</label>
							<div class="col-lg-3">
							<?php $userlist = $obj_enquiry->getIBList();
							//printr($userlist); 
							?>
								<select class="form-control validate[required]" name="user_name"  id="user_name"  >
								 <?php if($_SESSION['ADMIN_LOGIN_SWISS'] != '1' && $_SESSION['LOGIN_USER_TYPE']!= '1'){ echo "disabled=disabled" ;}?> >
									<option value="">Select User</option>
									<?php foreach($userlist as $user) { 
											?>
												<option value="<?php echo "4=".$user['international_branch_id']; ?>"><?php echo $user['user_name']; ?></option>
											
									<?php	} ?>                                       
								</select>
							</div>
						  </div>
				<?php } 
				else if($_SESSION['LOGIN_USER_TYPE']=='4')
				{?>
					<div class="form-group">
						<input type="hidden" name="user_name" id="user_name" value="<?php echo $_SESSION['LOGIN_USER_TYPE']."=".$_SESSION['ADMIN_LOGIN_SWISS'];?>" />
					</div>
				<?php }
				else
				{   $ib_user_id= $obj_enquiry->getUser($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
				   // printr($ib_user_id);
				    echo '<input type="hidden" name="user_name" id="user_name" value="4='.$ib_user_id['international_branch_id'].'" />';
				}?>
				<?php //if(($_SESSION['ADMIN_LOGIN_SWISS'] == '1' && $_SESSION['LOGIN_USER_TYPE']== '1')|| $_SESSION['LOGIN_USER_TYPE']=='4')
						//{ ?>
							
							<div class="form-group">
							<label class="col-lg-3 control-label">Employee Selection</label>
								<div class="col-lg-3">                
									<div style="float:left;width: 200px;">
										<label style="font-weight: normal;">
											<input type="radio" name="emp[]" id="emp" value="0" checked="checked">
											All Employee
										</label>
									</div>
									<div style="float:left;width: 200px;">
										<label style="font-weight: normal;">
											<input type="radio" name="emp[]" id="emp" value="1">
											Employee
										</label>
									</div> 
								</div>
							</div>
							
							<div class="form-group ib_emp" style="display:none;">
							<label class="col-lg-3 control-label"><span class="required">*</span>IB Employee</label>
							<div class="col-lg-3">
							<?php //$userlist = $obj_address->getEmpList();
							//printr($userlist); 
							?>
								<select class="form-control validate[required]" name="emp_name" id="emp_name">
								
									<option value="">Select User</option>
									                                       
								</select>
							</div>
						  </div>
					<?php//}
					//else
				//{?>
					<!--<div class="form-group">
						<input type="hidden" name="emp_name" id="emp_name" value="<?php //echo $_SESSION['LOGIN_USER_TYPE']."=".$_SESSION['ADMIN_LOGIN_SWISS'];?>" />
					</div>-->
				<?php //}?>
			  
               
			   <div class="form-group">
                <label class="col-lg-3 control-label">Date From</label>
                <div class="col-lg-3">
                	<input type="text" name="f_date"  value="<?php echo isset($_POST)?$_POST['f_date']:''?>" placeholder="From Date" class="span2 form-control" data-date-format="yyyy-mm-dd" readonly="readonly" id="f_date"/>
                    </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Date To</label>
                <div class="col-lg-3">
                  <input type="text" name="t_date"  value="<?php echo isset($_POST)?$_POST['t_date']:''?>" placeholder="To Date" class="span2 form-control" data-date-format="yyyy-mm-dd" readonly="readonly" id="t_date"  />
                </div>
              </div>
           	 <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
               	    <button type="submit" name="btn_pro" id="btn_pro" class="btn btn-primary">Proceed</button>	
                </div>
              </div>
              
              <div class="form-group">
                <div class="col-lg-12 tab_data"> <?php echo $excel;?><br><br><?php echo $data_report;?> </div>
              </div>
            </form>
          <footer class="panel-footer">
            
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
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script>
    $("#excel_link").click(function(){
        console.log($("#excel_div").html());
        var html_data = $("#excel_div").html();
	   excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(html_data);
		 $('<a></a>').attr({
				'id':'downloadFile',
				'download': 'Contacts_report.xls',
				'href': excelData,
				'target': '_blank'
		}).appendTo('body');
		$('#downloadFile').ready(function() {
			$('#downloadFile').get(0).click();
		});
    });
 
	$('#frm_add input[name="emp[]"]').on('change', function () {
		var sel = $(this).val();
		if(sel=='1')
			$("div .ib_emp").show();
		else
			$("div .ib_emp").hide();
		
        var ib = $('#user_name').val();
		if(sel=='1')
		{
			var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getEmpList_new', '',1);?>");
			$.ajax({
				method: "POST",
				url:url ,
				data:{ib:ib,sel:sel},
				success: function(response)
				{  
				//	console.log(response);
					$("#emp_name").html(response);				
						
				}
				
			});
		}
	});

    
      jQuery(document).ready(function(){
    	   jQuery("#frm_add").validationEngine();
    	   $( "#user_name, #emp_name" ).change();
    	   var nowTemp = new Date();
    	   var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
    	   var checkin = $('#f_date').datepicker({
       			onRender: function(date) {
        		return date.valueOf() < now.valueOf() ? '' : '';
        		}
        	}).on('changeDate', function(ev) {
    			if (ev.date.valueOf() <= checkout.date.valueOf()) {
    				var newDate = new Date(ev.date);
              		newDate.setDate(newDate.getDate());
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
    				
        		}
        	}).on('changeDate', function(ev) {
        		checkout.hide();
        	}).data('datepicker');
        });
        var emp_user = $("#emp_name").val();
        if(emp_user!='')
        	$( "#user_name, #emp_name" ).change();	
</script>
          
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>