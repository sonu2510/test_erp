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

if(!$obj_general->hasPermission('view',$menuId)){
	$display_status = false;
}

$class = 'collapse';

if($display_status) { 
   if(isset($_POST['btn_report'])){
    	$post = post($_POST);
		$data = $obj_enquiry->viewCustomerLeadsReport($post);
		
      
    }
?>
<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-list"></i> Customer Leads Report</h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>   
        
      <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading bg-white"> 
       
          	<span> Customer Leads Report</span>
          	   <span class="text-muted m-l-small pull-right">
                  <a class="label bg-success" href="javascript:void(0);" id="excel_link"><i class="fa fa-print"></i> Excel</a>
                  </span>
           
          </header>
          
          <div class="panel-body"></div>
           
			 <form class="form-horizontal" method="post" name="frm_add" id="frm_add" enctype="multipart/form-data" action="">
            
             <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Date From</label>
                <div class="col-lg-3">
                	<input type="text" name="f_date"  value="" placeholder="From Date" class="span2 form-control validate[required]" data-date-format="yyyy-mm-dd" readonly="readonly" id="f_date"/>
                    </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Date To</label>
                <div class="col-lg-3">
                  <input type="text" name="t_date"  value="" placeholder="To Date" class="span2 form-control validate[required]" data-date-format="yyyy-mm-dd" readonly="readonly" id="t_date"  />
                </div>
              </div>
             <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>User</label>
                <?php $userlist = $obj_enquiry->getUserListReport(); //printr($userlist);?>
			 <div class="col-lg-3">
					<select class="form-control chosen_data" name="user_name">
						<option value="">Please Select</option>
						<?php foreach ($userlist as $user) { ?>
							<?php if ($splitdata[0] == '2' && $splitdata[1] == $user['employee_id']) { ?>

								<option value="<?php echo "2=" . $user['employee_id']; ?>" selected="selected"><?php echo $user['name'].'('.$user['user_name'].')'; ?></option>
							<?php } else { ?>
								<option value="<?php echo "2=" . $user['employee_id']; ?>"><?php echo $user['name'].' => ('.$user['user_name'].')'; ?></option>
							<?php } ?>
						<?php } ?>                                       
					</select>
				</div>
              </div>
			  
      
           
              <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                	<button type="submit" name="btn_report" id="btn_report" class="btn btn-primary">Proceed</button>	
                </div>
              </div>
              
                  <div class="form-group">
                     <div class="col-lg-10 col-lg-offset-1 tab_data">
                        <div class="panel-body">
                           <input type="hidden" id="post_data"  name ="post_data" value='<?php echo json_encode($_POST);?>' />
                           <div class="table-responsive">
                              <?php echo $data;?>
                           </div>
                        </div>
                     </div>
                  </div>
              
            </form>
                    
          <footer class="panel-footer">
            <div class="row">
              <div class="col-sm-4 hidden-xs"> </div>
             
             
            </div>
          </footer>
        </section>
      </div>
    </div>
  </section>
</section>

<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script src="https://harvesthq.github.io/chosen/chosen.jquery.js" type="text/javascript"></script>
<link rel="stylesheet" href=" https://harvesthq.github.io/chosen/chosen.css" type="text/css"/> 
<script>
jQuery(document).ready(function(){
	  
	   $(".chosen_data").chosen();
	   var nowTemp = new Date();
		//alert(nowTemp);
	    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
		//alert(now);
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
$("#excel_link").click(function(){
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=viewCustomerLeadsReport', '',1);?>");
	var post_arr = $('#post_data').val();
	 $.ajax({
        url: url, // the url of the php file that will generate the excel file
       	data : {post_arr : post_arr},
		method : 'post',
        success: function(response){
         //   alert(response);
			excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(response);
			 $('<a></a>').attr({
							'id':'downloadFile',
							'download': 'CUSTOMER-LEADS-REPORT.xls',
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