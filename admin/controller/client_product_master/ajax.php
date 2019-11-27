<?php 
include("mode_setting.php");

$ajax = $_GET['ajaxfun'];


if($ajax=='UpdateStatus')
{
	//printr($_POST);
	$client_product_id = $_POST['client_product_id'];
	$product_status = $_POST['product_status'];
	
	$obj_product->$ajax($client_product_id,$product_status);
	
}
if($ajax=='getProductSize')
{
	//echo "hii";die;
	$product_id = $_POST['product_id'];
	$zipper_id = $_POST['zipper_id'];
	$client_product_id = $_POST['id'];
	//echo $product_id.' rtt '.$zipper_id;
	
	$data=$obj_product->$ajax($product_id,$zipper_id);
	//printr($data);
	$response = '';	
	$response .= '<div class="form-group">
            			<label class="col-lg-3 control-label "><span class="required">*</span>Quantity Description:</label>
   				</div>';
	if($data){
		$s=1;	
		foreach($data as $item){
					$response .= '<div class="col-sm-3 parent-div">';
					$response .= ' <input type="hidden" readonly="readonly" name="size['.$s.']['.$item['size_master_id'].']" value="'.$item['size_master_id'].'" class="form-control">';
						$volume = '';
						if($item['volume']!=0)
							$volume = $item['volume'];
					$response .= ' <input type="text" readonly="readonly" value="'.$volume.'['.$item['width'].'X'.$item['height'].'X'.$item['gusset'].']" class="form-control">';
					$response .= ' </div>';
					$response .= '<div class="col-sm-9 second-part">';
					$response .= '<table border="0"  width="100%" class="table  b-t text-small">
										<tr>
											<td><b>From Qty</b></td>
											<td> <b>To Qty</b> </td>
											<td><b>Price</b></td>
										</tr>
										<tr>
                							<td colspan="6">
												 <table class="tool-row table  b-t text-small" id="myTable">';
												 	 if($client_product_id !='0')
													 {
														$total_qty_price_data = $obj_product->getclientProQtyPrice($client_product_id,$item['size_master_id']);
													 }
													 else
													 {
														 $total_qty_price_data = $obj_product->getQtyPrice();
														 
													 }
													 if(isset($total_qty_price_data) && !empty($total_qty_price_data)){
														$count = 0;
														foreach ($total_qty_price_data as $qtyRate)
														{	
															if(isset($qtyRate['client_pro_qty_id']) && !empty($qtyRate['client_pro_qty_id']))
															{
																
															}
															else
															{
																$qtyRate['price']='';
															}
															$client_pro_qty_id = isset($qtyRate['client_pro_qty_id']) ? $qtyRate['client_pro_qty_id'] : '';
															$product_size_id = isset($qtyRate['product_size_id']) ? $qtyRate['product_size_id'] : '';
                                         	$response .= '<tr>
																<input type="hidden" name="size['.$s.']['.$item['size_master_id'].'][qty_master]['.$count.'][client_pro_qty_id]" id="client_pro_qty_id_'.$count.'" value="'.$client_pro_qty_id.'" class="form-control validate[required,custom[number]]" >
                                            					<input type="hidden" name="size['.$s.']['.$item['size_master_id'].'][qty_master]['.$count.'][product_size_id]" id="product_size_id'.$count.'" value="'.$product_size_id.'" class="form-control validate[required,custom[number]]" >
                                            					<input type="hidden" name="size['.$s.']['.$item['size_master_id'].'][qty_master]['.$count.'][client_qty_id]" id="client_qty_id_'.$count.'" value="'.$qtyRate['client_qty_id'].'" class="form-control validate[required,custom[number]]" >
																	<td>
																			<input type="text" name="size['.$s.']['.$item['size_master_id'].'][qty_master]['.$count.'][from_qty]" id="form_'.$count.'" value="'.$qtyRate['from_qty'].'" class="form-control validate[required,custom[number]]" placeholder="From Qty" readonly="readonly" />
																		</td>
																		
																	<td>
																			<input type="text" name="size['.$s.']['.$item['size_master_id'].'][qty_master]['.$count.'][to_qty]" id="to_'.$count.'" value="'.$qtyRate['to_qty'].'" class="form-control validate[required,custom[number]]" placeholder="To Qty" readonly="readonly" />
																		</td>
																		
																	<td>
																			<input type="text" name="size['.$s.']['.$item['size_master_id'].'][qty_master]['.$count.'][price]" id="price_'.$s.'_'.$count.'" value="'.$qtyRate['price'].'" class="form-control validate[required,custom[number]] price_text" placeholder="Price" />
																	</td>
															</tr>';
															//,min[1];
															$count++; 
														} 
													} 
										$response .= '</table>
                        
									</td>
								 </tr>
								 
								</table>';
					$response .= '</div>';
		$s++;	
		}
	}
	echo $response;
}

?>