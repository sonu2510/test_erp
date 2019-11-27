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
$click = '';
if(isset($_GET['invoice_no']) && !empty($_GET['invoice_no'])){
	if(!$obj_general->hasPermission('view',$menuId)){
		$display_status = false;
	}else{
		$invoice_no = base64_decode($_GET['invoice_no']);
		$click = 1;
	}
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
      <div class="col-sm-12">
        <section class="panel">  
              <header class="panel-heading bg-white"> Invoice Detail </header>
              <div class="panel-body">
                <div>
                 <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
                     <?php $customer = $obj_invoice->getInvoice($user_type_id,$user_id);?>
				        <div class="form-group">
                               <div class="col-lg-4" onclick="open_tab()">
                                    <select  name="cust" id="customer" class="form-control validate[required] chosen_data" >
                                        <option value="">Select Customer</option>
                                        <?php  foreach($customer as $cust)
                                               {
                                                   echo '<option value="'.$cust['sales_invoice_id'].'">'.$cust['customer_name'].'</option>';
                                               }
                                        ?>
                                    </select>
                               </div>
                               <div class="col-lg-6" id="detail"></div>
                        </div>
				       
                  </form>
                </div>         
            </div>
            <div class="panel-body">
            </div>
             <div class="panel-body">
            </div>
             <div class="panel-body">
            </div>
        </section>    
      </div>
    </div>
  </section>
</section>
<div id="er"></div>
<style>
.col-lg-3 {
width: 15%;
}
#client {
    border-left: 6px solid #0087c3;
    float: left;
    padding-left: 6px;
}
h1 {
   /* background: url("dimension.png") repeat scroll 0 0 rgba(0, 0, 0, 0);*/
	background:#333;
    border-bottom: 1px solid #5d6975;
    border-top: 1px solid #5d6975;
    color: #FFF;
    font-size: 2.4em;
    font-weight: normal;
    line-height: 1.4em;
    margin: 0 0 20px;
    text-align: center;
}
#in_out .in_out {
	float:left;width:100px;
}
.sign_td {
	height:150px;
}
</style>
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/FileSaver.js"></script> 
<script src="<?php echo HTTP_SERVER;?>js/jquery.wordexport.js"></script> 
<script src="https://harvesthq.github.io/chosen/chosen.jquery.js" type="text/javascript"></script>
<link rel="stylesheet" href=" https://harvesthq.github.io/chosen/chosen.css" type="text/css"/> 
<script>
 jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#sform").validationEngine();	
        $(".chosen_data").chosen();
        
        
       
});
function open_tab()
{   //$('#detail').html('');
    $(document).on('mouseover', '#customer + .chosen-container .chosen-results li', function() {
    var invoice_id = $(".chosen_data option").eq($(this).data("option-array-index")).val();

    
        if(invoice_id!='0')
		{
			var url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getProductDesc', '',1);?>");
				$.ajax({
					url : url,
					method : 'post',
					data : {invoice_id : invoice_id},
					success: function(response){
						$('#detail').html(response);			
						},
					error: function(){
						return false;	
					}
				});
		
		}
		
	});
}
// $(document).ready(function(event) {
//     $('select #cust').on('mouseenter', 'option', function(e) {
//         alert(this.value, 'Yeah');
//         // this refers to the option so you can do this.value if you need..
//     });
// });
</script>	
<!-- Close : validation script -->