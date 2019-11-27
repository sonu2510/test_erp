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

		$data = $obj_invoice->viewFullSalePurReport($post);
		
//	printr($data);
			
}

?> 
<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-list"></i> Sales / Puchase Report</h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>   
        
      <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading bg-white"> 
       
          	<span> Sales / Puchase Report</span>
          	<span class="text-muted m-l-small pull-right">
          			
            </span>
           
          </header>
          
          <div class="panel-body"></div>
			 <form class="form-horizontal" method="post" name="frm_add" id="frm_add" enctype="multipart/form-data" action="">
                
              <div class="form-group">
                <label class="col-lg-3 control-label">Month</label>
                    <div class="col-lg-3"> 
                          <select class="form-control" name="m_value" id="m_value" >
                                <option value="1" <?php if(isset($post) && $post['m_value']==1) echo 'selected=selected';?>>Monthly Basis</option>
                                <option value="2" <?php if(isset($post) && $post['m_value']==2) echo 'selected=selected';?>>Quarterly Basis</option>
                                <option value="3" <?php if(isset($post) && $post['m_value']==3) echo 'selected=selected';?>>Full Year</option>
                                <option value="4" <?php if(isset($post) && $post['m_value']==4) echo 'selected=selected';?>>Half Year</option>                                                            
                   		 </select>
                    </div>
               </div>
			   
			   <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Date From</label>
                <div class="col-lg-3">
                	<input type="text" name="f_date"  value="<?php echo isset($post)?$post['f_date']:'';?>" placeholder="From Date" class="span2 form-control validate[required]" data-date-format="yyyy-mm-dd" readonly="readonly" id="f_date"/>
                    </div>
              </div>
              
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Date To</label>
                <div class="col-lg-3">
                  <input type="text" name="t_date"  value="<?php echo isset($post)?$post['t_date']:'';?>" placeholder="To Date" class="span2 form-control validate[required]" data-date-format="yyyy-mm-dd" readonly="readonly" id="t_date"  />
                </div>
              </div>
              
       	    <div class="form-group">
                <label class="col-lg-3 control-label">Product Code</label>
                <div class="col-lg-3">
                <?php $pro_code = $obj_invoice->getActiveProductCode();?>
					<select class="form-control chosen_data " name="product_code" >
                        <option value="">Please Select</option>
                        <?php foreach($pro_code as $code) {?>
                                <option value="<?php echo $code['product_code_id']; ?>" <?php if(isset($post) && $post['product_code']==$code['product_code_id']) echo 'selected=selected';?>><?php echo $code['product_code']; ?></option>
                        <?php } ?>
                    </select>
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
              	<div class="col-lg-10 col-lg-offset-1 tab_data">
                	<!--<div class="panel-body">
                    	<div class="table-responsive">-->
                    	      <input type="hidden" id="data_detail"  name ="data_detail" value='<?php echo json_encode($_POST);?>' />
                    	       <?php echo $data;?>
                        <!--</div>
                        
                    </div> -->
                    
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

    .chosen-container.chosen-container-single {
    width: 300px !important; /* or any value that fits your needs */
}
</style>
<script src="https://harvesthq.github.io/chosen/chosen.jquery.js" type="text/javascript"></script>
<link rel="stylesheet" href="https://harvesthq.github.io/chosen/chosen.css" type="text/css"/> 
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script>
  jQuery(document).ready(function(){
	   jQuery("#frm_add").validationEngine();
	   $(".chosen_data").chosen();
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
});
function get_report_sale_pur(){
var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=get_report_sale_pur', '',1);?>");
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
							'download': 'Full Sale / Purchase Report.xls',
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