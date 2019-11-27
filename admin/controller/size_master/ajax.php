<?php 
include("mode_setting.php");

$fun = $_GET['fun'];
$json=1;
if($fun == 'remove'){
	$size_master_id= $_POST['size_master_id'];
	$obj_size->remove_size_record($size_master_id);
}
?>