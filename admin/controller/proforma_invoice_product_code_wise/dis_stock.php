<?php
include("mode_setting.php");
//Start : bradcums
$bradcums = array();
$bradcums[] = array(
  'text'  => 'Dashboard',
  'href'  => $obj_general->link('dashboard', '', '',1),
  'icon'  => 'fa-home',
  'class' => '',
);

$bradcums[] = array(
  'text'  => $display_name.' List',
  'href'  => $obj_general->link($rout, 'mod=index', '',1),
  'icon'  => 'fa-list',
  'class' => '',
);

$bradcums[] = array(
  'text'  => $display_name.' Detail',
  'href'  => '',
  'icon'  => 'fa-edit',
  'class' => 'active',
);
//Close : bradcums
$user_type_id = $_SESSION['LOGIN_USER_TYPE'];
$user_id = $_SESSION['ADMIN_LOGIN_SWISS'];
//Start : edit
$edit = '';
//echo HTTP_SERVER; 
//$menuId='220';
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
 $invoice=$obj_invoice->getInvoiceNetData($invoice_no);//printr($invoice);
 $invoice_product_second=$obj_pro_invoice->getInvoiceProduct($invoice['invoice_id']);//printr($invoice_product_second);
 $addedByInfo=$obj_pro_invoice->getUser($_SESSION['ADMIN_LOGIN_SWISS'],$_SESSION['LOGIN_USER_TYPE']);
//printr($invoice_product_second);
//printr($addedByInfo);

if($display_status)
{
	if(isset($_POST['btn_convert'])){
		$obj_invoice->genSalesInvoice($invoice['invoice_id']);
		$obj_session->data['success'] = GEN;
		page_redirect($obj_general->link('sales_invoice', 'mod=index&is_delete=0', '',1));
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
				<div id="test"></div>
		  <div class="col-sm-12">
				<section class="panel">  
					<header class="panel-heading bg-white">Invoice Number : <b><?php echo $invoice['proforma_no'];?></b></header>
				  
					  <div class="panel-body">                                          
						<form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
							<?php if($invoice['customer_dispatch']==0) { ?>
								<div class="table-responsive">
									 <table class="table b-t text-small">
									  <thead>
										<tr>
											  <th>Product Code</th>
											  <th>Invoice Qty</th>
											  <th>Rate</th>
											  <th>Remaining Qty To Dispatch</th>
											  <th>Box Detail</th>
										</tr>
									  </thead>
									  <tbody>
											<?php  
											$done=0;$count=count($invoice_product_second);
											foreach($invoice_product_second as $invoice_d )
											{ $case=$label=$prepress='';
											   // printr($invoice_d['product_code_id']);
												?>
												 
												<tr id="invoice_product_id_<?php echo $invoice_d['invoice_product_id']; ?>">
													 <td>
															<b><?php 
																$product_code['product_id']=0;
																if($invoice_d['product_code_id'] == '-2')
																{
																	echo 'Cylinder <br>';
																
																}elseif($invoice_d['product_code_id'] == '-1')
																{
																	echo 'Custom <br>'. $invoice_d['product_description'];
																}
																elseif($invoice_d['product_code_id'] == '1194')
																{
																	echo 'Sample <br>';	
																}
																elseif($invoice_d['product_code_id'] == '0')
																{
																	if($invoice_d['rate']!='0.0000')
																		echo'<b>Freight Charges : </b>'.$invoice_d['rate'];
																		
																	if($invoice_d['case_breaking_fees']!='0.0000')
																	{
																		echo'<br><b>Case Breaking Charges : </b>'.$invoice_d['case_breaking_fees'];
																		$case = '<br>'.$invoice_d['case_breaking_fees'];
																	}
																	if($invoice_d['label_charges']!='0.0000')
																	{
																		echo'<br><b>Label Charges : </b>'.$invoice_d['label_charges'];
																		$label = '<br>'.$invoice_d['label_charges'];
																	}
																	if($invoice_d['prepress_charges']!='0.0000')
																	{
																		echo'<br><b>Prepress Charges : </b>'.$invoice_d['prepress_charges'];
																		$prepress = '<br>'.$invoice_d['prepress_charges'];
																	}
																	
																	
																		
																}
																else
																{
																	  $product_code= $obj_invoice->getProductCode($invoice_d['product_code_id']);
																	  echo $product_code['product_name'].'<br>'.$product_code['product_code'];
																	  $product_code['product_id']=$product_code['product'];
																}//printr($product_code);
																?>
															</b>
															<input type="hidden" id="invoice_pcode_<?php echo $invoice_d['invoice_product_id']; ?>" value="<?php echo $invoice_d['product_code_id']; ?>" />
															</td>
															
													   <td>
														<?php echo $invoice_d['qty'];?>
														<input type="hidden" id="invoice_qty_<?php echo $invoice_d['invoice_product_id']; ?>" value="<?php echo $invoice_d['qty']; ?>"  />
													  </td>
													  <td>
														  <?php
															if($invoice_d['rate']!='0.0000')
																 echo $invoice_d['rate'];
															echo $case;
															echo $label;
															echo $prepress;?>
														   <input type="hidden" id="invoice_rate_<?php echo $invoice_d['invoice_product_id']; ?>" value="<?php echo $invoice_d['rate']; ?>"  />
													  </td>
											   
													  <td><?php echo $invoice_d['rack_remaining_qty'];?></td>
														<td>
														<?php if($invoice_d['rack_remaining_qty']!=0)
															 { ?>
																<input type="hidden" name="<?php echo $invoice_d['invoice_product_id'];?>[segments]" id="segments" value="">
																<input type="hidden" name="<?php echo $invoice_d['invoice_product_id'];?>[proforma_no]" id="proforma_no" value="<?php echo $invoice['proforma_no'];?>"/>
																<input type="hidden" name="<?php echo $invoice_d['invoice_product_id'];?>[invoice_no]" id="invoice_no_model" value="<?php echo $invoice['invoice_no'];?>">
																<input type="hidden" name="<?php echo $invoice_d['invoice_product_id'];?>[company_name]" id="company_name" value="<?php echo $invoice['customer_name'];?>">
																<input type="hidden" name="<?php echo $invoice_d['invoice_product_id'];?>[courier_id]" id="courier_id" value="0">
																<input type="hidden" name="<?php echo $invoice_d['invoice_product_id'];?>[courier_india]" id="courier_india" value="">
																<input type="hidden" name="<?php echo $invoice_d['invoice_product_id'];?>[courier_amount]" id="courier_amount" value="0">
																<input type="hidden" name="<?php echo $invoice_d['invoice_product_id'];?>[product_code_id]" id="product_code_id" value="<?php echo $invoice_d['product_code_id'];?>">
																<input type="hidden" name="<?php echo $invoice_d['invoice_product_id'];?>[invoice_product_id]" id="invoice_product_id" value="<?php echo $invoice_d['invoice_product_id'];?>">
																<input type="hidden" name="<?php echo $invoice_d['invoice_product_id'];?>[product_id]" id="product_id" value="<?php echo $product_code['product_id'];?>">
																<input type="hidden" name="<?php echo $invoice_d['invoice_product_id'];?>[sales_qty]" id="sales_qty_<?php echo $invoice_d['invoice_product_id'];?>" value="<?php echo $invoice_d['rack_remaining_qty'];?>">
															<?php 	
																$a=array();
																$r_qty=array(); 
																$rack_qty = $obj_rack_master->getRackQty($invoice_d['product_code_id'],$invoice['user_type_id'],$invoice['user_id'],'','','',3);
																//printr($rack_qty);?>
																<div class="form-group">
																	<div class="checkbox col-lg-10">
																		<table border="1" width="100%">
																			<tr>
																				<th>Rack Name</th>
																				<th>Rack Position</th>
																				<th>Qty</th>
																				<th>Box Details</th>
																			</tr>
																			
																			<?php if(!empty($rack_qty))
																				{ $j=1;$goods_id=array();
																					foreach($rack_qty as $rack)
																					{ //printr($rack);
																						$d=1;
																						$rc = $rack['row'].'@'.$rack['column_name'];
																						for($i=1;$i<=$rack['g_row'];$i++)
																						{
																							for($r=1;$r<=$rack['g_col'];$r++) 
																							{
																								$n = $i.'@'.$r;
																								if($rc==$n)
																								{
																									$col_row = $rc;
																									$k=$d;
																									$r_no[]=$k;	
																								}
																								$d++;
																							}
																						}
																						$dispatch_qty=$obj_rack_master->gettotaldispatchSales($rack['stock_id'],$invoice['user_type_id'],$invoice['user_id']);
																						if($addedByInfo['user_id']==44 || $addedByInfo['user_id']==19)
                                                        								    $lable = $obj_rack_master->getRackLabelCanada($col_row,$rack['goods_master_id']);
                                                        								else
                                                        								    $lable = $obj_rack_master->getLabel($col_row,$rack['goods_master_id']);
																							//printr($lable);
																						$rm_qty=$rack['store_qty']-$dispatch_qty['total'];
																						$l =$k;
																						if($lable!='')
																							$l = $lable;
																						
																						//printr($rack['store_qty']);printr($dispatch_qty['total']);
																						if($rm_qty!=0)
																						{
																							  $inv_no = "'".$invoice['invoice_no']."'"; 
																							  $proforma_no = "'".$invoice['proforma_no']."'";
																							  $customer_name ="'".addslashes($invoice['customer_name'])."'";
																								echo '<tr>
																											<td>'.$rack['name'].'</td>
																											<td align="center">'.$l.'</td>
																											<td>'.$rm_qty.'</td>
																											<td><div class="checkbox col-lg-10">
																											    ';
																											   $box=explode(',',$rack['box_no']);
																											   
																											   foreach($box as $bx)
																											   {
																												   $b= explode('=',$bx);//printr($b);
																												   $dispatch_box=$obj_rack_master->gettotaldispatchSales($b[2],$invoice['user_type_id'],$invoice['user_id']);//printr($dispatch_box);
																												   $rem_qty = $b[1] - $dispatch_box['total'];
																												   $box_number = $b[0].'==';
																												   if($b[0]=='')
																													  $box_number='';
																												   $boxno = $obj_rack_master->getBoxNo($b[0]);
																												   echo '<input type="hidden" name="'.$invoice_d['invoice_product_id'].'[alldata]['.$b[2].']" id="product_code_id" value="'.$rack['row'].'='.$rack['column_name'].'='.$rack['goods_master_id'].'">';
																												   if($rem_qty!=0)
																												   {?>
																													  <div class="form-group">
																														  <div class="checkbox col-lg-7"><label>
																																<input type="checkbox" class="validate[minCheckbox[1]]" name="<?php echo $invoice_d['invoice_product_id'];?>[box][]" id="<?php echo $invoice_d['invoice_product_id'];?>" value="<?php echo $b[0];?>==<?php echo $b[2];?>==<?php echo $rem_qty;?>==<?php echo $rack['row'];?>==<?php echo $rack['column_name'];?>==<?php echo $rack['goods_master_id'];?>"> 
																																<?php echo '<b>['.$boxno['box_no'].']</b>- '.$box_number.''.$rem_qty;?></label>	
																														  </div>
																														  <div class="col-lg-5">
																															<input type="text" name="<?php echo $invoice_d['invoice_product_id'];?>[dispatch_qty][<?php echo $b[2];?>]" value="" placeholder="Dispatch Qty" class="form-control" id="dispatch_qty_<?php echo $b[2];?>">
																														   </div>
																														   
																													  </div>
																											  <?php } 
																											   } ?>
    																								           <a class="btn btn-default btn-xs selectall1 mt5" href="javascript:void(0);">Select All</a>
    																                                            <a class="btn btn-default btn-xs unselectall1 mt5" href="javascript:void(0);">Unselect All</a>
    																                                            
																                                      <?php	echo '</td>';
																								echo '</tr>';
																							$a[]=$k;
																							$r_qty[] =$rm_qty.'='.$k;
																							$goods_id[] = $rack['row'].'='.$rack['column_name'].'='.$rack['goods_master_id'].'='.$rack['name'].'='.$invoice_d['invoice_product_id'].'='.$invoice_d['product_code_id'].'='.$l.'='.$rm_qty;
																						}
																						$j++;
																					}
																					
																				}
																				else
																				{
																					echo '<tr><td colspan="4">No Records Found!!!</td></tr>';
																				}	?>
																			
																		</table>
																	</div>
																</div>
													 <?php } 
														   else
														   {
															   echo '<span style="color:red">Dispatched</span>';
														  $done++;	 } ?>
														</td>
													
								  </tr>
								  
											<?php 
												$inv_pro_id[]=$invoice_d['invoice_product_id'];
												//printr($inv_pro_id);
												$pro_code= htmlspecialchars(json_encode($inv_pro_id), ENT_QUOTES, 'UTF-8');
												
											}?>
											<input type="hidden" name="invoice_product_id_all" id="invoice_product_id_all" value="<?php echo $pro_code;?>">
									  </tbody>
									 </table>
								
							</div>  
							<?php } ?>
							<div class="form-group">
								 <div class="col-lg-9 col-lg-offset-3"> 
								   <?php ///$link='mod=index&inv_status='.$_GET['inv_status'];
								  // printr($count,'=='.$done);
									if(($count==$done || $invoice['customer_dispatch']==1) || empty($invoice_product_second)){?>
								
									  	<button type="submit" class="btn btn-primary btn-sm pull-right ml5" name="btn_convert" id="btn_convert">Generate Tax Invoice</button>
								    	
								<?php 	}else { ?> 
										 <button type="button" class="btn btn-primary btn-sm pull-right ml5" name="btn_dispatch" id="btn_dispatch" >Dispatch Stock</button>
									<?php } ?>
								
								   <a class="btn btn-default" href="<?php echo $obj_general->link($rout, $link, '',1);?>">Cancel</a>
								 
							   </div>
							</div>  
						</form>
					</div>
			   </section>    
		  </div>
		</div>
	  </section>
	</section>
	<style>
		.chosen-container.chosen-container-single {
				width: 300px !important;
			}
	</style>
	<!-- Start : validation script -->
	
	<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
	<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
	
	<script src="https://harvesthq.github.io/chosen/chosen.jquery.js" type="text/javascript"></script>
	<link rel="stylesheet" href="https://harvesthq.github.io/chosen/chosen.css" type="text/css"/>

	<script>
		
		 jQuery(document).ready(function(){
			
			// binds form submission and fields to the validation engine
				jQuery("#form").validationEngine();	
			
			$(".choosen_data").chosen();
			$(".selectall1").click(function(){
				$(this).parent().children().find(':checkbox').prop('checked', true);
			});
			$(".unselectall1").click(function(){
				$(this).parent().children().find(':checkbox').prop('checked', false);
			});
			
		 });
		 /*function removeClass()
		 {
			$("#select2-option").removeClass('validate[required]');
		 }
		function getGenId(invoice_product_id)
		{
			if($("#check_"+invoice_product_id).prop('checked') == true)
			{
				$("#rack_"+invoice_product_id).show();
				$("#box_sel"+invoice_product_id).show();
			}
			else
			{
				$("#rack_"+invoice_product_id).hide();
				$("#box_sel"+invoice_product_id).hide();
			}
		}*/
		function get_pallet(invoice_product_id)
		{
			var rack_val=$("#rack_"+invoice_product_id).val();
			var arr = rack_val.split('=');
			var row = arr[0];
			var col = arr[1];
			var goods_master_id = arr[2];
			
			var order_status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getLabel_pallet', '',1);?>");
			$.ajax({
					url : order_status_url,
					method : 'post',
					data : {row:row,col:col,goods_master_id:goods_master_id,invoice_product_id:invoice_product_id},
					success: function(response){
						$("#rack_number_"+invoice_product_id).html(response);
				},
				error: function(){
					return false;	
				}
			});
			$("#qty_insert_"+invoice_product_id).show();
			$("#btn_done_"+invoice_product_id).show();
		}
		$("#btn_dispatch").click(function(){
			var arr = jQuery.parseJSON($('#invoice_product_id_all').val());
			var condition = 0;	
			if($("#form").validationEngine('validate'))
			{
				for(var i=0;i<arr.length;i++)
				{
					 var total_dis_qty = 0;
					 var sales_qty = parseInt($("#sales_qty_"+arr[i]).val());
					 $.each($("input[id='"+arr[i]+"']:checked"), function(){ 
							
							var value = $(this).val();
							var result = value.split('==');
							$("#dispatch_qty_"+result[1]).attr("class","form-control validate[required,custom[number]]");
							var rack_qty = result[2];
							var dispatch_qty = parseInt($("#dispatch_qty_"+result[1]).val());
							total_dis_qty += parseInt(result[2]);  
							  if(dispatch_qty>rack_qty)
							  {
									alert('Your Rack Qty is '+rack_qty+'. Please Enter Proper Qty!! ');
									$("#dispatch_qty_"+result[1]).val('');
									return false;
							  }
							  else if(dispatch_qty>sales_qty)
							  {
									alert('Your Remaining Qty is '+sales_qty+'. You Selected '+total_dis_qty+' & it is more Than sales qty. Please Select Proper Box!! ');
									$("#dispatch_qty_"+result[1]).val('');
									return false;
							  }
							  else
							  {           
									//$("#dispatch_qty_"+result[1]).attr("class","form-control validate[required,custom[number]]");
									//if($("#form").validationEngine('validate'))
			                        //{
								    	condition = 1;	
			                        //}
							  }
					 });
				}
				if(condition==1)
				{
					var label_url = getUrl("<?php  echo $obj_general->ajaxLink($rout, '&mod=ajax&fun=savedispatch_racknotify', '',1);?>");
					var formData = $("#form").serialize();
					$.ajax({
							url : label_url,
							method : 'post',
							data : {formData:formData},
							success: function(response){
							    location.reload();	
						},
						error: function(){
							return false;	
						}
					});
				}
			}
		});
	</script> 
	<!-- Close : validation script -->
<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>