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
      <div class="col-sm-8" style="width:75%">
            <section class="panel">  
                <header class="panel-heading bg-white">
                     <span>Invoice Detail</span>
                    <center><a class="label bg-info " onclick="downloadPdf('<?php echo $_GET['invoice_no'];?>',<?php echo $_GET['inv_status'];?>,<?php echo $_GET['status'];?>);" href="javascript:void(0);"><i class="fa fa-print" ></i> If you not able to download so plz click on this button</a></center>
                 <span class="text-muted m-l-small pull-right">
                      <a class="label bg-info " onclick="test();" href="javascript:void(0);"><i class="fa fa-print" ></i> Print</a>
                     <a class="label bg-success" href="javascript:void(0);" id="excel_link"><i class="fa fa-print"></i> Excel</a>
                     <a class="label bg-info pdfcls" href="javascript:void(0);"><i class="fa fa-print"></i> PDF</a>
                 </span>
              </header>
              <div class="panel-body">
              	<!--label class="label bg-white m-l-mini">&nbsp;</label-->
                	<span class="text-muted m-l-small pull-right">
                    	 <b></b>
                    </span>
                    <div >
                 <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
                     
						<div >	 
                              <div class="form-group">
                              <?php if($_GET['status']==2)
							  {?>
                              		<h1>OUT</h1>
                              <?php }else{?>
                             		 <h1>IN</h1>
                              <?php }?>
							  </div>
                              </div>
                              
            <div class="panel-body" id="print_div_detail">
                <div class="">
                     <div class="form-group" id="in_out" style="width:600px; font-size:18px"> 
                    <style>@font-face {font-family: IDAutomationHC39M;src: url("<?php echo HTTP_SERVER.'css/fonts/IDAutomationHC39M.ttf';?>");font-size:8px;}
                .barcode{ font-family: IDAutomationHC39M;src:("<?php echo HTTP_SERVER.'css/fonts/IDAutomationHC39M.ttf';?>");font-size:8px;}
                .table,#sub_table {font-size:14px; }</style>
              


                 <?php 

                      $html=$obj_invoice->viewbarcode();
                			
                       echo $html;
                		?>
                     </div>
                    </div>
             </div>  
             <div class="form-group">
                 <div class="col-lg-9 col-lg-offset-3"> 
                           <a class="btn btn-default" href="<?php echo $obj_general->link($rout, 'mod=view&status=1&invoice_no='.$_GET['invoice_no'].'&inv_status='.$_GET['inv_status'], '',1);?>">Cancel</a>
                 </div>
               </div>   
             </form>
            </div>         
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
<script>
 jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#sform").validationEngine();	

		$(".pdfcls").click(function(){			
			
				$(".note-error").remove();
				var url = '<?php echo HTTP_SERVER.'pdf/inoutpdf_test.php?mod='.encode('inout_test').'&token='.rawurlencode($_GET['invoice_no']).'&status='.rawurlencode($_GET['status']).'&ext='.md5('php').'&p_status=2';?>';
				window.open(url, '_blank');
			return false;
		});
	});
	var he=0;
	var page=1
	jQuery(window).load(function () {
		  jQuery("#print_div").children().each(function(n, i) {
    if($(this).height()>23)
	  he = he+$(this).height(); 
	   if(he>800)
	   {
		   page++;
		   var id =this.id;
		   	$("#"+id).before('<br style="page-break-before:always" >Page - '+page);
		he=0;
	   }
	  });
});
function te()
{
	 $("#in_out").wordExport();
}

function test() {
	//alert( $('#print_div').html());
    var html="<html>";	
html+='<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/  media="print"><link rel="stylesheet" href="<?php echo HTTP_SERVER;?>css/font.css"><link rel="stylesheet" href="<?php echo HTTP_SERVER;?>css/app.v2.css" type="text/css" /><link rel="stylesheet" href="<?php echo HTTP_SERVER;?>css/custom.css">';
 //   html+="<link rel='Stylesheet' type='text/css' href='css/print.css' media='print' />";
    html+= $('#in_out').html();
    html+="<style>.col-lg-3 {width: 15%;}#client {    border-left: 6px solid #0087c3;    float: left;    padding-left: 6px;}h1 {	background:#333;    border-bottom: 1px solid #5d6975;    border-top: 1px solid #5d6975;    color: #FFF;    font-size: 2.4em;    font-weight: normal;    line-height: 1.4em;    margin: 0 0 20px;    text-align: center;}article, article address, table.meta, table.inventory { margin: 0 0 3em; }table.meta, table.balance { float: right; width: 50%; }. table.meta:after, table.balance:after { clear: both; display: table; }table.meta th { width: 40%; }table.meta td { width: 60%; }table { font-size: 75%; table-layout: fixed; width: 100%; }table { border-collapse: separate; border-spacing: 2px; }th, td { border-width: 1px; padding: 0.5em; position: relative; text-align: left; }th, td { border-radius: 0.25em; border-style: solid; }th { background: #EEE; border-color: #BBB; }td { border-color: #DDD; }#in_out .in_out {	float:left;width:100px;}</style></html>";	//alert(html);
    //console.log(html);
    var printWin = window.open('','','');
    printWin.document.write(html);
    printWin.document.close();
    printWin.focus();
    printWin.print();
    printWin.close();
    var divToPrint=document.getElementById('in_out');

      var newWin=window.open('','Print-Window');
    
      newWin.document.open();
    
      newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
    
      newWin.document.close();
    
   //   setTimeout(function(){newWin.close();},10);
}

$("#excel_link").click(function(){
    var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=barcode_detail', '',1);?>");
    $.ajax({
        url: url, 
        data : {},
        method : 'post',
        success: function(response){
            excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(response);
             $('<a></a>').attr({
                            'id':'downloadFile',
                            'download': 'Canada barcode.xls',
                            'href': excelData,
                            'target': '_blank'
                    }).appendTo('body');
                    $('#downloadFile').ready(function() {
                        $('#downloadFile').get(0).click();
                    });
        }
    });
}); 
function downloadPdf()
{
	window.location  ='<?php echo HTTP_SERVER;?>admin/index.php?route=invoice_test&mod=downPdf&invoice_id=0&status=3&inv_status=0';
	//console.log(url);
//	window.open(url, '');
}
</script>	
<!-- Close : validation script -->