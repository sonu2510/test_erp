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

if(isset($_GET['Agent_id']) && !empty($_GET['Agent_id'])){
	$country_id = base64_decode($_GET['country_id']);
	$Agent_id= base64_decode($_GET['Agent_id']);
	//printr($Agent_id);
	//printr($country_id);
	$country = $obj_councharge->getcountry_name($country_id);
	$country_data= $obj_councharge->getcountry_data($Agent_id,$country_id);
	//printr($country_data);
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
        			<header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
        			<div class="panel-body form-horizontal">                    
                    	<div class="form-group">
                            <label class="col-lg-3 control-label">Agent Name</label>
                            <div class="col-lg-4">
                            
                                <label class="control-label normal-font">
                                	<?php echo $country_data['agent_name'];?>
                                </label>
                        	</div>
                     	</div>
                        <div class="form-group">
                        	<label class="col-lg-3 control-label">Agent Adress</label>
                        	<div class="col-lg-4">
                            	<label class="control-label normal-font">
                            		<?php echo $country_data['agent_address'];?>
                            	</label>
                        	</div>
                      	</div>
                          <div class="form-group">
                        	<label class="col-lg-3 control-label">Email Id</label>
                        	<div class="col-lg-4">
                            	<label class="control-label normal-font">
                            		<?php echo $country_data['email_id'];?>
                            	</label>
                        	</div>
                      	</div>
                          <div class="form-group">
                        	<label class="col-lg-3 control-label">ABN No</label>
                        	<div class="col-lg-4">
                            	<label class="control-label normal-font">
                            		<?php echo $country_data['ABN_no'];?>
                            	</label>
                        	</div>
                      	</div>
                          <div class="form-group">
                        	<label class="col-lg-3 control-label">CIF Amount</label>
                        	<div class="col-lg-4">
                            	<label class="control-label normal-font">
                            		<?php echo $country_data['CIF_amount'];?>
                            	</label>
                        	</div>
                      	</div>
                          <div class="form-group">
                        	<label class="col-lg-3 control-label">FOB Amount</label>
                        	<div class="col-lg-4">
                            	<label class="control-label normal-font">
                            		<?php echo $country_data['FOB_amount'];?>
                            	</label>
                        	</div>
                      	</div>
                          <div class="form-group">
                        	<label class="col-lg-3 control-label">Custom Duty</label>
                        	<div class="col-lg-4">
                            	<label class="control-label normal-font">
                            		<?php echo $country_data['custom_duty'];?>
                            	</label>
                        	</div>
                      	</div>
                          <div class="form-group">
                        	<label class="col-lg-3 control-label">VOTI</label>
                        	<div class="col-lg-4">
                            	<label class="control-label normal-font">
                            		<?php echo $country_data['voti'];?>
                            	</label>
                        	</div>
                      	</div>
                         <div class="form-group">
                        	<label class="col-lg-3 control-label">GST On Import</label>
                        	<div class="col-lg-4">
                            	<label class="control-label normal-font">
                            		<?php echo $country_data['Gst_on_import'];?>
                            	</label>
                        	</div>
                      	</div>
                         <div class="form-group">
                        	<label class="col-lg-3 control-label">Other Charges</label>
                        	<div class="col-lg-4">
                            	<label class="control-label normal-font">
                            		<?php echo $country_data['other_charges'];?>
                            	</label>
                        	</div>
                      	</div>
                         <div class="form-group">
                        	<label class="col-lg-3 control-label">Clearing Charges</label>
                        	<div class="col-lg-4">
                            	<label class="control-label normal-font">
                            		<?php echo $country_data['clearing_charges'];?>
                            	</label>
                        	</div>
                      	</div>
                    	<div class="form-group">
        					<div class="col-lg-9 col-lg-offset-3">                
          						<a class="btn btn-default" href="<?php echo $obj_general->link($rout,'mod=add&country_id='.encode($country['country_id']),'',1);?>">Cancel</a>
        					</div>
        				</div>
        
        			</div>
        		</section>
        	</div>
        </div>
    </section>
</section>

