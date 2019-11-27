<?php

include("mode_setting.php");
$fun = $_GET['fun'];
$json = 1;
if ($_GET['fun'] == 'reset_val') {
	parse_str($_POST['formData'], $post);
	//printr($post);die;
     $obj_ink->EditCustMulValue($post);
}
?>
