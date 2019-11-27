<?php
include("mode_setting.php");

$fun = $_GET['fun'];
//echo $fun;die;
$json=array();
if($_GET['fun']=='updateProductStatus') {
	
	$product_code_id = $_POST['product_code_id'];
	$status_value = $_POST['status_value'];
	
	$obj_product_code->$fun($product_code_id,$status_value);
	
	
}
if($_GET['fun']=='ProductCodeExsist') {
    //printr($_POST);
	if(isset($_POST['code']) && $_POST['code'] != ''){
		$product_code = $_POST['code'];
		$sql = "SELECT COUNT(*) as total FROM `" . DB_PREFIX . "product_code` WHERE product_code = '".$product_code."' AND is_delete='0'";
		$data = $obj_db->query($sql);
		if($data->row['total'] > 0){
			echo 1;
		}else{
			echo 0;
		}
	}else{
		echo 0;
	}
}

/*if($_GET['fun']=='code_stock') {
	
	$product_code_id = $_POST['product_code_id'];
	$status_value = $_POST['status_value'];
	
	$obj_product_code->$fun($product_code_id,$status_value);*/
if($fun == 'code_stock') {
	parse_str($_POST['formData'], $postdata);
	$data=$obj_product_code->$fun($postdata);	
}
if($fun == 'clone_Product_code') {
	parse_str($_POST['formData'], $postdata);
	$data=$obj_product_code->cloneData($postdata);	
}
if($fun == 'csvInvoice'){
parse_str($_POST['formData'], $post);
	//printr($post['post']);die;
	$csv=$obj_product_code->productArrayForCSV($post['post']);//printr($csv);die;
	$input_array = Array(
    Array('*ProductCode',
          'ProductName',
          'Product Description',
          'Dimension',
        )
	);
	$delimiter= ',';
	$output_file_name='report.csv';
    /** open raw memory as file, no need for temp files, be careful not to run out of memory thought */
    $f = fopen('php://memory', 'w');
    /** loop through array  */
	$d='';
    foreach ($input_array as $line)
	{
       $d.= fputcsv($f, $line, $delimiter);
    }
	foreach ($csv as $line)
	{
        $d.=fputcsv($f, $line, $delimiter);
    }
   	fseek($f, 0);
    fpassthru($f);
}
?>