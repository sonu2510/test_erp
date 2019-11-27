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
$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
//$allow_currency_status = $obj_quotation->allowCurrencyStatus($user_type_id,$user_id);
//Start : edit
$edit = '';

$click = '';
if(isset($_GET['proforma_id']) && !empty($_GET['proforma_id'])){
	if(!$obj_general->hasPermission('view',$menuId)){
		$display_status = false;
	}else{
		$proforma_id = base64_decode($_GET['proforma_id']);
		$proforma=$obj_pro_invoice->getProformaData($proforma_id);
		$click = 1;
	}
}else{
	if(!$obj_general->hasPermission('add',$menuId)){
		$display_status = false;
	}
}
if(isset($_POST['btn_sendemail'])){
	$obj_pro_invoice->sendInvoiceEmail(decode($_GET['proforma_id']),$_POST['smail']);
	}

?>
<section id="content">
    <section class="main padder">
   		<div class="clearfix">
    		<h4><i class="fa fa-edit"></i> <?php echo $display_name;?></h4>
    	</div>
    	<div class="row">
    		<div class="col-lg-12"><?php include("common/breadcrumb.php");?>	</div> 
		    <div class="col-sm-8" style="width:75%">
    			<section class="panel" >  
    				<header class="panel-heading bg-white">
    					<span>Proforma Invoice Detail</span>
    					<span class="text-muted m-l-small pull-right">
    						<a class="label bg-info " onclick="test();" href="javascript:void(0);"><i class="fa fa-print" ></i> Print</a>
                            <a class="label bg-info pdfcls" href="javascript:void(0);"><i class="fa fa-print"></i> PDF</a>
						    <a class="label bg-primary sendmailcls" href="javascript:void(0);"><i class="fa fa-envelope"></i> Send Mail</a>
    					</span>
    				</header>
      
    				<div class="panel-body" id="print_div">
    			<span class="text-muted m-l-small pull-right"></span>
			    <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
    				<div>	 
					    <div class="form-group">
						    <h1>PROFORMA INVOICE</h1>
					    </div>
				    </div>
    				<div class="panel-body">
    					<div class="">
                            <div class="form-group">
                                <div class="row"> 
                                    <div class="col-xs-6 border-bottom">
                                        <div id="client">
                                            <strong>Exporter</strong>
                                            <div><strong>SWISS PAC PVT LTD</strong></div>
                                            <div>Padra Jambusar National highway<br />
                                                    At Dabhasa vaillage,Pin 391440<br/>
                                                    Taluka.Padra, Dist.Vadodara(State Gujarat) India
                                            </div>
                                        </div>
                                    </div>     
    								<div class="col-xm-6  text-right">
									    <?php $proforma_id=$proforma['proforma_id'];
											  $proforma_inv=$obj_pro_invoice->getProformaInvoice($proforma_id);?>
                                        <table class="meta">
                                        	<tbody>
                                       			<tr>
                                        			<th>
                                        				<span>Invoice No. & Date:</span>
                                        			</th>
                                        			<td>
                                                        <span><?php echo $proforma['invoice_number'];?> &nbsp;/&nbsp;
                                                        <?php echo dateFormat(4,$proforma['invoice_date']);?></span>
                                        			</td>
                                        		</tr>
                                        		<tr>
                                        			<th>
                                        				<span>Porforma:</span>
                                        			</th>
                                        			<td>
                                        				<span>/ &nbsp;<?php echo dateFormat(4,$proforma['proforma']);?></span>
                                        			</td>
                                        		</tr>
                                        		<tr>
                                        			<th>
                                        				<span>Buyers Order No. & Date:</span>
			                                        </th>
    	    		                                <td>
        	        			                        <span><?php echo $proforma['buyers_order_no'];?> &nbsp;/&nbsp;
            	                			            <?php echo dateFormat(4,$proforma['buyers_date']);?></span>
                	                        		</td>
	                	                        </tr>
    	                	                    <tr>
        	        	    	                    <th>
            		    	    	                    <span>Country of origin of goods:</span>
                            			            </th>
                                    			    <td>
			                                	        <span><?php echo $proforma['goods_country'];?></span>
            		                            	</td>
                    	                    	</tr>
                        	                </tbody>
                                        </table>
    								</div>
    							</div>
    						</div>
    					</div>
    
    					<div class="form-group">
						    <div class="row"> 
							    <div class="col-xs-6">
								    <div id="client">
									    <strong>Consignee</strong>
									    <h4><?php echo $proforma['customer_name'];?></h4>
									    <div><?php echo $proforma['address_info'];?></div>
									    <div><?php echo $proforma['email'];?></div>
   									</div> 
   								</div>
    
    							<div class="col-xm-6  text-right">    
                                    <table class="meta">
	                                    <thead>
    		                                <th colspan="2">
            			                        <span>Terms of Delivery & Payment:</span>
                        		            </th>
                                	    </thead>
                                    	<tbody>							
                                            <tr>
                                                <th>
                                                    <span>Delivery:</span>
                                                </th>
                                                <td>
                                                    <span><?php echo $proforma['delivery_info'];?></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <span>Mode Of Shipment:</span>
                                                </th>
                                                <td>
                                                    <span>By <?php echo ucwords(decode($proforma['transportation']));?></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>
                                                    <span>Payment Terms:</span>
                                                </th>
                                                <td>
                                                    <span><?php echo $proforma['payment_terms'];?></span>
                                                </td>
                                            </tr>
                                    	</tbody>
                                    </table>
   								</div>
    						</div>
    					</div>
    					<div class="form-group">
						    <div class="row">     
							    <div class="col-xs-6"></div>
							    <div class="col-xm-6  text-right">
                                    <table class="meta">
                                    	<tbody>
		                                    <tr>
        			                            <th>
                    				                <span>Port Of Loading:</span>
                                    			</th>
			                                    <th>
            				                        <span>Final Destination:</span>
                            		
                                   	        </th>
		                                    </tr>
        		                            <tr>
                			                    <td>
                            				        <span><?php echo $proforma['port_loading'];?></span>
			                                    </td><strong></strong>
            			                        <td>
                        				            <span><?php
														$con_id =$proforma['destination'];
														$countrys = $obj_pro_invoice->getCountry($con_id);
														//printr($countrys);
														//$dest = $obj_pro_invoice->
														echo $countrys['country_name'];?></span>
                                    			</td>
		                                    </tr>
        	                            </tbody>
                                    </table>
   								</div>                      
						    </div>
					    </div> 
    				</div>
    				<div class="line"></div>       
    				<?php $n=1; ?>
                    <table class="table"> 
                    	<thead>
                    		<tr>
			                    <th style="width:8%">Sr.No.</th>
            			        <th style="width:50%">Discription of Goods</th> 
			                    <th style="width:20%">Quantity In Units</th>
            			        <?php $currency = $obj_pro_invoice->getCurrencyId($proforma['currency_id']);
			                    // printr($currency); ?>
            			        <th style="width:20%">Rate &nbsp;<?php echo $currency['currency_code']; ?></th>
			                    <th style="width:20%">Amount &nbsp;<?php echo $currency['currency_code']; ?></th>
            		        </tr> 
                    	</thead> 
                    	<tbody>
		                    <?php 
							$total = 0;$total_rate=0; $final_total=0;
        		            // printr($proforma_inv);
                			    foreach($proforma_inv as $invoice_key=>$invoice){
				                    /*$proforma_invoice = $proforma_invoice['proforma_invoice_id'];
                				    $getInvoices = $obj_pro_invoice->getInvoice($proforma_invoice);
				                    foreach($getInvoices as $invoice ) {*/
                				    // printr($invoice);
									//  die;
				                    //get spout details
                					    $getProductSpout = $obj_pro_invoice->getSpout(decode($invoice['spout']));
				                    //get zipper details
                					    $getProductZipper = $obj_pro_invoice->getZipper(decode($invoice['zipper']));
				                    //get accessorie details
                					    $getProductAccessorie = $obj_pro_invoice->getAccessorie(decode($invoice['accessorie']));
                    		?>
                   			<tr>
			                    <td><?php echo $n;?></td>
            			        <?php 
								//echo $invoice['product_id'];
								if($invoice['product_id'] == 3)
								{
									$gusset = floatval($invoice['gusset']).'+'.floatval($invoice['gusset']);
								}
								else
								{
									$gusset = floatval($invoice['gusset']);
								}?>
                                <td style="width:50%">
                                  <div><b>Size:</b>&nbsp;&nbsp;<?php echo floatval($invoice['width']).'mm &nbsp;Width &nbsp;X&nbsp;'.floatval($invoice['height']).'mm &nbsp;Height &nbsp;X&nbsp;'.$gusset.'mm';
								   if($invoice['volume'] > 0)  echo ' ('.$invoice['volume'].')'; ?></div>
                                	<div><b>Make up of pouch:&nbsp;&nbsp;</b><?php echo $invoice['product_name'];?></div>
									<?php 
                                    $quantity = $obj_pro_invoice->getColorDetails($proforma['proforma_id'],$invoice['proforma_invoice_id']);
									//printr($quantity);
									//die;
                                    // $quantity = $obj_pro_invoice->getColorDetails($proforma_id,$invoice['proforma_invoice_id']);
                                    foreach($quantity as $quantity_val) { 
									$colorName = $obj_pro_invoice->getColorName($quantity_val['color']);
									?>
                                    
                                		<div><b>Color:&nbsp;&nbsp;</b><?php echo $colorName['color'];?></div>
                                <?php }?>
                                </td>
                    			<td>
				                    <div></div><br />
                				    <div></div><br />
				                    <?php foreach($quantity as $quantity_val) { ?>
                					    <div><p align="center"><?php echo $quantity_val['quantity']; $total = $total+$quantity_val['quantity'] ;$total_qty = $quantity_val['quantity'];?></p></div>
           				         <?php	}?>
			                    </td>
            			        <td>
				                    <div></div><br />
                				    <div></div><br/>
				                    <?php foreach($quantity as $rate_val) { ?>
                						    <div><p align="center"><?php echo $rate_val['rate'];
						                    $total_rate=$total_rate+$rate_val['rate'];$total_rt = $rate_val['rate'];
					                    //$total_amnt= 
                    				?></p></div>
                                 <?php	}?>
                    			</td>                    
                                <td>
                                    <div></div><br />
                                    <div></div><br />
                                    <?php 
                                    foreach($quantity as $rate_val) {
                                    	$total_amnt = $rate_val['quantity'] * $rate_val['rate'];
                                  	  //echo $total_amnt;
                                   		echo '<div><p align="center">'.$total_amnt.'</p><div>'; 
                                    	$final_total=$final_total+$total_amnt;
                                    }?>
                                </td>
                    
                   			</tr>
                    <?php $n++;}//}?>
                            <tr>
                                <td colspan="2"></td>
                                <td><p align="center"><?php echo $total;?></p></td>
                                <td><p align="center">Total(<?php echo $currency['currency_code']; ?>)</p></td>
                                <td><p align="center"><?php echo $final_total;?></p></td>
                            </tr>
                    	</tbody>
                    </table>
					<?php 
                    //$final_total = 12345656;
                    $number = $obj_pro_invoice->convert_number($final_total);
                    //printr($number);
                    ?>
    				<div><strong>Amount Chargeable</strong>(In Words):&nbsp;&nbsp;&nbsp;<u><?php echo $number;?></u>&nbsp.&nbsp
    				{<?php echo $currency['currency_code']; ?>}.</div>
                 </form>
              		
    				<div class="panel-body">
					    <div class="">
						    <div class="form-group">
							    <div class="row"> 
								    <div class="col-xs-6 border-bottom"> 
									    <strong>Declaration:</strong>
                                        <p>We declare that this Invoice shows the actual price of the goods described and that all particular are true and correct.</p>
                                    </div>
                                    <div class="col-xm-6  text-right">
                                        <table class="meta" style="width:30%">
                                            <tbody>
                                                <tr>
                                                    <td >
                                                        <span><strong>Signature & Date:</strong></span></br>
                                                        <span><strong>For Swiss PAC PVT LTD</strong></span></br></br></br></br>
                                                        <div style="border:1px solid black"></div>
                                                        <span id="prefix"><p align="right">Authorised Signature</p></span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
    							</div>
						    </div>
					    </div>
    				</div>
                   
    				<div class="line"></div><br/>
    				<div id="print_div">	 
					    <div class="form-group">
							<h1>BANK DETAIL</h1>
					    </div>
					</div>
     				<div class="panel-body">
                        <div class="form-group">
                            <div class="col-lg-9">
                                <label class="col-lg-3 control-label"><?php echo $currency['currency_code']; ?></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-9">
                                <section class="panel">
                                    <div class="table-responsive">
                                    
                                        <table class="tool-row table-striped  b-t text-small" id="myTable" border='1'>
                                            <tr><td><label class="col-lg-12 control-label">Bank Account</label></td><td><?php echo $proforma['bank_accnt'];?></td></tr>
                                            <tr><td><label class="col-lg-12 control-label">Beneficiary Address</label></td><td><?php echo $proforma['benefry_add'];?></td></tr>
                                            <tr><td><label class="col-lg-12 control-label">Account Number</label></td><td><?php echo $proforma['accnt_no'];?></td></tr>
                                            <tr><td><label class="col-lg-12 control-label">Beneficiary Bank Name</label></td><td><?php echo $proforma['benefry_bank_name'];?></td></tr>
                                            <tr><td><label class="col-lg-12 control-label">Beneficiary Bank Address</label></td><td><?php echo $proforma['benefry_bank_add'];?></td></tr>
                                            <tr><td><label class="col-lg-12 control-label">Swift Code of India HSBC</label></td><td><?php echo $proforma['swift_cd_hsbc'];?></td></tr>
                                            <tr><td><label class="col-lg-12 control-label">Intermediary Bank Name</label></td><td><?php echo $proforma['intery_bank_name'];?></td></tr>
                                            <tr><td><label class="col-lg-12 control-label">HSBC India Account with Intermediary Bank</label></td><td><?php echo $proforma['hsbc_accnt_intery_bank'];?></td></tr>
                                            <tr><td><label class="col-lg-12 control-label">Swift Code of Intermediary Bank (BIC)</label></td><td><?php echo $proforma['swift_cd_intery_bank'];?></td></tr>
                                            <tr><td><label class="col-lg-12 control-label">Intermediary Bank ABA Routing Number</label></td><td><?php echo $proforma['intery_aba_rout_no'];?></td></tr>
                                        </table>
                                    </div>
                                </section> 
                            </div>
                        </div>
						
    				</div>
                    </div>
                    </div>
                    <div class="form-group">
						    <div class="col-lg-9 col-lg-offset-3">
							    <a class="btn btn-default" href="<?php echo $obj_general->link($rout, '','',1);?>">Cancel</a>
    						</div>
					    </div>
                    
               
            </section>
    		</div>     
		</div>
    </section>    
</section>
<!-- Modal -->
<div class="modal fade" id="smail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    	<form class="form-horizontal" method="post" name="sform" id="sform" style="margin-bottom:0px;">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <input type="hidden" name="sscurrency" id="sscurrency" value="" />
                <input type="hidden" name="sscurrencyrate" id="sscurrencyrate" value="" />
                <h4 class="modal-title" id="myModalLabel">Send Email</h4>
              </div>
              <div class="modal-body">
                   <div class="form-group">
                        <label class="col-lg-3 control-label">Email</label>
                        <div class="col-lg-8">
                             <input type="text" name="smail" placeholder="Email" value="" class="form-control validate[required,custom[email]]">
                        </div>
                     </div> 
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                <button type="submit" name="btn_sendemail" class="btn btn-primary btn-sm">Send</button>
              </div>
   		</form>   
    </div>
  </div>
</div>

<style>
@media print{
  body{ background-color:#FFFFFF; background-image:none; color:#000000 }
  #ad{ display:none;}
  #leftbar{ display:none;}
  #contentarea{ width:100%;}
}

.col-lg-3 {
width: 15%;
}
#client {
    border-left: 6px solid #0087c3;
    float: left;
    padding-left: 6px;
}
h1 {
	background:#333;
    border-bottom: 1px solid #5d6975;
    border-top: 1px solid #5d6975;
    color: #FFF;
    font-size: 2.4em;
    font-weight: normal;
    line-height: 1.4em;
    margin: 0 0 20px;
    text-align: center;
}
article, article address, table.meta, table.inventory { margin: 0 0 3em; }
table.meta, table.balance { float: right; width: 50%; }
table.meta:after, table.balance:after { clear: both; content: ""; display: table; }

/* table meta */

table.meta th { width: 40%; }
table.meta td { width: 60%; }

/* table items */

table { font-size: 75%; table-layout: fixed; width: 100%; }
table { border-collapse: separate; border-spacing: 2px; }
th, td { border-width: 1px; padding: 0.5em; position: relative; text-align: left; }
th, td { border-radius: 0.25em; border-style: solid; }
th { background: #EEE; border-color: #BBB; }
td { border-color: #DDD; }
</style>
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

<script>
 jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#sform").validationEngine();
		$(".sendmailcls").click(function(){			
			$(".note-error").remove();
			$("#smail").modal('show');			
			return false;
		});	
		$(".pdfcls").click(function(){			
			
				$(".note-error").remove();
				var url = '<?php echo HTTP_SERVER.'pdf/proformapdf.php?mod='.encode('proformainvoice').'&token='.rawurlencode($_GET['proforma_id']).'&ext='.md5('php');?>';
				window.open(url, '_blank');
			return false;
		});		
	});

function test() {
	//alert( $('#print_div').html());
    var html="<html>";
	
html+='<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/  media="print"><link rel="stylesheet" href="<?php echo HTTP_SERVER;?>css/font.css"><link rel="stylesheet" href="<?php echo HTTP_SERVER;?>css/app.v2.css" type="text/css" /><link rel="stylesheet" href="<?php echo HTTP_SERVER;?>css/custom.css">';
 //   html+="<link rel='Stylesheet' type='text/css' href='css/print.css' media='print' />";
    html+= $('#print_div').html();
    html+="<style>.col-lg-3 {width: 15%;}#client {    border-left: 6px solid #0087c3;    float: left;    padding-left: 6px;}h1 {	background:#333;    border-bottom: 1px solid #5d6975;    border-top: 1px solid #5d6975;    color: #FFF;    font-size: 2.4em;    font-weight: normal;    line-height: 1.4em;    margin: 0 0 20px;    text-align: center;}article, article address, table.meta, table.inventory { margin: 0 0 3em; }table.meta, table.balance { float: right; width: 50%; }table.meta:after, table.balance:after { clear: both; display: table; }table.meta th { width: 40%; }table.meta td { width: 60%; }table { font-size: 75%; table-layout: fixed; width: 100%; }table { border-collapse: separate; border-spacing: 2px; }th, td { border-width: 1px; padding: 0.5em; position: relative; text-align: left; }th, td { border-radius: 0.25em; border-style: solid; }th { background: #EEE; border-color: #BBB; }td { border-color: #DDD; }</style></html>";	//alert(html);
    var printWin = window.open('','','');
    printWin.document.write(html);
    printWin.document.close();
    printWin.focus();
    printWin.print();
    printWin.close();
}
</script>	
<!-- Close : validation script -->
