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
if(isset($_GET['lamination_id']) && !empty($_GET['lamination_id'])){
	$lamination_id = base64_decode($_GET['lamination_id']);	
}

if($display_status){
	$data = $obj_lamination->viewlamination_report($lamination_id);
	
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
         <div class="col-sm-8" style="width:75%">
            <section class="panel">  
            	
                <header class="panel-heading bg-white">
                 <span>Lamination Detail</span>
               
                 <span class="text-muted m-l-small pull-right">
                 		  <a class="label bg-success" href="javascript:void(0);" id="excel_link"><i class="fa fa-print"></i> Excel</a>
						   <a class="label bg-info " onclick="test();" href="javascript:void(0);"><i class="fa fa-print" ></i> Print</a>
                         
                 </span>
                </header>
              
              <div class="panel-body">
              	<label class="label bg-white m-l-mini" >&nbsp;</label>
                	
                 <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
                 	 <div> 
                 	  <div class="form-group"><h1>LAMINATION REPORT</h1> </div></div>

                 <div id="print_div_details">
                       <input type="hidden" id="production_data"  name ="production_data" value='<?php echo json_encode($_POST);?>' />
                     
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

<script type="application/javascript">

$("#excel_link").click(function(){
	var lamination_id = <?php echo $lamination_id?>;
//	alert(lamination_id);
	
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=lamination_report', '',1);?>");
	
	 $.ajax({
        url: url, // the url of the php file that will generate the excel file
       	data : {lamination_id : lamination_id},
		method : 'post',
        success: function(response){
			excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(response);
			 $('<a></a>').attr({
							'id':'downloadFile',
							'download': 'lamination-report.xls',
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

	

 html+='<style>.col-lg-3 {width: 15%;}#client { border-left: 6px solid #0087c3;  float: left;   padding-left: 6px;}h1 {	background:#333;    border-bottom: 1px solid #5d6975;    border-top: 1px solid #5d6975;    color: #FFF;    font-size:  14px;    font-weight: normal;    line-height: 1.4em;    margin: 0 0 20px;    text-align: center;}article, article address, table.meta, table.inventory { margin: 0 0 3em; }table.meta, table.balance { float: right; width: 50%; }table.meta:after, table.balance:after { clear: both; content: ""; display: table; }table.meta th { width: 40%;  font-size:  14px; }table.meta td { width: 60%;   font-size:  14px; }table { font-size:  14px; table-layout: fixed; width: 100%; }table { border-collapse: separate; border-spacing: 1px; font-size:  14px;  }th, td { border-width: 1px;position: relative; text-align: left;  font-size:  14px; }th, td { border-radius: 0em; border-style: solid; font-size:  14px;}th { background: #EEE; border-color: #BBB; font-size:  14px;td { border-color: #DDD; font-size:  font-size:  14px;}.sign_td {	height:100px;}div #express{	margin:30px 40px;}</style>';

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