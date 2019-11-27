<?php
include("mode_setting.php");

$bradcums = array();
$bradcums[] = array(
	'text' 	=> 'Dashboard',
	'href' 	=> $obj_general->link('dashboard', '', '',1),
	'icon' 	=> 'fa-home',
	'class'	=> '',
);
$r=0;
if(isset($_GET['m']) && $_GET['m']=='1')
	$r='&mod=stock_report';
elseif(isset($_GET['m']) && $_GET['m']=='2')
	$r='&mod=pending_order_report';
	
$bradcums[] = array(
	'text' 	=> $display_name.' List',
	'href' 	=> $obj_general->link($rout, $r, '',1),
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
//printr($_POST);
$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];

//Close : edit

if($display_status){

if(isset($_POST['btn_pro'])){
		$post = post($_POST);
		if(isset($_GET['m']) && $_GET['m']=='2')
		{
			$stock_order_details=$obj_stock->GetCartOrderList($post);
		//	printr($stock_order_details);
		}
		else
		{
			$m=0;
			if(isset($_GET['m'])&& $_GET['m']=='1')
				$m=$_GET['m'];
			$stock_order_details=$obj_stock->getReport($post,$m);
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
            	
            <header class="panel-heading bg-white">
                 <span> Stock Order Report Detail </span>
                 <span class="text-muted m-l-small pull-right">
                 		 <a class="label bg-success" href="javascript:void(0);" id="excel_link"><i class="fa fa-print"></i> Excel</a>
                         
                 </span>
                 
              
            </header>
            <br />
             
             <div class='panel-body'>
              	 
                	
                 <form class='form-horizontal' method='post' name='form' id='form' enctype='multipart/form-data'>
               	<input type="hidden" id="stock_data"  name ="stock_data" value='<?php echo json_encode($post);?>' />
               	<input type="hidden" id="m"  name ="m" value='<?php echo isset($_GET['m'])?$_GET['m']:'0' ;?>' />
              <?php 
			  	if(isset($_GET['m']) && $_GET['m']=='1')
					$html = $obj_stock->stock_report($stock_order_details,$post );
				elseif(isset($_GET['m']) && $_GET['m']=='2')
				    $html=$obj_stock->pending_order_report($stock_order_details,$post);
				else
					$html = $obj_stock->view_stock_report($stock_order_details,$post );
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
                           <?php
                           $mod =''; 
                                if(isset($_GET['m']) && $_GET['m']=='1') 
                                    $mod= '&mod=stock_report';
                                if(isset($_GET['m']) && $_GET['m']=='2')
                                    $mod= '&mod=pending_order_report';?>             
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
<style>
#stock_order tbody tr{cursor: pointer; }
</style>

<script src="<?php echo HTTP_SERVER;?>js/lightbox/js/lightbox.min.js"></script>
<link href="<?php echo HTTP_SERVER;?>js/lightbox/css/lightbox.css" rel="stylesheet" />

<script type="application/javascript">

	$("#excel_link").click(function(){
	var add_product_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=stock_data', '',1);?>");
	var post_arr = $('#stock_data').val();
	var m = $('#m').val();
	
	 $.ajax({
        url: add_product_url, // the url of the php file that will generate the excel file
       	data : {post_arr : post_arr,m:m},
		method : 'post',
        success: function(response){
            //alert(response);
			excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(response);
			 $('<a></a>').attr({
							'id':'downloadFile',
							'download': 'stock-report.xls',
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
    var m = $('#m').val();
    if(m=='0')
    {
        $('#stock_order tbody tr').click(function(){
    		var url = decodeURIComponent($(this).attr('href'));
            window.location = url;
           return false;
        });
    }
});


</script>           
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>



<?php } else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>