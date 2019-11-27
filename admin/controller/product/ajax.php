<?php
include("mode_setting.php");

$fun = $_GET['fun'];

if($fun == 'clone_Product') {
	parse_str($_POST['formData'], $postdata);
	$data=$obj_product->cloneData($postdata);
}

?>