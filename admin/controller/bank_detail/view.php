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
if(isset($_GET['bank_detail_id']) && !empty($_GET['bank_detail_id'])){
	$bank_detail_id = base64_decode($_GET['bank_detail_id']);
	$bank_detail = $obj_bank->getBankDetail($bank_detail_id);
	$currency_name = $obj_bank->getCurrencyCode($bank_detail['curr_code']);	
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
                            <label class="col-lg-3 control-label">Currency</label>
                            <div class="col-lg-4">
                                <label class="control-label normal-font">
                                	<?php echo ucwords($currency_name['currency_code']);?>
                                </label>
                        	</div>
                     	</div>
                        <div class="form-group">
                        	<label class="col-lg-3 control-label">Beneficiary Name</label>
                        	<div class="col-lg-4">
                            	<label class="control-label normal-font">
                            		<?php echo ucwords($bank_detail['bank_accnt']);?>
                            	</label>
                        	</div>
                      	</div>
                        <div class="form-group">
                        	<label class="col-lg-3 control-label">Account Number </label>
                        	<div class="col-lg-4">
                            	<label class="control-label normal-font">
                            		<?php echo $bank_detail['accnt_no'];?>
                            	</label>
                        	</div>
                      	</div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Beneficiary Address</label>
                            <div class="col-lg-9">
                                <label class="control-label normal-font">
                                	<?php echo ucwords($bank_detail['benefry_add']);?>
                                </label>
                        	</div>
                     	</div>
                        
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Beneficiary Bank Name</label>
                            <div class="col-lg-4">
                                <label class="control-label normal-font">
                                	<?php echo ucwords($bank_detail['benefry_bank_name']);?>
                                </label>
                        	</div>
                     	</div>
                        <?php if(isset($bank_detail['curr_code']) &&  $bank_detail['curr_code']== '8'){ ?>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Bank Code</label>
                            <div class="col-lg-4">
                                <label class="control-label normal-font">
                                	<?php  echo ucwords($bank_detail['bank_code']);?>
                                </label>
                        	</div>
                     	</div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Branch Code</label>
                            <div class="col-lg-4">
                                <label class="control-label normal-font">
                                	<?php echo ucwords($bank_detail['branch_code']);?>
                                </label>
                        	</div>
                     	</div>
                        <?php }
						else{ ?>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">IFSC Code</label>
                            <div class="col-lg-4">
                                <label class="control-label normal-font">
                                	<?php echo $bank_detail['swift_cd_hsbc'];?>
                                </label>
                        	</div>
                     	</div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">MICR Code</label>
                            <div class="col-lg-4">
                                <label class="control-label normal-font">
                                	<?php echo $bank_detail['micr_code'];?>
                                </label>
                        	</div>
                     	</div>
                        <?php } ?>
                        <div class="form-group">
                        	<label class="col-lg-3 control-label">Beneficiary Bank Address</label>
                        	<div class="col-lg-9">
                            	<label class="control-label normal-font">
                            		<?php echo ucwords($bank_detail['benefry_bank_add']);?>
                            	</label>
                        	</div>
                      	</div>
                        
                        <?php if($currency_name['currency_code']=='MXN')
						{
						?>
                         <div class="form-group">
                        	<label class="col-lg-3 control-label">Clabe</label>
                        	<div class="col-lg-9">
                            	<label class="control-label normal-font">
                            		<?php echo ucwords($bank_detail['clabe']);?>
                            	</label>
                        	</div>
                      	</div>
                        <?php 
						}elseif($currency_name['currency_code']=='AUD')
						{
						?>
                          <div class="form-group">
                        	<label class="col-lg-3 control-label">Bsb</label>
                        	<div class="col-lg-9">
                            	<label class="control-label normal-font">
                            		<?php echo ucwords($bank_detail['bsb']);?>
                            	</label>
                        	</div>
                      	</div>
                        <div class="form-group">
                        	<label class="col-lg-3 control-label">Swift Code</label>
                        	<div class="col-lg-9">
                            	<label class="control-label normal-font">
                            		<?php echo ucwords($bank_detail['swift_code']);?>
                            	</label>
                        	</div>
                      	</div>
                        <?php } ?>                      
                        <?php if($bank_detail['intery_bank_name']!=''){?>
                        <div class="form-group">
                        	<label class="col-lg-3 control-label">Intermediary Bank Name</label>
                        	<div class="col-lg-4">
                            	<label class="control-label normal-font">
                            		<?php echo ucwords($bank_detail['intery_bank_name']);?>
                            	</label>
                        	</div>
                      	</div>
                        <?php } 
						if($bank_detail['hsbc_accnt_intery_bank']!=''){?>
                        <div class="form-group">
                            <label class="col-lg-3 control-label"> Intermediary Bank</label>
                            <div class="col-lg-4">
                                <label class="control-label normal-font">
                                	<?php echo $bank_detail['hsbc_accnt_intery_bank'];?>
                                </label>
                        	</div>
                     	</div>
                        <?php } 
						if($bank_detail['swift_cd_intery_bank']!='') { ?>
                        <div class="form-group">
                        	<label class="col-lg-3 control-label">Swift Code Of Intermediary Bank</label>
                        	<div class="col-lg-4">
                            	<label class="control-label normal-font">
                            		<?php echo $bank_detail['swift_cd_intery_bank'];?>
                            	</label>
                        	</div>
                      	</div>
                        <?php } 
						if($bank_detail['intery_aba_rout_no']!='')
						{
						?>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Intermediary Bank ABA Routing Number</label>
                            <div class="col-lg-4">
                                <label class="control-label normal-font">
                                	<?php echo $bank_detail['intery_aba_rout_no'];?>
                                </label>
                        	</div>
                     	</div>
                        <?php } ?>
        				<div class="form-group">
        					<div class="col-lg-9 col-lg-offset-3">                
          						<a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a>
        					</div>
        				</div>
        
        			</div>
        		</section>
        	</div>
        </div>
    </section>
</section>

