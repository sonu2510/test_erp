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

//sonu add 21-4-2017
$address_id = '0';
$add_url='';
if(isset($_GET['address_book_id']))
{
    $address_id = decode($_GET['address_book_id']);
    $add_url = '&address_book_id='.$_GET['address_book_id'];
}


$bradcums[] = array(
	'text' 	=> $display_name.' List',
	'href' 	=> $obj_general->link($rout, 'mod=index&is_delete='.$_GET['is_delete'].$add_url, '',1),
	'icon' 	=> 'fa-list',
	'class'	=> '',
);

$bradcums[] = array(
	'text' 	=> 'Courier Details',
	'href' 	=> '',
	'icon' 	=> 'fa-edit',
	'class'	=> 'active',
);
//Close : bradcums
$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
//Start : edit
$edit = '';

$click = '';
if(isset($_GET['invoice_no']) && !empty($_GET['invoice_no'])){
	if(!$obj_general->hasPermission('view',$menuId)){
		$display_status = false;
	}else{
		$invoice_no = base64_decode($_GET['invoice_no']);
		$click = 1;
	} //end first else
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}
$invoice=$obj_invoice->getInvoiceData($invoice_no);
$addedByInfo = $obj_invoice->getUser($invoice['user_id'],$invoice['user_type_id']);
if(isset($_POST['btn_save']))
{
	 
      $obj_invoice->save_courier_details($_POST);
    //  $obj_invoice->genSalesInvoice($invoice['invoice_id']);
      	page_redirect($obj_general->link('sales_invoice', 'mod=index&is_delete=0', '',1));
}
/*if(isset($_POST['btn_convert'])){ 
		$obj_invoice->genSalesInvoice($invoice['invoice_id']);
		$obj_session->data['success'] = GEN;
		page_redirect($obj_general->link('sales_invoice', 'mod=index&is_delete=0', '',1));
	}*/

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
					<header class="panel-heading bg-white">
						<span>Courier Details</span>
					</header>
					<div class="panel-body">
						<form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
							<input type="hidden" name="sales_invoice_id"  value="<?php echo $invoice_no;?>" id="sales_invoice_id" class="form-control validate[required]" />
							<div class="form-group">
								<label class="col-lg-4 control-label">Customer Name</label>
								<label class="col-lg-1 control-label normal-font"><?php echo $invoice['customer_name'];?></label>
								<label class="col-lg-3 control-label">Sales Invoice Number</label>
								<label class="col-lg-2 control-label normal-font"><?php echo $invoice['invoice_no'];?></label>
							</div>
							<div class="form-group">
								<label class="col-lg-4 control-label">Are all products dispatched in this Consignment?</label>
								<label class="col-lg-1 control-label normal-font">
									<input type="radio" name="consignment_option" value="1" checked >  Yes 
								</label>
								<label class="col-lg-1 control-label normal-font">
									<input type="radio" name="consignment_option" value="0" >  No 
								</label>
							</div>
							<div class="form-group">
								<label class="col-lg-4 control-label">Courier Name</label>
								<label class="control-label normal-font">
									<label class="col-lg-12 control-label normal-font">
										<table style="text-align: left;">
											<tr><td><input type="radio" name="courier_option" id="courier_option" value="TNT" checked>  TNT </td></tr>
											<tr><td><input type="radio" name="courier_option" id="courier_option" value="Australia Post">  Australia Post </td></tr>
											<tr><td><input type="radio" name="courier_option" id="courier_option" value="ATC Couriers">  ATC Couriers </td></tr>
											<tr><td><input type="radio" name="courier_option" id="courier_option" value="Courier Please">  Courier Please </td></tr>
											<tr><td><input type="radio" name="courier_option" id="courier_option" value="Warehouse Pick UP">  Warehouse Pick UP </td></tr>
											<tr><td><input type="radio" name="courier_option" id="courier_option" value="Self Delivered by me">  Self Delivered by me </td></tr>
											<tr><td><input type="radio" name="courier_option" id="courier_option" value="Other">  Other <br><input type="text" name="Other_courier" value="" id="Other_courier" class="form-control validate[required]" style="display:none;"/></td></tr>
										</table>
									</label>
								</label>
							</div>
							<div class="form-group">
								<label class="col-lg-4 control-label"></label>
								<label class="col-lg-1 control-label">Courier Details : </label>
								<label class="control-label normal-font">
									<label class="col-lg-12 control-label normal-font">
										<table class="table" style="text-align: left;" border='1' width="auto">
											<thead>
												<tr>
													<th>Courier</th>
													<th>Date</th>
													<th>Type</th>
													<th>Total Freight Cost</th>
													<th>Consignment Number</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td id="courier__name_td">TNT</td>
													<td><input type="text" class="input-sm form-control datepicker validate" readonly data-date-format="yyyy-mm-dd" id="courier_date" name="courier_date" value="<?php echo date("Y-m-d") ; ?>" placeholder="Courier Date" /></td>
													<td>
														<div class="btn-group">
														  <button data-toggle="dropdown" class="btn btn-sm btn-white dropdown-toggle"> <span class="dropdown-label first_label" data-placeholder="Select Courier Type">Select Courier Type</span> <span class="caret"></span> </button> 
														  <ul class="dropdown-menu dropdown-select first_ul">
															 <li class=""><a href="#"><input type="checkbox" value="Box" class="courier_type" name="courier_type[]">Box</a></li>
															 <li class=""><a href="#"><input type="checkbox" value="Large Satchel - TNT" class="courier_type" name="courier_type[]">Large Satchel - TNT</a></li>
															 <li class=""><a href="#"><input type="checkbox" value="Regular Satchel - TNT" class="courier_type" name="courier_type[]">Regular Satchel - TNT</a></li>
															 <li class=""><a href="#"><input type="checkbox" value="500g Aus Post" class="courier_type" name="courier_type[]">500g Aus Post</a></li>
															 <li class=""><a href="#"><input type="checkbox" value="1kg Aus Post" class="courier_type" name="courier_type[]">1kg Aus Post</a></li>
															 <li class=""><a href="#"><input type="checkbox" value="3kg Aus Post" class="courier_type" name="courier_type[]">3kg Aus Post</a></li>
															 <li class=""><a href="#"><input type="checkbox" value="5kg Aus Post" class="courier_type" name="courier_type[]">5kg Aus Post</a></li>
															 <li class=""><a href="#"><input type="checkbox" value="Express 500g Aus Post" class="courier_type" name="courier_type[]">Express 500g Aus Post</a></li>
															 <li class=""><a href="#"><input type="checkbox" value="Express 1kg Aus Post" class="courier_type" name="courier_type[]">Express 1kg Aus Post</a></li>
															 <li class=""><a href="#"><input type="checkbox" value="Express 3kg Aus Post" class="courier_type" name="courier_type[]">Express 3kg Aus Post</a></li>
															 <li class=""><a href="#"><input type="checkbox" value="Express 5kg Aus Post" class="courier_type" name="courier_type[]">Express 5kg Aus Post</a></li>
															 <li class=""><a href="#"><input type="checkbox" value="Pallet" class="courier_type" name="courier_type[]">Pallet</a></li>
															 <li class=""><a href="#"><input type="checkbox" value="Other" class="courier_type" name="courier_type[]">Other</a></li>
														  </ul>
													   </div>
													    <input class="form-control" type="text" id="other_box_details" name="other_box_details" style="display:none" >
													</td>
													<td><input type="text" name="courier_freight"  value="" id="courier_freight" class="form-control validate[required]" /></td>
													<td><input type="text" name="consignment_no"  value="" id="consignment_no" class="form-control validate[required]" /></td>
												</tr>
											</tbody>
										</table>
									</label>
								</label>
							</div>	
							<div class="form-group">
								<label class="col-lg-4 control-label">Do you sent any Return Satchel of organised Pickup of any Returned Goods?</label>
								<label class="col-lg-1 control-label normal-font">
									<input type="radio" name="returned_goods" value="0" checked >  No 
								</label>
								<label class="col-lg-1 control-label normal-font">
									<input type="radio" name="returned_goods" value="1" >  Yes 
								</label>
							</div>
							<div id="hidden_div_area" style="display:none;">
								<div class="form-group">
									<label class="col-lg-4 control-label">Courier Name</label>
									<label class="control-label normal-font">
										<label class="col-lg-12 control-label normal-font">
											<table style="text-align: left;">
												<tr><td><input type="radio" name="courier_option1" id="courier_option1" value="TNT" checked>  TNT </td></tr>
												<tr><td><input type="radio" name="courier_option1" id="courier_option1" value="Australia Post">  Australia Post </td></tr>
												<tr><td><input type="radio" name="courier_option1" id="courier_option1" value="ATC Couriers">  ATC Couriers </td></tr>
												<tr><td><input type="radio" name="courier_option1" id="courier_option1" value="Courier Please">  Courier Please </td></tr>
												<tr><td><input type="radio" name="courier_option1" id="courier_option1" value="Warehouse Pick UP">  Warehouse Pick UP </td></tr>
												<tr><td><input type="radio" name="courier_option1" id="courier_option1" value="Self Delivered by me">  Self Delivered by me </td></tr>
												<tr><td><input type="radio" name="courier_option1" id="courier_option1" value="Other">  Other <br><input type="text" name="Other_courier1" value="" id="Other_courier1" class="form-control validate[required]" style="display:none;"/></td></tr>
											</table>
										</label>
									</label>
								</div>
								<div class="form-group">
									<label class="col-lg-4 control-label"></label>
									<label class="col-lg-1 control-label">Courier Details : </label>
									<label class="control-label normal-font">
										<label class="col-lg-12 control-label normal-font">
											<table class="table" style="text-align: left;" border='1' width="auto">
												<thead>
													<tr>
														<th>Courier</th>
														<th>Date</th>
														<th>Type</th>
														<th>Total Freight Cost</th>
														<th>Consignment Number</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td id="courier__name_td1">TNT</td>
														<td><input type="text" class="input-sm form-control datepicker validate" readonly data-date-format="yyyy-mm-dd" id="courier_date1" name="courier_date1" value="<?php echo date("Y-m-d") ; ?>" placeholder="Courier Date" /></td>
														<td>
															<div class="btn-group">
															  <button data-toggle="dropdown" class="btn btn-sm btn-white dropdown-toggle"> <span class="dropdown-label" data-placeholder="Select Courier Type">Select Courier Type</span> <span class="caret"></span> </button> 
															  <ul class="dropdown-menu dropdown-select sec_ul">
																 <li class=""><a href="#"><input type="checkbox" class="courier_type1"  value="Box" name="courier_type1[]">Box</a></li>
																 <li class=""><a href="#"><input type="checkbox" class="courier_type1"  value="Large Satchel - TNT" name="courier_type1[]">Large Satchel - TNT</a></li>
																 <li class=""><a href="#"><input type="checkbox" class="courier_type1"  value="Regular Satchel - TNT" name="courier_type1[]">Regular Satchel - TNT</a></li>
																 <li class=""><a href="#"><input type="checkbox" class="courier_type1"  value="500g Aus Post" name="courier_type1[]">500g Aus Post</a></li>
																 <li class=""><a href="#"><input type="checkbox" class="courier_type1"  value="1kg Aus Post" name="courier_type1[]">1kg Aus Post</a></li>
																 <li class=""><a href="#"><input type="checkbox" class="courier_type1"  value="3kg Aus Post" name="courier_type1[]">3kg Aus Post</a></li>
																 <li class=""><a href="#"><input type="checkbox" class="courier_type1"  value="5kg Aus Post" name="courier_type1[]">5kg Aus Post</a></li>
																 <li class=""><a href="#"><input type="checkbox" class="courier_type1"  value="Express 500g Aus Post" name="courier_type1[]">Express 500g Aus Post</a></li>
																 <li class=""><a href="#"><input type="checkbox" class="courier_type1"  value="Express 1kg Aus Post" name="courier_type1[]">Express 1kg Aus Post</a></li>
																 <li class=""><a href="#"><input type="checkbox" class="courier_type1"  value="Express 3kg Aus Post" name="courier_type1[]">Express 3kg Aus Post</a></li>
																 <li class=""><a href="#"><input type="checkbox" class="courier_type1"  value="Express 5kg Aus Post" name="courier_type1[]">Express 5kg Aus Post</a></li>
																 <li class=""><a href="#"><input type="checkbox" class="courier_type1"  value="Pallet" name="courier_type1[]">Pallet</a></li>
																 <li class=""><a href="#"><input type="checkbox" class="courier_type1"  value="Other" name="courier_type1[]">Other</a></li>
															  </ul>
														   </div>
														   <input class="form-control" type="text" id="other_box_details1" name="other_box_details1" style="display:none" >
														</td>
														<td><input type="text" name="courier_freight1"  value="" id="courier_freight1" class="form-control validate[required]" /></td>
														<td><input type="text" name="consignment_no1"  value="" id="consignment_no1" class="form-control validate[required]" /></td>
													</tr>
												</tbody>
											</table>
										</label>
									</label>
								</div>
								<div class="form-group">
									<label class="col-lg-4 control-label">Buyer Order No. / Sales Invoice No.</label>
									<label class="col-lg-1 control-label normal-font">
										<input type="text" name="buyer_order_no"  value="" id="buyer_order_no" class="form-control validate[required]" />
									</label>
								</div>
							</div>
							<div class="form-group"> 
								 <div class="col-lg-9 col-lg-offset-3"> 
									   <button type="submit" name="btn_save" id="btn_save" class="btn btn-primary" >Save </button>
									  <!-- 	<button type="submit" class="btn btn-primary btn-sm pull-right ml5" name="btn_convert" id="btn_convert">Generate Tax Invoice</button>-->
									   <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '', '',1);?>">Cancel</a>
								</div>
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
<script>
 jQuery(document).ready(function(){
	 jQuery("#form").validationEngine();
	 $("#courier_date,#courier_date1").datepicker({format: 'yyyy/mm/dd',}).on('changeDate', function(e){$(this).datepicker('hide');});
});
	$("input[type='radio'][name='courier_option']").on("change", function () {
		var value = this.value;
		if(value == 'Other')
			$("#Other_courier").show();
		else
			$("#Other_courier").hide();
		if(value == 'Self Delivered by me' || value == 'Warehouse Pick UP')
		{
		    $("#courier_freight").val('0');$("#consignment_no").val('NA');
		}
		else
		    $("#courier_freight,#consignment_no").val('');
		    
		$("#courier__name_td").text(value);
	});
	$("input[type='radio'][name='courier_option1']").on("change", function () {
		var value = this.value;
		if(value == 'Other')
			$("#Other_courier1").show();
		else
			$("#Other_courier1").hide();
		
		if(value == 'Self Delivered by me' || value == 'Warehouse Pick UP')
		{
		    $("#courier_freight1").val('0');$("#consignment_no1").val('NA');
		}
		else
		    $("#courier_freight1,#consignment_no1").val('');
		    
		$("#courier__name_td1").text(value);
	});
	$("input[type='radio'][name='returned_goods']").on("change", function () {
		var value = this.value;
		if(value == '1')
			$("#hidden_div_area").show();
		else
			$("#hidden_div_area").hide();
		
	});
     
		setInterval(
		  function(){
		  	var final = [];

				  $('.courier_type:checked').each(function(){  //alert('ffffff'); 
		          	var values = $(this).val();
		             final.push(values);
		        });
				if(final.includes("Other")==true){
					$('#other_box_details').css('display','inline');
				}else{
					$('#other_box_details').css('display','none');$('#other_box_details').val('');
				}
		   },
		  2000
		)
		setInterval(
		  function(){
		  	var final1 = [];

				  $('.courier_type1:checked').each(function(){  //alert('ffffff'); 
		          	var values = $(this).val();
		             final1.push(values);
		        });
				if(final1.includes("Other")==true){
					$('#other_box_details1').css('display','inline');
				}else{
					$('#other_box_details1').css('display','none');$('#other_box_details1').val('');
				}
		   },
		  2000
		)
</script>	
<!-- Close : validation script -->