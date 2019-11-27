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
	'text' 	=> $display_name.' Detail',
	'href' 	=> '',
	'icon' 	=> 'fa-edit',
	'class'	=> 'active',
);
//Close : bradcums
if($display_status){
if(isset($_POST['submit'])){
	if(isset($_FILES['file']['name']) && $_FILES['file']['name'] != '' && $_FILES['file']['error'] == 0){
		$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
		$path_parts = pathinfo($_FILES['file']['name']);			
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
				
				//printr($handle);
				$invoice_data = $obj_rack_master->InsertCSVData($handle);
			
				$obj_session->data['success'] = 'Invoice successfully Added!';
			}
			$filename = $_FILES['file']['name'];
			$filetemp = $_FILES['file']['tmp_name'];
			$path 	 = DIR_UPLOAD.'admin/india_stock/';
			$f_nm=$path_parts['filename'];
			if(file_exists(DIR_UPLOAD."admin/india_stock/".$_FILES['file']['name'])) 
			{
				@unlink("india_stock/$filename");
				
			}
			else
			{
				echo "not exists";
			}
			move_uploaded_file($filetemp,$path.$_FILES['file']['name']);
		}
	}
	else
	{
		$obj_session->data['warning'] = 'Please Upload CSV File!';
	}
		$invoice_data1 = $obj_rack_master->getInvoiceProduct_test(3291);
		printr($invoice_data1);
		printr('hii');
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
	          <header class="panel-heading bg-white">Import Price List</header>
    	      <div class="panel-body">
        	    <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
            		<div class="form-group">
                  	 <label class="col-lg-3 control-label">CSV</label>
        		      <input type="file" name="file" title="Upload CSV" class="btn btn-sm btn-info m-b-small " >
		            </div>
                    <div class="form-group"> 
                                        
                    
                        <div class="col-lg-9 col-lg-offset-3"> 
                        	<button type="submit" class="btn btn-primary" id="submit" name="submit">Submit</button> 
                        </div>
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
	   jQuery("#form").validationEngine();
	});
</script> 
<!-- Close : validation script -->
<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>