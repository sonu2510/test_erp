<?php
include("mode_setting.php");

$fun = $_GET['fun'];
//echo $fun;die;
$json=array();

if($_GET['fun']=='updateStatus') {
	
	$indent_id = $_POST['indent_id'];
	$status_value = $_POST['status_value'];
	
	$obj_inventory->$fun($indent_id,$status_value);
	
	
}
 if($_GET['fun']=='deleteIndent')
 {
	
	$indent_id = $_POST['indent_id'];
	$obj_inventory->$fun($indent_id);
	
}
if($_GET['fun']=='getApprove')
 {
	$qty=$_POST['qty'];
	$table_name=$_POST['tname'];
	$id=$_POST['id'];
	$app=$obj_inventory->$fun($id,$table_name);
	$total_qty=$obj_inventory->getStockQty($id,$table_name);
	$minus= $app[0]['app_qty'] - $total_qty[0]['qty'];
	echo json_encode($minus);
}

if($_GET['fun']=='displayStk')
 {
	 $t_id = $_POST['tnm'];
	 $id = $_POST['id'];
	
$f=1;
$response = '';
		$response.='<td><div class="table-responsive " id="history_'.$f.','.$t_id.'" style="overflow:auto;height:150px;">';
		$response.= '<div class="panel-body">';
		$response.= '<table style="width: 1000px;" class="table table-striped  m-top-md">';
		$response.= '<thead>';
		$response.= '<tr class="bg-dark-blue">';
   		$response.= '<th >Sr.No</th>';
      	$response.= '<th>Order No</th>';
        $response.= '<th>Item Name</th>';
		 $response.= '<th>Order Date</th>'; 
        $response.= '<th>Order By</th>';
		 $response.= '<th>Order Quantity</th></tr></thead>'; 
		$response.= '<tbody>';
	
		$n = 1;
		$items = $obj_inventory->getPdctStock($id);
		foreach($items as $item)
		{	//printr($item);
				$response.='<tr>';
				$response.='<td>'.$n.'</td>';
				$response.='<td>'.$item['order_no'].'</td>';
				$t_name=$item['table_name'];
				$itm_id=$item['item_id'];
				$product = $obj_inventory->getItem($t_name,$itm_id);
				$sum = $obj_inventory->getSum($t_name,$itm_id,$id);
			if($t_name=='product_zipper')
			{
				$nm = 'Zipper-';
			}
			if($t_name=='product_spout')
			{
				$nm = 'Spout-';
			}
			if($t_name=='product_material')
			{
				$nm = 'Material-';
			}
			if($t_name=='product_accessorie')
			{
				$nm = 'Accessorie-';
			}
			if($t_name=='ink_master')
			{
				$nm = 'Ink-';
			}
			if($t_name=='ink_solvent')
			{
				$nm = 'Ink Solvent-';
			}
			if($t_name=='adhesive')
			{
				$nm = 'Adhesive-';
			}
			if($t_name=='adhesive_solvent')
			{
				$nm = 'Adhesive Solvent-';
			}
				$response.='<td><b>'.$nm.'</b>'.$product['itemname'].'</td>';
				$response.='<td>'.$item['added_date'].'</td>';
				
				$addedByData = $obj_inventory->getUser($item['added_by_id'],$item['added_by_type_id']);
				$user_nm = $addedByData['user_name'];
				$response.='<td><span style="color:#090">'.$user_nm.'</span></td>';
				$response.='<td>'.$sum[0]['sum'].'</td>';
				$response.='</tr>';
				$n++;
		}
		 $response.='</tbody>';
	 	 $response.='</table>';
		 $response.='</div>';
		 $response.='</div></td>';  
		 $arr['response'] = $response;
		 echo $response;
}

 if($_GET['fun']=='displaydata')
 {
    $t_id = $_POST['tnm'];
	$f=1;
	$response = '';
	if($t_id==1)
	{	$table='product_zipper';
		$op='Zipper-';	
		$name='zipper_name';
		$id_name='product_zipper_id';
		$noval='No zip';
		$unit='zipper_unit';
	}
	if($t_id==2)
	{	$table="product_spout";
		$op='Spout-';
		$name='spout_name';
		$id_name='product_spout_id';
		$noval='No Spout';
		$unit='spout_unit';
	}
	if($t_id==3)
	{	$table= "product_material";
		 $op='Material-';
		 $name='material_name';
		 $id_name='product_material_id';
		 $noval='No Material';
		 $unit='material_unit';
	}
	if($t_id==4)
	{	$table="ink_master";
		 $op='Ink-';
		 $name='make_name';
		 $id_name='ink_master_id';
		 $unit='ink_master_unit';
	}
	if($t_id==5)
	{	$table = "ink_solvent";
		 $op='Ink Solvent-';
		 $name='make_name';
		 $id_name='ink_solvent_id';
		 $unit='ink_solvent_unit';
	}
	if($t_id==6)
	{	$table = "adhesive";
		 $op='Adhesive-';
		 $name='make_name';
		 $id_name='adhesive_id';
		 $unit='adhesive_unit';
	}
	if($t_id==7)
	{	$table= "adhesive_solvent";
		 $op='Adhesive Solvent-';
	     $name='make_name';
		 $id_name='adhesive_solvent_id';
		 $unit='adhesive_solvent_unit';
	}
	if($t_id==8)
	{	$table= "product_accessorie";
		 $op='Accessorie-';
		 $name='product_accessorie_name';
		 $id_name='product_accessorie_id';
		 $noval='No Accessorie';
		 $unit='product_accessorie_unit';
	}
		$response.='<td><div class="table-responsive " id="history_'.$f.','.$t_id.'" style="overflow:auto;height:150px;">';
		$response.= '<div class="panel-body">';
		$response.= '<table style="width: 1000px;" class="table table-striped  m-top-md">';
		$response.= '<thead>';
		$response.= '<tr class="bg-dark-blue">';
   		$response.= '<th >Sr.No</th>';
      	$response.= '<th>Product Name</th>';
        $response.= '<th>Available Quantity</th></tr></thead>'; 
		$response.= '<tbody>';
	if($table=='product_zipper' || $table=='product_spout' || $table=='product_accessorie' || $table=='product_material')
	{ 	$n = 1;
		$items = $obj_inventory->getitemslist($table);
	
		foreach($items as $item)
		{
			if($item[$name]!=$noval)
			{
				$response.='<tr>';
				$response.='<td>'.$n.'</td>';
				$response.='<td><b>'.$op.'</b>&nbsp;'.$item[$name].'</td>';
				$id=$item[$id_name];
				$app_qty = $obj_inventory->getApprove($id,$table);
			 	$stock_qty = $obj_inventory->getStockQty($id,$table);
			 	$remain=$app_qty[0]['app_qty'];
				$rem=$stock_qty[0]['qty'];
				$minus=$remain - $rem;
				$response.='<td>'.$minus.'&nbsp;&nbsp;'.$item[$unit].'</td>';
				$response.='</tr>';
		
				$n++;
			}
		}
	}
	else
	{	$n = 1;$i = $obj_inventory->getitemsOfInk($table);
		foreach($i as $it)
		{	$id=$it[$id_name];
           	 $response.='<tr>';
        	 $response.='<td>'.$n.'</td>';
           	 $response.='<td><b>'.$op.'</b>&nbsp;'.$it[$name].'</td>';
          	 $app_qty = $obj_inventory->getApprove($id,$table);
		 	 $stock_qty = $obj_inventory->getStockQty($id,$table);
			 $remain=$app_qty[0]['app_qty'];
			 $rem=$stock_qty[0]['qty'];
			 $minus=$remain - $rem;
			 $response.='<td>'.$minus.'&nbsp;&nbsp;'.$it[$unit].'</td>';
			 $response.='</tr>';
                              
        	 $n++;
		}
	}
		 $response.='</tbody>';
	 	 $response.='</table>';
		 $response.='</div>';
		 $response.='</div></td>';  
		 $arr['response'] = $response;
		 echo $response;
}

if($_GET['fun']=='updateindentstatus')
{
	if(isset($_POST['postArray']['status']))
	{
		$status = $_POST['postArray']['status'];
	}
	if(isset($_POST['postArray']['indent_id']))
	{
		$indent_id = $_POST['postArray']['indent_id'];
	}
	if(isset($_POST['postArray']['receiveqty']))
	{
		$receiveqty = $_POST['postArray']['receiveqty'];
	}
	if(isset($_POST['postArray']['rec_qty']))
	{
		$rec_qty = $_POST['postArray']['rec_qty'];
	}
	if(isset($_POST['postArray']['review']))
	{
		$review = $_POST['postArray']['review'];
	}
	if(isset($_POST['postArray']['cancel']))
	{
		$cancel = $_POST['postArray']['cancel'];
	}
	if(isset($_POST['postArray']['purchase_indent_items_id']))
	{
		$purchase_indent_items_id = $_POST['postArray']['purchase_indent_items_id'];
	}
	if(isset($_POST['postArray']['appqty']))
	{
		$appqty = $_POST['postArray']['appqty'];
	}
	if(isset($_POST['postArray']['h_qty']))
	{
		$h_qty = $_POST['postArray']['h_qty'];
	}
	if(isset($_POST['postArray']['pending']))
	{
		$pending = $_POST['postArray']['pending'];
	}
	if(isset($_POST['postArray']['pen_qty']))
	{
		$pen_qty = $_POST['postArray']['pen_qty'];
	}
	if(isset($_POST['postArray']['cancelqty']))
	{
		$cancelqty = $_POST['postArray']['cancelqty'];
	}
	$result = $obj_inventory->$fun($status,$indent_id,$receiveqty,$rec_qty,$review,$purchase_indent_items_id,$appqty,$h_qty,$cancel,$pending,$pen_qty,$cancelqty);
	echo $result;
}
if($_GET['fun'] == 'removeItem'){
	//$id=$_POST['purchase_indent_items_id'];
	//echo $id;
	$obj_inventory->removeItem($_POST['purchase_indent_items_id']);
	//$obj_inventory->getId($_POST['purchase_indent_items_id']);
	
	//echo $response;
	//echo json_encode($arr);
}
if($_GET['fun'] == 'getItem'){
	//$id=$_POST['purchase_indent_items_id'];
	//echo $id;
	//$obj_inventory->getItem($_POST['purchase_indent_items_id'],$_POST['indent_id']);
	//$obj_inventory->getId($_POST['purchase_indent_items_id']);
	
	//echo $response;
	//echo json_encode($arr);
}
if($_GET['fun']=='addProduct')
{
	parse_str($_POST['str'], $searcharray);
	
	$response = '';
	$colorval='';
	$detailslist='';
	$indentid = $searcharray['indentid'];
	$vander_name = $searcharray['vander'];
	$description = $searcharray['description'];
	$delivery_date = $searcharray['due_date'];
	$reminder_date = $searcharray['reminder_date'];
	$items_id = $searcharray['items'];
	$itemsqty = $searcharray['itemsqty'];
		if($searcharray['indentid']== 0)
		{
	   		$product_description = $obj_inventory->addProduct($vander_name,$description,$delivery_date,$reminder_date);
		}
		else
		{
			$product_description=$searcharray['indentid'];	
		}
	   $multiple_items = $obj_inventory->addmultipleitems($product_description,$items_id,$itemsqty);
		
				
		$response .= '<table class="table table-bordered" style="width:800px">';
		$response .= '<thead>';
		$response .= '<tr>';
		$response .= ' <th>Name Of Item</th>';
	 	$response .= '<th>Qty</th>';
		$response .= '<th>Action</th>';
	 	$response .= '</tr>'; 
		$response .= '</thead>'; 
		$response .= '<tbody>'; 
		
		$detailslist = $obj_inventory->getaddProductDetails($product_description);
	
		//printr($detailslist);
		foreach($detailslist as $details)
		{	
			$t_name = $details['table_name'];
			$id = $details['item_id'];
			$i_id = $obj_inventory->getItem($t_name,$id);
			if($t_name=='product_zipper')
			{
				$nm = 'Zipper-';
			}
			if($t_name=='product_spout')
			{
				$nm = 'Spout-';
			}
			if($t_name=='product_material')
			{
				$nm = 'Material-';
			}
			if($t_name=='product_accessorie')
			{
				$nm = 'Accessorie-';
			}
			if($t_name=='ink_master')
			{
				$nm = 'Ink-';
			}
			if($t_name=='ink_solvent')
			{
				$nm = 'Ink Solvent-';
			}
			if($t_name=='adhesive')
			{
				$nm = 'Adhesive-';
			}
			if($t_name=='adhesive_solvent')
			{
				$nm = 'Adhesive Solvent-';
			}
			
			$response .= '<tr id='.$details['purchase_indent_items_id'].' class="id">';
			$response .= '<td id='.$t_name.'-'.$id.' class="user"><b>'.$nm.'</b>'.$i_id['itemname'].'</td>';
			$response .= '<td>'.$details['total_qty'].'&nbsp;'.$i_id['unit'].'</td>';
			$response .= '<td class="del-product"><a class="btn btn-danger btn-sm" href="javascript:void(0);" 
			onClick="removeItem('.$details['purchase_indent_items_id'].','.$details['indent_id'].','.$id.','.'\''.$t_name.'\''.')"><i class="fa fa-trash-o"></i></a></td><div class="modal fade" id="myModal'.$id.'">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title'.$id.'">Title</h4>
            </div>
            <div class="modal-body">
                <p id="setmsg'.$id.'">Message</p>
            </div>
            <div class="modal-footer">
                <button type="button" id="popbtncan'.$id.'" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" name="popbtnok" id="popbtnok'.$id.'" class="btn btn-primary">Ok</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->';
			$response .= '</tr>'; 
		}
		$response .= '</tbody>'; 
		$response .= '</table>';
		$arr['response'] = $response;
		$arr['result'] = $product_description;
		
		echo json_encode($arr);
}

?>