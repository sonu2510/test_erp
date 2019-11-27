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
	'href' 	=> $obj_general->link($rout, 'mod=index&inv_status='.$_GET['inv_status'], '',1),
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
$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
//Start : edit
$edit = '';
//echo HTTP_SERVER; 
$click = '';
if(isset($_GET['invoice_no']) && !empty($_GET['invoice_no'])){
	if(!$obj_general->hasPermission('view',$menuId)){
		$display_status = false;
	}else{
		$invoice_no = base64_decode($_GET['invoice_no']);
		$click = 1;
	} //end first else
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}


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
            <div id="test"></div>
      <div class="col-sm-8" style="width:75%">
            <section class="panel">  
                <header class="panel-heading bg-white">
                 <span>Invoice Detail</span>
                 <span class="text-muted m-l-small pull-right">                
                 
				 
            
                          
                 </span><br /><br />
              </header>
              
              <div class="panel-body">                                        	
                    <span class="text-muted m-l-small pull-right">
                    </span>
                    
                    <div>
					<form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
						<div class="form-group">
							<label class="col-lg-3 control-label"></label>
							<div class="col-lg-3">
								<small style="color:red">If u have total boxes like 700 so plz type hear in from box 1 and in to box 100. <br>then u start with 101 to ....numbers. </small>
							</div>
        		     </div>
						<div class="form-group">
                    
							<label class="col-lg-3 control-label"><span class="required">*</span>Box From</label>
							<div class="col-lg-3">
								<input type="text" name="from_box" id="from_box" value="" placeholder="Box From" class="form-control validate[required]">
							</div>
						
							
        		     </div>
					 <div class="form-group">
                    
							<label class="col-lg-3 control-label"><span class="required">*</span>Box To</label>
							<div class="col-lg-3">
								 <input type="text" name="to_box" id="to_box" value=""  placeholder="Box To" class="form-control validate[required]">
							</div>
        		     </div>
					 <div class="form-group">
						<label class="col-lg-3 control-label"></label>
							<a class="label bg-info " onclick="inout_label(1);" href="javascript:void(0);"><i class="fa fa-print" ></i> IN Lable PDF</a>
							<a class="label bg-info " onclick="inout_label(2);" href="javascript:void(0);"><i class="fa fa-print" ></i> OUT Lable PDF</a>
        		     </div>
					
					</form>
                    
           </div>      
            <div class="form-group">
         <div class="col-lg-9 col-lg-offset-3"> 
       
       <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '&mod=view&invoice_no='.$_GET['invoice_id'].'&status='.$_GET['status'].'&inv_status='.$_GET['inv_status'], '',1);?>">Cancel</a>
      
       </div>
         </div>   
                  </div>
                </section>    
      </div>
    </div>
  </section>
</section>

<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
 
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script>
 jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#form").validationEngine();
		
});

function inout_label(status)
{
	var invoice_id = '<?php echo $_GET['invoice_id'];?>';
	var from_box = $("#from_box").val();
	var to_box = $("#to_box").val();
	$(".note-error").remove();
	var url = '<?php echo HTTP_SERVER.'pdf/inoutpdf.php?mod='.encode('inoutpdfshort').'&token='.rawurlencode($_GET['invoice_id']).'&status=';?>'+status+'<?php echo  '&ext='.md5('php');?>&from='+from_box+'&to='+to_box;
	console.log(url);
	window.open(url, '_blank');
}

</script>	
<!-- Close : validation script -->