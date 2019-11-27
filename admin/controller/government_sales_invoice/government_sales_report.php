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

		$data = $obj_invoice->government_sales_report($post);
		
	
			
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
                 <span> Sales Report Detail</span>
               
                 <span class="text-muted m-l-small pull-right">
                     	<a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=index_opening_balance', '',1);?>"><i class="fa fa-plus"></i> Add Opening Balance </a> &nbsp;
                 		 <a class="label bg-success" href="javascript:void(0);" id="excel_link"><i class="fa fa-print"></i> Excel</a>
                 </span>
                </header>
          
          <div class="panel-body">
			 <form class="form-horizontal" method="post" name="frm_add" id="frm_add" enctype="multipart/form-data" action="">
         
			   <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Month</label>
                <div class="col-lg-3">
                    <select name="f_date" class="form-control validate[required]">
                      <option>Select Month</option>
                      <?php for($m=1;$m<=12;$m++)
                            { ?>
                                <option value=" <?php echo $m; ?> "  ><?php echo DateTime::createFromFormat('!m', $m)->format('F'); ?></option>
                      <?php }?>
                    </select>	
                </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Year</label>
                <div class="col-lg-3">
                    <select name="t_date" class="form-control validate[required]">
                      <option>Select Year</option>
                      <?php for($m=2018;$m<=2025;$m++)
                            { ?>
                                <option value='<?php echo $m;?>'   ><?php echo $m;?></option>
                      <?php }?>
                    </select>	
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Product</label>
                <div class="col-lg-3">
                     <select name="product" id="product" class="form-control " >
                            <option value="0">Pouch</option> </option>
                            <?php 
                             $product_details=$obj_invoice-> getActiveProductReport();
                                foreach($product_details as $product)
                                { ?>
                                    <option value="<?php echo $product['product_id']; ?>" id="option" ><?php echo $product['product_name']; ?></option>
                           <?php } ?>                                                      
                         </select>
                </div>
              </div>
              
              
              <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
               	<button type="submit" name="btn_pro" id="btn_pro" class="btn btn-primary">Proceed</button>	
                </div>
              </div>
              
              <div class="panel-body">
              <div class="form-group report_div">
              	<div class="col-lg-12 ">
                	
                	     <input type="hidden" id="post_data"  name ="post_data" value='<?php echo json_encode($_POST);?>' />
                    	<div class="table-responsive">
                    	    
                    	    <?php echo $data;?>
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
    <footer class="panel-footer">
            <div class="row">
              <div class="col-sm-4 hidden-xs"> </div>
             
             
            </div>
          </footer>
<style>
	 .report_div{zoom : 68%; }
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


$("#excel_link").click(function(){
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=government_sales_report', '',1);?>");
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
							'download': 'Governmant Sales-report.xls',
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