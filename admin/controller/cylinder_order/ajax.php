<?php
include("mode_setting.php");

$fun = $_GET['fun'];
$json=array();


if($_GET['fun']=='updateCylinderStatus') {
	
	$order_id = $_POST['order_id'];
	$status_value = $_POST['status_value'];	
	$obj_cylinder->$fun($order_id,$status_value);
}
if($_GET['fun'] == 'checkProductGusset'){
	$product_id = $_POST['product_id'];
	$gusset_available = $obj_cylinder->checkProductGusset($_POST['product_id']);
	echo $gusset_available;
	}
if($_GET['fun'] == 'removeProduct'){
	
	$obj_cylinder->removeOrderedProduct($_POST['order_product_id']);
	//echo "hiiii";
}

if($_GET['fun'] == 'updateCyl'){
	
	$obj_cylinder->updateCyl($_POST['id'],$_POST['datetime']);
}
	
if($_GET['fun'] == 'addCylinderOrder'){
	
	parse_str($_POST['formData'], $post);
	//printr($post);
	//die;
	$post['status']=1;
	$cylinder = $obj_cylinder->addCylinder($post);
	$cond = 'AND status!=2';
	$cylinderdetails = $obj_cylinder->cylinderdetails($post['order_no'],$cond);
	////printr($cylinderdetails);
	//die;
	$result = '';
		$result.='<div class="form-group">
								<div class="col-lg-12">
									<section class="panel">
									  <div class="table-responsive">
										<table class="table table-striped b-t text-small">
										  <thead>
											  <tr>
                                                <th>Company name</th>';
                                              $result.='<th>Dimension </th><th>Description</th>';
                                               $result.='<th>Cylinder Date</th>
                                                <th>Vender Name</th>';
                                                $result.='<th>Receive Date </th>
												 <th></th></tr>
										  </thead>
                                          <tbody>';
										  foreach($cylinderdetails as $k=>$cylinder_data)
										{
                                         // printr($cylinder_data);
											//die;
                                                 $result.='<tr><td>'.$cylinder_data['company_name'].'</td>';
                                                             $result.=' 
                                                                <td>'.$cylinder_data['width'].'X'.$cylinder_data['height'].'X'.
																$cylinder_data['gusset']; 
																$result.='</td><td>'.$cylinder_data['discription'].'</td>';
																$result.='<td>'.$cylinder_data['cylinder_date'].'</td>';
																$vender_name=$obj_cylinder->getvander($cylinder_data['vander_name']);
													$result.='<td>'.$vender_name['vander_first_name'].' '.$vender_name['vander_last_name'].'</td>';
																$result.='<td>'.$cylinder_data['receive_date'].'</td>';
																//if($cylinder_data['status']==1)
																//$status='Active';
																//else
																//$status='Inactive';
																//$result.='<td>'.$status.'</td>';
																$result.= '<td class="del-product"><a class="btn btn-danger btn-sm" href="javascript:void(0);" onClick="removeProduct('.$cylinder_data['order_id'].')"><i class="fa fa-trash-o"></i></a></td>';
                                                    $result.='<div class="modal fade" id="myModal'.$cylinder_data['order_id'].'">    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title'.$cylinder_data['order_id'].'">Title</h4>
            </div>
            <div class="modal-body">
                <p id="setmsg'.$cylinder_data['order_id'].'">Message</p>
            </div>
            <div class="modal-footer">
                <button type="button" id="popbtncan'.$cylinder_data['order_id'].'" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" name="popbtnok" id="popbtnok'.$cylinder_data['order_id'].'" class="btn btn-primary">Ok</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div></tr>';
                                                }
                                            $result.='</tbody>
										</table>
									  </div>
									</section> 
								</div>
							  </div>';
	//printr($cylinder);
	//die;
	//echo "hhhhh";
	echo $result;
	
}

?>