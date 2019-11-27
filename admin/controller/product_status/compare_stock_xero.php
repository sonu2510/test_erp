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

$address_id = '0';
$add_url='';
if(isset($_GET['address_book_id']))
{
    $address_id = decode($_GET['address_book_id']);
    $add_url = '&address_book_id='.$_GET['address_book_id'];
}


$bradcums[] = array(
	'text' 	=> $display_name.' List',
	'href' 	=> $obj_general->link($rout, 'mod=index&is_delete=0'.$add_url, '',1),
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
if($display_status){
if(isset($_POST['report'])){
	if(isset($_FILES['file']['name']) && $_FILES['file']['name'] != '' && $_FILES['file']['error'] == 0){
		$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);		
		//echo $ext;die;
		if($ext != 'csv'){
			$obj_session->data['warning'] = 'Please Upload only CSV File!';
		}
		else
		{
			if ($_FILES['file']['size'] > 0) { 
				//get the csv file 
				$file = $_FILES['file']['tmp_name']; 
				$handle = fopen($file,"r"); 
				$charge = fopen($file,"r"); 
				$csv_data = $obj_productstatus->getCSVData($handle,$charge);
				
				if(empty($csv_data))
				  $obj_session->data['success'] = 'All Data Are Match With ERP System Thank You !!!';  
			}
		}
	} 
	else
	{
		$obj_session->data['warning'] = 'Please Upload CSV File!';
	}
}
if(isset($_POST['submit'])){
	if(isset($_FILES['file']['name']) && $_FILES['file']['name'] != '' && $_FILES['file']['error'] == 0){
		$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);		
		//echo $ext;die;
		if($ext != 'csv'){
			$obj_session->data['warning'] = 'Please Upload only CSV File!';
		}
		else
		{
		    $stock_data=array();
			if ($_FILES['file']['size'] > 0) { 
				//get the csv file 
				$file = $_FILES['file']['tmp_name']; 
				$handle = fopen($file,"r"); 
				$charge = fopen($file,"r"); 
				$stock_data = $obj_productstatus->GetCompareCSVData($handle,$charge);
				
			//	printr($stock_data);
					$obj_session->data['success'] = 'Invoice successfully Added!';
			}
		}
	}
	else
	{
		$obj_session->data['warning'] = 'Please Upload CSV File!';
	}
	


}
?>
<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-edit"></i> Compare Stock ERP</h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>
        <div class="col-sm-12">
        	<section class="panel">
	          <header class="panel-heading bg-white">Import XERO Stock </header>
    	      <div class="panel-body">
        	    <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
            		<div class="form-group">
                  	 <label class="col-lg-5 control-label"><b style="color:#ff0000">Please import  CSV File.</b></label>
		            </div>
            		
            		<div class="form-group">
                  	 <label class="col-lg-3 control-label">CSV</label>
        		      <input type="file" name="file" title="Upload CSV" class="btn btn-sm btn-info m-b-small " >
		            </div>
                    <div class="form-group"> 
                        <div class="col-lg-9 col-lg-offset-3">
	    
	    
                	    <?php if(!isset($_GET['status']))
                	          {?>
                	                <button type="submit" class="btn btn-primary" id="submit" name="submit">Submit</button> 
                        <?php }
                	          else
                	          {?>
                	                <button type="submit" class="btn btn-primary" id="report" name="report">Proceed</button> 
                	    <?php } ?>
                        </div>
                   </div>
              
	            </form>
	            
	            
    	     
            	         <div class="col-sm-12">
                        
                    	        <header class="panel-heading bg-white"><center><b><h3>Compare Stock ERP VS XERO </h3> </b></center>
                        	          <span class="text-muted m-l-small pull-right">
                                      <a class="label bg-success" href="javascript:void(0);" id="excel_link"><i class="fa fa-print"></i> Excel</a>
                                      </span>
                                  </header>  
                                   <?php if(!empty($stock_data))
                                    {?>
                                    
                                       <div class="form-group">
                                            <div class="col-lg-12">
                                                <div class="table-responsive">
                                                     <input type="hidden" id="post_data"  name ="post_data" value='<?php echo json_encode($stock_data);?>' />
                            		                <table class="table table-striped m-b-none text-small">
                                    		          <thead>
                                            		     <tr>
                                        		            <th>Product code</th>
                                            		        <th>product decription</th>
                                            		        <th>Xero Qty</th>
                                            		        <th>ERP Stock Qty</th>
                                            		        <th>Difference Qty</th>
                                            		     </tr>
                                            		   </thead>
                	                 	                <tbody>
                	                 	                    <?php 
                	                 	              
                	                 	                    foreach($stock_data as $key=>$csv)
                	                 	                          { 
                	                 	                          ?>
                	                 	                               <tr>
                                                        		            <td><?php echo $key;?></td>
                                                        		            <td><?php echo $csv[0]->pro_description;?></td>
                                                        		            <td><?php  echo $csv[0]->xero_qty;?></td>
                                                        		            <td><?php  echo $csv[0]->qty;?></td>
                                                        		            <td><?php  echo ($csv[0]->xero_qty-$csv[0]->qty);?></td>
                                                            		     </tr> 
                	                 	                    <?php } ?>
                	                 	              </tbody>
                	                 	           </table>
                	                 	         </div>
                                            </div>    
                                       </div>  
                                 <?php } ?>
                    
        	         </div>
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
	   jQuery("#form").validationEngine();
	});
	
	$("#excel_link").click(function(){
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=compare_stock', '',1);?>");
	var post_arr = $('#post_data').val();
	 $.ajax({
        url: url, // the url of the php file that will generate the excel file
       	data : {post_arr : post_arr},
		method : 'post',
        success: function(response){
         //   alert(response);
			excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(response);
			 $('<a></a>').attr({
							'id':'downloadFile',
							'download': 'Compare-stock-report.xls',
							'href': excelData,
							'target': '_blank'
					}).appendTo('body');
					$('#downloadFile').ready(function() {
						$('#downloadFile').get(0).click();
					});
        }
		
    });


});	
</script> 
<!-- Close : validation script -->
<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>