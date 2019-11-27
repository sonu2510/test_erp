<?php
include("mode_setting.php");
//[kinjal]:
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
	'text' 	=> 'Branchwise Inventory List',
	'href' 	=> $obj_general->link($rout, '&mod=branch_inventory&branch_id='.$_GET['branch_id'], '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);

if(isset($_GET['product_code_id']) && !empty($_GET['product_code_id'])){	
		$product_code_id = decode($_GET['product_code_id']);
		$invoice = $obj_invoice->getProductCodeName($product_code_id);
} 

$bradcums[] = array(
	'text' 	=> $invoice['description'].' <b>[ </b>'.$invoice['product_code'].' <b>]</b>'.' List',
	'href' 	=> '',
	'icon' 	=> 'fa-list',
	'class'	=> 'active',
);

if(!$obj_general->hasPermission('view',$menuId)){
	$display_status = false;
}

?>
<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-list"></i>Inventory List</h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>   
        
      <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading bg-white"> 
		  	<span><?php echo $invoice['description'].' <b>[ </b>'.$invoice['product_code'].' <b>]</b>';?> Listing</span>
          </header>
         
            	  <div class="panel-body">
          <form name="form_list" id="form_list" method="post" action="<?php echo $obj_general->link($rout,'mod=listItems', '',1); ?>">
           <input type="hidden" id="action" name="action" value="" />
          	<div class="table-responsive">
                
                <div class="panel-group m-b" id="accordion2"> <div class="panel"> <div class="panel-heading"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne" onclick="div_hide(1,<?php echo decode($_GET['product_code_id']);?>,<?php echo decode($_GET['branch_id']);?>)"> Purchase Invoice List </a> </div> <div id="collapseOne" class="panel-collapse collapse"> <div class="panel-body text-small" id="tabletr_1"></div> </div> </div>
                
               <div class="panel"> <div class="panel-heading"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo" onclick="div_hide(2,<?php echo decode($_GET['product_code_id']);?>,<?php echo decode($_GET['branch_id']);?>)"> Sales Invoice List </a> </div> <div id="collapseTwo" class="panel-collapse collapse"> <div class="panel-body text-small" id="tabletr_2"></div> </div> </div>
               
               <div class="panel"> <div class="panel-heading"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseThree" onclick="div_get(<?php echo decode($_GET['product_code_id']);?>,<?php echo decode($_GET['branch_id']);?>)"> Rack Detail List </a> </div> <div id="collapseThree" class="panel-collapse collapse"> <div class="panel-body text-small" id="tabletr"></div> </div> </div>
             
             </div>
          </form>
        </section>
      </div>
    </div>
  </section>
</section>
<script type="application/javascript">
jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        //jQuery("#sform").validationEngine();
});
function div_hide(tnm,product_code_id,user_id)
{ 	var indent_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=displaydata', '',1);?>");
	 $.ajax({
			url : indent_url,
			method : 'POST',
				data:{tnm:tnm ,product_code_id:product_code_id, user_id:user_id},
				success: function(response){
					//alert(response);
					$('#tabletr_'+tnm).html(response);
				},
				error: function(){
					return false;	
				}
				});
}
function div_get(product_code_id,user_id)
{
	var indent_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=displayrackdata', '',1);?>");
	 $.ajax({
			url : indent_url,
			method : 'POST',
				data:{product_code_id:product_code_id, user_id:user_id},
				success: function(response){
					//alert(response);
					$('#tabletr').html(response);
				},
				error: function(){
					return false;	
				}
				});
}
</script>           
