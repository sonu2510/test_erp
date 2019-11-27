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
      <h4><i class="fa fa-list"></i> Fastest Moving Goods </h4>
    </div>
    <div class="row">
    	
        <div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>
        
      <div class="col-sm-8">
        <section class="panel">
          <header class="panel-heading bg-white"> Fastest Moving Goods </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
              <?php $n=1;?>
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
                   <label class="col-lg-3 control-label">Fastest Moving Product By?</label>
                    	<div class="col-lg-4"> 
                            <div class="col-lg-8"> 
                                    <select name="by" id="by" class="form-control">
                                        <option value="1">Quantity</option>
                                        <option value="2">Rate</option>
                                        <option value="3">Product</option>
                                        <option value="4">Size</option>
                                    </select>
                             </div>
                        </div>
               </div>
              
             <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                    <button type="button" name="btn_save" id="btn_save" onclick="get_sheet(<?php echo $n;?>)" class="btn btn-primary">Submit </button>	
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
		//getdate();
		$("#f_date").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
		$("#t_date").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
    
	  <?php  if($_SESSION['LOGIN_USER_TYPE']!='4' && $_SESSION['ADMIN_LOGIN_SWISS']!='10')
	   { ?>
    		var StartDate = $("#f_date").val();
    		var nowTemp = new Date();
    	    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
    	    var checkin = $('#f_date').datepicker({
       			onRender: function(date) {
    		
    			return date.valueOf();
        		}
        	}).on('changeDate', function(ev) {
    				$(".tab_data").html('');
    			var minu=ev.date.valueOf();
    			var newDate = new Date(minu);
    			var rMax = new Date((newDate.getFullYear()) + parseInt(1), newDate.getMonth(),newDate.getDate() - 1);
    			checkout.setValue(rMax);
    			checkin.hide();
        		
        	}).data('datepicker');
    		
    		var checkout = $('#t_date').datepicker({
        		onRender: function(date) {
    			}}).on('changeDate', function(ev) {
        		checkout.hide();
        	}).data('datepicker');
    		
	   <?php } ?>
    	});
    
	function get_sheet(n)
	{
		
		var f_date = $("#f_date").val();
		var t_date = $("#t_date").val();
		var by_option = $("#by option:selected").val();
		
		
		if($("#form").validationEngine('validate')){ 
			var get_price_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=get_sheet', '',1);?>");	
			$.ajax({
						url : get_price_url,
						method : 'post',
						data : {f_date : f_date, t_date:t_date, n:n,by_option:by_option},
						success: function(response){
							//alert(response);
							if(by_option=='1')
							    var id ='downloadFile';
							else if(by_option=='2')
							    var id ='downloadFile2';
							else if(by_option=='3')
							    var id ='downloadFile3';
							else 
							    var id ='downloadFile4';
							if(n == 1)
							{
								$(".tab_data").html(response);
							}
							else
							{
								 excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(response);	
								 $('<a></a>').attr({
											'id':id,
											'download': 'Record_Year.xls',
											'href': excelData,
											'target': '_blank'
									}).appendTo('body');
									$('#'+id).ready(function() {
										$('#'+id).get(0).click();
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
		var res = get_sheet(n);		
	}
</script> 
<!-- Close : validation script -->
<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>