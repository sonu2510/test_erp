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
	'text' 	=> $display_name.' List',
	'href' 	=> $obj_general->link($rout, '', '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);

$limit = 50;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}

if(isset($_GET['order'])){
	$sort_order = $_GET['order'];	
}else{
	$sort_order = 'ASC';	
}

if(isset($_GET['sort'])){
	$sort_name = $_GET['sort'];	
}else{
	$sort_name = 'taxation_canada_id';
}
$courier_data = $obj_tax_canada->getCouriers();

if($display_status) {

?>
<section id="content">
  <section class="main padder">
      <h4><i class="fa fa-users"></i> <?php echo $display_name;?></h4>
    <div class="clearfix">
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div>   
        
      <div class="col-lg-12">
        <section class="panel">
          <header class="panel-heading"> 		  	
			<span><?php echo $display_name;?> Listing</span>          		
      		<span class="text-muted m-l-small pull-right">
                    
            </span>   
          </header>
          <div class="panel-body">
          </div>
          <form name="form_list" id="form_list" method="post">
          	<div class="table-responsive">
            <table class="table table-striped b-t text-small">
              <thead>
                <tr>
			      <th>Sr.No</th>	
                  <th>State</th>
                  <!--<th>Courier</th>-->
                  <th>Abbreviation</th>
                  <th>GST(%)</th>
                  <th>PST(%)</th>
                  <th>HST(%)</th>
                  <!--<th>Price Per Box(Normal Shipping)</th>
                  <th>Price Per Box(Express Shipping)</th>-->
                </tr>
              </thead>
              <tbody>
              <?php  $i=1;
				$labels =$obj_tax_canada->getLabel();
				
				if($labels !='')
				{			
                foreach($labels as $label){
				//printr($labels);
			
			?>
            <input type="hidden" name="courier" value="<?php echo isset($country['default_courier_id']) ? $country['default_courier_id'] : '';?>" />
                <tr>
                	<td class="col-lg-1"><?php echo $i;?></td>
                    
                    <td><label style="font-size:14px" class="col-lg-8 control-label"><?php echo $label['state'];?></label></td>
                    
                    <td><input type="text" class="form-control validate[required]" id="abb_<?php echo $i;?>" onchange="tax_change(<?php echo $i;?>,<?php echo $label['taxation_canada_id'] ;?>)" value="<?php echo isset($label['abbreviation'])?$label['abbreviation']:'' ;?>" > </td>
                    
                    <td><input type="text" class="form-control validate[required,custom[number],min[0.00]]" id="tax_gst<?php echo $i;?>" onchange="tax_change(<?php echo $i;?>,<?php echo $label['taxation_canada_id'] ;?>)" value="<?php echo isset($label['gst'])?$label['gst']:'' ;?>" > </td>
                      
                    <td><input type="text" class="form-control validate[required,custom[number],min[0.00]]" id="tax_rst<?php echo $i;?>" onchange="tax_change(<?php echo $i;?>,<?php echo $label['taxation_canada_id'] ;?>)" value="<?php echo isset($label['rst'])?$label['rst']:'' ;?>" > </td>
                    
                    <td><input type="text" class="form-control validate[required,custom[number],min[0.00]]" id="tax_hst<?php echo $i;?>" onchange="tax_change(<?php echo $i;?>,<?php echo $label['taxation_canada_id'] ;?>)" value="<?php echo isset($label['hst'])?$label['hst']:'' ;?>" > </td>
                    
                    <?php /*?><td><input type="text" class="form-control validate[custom[number],min[0.00]]" id="price_n<?php echo $i;?>" onchange="tax_change(<?php echo $i;?>,<?php echo $label['taxation_canada_id'] ;?>)" value="<?php echo isset($label['price_normal_shipping'])?$label['price_normal_shipping']:'' ;?>" > </td>
                    
                    <td><input type="text" class="form-control validate[custom[number],min[0.00]]" id="price_e<?php echo $i;?>" onchange="tax_change(<?php echo $i;?>,<?php echo $label['taxation_canada_id'] ;?>)" value="<?php echo isset($label['price_express_shipping'])?$label['price_express_shipping']:'' ;?>" > </td><?php */?>
                    
                </tr> 
        	<?php $i++; }
		
				} ?>
					
              </tbody>
          </table>
          </div>
          </form>
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
<?php } else {
	include(DIR_ADMIN.'access_denied.php');
}
?>
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/><strong></strong>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script>
jQuery(document).ready(function(){
		
        jQuery("#form_list").validationEngine();
});
/* function for gst tax*/

function tax_change(i,taxation_canada_id){
		var tax_gst=$('#tax_gst'+i).val();
		var tax_rst=$('#tax_rst'+i).val();
		var tax_hst=$('#tax_hst'+i).val();
		var price=$('#price_n'+i).val();
		var price_e=$('#price_e'+i).val();
		var abb = $('#abb_'+i).val();
		//console.log(abb);
	//if($("#form_list").validationEngine('validate')){
		var gst_url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=TaxUpdate', '',1);?>");
		$.ajax({
			url : gst_url,
			type :'post',
			data :{tax_gst:tax_gst,tax_rst:tax_rst,tax_hst:tax_hst,price:price,price_e:price_e,taxation_canada_id:taxation_canada_id,abb:abb},
			success: function(response){
				//console.log(response);
			
				set_alert_message('Successfully Updated',"alert-success","fa-check");
					window.setTimeout(function(){location.reload()},500);					
			},
			error:function(){
				set_alert_message('Error During Updation',"alert-warning","fa-warning");          
			}						
		});
  	//}
}
</script>