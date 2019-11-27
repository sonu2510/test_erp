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
//Close : bradcums

//Start : edit
$edit = '';

if($display_status){
	//insert user
	if(isset($_POST['btn_generate'])){
		$post = post($_POST);
		//printr($_GET['quotation_no']);die;
	$insert_id = $obj_quotation->addTemplate($post,$_GET['quotation_no']);//die;
	if($insert_id!='no')
		$obj_session->data['success'] = 'Stock Template Generated!';
	else
		$obj_session->data['warning'] = 'Error : Stock Template Allready Added !';
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
      <div class="col-sm-8">
        <section class="panel">
          <header class="panel-heading bg-white"> <?php echo $display_name;?> Detail </header>
          <div class="panel-body">
            <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span>User</label>
                <?php							
					$userlist = $obj_quotation->getInternational();
				?>
                <div class="col-lg-3">                
                <select class="form-control validate[required]" name="filter_user_name" id="filter_user_name" onchange="getcurrency()">
                    <option value="">Please Select</option>
                    <?php foreach($userlist as $user) { ?>
	                       <option value="<?php echo $user['international_branch_id']; ?>"><?php echo $user['first_name']. " " .$user['last_name']; ?></option>
                    <?php } ?>                                       
                </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-lg-3 control-label heightb"><span class="required">*</span>Shipment Country</label>
                <div class="col-lg-4">
                  <?php
					$sel_country = (isset($branch['country_id']))?$branch['country_id']:''; 
					$countrys = $obj_general->getCountryCombo($sel_country);
					echo $countrys;                   
				?>	 
                </div>
              </div>
              
                <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span> Currency</label>
                        <div class="col-lg-3">
                        	<?php
							  $currency = $obj_quotation->getCurrency();
                            ?>
                            <select name="currency" id="currency" class="form-control validate[required]"  >
                               <option value="">Select Currency</option>
                                <?php
							
					            foreach($currency as $curr){
                                        echo '<option value="'.$curr['currency_id'].'">'.$curr['currency_code'].'</option>';
                                }
								?>
                            </select>
                           
                            <?php /*?><input type="hidden" name="currency" id="currency" value="" /><?php */?>
						</div>
                      </div>
                <div class="form-group">
                    <label class="col-lg-3 control-label"><span class="required">*</span> Valve Option</label>
                    <div class="col-lg-9">                
                    	<div style="float:left;width: 200px;">
                            <label style="font-weight: normal;">
                              <input type="radio" name="valve" id="nv" value="0" checked="checked" class="valve">
                             No Valve
                             </label>
                         </div>
                         <div style="float:left;width: 200px;">
                            <label style="font-weight: normal;">
                              	<input type="radio" name="valve" id="wv" value="1" class="valve">
                          	With Valve
                             </label>
                          </div> 
                    </div>
                  </div> 
                      
              <div class="form-group">
                <label class="col-lg-3 control-label"><span class="required">*</span> Transportation Type</label>
                <div class="col-lg-9">                
                	<div  style="float:left;width: 200px;">
                        <label  style="font-weight: normal;">
                       	 <input type="radio" name="transpotation[]" id="transpotation[]" value="<?php echo encode('air');?>"  checked="checked" >
                        By Air	
                         </label>	
                          <label  style="font-weight: normal;">
                          	<input type="radio" name="transpotation[]" id="transpotation[]" value="<?php echo encode('sea');?>">
                      	 By Sea
                         </label>
                           <label  style="font-weight: normal;">
                          	<input type="radio" name="transpotation[]" id="transpotation[]" value="<?php echo encode('pickup');?>">
                      	 By pickup
                         </label>
                    </div> 
                </div>
              </div>
              <div class="form-group apply">
                <label class="col-lg-3 control-label">Apply Courier Option</label>
                <div class="col-lg-3">
                	<div  style="float:left;width: 300px;">
                         <label  style="font-weight: normal;">
                       	    <input type="radio" name="charge" id="charge" value="<?php echo 'Normal Courier Charge';?>"  checked="checked" > Normal Courier Charge (working on)
                         </label>	
                         <label  style="font-weight: normal;">
                          	<input type="radio" name="charge" id="100_plus" value="<?php echo '100 Plus Weight';?>"> 100 Plus Weight (working on)
                      	 </label>
                    </div> 
                 </div>
              </div>
               <div class="form-group">
                        <label class="col-lg-3 control-label"><span class="required">*</span> Stock Delivery</label>
                        <div class="col-lg-9">                
                        	<div  style="float:left;width: 200px;">
                                <label  style="font-weight: normal;">
                               	 <input type="radio" name="stockdelivery[]" id="stockdelivery[]" value="<?php echo encode('swisspack_to_other_country');?>" checked="checked" >
                                Swisspack to other country
                                 </label>	
                                  <label  style="font-weight: normal;">
                                  	<input type="radio" name="stockdelivery[]" id="stockdelivery[]" value="<?php echo encode('other_country_to_customer');?>" >
                              	Other country to Customer 
                                 </label>
                             </div> 
                        </div>
                      </div>                   
                         <div class="form-group">
                <div class="col-lg-9 col-lg-offset-3">
                  <input type="submit" name="btn_generate" id="btn_generate" class="btn btn-primary" value="Add"> 
                  <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a> </div>
              </div>
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
<script type="text/javascript">
function getcurrency()
{
	var user_name=$("#filter_user_name").val();
	
	var url = getUrl("<?php echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=getstocktemplateper', '',1);?>");
	
	$.ajax({
		url: url,
		data: {user_name : user_name},       
		type: 'post',
		success : function(response){
			//alert(response);
			var val=$.parseJSON(response);
			$("#currency_name").val(val.result);
			
			//$("#currency").
			var theText=val.result;
			//$("#currency option[text=" +  +"]").attr("selected","selected");
			$("#currency option:contains(" + theText + ")").attr('selected', 'selected');
			//$("#currency").val(val.response);
			
			
		}
	});
	

}
$('input[type=radio][name="transpotation[]"]').change(function() {
    var transpotation = $(this).val();
    if(transpotation == 'YWly')
        $(".apply").show();
    else
        $(".apply").hide();
});
jQuery(document).ready(function(){
    // binds form submission and fields to the validation engine
    jQuery("#form").validationEngine();
});		
</script>