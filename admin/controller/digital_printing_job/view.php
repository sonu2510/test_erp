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
	'text' 	=> 'View Job Detail',
	'href' 	=> '',
	'icon' 	=> 'fa-edit',
	'class'	=> 'active',
);
if(isset($_GET['digital_printing_id']) && !empty($_GET['digital_printing_id'])){
	$digital_printing_id = base64_decode($_GET['digital_printing_id']);
	$job_detail = $obj_digital_printing->getJobDetail($digital_printing_id);
	//printr($job_detail);	
	
//	printr($roll_code);
}
if($display_status){
	$data = $obj_digital_printing->viewdigital_printing_report($digital_printing_id);
	//echo $data ;die;

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
            	
                <header class="panel-heading bg-white">
                 <span>Lamination Detail</span>
               
                 <span class="text-muted m-l-small pull-right">
                 		  <a class="label bg-success" href="javascript:void(0);" id="excel_link"><i class="fa fa-print"></i> Excel</a>
                         
                 </span>
                </header>
              
              <div class="panel-body">
              	<label class="label bg-white m-l-mini" >&nbsp;</label>
                	
                 <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
                
                       <input type="hidden" id="production_data"  name ="production_data" value='<?php echo json_encode($_POST);?>' />
                      <?php 
						
						echo $data;
					 ?>                       
				
                  <div class="form-group">
                    <div class="col-lg-12">
                    	<div id="results_box"></div>
                         <div id="pagination_controls"></div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                       <div class="col-lg-9 col-lg-offset-3">             
                          <a class="btn btn-default" href="<?php echo $obj_general->link($rout,'', '',1);?>">Cancel</a>
                       </div>
                    </div>
                  </form>
                </div>
              </section>    
           </div>
      </div>
  </section>
</section>
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<style>
	#first {
		font-size:14px;
	}
	#lamination{
		font-size:24px;
	}
	#enquiry tr{
		border-color:#000;
	}
	
	#lamination_report tbody tr, #enquiry tr{cursor: pointer; }
</style>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

<script type="application/javascript">

$("#excel_link").click(function(){
	var lamination_id = <?php echo $digital_printing_id?>;
//	alert(lamination_id);
	
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=viewdigital_printing_report', '',1);?>");
	
	 $.ajax({
        url: url, // the url of the php file that will generate the excel file
       	data : {lamination_id : lamination_id},
		method : 'post',
        success: function(response){
			excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(response);
			 $('<a></a>').attr({
							'id':'downloadFile',
							'download': 'DigitalPrintng-report.xls',
							'href': excelData,
							'target': '_blank'
					}).appendTo('body');
					$('#downloadFile').ready(function() {
						$('#downloadFile').get(0).click();
					});
        }
		
    });


});	

$(document).ready(function(){
    $('#enquiry_report tbody tr,#enquiry tr').click(function(){
		var url = decodeURIComponent($(this).attr('href'));
        window.location = url;
       return false;
    });
});
</script>   

<?php } else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>       