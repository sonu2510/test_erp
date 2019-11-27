<?php
include("mode_setting.php");
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

$limit = LISTING_LIMIT;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}

//$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
//$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];

if($display_status){

/*if(isset($_POST['btn_pro'])){
		$post = post($_POST);
		//printr($post);
		//die;
		$Sample_view=$obj_sample_sheet->getSampleView($post);
		printr($Sample_view);
		//$data = json_encode($post);
		//$url = urlencode($data);
		//page_redirect($obj_general->link($rout, 'mod=view&user_info='.$url, '',1));
}
*/

if(isset($_GET['sample_id']) && !empty($_GET['sample_id'])){
	$sample_view = base64_decode($_GET['sample_id']);
	//$post = post($_POST);
	$sample_detail = $obj_sample_sheet->getSampleView($sample_view);
	//printr($sample_view);
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
                 
                <?php /*?> <input type="text" id="sample_data"  name ="sample_data" value='<?php echo json_encode($post);?>' /><?php */?>
                 
                  <?php 
					$html = $obj_sample_sheet->sample_view_detail($sample_detail);
					echo $html;
			 		 ?>
                 
               		<?php /*?><div class='form-group'>
						<div class='table-responsive'>
					
					    <table class='table table-striped b-t text-small' id='stock_order' >
								<thead>
									<tr>
                                         <!-- <th>Sr No</th>-->
                                          <th>Customer Name</th>
                                          <th>Customer Visit Date</th>
                                          <th>Customer Address</th>
                                          <th> Customer Requirements</th>
                                          <th> Customer Product</th>
                                          <th>Weight of Product in each bag</th>
                                          <th>Total no of bag</th>
                                          <th>Sample Name given to Customer</th>
                                          <th>Who attend Customer?</th>
                                          <th>Result of Meeting</th>
                                          <th>Follow Up-1 Date</th>
                                          <th>Follow Up-1 Description</th>
                                          <th>Follow Up-2 Date</th>
                                          <th>Follow Up-2 Description</th>
                                          <th>Follow Up-3 Date</th>
                                          <th>Follow Up-3 Description</th>
                                          <th>Deal Closed</th>
                                          
                                    </tr>
                                </thead> 
                                
                                <tbody>
                                 <td> <?php echo $sample_detail['customer_name']; ?></td>
                                 <td> <?php echo $sample_detail['customer_visit_date']; ?></td>
                                 <td> <?php echo $sample_detail['customer_address']; ?></td>
                                 <td> <?php echo $sample_detail['customer_requirements']; ?></td>
                                 <td> <?php echo $sample_detail['customer_Product']; ?></td>
                                 <td> <?php echo $sample_detail['weight']; ?></td>
                                 <td> <?php echo $sample_detail['total_bag']; ?></td>
                                 <td> <?php echo $sample_detail['sample_name']; ?></td>
                                 <td> <?php echo $sample_detail['attend_customer']; ?></td>
                                 <td> <?php echo $sample_detail['result']; ?></td>
                                 <td> <?php echo $sample_detail['f1_date']; ?></td>
                                 <td> <?php echo $sample_detail['f1_description']; ?></td>
                                 <td> <?php echo $sample_detail['f2_date']; ?></td>
                                 <td> <?php echo $sample_detail['f2_description']; ?></td>
                                 <td> <?php echo $sample_detail['f3_date']; ?></td>
                                 <td> <?php echo $sample_detail['f3_description']; ?></td>
                                 <td> <?php echo $sample_detail['deal']; ?></td>
                                
                                 
                                </tbody>
                          </table>
                        </div>
                      </div><?php */?>          
              <?php 
			  	/*$html = $obj_sample_sheet->view_stock_report($stock_order_details,$post );
				echo $html;*/
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
#stock_order tbody tr{cursor: pointer; }
</style>

<script src="<?php echo HTTP_SERVER;?>js/lightbox/js/lightbox.min.js"></script>
<link href="<?php echo HTTP_SERVER;?>js/lightbox/css/lightbox.css" rel="stylesheet" />

<script type="application/javascript">

	$("#excel_link").click(function(){
	
	var sample_id= '<?php echo decode($_GET['sample_id']); ?>';
	var add_product_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=sample_data', '',1);?>");
	//var post_arr = $('#sample_data').val();
	 $.ajax({
        url: add_product_url, // the url of the php file that will generate the excel file
       	data : {sample_id:sample_id},
		method : 'post',
        success: function(response){
		//alert(response);
			excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(response);
			 $('<a></a>').attr({
							'id':'downloadFile',
							'download': 'sample-report.xls',
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
    $('#stock_order tbody tr').click(function(){
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