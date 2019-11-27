<?php
include("mode_setting.php");
//include("model/product_quotation.php");
//$obj_quotation = new productQuotation;
$bradcums = array();
$bradcums[] = array(
	'text' 	=> 'Dashboard',
	'href' 	=> $obj_general->link('dashboard', '', '',1),
	'icon' 	=> 'fa-home',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> $display_name.' List',
	'href' 	=> '',
	'icon' 	=> 'fa-list',
	'class'	=> 'active',
);

/*if(!$obj_general->hasPermission('view',$menuId)){
	$display_status = false;
}
*/
$limit = LISTING_LIMIT;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}

/*$class ='collapse';
if(isset($_GET['sort'])){
	$sort_name = $_GET['sort'];
}else{
	$sort_name='country_name';
}
*/
if(isset($_GET['order'])){
	$sort_order = $_GET['order']; 
}else{
	$sort_order = 'ASC';
}

if($display_status) {
$total_stock_order = $obj_stock->getUserList('');

if(isset($_POST['btn_pro'])){
		$post = post($_POST);

			$stock_order_details=$obj_stock->getReport($post,$m);

		
	
				$arr=array();
				foreach($stock_order_details as $stock){
				    $arr[]=$stock['template_order_id'].'=='.$stock['product_template_order_id'].'=='.$stock['client_id'];
				    
				}
					
			$html=$obj_stock-> ViewOrderEmail($arr,$post['Status']);
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
		  	<span><?php echo $display_name;?> Listing</span>
          	<span class="text-muted m-l-small pull-right">
            </span>
          </header>

          <div class="panel-body"></div>
			 <form class="form-horizontal" method="post" name="frm_add" id="frm_add" enctype="multipart/form-data" action="">
              <div class="form-group">
                <label class="col-lg-3 control-label">User</label>
                <div class="col-lg-3">
                <?php $userlist = $obj_stock->getUserList(); ?>
					<select class="form-control" name="user_name" >
                        <option value="">Please Select</option>
                        <?php foreach($userlist as $user) { ?>
                                <option value="<?php echo $user['user_type_id']."=".$user['user_id']; ?>"><?php echo $user['user_name']; ?></option>
                              
                                ?>
                        <?php } ?>                                       
                    </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Date From</label>
                <div class="col-lg-3">
                  <input type="text" class="form-control validate[required]" name="f_date" value="" placeholder="From Date" class="span2 form-control" data-date-format="yyyy-mm-dd" readonly="readonly"  id="f_date"/>
                    </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label">Status</label>
                <div class="col-lg-3">
                <?php //$date_status = $obj_stock->getStatus();?>
					<select class="form-control" name="Status" >
                        <option value="">Select Status</option>
                        <option value="0">New</option>
                         <option value="1">Process In</option>
                          <option value="3">Dispatch</option>
                           <option value="2">Decline</option>
                     </select>
                </div>
              </div> 
             <div class="form-group">
                <label class="col-lg-3 control-label">Ship Type</label>
                <div class="col-lg-3">
                    <select class="form-control" name="Ship_type" >
                        <option value="">Select Ship Type</option>
                        <option value="By Air">By Air</option>
                        <option value="By Sea">By Sea</option>
                        <option value="By Pickup">By Pickup</option>
                    </select>
                </div>
              </div> 
              <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                	<button type="submit" name="btn_pro" id="btn_pro" class="btn btn-primary">Proceed</button>	
                </div>
              </div>
              
              
            </form>
            	
            <header class="panel-heading bg-white">
                 <span> Stock Order Report Detail </span>
                 <span class="text-muted m-l-small pull-right">
                 	       <a class="label bg-success " href="javascript:void(0);" onclick="print()"><i class="fa fa-print" ></i> Print</a>
                         
                 </span>
                 
              
            </header>
            <br />
            
             <div class='panel-body' id="print_div">
              <?php 
			    	echo $html;
			  ?>
              </div> 
          	 
          <footer class="panel-footer">
            <div class="row">
              <div class="col-sm-3 hidden-xs"> </div>
             
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
 
function print() {
	
    var divToPrint=document.getElementById('print_div');

      var newWin=window.open('','Print-Window');
    
      newWin.document.open();
    
      newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
    
      newWin.document.close();
}
</script>

<?php 
 } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>       
                
                
                
                
                
                
                
                
                
                
            
            
            
            
            
            
            
            
            
            
            
            
            
            
            