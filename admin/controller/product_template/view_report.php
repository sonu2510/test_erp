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
	'text' 	=> 'Stock Price List Report',
	'href' 	=> $obj_general->link($rout, '&mod=stock_price_list_report', '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> 'Stock Price Detail',
	'href' 	=> '',
	'icon' 	=> 'fa-edit',
	'class'	=> 'active',
);
//Close : bradcums

$limit = LISTING_LIMIT;
if(isset($_GET['limit'])){
	$limit = $_GET['limit'];	
}

$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];

//Start : edit
$edit = '';

if(isset($_POST['btn_pro'])){
		
    $data = $obj_template->getDataOFReport($_POST);
    $data_excel = $obj_template->getDataOFReport($_POST,1);
    $currencys = $obj_template->getNewCurrencys($_POST['user_name']);//$_POST['user_name']//$_POST['country']
    $ib_curr = $obj_template->getIBDetail($_POST['user_name']);
}
if(isset($_POST['btn_proceed'])){
	//printr($_POST);die;	
    $data = $obj_template->getDataOFReport($_POST);
    $data_excel = $obj_template->getDataOFReport($_POST,1);
    $currencys = $obj_template->getNewCurrencys($_POST['user_name']);//$_POST['user_name']//$_POST['country']
    $ib_curr = $obj_template->getIBDetail($_POST['user_name']);
}
if($display_status){	
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
        
        <div class="col-lg-12">
        	<section class="panel">
              <header class="panel-heading bg-white"> Stock Price Detail </header>
                <form class="form-horizontal" method="post" name="form_curr" id="order-form" enctype="multipart/form-data">
                        <div class="panel-body">
                    	<?php if($currencys)
                    	      { ?>
						          <div class="form-group">
    								<label class="col-lg-3 control-label" id="currency_label">Select Currency</label>
    								<div class="col-lg-9 row">
    								    <input type="hidden" name="user_name"  value="<?php echo $_POST['user_name'];?>" readonly="readonly" id="user_name">
    								    <input type="hidden" name="country"  value="<?php echo $_POST['country'];?>" readonly="readonly" id="country">
    								    <input type="hidden" name="report"  value="<?php echo $_POST['report'];?>" readonly="readonly" id="report">
    								    <input type='hidden' id='default_currency' value='<?php echo $ib_curr['currency_code']; ?>'>
    								    <div class="col-lg-3">
                                        	<select name="sel_currency" id="sel_currency" class="form-control" >
                                                <option value="<?php echo $ib_curr['currency_code'];?>" price = "1" attr_curr="drop"><?php echo $ib_curr['currency_code'];?></option>
                                                <?php foreach($currencys as $crr)
									            {?>
                                                    <option value="<?php echo $crr['currency_code'];?>" price = "<?php echo $crr['price'];?>" attr_curr="drop"><?php echo $crr['currency_code'];?></option>
                                          <?php } ?>
                                              </select>
    								    </div>
    								    <div class="col-lg-3">
    								        <input type="text" name="sel_currency_rate"  value="1" readonly="readonly" id="sel_currency_rate" placeholder="Currency Rate" class="form-control validate[condRequired[sel_currency],custom[number]]">
    								    </div>
    								    <div class="col-lg-3">
    								        <button type="submit" name="btn_proceed" id="btn_proceed" class="btn btn-primary">Change Currency</button>
    								    </div>
    								</div>
    							  </div>
						<?php }?>
						</div>
				    </form>
					<form class="form-horizontal" method="post" name="form" id="order-form" enctype="multipart/form-data">
                        <div class="panel-body">
                    	<div class="col-lg-6" style="width:100%">
                           <h4><i class="fa fa-edit"></i> Price Detail</h4>
                           <span class="text-muted m-l-small pull-right">  <a class="label bg-success" href="javascript:void(0);" onclick="excellink()"><i class="fa fa-print"></i> Excel</a>
                            <!--<a class="label bg-info " href="javascript:void(0);" onclick="pdfcls()"><i class="fa fa-print"></i> PDF</a>-->
                            <a class="label bg-inverse " onclick="print_link();" href="javascript:void(0);"><i class="fa fa-print"></i> Print</a></span>
                                <?php echo $data;?>  
                            
                            <div id="data_excel" style='display:none;'>
                                <?php echo $data_excel;?>
                            </div>
                        </div>   
                    </div>
                </form>
        	</section>
      	</div>
     </div>
  </section>
</section>

<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

           

<script>
function excellink(){
    var html_data = $("#data_excel").html();
	excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(html_data);	
	$('<a></a>').attr({
				'id':'data_down',
				'download': 'Stock Price List.xls',
				'href': excelData,
				'target': '_blank'
		}).appendTo('body');
		$('#data_down').ready(function() {
			$('#data_down').get(0).click();
		});
}
/*function pdfcls(){
    var html_data = $("#data_excel").html();
	excelData = 'data:application/excel;charset=utf-8,' + encodeURIComponent(html_data);	
	$('<a></a>').attr({
				'id':'data_down',
				'download': 'Stock Price List.pdf',
				'href': excelData,
				'target': '_blank'
		}).appendTo('body');
		$('#data_down').ready(function() {
			$('#data_down').get(0).click();
		});
}*/
function print_link()
{
    var html="<html>";
	html+='<head>';
	
    html+="<style>.col-lg-3 {width: 15%;}#client {    border-left: 6px solid #0087c3;    float: left;    padding-left: 6px;}h1 {  background:#333;    border-bottom: 1px solid #5d6975;    border-top: 1px solid #5d6975;    color: #FFF;    font-size: 2.4em;    font-weight: normal;    line-height: 1.4em;    margin: 0 0 20px;    text-align: center;}article, article address, table.meta, table.inventory { margin: 0 0 3em; }table.meta, table.balance { float: right; width: 50%; }table.meta:after, table.balance:after { clear: both; display: table; }table.meta th { width: 40%; }table.meta td { width: 60%; }table { font-size: 75%; table-layout: fixed; width: 100%; }table { border-collapse: separate; border-spacing: 0px; }th, td { border-width: 1px; padding: 0.5em; position: relative; text-align: left; }th, td { border-radius: 0; border-style: solid; }th { background: #EEE; border-color: #BBB; }td { border-color: #DDD; }</style></html>";

    html+= $('#data_excel').html();

    html+="</html>";	//alert(html);

    var printWin = window.open('','','');

    printWin.document.write(html);

    printWin.document.close();

    printWin.focus();

    printWin.print();

    printWin.close();
}
$('#sel_currency').change(function(){
    var value = $(this).val();
    
    var old = $("#default_currency").val();
    var price = $(this).find('option:selected').attr("price");
//alert(value);alert(price);
    if(old == value)
    {
        var sel_curr=$("#default_currency").val();
		 $("#sel_currency").val(sel_curr);
		 $("#sel_currency_rate").removeAttr('readonly','readonly');
		 $("#sel_currency_rate").val('1');
		 $("#sel_currency_rate").attr('readonly','readonly');
    }
    else
    {
	    $("#sel_currency_rate").attr('readonly','readonly');
		$("#sel_currency_rate").val(price);
    }
});	
</script> 
<!-- Close : validation script -->

<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>