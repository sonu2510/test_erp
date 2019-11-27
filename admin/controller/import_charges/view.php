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
	'text' 	=> $display_name.' add',
	'href' 	=> '',
	'icon' 	=> 'fa-edit',
	'class'	=> 'active',
);
$limit = LISTING_LIMIT;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}

if(isset($_GET['country_id']) && !empty($_GET['country_id'])){
	$country_id = base64_decode($_GET['country_id']);
	//printr($country_id);
	
	$country = $obj_councharge->getcountry_name($country_id);
	$country_data= $obj_councharge->getcountry_details($country_id);
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
             
               <input type="hidden" name="country_id" value="<?php  echo $country_id ?>" id="country_id" />
                        
            <div class="panel-body">
                        <label class="col-lg-3 control-label">Agent Name</label>
                        <div class="col-lg-3">
                            <input type="text" name="agentname" value="" id="agentname" class="form-control"/>
                        </div>
                 	 </div>
                   <div class="panel-body">
                        <label class="col-lg-3 control-label">Agent Adress</label>
                        <div class="col-lg-3">
                            <input type="text" name="agentaddress" value="" id="agentaddress" class="form-control" />
                        </div>
                  </div>
                   <div class="panel-body">
                        <label class="col-lg-3 control-label">Email Id</label>
                        <div class="col-lg-3">
                            <input type="email" name="mailid" value="" id="mailid" class="form-control" />
                        </div>
    					</div>
                 
                   <div class="panel-body">
                        <label class="col-lg-3 control-label">ABN No</label>
                        <div class="col-lg-3">
                            <input type="text" name="abnno" value="" id="abnno" class="form-control"/>
                        </div>
                  </div>
                   <div class="panel-body">
                        <label class="col-lg-3 control-label">CIF Amount</label>
                        <div class="col-lg-3">
                            <input type="text" name="cifamount" value="" id="cifamount" class="form-control"/>
                        </div>
                  </div>
                   <div class="panel-body">
                        <label class="col-lg-3 control-label">FOB Amount</label>
                        <div class="col-lg-3">
                            <input type="text" name="fobamount" value="" id="fobamount" class="form-control"/>
                        </div>
                  </div>
                   <div class="panel-body">
                        <label class="col-lg-3 control-label">Custom Duty</label>
                        <div class="col-lg-3">
                            <input type="text" name="customduty" value="" id="customduty" class="form-control"/>
                        </div>
                  </div>
                   <div class="panel-body">
                        <label class="col-lg-3 control-label">VOTI</label>
                        <div class="col-lg-3">
                            <input type="text" name="voti" value="" id="voti" class="form-control"/>
                        </div>


                  </div>
                  <div class="panel-body">
                        <label class="col-lg-3 control-label">GST On Import</label>
                        <div class="col-lg-3">
                            <input type="text" name="gst" value="" id="gst" class="form-control"/>
                        </div>
                  </div>
                  
                  <div class="panel-body">
                        <label class="col-lg-3 control-label">Other Charges</label>
                        <div class="col-lg-3">
                            <input type="text" name="othercharges" value="" id="othercharges" class="form-control"/>
                        </div>
                  </div>
                  <div class="panel-body">
                        <label class="col-lg-3 control-label">Clearing Charges</label>
                        <div class="col-lg-3">
                            <input type="text" name="clearingcharges" value="" id="clearingcharges" class="form-control"/>
                        </div>
                  </div>
                <div class="panel-body">
                  <div class="col-lg-9 col-lg-offset-3">
                  	<button type="submit" name="btn_save" id="btn_save" class="btn btn-primary" onClick="getdetail()" href="<?php echo $obj_general->link($rout,'mod=add&country_id='.encode($country['country_id']),'',1);?>" > Save </button> <a class="btn btn-default" href="<?php echo $obj_general->link($rout,'mod=add&country_id='.encode($country['country_id']),'',1);?>">Cancel</a>
                  </div>
                  </div>
                  
          </form>
          
           
       </div>
          
          </div>
          </section>
          </div>
          </section>
          <link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>ckeditor3/ckeditor.js"></script>

<script type="text/javascript">
function getdetail()
{   
	var countryid=$("#country_id").val();
	var agentname=$("#agentname").val();
//	alert(agentname);
	var agentadress=$("#agentaddress").val();
	//alert(agentadress);
	var mailid=$("#mailid").val();
	var abnno=$("#abnno").val();
	var cifamount=$("#cifamount").val();
	var fobamount=$("#fobamount").val();
	var customduty=$("#customduty").val();
	var voti=$("#voti").val();
	var gstonimport=$("#gst").val();
	var othercharges=$("#othercharges").val();
	var clearingcharges=$("#clearingcharges").val();
	var detail_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=formdetails', '',1);?>");
	$.ajax({
		url :detail_url,
		method : 'post',
		data : {agentname:agentname,countryid:countryid,agentadress:agentadress,mailid:mailid,abnno:abnno,cifamount:cifamount,fobamount:fobamount,customduty:customduty,voti:voti,gstonimport:gstonimport,othercharges:othercharges,clearingcharges:clearingcharges},
		success: function(response){
		alert(response);
			//set_alert_message('Successfully Added',"alert-success");
			
		},
		error: function(){
			return false;	
		}
		});
	
	//alert(aggentadress);
	
	
	}

</script>
          