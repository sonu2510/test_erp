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
	'href' 	=> $obj_general->link($rout, 'mod=index&is_delete='.$_GET['is_delete'].$add_url, '',1),
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
				$csv_data = $obj_invoice->getCSVData($handle,$charge);
				
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
			if ($_FILES['file']['size'] > 0) { 
				//get the csv file 
				$file = $_FILES['file']['tmp_name']; 
				$handle = fopen($file,"r"); 
				$charge = fopen($file,"r"); 
				$invoice_data = $obj_invoice->InsertCSVData($handle,$charge);
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
      <h4><i class="fa fa-edit"></i> Invoice</h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>
        <div class="col-sm-12">
        	<section class="panel">
	          <header class="panel-heading bg-white">Import Invoice </header>
    	      <div class="panel-body">
        	    <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
            		<div class="form-group">
                  	 <label class="col-lg-5 control-label"><b style="color:#ff0000">Before Importing Please Verify The CSV File.</b></label>
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
                   
                   <?php if($csv_data)
                    {?>
                       <div class="form-group">
                            <div class="col-lg-9 col-lg-offset-3">
                                <div class="table-responsive">
            		                <table class="table b-t text-small table-hover">
                    		          <thead>
                            		     <tr>
                        		            <th> Invoice No.</th>
                            		        <th>Mismatched Data</th>
                            		     </tr>
                            		   </thead>
	                 	                <tbody>
	                 	                    <?php foreach($csv_data as $key=>$csv)
	                 	                          { ?>
	                 	                               <tr>
                                        		            <td><?php echo $key;?></td>
                                            		        <td>
                                            		            <?php foreach($csv as $c)
                                            		                  {
                                            		                     echo $c.'<br>';   
                                            		                  } ?>      
                                            		        </td>
                                            		     </tr> 
	                 	                    <?php } ?>
	                 	              </tbody>
	                 	           </table>
	                 	         </div>
                            </div>    
                       </div>  
                 <?php } ?>
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