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
	'href' 	=> $obj_general->link($rout, 'mod=daily_stock_register', '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);

if(isset($_GET['year']))
{
    $bradcums[] = array(
    	'text' 	=>'Year List',
    	'href' 	=> $obj_general->link($rout, 'mod=index_opening_balance', '',1),
    	'icon' 	=> 'fa-list',
    	'class'	=> '',
    );
}

if(isset($_GET['month']))
{
    $bradcums[] = array(
    	'text' 	=> 'Month List',
    	'href' 	=> $obj_general->link($rout, 'mod=index_opening_balance&year='.$_GET['year'], '',1),
    	'icon' 	=> 'fa-list',
    	'class'	=> '',
    );
}
 
$bradcums[] = array(
	'text' 	=> $display_name.' Detail',
	'href' 	=> '',
	'icon' 	=> 'fa-edit',
	'class'	=> 'active',
);
//Close : bradcums
$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];

$year='';
if((isset($_GET['year'])) && !(isset($_GET['month'])))
{
		$year = decode($_GET['year']);
}
if((isset($_GET['year'])) && (isset($_GET['month'])))
{
		$month = decode($_GET['month']);
		$year = decode($_GET['year']);
		
		//printr($month);
}

?>
<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-list"></i> <?php echo $display_name;?></h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>   
        
      <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading bg-white">
                 <span> Daily Stock Opening Balance</span>
                <span class="text-muted m-l-small pull-right">
                	<a class="label bg-primary" href="<?php echo $obj_general->link($rout, 'mod=opening_balance', '',1);?>"><i class="fa fa-plus"></i> Add Opening Balance </a> &nbsp;
                </span>
                </header>
          
          <div class="panel-body">
              <div class="table-responsive">
		                <table class="table b-t text-small table-hover">
        		          <thead>
                		    <tr><?php 
                		     if((isset($_GET['year'])) && !(isset($_GET['month'])))

                                    {
                		    ?>
		                         <th>Month</th>
		                         <th>Month Opening</th>
		                         <?php }
		                       else if((isset($_GET['year'])) && (isset($_GET['month'])))
                                    {
		                              echo '<th>Date</th>
		                                    <th>Quantity Manufactured</th>
		                                    <th>Quantity Manufactured For Roll</th>
		                                    <th>Quantity Manufactured For Scrap</th>
		                                    <th>Action</th>';
		                         }else{
		                             echo '<th>Year</th>';
		                         }?>
    		               </tr>
                		  </thead>
	                 	 <tbody>
	                 	     
	                 	     
	           
	                 	     <?php
	                 	     
	                 	     
	                 	       if((isset($_GET['year'])) && !(isset($_GET['month'])))
                                    {
                                        $data_month = $obj_invoice->getOpening_Balance($year);
                                        	if(!empty($data_month)){
            	                 	     	    foreach($data_month as $month){
            	                 	     	         $dt = strtotime($month['manufactured_date']);
                                    				$nmonth=date("F", $dt);
                                    				 $dt =(date("Y",$dt));?>
                                    			
                                    				<tr>
                                    				    <td><a href="<?php echo $obj_general->link($rout,'mod=index_opening_balance&year='.$_GET['year'].'&month='.encode($month['month']),'',1); ?>" ><?php echo $nmonth .' - '.$year;?></a></td>
                                    				    <td><a href="<?php echo $obj_general->link($rout,'mod=index_opening_balance&year='.$_GET['year'].'&month='.encode($month['month']),'',1); ?>"><?php echo $month['month_opening']?></a></td></tr>
            	                 	     	<?php    }
                                        	}
                                    }
                                    
                                    else  if((isset($_GET['year'])) && (isset($_GET['month'])))
                                            {
                                        
                                      
                                           $data_daily= $obj_invoice->getOpening_Balance($year,$month);
                                           
                                           
                                        	if(!empty($data_daily)){
            	                 	     	    foreach($data_daily as $daily){
            	                 	     	         $dt = strtotime($daily['manufactured_date']);
                                    				$nmonth=date("F", $dt);
                                    				 $dt =(date("Y",$dt));?>
                                    			
                                    				<tr>
                                    				    <td><?php echo dateFormat(4, $daily['manufactured_date']); ?></td>
                                    				    <td><?php echo $daily['quantity_manufactured']; ?></td>
                                    				    <td><?php echo $daily['quantity_manufactured_roll']; ?></td>
                                    				    <td><?php echo $daily['quantity_manufactured_scrap']; ?></td>
                                    				    <td><a  class="btn btn-info btn-xs" href="<?php echo $obj_general->link($rout,'mod=opening_balance&daily_stock_opening_balance_id='.encode($daily['daily_stock_opening_balance_id']),'',1); ?>" >Edit</a></td>
                                    				    </tr>
            	                 	     	<?php    }
                                        	}
                                      }
                                    
                                    
                                    else{
            	                 	     	$data = $obj_invoice->getOpening_Balance();
            	                 	     	if(!empty($data)){
            	                 	     	    foreach($data as $d){
            	                 	     	         $dt = strtotime($d['manufactured_date']);
                                    				$nmonth=date("F", $dt);
                                    				 $dt =(date("Y",$dt));
                                    			
                                    				 $date[]= $dt; 
                                    			
            	                 	     	   }
            	                 	     	  $arr= array_unique($date);
            	                 	     	  foreach($arr as $a){
            	                 	     	      ?>
            	                 	     	      <tr><td>
            	                 	     	          <a href="<?php echo $obj_general->link($rout,'mod=index_opening_balance&year='.encode($a),'',1); ?>" ><?php echo  $a;  ?></a>
            	                 	     	          </td></tr>
            	                 	     	      
            	                 	     	 <?php  }
            	                 	     	 }
            	                 	     	 
                                    }
	                 	     ?>
                        </tbody>
                		</table>
              	   </div>
             
             
              
              
          </div>
			 
                    
          <footer class="panel-footer">
            <div class="row">
              <div class="col-sm-4 hidden-xs"> </div>
             
             
            </div>
          </footer>
        </section>
      </div>
    </div>
  </section>
</section>

<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
 <?php if($addedByInfo['country_id']=='111') {?>
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>css/invoice.css">
<?php }?>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

<!-- Close : validation script -->