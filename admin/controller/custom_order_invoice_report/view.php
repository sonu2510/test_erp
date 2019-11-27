<?php
include("mode_setting.php");
//include("index.php");
//$obj_quotation = new productQuotation;
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
//Close : bradcums

$limit = LISTING_LIMIT;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}

$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
//$allow_currency_status = $obj_enquiry->allowCurrencyStatus($user_type_id,$user_id);

//Start : edit
//echo $_GET['enquiry_id'];

/*$edit = '';

if(isset($_GET['enquiry_id']) && !empty($_GET['enquiry_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
		$enquiry_id = base64_decode($_GET['enquiry_id']);
		$enquiry_details = $obj_enquiry->getEnquiry($enquiry_id);
		
	}
}*/
//printr($enquiry_details);
//Close : edit
if($display_status){

if(isset($_POST['btn_pro'])){
		$post = post($_POST);
		$custom_order_details=$obj_custom->getReport1($post);
	   //printr($custom_order_details);
	   //die;
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
                 <span> Custom Order Report Detail </span>
                 <span class="text-muted m-l-small pull-right">
                 		 <a class="label bg-success" href="javascript:void(0);" id="excel_link"><i class="fa fa-print"></i> Excel</a>
                         
                 </span>
                 
              
            </header>
            <br />
            <?php /*?> <div> Searching Date From:  <b> <?php echo dateFormat(4,$post['f_date']); ?></b>   To: <b>  <?php echo dateFormat(4,$post['t_date']);?></b> </div>
             <?php */?>
             <div class='panel-body'>
              	 
                	
                 <form class='form-horizontal' method='post' name='form' id='form' enctype='multipart/form-data'>
               	<input type="hidden" id="custom_data"  name ="custom_data" value='<?php echo json_encode($post);?>' />
              <?php 
			  	$html = $obj_custom->view_custom_report($custom_order_details,$post );
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
<style>
#custom_order tbody tr{cursor: pointer; }
</style>
<script src="<?php echo HTTP_SERVER;?>js/lightbox/js/lightbox.min.js"></script>
<link href="<?php echo HTTP_SERVER;?>js/lightbox/css/lightbox.css" rel="stylesheet" />

<script type="application/javascript">

	$("#excel_link").click(function(){
	var add_product_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=custom_data', '',1);?>");
	var post_arr = $('#custom_data').val();
	 $.ajax({
        url: add_product_url, // the url of the php file that will generate the excel file
       	data : {post_arr : post_arr},
		method : 'post',
        success: function(response){
		//alert(response);
			excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(response);
			 $('<a></a>').attr({
							'id':'downloadFile',
							'download': 'custom-report.xls',
							'href': excelData,
							'target': '_blank'
					}).appendTo('body');
					$('#downloadFile').ready(function() {
						$('#downloadFile').get(0).click();
					});
        }
		
    });
});	

/*$(document).ready(function(){
    $('#custom_order tbody tr').click(function(){
		var url = decodeURIComponent($(this).attr('href'));
        window.location = url;
       return false;
    });
});*/

</script>           
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>



<?php } else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>
