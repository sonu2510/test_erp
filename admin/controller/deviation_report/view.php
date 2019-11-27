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
	'text' 	=> $display_name.' detail',
	'href' 	=> '',
	'icon' 	=> 'fa-edit',
	'class'	=> 'active',
);

if(isset($_GET['invoice_id']) && !empty($_GET['invoice_id'])){
	$invoice_id = base64_decode($_GET['invoice_id']);	
	$report_details= $obj_deviation_report->reportdetails($invoice_id);
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
       <!-- [kinjal] make if cond on 15-12-2016-->
        	<div class="col-sm-12">
        		<section class="panel">
        			<header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
        			<div class="panel-body form-horizontal">                    
                    	<div class="form-group">
                            <label class="col-lg-3 control-label">Invoice No</label>
                            <div class="col-lg-4">
                          
                                <label class="control-label normal-font">
                                	<?php echo $report_details['invoice_no'];?>
                                </label>
                        	</div>
                     	</div>
                        <?php if($report_details['custom_duty_deviation_per']!=0)
						{  ?>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Custom Duty Charge </label>
                            <div class="col-lg-4">
                                <label class="control-label normal-font">
                                	<?php echo number_format($report_details['custom_duty_deviation_per'],2).' % ';?>
                                </label>
                        	</div>
                     	</div>
                        <?php  } 
							if($report_details['GST_on_import_deviation_per']!=0)
						{ ?>
                        
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Gst On Import Charge</label>
                            <div class="col-lg-4">
                          
                                <label class="control-label normal-font">
                                	<?php echo number_format($report_details['GST_on_import_deviation_per'],2).' %';?>
                                </label>
                        	</div>
                     	</div>
                        <?php  } 
						if($report_details['other_charges_deviation_per']!=0)
						{  ?>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Other Charge</label>
                            <div class="col-lg-4">
                          
                                <label class="control-label normal-font">
                                	<?php echo $report_details['other_charges_deviation_per'];?>
                                </label>
                        	</div>
                     	</div>
                        <?php  } 
						if($report_details['clearing_charges_deviation_per']!=0)
						{  ?>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Clearing Charge</label>
                            <div class="col-lg-4">
                          
                                <label class="control-label normal-font">
                                	<?php echo number_format($report_details['clearing_charges_deviation_per'],2).' %';?>
                                </label>
                        	</div>
                     	</div>
                         <?php
						 }
						// if($report_details['close_status']=='1')
						//{?>
                            <div class="form-group">
                           
                                <label class="col-lg-3 control-label">Need Clarification</label>
                                <div class="col-lg-4">
                              
                                    <label class="control-label normal-font">
                                        <?php echo $report_details['need_clarification']; //echo phpinfo();?>
                                    </label>
                                </div>
                            </div>
                        <?php // }?>
                          
                     
                    	<div class="form-group">
        					<div class="col-lg-9 col-lg-offset-3">                
          						<a class="btn btn-default" href="<?php echo $obj_general->link($rout,'','',1);?>">Cancel</a>
        					</div>
        				</div>
        
        			</div>
        		</section>
        	</div>
        </div>
    </section>
</section>

