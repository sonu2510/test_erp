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
	'text' 	=> $display_name,
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
		$data = $obj_production_report->get_all_operator_reports($post);

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
         <div class="col-sm-12">
            <section class="panel">  
            
                <header class="panel-heading bg-white">
                 <span>Job Report</span>
               
                 <span class="text-muted m-l-small pull-right">
                 		<a class="label bg-inverse " onclick="test();" href="javascript:void(0);"><i class="fa fa-print" ></i> Print</a>
                 		 <a class="label bg-success" href="javascript:void(0);" id="excel_link"><i class="fa fa-print"></i> Excel</a>
                 		 <a class="label bg-info pdfcls" href="javascript:void(0);"><i class="fa fa-print"></i> PDF</a>
                 		  
                 </span>
                </header>
              
              <div class="panel-body">
              	<label class="label bg-white m-l-mini" >&nbsp;</label>
                	
                 <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
                
                       <input type="hidden" id="production_data"  name ="production_data" value='<?php echo json_encode($_POST);?>' />
                       <div> 
                 	 
                     <div id="print_div_details">
		                      <?php 
								
								echo $data;
							 ?>                       
					</div>
                  <div class="form-group">
                    <div class="col-lg-12">
                    	<div id="results_box"></div>
                         <div id="pagination_controls"></div>
                        </div>
                    </div>
                    
                   
                  </form>

                </div>
               
              </section>  
              </div>  
           </div>
      </div>

  </section>
</section>


<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
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
#data {
    border: 1px solid black;
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


</style>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="jspdf/libs/sprintf.js"></script>
<script type="text/javascript" src="jspdf/jspdf.js"></script>
<script type="text/javascript" src="jspdf/libs/base64.js"></script>

<script type="application/javascript">

$("#excel_link").click(function(){
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=production_data', '',1);?>");
	var post_arr = $('#production_data').val();
	 $.ajax({
        url: url, // the url of the php file that will generate the excel file
       	data : {post_arr : post_arr},
		method : 'post',
        success: function(response){
			excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(response);
			 $('<a></a>').attr({
							'id':'downloadFile',
							'download': 'production-report.xls',
							'href': excelData,
							'target': '_blank'
					}).appendTo('body');
					$('#downloadFile').ready(function() {
						$('#downloadFile').get(0).click();
					});
        }
		
    });


});	


function test() {

	 var html="<html>";

	



    html+= $('#print_div_details').html();

    html+="</html>";	//alert(html);

    var printWin = window.open('','','');

    printWin.document.write(html);

    printWin.document.close();

    printWin.focus();

    printWin.print();

    printWin.close();

}


</script>           


<?php } else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>