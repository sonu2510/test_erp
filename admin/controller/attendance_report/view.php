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
$mod='';
if(isset($_GET['p']))
	$mod ='&mod=person';
$bradcums[] = array(
	'text' 	=> $display_name.' List',
	'href' 	=> $obj_general->link($rout, $mod, '',1),
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

$limit = LISTING_LIMIT;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}

$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];

//Start : edit
$edit = '';

//Close : edit
if($display_status){
if(isset($_POST['btn_pro'])){
		$post = post($_POST);
		
		
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
            	
                <header class="panel-heading bg-white">
                 <span>Enquiry Detail</span>
               
                 <span class="text-muted m-l-small pull-right">
                 		 <a class="label bg-success" href="javascript:void(0);" id="excel_link"><i class="fa fa-print"></i> Excel</a>
                         
                 </span>
                </header>
              
              <div class="panel-body">
              	<label class="label bg-white m-l-mini">&nbsp;</label>
                	
                 <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
                
                       <input type="hidden" id="att_data"  name ="att_data" value='<?php echo json_encode($post);?>' />
					   <input type="hidden" id="p"  name ="p" value='<?php echo isset($_GET['p'])?$_GET['p']:'0';?>' />
                      <?php 
					  	if(isset($_GET['p']))
							$html =$obj_attendance->getAnnualAttendanceReport($post);
						else
							$html =$obj_attendance->getAttendanceReport($post);
						echo $html;
					 ?>                       
				
                  <div class="form-group">
                    <div class="col-lg-12">
                    	<div id="results_box"></div>
                         <div id="pagination_controls"></div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                       <div class="col-lg-9 col-lg-offset-3">             
                          <a class="btn btn-default" href="<?php echo $obj_general->link($rout,$mod, '',1);?>">Cancel</a>
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
	#enquiry_report tbody tr, #enquiry tr{cursor: pointer; }
	body {zoom : 80%;}
</style>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

<script type="application/javascript">

$("#excel_link").click(function(){
	var add_product_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=att_data', '',1);?>");
	var post_arr = $('#att_data').val();
	var p = $('#p').val();
	//alert(p);
	 $.ajax({
        url: add_product_url, // the url of the php file that will generate the excel file
       	data : {post_arr : post_arr,p:p},
		method : 'post',
        success: function(response){
			excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(response);
			 $('<a></a>').attr({
							'id':'downloadFile',
							'download': 'Attendance-report.xls',
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