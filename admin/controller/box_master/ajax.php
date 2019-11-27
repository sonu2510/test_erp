<?php

include("mode_setting.php");

$ajax = $_GET['ajax'];
//printr($_GET['ajax']);
if($ajax == 'addPouch')
{	
	parse_str($_POST['formData'], $post);
	//printr($post);
	$data = $obj_boxmaster->$ajax($post);
	echo $data;
}
/*if($ajax == 'updatePouchData')
{	
	parse_str($_POST['formData'], $post);
	//printr($_POST['id']);
	$data = $obj_boxmaster->$ajax($_POST['id'],$post);
	echo $data;
}*/
if($ajax == 'updateProductColorStatus')
{
	
	$product_id = $_POST['product_id'];
	$status = $_POST['status_value'];
	
	//$msg = "hi....";
	//echo $product_id."=====".$status;
	
	//$obj_product_color->$ajax($product_id,$status);
	$obj_boxmaster->$ajax($product_id,$status);
	
}
if($ajax == 'updateBoxMasterStatus')
{
	$transport_id = $_POST['transport_id'];
	$status_value = $_POST['status_value'];
	$obj_boxmaster->$ajax($transport_id,$status_value);
}
if($ajax == 'checkProductZipper')
{	
	$zipper_available = $obj_boxmaster->checkProductzipper($_POST['product_id']);
	$html ='<div class="form-group option"> <label class="col-lg-3 control-label">Zipper</label>
                        <div class="col-lg-9">';
	                        $zippers = $obj_boxmaster->getActiveProductZippers();
							$ziptxt = '';
                            foreach($zippers as $zipper){
                           
							   $ziptxt .= '<div   style="float:left;width: 200px;">';
							   		$ziptxt .= '<label  style="font-weight: normal;">';
									if($zipper_available==0 )
									{ 
										if( $zipper['product_zipper_id']==2)
										{
										$ziptxt .= '<input type="radio" name="zipper" value="'.encode($zipper['product_zipper_id']).'" checked="checked"  onclick="showSize()"  class="zipper"> '.$zipper['zipper_name'];
										}
				 						else
										{
										$ziptxt .= '<input type="radio" name="zipper" value="'.encode($zipper['product_zipper_id']).'"   disabled="disabled" class="zipper"> '.$zipper['zipper_name'];
										}
									}
									else
									{
										if( $zipper['product_zipper_id']==2)
										{
										$ziptxt .= '<input type="radio" name="zipper" value="'.encode($zipper['product_zipper_id']).'"   checked="checked" onclick="showSize()"  class="zipper"> '.$zipper['zipper_name'];
										}
										else
										{
									$ziptxt .= '<input type="radio" name="zipper" class="zipper" value="'.encode($zipper['product_zipper_id']).'" onclick="showSize()">';
									$ziptxt .= ''.$zipper['zipper_name'];
										}
									}
									$ziptxt .= '</label>';
								$ziptxt .= '</div>';
                            }
							$html.= $ziptxt;                            
                        $html.='</div></div>';
	echo json_encode($html);
}


?>