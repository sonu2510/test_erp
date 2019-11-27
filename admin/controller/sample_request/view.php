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
if(isset($_GET['request_id']) && !empty($_GET['request_id'])){
	$request_id = base64_decode($_GET['request_id']);
	$data = $obj_sample->getRequest($request_id);
	if($data['invoice_status']!='1'){
	    $data1 = $obj_sample->viewsampledata($request_id);
	}else{
	    $data1 = $obj_sample->viewsampledataOther($request_id);
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
        			<header class="panel-heading bg-white"> <?php echo $display_name;?> Detail
        			 <span class="text-muted m-l-small pull-right">
                 		<a class="label bg-inverse " onclick="test();" href="javascript:void(0);"><i class="fa fa-print" ></i> Print</a>]
                 		  <a class="label bg-info pdfcls" href="javascript:void(0);"><i class="fa fa-print"></i> PDF</a>
                 	
                 </span></header>
                 <div class="table-responsive">
        			<div class="panel-body form-horizontal" id="print_div_details">   
        			
        			<?php echo $data1;?>
                </div>
                </div>
        				<div class="form-group">
        					<div class="col-lg-9 col-lg-offset-3">                
          						<a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a>
        					</div>
        				</div>
        
        			</div>
        		</section>
        	</div>
        </div>
    </section>
</section>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

<script type="application/javascript">
function test() {
	/*//alert( $('#print_div').html());
    var html="<html>";	
html+='<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/  media="print"><link rel="stylesheet" href="<?php echo HTTP_SERVER;?>css/font.css"><link rel="stylesheet" href="<?php echo HTTP_SERVER;?>css/app.v2.css" type="text/css" /><link rel="stylesheet" href="<?php echo HTTP_SERVER;?>css/custom.css">';
 //   html+="<link rel='Stylesheet' type='text/css' href='css/print.css' media='print' />";
    html+= $('#print_div').html();
    html+="<style>.col-lg-3 {width: 15%;}#client {    border-left: 6px solid #0087c3;    float: left;    padding-left: 6px;}h1 {	background:#333;    border-bottom: 1px solid #5d6975;    border-top: 1px solid #5d6975;    color: #FFF;    font-size: 2.4em;    font-weight: normal;    line-height: 1.4em;    margin: 0 0 20px;    text-align: center;}article, article address, table.meta, table.inventory { margin: 0 0 3em; }table.meta, table.balance { float: right; width: 50%; }. table.meta:after, table.balance:after { clear: both; display: table; }table.meta th { width: 40%; }table.meta td { width: 60%; }table { font-size: 75%; table-layout: fixed; width: 100%; }table { border-collapse: separate; border-spacing: 2px; }th, td { border-width: 1px; padding: 0.5em; position: relative; text-align: left; }th, td { border-radius: 0.25em; border-style: solid; }th { background: #EEE; border-color: #BBB; }td { border-color: #DDD; }#in_out .in_out {	float:left;width:100px;}</style></html>";	//alert(html);
    console.log(html);
    var printWin = window.open('','','');
    printWin.document.write(html);
    printWin.document.close();
    printWin.focus();
    printWin.print();
    printWin.close();*/
    var divToPrint=document.getElementById('print_div_details');

      var newWin=window.open('','Print-Window');
    
      newWin.document.open();
    
      newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
    
      newWin.document.close();
    
   //   setTimeout(function(){newWin.close();},10);
}

	$(".pdfcls").click(function(){			
			
				$(".note-error").remove();
				var url_ori = '<?php echo HTTP_SERVER.'pdf/sample_view_pdf.php?mod='.encode('sample_view').'&token='.rawurlencode($_GET['request_id']).'&ext='.md5('php').'&n=1&p=1';?>';
				
				window.open(url_ori, '_blank');
				
			//	window.open(url_dup, '_blank');
			//	window.open(url_tri, '_blank'); 
			return false;
		});
</script>
