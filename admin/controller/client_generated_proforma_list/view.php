<?php
// mansi
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

$proforma_new=$obj_pro_invoice->getProforma(decode($_GET['proforma_id']));
if(isset($_GET['proforma_id']) && !empty($_GET['proforma_id'])){
		$proforma_id = base64_decode($_GET['proforma_id']);
		$proforma=$obj_pro_invoice->getProformaData($proforma_id);
		$click = 1;
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}
if(isset($_POST['btn_sendemail']))
{
		$url = HTTP_SERVER.'proforma_invoice/pdf/proformapdf.php?mod='.encode('proformainvoice').'&token='.$_GET['proforma_id'].'&ext='.md5('php');
		$obj_pro_invoice->sendInvoiceEmail(decode($_GET['proforma_id']),$_POST['smail'],$url);
}
	$proformaid=decode($_GET['proforma_id']); 
?>
<section id="content">
    <section class="main padder">
   		<div class="clearfix">
    		<h4><i class="fa fa-edit"></i> <?php echo $display_name;?></h4>
    	</div>
    	<div class="row">
    		<div class="col-lg-12"><?php include("common/breadcrumb.php"); ?>	</div> 
		    <div class="col-sm-8" style="width:75%">
    			<section class="panel">  
    				<header class="panel-heading bg-white">
    					<span>Proforma Invoice Detail</span>
                        <?php if(isset($proforma) && ($proforma['proforma_status'] != '1') && $proforma_new['status'] == '1') { ?>
    					<span class="text-muted m-l-small pull-right">
    						<a class="label bg-info " onclick="test();" href="javascript:void(0);"><i class="fa fa-print" ></i> Print</a>
                           <a class="label bg-info pdfcls" href="javascript:void(0);"><i class="fa fa-print"></i> PDF</a>
                           <a class="label bg-success" href="javascript:void(0);" onclick="wordlink('<?php echo rawurlencode($proforma_id);?>')"><i class="fa fa-print"></i> Doc</a>

						  <!--  <a class="label bg-primary sendmailcls" href="javascript:void(0);"><i class="fa fa-envelope"></i> Send Mail</a>-->
    					</span>
                        <?php }?>
    				</header>
      
    				<div class="panel-body">
    					<span class="text-muted m-l-small pull-right"></span>
                    		<form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
    				
                                <div>	 
                                    <div class="form-group">
                                        <h1>PROFORMA INVOICE</h1>
                                    </div>
                                </div>
    				
                      			<div class="panel-body font_medium"  id="print_div" style="font-size: 25px;">
                     
                        <?php
                        $html = $obj_pro_invoice->viewProformaInvoice($proforma['proforma_id']); 
                        echo $html;
                        ?> 
                       
                        </div>   					
               
                                 <div class="form-group">
                                    <div class="col-lg-9 col-lg-offset-3">
                                    <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '','',1);?>">Cancel</a>
                                    </div>
                                </div>
            
             				 </form>
					</div>
    			</section>    
		</section>
<!-- Modale -->

<!--<div class="modal fade" id="smail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="sform" id="sform" style="margin-bottom:0px;">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <input type="hidden" name="sscurrency" id="sscurrency" value="" />
                <input type="hidden" name="sscurrencyrate" id="sscurrencyrate" value="" />
                <h4 class="modal-title" id="myModalLabel">Send Email</h4>
              </div>
              <div class="modal-body" style="height:65px;">
                   <div class="form-group">
                        <label class="col-lg-3 control-label">Email</label>
                        <div class="col-lg-8">
                             <input type="text" name="smail" placeholder="Email" value="" class="form-control validate[required,custom[email]]">
                        </div>
                     </div> 
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="submit" name="btn_sendemail" class="btn btn-primary btn-sm">Send</button>
              </div>
   		</form>   
    </div>
  </div>
</div>
-->
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
th, td { border-width: 1px; padding: 0.5em; position: relative; text-align: left;}
th, td { border-radius: 0.25em; border-style: solid; }
th { background: #EEE; border-color: #BBB; }
td { border-color: #DDD; }
</style>
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

<script>
 jQuery(document).ready(function(){
        
        /*jQuery("#sform").validationEngine();
		$(".sendmailcls").click(function(){
			$(".note-error").remove();
			$("#smail").modal('show');			
			return false;
		});*/	
		$(".pdfcls").click(function(){			
			//alert("pdf");
				$(".note-error").remove();
				var url = '<?php echo HTTP_SERVER.'pdf/client_proforma_pdf.php?mod='.encode('proformainvoice').'&token='.rawurlencode($_GET['proforma_id']).'&ext='.md5('php');?>';					
				window.open(url, '_blank');
			return false;
		});		
	});
function wordlink(id){
		var url = '<?php echo HTTP_SERVER.'word/client_proforma_word.php?mod='.encode('client_proforma_word').'&ext='.md5('php');?>&token='+id;
		window.open(url, '_blank');
	return false;
}
function test() {
	 var html="<html>";
	
html+='<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/  media="print"><link rel="stylesheet" href="<?php echo HTTP_SERVER;?>css/font.css"><link rel="stylesheet" href="<?php echo HTTP_SERVER;?>css/app.v2.css" type="text/css" /><link rel="stylesheet" href="<?php echo HTTP_SERVER;?>css/custom.css">';
    html+= $('#print_div').html();
    html+="<style>.col-lg-3 {width: 15%;}#client {    border-left: 6px solid #0087c3;    float: left;    padding-left: 6px;}h1 {	background:#333;    border-bottom: 1px solid #5d6975;    border-top: 1px solid #5d6975;    color: #FFF;    font-size: 2.4em;    font-weight: normal;    line-height: 1.4em;    margin: 0 0 20px;    text-align: center;}article, article address, table.meta, table.inventory { margin: 0 0 3em; }table.meta, table.balance { float: right; width: 50%; }table.meta:after, table.balance:after { clear: both; display: table; }table.meta th { width: 40%; }table.meta td { width: 60%; }table { font-size: 75%; table-layout: fixed; width: 100%; }table { border-collapse: separate; border-spacing: 2px; }th, td { border-width: 1px; padding: 0.5em; position: relative; text-align: left; }th, td { border-radius: 0.25em; border-style: solid; }th { background: #EEE; border-color: #BBB; }td { border-color: #DDD; }</style></html>";	//alert(html);
    var printWin = window.open('','','');
    printWin.document.write(html);
    printWin.document.close();
    printWin.focus();
    printWin.print();
    printWin.close();
}
</script>	
<!-- Close : validation script -->
