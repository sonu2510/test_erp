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
if(isset($_GET['invoice_no']) && !empty($_GET['invoice_no'])){
	$invoice_no = base64_decode($_GET['invoice_no']);
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
                        <?php 
							$html = $obj_invoice->viewTransDetail($invoice_no);
							echo $html;
						?>
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

