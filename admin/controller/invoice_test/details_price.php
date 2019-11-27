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
                             <a class="label bg-success excel_link" href="javascript:void(0);" ><i class="fa fa-print"></i> Excel</a>
                             <a class="label bg-info pdfcls" href="javascript:void(0);"><i class="fa fa-print"></i> PDF</a>
						</span>
                    </header>
                  <div class="panel-body">
                    <form name="form_list" id="form_list" method="post">
                    	<input type="hidden" id="action" name="action" value="" />
                        <div>
                        <?php $html =$obj_invoice->viewDetailsTest($invoice_no,1);
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
jQuery(document).ready(function(){
    $(".pdfcls").trigger('click');
});
$(".pdfcls").click(function(){	
    
    alert('testing');
				$(".note-error").remove();
				//alert(<?php //echo $_GET['invoice_no']?>);
				var url = '<?php echo HTTP_SERVER.'pdf/detailspdf.php?mod='.encode('details').'&token='.rawurlencode($_GET['invoice_no']).'&ext='.md5('php');?>';
			//	var url = '<?php echo HTTP_SERVER.'pdf/detailspdf.php?mod='.encode('details').'&token=MTc0Ng==&ext='.md5('php');?>';
				window.open(url, '_blank');
			return false;
		});




$("#excel_link").click(function(){
   var id=<?php echo $invoice_no_e;?>;
  
	var price = <?php echo $_GET['price'];?>;
//	alert(lamination_id);
	
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=packing_detail', '',1);?>");
	
	 $.ajax({
        url: url, // the url of the php file that will generate the excel file
       	data : {id : id,price:price},
		method : 'post',
        success: function(response){
			excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(response);
			 $('<a></a>').attr({
							'id':'downloadFile',
							'download': 'packing_detail.xls',
							'href': excelData,
							'target': '_blank'
					}).appendTo('body');
					$('#downloadFile').ready(function() {
						$('#downloadFile').get(0).click();
					});
        }
		
    });


});	
//var count = $('#tr').length;
//alert(count);
 
</script>