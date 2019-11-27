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
	'text' 	=> 'Stock Order List',
	'href' 	=> '',
	'icon' 	=> 'fa-list',
	'class'	=> 'active',
);




if(isset($_GET['proforma_id']) && !empty($_GET['proforma_id'])){
	$proforma_id = decode($_GET['proforma_id']);
	$proforma_inv = $obj_pro_invoice->Payment_detail_for_Customer($proforma_id);
	
	//printr($proforma_inv);
}
?>
<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-list"></i>Customer Payment List</h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>   
    <div class="col-sm-8">
        
            <section class="panel">  
            	
                <header class="panel-heading bg-white">
                 <span>Customer Payment Listing</span>
               
                 <span class="text-muted m-l-small pull-right">
                 	
                 		  <a class="label bg-info pdfcls" href="javascript:void(0);"><i class="fa fa-print"></i> PDF</a>
                         
                 </span>
                </header>
              
            	<div class="panel-body">
                        <span class="text-muted m-l-small pull-right"></span>
                        <form class="form-horizontal responsive" method="post" name="form" id="form" enctype="multipart/form-data">
                            <div>
                                <div class="form-group">
                                    <h1>Payment Details</h1>
                                </div>
                            </div>
                            <div class="panel-body font_medium"  id="print_div1" style="font-size: 25px;">
                                <?php
                                        echo $proforma_inv;

                                ?> 
                            </div> 
                            <div class="panel-body font_medium"  id="print_div" style="font-size: 30px;">
                                <?php
                                         $html = $obj_pro_invoice->viewProformaPaymentDetail($proforma_id); 
                                         echo $html;

                                ?> 
                            </div> 
                                <div class="form-group table-responsive" >
                                    <div class="col-lg-9 col-lg-offset-3">
                                   		 <a class="btn btn-default" href="<?php echo $obj_general->link($rout, 'mod=index&is_delete='.$_GET['is_delete'].$add_url,'',1);?>">Cancel</a>
    
                                    </div>
    
                                </div>
                            

                            </div>
              </section>    
           </div>    
     
  </section>
</section>

<div class="modal fade" id="payment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="width:80%; height:300%;">
    <div class="modal-content">

    	<form class="form-horizontal" method="post" name="p_form" id="p_form" style="margin-bottom:0px;">
              <div class="modal-header title">
                   <h4 class="modal-title" id="myModalLabel"><span></span>Payment Details</h4>
                  	<input type="hidden" name="gen_payment_id" id="gen_payment_id" value=""  />

              </div>
              <!--sonu 6/12/2016-->
                <div class="modal-body">
			
				
				<div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>Payment Type</label>
                <div class="col-lg-3" >
                  <select name="payment_type" id="payment_type" class="form-control validate[required]" onchange="getpayment_amt()"required>   
                        <option value="">Select Payment Type</option>               
                        <option value="full"  > Full</option>
                        <option value="Advance" > Advance</option>
                        <option value="Part"> Part</option>
                   
					</select>                         
                  
                </div>
				</div>
				  <div class="form-group" id = "r_amt" style="display:none;" id="Remainder">
			
					  <label class="col-lg-3 control-label"><span class="required">*</span> Remainder Date</label> 
						<div class="col-lg-3"> 
                       <input type="text" class="combodate form-control validate[required]" data-format="DD-MM-YYYY" data-required="true" data-template="D MMM YYYY" name="remainder" id="datetime" value="<?php echo date("d-m-Y");   ?> " >
                    </div>
					</div>      
             <div class="form-group">
				<label class="col-lg-3 control-label"><span class="required">*</span>Amount</label>
                        <div class="col-lg-3">
						
                         <input type="text" name="amt_maxico" class="form-control validate[required,custom[number],min[0.001]]" id="amt_maxico" value="" >
                        </div>
                        
                       
             
                </div>
           		 <div class="form-group">
				<label class="col-lg-3 control-label payment_label" > <span class="required">*</span>Mode Of Payment</label>
					<div class="col-lg-3 ">
                  
						<input type="radio" name="payment" id="payment" value="cash" class="validate[minCheckbox[1]]" checked="checked" > Cash </br>
						<input type="radio" name="payment" id="payment" value="check" class="validate[minCheckbox[1]]" > Check </br>
						<input type="radio" name="payment" id="payment" value="credit_card" class="validate[minCheckbox[1]]" > Credit Card </br>
						<input type="radio" name="payment" id="payment" value="transfer" class="validate[minCheckbox[1]]" > Transfer </br>
                
					</div>
					  
				</div>
				
				</div>
                <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Payment Details</label>
                <div class="col-lg-6">
                  
                  	<textarea name="detail_maxico" id="detail_maxico"  class="form-control validate[required]" ></textarea>
                   
                </div>				
             </div>
			 <div  class="form-group">
			   <label class="col-lg-3 control-label"><span class="required">*</span>Date of Payment Receipt</label> 
                    <div class="col-lg-3"> 
                       	<input type="text" name="datetime" id="datetime" value="<?php echo date("Y-m-d");?>"  data-format="YYYY-MM-DD"  data-template="D MMM YYYY" placeholder="Date"  class="combodate form-control"/>
                    </div>
			 </div>
		 
             
      		<div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="button"  name="btn_payment" onclick="edit_payment()" class="btn btn-warning">Save</button>
              </div>
			  </div>
			</div>
   		</form>   
		
    </div>
  </div>
</div>
<div class="modal fade" id="form_con" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" method="post" name="form" id="conform_form" style="margin-bottom:0px;">
                <div class="modal-header title">
                    <h4 class="modal-title" id="myModalLabel"><span id="pro"></span></h4>
                </div>
                <div class="modal-body">
                    <input name="payment_id" id="payment_id" value=""  type="hidden"/>
                    <input name="proforma_id" id="proforma_id" value=""  type="hidden"/>	
                    <h4 class="streamlined_title"> Sure !!! <br /><br />
                        Do you want to Delete Invoice Payment ?</h4>
                </div> 
                <div class="modal-footer">
                    <button type="button" name="btn_submit1" class="btn btn-primary" onclick="remove_payment_detail()">Yes</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
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
<script type="application/javascript">
jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
       // jQuery("#sform").validationEngine();
});

$("#excel_link").click(function(){
	var proforma_id = <?php echo $proforma_id?>;
//	alert(proforma_id);
	
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=Payment_detail_for_Customer', '',1);?>");
	
	 $.ajax({
        url: url, // the url of the php file that will generate the excel file
       	data : {proforma_id : proforma_id},
		method : 'post',
        success: function(response){
			excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(response);
			 $('<a></a>').attr({
							'id':'downloadFile',
							'download': 'payment-detail.xls',
							'href': excelData,
							'target': '_blank'
					}).appendTo('body');
					$('#downloadFile').ready(function() {
						$('#downloadFile').get(0).click();
					});
        }
		
    });


});	

$(".pdfcls").click(function(){			 
		
				$(".note-error").remove();
				var url = '<?php echo HTTP_SERVER.'pdf/payment_invoicepdf.php?mod='.encode('payment_invoice').'&token='.$_GET['proforma_id'].'&ext='.md5('php').'&n=0';?>';
				window.open(url, '_blank');
			return false;
		});

	function Edit_payment_details(payment_id,proforma_id){
	
           var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=Edit_payment_details', '', 1); ?>");
            $.ajax({
                url : url,
                type :'post',
                data :{payment_id:payment_id},
                success: function(response){
                var val =  $.parseJSON(response);

                    //console.log(val.payment_receive_date);
                 //   console.log(val);
                    var payment_type = JSON.stringify(val.payment_type);
						var arr = val.payment_receive_date.split("-");
				 var mon = parseInt(arr[1])-parseInt(1);
			//	console.log(arr[0]+'-'+arr[1]+'-'+arr[2]);
				$('.day option[value='+arr[2]+']').attr('selected', true);
				$('.month option[value='+mon+']').attr('selected', true);
				$('.year option[value='+arr[0]+']').attr('selected', true);
                               
                  $("#gen_payment_id").val(payment_id); 
				  $("#amt_maxico").val(val.payment_amount); 
				  $("#detail_maxico").val(val.payment_detail);
				  $("#payment").val(val.payment_mode); 
				  $("#datetime").val(val.payment_receive_date);
				  $("#payment_type").val(val.payment_type);
				  
				  
                 //  alert(payment_type );
				   //  alert(val.Remainder);
                   if(payment_type =="\"Part\""){
				
                        $("#remainder").val(val.Remainder);
						$("#r_amt").css("display","inline");
                   }else if(payment_type !="\"Part\"") {
					    $("#r_amt").css("display","none");
				   }
                   $("#payment").modal("show");

                },


            });
       
		// $("#payment").modal("show");

	}
	function remove_payment(payment_id,proforma_id){
	
         $(".note-error").remove();
		 $("#payment_id").val(payment_id);	
		 $("#proforma_id").val(proforma_id);	
		 $("#form_con").modal("show");

	}
	
	
	function edit_payment(){
		payment_id =$("#gen_payment_id").val();	
		payment_amount=$("#amt_maxico").val(); 
		payment_detail=$("#detail_maxico").val();
		payment_mode=$("#payment").val(); 
		payment_receive_date=$("#datetime").val();
		alert(payment_receive_date);
		payment_type=$("#payment_type").val();
		Remainder= $("#remainder").val();
		    var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=edit_payment', '', 1); ?>");
            $.ajax({
                url : url,
                type :'post',
                data :{payment_id:payment_id,payment_amount:payment_amount,payment_detail:payment_detail,payment_mode:payment_mode,payment_receive_date:payment_receive_date,payment_type:payment_type,Remainder:Remainder},
                success: function(response){             
				
                   $("#form_con").modal("hide");
				  location.reload();
                },


            });
		
	}
	
		function remove_payment_detail(){
		payment_id =$("#payment_id").val();	
			proforma_id =$("#proforma_id").val();
		    var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=remove_payment', '', 1); ?>");
            $.ajax({
                url : url,
                type :'post',
                data :{payment_id:payment_id,proforma_id:proforma_id},
                success: function(response){             
				
                   $("#form_con").modal("hide");
				 location.reload();
                },


            });
		
	}

</script>           
