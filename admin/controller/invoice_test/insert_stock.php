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
	/*$proforma=$obj_pro_invoice->getProformaData($invoice_no);
	$proforma_inv=$obj_pro_invoice->getProformaInvoice($invoice_no);
	printr($proforma);
	printr($proforma_inv);die;*/
 $invoice=$obj_invoice->getInvoiceNetData($invoice_no);//printr($invoice);
 $invoice_product_second=$obj_invoice->getInvoiceProduct_insert($invoice['invoice_id']);///printr($invoice_product_second);
 $addedByInfo = $obj_invoice->getUser($invoice['order_user_id'],4);
if($display_status)
{
	if(isset($_POST['btn_add'])){
		$obj_invoice->addRackDetail($_POST);
		page_redirect($obj_general->link('invoice_test', 'mod=insert_stock&invoice_no='.$_GET['invoice_no'].'&inv_status=2', '',1));
	}
	if(isset($_POST['btn_convert'])){
		
		$obj_invoice->convertInPurchase_new($invoice['invoice_id']);
		$obj_session->data['success'] = CONVERT;
		page_redirect($obj_general->link('invoice_test', 'mod=index&invoice_no='.$_GET['invoice_no'].'&inv_status=2', '',1));
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
					<header class="panel-heading bg-white">Invoice Number : <b><?php echo $invoice['invoice_no'];?></b></header>
				  
					  <div class="panel-body">                                          
						<form class="form-horizontal" method="post" name="form" id="form" enctype="multipart/form-data">
								<?php if($invoice['customer_dispatch']==0){ ?>
								    <div class="table-responsive">
									 <input type="hidden" name="invoice_no" id="invoice_no" value="<?php echo $invoice['invoice_no']; ?>" />
									 <input type="hidden" name="company_name" id="company_name" value="<?php echo $invoice['customer_name']; ?>" />
															
									 <table class="table b-t text-small table-hover">
									  <thead>
										<tr>
											  <th>Sr. No.</th>
											  <th>Product Code</th>
											  <th>Invoice Qty</th>
											  <th>Rate</th>
											  <th>Remaining Qty To Add</th>
											  <th>Box Detail</th>
											  <th>Pallet Selection</th>
											  <th>Rack Selection</th>
										</tr>
									  </thead>
									  <tbody>
											<?php 
											$done=0;$count=count($invoice_product_second);$i=1;
											foreach($invoice_product_second as $invoice_d )
											{ $case=$label=$prepress='';//printr($invoice_d['product_code_id']);
											  // printr($invoice_d);
												?>
												 
												<tr id="invoice_product_id_<?php echo $invoice_d['invoice_product_id']; ?>">
													 <td><?php echo $i;?></td>
													 <td>
															<?php if($invoice_d['product_code_id']==0)
															{   $custom=$obj_rack_master->getCustomDigitalProduct($invoice_d['invoice_product_id']);//printr('fsdff'); ?>
																		<select name="<?php echo $invoice_d['invoice_product_id'];?>[product_code]" id="product_code_<?php echo $invoice_d['invoice_product_id'];?>" class="form-control chosen_data">
																				<option value="">Select Product Code</option>
																				<?php foreach($custom as $cust){ ?>
																						<option value="<?php echo $cust['product_code_id'];?>"><?php echo $cust['product_code'];?></option>
																				<?php } ?>
																		</select>
													  <?php }
															else
															{
																echo  '<b>'.$invoice_d['product_code'].'</b>';
																echo '<input type="hidden" name="'.$invoice_d['invoice_product_id'].'[product_code]" id="" value="'.$invoice_d['product_code_id'].'" />';
															}?>
															
															<input type="hidden" name="<?php echo $invoice_d['invoice_product_id'];?>[order_no]" id="" value="<?php echo $invoice_d['buyers_o_no']; ?>" />
															<input type="hidden" name="<?php echo $invoice_d['invoice_product_id'];?>[rack_status]" id="" value="<?php echo $invoice_d['rack_status']; ?>" />
															<input type="hidden" name="<?php echo $invoice_d['invoice_product_id'];?>[invoice_product_id]" id="" value="<?php echo $invoice_d['invoice_product_id']; ?>" />
													</td>
													<td><?php echo $invoice_d['qty'];?><?php //echo 'test cvdfdg===>'.$invoice_d['invoice_product_id']; ?></td>
													<td><?php echo $invoice_d['rate'];?></td>
													<td><?php echo $invoice_d['rack_status'];?></td>
													<td> 
														<?php if($invoice_d['rack_status']!=0)
														{ ?>
															<div class="col-lg-10">
																<div class="form-control scrollbar scroll-y" style="height:200px" id="groupbox">
																	<div class="checkbox col-lg-10">
																		<?php $boxes = $obj_invoice->getBoxForProduct($invoice_d['invoice_id'],$invoice_d['invoice_product_id'],$invoice_d['invoice_color_id']);//printr($boxes);
																			//	printr(count($boxes).'nb');
																			//	printr($boxes);
																				 foreach($boxes as $box)
																				 {  $pre='';
																				     if($invoice_d['product_id']==6)
																				     {
																				        $pre = ' KGS';
																				        $box['qty'] = $box['net_weight'];
																				     }
																					 if($box['parent_id']==0)
																					 {
																					 ?>
																						<div class="form-group">
																							  <div class="checkbox col-lg-6"><label>
																									<input type="checkbox" class="validate[minCheckbox[1]]" name="<?php echo $invoice_d['invoice_product_id'];?>[box][]" id="<?php echo $invoice_d['invoice_product_id'];?>" value="<?php echo $box['box_unique_number'].'=='.$box['in_gen_invoice_id'].'=='.$box['qty'];?>"><?php echo '<b>['.$box['box_no'].']</b>-'.$box['box_unique_number'];?>==<?php echo $box['qty'].''.$pre;?></label>	
																							  </div>
																						</div>
																		<?php       }
																					else
																					{ $child = $obj_invoice->getparentbox_number($box['parent_id']);//printr($child);
																						?>
																						<div class="form-group">
																							  <div class="checkbox col-lg-6"><label>
																									<input type="checkbox" class="validate[minCheckbox[1]]" name="<?php echo $invoice_d['invoice_product_id'];?>[box][]" id="<?php echo $invoice_d['invoice_product_id'];?>" value="<?php echo $child['box_unique_number'].'=='.$box['in_gen_invoice_id'].'=='.$box['qty'];?>"><?php echo '<b>['.$child['box_no'].']</b>-'.$child['box_unique_number'];?>==<?php echo $box['qty'].''.$pre;?></label>	
																							  </div>
																						</div>
																		<?php   	}
																		  }?>
																		</div>
																	</div>
																	<a class="btn btn-default btn-xs selectall1 mt5" href="javascript:void(0);">Select All</a>
																<a class="btn btn-default btn-xs unselectall1 mt5" href="javascript:void(0);">Unselect All</a>
																</div>
																 
														</td>
														<td>
															<?php $goods_master = $obj_goods_master->getGoodsMaster('','',$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);?>
															<select class="form-control validate[required]" name="<?php echo $invoice_d['invoice_product_id'];?>[pallet]" id="pallet_<?php echo $invoice_d['invoice_product_id'];?>" onchange="get_pallet(<?php echo $invoice_d['invoice_product_id'];?>)" style="width:inherit;">
																<option value="">Select Pallet</option>
																<?php foreach($goods_master as $gd){ ?>
																		<option value="<?php echo $gd['row'].'='.$gd['column_name'].'='.$gd['goods_master_id'];?>"><?php echo $gd['name'];?></option>
																<?php } ?>
															</select>
														</td>
														<td>
																<div id="rack_number_<?php echo $invoice_d['invoice_product_id'];?>"></div>
    														<?php }
    														 else
    														 {
    															 echo '<span style="color:red">Done.</span>';
    															$done++;	 
    														 }?>
										        	    </td>
								                </tr>
								  
											<?php 
												$inv_pro_id[]=$invoice_d['invoice_product_id'];
												//printr($inv_pro_id);
												$pro_code= htmlspecialchars(json_encode($inv_pro_id), ENT_QUOTES, 'UTF-8');
											$i++;	
											}?>
									  </tbody>
									 </table>
								
							</div>    
							    <?php } ?>
							<div class="form-group">
								 <div class="col-lg-9 col-lg-offset-3"> 
								   <?php ///$link='mod=index&inv_status='.$_GET['inv_status'];
								  // printr($count,'=='.$done);
									if($count==$done || $invoice['customer_dispatch']==1){?>
										<button type="submit" class="btn btn-primary btn-sm pull-right ml5" onclick="convert(1)" name="btn_convert" id="btn_convert" >Convert to Purchase</button>
									<?php } else { ?>
										 <button type="submit" class="btn btn-primary btn-sm pull-right ml5" onclick="convert(0)" name="btn_add" id="btn_add" >Add Stock</button>
									<?php } ?>
									<!--<button type="button" class="btn btn-primary btn-sm pull-right ml5" name="stock" id="stock" >Add</button>
								   <a class="btn btn-default" href="<?php //echo $obj_general->link($rout, $link, '',1);?>"></a>-->
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
			width: 350px !important; /* or any value that fits your needs */
		}
	</style>
	<!-- Start : validation script -->
	
	<link rel="stylesheet" href="<?php echo HTTP_SERVER;?>js/validation/css/validationEngine.jquery.css" type="text/css"/>
	<script src="<?php echo HTTP_SERVER;?>js/validation/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo HTTP_SERVER;?>js/validation/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
	<script src="https://harvesthq.github.io/chosen/chosen.jquery.js" type="text/javascript"></script>
	<link rel="stylesheet" href=" https://harvesthq.github.io/chosen/chosen.css" type="text/css"/> 
	
	<script>
		
		 jQuery(document).ready(function(){
			
			// binds form submission and fields to the validation engine
			jQuery("#form").validationEngine();	
			$(".chosen_data").chosen();
			$(".selectall1").click(function(){
				$(this).parent().children().find(':checkbox').prop('checked', true);
			});
			$(".unselectall1").click(function(){
				$(this).parent().children().find(':checkbox').prop('checked', false);
			});
				
		});
		 
		function get_pallet(invoice_product_id)
		{
			var rack_val=$("#pallet_"+invoice_product_id+" option:selected").val();
			var arr = rack_val.split('=');
			var row = arr[0];
			var col = arr[1];
			var goods_master_id = arr[2];
			var country_id = "<?php echo $addedByInfo['country_id']?>";
			var order_status_url = getUrl("<?php echo $obj_general->ajaxLink($rout, 'mod=ajax&fun=getLabel_pallet', '',1);?>");
			$.ajax({
					url : order_status_url,
					method : 'post',
					data : {row:row,col:col,goods_master_id:goods_master_id,invoice_product_id:invoice_product_id,country_id:country_id},
					success: function(response){
						console.log(response);
						$("#rack_number_"+invoice_product_id).html(response);
				},
				error: function(){
					return false;	
				}
			});
		}
		function convert(n)
		{
			//$(".chosen-container").removeClass('validate[required]');
			
			$('.chosen_data').each(function() {
				var id = $(this).attr('id'); 	
				var value = $("#"+id+"").val();
				if($("#"+id+"").val()!='')
				{
					$("#"+id+"_chosen").attr('class','chosen-container chosen-container-single');
					$(".formError").remove();
				}
				else
				{
					if(n==0)
					    $("#"+id+"_chosen").attr('class','chosen-container chosen-container-single validate[required]');
				}
			});
			
		}
		
	</script> 
	<!-- Close : validation script -->
<?php } else { 
		include(DIR_ADMIN.'access_denied.php');
	}
?>