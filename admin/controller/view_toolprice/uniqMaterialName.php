<?php
if(isset($_POST['name']) && $_POST['name'] != ''){
	
	include('../../model/product.php');
	$obj_product = new product();
	echo "DfsfsfsDF";die;
	$material_name = mysql_real_escape_string($_POST['name']);
	echo $material_name;die;
	
}else{
	return 0;
}

?>