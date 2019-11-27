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
//[sonu] (18-4-2017) for get address_book_id wise data
$add_book_id='0';
$add_url='';
if(isset($_GET['address_book_id']))
{
		$add_book_id = decode($_GET['address_book_id']);
		$add_url = '&address_book_id='.$_GET['address_book_id'];
}
$bradcums[] = array(
	'text' 	=> $display_name.' List',
	'href' 	=> $obj_general->link($rout, $add_url, '',1),
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
$allow_currency_status = $obj_quotation->allowCurrencyStatus($user_type_id,$user_id);
//Start : edit
$edit = '';
if(isset($_GET['quotation_id']) && !empty($_GET['quotation_id'])){
	if(!$obj_general->hasPermission('view',$menuId)){
		$display_status = false;
	}else{
		$quotation_id = base64_decode($_GET['quotation_id']);
	    $data = $obj_digital->getQuotation($quotation_id,'',$obj_session->data['LOGIN_USER_TYPE'],$obj_session->data['ADMIN_LOGIN_SWISS']);
	    $addedByInfo = $obj_digital->getUser($data[0]['user_id'],$data[0]['user_type_id']);
	    $tax_type = $obj_digital->CheckQuotationTax($data[0]['digital_product_quotation_id']);
    }
   // printr($addedByInfo);
}
$user_id_emp = $_SESSION['ADMIN_LOGIN_SWISS'];
//Close : edit
if($display_status){
	if(!empty($data))
	{
	//Add quotation
	//send email code
	if(isset($_POST['btn_sendemail'])){
		if(isset($_POST['smail']) && !empty($_POST['smail'])){
		    
		    $gemail = trim($_POST['smail']);
			if (!filter_var($gemail, FILTER_VALIDATE_EMAIL)) {
			  	$obj_session->data['warning'] = 'Please enter email address!';
				page_redirect($obj_general->link($rout, '&mod=view&quotation_id='.encode($quotation_id), '',1));
			}else{
			    $setCurrencyId = '';
				
				if(isset($_POST['sscurrency']) && !empty($_POST['sscurrency']) ){
					$getSelCurrecnyData = $obj_quotation->getSelCurrencyInfo($_POST['sscurrency']);
					$setCurrencyId = 0;
					if($getSelCurrecnyData){
						$setCurrencyId = $obj_digital->setQuotationCurrency($quotation_id,$getSelCurrecnyData['currency_code'],1,1,$_POST['sel_currency_sec'],$_POST['currency_rate']);
					}
				}
				if(!empty($addedByInfo['lang_id']) && $addedByInfo['lang_id']=='86')
    			{
    			    $obj_digital->sendQuotationEmailInOtherLang($quotation_id,$gemail,$setCurrencyId,$_POST['sel_currency_sec'],$_POST['currency_rate']);
    			}
				$obj_digital->Digital_quotation_mail($quotation_id,$gemail,$setCurrencyId,$_POST['sel_currency_sec'],$_POST['currency_rate']);
		        $obj_session->data['success'] = 'Success : Email send !';
			}
			page_redirect($obj_general->link($rout, '&mod=view&quotation_id='.encode($quotation_id), '',1));
		}else{
			$obj_session->data['warning'] = 'Please enter email address!';
			page_redirect($obj_general->link($rout, '&mod=view&quotation_id='.encode($quotation_id), '',1));
		}
	}
	if(isset($_GET['filter_edit'])){
		$filteredit = $_GET['filter_edit'];
	}else{
		$filteredit = 0;
	}
	$adminCountryId=$obj_digital->getUser($user_id,$user_type_id);
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
      <div class="col-sm-8" style="width:100%">
            <section class="panel">  
                <header class="panel-heading bg-white">
                 <span>Quotation Detail</span>
                 <span class="text-muted m-l-small pull-right">
                    <!--<a class="label bg-info pdfcls" href="javascript:void(0);"><i class="fa fa-print"></i> PDF</a>-->
                    	<a class="label bg-inverse " onclick="test();" href="javascript:void(0);"><i class="fa fa-print" ></i> Print</a>
                    <a class="label bg-primary sendmailclass" href="javascript:void(0);"><i class="fa fa-envelope"></i> Send Mail</a>
                 </span>
              </header>
              <div class="panel-body">
              	<label class="label bg-white m-l-mini">&nbsp;</label>
                	<span class="text-muted m-l-small pull-right">
                    </span>
                 <form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
                   <?php
					  if($allow_currency_status && $data[0]['status'] == 1){
						  if($tax_type['currency'] && $tax_type['currency'] != ''){
							  $notGetCurrency = $tax_type['currency'];
						  }else{
							  $notGetCurrency = 'INR';
						  }
						  $currencys = $obj_quotation->getNewCurrencys();
						  //printr($currencys);
						  if(isset($tax_type['currency']) && $tax_type['currency'] != '')
						  {
						  	$tax_type['currency']=$tax_type['currency'];
						  }
						  else
						  {
						  	$tax_type['currency']='INR';
						  }
						  
						  if($currencys){//printr($currencys);
						   ?>
                              <div class="form-group">
								<label class="col-lg-3 control-label" id="currency_label">Select Currency</label>
								
								<div class="col-lg-9 row">
                                	<input type="hidden" name="curr_name" id="curr_name" value="<?php echo (isset($tax_type['currency']) && $tax_type['currency'] != '')?$tax_type['currency']:'INR'?>" />
                                	<?php if($adminCountryId['country_id'] == '209')
									  {  ?>
									      <div class="col-lg-3">
                                        	<select name="sel_currency" id="sel_currency" class="form-control" >
                                                <?php foreach($currencys as $crr)
    									            {?>
                                                        <option value="<?php echo $crr['currency_code'];?>" price = "<?php echo $crr['price'];?>"  <?php if($tax_type['currency']==$crr['currency_code']) echo 'selected' ;?> attr_curr="drop"><?php echo $crr['currency_code'];?></option>
                                              <?php } ?>
                                              </select>
                                        
                                        
                                        	<select name="sel_currency_secondary" id="sel_currency_secondary" class="form-control"   style="display:none">
                                                <option value="<?php echo $tax_type['currency'];?>" ><?php echo $tax_type['currency'];?></option>
                                                <?php foreach($currencys as $crr)
									            {?>
                                                    <option value="<?php echo $crr['currency_code'];?>" price = "<?php echo $crr['price'];?>" ><?php echo $crr['currency_code'];?></option>
                                          <?php } ?>
                                              </select>
                                        </div>
                                        
                                        <input type="hidden" name="else_curr_rate" id="else_curr_rate" value="1"/>
								<?php }
									  else
									  {
									      ?>
                                	<div class="col-lg-4">
                                        <?php 
											if($currencys)
											{
										?>
                                                <input type="text" name="sel_currency" id="sel_currency" value="<?php echo $tax_type['currency'];?>" attr_curr="select" class="form-control" readonly="readonly"/>
                                                <input type="text" name="sel_currency_secondary" id="sel_currency_secondary" value="" class="form-control" style="display:none"/>
                                               
                                         <?php 
										 	} 
											else
											{
										?>
                                                <input type="hidden" name="else_curr_rate" id="else_curr_rate" value="1"/>
                                        
                                        <?php 
											}
											
											if($_SESSION['LOGIN_USER_TYPE']=='2')
											{
												$id = $obj_quotation->getEmpAdminId($_SESSION['ADMIN_LOGIN_SWISS']);
												
												$user_id_emp = $id;
											}
										?>
										 
                                        <input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['ADMIN_LOGIN_SWISS'];?>"/>
                                        <input type="hidden" name="user_type_id" id="user_type_id" value="<?php echo $_SESSION['LOGIN_USER_TYPE'];?>"/>
                                    </div>
                                    <?php
						  }?>
                                    <div class="col-lg-4">
                                      <?php 
									  if($currencys)
										{
										?>
                                        <input type="text" name="sel_currency_rate"  value="1" readonly="readonly" id="sel_currency_rate" placeholder="Currency Rate" class="form-control validate[condRequired[sel_currency],custom[number]]">
                                    <?php 
									}
									else
									{
									?>
                                     <input type="text" name="sel_currency_rate"  id="sel_currency_rate"  readonly="readonly" placeholder="Currency Rate" class="form-control validate[condRequired[sel_currency],custom[number]]" value="1">
                                    <?php } ?>
                                    </div>
                                    <?php if($adminCountryId['country_id'] != '209')
									  {  ?>
                                    <input type="checkbox" name="mail" id="mail" value="check" class="mail_check"/> Please select Checkbox for Changing currency...!
								    <?php } ?>
								</div>
                                
							  </div>
							  <?php
						  }
						  
					  }
					  else
						  { ?>
						      <input type="hidden" name="sel_currency" id="sel_currency" value="<?php echo $tax_type['currency'];?>" attr_curr="select" class="form-control" readonly="readonly"/>
					<?php }
					  ?>
                   
                   
                   
                   
                   
                   <div id="print_div_details">
                     <div class="form-group">
                        <label class="col-lg-3 control-label">Quotation Number</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font"><?php echo $data[0]['digital_quotation_no'].'&nbsp;&nbsp; [ <small class="text-muted">'.dateFormat(4,$data[0]['date_added']).'</small> ]';?></label>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Customer Name</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                               <?php $add_url_to=ucwords($data[0]['client_name']);
    								if($data[0]['address_book_id']!='0' && $obj_general->hasPermission('view',178))
    									$add_url_to='<a href="'.$obj_general->link('address_book', '&mod=view&address_book_id=' . encode($data[0]['address_book_id']), '', 1).'">'.ucwords($data[0]['client_name']).'</a>'?>
    						   <?php echo $add_url_to;?>
                            <input type="hidden" name="customer_email" id="customer_email" value="<?php echo $addedByInfo['email'];?>" />
                            </label>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Shipment Country</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php echo $adminCountryId['country_name'];?>
                            </label>
                            <?php if(isset($tax_type['tax_type']) && $tax_type['tax_type'] !='')
							{
								if($tax_type['tax_type']=='Out Of Gujarat')
								{
									echo ' <label class="control-label normal-font"> &nbsp;&nbsp;&nbsp;&nbsp;(Out Of Gujarat)  </label>';
								}
								else
								{
									echo '<label class="control-label normal-font"> &nbsp;&nbsp;&nbsp;&nbsp;(With In Gujarat)</label> ';
								}
							}
							?>
                        </div>
                      </div>
                      	
                      <div class="form-group">
                        <label class="col-lg-3 control-label">Product Name</label>
                        <div class="col-lg-4">
                            <label class="control-label normal-font">
                            <?php $product_name = array_column($data, 'product_name');
                                 echo implode(" , ",array_unique($product_name));?>
                            </label>
                        </div>
                      </div>
                      <?php
					    foreach($data as $dat)
					    {
					   		 $result = $obj_digital->getDigiQuotationQuantity($dat['digital_product_quotation_id']);
    						 if($result!='')
    						    $quantityData[] =$result;
					    } 
					    
					   if(!empty($quantityData))
					   {
    						foreach($quantityData as $k=>$qty_data)
    						{
    							
    							foreach($qty_data as $tag=>$qty)
    							{
    								foreach($qty as $q=>$arr)
    								{
    									$new_data[$tag][$q][]=$arr[0];
    								}
    							}	
    						}
    						
						
						foreach($new_data as $k=>$qty_data)
						{ //printr($new_data);
					?>
                      <div class="form-group">
								<label class="col-lg-3 control-label">Price (<?php echo $k;?>)</label> 
								<div class="col-lg-8" >
									<section class="panel">
									  <div class="table-responsive" >
										<table class="table table-striped b-t text-small" >
										  <thead>
											  <tr>
                                                <th>Quntity</th>
                                                <th>Option( Printing Effect )</th>
                                                <th>Dimension (Make Pouch)</th>
                                                <th>Client Price / Pouch</th>
                                               
                                                <th>Total</th>
                                                <?php if($k=='pickup') {
													if($data[0]['shipment_country_id']==111){  ?>
                                                         <th> Price / pouch  With Tax </th>
                                                         <th>Total Price With Tax </th>
                                                 <?php } }?>
                                                 <!--<th> Per Plate Price</th>-->
                                                 
                                                  <?php if($k=='pickup') {
													if($data[0]['shipment_country_id']==111){  ?> <th>Cylinder Price  With Tax </th><?php } } ?>
                                                 
                                              </tr>
										  </thead>
                                          <tbody>
                                          	<?php $i=1;
                                                foreach($qty_data as $skey=>$sdata){
                                                    ?>
                                                    <tr>
                                                        <?php 
                                                        foreach($sdata as $soption){ //printr($soption['quantity_data']['total_color']);  
											                $total_color = explode('==',$soption['quantity_data']['total_color']);
												?>
                                                            <tr id="quotation-row-<?php echo $soption['product_quotation_price_id']; ?>">
                                                                    <th><?php echo $skey;?></th>
                                                                    <td><?php echo ucwords($soption['text']).'<br><b>Printing Effect Option : </b> '.$soption['quantity_data']['printing_effect'].' <br><br> <b style="color:red;">Quoted Printing in : </b>'.$total_color[1].' Colors';?></td>
                                                                    <td><?php echo $soption['dimension'].' ( <b>'.$soption['make_name'].'</b> )'; ?></td>
                                                                    <td><?php echo '<label class="btn btn-info btn-xs">'.$soption['quantity_data']['price'].' '.$soption['quantity_data']['currency_code'].'</label>';?> 
                                                                        <?php echo $soption['quantity_data']['stock_print'];?></td>
        															<td><?php echo $soption['quantity_data']['currency_code'].' '.$soption['quantity_data']['total_price'];?></td>
                                                                         <?php if($k=='pickup') {
        																	    if($data[0]['shipment_country_id']==111) { ?>
                                                                      				<td>
                    																	<?php echo $dat['currency'].' '.$obj_quotation->numberFormate(($soption['quantity_data']['pouch_price_with_tax']),"3");?>
                                                                                     </td>
                                                                                     <td>
                    																	<?php echo $dat['currency'].' '.$obj_quotation->numberFormate(($soption['quantity_data']['total_price_with_tax']),"3");?>
                                                                                     </td>
                                                                         
                                                                        <?php }  }?>  
                                                                    <!--<td><?php //echo $soption['quantity_data']['color_plate_price'].' '.$soption['quantity_data']['currency_code'];?></td>-->
                                                                    <?php if($k=='pickup') {
        																	  if($data[0]['shipment_country_id']==111) { ?>
                                                                  				<td>
                																	<?php if($soption['cylinder_price']>0) {echo (int)$soption['color_plate_price_withtax'];}else '';?>
                                                                                 </td>
                                                                       
        																 <?php } 
        																 }?>
                                                                 </tr>
                                                            <?php
                                                        }
                                                        ?>
                                                    </tr>
                                                    <?php $i++;
                                                }
                                             ?>
                                          </tbody>
										</table>
									  </div>
									</section> 
								</div>
							  </div>
							  </div>
			<?php	} } ?>
                            <div class="form-group">
                                <div class="col-lg-12"><h4><i class="fa fa-tags"></i> Email/PDF History</h4>
                                    <div class="line m-t-large" style="margin-top:-4px;"></div><br/>
                                	<?php $history_data = $obj_digital->getEmailHistories($quotation_id);?>
                                         <section class="panel">
                                          <div class="table-responsive">
                                            <table class="table table-responsive table-striped b-t text-small">
                                              <thead>
                                                 <tr>
                                                     <th>To Email / PDF</th>
                                                     <th>Currency</th>
                                                     <th>Date</th>
                                                     <th>Sent By</th> 
                                                     <!--<th></th>-->
                                                 </tr>
                                               </thead>
                                               
                                               <tbody>
                                                <?php if($history_data) {
                                                      foreach($history_data as $hdata){
                                                            $postedByData = $obj_digital->getUser($hdata['user_id'],$hdata['user_type_id']);
														  ?>
                                                          <tr>
                                                             <td><?php echo $hdata['to_email'];?></td>
                                                             <td><?php echo $hdata['currency_code']; ?></td>
                                                             <td><?php echo date('F d, Y',strtotime($hdata['sent_date'])); ?></td>
                                                             <td><a class="label bg-primary"><?php echo $postedByData['name'];?></a></td>
                                                             <!--<td><a class="label bg-info" onclick="gen_pdf('<?php //echo $curr_code; ?>',<?php //echo $obj_quotation->numberFormate($curr_rate,"3");?>)"><i class="fa fa-print"></i> PDF</a></td>-->
                                                           </tr>
                                                      		<?php
                                                        } 
                                                } else { ?>
                                                    	<tr><td colspan="5">No Email History Available</td></td>    
                                                <?php } ?>       
                                               </tbody>
                                            </table> 
                                            </div>
                                         </section>
                                     </div>
                              </div>
                    		
                      <div class="form-group">
                        <div class="col-lg-9 col-lg-offset-3">
                                                 
                            <a class="btn btn-default" href="<?php echo $obj_general->link($rout, 'filter_edit='.$filteredit.$add_url, '',1);?>">Cancel</a>   
                                <?php if($obj_session->data['ADMIN_LOGIN_SWISS']==1 && $obj_session->data['LOGIN_USER_TYPE']==1) { ?>
                            	<a class="btn btn-primary" href="<?php echo $obj_general->link($rout, 'mod=details&quotation_id='.encode($quotation_id).$add_url, '',1);?>">Details</a>
                            <?php } ?>
                           
                        </div>
                      </div>
                    </form>
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
                <input type="hidden" name="currency_rate" id="currency_rate" value="" />
                <input type="hidden" name="sel_currency_sec" id="sel_currency_sec" value="" />
                <h4 class="modal-title" id="myModalLabel">Send Email</h4>
              </div>
              <div class="modal-body">
                   <div class="form-group">
                        <label class="col-lg-3 control-label">Email</label>
                        <div class="col-lg-8">
                             <input type="text" name="smail" id="cust_email" placeholder="Email" value="" class="form-control validate[required,custom[email]]">
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
.col-lg-3 {
width: 15%;
}
</style>
<!-- Start : validation script -->
<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>

<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>

<script>

jQuery(document).ready(function(){
        // binds form submission and fields to the validation engine
        jQuery("#sform").validationEngine();
});
function gen_pdf(curr_code,curr_rate)
{
	var selcurrency = "<?php echo (isset($tax_type['currency']) && $tax_type['currency'] != '')?$tax_type['currency']:'INR'?>";
	var url = '<?php echo HTTP_SERVER.'pdf/pdf.php?mod='.encode('productQuotation_new').'&token='.rawurlencode(encode($quotation_id)).'&ext='.md5('php');?>&ssc='+selcurrency+'&curr_code='+curr_code+'&curr_rate='+curr_rate;
	window.open(url, '_blank');
}
/*$(".sendmailclass").click(function(){	
	var customer_email=$("#customer_email").val();
	$(".note-error").remove();
	$("#smail").modal('show');
    $("#cust_email").val(customer_email);
	return false;
});*/
function test() {

	 var html="<html>";
	html+='<head>';
	
 html+="<style>.col-lg-3 {width: 15%;}#client {    border-left: 6px solid #0087c3;    float: left;    padding-left: 6px;}h1 {  background:#333;    border-bottom: 1px solid #5d6975;    border-top: 1px solid #5d6975;    color: #FFF;    font-size: 2.4em;    font-weight: normal;    line-height: 1.4em;    margin: 0 0 20px;    text-align: center;}article, article address, table.meta, table.inventory { margin: 0 0 3em; }table.meta, table.balance { float: right; width: 50%; }table.meta:after, table.balance:after { clear: both; display: table; }table.meta th { width: 40%; }table.meta td { width: 60%; }table { font-size: 75%; table-layout: fixed; width: 100%; }table { border-collapse: separate; border-spacing: 0px; }th, td { border-width: 1px; padding: 0.5em; position: relative; text-align: left; }th, td { border-radius: 0; border-style: solid; }th { background: #EEE; border-color: #BBB; }td { border-color: #DDD; }</style></html>";
	



    html+= $('#print_div_details').html();

    html+="</html>";	//alert(html);

    var printWin = window.open('','','');

    printWin.document.write(html);

    printWin.document.close();

    printWin.focus();

    printWin.print();

    printWin.close();

}
$(function(){
   $('.mail_check').change(function(){
	 if($(this).is(':checked')) {
	
	 var user_id=<?php echo $user_id_emp;?>;
	

	//  var user_id=$("#user_id").val();
	  
	  var user_type_id=$("#user_type_id").val();

	  	      var url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getcurrency', '',1);?>");
	  $.ajax({
				url : url,
				method : 'post',
				data : {user_id:user_id,user_type_id:user_type_id},
				success: function(response){
						var val = $.parseJSON(response);
						//console.log(val);
						$("#sel_currency").removeAttr('readonly','readonly');
						$("#sel_currency").hide();
						$("#sel_currency_secondary").show();
						$("#sel_currency_secondary").val(val.result);
							
						$("#sel_currency_rate").attr('readonly','readonly');
						$("#sel_currency_rate").val(val.price);
						
						$("#sel_currency").attr('readonly','readonly');
						$("#sel_currency_secondary").attr('readonly','readonly');
				
				},
				error: function(){
					return false;	
				}
				});
	  
              
      } else {
	  
		 var sel_curr=$("#curr_name").val();
		 $("#sel_currency_secondary").hide();
		 $("#sel_currency").show();
		 $("#sel_currency").removeAttr('readonly','readonly');
		 $("#sel_currency").val(sel_curr);
		 $("#sel_currency_rate").removeAttr('readonly','readonly');
		 $("#sel_currency_rate").val('1');
		 $("#sel_currency").attr('readonly','readonly');
		 $("#sel_currency_rate").attr('readonly','readonly');
		 $("#sel_currency_secondary").val('');
              
      }
   });
});
$(".sendmailclass").click(function(){	
		    
		    
		    var attr_curr = $("#sel_currency").find('option:selected').attr("attr_curr");
		    if(attr_curr=='drop')
		        var selcurrency = '<?php echo $tax_type['currency']; ?>';
		    else      
                var selcurrency = $("#sel_currency").val();
		//	console.log(selcurrency);
			var else_curr_rate=$("#else_curr_rate").val();
			var sel_currency_rate=$("#sel_currency_rate").val();
			var sel_currency_secondary=$("#sel_currency_secondary").val();	
			var customer_email=$("#customer_email").val();
			//alert(selcurrency);			
			if(selcurrency.length == 0 ){
				$(".note-error").remove();
				$("#sel_currency").after('<span class="note-error required">Please select Currency</span>');
			}else{
				$(".note-error").remove();
				$("#smail").modal('show');
				$("#sscurrency").val(selcurrency);
				$("#currency_rate").val(sel_currency_rate);
				$("#sel_currency_sec").val(sel_currency_secondary);
				$("#else_cuur_rate").val(else_curr_rate);
				$("#cust_email").val(customer_email);
				//$("#cust_email").attr('readonly','readonly');
			}
			return false;
		});
</script>	
<!-- Close : validation script -->
<?php }else
{?><section id="content">
  <section class="main padder">
    <div class="clearfix">
      <h4><i class="fa fa-edit"></i> <?php echo $display_name;?></h4>
    </div>
    <div class="row">
    	<div class="col-lg-12">
	       <?php include("common/breadcrumb.php");?>	
        </div> 
         <div class="col-sm-8" style="width:100%">
            <section class="panel">  
                <header class="panel-heading bg-white">
                 <span>Quotation Detail</span>
                 <span class="text-muted m-l-small pull-right">
                     
                 </span>
              </header>
              <div class="panel-body">No Record Found !
              </div>
              </section>
              </div>
		</div>
		</section>
		</section><?php }
} else { 
		include(SERVER_ADMIN_PATH.'access_denied.php');
	}
?>