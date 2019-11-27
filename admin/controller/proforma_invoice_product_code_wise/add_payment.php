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
	'href' 	=> $obj_general->link($rout, 'mod=index&is_delete='.$_GET['is_delete'], '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);
$bradcums[] = array(
	'text' 	=> $display_name.' Detail',
	'href' 	=> '',
	'icon' 	=> 'fa-edit',
	'class'	=> 'active',
);

//printr($_GET['proforma_id']);

//Close : bradcums

$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];

$proforma_new=$obj_pro_invoice->getProforma(decode($_GET['proforma_id']));
//printr($proforma_new);
if(isset($_GET['proforma_id']) && !empty($_GET['proforma_id'])){
	if(!$obj_general->hasPermission('view',$menuId)){
		$display_status = false;
	}else{
		$proforma_id = base64_decode($_GET['proforma_id']);
		$proforma=$obj_pro_invoice->getProformaData($proforma_id);
		$click = 1;
		}

}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}
$user_info  =$obj_pro_invoice->getUser($proforma['added_by_user_id'],$proforma['added_by_user_type_id']);

if(isset($_POST['btn_payment']))
{
	$post = post($_POST);	
	$update_id = $obj_pro_invoice->InsertPayment_detail($post,ADMIN_EMAIL);	
	$obj_session->data['success'] = ADD;
    page_redirect($obj_general->link($rout, 'mod=index&is_delete=0'.$add_url, '', 1));
}
?>

<section id="content">
    <section class="main padder">
   		<div class="clearfix">
    		<h4><i class="fa fa-edit"></i> <?php echo $display_name;?></h4>
    	</div>
    	<div class="row">
    		<div class="col-lg-12"><?php include("common/breadcrumb.php");?>	</div> 
		    <div class="col-sm-10">
    			<section class="panel" >
    			       <header class="panel-heading bg-white"> 
       	        	<span>Proforma Payment Details </span>    </header>
    				<div class="panel-body">
                        <span class="text-muted m-l-small pull-right"></span>
                   
                                <div class="form-group">
                                    <h1>Add Payment For <?php echo $proforma['pro_in_no'];?></h1>
                                </div>

                                	<form class="form-horizontal" method="post" name="p_form" id="p_form" enctype="multipart/form-data" style="margin-bottom:0px;">
            
                                      <!--sonu 6/12/2016-->
                                      	<div class="panel-body">
                        				<div class="form-group">
                        					<label class="col-lg-3 control-label">Invoice Amount</label>
                        					<div class="col-lg-3">					
                        						<input type="text" name="invoice_total" class="form-control terms" id="invoice_total" value="<?php echo $proforma['invoice_total'];?>" readonly="readonly"/>                    
                        							<input type="hidden" name="gen_invoice_id" id="gen_invoice_id" value="<?php echo $proforma['proforma_id'];?>"  />
                        					</div> 
                        				</div>
                        				<div class="form-group " id ="r_amt" >
                        				<label class="col-lg-3 control-label">Receive  Amount</label>
                        					<div class="col-lg-3">					
                        						<input type="text" name="receive_amount" class="form-control terms" id="receive_amount" value="" readonly="readonly"/>                    
                        						
                        					</div> 	
                        				<label class="col-lg-3 control-label">Receive  Amount Date</label>
                        					<div class="col-lg-3">					
                        						<input type="text" name="receive_amount_date" class="form-control terms" id="receive_amount_date" value="" readonly="readonly"/>  
                        					</div> 
                        				</div>
                        				<div class="form-group">
                                        <label class="col-lg-3 control-label"><span class="required">*</span>Payment Type</label>
                                        <div class="col-lg-3" >
                                          <select name="payment_type" id="payment_type" class="form-control validate[required]" onchange="getpayment_amt()"required>   
                                                <option value="">Select Payment Type</option>               
                                                <option value="full"> Full</option>
                                                <option value="Advance" > Advance</option>
                                                <option value="Part"> Part</option>
                                           
                        					</select>                         
                                          
                                        </div>
                        				</div>
                        				  <div class="form-group" style="display:none;" id="Remainder">
                        			
                        					  <label class="col-lg-3 control-label"><span class="required">*</span> Remainder Date</label> 
                        						<div class="col-lg-3">
                                              
                                                 <input type="text" name="remainder"  data-date-format="yyyy-mm-dd" value="<?php echo date("Y-m-d");   ?>" placeholder="Remainder Date" id="remainder" class="input-sm form-control datepicker" />
                                            </div>
                        					</div>      
                                     <div class="form-group">
                        				<label class="col-lg-3 control-label"><span class="required">*</span>Amount</label>
                                                <div class="col-lg-3">
                                                 <input type="text" name="amt_maxico" class="form-control validate[required,custom[number],min[0.001]]"   onchange ="check_amt()" id="amt_maxico" value="" >
                                                </div>
                                     
                                        </div> 
                                   		 <div class="form-group">
                        				<label class="col-lg-3 control-label payment_label" > <span class="required">*</span>Mode Of Payment</label>
                        					<div class="col-lg-3 ">
                                          
                        						<input type="radio" name="payment"  id="payment" value="cash" class="validate[minCheckbox[1]]" checked="checked" > Cash </br>
                        						<input type="radio" name="payment" id="payment" value="cheque" class="validate[minCheckbox[1]]" > Cheque </br>
                        						<input type="radio" name="payment" id="payment" value="Credit Card" class="validate[minCheckbox[1]]" > Credit Card </br>
                        						<input type="radio" name="payment"  id="payment" value="transfer" class="validate[minCheckbox[1]]" > Transfer </br>
                        						<input type="radio" name="payment"  id="payment" value="Paypal" class="validate[minCheckbox[1]]" > Paypal </br>
                        						<input type="radio" name="payment"  id="payment" value="E-Transfer" class="validate[minCheckbox[1]]" > E-Transfer </br>
                        						<input type="radio" name="payment"  id="payment" value="POS - Merchant Settlement" class="validate[minCheckbox[1]]" > POS - Merchant Settlement </br>
                                        
                        					</div> 
                        					
                        				</div>
                        				
                        				<div class="form-group" id="transfer_neft_div" style="display:none">
                        				<label class="col-lg-3 control-label transfer_payment" ><span class="required">*</span>NEFT / Receipt Number</label>
                        					<div class="col-lg-3 ">
                                          
                        					 <input type="text" name="transfer_neft" class="form-control "  id="transfer_neft" value="" >
                                        
                        					</div>
                        					
                        				</div>
                        				<div class="form-group" id="transfer_irtgs_div" style="display:none">
                        				<label class="col-lg-3 control-label transfer_payment" ><span class="required">*</span>IRTGS</label>
                        					<div class="col-lg-3 ">
                                          
                        					 <input type="text" name="transfer_irtgs" class="form-control "  id="transfer_irtgs" value="" >
                                        
                        					</div>
                        					
                        				</div>
                        				
                        				</div>
                                        <div class="form-group">
                                        <label class="col-lg-3 control-label">Payment Details</label>
                                        <div class="col-lg-6">
                                          
                                          	<textarea name="detail_maxico" id="detail_maxico"  class="form-control " ></textarea>
                                           
                                        </div>	
                        
                        
                        						
                                     </div>
                        			 <div  class="form-group">
                        			   <label class="col-lg-3 control-label"><span class="required">*</span>Date of Payment Receipt</label> 
                                             <div class="col-lg-3">
                                                <input type="text" name="datetime" id="datetime"   data-date-format="yyyy-mm-dd" value="<?php  echo date("Y-m-d");  ?>" placeholder="Payment Date"    class="input-sm form-control datepicker" />
                                            </div>
                        			 </div>
                        			   <div class="form-group table-responsive" >
                                            <div class="col-lg-9 col-lg-offset-3">
                                                 
                                               <?php  $check_sales_qty = $obj_pro_invoice->checkSalesQty($proforma['proforma_id'], $proforma['added_by_user_type_id'], $proforma['added_by_user_id'], $proforma['pro_in_no'],$proforma_user['user_id']);
    											?>
                                                  <!--<button type="submit"  name="btn_payment" class="btn btn-primary">Save</button>-->
                                                <button type="button" id="generate_inv" onclick="gen_invoice('close')" class="btn btn-primary" >Save and Close</button>
                        						<button type="button" id="generate_inv" onclick="gen_invoice('download')" class="btn btn-primary">Save And Download Payment Pdf</button> 
                        						<?php if(empty($check_sales_qty) ){
                            						    if($user_info['user_id']=='10'){ ?>
                            					            <button type="button" id="generate_inv" onclick="gen_invoice('paid')" class="btn btn-primary">Save And Paid</button>
                            					       <?php }else { ?>
                            					             <button type="button" id="generate_inv" onclick="gen_invoice('invoice')" class="btn btn-primary">Save And Generate Sales invoice</button>    
                            					       <?php }
                        					     }else{?> 
                                                     <a class="btn btn-primary btn-sm" onclick="check_stock_qty(<?php echo $proforma['proforma_id']; ?>, '<?php echo $proforma['pro_in_no']; ?>',<?php echo $proforma['added_by_user_type_id']; ?>,<?php echo $proforma['added_by_user_id']; ?>,<?php echo $proforma_user['user_id'];?>)">Check Stock</a>
                                                <?php }?>
                                           		 <a class="btn btn-default" href="<?php echo $obj_general->link($rout, 'mod=index&is_delete='.$_GET['is_delete'].$add_url,'',1);?>">Cancel</a>
                                            </div>
                                        </div>
                                        
                                        <?php //if($user_id==44 && $user_type_id==2){?>
                                         <!--<div class="col-lg-9 col-lg-offset-3">
                                         <div id="mySelect" class="select btn-group m-b" data-resize="auto" >
                                                      <button type="button" data-toggle="dropdown" class="btn btn-white btn-sm dropdown-toggle"> <span class="dropdown-label">Please Select </span> <span class="caret"></span> </button> 
                                                      <ul class="dropdown-menu" onclick="">
                                                         <li data-value=""><a>Please Select</a></li>
                                                         <li class="li_tag" data-value="close"><a href="" >Save and Close</a></li>
                                                         <li class="li_tag" data-value="download"><a href="">Save And Download Payment Pdf</a></li>
                                                         <li class="li_tag" data-value="invoice" ><a href="">Save And Generate Sales invoice</a></li>
                                                      </ul>
                                                 </div>
                                                 <div class="col-lg-9 col-lg-offset-1" id="btn_combo">
                            						<button type="button" id="generate_inv" onclick="gen_invoice('close')" class="btn btn-primary" >Save and Close</button>
                            						<button type="button" id="generate_inv" onclick="gen_invoice('download')" class="btn btn-primary">Save And Download Payment Pdf</button> 
                            						<button type="button" id="generate_inv" onclick="gen_invoice('invoice')" class="btn btn-primary">Save And Generate Sales invoice</button> 
                            					</div>
                                                 </div>-->
                                        <?php //}?>
                                 
                        			  </div>
                        			</div>
                           		</form>  
                              
                            </div>
                              </section>
                            </div> 
                      
				</div>
	</section>    
</section>

<!-- Modal -->

<!--add modal by sonu 20-9-2019-->
<div class="modal fade" id="form_con1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:46%;">
    <div class="modal-content">
   	 <form class="form-horizontal" method="post" name="form" id="ckeck_stock" style="margin-bottom:0px;">
              <div class="modal-header">
                   	 	<h4 class="dispatch" id="myModalLabel">Stock Details For <!--kavita 10-4-2017--><span id="pr_no" style=""></span><!--END--></h4>
                  	<!-- <input type="hidden" name="product_code_id" id="product_code_id" value=""  />-->
              </div>
               <div class="modal-body">
               <input name="stock_detail_id" id="stock_detail_id" value=""  type="hidden"/>             
                    <div class="table-responsive">                      
                       	
        			<table class="table table-striped m-b-none text-small">
        				<thead>
        					<tr>
        					<th>Product Code</th>
        					<th>Proforma Qty</th>
        					<th>Stock Qty</th>
        					</tr>
        				</thead>
                       <tbody id="stock_data">
        
                       </tbody>
                        </table>
                </div>
             </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
               
              </div>
   		</form>   
    </div>
  </div>
</div>
<style>

@media print{

  body{ background-color:#FFFFFF; background-image:none; color:#000000 }

  #ad{ display:none;}

  #leftbar{ display:none;}

  #contentarea{ width:100%;}

}



.col-lg-3 {

width: 15%;

}

#client {

    border-left: 6px solid #0087c3;

    float: left;

    padding-left: 6px;

}

h1 {

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

article, article address, table.meta, table.inventory { margin: 0 0 3em; }

table.meta, table.balance { float: right; width: 50%; }

table.meta:after, table.balance:after { clear: both; content: ""; display: table; }



/* table meta */



table.meta th { width: 40%; }

table.meta td { width: 60%; }



/* table items */



table { font-size: 75%; table-layout: fixed; width: 100%; }

table { border-collapse: separate; border-spacing: 2px; } 

th, td { border-width: 1px; padding: 0.5em; position: relative; text-align: left; }

th, td { border-radius: 0.25em; border-style: solid; }

th { background: #EEE; border-color: #BBB; }

td { border-color: #DDD; }

</style>


<!-- Start : validation script -->

<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/select2/select2.min.js"></script>
<script>
 jQuery(document).ready(function(){
   jQuery("#p_form").validationEngine();
 var proforma_id= $("#gen_invoice_id").val();
    check_amt_payment(proforma_id);
 });
$(document).ready(function() {
	 $("#remainder").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
	 $("#datetime").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
});
    /*function getpayment_amt()
	{
	 $('#Remainder').hide();
	$("#amt_maxico").prop('readonly', false);
	var edit_amt = $("#invoice_total").val();
	var payment_type=$("#payment_type").val();
	var receive_amount=$("#receive_amount").val();
	var amt = edit_amt - receive_amount;
   
		if(payment_type=='full')
		{
		    if(amt!=0){
			    $("#amt_maxico").val(amt);			
		
		    	$("#amt_maxico").prop('readonly', true);
		    }
		}
		else if(payment_type=='Part')
		{
			$('#Remainder').show();
		}
		else
		{	
			$("#amt_maxico").val('');
			
		}

}*/

function check_amt(){
	var inv_amt = $("#invoice_total").val();
	var receive_amount=$("#receive_amount").val();
	var amt = inv_amt - receive_amount;
	var payment_amt =$("#amt_maxico").val();
	if(payment_amt>amt)
	{
		$("#amt_maxico").val('');
	}else{
		
	}
	
}	function getpayment_amt()
	{
	$('#Remainder').hide();
	$("#amt_maxico").prop('readonly', false);
	var edit_amt = $("#invoice_total").val();
	var payment_type=$("#payment_type").val();
	var receive_amount=$("#receive_amount").val();
	var amt = edit_amt - receive_amount;
//	alert(recive_amount);
	//if(recive_amount == ''){
		if(payment_type=='full')
		{ 
		      if(amt!=0){
    			$("#amt_maxico").val(amt);			 
    			//$("#amt_maxico").pro(amt);
    			$("#amt_maxico").prop('readonly', true);
		      }
		}
		else if(payment_type=='Part')
		{
			$('#Remainder').show();
		}
		else
		{	
			$("#amt_maxico").val('');
			
		}
	//}
	//$("input[name*='amount_paid']").change();
}

$("input[name='payment']").click(function(){

  var selection=$(this).val();
  var invoice_total=$('#invoice_total').val();
  
   if(selection=='transfer'){
       if(invoice_total<=200000)
           $("#transfer_neft_div").css("display","block")
        else
         $("#transfer_irtgs_div").css("display","block")
    }else{
        $("#transfer_neft_div").css("display","none")
        $("#transfer_irtgs_div").css("display","none")
    }
});
     

 function check_amt_payment(proforma_id){
     //  alert(proforma_id);
            var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=Payment_detail', '', 1); ?>");
            $.ajax({
                url : url,
                type :'post',
                data :{proforma_id:proforma_id},
                success: function(response){
                var val =  $.parseJSON(response);
                    var receive_amount = JSON.stringify(val.receive_amount);
                    $("#receive_amount_date").val(val.receive_date);
                    $("#receive_amount").val(val.receive_amount);
                   // alert(receive_amount);
                    if(receive_amount=='null'){
                          $("#receive_amount").val(0);
                    } 
                   

                },


            });
   
      
		
	}
	
	
	
function generate_sales()
{
// alert('generate_sales');

    var proforma_id= $("#gen_invoice_id").val();
    var gen_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=gen_sales', '',1);?>");
    $.ajax({            
        url : gen_url,
        type :'post',
        data :{proforma_id:proforma_id},
        success: function(response){
                <?php if($user_info['user_id']=='33' || $user_info['user_id']=='24' || $user_info['user_id']=='19'  || $user_info['user_id']=='44') { ?>
                        window.location.href='<?php echo HTTP_SERVER; ?>/admin/index.php?route=proforma_invoice_product_code_wise&mod=dis_stock&invoice_no='+response+'&is_delete=0';
                <?php } 
                else { ?>
				        window.location.href='<?php echo HTTP_SERVER; ?>/admin/index.php?route=sales_invoice&mod=add&invoice_no='+response+'&is_delete=0';
			<?php } ?>
               // window.location.href='<?php  echo HTTP_SERVER; ?>/admin/index.php?route=sales_invoice&mod=add&invoice_no='+response+'&is_delete=0';
        },
    });
}
function pdfcls(){  
        $(".note-error").remove();
        var url = '<?php echo HTTP_SERVER.'pdf/payment_invoicepdf.php?mod='.encode('payment_invoice').'&token='.$_GET['proforma_id'].'&ext='.md5('php').'&n=0';?>';
        window.open(url, '_blank');
        return false;
}

/*$("ul[class*=dropdown-menu] li").click(function () {
    var data_val = $(this).attr('data-value'); // gets innerHTML of clicked li
    var proforma_id= $("#gen_invoice_id").val();
    var formData = $("#p_form").serialize();      
    var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=addpaymentAndClose', '',1);?>");
    var rout = '<?php echo $rout;?>';
    $.ajax({
        type: "POST",
        url: url,                   
        data:{data_val:data_val,formData:formData},
        success: function(json) {
               
                if(data_val=='download')
                  pdfcls();
                if(data_val=='invoice')                
                   generate_sales();
                if(data_val=='close'){
            	    var redirect=setTimeout(function(){window.location = getUrl('<?php echo $obj_general->link($rout, '&mod=index&is_delete=0', '',1); ?>');}, 800);
            }
                 }
         
    });
});*/
function gen_invoice(data_val)
{  
    //var data_val = $(this).attr('data-value'); // gets innerHTML of clicked li
     var receive_amount=  parseInt($("#receive_amount").val());
	var invoice_total = parseInt($("#invoice_total").val());
	var Amt=$("#amt_maxico").val();
	var total_paid_amt=0;
    var total_paid_amt=parseInt(Amt)+parseInt(receive_amount);
 
   if($("#p_form").validationEngine('validate')){ 
        gen_invoice=function(){};
        var proforma_id= $("#gen_invoice_id").val();
        var formData = $("#p_form").serialize();      
        var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=addpaymentAndClose', '',1);?>");
        var rout = '<?php echo $rout;?>';
        $.ajax({
            type: "POST",
            url: url,                   
            data:{data_val:data_val,formData:formData},
            success: function(json) {
                   
                    if(data_val=='download')
                      pdfcls();
                    if(data_val=='invoice'){ 
                       if(total_paid_amt==parseInt(invoice_total)){
                            generate_sales();
                       }else{
                             alert('Add Full Payment after You Can Generate Sales Invoice');
                             location.reload();
                       }
                    }
                    if(data_val =='paid')
                    {
                        gotopaid(proforma_id,<?php echo $proforma['gen_pro_as']; ?>);
                    }
                    var redirect=setTimeout(function(){window.location = getUrl('<?php echo $obj_general->link($rout, '&mod=index&is_delete=0', '',1); ?>');}, 800);
                }
             
        });
    }
}
	function check_stock_qty(proforma_id,pr_no,user_type_id,user_id,admin_user_id)
	{
		
		$("#form_con1").modal('show');
	
		$("#pr_no").html(pr_no);
		<!--END kavita-->
		var stk_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=checkStock', '',1);?>");
		$.ajax({			
			url : stk_url,
			type :'post',
			data :{proforma_id:proforma_id,pr_no:pr_no,user_type_id:user_type_id,user_id:user_id,admin_user_id:admin_user_id},
			success: function(response){
				//alert(response);
					//window.location.href='<?php //echo HTTP_SERVER; ?>/admin/index.php?route=sales_invoice&mod=add&invoice_no='+response+'&is_delete=0';
					$('#stock_data').html(response);
				
			},
			
						
		});
	}
    function gotopaid(proforma_id,gen_pro_as)
    {
        
        var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=gotopaid', '', 1); ?>");
                $.ajax({
                    url : url,
                    type :'post',
                    data :{proforma_id:proforma_id},
                    success: function(response){
                    
                        window.location.href='<?php echo HTTP_SERVER; ?>/admin/index.php?route=packing_order&mod=index&gen_pro_as='+gen_pro_as;
    
                    },
    
    
                });
    }
 
</script>	

<!-- Close : validation script -->

