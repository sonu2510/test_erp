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
	'text' 	=> $display_name.' List',
	'href' 	=> $obj_general->link($rout, '', '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> $display_name.' Detail',
	'href' 	=> '',
	'icon' 	=> 'fa-edit',
	'class'	=> 'active',
);
//Close : bradcums

//Start : edit
$edit = '';
if(isset($_GET['packing_order_id']) && !empty($_GET['packing_order_id'])){
	$packing_order_id = decode($_GET['packing_order_id']);
	$packing = $obj_source->getProforma($packing_order_id);
	$edit = 1;
//	printr($packing_order_id);
//	printr($packing);
}
if(isset($_GET['proforma_packing_order_id']) && !empty($_GET['proforma_packing_order_id'])){
	$proforma_packing_order_id = decode($_GET['proforma_packing_order_id']);
	$invoice_detail = $obj_source-> getInvoice_data($proforma_packing_order_id);
//	printr($invoice_detail);
}
	
//Close : edit


if($display_status){
	//insert 
	if(isset($_POST['btn_save'])){
		$post = post($_POST);		
		
		$insert_id = $obj_source->add_packing_order($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	
	//edit
	if(isset($_POST['btn_update'])){
		$post = post($_POST);		
		//	printr($post);die;
		$insert_id = $obj_source->update_packing_order($post);
		$obj_session->data['success'] = ADD;
		page_redirect($obj_general->link($rout, '', '',1));
	}
	 $latest_no=0;
	$packing_order_id = $obj_source->getOrderNo();
	if(!empty($packing_order_id))
		$latest_no=$packing_order_id;
	
	$strpad = str_pad($latest_no+1,8,'0',STR_PAD_LEFT) 
?>

<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-edit"></i> <?php echo $display_name;?></h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div> 
        
      <div class="col-sm-12">
        <section class="panel">
          <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="frm_add" enctype="multipart/form-data">
              
			<div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Order No. </label>
                <div class="col-lg-3">
				   <input type="hidden" name="edit" id="edit" value="<?php echo $edit; ?>"  />
                  	<input type="text" readonly="readonly" name="order_no" id="order_no" value="<?php echo isset($packing['order_no'])?$packing['order_no']:'PACK'.$strpad;?>" class="form-control validtae[required],custom[number]" />
					<input type="hidden" name="packing_order_id" value="<?php echo isset($packing['packing_order_id'])?$packing['packing_order_id']:'';?>"  />
			  </div>
              </div>
			  
			  <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Mexico Refrence No. </label>
                <div class="col-lg-3">
                  	<input type="text"  name="ref_order_no" id="ref_order_no" value="<?php echo isset($packing['ref_order_no'])?$packing['ref_order_no']:'';?>"   class="form-control validtae[required],custom[number]" />
                </div>
              </div>
             
			   
			  <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Order Date</label>
                <div class="col-lg-3">
                	<input type="text" name="order_date" id="date" value="<?php echo isset($packing['order_date'])?$packing['order_date']:date('Y-m-d');?>" class="form-control validtae[required],custom[number]" />
                    </div>
              </div>
           
			<div class="form-group">
					<label class="col-lg-3 control-label"><span class="required">*</span>Payment Received</label>
					<div class="col-lg-6">
						<input type="text" name="amt_maxico" id="amt_maxico" placeholder="Payment Received" value="<?php echo isset($packing['payment_amount'])?$packing['payment_amount']:'';?>" class="form-control validate[required]">
					</div>
              </div>
             
                <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                <?php if($edit){?>
					<input type="hidden" name="packing_order_id" value="<?php echo isset($packing['packing_order_id'])?$packing['packing_order_id']:'';?>">
                  	<button type="submit" name="btn_update" id="btn_update" class="btn btn-primary">Update </button>
                <?php } else { ?>
                	<button type="submit" name="btn_save" id="btn_save" class="btn btn-primary">Save </button>	
                <?php } ?>  
                  <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a>
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
<!-- Start : validation script -->
<style type="text/css">
.btn-on.active {
    background: none repeat scroll 0 0 #3fcf7f;
}
.btn-off.active{
	background: none repeat scroll 0 0 #3fcf7f;
	border: 1px solid #767676;
	color: #fff;
}
@media (max-width: 400px) {
  .chunk {
    width: 100% !important;
  }
}
#ajax_response,#ajax_return{
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
.about{
	text-align:right;
	font-size:10px;
	margin : 10px 4px;
}
.about a{
	color:#BCBCBC;
	text-decoration : none;
}
.about a:hover{
	color:#575757;
	cursor : default;
}
</style>

<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>ckeditor3/ckeditor.js"></script>
<style type="text/css">
@media (max-width: 400px) {
  .chunk {
    width: 100% !important;
  }
}
</style>
<script>
jQuery(document).ready(function(){
	   jQuery("#form_stock").validationEngine();
	   
	    $("#date").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
		$("#date1").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});

		var checkin = $('#date').datepicker({
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
    		$('#date')[0].focus();
    	}).data('datepicker');
	
});	

</script>


<script>

    jQuery(document).ready(function(){
		
        jQuery("#frm_add").validationEngine();
});



</script> 



<!-- Close : validation script -->

<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>