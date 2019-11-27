<?php
include("mode_setting.php");
//[kinjal]:
$fun = $_GET['fun'];
$json=array();

 if($_GET['fun']=='displaydata')
 {
	 	$t_id = $_POST['tnm'];
		$product_code_id = $_POST['product_code_id'];
		$user_id = $_POST['user_id'];
	    $f=1;
		$response = '';
		if($t_id==1)
		{	$table='purchase_invoice';
			$by='Purchase By';
			$date='Purchase Date';
			$no='Purchase Invoice No';
		}
		if($t_id==2)
		{	$table="sales_invoice";
			$by='Sales By';
			$date='Sales Date';
			$no='Sales Invoice No';
		}
			$response.='<td><div class="table-responsive " id="history_'.$f.','.$t_id.'" style="overflow:auto;height:150px;">';
			$response.= '<div class="panel-body">';
			$response.= '<table style="width: 1000px;" class="table table-striped  m-top-md">';
			$response.= '<thead>';
			$response.= '<tr class="bg-dark-blue">';
			$response.= '<th >Sr.No</th>';
			$response.= '<th >'.$no.'</th>';
			$response.= '<th>'.$date.'</th>';
			$response.= '<th>Quantity</th>';
			$response.= '<th>'.$by.'</th></tr></thead>'; 
			$response.= '<tbody>';
			$n = 1;
			
			$items = $obj_invoice->getStocklist($table,$product_code_id,$user_id);
			if(isset($items) && !empty($items))
			{
				foreach($items as $item)
				{	//printr($item);	
						$response.='<tr>';
						$response.='<td>'.$n.'</td>';
						$response.='<td>'.$item['invoice_no'].'</td>';
						$response.='<td>'.dateFormat(4,$item['invoice_date']).'</td>';
						$response.='<td>'.$item['qty'].'</td>';
							$addedByData = $obj_invoice->getUser($item['user_id'],$item['user_type_id']);	
						$response.='<td><span style="color:#26B756">'.$addedByData['user_name'].'</span></td>';
							$response.='</tr>';
						$n++;
				}
			}else{
				$response.='<tr><td>No records! </td></tr>';
			}
	
		 $response.='</tbody>';
	 	 $response.='</table>';
		 $response.='</div>';
		 $response.='</div></td>';  
		 $arr['response'] = $response;
		 echo $response;
}
if($_GET['fun']=='displayrackdata')
 {
	 	//$t_id = $_POST['tnm'];
		$product_code_id = $_POST['product_code_id'];
		$user_id = $_POST['user_id'];
		//echo $product_code_id.']]]'.$user_id;
		
	    $f=1;
		$response = '';
		/*if($t_id==1)
		{	$table='purchase_invoice';
			$by='Purchase By';
			$date='Purchase Date';
			$no='Purchase Invoice No';
		}
		if($t_id==2)
		{	$table="sales_invoice";
			$by='Sales By';
			$date='Sales Date';
			$no='Sales Invoice No';
		}*/
			$response.='<td><div class="table-responsive " id="history_'.$f.'" style="overflow:auto;height:150px;">';
			$response.= '<div class="panel-body">';
			$response.= '<table style="width: 1000px;" class="table table-striped  m-top-md">';
			$response.= '<thead>';
			$response.= '<tr class="bg-dark-blue">';
			$response.= '<th >Sr.No</th>';
			$response.= '<th >Product Code</th>';
			$response.= '<th>Description</th>';
			//$response.= '<th>Store Qty</th>';
			//$response.= '<th>Dispatch Qty</th>';
			//$response.= '<th>Remaining Qty</th>';
			$response.= '<th>Qty</th>';
			$response.= '<th>Row</th>';
			$response.= '<th>Column</th>';
			$response.= '<th>Rack Name</th>';
			$response.= '<th>Date</th>';
			$response.= '<th>Posted By</th></tr></thead>'; 
			$response.= '<tbody>';
			$n = 1;
			
			$items = $obj_invoice->getRackWiseQty($product_code_id,$user_id);
			//printr($items);
			if(isset($items) && !empty($items))
			{
				foreach($items as $item)
				{	
						//printr($item['stock_id']);
						$dispatch_qty=$obj_invoice->gettotaldispatchChild($item['stock_id']);
						//printr($dispatch_qty);
						if($item['description']==2){$des="Dispatched";$qty=$item['dispatch_qty'];}
									elseif($item['description']==1){$des="Store";$qty=$item['qty'];}
									else{$des="Goods Return";$qty=$item['qty'];}
						$response.='<tr>';
						$response.='<td>'.$n++.'</td>';
						$response.='<td>'.$item['product_code'].'<br>'.$item['product_name'].'</td>';
						$response.='<td>'.$des.'</td>';
						//$response.='<td>'.$item['qty'].'</td>';
						//$response.='<td>'.$item['dispatch_qty'].'</td>';
						//$response.='<td>'.($item['qty'] - $item['dispatch_qty']).'</td>';
						$response.='<td>'.$qty.'</td>';
						$response.='<td>'.$item['row'].'</td>';
						$response.='<td>'.$item['column_name'].'</td>';
						$response.='<td>'.$item['name'].'</td>';
						$response.='<td>'.dateFormat(4,$item['date_added']).'</td>';
							$addedByData = $obj_invoice->getUser($item['user_id'],$item['user_type_id']);	
						$response.='<td><span style="color:#26B756">'.$addedByData['user_name'].'</span></td>';
							$response.='</tr>';
						
						if(isset($dispatch_qty) && !empty($dispatch_qty))
						{
							  foreach($dispatch_qty as $child)
							  {
							  	//printr( $child);
								if($child['description']==2){$desc="Dispatched";$qtyc=$child['dispatch_qty'];}
									elseif($child['description']==1){$desc="Store";$qtyc=$child['qty'];}
									else{$desc="Goods Return";$qtyc=$child['qty'];}
								$response.='<tr>';
								$response.='<td></td>';
								$response.='<td></td>';
								$response.='<td>'.$desc.'</td>';
								//$response.='<td></td>';
								$response.='<td>'.$qtyc.'</td>';
								//$response.='<td>'.($item['qty'] - $child['dispatch_qty']).'</td>';
								$response.='<td></td>';
								$response.='<td></td>';
								$response.='<td></td>';
								$response.='<td>'.dateFormat(4,$child['date_added']).'</td>';
									$addedByData = $obj_invoice->getUser($child['user_id'],$child['user_type_id']);	
								$response.='<td><span style="color:#26B756">'.$addedByData['user_name'].'</span></td>';
									$response.='</tr>';
								
							  }
						}
						
						
						
						//$n++;
				}
			}else{
				$response.='<tr><td>No records! </td></tr>';
			}
	
		 $response.='</tbody>';
	 	 $response.='</table>';
		 $response.='</div>';
		 $response.='</div></td>';  
		 $arr['response'] = $response;
		 echo $response;
}

if($_GET['fun']=='openingStockQty')
 {
	$product_code_id = $_POST['product_code_id'];
	$open_qty = $_POST['open_qty'];
	$obj_invoice->$fun($product_code_id,$open_qty );
	echo $product_code_id;
	
	
 }
//sonu 8/12/2016
//if($_GET['fun']=='openingStockQtybycountry')
// {
//	$product_code_id = $_POST['product_code_id'];
//	$open_qty_by_country= $_POST['open_qty_by_country'];
//	$branch_id=$_POST['branch_id'];
//	$obj_invoice->getupdatestockqtybycontry($product_code_id,$open_qty_by_country,$branch_id );
//	echo $product_code_id;
//	
// }
if($_GET['fun']=='addInventory') {
	//echo "kkkk";die;
	parse_str($_POST['formData'], $postdata);
	//printr($postdata);
	$data = $obj_invoice->addInventory($postdata,$_POST['branch_id']);
	//printr($data);
	echo $data;
}
if($_GET['fun']=='addPhyStock') 
{
	$obj_invoice->$fun($_POST['product_code_id'],$_POST['phy_stock_qty'],$_POST['yes']);
	
}
if($_GET['fun']=='openingStockQtybyrate')
{
 {
	$product_code_id = $_POST['product_code_id'];
	$open_stock_rate= $_POST['open_stock_rate'];
	$branch_id=$_POST['branch_id'];
	$obj_invoice->getupdatestockqtybyrate($product_code_id,$open_stock_rate,$branch_id );
	echo $product_code_id;
	
 }	
	
}
if($_GET['fun']=='Report') 
{
	//printr($_POST['branch_id']);die;
	$data  = $obj_invoice->$fun($_POST['branch_id']);
	echo $data;
}

?>