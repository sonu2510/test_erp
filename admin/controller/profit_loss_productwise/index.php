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
      <h4><i class="fa fa-list"></i> Profit Loss List </h4>
    </div>
    <div class="row">
    	
        <div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>
        
      <div class="col-sm-8">
        <section class="panel">
          <header class="panel-heading bg-white"> Profit Loss List </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Date From</label>
                    <div class="col-lg-4"> 
                       <div class="col-lg-8">
                             <input type="text" name="f_date" readonly data-date-format="yyyy-mm-dd" value="" placeholder="From Date" id="f_date" class="input-sm form-control datepicker validate[required]" />
                        </div>
                    </div>
               </div> 
               <div class="form-group">           
                   <label class="col-lg-3 control-label">Date To</label>
                    	<div class="col-lg-4"> 
                            <div class="col-lg-8"> 
                                    <input type="text" name="t_date" readonly data-date-format="yyyy-mm-dd" value="" placeholder="To Date" id="t_date" class="input-sm form-control datepicker validate[required]" />
                             </div>
                        </div>
               </div>
              
             <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                    <button type="button" name="btn_save" id="btn_save" onclick="get_price(<?php echo $n=1;?>)" class="btn btn-primary">Submit </button>	
                 <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a>
                </div>
              </div>
              
              
              <div class="form-group">
              	<div class="col-lg-9 col-lg-offset-3 tab_data">
                	<div class="panel-body">
                    	<div class="table-responsive">
                        
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
</style>
<!-- Start : validation script -->
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
		//$("#f_date").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
		//$("#t_date").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});	
		
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
				//alert(date);
				if(checkin.date.valueOf() > date.valueOf())
						return 'disabled';
					else
						return '';
				
    		}
    	}).on('changeDate', function(ev) {
    		checkout.hide();
    	}).data('datepicker');	
    });
	
	

	function get_price(n)
	{
		var f_date = $("#f_date").val();
		var t_date = $("#t_date").val();
		if($("#form").validationEngine('validate')){ f_date
			var get_price_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=get_profit_loss', '',1);?>");	
			$.ajax({
						url : get_price_url,
						method : 'post',
						data : {f_date : f_date, t_date : t_date, n:n},
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
											'download': 'profit_loss_report.xls',
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
		/*var response = $('.tab_data').html();
		
		excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(response);	
					 $('<a></a>').attr({
								'id':'downloadFile',
								'download': 'profit_loss_report.xls',
								'href': excelData,
								'target': '_blank'
						}).appendTo('body');
						$('#downloadFile').ready(function() {
							$('#downloadFile').get(0).click();
						});*/	
						
		var n=0;
		var res = get_price(n);	
	}
</script> 
<!-- Close : validation script -->
<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>