<?php
include("mode_setting.php");

$bradcums = array();
$bradcums[] = array(
	'text' 	=> 'Dashboard',
	'href' 	=> $obj_general->link('dashboard', '', '',1),
	'icon' 	=> 'fa-home',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> 'Stock Order List',
	'href' 	=> '',
	'icon' 	=> 'fa-list',
	'class'	=> 'active',
);

if(!$obj_general->hasPermission('view',$menuId)){
	$display_status = false;
}

//$class ='collapse';
$filter_data=array();

if(!isset($_GET['filter_edit'])){
	$filter_edit = 0;
}else{
	$filter_edit = $_GET['filter_edit'];
}

$limit = LISTING_LIMIT;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}
if(!isset($_GET['filter_edit']) || $_GET['filter_edit']==0){
	if(isset($obj_session->data['filter_data'])){
		unset($obj_session->data['filter_data']);	
	}
}
$class='collapse';
if(isset($_GET['sort'])){
	$sort_name = $_GET['sort'];
}else{
	$sort_name='product_id';
}

if(isset($_GET['order'])){
	$sort_order = $_GET['order']; 
}else{
	$sort_order = 'ASC';
}

?>
<section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-list"></i>Stock Order List</h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>   
        
      <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading bg-white"> 
		  	<span>Stock Order Listing</span>
          </header>
         
            	  <div class="panel-body">
          <form name="form_list" id="form_list" method="post" action="<?php echo $obj_general->link($rout,'mod=listItems', '',1); ?>">
           <input type="hidden" id="action" name="action" value="" />
          	<div class="table-responsive">
                
                      <?php $k=2; $t="product_spout";?>
  
             
                <div class="panel-group m-b" id="accordion2"> <div class="panel"> <div class="panel-heading"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne" onclick="div_hide(1)"> Zipper </a> </div> <div id="collapseOne" class="panel-collapse collapse"> <div class="panel-body text-small" id="tabletr_1"></div> </div> </div>
                
               <div class="panel"> <div class="panel-heading"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo" onclick="div_hide(2)"> Spout </a> </div> <div id="collapseTwo" class="panel-collapse collapse"> <div class="panel-body text-small" id="tabletr_2"></div> </div> </div>
                
               <div class="panel"> <div class="panel-heading"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseThree" onclick="div_hide(3)"> Material</a> </div> <div id="collapseThree" class="panel-collapse collapse"> <div class="panel-body text-small" id="tabletr_3"></div> </div> </div> 
               
                <div class="panel"> <div class="panel-heading"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseFour" onclick="div_hide(4)"> Ink</a> </div> <div id="collapseFour" class="panel-collapse collapse"> <div class="panel-body text-small" id="tabletr_4"> </div> </div> </div>
                
                <div class="panel"> <div class="panel-heading"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseFive" onclick="div_hide(5)"> Ink Solvent</a> </div> <div id="collapseFive" class="panel-collapse collapse"> <div class="panel-body text-small" id="tabletr_5"> </div> </div> </div>
                
                <div class="panel"> <div class="panel-heading"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseSix" onclick="div_hide(6)"> Adhesive</a> </div> <div id="collapseSix" class="panel-collapse collapse"> <div class="panel-body text-small" id="tabletr_6"> </div> </div></div>
                
                 <div class="panel"> <div class="panel-heading"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseSeven" onclick="div_hide(7)"> Adhesive Solvent</a> </div> <div id="collapseSeven" class="panel-collapse collapse"> <div class="panel-body text-small" id="tabletr_7"></div> </div></div> 
                 
                  <div class="panel"> <div class="panel-heading"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseEight" onclick="div_hide(8)"> Accessorie</a> </div> <div id="collapseEight" class="panel-collapse collapse"> <div class="panel-body text-small" id="tabletr_8"></div> </div> </div></div>
              
                  
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
        jQuery("#sform").validationEngine();
});
function div_hide(tnm)
{ 
	 var indent_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=displaydata', '',1);?>");
	 $.ajax({
			url : indent_url,
			method : 'POST',
				data:{tnm:tnm},
				success: function(response){
					//alert(response);
					//alert(tnm);
					$('#tabletr_'+tnm).html(response);
				
				},
				error: function(){
					return false;	
				}
				});
}
</script>           
