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
	'text' 	=> $display_name.' List ',
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
//Close : bradcums
//Start : edit
$edit = '';
if(isset($_GET ['country_id']) && !empty($_GET['country_id'])){
	if(!$obj_general->hasPermission('edit',$menuId)){
		$display_status = false;
	}else{
	   $country_id = base64_decode($_GET['country_id']);
		
		$country = $obj_councharge->getcountry_name($country_id);
		$edit = 1;
		//printr($country);
	}
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
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
      <div class="col-sm-11">
        <section class="panel">
          <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
              <?php if($edit==1 && !empty($edit)){ ?>
                  <div class="form-group">
                      <label class="col-lg-3 control-label">Country Name</label>
                      <div class="col-lg-9">
                         <label class="control-label normal-font">
                           <?php echo $country['country_name']; ?>
                         </label>   
                           <span class="pull-right"> <a class="btn btn-primary" href="<?php echo $obj_general->link($rout, 'mod=view&country_id='.encode($country['country_id']), '',1);?>" >  Add </a> </span>
                      </div>
                </div>
               <table border="0"  width="100%" class="table  b-t text-small">              	
               	<tr>
                	<td colspan="6">
              			<table class="tool-row table  b-t text-small" id="myTable">
                        <tr>
                                    <th>Agent Name</th>
                                    <th>Agent address</th>
                                    <th>E-Mail id</th>
                                    
                        </tr>
                        <tbody>
                        <?php
						 $country_data= $obj_councharge->getcountry_details($country_id);
						 if($country_data!='')
						 {
							 
                         // printr($country_data);
						 //  printr($country);
						 
							 foreach ($country_data as $country_data)
							 {
							
						 ?>
                           
                           <td><a href="<?php echo $obj_general->link($rout, 'mod=detail&Agent_id='.encode($country_data['Agent_id']).'&country_id='.encode($country['country_id']),'',1);?>"><?php echo $country_data['agent_name'];?></a></td> 
                         
                           <td><a href="<?php echo $obj_general->link($rout, 'mod=detail&Agent_id='.encode($country_data['Agent_id']).'&country_id='.encode($country['country_id']),'',1);?>"><?php echo $country_data['agent_address'];?></a></td> 
                           <td><a href="<?php echo $obj_general->link($rout, 'mod=detail&Agent_id='.encode($country_data['Agent_id']).'&country_id='.encode($country['country_id']),'',1);?>"><?php echo $country_data['email_id'] ;?></a></td> 
                            
                             </tbody>   
											
                      <?php 
								  }
							  }else
							  {
								  echo "no record found";
							  }
								
								 ?>
				
               </table> 
               </form>          
		</div>             
      </div>
     
   </section>
</section>
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>




<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>
