<?php

include("mode_setting.php");

$fun = $_GET['fun'];
if($fun == 'product_code'){
	//echo "hi";
	$product_code = $_POST['product_code'];
	//echo $product_code;
	$result = $obj_domestic_stock->getProductCd($product_code);
//	printr($result);
	echo json_encode($result);
}

if($_GET['fun']=='getrackdataforindia') {
	$rack_qty = $obj_domestic_stock->getRackQty($_POST['product_code_id'],$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
	$response =$select_box='';
		$r_no=array();
		$response .='<div class="form-group" >';
		$response .='<label class="col-lg-3 control-label">';
		$response .='<span class="required">*</span>Box Information</label>';
	    	$response .='<div class="col-lg-6">';
	
	        	$response .='<div>';
        			$response .='<table border="1">';
        
        					$response.='<tr>
        							    	<td colspan="2"> <center><b>Rack Information</b></center></td>
        							    </tr>';
        					$response.='<tr>
            								<td>Rack Position</td>
            								<td>Qty</td>
        								</tr>';
        					$select_box.='<div class="form-group">
        									<label class="col-lg-3 control-label"><span class="required">*</span>Box Number</label>
    										<div class="col-lg-4">
    											<select name="box_no" id="box_no" class="form-control validate[required]" onchange="getboxValue()">
    												<option value="">Select Box</option>';
                            							$box_qty_array=array();
                            							if(!empty($rack_qty))
                            							{
                            								foreach($rack_qty as $rack)
                            								{
                            									$dispatch_qty=$obj_domestic_stock->gettotaldispatchSales($rack['stock_id'],$_SESSION['LOGIN_USER_TYPE'],$_SESSION['ADMIN_LOGIN_SWISS']);
                            									$rm_qty=$rack['store_qty']-$dispatch_qty['total'];
                            									//if($rm_qty!=0)
                            									//{
                            										$response .='<tr>
                                													<td align="center">'.$rack['box_no'].'</td>
                                													<td>'.$rm_qty.'</td>
                                												</tr>';
                            									
                            												$select_box.='<option value="'.$rack['box_no'].'">'.$rack['box_no'].'</option>';
                            									//}
                            												$box_qty_array[$rack['box_no']]=$rm_qty;
                            								}						
                            							}
    						                            $array= htmlspecialchars(json_encode($box_qty_array), ENT_QUOTES, 'UTF-8');
    						    $select_box.='	</select>
    									</div>
        							</div>
        							<input type="hidden" name="box_qty_array" id="box_qty_array" value="'.$array.'">';
        		$response .='</table>';
		$response .='</div>';
		
     $response .='</div>';
    $response .='</div>';
	
	echo $response.''.$select_box;
}
if($fun == 'getdomesticStock'){

	    $product_code_id = $_POST['product_code_id'];
         $rm_qty=0;
    	$result = $obj_domestic_stock->getdomesticStock($product_code_id); 
		$dispatch_qty=$obj_domestic_stock->getdomesticStockDispatch($result['grouped_s_id']);
		$rm_qty= ($result['qty']-$dispatch_qty);
		if($rm_qty!='0')
	    	$rm_qty=$rm_qty;
		else
		    $rm_qty=0;
	
	echo $rm_qty;
}
if($fun == 'check_invoice'){
    parse_str($_POST['formData'], $post);
    $result = $obj_domestic_stock->$fun($post);
    echo $result;
}
if($fun == 'dispatchstock'){
    parse_str($_POST['formData'], $post);
    $result = $obj_domestic_stock->$fun($post); 
}
?>