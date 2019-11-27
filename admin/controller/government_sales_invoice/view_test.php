<?php
include("mode_setting.php");
$_GET['inv_status']=0;
$_GET['status']=1;
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
//$menuId='220';
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
//printr($_GET);
if(isset($_POST['btn_sendemail'])){
	$url = HTTP_SERVER.'pdf/invoicepdf.php?mod='.encode('invoice').'&token='.encode($invoice_no).'&status='.rawurlencode($_GET['status']).'&ext='.md5('php');
	$obj_invoice->sendInvoiceEmail($invoice_no,$_GET['status'],$_POST['smail'],$url);
	}
		$invoice=$obj_invoice->getSalesInvoiceData($invoice_no);
	//	printr($invoice);
$addedByInfo = $obj_invoice->getUser($invoice['user_id'],$invoice['user_type_id']);

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
                  
                      <a class="label bg-info pdfcls" href="javascript:void(0);"><i class="fa fa-print"></i> PDF</a>
                      <a class="label bg-info pdfclstesddt"  onclick="ExportPdf()" href="javascript:void(0);"><i class="fa fa-print"></i> PDF TEST</a>
                      
                     <a class="label bg-success " href="javascript:void(0);" onclick="print()"><i class="fa fa-print" ></i> Print</a>
          
                 </span><br /><br />
                
              </header>
              
              <div class="panel-body" >                                        	
                    <span class="text-muted m-l-small pull-right">
                
                  
                    </span>
                    
                  <div id="print_div_details" style="size:A4" class="table-responsive">
                     <?php 
                     
                     
                     if($addedByInfo['country_id']=='111')
                     {
    					 
    					    $html =$obj_invoice->viewInvoice('',$invoice_no,'',$_GET['pdf_status'],'0');

                     } 
					 
					echo $html;?> 
                    <input type="hidden" value="<?php echo $invoice_no;?>" id="number" />
           			<input type="hidden" value="<?php //echo $total_no_of_box;?>" id="total_box" />
           				   <div style="size:A4">
           			 <div id="print_div1" style=" display:none;" class="table-responsive">
           		
           			  <?php   if($addedByInfo['country_id']=='111') 
                     {
    					 
    					    $html1 =$obj_invoice->viewInvoice('',$invoice_no,'',1,'0');
                            	echo $html1;
                     } ?>
           			</div>
           			</div>
               </div>      
            <div class="form-group">
         <div class="col-lg-9 col-lg-offset-3"> 
       <?php $link='mod=index&inv_status='.$_GET['inv_status'];
	   		if($_GET['status']==2)
	   		 { $link= 'mod=view&status=1&invoice_no='.$_GET['invoice_no'].'&inv_status='.$_GET['inv_status'];
			  }?>
       <a class="btn btn-default" href="<?php echo $obj_general->link($rout, $link, '',1);?>">Cancel</a>
      
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
 <?php if($addedByInfo['country_id']=='111') {?>
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>css/invoice.css">
<?php }?>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>



<script>
 jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#sform").validationEngine();
		$(".sendmailcls").click(function(){			
			$(".note-error").remove();
			$("#smail").modal('show');			
			return false;
		}); 
		$(".pdfcls").click(function(){			
			
				$(".note-error").remove();
				var url_ori = '<?php echo HTTP_SERVER.'pdf/g_invoicepdf.php?mod='.encode('g_invoice').'&token='.rawurlencode($_GET['invoice_no']).'&ext='.md5('php').'&n=1&p=1';?>';
				window.open(url_ori, '_blank');
		
			return false;
		});
	/*	$(".pdfclstest").click(function(){			
			
				$(".note-error").remove();
			//	var url_ori = '<?php //echo HTTP_SERVER.'mpdf-development/testing.php?mod='.encode('g_invoice').'&token='.rawurlencode($_GET['invoice_no']).'&n=1&p=1&ext='.md5('php');?>';
			var url_ori = '<?php //echo HTTP_SERVER.'pdf/g_invoicepdf.php?mod='.encode('g_invoice').'&token='.rawurlencode($_GET['invoice_no']).'&ext='.md5('php').'&n=1&p=1';?>';
				window.open(url_ori, '_blank');
				
		
			return false;
		});*/
	
	
	});


//[kinjal] on 19-11-2016
function change_qty_per_kg(inv_color_id,n,name,invoice_status)
{
    //var name1 = "'"+name+"'";
 
    var value=$(name).val();
   
	   //alert(value);	
	  // alert(invoice_status);	
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=change_qty_per_kg', '',1);?>");
	$.ajax({
			method: "POST",					
			url: url,
			data : {inv_color_id:inv_color_id,value:value,n:n,invoice_status:invoice_status},
			success: function(response)
			{
				//console.log(response);
				 set_alert_message('Invoice Record successfully updated ','alert-success','fa fa-check');
				location.reload();
			
			},
			error: function(){
					return false;	
			}
		});
	
}

 


function print() {
	
    var divToPrint=document.getElementById('print_div1');

      var newWin=window.open('','Print-Window');
    
      newWin.document.open();
    
      newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
    
      newWin.document.close();
}
</script>	
<!-- Close : validation script -->