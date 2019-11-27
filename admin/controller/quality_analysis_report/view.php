<?php
include("mode_setting.php");
if(isset($_GET['product_id']) && !empty($_GET['product_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$product_id = base64_decode($_GET['product_id']);
		$product = $obj_quality_report->getProduct($product_id);
		//printr($product);
		
		//printr($product_id);die;
	}
} if(isset($_GET['size_id']) && !empty($_GET['size_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$size_id = base64_decode($_GET['size_id']);	
	}
} if(isset($_GET['category_id']) && !empty($_GET['category_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$category_id = base64_decode($_GET['category_id']);	
	}
}
//Start : bradcums
$bradcums = array();
$bradcums[] = array(
	'text' 	=> 'Dashboard',
	'href' 	=> $obj_general->link('dashboard', '', '',1),
	'icon' 	=> 'fa-home',
	'class'	=> '',
);
$bradcums[] = array(
	'text' 	=> 'Product Detail  ',
	'href' 	=> $obj_general->link($rout, '', '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);
$bradcums[] = array(
	'text' 	=> ' Product Size Detail ',
	'href' 	=> $obj_general->link($rout, 'mod=size_detail&product_id='.encode($product_id).'&size_id='.encode($size_id).'', '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);
$bradcums[] = array(
	'text' 	=> 'Color Category  Detail',
	'href' 	=> '',
	'icon' 	=> 'fa-edit',
	'class'	=> 'active',
);
//Close : bradcums
//Start : edit
$edit = '';

//printr($product_id);
//Close : edit
if($display_status){
	$qcreport = $obj_quality_report->getQcDetail(base64_decode($_GET['product_id']),base64_decode($_GET['size_id']),base64_decode($_GET['category_id']),base64_decode($_GET['color_id']));
?>

<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-edit"></i> Color Category List</h4>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <?php include("common/breadcrumb.php");?>
      </div>
      <div class="col-sm-11">
        <section class="panel">
          <header class="panel-heading bg-white"> Color Category  Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
          
              <div class="form-group">
                <label class="col-lg-5 control-label">Product Name</label>
                <div class="col-lg-7">
                  <label class="control-label normal-font"> <?php echo $product['product_name']; ?> </label>
                </div>
				<span class="text-muted m-l-small pull-right">
				 <a class="label bg-info " href="<?php echo $obj_general->link($rout, 'mod=add&qc_report_id='.encode($qcreport['qc_report_id']), '',1);?>"><i class="fa fa-print"></i> Edit</a>
				 <a class="label bg-info pdfcls" href="javascript:void(0);"><i class="fa fa-print"></i> PDF</a>
				 <a class="label bg-success" href="javascript:void(0);" onclick="wordlink('<?php echo rawurlencode(isset($qcreport['qc_report_id'])?$qcreport['qc_report_id']:'');?>')"><i class="fa fa-print"></i> Doc</a>
			   </span>
			  </div>
			  
			<div class="panel-body font_medium"  id="print_div" style="font-size: 25px;">
				<?php

					$html = $obj_quality_report->viewCOAreport($qcreport['qc_report_id']); 

					echo $html;

				?> 
			</div>
            </form>
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
 $(".pdfcls").click(function(){			
			$(".note-error").remove();

				var url = '<?php echo HTTP_SERVER.'pdf/qcpdf.php?mod='.encode('qcreport').'&token='.rawurlencode(isset($qcreport['qc_report_id'])?$qcreport['qc_report_id']:'').'&ext='.md5('php').'&num=0';?>';				

				window.open(url, '_blank');

			return false;

		});		

	});
function wordlink(id){

		var url = '<?php echo HTTP_SERVER.'word/qc_report.php?mod='.encode('qc_report').'&ext='.md5('php');?>&token='+id;

		window.open(url, '_blank');

		return false;

}
	</script>
<!-- Close : validation script -->
<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>
