<?php
include("mode_setting.php");
$fun = $_GET['fun'];
$json=1;
if($fun == 'update_price'){
//	printr($_POST);
	$qry = $obj_cylinder->update_price($_POST['international_branch_id'],$_POST['gres_cyli'],$_POST['default_cyli_base_price']);
	echo $qry;
}
?>