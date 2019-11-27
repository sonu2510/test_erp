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
	'text' 	=> $display_name.' List ',
	'href' 	=> '',
	'icon' 	=> 'fa-list',
	'class'	=> 'active',
);

if(!$obj_general->hasPermission('view',$menuId)){
	$display_status = false;
}

if($display_status) {

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
		  	 <span><?php echo $display_name;?> Listing </span>
           
              
          </header>
          <div class="panel-body">
              
            <form class="form-horizontal" method="post" name="frm_add" id="frm_add" enctype="multipart/form-data">
                     
              
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Country</label>
                <div class="col-lg-3">
                <?php $coun = $obj_address->getIbCountry(); //printr($coun); ?>
					<select class="form-control validate[required]" name="group" >
                        <option value="">Select Option</option>
                        <?php foreach($coun as $group) { ?>
                                <option value="<?php echo $group['international_branch_id']; ?>"><?php echo $group['country_name'] .'  ==>  '.$group['name']; ?></option>
                        <?php } ?>                                       
                    </select>
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Date From</label>
                <div class="col-lg-3">
                	<input type="text" name="f_date"  value="" placeholder="From Date" class="span2 form-control" data-date-format="yyyy-mm-dd" readonly="readonly" id="f_date"/>
                    </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Date To</label>
                <div class="col-lg-3">
                  <input type="text" name="t_date"  value="" placeholder="To Date" class="span2 form-control" data-date-format="yyyy-mm-dd" readonly="readonly" id="t_date"  />
                </div>
              </div>
              
             <div class="form-group">
                <label class="col-lg-3 control-label">Order Type</label>
                <div class="col-lg-3">
                    <select class="form-control" name="order_type" >
                       <option value="">Select Option</option>
                       <option value="Custom">Custom Order</option>
                       <option value="Stock">Stock Order</option>
                    </select>
                </div>
              </div>
              
              <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                	<button type="button" name="btn_pro" id="btn_pro" class="btn btn-primary">Proceed</button>	
                </div>
              </div>
            </form>
                <form class="form-horizontal response_div" method="post" name="frm" id="frm" enctype="multipart/form-data">
                     
                </form>
          </div>
          
        </section>
      </div>
    </div>
  </section>
</section>
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script type="application/javascript">
 jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
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
$('#btn_pro').click(function(){
      var formData = $("#frm_add").serialize();
      if($("#frm_add").validationEngine('validate')){
          var url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=customer_followups_report', '',1);?>");
    		$.ajax({
    			url : url,
    			method : 'post',
    			data : {formData : formData},
    			success: function(response){
    			 console.log(response);
    			  //$(".response_div").html(response);
    			  /*excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(response);
    				 $('<a></a>').attr({
    								'id':'downloadFile',
    								'download': 'Contact-report.xls',
    								'href': excelData,
    								'target': '_blank'
    						}).appendTo('body');
    						$('#downloadFile').ready(function() {
    							$('#downloadFile').get(0).click();
    						});*/
    			}
    	});
     }
});
</script>           
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>