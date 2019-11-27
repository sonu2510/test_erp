<?php
include("mode_setting.php");

$fun = $_GET['fun'];
//echo $fun; //website list
$json=array();

 if($_GET['fun']=='excel_data')
 { 
 	$domain_name =$_POST['post_arr'];
	$product_list = $obj_website_list->getProduct('','',$domain_name);
	//printr($product_list);die;
	$show_list =$obj_website_list->view_webList($product_list,'',$domain_name);
	
	echo $show_list;

 }
 if($_GET['fun']=='send_email_enq')
 {
 	parse_str($_POST['send_data'], $post);
	$return = $obj_website_list->send_email_enq($post['post'],$_POST['email_id']);
	echo $return;
 }

?>