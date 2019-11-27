<?php
//rohit
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
	'href' 	=>  $obj_general->link($rout, 'mod=index&inv_status='.$_GET['inv_status'], '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);
$bradcums[] = array(
	'text' 	=> $display_name.' Details',
	'href' 	=> '',
	'icon' 	=> 'fa-edit',
	'class'	=> 'active',
);
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
                             <a class="label bg-success" href="javascript:void(0);" onclick="excelfile('<?php echo rawurlencode($_GET['invoice_no']);?>')"><i class="fa fa-print"></i> Doc</a>
                             <a class="label bg-info pdfcls" href="javascript:void(0);"><i class="fa fa-print"></i> PDF</a>
                             <a class="label bg-success" href="javascript:void(0);" id="excel_link"><i class="fa fa-print"></i> Excel</a>
                        <a class="label bg-info " href="javascript:void(0);" onclick="print()"><i class="fa fa-print" ></i> Print</a>
						</span>
                    </header>
                  <div class="panel-body" >
                    <form name="form_list" id="form_list" method="post">
                    	<input type="hidden" id="action" name="action" value="" />
                        <div id="print_div1">
                        <?php  $html =$obj_invoice->viewPalletSheet($invoice_no,1,$_GET['price']);
								echo $html;?>                    
                            </div>
                              <div class="form-group">
        					 <div class="col-lg-9 col-lg-offset-3"> 
        						<a class="btn btn-default" href="<?php echo $obj_general->link($rout, 'mod=view&status=1&invoice_no='.$_GET['invoice_no'].'&inv_status='.$_GET['inv_status'], '',1);?>">Cancel</a>
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
<style type="text/css">
.table{ border-bottom:hidden; border-style:hidden; 
/* border:hidden; border-style:hidden;*/
	
	}
</style>
<script type="application/javascript">

$(".pdfcls").click(function(){			
	$(".note-error").remove();
	var url = '<?php echo HTTP_SERVER.'pdf/palletdetailspdf.php?mod='.encode('palletdetails').'&token='.rawurlencode($_GET['invoice_no']).'&price='.$_GET['price'].'&ext='.md5('php');?>';
	window.open(url, '_blank');
	return false;
});
function excelfile(id){
		var url = '<?php echo HTTP_SERVER.'word/invoice_pallet.php?mod='.encode('invoice').'&price='.$_GET['price'].'&ext='.md5('php');?>&token='+id;
		window.open(url, '_blank');
	return false;
} 
 
function print() {

      var html="<html>";
	html+='<head>';
	
 //html+="<style>.col-lg-3 {width: 15%;}#client {    border-left: 6px solid #0087c3;    float: left;    padding-left: 6px;}h1 {  background:#333;    border-bottom: 1px solid #5d6975;    border-top: 1px solid #5d6975;    color: #FFF;    font-size: 2.4em;    font-weight: normal;    line-height: 1.4em;    margin: 0 0 20px;    text-align: center;}article, article address, table.meta, table.inventory { margin: 0 0 3em; }table.meta, table.balance { float: right; width: 50%; }table.meta:after, table.balance:after { clear: both; display: table; }table.meta th { width: 40%; }table.meta td { width: 60%; }table { font-size: 65%; table-layout: fixed; width: 100%; }table { border-collapse: separate; border-spacing: 0px; }th, td { border-width: 1px; padding: 0.5em; position: relative; text-align: left; }th, td { border-radius: 0; border-style: solid; }th { background: #EEE; border-color: #BBB; }td { border-color: #DDD; }</style></html>";
  
 html+="<style> table-responsive{font-size: 10%;}.m-t-large {margin-top: 20px;}.line-dashed {border-style: dashed;background: transparent;}.line {height: 2px;margin: 10px 0;font-size: 0;overflow: hidden;background-color: #fff;border-width: 0;border-top: 1px solid #e0e4e8;} .detail_table {font-size: 10%; }table.detail_table { font-size: 10%; table-layout: fixed; width: 100%; font-size:  9px;}table.detail_table { border-collapse: separate; border-spacing: 0px;font-size:  9px; } table.detail_table th, table.detail_table td { border-width: 1px; padding: 0; position: relative; text-align: left;font-size:  9px; } table.detail_table th, table.detail_table td { border-radius: 0; border-style: solid;font-size:  9px; }table.detail_table th { background: #EEE; border-color: #BBB;font-size:  9px; } table.detail_table td { border-color: #DDD; font-size:  9px;}.no_border { border-bottom: 0 none; border-radius: 0; border-top: 0 none !important; }</style></html>";
	



    html+= $('#print_div1').html();

    html+="</html>";	//alert(html);

    var printWin = window.open('','','');

    printWin.document.write(html);

    printWin.document.close();

    printWin.focus();

    printWin.print();

    printWin.close();
}
$("#excel_link").click(function(){
   var id=<?php echo $invoice_no;?>;
  
	

	
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=viewPalletSheet', '',1);?>");
	
	 $.ajax({
        url: url, // the url of the php file that will generate the excel file
       	data : {id : id},
		method : 'post',
        success: function(response){
			excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(response);
			 $('<a></a>').attr({
							'id':'downloadFile',
							'download': 'PalletSheet.xls',
							'href': excelData,
							'target': '_blank'
					}).appendTo('body');
					$('#downloadFile').ready(function() {
						$('#downloadFile').get(0).click();
					});
        }
		
    });


});	
</script>