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
    if(isset($_POST['btn_pro'])){
		$post = post($_POST);

		$data = $obj_invoice->viewbusinessReport($post);
		
//	printr($data);
			
}

?> 
<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-list"></i>Business Report</h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>   
        
      <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading bg-white"> 
       
          	<span>Business Report</span>
          	<span class="text-muted m-l-small pull-right">
          			
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
                <div class="col-lg-9 col-lg-offset-3">
               	<button type="submit" name="btn_pro" id="btn_pro" class="btn btn-primary">Proceed</button>	
                </div>
              </div>
            </form>
             <div class="panel-body">
                <div class="form-group">
              	<div class="col-lg-10 col-lg-offset-3 tab_data">
                	<div class="panel-body">
                    	<div class="table-responsive">
                    	      <input type="hidden" id="data_detail"  name ="data_detail" value='<?php echo json_encode($_POST);?>' />
                    	       <?php echo $data;?>
                        </div>
                        
                    </div> 
                    
                </div>
              
              </div> 
             </div>        
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
<style>
	.inactive{
		//background-color:#999;	
	}
</style>
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script>
  
 
		
	
 
  jQuery(document).ready(function(){
	   jQuery("#frm_add").validationEngine();
	   
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
function get_report(){
var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=get_report', '',1);?>");
	var post_arr = $('#data_detail').val();
	var n=1;
	 $.ajax({
        url: url, // the url of the php file that will generate the excel file
       	data : {post_arr : post_arr,n:n},
		method : 'post',
        success: function(response){
			excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(response);
			 $('<a></a>').attr({
							'id':'downloadFile',
							'download': 'Business Report.xls',
							'href': excelData,
							'target': '_blank'
					}).appendTo('body');
					$('#downloadFile').ready(function() {
						$('#downloadFile').get(0).click();
					});
        }
		
    });
}
</script>
          
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>